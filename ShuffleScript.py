__version__ = '0.1.0'

import random
import os
import time
import sys
from typing import List

from helpers.MotorDriver import DispenseStep, PushStep, BinStep
from helpers.SerialDevice import SerialDevice
from helpers.DispenserDriver import Dispenser
from helpers.WebFuncs import check_for_cmd
from helpers.CameraDriver import Camera
import helpers.Config as cfg


def main():
    sd = SerialDevice()
    d_motor = DispenseStep(serial_device=sd)
    p_motor = PushStep(serial_device=sd)
    b_motor = BinStep(serial_device=sd)
    dispenser = Dispenser(serial_device=sd)
    camera = Camera()

    b_motor.zero()
    p_motor.zero()
    d_motor.zero()

    t_start = time.time()
    print('READY')
    try:
        while True:
            cmd, data = check_for_cmd()
            if cmd is not None:
                pre_shuffle(d_motor, p_motor, b_motor, dispenser)
                if 'RAND' in cmd:
                    random_shuffle(d_motor, p_motor, b_motor, dispenser, data)
                elif 'BJACK' in cmd or 'HOLDEM' in cmd:
                    planned_shuffle(d_motor, p_motor, b_motor, dispenser, camera, data)
                post_shuffle(d_motor, p_motor, b_motor, dispenser)
                print('Shuffle Completed!')

            time.sleep(0.1)
    finally:
        post_shuffle(d_motor, p_motor, b_motor, dispenser)


def pre_shuffle(d_motor:DispenseStep, p_motor:PushStep, b_motor:BinStep, dispenser:Dispenser):
    b_motor.enable()
    p_motor.disable()
    d_motor.enable()
    # d_motor.lower_stage()
    d_motor.disable()
    dispenser.disable_motor()


def random_shuffle(d_motor:DispenseStep, p_motor:PushStep, b_motor:BinStep, dispenser:Dispenser, params: List[float]):
    shuffle_loops = params[0]
    cards_per_shuffle_loop = params[1]
    for i in range(shuffle_loops):
        print("CYCLE {} / {}".format(i + 1, shuffle_loops))
        dispenser.enable_motor()
        dispenser.baseline_motor_cur()
        
        # Distribute N cards to random bins
        t_last_dispense = time.time()
        last_bin = -1
        cards_in_bin = [0] * len(cfg.bin_heights_load_mm)
        for _ in range(cards_per_shuffle_loop):
            # Select bin index, ugly brute force but it's fine shutup
            while True:
                bin_index = random.randint(0, len(cfg.bin_heights_load_mm) - 1)
                if cards_in_bin[bin_index] < cfg.max_cards_per_bin and bin_index is not last_bin:
                    last_bin = bin_index
                    cards_in_bin[bin_index] += 1
                    break

            # Move to bin location
            b_motor.load_bin_pos(bin_index)

            # Dispense card
            while time.time() < t_last_dispense + cfg.min_time_between_dispenses_s:
                pass
            if not dispenser.dispense_card():
                raise Exception("Card Jam")
            t_last_dispense = time.time()

        # Shutdown dispenser motor and servo
        dispenser.disable_motor()
        time.sleep(cfg.dc_motor_spin_down_dwell_s)

        # Return cards from bins to dispenser
        return_all_cards(p_motor, b_motor)


def planned_shuffle(d_motor:DispenseStep, p_motor:PushStep, b_motor:BinStep, dispenser:Dispenser, camera:Camera, deck):
    # Initialize vars
    dispenser.enable_motor()
    dispenser.baseline_motor_cur()
    t_last_dispense = time.time()
    n_bins = len(cfg.bin_heights_load_mm)
    cards_in_trash = 0

    camera.start_camera()

    # Generate bin reqs for deck
    deck.break_into_bins(n_bins=n_bins)

    def empty_trash():
        p_motor.enable()
        b_motor.unload_bin_pos(n_bins-1)
        time.sleep(0.1)
        p_motor.run()
        p_motor.disable()

    junk_cards_dispensed = 0
    while junk_cards_dispensed < cfg.planned_shuffle_timeout:
        # See which card is next, try multiple times if failing. Will trash if unable to read
        for i in range(3):
            card = camera.read_card()
            if card.rank is not None and card.suit is not None:
                break
            time.sleep(0.1)

        if cfg.DEBUG_MODE:
            print(card.rank, card.suit)

        # Determine where to put card
        if card.rank is None and card.suit is None:
            bin_index = None
        else:
            bin_index = deck.get_bin(card)

        print(card.rank, card.suit, ":", bin_index)

        # Handle vars if trash card
        if bin_index is None:
            bin_index = n_bins - 1
            cards_in_trash += 1
            junk_cards_dispensed += 1

        # Move to bin location
        b_motor.load_bin_pos(bin_index)

        # Dispense card
        while time.time() < t_last_dispense + cfg.min_time_between_dispenses_s:
            pass
        if not dispenser.dispense_card():
            raise Exception("Card Jam")
        t_last_dispense = time.time()

        # Check for full trash
        if cards_in_trash >= cfg.max_cards_per_bin:
            empty_trash()
            cards_in_trash = 0

        # Check for shuffle completion
        if deck.is_shuffle_complete:
            return_all_cards(p_motor, b_motor)
            break

    else:
        print("Planned Shuffle Timeout")

    camera.stop_camera()


def post_shuffle(d_motor:DispenseStep, p_motor:PushStep, b_motor:BinStep, dispenser:Dispenser):
    dispenser.disable_motor()
    p_motor.disable()
    b_motor.disable()
    d_motor.enable()
    # d_motor.raise_stage()
    d_motor.disable()


def return_all_cards(p_motor, b_motor):
    nBins = len(cfg.bin_heights_load_mm)
    p_motor.enable()
    for bin_index in reversed(range(nBins)):
        b_motor.unload_bin_pos(bin_index)
        time.sleep(0.1)
        p_motor.run()

    p_motor.disable()


""" TEMPORARY DEBUGGING FUNCTIONS """

def motor_test():
    sd = SerialDevice()
    # motor = DispenseStep(serial_device=sd)
    # motor = PushStep(serial_device=sd)
    motor = BinStep(serial_device=sd)
    # dispenser = Dispenser(serial_device=sd)

    motor.enable()
    motor.zero()
    v = 10
    a = 50
    d = 5
    for _ in range(2):
        motor.relative_move(d, v, a)
        motor.relative_move(-d, v, a)
    motor.disable()

    return


def cam_test():
    c = Camera()
    while True:
        _ = input('Press enter to read card')
        card = c.read_card(enable_and_disable=True)
        print(card.rank, card.suit)


def gen_random_card():
    import helpers.Gameplay as g
    rank_i = random.randint(0, 12)
    suit_i = random.randint(0, 3)
    return g.Card(rank = g.ALLRANKS[rank_i], suit = g.ALLSUITS[suit_i])

if __name__ == "__main__":
    main()
    # motor_test()
    # cam_test()

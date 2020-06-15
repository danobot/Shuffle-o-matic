<html>
	<head>
	<title>Shuffl-o-Matic</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
	* {
	box-sizing: border-box;
	}

	body {
	font-family: Arial, Helvetica, sans-serif;
	}

	/* Style the header */
	header {
	background-color: #666;
	padding: 30px;
	text-align: center;
	font-size: 35px;
	color: white;
	}

	.column {
	float: left;
	padding: 10px;
	height: 1400px;
	}

	.left {
	background-color: #aaa;
	width: 33%;
	}

	.mid {
	background-color: #bbb;
	width: 34%;
	}

	.right {
	background-color: #aaa;
	width: 33%;
	}

	/* Clear floats after the columns */
	.row:after {
	content: "";
	display: table;
	clear: both;
	}

	/* Style the footer */
	footer {
	background-color: #777;
	padding: 10px;
	text-align: center;
	color: white;
	}

	}
	</style>
	</head>

	<body>
		<header>
		<h2>Shuffl-o-matic</h2>
		</header>

		<iframe name="hidden_iframe" width="0" height="0" style="border:none"></iframe>

		<div class="row">
			<div class="column left">
				<h1>Random Shuffle</h1>
				<p>Enter shuffle parameters.</p>
				<form method="post">
					<label for="r_cycles">Number of Shuffle Cycles:</label>
					<input type="text" id="r_cycles" name="r_cycles" value="4"><br>
					<label for="r_cards">Number of Cards per Cycles:</label>
					<input type="text" id="r_cards" name="r_cards" value="20"><br><br>
					<input type="submit" name="random_shuffle" id="random_shuffle" value="Shuffle">
				</form>
			</div>

			<div class="column mid">
				<h1>Blackjack Shuffle</h1>
				<p>Enter number of players and who will win.</p>
				<form method="post">
					<label for="nplayers">Number of Players (Include Dealer):</label>
					<input type="text" id="nplayers" name="nplayers"><br><br>
					<input type="checkbox" id="d_win" name="d_win" value="true">
					<label for="d_win"> Dealer Blackjack</label><br>
					<input type="checkbox" id="p1_win" name="p1_win" value="true">
					<label for="p1_win"> Player 1 Blackjack</label><br>
					<input type="checkbox" id="p2_win" name="p2_win" value="true">
					<label for="p2_win"> Player 2 Blackjack</label><br>
					<input type="checkbox" id="p3_win" name="p3_win" value="true">
					<label for="p3_win"> Player 3 Blackjack</label><br>
					<input type="checkbox" id="p4_win" name="p4_win" value="true">
					<label for="p4_win"> Player 4 Blackjack</label><br>
					<input type="checkbox" id="p5_win" name="p5_win" value="true">
					<label for="p5_win"> Player 5 Blackjack</label><br><br>
					<input type="submit" name="blackjack_shuffle" id="blackjack_shuffle" value="Shuffle">
				</form>
			</div>

			<div class="column right">
				<datalist id="suits">
					<option value="Heart">
					<option value="Diamond">
					<option value="Spade">
					<option value="Club">
				</datalist>

				<datalist id="cards">
					<option value="A">
					<option value="2">
					<option value="3">
					<option value="4">
					<option value="5">
					<option value="6">
					<option value="7">
					<option value="8">
					<option value="9">
					<option value="10">
					<option value="J">
					<option value="Q">
					<option value="K">
				</datalist>

				<h1>Hold-em Shuffle</h1>
				<p>Designate cards as desired. Any unfilled fields will use a random card.</p>
				<form method="post">
					<label for="nplayers">Number of Players (Include Dealer):</label>
					<input type="text" id="nplayers" name="nplayers"><br><br>

					<pre>         Flop                  Turn                 River</pre>
					<input list="cards" name="flop_card0" size="1">
					<input list="suits" name="flop_suit0" size="10">
					<input list="cards" name="turn_card0" size="1">
					<input list="suits" name="turn_suit0" size="10">
					<input list="cards" name="river_card0" size="1">
					<input list="suits" name="river_suit0" size="10"><br>
					<input list="cards" name="flop_card1" size="1">
					<input list="suits" name="flop_suit1" size="10"><br>
					<input list="cards" name="flop_card2" size="1">
					<input list="suits" name="flop_suit2" size="10"><br><br>

					<p> Dealer Cards</p>
					<input list="cards" name="dealer_card0" size="1">
					<input list="suits" name="dealer_suit0" size="10"><br>
					<input list="cards" name="dealer_card1" size="1">
					<input list="suits" name="dealer_suit1" size="10"><br><br>
					
					<p> Player 1 Cards</p>
					<input list="cards" name="p1_card0" size="1">
					<input list="suits" name="p1_suit0" size="10"><br>
					<input list="cards" name="p1_card1" size="1">
					<input list="suits" name="p1_suit1" size="10"><br><br>

					<p> Player 2 Cards</p>
					<input list="cards" name="p2_card0" size="1">
					<input list="suits" name="p2_suit0" size="10"><br>
					<input list="cards" name="p2_card1" size="1">
					<input list="suits" name="p2_suit1" size="10"><br><br>

					<p> Player 3 Cards</p>
					<input list="cards" name="p3_card0" size="1">
					<input list="suits" name="p3_suit0" size="10"><br>
					<input list="cards" name="p3_card1" size="1">
					<input list="suits" name="p3_suit1" size="10"><br><br>

					<p> Player 4 Cards</p>
					<input list="cards" name="p4_card0" size="1">
					<input list="suits" name="p4_suit0" size="10"><br>
					<input list="cards" name="p4_card1" size="1">
					<input list="suits" name="p4_suit1" size="10"><br><br>

					<p> Player 5 Cards</p>
					<input list="cards" name="p5_card0" size="1">
					<input list="suits" name="p5_suit0" size="10"><br>
					<input list="cards" name="p5_card1" size="1">
					<input list="suits" name="p5_suit1" size="10"><br><br>

					<p> Player 6 Cards</p>
					<input list="cards" name="p6_card0" size="1">
					<input list="suits" name="p6_suit0" size="10"><br>
					<input list="cards" name="p6_card1" size="1">
					<input list="suits" name="p6_suit1" size="10"><br><br>

					<p> Player 7 Cards</p>
					<input list="cards" name="p7_card0" size="1">
					<input list="suits" name="p7_suit0" size="10"><br>
					<input list="cards" name="p7_card1" size="1">
					<input list="suits" name="p7_suit1" size="10"><br><br>

					<input type="checkbox" id="discard_bool" name="discard_bool" value="true" checked>
					<label for="discard_bool"> One card discarded before flop, turn, and river</label><br><br>
					<input type="submit" name="holdem_shuffle" id="holdem_shuffle" value="Shuffle">
				</form>
			</div>
		</div>	
	</body>

	<footer>
	<p>www.youtube.com/3DprintedLife</p>
	</footer>
</html>

<?php

if(isset($_POST['r_cycles']))
{
$data=$_POST['r_cycles'];
$fp = fopen('/tmp/data.txt', 'a');
fwrite($fp, $data);
fclose($fp);
}

?>
<html>
<head>
	<style>
	
	.form {
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size: 1.2em;
	width: 30em;
	padding: 3em;
	border: 2px solid #ccc;
	}

	.extra {
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size: 0.8em;
	}

	.tab { margin-left: 30px; }

	</style>
</head>
<body>
<center>
<div class="form">
<h1>Quiz Match Results</h1>

<?php 
$num_traits = 4;
for ($i = 0; $i < $num_traits; $i++)
		$values[$i] = 0;

$answer1 = $_POST['r1']; $answer6 = $_POST['r6'];
$answer2 = $_POST['r2']; $answer7 = $_POST['r7'];
$answer3 = $_POST['r3']; $answer8 = $_POST['r8'];
$answer4 = $_POST['r4']; $answer9 = $_POST['r9'];
$answer5 = $_POST['r5']; $answer10 = $_POST['r10'];
$answer11 = $_POST['r11']; $answer16 = $_POST['r16'];
$answer12 = $_POST['r12']; $answer17 = $_POST['r17'];
$answer13 = $_POST['r13']; $answer18 = $_POST['r18'];
$answer14 = $_POST['r14']; $answer19 = $_POST['r19'];
$answer15 = $_POST['r15']; $answer20 = $_POST['r20'];

// 0 - SANGUINE, 1 - PHLEGMATIC, 2 - CHOLERIC, 3 - MELANCHOLIC

if ($answer1 == "A") { $values[3] += 2; }
else if ($answer1 == "B") { $values[1] += 2; }
else if ($answer1 == "C") { $values[2] += 2; }
else if ($answer1 == "D") { $values[0] += 2; }

if ($answer2 == "A") { $values[2] += 2; }
else if ($answer2 == "B") { $values[0] += 2; }
else if ($answer2 == "C") { $values[1] += 2; }
else if ($answer2 == "D") { $values[3] += 2; }

if ($answer3 == "A") { $values[3] += 2; }
else if ($answer3 == "B") { $values[0] += 2; }
else if ($answer3 == "C") { $values[1] += 2; }
else if ($answer3 == "D") { $values[2] += 2; }

if ($answer4 == "A") { $values[2] += 2; }
else if ($answer4 == "B") { $values[3] += 2; }
else if ($answer4 == "C") { $values[1] += 2; }
else if ($answer4 == "D") { $values[0] += 2; }

if ($answer5 == "A") { $values[3] += 2; }
else if ($answer5 == "B") { $values[1] += 2; }
else if ($answer5 == "C") { $values[2] += 2; }
else if ($answer5 == "D") { $values[0] += 2; }

if ($answer6 == "A") { $values[2] += 2; }
else if ($answer6 == "B") { $values[3] += 2; }
else if ($answer6 == "C") { $values[1] += 2; }
else if ($answer6 == "D") { $values[0] += 2; }

if ($answer7 == "A") { $values[0] += 2; }
else if ($answer7 == "B") { $values[2] += 2; }
else if ($answer7 == "C") { $values[1] += 2; }
else if ($answer7 == "D") { $values[3] += 2; }

if ($answer8 == "A") { $values[2] += 2; }
else if ($answer8 == "B") { $values[1] += 2; }
else if ($answer8 == "C") { $values[3] += 2; }
else if ($answer8 == "D") { $values[0] += 2; }

if ($answer9 == "A") { $values[1] += 2; }
else if ($answer9 == "B") { $values[2] += 2; }
else if ($answer9 == "C") { $values[3] += 2; }
else if ($answer9 == "D") { $values[0] += 2; }

if ($answer10 == "A") { $values[2] += 2; }
else if ($answer10 == "B") { $values[0] += 2; }
else if ($answer10 == "C") { $values[1] += 2; }
else if ($answer10 == "D") { $values[3] += 2; }

if ($answer11 == "A") { $values[3] += 2; }
else if ($answer11 == "B") { $values[0] += 2; }
else if ($answer11 == "C") { $values[1] += 2; }
else if ($answer11 == "D") { $values[2] += 2; }

if ($answer12 == "A") { $values[3] += 2; }
else if ($answer12 == "B") { $values[0] += 2; }
else if ($answer12 == "C") { $values[1] += 2; }
else if ($answer12 == "D") { $values[2] += 2; }

if ($answer13 == "A") { $values[0] += 2; }

if ($answer14 == "D") { $values[1] += 2; }

if ($answer15 == "B") { $values[3] += 2; }

if ($answer16 == "A") { $values[2] += 2; }

if ($answer17 == "B") { $values[2] += 2; }

if ($answer18 == "A") { $values[0] += 2; }

if ($answer19 == "C") { $values[1] += 2; }

if ($answer20 == "B") { $values[3] += 2; }




$val = 0;
$index = 0;
$index2 = 0;
for ($i = 0; $i < $num_traits; $i++) {
	if ($values[$i] > $val) {
		$val = $values[$i];
		$index = $i;
	}
}
$val = 0;
for ($i = 0; $i < $num_traits; $i++) {
	if ($values[$i] > $val && $i != $index) {
		$val = $values[$i];
		$index2 = $i;
	}
}

echo "Your personality values are:  <br><br>";
echo "SANGUINE +" . $values[0] . "<br>PHLEGMATIC + " . $values[1] . "<br>CHOLERIC +" . $values[2] . "<br>MELANCHOLIC +" . $values[3];

if ($index == 0) 
	echo "<br><br>Your highest value is: SANGUINE";
else if ($index == 1) 
	echo "<br><br>Your highest value is: PHLEGMATIC";
else if ($index == 2) 
	echo "<br><br>Your highest value is: CHOLERIC";
else if ($index == 3) 
	echo "<br><br>Your highest value is: MELANCHOLIC";
if ($index2 == 0) 
	echo "<br><br>Your 2nd highest value is: SANGUINE";
else if ($index2 == 1) 
	echo "<br><br>Your 2nd highest value is: PHLEGMATIC";
else if ($index2 == 2) 
	echo "<br><br>Your 2nd highest value is: CHOLERIC";
else if ($index2 == 3) 
	echo "<br><br>Your 2nd highest value is: MELANCHOLIC";
?> 
</div>

<div class="extra">
<br>Find out more about each: <br><br>
<a href="https://psychologia.co/sanguine-personality/">SANGUINE </a>
<a class="tab" href="https://psychologia.co/phlegmatic-personality/">PHLEGMATIC </a>
<a class="tab" href="https://psychologia.co/choleric-personality/">CHOLERIC </a>
<a class="tab" href="https://psychologia.co/melancholic-personality/">MELANCHOLIC </a>
</div>

</center>
</body>
</html>

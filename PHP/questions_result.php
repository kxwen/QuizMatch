<?php 
  
for ($i = 0; $i < 13; $i++)
		$values[$i] = 0;
// 
// 0 - Hardy, 1 - Docile, 2 - Brave, 3 - Jolly, 
// 4 - Impish, 5 - Naive, 6 - Timid, 7 - Hasty, 
// 8 - Sassy, 9 - Calm, 10 - Relaxed, 11 - Lonely, 
// 12 - Quirky

$answer1 = $_POST['r1']; $answer6 = $_POST['r6'];
$answer2 = $_POST['r2']; $answer7 = $_POST['r7'];
$answer3 = $_POST['r3']; $answer8 = $_POST['r8'];
$answer4 = $_POST['r4']; $answer9 = $_POST['r9'];
$answer5 = $_POST['r5']; $answer10 = $_POST['r10'];

$answer11 = $_POST['r11']; $answer16 = $_POST['r16'];
$answer12 = $_POST['r12'];
$answer13 = $_POST['r13'];
$answer14 = $_POST['r14'];
$answer15 = $_POST['r15'];

if ($answer1 == "A") { $values[2] += 3; $values[4] += 1;}
else if ($answer1 == "B") { $values[1] += 2; $values[6] += 1; }

if ($answer2 == "A") { $values[4] += 2; }
else if ($answer2 == "B") { $values[10] += 2; }

if ($answer3 == "A") { $values[2] += 2;}
else if ($answer3 == "B") { $values[0] += 2; $values[7] += 1; }

if ($answer4 == "A") { $values[3] += 2; }
else if ($answer4 == "B") { $values[10] += 2; }
else if ($answer4 == "C") { $values[8] += 2; }

if ($answer5 == "A") { $values[1] += 2; }
else if ($answer5 == "B") { $values[0] += 2; $values[8] += 2; }

if ($answer6 == "A") { $values[5] += 3; }
else if ($answer6 == "B") { $values[12] += 2; }
else if ($answer6 == "C") { $values[8] += 2; }

if ($answer7 == "A") { $values[1] += 2; }
else if ($answer7 == "B") { $values[5] += 2; $values[10] += 1; }

if ($answer8 == "A") { $values[4] += 2; $values[12] += 2;}
else if ($answer8 == "B") { $values[6] += 2; $values[11] += 2; }

if ($answer9 == "A") { $values[2] += 2; $values[0] += 1;}
else if ($answer9 == "B") { $values[6] += 2; }

if ($answer10 == "A") { $values[9] += 2; }
else if ($answer10 == "B") { $values[5] += 2; }

if ($answer11 == "A") { $values[11] += 2; $values[6] += 1;}
else if ($answer11 == "B") { $values[3] += 2; $values[9] += 2; }

if ($answer12 == "A") { $values[7] += 2; $values[5] += 1;}
else if ($answer12 == "B") { $values[9] += 2; }

if ($answer13 == "A") { $values[3] += 2; $values[9] += 2; $values[10] += 2;}
else if ($answer13 == "B") { $values[6] += 2; $values[11] += 2; }

if ($answer14 == "A") { $values[2] += 2; }
else if ($answer14 == "B") { $values[7] += 2; }

if ($answer15 == "A") { $values[3] += 2; $values[7] += 2; $values[12] += 2;}
else if ($answer15 == "B") { $values[9] += 2; }
else if ($answer15 == "C") { $values[2] += 2; $values[7] += 2; }
else if ($answer15 == "D") { $values[11] += 2; }

if ($answer16 == "A") { $values[0] += 2; }
else if ($answer16 == "B") { $values[1] += 2; }

$val = 0;
$index = 0;
for ($i = 0; $i < 13; $i++) {
	if ($values[$i] > $val) {
		$val = $values[$i];
		$index = $i;
}
		


?> 
<?php

DEFINE ('DB_USER', 'quizuser');
DEFINE ('DB_PASSWORD', 'match');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'QuizMatch');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
OR die('Could not connect to MySQL: ' .
mysqli_connect_error());

?>

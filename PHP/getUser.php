<?php

$q= $_GET['q'];

$link = mysqli_connect("localhost", "root", "", "QuizMatch"); 
  
if($link === false){ 
    die("ERROR: Could not connect. " 
            . mysqli_connect_error()); 
} 

$sql = "SELECT * FROM users Where id = ".$q;
$res = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($res);

echo json_encode($row);            
   
?>

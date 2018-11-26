<?php
$link = mysqli_connect("localhost", "root", "", "QuizMatch"); 
  
if($link === false){ 
    die("ERROR: Could not connect. " 
                . mysqli_connect_error()); 
} 

  
$sql = "SELECT username, age, gender, defaultCategory FROM QuizMatchUsers Where defaultCategory = 'Charmander' AND gender = 'Female';"; 
if($res = mysqli_query($link, $sql)){ 
    if(mysqli_num_rows($res) > 0){ 
        echo "<table>"; 
            echo "<tr>"; 
                echo "<th>Username</th>"; 
                echo "<th>Age</th>"; 
                echo "<th>Gender</th>"; 
                echo "<th>defaultCategory</th>"; 
            echo "</tr>"; 
        while($row = mysqli_fetch_array($res)){ 
            echo "<tr>"; 
                echo "<td>" . $row['username'] . "</td>"; 
                echo "<td>" . $row['age'] . "</td>"; 
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . $row['defaultCategory'] . "</td>"; 
            echo "</tr>"; 
        } 
        echo "</table>"; 
        mysqli_free_result($res); 
    } else{ 
        echo "No Matches Found"; 
    } 
} else{ 
    echo "ERROR: Could not able to execute $sql. "  
                                . mysqli_error($link); 
} 
  
mysqli_close($link);
?>

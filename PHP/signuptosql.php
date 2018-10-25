<html>
<head>
<title>Add Student</title>
</head>
<body>

<?php


$usernameERR = $passwordERR = $CpasswordERR = $emailERR =  $genderERR = "";


$username = $password = $Cpassword = $email = $gender = "";


if(isset($_POST['submit'])){


	// Email Verification
    if(empty($_POST["email"])){

        $emailERR = "Please enter a valid email";

    }else{

        $email = input($_POST["email"]);

    }


	// Username Verification
    if(empty($_POST["username"])){
	    
	    $usernameERR = "Please enter a username";

    }else{

        $username = input($_POST["username"]);

    }


	// Password Verification
    if(empty($_POST["password"]) or empty($_POST["Cpassword"])){

        if(empty($_POST["password"])){


			$passwordERR = "Please enter a password";


		}


		if(empty($_POST["Cpassword"]))


		{


			$CpasswordERR = "Please re-enter your password";


		}


	}else{


		if(strcmp($_POST["password"],$_POST["Cpassword"]) == 0)


		{


			$password = input($_POST["password"]);

	    }else{


			$CpasswordERR = "Password does not match";

        }

    }
    
    // Gender
    if(empty($_POST["gender"])){

        $genderERR = "Please enter 'M' or 'F'";

    }else{

        $gender = input($_POST["gender"]);

    }


	require_once('../mysqli_connect.php');
    $query = "INSERT INTO users(email, username, password, gender, user_id) VALUES ($email, $username, $password, $gender, NULL)";

    $stmt = mysqli_prepare($dbc, $query);
    
    //mysqli_stmt_bind_param($stmt, "ssssd", $email, $username, $password, $gender);

    mysqli_stmt_execute($stmt);
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    
    if($affected_rows == 1){

            echo 'User Entered';
			mysqli_stmt_close($stmt);
			mysqli_close($dbc);

    } else {

            echo 'Error Occurred<br />';
            echo mysqli_error();
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);

    }

}

}


?>
</body>
</html>

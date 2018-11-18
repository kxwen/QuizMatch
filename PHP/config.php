<?php
/* config.php
 * Configuration file in which can be used across
 * various PHP files to connect to Database.
 * Allows for simpler edits if Database details change.
 * Also includes a variable for Password Length for
 * any files that require a new password to be entered.
 */
 
/* ---------------------------------------------------------
 * NOTE:
 * Login and Signup work under the assumption that there
 * exists a table as follows:
 * CREATE TABLE users (
     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     username VARCHAR(x) NOT NULL UNIQUE,
     email VARCHAR(y) NOT NULL UNIQUE,
     password VARCHAR(z) NOT NULL,
     created_at DATETIME DEFAULT CURRENT_TIMESTAMP
 *);
 * where x, y, and z are max lengths of strings(?)
 * ---------------------------------------------------------
 */ 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'QuizMatch');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false)die("ERROR: Unable to connect. " . mysqli_connect_error());

$min_username_len = 6;

$min_pw_len = 5;

$traits[0] = "SANGUINE";
$traits[1] = "PHLEGMATIC";
$traits[2] = "CHOLERIC";
$traits[3] = "MELANCHOLIC";
$catagories = array(array("",""),array($traits[0], $traits[1]), array($traits[0], $traits[2]), array($traits[0], $traits[3]),
								 array($traits[1], $traits[0]), array($traits[1], $traits[2]), array($traits[1], $traits[3]),
								 array($traits[2], $traits[0]), array($traits[2], $traits[1]), array($traits[2], $traits[3]),
								 array($traits[3], $traits[0]), array($traits[3], $traits[1]), array($traits[3], $traits[2]));

?>
<?php
/* logout.php
 * Simple script to destroy current session and returns user to
 * Non-user Homepage.
 */
 
session_start();
$_SESSION = array();
session_destroy();
header("location: welcome.html");
exit;
?>
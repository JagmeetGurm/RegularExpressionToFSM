<?php
/*
This registration process, login and making connection to DB system is derived from Javed Ur Rehman's
Simple User Registration & Login Script in PHP and MySQLi, "Website: http://www.allphptricks.com/ "
and adapted to meet the needs of this project.
*/
?>

<?php
session_start(); //the session starts here
if(!isset($_SESSION["username"])){
header("Location: login.php"); //if username not set, go to login page
exit(); }
?>

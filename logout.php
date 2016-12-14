<?php
/*
This registration process, login and making connection to DB system is derived from Javed Ur Rehman's
Simple User Registration & Login Script in PHP and MySQLi, "Website: http://www.allphptricks.com/ "
and adapted to meet the needs of this project.
*/

session_start();
if(session_destroy()) 
{
header("Location: login.php"); 
}
?>
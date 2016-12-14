<?php
/*
This registration process, login and making connection to DB system is derived from Javed Ur Rehman's
Simple User Registration & Login Script in PHP and MySQLi, "Website: http://www.allphptricks.com/ "
and adapted to meet the needs of this project.
*/


$con = mysqli_connect("localhost","root","","register");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to make connection to the MySQL database, please try again: " . mysqli_connect_error();
  }
?>
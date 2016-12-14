<?php
require_once 'db.php';
include("auth.php");
include("header.php"); //include auth.php file on all secure pages
?>
<html>
<head>
<link rel="stylesheet" href="css/style.css" media="screen"/>     
	 <style>
			.container {
				margin-top: 110px;
			}
			.error {
				color: #B94A48;
			}
			.form-horizontal {
				margin-bottom: 0px;
			}
			.hide{display: none;}
			#ques_form{
				margin:20px;
			}
		</style>
</head>
<body>
<div class="form">

<p style="float:left; display:block; margin:10px;"><b>Welcome <?php echo $_SESSION['username']; ?>!</b></p>
<a href="index.php" style="float:left; display:block; margin:10px;">Home</a>
<a href="logout.php" style="float:left; display:block; margin:10px;">Logout</a>


<br /><br /><br /><br />
<p>Thank you for taking the quiz! Please visit home page and select the taken quiz to view detailed result.</p>
</div>
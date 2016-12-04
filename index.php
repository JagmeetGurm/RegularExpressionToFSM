<?php
include("auth.php");
include("header.php"); //include auth.php file on all secure pages ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Home</title>
<link rel="stylesheet" href="css/style.css" />
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
<a href="dashboard.php" style="float:left; display:block; margin:10px;">Quiz 1</a>
<a href="quiz2.php" style="float:left; display:block; margin:10px;">Quiz 2</a>
<a href="logout.php" style="float:left; display:block; margin:10px;">Logout</a>

<form id="myForm" action="test.php" method="post">
  <select name="quiz">
    <option value="1">Quiz 1- Easy </option>
    <option value="2">Quiz 2- Medium- 1</option>
    <option value="3">Quiz 3- Medium- 2</option>
    <option value="4">Quiz 4- Difficult- 1</option>
	<option value="5">Quiz 5- Difficult- 2</option>
  </select>
  <br><br>
  <input type="Submit" value="Submit" name ="Submit">
</form>



<br /><br /><br /><br />
</div>
</body>
</html>
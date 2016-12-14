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
			#quizAdvice{
				float: none;
			}
		</style>
</head>
<body>
<div class="form">

<p style="float:left; display:block; margin:10px;"><b>Welcome <?php echo $_SESSION['username']; ?>!</b></p>

<a href="logout.php" style="float:left; display:block; margin:10px;">Logout</a>
<br>
<br>
<h4> Select a quiz to continue.</h4>
<p id="quizAdvice"> Can take random quiz as many times but other quizes only once.</p>

<form id="myForm" action="test.php" method="post">
  <select name="quiz">
    <option value="1">Quiz 1- Easy </option>
    <option value="2">Quiz 2- Medium</option>
    <option value="3">Quiz 3- Difficult</option>
   	<option value="6">Random Quiz</option>
  </select>
  <br><br>
  <input type="Submit" value="Submit" name ="Submit">
</form>
</div>


<br /><br /><br /><br />

</body>
</html>
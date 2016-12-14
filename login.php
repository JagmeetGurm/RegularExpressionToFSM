<?php
/*
This registration process, login and making connection to DB system is derived from Javed Ur Rehman's
Simple User Registration & Login Script in PHP and MySQLi, "Website: http://www.allphptricks.com/ "
and adapted to meet the needs of this project.
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
include("header.php");
	require('db.php');
	session_start();
    //the user session starts from here
    if (isset($_POST['username'])){
		
		$u_name = stripslashes($_REQUEST['username']); 
		$u_name = mysqli_real_escape_string($con,$u_name); 
		$userPassword = stripslashes($_REQUEST['password']);
		$userPassword = mysqli_real_escape_string($con,$userPassword);
		
	
        $userQuery = "SELECT * FROM `users` WHERE username='$u_name' and password='".md5($userPassword)."'";
		$userResult = mysqli_query($con,$userQuery) or die(mysql_error());
		$returnedRows = mysqli_num_rows($userResult);
        if($returnedRows==1)
		{
			$_SESSION['username'] = $u_name; //assign the username to global session variable 
			header("Location: index.php"); // after login send the user to his homepage
        }
			else
			{
				echo "<div class='form'><h3>Username/password is incorrect.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
		    }
    }else{
?>
<div class="form">
<h1>Log In</h1>
<form action="" method="post" name="login">
<input type="text" name="username" placeholder="enter username" required />
<input type="password" name="password" placeholder="enter password" required />
<input name="submit" type="submit" value="Login" />
</form>
<p>Not registered yet? <a href='registration.php'>Register Here</a></p>

<br /><br />
</div>
<?php } ?>


</body>
</html>

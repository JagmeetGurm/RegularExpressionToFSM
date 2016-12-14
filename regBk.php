<?php
/*
This registration process is derived from:
Website: http://www.allphptricks.com/ tutorial
and adapted to meet the needs of this project.
*/
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
include("header.php");
	require('db.php');
    
    if (isset($_REQUEST['username'])){
		$u_name = stripslashes($_REQUEST['username']);  //get user name
		$u_name = mysqli_real_escape_string($con,$u_name); //remove unwanted chars
		$userEmail = stripslashes($_REQUEST['email']); //get user email
		$userEmail = mysqli_real_escape_string($con,$userEmail); //remove unwanted chars
		$userPassword = stripslashes($_REQUEST['password']); //get password
		$userPassword = mysqli_real_escape_string($con,$userPassword); //remove unwanted chars

		$user_date = date("Y-m-d H:i:s");
		
		//check if the user already exists
		//$checkUser = "SELECT * FROM users WHERE username= '$u_name' and email = '$userEmail'";
		
		//taking care of sql injections
		$stmt=$con->prepare("SELECT * FROM users WHERE username= ? and email = ?");
		$stmt->bind_param("ss", $u_name, $userEmail);
		$stmt->execute();
		$userResult=$stmt->get_result();
		//$userResult = mysqli_query($con,$checkUser) or die(mysql_error());
		$returnedRows = mysqli_num_rows($userResult);
        if($returnedRows==1)
		{ //if user does exists, then redirect him to registraction process again.
			echo "<p> Sorry! the name and useremail already exists. Please choose another values. <a href= 'registration.php'>Registration</a></p>";
		}
		else 
		{
			//taking care of sql injections
		$stmt=$con->prepare("INSERT into `users` (username, password, email, trn_date) VALUES (?,?,?,?)");	
		$userPass=md5($userPassword);
		$stmt->bind_param("ssss", $u_name, $userPass, $userEmail, $user_date);
		$userResult=$stmt->execute();
		
     //   $userQuery = "INSERT into `users` (username, password, email, trn_date) VALUES ('$u_name', '".md5($userPassword)."', '$userEmail', '$user_date')";
      //  $userResult = mysqli_query($con,$userQuery);
        if($userResult){
            echo "<div class='form'><h3>You are registered successfully.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
        }
		}
    }else{
?>
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<input type="text" name="username" placeholder="enter username" required />
<input type="email" name="email" placeholder="enter email" required />
<input type="password" name="password" placeholder="enter password" required />
<input type="submit" name="submit" value="Register" />
</form>
<br /><br />

</div>
<?php } 
	?>
</body>
</html>

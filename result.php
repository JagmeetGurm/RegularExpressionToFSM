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
</div>
<?php


$response2=mysqli_query($con, "select ques_id,ques_name,correct_ans from questions");
	 $i=1;
	 $right_answer=0;
	 $wrong_answer=0;
	 $unanswered=0;
	/* while($result=mysqli_fetch_array($response2, MYSQLI_ASSOC)){ 
	       if($result['correct_ans']==$_POST["$i"]){
		       $right_answer++;
		   }else if($_POST["$i"]==5){
		       $unanswered++;
		   }
		   else{
		       $wrong_answer++;
		   }
		   $i++;
	 } */
	 $temp=$_SESSION['result'];
	 foreach($_SESSION AS $key => $value)
	 {  //echo "<p>key1: ".$key."</p>";
		// echo "<p>".$value."</p>";
		 foreach($_POST AS $key2 => $value2){
			 // echo "<p>Key2: ".$key2."</p>";
			  //echo "<p>value: ".$value2."</p>";
		 if($key===$key2){
			// echo "<p>Key1: ".$key."</p>";
			//  echo "<p>value1: ".($value)."</p>";
			// echo "<p>Key2: ".$key2."</p>";
			//  echo "<p>value2: ".($value2)."</p>";
			 if($value==$value2){
				 $right_answer++;
			 }
			 else if($value!=$value2){
				 $wrong_answer++;
			 }
			 else{$unanswered++;}
		  } 
		 }
	 }
	
	 echo " Right Answer  : <span class='highlight'>". $right_answer."</span><br>";

	 echo " Wrong Answer  : <span class='highlight'>". $wrong_answer."</span><br>";

	 echo " Unanswered Question  : <span class='highlight'>". $unanswered."</span><br>";
	// echo "</div>";
//}
?>
</body>
</html>
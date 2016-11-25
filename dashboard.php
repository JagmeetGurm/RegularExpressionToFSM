<?php
/*The login system has been derived from: 
 Javed Ur Rehman's Website: http://www.allphptricks.com/
*/
//session_start();
require('db.php');
include("auth.php");
include("header.php"); //include auth.php file on all secure pages
$category='';
if(!empty($_REQUEST['username']))
{
	$name=$_REQUEST['username'];
	//$category=$_R
	$_SESSION['name'] = $name;
	$_SESSION['id'] = mysqli_insert_id($con);

}
$category = 2;
if(!empty($_SESSION['name']))
{
}?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quiz - Secured Page</title>
<!--Bootstrap-->
<link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
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
<?php $response=mysqli_query($con, "SELECT * FROM questions ");?>

<!--dynamically generate questions-->
<form method ='post' id='quiz_form' action="" >
<div id='ques_form' >
<?php while($result=mysqli_fetch_array($response, MYSQLI_ASSOC))
{$_SESSION['result']=$result;
	?>

<div id="question_<?php echo $result['ques_id'];?>" class='questions'>
<h2 id="question_<?php echo $result['ques_id'];?>"><?php echo $result['ques_id'].".".$result['ques_name'];?></h2>

<div class='align' >
<input type="radio" value="1" id='radio1_<?php echo $result['ques_id'];?>' name='<?php echo $result['ques_id'];?>'>
<label id='ans1_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans1'];?></label>
<br/>
<input type="radio" value="2" id='radio2_<?php echo $result['ques_id'];?>' name='<?php echo $result['ques_id'];?>'>
<label id='ans2_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans2'];?></label>
<br/>
<input type="radio" value="3" id='radio3_<?php echo $result['id'];?>' name='<?php echo $result['ques_id'];?>'>
<label id='ans3_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans3'];?></label>
<br/>
<input type="radio" value="4" id='radio4_<?php echo $result['ques_id'];?>' name='<?php echo $result['ques_id'];?>'>
<label id='ans4_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans4'];?></label>
<input type="radio" checked='checked' value="5" style='display:none' id='radio4_<?php echo $result['ques_id'];?>' name='<?php echo $result['ques_id'];?>'>
</div>
<br/>

</div>

<?php }?>
<input type='submit' value="Submit" name ="submit" />
</form>

<?php
if(isset($_POST['submit'])){

$response2=mysqli_query($con, "select ques_id,ques_name,correct_ans from questions");
	 $i=1;
	 $right_answer=0;
	 $wrong_answer=0;
	 $unanswered=0;
	 while($result=mysqli_fetch_array($response2, MYSQLI_ASSOC)){ 
	       if($result['correct_ans']==$_POST["$i"]){
		       $right_answer++;
		   }else if($_POST["$i"]==5){
		       $unanswered++;
		   }
		   else{
		       $wrong_answer++;
		   }
		   $i++;
	 }
	 echo "<div id='answer'>";
	 echo " Right Answer  : <span class='highlight'>". $right_answer."</span><br>";

	 echo " Wrong Answer  : <span class='highlight'>". $wrong_answer."</span><br>";

	 echo " Unanswered Question  : <span class='highlight'>". $unanswered."</span><br>";
	 echo "</div>";
}
?>
</body>
</html>

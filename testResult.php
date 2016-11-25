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
$selectedUser=$_SESSION['username'];
$selectedQuiz=$_SESSION['quiz_no'];
$userIdentity=0;
$selectedCategory=$_SESSION['cat_id'];
$userIdResult=mysqli_query($con, "SELECT `id` FROM `users` WHERE `username`='{$selectedUser}'");
	 while($userId=$userIdResult->fetch_assoc()){
		$userIdentity=$userId['id'];
	 }
	$right_answer=0;
	 $wrong_answer=0;
	 $unanswered=0;
		 $temp=$_SESSION['result'];
		 foreach($_SESSION AS $key => $value)
		 {

			 foreach($_POST AS $key2 => $value2)
			 {
			 
				 if($key===$key2)
				 {
					
					 if($value==$value2)
					 {
						 $right_answer++;
					 }
					 else if($value!=$value2)
					 {
						 $wrong_answer++;
					 }
					 else{$unanswered++;}
				  } 
			  }	
	     }
	
	 echo " Right Answer  : <span class='highlight'>". $right_answer."</span><br>";

	 echo " Wrong Answer  : <span class='highlight'>". $wrong_answer."</span><br>";

	 echo " Unanswered Question  : <span class='highlight'>". $unanswered."</span><br>";
	 
	 //save the result
	
//review result 

?>

<!--dynamically generate questions-->
<script>var eventListeners = [];</script>
<form method ='post' id='quiz_form' >
<div id='ques_form' >

 <script>var eventListeners = [];</script>
<?php
 $i=1;
foreach($_POST AS $key => $value2)
{ $check4=substr($key, 0, 4);
	if($check4=='ques')
	{			
	$tempKey=substr($key, 5);
	
	$response2=mysqli_query($con, "SELECT * FROM questions WHERE ques_id=$tempKey && quiz_no='$selectedQuiz'");
	$result=mysqli_fetch_array($response2, MYSQLI_ASSOC);
	if($result['ques_type']==='m')
	 {
	 ?>
		<div name="ques_<?php echo $result['ques_id'];?>" class='questions'>
	<h2 name="ques_<?php echo $result['ques_id'];?>"><?php echo $i.".".$result['ques_name'];?></h2>

	<div class='align' >
	<input type="radio" value="<?php echo $result['ans1'];?>" <?php echo ($_POST[$key]==$result['ans1'])?'checked':''?> id='radio1_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans1'];?>
	</br>
	<input type="radio" value="<?php echo $result['ans2'];?>" <?php echo ($_POST[$key]==$result['ans2'])?'checked':''?> id='radio2_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans2'];?>
	</br>
	<input type="radio" value="<?php echo $result['ans3'];?>" <?php echo ($_POST[$key]==$result['ans3'])?'checked':''?> id='radio3_<?php echo $result['id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans3'];?>
	</br>
	<input type="radio" value="<?php echo $result['ans4'];?>" <?php echo ($_POST[$key]==$result['ans4'])?'checked':''?> id='radio4_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans4'];?>
	</div>
	<br/>
	<?php echo $_POST[$key]== $_SESSION[$key]? "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Your answer: <?php echo $_POST[$key]==5?  "unanswered" : $_POST[$key];?></p>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Correct answer: <?php echo $_SESSION[$key];?></p>

	</div>
	<?php $i++;
	//$intKey=(int)$tempKey;
	//mysqli_query($con, "INSERT INTO register.results (user_id, category_id, ques_id, quiz_no, user_ans, score, taken) 
      //               	VALUES ({$userId['id']},2,{$intKey} ,'2',{$right_answer}, 'yes')");
	} 
	
		
		else if($result['ques_type']==='fsm')
	{ 
      $t=$result['ques_id'];
	//echo $t;
		//$_SESSION["ques_".$t]=$result['correct_ans'];?>
		<form method ='post' id='quiz_form1' action="" >
		<div id="question_<?php echo $i;?>" class='questions'>
        <h2 id="question_<?php echo $i;?>"><?php echo $i.".".$result['ques_name'];?></h2>
	    <input id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" value="<?php echo $_POST[$key];?>"> 
		</br>
	    <button id="btnclick_<?php echo $result['ques_id'];?>" type="button">Create FSM</button>
		<p> Your answer: </p>
		<p id="testResult_<?php echo $result['ques_id'];?>"></p>
		<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>
        <p id="ques_ques_<?php echo $result['ques_id'];?>"></p>
<?php echo $_POST[$key]== $_SESSION[$key]? "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
	
		<script src="viz.js"></script>
		<script>
		var fn = function(){
		var inputAns<?php echo $result['ques_id']?>=document.querySelector("#txt_<?php echo $result['ques_id'];?>");
		var btn=document.querySelector("#btnclick_<?php echo $result['ques_id'];?>");
	    
		//btn.addEventListener('click', generateFSM);
		
	
	//function generateFSM(){
      var myresult='digraph G {none[style=invis];rankdir = LR; none->2[label=start]; '; //none->2[label=start];2->1[label=a]}';
	  myresult+=inputAns<?php echo $result['ques_id']?>.value;
	  myresult+='}';
      document.querySelector("#testResult_<?php echo $result['ques_id'];?>").innerHTML=Viz(myresult);	
	  myresult="";
	 // }
	};
	eventListeners.push(fn);
console.log(fn.toString());		
		</script>
		

		
	<?php
	
	
	$i++;} 
   
		else if($result['ques_type']==='b') 
		{
	//$t=$result['ques_id'];
	//echo $t;
		//$_SESSION["ques_".$t]=$result['correct_ans'];
	?>	
	<div name="ques_<?php echo $result['ques_id'];?>" class='questions'>
	<h2 name="ques_<?php echo $result['ques_id'];?>"><?php echo $i.".".$result['ques_name'];?></h2>

	<div class='align' >
		<input type="radio" value="<?php echo $result['ans1'];?>" <?php  if(isset($_POST[$key]) && $_POST[$key]=='True') echo'checked' ;?> id='radio1_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans1'];?>
	</br>
	<input type="radio" value="<?php echo $result['ans2'];?>" <?php  if(isset($_POST[$key]) && $_POST[$key]=='False') echo 'checked' ;?> id='radio2_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans2'];?>
	</br>
	</div>
	<br/>
	<?php echo isset($_POST[$key]) && $_POST[$key]== $_SESSION[$key]? "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Your answer: <?php echo isset($_POST[$key])? $_POST[$key]: "unanswered" ;?></p>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Correct answer: <?php echo $_SESSION[$key];?></p>

	</div>
	<?php $i++;}	
	$insertTaken="INSERT INTO quiz_taken (user_id, quiz_id, taken)
values($userIdentity,'$selectedQuiz' , 'yes')";
if($con->query($insertTaken)==TRUE){
	
}
else{
				echo "Error: " . $insertTaken . "<br>" . $con->error;

}
	$intKey=(int)$tempKey;
	$sql="INSERT INTO register.results (user_id, category_id, ques_id, quiz_no, user_ans, score, taken) 
                     	VALUES ({$userIdentity},{$selectedCategory},{$intKey} ,'{$selectedQuiz}','{$_POST[$key]}',{$right_answer}, 'yes')";
			if($con->query($sql)==TRUE){
		echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $con->error;
			
		}
	
		}
    }
 ?>
<script>document.addEventListener("DOMContentLoaded", function () {
	console.log(eventListeners);
	for(var i = 0; i < eventListeners.length; i++) {
		eventListeners[i]();
		console.log(eventListeners[i].toString());
	}
});</script>
</body>
</html>
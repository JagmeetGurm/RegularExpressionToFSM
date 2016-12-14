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

<p style="float:left; display:block; margin:10px;"><b>Welcome <?php echo $_SESSION['username']; ?>!</b></p>
<a href="index.php" style="float:left; display:block; margin:10px;">Home</a>
<a href="logout.php" style="float:left; display:block; margin:10px;">Logout</a>
<br></br>
<script>var eventListeners = [];</script>
<?php 
/*
$sql="INSERT INTO register.results (user_id, category_id, ques_id, score, taken, quiz_no) 
                     	VALUES (5,2,4,10, 'yes', '2')";
if($con->query($sql)==TRUE){
echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
	
}
*/
$selectedQuiz='';
$currentUserId=0;
$takenValue='';
$currentUser=$_SESSION['username'];
$j=1;
if(isset($_POST['Submit'])){
	$selectedQuiz=$_POST['quiz'];
	$_SESSION['quiz_no']=$selectedQuiz;
	$sql=mysqli_query($con, "SELECT id FROM `users` where `username`='$currentUser'"); 
	//if($con->query($sql)==TRUE){
      while($currentId=$sql->fetch_assoc()){
		$currentUserId=$currentId['id'];
	 }
	// echo  $currentUserId;
	//}
//	$getCountSql=mysqli_query($con, "SELECT COUNT(*) FROM results where user_id=$currentUserId && quiz_id='$selectedQuiz'"); 
//	echo "<h1>". $selectedQuiz.  $currentUser. "</h1>";
//while($getCount=$getCountSql->fetch_assoc()){
		//echo "Cont: ". $getCount[']
//}
	$sql2= mysqli_query($con,"SELECT taken FROM quiz_taken WHERE user_id=$currentUserId && quiz_id='$selectedQuiz' LIMIT 1");
/*	if($con->query($sql2)==TRUE){
		echo "success";
	}*/
	if(mysqli_num_rows($sql2) > 0)
	{
	$i=1;
		 while($val=$sql2->fetch_assoc())
		 {
		
		$takenValue=$val['taken'];
		//echo" taken".$takenValue;
		
				if($takenValue=='yes')
				{ $j++;
		//go to review page
						$response2=mysqli_query($con, "SELECT ques_id, user_ans FROM results WHERE user_id=$currentUserId && quiz_no='$selectedQuiz'");
						 while($result=mysqli_fetch_array($response2, MYSQLI_ASSOC))
						 {
							$getQues = mysqli_query($con, "SELECT * FROM questions WHERE ques_id={$result['ques_id']}" );
							$getQuesName=mysqli_fetch_array($getQues, MYSQLI_ASSOC);
							
						//	echo "<p>id: ". $result['ques_id']. "</p>";
					//		echo "<p>ques name: ". $getQuesName['ques_name']. "</p>";
															
										if($getQuesName['ques_type']==='m')
									 {
									 ?>
										<div name="ques_<?php echo $result['ques_id'];?>" class='questions'>
									<h2 name="ques_<?php echo $result['ques_id'];?>"><?php echo $i.".".$getQuesName['ques_name'];?></h2>

									<div class='align' >
									<input type="radio" value="<?php echo $getQuesName['ans1'];?>" <?php echo ($result['user_ans']==$getQuesName['ans1'])?'checked':''?> id='radio1_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['ans1'];?>
									</br>
									<input type="radio" value="<?php echo $getQuesName['ans2'];?>" <?php echo ($result['user_ans']==$getQuesName['ans2'])?'checked':''?> id='radio2_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['ans2'];?>
									</br>
									<input type="radio" value="<?php echo $getQuesName['ans3'];?>" <?php echo ($result['user_ans']==$getQuesName['ans3'])?'checked':''?> id='radio3_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['ans3'];?>
									</br>
									<input type="radio" value="<?php echo $getQuesName['ans4'];?>" <?php echo ($result['user_ans']==$getQuesName['ans4'])?'checked':''?> id='radio4_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['ans4'];?>
									</div>
									<br/>
									<?php echo $result['user_ans']== $getQuesName['correct_ans']? "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
									<p id="ques_ques_<?php echo $result['ques_id'];?>">Your answer: <?php echo $result['user_ans']==5?  "unanswered" : $result['user_ans'];?></p>
									<p id="ques_ques_<?php echo $result['ques_id'];?>">Correct answer: <?php echo $getQuesName['correct_ans'];?></p>
	                                <p id="ques_ques_<?php echo $result['ques_id'];?>">Feedback: <?php echo $getQuesName['feedback'];?></p>

									</div>
									<?php $i++;
									//$intKey=(int)$tempKey;
									//mysqli_query($con, "INSERT INTO register.results (user_id, category_id, ques_id, quiz_no, user_ans, score, taken) 
									  //               	VALUES ({$userId['id']},2,{$intKey} ,'2',{$right_answer}, 'yes')");
									}
//fsm type questions
									else if($getQuesName['ques_type']==='fsm')
										{ 
										  $t=$result['ques_id'];
										//echo $t;
											//$_SESSION["ques_".$t]=$result['correct_ans'];?>
											
											<div id="question_<?php echo $i;?>" class='questions'>
											<h2 id="question_<?php echo $i;?>"><?php echo $i.".".$getQuesName['ques_name'];?></h2>
											  <p> Your answer: </p>
											<textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" style="font-size:10pt;height:220px;width:600px;"> <?php echo $result['user_ans'];?>
											
											</textarea>
											
											</br>
											 <b>	<p id="fsmcheck_<?php echo $result['ques_id'];?>"></p></b>
											 <p>Correct answer: </p>
											 <p id="correct_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['correct_ans'];?></p>	
		                                     <p id="testResult_<?php echo $result['ques_id'];?>"></p>
	                                <p id="ques_ques_<?php echo $result['ques_id'];?>">Feedback: <?php echo $getQuesName['feedback'];?></p>

											<script src="Underscore.js"></script>
		                                     <script src="bfs.js"></script>
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
									//console.log(fn.toString());		
											</script>
											

											
										<?php
										
										
										$i++;} 
	
											else if($getQuesName['ques_type']==='b') 
												{
											//$t=$result['ques_id'];
											//echo $t;
												//$_SESSION["ques_".$t]=$result['correct_ans'];
											?>	
											<div name="ques_<?php echo $result['ques_id'];?>" class='questions'>
											<h2 name="ques_<?php echo $result['ques_id'];?>"><?php echo $i.".".$getQuesName['ques_name'];?></h2>

											<div class='align' >
												<input type="radio" value="<?php echo $getQuesName['ans1'];?>" <?php  if ($result['user_ans']=='True') echo'checked' ;?> id='radio1_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['ans1'];?>
											</br>
											<input type="radio" value="<?php echo $getQuesName['ans2'];?>" <?php  if($result['user_ans']=='False') echo 'checked' ;?> id='radio2_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>"><?php echo $getQuesName['ans2'];?>
											</br>
											</div>
											<br/>
											<?php echo ($result['user_ans']== $getQuesName['correct_ans'])? "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
											<p id="ques_ques_<?php echo $result['ques_id'];?>">Your answer: <?php echo ($result['user_ans'])? $result['user_ans']: "unanswered" ;?></p>
											<p id="ques_ques_<?php echo $result['ques_id'];?>">Correct answer: <?php echo $getQuesName['correct_ans'];?></p>

											</div>
											<?php $i++;}	
	                           }
	                    }
	
	
								else{ 
								//take the quiz
								header("location: newQuiz.php");
									echo "not taken";
								}
         }
      }
         else { 
				//take the quiz
				header("location: newQuiz.php");
					echo "not taken";
				}
	}
//mysqli_query($con, );
?>
<script>document.addEventListener("DOMContentLoaded", function () {
	//console.log(eventListeners);
	for(var i = 0; i < eventListeners.length; i++) {
		eventListeners[i]();
		//console.log(eventListeners[i].toString());
	}
});</script>	
</body>
</html>
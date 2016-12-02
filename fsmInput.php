<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
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
<body >
<div class="form">

<p style="float:left; display:block; margin:10px;"><b>Welcome <?php echo $_SESSION['username']; ?>!</b></p>
<a href="index.php" style="float:left; display:block; margin:10px;">Home</a>
<a href="logout.php" style="float:left; display:block; margin:10px;">Logout</a>


<br /><br /><br /><br />
</div>
<?php
$chosenQuiz=4; //$_SESSION['quiz_no']; 
echo "chosen: ".$chosenQuiz;
$response=mysqli_query($con, "SELECT * FROM questions where quiz_no ='$chosenQuiz'");?>

<!--dynamically generate questions-->
<script>var eventListeners = [];</script>
<form method ='post' id='quiz_form' action="fsmResult.php" >
<div id='ques_form' >
<?php 
//right now category hard coded
//$_SESSION['cat_id']=2;
$i=1;
while($result=mysqli_fetch_array($response, MYSQLI_ASSOC))
{//$_SESSION['result']=$result;

	if($result['ques_type']==='m')
		
	{$t=$result['ques_id'];
	//echo $t;
		$_SESSION["ques_".$t]=$result['correct_ans'];
	
   	?>

<div name="ques_<?php echo $result['ques_id'];?>" class='questions'>
<h2 name="ques_<?php echo $result['ques_id'];?>"><?php echo $i.".".$result['ques_name'];?></h2>

<div class='align' >
<input type="radio" value="<?php echo $result['ans1'];?>" id='radio1_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>">
<label id='ans1_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans1'];?></label>
<br/>
<input type="radio" value="<?php echo $result['ans2'];?>" id='radio2_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>">
<label id='ans2_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans2'];?></label>
<br/>
<input type="radio" value="<?php echo $result['ans3'];?>" id='radio3_<?php echo $result['id'];?>' name="ques_<?php echo $result['ques_id'];?>">
<label id='ans3_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans3'];?></label>
<br/>
<input type="radio" value="<?php echo $result['ans4'];?>" id='radio4_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>">
<label id='ans4_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans4'];?></label>
<input type="radio" checked='checked' value="5" style='display:none' id='radio4_<?php echo $result['ques_id'];?>' name='ques_<?php echo $result['ques_id'];?>'>
</div>
<br/>
<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>
<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>

</div>
	<?php $i++;} 
	
	
	else if($result['ques_type']==='b') 
		{
	$t=$result['ques_id'];
	//echo $t;
		$_SESSION["ques_".$t]=$result['correct_ans'];
	?>	
	<div id="question_<?php echo $result['ques_id'];?>" class='questions' name="ques_<?php echo result['ques_id'];?>">
    <h2 name="quest_<?php echo $result['ques_id'];?>"><?php echo  $i.".".$result['ques_name'];?></h2>
	
	<div class='align' >
	<input type="radio" value="<?php echo $result['ans1'];?>" name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans1'];?>
	<br/>
	<input type="radio" value="<?php echo $result['ans2'];?> "  name="ques_<?php echo $result['ques_id'];?>"><?php echo $result['ans2'];?>
	
	<br/>
	</div>
	<?php $i++;}
	else if($result['ques_type']==='fsm')
	{ 
      $t=$result['ques_id'];
	//echo $t;
		$_SESSION["ques_".$t]=$result['correct_ans'];?>
		
		<div id="question_<?php echo $i;?>" class='questions'>
        <h2 id="question_<?php echo $i;?>"><?php echo $i.".".$result['ques_name'];?></h2>
	    <textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" style="font-size:10pt;height:220px;width:300px;"> 
		</textarea>
		</br>
	    <button id="btnclick_<?php echo $result['ques_id'];?>" type="button">Create FSM</button>
		<p id="testResult_<?php echo $result['ques_id'];?>"></p>
		<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>
        <p id="ques_ques_<?php echo $result['ques_id'];?>"></p>

		<script src="viz.js"></script>
		<script src="bfs.js"></script>
		<script>
		var fn = function(){
		var inputAns<?php echo $result['ques_id']?>=document.querySelector("#txt_<?php echo $result['ques_id'];?>");
		var btn=document.querySelector("#btnclick_<?php echo $result['ques_id'];?>");
	    btn.addEventListener('click', genFSM);
		//btn.addEventListener('click', generateFSM);
		
	function genFSM(){
		var transition={
  src:ident,
  ch:'~',
  dest:[ident]
};
var ident=0;
var bfsm={
  states:[ident],
  trans:[transition]
};
var text=inputAns<?php echo $result['ques_id']?>.value;
var nfa=JSON.parse(text);
/*	var nfa= { states: [1,2],
  trans: [
{src:1, ch: 'a', dest:[1,0] },
{src:2, ch:'b', dest:[2,0] }
          ]
}; */	
	console.log("nfa: "+nfa.states.length);
	var result='digraph { rankdir = LR; none[style=invis];' ;
	for(var i=0; i<nfa.states.length; i++){
  result+="none->"+nfa.states[i]+ "[label=start];";
}
//display transitions
for(var j=0; j<nfa.trans.length; j++)
{
  for(var k=0; k<nfa.trans[j].dest.length; k++)
  {
    result+=nfa.trans[j].src+"->"+nfa.trans[j].dest[k]+ " [label="+nfa.trans[j].ch+"];";

  }
}
result+=0+"[shape=doublecircle];";
result+='}';
      document.querySelector("#testResult_<?php echo $result['ques_id'];?>").innerHTML=Viz(result);	
 result="";
	}
	function generateFSM(){
      var myresult='digraph G {none[style=invis];rankdir = LR; none->2[label=start]; '; //none->2[label=start];2->1[label=a]}';
	  myresult+=inputAns<?php echo $result['ques_id']?>.value;
	  myresult+='}';
      document.querySelector("#testResult_<?php echo $result['ques_id'];?>").innerHTML=Viz(myresult);	
	  myresult="";
	  }
	};
	eventListeners.push(fn);
//console.log(fn.toString());		
		</script>
		

		
	<?php
	
	
	$i++;} ?>
	
	
	
<?php }?>
<input type='submit' value="Submit" name ="submit" />
</form>

<?php
/*
if(isset($_POST['submit'])){

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
	 }
	 
	 foreach($_SESSION AS $key => $value)
	 {  //echo "<p>key1: ".$key."</p>";
		// echo "<p>".$value."</p>";
		 foreach($_POST AS $key2 => $value2){
			 // echo "<p>Key2: ".$key2."</p>";
			  //echo "<p>value: ".$value2."</p>";
		 if($key===$key2){
			 $tempKey=substr($key2, -2);
			 ?>
			 <script>
			 window.onload=function(){
			 document.querySelector("#ques_<?php echo $key;?>").innerHTML="the correct ans is : "<?php $_SESSION[$key]?>;
			 }
			 </script>
			 <?php
			// 
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
	 echo "<div id='answer'>";
	 echo " Right Answer  : <span class='highlight'>". $right_answer."</span><br>";

	 echo " Wrong Answer  : <span class='highlight'>". $wrong_answer."</span><br>";

	 echo " Unanswered Question  : <span class='highlight'>". $unanswered."</span><br>";
	 echo "</div>";
}
*/
?>
<script>document.addEventListener("DOMContentLoaded", function () {
	//console.log(eventListeners);
	for(var i = 0; i < eventListeners.length; i++) {
		eventListeners[i]();
		//console.log(eventListeners[i].toString());
	}
});</script>
</body>

<script src="viz.js"></script>
<script src="bfs.js"></script>
<script src="Underscore.js"></script>
</html>

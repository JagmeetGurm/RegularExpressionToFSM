<?php

require('db.php');
include("auth.php");
include("header.php"); //include auth.php file on all secure pages
//$category='';
if(!empty($_REQUEST['username']))
{
	$name=$_REQUEST['username'];
	//$category=$_R
	$_SESSION['name'] = $name;
	$_SESSION['id'] = mysqli_insert_id($con);
}
//$category = 2;
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
<div id="instruction" style="padding:10px;">
<p><b>Instructions for taking quiz:</b></p>
<p>1. For multiple choice questions and two option questions, simple select one correct choice.</p>
<p>2. For create FSM type questions, the text has to be entered in <b>JSON</b> format. The JSON object represents
the NFA. NFA must be of McIlroy's Backward Construction. This means there is only one final state, 
can be one or more than one start states, and one char transition from each state. 
The format has to be strictly followed to get correct answer. For <b>example:</b></p>
<textarea  style="font-size:10pt;height:300px;width:600px;"> 
{
 "states": [1,2],
 "trans": [{"src": 1,
           "ch": "a",
           "dest": [1, 0]
          },
		  {"src": 2,
           "ch": "b",
           "dest": [0]
          }
         ]
}
</textarea>
<p id="exResult"></p>
<script src="viz.js"></script>
<script>
//the JSON example
var exampleResult='digraph G {none[style=invis];rankdir = LR; none->2[label=start]; none->1[label=start];';
exampleResult+='2->0[label=b]; 1->1[label=a]; 1->0[label=a];';
exampleResult+='0[shape=doublecircle];';
exampleResult+='}';
document.getElementById('exResult').innerHTML=Viz(exampleResult);
</script>



<p> The object consists of two elements: "states" which is a set of initial start states and 
"trans" which is a set of transitions from a source ("src") on a char ("ch") to a set of destination 
("dest").</p>
<p>3. <b>Warning: </b>Make sure <b>JSON</b> code is entered in correct format else result will be wrong.  
</p> 
<p>4. For a FSM to accept any string, it must have a specified start state and Final state. 
Start state will always have a start arrow and final state will have double circle and 0 id. </p>
</div>
<?php
$chosenQuiz=$_SESSION['quiz_no']; 

//specify quiz level
if($chosenQuiz=='6'){
	echo "<h1 style='padding:5px;'>Quiz Level: Random</h1></br></br>";
}
else{
echo "<h1 style='padding:5px;'>Quiz Level: ".$chosenQuiz."</h1></br></br>";
}

//for random quiz, take random questions, quiz level 6 specifies random quiz
if($chosenQuiz=='6')
{
$response=mysqli_query($con, "SELECT * FROM questions ORDER BY RAND() LIMIT 10");
	
}
else{
$response=mysqli_query($con, "SELECT * FROM questions where quiz_no ='$chosenQuiz'");
}
?>

<!--dynamically generate questions-->
<script>var eventListeners = [];</script>
<form method ='post' id='quiz_form' action="testResult.php" >
<div id='ques_form' >
<?php 

$i=1;
while($result=mysqli_fetch_array($response, MYSQLI_ASSOC))
{
	if($result['ques_type']==='m')
		
	{$t=$result['ques_id'];
	//echo $t;
		$_SESSION["ques_".$t]=$result['correct_ans'];
	
   	?>

<div name="ques_<?php echo $result['ques_id'];?>" class='questions'>
<h2 name="ques_<?php echo $result['ques_id'];?>" ><?php echo $i.".".$result['ques_name'];?></h2>

<div class='align' >
<input type="radio" value="<?php echo $result['ans1'];?>" id='radio1_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>" required="required" ><label id='ans1_<?php echo $result['ques_id'];?>' for='1' required="required" ><?php echo $result['ans1'];?></label>
<br/>
<input type="radio" value="<?php echo $result['ans2'];?>" id='radio2_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>" required >
<label id='ans2_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans2'];?></label>
<br/>
<input type="radio" value="<?php echo $result['ans3'];?>" id='radio3_<?php echo $result['id'];?>' name="ques_<?php echo $result['ques_id'];?>" required >
<label id='ans3_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans3'];?></label>
<br/>
<input type="radio" value="<?php echo $result['ans4'];?>" id='radio4_<?php echo $result['ques_id'];?>' name="ques_<?php echo $result['ques_id'];?>" required >
<label id='ans4_<?php echo $result['ques_id'];?>' for='1'><?php echo $result['ans4'];?></label>
<input type="radio" checked='checked' value="5" style='display:none' id='radio4_<?php echo $result['ques_id'];?>' name='ques_<?php echo $result['ques_id'];?>'>
</div>
<br/>
<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>
<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>

</div>
	<?php $i++;} 
	
	//questions of create FSM type
	else if($result['ques_type']==='fsm')
	{ 
      $t=$result['ques_id'];
	
		$_SESSION["ques_".$t]=$result['correct_ans'];?>
		
		<div id="question_<?php echo $i;?>" class='questions'>
        <h2 id="question_<?php echo $i;?>" required><?php echo $i.".".$result['ques_name'];?></h2>
	    <textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>"  style="font-size:10pt;height:220px;width:600px;" ></textarea>
		</br>
	    <button id="btnclick_<?php echo $result['ques_id'];?>" type="button">Create FSM</button>
		
		<b><p id="testResult_<?php echo $result['ques_id'];?>"></p></b>
		<p id="ques_ques_<?php echo $result['ques_id'];?>"></p>
        <p id="ques_ques_<?php echo $result['ques_id'];?>"></p>

		<script src="viz.js"></script>
		
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
  function isJson(text){
	  
   try {
        var temp=JSON.parse(text);
    } catch (e) {
        return false;
    }
	return true;
	 }
    if(!isJson(text))
	{
		document.getElementById("testResult_<?php echo $result['ques_id'];?>").innerHTML="Invalid JSON. Check instructions above.";
		return;
	}
		
	
var nfa=JSON.parse(text);

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
		
		</script>
	
	<?php
		
	$i++;} 
	
	
		?>

	
<?php }?>
<input type='submit' value="Submit" name ="submit" />
</form>

<?php


?>
<script>document.addEventListener("DOMContentLoaded", function () {
	
	for(var i = 0; i < eventListeners.length; i++) {
		eventListeners[i]();
		
	}
});</script>
</body>

<script src="viz.js"></script>

<script src="Underscore.js"></script>
</html>
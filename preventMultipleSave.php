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

$gFsmCheck=false;
//use it as a direct object
$userIdResult=mysqli_query($con, "SELECT `id` FROM `users` WHERE `username`='{$selectedUser}'");
	 while($userId=$userIdResult->fetch_assoc()){
		$userIdentity=$userId['id'];
	 }
	 if($selectedQuiz=='6')
	 {
		 
	$insertTaken="INSERT INTO quiz_taken (user_id, quiz_id, taken)
values($userIdentity,'$selectedQuiz' , 'no')"; 
	 }
	 else{
	$insertTaken="INSERT INTO quiz_taken (user_id, quiz_id, taken)
values($userIdentity,'$selectedQuiz' , 'yes')";
	 }
if($con->query($insertTaken)==TRUE){
	
}
else{
				echo "Error: " . $insertTaken . "<br>" . $con->error;
} 
	$right_answer=0;
	 $wrong_answer=0;
	 $unanswered=0;
		// $temp=$_SESSION['result'];
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
	
/*	 echo " <span class='highlight' style='padding:10px;'>Right Answer:". $right_answer."</span><br>";
	 echo "<span class='highlight' style='padding:10px;'> Wrong Answer: ". $wrong_answer."</span><br>";
	 echo "<span class='highlight' style='padding:10px;'>Unanswered Question: ". $unanswered."</span><br>";
	 */
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
	if($selectedQuiz=='6')
	{
			$response2=mysqli_query($con, "SELECT * FROM questions WHERE ques_id=$tempKey ");

	}
	else {
	$response2=mysqli_query($con, "SELECT * FROM questions WHERE ques_id=$tempKey && quiz_no='$selectedQuiz'");
	
	}$result=mysqli_fetch_array($response2, MYSQLI_ASSOC);
	if($result['ques_type']==='mm')
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
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Feedback: <?php echo $result['feedback'];?></p>

	</div>
	<?php $i++;
		} 
	
		
		else if($result['ques_type']==='fsmmm')
	{ 
$ob=json_decode($_POST[$key]);
if($ob == null){
	echo "Error. Your JSON code is invalid";
	return;
}
      $t=$result['ques_id'];
	//echo $t;
		//$_SESSION["ques_".$t]=$result['correct_ans'];?>
		
		<div id="question_<?php echo $i;?>" class='questions'>
        <h2 id="question_<?php echo $i;?>"><?php echo $i.".".$result['ques_name'];?></h2>
	    <p> Your answer: </p>
		<textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" style="font-size:10pt;height:220px;width:600px;"> <?php echo $_POST[$key];?>
		</textarea>
		</br>
	  	<h1 id="fsmcheck_correct_<?php echo $result['ques_id'];?>" style='color:green;'></h1>
	    <h1 id="fsmcheck_wrong_<?php echo $result['ques_id'];?>" style='color:red;'></h1>

	   <div id="displayTick_<?php echo $result['ques_id'];?>">
	   <div>
<!-- // "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
	-->
	<p>Correct answer: </p>
	
	<p id="correct_<?php echo $result['ques_id'];?>"></p>	
		<p id="testResult_<?php echo $result['ques_id'];?>"></p>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Feedback: <?php echo $result['feedback'];?></p>

		<script src="viz.js"></script>
		<script src="Underscore.js"></script>
		<script src="bfs.js"></script>
		<script>
		var fn = function(){
		var inputAns<?php echo $result['ques_id']?>=document.querySelector("#txt_<?php echo $result['ques_id'];?>");
		var btn=document.querySelector("#btnclick_<?php echo $result['ques_id'];?>");
	   // btn.addEventListener('click', genFSM);
		//btn.addEventListener('click', generateFSM);
		
	//function genFSM(){
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
//////////////////////enum
///////////////enumeration 
var Word={
  enumString:"",
  enumNfa:[]
} 
function visit(ArrWord, nfa)
{
  var listOfWords=[];
  
  var listOfStrings=[];
  
  for(var k=0; k<ArrWord.length; k++)
  {
   listOfStrings.push(ArrWord[k]);
  }
  while(listOfStrings.length>0 && listOfWords.length!=50)
  { 
  
   var firstElement=listOfStrings[0];
   
    listOfStrings.splice(0,1);
    if(_.contains(firstElement.enumNfa, 0))
    {listOfWords.push(firstElement.enumString);
    }
     var ret= grp(firstElement.enumNfa, nfa);
     for(var i=0; i<ret.length; i++)
     {
      var t=firstElement.enumString + ret[i].enumString;
      ret[i].enumString=t;
      listOfStrings.push(ret[i]);
     }
  }
  listOfWords.sort();
  //sort according to length
  listOfWords.sort(function(a,b){
    return a.length - b.length || a.localeCompare(b);;
  });
//remove duplicates
_.uniq(listOfWords);

return listOfWords;
}
//first element's list of start states
function grp(eNfa, nfa)
{var listWord=[];
  
    
    for(var i=0; i<eNfa.length; i++)
    {
      for(var j=0; j<nfa.trans.length; j++)
      {
    
        if(eNfa[i]==nfa.trans[j].src)
        {
          listWord.push({enumString: nfa.trans[j].ch, enumNfa:nfa.trans[j].dest});
        
        }
      }
    }
    
      return listWord;
    
    
  return listWord;
}
function enumA(nfaForMatching){
  var nfa=nfaForMatching;
  //change start ids to nfas
  var enumNfaStartStates= [];
  for(var i=0; i<nfa.states.length; i++)
  {
   enumNfaStartStates.push(nfa.states[i]);
  }
  
      var w={enumString: "", enumNfa: enumNfaStartStates};
 
  var retList=visit([w], nfa);
  //console.log(retList);
  return retList;
}
///////////////////////enum end

var nfaUser=<?php echo isset($_POST[$key])? $_POST[$key]: '{"states": [], "trans":[]}';?>;
var text=<?php echo $result['correct_ans'];?>;
//correct ans
var nfa=text;
compareNfas(nfa, nfaUser);
function compareNfas(n1, n2){
	var r1=enumA(n1);
	
	var r2=enumA(n2);
	if(r1.sort().join(',')=== r2.sort().join(',')){
     
	 document.getElementById("fsmcheck_correct_<?php echo $result['ques_id'];?>").innerHTML="Y&#x2713;";
}
else { 	 document.getElementById("fsmcheck_wrong_<?php echo $result['ques_id'];?>").innerHTML='&#x2717;';
<?php $gFsmCheck=false; ?>}


}

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
      document.querySelector("#correct_<?php echo $result['ques_id'];?>").innerHTML=Viz(result);	
 result="";
		
	
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
	
	else if($result['ques_type']==='str')
	{ 
      $t=$result['ques_id'];
	//echo $t;
		//$_SESSION["ques_".$t]=$result['correct_ans'];?>
		
		<div id="question_<?php echo $i;?>" class='questions'>
        <h2 id="question_<?php echo $i;?>"><?php echo $i.".".$result['ques_name'];?></h2>
	    <textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" style="font-size:10pt;height:220px;width:600px;"> <?php echo $_POST[$key];?>
		</textarea>
		
		<p><?php  $userEnum=explode(",",$_POST[$key]);
		          $correctEnum= explode(",",$_SESSION[$key]);
				  for($a=0; $a<count($userEnum); $a++)
		{
			if($_POST[$key][$a]==$_SESSION[$key][$a])
				echo "hi";
			else "no";
		}			?></p>
		<?php echo isset($_POST[$key]) && strcmp("$_POST[$key]","$_SESSION[$key]")==0? "<h1 style='color:green;'> &#x2713; </h1>": "<h1 style='color:red;'> &#x2717;</h1>";?>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Your answer: <?php echo isset($_POST[$key])? $_POST[$key]: "unanswered" ;?></p>
	<p id="ques_ques_<?php echo $result['ques_id'];?>">Correct answer: <?php echo $_SESSION[$key];?></p>

	</div>
		<?php $i++;
	}
	
	$intKey=(int)$tempKey;
	$sql="INSERT INTO register.results (user_id,  ques_id, quiz_no, user_ans, score, taken) 
                     	VALUES ({$userIdentity},{$intKey} ,'{$selectedQuiz}','{$_POST[$key]}',{$right_answer}, 'yes')";
			if($con->query($sql)==TRUE){
		//
		} else {
			echo "Error: " . $sql . "<br>" . $con->error;
			
		}
	
		}
    }
	//header("HTTP/1.1 303 See Other");
	header("location: displayResult.php");
 ?>
<script>document.addEventListener("DOMContentLoaded", function () {
	//console.log(eventListeners);
	for(var i = 0; i < eventListeners.length; i++) {
		eventListeners[i]();
		///console.log(eventListeners[i].toString());
	}
});</script>
</body>
</html>
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
		 //do nothing
	
	 }
	 else{
	$insertTaken="INSERT INTO quiz_taken (user_id, quiz_id, taken)
values($userIdentity,'$selectedQuiz' , 'yes')";

if($con->query($insertTaken)==TRUE){
//do nothing	
}
else{
				echo "Error: " . $insertTaken . "<br>" . $con->error;
} 
	 }
	 
	
	
//review result */
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
	<p id="ques_ques_<?php echo $result['ques_id'];?>"><b>Feedback: </b><?php echo $result['feedback'];?></p>

	</div>
	<?php $i++;
		} 
	
		
		else if($result['ques_type']==='fsm')
	{ 

      $t=$result['ques_id'];
	?>
		
		<div id="question_<?php echo $i;?>" class='questions'>
        <h2 id="question_<?php echo $i;?>"><?php echo $i.".".$result['ques_name'];?></h2>
	    <p> Your answer: </p>
		<textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" style="font-size:10pt;height:220px;width:600px;"> <?php echo $_POST[$key];?>
		</textarea>
		</br>
		<p id="user_ans_<?php echo $result['ques_id'];?>"></p>
	  	<h1 id="fsmcheck_correct_<?php echo $result['ques_id'];?>" style='color:green;'></h1>
	    <h1 id="fsmcheck_wrong_<?php echo $result['ques_id'];?>" style='color:red;'></h1>

	   <div id="displayTick_<?php echo $result['ques_id'];?>">
	   <div>

	<p>Correct answer: </p>
	
	<p id="correct_<?php echo $result['ques_id'];?>"></p>	
		<p id="testResult_<?php echo $result['ques_id'];?>"></p>
	<p id="ques_ques_<?php echo $result['ques_id'];?>"><b>Feedback: </b><?php echo $result['feedback'];?></p>
<?php
	$ob=json_decode($_POST[$key]);
if($ob == null){
	echo "Error. Your JSON code is invalid";
	?>
	<script src="viz.js"></script>
		<script src="Underscore.js"></script>
	<script>
	
	var nfa=<?php echo $result['correct_ans'];?>;
//correct ans
var result="";
	 result='digraph { rankdir = LR; none[style=invis];' ;
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
document.getElementById("fsmcheck_wrong_<?php echo $result['ques_id'];?>").innerHTML='&#x2717;';	  
 result="";
 
	</script>
	
	
	
<?php
	
}
else{
?>
		<script src="viz.js"></script>
		<script src="Underscore.js"></script>
		
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
  while(listOfStrings.length>0 && listOfWords.length!=80)
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
	  listOfWords=_.uniq(listOfWords);
  }
  listOfWords.sort();
  //sort according to length
  listOfWords.sort(function(a,b){
    return a.length - b.length || a.localeCompare(b);;
  });
//remove duplicates
listOfWords=_.uniq(listOfWords);

return listOfWords.splice(0,50);
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
     
	 document.getElementById("fsmcheck_correct_<?php echo $result['ques_id'];?>").innerHTML="&#x2713;";
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
		
	///
				
var resultUser=	'digraph { rankdir = LR; none[style=invis];' ;
for(var i=0; i<nfaUser.states.length; i++){
  resultUser+="none->"+nfaUser.states[i]+ "[label=start];";
}
//display transitions
for(var j=0; j<nfaUser.trans.length; j++)
{
  for(var k=0; k<nfaUser.trans[j].dest.length; k++)
  {
    resultUser+=nfaUser.trans[j].src+"->"+nfaUser.trans[j].dest[k]+ " [label="+nfaUser.trans[j].ch+"];";
  }
}
resultUser+=0+"[shape=doublecircle];";
resultUser+='}';
      document.querySelector("#user_ans_<?php echo $result['ques_id'];?>").innerHTML=Viz(resultUser);	
 resultUser="";	
	
	};
	eventListeners.push(fn);
//console.log(fn.toString());		
		</script>
		
		
		
	<?php
	
		}
	$i++;} 
   
		
	
	
	
	/*//store the quiz other than random
				if($selectedQuiz!='6')
				{   $intKey=(int)$tempKey;
					$userAns=$_POST[$key];
					$quizTaken='yes';
					$stmt= $con->prepare("INSERT INTO register.results (user_id,  ques_id, quiz_no, user_ans, score, taken) VALUES (?,?,?,?,?,?)");
					$stmt->bind_param("iissis", $userIdentity,$intKey ,$selectedQuiz,$userAns,$right_answer, $quizTaken);
					$stmt->execute();
				}
*/
		}
    }
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
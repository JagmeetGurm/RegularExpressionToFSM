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
	echo"<p><b>Quiz ". $selectedQuiz. " Review</b><p>";
	$sql2= mysqli_query($con,"SELECT taken FROM quiz_taken WHERE user_id=$currentUserId && quiz_id='$selectedQuiz' LIMIT 1");

	if(mysqli_num_rows($sql2) > 0)
	{
	$i=1;
		 while($val=$sql2->fetch_assoc())
		 {
		
		$takenValue=$val['taken'];
		
		
				if($takenValue=='yes')
				{ $j++;
		//go to review page
						$response2=mysqli_query($con, "SELECT  ques_id, user_ans FROM results WHERE user_id=$currentUserId && quiz_no='$selectedQuiz' LIMIT 10");
						 while($result=mysqli_fetch_array($response2, MYSQLI_ASSOC))
						 {
							$getQues = mysqli_query($con, "SELECT * FROM questions WHERE ques_id={$result['ques_id']}" );
							$getQuesName=mysqli_fetch_array($getQues, MYSQLI_ASSOC);
															
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
	                                <p id="ques_ques_<?php echo $result['ques_id'];?>"><b>Feedback:</b> <?php echo $getQuesName['feedback'];?></p>

									</div>
									<?php $i++;
									
										}
//fsm type questions
									else if($getQuesName['ques_type']==='fsm')
										{ 
										  $t=$result['ques_id'];
										?>
											
											<div id="question_<?php echo $i;?>" class='questions'>
											<h2 id="question_<?php echo $i;?>"><?php echo $i.".".$getQuesName['ques_name'];?></h2>
											  <p> Your answer: </p>
											<textarea id="txt_<?php echo $result['ques_id'];?>" type="text" name="ques_<?php echo $result['ques_id'];?>" style="font-size:10pt;height:220px;width:600px;"> <?php echo $result['user_ans'];?>
											
											</textarea>
											
											</br>
											<p id="user_ans_<?php echo $result['ques_id'];?>"></p>	
											 <b>	<p id="fsmcheck_<?php echo $result['ques_id'];?>"></p></b>
											 <h1 id="fsmcheck_correct_<?php echo $result['ques_id'];?>" style='color:green;'></h1>
	    <h1 id="fsmcheck_wrong_<?php echo $result['ques_id'];?>" style='color:red;'></h1>
											 <p>Correct answer: </p>
											 <p id="correct_<?php echo $result['ques_id'];?>"></p>	
		                                     <p id="testResult_<?php echo $result['ques_id'];?>"></p>
	                                <p id="ques_ques_<?php echo $result['ques_id'];?>"><b>Feedback: </b><?php echo $getQuesName['feedback'];?></p>

											<script src="Underscore.js"></script>
		                            
											<script src="viz.js"></script>
											<script>
											var fn = function(){
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
										
							var nfaUser=<?php echo (json_decode($result['user_ans']))==null? '{"states": [], "trans":[]}': $result['user_ans'];?>;
var text=<?php echo $getQuesName['correct_ans'];?>;
//correct ans
var nfa=text;
compareNfas(nfa, nfaUser);
function compareNfas(n1, n2){
	var r1=enumA(n1);
	
	var r2=enumA(n2);
	if(r1.sort().join(',')=== r2.sort().join(',')){
    
	 document.getElementById("fsmcheck_correct_<?php echo $result['ques_id'];?>").innerHTML="&#x2713;";
	
}
else {

 document.getElementById("fsmcheck_wrong_<?php echo $result['ques_id'];?>").innerHTML='&#x2717;';

}


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
										
										
										$i++;} 
	
												
	                           }
	                    }
	
	
								else{ 
								//take the quiz
								header("location: index.php");
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
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
<body>
<div class="form">

<p style="float:left; display:block; margin:10px;"><b>Welcome <?php echo $_SESSION['username']; ?>!</b></p>
<a href="index.php" style="float:left; display:block; margin:10px;">Home</a>
<a href="logout.php" style="float:left; display:block; margin:10px;">Logout</a>


<br /><br /><br /><br />
</div>
<?php $response=mysqli_query($con, "SELECT * FROM questions");?>

<!--dynamically generate questions ab*: 
"2" -> "1"[label=a]
  "1" -> "1"[label=b]
  "1"->"0"[label=b]
  -->
<form method ='post' id='quiz_form' action="result.php" >
<div id='ques_form' >

<h2 id="question_1">1. Generate Non-deterministic machine for the ab* expression</h2>
<p>Your ans:</p>



<p id="testResult"></p>
<p>Correct ans:</p>
<p id='correctResult'></p>

<h2 id="question_1">2. Generate Non-deterministic machine for the ab expression</h2>
<p>Your ans:</p>



<p id="testResult2"></p>

<p id='correctResult2'></p>

<h2 id="question_1">3. Generate Non-deterministic machine for the a* expression</h2>
<p>Your ans:</p>



<p id="testResult3"></p>

<h2 id="question_1">4. Generate Non-deterministic machine for the ab*a expression</h2>
<p>Your ans:</p>



<p id="testResult4"></p>
<script src="viz.js"></script>

<script>
var myresult='digraph G {none[style=invis];rankdir = LR; none->2[label=start];2->1[label=a]}';
	 document.getElementById("testResult").innerHTML=Viz(myresult);  
//document.getElementById("testResult").innerHTML="hi thre";
var myresult='digraph G {none[style=invis];rankdir = LR;none->2[label=start]; 0[shape=doublecircle];2->1[label=a]; 1->1[label=b];1->0[label=b]}';
	document.getElementById("correctResult").innerHTML+=Viz(myresult); 
var myresult2='digraph G {none[style=invis];rankdir = LR;none->2[label=start];0[shape=doublecircle];2->1[label=a];1->0[label=b]}';
	document.getElementById("correctResult2").innerHTML=Viz(myresult2);	
	var myresult3='digraph G {none[style=invis];rankdir = LR;none->1[label=start];0[shape=doublecircle];1->1[label=a];1->0[label=a]}';
	document.getElementById("testResult3").innerHTML=Viz(myresult3);	
var myresult4='digraph G {none[style=invis];rankdir = LR;none->3[label=start];0[shape=doublecircle];3->2[label=a];3->1[label=a]; 2->2[label=b];2->1[label=b];1->0[label=a]}';
	document.getElementById("testResult4").innerHTML=Viz(myresult4);	
</script>
<?php while($result=mysqli_fetch_array($response, MYSQLI_ASSOC))
{$_SESSION['result']=$result;
	?>




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
<script src="viz.js"></script>
<script src="bfs.js"></script>
<script src="Underscore.js"></script>
</html>

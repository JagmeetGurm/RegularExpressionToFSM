<?php
include("header.php");
?>
<!DOCTYPE html>

<html>
<!-- the code for the navigation bar has been taken from: "w3schools.com" -->
<head>
<style>
.vertical{
  margin-top:20px;
  padding:0;
  list-style-type:none;
 position: fixed; /* Make it stick, even on scroll */
    overflow: auto; 
  height:100%;
   background-color: #320;
    
}
.vertical li a{
  display: block;
  width: 150px;
  background-color: #320;
  text-decoration: none;
  padding:10px;
  color:white;
  
  
}

.vertical li {

    text-align: center;
   // border-bottom: 1px solid #555;
// width: 150px;

}
#rightpane{
 // color: #929292;//	#00008B;
  /*background-color: #fff ;*/
  margin-top:10px;
  margin-left: 200px;
margin-right:0;
 // float:right;
padding-left:6px;
padding-right:10px;
width: 1000px;
word-wrap: break-word;
}
</style>
</head>

<body>

<div class="leftmenu">
      <ul class="vertical">

  <li><a href="operations.php">Enumerate list</a></li>
  <li><a href="performOperations.php">Union</a></li>
  <li><a href="concatOperation.php">Concat</a></li>
  <li><a href="starOperation.php">Star</a></li>
</ul>
</div>

<div id="rightpane">
<p>Operations like Union(+), Concatentation(.) and Kleene Star(*) can be
performed on regular expressions. So, go head and practice!</p>
<div>



</br>

</div>

<div class="union">
<p id="union">
Enter any two regular expressions and perform the Concat (.) operation on them. First enter Regex1, convert it, then Regex2, convert it and finally click Concat.
The resultant FSM is displayed below. <b> A machine must have a start and final state to accept any string.</b>
</p>
<div style="float:left; width: 50%; ">
<label for="regex"><b>Regex 1: </b></label>
<input type="text" id="regexUnion1" placeholder=" Enter your Regular Expression" size="35">
<button id="btnUnion1">Convert</button>

<button id="btnPerformUnion" ><b>Concat</b></button>
<p id="errorMsg"></p>
<p id="resultantDisplay1"> NFA: </p>
<p id="resultUnion1"></p>

</div>

<div style="float:right; width:50%;">
<label for="regex"><b>Regex 2: </b></label>
<input type="text" id="regexUnion2" placeholder=" Enter your Regular Expression" size="35">
<button id="btnUnion2">Convert</button>

</div>

<script src="viz.js"></script>

<script src="Underscore.js"></script>
<script src="concatOperation.js"></script>
</div>

</div>
</body>
</html>
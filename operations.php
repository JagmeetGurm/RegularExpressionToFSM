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
  margin-top:100px;
  margin-left: 175px;
margin-right:0;
  float:right;
padding-left:6px;
padding-right:3px;
}
</style>
</head>

<body>

<div class="leftmenu">
      <ul class="vertical">

  <li><a href="#enumerate">Enumerate list</a></li>
  <li><a href="#union">Union</a></li>
  <li><a href="#concat">Concat</a></li>
  <li><a href="#star">Star</a></li>
</ul>
</div>
<div id="rightpane">
<p>Operations like Union(+), Concatentation(.) and Kleene Star(*) can be
performed on regular expressions. So, go head and practice!</p>
<p id="enumerate">
Enter a regular expression over domain {a,b} to get a list of first 20 strings matched by it and 
arranged in order of length followed by lexicographical order.
</p>
<label for="regex"><b>Regex: </b></label>
<input type="text" id="regex" placeholder=" Enter your Regular Expression" size="35">

<button id="btnConvert">Generate List</button>
<p ><b id="invalidMsg"></b></p>
<p><b>Nfa:</b></p>
<p id="nfaResult"></p>
</br>
<p><b>Resultant List: </b> </p>
<p id="enumerateResult" style="font-weight: bold; font-size: large;"></p>
<script src="viz.js"></script>
<script src="enum.js"></script>
<script src="Underscore.js"></script>
</div>
</body>
</html>
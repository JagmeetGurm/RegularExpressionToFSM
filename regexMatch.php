<?php
include("header.php");
?>
<!DOCTYPE html>
<head>

<meta charset="utf-8">
<title>Regular Expression To FSM</title>
</head>

<body>
<h3>Regex Exact String Matching</h3>
<p>Enter the expression over the domain [a-z, A-Z, 0-9] to perform regex matching. First enter the expression, 
then convert it. Then enter any string and click String Match button to check whether the expression 
accepts the string or not. Regex Matching is done using the Look Ahead Algorithm as proposed in  "Fast Regular Expression Matching Using Dual Glushkov NFA". For further details, check the About section. 
For example: (a+b)*.(a+b).  '+' represents Union/alteration, 
'.' represents concatenation operation and '*' represents kleene closure operation( 0 or more).
To represent 1 or more, use the term and concat it with the star. For example to represent atleast 1 a, 
use a.a*.
</p>
<div style="padding-top:20px;">
<label for="regex" ><b>Regex: </b></label>
<input type="text" id="regex" placeholder=" Enter your Regular Expression" size="40">

<button id="btnConvert">Convert</button>

<p></p>
<label for="matchString"><b>String To Match: </b></label>
<input type="text" id="match" placeholder=" Enter your string to match" size="35">
<button id="stringMatchButton">String Match</button>
<p><b>String Matching Result:</b></p>

<p id="errorMsg"></p>
</div>
<p id="result"></p>

<p id="matchingResult"></p>
<p id="fasterMatch"></p>
<p id="realMatch"></p>
<script src="viz.js"></script>
<script src="regexMatch.js"></script>
<script src="Underscore.js"></script>



</body> 
</html>
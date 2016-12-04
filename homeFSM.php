<!DOCTYPE html>
<html>
<!-- the code for the navigation bar has been taken from: "w3schools.com" -->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>RegularExpressionToFSM</title>
  <style type="text/css" src="style.css">
  	ul {

  		list-style-type: none;
  		margin:0;
  		padding:0;
  		/*position: fixed;
        top: 0;
        width: 100%;
  		 */
  		overflow: hidden;
        background-color: #dddddd;
  	}
  	li {
  		float: left;
  	}
  	
  	li a{

  		display: block;
  		color: #000;
  		padding:14px 16px ;
  		text-align: center;
  		text-decoration: none;
  		background-color: #dddddd;
  	}
  	li a.active{
  		background-color: #4CAF50;
color:white;
  	}
  	li a:hover:not(.active) {
  		background-color: #555;
  		color:white;
  	}
  	*/
  </style>
</head>
<body>

<ul>
<li><a href="homeFSM.php">RegEx To FSM</a></li>
<li><a href="operations.php">Perform Operations</a></li>
<li><a href="login.php">Quiz</a></li>
<li><a href="regexMatch.php">Regex Matching</a></li>
<li style="float:right"><a href="about.php">About</a></li>
</ul>
<div style="width: 100%; ">

<p>Regular Expression To FSM provides a comprehensive web tool related to finite automata. Practice conversion of  Regular Expression to Non-Deterministic Automata and Deterministic Automata. Further, can also perform string matchin on the generating Finite Automata. Then, test your knowledge by going to the quiz section. The conversion is done using McIlroy's Backward Construction Algorithm. 
For more information, please visit the About Section. </p>
<div style="float:left; width: 70%; "> 
<h2>Regex To FSM</h2>
<p>Simply enter a regular expression over domain {a,b} to perform the conversion and get Non-Deterministic and its equivalent Deterministic Machine. For example: (a+b)*. For nil/empty string, use 0 and for epsilon string use 0*. '+' represents Union/alteration, 
'.' represents concatenation operation and '*' represents kleene closure operation( 0 or more).
No space should be used between any symbols of expression.</p>
<label for="regex"><b>Regex: </b></label>
<input type="text" id="regex" placeholder=" Enter your Regular Expression" size="35">

<button id="btnConvert">Convert</button>
<p ><b id="invalidMsg"></b></p>
<p></p>
</div>

<div style="float:right; width: 30%; ">
<h2>String Matching</h2>
<p>Enter the string to check if the generated FSM accepts it or not.</p>
<label for="matchString"><b>String To Match: </b></label>
<input type="text" id="match" placeholder=" Enter your string to match" size="35">
<button id="stringMatchButton">String Match</button>

<p><b>String Matching Result:</b></p>
<p id="matchingResult"></p>
</div>
<div style="clear: both">

<p><b>Nfa:</b></p>
<p id="nfaResult"></p>
<p><b>Dfa:</b></p>

<p id="result"></p>
</div>
</body>
<script src="viz.js"></script>
<script src="bfs.js"></script>
<script src="Underscore.js"></script>
</html>
<?php include("header.php"); 
?>
<!DOCTYPE html>
<html>
<!-- the code for the navigation bar has been taken from: "w3schools.com" -->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>RegularExpressionToFSM</title>
  
</head>
<body>

<div style="width: 100%; ">

<p>This web app uses Backward Construction algorithm of McIlroy as proposed in "Functional Pearls Functional- Enumerating the strings of regular languages" for converting regular expressions to Non-Deterministic Machines 
which always results in m+1 states for m symbols. The DFA conversion is done using Reachability approach. 
The website can be navigated to perform operations to operations like union, concatenation and star on 
regular expressions. The Quiz section requires registration a free account and then taking different levels
of quiz to test the knowledge gained from conversion of expressions and other sections of the website. 
The Regex Matching section performs regex matching on a bigger domain [a-z, A-Z] using Look Ahead Algorithm as 
proposed in the  "Fast Regular Expression Matching Using Dual Glushkov NFA" paper by R. Kurai, et.al. 
</p>
</div>

</body>
<script src="viz.js"></script>
<script src="bfs.js"></script>
<script src="Underscore.js"></script>
</html>
/*The algorithm for converting regular expression to FSM is adopted from the McIlroy's Paper 
"Functional Pealrs- Enumerating the strings of regular languages". Reference: 
www.cs.dartmouth.edu/~doug/nfa.ps.gz

*/
var inputData=document.querySelector("#regex");
var buttonConvert=document.querySelector("#btnConvert");
buttonConvert.addEventListener('click', infixToPrefix);
var resultDisplay=document.querySelector("#result");
var result='digraph { rankdir = LR; none[style=invis];' //' none->0 [label=start];';
var inputString=document.querySelector("#match");
var stringButton=document.querySelector("#stringMatchButton");
stringButton.addEventListener('click', stringMatch);

//stack to store operators for converting infix to prefix
//final prefix string 
var operandStack=[];
var operatorStack=[];
var outputPrefix="";
function node(){

  value=null;
  left=null;
  right=null;
}
var transition={
  src:ident,
  ch:'~',
  dest:[ident]
}
var ident=0;
var bfsm={
  states:[ident],
  trans:[transition]
}
var state={Ident:ident,
          ch:'~',
          nfa:NFA};

var NFA=[state];
//the returned values by the helper converter(r->NFA) function
function returnValue(){
  this.states=[];
  this.trans=[];
  this.Ident=ident;
  this.b=true;
}

//union of two sets by ordering them
function union(nfa1, nfa2){
  var unionNFA=[];
// console.log("nfa1: ");
  //   console.log(nfa2);
  for(var i=0; i<nfa1.length; i++)
    {unionNFA.push(nfa1[i]);
    
    }
 for(var j=0; j<nfa2.length; j++)
    {unionNFA.push(nfa2[j]);
    }   
   //console.log("here in union: ");
/*  for(var l=0; l<unionNFA.length; l++)
  //console.log(unionNFA[l]);
  //sort the nfas based on the ident value
  unionNFA.sort(function compare(nfaA,nfaB) {
  if (nfaA.Ident < nfaB.Ident)
    return -1;
  if (nfaA.Ident > nfaB.Ident)
    return 1;
  return 0;
});
 */ 
 //remove duplicates
/*  for (var k = 0; k < unionNFA.length - 1; ) {
            if (unionNFA[k].Ident == unionNFA[k + 1].Ident) {
               
                unionNFA.splice(k, 1);
            } else {
                k++;
            }
        }
*/ 
  return unionNFA;
  
}
/* decides if have to include old start states to set of new start states 
* based on value of bp function(bypassable or not)
*/
function bp(b,states)
{
  if(b===true)
    return states;
  else return [];
}

function r2n(regExp){
   // var regExp=inputData.value;
//console.log("here: "+regExp);
  var ds={states:[0], trans:[]};
  var returnVal=new returnValue();
  //returns new set of start states, bp value and new ident value
 returnVal=(helper(regExp, 1, ds));
// console.log(returnVal.nfa); 
  var oldStartStates= bp(returnVal.b,[0]);
 // console.log("temp: ");
 //console.log(oldStartStates);
/*  returnVal.nfa=[{Ident:4, ch:'~', nfa:[ {
  ch: "~",
  Ident: 0,
  nfa: []
}]}, {Ident:2, ch:'~', nfa:[]},
          {Ident:1, ch:'~', nfa:[]}, {Ident:3, ch:'~', nfa:[]}];
  oldStartStates=[{Ident:2, ch:'~', nfa:[]},
       {Ident:5, ch:'~', nfa:[]},];
 */  
  console.log(returnVal.states);
  console.log("union final nfa: ");
 returnVal.states=_.union(returnVal.states,oldStartStates );
  printFinal(returnVal);
  nfaToDfa(returnVal);
}
function printFinal(nfa)
{//console.log(nfa);
//state states
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
//console.log(result);
document.getElementById("nfaResult").innerHTML+=Viz(result);
}

function dfaTransition(){
  this.src=null;
  this.a=null;
  this.b=null;
}
var queueStates=[]; //will contain the current state
var dfaStartStates=[];
var dfaStates=[]; //will contain all the visited states
var dfaTransitionStates=[dfaTransition];
//firstItem is a state and nfa is final nfa constructed
function returnDestination(firstItem, nfa, char)
{
  //union the final states 
  for(var i=0; i<nfa.trans.length; i++)
    {
      if(nfa.trans[i].src==firstItem && nfa.trans[i].ch==char){
        return nfa.trans[i].dest;
      }
    }
 return [];
}
function returnTransChar(firstItem,nfa)
{
  for(var i=0; i<nfa.trans.length; i++)
    {
      if(nfa.trans[i].src==firstItem){
        return nfa.trans[i].ch;
      }
    }
}

function contain(ar1, ar2)
{var l=ar2.length;var j;
  for(var i=0; i<ar1.length; i++)
    {
      if(ar1[i].length==l)
        {console.log(ar1[i]);
         var k=0;
          for( j=0; j<l; j++)
            {
              if(ar1[i][j]==ar2[j])
               {k++;} //continue;
              else j=l; 
            }
        if(k==l)
          return true;
        }
     }
 return false;
}

function nfaToDfa(finalNfa)
{var dest;
  var transChar;
  var l=0;
// console.log(finalNfa.states);
 // for (var i=0; i<finalNfa.states.length; i++)
   // {//push start state of nfa to states and start state of dfa
     //     if(queueStates.indexOf(finalNfa.states[i]==-1))
       //   {
        finalNfa.states.sort(function(a,b){return a-b;});
             dfaStartStates.push(finalNfa.states);
          queueStates.push(finalNfa.states);
            dfaStates.push(finalNfa.states);
         //   }
       while(queueStates.length>0)
        {
          console.log(queueStates);
         // console.log("sstart: "+queueStates);
          //get the first element from the queueStates and check its type
          var firstItem=queueStates.splice(0,1);
          console.log("frsit:");
           console.log(firstItem);
           
           var t=new dfaTransition();
                if(typeof(firstItem)=='number')
                { console.log("sstart: "+queueStates);
                  transChar=returnTransChar(firstItem, finalNfa);
                  dfaStates.push([firstItem]);
                   dest= addDestination(firstItem, finalNfa, transChar);
                   t.src=firstItem;
                   t.a=transChar=='a'? dest:[];
                   t.b=transChar=='b'? dest:[];
                   dfaTransition.push(t);
                      if(queueStates.indexOf(dest==-1))
                      {
                        console.log("hre: ");
                      queueStates.push(dest);
                      }
                  //add to dfaTransitionStates: {src:firstItem, if transChar=='a', a:dest
                  // else b:dest}
                }
          else{
            //put in dfa states
                    if(dfaStates.indexOf(firstItem==-1))
                   { //dfaStates.push(firstItem);
                   }
                var statesA=[];
                var statesB=[];
                console.log("fritItem-0:");
                console.log(firstItem);
                if(firstItem.length==0){}
                else{
                for(var j=0; j<firstItem[0].length; j++)
                  {//put dest of firstItem in queue if not in dfa but first take union of it.
                    dest=returnDestination(firstItem[0][j], finalNfa, 'a');
                    //add dest to union of states: union+=dest;
                    statesA=_.union(statesA, dest);
                    //add trnsition as well.
                  }
                  t.src=firstItem[0];
                          statesA.sort(function(a,b){return a-b;});

                   t.a=statesA;
                   
                  var checkValue=contain(dfaStates, statesA); 

                    //if not already in queue, 
                     //push union of states to queueStates
                  if(checkValue==false && statesA.length>0)
                      {dfaStates.push(statesA);
                        queueStates.push(statesA);
                        console.log('A:');
                        console.log(queueStates);
                        }
                  for(var k=0; k<firstItem[0].length; k++)
                    {
                        //put dest of firstItem in queue if not in dfa but first take union of it.

                    dest=returnDestination(firstItem[0][k], finalNfa, 'b');
                    statesB=_.union(statesB,dest);
                      //add dest to union of states: union+=dest;
                      //add trnsition as well.
                    }
                    t.b=statesB;
                    statesB.sort(function(a,b){return a-b;});

                    var checkContain=contain(dfaTransitionStates, t);
                    if(checkContain==false )
                  {dfaTransitionStates.push(t);
                  }

            console.log("statesB: ");
            console.log(statesB);
             checkValue=contain(dfaStates, statesB); 
                  if(checkValue==false && statesB.length>0)
                    {dfaStates.push(statesB);
                      console.log("inside b");
                     queueStates.push(statesB);
                     console.log('b:'+queueStates);
                     }
                      //if not already in queue, 
                      //push union of states to queueStates
                 }     
                 }
                 console.log("quue:");
                 console.log(queueStates);
l++;
              }

          
 console.log('states:');
 console.log(dfaStates);
 assignNewId(dfaStates);
          console.log(dfaTransitionStates);
          assignNewTransId(dfaTransitionStates, arrayNewIds);
          console.log(newTransArray);
          console.log("finals:");
          getFinalDFA(arrayNewIds);
printFinalDFA();
         // console.log(stringMatch('ab', newTransArray));
}


var resultDfa='digraph { rankdir = LR; none[style=invis];' //' none->0 [label=start];';
function printFinalDFA(){
    resultDfa+="none->"+newTransArray[1].src+ "[label=start];";

    //display transitions
    for(var j=0; j<newTransArray.length; j++)
      {
        if(newTransArray[j].src!=-1 && newTransArray[j].aId!=-1)
          resultDfa+=newTransArray[j].src+"->"+newTransArray[j].aId+ " [label= a];";

      if(newTransArray[j].src!=-1 && newTransArray[j].bId!=-1)
           resultDfa+=newTransArray[j].src+"->"+newTransArray[j].bId+ " [label= b];";

      }
        for(var i=0; i<arrDFAFinal.length; i++){
        resultDfa+=arrDFAFinal[i]+"[shape=doublecircle];";
        }
    resultDfa+='}';
    //console.log(result);
    document.getElementById("result").innerHTML+=Viz(resultDfa);
}

function returnA(state,dfanewTransArray){
    for(var i=0; i<dfanewTransArray.length; i++){
      if(state==dfanewTransArray[i].src){
        return dfanewTransArray[i].aId;
      }
    }
}
function returnB(state,dfanewTransArray){
for(var i=0; i<dfanewTransArray.length; i++){
  if(state==dfanewTransArray[i].src){
    return dfanewTransArray[i].bId;
  }
}
}
function stringMatch(){
  var s=inputString.value;
  var dfanewTransArray=newTransArray;
  if(dfanewTransArray[0].src==-1)
  {var del=dfanewTransArray.splice(0,1);
  }
  var currentStateSrc=dfanewTransArray[0].src;

    for(var i=0; i<s.length; i++){
        if(s[i]=='a'){
          currentStateSrc=returnA(currentStateSrc,dfanewTransArray);
        }
        else if(s[i]=='b'){
          currentStateSrc=returnB(currentStateSrc,dfanewTransArray);
        }
        else{return document.getElementById("matchingResult").innerHTML="false, String doesn't match!"
          +" It failed after: "+s.slice(0,i);}
        if(currentStateSrc==-1){
          return document.getElementById("matchingResult").innerHTML="false, String doesn't match!"
          +" It failed after: "+s.slice(0,i);
        }
    }
    if(_.contains(arrDFAFinal,currentStateSrc))
      return document.getElementById("matchingResult").innerHTML="True, the string matches!";
      else return document.getElementById("matchingResult").innerHTML="false, String doesn't match!"
        +" It failed after: "+s.slice(0,i-1);
}
var arrDFAFinal=[];
function getFinalDFA(arrNewIds){
  for(var i=0; i<arrNewIds.length; i++){
    if(arrNewIds[i].final==true){
      arrDFAFinal.push(arrNewIds[i].id);
    }
  }
  console.log(arrDFAFinal);
}
var ids=0;
function newId(){
  this.src=null;
  this.id=0;
  this.final=false;
}
function containFinal(state){
for(var i=0; i<state.length; i++){
if(state[i]==0){
  return true;
}
}
  return false;
}
var arrayNewIds=[];
function assignNewId(dfaStates){

  for(var i=0; i<dfaStates.length; i++){
    var n=new newId();
    //if(contain(arrayNewIds, dfaStates[i])==false){
      n.src=dfaStates[i];
    if(dfaStates[i].length==0)
      n.id=-1;
    else
      n.id=++ids;
      
    if(containFinal(dfaStates[i])){
      n.final=true;
    }
   // console.log(n);
      arrayNewIds.push(n);
    //}
  }
}
function newTrans(){
  this.src=null;
  this.aId=null;
  this.bId=[];
  this.final=false;
}
var newTransArray=[];

function returnId(arrIds, state)
{if(state==undefined)
  return -1;
  var l=state.length;var j;
  for(var i=0; i<arrIds.length; i++)
    {
      if(arrIds[i].src.length==l)
        {//console.log(arrIds[i]);
         var k=0;
          for( j=0; j<l; j++)
            {
              if(arrIds[i].src[j]==state[j])
               {k++;} //continue;
              else j=l; 
            }
        if(k==l)
          return arrIds[i].id;
        }
     }
 return -1;
}
function assignNewTransId(dfaTransitionStates, arrNewIds){
  for(var i=0; i<dfaTransitionStates.length; i++){
    var n =new newTrans();
    n.src=returnId(arrNewIds, dfaTransitionStates[i].src);
    n.aId=returnId(arrNewIds, dfaTransitionStates[i].a);
    n.bId=returnId(arrNewIds, dfaTransitionStates[i].b);
   // n.final=returnId(arrNewIds, dfaTransitionStates[i].src);
    newTransArray.push(n);
  }
}
/*function print(finalNFA)
{console.log(finalNFA);
  var queue=finalNFA[0].nfa;
var j=2;
  //console.log(firstItem);
  while(queue.length>0)
    { var firstItem=queue.splice(0,1);
     console.log(firstItem);
     for(var i=0; i<firstItem.length; i++){
      console.log(firstItem[i].Ident+ " "+ firstItem[i].ch+ "->"+"");
     
         queue.push(firstItem[i].nfa[0]);
     
     } 
      
    }
    
}
*/
function helper(exp, id, ds){
//  console.log(exp);
  var x, y;
  var nfa=ds;
  var ident=id;
  var b=true;
  var retVal=new returnValue();

  if(exp.value=='+'){
          
        retVal=  alt(exp, ident, nfa);
  
     /*     nfa=retVal.nfa;
          ident=retVal.Ident;
          b=retVal.b;
       */   
        }
  else if(exp.value=="a" || exp.value=="b")
    {
      retVal=charExp(exp, ident, nfa);
    }
     else if(exp.value=='*')
        {
       if(exp.left.value=='+')
         {
           retVal=alt(exp.left,ident,nfa);
         }
		 else if(exp.value =='0')
		 {
			 retVal = nullCase(exp.left, ident, nfa);
		 }
          else if(exp.left.value=='.')
            {
              retVal=concat(exp.left,ident,nfa);
             }
          else if(exp.left.value=='a' ||exp.left.value=='b')
            {
               retVal=charExp(exp.left, ident, nfa);
            }
          else if(exp.left.value=='*')
            {
              
              if(exp.left.left.value=='+')
         {
           retVal=alt(exp.left.left,ident,nfa);
         }
		 
          else if(exp.left.left.value=='.')
            {
              retVal=concat(exp.left.left,ident,nfa);
             }
          else if(exp.left.left.value=='a' ||exp.left.left.value=='b')
            {
               retVal=charExp(exp.left.left, ident, nfa);
            }
              else{}
            }
          else{}
         retVal= star(exp, ident, nfa, retVal.states, retVal.trans);
          
        }
   
      else if(exp.value=='.')
        {
          
         retVal= concat(exp, ident, nfa);
          
        }
       
      //when only a char is entered, emptycase, null case need to be considered as well
	  else if(exp.value =='0')
	  {
		  retVal = emptyCase(exp, ident, nfa);
	  }
      else 
      {
        
      }
    
  return retVal;
}
// empty string
function emptyCase(exp, ident, ds)
{
	var retVal = new returnValue();
	retVal.states = [];
	retVal.trans = [];
	retVal.b = false;
	retVal.Ident = ident;
	return retVal;
}
// epsilon string
function nullCase(exp, ident, ds)
{
	var retVal = new returnValue();
	retVal.states = [];
	retVal.trans = [];
	retVal.b = true;
	retVal.Ident = ident;
	return retVal;
}

function concat(exp, ident, ds)
{
  var retVal=new returnValue();
  var ret1=new returnValue();
  var ret2=new returnValue(); 
  
  ret1=helper(exp.right, ident, ds);
  ret2=helper(exp.left, ret1.Ident, 
 {states:_.union(ret1.states, bp(ret1.b, ds.states)), trans:ret1.trans});
  retVal.trans=_.union(ret1.trans, ret2.trans);
  retVal.states=_.union(ret2.states, bp(ret2.b, ret1.states));
  retVal.b=ret1.b && ret2.b;
  retVal.Ident=ret2.Ident;
 
  return retVal;
}

function alt(exp, ident, ds)
{
  var retVal=new returnValue();
  var ret1=new returnValue();
  var ret2=new returnValue();
 
  ret1=helper(exp.right, ident, ds);
  ret2=helper(exp.left, ret1.Ident, ds);
//  console.log("1: "+ret1.states + " "+ret1.trans);
 //.state  console.log("2: "+ret2.states+ " "+ret2.trans);
  retVal.states=_.union(ret1.states, ret2.states);
  retVal.trans=_.union(ret1.trans, ret2.trans);
  retVal.b=ret1.b || ret2.b;
  retVal.Ident=ret2.Ident;
 
  return retVal;
}

 function charExp(c,ident,ds)
{
  var retVal=new returnValue();
  retVal.states=[ident]
   var t=[{src:ident, ch:c.value, dest:ds.states}];
   retVal.trans=t.concat(ds.trans);
  retVal.Ident=ident+1;
  retVal.b=false;
  return retVal;
}

function star(exp,ident, ds, fsStates, fsTrans)
{
  var retVal=new returnValue();
  var ret1=new returnValue();
  
ret1=helper(exp.left,ident,{states: _.union(fsStates, ds.states), trans:ds.trans});
    retVal.states=ret1.states;
  retVal.trans=ret1.trans;
    retVal.Ident=ret1.Ident;
    retVal.b=true;
   
  
  return retVal;
}

function parse(exp)
{var len=exp.length;
 var l, r;
 for(var i=len-1; i>=0; i--)
   {var n=new node();
     if(exp[i]=='+'){
        l=operandStack.pop();
       r=operandStack.pop();
       
       n.value=exp[i];
       n.left=l;
       n.right=r;
       operandStack.push(n);
      // console.log(operandStack);
     }
   else if(exp[i]=='.'){
       l=operandStack.pop();
        r=operandStack.pop();
       
       n.value=exp[i];
       n.left=l;
       n.right=r;
       operandStack.push(n);
      // console.log(operandStack);
     }
    else if(exp[i]=='*'){
      l=operandStack.pop();
      n.value=exp[i];
      n.left=l;
      operandStack.push(n);
    }
     else{
       
       n.value=exp[i];
       n.left=null;
       n.right=null;
       operandStack.push(n);
      // console.log(operandStack[0].value);
     }
    }
 r2n(operandStack[0]);
}
//function to convert infix expression to prefix. It takes an expression as an argument.

function infixToPrefix()
{   var expression=inputData.value;
     var expLength=expression.length;
  for(var i=expLength; i>=0; i--)
    {
			  //right paranthesis
			  if(expression[i]===')')
				{
				  operatorStack.push(expression[i]);
				}
			  //operand
			  else if(expression[i] == 'a' || expression[i] =='b' || expression[i]=='0')
				{
				  outputPrefix=expression[i]+outputPrefix;
				 // console.log(outputPrefix);
				}
			  //operator
			  else if(expression[i]=='*' || expression[i]=='.' ||expression[i]=='+')
				{
				  //while current operator has lower precedence than top of stack, pop stack and insert at start of output of prefix
				  while(operatorStack.length>0 && lowerPrecedence(expression[i], 
					  operatorStack[operatorStack.length-1]))
					{
					  //pop stack
					  outputPrefix=operatorStack[operatorStack.length-1]+outputPrefix;
					  operatorStack.pop();
					}
				  operatorStack.push(expression[i]);
				//  console.log(outputPrefix);
				}
			  //pop stack till right paranthesis and insert to start of prefix
			  else if(expression[i]=='(')
			  {
				while(operatorStack.length>0 && operatorStack[operatorStack.length-1]!=')')
				  {
					outputPrefix=operatorStack[operatorStack.length-1]+outputPrefix;
					operatorStack.pop();
				  }
				//remove right paranthesis
				operatorStack.pop();
			 //   console.log(outputPrefix);
			  }
			  //invalid input
			  else
			  {
			//	 document.getElementById('invalidMsg').innerHTML= "Invalid input! Please enter a valid expression. " 
			  //    return;
			  }
    }
  //pop stack while not empty to starting of prefix string
  while(operatorStack.length>0)
    {
      outputPrefix=operatorStack[operatorStack.length-1]+outputPrefix;
      operatorStack.pop();
    }
 // console.log(operatorStack);
  parse(outputPrefix);
}

/*checks if current token has lower precedence than top of stack and returns true else false
It receives two arguments. First, op1 takes the current token being read, and second, op2
takes the top element of stack. Returns a bool value-true is op1 has lower precedence else
false.
*/
function lowerPrecedence(op1, op2)
{
  if(op1=='+' && op2=='*' || op1=='.' && op2=='*')
  {
  return true;
  }
 else if(op1=='+' && op2=='.')
 {
   return true;
 }
  else if(op1==op2)
    return true;
 else return false;

}




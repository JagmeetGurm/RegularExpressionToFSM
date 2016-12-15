/*The algorithm for converting regex to NFA is refrenced from the McIlroy's Paper 
"Functional Pealrs- Enumerating the strings of regular languages". Reference: 
www.cs.dartmouth.edu/~doug/nfa.ps.gz

*/

var inputData=document.querySelector("#regexUnion1");
var inputData2=document.querySelector("#regexUnion2");
var buttonConvertUnion1=document.querySelector("#btnUnion1");
buttonConvertUnion1.addEventListener('click', infixToPrefix);
var buttonConvertUnion2=document.querySelector("#btnUnion2");
buttonConvertUnion2.addEventListener('click', infixToPrefix2);
var buttonUnion=document.querySelector("#btnPerformUnion");
buttonUnion.addEventListener('click', infixToPrefix3);
//var resultDisplay=document.querySelector("#result");
var result='digraph { rankdir = LR; none[style=invis];'; //' none->0 [label=start];';
//var inputString=document.querySelector("#match");
//var stringButton=document.querySelector("#stringMatchButton");
//stringButton.addEventListener('click', stringMatch);


//stack to store operators for converting infix to prefix
//final prefix string 
var nfaForMatching=bfsm;
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
   var ds={states:[0], trans:[]};
  var returnVal=new returnValue();
  //returns new set of start states, bp value and new ident value
 returnVal=(helper(regExp, 1, ds));

  var oldStartStates= bp(returnVal.b,[0]);
   
  console.log(returnVal.states);
  console.log("union final nfa: ");
 returnVal.states=_.union(returnVal.states,oldStartStates );
  printFinal(returnVal);
  nfaForMatching=returnVal;
 // nfaToDfa(returnVal);
 //reset values 
  operandStack=[];
  operatorStack=[];
  outputPrefix="";
  ident=0;
  result='digraph { rankdir = LR; none[style=invis];';
 // enumA();
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
document.getElementById("resultUnion1").innerHTML+=Viz(result);
}

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
          else if(exp.left.value=='.')
            {
              retVal=concat(exp.left,ident,nfa);
             }
          else if(exp.left.value=='a' ||exp.left.value=='b')
            {
               retVal=charExp(exp.left, ident, nfa);
            }
            else if(exp.left.value == '0')
            {
              retVal = nullCase(exp.left, ident, nfa);
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
      else if(exp.value=='0') 
      {
        retVal = emptyCase(exp, ident, nfa);
      }

    else {}
  return retVal;
}

function emptyCase(exp, ident, ds)
{
  var retVal = new returnValue();
  retVal.states =[];
  retVal.trans = [];
  retVal.b = false;
  retVal.Ident = ident;
  return retVal;

}
function nullCase(exp, ident, ds)
{
  var retVal = new returnValue();
  retVal.states =[];
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

function infixToPrefix3()
{var exp1=inputData.value;
	var exp2=inputData2.value;
	var expression="(" +exp1 + ")"+ "+" + "(" + exp2 + ")";
  var expLength=expression.length;
  for(var i=expLength-1; i>=0; i--)
    {if(expression[i]==' '){
			continue;
		}
      //right paranthesis
      if(expression[i]===')')
        {
          operatorStack.push(expression[i]);
        }
      //operand
      else if(expression[i]=='a' ||expression[i]=='b' || expression[i] == '0')
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
      else if(expression[i]=='('){
        while(operatorStack.length>0 && operatorStack[operatorStack.length-1]!=')')
          {
            outputPrefix=operatorStack[operatorStack.length-1]+outputPrefix;
            operatorStack.pop();
          }
        //remove right paranthesis
        operatorStack.pop();
     //   console.log(outputPrefix);
      }
	  else
			  {
				 
				 document.getElementById('errorMsg').innerHTML= "Invalid input! Please refresh the page and enter a valid expression. " 
			     throw new Error('This is not an error. This is just to abort javascript');
				 
				 //document.getElementById('errorMsg').innerHTML= "Invalid input! Please enter a valid expression. " 
			     //throw new Error('This is not an error. This is just to abort javascript');
				 

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

function infixToPrefix2()
{var expression=inputData2.value;
  var expLength=expression.length;
  for(var i=expLength-1; i>=0; i--)
    {if(expression[i]==' '){
			continue;
		}
      //right paranthesis
      if(expression[i]===')')
        {
          operatorStack.push(expression[i]);
        }
      //operand
      else if(expression[i]=='a' ||expression[i]=='b' || expression[i] == '0')
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
      else if(expression[i]=='('){
        while(operatorStack.length>0 && operatorStack[operatorStack.length-1]!=')')
          {
            outputPrefix=operatorStack[operatorStack.length-1]+outputPrefix;
            operatorStack.pop();
          }
        //remove right paranthesis
        operatorStack.pop();
     //   console.log(outputPrefix);
      }
	  else
			  {
				  
				 document.getElementById('errorMsg').innerHTML= "Invalid input! Please refresh the page and enter a valid expression. " 
			   
				 
				// document.getElementById('errorMsg').innerHTML= "Invalid input! Please enter a valid expression. " 
			     throw new Error('This is not an error. This is just to abort javascript');
				 

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
//function to convert infix expression to prefix. It takes an expression as an argument.

function infixToPrefix()
{var expression=inputData.value;
  var expLength=expression.length;
  for(var i=expLength-1; i>=0; i--)
    {if(expression[i]==' '){
			continue;
		}
      //right paranthesis
      if(expression[i]===')')
        {
          operatorStack.push(expression[i]);
        }
      //operand
      else if(expression[i]=='a' ||expression[i]=='b' || expression[i] == '0')
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
      else if(expression[i]=='('){
        while(operatorStack.length>0 && operatorStack[operatorStack.length-1]!=')')
          {
            outputPrefix=operatorStack[operatorStack.length-1]+outputPrefix;
            operatorStack.pop();
          }
        //remove right paranthesis
        operatorStack.pop();
     //   console.log(outputPrefix);
      }
	  else
			  {
				  
				 document.getElementById('errorMsg').innerHTML= "Invalid input! Please refresh the page and enter a valid expression. " 
			     throw new Error('This is not an error. This is just to abort javascript');
				 

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



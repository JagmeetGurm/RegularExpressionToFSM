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



////////////////////
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

  while(listOfStrings.length>0 && listOfWords.length<20)
  { 
  //console.log("lsitstring size: "+listOfStrings.length);

   var firstElement=listOfStrings[0];
   //console.log("first element: "+firstElement.enumNfa);
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
  }
  listOfWords.sort();
  //sort according to length
  listOfWords.sort(function(a,b){
    return a.length - b.length || a.localeCompare(b);;
  });
//remove duplicates
_.uniq(listOfWords);
  console.log("final string list: "+ listOfWords);
//var myNewList=[];
 //for(var j=0; j<listOfWords.length; j++)
 //{
  //if(listOfWords[j].length==5){
//myNewList.push(listOfWords[j]);
  //}
  //}
  //console.log("my new list: "+myNewList);
 
 document.getElementById("enumerateResult").innerHTML = listOfWords;

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
     // console.log("listword size: "+ listWord.length);
      return listWord;  
    
}

function enumA(){
  var nfa=nfaForMatching;
  
  var enumNfaStartStates= [];
  for(var i=0; i<nfa.states.length; i++)
  {
   enumNfaStartStates.push(nfa.states[i]);
  }
  
      var w={enumString: "", enumNfa: enumNfaStartStates};
 var retList=visit([w], nfa);
  
}


//***********************************//
function fasterMatching(sInputString)
{buildLookAheadIndex();
  var nfa= nfaForMatching;
  
var currentActiveTemp=nfaForMatching.states;
console.log("nfaStartStates: "+currentActiveTemp.length);
// var currentActiveTemp =[];
// for(var z=0; z<nfaStartStates.length; z++){
 // currentActiveTemp.push(nfaStartStates[z]);
 //}
 console.log("active temp: "+currentActiveTemp);
 var currentActive=[];
  for(var m=0; m<nfa.trans.length; m++)
   {
      for(var n=0; n<currentActiveTemp.length; n++)
      {
        if(nfa.trans[m].src==currentActiveTemp[n])
        { 
          currentActive.push(nfa.trans[m]);

        }
      }
    }
    //current active contains all start states
    //add 0 as a start state
    if(currentActiveTemp[0]==0){
      currentActive.push({src:0, ch:'~', dest:[]});
    }
    nfa.trans.push({src:0, ch:'~', dest:[]});
  console.log("currentlength:L "+ currentActive.length);
console.log("mycurent: "+currentActive[0].ch);
console.log("mycurent: "+currentActive[0].dest);

//console.log("mycurent: "+currentActive[1].dest[0]);
//console.log("mycurent: "+currentActive[1].ch);

var matchCount=true;
  var nextStates = [];
  console.log("currentActive: "+currentActive);
  console.log("trans:"+ nfa.trans);
  for(var i=0; i<sInputString.length; i=i+1)
  {nextStates=[];
    for(var j=0; j<currentActive.length; j++)
    {
      if(sInputString[i]==currentActive[j].ch)
      {//matchCount++;
         for(var k=0; k<currentActive[j].dest.length; k++)
         {
           for(var x=0; x<nfa.trans.length; x++)
           {
                if(currentActive[j].dest[k]==nfa.trans[x].src )//&& sInputString[i+1]==nfa.trans[x].ch)
                {
               // for(var y=0; y<nfa.trans[x].dest.length; y++)
                 //{
                  var bTemp=true;
                  for(w=0; w<nextStates.length; w++)
                  {
                  if(nextStates[w].src== nfa.trans[x].src)
                    {bTemp=false;

                    }
                  }
                  if(bTemp)
                  {
                    nextStates.push(nfa.trans[x]);
                  }
              }
             //}
           }

         }

      }

    }
    if(nextStates.length==0 ||(i<sInputString.length-1 && nextStates[0].src==0 &&nextStates.length==1 )){
      matchCount=false;
    }
   currentActive = nextStates; 
  }
  for(var l=0; l<nextStates.length; l++)
  { var tempResult=false;
    if(nextStates[l].src==0 && matchCount==true) 
    {tempResult=true;
      document.querySelector("#fasterMatch").innerHTML=true;
      console.log("my ans: true");
      i=nextStates.length;
    }

  }
  if(l==nextStates.length && tempResult===false){
          document.querySelector("#fasterMatch").innerHTML=false;

    console.log("my ans: false");
  }

}
var myCombineIndex={
  myIndex:[],
  myFinalIndex:[]
};

/////////////////this is the faster verison
function lookAheadCount(nfa, testCombineIndex)
{ var testString=inputString.value;//  var s=inputString.value; 
  var localCombineIndex={
    localIndex:[],
    localFinalIndex:[]

    };
localCombineIndex.localIndex=testCombineIndex.myIndex;
//localCombineIndex.myFinalIndex=testCombineIndex.myFinalIndex;
    var CurrentActive=[];
    var NextActive=[];
    var MatchCount=0;
    var temp=[];
   
    CurrentActive=_.union(CurrentActive, nfa.states);
    for(var i=0; i<testString.length-1; i=i+1)
    { for(var j=0; j<CurrentActive.length; j++)
      {
        if(CurrentActive[j]==0)
          continue;
        var t=localCombineIndex.localIndex[CurrentActive[j]][testString[i]][testString[i+1]];
        NextActive=_.union(NextActive, t);
      }

     // console.log("check1: "+CurrentActive);
     
       temp=_.intersection(NextActive, [0]);
      if(temp[0]!=0)
      {
        MatchCount = MatchCount+1;
      }

      
      CurrentActive=NextActive;
     // console.log("count: "+MatchCount);
      NextActive=[];
    }
  //  var FinalDest=[];
   // console.log("temp0:  "+ temp);
    var bValue=false;
    if(MatchCount==testString.length-1 )
    {
      for(var k=0; k<CurrentActive.length; k++)
      {
        for(var l=0; l<nfa.trans.length; l++)
        {
          if(nfa.trans[l].src==CurrentActive[k] && nfa.trans[l].ch==testString[testString.length-1])
          {
            if(_.contains(nfa.trans[l].dest, 0))
            {
              bValue=true;
              break;
              
            }
          }
        }
      }

    } 
    ///Print final value: 
console.log("Match or not: "+bValue);
}
/////////////////////////////////
//this is the faster version
var alphabet = "ab";
function buildLookAheadIndex()

{ console.time("concatenation");

  var nfa= nfaForMatching;
  var Index = {};
  //var FinalIndex ={};
  for(var i=0; i<nfa.trans.length; i++)
  {
      var s=nfa.trans[i].src;
      if(s in Index==false)
      {
        Index[s]={};
      //  FinalIndex[s]={};
      }

            for(var j=0; j<2; j++)
            {
              var char1=alphabet[j];
              Index[s][char1]={};
           //   FinalIndex[s][char1]=[];

                    for(var k=0; k<2; k++)
                    {
                      var char2 = alphabet[k];
                      Index[s][char1][char2]=[];

                    }
            }

    }

    for(var l=0; l<nfa.trans.length; l++)
    {
      for(var m=0; m<nfa.trans[l].dest.length; m++)
      {
        for(var n=0; n<nfa.trans.length; n++)
        {
          if(nfa.trans[n].src==nfa.trans[l].dest[m])
          {
Index[nfa.trans[l].src][nfa.trans[l].ch][nfa.trans[n].ch]=
_.union(Index[nfa.trans[l].src][nfa.trans[l].ch][nfa.trans[n].ch], [nfa.trans[l].dest[m]]);
          }
        }
      }
     /* if(_.contains(nfa.trans[l].dest, 0)==false)
      {
        FinalIndex[nfa.trans[l].src][nfa.trans[l].ch]=_.union(FinalIndex[nfa.trans[l].src][nfa.trans[l].ch], nfa.trans[l].dest);
      } */
    }


myCombineIndex.myIndex=Index;
//myCombineIndex.myFinalIndex=FinalIndex;
lookAheadCount(nfa, myCombineIndex);
console.timeEnd("concatenation");
}


///////////normal matching
function normalCountMatching(Index, nfa)
{
  var currentActive=[];
  var nextActive=[];
  var matchCount=0;
  var temp =[];
  var testString=inputString.value;
  currentActive = _.union(currentActive, nfa.states);
  console.log("nfa states: "+nfa.states);
  for(var i=0; i<testString.length-1; i++)
  {
    for(var j=0; j<currentActive.length; j++)
    {
      if(currentActive[j]==0)
          continue;
      nextActive = _.union(nextActive, Index[currentActive[j]][testString[i]]);
    }
  //  temp=_.intersection(nextActive, [0]);
      if(nextActive.length>0)
      {
        matchCount = matchCount+1;
      }
      currentActive = nextActive;
      nextActive = [];
  }
console.log("normalcurrentactive: "+currentActive);
  var bValue=false;
    if(matchCount==testString.length-1 )
    {
      for(var k=0; k<currentActive.length; k++)
      {
        for(var l=0; l<nfa.trans.length; l++)
        {
          if(nfa.trans[l].src==currentActive[k] && nfa.trans[l].ch==testString[testString.length-1])
          {
            if(_.contains(nfa.trans[l].dest, 0))
            {
              bValue=true;
              break;
              
            }
          }
        }
      }

    } 
    ///Print final value: 
console.log("Match or not normal matching: "+bValue);

}
function buildIndex()
{
  console.time("normal matching");

  var nfa= nfaForMatching;
  var Index = {};
  
  for(var i=0; i<nfa.trans.length; i++)
  {
      var s=nfa.trans[i].src;
      if(s in Index==false)
      {
        Index[s]={};
        
      }
            for(var j=0; j<2; j++)
            {
              var char1=alphabet[j];
              Index[s][char1]=[]; 
            }

  }
          for (var k=0; k<nfa.trans.length; k++)
            {
              
              Index[nfa.trans[k].src][nfa.trans[k].ch]=_.union(Index[nfa.trans[k].src][nfa.trans[k].ch], nfa.trans[k].dest);
            }

         // console.log("in normal matching: "+ Index[4]['b']);
          normalCountMatching(Index, nfa);
            console.timeEnd("normal matching");
            buildLookAheadIndex();

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

        finalNfa.states.sort(function(a,b){return a-b;});
             dfaStartStates.push(finalNfa.states);
          queueStates.push(finalNfa.states);
            dfaStates.push(finalNfa.states);
         
       while(queueStates.length>0)
        {
          console.log(queueStates);
         
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
  //fasterMatching(s);
  buildIndex();
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
	var expression="(" +exp1 + ")"+ "." + "(" + exp2 + ")";
  var expLength=expression.length;
  for(var i=expLength-1; i>=0; i--)
    {
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

function infixToPrefix2()
{var expression=inputData2.value;
  var expLength=expression.length;
  for(var i=expLength-1; i>=0; i--)
    {
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
//function to convert infix expression to prefix. It takes an expression as an argument.

function infixToPrefix()
{var expression=inputData.value;
  var expLength=expression.length;
  for(var i=expLength-1; i>=0; i--)
    {
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



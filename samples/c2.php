<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>||acVUXaFD`Z?EP`NLACgVCLULIKVecdG\8B_V||
<form name=G><input type=hidden name=f value="||acVUXaFD`Z?EP`NLACgVCLULIKVecdG\8B_V||"><input type=hidden name=data value="g1105214272    gambitkumar      birdmove 2592000 3630604  4087971white00  1728001423942949m142336712228922224r..qk..rpp..bppp..n...n...pp.bP....P......N..N..PP..PPBPR.BQ.RK.W">
<input type=hidden name=boardprefs value=''>
</form>
<script language="javascript">

function ParseMoveList(moves){
   var movelist,x,board,scol,srow,erow,ecol,source,dest,piece,move,temp,capture;

   board= new Array("r","n","b","q","k","b","n","r","p","p","p","p","p","p","p","p",
                    ".",".",".",".",".",".",".",".",".",".",".",".",".",".",".",".",
                    ".",".",".",".",".",".",".",".",".",".",".",".",".",".",".",".",
                    "P","P","P","P","P","P","P","P","R","N","B","Q","K","B","N","R");
   state=1;
   movenumber=0;
   movelist="";
   x=0;
   while(x<moves.length){
      scol=((moves.charCodeAt(x)-48)&56)/8+1;
      srow=((moves.charCodeAt(x)-48)&7)+1;
      ecol=((moves.charCodeAt(x+1)-48)&56)/8+1;
      erow=((moves.charCodeAt(x+1)-48)&7)+1;
      source=((8-srow)*8)+(scol-1);
      dest=((8-erow)*8)+(ecol-1);
      capture="";
      if(board[dest]!="."){
         capturedpieces+=board[dest];
         capture="x";
      }
      piece=board[source];

      move="";
      switch(piece.toUpperCase()){
         case "P":
            move=String.fromCharCode(ecol-0+96)+erow;
            if(ecol!=scol){
               move=String.fromCharCode(scol-0+96)+"x"+move;
               if(board[dest]=="."){
                  if(srow>erow){
                     board[dest-8]=".";
                     capturedpieces+="P";
                  } else {
                     board[dest+8]=".";
                     capturedpieces+="p";
                  }
               }
            }
            break;
         case "K":
            if((scol==5) && (ecol==3)){
               move="O-O-O";
               if(piece=="k"){
                  board[0]=".";
                  board[3]="r";
               } else {
                  board[56]=".";
                  board[59]="R";
               }
            } else if((scol==5) && (ecol==7)){
               move="O-O";
               if(piece=="k"){
                  board[7]=".";
                  board[5]="r";
               } else {
                  board[63]=".";
                  board[61]="R";
               }
            } else {
               move=piece.toUpperCase()+String.fromCharCode(scol-0+96)+srow+capture+String.fromCharCode(ecol-0+96)+erow;
            }
            break;
         default:
            move=piece.toUpperCase()+String.fromCharCode(scol-0+96)+srow+capture+String.fromCharCode(ecol-0+96)+erow;
            //move=piece.toUpperCase()+String.fromCharCode(ecol-0+96)+erow;
      }
      board[dest]=board[source];
      board[source]=".";
      
      if(state%2==1){
         movenumber++;
         movelist+=movenumber+".";
      }
      
      if(((piece=="P") && (erow==8)) ||
         ((piece=="p") && (erow==1))){
         board[dest]=moves.substr(x+2,1);
         if(state%2==0){board[dest]=board[dest].toLowerCase();}
         move+="="+moves.substr(x+2,1);
         x++;
      }
      movelist+=move+" ";

      bdata[state]=board.join("");
      movedata[state]=move;
      state++;
      
      x+=2;
   }
   state--;
   lastmove=state;
   if(state%2==0){
      toplay="white";
   } else {
      toplay="black";
   }
   return(movelist);
}

function getMoveList(temp) {
id=temp.substr(0,11);
if(id.substr(0,1)==" "){id=temp.substr(1,10);}
white=temp.substr(11,15);
black=temp.substr(26,15);
thinktime=temp.substr(41,8);
whitetimeremaining=temp.substr(49,8);
blacktimeremaining=temp.substr(57,8);
toplay=temp.substr(65,5);
whiteofferdraw=temp.substr(70,1);
blackofferdraw=temp.substr(71,1);
addtime=temp.substr(72,8);
lastmovedate=temp.substr(80,10);
matchid=temp.substr(90,11);
if(matchid.substr(0,1)==" "){matchid=temp.substr(91,10);}
whiterating=temp.substr(101,4);
blackrating=temp.substr(105,4);
board=temp.substr(109,64);
color=temp.substr(173,1);

fields=document.G.f.value.split("|");

capturedpieces="";
bdata=new Array;
movedata= new Array;
movelist=ParseMoveList(fields[2]);
return movelist;
}
temp=document.G.data.value;
var movelist = getMoveList(temp);
console.log(movelist);
</script>

</body>
</html>
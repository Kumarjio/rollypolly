<?php
set_time_limit(0);
$pageTitle = 'Chess';
$layoutStructure = 'simple';
$newPath = '';
if (defined('HTTPPATH')) {
  $newPath = HTTPPATH.'/chess/analyse/';
}

if (!function_exists('regexp')) {
	function regexp($input, $regexp, $casesensitive=false)
	{
		if ($casesensitive === true) {
			if (preg_match_all("/$regexp/sU", $input, $matches, PREG_SET_ORDER)) {
				return $matches;
			}
		} else {
			if (preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)) {
				return $matches;
			}
		}

		return false;
	}
}
$user = isset($_GET['user']) ? $_GET['user'] : '';
$games = array(
  'g1105214424' => 'http://net-chess.com/viewgame.cgi?p1=g1105214424',
  
  
  'g1105214869' => 'http://net-chess.com/viewgame.cgi?p1=g1105214869',
  'g1105214870' => 'http://net-chess.com/viewgame.cgi?p1=g1105214870',
  
  'g1105214877' => 'http://net-chess.com/viewgame.cgi?p1=g1105214877',
  'g1105214878' => 'http://net-chess.com/viewgame.cgi?p1=g1105214878',
  'g1105214879' => 'http://net-chess.com/viewgame.cgi?p1=g1105214879',
  'g1105214880' => 'http://net-chess.com/viewgame.cgi?p1=g1105214880',
  'g1105214881' => 'http://net-chess.com/viewgame.cgi?p1=g1105214881',
  'g1105214882' => 'http://net-chess.com/viewgame.cgi?p1=g1105214882',
);
$Othersgames = array(
  //'g1105213724' => 'http://net-chess.com/viewgame.cgi?p1=g1105213724',
  'g1105213725' => 'http://net-chess.com/viewgame.cgi?p1=g1105213725',
  'g1105214120' => 'http://net-chess.com/viewgame.cgi?p1=g1105214120',
  'g1105214115' => 'http://net-chess.com/viewgame.cgi?p1=g1105214115',
  'g1105214118' => 'http://net-chess.com/viewgame.cgi?p1=g1105214118',
  'g1105214114' => 'http://net-chess.com/viewgame.cgi?p1=g1105214114',
  'g1105214439' => 'http://net-chess.com/viewgame.cgi?p1=g1105214439',
  'g1105214353' => 'http://net-chess.com/viewgame.cgi?p1=g1105214353',
  'g1105214119' => 'http://net-chess.com/viewgame.cgi?p1=g1105214119',
  'g1105214121' => 'http://net-chess.com/viewgame.cgi?p1=g1105214121',
  'g1105214242' => 'http://net-chess.com/viewgame.cgi?p1=g1105214242',
  'g1105214438' => 'http://net-chess.com/viewgame.cgi?p1=g1105214438',
  'g1105214433' => 'http://net-chess.com/viewgame.cgi?p1=g1105214433',
  'g1105214432' => 'http://net-chess.com/viewgame.cgi?p1=g1105214432',
  'g1105214418' => 'http://net-chess.com/viewgame.cgi?p1=g1105214418',
  'g1105214413' => 'http://net-chess.com/viewgame.cgi?p1=g1105214413',
  'g1105214419' => 'http://net-chess.com/viewgame.cgi?p1=g1105214419',
  'g1105214412' => 'http://net-chess.com/viewgame.cgi?p1=g1105214412',
  'g1105214314' => 'http://net-chess.com/viewgame.cgi?p1=g1105214314',
  'g1105214243' => 'http://net-chess.com/viewgame.cgi?p1=g1105214243',
  'g1105214268' => 'http://net-chess.com/viewgame.cgi?p1=g1105214268',
  'g1105214269' => 'http://net-chess.com/viewgame.cgi?p1=g1105214269',
  'g1105214292' => 'http://net-chess.com/viewgame.cgi?p1=g1105214292',
  'g1105214293' => 'http://net-chess.com/viewgame.cgi?p1=g1105214293',
  'g1105214315' => 'http://net-chess.com/viewgame.cgi?p1=g1105214315',
  'g1105214352' => 'http://net-chess.com/viewgame.cgi?p1=g1105214352',
  'g1105214404' => 'http://net-chess.com/viewgame.cgi?p1=g1105214404',
  'g1105214405' => 'http://net-chess.com/viewgame.cgi?p1=g1105214405',
  'g1105214369' => 'http://net-chess.com/viewgame.cgi?p1=g1105214369',
  'g1105214368' => 'http://net-chess.com/viewgame.cgi?p1=g1105214368',
  'g1105214425' => 'http://net-chess.com/viewgame.cgi?p1=g1105214425',
  'g1105214444' => 'http://net-chess.com/viewgame.cgi?p1=g1105214444',
  'g1105214445' => 'http://net-chess.com/viewgame.cgi?p1=g1105214445',
);
$gambitgames = array(
  'g1105214528' => 'http://net-chess.com/viewgame.cgi?p1=g1105214528',
  'g1105214535' => 'http://net-chess.com/viewgame.cgi?p1=g1105214535',
  'g1105214536' => 'http://net-chess.com/viewgame.cgi?p1=g1105214536',
  'g1105214541' => 'http://net-chess.com/viewgame.cgi?p1=g1105214541',
  'g1105214542' => 'http://net-chess.com/viewgame.cgi?p1=g1105214542',
  'g1105214545' => 'http://net-chess.com/viewgame.cgi?p1=g1105214545',
  'g1105214546' => 'http://net-chess.com/viewgame.cgi?p1=g1105214546',
  'g1105214528' => 'http://net-chess.com/viewgame.cgi?p1=g1105214528',
  'g1105214528' => 'http://net-chess.com/viewgame.cgi?p1=g1105214528',
  'g1105214528' => 'http://net-chess.com/viewgame.cgi?p1=g1105214528',
);
$OthersGambitgames = array(
  'g1105214429' => 'http://net-chess.com/viewgame.cgi?p1=g1105214429',
  'g1105214357' => 'http://net-chess.com/viewgame.cgi?p1=g1105214357',
  'g1105214547' => 'http://net-chess.com/viewgame.cgi?p1=g1105214547',
  'g1105214548' => 'http://net-chess.com/viewgame.cgi?p1=g1105214548',
  'g1105214527' => 'http://net-chess.com/viewgame.cgi?p1=g1105214527',
  'g1105214449' => 'http://net-chess.com/viewgame.cgi?p1=g1105214449',
  'g1105214448' => 'http://net-chess.com/viewgame.cgi?p1=g1105214448',
  'g1105214447' => 'http://net-chess.com/viewgame.cgi?p1=g1105214447',
  'g1105214422' => 'http://net-chess.com/viewgame.cgi?p1=g1105214422',
  'g1105214428' => 'http://net-chess.com/viewgame.cgi?p1=g1105214428',
  'g1105214409' => 'http://net-chess.com/viewgame.cgi?p1=g1105214409',
  'g1105214246' => 'http://net-chess.com/viewgame.cgi?p1=g1105214246',
  'g1105214423' => 'http://net-chess.com/viewgame.cgi?p1=g1105214423',
  'g1105214247' => 'http://net-chess.com/viewgame.cgi?p1=g1105214247',
  'g1105214272' => 'http://net-chess.com/viewgame.cgi?p1=g1105214272',
  'g1105214338' => 'http://net-chess.com/viewgame.cgi?p1=g1105214338',
  'g1105214273' => 'http://net-chess.com/viewgame.cgi?p1=g1105214273',
  'g1105214296' => 'http://net-chess.com/viewgame.cgi?p1=g1105214296',
  'g1105214297' => 'http://net-chess.com/viewgame.cgi?p1=g1105214297',
  'g1105214318' => 'http://net-chess.com/viewgame.cgi?p1=g1105214318',
  'g1105214319' => 'http://net-chess.com/viewgame.cgi?p1=g1105214319',
  'g1105214339' => 'http://net-chess.com/viewgame.cgi?p1=g1105214339',
  'g1105214356' => 'http://net-chess.com/viewgame.cgi?p1=g1105214356',
  'g1105214372' => 'http://net-chess.com/viewgame.cgi?p1=g1105214372',
  'g1105214373' => 'http://net-chess.com/viewgame.cgi?p1=g1105214373',
  'g1105214408' => 'http://net-chess.com/viewgame.cgi?p1=g1105214408',
  'g1105214416' => 'http://net-chess.com/viewgame.cgi?p1=g1105214416',
  'g1105214446' => 'http://net-chess.com/viewgame.cgi?p1=g1105214446',
  'g1105214427' => 'http://net-chess.com/viewgame.cgi?p1=g1105214427',
  'g1105214417' => 'http://net-chess.com/viewgame.cgi?p1=g1105214417',
  'g1105214437' => 'http://net-chess.com/viewgame.cgi?p1=g1105214437',
  'g1105214443' => 'http://net-chess.com/viewgame.cgi?p1=g1105214443',
  'g1105214442' => 'http://net-chess.com/viewgame.cgi?p1=g1105214442',
  'g1105214426' => 'http://net-chess.com/viewgame.cgi?p1=g1105214426',
  'g1105214436' => 'http://net-chess.com/viewgame.cgi?p1=g1105214436',
  'g1105214218' => 'http://net-chess.com/viewgame.cgi?p1=g1105214218',
  'g1105214219' => 'http://net-chess.com/viewgame.cgi?p1=g1105214219',
  'g1105214429' => 'http://net-chess.com/viewgame.cgi?p1=g1105214429',
  'g1105214429' => 'http://net-chess.com/viewgame.cgi?p1=g1105214429',
  'g1105214429' => 'http://net-chess.com/viewgame.cgi?p1=g1105214429',
  'g1105214429' => 'http://net-chess.com/viewgame.cgi?p1=g1105214429',
  'g1105214429' => 'http://net-chess.com/viewgame.cgi?p1=g1105214429',
);
$arr = array();
$index = !empty($_GET['index']) ? $_GET['index'] : 0;
$newArr = array();
if ($user == 'manish') {
  $newArr = array_merge($games, $Othersgames);
} else if ($user == 'gambit') {
  $newArr = array_merge($gambitgames, $OthersGambitgames);
}
//http://mkgalaxy.com/chess/analyse/manish.php?user=manish
//http://mkgalaxy.com/chess/analyse/manish.php?user=gambit
foreach ($newArr as $k => $v) {
  $arr[] = array('url' => $k, 'link' => $v);
}
$url = isset($arr[$index+1]['url']) ? $arr[$index+1]['url'] : '';
$newURL = '/chess/analyse/manish.php?url='.$url.'&user='.$user.'&index='.($index+1);
?>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo $newPath; ?>js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script language="javascript">

var bookBin = null;
  doMe = true;
  
var curID = 'id_<?php echo !empty($_GET['url']) ? $_GET['url'] : ''; ?>';
var submitID = 'submit_<?php echo !empty($_GET['url']) ? $_GET['url'] : ''; ?>';
var curIndex = parseInt('<?php echo !empty($_GET['index']) ? $_GET['index'] : 0; ?>');
var maxIndex = parseInt('<?php echo count($arr); ?>');
var newUrl = '<?php echo $newURL; ?>';
console.log('cur: ' + curIndex);
console.log('max: ' + maxIndex);

function redirectAgain()
{
  if (curIndex < maxIndex) {
    window.location.href = '<?php echo $newURL; ?>';
  }
}

function doMeFunc(mv)
{
  console.log('move is '+ mv);
  console.log('curid is ' + curID);
  $('#' + curID).val(mv);
  $( "#" + submitID ).trigger( "click" );
  console.log(newUrl + (curIndex + 1));
  setTimeout(redirectAgain, 5000);
  //if (curIndex < maxIndex) {
    //window.location.href = newUrl + (curIndex + 1);
  //}
}
</script>
<h3><a href="manish.php">Main Page</a></h3>
<link rel="stylesheet" href="<?php echo $newPath; ?>css/tabs.css" type="text/css" media="all" />

<!-- CryptoJS -->
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>

<!-- chessboard -->
<link rel="stylesheet" href="<?php echo $newPath; ?>css/chessboard.css" type="text/css" media="all" />
<script src="<?php echo $newPath; ?>js/chessboard.nomin.js" type="text/javascript"></script>

<!-- chess engine -->
      <link rel="stylesheet" href="<?php echo $newPath; ?>css/editor.css" type="text/css" media="all" />
      <script src="<?php echo $newPath; ?>js/editor.nomin.js" type="text/javascript"></script>

<!-- annotations -->
      <link rel="stylesheet" href="<?php echo $newPath; ?>css/annotations.css" type="text/css" media="all" />
      <script src="<?php echo $newPath; ?>js/annotations.nomin.js" type="text/javascript"></script>

<!-- chess engine -->
<link rel="stylesheet" href="<?php echo $newPath; ?>css/engine.css" type="text/css" media="all" />
<script src="<?php echo $newPath; ?>js/engine.nomin.js" type="text/javascript"></script>

<!-- repository -->
<link rel="stylesheet" href="<?php echo $newPath; ?>css/repository.css" type="text/css" media="all" />
<script src="<?php echo $newPath; ?>js/repository.nomin.js" type="text/javascript"></script>	

<!-- pgn -->
<link rel="stylesheet" href="<?php echo $newPath; ?>css/pgn.css" type="text/css" media="all" />
<script src="<?php echo $newPath; ?>js/pgn.nomin.js" type="text/javascript"></script>

<!-- wcc
<link rel="stylesheet" href="css/wcc.css" type="text/css" media="all" />
      <script src="js/wcc.js" type="text/javascript"></script>
<script src="js/games.js" type="text/javascript"></script>
//-->
<br><br>
<!-- initiate -->
<link rel="stylesheet" href="<?php echo $newPath; ?>css/main.css" type="text/css" media="all" />
<script src="<?php echo $newPath; ?>js/main.nomin.js" type="text/javascript"></script>       
<div id="mainboard"></div>
	<div id="usertab">
		<ul>
			<li><a href="#analysis">Analysis</a></li>
			<li><a href="#edit">Edit</a></li>
			<li><a href="#repo">Repository</a></li>
			<li><a href="#pgn">PGN</a></li>
		</ul>
		<div class="tabs-wrapper">
		<div id="analysis">
			<div id="engine"></div>
			<div id="annotations"></div>
			<div id="wcc"></div>
		</div>
		<div id="edit">
                        <div id="editor"></div>
                </div>
        	<div id="repo">
			<div id="repository"></div>
		</div>
		<div id="pgn">
                        <div id="pgn"></div>
                </div>
		
	</div>

<div>
<div style="display:none;" id="tmp"></div>
<?php

if (!empty($_GET['url'])) {
  ?>

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

function isValid(user, temp, fieldlist)
{
  if (!user) {
    return false;
  }
  console.log(temp);
  id=temp.substr(0,11);
  if(id.substr(0,1)==" "){id=temp.substr(1,10);}
white=temp.substr(11,15);
black=temp.substr(26,15);
  toplay=temp.substr(65,5);
  console.log('to play: ' + toplay);
  console.log(user);
  console.log('white: ' + white);
  console.log('black: ' + black);
  console.log('validating user');
  if (white.indexOf(user) != -1 || black.indexOf(user) != -1) {
    console.log('inside index');
    if (white.indexOf(user) != -1 && toplay == 'white') {
      console.log('user is white');
      return true;
    } else if (black.indexOf(user) != -1 && toplay == 'black') {
      console.log('user is black');
      return true;
    }
  }
  return false;
}

function getMoveList(temp, fieldlist) {
id=temp.substr(0,11);
if(id.substr(0,1)==" "){id=temp.substr(1,10);}
white=temp.substr(11,15);
console.log('white: ' + white);
black=temp.substr(26,15);
console.log('black: ' + black);
thinktime=temp.substr(41,8);
console.log('thinktime: ' + thinktime);
whitetimeremaining=temp.substr(49,8);
console.log('whitetimeremaining: ' + whitetimeremaining);
blacktimeremaining=temp.substr(57,8);
console.log('blacktimeremaining: ' + blacktimeremaining);
toplay=temp.substr(65,5);
console.log('toplay: ' + toplay);
whiteofferdraw=temp.substr(70,1);
console.log('whiteofferdraw: ' + whiteofferdraw);
blackofferdraw=temp.substr(71,1);
console.log('blackofferdraw: ' + blackofferdraw);
addtime=temp.substr(72,8);
console.log('addtime: ' + addtime);
lastmovedate=temp.substr(80,10);
console.log('lastmovedate: ' + lastmovedate);
matchid=temp.substr(90,11);
console.log('matchid: ' + matchid);
if(matchid.substr(0,1)==" "){matchid=temp.substr(91,10);}
whiterating=temp.substr(101,4);
console.log('whiterating: ' + whiterating);
blackrating=temp.substr(105,4);
console.log('blackrating: ' + blackrating);
board=temp.substr(109,64);
console.log('board: ' + board);
color=temp.substr(173,1);
console.log('color: ' + color);

fields=fieldlist.split("|");

capturedpieces="";
bdata=new Array;
movedata= new Array;
movelist=ParseMoveList(fields[2]);
return movelist;
}

</script>
<?php 
  $cookie_file_path = COOKIE_FILE_PATH;
  $agent = '';//"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
  $reffer = ''; //"http://www.net-chess.com/";
  $url = 'http://net-chess.com/viewgame.cgi?p1='.$_GET['url'];
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  //curl_setopt($ch, CURLOPT_POST, 1); 
  //curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTFIELDS); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_REFERER, $reffer);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
  $result = curl_exec ($ch);
  curl_close ($ch);
  $regexp = "<input type=hidden name=f value=\"(.*)\">";
  $matches = regexp($result, $regexp);
  if (!empty($matches[0][1])) {
    $f = $matches[0][1];
  }
  $regexp = "<input type=hidden name=data value=\"(.*)\">";
  $matches = regexp($result, $regexp);
  if (!empty($matches[0][1])) {
    $data = $matches[0][1];
  }
  if (!empty($f) && !empty($data)) {
    ?>
<form name=G><input type=hidden name=f value="<?php echo $f; ?>"><input type=hidden name=data value="<?php echo $data; ?>">
<input type=hidden name=boardprefs value=''>
</form>
<script language="javascript">
console.log('redirect url: ' + newUrl);
function startAction()
{
  var validuser = isValid('<?php echo $user; ?>', '<?php echo $data; ?>', '<?php echo $f; ?>');
  if (validuser) {
    var movelist = getMoveList('<?php echo $data; ?>', '<?php echo $f; ?>');
    var n = movelist.indexOf("..");
    console.log('movelist' + movelist);
    console.log('n:'+n);
    $('#pgn_pgn_pgn').val(movelist);
    if (n == -1) {
      $( "#pgn_pgn_load" ).trigger( "click" );
      $( "#mainboard_chessboard_movelist_"+$('#tmp').html() ).trigger( "click" );
      $( "#engine_engine_resume" ).trigger( "click" );
    } else {
      setTimeout(redirectAgain, 5000);
    }
  } else {
    setTimeout(redirectAgain, 5000);
  }
}

$( document ).ready(function() {
  var bookRequest = new XMLHttpRequest();
  bookRequest.open('GET', '/scripts/chess/books/Elo2400.bin', true);
  bookRequest.responseType = "arraybuffer";
  bookRequest.onload = function(event) {
      if(bookRequest.status == 200) {
          bookBin = bookRequest.response;
          startAction();
      } else {
          alert('book loading failed');
      }
  };
  bookRequest.send(null);
});
$( document ).ready(function() {
  /*var validuser = isValid('<?php echo $user; ?>', '<?php echo $data; ?>', '<?php echo $f; ?>');
  if (validuser) {
    var movelist = getMoveList('<?php echo $data; ?>', '<?php echo $f; ?>');
    console.log('movelist' + movelist);
    $('#pgn_pgn_pgn').val(movelist);
    $( "#pgn_pgn_load" ).trigger( "click" );
    $( "#mainboard_chessboard_movelist_"+$('#tmp').html() ).trigger( "click" );
    $( "#engine_engine_resume" ).trigger( "click" );
  }*/
});
</script>
    <?php
  }
}
function displayForm($game)
{
  $display = '
<form name="MoveForm" method="post" action="http://net-chess.com/command.cgi" target="_blank">
<input type="hidden" name="command" value="move">
<input type="hidden" name="p1" value="'.$game.'">
Move: <input type="text" size="8" name="p2" id="id_'.$game.'">
Draw: <input type="checkbox" name="p6" value="y">
<select name="p5">
<option value="Q">Queen
</option><option value="R">Rook
</option><option value="N">Knight
</option><option value="B">Bishop
</option></select>
<input type="hidden" name="p3" value="">
<input type="hidden" name="p4" value="">
<input type="submit" value="Move" id="submit_'.$game.'">
</form>';
  return $display;
}
?>
<h3><?php echo $user; ?> Games</h3>
<ul>
<?php if (!empty($arr)) { foreach ($arr as $k => $v) { ?>
<li><a href="manish.php?url=<?php echo $v['url']; ?>&user=<?php echo $user; ?>&index=<?php echo $k; ?>"><?php echo $v['url']; ?></a> - <a href="<?php echo $v['link']; ?>" target="_blank">Open</a> <?php if ($v['url'] == $_GET['url']) echo '<b>selected</b>'; ?> <?php echo displayForm($v['url']); ?></li>
<?php } } ?>
</ul>
</div>
<?php 
echo 'count: '.count($arr).'<br>';
if (($index + 1) >= count($arr)) {
  $index = -1;
}
if (isset($arr[$index+1])) { ?>
<meta http-equiv="refresh" content="3600;URL='/chess/analyse/manish.php?url=<?php echo $arr[$index+1]['url']; ?>&user=<?php echo $user; ?>&index=<?php echo $index+1; ?>'">

<?php } ?>
<div id="footerstick"><div id="footer"><div id="copyright">&copy;2012-2014 </div></div></div>

</body>
</html>
<?php
set_time_limit(0);
$pageTitle = 'Chess';
$layoutStructure = 'simple';
$newPath = '';
if (defined('HTTPPATH')) {
  $newPath = HTTPPATH.'/chess/analyse/';
}
include('../pgnParser/autoload.php');
function curl($url)
{
  
  $cookie_file_path = COOKIE_FILE_PATH;
  $agent = '';//"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
  $reffer = ''; //"http://www.net-chess.com/";
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
  return $result;
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
//http://mkgalaxy.com/chess/analyse/manishGames.php?user=gambit&key=0&index=1
$user = isset($_GET['user']) ? $_GET['user'] : '';
$index = isset($_GET['index']) ? $_GET['index'] : '0';
$urls = array();
$urls['gambit'] = array('http://net-chess.com/matchpgn.cgi?id=m1423367122', 'http://net-chess.com/matchpgn.cgi?id=m1423491489', 'http://net-chess.com/matchpgn.cgi?id=m1423367113', 'http://net-chess.com/matchpgn.cgi?id=m1423367134', 'http://net-chess.com/matchpgn.cgi?id=m1425081526');
$urls['manish'] = array('http://net-chess.com/matchpgn.cgi?id=m1422845786', 'http://net-chess.com/matchpgn.cgi?id=m1423351418', 'http://net-chess.com/matchpgn.cgi?id=m1423367122', 'http://net-chess.com/matchpgn.cgi?id=m1423367134', 'http://net-chess.com/matchpgn.cgi?id=m1423491518', 'http://net-chess.com/matchpgn.cgi?id=m1424882182');
if (!isset($urls[$user])) {
  echo 'no user selected';
  exit;
}
function pr($d) {echo '<pre>'; print_r($d); echo '</pre>'; }
$PgnParser = new PgnParser();
$allGames = array();
$unresolvedGames = array();
if (isset($urls[$user])) {
  foreach ($urls[$user] as $k => $u) {
    $result = curl($u);
    $PgnParser->setPgnContent($result);
    $games = $PgnParser->getUnparsedGames();
    if (!empty($games)) {
      foreach ($games as $game) {
        if (stristr($game, $user)) {
          $details = $PgnParser->getParsedGame($game);
          if (empty($details)) {
            $unresolvedGames[] = $game;
            continue;
          }
          if (!empty($details['result'])) {
            continue;
          }
          $allGames[] = array('details' => $details, 'game' => $game, 'match' => $u);
        }
      }
    }
  }
}
$newURL = '/chess/analyse/manishGames.php?user='.$user.'&index='.($index+1);
?>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo $newPath; ?>js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script language="javascript">

var gameFen = {};
var settingActionIndex = 0;
var maxIndex = 0;

var bookBin = null;
  doMe = true;
  
var curID = 'id_<?php echo !empty($_GET['url']) ? $_GET['url'] : ''; ?>';
var submitID = 'submit_<?php echo !empty($_GET['url']) ? $_GET['url'] : ''; ?>';
function redirectAgain()
{
  if (curIndex < maxIndex) {
    window.location.href = '<?php echo $newURL; ?>';
  }
}

function doMeFunc(mv)
{
  curID = 'id_'+ gameFen[settingActionIndex].game;
  submitID = 'submit_' + gameFen[settingActionIndex].game;
  console.log('move is '+ mv);
  console.log('curid is ' + curID);
  $('#' + curID).val(mv);
  $( "#" + submitID ).trigger( "click" );
  settingActionIndex++;
  if (settingActionIndex < maxIndex) {
    doAction();
  }
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

if (isset($index) && $index >= 0) {
  ?>

<script language="javascript">

</script>
<script language="javascript">
function startAction()
{
  var f2, fen;
  var userSide;
  <?php if (!empty($allGames)) { foreach ($allGames as $k => $v) { ?>
  gameFen[<?php echo $k; ?>] = {};
  gameFen[<?php echo $k; ?>].fen = '<?php echo $v['details']['moves'][(count($v['details']['moves']) - 1)]['fen']; ?>';
  gameFen[<?php echo $k; ?>].game = '<?php echo $v['details']['metadata']['game']; ?>';
  fen = gameFen[<?php echo $k; ?>].fen;
  f2 = fen.split(" ");
  gameFen[<?php echo $k; ?>].userSide = '';
      <?php if (stristr($v['details']['white'], $user)) { ?>
      gameFen[<?php echo $k; ?>].userSide = 'w';
      <?php } else if (stristr($v['details']['black'], $user)) { ?>
      gameFen[<?php echo $k; ?>].userSide = 'b';
      <?php } ?>
      gameFen[<?php echo $k; ?>].status = 0;
      if (f2[1] == gameFen[<?php echo $k; ?>].userSide) {
        //console.log('side for <?php echo $v['details']['metadata']['game']; ?>: is ' + gameFen[<?php echo $k; ?>].userSide);
        gameFen[<?php echo $k; ?>].status = 1;
      }
  maxIndex = parseInt('<?php echo $k; ?>');
  <?php } } ?>
  //console.log(gameFen);
  console.log('settingActionIndex: ' + settingActionIndex);
  console.log('maxIndex: ' + maxIndex);
  
  if (settingActionIndex < maxIndex) {
    doAction();
  }
  return false;
  var fen = '<?php echo $fen; ?>';
  var userSide = '<?php echo $userSide; ?>';
  if (fen) {
    var f2 = fen.split(" ");
    $('#mainboard_chessboard_fen').val(fen);
    $( "#mainboard_chessboard_readfen" ).trigger( "click" );
    if (userSide == f2[1]) {
      $( "#engine_engine_resume" ).trigger( "click" );
    }
  }
}
function doAction() {
  console.log('settingActionIndex ' + settingActionIndex);
  if (settingActionIndex >= maxIndex) {
    return false;
  }
  console.log(gameFen[settingActionIndex]);
  if (typeof gameFen[settingActionIndex].status != 'undefined') {
    console.log('status: ' + gameFen[settingActionIndex].status);
    if (gameFen[settingActionIndex].status == 1) {
      $('.selected').html('');
      $('#sel_'+gameFen[settingActionIndex].game).html('selected');
      $('#mainboard_chessboard_fen').val(gameFen[settingActionIndex].fen);
      $( "#mainboard_chessboard_readfen" ).trigger( "click" );
      $( "#engine_engine_resume" ).trigger( "click" );
    } else {
      settingActionIndex++;
      doAction();
    }
  } else {
    settingActionIndex++;
    doAction();
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
<pre>
<?php //print_r($allGames); ?>
</pre>
<ul>
<?php if (!empty($allGames)) { foreach ($allGames as $k => $v) { ?>
    <?php if (!empty($v['details']['result'])) {
          continue;
          }
          //pr($v['details']);
        ?>
        
        <li><a href="manishGames.php?user=<?php echo $user; ?>&index=<?php echo $k; ?>"><?php echo $v['details']['metadata']['game']; ?></a> - <a href="http://net-chess.com/viewgame.cgi?p1=<?php echo $v['details']['metadata']['game']; ?>" target="_blank">Open</a> <span id="sel_<?php echo $v['details']['metadata']['game']; ?>" class="selected" style="font-weight:bold;"></span><?php echo displayForm($v['details']['metadata']['game']); ?></li>
</li>
<?php } } ?>
</ul>
</div>
<meta http-equiv="refresh" content="600;URL='/chess/analyse/manishGames.php?user=<?php echo $user; ?>'">
<?php 
/*
echo 'count: '.count($arr).'<br>';
if (($index + 1) >= count($arr)) {
  $index = -1;
}
if (isset($arr[$index+1])) { ?>
<meta http-equiv="refresh" content="3600;URL='/chess/analyse/manish.php?url=<?php echo $arr[$index+1]['url']; ?>&user=<?php echo $user; ?>&index=<?php echo $index+1; ?>'">

<?php } */
pr($unresolvedGames);
?>
<div id="footerstick"><div id="footer"><div id="copyright">&copy;2012-2014 </div></div></div>

</body>
</html>
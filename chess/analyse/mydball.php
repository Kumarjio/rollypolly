<?php
try {
check_login();
//http://mkgalaxy.com/chess/analyse/mydb?id=19&side=W&base_id=558

$pageTitle = 'Chess';
$layoutStructure = 'simple';
$newPath = '';
if (defined('HTTPPATH')) {
  $newPath = HTTPPATH.'/chess/analyse/';
}
if (empty($_GET['id'])) {
  throw new Exception('empty id');
}

require_once 'Games/Chess/Standard.php';
include(SITEDIR.'/includes/chess_functions.php');

$query = "SELECT * FROM chess_repertorie WHERE uid = ? AND repertory_id = ?";
$resultChess = $modelGeneral->fetchRow($query, array($_SESSION['user']['id'], $_GET['id']), 3600);
if (empty($resultChess)) {
  $modelGeneral->clearCache($query, array($_SESSION['user']['id'], $_GET['id']));
  throw new Exception('repertorie does not exists');
}

$level = 0;
$curfen = '';
$move = '';
$queryChessCurrentMove = "SELECT * FROM chess_repertorie_allmoves WHERE uid = ? AND repertory_id = ? AND flag = 0 LIMIT 1";
$resultChessCurrentMove = $modelGeneral->fetchRow($queryChessCurrentMove, array($_SESSION['user']['id'], $_GET['id']), 0);
if (!empty($resultChessCurrentMove)) {
  $curfen = $resultChessCurrentMove['move_fen'];
  $move = $resultChessCurrentMove['move'];
  $level = $resultChessCurrentMove['move_number'];
}

$standard = new Games_Chess_Standard;
$standard->resetGame($curfen);
$fen = $standard->renderFen();
$toMove = $standard->toMove();

//legal moves ended
if ($toMove === 'W') {
  $level = $level + 1;
}
$legalMoves = getlegalmoves($standard, $fen);
$copyLegalMoves = array();
$find = false;
//http://mkgalaxy.com/chess/analyse/mydb?id=19&side=W&base_id=558
if (!empty($legalMoves['success'])) {
  $copyLegalMoves = $legalMoves;
  $error = $legalMoves['result'];
  $msg = $legalMoves['msg'];
  echo 'check this';
  pr($error);
  pr($msg);
  exit;
  $legalMoves = array();
} else {
  if (!empty($legalMoves)) {
    foreach ($legalMoves as $k => $v) {
      $uid = $_SESSION['user']['id'];
      $repertory_id = $_GET['id'];
      $parent_id = !empty($resultChessCurrentMove['move_id']) ? $resultChessCurrentMove['move_id'] : 0;
      $move = $v['move'];
      $move_number = $level;
      $move_fen = $v['fen'];
      $move_side = $toMove;
      //echo "$uid, $repertory_id, $parent_id, $move, $move_number, $move_fen, $move_side<br>";
      addinrepertorieAll($uid, $repertory_id, $parent_id, $move, $move_number, $move_fen, $move_side);
    }
    $refresh = 1;
  }
}

?>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo $newPath; ?>js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
<script language="javascript">
var bookBin = null;
  /*var bookRequest = new XMLHttpRequest();
  bookRequest.open('GET', '/scripts/chess/books/Elo2400.bin', true);
  bookRequest.responseType = "arraybuffer";
  bookRequest.onload = function(event) {
      if(bookRequest.status == 200) {
          bookBin = bookRequest.response;
          //$('#mainboard_chessboard_fen').val('<?php echo $fen; ?>');
          //$( "#mainboard_chessboard_readfen" ).trigger( "click" );
          //$( "#engine_engine_resume" ).trigger( "click" );
      } else {
          alert('book loading failed');
      }
  };
  bookRequest.send(null);*/
var doMe = true;
</script>
<h3><a href="mydb">Main Page</a></h3>
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

<script src="/scripts/chess.js/chess.js" type="text/javascript"></script>

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
<?php echo $fen; ?>
<script language="javascript">
$( document ).ready(function() {
  $('#mainboard_chessboard_fen').val('<?php echo $fen; ?>');
  $( "#mainboard_chessboard_readfen" ).trigger( "click" );
  
});
</script>
<?php

if (!empty($find)) {
  ?>
<script language="javascript">
function doMeFunc(mv)
{
  console.log('move is '+ mv);
  var chess = new Chess('<?php echo $fen; ?>');
  console.log(chess);
  var move = {};
  var f = mv.substr(0, 2);
  var s = mv.substr(2, 2);
  move.from = f;
  move.to = s;
  var newmove = chess.move(move);
  console.log(newmove.san);
  console.log(chess.fen());
  console.log("/api/help/chess/add?uid=<?php echo $_SESSION['user']['id']; ?>&repertory_id=<?php echo $_GET['id']; ?>&parent_id=<?php echo $resultChessCurrentMove['move_id']; ?>&move="+newmove.san+"&move_number=<?php echo $level; ?>&move_fen="+encodeURIComponent(chess.fen())+"&move_side=<?php echo $toMove; ?>");
  $.get("/api/help/chess/add?uid=<?php echo $_SESSION['user']['id']; ?>&repertory_id=<?php echo $_GET['id']; ?>&parent_id=<?php echo $resultChessCurrentMove['move_id']; ?>&move="+newmove.san+"&move_number=<?php echo $level; ?>&move_fen="+encodeURIComponent(chess.fen())+"&move_side=<?php echo $toMove; ?>", function( data ) {
    console.log(data);
    location.reload();
  });
}

$( document ).ready(function() {
  /*$('#mainboard_chessboard_fen').val('<?php echo $fen; ?>');
  $( "#mainboard_chessboard_readfen" ).trigger( "click" );
  $( "#engine_engine_resume" ).trigger( "click" );*/
  
});
</script>
    <?php
}
if (!empty($refresh)) {
  ?>
<meta http-equiv="refresh" content="5">
  <?php
} else {
?>

<meta http-equiv="refresh" content="600">
<?php
}
?>
<?php
if (!empty($resultChessCurrentMove)) {
  pr($resultChessCurrentMove);
}
pr($legalMoves);
?>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
<?php
try {
check_login();
$pageUrl = 'detail';
$layoutStructure = 'simple';
$pageTitle = 'Chess Opening Repertorie';
if (empty($_GET['id'])) {
  throw new Exception('missing id');
}
require_once 'Games/Chess/Standard.php';
include(SITEDIR.'/includes/chess_functions.php');
$error = '';
$msg = '';


$move_id = !empty($_GET['move_id']) ? $_GET['move_id'] : '';
$parent_id = !empty($_GET['parent_id']) ? $_GET['parent_id'] : 0;
$move = !empty($_GET['move']) ? $_GET['move'] : '';
$level = !empty($_GET['level']) ? $_GET['level'] : 0;



$t = 3600;
if (!empty($_GET['fenKey'])) {
$queryFenSearch = "SELECT * FROM chess_repertorie_moves WHERE uid = ? AND repertory_id = ? AND move_fen = ?";
$resultFenSearch = $modelGeneral->fetchRow($queryFenSearch, array($_SESSION['user']['id'], $_GET['id'], $_GET['fenKey']), 0);

    if (!empty($resultFenSearch)) {
      $move_id = $resultFenSearch['move_id'];
      $parent_id = $resultFenSearch['move_id'];
      $level = $resultFenSearch['move_number'];
    }

}

$queryAll = "SELECT * FROM chess_repertorie_moves WHERE uid = ? AND repertory_id = ?";

$query = "SELECT * FROM chess_repertorie WHERE uid = ? AND repertory_id = ?";
$resultChess = $modelGeneral->fetchRow($query, array($_SESSION['user']['id'], $_GET['id']), $t);
if (empty($resultChess)) {
  $modelGeneral->clearCache($query, array($_SESSION['user']['id'], $_GET['id']));
  throw new Exception('repertorie does not exists');
}

$queryChessMoves = "SELECT * FROM chess_repertorie_moves WHERE uid = ? AND repertory_id = ? AND parent_id = ?";
$resultChessMoves = $modelGeneral->fetchAll($queryChessMoves, array($_SESSION['user']['id'], $_GET['id'], $parent_id), $t);
if (empty($resultChessMoves)) {
  $modelGeneral->clearCache($queryChessMoves, array($_SESSION['user']['id'], $_GET['id'], $parent_id));
}

$resultChessCurrentMove = array();
$curfen = '';
if (!empty($move_id)) {
    $queryChessCurrentMove = "SELECT * FROM chess_repertorie_moves WHERE uid = ? AND repertory_id = ? AND move_id = ?";
    $resultChessCurrentMove = $modelGeneral->fetchRow($queryChessCurrentMove, array($_SESSION['user']['id'], $_GET['id'], $move_id), $t);
    if (empty($resultChessCurrentMove)) {
      $modelGeneral->clearCache($queryChessCurrentMove, array($_SESSION['user']['id'], $_GET['id'], $move_id));
    }
    $curfen = $resultChessCurrentMove['move_fen'];
    $move = $resultChessCurrentMove['move'];
    $level = $resultChessCurrentMove['move_number'];
} else if (!empty($_GET['move']) && !empty($_GET['fen'])) {
  $curfen = $_GET['fen'];
}
//pr($resultChess);
//pr($resultChessMoves);
//pr($resultChessCurrentMove);

//Game meta start
$standard = new Games_Chess_Standard;
$standard->resetGame($curfen);
$fen = $standard->renderFen();
$toMove = $standard->toMove();
//Game meta end

//moves in db
$movesInDb = array();
if (!empty($resultChessMoves)) { 
  foreach ($resultChessMoves as $v) {
    $movesInDb[] = $v['move'];
  }
}
//moves in db ends

//legal moves started
$legalMoves = getlegalmoves($standard, $fen);
$copyLegalMoves = array();
if (!empty($legalMoves['success'])) {
  $copyLegalMoves = $legalMoves;
  $error = $legalMoves['result'];
  $msg = $legalMoves['msg'];
  $legalMoves = array();
}
//legal moves ended
if ($toMove === 'W') {
  $level = $level + 1;
}

if (!empty($_GET['move'])) {
    $data = array();
    $data['repertory_id'] = $_GET['id'];
    $data['uid'] = $_SESSION['user']['id'];
    $data['parent_id'] = $parent_id;
    $data['move_number'] = $level;
    $data['move'] = $_GET['move'];
    $data['move_fen'] = $_GET['fen'];
    $data['move_side'] = $_GET['toMove'];
    $queryCheck = "SELECT * FROM chess_repertorie_moves WHERE uid = ? AND repertory_id = ? AND parent_id = ? AND move = ?";
    $resultCheck = $modelGeneral->fetchRow($queryCheck, array($_SESSION['user']['id'], $_GET['id'], $parent_id, $_GET['move']), 0);
    if (empty($resultCheck)) {
        $result = $modelGeneral->addDetails('chess_repertorie_moves', $data);
        $modelGeneral->clearCache($query, array($_SESSION['user']['id'], $_GET['id']));
        $modelGeneral->clearCache($queryChessMoves, array($_SESSION['user']['id'], $_GET['id'], $parent_id));
        $modelGeneral->clearCache($queryChessCurrentMove, array($_SESSION['user']['id'], $_GET['id'], $move_id));
        $modelGeneral->clearCache($queryAll, array($_SESSION['user']['id'], $_GET['id']));
        header("Location: ".HTTPPATH."/chess/repertorie/$pageUrl?id=".$_GET['id']."&move_id=".$result."&parent_id=".$result);

        exit;
    }
}//end addition of repertorie


$arrayCategories = array();
$list = array();
$cat_tree = array();
$arrSelectedBox = array();
$arrSelectedDetails = array();
$resultAll = $modelGeneral->fetchAll($queryAll, array($_SESSION['user']['id'], $_GET['id']), $t);
if (!empty($resultAll)) { 
    foreach ($resultAll as $k => $v) {
        $mid = $v['move_id'];
        $cat_parent_id = $v['parent_id'];
        $arrayCategories[$mid] = array("parent_id" => $cat_parent_id, "name" => $v['move'], "details" => $v);
        $cat_tree = chess_tree_add($cat_tree, $cat_parent_id, $v, $mid);
    }
    if (!empty($move_id)) {
      list($arrSelectedBox, $arrSelectedDetails) = display_parent_nodes($move_id, $arrayCategories);
    }
}
if (!empty($_GET['del'])) {
  $queryDel = "DELETE FROM chess_repertorie_moves WHERE move_id = ? AND repertory_id = ? AND uid = ?";
  $modelGeneral->deleteDetails($queryDel, array($_GET['del'], $_GET['id'], $_SESSION['user']['id']));
  delete_child_nodes($_GET['del'], $arrayCategories, $modelGeneral, $_GET['id'], $_SESSION['user']['id']);
  $modelGeneral->clearCache($query, array($_SESSION['user']['id'], $_GET['id']));
  $modelGeneral->clearCache($queryChessMoves, array($_SESSION['user']['id'], $_GET['id'], $parent_id));
  $modelGeneral->clearCache($queryChessCurrentMove, array($_SESSION['user']['id'], $_GET['id'], $move_id));
  $modelGeneral->clearCache($queryAll, array($_SESSION['user']['id'], $_GET['id']));
  header("Location: ".HTTPPATH."/chess/repertorie/$pageUrl?id=".$_GET['id']."&move_id=".$move_id."&parent_id=".$move_id);
  exit;
}
?>


<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HTTPPATH; ?>/samples/chessstockfish/css/chess.css"></link>

<script language="javascript">
  var bookBin = null;
  var bookRequest = new XMLHttpRequest();
  bookRequest.open('GET', '/scripts/chess/books/Elo2400.bin', true);
  bookRequest.responseType = "arraybuffer";
  bookRequest.onload = function(event) {
      if(bookRequest.status == 200) {
          bookBin = bookRequest.response;
      } else {
          alert('book loading failed');
      }
  };
  bookRequest.send(null);
  var pageURL = '/chess/repertorie/detail?id=<?php echo $_GET['id']; ?>&move_id=<?php echo $move_id; ?>&parent_id=<?php echo $parent_id; ?>';
</script>

<script type="text/javascript" src="<?php echo HTTPPATH; ?>/samples/chessstockfish/js/ChessFen.js"></script>


<script src="<?php echo HTTPPATH; ?>/scripts/chessboardjs/js/chessboard-0.3.0.js"></script>
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/scripts/chessboardjs/css/chessboard-0.3.0.css" />
<script src="<?php echo HTTPPATH; ?>/scripts/chess.js/chess.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/chess/lozuilib.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/chess/fen.js"></script>

<script type="text/javascript">

function displayFen(fenString)
{
  try{
    chessObj.loadFen(fenString,'board');
    document.getElementById('fen').value = fenString;
  }catch(e){
    alert('Invalid FEN');	
  }
}
function loadmypgn()
{
  var pgn = $('#pgnVal').val();
  var chess = new Chess();
  var result = chess.load_pgn(pgn);
  $('#fen').val(chess.fen());
  updateBoardFromFen();
}
</script>
<link rel="stylesheet" type="text/css" href="<?php echo HTTPPATH; ?>/samples/treeview/_stylesMain.css" media="screen">
<div class="row">
  <div class="col-lg-12">
    <h3>Chess Opening Repertorie "<?php echo $resultChess['repertory_name']; ?>"</h3>
    <a href="<?php echo HTTPPATH; ?>/chess/repertorie/my">My Chess Repertorie</a> | 
    <a href="<?php echo HTTPPATH; ?>/chess/repertorie/<?php echo $pageUrl; ?>?id=<?php echo $_GET['id']; ?>">Back To Default</a>
  </div>
</div>
<div class="row">
    <div class="col-lg-5">load_pgn
      <div><table><tr><td>Type FEN in the field below:<a name="show"></a></td></tr>
      <tr><td><input type="text" id="fen" style="width:360px" value="<?php echo $fen; ?>"></td></tr>
      <tr><td><textarea name="pgnVal" id="pgnVal" rows="3" style="width:100%"></textarea><input type="button" name="btn" id="btn" onClick="loadmypgn()" value="Load PGN" /></td></tr>
      </table><br /><?php if (!empty($move)) { echo 'Move: <b>'.$move.'</b>'; } ?><br /><br /></div>
      <div id="boardDiv"></div>
      
<p id="board" style="width:400px;">
</p>
<p>

<div class="btn-group" role="group">
<button class="btn btn-primary" id="start">Analyse</button>
<button class="btn btn-primary" id="stop">Stop</button>
</div>
<button class="btn btn-primary" id="eval">Evaluate</button>
<span id="enginelist"></span>

</p>
<p id="moves"></p>

<p id="stats">
</p>
<p id="info">
</p>


    </div>
    <div class="col-lg-2">
      <h3>Legal Moves</h3>
      <?php if (!empty($error)) { echo '<div class="error">'.$error.' '.$msg.'</div>'; } ?>
      <?php
      if (!empty($legalMoves)) { 
      ?>
      <ol>
      <?php
        foreach ($legalMoves as $num => $moves) {
      ?>
      <li>
        <?php if (in_array($moves['move'].$moves['result'], $movesInDb)) { ?>
            <b><?php echo $moves['move'].$moves['result']; ?></b>
        <?php } else { ?>
            <a href="<?php echo HTTPPATH; ?>/chess/repertorie/<?php echo $pageUrl; ?>?id=<?php echo $_GET['id']; ?>&move=<?php echo urlencode($moves['move'].$moves['result']); ?>&fen=<?php echo urlencode($moves['fen']); ?>&level=<?php echo $level; ?>&move_id=<?php echo $move_id; ?>&parent_id=<?php echo $parent_id; ?>&toMove=<?php echo $toMove; ?>"><?php echo $moves['move'].$moves['result']; ?></a>
        <?php }//end if (in_array($moves['move'], $movesInDb)) {?>
      </li>
      <?php } ?>
      </ol>
      <?php } ?>
    </div>
    <div class="col-lg-5">
        <h3>Current Repertorie Moves</h3>
        <?php
        if (!empty($resultChessMoves)) { 
        ?>
        <ol>
        <?php
          foreach ($resultChessMoves as $num => $moves) {
        ?>
        <li><a href="<?php echo HTTPPATH; ?>/chess/repertorie/<?php echo $pageUrl; ?>?id=<?php echo $_GET['id']; ?>&level=<?php echo $moves['move_number']; ?>&move_id=<?php echo $moves['move_id']; ?>&parent_id=<?php echo $moves['move_id']; ?>"><?php echo $moves['move']; ?></a> - <a href="<?php echo HTTPPATH; ?>/chess/repertorie/<?php echo $pageUrl; ?>?id=<?php echo $_GET['id']; ?>&del=<?php echo $moves['move_id']; ?>&move_id=<?php echo $moves['parent_id']; ?>&parent_id=<?php echo $moves['parent_id']; ?>" onClick="var a = confirm('Do you really want to delete this move and all moves under this tree?'); return a;">Delete</a></li>
        <?php } ?>
        </ol>
        <?php } ?>
        <h3><?php echo $resultChess['repertory_name']; ?></h3>
        <?php if (!empty($resultAll)) { ?>
        <?php
        
        
        if (!empty($arrSelectedDetails)) {
          $tmp = array();
          $string = '<a href="'.HTTPPATH.'/chess/repertorie/'.$pageUrl.'?id='.$_GET['id'].'" class="acustom">Pgn</a>: ';
          foreach ($arrSelectedDetails as $k => $v) {
            if ($v['details']['move_side'] === 'W') {
              $string .= $v['details']['move_number'].'.';
            }
            $string .= ' <a href="'.HTTPPATH.'/chess/repertorie/'.$pageUrl.'?id='.$_GET['id'].'&move_id='.$v['details']['move_id'].'&parent_id='.$v['details']['move_id'].'" class="acustom">'.$v['details']['move'].'</a> </i>';
          }
          $string .= '<br><br>';
          echo $string;
        }
        ?>

        <div class="general-style1">
        <?php
        createTree($_GET['id'], $arrSelectedBox, $arrayCategories, 0);
        ?>
        </div>
        <?php } ?>
        <h3>Search Fen</h3>
        <form name="formFen" id="formFen" method="get" action="">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
        <input type="hidden" name="move_id" value="<?php echo $move_id; ?>" />
        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
        <input name="fenKey" type="text" class="inputText" id="fenKey" value="<?php echo !empty($_GET['fenKey']) ? $_GET['fenKey'] : ''; ?>" required placeholder="Enter Fen" />
        <br />
        <input type="submit" name="submit" id="submit" value="Search" class="inputText">
        </form>
    </div>
</div>

        <?php //pr($manish); pr($cat_tree); ?>
<script type="text/javascript">

function lozUpdateBestMove ()
{

  var move = {};

  move.from = lozData.bmFr;
  move.to   = lozData.bmTo;
  alert(move.from+move.to);

}

//var chessObj = new DHTMLGoodies.ChessFen( { pieceType:'alpha',squareSize:60 });
//displayFen('<?php echo $fen; ?>');
</script>


<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
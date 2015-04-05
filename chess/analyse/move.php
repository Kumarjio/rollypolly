<?php
$pageTitle = 'Chess';
$layoutStructure = 'simple';
$newPath = '';
if (defined('HTTPPATH')) {
  $newPath = HTTPPATH.'/chess/analyse/';
}
?>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo $newPath; ?>js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
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
  doMe = false;
</script>
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

</div>
<?php include('db.php'); ?>
<div id="footerstick"><div id="footer"><div id="copyright">&copy;2012-2014 </div></div></div>
</body>
</html>
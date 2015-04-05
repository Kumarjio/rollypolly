<?php
$layoutStructure = 'simple';
$pageTitle = 'Chess';
?>
<script src="<?php echo HTTPPATH; ?>/scripts/chessboardjs/js/chessboard-0.3.0.js"></script>
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/scripts/chessboardjs/css/chessboard-0.3.0.css" />
<script src="<?php echo HTTPPATH; ?>/scripts/chess.js/chess.js"></script>

<script src="<?php echo HTTPPATH; ?>/scripts/chess/lozuilib.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/chess/play.js"></script>

<div class="row">
  <div class="col-lg-4">
<h1>Play Chess With Computer</h1>
<p>

<a id="playw" class="btn btn-primary" href="#">play with white</a>

<a id="playb" class="btn btn-primary" href="#">play with black</a>

<input data-toggle="tooltip" data-placement="bottom"title="the number of seconds the engine takes to move" type="text" value="10" id="permove" size=3 />

</p>



<p id="stats">

</p>



<p id="board"></p>



<p id="moves"></p>

<p id="info"></p>

</div>
</div>

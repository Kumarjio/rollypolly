<?php
$layoutStructure = 'simple';
$pageTitle = 'Chess';
?>
<script src="<?php echo HTTPPATH; ?>/scripts/chessboardjs/js/chessboard-0.3.0.js"></script>
<link rel="stylesheet" href="<?php echo HTTPPATH; ?>/scripts/chessboardjs/css/chessboard-0.3.0.css" />
<script src="<?php echo HTTPPATH; ?>/scripts/chess.js/chess.js"></script>

<script src="<?php echo HTTPPATH; ?>/scripts/chess/lozuilib.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/chess/fenSample.js"></script>
<script src="<?php echo HTTPPATH; ?>/scripts/chess/lozza.js"></script>


<p>
<a class="btn btn-success" href="index.htm">Lozza home</a>
<a class="btn btn-info"    href="http://op12no2.me/posts/1810">Help</a>
<span id="enginelist"></span>
</p>
<form role="form">
<input class="form-control" id="fen" type="text" value="">
</form>
<p>
<div class="btn-group" role="group">
<button class="btn btn-warning" id="startpos">Reset</button>
<button class="btn btn-warning" id="clearpos">Clear</button>
</div>

<div class="btn-group" role="group">
<button class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="toggle the turn (w/b) in the fen string" id="flipturn">Turn</button>
<button class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="this doers not affect the fen string" id="flippos">Flip</button>
</div>
</p>



<p>

<div class="btn-group" role="group">
<button class="btn btn-primary" id="start">Analyse</button>
<button class="btn btn-primary" id="stop">Stop</button>
</div>
<button class="btn btn-primary" id="eval">Evaluate</button>
</p>
<p id="stats">
</p>
<p id="board" style="width:400px;">
</p>
<p id="info">
</p>
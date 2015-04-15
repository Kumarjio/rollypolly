<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>

<body>
<img id="ims" src="http://upload.wikimedia.org/wikipedia/commons/6/6f/ChessSet.jpg" class="item" />
<div id="start">Waiting for dragging the image get started...</div>
<div id="stop">Waiting image getting dropped...</div>

<script language="javascript">
var clicking = false;
var startPositionX = 0;
var startPositionY = 0;
$( ".item" ).mousedown(function( event ) {
    event.preventDefault();
  clicking = true;
});
$(document).mouseup(function(event){
    clicking = false;
    event.preventDefault();
});

$('.item').mousemove(function(event){
    if(clicking === false) return;
    event.preventDefault();
    var pageCoords = "( " + event.pageX + ", " + event.pageY + " )";
    var clientCoords = "( " + event.clientX + ", " + event.clientY + " )";
    $( "#start" ).text( "( event.pageX, event.pageY ) : " + pageCoords );
    $( "#stop" ).text( "( event.clientX, event.clientY ) : " + clientCoords );
});
</script>
</body>
</html>
<html>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript">
var canvas, ctx, flag = false,
    prevX = 0,
    currX = 0,
    prevY = 0,
    currY = 0,
    dot_flag = false;

var x = "black",
    y = 4;

function init() {
    canvas = document.getElementById('can');
    ctx = canvas.getContext("2d");
    //added
    var imageObj = new Image();
    imageObj.onload = function() {
      ctx.drawImage(imageObj, 0, 0);
    };
    imageObj.src = 'http://mkgxy.consultlawyers.us/samples/p2/images/userImages/Female%20Story%20board.jpg';
    //added ended
    w = canvas.width;
    h = canvas.height;

    canvas.addEventListener("mousemove", function (e) {
        findxy('move', e)
    }, false);
    canvas.addEventListener("mousedown", function (e) {
        findxy('down', e)
    }, false);
    canvas.addEventListener("mouseup", function (e) {
        findxy('up', e)
    }, false);
    canvas.addEventListener("mouseout", function (e) {
        findxy('out', e)
    }, false);
}

function color(obj) {
    switch (obj.id) {
        case "green":
            x = "green";
            break;
        case "blue":
            x = "blue";
            break;
        case "red":
            x = "red";
            break;
        case "yellow":
            x = "yellow";
            break;
        case "orange":
            x = "orange";
            break;
        case "black":
            x = "black";
            break;
        case "white":
            x = "white";
            break;
    }
    if (x == "white") y = 14;
    else y = 2;

}

function draw() {
    ctx.beginPath();
    ctx.moveTo(prevX, prevY);
    ctx.lineTo(currX, currY);
    ctx.strokeStyle = x;
    ctx.lineWidth = y;
    ctx.stroke();
    ctx.closePath();
}

function erase() {
    var m = confirm("Want to clear");
    if (m) {
        ctx.clearRect(0, 0, w, h);
        document.getElementById("canvasimg").style.display = "none";
    }
}

function save() {
    document.getElementById("canvasimg").style.border = "2px solid";
    var dataURL = canvas.toDataURL();
    console.log(dataURL);
    document.getElementById("canvasimg").src = dataURL;
    document.getElementById("canvasimg").style.display = "inline";
}

function findxy(res, e) {
    if (res == 'down') {
        prevX = currX;
        prevY = currY;
        currX = e.clientX - canvas.offsetLeft;
        currY = e.clientY - canvas.offsetTop;

        flag = true;
        dot_flag = true;
        if (dot_flag) {
            ctx.beginPath();
            ctx.fillStyle = x;
            ctx.fillRect(currX, currY, 2, 2);
            ctx.closePath();
            dot_flag = false;
        }
    }
    if (res == 'up' || res == "out") {
        flag = false;
    }
    if (res == 'move') {
        if (flag) {
        console.log(currX);
        console.log(currY);
        var value = $('#coordsText').val();
        if(value == '') {
          value = currX+','+currY;
        } else {
          value = value+','+currX+','+currY;
        }
        $('#coordsText').val(value);
        
        
            prevX = currX;
            prevY = currY;
            currX = e.clientX - canvas.offsetLeft;
            currY = e.clientY - canvas.offsetTop;
            draw();
        }
    }
}
</script>
<body onload="init()">
    <canvas id="can" width="920" height="1186" style="border:2px solid;"></canvas>
    <div>Choose Color</div>
    <div style="width:10px;height:10px;background:green;" id="green" onclick="color(this)"></div>
    <div style="width:10px;height:10px;background:blue;" id="blue" onclick="color(this)"></div>
    <div style="width:10px;height:10px;background:red;" id="red" onclick="color(this)"></div>
    <div style="width:10px;height:10px;background:yellow;" id="yellow" onclick="color(this)"></div>
    <div style="width:10px;height:10px;background:orange;" id="orange" onclick="color(this)"></div>
    <div style="width:10px;height:10px;background:black;" id="black" onclick="color(this)"></div>
    <div style="">Eraser</div>
    <div style="width:15px;height:15px;background:white;border:2px solid;" id="white" onclick="color(this)"></div>
    <img id="canvasimg" style="display:none;">
    <input type="button" value="save" id="btn" size="30" onclick="save()">
    <input type="button" value="clear" id="clr" size="23" onclick="erase()">
    <input id="coordsText" class="effect" name="" type="text" value="" size="50" placeholder="&laquo; Coordinates &raquo;" />
      
</body>
</html>
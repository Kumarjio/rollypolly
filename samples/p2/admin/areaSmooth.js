// JavaScript Document
var canvas, ctx, flag = false,
    prevX = 0,
    currX = 0,
    prevY = 0,
    currY = 0,
    dot_flag = false;

var x = "black",
    y = 2;
var mboxArea;

function init(imgUrl) {
  canvas = document.getElementById('can');
  ctx = canvas.getContext("2d");
  ctx.globalCompositeOperation='destination-over';
  //added
  var imageObj = new Image();
  imageObj.onload = function() {
    ctx.drawImage(imageObj, 0, 0);
  };
  imageObj.src = imgUrl;
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


function findxy(res, e) {
  if (res == 'down') {
        prevX = currX;
        prevY = currY;
        currX = e.offsetX; //e.clientX - canvas.offsetLeft;
        currY = e.offsetY; //e.clientY - canvas.offsetTop;

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
        var value = $('#coordsText').val();
        if (value) {
          mboxArea = '<input type="text" name="areas[]" value="'+value+'" size="50" style="display:block">';
          $('#maskedArea').append(mboxArea);
          $('#coordsText').val('');
        }
    }
    if (res == 'move') {
        if (flag) {
        var value = $('#coordsText').val();
        if(value == '') {
          value = currX+','+currY;
        } else {
          value = value+','+currX+','+currY;
        }
        $('#coordsText').val(value);
            prevX = currX;
            prevY = currY;
            currX = e.offsetX; //e.clientX - canvas.offsetLeft;
            currY = e.offsetY; //e.clientY - canvas.offsetTop;
            draw();
        }
    }
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


function drawPast(pX, pY, cX, cY) {
    pX = parseInt(pX);
    pY = parseInt(pY);
    cX = parseInt(cX);
    cY = parseInt(cY);
    ctx.beginPath();
    ctx.moveTo(pX, pY);
    ctx.lineTo(cX, cY);
    ctx.strokeStyle = x;
    ctx.lineWidth = y;
    ctx.stroke();
    ctx.closePath();
}

function resetSettings()
{
  ctx.globalCompositeOperation='source-over';
}
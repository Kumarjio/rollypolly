<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script language="javascript">
$(document).ready(function() {
  $('img').click(function(e) {
    var offset = $(this).offset();
    alert(e.clientX - offset.left);
    alert(e.clientY - offset.top);
  });
});
</script>
</head>

<body>
<img src="../images/mastercard.png" width="128" height="128" usemap="#Map" />
<map name="Map">
  <area shape="poly" coords="35,44,86,60,49,95" href="#">
</map>
</body>
</html>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<p>
    <label for="number1">Which Number:</label>
    <input type="number" name="number1" id="number1">
</p>
<button id="find">Find</button>
<p id="test1"></p>


<script language="javascript">
    var myArray = new Array();
    myArray[0] = 'apple';
    myArray[1] = 'mango';
    myArray[2] = 'watermelon';
    console.log(myArray);
    
    document.getElementById("find").onclick = function() {
        var num = parseInt(document.getElementById("number1").value);
        document.getElementById("test1").innerHTML = myArray[num];
    };
</script>

</body>
</html>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<p>New Rating</p>
<form id="form1" name="form1" method="post">
  <p>
    <label>
      <input type="radio" name="disease_group" value="1" id="disease_group_0">
      Choose</label>
    <br>
    <label for="select">Disease List:</label>
    <select name="select" id="select">
    </select>
    <br>
    <label>
      <input type="radio" name="disease_group" value="2" id="disease_group_1">
      New</label>
    <label for="textfield"><br>
    Add New Disease:</label>
<input type="text" name="textfield" id="textfield">
  </p>
  <p>
    <label>
      <input type="radio" name="therapy_group" value="1" id="therapy_group_0">
      Choose</label>
    <br>
    Therapy List:
    <select name="select2" id="select2">
</select>
    <br>
    <label>
      <input type="radio" name="therapy_group" value="2" id="therapy_group_1">
      New</label>
    <br>
<label for="textfield2">Add New Therapy:</label>
    <input type="text" name="textfield2" id="textfield2">
</p>
  <p>
    <label>
      <input type="radio" name="remedy_group" value="1" id="remedy_group_0">
      Choose</label>
    <br>
    <label for="select3">Remedy List:</label>
    <select name="select3" id="select3">
    </select>
<br>
    <label>
      <input type="radio" name="remedy_group" value="2" id="remedy_group_1">
      New</label>
    <br>
    <label for="textfield3">Add New Remedy:</label>
    <input type="text" name="textfield3" id="textfield3">
  </p>
  <p>Rating: <label>
      <input type="radio" name="rating" value="1" id="rating_0">
    1</label> 
    <label>
      <input type="radio" name="rating" value="2" id="rating_1">
    2</label> 
    <label>
      <input type="radio" name="rating" value="3" id="rating_2">
      3</label> 
    <label>
      <input type="radio" name="rating" value="4" id="rating_3">
      4</label>  
    <label>
      <input type="radio" name="rating" value="5" id="rating_4">
      5</label> 
    <label>
      <input type="radio" name="rating" value="6" id="rating_5">
      6</label> 
    <label>
      <input type="radio" name="rating" value="7" id="rating_6">
      7</label> 
    <label>
      <input type="radio" name="rating" value="8" id="rating_7">
      8</label> 
    <label>
      <input type="radio" name="rating" value="9" id="rating_8">
      9</label> 
    <label>
      <input type="radio" name="rating" value="10" id="rating_9">
      10</label>
  </p>
  <p> 
    <label for="rating_details">Rating Experience:</label>
    <br>
    <textarea name="rating_details" id="rating_details"></textarea>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit">
    <input type="hidden" name="disease_id" id="disease_id">
    <input type="hidden" name="therapy_id" id="therapy_id">
    <input type="hidden" name="remedy_id" id="remedy_id">
    <input type="hidden" name="uid" id="uid">
    <input type="hidden" name="hiddenField5" id="hiddenField5">
    <input type="hidden" name="hiddenField6" id="hiddenField6">
    <input type="hidden" name="hiddenField7" id="hiddenField7">
    <br>
    <br>
    <br>
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>
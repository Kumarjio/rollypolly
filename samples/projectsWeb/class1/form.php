<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<form>
    <p>First Name:
    <br>
        Last Name:    </p>
    <p>
        <label for="age">Age:</label>
        <input type="text" name="age" id="age">
    </p>
    <p>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">
    </p>
    <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </p>
    <p>
        <input type="radio" name="gender" id="radio" value="radio">
        <label for="radio">Male</label>
    </p>
    <p>
        <label for="select">Month:</label>
        <select name="month" id="month">
            <option value="1">18</option>
            <option value="2">19</option>
        </select>
    </p>
    <p>Day: 
        <label for="select">Select:</label>
        <select name="select" id="select">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </p>
    <p>
        <input type="radio" name="gender" id="radio2" value="radio2">
        <label for="radio2">Female</label>
    </p>
    <p>
        <input type="checkbox" name="checkbox" id="checkbox">
        <label for="checkbox">Checkbox </label>
    </p>
    <p>&nbsp;</p>
    <table width="100%" border="1" cellspacing="1" cellpadding="5">
        <tr>
            <td>First Name</td>
            <td><input type="text" name="firstname" id="firstname"></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" name="lastname" id="lastname"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" id="submit" value="Submit"></td>
        </tr>
    </table>
    <p>&nbsp;</p>
</form>
<hr>
<p><img src="file:///Macintosh HD/Users/mkhancha/Pictures/bangbang.jpg" width="214" height="317" /></p>
<p>&nbsp;</p>


<form class="form-horizontal">
  <fieldset>
    <legend>Legend</legend>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Email</label>
      <div class="col-lg-10">
        <input type="text" class="form-control" id="inputEmail" placeholder="Email">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword" class="col-lg-2 control-label">Password</label>
      <div class="col-lg-10">
        <input type="password" class="form-control" id="inputPassword" placeholder="Password">
        <div class="checkbox">
          <label>
            <input type="checkbox"> 
            I agree to terms and conditions
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="textArea" class="col-lg-2 control-label">Textarea</label>
      <div class="col-lg-10">
        <textarea class="form-control" rows="3" id="textArea"></textarea>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-2 control-label">Radios</label>
      <div class="col-lg-10">
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
            Option one is this
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
            Option two can be something else
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="select" class="col-lg-2 control-label">Selects</label>
      <div class="col-lg-10">
        <select class="form-control" id="select">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
        <br>
        <select multiple="" class="form-control">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button type="reset" class="btn btn-default">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </fieldset>
</form>
</body>
</html>
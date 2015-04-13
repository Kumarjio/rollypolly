<?php require_once('../Connections/connWork.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="registerError.php";
  $loginUsername = $_POST['username'];
  $LoginRS__query = sprintf("SELECT username FROM users WHERE username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_connWork, $connWork);
  $LoginRS=mysql_query($LoginRS__query, $connWork) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO users (username, email, password, name, paypal_email, gender, age) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['paypal_email'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['age'], "int"));

  mysql_select_db($database_connWork, $connWork);
  $Result1 = mysql_query($insertSQL, $connWork) or die(mysql_error());

  $insertGoTo = "registerConfirm.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Register New User</title>
</head>

<body>
<h1>Register</h1>
<form ACTION="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <p>
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
  </p>
  <p>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
  </p>
  <p>Confirm Password:
<input type="password" name="confirmPassword" id="confirmPassword" required>
  </p>
  <p>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
  </p>
  <p>
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
  </p>
  <p>
    <label for="paypal_email">Paypal Email:</label>
    <input type="text" name="paypal_email" id="paypal_email" required>
  </p>
  <p>Gender: 
    <label>
      <input name="gender" type="radio" id="gender_0" value="Male" checked>
      Male</label> 
    <label>
      <input type="radio" name="gender" value="Female" id="gender_1">
      Female</label>
  </p>
  <p>
    <label for="age">Age:</label>
    <input type="number" name="age" id="age" min="18" max="200">
    <br>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Register as a new user">
  </p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p></p>
</body>
</html>
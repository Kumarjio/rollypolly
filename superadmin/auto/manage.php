<?php require_once('../../Connections/connMain.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $array = array('name', 'email');
  if (in_array($_POST['field_name'], $array)) {
    $error = 'Cannot use '.$_POST['field_name'].' as field name. Please change it.';
    unset($_POST["MM_insert"]);
  }
  if (!empty($_POST['ri']['key'])) {
    $ri = array();
    foreach ($_POST['ri']['key'] as $k => $v) {
      $ri[$v] = $_POST['ri']['value'][$k];
    }
    $_POST['related_information'] = json_encode($ri);
  }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  if (!empty($_POST['ri']['key'])) {
    $ri = array();
    foreach ($_POST['ri']['key'] as $k => $v) {
      $ri[$v] = $_POST['ri']['value'][$k];
    }
    $_POST['related_information'] = json_encode($ri);
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO z_modules_fields (module_id, field_name, field_display_name, field_type, searchable, related_information, sorting, encrypted, search_display_name, help_text, required, field_prefix, field_suffix, field_default_value) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['module_id'], "int"),
                       GetSQLValueString($_POST['field_name'], "text"),
                       GetSQLValueString($_POST['field_display_name'], "text"),
                       GetSQLValueString($_POST['field_type'], "text"),
                       GetSQLValueString(isset($_POST['searchable']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['related_information'], "text"),
                       GetSQLValueString($_POST['sorting'], "int"),
                       GetSQLValueString(isset($_POST['encrypted']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['search_display_name'], "text"),
                       GetSQLValueString($_POST['help_text'], "text"),
                       GetSQLValueString(isset($_POST['required']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['field_prefix'], "text"),
                       GetSQLValueString($_POST['field_suffix'], "text"),
                       GetSQLValueString($_POST['field_default_value'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE z_modules_fields SET field_name=%s, field_display_name=%s, field_type=%s, searchable=%s, related_information=%s, sorting=%s, encrypted=%s, search_display_name=%s, help_text=%s, required=%s, field_prefix=%s, field_suffix=%s, field_default_value=%s WHERE field_id=%s",
                       GetSQLValueString($_POST['field_name'], "text"),
                       GetSQLValueString($_POST['field_display_name'], "text"),
                       GetSQLValueString($_POST['field_type'], "text"),
                       GetSQLValueString(isset($_POST['searchable']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['related_information'], "text"),
                       GetSQLValueString($_POST['sorting'], "int"),
                       GetSQLValueString(isset($_POST['encrypted']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['search_display_name'], "text"),
                       GetSQLValueString($_POST['help_text'], "text"),
                       GetSQLValueString(isset($_POST['required']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['field_prefix'], "text"),
                       GetSQLValueString($_POST['field_suffix'], "text"),
                       GetSQLValueString($_POST['field_default_value'], "text"),
                       GetSQLValueString($_POST['field_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($updateSQL, $connMain) or die(mysql_error());
}

if ((isset($_GET['delete_field_id'])) && ($_GET['delete_field_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM z_modules_fields WHERE field_id=%s",
                       GetSQLValueString($_GET['delete_field_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($deleteSQL, $connMain) or die(mysql_error());
}

$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsModule = sprintf("SELECT * FROM z_modules WHERE module_id = %s", GetSQLValueString($colname_rsModule, "int"));
$rsModule = mysql_query($query_rsModule, $connMain) or die(mysql_error());
$row_rsModule = mysql_fetch_assoc($rsModule);
$totalRows_rsModule = mysql_num_rows($rsModule);

$colname_rsView = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsView = $_GET['module_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT * FROM z_modules_fields WHERE module_id = %s ORDER BY sorting ASC", GetSQLValueString($colname_rsView, "int"));
$rsView = mysql_query($query_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$colname_rsEdit = "-1";
if (isset($_GET['field_id'])) {
  $colname_rsEdit = $_GET['field_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsEdit = sprintf("SELECT * FROM z_modules_fields WHERE field_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $connMain) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

if (!empty($_GET['create'])) {
  $sql = sprintf("SELECT * FROM z_modules_fields WHERE module_id = %s ORDER BY sorting ASC", GetSQLValueString($colname_rsView, "int"));
  $rs = mysql_query($sql, $connMain) or die(mysql_error());
  if (mysql_num_rows($rs) > 0) {
    $tablename = 'auto_'.$row_rsModule['module_name'];
    $error = 'Table '.$tablename.' Created Successfully.';
    $querys[] = 'DROP TABLE IF EXISTS `'.$tablename.'`';
    $querys2 = array();
    $tmpSql = 'CREATE TABLE `'.$tablename.'` (';
    $tmpSql .= '`id` varchar(50) NOT NULL, ';
    $tmpSql .= '`city_id` int(11) DEFAULT NULL, ';
    $tmpSql .= '`uid` varchar(50) DEFAULT NULL, ';
    $tmpSql .= '`module_id` int (11) DEFAULT NULL, ';
    
    $querys2[] = 'CREATE INDEX `uid` ON `'.$tablename.'` (`uid`)';
    $querys2[] = 'CREATE INDEX `city_id` ON `'.$tablename.'` (`city_id`)';
    $querys2[] = 'CREATE INDEX `module_id` ON `'.$tablename.'` (`module_id`)';

    $tmpSqlIndex = 'CREATE INDEX `comboIndex` ON `'.$tablename.'` (';
    $tmpSqlIndex .= '`uid`, ';
    $tmpSqlIndex .= '`city_id`, ';
    $tmpSqlIndex .= '`module_id`, ';
    while ($rec = mysql_fetch_array($rs)) {
      //print_r($rec);
      //echo '<br><br>';
      switch ($rec['field_type']) {
        case 'varchar':
        case 'hidden':
        case 'noshow':
          $tmpSql .= '`'.$rec['field_name'].'` varchar(255) DEFAULT NULL, ';
          if ($rec['searchable'] == 1) {
            $querys2[] = 'CREATE INDEX `'.$rec['field_name'].'` ON `'.$tablename.'` (`'.$rec['field_name'].'`)';
            $tmpSqlIndex .= '`'.$rec['field_name'].'`, ';
          }
          break;
        case 'text':
        case 'multipleselectbox':
        case 'urls':
        case 'images':
        case 'videos':
        case 'richtext':
          $tmpSql .= '`'.$rec['field_name'].'` text DEFAULT NULL, ';
          break;
        case 'int':
        case 'selectbox':
        case 'dependentselectbox':
        case 'checkbox':
        case 'radio':
          $tmpSql .= '`'.$rec['field_name'].'` int(11) DEFAULT NULL, ';
          if ($rec['searchable'] == 1) {
            $querys2[] = 'CREATE INDEX `'.$rec['field_name'].'` ON `'.$tablename.'` (`'.$rec['field_name'].'`)';
            $tmpSqlIndex .= '`'.$rec['field_name'].'`, ';
          }
          break;
        case 'double':
          $tmpSql .= '`'.$rec['field_name'].'` Double DEFAULT NULL, ';
          if ($rec['searchable'] == 1) {
            $querys2[] = 'CREATE INDEX `'.$rec['field_name'].'` ON `'.$tablename.'` (`'.$rec['field_name'].'`)';
            $tmpSqlIndex .= '`'.$rec['field_name'].'`, ';
          }
          break;
        case 'date':
          $tmpSql .= '`'.$rec['field_name'].'` Date DEFAULT NULL, ';
          break;
        case 'time':
          $tmpSql .= '`'.$rec['field_name'].'` Time DEFAULT NULL, ';
          break;
        case 'datetime':
          $tmpSql .= '`'.$rec['field_name'].'` Datetime DEFAULT NULL, ';
          break;
        case 'addressbox':
          $tmpSql .= '`address` varchar(255) DEFAULT NULL, ';
          $tmpSql .= '`address2` varchar(255) DEFAULT NULL, ';
          $tmpSql .= '`showAddress` int(1) DEFAULT NULL, ';
          $tmpSql .= '`clatitude` Double DEFAULT NULL, ';
          $tmpSql .= '`clongitude` Double DEFAULT NULL, ';
          if ($rec['searchable'] == 1) {
            //$querys2[] = 'CREATE INDEX `showAddress` ON `'.$tablename.'` (`showAddress`)';
            $querys2[] = 'CREATE INDEX `clatitude` ON `'.$tablename.'` (`clatitude`)';
            $querys2[] = 'CREATE INDEX `clongitude` ON `'.$tablename.'` (`clongitude`)';
            //$querys2[] = 'CREATE INDEX `address` ON `'.$tablename.'` (`address`)';
            //$querys2[] = 'CREATE INDEX `address2` ON `'.$tablename.'` (`address2`)';
            //$tmpSqlIndex .= '`showAddress`, ';
            $tmpSqlIndex .= '`clatitude`, ';
            $tmpSqlIndex .= '`clongitude`, ';
            //$tmpSqlIndex .= '`address`, ';
            //$tmpSqlIndex .= '`address2`, ';
          }
          break;
      }
    }
    $tmpSql .= '`rc_created_dt` Datetime DEFAULT NULL, ';
    $tmpSql .= '`rc_updated_dt` Datetime DEFAULT NULL, ';
    $tmpSql .= '`rc_deleted_dt` Datetime DEFAULT NULL, ';
    $tmpSql .= '`rc_status` int(1) NOT NULL DEFAULT 1, ';
    $tmpSql .= '`rc_approved` int(1) NOT NULL DEFAULT 0, ';
    $tmpSql .= '`rc_deleted` int(1) NOT NULL DEFAULT 0, ';
    $tmpSql .= 'PRIMARY KEY (`id`), ';
    $tmpSql = substr($tmpSql, 0, -2);
    $tmpSql .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    $querys[] = $tmpSql;
    $tmpSqlIndex = substr($tmpSqlIndex, 0, -2);
    $tmpSqlIndex .= ')';
    $querys2[] = $tmpSqlIndex;
    //echo '<br><br>';
    //print_r($querys);
    //echo '<br><br>';
    //print_r($querys2);
    if (!empty($querys)) {
      foreach ($querys as $q) {
       mysql_query($q) or die(mysql_error());
      }
    }
    if (!empty($querys2)) {
      foreach ($querys2 as $q) {
        @mysql_query($q);
      }
    }
  }
  header("Location: manage.php?module_id=".$_GET['module_id']);
  exit;
}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Fields</title>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script language="javascript">

function addkeyvalue()
{
  $('#keyvaluepairs').append($('#tmp').html());
}
function addkeyvalueEdit()
{
  $('#keyvaluepairsEdit').append($('#tmp').html());
}
</script>
</head>

<body>
<h1>Manage Module &quot;<?php echo $row_rsModule['menu_display_name']; ?>&quot;</h1>
<p><a href="modules.php">Back</a></p>
<?php if (!empty($error)) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>
<h3>Add Fields</h3>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <p>
    <label for="field_name"><strong>Field Name:</strong></label>
    <br>
<input type="text" name="field_name" id="field_name" size="100">
  </p>
  <p><strong>Field Display Name:</strong><br>
<input type="text" name="field_display_name" id="field_display_name" size="100">
  </p>
  <p><strong>Field Type:</strong><br>
<select name="field_type" id="field_type">
  <option value="varchar" selected="selected">Text</option>
  <option value="addressbox">Address Box</option>
  <option value="text">TextArea</option>
  <option value="hidden">Hidden</option>
  <option value="int">Integer</option>
  <option value="double">Float</option>
  <option value="date">Date</option>
  <option value="time">Time</option>
  <option value="datetime">DateTime</option>
  <option value="selectbox">SelectBox</option>
  <option value="interestbox">Interest Box</option>
  <option value="multipleselectbox">Multiple Select Box</option>
  <option value="radio">Radio</option>
  <option value="checkbox">Checkbox</option>
  <option value="richtext">Rich Text Editor</option>
  <option value="images">Images</option>
  <option value="videos">Youtube Videos</option>
  <option value="urls">Web URLS</option>
  <option value="calculation">Calculation</option>
  <option value="noshow">Do Not Show (Admin Purpose)</option>
</select>
  </p>
  <table border="1" cellspacing="1" cellpadding="5">
    <tr>
      <td valign="top"><p>
        <label for="related_information"><strong>Related Information (Json):</strong></label>
        <br>
        <textarea name="related_information" cols="50" rows="5" id="related_information"></textarea>
      </p></td>
      <td valign="top"><input type="button" name="button" id="button" value="Add Key Value Pair" onClick="addkeyvalue();">
        <div id="keyvaluepairs">
        
        </div>
      </td>
    </tr>
  </table>
  <p>
    <input type="checkbox" name="searchable" id="searchable" value="1">
    <label for="searchable"><strong>Searchable</strong></label>
    <input type="checkbox" name="encrypted" id="encrypted">
    <label for="encrypted"><strong>Encrypted </strong></label>
    <input name="required" type="checkbox" id="required" value="1">
    <label for="required"><strong>Required </strong></label>
  </p>
  <p>
    <label for="sorting"><strong>Sorting:</strong></label>
    <br>
    <input type="text" name="sorting" id="sorting" value="<?php echo (($totalRows_rsView + 1) * 5); ?>">
  </p>
  <p><strong>Search Display Name:
    </strong><br>
    <input type="text" name="search_display_name" id="search_display_name">
  </p>
  <p>
    <label for="field_default_value"><strong>Field Default Value:</strong></label>
    <br>
    <input name="field_default_value" type="text" id="field_default_value" size="50">
  </p>
  <p>
    <label for="help_text"><strong>Help Text:</strong><br>
    </label><textarea name="help_text" cols="50" rows="5" id="help_text"></textarea>
  </p>
  <p>
    <label for="field_prefix"><strong>Field Prefix:</strong></label>
    <br>
    <input name="field_prefix" type="text" id="field_prefix" size="45">
  </p>
  <p>
    <label for="field_suffix"><strong>Field Suffix:</strong><br>
    </label>
    <input name="field_suffix" type="text" id="field_suffix" size="45">
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Create New Field">
  </p>
  <input type="hidden" name="MM_insert" value="form1">
  <input name="module_id" type="hidden" id="module_id" value="<?php echo $_GET['module_id']; ?>">
</form>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <h3>View Fields</h3>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td><strong>field_id</strong></td>
      <td><strong>module_id</strong></td>
      <td><strong>field_name</strong></td>
      <td><strong>field_display_name</strong></td>
      <td><strong>field_type</strong></td>
      <td><strong>related_information</strong></td>
      <td><strong>searchable</strong></td>
      <td><strong>sorting</strong></td>
      <td><strong>encrypted</strong></td>
      <td><strong>search_display_name</strong></td>
      <td><strong>help_text</strong></td>
      <td><strong>edit</strong></td>
      <td><strong>delete</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsView['field_id']; ?></td>
        <td><?php echo $row_rsView['module_id']; ?></td>
        <td><a href="manage.php?module_id=<?php echo $_GET['module_id']; ?>&field_id=<?php echo $row_rsView['field_id']; ?>#edit"><?php echo $row_rsView['field_name']; ?></a></td>
        <td><?php echo $row_rsView['field_display_name']; ?></td>
        <td><?php echo $row_rsView['field_type']; ?></td>
        <td><?php if (!empty($row_rsView['related_information'])) {
          $str = stripslashes($row_rsView['related_information']);
          $arr = json_decode($str, 1);
          print_r($arr);
          echo '<br>';
          echo $str;
        }?></td>
        <td><?php echo $row_rsView['searchable']; ?></td>
        <td><?php echo $row_rsView['sorting']; ?></td>
        <td><?php echo $row_rsView['encrypted']; ?></td>
        <td><?php echo $row_rsView['search_display_name']; ?></td>
        <td><?php echo $row_rsView['help_text']; ?></td>
        <td><a href="manage.php?module_id=<?php echo $_GET['module_id']; ?>&field_id=<?php echo $row_rsView['field_id']; ?>#edit">edit</a></td>
        <td><a href="manage.php?module_id=<?php echo $_GET['module_id']; ?>&delete_field_id=<?php echo $row_rsView['field_id']; ?>" onClick="var a = confirm('do you really want to delete this field?'); return a;">delete</a></td>
      </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <p><a href="manage.php?module_id=<?php echo $_GET['module_id']; ?>&create=1">Create Table</a></p>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
  <h3>Edit Field <a name="edit"></a></h3>
    <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
      <table>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Field Name:</strong></td>
          <td valign="top"><input type="text" name="field_name" value="<?php echo htmlentities($row_rsEdit['field_name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Field Display Name:</strong></td>
          <td valign="top"><input type="text" name="field_display_name" value="<?php echo htmlentities($row_rsEdit['field_display_name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Field Type:</strong></td>
          <td valign="top"><select name="field_type" id="field_type">
            <option value="varchar" <?php if (!(strcmp("varchar", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Text</option>
            <option value="addressbox" <?php if (!(strcmp("addressbox", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Address Box</option>
            <option value="text" <?php if (!(strcmp("text", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>TextArea</option>
            <option value="hidden" <?php if (!(strcmp("hidden", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Hidden</option>
            <option value="int" <?php if (!(strcmp("int", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Integer</option>
            <option value="double" <?php if (!(strcmp("double", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Float</option>
            <option value="date" <?php if (!(strcmp("date", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Date</option>
            <option value="time" <?php if (!(strcmp("time", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Time</option>
            <option value="datetime" <?php if (!(strcmp("datetime", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>DateTime</option>
            <option value="selectbox" <?php if (!(strcmp("selectbox", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>SelectBox</option>
            <option value="interestbox" <?php if (!(strcmp("interestbox", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Interest Box</option>
            <option value="multipleselectbox" <?php if (!(strcmp("multipleselectbox", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Multiple Select Box</option>
            <option value="radio" <?php if (!(strcmp("radio", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Radio</option>
            <option value="checkbox" <?php if (!(strcmp("checkbox", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Checkbox</option>
            <option value="richtext" <?php if (!(strcmp("richtext", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Rich Text Editor</option>
            <option value="images" <?php if (!(strcmp("images", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Images</option>
            <option value="videos" <?php if (!(strcmp("videos", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Youtube Videos</option>
            <option value="urls" <?php if (!(strcmp("urls", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Web URLS</option>
            <option value="calculation" <?php if (!(strcmp("calculation", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Calculation</option>
            <option value="noshow" <?php if (!(strcmp("noshow", $row_rsEdit['field_type']))) {echo "selected=\"selected\"";} ?>>Do Not Show (Admin Purpose)</option>
          </select></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Searchable:</strong></td>
          <td valign="top"><input type="checkbox" name="searchable" value=""  <?php if (!(strcmp(htmlentities($row_rsEdit['searchable'], ENT_COMPAT, 'UTF-8'),1))) {echo "checked=\"checked\"";} ?>></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right" valign="top"><strong>Related Information:</strong></td>
          <td valign="top"><textarea name="related_information" cols="50" rows="5"><?php echo htmlentities($row_rsEdit['related_information'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
          <td valign="top"><input type="button" name="button" id="button" value="Add Key Value Pair" onClick="addkeyvalueEdit();">
        <div id="keyvaluepairsEdit">
        <?php if (!empty($row_rsEdit['related_information'])) { 
          $info = json_decode($row_rsEdit['related_information'], 1);
          $counter = 0;
          foreach ($info as $k => $v) {
        ?>
        <label for="ri[key][<?php echo $counter; ?>]">Key:</label>
        <input type="text" name="ri[key][<?php echo $counter; ?>]" id="ri[key][<?php echo $counter; ?>]" value="<?php echo $k; ?>">
        <label for="ri[value][]">Value:</label>
        <input type="text" name="ri[value][<?php echo $counter; ?>]" id="ri[value][<?php echo $counter; ?>]" value="<?php echo $v; ?>"><br />
        <?php $counter++; } } ?>
        </div></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Sorting:</strong></td>
          <td valign="top"><input type="text" name="sorting" value="<?php echo htmlentities($row_rsEdit['sorting'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Encrypted:</strong></td>
          <td valign="top"><input type="checkbox" name="encrypted" value=""  <?php if (!(strcmp(htmlentities($row_rsEdit['encrypted'], ENT_COMPAT, 'UTF-8'),1))) {echo "checked=\"checked\"";} ?>></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Required:</strong></td>
          <td valign="top"><input <?php if (!(strcmp($row_rsEdit['required'],1))) {echo "checked=\"checked\"";} ?> name="required" type="checkbox" id="required" value="1"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Search Display Name: </strong></td><td valign="top"><input name="search_display_name" type="text" id="search_display_name" value="<?php echo $row_rsEdit['search_display_name']; ?>"></td>
        <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Field Default Value:</strong></td>
          <td valign="top"><input name="field_default_value" type="text" id="field_default_value" value="<?php echo $row_rsEdit['field_default_value']; ?>" size="50"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Help Text: </strong></td>
          <td valign="top"><textarea name="help_text" cols="50" rows="5" id="help_text"><?php echo htmlentities($row_rsEdit['help_text'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Field Prefix: </strong></td>
          <td valign="top"><input name="field_prefix" type="text" id="field_prefix" value="<?php echo $row_rsEdit['field_prefix']; ?>" size="45"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Field Suffix:</strong></td>
          <td valign="top"><input name="field_suffix" type="text" id="field_suffix" value="<?php echo $row_rsEdit['field_suffix']; ?>" size="45"></td>
          <td valign="top">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap>&nbsp;</td>
          <td valign="top"><input type="submit" value="Update record"></td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form2">
      <input type="hidden" name="field_id" value="<?php echo $row_rsEdit['field_id']; ?>">
  </form>
    <?php } // Show if recordset not empty ?>
<div id="tmp" style="display:none;">
  <label for="ri[key][]">Key:</label>
  <input type="text" name="ri[key][]" id="ri[key][]">
  <label for="ri[value][]">Value:</label>
  <input type="text" name="ri[value][]" id="ri[value][]"><br />
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsModule);

mysql_free_result($rsView);

mysql_free_result($rsEdit);
?>

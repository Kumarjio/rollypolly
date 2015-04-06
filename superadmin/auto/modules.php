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

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_Copy"])) && ($_POST["MM_Copy"] == "1") && !empty($_GET['copy_id'])) {
  $query1 = "INSERT INTO z_modules (`module_name`, `menu_display_name`, `module_status`, `menu_hidden`, `parent`, `display_list_template`, `display_detail_template`, `browse_page`, `my_page`, `new_page`, `approval_needed`, `page_title`, `module_info_page`, `module_sorting`, `feature_comments`, `feature_rating`, `feature_like`, `feature_businesses`, `feature_products`, `search_box`, `paid_module`, `paid_message_any_one`, `paid_message_user`, `paid_posting`, `paid_amount`, `paid_posting_categorybased`, `paid_posting_categories`, `user_points_matching`, `custom_points_matching`, `custom_user_payment`, `custom_user_payment_field`, `showDate`, `showprofilepic`, `showmappic`, `showallmappics`, `messages`, `title_field`, `feature_charts`, `default_sorting_field`, `default_sorting_type`, `default_max_rows`) SELECT ".GetSQLValueString($_POST['module_name'], 'text').", ".GetSQLValueString($_POST['menu_display_name'], 'text').", `module_status`, `menu_hidden`, `parent`, `display_list_template`, `display_detail_template`, `browse_page`, `my_page`, `new_page`, `approval_needed`, `page_title`, `module_info_page`, `module_sorting`, `feature_comments`, `feature_rating`, `feature_like`, `feature_businesses`, `feature_products`, `search_box`, `paid_module`, `paid_message_any_one`, `paid_message_user`, `paid_posting`, `paid_amount`, `paid_posting_categorybased`, `paid_posting_categories`, `user_points_matching`, `custom_points_matching`, `custom_user_payment`, `custom_user_payment_field`, `showDate`, `showprofilepic`, `showmappic`, `showallmappics`, `messages`, `title_field`, `feature_charts`, `default_sorting_field`, `default_sorting_type`, `default_max_rows` FROM z_modules WHERE module_id = ".GetSQLValueString($_GET['copy_id'], 'int');
  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($query1, $connMain) or die(mysql_error());
  $newId = mysql_insert_id();
  $query1 = "INSERT INTO z_modules_fields (`module_id`, `field_name`, `field_display_name`, `field_type`, `searchable`, `related_information`, `sorting`, `encrypted`, `search_display_name`, `help_text`, `required`) SELECT ".$newId.", `field_name`, `field_display_name`, `field_type`, `searchable`, `related_information`, `sorting`, `encrypted`, `search_display_name`, `help_text`, `required` FROM z_modules_fields WHERE module_id = ".GetSQLValueString($_GET['copy_id'], 'int');
  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($query1, $connMain) or die(mysql_error());
  header("Location: modules.php");
  exit;
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO z_modules (module_name, menu_display_name, parent, display_list_template, display_detail_template, browse_page, my_page, new_page, detail_page, approval_needed, page_title, module_info_page, module_sorting, feature_comments, feature_rating, feature_like, feature_businesses, feature_products, search_box, paid_module, paid_message_any_one, paid_message_user, paid_posting, paid_amount, user_points_matching, custom_points_matching, custom_user_payment, custom_user_payment_field, showDate, showprofilepic, showmappic, showallmappics, messages, title_field, feature_charts) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['module_name'], "text"),
                       GetSQLValueString($_POST['menu_display_name'], "text"),
                       GetSQLValueString($_POST['parent'], "text"),
                       GetSQLValueString($_POST['display_list_template'], "text"),
                       GetSQLValueString($_POST['display_detail_template'], "text"),
                       GetSQLValueString(isset($_POST['browse_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['my_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['new_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['detail_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['approval_needed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['page_title'], "text"),
                       GetSQLValueString($_POST['module_info_page'], "text"),
                       GetSQLValueString($_POST['module_sorting'], "int"),
                       GetSQLValueString(isset($_POST['feature_comments']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_rating']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_like']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_businesses']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_products']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['search_box']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_module']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_message_any_one']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_message_user']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_posting']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['paid_amount'], "double"),
                       GetSQLValueString(isset($_POST['user_points_matching']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['custom_points_matching']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['custom_user_payment']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['custom_user_payment_field'], "text"),
                       GetSQLValueString(isset($_POST['showDate']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['showprofilepic']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['showmappic']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['showallmappics']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['messages']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['title_field'], "text"),
                       GetSQLValueString(isset($_POST['feature_charts']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE z_modules SET module_name=%s, menu_display_name=%s, module_status=%s, menu_hidden=%s, parent=%s, display_list_template=%s, browse_page=%s, my_page=%s, new_page=%s, detail_page=%s, approval_needed=%s, page_title=%s, module_info_page=%s, module_sorting=%s, feature_comments=%s, feature_rating=%s, feature_like=%s, feature_businesses=%s, feature_products=%s, search_box=%s, paid_module=%s, paid_message_any_one=%s, paid_message_user=%s, paid_posting=%s, paid_amount=%s, user_points_matching=%s, custom_points_matching=%s, custom_user_payment=%s, custom_user_payment_field=%s, showDate=%s, showprofilepic=%s, showmappic=%s, showallmappics=%s, messages=%s, title_field=%s, feature_charts=%s WHERE module_id=%s",
                       GetSQLValueString($_POST['module_name'], "text"),
                       GetSQLValueString($_POST['menu_display_name'], "text"),
                       GetSQLValueString($_POST['module_status'], "int"),
                       GetSQLValueString($_POST['menu_hidden'], "int"),
                       GetSQLValueString($_POST['parent'], "text"),
                       GetSQLValueString($_POST['display_list_template'], "text"),
                       GetSQLValueString(isset($_POST['browse_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['my_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['new_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['detail_page']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['approval_needed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['page_title'], "text"),
                       GetSQLValueString($_POST['module_info_page'], "text"),
                       GetSQLValueString($_POST['module_sorting'], "int"),
                       GetSQLValueString(isset($_POST['feature_comments']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_rating']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_like']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_businesses']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['feature_products']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['search_box']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_module']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_message_any_one']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_message_user']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['paid_posting']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['paid_amount'], "double"),
                       GetSQLValueString(isset($_POST['user_points_matching']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['custom_points_matching']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['custom_user_payment']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['custom_user_payment_field'], "text"),
                       GetSQLValueString(isset($_POST['showDate']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['showprofilepic']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['showmappic']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['showallmappics']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['messages']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['title_field'], "text"),
                       GetSQLValueString(isset($_POST['feature_charts']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['module_id'], "int"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($updateSQL, $connMain) or die(mysql_error());
}

$maxRows_rsView = 100;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connMain, $connMain);
$query_rsView = "SELECT * FROM z_modules ORDER BY module_sorting ASC";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$colname_rsEdit = "-1";
if (isset($_GET['edit_id'])) {
  $colname_rsEdit = $_GET['edit_id'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsEdit = sprintf("SELECT * FROM z_modules WHERE module_id = %s", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $connMain) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);

$queryString_rsView = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsView") == false && 
        stristr($param, "totalRows_rsView") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsView = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $queryString_rsView);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Module Management</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Modules</h1>
<p><a href="index.php">Go to Main Page</a></p>
<h3>Add a New Module</h3>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <p>
    <label for="module_name"><strong>Module Name:</strong></label>
    <br>
  <input type="text" name="module_name" id="module_name">
  </p>
  <p>    <strong>Menu Display Name: </strong><br>
    <input type="text" name="menu_display_name" id="menu_display_name">
  </p>
  <p>
    <label for="parent"><strong>Parent:</strong><br>
    </label>
    <input name="parent" type="text" id="parent" value="Root">
  </p>
  <p>
    <label for="display_list_template"><strong>Display List Template:</strong></label>
    <br>
    <input name="display_list_template" type="text" id="display_list_template" value="Default">
  </p>
  <p>
    <label for="display_detail_template"><strong>Display Detail Template:</strong></label>
    <br>
    <input name="display_detail_template" type="text" id="display_detail_template" value="Default">
  </p>
  <p>
    <input name="browse_page" type="checkbox" id="browse_page" value="1" checked>
    <label for="browse_page">Browse Page </label>
    <input name="my_page" type="checkbox" id="my_page" value="1" checked>
    <label for="my_page">My Page
      <input name="new_page" type="checkbox" id="new_page" value="1" checked>
    New Page</label>
    <input name="approval_needed" type="checkbox" id="approval_needed" value="1">
    <label for="approval_needed">Admin Approval Needed</label>
    <input name="detail_page" type="checkbox" id="detail_page" value="1" checked>
Detail Page 
<input name="showDate" type="checkbox" id="showDate" checked>
showDate
  <input name="messages" type="checkbox" id="messages" checked>
  <label for="messages">Messages </label>
  </p>
  <p>
    <input name="feature_comments" type="checkbox" id="feature_comments" value="1">
    <label for="feature_comments">Feature Comments</label>
    <input name="feature_rating" type="checkbox" id="feature_rating" value="1">
    <label for="feature_rating">Feature Rating</label>
    <input name="feature_like" type="checkbox" id="feature_like" value="1">
    <label for="feature_like">Feature Likes</label>
    <input name="feature_businesses" type="checkbox" id="feature_businesses" value="1">
    <label for="feature_businesses">Feature Businesses</label>
    <input name="feature_products" type="checkbox" id="feature_products" value="1">
  <label for="feature_products">Feature Products</label> 
  <input type="checkbox" name="feature_charts" id="feature_charts">
  <label for="feature_charts">Feature Charts </label>
  </p>
  <p>
    <input name="search_box" type="checkbox" id="search_box" checked="checked">
    <label for="search_box">Search Box </label>
    <input type="checkbox" name="user_points_matching" id="user_points_matching">
    Show 
    <label for="user_points_matching">User Points Matching </label>
    <input type="checkbox" name="custom_points_matching" id="custom_points_matching">
    <label for="custom_points_matching">Show Custom Points Matching</label>
  </p>
  <p>
    <input name="paid_module" type="checkbox" id="paid_module">
    <label for="paid_module">Paid Module 
      <input name="paid_message_any_one" type="checkbox" id="paid_message_any_one">
    Paid Messaging By Any One 
    <input type="checkbox" name="paid_message_user" id="paid_message_user">
    Paid Messaging by client to contact owner
    <input type="checkbox" name="paid_posting" id="paid_posting">
    Paid Posting</label>
  </p>
  <p>
    <label for="paid_amount"><strong>Paid Amount:</strong></label>
    <br>
    <input name="paid_amount" type="text" id="paid_amount" placeholder="Enter Amount">
  </p>
  <p>
      <input type="checkbox" name="custom_user_payment" id="custom_user_payment">
    <label for="custom_user_payment">Custom User Payment </label>
    <input type="text" name="custom_user_payment_field" id="custom_user_payment_field">
    <label for="custom_user_payment_field">Custom User Payment Field</label>
  </p>
  <p>
    <input name="showprofilepic" type="checkbox" id="showprofilepic" checked>
    <label for="showprofilepic">Show Profile Pic on Browse page if other image is missing</label>
  </p>
  <p>
    <input type="checkbox" name="showmappic" id="showmappic">
    <label for="showmappic">Show Map pic on Browse page if other image is missing</label>
  </p>
  <p>
    <input type="checkbox" name="showallmappics" id="showallmappics">
    <label for="showallmappics">Show All Degree Map pics on detail page. </label>
  </p>
  <p>
    <label for="page_title"><strong>Page Title:</strong><br>
    </label>
    <input type="text" name="page_title" id="page_title">
  </p>
  <p>
    <label for="module_info_page"><strong>Info Page Details:</strong><br>
    </label>
    <textarea name="module_info_page" cols="50" rows="5" id="module_info_page"></textarea>
  </p>
  <p>
    <label for="module_sorting"><strong>Sorting:</strong></label>
    <br>
    <input type="text" name="module_sorting" id="module_sorting" value="<?php echo (($totalRows_rsView + 1) * 5); ?>">
  </p>
  <p>
    <label for="title_field"><strong>Title Field:</strong></label>
    <br>
    <input name="title_field" type="text" id="title_field" value="title" size="45">
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Create New Module">
  </p>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php if ($totalRows_rsView > 0) { // Show if recordset not empty ?>
  <h3>View All Modules</h3>
  <p> Records <?php echo ($startRow_rsView + 1) ?> to <?php echo min($startRow_rsView + $maxRows_rsView, $totalRows_rsView) ?> of <?php echo $totalRows_rsView ?> &nbsp;
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView); ?>">First</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsView > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView); ?>">Last</a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
  </p>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <td valign="top"><strong>module_id</strong></td>
      <td valign="top"><strong>module_name</strong></td>
      <td valign="top"><strong>menu_display_name</strong></td>
      <td valign="top"><strong>module_status</strong></td>
      <td valign="top"><strong>parent</strong></td>
      <td valign="top"><strong>sorting</strong></td>
      <td valign="top"><strong>display_list_template</strong></td>
      <td valign="top"><strong>display_detail_template</strong></td>
      <td valign="top"><strong>browse_page</strong></td>
      <td valign="top"><strong>my_page</strong></td>
      <td valign="top"><strong>approval_needed</strong></td>
      <td valign="top"><strong>page_title</strong></td>
      <td valign="top"><strong>Create Table</strong></td>
      <td valign="top"><strong>Copy</strong></td>
      <td valign="top"><strong>Edit</strong></td>
      <td valign="top"><strong>Manage</strong></td>
    </tr>
    <?php do { ?>
      <tr>
        <td valign="top"><?php echo $row_rsView['module_id']; ?></td>
        <td valign="top"><?php echo $row_rsView['module_name']; ?></td>
        <td valign="top"><?php echo $row_rsView['menu_display_name']; ?></td>
        <td valign="top"><?php echo $row_rsView['module_status']; ?></td>
        <td valign="top"><?php echo $row_rsView['parent']; ?></td>
        <td valign="top"><?php echo $row_rsView['module_sorting']; ?></td>
        <td valign="top"><?php echo $row_rsView['display_list_template']; ?></td>
        <td valign="top"><?php echo $row_rsView['display_detail_template']; ?></td>
        <td valign="top"><?php echo $row_rsView['browse_page']; ?></td>
        <td valign="top"><?php echo $row_rsView['my_page']; ?></td>
        <td valign="top"><?php echo $row_rsView['approval_needed']; ?></td>
        <td valign="top"><?php echo $row_rsView['page_title']; ?></td>
        <td valign="top"><a href="manage.php?module_id=<?php echo $row_rsView['module_id']; ?>&create=1">Create Table</a></td>
        <td valign="top"><a href="modules.php?copy_id=<?php echo $row_rsView['module_id']; ?>#copy">Copy</a></td>
        <td valign="top"><a href="modules.php?edit_id=<?php echo $row_rsView['module_id']; ?>#edit">Edit</a></td>
        <td valign="top"><a href="manage.php?module_id=<?php echo $row_rsView['module_id']; ?>">Manage</a></td>
      </tr>
      <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsEdit > 0) { // Show if recordset not empty ?>
  <h3>Edit Module</h3>
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
  <a name="edit"></a>
    <table>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Module Name:</strong></td>
        <td valign="top"><input type="text" name="module_name" value="<?php echo htmlentities($row_rsEdit['module_name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Menu Display Name:</strong></td>
        <td valign="top"><input type="text" name="menu_display_name" value="<?php echo htmlentities($row_rsEdit['menu_display_name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Module Status:</strong></td>
        <td valign="top"><table>
          <tr>
            <td><input type="radio" name="module_status" value="1" <?php if (!(strcmp(htmlentities($row_rsEdit['module_status'], ENT_COMPAT, 'UTF-8'),1))) {echo "checked=\"checked\"";} ?>>
              Active</td>
            </tr>
          <tr>
            <td><input type="radio" name="module_status" value="0" <?php if (!(strcmp(htmlentities($row_rsEdit['module_status'], ENT_COMPAT, 'UTF-8'),0))) {echo "checked=\"checked\"";} ?>>
              Inactive</td>
            </tr>
        </table></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Menu Hidden:</strong></td>
        <td valign="top"><table>
          <tr>
            <td><input type="radio" name="menu_hidden" value="1" <?php if (!(strcmp(htmlentities($row_rsEdit['menu_hidden'], ENT_COMPAT, 'UTF-8'),1))) {echo "checked=\"checked\"";} ?>>
              Yes</td>
          </tr>
          <tr>
            <td><input type="radio" name="menu_hidden" value="0" <?php if (!(strcmp(htmlentities($row_rsEdit['menu_hidden'], ENT_COMPAT, 'UTF-8'),0))) {echo "checked=\"checked\"";} ?>>
              No</td>
          </tr>
        </table></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Parent:</strong></td>
        <td valign="top"><input name="parent" type="text" id="parent" value="<?php echo $row_rsEdit['parent']; ?>"></td>
      </tr>
      <tr valign="baseline"><td align="right" valign="top" nowrap><label for="display_list_template2"><strong>Display List Template:</strong></label></td>
        <td valign="top"><input name="display_list_template" type="text" id="display_list_template" value="<?php echo $row_rsEdit['display_list_template']; ?>"></td></tr><tr valign="baseline">
        <td align="right" valign="top" nowrap><label for="display_detail_template2"><strong>Display Detail Template:</strong></label></td>
        <td valign="top"><input name="display_detail_template" type="text" id="display_detail_template" value="<?php echo $row_rsEdit['display_detail_template']; ?>"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Browse Page:</strong></td><td valign="top"><input <?php if (!(strcmp($row_rsEdit['browse_page'],1))) {echo "checked=\"checked\"";} ?> name="browse_page" type="checkbox" id="browse_page" value="1"></td></tr><tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>My Page:</strong></td><td valign="top"><input <?php if (!(strcmp($row_rsEdit['my_page'],1))) {echo "checked=\"checked\"";} ?> name="my_page" type="checkbox" id="my_page" value="1"></td></tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>New Page:</strong></td>
          <td valign="top"><input <?php if (!(strcmp($row_rsEdit['new_page'],1))) {echo "checked=\"checked\"";} ?> name="new_page" type="checkbox" id="my_page3" value="1"></td>
        </tr>
        <tr valign="baseline"><td align="right" valign="top" nowrap><label for="approval_needed"><strong>Admin Approval Needed:</strong></label></td><td valign="top"><input <?php if (!(strcmp($row_rsEdit['approval_needed'],1))) {echo "checked=\"checked\"";} ?> name="approval_needed" type="checkbox" id="approval_needed" value="1"></td></tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Detail Page:</strong></td>
          <td valign="top"><input <?php if (!(strcmp($row_rsEdit['detail_page'],1))) {echo "checked=\"checked\"";} ?> name="detail_page" type="checkbox" id="detail_page" value="1"></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Page Title:</strong></td>
          <td valign="top"><input name="page_title" type="text" id="page_title" value="<?php echo $row_rsEdit['page_title']; ?>"></td>
      </tr>
      <tr valign="baseline">
          <td align="right" valign="top" nowrap><strong>Info Page Details: </strong></td>
          <td valign="top"><textarea name="module_info_page" cols="50" rows="5" id="module_info_page"><?php echo htmlentities($row_rsEdit['module_info_page'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Sorting:</strong></td>
        <td valign="top"><input name="module_sorting" type="text" id="module_sorting" value="<?php echo $row_rsEdit['module_sorting']; ?>"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Feature Comments</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['feature_comments'],1))) {echo "checked=\"checked\"";} ?> name="feature_comments" type="checkbox" id="feature_comments2" value="1"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Feature Rating</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['feature_rating'],1))) {echo "checked=\"checked\"";} ?> name="feature_rating" type="checkbox" id="feature_rating2" value="1"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Feature Likes</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['feature_like'],1))) {echo "checked=\"checked\"";} ?> name="feature_like" type="checkbox" id="feature_like2" value="1"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>
          <label for="feature_businesses2">Feature Businesses</label>
        </strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['feature_businesses'],1))) {echo "checked=\"checked\"";} ?> name="feature_businesses" type="checkbox" id="feature_businesses2" value="1"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Feature Products</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['feature_products'],1))) {echo "checked=\"checked\"";} ?> name="feature_products" type="checkbox" id="feature_products2" value="1"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Feature Charts:</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['feature_charts'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="feature_charts" id="feature_charts"></td>
      </tr>
      <tr valign="baseline"><td align="right" valign="top" nowrap><strong>Search Box</strong></td><td valign="top"><input <?php if (!(strcmp($row_rsEdit['search_box'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="search_box" id="search_box2"></td></tr><tr valign="baseline"><td align="right" valign="top" nowrap><strong>Show
            <label for="user_points_matching2">User Points Matching</label>
      </strong></td><td valign="top"><input <?php if (!(strcmp($row_rsEdit['user_points_matching'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="user_points_matching" id="user_points_matching2"></td></tr><tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Show Custom Points Matching</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['custom_points_matching'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="custom_points_matching" id="custom_points_matching2"></td>
      </tr>
      <tr valign="baseline">
            <td align="right" valign="top" nowrap><strong>Paid Module </strong></td>
            <td valign="top"><input <?php if (!(strcmp($row_rsEdit['paid_module'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="paid_module" id="paid_module2"></td>
      </tr>
      <tr valign="baseline">
            <td align="right" valign="top" nowrap><strong>Paid Messaging By Any One</strong></td>
            <td valign="top"><input <?php if (!(strcmp($row_rsEdit['paid_message_any_one'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="paid_message_any_one" id="paid_message_any_one2"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Paid Messaging by client to contact owner</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['paid_message_user'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="paid_message_user" id="paid_message_user2"></td>
      </tr>
      <tr valign="baseline">
            <td align="right" valign="top" nowrap><strong>
              <label for="paid_module3">Paid Posting</label>
            </strong></td>
            <td valign="top"><input <?php if (!(strcmp($row_rsEdit['paid_posting'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="paid_posting" id="paid_posting2"></td>
      </tr>
      <tr valign="baseline">
            <td align="right" valign="top" nowrap><label for="paid_amount2"><strong>Paid Amount:</strong></label></td>
            <td valign="top"><input name="paid_amount" type="text" id="paid_amount2" placeholder="Enter Amount" value="<?php echo $row_rsEdit['paid_amount']; ?>"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap>
            <strong>
            <label for="paid_posting4">Custom User Payment</label>
            </strong></td><td valign="top"><input <?php if (!(strcmp($row_rsEdit['custom_user_payment'],1))) {echo "checked=\"checked\"";} ?> name="custom_user_payment" type="checkbox" id="custom_user_payment2" value="1">
      </p></td></tr><tr valign="baseline">
        <td align="right" valign="top" nowrap>
          <strong>
          <label for="custom_user_payment_field2">Custom User Payment Field</label>
          </strong></td>
        <td valign="top">
  <input name="custom_user_payment_field" type="text" id="custom_user_payment_field2" value="<?php echo $row_rsEdit['custom_user_payment_field']; ?>"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Show Date:</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['showDate'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="showDate" id="showDate"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Show Profile Pic on Browse page if other image is missing&nbsp;</strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['showprofilepic'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="showprofilepic" id="showprofilepic">
        </td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><label for="showmappic2"><strong>Show Map pic on Browse page if other image is missing</strong></label>          &nbsp;</td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['showmappic'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="showmappic" id="showmappic2">
        </td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><label for="showallmappics2"><strong>Show All Degree Map pics on detail page.</strong></label>          &nbsp;</td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['showallmappics'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="showallmappics" id="showallmappics2">
        </td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Show Messages: </strong></td>
        <td valign="top"><input <?php if (!(strcmp($row_rsEdit['messages'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="messages" id="messages"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap><strong>Title Field: </strong></td>
        <td valign="top"><input name="title_field" type="text" id="title_field" value="<?php echo $row_rsEdit['title_field']; ?>" size="45"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="top" nowrap>&nbsp;</td>
        <td valign="top"><input type="submit" value="Update record"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form2">
    <input type="hidden" name="module_id" value="<?php echo $row_rsEdit['module_id']; ?>">
  </form>
  <?php } // Show if recordset not empty ?>


<?php if (!empty($_GET['copy_id'])) { ?>
<h3>Copy Module</h3>
<form id="form3" name="form3" method="post">
  <p>
    <label for="textfield"><strong>Module Name:</strong></label>
    <br>
    <input type="text" name="module_name" id="module_name_2">
  </p>
  <p><strong>Menu Display Name:
    </strong><br>
    <input type="text" name="menu_display_name" id="menu_display_name_2">
  </p>
  <p>
    <input type="submit" name="submit2" id="submit2" value="Copy Module">
    <input name="MM_Copy" type="hidden" id="MM_Copy" value="1">
    <input name="copy_id" type="hidden" id="copy_id" value="<?php echo $_GET['copy_id']; ?>">
  </p>
</form>
<?php } ?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsEdit);
?>

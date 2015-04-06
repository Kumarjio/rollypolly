<?php require_once('../Connections/connHm.php'); ?>
<?php
include_once('start.php');
print_r($_GET);
?>
<?php
$colid_rsCurrent = "-1";
if (isset($_GET['id'])) {
  $colid_rsCurrent = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
$coluserid_rsCurrent = "-1";
if (isset($_SESSION['user_id'])) {
  $coluserid_rsCurrent = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}
$colname_rsCurrent = "-1";
if (isset($_GET['pid'])) {
  $colname_rsCurrent = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsCurrent = sprintf("SELECT * FROM home_product_management_details WHERE pid = %s AND id = %s AND user_id = %s", $colname_rsCurrent,$colid_rsCurrent,$coluserid_rsCurrent);
$rsCurrent = mysql_query($query_rsCurrent, $connHm) or die(mysql_error());
$row_rsCurrent = mysql_fetch_assoc($rsCurrent);
$totalRows_rsCurrent = mysql_num_rows($rsCurrent);

$colname_rsPid = "-1";
if (isset($_GET['pid'])) {
  $colname_rsPid = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsPid = sprintf("SELECT * FROM home_product_management_details WHERE detail_id = %s", $colname_rsPid);
$rsPid = mysql_query($query_rsPid, $connHm) or die(mysql_error());
$row_rsPid = mysql_fetch_assoc($rsPid);
$totalRows_rsPid = mysql_num_rows($rsPid);

$colname_rsPlace = "-1";
if (isset($_GET['id'])) {
  $colname_rsPlace = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsPlace = sprintf("SELECT * FROM home_product_management WHERE id = %s", $colname_rsPlace);
$rsPlace = mysql_query($query_rsPlace, $connHm) or die(mysql_error());
$row_rsPlace = mysql_fetch_assoc($rsPlace);
$totalRows_rsPlace = mysql_num_rows($rsPlace);
?>
<div class="post">
	<h1 class="title"><a href="#"><?php echo $row_rsPlace['title']; ?> :: Add New Item Under <?php if($_GET['pid']==0) echo 'Root'; else echo $row_rsPid['name']; ?></a></h1>
	<p class="byline"><small>Breadcrumb will come here.</small></p>
	<div class="entry">
		<div id="submitMsg"></div>
		<form method="post" name="form1" action="" onSubmit="return false;">
		  <table align="center">
			<tr valign="baseline">
			  <td nowrap align="right">Name:</td>
			  <td><input type="text" name="name" value="" id="name" size="32" onKeyDown="code=handleKey2(event);if(code=='13') {str=getFormElements(this.form);doAjaxLoadingText('home_product_management/addsubmit.php','POST','',str,'submitMsg','yes','Loading...','0','','','','','0');}"></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap align="right">&nbsp;</td>
			  <td><input type="button" value="Add New Item" onclick="str=getFormElements(this.form);doAjaxLoadingText('home_product_management/addsubmit.php','POST','',str,'submitMsg','yes','Loading...','0','','','','','0');"></td>
			</tr>
		  </table>		  
		  	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
			<input type="hidden" name="site_id" value="">
			<input type="hidden" name="user_id" value="">
			<input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
			<input type="hidden" name="created_dt" value="<?php echo date('Y-m-d H:i:s'); ?>">
			<input type="hidden" name="MM_insert" value="form1">
		</form>
		<?php include('menu.php'); ?>
	</div>
</div>
<div id="list_hpm">
<?php if ($totalRows_rsCurrent > 0) { // Show if recordset not empty ?>
<div class="post">
	<h1 class="title"><a href="#"><?php echo $row_rsPlace['title']; ?> :: Item Under <?php if($_GET['pid']==0) echo 'Root'; else echo $row_rsPid['name']; ?></a></h1>
	<p class="byline"><small>List of Items Under <?php if($_GET['pid']==0) echo 'Root'; else echo $row_rsPid['name']; ?></small></p>
  <div class="entry">
		<?php do { ?>
		  <p><a href="#" onclick="doAjaxLoadingText('home_product_management/add.php','GET','pid=<?php echo $row_rsCurrent['detail_id']; ?>&id=<?php echo $row_rsCurrent['id']; ?>','','centerDiv','yes','Loading...','0','','','','','0');"><?php echo $row_rsCurrent['name']; ?></a> [<a href="#">Edit</a> | <a href="#">Delete</a>]</p>
		  <?php } while ($row_rsCurrent = mysql_fetch_assoc($rsCurrent)); ?>
	</div>
</div>
<?php } // Show if recordset not empty ?>
</div>
<?php
mysql_free_result($rsCurrent);

mysql_free_result($rsPid);

mysql_free_result($rsPlace);
?>

<?php require_once('../Connections/conn.php'); ?>
<?php session_start(); ?>
<?php
$coluserid_rsOne = "-1";
if (isset($_GET['user_id'])) {
  $coluserid_rsOne = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsOne = sprintf("SELECT * FROM procentris_list, procentris_user_client WHERE procentris_list.pid = 0 AND procentris_list.list_id = procentris_user_client.list_id AND procentris_user_client.user_id = %s GROUP BY procentris_user_client.list_id ORDER BY procentris_list.list ASC", $coluserid_rsOne);
$rsOne = mysql_query($query_rsOne, $conn) or die(mysql_error());
$row_rsOne = mysql_fetch_assoc($rsOne);
$totalRows_rsOne = mysql_num_rows($rsOne);
?>
<form name="formAction" action="" method="post">
<input type="hidden" name="mydate" value="<?php echo $_GET['mydate']; ?>">
Client: <select name="category" onChange="document.getElementById('newProject').innerHTML = ''; document.getElementById('newTask').innerHTML = ''; document.getElementById('newTime').innerHTML = ''; doAjax('ajaxAddNewProject.php','GET','user_id=<?php echo $_GET['user_id']; ?>&list_id='+this.value,'','newProject');">
<option value="0">Select Client</option>
  <?php
do {  
?>
  <option value="<?php echo $row_rsOne['list_id']?>"><?php echo $row_rsOne['list']?></option>
  <?php
} while ($row_rsOne = mysql_fetch_assoc($rsOne));
  $rows = mysql_num_rows($rsOne);
  if($rows > 0) {
      mysql_data_seek($rsOne, 0);
	  $row_rsOne = mysql_fetch_assoc($rsOne);
  }
?>
</select>
<div id="newProject">

</div>
<div id="newTask">

</div>
<div id="newTime">

</div>
<div id="newTimeSubmit">

</div>
</form>
<?php
mysql_free_result($rsOne);
?>

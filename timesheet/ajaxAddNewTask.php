<?php require_once('../Connections/conn.php'); ?>
<?php
$collist_rsTwo = "-1";
if (isset($_GET['list_id'])) {
  $collist_rsTwo = (get_magic_quotes_gpc()) ? $_GET['list_id'] : addslashes($_GET['list_id']);
}
$coluserid_rsTwo = "-1";
if (isset($_GET['user_id'])) {
  $coluserid_rsTwo = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}
mysql_select_db($database_conn, $conn);
$query_rsTwo = sprintf("select *  from procentris_list where pid = %s and user_id = %s and deleted = 0 order by list", $collist_rsTwo,$coluserid_rsTwo);
$rsTwo = mysql_query($query_rsTwo, $conn) or die(mysql_error());
$row_rsTwo = mysql_fetch_assoc($rsTwo);
$totalRows_rsTwo = mysql_num_rows($rsTwo);
?>
<?php if ($totalRows_rsTwo > 0) { // Show if recordset not empty ?>
Task: <select name="tasks" onChange="document.getElementById('newTime').innerHTML = ''; doAjax('ajaxAddNewTime.php','GET','user_id=<?php echo $_GET['user_id']; ?>&list_id='+this.value,'','newTime')">
  <option value="0">Select Task</option>
  <?php
do {  
?>
  <option value="<?php echo $row_rsTwo['list_id']?>"><?php echo $row_rsTwo['list']?></option>
  <?php
} while ($row_rsTwo = mysql_fetch_assoc($rsTwo));
  $rows = mysql_num_rows($rsTwo);
  if($rows > 0) {
      mysql_data_seek($rsTwo, 0);
	  $row_rsTwo = mysql_fetch_assoc($rsTwo);
  }
?>
</select>
&nbsp;&nbsp;<a href="#">Click Here</a> To Add New Task.
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsTwo == 0) { // Show if recordset empty ?>
<p>No Task Found. <a href="#">Click Here</a> To Add New Task. </p>
<?php } // Show if recordset empty ?>
<?php
mysql_free_result($rsTwo);
?>

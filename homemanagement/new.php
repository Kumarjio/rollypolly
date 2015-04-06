<?php require_once('../Connections/connHm.php'); ?>
<?php
include_once('start.php');
?>
<div class="post">
	<h1 class="title"><a href="#">Create New Place</a></h1>
	<p class="byline"><small>Add a new place for which you want to manage assets!</small></p>
	<div class="entry">
<?php
if(!$_COOKIE['user_id']) { 
	echo '<div class="error">Please login first.</div>';
} else {
?>
		<div id="submitMsg"></div>
		<form method="post" name="form1" action="" onSubmit="return false;">
		  <table align="center">
			<tr valign="baseline">
			  <td nowrap align="right">Title:</td>
			  <td><input type="text" name="title" value="" size="32" onKeyDown="code=handleKey2(event);if(code=='13') {str=getFormElements(this.form);doAjaxLoadingText('home_product_management/newsubmit.php','POST','',str,'submitMsg','yes','Loading...','0','','','','','0');}"></td>
			</tr>
			<tr valign="baseline">
			  <td nowrap align="right">&nbsp;</td>
			  <td><input type="button" value="Add New Place" onclick="str=getFormElements(this.form);doAjaxLoadingText('home_product_management/newsubmit.php','POST','',str,'submitMsg','yes','Loading...','0','','','','','0');"></td>
			</tr>
		  </table>
		  <input type="hidden" name="site_id" value="">
		  <input type="hidden" name="user_id" value="">
		  <input type="hidden" name="created" value="<?php echo date('Y-m-d H:i:s'); ?>">
		  <input type="hidden" name="MM_insert" value="form1">
		</form>
		<?php include('menu.php'); ?>
<?php } ?>
	</div>
</div>
<form name="inputForm<?php echo $_GET['did']; ?>" method="post" action=""><input type="text" value="" name="list" size="5"><input type="button" name="Go" value="Go" onClick="var str = getFormElements(document.inputForm<?php echo $_GET['did']; ?>); doAjax('ajaxUpdateCategory.php','GET',str,'','<?php echo $_GET['did']; ?>');">
<input type="text" value="<?php echo $_GET['pid']; ?>" name="pid">
<input type="hidden" value="<?php echo $_GET['did']; ?>" name="did">
<input type="hidden" value="<?php echo $_GET['user_id']; ?>" name="user_id">
<input type="hidden" value="<?php echo $_GET['mydate']; ?>" name="mydate">
</form>
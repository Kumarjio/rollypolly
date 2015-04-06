<form name="inputForm<?php echo $_GET['did']; ?>" method="post" action="javascript:var str = getFormElements(document.inputForm<?php echo $_GET['did']; ?>); doAjax('ajaxUpdate.php','GET',str,'','<?php echo $_GET['did']; ?>');"><input type="text" value="<?php echo $_GET['timetaken']; ?>" name="timetaken" size="5" onKeyDown="var code=handleKey(event); if(code==13) { var str = getFormElements(document.inputForm<?php echo $_GET['did']; ?>); doAjax('ajaxUpdate.php','GET',str,'','<?php echo $_GET['did']; ?>'); }"><input type="button" name="Go" value="Go" onClick="var str = getFormElements(document.inputForm<?php echo $_GET['did']; ?>); doAjax('ajaxUpdate.php','GET',str,'','<?php echo $_GET['did']; ?>');">
<input type="hidden" value="<?php echo $_GET['category']; ?>" name="category">
<input type="hidden" value="<?php echo $_GET['project']; ?>" name="project">
<input type="hidden" value="<?php echo $_GET['tasks']; ?>" name="tasks">
<input type="hidden" value="<?php echo $_GET['cdate']; ?>" name="cdate">
<input type="hidden" value="<?php echo $_GET['cday']; ?>" name="cday">
<input type="hidden" value="<?php echo $_GET['cmonth']; ?>" name="cmonth">
<input type="hidden" value="<?php echo $_GET['cyear']; ?>" name="cyear">
<input type="hidden" value="<?php echo $_GET['did']; ?>" name="did">
<input type="hidden" value="<?php echo $_GET['user_id']; ?>" name="user_id">
<input type="hidden" value="<?php echo $_GET['mydate']; ?>" name="mydate">
</form>
<?php
checkLogin();
if (!empty($_POST['MM_Insert'])) {
    $arr = array();
    $arr['proposal_id'] = guid();
    $arr['title'] = $_POST['title'];
    $arr['description'] = $_POST['description'];
    $arr['reference'] = $_POST['reference'];
    $arr['user_id'] = $_SESSION['user']['user_id'];
    $arr['updated_dt'] = date('Y-m-d H:i:s');
    $arr['status'] = 'New';
    $data = array();
    $data = array_merge($arr, $_SESSION['user']);
    $General->addDetails('religion_proposals', $arr);
    $confirmMessage = 'New proposal posted successfully. It will be visible in new section of aphorisms once it is approved by admin';
    send_email($data['email'], 'New Proposal "'.$data['title'].'" Created for "'.SITENAME.'"', 'proposal.php', $data);
}
?>
<script src="/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="/SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<script src="/SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>

<h3>New Religion Proposal</h3>
<?php if (!empty($confirmMessage)) { ?><div class="error"><?php echo $confirmMessage; ?></div><?php } ?>
<p><strong>Note: </strong>If you want to add new aphorism to our religion of humanity then this is the place to add that. Here are the steps to add new aphorism. You add new proposal, admin will approve your proposal, it will be listed in new proposal tab, people will vote and comment on your proposal and if it becomes more popular and admin thinks it to be part of our religion of humanity then that proposal will become a new aphorism of  &quot;ReligionOfHumanity.Us&quot;</p>
<form id="form1" name="form1" method="post">
<strong>Title of Aphorism:</strong><br>
<span id="spryTitle">
<input name="title" type="text" id="title" maxlength="255">
<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMinCharsMsg">Minimum number of 5 characters not met.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of 255 characters.</span></span>
<p><strong>Description of Aphorism:</strong><br>
    <span id="spryDescription">
    <textarea name="description" id="description" cols="45" rows="5"></textarea>
    <span id="countspryDescription">&nbsp;</span><span class="textareaRequiredMsg">A value is required.</span></span>
</p>
<p><strong>Reference Book &amp; Page Number (e.g. RigVeda Pg 32/Chap 3 Verse 10)</strong><br>
    <input name="reference" type="text" id="reference" placeholder="Enter Reference">
</p>
<p>
    <input type="submit" name="Add New Proposal" id="Add New Proposal" value="Submit">
    <input type="hidden" name="user_id" id="user_id">
    <input type="hidden" name="created_dt" id="created_dt">
    <input type="hidden" name="updated_dt" id="updated_dt">
    <input name="status" type="hidden" id="status" value="New">
    <input name="MM_Insert" type="hidden" id="MM_Insert" value="form1">
</p>
<p>&nbsp;</p>
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("spryTitle", "none", {validateOn:["blur"], hint:"Enter Title", minChars:5, maxChars:255});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("spryDescription", {counterId:"countspryDescription", counterType:"chars_count", validateOn:["blur"], hint:"Enter Description"});
</script>

<?php

if (empty($_GET['message_id'])) {
  header("Location: ".HTTPPATH.'/messages/my');
  exit;
}
check_login();
$pageTitle = 'My Messages';
$url = APIHTTPPATH.'/help/messages/detail?message_id='.$_GET['message_id'].'&uid='.$_SESSION['user']['id'];
$returnMyMessage = curlget($url);
$dataMyMessage = json_decode($returnMyMessage, 1);
if ($dataMyMessage['success'] == 0) {
  $errorMyMessage = $dataMyMessage['msg'];
}
if (empty($dataMyMessage['data'])) {
  $errorMyMessage = 'No Message Found in Your List.';
} else {
  $pageTitle = $dataMyMessage['data']['subject'];
}

?>
<?php if (!empty($errorMyMessage)) { ?>
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
      <div class="error"><?php echo $errorMyMessage; ?></div>
    </div>
  </div>
</div>
<?php } else { ?>
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
        <?php echo nl2br($dataMyMessage['data']['message']); ?>
        <br /><br />
        <b>From: </b><?php echo $dataMyMessage['data']['name']; ?> (<b>Sent Date: </b> <?php echo ago(strtotime($dataMyMessage['data']['message_date'])); ?>)
    </div>
  </div>
</div>
<br /><br />
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
        <a href="#" class="btn btn-primary btn-sm" style="width:100%">Delete Message</a>
    </div>
  </div>
</div>
<br /><br />
<?php
$to_uid = $dataMyMessage['data']['uid'];
$baseMessage = "\n\nOriginal Message: \n\n".$dataMyMessage['data']['message'];
include(SITEDIR.'/libraries/messages/new.php');
?>
<?php } ?>
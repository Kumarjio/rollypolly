<?php
check_login();
$pageTitle = 'My Messages';
$url = APIHTTPPATH.'/help/messages/view?uid='.$_SESSION['user']['id'];
$returnMyMessage = curlget($url);
$dataMyMessage = json_decode($returnMyMessage, 1);
if ($dataMyMessage['success'] == 0) {
  $errorMyMessage = $dataMyMessage['msg'];
}
if (empty($dataMyMessage['data'])) {
  $errorMyMessage = 'No Message Found in Your List.';
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
        <?php foreach ($dataMyMessage['data'] as $detailMessage) { 
          $link = HTTPPATH.'/messages/detail?message_id='.$detailMessage['message_id'];
          $params = array();
          $params['class'] = '';
          if (empty($detailMessage['message_read'])) {
            $params['class'] = 'active';
          }
          echo viewDisplay($link, $detailMessage['subject'], 'From: '.$detailMessage['name'], $detailMessage['message_date'], $params);
        } ?>
    </div>
  </div>
</div>
<?php } ?>
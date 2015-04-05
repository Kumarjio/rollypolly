<?php
if (!empty($to_uid) && !empty($_SESSION['user']['id'])) {
$messageCurrentPath = HTTPPATH.$_SERVER['REQUEST_URI'];
if (empty($baseMessage)) {
  $baseMessage = "\n\n".'----------------------------------------------------------------------------------
Original Posting: '.HTTPPATH.$_SERVER['REQUEST_URI'];
}
if (isset($_POST['MM_Message'])) {
  //submit the form
    $url = APIHTTPPATH.'/help/messages/add';
    $params = $_POST;
    $params['data']['uid'] = $_SESSION['user']['id'];
    $params['data']['to_uid'] = $to_uid;
    $params['data']['message'] = $_POST['message'];
    $params['data']['subject'] = $_POST['subject'];
    $POSTFIELDS = http_build_query($params);
    $returnMessage = curlget($url, 1, $POSTFIELDS);
    $dataMessage = json_decode($returnMessage, 1);
    if ($dataMessage['success'] == 0) {
      $errorMessage = $dataMessage['msg'];
    } else {
      $errorMessage = $dataMessage['data']['confirm'];
    }
}
?>
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
      <div class="alert alert-dismissable alert-danger" style="text-align:center;">
        Send Message<a name="messageForm"></a>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="bs-component">
      <?php if (!empty($errorMessage)) { ?>
      <div class="error"><?php echo $errorMessage; ?></div>
      <?php } ?>
      <form name="formMessage" id="formMessage" action="<?php echo $messageCurrentPath; ?>#messageForm" method="post">
        <p><b>Subject: *</b>
        <br />
        <input type="text" name="subject" id="subject" style="width:100%" />
        </p>
        <p><b>Message: *</b>
        <br />
        <textarea name="message" id="message" style="width:100%;" rows="7">


<?php echo $baseMessage; ?>
        </textarea>
        </p>
        <p>
          <input type="submit" name="submit" id="submit" value="Send Message" style="width:100%">
          <input type="hidden" name="MM_Message" id="MM_Message" value="formMessage" />
        </p>
      </form>
    </div>
  </div>
</div>

<?php
}
?>
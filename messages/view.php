<?php
try {
check_login();
$layoutStructure = 'simple';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($id)) {
  $params = array();
  $params['where'] = sprintf(" AND uid = %s", $modelGeneral->qstr($id));
  $userDetails = $modelGeneral->getDetails('google_auth', 1, $params);
  if (empty($userDetails)) {
    throw new Exception('No User Active with ID: '.$id);
  }
  if (!empty($userDetails[0])) {
    $userDetails = $userDetails[0];
  }
}
//SELECT * FROM `help_messages` WHERE uid = '112913147917981568678' or to_uid = '112913147917981568678' GROUP BY uid, to_uid
$users = array();
$params = array();
$params['cacheTime'] = 30;
$params['order'] = 'ORDER BY message_date DESC';
$params['fields'] = "help_messages.*, gf.email, gf.gender, gf.name, gf.picture, gf.link , gt.email as to_email, gt.gender as to_gender, gt.name as to_name, gt.picture as to_picture, gt.link as to_link";
$params['group'] = 'GROUP BY uid, to_uid';
$params['where'] = sprintf(" AND (help_messages.uid = %s OR help_messages.to_uid = %s)", $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_SESSION['user']['id']));
$summary = $modelGeneral->getDetails('help_messages LEFT JOIN google_auth as gf ON help_messages.uid = gf.uid LEFT JOIN google_auth as gt ON help_messages.to_uid = gt.uid', 1, $params);
if (!empty($summary)) {
  foreach ($summary as $k => $v) {
    if ($v['uid'] != $_SESSION['user']['id']) {
      $tmp = array('id' => $v['uid'], 'name' => $v['name'], 'picture' => $v['picture'], 'link' => $v['link'], 'gender' => $v['gender'], 'email' => $v['email']);
      $uid = $v['uid'];
    } else {
      $tmp = array('id' => $v['to_uid'], 'name' => $v['to_name'], 'picture' => $v['to_picture'], 'link' => $v['to_link'], 'gender' => $v['to_gender'], 'email' => $v['to_email']);
      $uid = $v['to_uid'];
    }
    $users[$uid] = $tmp;
  }
}

$params = array();
if (!empty($_POST['MM_Insert']) && $_POST['MM_Insert'] === 'formMessage' && !empty($id)) {
  postMessage();
  $cache = 0;
} else {
  $params['cacheTime'] = 30;
  $cache = 1;
}
$params['order'] = "ORDER BY message_date DESC";
$params['limit'] = "LIMIT 0, 100";
$params['fields'] = "help_messages.*, gf.email, gf.gender, gf.name, gf.picture, gf.link , gt.email as to_email, gt.gender as to_gender, gt.name as to_name, gt.picture as to_picture, gt.link as to_link";
$params['where'] = sprintf(" AND ((help_messages.uid = %s AND help_messages.to_uid = %s) OR (help_messages.uid = %s AND help_messages.to_uid = %s))", $modelGeneral->qstr($id), $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($id));
$details = $modelGeneral->getDetails('help_messages LEFT JOIN google_auth as gf ON help_messages.uid = gf.uid LEFT JOIN google_auth as gt ON help_messages.to_uid = gt.uid', $cache, $params);

//get points
if (!empty($id)) {
$returnPoints = updatePoints($id, $_SESSION['user']['id']);
}
//include(SITEDIR.'/libraries/addresses/nearby.php');

if (empty($users)) {
  throw new Exception('No messages available for you.');
}
?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<style type="text/css">
    .conversation-wrap
    {
        box-shadow: -2px 0 3px #ddd;
        padding:0;
        max-height: 400px;
        overflow: auto;
    }
    .conversation
    {
        padding:5px;
        border-bottom:1px solid #ddd;
        margin:0;

    }

    .message-wrap
    {
        box-shadow: 0 0 3px #ddd;
        padding:0;

    }
    .msg
    {
        padding:5px;
        /*border-bottom:1px solid #ddd;*/
        margin:0;
    }
    .msg-wrap
    {
        padding:10px;
        max-height: 400px;
        overflow: auto;

    }

    .time
    {
        color:#bfbfbf;
    }

    .send-wrap
    {
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        padding:10px;
        /*background: #f8f8f8;*/
    }

    .send-message
    {
        resize: none;
    }

    .highlight
    {
        background-color: #f7f7f9;
        border: 1px solid #e1e1e8;
    }

    .send-message-btn
    {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-left-radius: 0;

        border-bottom-right-radius: 0;
    }
    .btn-panel
    {
        background: #f7f7f9;
    }

    .btn-panel .btn
    {
        color:#b8b8b8;

        transition: 0.2s all ease-in-out;
    }

    .btn-panel .btn:hover
    {
        color:#666;
        background: #f8f8f8;
    }
    .btn-panel .btn:active
    {
        background: #f8f8f8;
        box-shadow: 0 0 1px #ddd;
    }

    .btn-panel-conversation .btn,.btn-panel-msg .btn
    {

        background: #f8f8f8;
    }
    .btn-panel-conversation .btn:first-child
    {
        border-right: 1px solid #ddd;
    }

    .msg-wrap .media-heading
    {
        color:#003bb3;
        font-weight: 700;
    }


    .msg-date
    {
        background: none;
        text-align: center;
        color:#aaa;
        border:none;
        box-shadow: none;
        border-bottom: 1px solid #ddd;
    }


    body::-webkit-scrollbar {
        width: 12px;
    }
 
    
    /* Let's get this party started */
    ::-webkit-scrollbar {
        width: 6px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
/*        -webkit-border-radius: 10px;
        border-radius: 10px;*/
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
/*        -webkit-border-radius: 10px;
        border-radius: 10px;*/
        background:#ddd; 
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
    }
    ::-webkit-scrollbar-thumb:window-inactive {
        background: #ddd; 
    }

</style>
    
    <div class="row">

        <div class="conversation-wrap col-lg-3">

            <?php foreach ($users as $k => $v) { ?>
            <div class="media conversation">
                <a class="pull-left" href="<?php echo HTTPPATH; ?>/messages/view?id=<?php echo $v['id']; ?>">
                    <img class="media-object" alt="64x64" style="width: 50px; height: 50px;" src="<?php echo $v['picture']; ?>">
                </a>
                <div class="media-body">
                    <h5 class="media-heading"><a href="<?php echo HTTPPATH; ?>/messages/view?id=<?php echo $v['id']; ?>"><?php echo $v['name']; ?></a></h5>
                </div>
            </div>
            <?php } ?>
        </div>



        <div class="message-wrap col-lg-8">
            <?php if (empty($id)) { ?>
            <p><small><b>Note: </b> You cannot share your phone number, email address or physical address. Only paid members can share the details. Our system will remove any unwanted text or contact details for unpaid members. <a href="<?php echo HTTPPATH; ?>/users/memberPlans">Click here to become paid member.</a></small></p>
            <p>Choose user from left hand side.</p>
            <?php } else { ?>
<div class="row">
<div class="span4 well">
        <div class="row">
        <div class="span1"><a href="<?php echo HTTPPATH; ?>/users/detail?id=<?php echo $userDetails['uid']; ?>" class="thumbnail"><img src="<?php echo $userDetails['picture']; ?>" alt="" class="imglist"></a></div>
        <div class="span3">
          <!--<p>admin</p>-->
          <p><strong><?php echo $userDetails['name']; ?></strong></p>
          <p><strong>Gender: </strong> <?php echo $userDetails['gender']; ?></p>
          <?php if (!empty($returnPoints[$_SESSION['user']['id']][$id]['points'])) { ?>
            <p><strong>Matching Points: </strong> <?php echo $returnPoints[$_SESSION['user']['id']][$id]['points']; ?> (<?php echo $returnPoints[$_SESSION['user']['id']][$id]['results']; ?>)</p>
          <?php } ?>
          <!--<span class=" badge badge-warning">8 messages</span> <span class=" badge badge-info">15 followers</span>-->
        </div>
      </div>
    </div>
</div>
      <div class="row">
        <div class="col-lg-12">
          <p><small><b>Note: </b> You cannot share your phone number, email address or physical address. Only paid members can share the details. Our system will remove any unwanted text or contact details for unpaid members. <a href="<?php echo HTTPPATH; ?>/users/memberPlans">Click here to become paid member.</a></small></p>
            <form action="" method="post" name="formMEnter" id="formMEnter" class="formMEnter">
            <div class="send-wrap ">

                <input type="text" name="subject" class="send-message" style="width:45%; height:50px;" placeholder="Write a subject..." required />
                <input type="text" name="message" class="send-message" style="width:45%; height:50px;" placeholder="Write a message..." required />


            </div>
            <div class="btn-panel">
                <input type="submit" class=" col-lg-12" role="button" value="Send Message" name="submitMessage" id="submitMessage">
                <input type="hidden" name="to_uid" id="to_uid" value="<?php echo $id; ?>" />
                <input type="hidden" name="MM_Insert" id="MM_Insert" value="formMessage" />
            </div>
            <br style="clear:both" />
            </form>
            <div class="msg-wrap">
                <?php //pr($details);
                  if (!empty($details)) { 
                  foreach ($details as $detail) {
                ?>
                <div class="media msg ">
                    <a class="pull-left" href="<?php echo HTTPPATH; ?>/users/detail?id=<?php echo $detail['uid']; ?>">
                        <img class="media-object" data-src="holder.js/64x64" alt="64x64" style="width: 32px; height: 32px;" src="<?php echo $detail['picture']; ?>">
                    </a>
                    <div class="media-body">
                        <small class="pull-right time"><i class="fa fa-clock-o"></i> <?php echo ago(strtotime($detail['message_date'])); ?></small>
                        <h5 class="media-heading"><?php echo $detail['name']; ?></h5>
                        <small class="col-lg-10"><?php echo $detail['subject']; ?></small>
                        <small class="col-lg-10"><?php echo $detail['message']; ?></small>
                    </div>
                </div>
            <?php } } ?>
            </div>
        </div>
    </div>
            <?php } ?>
        </div>
    </div>

    
<?php
} catch (Exception $e) {
  echo '<h3>Messages</h3>
  <p class="error">'.$e->getMessage().'</p>
  ';
}
?>
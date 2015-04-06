<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$layoutStructure = 'simple';
if (empty($_GET['module_id'])) {
  throw new Exception('Incorrect Module');
}

$colname_rsID = '';
if (!empty($_GET['id'])) {
  $colname_rsID = $_GET['id'];
}

$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
$query = "SELECT * FROM z_modules WHERE module_id = ?";
$resultModule = $modelGeneral->fetchRow($query, array($colname_rsModule), $t);
if (empty($resultModule)) {
  throw new Exception('Could not find the module');
}
if ($resultModule['messages'] == 0) {
  throw new Exception('message is not accessible for this module.');
}

$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
$users = array();
if (!empty($uid)) {
  $params = array();
  $params['where'] = sprintf(" AND uid = %s AND status = 1", $modelGeneral->qstr($uid));
  $userDetails = $modelGeneral->getDetails('google_auth', 1, $params);
  if (empty($userDetails)) {
    throw new Exception('No User Active with ID: '.$uid);
  }
  if (!empty($userDetails[0])) {
    $userDetails = $userDetails[0];
  }
}
if (!empty($userDetails) && $userDetails['uid'] != $_SESSION['user']['id']) {
  $users[$userDetails['uid']] = array('id' => $userDetails['uid'], 'name' => $userDetails['name'], 'picture' => $userDetails['picture'], 'link' => $userDetails['link'], 'gender' => $userDetails['gender'], 'email' => $userDetails['email'], 'member_type' => $userDetails['member_type'], 'member_expires' => $userDetails['member_expires']);
}
//pr($resultModule);
$approved = 0;
if ($resultModule['paid_module'] == 0) {
    $approved = 1;
} else {
  if ($resultModule['paid_posting'] == 1) {
    //check if paid for posting
    $query = "SELECT * FROM auto_pre_transactions WHERE module_id = ? AND id = ? AND internal_status = 1";
    $resultTransaction = $modelGeneral->fetchRow($query, array($colname_rsModule, $colname_rsID), 300);
    if (empty($resultTransaction)) {
      throw new Exception('transaction for this record is not yet confirmed. please check back later.');
    }
    $approved = 1;
  }
  if ($resultModule['paid_message_any_one'] == 1 && !empty($uid)) {
    $resultTransaction = isPaidAnyOneByModuleId($colname_rsModule, $uid, $_SESSION['user']['id']);
    if (!empty($resultTransaction)) {
        $approved = 1;
    }
  }
  /*if ($resultModule['paid_module'] == 1 && $resultModule['paid_message_any_one'] == 1) {
      if (!empty($userDetails)) {
          if (is_paid($_SESSION['user']) || is_paid($userDetails)) {
            $approved = 1;
          }
      }
  } else {
    $approved = 1;
  }*/
}

//SELECT * FROM `help_messages` WHERE uid = '112913147917981568678' or to_uid = '112913147917981568678' GROUP BY uid, to_uid

$params = array();
$params['cacheTime'] = 30;
$params['order'] = 'ORDER BY message_date DESC';
$params['fields'] = "help_messages.*, gf.email, gf.gender, gf.name, gf.picture, gf.link , gt.email as to_email, gt.gender as to_gender, gt.name as to_name, gt.picture as to_picture, gt.link as to_link";
$params['group'] = 'GROUP BY uid, to_uid';
$params['where'] = sprintf(" AND (help_messages.uid = %s OR help_messages.to_uid = %s) AND help_messages.module_id = %s AND help_messages.deleted = 0", $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_GET['module_id']));

$params['where'] .= sprintf(" AND help_messages.id = %s", $modelGeneral->qstr($colname_rsID));

$summary = $modelGeneral->getDetails('help_messages LEFT JOIN google_auth as gf ON help_messages.uid = gf.uid LEFT JOIN google_auth as gt ON help_messages.to_uid = gt.uid', 1, $params);
if (!empty($summary)) {
  foreach ($summary as $k => $v) {
    if ($v['uid'] != $_SESSION['user']['id']) {
      $tmp = array('id' => $v['uid'], 'name' => $v['name'], 'picture' => $v['picture'], 'link' => $v['link'], 'gender' => $v['gender'], 'email' => $v['email']);
      $userId = $v['uid'];
    } else {
      $tmp = array('id' => $v['to_uid'], 'name' => $v['to_name'], 'picture' => $v['to_picture'], 'link' => $v['to_link'], 'gender' => $v['to_gender'], 'email' => $v['to_email']);
      $userId = $v['to_uid'];
    }
    $users[$userId] = $tmp;
  }
}

/*
$params = array();
$params['order'] = "ORDER BY message_date DESC";
$params['limit'] = "LIMIT 0, 100";
$params['fields'] = "help_messages.*, gf.email, gf.gender, gf.name, gf.picture, gf.link , gt.email as to_email, gt.gender as to_gender, gt.name as to_name, gt.picture as to_picture, gt.link as to_link";
$params['where'] = sprintf(" AND ((help_messages.uid = %s AND help_messages.to_uid = %s) OR (help_messages.uid = %s AND help_messages.to_uid = %s)) AND help_messages.module_id = %s AND help_messages.deleted = 0", $modelGeneral->qstr($uid), $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($uid), $modelGeneral->qstr($_GET['module_id']));
$details = $modelGeneral->getDetails('help_messages LEFT JOIN google_auth as gf ON help_messages.uid = gf.uid LEFT JOIN google_auth as gt ON help_messages.to_uid = gt.uid', 0, $params);
*/
$maxRows_rsView = 100;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;
$params = array($uid, $_SESSION['user']['id'], $_SESSION['user']['id'], $uid, $_GET['module_id']);
$cacheTime = 30;
$sql = "SELECT help_messages.*, gf.email, gf.gender, gf.name, gf.picture, gf.link , gt.email as to_email, gt.gender as to_gender, gt.name as to_name, gt.picture as to_picture, gt.link as to_link FROM help_messages LEFT JOIN google_auth as gf ON help_messages.uid = gf.uid LEFT JOIN google_auth as gt ON help_messages.to_uid = gt.uid WHERE 1 AND ((help_messages.uid = ? AND help_messages.to_uid = ?) OR (help_messages.uid = ? AND help_messages.to_uid = ?)) AND help_messages.module_id = ? AND help_messages.deleted = 0";
$sql .= sprintf(" AND help_messages.id = %s", $modelGeneral->qstr($colname_rsID));
$sql .= " ORDER BY message_date DESC";
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $sql, $startRow_rsView, $maxRows_rsView);
$details = $modelGeneral->fetchAll($query_limit_rsView, $params, $cacheTime);

if (!empty($_POST['MM_Insert']) && $_POST['MM_Insert'] === 'formMessage' && !empty($uid)) {
  postMessage($approved);
  $cache = 0;
  $modelGeneral->clearCache($query_limit_rsView, $params);
  header("Location:".$currentURL."/auto/messages?uid=".$uid."&module_id=".$colname_rsModule."&id=".$colname_rsID);
  exit;
}
//get points
if (!empty($uid)) {
$returnPoints = updatePoints($uid, $_SESSION['user']['id']);
}
//include(SITEDIR.'/libraries/addresses/nearby.php');

if (empty($users)) {
  throw new Exception('No messages available.');
}
?>
<script language="javascript">
$( document ).ready(function() {
  $( "#message" ).focus();
});
</script>
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
                <a class="pull-left" href="<?php echo $currentURL; ?>/auto/messages?uid=<?php echo $v['id']; ?>&module_id=<?php echo $colname_rsModule; ?>&id=<?php echo $colname_rsID; ?>">
                    <img class="media-object" alt="64x64" style="width: 50px; height: 50px;" src="<?php echo $v['picture']; ?>">
                </a>
                <div class="media-body">
                    <h5 class="media-heading"><a href="<?php echo $currentURL; ?>/auto/messages?uid=<?php echo $v['id']; ?>&module_id=<?php echo $colname_rsModule; ?>&id=<?php echo $colname_rsID; ?>"><?php echo $v['name']; ?></a></h5>
                </div>
            </div>
            <?php } ?>
        </div>



        <div class="message-wrap col-lg-8">
            <?php if (empty($uid)) { ?>
            <p>Choose user from left hand side.</p>
            <?php } else { ?>
<div class="row">
<div class="span4 well">
        <div class="row">
        <div class="span1"><a href="javascript:;" class="thumbnail"><img src="<?php echo $userDetails['picture']; ?>" alt="" class="imglist"></a></div>
        <div class="span3">
          <!--<p>admin</p>-->
          <p><strong><?php echo $userDetails['name']; ?></strong></p>
          <p><strong>Gender: </strong> <?php echo $userDetails['gender']; ?></p>
          <?php if (!empty($returnPoints[$_SESSION['user']['id']][$uid]['points'])) { ?>
            <p><strong>Matching Points: </strong> <?php echo $returnPoints[$_SESSION['user']['id']][$uid]['points']; ?> (<?php echo $returnPoints[$_SESSION['user']['id']][$uid]['results']; ?>)</p>
          <?php } ?>
          <!--<span class=" badge badge-warning">8 messages</span> <span class=" badge badge-info">15 followers</span>-->
        </div>
      </div>
    </div>
</div>
      <div class="row">
        <div class="col-lg-12">
          <?php if ($approved == 0) { ?>
              <p><small><b>Note: </b> You cannot share your phone number, email address, physical address or any contact details. To share your contact details, please pay <a href="<?php echo $currentURL; ?>/auto/pay?module_id=<?php echo $colname_rsModule; ?>&id=<?php echo $colname_rsID; ?>&t=<?php echo urlencode($resultModule['menu_display_name'].' Module'); ?>&d=<?php echo urlencode($resultModule['menu_display_name'].' Module'); ?>&new=1&submit=1">here</a></small></p>
          <?php } ?>
            <?php if ($uid != $_SESSION['user']['id']) { ?>
            <form action="" method="post" name="formMEnter" id="formMEnter" class="formMEnter">
            <div class="send-wrap ">
                <input type="text" name="message" class="send-message" id="message" style="width:100%; height:50px;" placeholder="Write a message..." required />


            </div>
            <div class="btn-panel">
                <input type="submit" class=" col-lg-12" role="button" value="Send Message" name="submitMessage" id="submitMessage">
                <input type="hidden" name="to_uid" id="to_uid" value="<?php echo $uid; ?>" />
                <input type="hidden" name="module_id" id="module_id" value="<?php echo $colname_rsModule; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $colname_rsID; ?>" />
                <input type="hidden" name="MM_Insert" id="MM_Insert" value="formMessage" />
            </div>
            <br style="clear:both" />
            </form>
            <?php } ?>
            <div class="msg-wrap">
                <?php //pr($details);
                  if (!empty($details)) { 
                  foreach ($details as $detail) {
                   // pr($detail);
                    if ($detail['message_approved'] == 0) {
                      if ($detail['uid'] != $_SESSION['user']['id']) {
                        continue;
                      }
                    }
                ?>
                <div class="media msg ">
                    <a class="pull-left" href="<?php echo HTTPPATH; ?>/users/detail?id=<?php echo $detail['uid']; ?>">
                        <img class="media-object" data-src="holder.js/64x64" alt="64x64" style="width: 32px; height: 32px;" src="<?php echo $detail['picture']; ?>">
                    </a>
                    <div class="media-body">
                        <small class="pull-right time"><i class="fa fa-clock-o"></i> <?php echo ago(strtotime($detail['message_date'])); ?></small>
                        <h5 class="media-heading"><?php echo $detail['name']; ?></h5>
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
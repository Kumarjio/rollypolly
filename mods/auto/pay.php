<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$layoutStructure = 'leftmap';
if (empty($_GET['module_id'])) {
  throw new Exception('Incorrect Module');
}
if (empty($_GET['id'])) {
  throw new Exception('Incorrect ID');
}

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}


$t = (3600*24);

$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
$query = "SELECT * FROM z_modules WHERE module_id = ?";
$resultModule = $modelGeneral->fetchRow($query, array($colname_rsModule), $t);
if (empty($resultModule)) {
  throw new Exception('Could not find the module');
}
$tablename = 'auto_'.$resultModule['module_name'];

$query = "SELECT * FROM z_modules_fields WHERE module_id = ? ORDER BY sorting ASC";
$resultModuleFields = $modelGeneral->fetchAll($query, array($colname_rsModule), $t);
if (empty($resultModuleFields)) {
  throw new Exception('Could not find the module fields');
}
//pr($resultModuleFields);
$resultModuleFields2 = array();
foreach ($resultModuleFields as $k => $v) {
  $resultModuleFields2[$v['field_name']] = $v;
}
//pr($resultModuleFields2);
$searchCriteria = '';
$searchCriteria .= ' AND a.id = ? AND a.module_id = ?';
$query_rowResult = "SELECT a.*, u.name as fullname, u.* FROM $tablename as a LEFT JOIN google_auth as u ON a.uid = u.uid WHERE 1 $searchCriteria";
$rowResult = $modelGeneral->fetchRow($query_rowResult, array($_GET['id'], $colname_rsModule), $t);

//orders
if (!empty($_GET['checkoutId'])) {
  if (!empty($_GET['error'])) {
    $error = $_GET['error_description'];
  } else {
      $error = 'We did not received your money. Please contact the administrator at '.ADMIN_EMAIL;
      $d = array();
      $d['tid'] = guid();
      $d['checkoutId'] = $_GET['checkoutId'];
      $d['orderId'] = $_GET['orderId'];
      $d['flag_test'] = $_GET['test'];
      $d['transaction'] = $_GET['transaction'];
      $d['postback'] = $_GET['postback'];
      $d['amount'] = $_GET['amount'];
      $d['signature'] = $_GET['signature'];
      $d['clearingDate'] = $_GET['clearingDate'];
      $d['status'] = $_GET['status'];
      $d['internal_status'] = 0;
      if (verifyGatewaySignature($d['signature'], $d['checkoutId'], $d['amount'])) {
          $d['module_id'] = $colname_rsModule;
          $d['id'] = $_GET['id'];
          $d['transaction_date'] = date('Y-m-d H:i:s');
          $d['user_id'] = $_SESSION['user']['id'];
          $d['transaction_date'] = date('Y-m-d H:i:s');
          $d['comments'] = '';
          $d['transaction_details'] = json_encode($_GET);
          if ($_GET['status'] === 'Completed') {
              $d['internal_status'] = 1;
              $error = 'We have recieved your money. If you need any help then please contact the administrator at '.ADMIN_EMAIL;
              $dArr = array();
              $dArr['rc_approved'] = 1;
              $where = sprintf('uid = %s AND id=%s', $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_GET['id']));
              $result = $modelGeneral->updateDetails($tablename, $dArr, $where);
              //send email
          }
          $d['payment_type'] = 'dwolla';
          $modelGeneral->addDetails('auto_pre_transactions', $d);
          header("Location: ".$currentURL."/auto/pay?id=".$_GET['id']."&module_id=".$colname_rsModule."&t=".$_GET['t']."&d=".$_GET['d']."&msg=".urlencode($error));
          exit;
      }//end verify if
  }//end error if
}
//orders ends

$pageTitle .= ' '.$rowResult['menu_display_name'];

include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
?>
<div class="jumbotron">
  <h1>Pay for <?php echo $resultModule['menu_display_name']; ?></h1>
<?php
if (isset($_GET['new']) == 1 && isset($_GET['submit']) == 1 && $resultModule['paid_module'] == 1) {
?>
<p>Please click below to pay.</p>
<script src="https://www.dwolla.com/scripts/button.min.js" 
    class="dwolla_button" 
    type="text/javascript" 
    data-key="IVQZVEDpwzJiECGzWKczZZ0pUa6TXqMylcJCd3pBTR3IaLWT0h" 
    data-redirect="<?php echo $currentURL; ?>/auto/pay?id=<?php echo $_GET['id']; ?>&module_id=<?php echo $colname_rsModule; ?>&t=<?php echo urlencode($_GET['t']); ?>&d=<?php echo urlencode($_GET['d']); ?>&new=1&confirm=1" 
    data-label="Pay Now (<?php echo $resultModule['paid_amount']; ?>)" 
    data-name="<?php echo $_GET['t']; ?>" 
    data-description="<?php echo $_GET['d']; ?>" 
    data-amount="<?php echo $resultModule['paid_amount']; ?>" 
    data-shipping="0"
    data-tax="0"
    data-guest-checkout="true"
    data-orderid="<?php echo guid(); ?>"
    data-type="simple"
    data-test="true">
</script>
<!--https://developers.dwolla.com/dev/pages/button#redirect
    data-test="true"-->
<?php
}
?>
  <?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
  <div><a href="<?php echo $currentURL; ?>/auto/detail?id=<?php echo $_GET['id']; ?>&module_id=<?php echo $colname_rsModule; ?>">View Posting</a></div>
</div>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
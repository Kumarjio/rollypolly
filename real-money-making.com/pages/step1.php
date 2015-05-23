<?php require_once('../Connections/connWork.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if (empty($_SESSION['user'])) {
	header("Location: /users/login");
	exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$_POST['user_id'] = $_SESSION['user']['id'];
	$arr = $_POST;
	unset($arr['Submit']);
	unset($arr['agree']);
	unset($arr['MM_insert']);
	unset($arr['user_id']);
	$customStr = http_build_query($arr);
	$_POST['created_dt'] = date('Y-m-d H:i:s');
	$_POST['updated_dt'] = date('Y-m-d H:i:s');
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO real_money_records (record_id, amount, deposit, adminFees, paypalFees, netFees, user_id, created_dt, updated_dt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['record_id'], "text"),
                       GetSQLValueString($_POST['amount'], "double"),
                       GetSQLValueString($_POST['deposit'], "double"),
                       GetSQLValueString($_POST['adminFees'], "double"),
                       GetSQLValueString($_POST['paypalFees'], "double"),
                       GetSQLValueString($_POST['netFees'], "double"),
                       GetSQLValueString($_POST['user_id'], "text"),
                       GetSQLValueString($_POST['created_dt'], "date"),
                       GetSQLValueString($_POST['updated_dt'], "date"));

  mysql_select_db($database_connWork, $connWork);
  $Result1 = mysql_query($insertSQL, $connWork) or die(mysql_error());
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $insertGoTo = "/step2?".$customStr;
  header(sprintf("Location: %s", $insertGoTo));
  exit;

}
?>

<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>" data-ng-controller="step1Ctrl">
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-header text-center">
                    <h1>
                        Step 1: Amount Overview
                    </h1>
                </div>
            </div>
        </div>
		
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <h3>
                            Amount Needed
                        </h3>
                    </div>
                    <div class="panel-body">
                        <p><strong>How Much Money You Need: </strong><br />
							<input name="amount" type="text" id="amount" data-ng-model="amount" data-ng-init="amount=0" style="width:100%;" />
						  </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <h3>
                            Deposit With Forex Broker
                        </h3>
                    </div>
                    <div class="panel-body">
                        <p><strong>Amount to Deposit</strong> with us (This amount will be refunded to you whenever you close the account with us):<br />
                          This amount will be directly related to how much money you want. If you want to earn more, then this deposit will also be increased. <br /> 
							<input name="deposit" type="text" id="deposit" style="width:100%;" value="{{amount * 10 * 1}}" readonly="readonly" data-ng-model="deposit" />
					  </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <h3>
                            Admin Charges
                        </h3>
                    </div>
                    <div class="panel-body">
						  <p><strong>Admin Charges:</strong><br />
							<input name="adminFees" type="text" id="adminFees" style="width:100%;" value="{{amount * (10 / 100)}}" readonly="readonly" data-ng-model="adminFees" />
						</p>
						  <p><strong>Paypal Fees:</strong><br />
							<input name="paypalFees" type="text" id="paypalFees" style="width:100%;" value="{{(amount * (10 / 100)) * 3.5 / 100}}" readonly="readonly" data-ng-model="paypalFees" />
						</p>
						  <p><strong>Current Total Charges:</strong><br />
							<input name="netFees" type="text" id="netFees" value="{{(amount * (10 / 100)) + ((amount * (10 / 100)) * 3.5 / 100)}}" readonly="readonly" style="width:100%;" data-ng-model="netFees" />
						</p>
                    </div>
                </div>
            </div>
        </div><hr />
		
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
  <input name="MM_Step" type="hidden" id="MM_Step" value="1" />
  <input name="record_id" type="hidden" id="record_id" value="<?php echo guid(); ?>" />
  <input name="user_id" type="hidden" id="user_id" value="<?php echo $_SESSION['user']['id']; ?>" />
  <input type="hidden" name="MM_insert" value="form1">
			<input type="submit" name="Submit" value="Proceed To Step 2" class="btn btn-primary btn-lg" />
			</div>
		</div>
    </div>
</section>
</form>
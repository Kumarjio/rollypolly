<?php
try {
check_login();

include(SITEDIR.'/includes/navLeftSideVars.php');
if (!is_super_admin()) {
  check_city_owner($globalCity['id']);
}


$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}

$id = !empty($_GET['product_id']) ? $_GET['product_id'] : '';
$query = "SELECT * FROM  product_base WHERE product_id = ?";
$resultProductBase = $modelGeneral->fetchRow($query, array($id), 0);

$pageTitle = 'Add Product "'.$resultProductBase['product_name'].'" in Inventory';


if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  $d = array();
  $d['product_id'] = $id;
  $d['uid'] = $_SESSION['user']['id'];
  $d['cty_id'] = $globalCity['id'];
  $d['cost_price'] = $_POST['cost_price'];
  $d['commission_percentage'] = $_POST['commission_percentage'];
  $d['tax_percentage'] = $_POST['tax_percentage'];
  $d['discount_percentage'] = $_POST['discount_percentage'];
  $d['net_price'] = $_POST['net_price'];
  $d['inventory_created_dt'] = date('Y-m-d H:i:s');
  $d['inventory_status'] = 1;
  $modelGeneral->addDetails('product_inventory', $d);
  unset($_POST);
  $error = 'Product added in inventory.';
}

if (isset($_POST['MM_Update']) && $_POST['MM_Update'] == 'form1' && is_super_admin()) {
  try {
      $data = array();
      $data['uid'] = $_SESSION['user']['id'];
      $data['cost_price'] = $_POST['cost_price'];
      $data['commission_percentage'] = $_POST['commission_percentage'];
      $data['tax_percentage'] = $_POST['tax_percentage'];
      $data['discount_percentage'] = $_POST['discount_percentage'];
      $data['net_price'] = $_POST['net_price'];
      $data['inventory_updated_dt'] = date('Y-m-d H:i:s');
      $data['inventory_status'] = $_POST['inventory_status'];
      $where = sprintf('product_id = %s AND cty_id = %s', $modelGeneral->qstr($id), $modelGeneral->qstr($globalCity['id']));
      $result = $modelGeneral->updateDetails('product_inventory', $data, $where);
      $error = 'Your Inventory Details updated successfully.';
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}
if (!empty($id)) {
$query = "SELECT * FROM  product_inventory WHERE cty_id = ? AND product_id = ?";
$resultProductInventory = $modelGeneral->fetchRow($query, array($globalCity['id'], $id), 0);
}

if (!empty($resultProductInventory)) {
  $_POST = $resultProductInventory;
}

?>
<div class="row">
  <div class="col-lg-12">
    <h3><?php echo $pageTitle; ?></h3>
    <p><a href="<?php echo $currentURL; ?>/admin/city/store/productBaseView">View All</a></p>
  </div>
</div>
<form name="form1" id="form1" method="post" action="">
<?php if (!empty($error)) { echo '<div class="row"><div class="col-md-12"><div class="error">'.$error.'</div></div></div>'; } ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail"><span class="glyphicon glyphicon-file">
                        </span>Product Details</a>
                    </h4>
                </div>
                <div id="collapseDetail" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <?php if (!empty($resultProductInventory)) { ?>
                        <div class="form-group">
                          <strong>Inventory Status:</strong> <br />
                          <input type="radio" name="inventory_status" id="inventory_status_1" value="1" <?php echo (!empty($_POST['inventory_status']) && $_POST['inventory_status'] == 1) ? 'checked' : ''; ?> /> <strong>Active</strong> 
                            <input type="radio" name="inventory_status" id="inventory_status_2" value="0" <?php echo (isset($_POST['inventory_status']) && $_POST['inventory_status'] == 0) ? 'checked' : ''; ?> /> <strong>Inactive</strong>
                        </div>
                        <?php } ?>
                        <div class="form-group">
                          <strong>Cost Price:</strong> <br />
                          <input name="cost_price" type="text" class="inputText" id="cost_price" placeholder="Enter Cost Price" value="<?php echo !empty($_POST['cost_price']) ? $_POST['cost_price'] : ''; ?>" maxlength="200" required />
                        </div>
                        <div class="form-group">
                          <strong>Commission (in %):</strong> <br />
                          <input name="commission_percentage" type="text" class="inputText" id="commission_percentage" placeholder="Enter Commission Percentage" value="<?php echo !empty($_POST['commission_percentage']) ? $_POST['commission_percentage'] : ''; ?>" maxlength="200" required />
                        </div>
                        <div class="form-group">
                          <strong>Tax Percentage (in %):</strong> <br />
                          <input name="tax_percentage" type="text" class="inputText" id="tax_percentage" placeholder="Enter Tax Percentage" value="<?php echo !empty($_POST['tax_percentage']) ? $_POST['tax_percentage'] : ''; ?>" maxlength="200" required />
                        </div>
                        <div class="form-group">
                          <strong>Discount Percentage (in %):</strong> <br />
                          <input name="discount_percentage" type="text" class="inputText" id="discount_percentage" placeholder="Enter Discount Percentage" value="<?php echo isset($_POST['discount_percentage']) ? $_POST['discount_percentage'] : ''; ?>" maxlength="200" required />
                        </div>
                        <div class="form-group">
                          <strong>Net Price (in $):</strong> <br />
                          <input name="net_price" type="text" class="inputText" id="net_price" placeholder="Enter Net Price" value="<?php echo !empty($_POST['net_price']) ? $_POST['net_price'] : ''; ?>" maxlength="200" onFocus="calculate()" required />
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<p>
<?php if (!empty($resultProductInventory)) { ?>
  <input type="hidden" name="MM_Update" id="MM_Update" value="form1">
  <input type="submit" name="submit" id="submit" value="Update Product" class="inputText">
<?php } else  { ?>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Add New Product" class="inputText">
<?php } ?>
</p>
</form>
<script language="javascript">
  function calculate()
  {
    var cost_price = parseFloat($('#cost_price').val());
    var commission_percentage = parseFloat($('#commission_percentage').val());
    var tax_percentage = parseFloat($('#tax_percentage').val());
    var discount_percentage = parseFloat($('#discount_percentage').val());
    var net_price = cost_price + (cost_price * (commission_percentage / 100)) + (cost_price * (tax_percentage / 100)) - (cost_price * (discount_percentage / 100));
    $('#net_price').val(net_price);
  }
</script>

<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
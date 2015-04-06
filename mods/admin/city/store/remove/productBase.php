<?php
try {
check_login();

include(SITEDIR.'/includes/navLeftSideVars.php');
if (!is_super_admin()) {
  check_city_owner($globalCity['id']);
}
$pageTitle = 'New Product';


$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}

$params = array();
$params['where'] = sprintf(' AND store_city_id = %s', $modelGeneral->qstr($globalCity['id']));
$details = $modelGeneral->getDetails('my_store', 0, $params);
$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];

$id = !empty($_GET['product_id']) ? $_GET['product_id'] : '';

if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  $d = array();
  $d['product_id'] = guid();
  $d['uid'] = $_SESSION['user']['id'];
  $d['product_name'] = $_POST['product_name'];
  $d['product_description'] = $_POST['product_description'];
  $d['product_images'] = json_encode(array_filter($_POST['product_images']));
  $modelGeneral->addDetails('product_base', $d);
  unset($_POST);
  $error = 'New product added successfully.';
}

if (isset($_POST['MM_Update']) && $_POST['MM_Update'] == 'form1' && is_super_admin()) {
  try {
      if (!empty($_POST['product_images'])) {
        $_POST['product_images'] = array_filter($_POST['product_images']);
      }
      $data = array();
      $data['uid'] = $_SESSION['user']['id'];
      $data['product_name'] = !empty($_POST['product_name']) ? $_POST['product_name'] : '';
      $data['product_description'] = !empty($_POST['product_description']) ? $_POST['product_description'] : '';
      $data['product_images'] = !empty($_POST['product_images']) ? json_encode($_POST['product_images']) : '';
      $data['product_status'] = !empty($_POST['product_status']) ? $_POST['product_status'] : '';
      $where = sprintf('product_id = %s', $modelGeneral->qstr($id));
      $result = $modelGeneral->updateDetails('product_base', $data, $where);
      $error = 'Your Product Details updated successfully.';
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}

if (!empty($id) && is_super_admin()) {
  $query = "SELECT * FROM  product_base WHERE product_id = ?";
  $resultProductBase = $modelGeneral->fetchRow($query, array($id), 0);
  $pageTitle = 'Edit Product';

}
if (empty($_POST) && !empty($resultProductBase)) {
  $_POST = $resultProductBase;
  $_POST['product_images'] = json_decode($_POST['product_images'], 1);
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
                      <?php if (!empty($id)) { ?>
                      <div class="form-group">
                        <strong>Product Status:</strong> <br />
                        <input type="radio" name="product_status" id="product_status_1" value="1" <?php echo (!empty($_POST['product_status']) && $_POST['product_status'] == 1) ? 'checked' : ''; ?> /> <strong>Active</strong> 
                          <input type="radio" name="product_status" id="product_status_2" value="0" <?php echo (isset($_POST['product_status']) && $_POST['product_status'] == 0) ? 'checked' : ''; ?> /> <strong>Inactive</strong>
                      </div>
                      <?php } ?>
                      <div class="form-group">
                        <strong>Product Name:</strong> <br />
                        <input name="product_name" type="text" class="inputText" id="product_name" placeholder="Enter Product Name" value="<?php echo !empty($_POST['product_name']) ? $_POST['product_name'] : ''; ?>" maxlength="200" required />
                      </div>
                      <div class="form-group">
                        <strong>Product Description:</strong> <br />
                        <textarea name="product_description" rows="5" id="product_description" class="inputText" placeholder="Enter Product Description"><?php echo !empty($_POST['product_description']) ? $_POST['product_description'] : ''; ?></textarea>
                      </div>
                      <div class="form-group" id="imgs_1">
                          <strong>Product Images</strong><br />
                          <?php if (!empty($_POST['product_images'])) { ?>
                            <?php foreach ($_POST['product_images'] as $image) { ?>
                            <input type="text" name="product_images[]" class="inputText" value="<?php echo $image; ?>" placeholder="Enter Image URL" />
                            <?php } ?>
                          <?php } else { ?>
                          <input type="text" name="product_images[]" class="inputText" value="" placeholder="Enter Image URL"/>
                          <?php } ?>
                      </div>
                      <div class="form-group">
                        <input type="button" name="img_add" id="img_add" onClick="addimage('1')" value="Add More Image URL" />
                      </div>
                      <div id="tmpImgs_1" style="display:none;"><input type="text" name="product_images[]" class="inputText" value="" placeholder="Enter Image URL" /></div>
                      <script language="javascript">
                          function addimage(k)
                          {
                              $('#imgs_'+k).append($('#tmpImgs_'+k).html());
                          }
                      </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<p>
<?php if (!empty($id)) { ?>
  <input type="hidden" name="MM_Update" id="MM_Update" value="form1">
  <input type="submit" name="submit" id="submit" value="Update Product" class="inputText">
<?php } else  { ?>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Add New Product" class="inputText">
<?php } ?>
</p>
</form>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
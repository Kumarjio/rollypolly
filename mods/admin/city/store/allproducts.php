<?php
try {
check_login();

include(SITEDIR.'/includes/navLeftSideVars.php');
if (!is_super_admin()) {
  check_city_owner($globalCity['id']);
}
$pageTitle = 'Create New Product';
?>

                      <script language="javascript">
                        function addimage()
                        {
                          $('#imgs').append($('#tmpImgs').html());
                        }
                        function addvideo(k)
                        {
                          $('#videos').append($('#tmpVideo').html());
                        }
                        function addurl(k)
                        {
                          $('#urls').append($('#tmpURLS').html());
                        }
                      </script>
<div class="row">
  <div class="col-lg-12">
    <h3><?php echo $pageTitle; ?></h3>
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
                          <div class="form-group">
                            <strong>Product Name:</strong> <br />
                            <input name="product_name" type="text" class="inputText" id="product_name" placeholder="Enter Product Name" value="<?php echo !empty($_POST['product_name']) ? $_POST['product_name'] : ''; ?>" maxlength="200" required />
                          </div>
                          <div class="form-group">
                            <strong>Product Description:</strong> <br />
                            <textarea name="product_description" id="product_description" class="inputText"><?php echo !empty($_POST['product_description']) ? $_POST['product_description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group" id="imgs">
                              <strong>Product Images</strong><br />
                              <input type="text" name="product_images[]" class="inputText" value="" placeholder="Enter Image URL"/>
                          </div>
                          <div class="form-group">
                            <input type="button" name="img_add" id="img_add" onClick="addimage()" value="Add More Image URL" />
                          </div>
                          <div id="tmpImgs" style="display:none;"><input type="text" name="product_images[]" class="inputText" value="" placeholder="Enter Image URL" /></div>
                          <div class="form-group">
                            <strong>Fixed Delivery Charges:</strong> <br />
                            <input type="text" name="delivery_charge" id="delivery_charge" class="inputText" value="<?php echo isset($_POST['delivery_charge']) ? $_POST['delivery_charge'] : ''; ?>" placeholder="Delivery Charge" /><br />
                            <b>Note: </b> If delivery charge of any product is not mentioned then this charge will be applied to all the products.
                          </div>
                          <div class="form-group">
                            <strong>Store Visibility:</strong> <br />
                            <input type="radio" name="store_status" id="store_status_1" value="1" <?php echo (!empty($_POST['store_status'])) ? 'checked' : ''; ?> /> Enabled 
                            <input type="radio" name="store_status" id="store_status_2" value="0" <?php echo (empty($_POST['store_status']) || !isset($_POST['store_status'])) ? 'checked' : ''; ?> /> Disabled
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Update Configuration" class="inputText">
</p>

</form>

<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
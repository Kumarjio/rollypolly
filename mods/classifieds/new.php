<?php

try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');
$pageTitle = 'Create New Post';

$latitude = $globalCity['latitude'];
$longitude = $globalCity['longitude'];

$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}

if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      if (empty($_POST['title'])) {
        throw new Exception('Please fill the title. ');
      }
      $data = array();
      $data['classified_id'] = guid();
      $data['uid'] = $_SESSION['user']['id'];
      $data['city_id'] = $globalCity['id'];
      $data['title'] = !empty($_POST['title']) ? $_POST['title'] : '';
      $data['description'] = !empty($_POST['description']) ? $_POST['description'] : '';
      $data['cl_status'] = !empty($_POST['cl_status']) ? $_POST['cl_status'] : 0;
      $data['cl_approved'] = 0;
      $data['cl_created_dt'] = date('Y-m-d H:i:s');
      $data['cl_updated_dt'] = date('Y-m-d H:i:s');
      $data['cl_lat'] = !empty($_POST['lat']) ? $_POST['lat'] : '';
      $data['cl_lng'] = !empty($_POST['lng']) ? $_POST['lng'] : '';
      $_POST['images'] = !empty($_POST['images']) ? array_filter($_POST['images']) : array();
      $_POST['urls'] = !empty($_POST['urls']) ? array_filter($_POST['urls']) : array();
      $_POST['videos'] = !empty($_POST['videos']) ? array_filter($_POST['videos']) : array();
      $data['images'] = json_encode($_POST['images']);
      $data['urls'] = json_encode($_POST['urls']);
      $data['videos'] = json_encode($_POST['videos']);
      $data['address'] = !empty($_POST['address']) ? $_POST['address'] : '';
      $data['address2'] = !empty($_POST['address2']) ? $_POST['address2'] : '';
      $data['showAddress'] = !empty($_POST['showAddress']) ? $_POST['showAddress'] : '';
      $result = $modelGeneral->addDetails('z_classifieds', $data, $_SESSION['user']['id']);
      $error = 'Your post is submitted successfully. The post is currently under review and it will go live in some time.';
      unset($_POST);
      header("Location: ".$currentURL."/classifieds/new?cat_id=".(!empty($_GET['cat_id']) ? $_GET['cat_id'] : '')."&msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}

$params = array();
$params['cacheTime'] = 60*60*24*7;
$params['where'] = sprintf(' AND cat_status = 1', $modelGeneral->qstr($id));
$params['order'] = 'Order by sorting, category';
$tmp = $modelGeneral->getDetails('z_classifieds_category', 1, $params);
if (empty($tmp)) {
  //error
}
$newClassifiedsCategoryArr = array();

foreach ($tmp as $k => $v) {
  $newClassifiedsCategoryArr['c_'.$v['parent_id']]['c_'.$v['cat_id']] = $v;
}
$level_1 = array_shift($newClassifiedsCategoryArr);

$category = '';
if (!empty($_POST['category_id'])) {
  $category = substr($_POST['category_id'], 2);
} else if (!empty($_GET['cat_id'])) {
  $category = $_GET['cat_id'];
}
$subcategory = '';
if (!empty($_POST['subcategory_id'])) {
  $subcategory = substr($_POST['subcategory_id'], 2);
} else if (!empty($_GET['sub_cat_id'])) {
  $subcategory = $_GET['sub_cat_id'];
}
include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');
?>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.chained.min.js"></script>
<script language="javascript">
function catsel(t)
{
  console.log($('#'+t.id).val());
}
</script>
<form name="form1" id="form1" method="post" action="<?php echo $currentURL."/classifieds/new?cat_id=".$category; ?>" onSubmit="return validateFrm();">

<div class="row">
  <div class="col-lg-12">
    <h3>New Classified Post</h3>
    <?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
  </div>
</div>
<div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseLocation"><span class="glyphicon glyphicon-file">
                            </span>Location</a>
                        </h4>
                    </div>
                    <div id="collapseLocation" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?php
                                include(SITEDIR.'/libraries/addresses/addressGrabberBody2.php');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail"><span class="glyphicon glyphicon-file">
                            </span>Details</a>
                        </h4>
                    </div>
                    <div id="collapseDetail" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <div class="form-group">
                            <strong>Category:</strong> <br />
                            
                              <select id="category_id" name="category_id" onChange="catsel(this)">
                                <option value="">--</option>
                                <?php foreach ($level_1 as $k => $v) { ?>
                                <option value="<?php echo $k; ?>" <?php echo (!empty($category) && $k == 'c_'.$category) ? 'selected' : ''; ?>><?php echo $v['category']; ?></option>
                                <?php } ?>
                              </select>

                          </div>
                          <div class="form-group">
                            <strong>SubCategory:</strong> <br />
                              <select id="subcategory_id" name="subcategory_id">
                                <option value="">--</option>
                                <?php foreach ($newClassifiedsCategoryArr as $k => $v) { ?>
                                <?php foreach ($v as $k1 => $v1) { ?>
                                <option value="<?php echo $k1; ?>" class="<?php echo $k; ?>" <?php echo (!empty($subcategory) && $k1 == 'c_'.$subcategory) ? 'selected' : ''; ?>><?php echo $v1['category']; ?></option>
                                <?php } ?>
                                <?php } ?>
                              </select>
                          </div>
                          <div class="form-group">
                            <strong>Title:</strong> <br />
                            <input type="text" name="title" id="title" class="inputText" value="<?php echo !empty($_POST['title']) ? $_POST['title'] : ''; ?>" placeholder="Enter Title" />
                          </div>
                          <div class="form-group">
                              <strong>Description:</strong> <br />
                              <textarea name="description" rows="5" id="description" class="inputText" placeholder="Enter Description"><?php echo !empty($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                            <strong>Visibility:</strong> <br />
                            <input type="radio" name="cl_status" id="cl_status_1" value="0" <?php echo empty($_POST['cl_status']) ? 'checked' : ''; ?> /> Disabled 
                            <input type="radio" name="cl_status" id="cl_status_2" value="1" <?php echo (!empty($_POST['cl_status']) || !isset($_POST['cl_status'])) ? 'checked' : ''; ?> /> Enabled
                          </div>
                          <div class="form-group" id="classified_imgs">
                              <strong>Images:</strong> <br />
                              <input type="text" name="classified_image[]" class="inputText" placeholder="Enter Image URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="classified_img_add" id="classified_img_add" onClick="addclassifiedimg()" value="Add More Image URL" />
                          </div>
                          <div class="form-group" id="classified_vids">
                              <strong>Videos:</strong> <br />
                              <input type="text" name="classified_video[]" class="inputText" placeholder="Enter Youtube or Vimeo Video URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="classified_vids_add" id="classified_vids_add" onClick="addclassifiedvideo()" value="Add More Video URL" />
                          </div>
                          <div class="form-group" id="classified_web">
                              <strong>Web URLS:</strong> <br />
                              <input type="text" name="classified_urls[]" class="inputText" placeholder="Enter Web URL" value="" />
                          </div>
                          <div class="form-group">
                            <input type="button" name="classified_web_add" id="classified_web_add" onClick="addclassifiedurls()" value="Add More Web URL" />
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New Post" class="inputText">
</p>
</form>
<div id="tmpImgs" style="display:none;"><input type="text" name="classified_image[]" class="inputText" placeholder="Enter Image URL" value="" /></div>
<div id="tmpVideos" style="display:none;"><input type="text" name="classified_video[]" class="inputText" placeholder="Enter Youtube or Vimeo Video URL" value="" /></div>
<div id="tmpURLS" style="display:none;"><input type="text" name="classified_urls[]" class="inputText" placeholder="Enter Web URL" value="" /></div>

<script language="javascript">
  function addclassifiedimg()
  {
    $('#classified_imgs').append($('#tmpImgs').html());
  }
  function addclassifiedvideo()
  {
    $('#classified_vids').append($('#tmpVideos').html());
  }
  function addclassifiedurls()
  {
    $('#classified_web').append($('#tmpURLS').html());
  }
</script>
<script language="javascript">
$("#subcategory_id").chained("#category_id");
</script>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
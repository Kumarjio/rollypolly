<?php
try {
check_login();
include(SITEDIR.'/includes/navLeftSideVars.php');

if (empty($_GET['module_id'])) {
  throw new Exception('Incorrect Module');
}

$get_rsView = getString(array('id'));
$redirectURLClear = $currentURL.'/auto/browse?clear=1'.$get_rsView;
$redirectURL = $currentURL.'/auto/browse?'.$get_rsView;

$id = isset($_GET['id']) ? $_GET['id'] : '';

//$latitude = $globalCity['latitude'];
//$longitude = $globalCity['longitude'];


$error = '';
if (!empty($_GET['msg'])) {
  $error = $_GET['msg'];
}


$colname_rsModule = "-1";
if (isset($_GET['module_id'])) {
  $colname_rsModule = $_GET['module_id'];
}
$t = (3600*24*7);

$query = "SELECT * FROM z_modules WHERE module_id = ?";
$resultModule = $modelGeneral->fetchRow($query, array($colname_rsModule), $t);
//pr($resultModule);
if (empty($resultModule)) {
  throw new Exception('Could not find the module');
}
if ($resultModule['module_status'] == 0) {
  throw new Exception('module is inactive');
}


if ($resultModule['new_page'] == 0) {
  throw new Exception('page is not accessible');
}

$pageTitle = 'New "'.$resultModule['menu_display_name']. '" ';

/*if (!empty($_GET['rel_id'])) {
  $query = "SELECT * FROM z_modules WHERE module_id = ?";
  $resultModuleRelated = $modelGeneral->fetchRow($query, array($_GET['rel_id']), $t);
  if (empty($resultModuleRelated)) {
    throw new Exception('Could not find the related module');
  }
  
  if (!empty($_GET['detail_id'])) {
    $tablenameDetail = 'auto_'.$resultModuleRelated['module_name'];
    $queryDetail = "SELECT a.* FROM $tablenameDetail as a LEFT JOIN google_auth as u ON a.uid = u.uid WHERE 1 AND a.id = ?";
    $rowResultDetail = $modelGeneral->fetchRow($queryDetail, array($_GET['detail_id']), 0);
    if (empty($rowResultDetail)) {
      throw new Exception('Could not find the related record id');
    }
    $pageTitle .= !empty($rowResultDetail['title']) ? ' for "'.$rowResultDetail['title'].'"' : '';
  }
}*/

$query = "SELECT * FROM z_modules_fields WHERE module_id = ? ORDER BY sorting ASC";
$resultModuleFields = $modelGeneral->fetchAll($query, array($colname_rsModule), $t);
if (empty($resultModuleFields)) {
  throw new Exception('Could not find the module fields');
}

$tablename = 'auto_'.$resultModule['module_name'];

$images = array();
$videos = array();
$urls = array();

if ($id) {
    $queryEdit = "SELECT a.* FROM $tablename as a LEFT JOIN google_auth as u ON a.uid = u.uid WHERE 1 AND a.id = ?";
    $rowResultEdit = $modelGeneral->fetchRow($queryEdit, array($id), 0);
    if ($_SESSION['user']['id'] != $rowResultEdit['uid']) {
      header("Location: ".$redirectURL);
      exit;
    }
    //decryption
    foreach ($resultModuleFields as $k => $v) {
      if (!empty($rowResultEdit[$v['field_name']]) && $v['encrypted'] == 1) {
        $rowResultEdit[$v['field_name']] = decryptText($rowResultEdit[$v['field_name']]);
      }
      if ($v['field_type'] == 'images') {
        if (!empty($rowResultEdit[$v['field_name']])) {
          $images = json_decode($rowResultEdit[$v['field_name']], 1);
          if (!empty($images)) {
            $image = $images[0];
          }
        }
      }
      if ($v['field_type'] == 'videos') {
        if (!empty($rowResultEdit[$v['field_name']])) {
          $videos = json_decode($rowResultEdit[$v['field_name']], 1);
        }
      }
      if ($v['field_type'] == 'urls') {
        if (!empty($rowResultEdit[$v['field_name']])) {
          $urls = json_decode($rowResultEdit[$v['field_name']], 1);
        }
      }
    }
    //decryption
    if (!empty($rowResultEdit)) {
      if (empty($_POST)) {
        $_POST = $rowResultEdit;
      }
    }
    if (!empty($_POST['clatitude']) && !empty($_POST['clongitude'])) {
      $latitude = $_POST['clatitude'];
      $longitude = $_POST['clongitude'];
    } else if (!empty($_POST['lat']) && !empty($_POST['lng'])) {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
    }
    $queryEditTags = "SELECT * FROM auto_pre_tags WHERE id = ?";
    $rowResultEditTags = $modelGeneral->fetchAll($queryEditTags, array($id), 0);
    if (!empty($rowResultEditTags)) {
      $tmp = array();
      foreach ($rowResultEditTags as $tags) {
        $tmp[] = $tags['tag'];
      }
      $_POST['tags'] = implode(', ', $tmp);
    }

    $pageTitle = 'Edit Record "'.(!empty($rowResultEdit['title']) ? $rowResultEdit['title'] : '').'"';
    
}


if (isset($_POST['MM_Insert']) && $_POST['MM_Insert'] == 'form1') {
  $return = createNewPost($_POST, $currentURL, $globalCity, $colname_rsModule, $resultModule, $resultModuleFields, $modelGeneral, $tablename, $_SESSION['user']['id'], 0);
  if ($return['error'] == 0) {
    header("Location: ".$return['url']);
    exit;
  } else {
    $error = $return['error'];
  }
  /*try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      $data = $_POST;
      if (isset($data['MM_Insert'])) unset($data['MM_Insert']);
      if (isset($data['submit'])) unset($data['submit']);
      $data['id'] = guid();
      $data['uid'] = $_SESSION['user']['id'];

      $data['city_id'] = $globalCity['id'];
      $data['module_id'] = $colname_rsModule;
      $data['rc_created_dt'] = date('Y-m-d H:i:s');
      $data['rc_updated_dt'] = date('Y-m-d H:i:s');
      if (!empty($_POST['lat'])) {
          $data['clatitude'] = $latitude;
          unset($data['lat']);
      }
      if (!empty($_POST['lng'])) {
          $data['clongitude'] = $longitude;
          unset($data['lng']);
      }
      //encryption
      foreach ($resultModuleFields as $k => $v) {
        if (isset($data[$v['field_name']]) && $v['encrypted'] == 1) {
          $data[$v['field_name']] = encryptText($data[$v['field_name']]);
        }
      }
      //encryption
      foreach ($_POST as $k => $v) {
        if (is_array($v)) {
          $_POST[$k] = !empty($_POST[$k]) ? array_filter($_POST[$k]) : array();
          $data[$k] = json_encode($_POST[$k]);
        }
      }
      $data['rc_approved'] = 0;
      if ($resultModule['paid_module'] == 1 && $resultModule['paid_posting'] == 1) {
          $data['rc_approved'] = 0;
      } else {
        if ($resultModule['approval_needed'] == 0) {
            $data['rc_approved'] = 1;
        }
      }
      $result = $modelGeneral->addDetails($tablename, $data, $_SESSION['user']['id']);
      //tag start
      if (!empty($_POST['tags'])) {
        $tmp = explode(',', $_POST['tags']);
        foreach ($tmp as $v) {
          $v = trim($v);
          $d = array();
          $d['id'] = $data['id'];
          $d['tag'] = $v;
          $d['module_id'] = $colname_rsModule;
          $modelGeneral->addDetails('auto_pre_tags', $d);
        }
      }
      //tag ends
      //multiselect
      foreach ($resultModuleFields as $k => $v) {
        if ($v['field_type'] === 'multipleselectbox') {
          //adding category
          if (!empty($_POST[$v['field_name']])) {
            foreach ($_POST[$v['field_name']] as $v1) {
              $v1 = trim($v1);
              $d = array();
              $d['id'] = $data['id'];
              $d['module_id'] = $colname_rsModule;
              $d['category_id'] = $v1;
              $d['col_name'] = $v['field_name'];
              $modelGeneral->addDetails('auto_pre_multiselectcats', $d);
            }
          }
        }
      }
      //multiselect
      if ($resultModule['paid_module'] == 1 && $resultModule['paid_posting'] == 1) {
          $error = 'Successfully added your record in database. Please <a href="#todo">Click Here</a> to pay $ '.$resultModule['paid_amount'].' to activate your posting.';
      } else {
          $error = 'Successfully added your record in database. It will be visible in few minutes.';
      }
      header("Location: ".$currentURL."/auto/new?module_id=".$colname_rsModule."&msg=".urlencode($error).$extendedURL);
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }*/
}


if (isset($_POST['MM_Update']) && $_POST['MM_Update'] == 'form1') {
  try {
      $latitude = $_POST['lat'];
      $longitude = $_POST['lng'];
      $data = $_POST;
      if (isset($data['MM_Update'])) unset($data['MM_Update']);
      if (isset($data['submit'])) unset($data['submit']);
      $data['rc_updated_dt'] = date('Y-m-d H:i:s');
      if (!empty($_POST['lat'])) {
          $data['clatitude'] = $latitude;
          unset($data['lat']);
      }
      if (!empty($_POST['lng'])) {
          $data['clongitude'] = $longitude;
          unset($data['lng']);
      }
      //encryption
      foreach ($resultModuleFields as $k => $v) {
        if (isset($data[$v['field_name']]) && $v['encrypted'] == 1) {
          $data[$v['field_name']] = encryptText($data[$v['field_name']]);
        }
      }
      //encryption
      foreach ($_POST as $k => $v) {
        if (is_array($v)) {
          $_POST[$k] = !empty($_POST[$k]) ? array_filter($_POST[$k]) : array();
          $data[$k] = json_encode($_POST[$k]);
        }
      }
      $data['rc_approved'] = 0;
      if ($resultModule['approval_needed'] == 0) {
          $data['rc_approved'] = 1;
      }
      $where = sprintf('uid = %s AND id=%s', $modelGeneral->qstr($_SESSION['user']['id']), $modelGeneral->qstr($_GET['id']));
      $result = $modelGeneral->updateDetails($tablename, $data, $where);
      //deleting from tags table
      $query = "delete from auto_pre_tags where id = ".$modelGeneral->qstr($_GET['id']);
      $modelGeneral->deleteDetails($query);
      //adding tags
      if (!empty($_POST['tags'])) {
        $tmp = explode(',', $_POST['tags']);
        foreach ($tmp as $v) {
          $v = trim($v);
          $d = array();
          $d['id'] = $_GET['id'];
          $d['tag'] = $v;
          $modelGeneral->addDetails('auto_pre_tags', $d);
        }
      }
      //multiselect
      foreach ($resultModuleFields as $k => $v) {
        if ($v['field_type'] === 'multipleselectbox') {
          //deleting from multiselect category table
          $query = "delete from auto_pre_multiselectcats where id = ".$modelGeneral->qstr($_GET['id']);
          $modelGeneral->deleteDetails($query);
          //adding category
          if (!empty($_POST[$v['field_name']])) {
            foreach ($_POST[$v['field_name']] as $v1) {
              $v1 = trim($v1);
              $d = array();
              $d['id'] = $_GET['id'];
              $d['category_id'] = $v1;
              $d['col_name'] = $v['field_name'];
              $modelGeneral->addDetails('auto_pre_multiselectcats', $d);
            }
          }
        }
      }
      //multiselect
      $error = 'Successfully updated your record in database. Updated record will be displayed in few minutes.';
      header("Location: ".$currentURL."/auto/edit?id=".$_GET['id']."&module_id=".$colname_rsModule."&msg=".urlencode($error));
      exit;
  } catch (Exception $e) {
      $error = $e->getMessage();
  }
}

include(SITEDIR.'/libraries/addresses/addressGrabberHead2.php');
?>
<!-- Date time picker -->
<link rel="stylesheet" type="text/css" href="<?php echo HTTPPATH; ?>/styles/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

</style>
<script src="<?php echo HTTPPATH; ?>/scripts/jquery.datetimepicker.js"></script>
<!-- Date time picker -->

<div class="row">
  <div class="col-lg-12">
    <h3><?php echo $pageTitle; ?></h3>
    <?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
    <p><?php if ($resultModule['browse_page'] == 1) { ?><a href="<?php echo $currentURL; ?>/auto/browse?<?php echo $get_rsView; ?>">Browse <?php echo $resultModule['menu_display_name']; ?></a><?php } ?><?php if ($resultModule['my_page'] == 1) { ?> | <a href="<?php echo $currentURL; ?>/auto/my?<?php echo $get_rsView; ?>">My <?php echo $resultModule['menu_display_name']; ?></a><?php } ?></p>
  </div>
</div>
<form name="form1" id="form1" method="post" action="<?php if ($id) echo $currentURL."/auto/edit?id=".$id."&module_id=".$colname_rsModule.$extendedURL; else echo $currentURL."/auto/new?module_id=".$colname_rsModule.$extendedURL; ?>">

<div class="row">
  <div class="col-lg-12">
      <div class="panel-group" id="accordion">
      <?php
      foreach ($resultModuleFields as $k => $v) { 
        if ($v['field_type'] === 'addressbox') { ?>
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
          <?php
        unset($resultModuleFields[$k]);
        }
      }
      ?>
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail"><span class="glyphicon glyphicon-file">
                      </span>Details</a>
                  </h4>
              </div>
              <div id="collapseDetail" class="panel-collapse collapse in">
                  <div class="panel-body">
                      <?php if ($id) { ?>
                      <div class="form-group">
                        <strong>Status</strong> <br />
                          <input type="radio" name="rc_status" id="rc_status_1" value="1" <?php echo (!empty($_POST['rc_status']) && $_POST['rc_status'] == 1) ? 'checked' : ''; ?> /> <strong>Active</strong> 
                          <input type="radio" name="rc_status" id="rc_status_2" value="0" <?php echo (isset($_POST['rc_status']) && $_POST['rc_status'] == 0) ? 'checked' : ''; ?> /> <strong>Inactive</strong> 
                      </div>
                      <?php } ?>
                      <?php if ($resultModule['search_box'] == 1) { ?>
                      <div class="form-group">
                        <strong>Tags:</strong> (Comma separated words like tag1, tag2, tag3)<br />
                        <input name="tags" type="text" class="inputText" id="tags" value="<?php echo !empty($_POST['tags']) ? $_POST['tags'] : ''; ?>" maxlength="40" />
                      </div>
                      <?php } ?>
                      <?php foreach ($resultModuleFields as $k => $v) { ?>
                          <?php if ($v['field_type'] === 'varchar' || $v['field_type'] === 'hidden' || $v['field_type'] === 'double' || $v['field_type'] === 'int') { ?>
                              <div class="form-group">
                                <strong><?php echo $v['field_display_name']; ?> <?php echo !empty($v['required']) ? '*' : ''; ?></strong> <br />
                                <input type="text" name="<?php echo $v['field_name']; ?>" id="<?php echo $v['field_name']; ?>" class="inputText" value="<?php echo !empty($_POST[$v['field_name']]) ? $_POST[$v['field_name']] : ''; ?>" <?php echo !empty($v['required']) ? 'required' : ''; ?> />
                              </div>
                          <?php } ?>
                          <?php if ($v['field_type'] === 'checkbox') { ?>
                              <div class="form-group">
                                <input type="checkbox" name="<?php echo $v['field_name']; ?>" id="<?php echo $v['field_name']; ?>" value="1" <?php echo !empty($_POST[$v['field_name']]) ? 'checked' : ''; ?> /> <strong><?php echo $v['field_display_name']; ?></strong> <?php echo !empty($v['required']) ? '*' : ''; ?>
                              </div>
                          <?php } ?>
                          
                          <?php if ($v['field_type'] === 'radio') {
                              $options = json_decode($v['related_information'], 1);
                              
                            ?>
                              <div class="form-group">
                                <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                                  <?php foreach ($options as $k1 => $v1) { ?>
                                  <input type="radio" name="<?php echo $v['field_name']; ?>" id="<?php echo $v['field_name'].'_'.$k1; ?>" value="<?php echo $v1; ?>" <?php echo (!empty($_POST[$v['field_name']]) && $v['field_name'] == $k1) ? 'checked' : ''; ?> /> <strong><?php echo $v1; ?></strong> 
                                  <?php } ?>
                              </div>
                          <?php } ?>
                          <?php if ($v['field_type'] === 'text') { ?>
                          <div class="form-group">
                              <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                              <textarea name="<?php echo $v['field_name']; ?>" rows="5" id="<?php echo $v['field_name']; ?>" class="inputText"><?php echo !empty($_POST[$v['field_name']]) ? $_POST[$v['field_name']] : ''; ?></textarea>
                          </div>
                          <?php } ?>
                          <?php if ($v['field_type'] === 'datetime') { ?>
                              <div class="form-group">
                                <strong><?php echo $v['field_display_name']; ?> <?php echo !empty($v['required']) ? '*' : ''; ?></strong> <br />
                                <input type="text" name="<?php echo $v['field_name']; ?>" id="<?php echo $v['field_name']; ?>" class="inputText" value="<?php echo !empty($_POST[$v['field_name']]) ? $_POST[$v['field_name']] : ''; ?>" <?php echo !empty($v['required']) ? 'required' : ''; ?> />
                              </div>
                              <script language="javascript">
                                //date time picker
                                $('#<?php echo $v['field_name']; ?>').datetimepicker({
                                  format:'Y-m-d H:i:s',
                                  step:5,
                                });
                                //date time picker
                              </script>
                          <?php } ?>
                          <?php if ($v['field_type'] === 'date') { ?>
                              <div class="form-group">
                                <strong><?php echo $v['field_display_name']; ?> <?php echo !empty($v['required']) ? '*' : ''; ?></strong> <br />
                                <input type="text" name="<?php echo $v['field_name']; ?>" id="<?php echo $v['field_name']; ?>" class="inputText" value="<?php echo !empty($_POST[$v['field_name']]) ? $_POST[$v['field_name']] : ''; ?>" <?php echo !empty($v['required']) ? 'required' : ''; ?> />
                              </div>
                              <script language="javascript">
                                //date time picker
                                $('#<?php echo $v['field_name']; ?>').datetimepicker({
                                  format:'Y-m-d',
                                  timepicker:false
                                });
                                //date time picker
                              </script>
                          <?php } ?>
                          <?php if ($v['field_type'] === 'images') { ?>
                          <div class="form-group" id="imgs_<?php echo $k; ?>">
                              <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                              <?php if (!empty($images)) { ?>
                                <?php foreach ($images as $image) { ?>
                                <input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="<?php echo $image; ?>" placeholder="Enter Image URL" />
                                <?php } ?>
                              <?php } else { ?>
                              <input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="" placeholder="Enter Image URL"/>
                              <?php } ?>
                          </div>
                          <div class="form-group">
                            <input type="button" name="<?php echo $v['field_name']; ?>_img_add" id="<?php echo $v['field_name']; ?>_img_add" onClick="addimage('<?php echo $k; ?>')" value="Add More Image URL" />
                          </div>
                          <div id="tmpImgs_<?php echo $k; ?>" style="display:none;"><input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="" placeholder="Enter Image URL" /></div>
                          <?php } ?>
                          
                          <?php if ($v['field_type'] === 'videos') { ?>
                          <div class="form-group" id="videos_<?php echo $k; ?>">
                              <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                              <?php if (!empty($videos)) { ?>
                                <?php foreach ($videos as $video) { ?>
                                <input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="<?php echo $video; ?>" placeholder="Enter Youtube or Vimeo Video URL" />
                                <?php } ?>
                              <?php } else { ?>
                              <input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="" placeholder="Enter Youtube or Vimeo Video URL" />
                              <?php } ?>
                          </div>
                          <div class="form-group">
                            <input type="button" name="<?php echo $v['field_name']; ?>_video_add" id="<?php echo $v['field_name']; ?>_video_add" onClick="addvideo('<?php echo $k; ?>')" value="Add More Youtube or Vimeo Video URL" />
                          </div>
                          <div id="tmpVideo_<?php echo $k; ?>" style="display:none;"><input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" placeholder="Enter Youtube or Vimeo Video URL" value="" /></div>
                          <?php } ?>
                          
                          <?php if ($v['field_type'] === 'urls') { ?>
                          <div class="form-group" id="urls_<?php echo $k; ?>">
                              <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                              <?php if (!empty($urls)) { ?>
                                <?php foreach ($urls as $url) { ?>
                                <input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="<?php echo $url; ?>" placeholder="Enter Web URL" />
                                <?php } ?>
                              <?php } else { ?>
                              <input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" value="" placeholder="Enter Web URL" />
                              <?php } ?>
                          </div>
                          <div class="form-group">
                            <input type="button" name="<?php echo $v['field_name']; ?>_url_add" id="<?php echo $v['field_name']; ?>_url_add" onClick="addurl('<?php echo $k; ?>')" value="Add More Web URL" />
                          </div>
                          <div id="tmpURLS_<?php echo $k; ?>" style="display:none;"><input type="text" name="<?php echo $v['field_name']; ?>[]" class="inputText" placeholder="Enter Web URL" value="" /></div>
                          <?php } ?>
                          
                          <?php if ($v['field_type'] === 'multipleselectbox') {
                              $options = json_decode($v['related_information'], 1);
                              $fieldOptions = json_decode($_POST[$v['field_name']], 1);
                            ?>
                              <div class="form-group">
                                <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                                <select name="<?php echo $v['field_name']; ?>[]" multiple size="5" class="inputText" <?php echo !empty($v['required']) ? 'required' : ''; ?>>
                                  <?php foreach ($options as $k1 => $v1) { ?>
                                  <option value="<?php echo $k1; ?>" <?php echo (!empty($_POST[$v['field_name']]) && in_array($k1, $fieldOptions)) ? 'selected' : ''; ?>><?php echo $v1; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                          <?php } ?>
                          
                          <?php if ($v['field_type'] === 'selectbox') {
                              $options = json_decode($v['related_information'], 1);
                              
                            ?>
                              <div class="form-group">
                                <strong><?php echo $v['field_display_name']; ?></strong>  <?php echo !empty($v['required']) ? '*' : ''; ?><br />
                                <select name="<?php echo $v['field_name']; ?>" class="inputText" <?php echo !empty($v['required']) ? 'required' : ''; ?>>
                                  <option value="">Choose:</option>
                                  <?php foreach ($options as $k1 => $v1) { ?>
                                  <option value="<?php echo $k1; ?>" <?php echo (!empty($_POST[$v['field_name']]) && $k1 == $_POST[$v['field_name']]) ? 'selected' : ''; ?>><?php echo $v1; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                          <?php } ?>
                      <?php } ?>
                      <script language="javascript">
                        function addimage(k)
                        {
                          $('#imgs_'+k).append($('#tmpImgs_'+k).html());
                        }
                        function addvideo(k)
                        {
                          $('#videos_'+k).append($('#tmpVideo_'+k).html());
                        }
                        function addurl(k)
                        {
                          $('#urls_'+k).append($('#tmpURLS_'+k).html());
                        }
                      </script>
                   </div>
               </div>
          </div>
      </div>
  </div>
</div>
<p>
  <input type="hidden" name="<?php if ($id) echo 'MM_Update'; else echo 'MM_Insert'; ?>" id="<?php if ($id) echo 'MM_Update'; else echo 'MM_Insert'; ?>" value="form1">
  <input type="submit" name="submit" id="submit" value="<?php if ($id) echo 'Update Record'; else echo 'Create New Record'; ?>" class="inputText">
</p>
</form>
<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
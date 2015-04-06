<?php
$pageTitle = 'Category Management';
$layoutStructure = 'simple';

$params = array();
$params['order'] = 'ORDER BY sorting';
$params['cacheTime'] = 3600 * 24;
$details = $modelGeneral->getDetails('z_store_categories', 1, $params);
?>
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <h2><?php echo $pageTitle; ?></h2>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-9">
    <div class="page-header">
      <h2>All Categories</h2>
      <ul>
        <?php foreach ($details as $v) { ?>
        <li><a href="javascript:;"><?php echo $v['comments']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="col-md-3">
    <div class="page-header">
      <h2>Add Category</h2>
    </div>
    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <?php if (!empty($error)) { echo '<div class="row"><div class="col-md-12"><div class="error">'.$error.'</div></div></div>'; } ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail"><span class="glyphicon glyphicon-file">
                            </span>Category Details</a>
                        </h4>
                    </div>
                    <div id="collapseDetail" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <div class="form-group">
                            <strong>Category Name:</strong> <br />
                            <input name="category" type="text" class="inputText" id="category" placeholder="Enter Category Name" value="<?php echo !empty($_POST['category']) ? $_POST['category'] : ''; ?>" maxlength="200" />
                          </div>
                          <div class="form-group">
                              <strong>Category Description:</strong> <br />
                              <textarea name="category_description" rows="5" id="category_description" class="inputText" placeholder="Enter Category Description"><?php echo !empty($_POST['category_description']) ? $_POST['category_description'] : ''; ?></textarea>
                          </div>
                          <div class="form-group">
                            <strong>Category Image:</strong> <br />
                            <input type="text" name="img_url" id="img_url" class="inputText" value="<?php echo !empty($_POST['img_url']) ? $_POST['img_url'] : ''; ?>" placeholder="Enter Category Image URL" />
                            
                          </div>
                          <div class="form-group">
                            <strong>or Upload Category Image:</strong> <br />
                            <input type="file" name="img_file" id="img_file">
                          </div>
                          <div class="form-group">
                            <strong>Category Parent:</strong> <br />
                            <input type="text" name="category_parent_id" id="category_parent_id" class="inputText" value="<?php echo !empty($_POST['category_parent_id']) ? $_POST['category_parent_id'] : ''; ?>" placeholder="Enter Category Parent ID" />
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<p>
  <input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Add New Category" class="inputText">
</p>
    </form>
  </div>
</div>
<?php
try {
check_login();
$layoutStructure = 'simple';
$pageTitle = 'Chess Opening Repertorie';
if (!empty($_POST['MM_Insert']) && !empty($_POST['repertory_name'])) {
    $data = array();
    $data['repertory_name'] = !empty($_POST['repertory_name']) ? $_POST['repertory_name'] : '';
    $data['uid'] = $_SESSION['user']['id'];
    $result = $modelGeneral->addDetails('chess_repertorie', $data);
}//end addition of repertorie

if (!empty($_GET['del'])) {
  $query = "DELETE FROM chess_repertorie WHERE repertory_id = ? AND uid = ?";
  $result = $modelGeneral->deleteDetails($query, array($_GET['id'], $_SESSION['user']['id']));
  $query = "DELETE FROM chess_repertorie_moves WHERE repertory_id = ? AND uid = ?";
  $result = $modelGeneral->deleteDetails($query, array($_GET['id'], $_SESSION['user']['id']));
}

$t = 3600;
$query = "SELECT * FROM chess_repertorie WHERE uid = ?";
if (!empty($_POST['MM_Insert']) || !empty($_GET['del'])) {
  $modelGeneral->clearCache($query, array($_SESSION['user']['id']));
  header("Location: ".HTTPPATH."/chess/repertorie/my");
  exit;
}

$resultChess = $modelGeneral->fetchAll($query, array($_SESSION['user']['id']), $t);
if (empty($resultChess)) {
  $modelGeneral->clearCache($query, array($_SESSION['user']['id']));
}

currentActivity('chess_opening_repertorie', $_SERVER['REQUEST_URI'], $globalCity['id'], 'User has browsed the Chess Opening Repertorie page.');
?>
<div class="row">
  <div class="col-lg-12">
    <h3>Chess Opening Repertorie</h3>
    <?php if (!empty($error)) { echo '<div class="error">'.$error.'</div>'; } ?>
    <p>Create your opening repertorie and save it for your own personal use.</p>
  </div>
</div>

<form name="form1" id="form1" method="post" action="">
<div class="row">
    <div class="col-lg-12">
        <div class="panel-group" id="accordion">
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
                            <strong>Opening Repertorie Name:</strong> <br />
                            <input name="repertory_name" type="text" class="inputText" id="repertory_name" value="<?php echo !empty($_POST['repertory_name']) ? $_POST['repertory_name'] : ''; ?>" required placeholder="e.g. Black Repertorie or White Repertorie" />
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
               
<p><input type="hidden" name="MM_Insert" id="MM_Insert" value="form1">
  <input type="submit" name="submit" id="submit" value="Create New Repertorie" class="inputText"></p>
</form>
<?php if (!empty($resultChess)) { ?>
<style type="text/css">
.toggle-header{
    padding:10px 0;
    margin:10px 0;
    background-color:black;
    color:white;
}
.txt-green{
    color:green;
}
.txt-red{
    color:red;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default clearfix">
            <div class="panel-heading">
            <h2 class="panel-title">My Opening Repertorie's</h2>
            <p class="small">
            Click any repertorie to view more details
            </p>
            </div>
        
            <div id="feature-1" class="collapse in">
                <?php foreach ($resultChess as $k => $v) { ?>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <?php echo $v['repertory_name']; ?>
                            </div>
                            <div class="col-xs-2 text-center">
                                <i class="glyphicon glyphicon-ok txt-green"> <a href="<?php echo HTTPPATH; ?>/chess/repertorie/detail?id=<?php echo $v['repertory_id']; ?>">Manage</a></i>
                            </div>
                            <div class="col-xs-2 text-center">
                                <i class="glyphicon glyphicon-remove txt-red"> <a href="<?php echo HTTPPATH; ?>/chess/repertorie/my?del=1&id=<?php echo $v['repertory_id']; ?>" onClick="var a = confirm('Do you really want to delete this reperotory?'); return a;">Delete</a></i>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
<?php } ?>



<?php } catch (Exception $e) {
  ?>
  <h3>Error!!</h3>
  <?php
  echo $e->getMessage();
}
?>
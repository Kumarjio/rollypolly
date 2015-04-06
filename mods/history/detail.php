<?php
try {
include(SITEDIR.'/includes/navLeftSideVars.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
  header("Location: /");
  exit;
}
$params = array();
$params['where'] = sprintf(' AND history_id = %s', $modelGeneral->qstr($id));
$details = $modelGeneral->getDetails('z_history LEFT JOIN google_auth ON z_history.uid = google_auth.uid', 1, $params);
if (empty($details)) {
  throw new Exception('Record not found.');
}
$details = $details[0];
$pageTitle = $details['history_title'];
$layoutStructure = 'detail';
if (!empty($details['showAddress'])) {
    $latitude = $details['history_lat'];
    $longitude = $details['history_lng'];
}
include(SITEDIR.'/libraries/addresses/nearbyforcity.php');
//pr($details);
if ($details['uid'] != $_SESSION['user']['id']) {
  if ($details['history_approved'] == 0) {
    throw new Exception('History Record is in pending status. Check out after some time.');
  }
  if ($details['history_status'] == 0) {
    throw new Exception('History Record is in not available. Check out after some time.');
  }
  if ($details['is_private'] == 1) {
    throw new Exception('History Record is not public.');
  }
}
if (!empty($details['history_image'])) {
  $images = json_decode($details['history_image'], 1);
  $image = !empty($images[0]) ? $images[0] : $details['picture'];
} else {
  $image = $row_rsView['picture'];
}
?>
<style type="text/css">
.user-row {
    margin-bottom: 14px;
}

.user-row:last-child {
    margin-bottom: 0;
}

.dropdown-user {
    margin: 13px 0;
    padding: 5px;
    height: 100%;
}

.dropdown-user:hover {
    cursor: pointer;
}

.table-user-information > tbody > tr {
    border-top: 1px solid rgb(221, 221, 221);
}

.table-user-information > tbody > tr:first-child {
    border-top: 0;
}


.table-user-information > tbody > tr > td {
    border-top: 0;
}
.toppad
{margin-top:20px;
}

</style>
<script language="javascript">
$(document).ready(function() {
    var panels = $('.user-infos');
    var panelsButton = $('.dropdown-user');
    panels.hide();

    //Click dropdown
    panelsButton.click(function() {
        //get data-for attribute
        var dataFor = $(this).attr('data-for');
        var idFor = $(dataFor);

        //current button
        var currentButton = $(this);
        idFor.slideToggle(400, function() {
            //Completed slidetoggle
            if(idFor.is(':visible'))
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-up text-muted"></i>');
            }
            else
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-down text-muted"></i>');
            }
        })
    });


    $('[data-toggle="tooltip"]').tooltip();

    $('button').click(function(e) {
        e.preventDefault();
        alert("This is a demo.\n :-)");
    });
});
</script>
<div class="row">
        <div class="col-lg-12 toppad" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $details['history_title']; ?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="<?php echo $image; ?>" class="img-circle" width="100" height="100"> </div>
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                         <tr>
                        <td>User</td>
                        <td><?php echo $details['name']; ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><strong>Description:</strong><br><?php echo nl2br($details['history_description']); ?></td>
                      </tr>
                      <tr>
                        <td>History date:</td>
                        <td><?php echo $details['history_date']; ?></td>
                      </tr>
                      <?php if (!empty($details['showAddress'])) { ?>
                      <tr>
                        <td>Address:</td>
                        <td><?php echo !empty($details['address2']) ? $details['address2'] : $details['address']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php if (!empty($details['history_points'])) { ?>
                         <tr>
                        <td>Matching Points With Owner</td>
                        <td><?php echo $details['history_points']; ?> (<?php echo $details['history_points_results']; ?>)</td>
                      </tr>
                      <?php } ?>
                         
                     
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
                 <div class="panel-footer">
                        
                    </div>
            
          </div>
        </div>
      </div>
<?php
} catch (Exception $e) {
  echo '<h3>Error</h3>
  <p class="error">'.$e->getMessage().'</p>
  ';
}
?>
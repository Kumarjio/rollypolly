<div class="row">
    <div class="col-12 col-lg-6">
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-pushpin main-color"></i> Details</h3>
          </div>
          <div class="panel-body">
            <?php if (!empty($resultModuleFields2['category']['related_information'])) { ?>
            <b>Category: </b> 
            <?php
                $options = json_decode($resultModuleFields2['category']['related_information'], 1);
                echo $options[$rowResult['category']];
            ?>
            <br />
            <?php } ?>
            <?php include(SITEDIR.'/includes/related_link.php'); ?>
          </div>
        </div>
    </div>
   <div class="col-12 col-lg-6">
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-time main-color"></i> Features</h3>
          </div>
          <div class="panel-body">
              <?php if (!empty($rowResult['compensation'])) { ?>
              <b>Compensation: </b> 
              <?php
                  echo $rowResult['compensation'];
              ?>
              <br />
              <?php } ?>
              <b>Telecommuting: </b> 
              <?php
                  echo ($rowResult['telecommuting'] == 1) ? 'Yes' : 'No';
              ?>
              <br />
              <b>Part - Time: </b> 
              <?php
                  echo ($rowResult['parttime'] == 1) ? 'Yes' : 'No';
              ?>
              <br />
              <b>Contract: </b> 
              <?php
                  echo ($rowResult['contract'] == 1) ? 'Yes' : 'No';
              ?>
              <br />
              <b>Non Profit Organization: </b> 
              <?php
                  echo ($rowResult['nonprofitorganization'] == 1) ? 'Yes' : 'No';
              ?>
              <br />
              <b>Internship: </b> 
              <?php
                  echo ($rowResult['internship'] == 1) ? 'Yes' : 'No';
              ?>
              <br />
            </div>
         </div>
   </div>
</div>
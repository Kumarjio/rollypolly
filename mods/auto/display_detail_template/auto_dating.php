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
            <?php if (!empty($resultModuleFields2['marital_status']['related_information'])) { ?>
            <b>Marital Status: </b> 
            <?php
                $options = json_decode($resultModuleFields2['marital_status']['related_information'], 1);
                echo $options[$rowResult['marital_status']];
            ?>
            <br />
            <?php } ?>
            <?php if (!empty($resultModuleFields2['body']['related_information'])) { ?>
            <b>Body: </b> 
            <?php
                $options = json_decode($resultModuleFields2['body']['related_information'], 1);
                echo $options[$rowResult['body']];
            ?>
            <br />
            <?php } ?>
            <?php if (isset($rowResult['distance'])) { ?>
            <b>Distance: </b> 
            <?php
                echo $rowResult['distance'].' mi';
            ?>
            <br />
            <?php } ?>
          </div>
        </div>
    </div>
   <div class="col-12 col-lg-6">
        <div class="panel">
          <div class="panel-heading">
            <h3><i class="icon-time main-color"></i> more</h3>
          </div>
          <div class="panel-body">
            </div>
         </div>
   </div>
</div>
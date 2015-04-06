<div class="col-xs-3" id="adright" style="padding-top: 20px;">
    <div class="panel panel-primary">
      <div class="panel-heading">Activities</div>
      <div class="panel-body">


      <style type="text/css">
      /* 
// Listrap v1.0, by Gustavo Gondim (http://github.com/ggondim)
// Licenced under MIT License
// For updates, improvements and issues, see https://github.com/inosoftbr/listrap
*/

.listrap {
            list-style-type: none;
            margin: 0;
            padding: 0;
            cursor: default;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .listrap li {
            margin: 0;
            padding: 10px;
        }

        .listrap li.active, .listrap li:hover {
            background-color: #d9edf7;
        }

        .listrap strong {
            margin-left: 10px;
        }

        .listrap .listrap-toggle {
            display: inline-block;
            width: 60px;
            height: 60px;
        }

        .listrap .listrap-toggle span {
            background-color: #428bca;
            opacity: 0.8;
            z-index: 100;
            width: 60px;
            height: 60px;
            display: none;
            position: absolute;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            vertical-align: middle;
            color: #ffffff;
        }

        .listrap .listrap-toggle span:before {
            font-family: 'Glyphicons Halflings';
            content: "\e013";
        }

        .listrap li.active .listrap-toggle span {
            display: block;
        }
        
        
        
        
        .block-update-card {
  height: 100%;
  border: 1px #FFFFFF solid;
  /*width: 380px;*/
  float: left;
  margin-left: 10px;
  margin-top: 0;
  padding: 0;
  box-shadow: 1px 1px 8px #d8d8d8;
  background-color: #FFFFFF;
}
.block-update-card .h-status {
  width: 100%;
  height: 7px;
  background: repeating-linear-gradient(45deg, #606dbc, #606dbc 10px, #465298 10px, #465298 20px);
}
.block-update-card .v-status {
  width: 5px;
  height: 80px;
  float: left;
  margin-right: 5px;
  background: repeating-linear-gradient(45deg, #606dbc, #606dbc 10px, #465298 10px, #465298 20px);
}
.block-update-card .update-card-MDimentions {
  width: 80px;
  height: 80px;
}
.block-update-card .update-card-body {
  margin-top: 10px;
  margin-left: 5px;
}
.block-update-card .update-card-body h4 {
  color: #737373;
  font-weight: bold;
  font-size: 13px;
}
.block-update-card .update-card-body p {
  color: #737373;
  font-size: 12px;
}
.block-update-card .card-action-pellet {
  padding: 5px;
}
.block-update-card .card-action-pellet div {
  margin-right: 10px;
  font-size: 15px;
  cursor: pointer;
  color: #dddddd;
}
.block-update-card .card-action-pellet div:hover {
  color: #999999;
}
.block-update-card .card-bottom-status {
  color: #a9a9aa;
  font-weight: bold;
  font-size: 14px;
  border-top: #e0e0e0 1px solid;
  padding-top: 5px;
  margin: 0px;
}
.block-update-card .card-bottom-status:hover {
  background-color: #dd4b39;
  color: #FFFFFF;
  cursor: pointer;
}

/*
Creating a block for social media buttons
*/
.card-body-social {
  font-size: 30px;
  margin-top: 10px;
}
.card-body-social .git {
  color: black;
  cursor: pointer;
  margin-left: 10px;
}
.card-body-social .twitter {
  color: #19C4FF;
  cursor: pointer;
  margin-left: 10px;
}
.card-body-social .google-plus {
  color: #DD4B39;
  cursor: pointer;
  margin-left: 10px;
}
.card-body-social .facebook {
  color: #49649F;
  cursor: pointer;
  margin-left: 10px;
}
.card-body-social .linkedin {
  color: #007BB6;
  cursor: pointer;
  margin-left: 10px;
}

.music-card {
  background-color: green;
}

      </style>
        <?php $activities = getActivity(); ?>
        <?php if (!empty($activities)) { ?>
        <ul class="listrap">
        <?php foreach ($activities as $activity) { ?>
        <div class="media block-update-card">
  <a class="pull-left" href="<?php echo $activity['url']; ?>">
    <img class="media-object update-card-MDimentions" src="<?php echo !empty($activity['picture']) ? $activity['picture'] : '/images/user.png'; ?>" alt="...">
  </a>
  <div class="media-body update-card-body">
    <h4 class="media-heading"><?php echo ago(strtotime($activity['activity_date'])); ?></h4>
    <p><?php echo $activity['activity_description']; ?> (<?php echo $activity['city']; ?> City)</p>
    <p><a href="<?php echo $activity['url']; ?>">Click Here To View</a></p>
  </div>
 </div>
  
        <?php } ?>
        </ul>
        <?php } ?>
      </div>
    </div>
</div>
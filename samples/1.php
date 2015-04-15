<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>

<body>
<?php
$text = <<< EOT
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                    <div class="panel panel-primary"  onclick="return init_map(3);">
                        <div class="row padall">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <span></span>
                                <img src="/Content/images/shared/properties1/house2.jpg" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <div class="clearfix">
                                    <div class="pull-left">
                                        <span class="fa fa-dollar icon">945,000</span>
                                    </div>
                                    <div class="pull-right">
                                        3,500 SqFt | 5 Bedrooms | 3 Bathrooms
                                    </div>
                                </div>
                                <div>
                                    <h4><span class="fa fa-map-marker icon"></span>2717 S Arlington Ridge Rd</h4>
                                    Build 2012. Call for availability.<span class="fa fa-search icon pull-right">   More</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
EOT;

?>

<div id="tt"></div>
<script language="javascript">
var h = '<?php echo $text; ?>';
$('#tt').html(h);
</script>
</body>
</html>
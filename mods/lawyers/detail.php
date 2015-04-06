<?php
if (empty($_GET['city_id'])) {
  header("Locations: ".HTTPPATH);
  exit;
}
include(SITEDIR.'/includes/navLeftSideVars.php');

if (empty($_GET['id'])) {
  header("Location: ".$currentURL."/lawyers/browse");
  exit;
}
try {
$nm = 'Lawyers in '.$globalCity['city'].' & Nearby Cities';
//getting records
$Models_Records = new Models_Records();
$params = array();
$params['record_id'] = $_GET['id'];
$params['admin_status'] = 1;
$data = $Models_Records->records_view($params);
if (empty($data['result'])) {
  throw new Exception('No Data Found.');
}
$result = $data['result'][0];
//pr($result);
$pageTitle = $result['title'];
$details = json_decode($result['details'], 1);
$url = makecityurl($result['cty_id'], $result['name']).'/lawyers/detail?id='.$result['record_id'];
?>
<script language="javascript">
//initialize the google Maps
var latitude = '<?php echo $result['lat']; ?>';
var longitude = '<?php echo $result['lon']; ?>';
initializeGoogleMap('mapCanvas');
</script>
<h3 align="center"><?php echo $pageTitle; ?></h3>
<div class="row">
    <div class="col-lg-12">

<div class="dtile">
  <div class="togglediv" style="display: block;">
      <div style="float:left;">
          <div  class="pho">
            <img src="<?php echo $details['image']; ?>">
          </div>
      </div>
      <div style="float:left; padding: 8px 8px 0 8px;width:50%; font-size:11px;">
        <div>
          <p><?php echo $details['description']; ?></p>
          <p><strong>Online Consultation Charges: </strong>$ <?php echo $details['charges']; ?>, for 1 month unlimited messages related to one case per client.</p>
          <p><strong>Address: </strong><?php echo $result['address']; ?></p>
          <p><a href="<?php echo $currentURL; ?>/lawyers/browse" class="btn btn-primary">Send Message</a></p>
        </div>
      </div>

  </div>
</div>

    </div>
</div>

<div class="row">
    <div class="col-lg-12">
      <h2 id="nav-tabs">More....</h2>
      <div class="bs-component">
        <ul class="nav nav-tabs">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              Pre-Consulting <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#sendPreMessage" data-toggle="tab">Send Message</a></li>
              <li class="divider"></li>
              <li><a href="#viewPreMessage" data-toggle="tab">View Message</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              Consulting <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#sendMessage" data-toggle="tab">Send Message</a></li>
              <li class="divider"></li>
              <li><a href="#viewMessage" data-toggle="tab">View Message</a></li>
            </ul>
          </li>
          <li><a href="#reviews" data-toggle="tab">Reviews</a></li>
          <li><a href="#accounts" data-toggle="tab">Accounts</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade active in" id="sendPreMessage">
            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
          </div>
          <div class="tab-pane fade" id="viewPreMessage">
            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
          </div>
          <div class="tab-pane fade" id="sendMessage">
            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
          </div>
          <div class="tab-pane fade" id="viewMessage">
            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
          </div>
          <div class="tab-pane fade" id="reviews">
            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
          </div>
          <div class="tab-pane fade" id="accounts">
            <p>Nil</p>
          </div>
        </div>
      </div>
    </div>
</div>

<?php } catch (Exception $e) { ?>
<h3>Something is Wrong!!</h3>
<div class="error"><?php echo $e->getMessage(); ?></div>
<?php } ?>
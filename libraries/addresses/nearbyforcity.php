<script type="text/javascript">
// initialize the google Maps
var latitude = '<?php echo !empty($latitude) ? $latitude : $globalCity['latitude']; ?>';
var longitude = '<?php echo !empty($longitude) ? $longitude : $globalCity['longitude']; ?>';
initializeGoogleMap('mapCanvas');
</script>
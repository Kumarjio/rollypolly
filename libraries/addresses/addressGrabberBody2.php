
<script language="javascript">
$( document ).ready(function() {
    init();
});
</script>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div id="latlng"></div>
        </div>
        <div class="form-group">
          <div id="mapCanvas"></div>
          <input type="hidden" name="lat" id="lat" value="">
          <input type="hidden" name="lng" id="lng" value="">
          <input id="address" name="address" placeholder="Enter your address"
                                       onFocus="geolocate()" type="text" style="width:70%" value="<?php echo (!empty($_POST['address'])) ? $_POST['address'] : ''; ?>" class="addressBox"></input>
          <input type="button" value="Find Address" onclick="codeAddress()">
          <br /><br />
          <p><b>Note: If you feel that the address in above field is incorrect, then please re-enter your address here below (after you move the map icon to proper location):</b><br /><br />
          <input id="address2" name="address2" placeholder="Enter custom address" type="text" style="width:100%" value="<?php echo (!empty($_POST['address2'])) ? $_POST['address2'] : ''; ?>" />
                                       </p>
        </div>
        <div class="form-group">
          <input name="showAddress" type="checkbox" id="showAddress" value="1" <?php if ((isset($_POST['showAddress']) && $_POST['showAddress'] == 1) || !isset($_POST['showAddress'])) { ?>checked="checked"<?php } ?>>
          <label for="showAddress">Show on map </label>
        </div>
    </div>
</div>
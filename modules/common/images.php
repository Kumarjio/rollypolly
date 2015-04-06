<h1>Images</h1>
<div>
  <input type="button" name="addImg" value="Add Image" onClick="addImage();" /><br /><br /><br />
  <div id="imageList">
    
  </div>
  
<div id="tmp3" style="display:none">
  <input type="text" name="image[]" style="width:90%" placeholder="Insert Image Url"><br />
</div>

<script>
  function addImage()
  {
    var html = $('#tmp3').html();
    $('#imageList').append(html);
  }
</script>

</div>
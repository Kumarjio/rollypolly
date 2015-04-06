
      <p>
        <label for="title"><strong>Title:</strong></label>
        <br>
        <input type="text" name="title" id="title" value="<?php if (!empty($_POST['title']))  echo $_POST['title']; ?>" style="width:100%" class="required">
      </p>
      <p>
        <label for="description"><strong>Description:</strong> <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="please enter phone numbers as contact info below, not in posting body. Only one job description per posting please."><img src="<?php echo HTTPPATH; ?>/images/help.gif" /></a></label>
        <br>
        <textarea name="description" id="description" cols="45" rows="5" style="width:100%" class="required"><?php if (!empty($_POST['description']))  echo $_POST['description']; ?></textarea>
      </p>
      <p>
      <b>users can also contact me:</b> <input type="checkbox" value="1" name="contactbyphone" id="contactbyphone"> by phone <input type="checkbox" value="1" name="contactbytext" id="contactbytext"> by text<br /><br />
      <b>Phone Number:</b><br />
      <input type="text" name="phonenumber" id="phonenumber" value="<?php if (!empty($_POST['phonenumber']))  echo $_POST['phonenumber']; ?>"><br />
      <b>Contact Name:</b><br />
      <input type="text" name="contactname" id="contactname" value="<?php if (!empty($_POST['contactname']))  echo $_POST['contactname']; ?>"><br />
      </p>
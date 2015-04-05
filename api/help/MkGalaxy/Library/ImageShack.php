<?php
define('IMAGESHACK_KEY', '146HIJUV6647a9b0459fac0d39cd709c328dace3');
define('IMAGESHACK_USERNAME', 'websmc');
define('IMAGESHACK_PASSWORD', 'myflash74');
define('IMAGESHACK_APIURL_IMAGES', 'http://www.imageshack.us/upload_api.php');
define('IMAGESHACK_APIURL_VIDEOS', 'http://render.imageshack.us/upload_api.php');
$image_type_defined = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
define('IMAGE_TYPE_DEFINED', serialize($image_type_defined));

class Library_ImageShack
{
  
    
    public function upload($options=array())
    {
        $arr = $options;
        $arr['key'] = IMAGESHACK_KEY;
        $arr['a_username'] = IMAGESHACK_USERNAME;
        $arr['a_password'] = IMAGESHACK_PASSWORD;
        $arr = array_filter($arr);
        pr($arr);
        exit;
        $result = curlget(IMAGESHACK_APIURL_IMAGES, 1, $arr);
        $content = simplexml_load_string($result);
        $return['image'] = sprintf("%s", $content->links->image_link);
        $return['image_thumb_link'] = sprintf("%s", $content->links->thumb_link);
        pr($return);
        exit;
        return $return;
    }

    public function capture($image, $filename, $pathToSave, $httppath)
    {
      $data = base64_decode($image);
      $im = imagecreatefromstring($data);
      imagejpeg($im, $pathToSave.$filename);
      $arr = array();
      $arr['url'] = $httppath.$filename;
      $return = $this->upload($arr);
      pr($return);
      exit;
      $url = $return['image'];
      
    }
}
?>
<?php
class Library_Imageshack
{

	public function __construct()
	{
		
	}


	public function upload($url)
	{
		$arr = array();
		$arr['url'] = $url;
		$arr['key'] = IMAGESHACK_KEY;
		$arr['a_username'] = IMAGESHACK_USERNAME;
		$arr['a_password'] = IMAGESHACK_PASSWORD;
		$arr = array_filter($arr);
		$result = curlget(IMAGESHACK_APIURL_IMAGES, 1, $arr);
		$content = simplexml_load_string($result);
		$return['image'] = sprintf("%s", $content->links->image_link);
		$return['image_thumb_link'] = sprintf("%s", $content->links->thumb_link);
		return $return;
	}
	
	
	public function getextension($file)
	{
		$tumbnailExtention = preg_replace('/^.*\.([^.]+)$/D', '$1', $file);
		$tumbnailExtention = strtolower($tumbnailExtention);
		if ($tumbnailExtention === 'jpg') {
			$tumbnailExtention = 'jpeg';
		}

		return $tumbnailExtention;
	}
	
	
	public function getimagefile($file, $tumbnailExtention)
	{
		return "@".$file.';type=image/'.$tumbnailExtention;
	}
}
?>
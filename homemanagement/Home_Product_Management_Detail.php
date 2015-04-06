<?php
class Home_Product_Management_Detail {

	private static $instance;
	public function __construct() {
		if(self::$instance) {
			return self::$instance;
		} else {
			self::$instance = $this;
		}
	}
	
	public $catLink=array();
	public $catLinkChild=array();
	
	public function categoryParentLink($catId, $id, $siteId, $userId) {
		if(!$this->catLink) $this->catLink = array();
		$sql = "select * from home_product_management_details where detail_id = '".$catId."' and id = '".$id."' and site_id = '".$siteId."' and user_id = '".$userId."'";
			//$rs = $this->dbFrameWork->Execute($sql);
			//if($this->dbFrameWork->ErrorMsg()) {
				//throw new Exception($this->dbFrameWork->ErrorMsg());
			//}
			//if($rs->RecordCount()>0) {
		$rs = mysql_query($sql) or die('error');
		if(mysql_num_rows($rs)) {
				//$rec = $rs->FetchRow();			
			$rec = mysql_fetch_array($rs);
			$catId = $rec['detail_id'];
			$pid = $rec['pid'];
			$category = '<a href="'.$_SERVER['PHP_SELF'].'?catId='.$catId.'&id='.$id.'">'.$rec['name'].'</a>';
			array_unshift($this->catLink,$category);
			$this->categoryParentLink($ID, $pid);	
		} else {
			$this->catLinkDisplay = '<a href="'.$_SERVER['PHP_SELF'].'?catId='.$catId.'&id='.$id.'">Home</a> >> ';
			if($this->catLink) {
				foreach($this->catLink as $value) {
					$this->catLinkDisplay .= $value.' >> ';
				}
				$this->catLinkDisplay = substr($this->catLinkDisplay,0,-4);
			}
		}
	} 
	
	public function getArray($id, $siteId, $userId) {
		$sql = "select * from home_product_management_details where id = '".$id."' and site_id = '".$siteId."' and user_id = '".$userId."'";
		$rs = mysql_query($sql) or die('error');
		if(mysql_num_rows($rs)) {
			while($rec = mysql_fetch_array($rs)) {		
				$arr[] = $rec;
			}
		} else {
			$arr = array();
		}
		return $arr;
	} 
}
?>
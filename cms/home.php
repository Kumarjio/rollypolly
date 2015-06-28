<!--<script type="text/javascript" src="http://srvpub.com/adServe/banners?tid=34915_50844_0&size=728x90" ></script>-->
<?php
include(SITEDIR.'/libraries/rss.php');
$subTitle = $pageTitle.' News';
//caching
require_once('Cache/Lite/Output.php');

// Set a few options
$options = array(
    'cacheDir' => SITEDIR.'/cache/Pear_cache/',
    'lifeTime' => (3600 * 24 * 365 * 10)
);
$cache = new Cache_Lite_Output($options);
$key = 'rssNews_'.md5($pageTitle);

if (!($cache->start($key))) {

?>
<?php
//$myRss = new RSSParser("http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=".urlencode($pageTitle)."&cf=all&output=rss"); 
$myRss = new RSSParser("http://www.bing.com/news/search?q=".urlencode($pageTitle)."&FORM=HDRSC6&format=RSS");
$itemNum=0;
$myRss_RSSmax=0;
if($myRss_RSSmax==0 || $myRss_RSSmax>count($myRss->titles))$myRss_RSSmax=count($myRss->titles);
if ($myRss_RSSmax > 0) {
  ?>
  <ul>
  <?php
  for($itemNum=0;$itemNum<$myRss_RSSmax;$itemNum++){
      if ($itemNum == 1) {
        ?>
        <li><?php include('googletextads.php'); ?></li>
        <?php
      }
      ?>
      <li><a href="<?php echo $myRss->links[$itemNum]; ?>" target="_blank" rel="nofollow"><?php echo $myRss->titles[$itemNum]; ?></a><br>
      <small><?php $desc = $myRss->descriptions[$itemNum]; echo strip_tags($desc, '<b><strong><br>');?></small>
      </li>
<?php } ?>
  </ul>
  <?php
}
echo "<!--http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=".urlencode($pageTitle)."&cf=all&output=rss-->";
    $cache->end();
}
?>
<?php include('googleads.php'); ?>
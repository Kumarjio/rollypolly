<?php
include(SITEDIR.'/libraries/rss.php');
$subTitle = $pageTitle.' News';
?>
<?php

$myRss = new RSSParser("http://news.google.com/news?pz=1&cf=all&ned=us&hl=en&q=".urlencode($pageTitle)."&cf=all&output=rss"); 
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
?>
<?php include('googleads.php'); ?>
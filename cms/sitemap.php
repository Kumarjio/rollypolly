<?php
$pageTitle = 'SiteMap';
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsSitemap = 100;
$pageNum_rsSitemap = 0;
if (isset($_GET['pageNum_rsSitemap'])) {
  $pageNum_rsSitemap = $_GET['pageNum_rsSitemap'];
}
$result = $CMSActivities->getAllURLS('', $pageNum_rsSitemap, $maxRows_rsSitemap);
extract($result);

?>
<ul>
  <?php foreach ($rsSitemap as $row_rsSitemap) { ?>
  <li><a href="http://cms-<?php echo $row_rsSitemap['kw_url_lookup']; ?>.mkgalaxy.com/"><?php echo $row_rsSitemap['keyword']; ?></a> (<?php echo $row_rsSitemap['views']; ?> Views)</li>
    <?php } ?>
</ul>
<p> Records <?php echo ($startRow_rsSitemap + 1) ?> to <?php echo min($startRow_rsSitemap + $maxRows_rsSitemap, $totalRows_rsSitemap) ?> of <?php echo $totalRows_rsSitemap ?>
</p>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSitemap > 0) { // Show if not first page ?>
        <a href="<?php printf("http://cms.mkgalaxy.com/sitemap?pageNum_rsSitemap=%d%s", $currentPage, 0, $queryString_rsSitemap); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSitemap > 0) { // Show if not first page ?>
        <a href="<?php printf("http://cms.mkgalaxy.com/sitemap?pageNum_rsSitemap=%d%s", $currentPage, max(0, $pageNum_rsSitemap - 1), $queryString_rsSitemap); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSitemap < $totalPages_rsSitemap) { // Show if not last page ?>
        <a href="<?php printf("http://cms.mkgalaxy.com/sitemap?pageNum_rsSitemap=%d%s", $currentPage, min($totalPages_rsSitemap, $pageNum_rsSitemap + 1), $queryString_rsSitemap); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSitemap < $totalPages_rsSitemap) { // Show if not last page ?>
        <a href="<?php printf("http://cms.mkgalaxy.com/sitemap?pageNum_rsSitemap=%d%s", $currentPage, $totalPages_rsSitemap, $queryString_rsSitemap); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
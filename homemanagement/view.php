<?php require_once('../Connections/connHm.php'); ?>
<?php include_once('start.php') ?>
<?php
$page = $_GET['page'];
if(!$page) $page = 1;
?>
<?php
$maxRows_rsView = 5;
$pageNum_rsView = $page-1;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$coluser_rsView = "-1";
if (isset($_COOKIE['user_id'])) {
  $coluser_rsView = (get_magic_quotes_gpc()) ? $_COOKIE['user_id'] : addslashes($_COOKIE['user_id']);
}
$colname_rsView = "-1";
if (isset($_SESSION['site'][$_SESSION['siteurl']]['site_id'])) {
  $colname_rsView = (get_magic_quotes_gpc()) ? $_SESSION['site'][$_SESSION['siteurl']]['site_id'] : addslashes($_SESSION['site'][$_SESSION['siteurl']]['site_id']);
}
mysql_select_db($database_connHm, $connHm);
$query_rsView = sprintf("SELECT * FROM home_product_management WHERE site_id = %s and user_id = %s ORDER BY title ASC", $colname_rsView,$coluser_rsView);
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connHm) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;
// pagination
include_once('PaginateIt_hpm.php');
$PaginateIt = new PaginateIt();
$PaginateIt->linksHref = "home_product_management/view.php";
$PaginateIt->divtag = "centerDiv";
$PaginateIt->SetItemCount($totalRows_rsView);
$PaginateIt->SetItemsPerPage($maxRows_rsView);
$pagination = $PaginateIt->GetPageLinks();
?>
<div class="post">
	<h1 class="title"><a href="#">View My Places</a></h1>
	<p class="byline"><small>List of place created by me.</small></p>
	<div class="entry">
<?php
if(!$_COOKIE['user_id']) { 
	echo '<div class="error">Please login first.</div>';
} else {
	?>
<table width="60%" border="0" cellpadding="5" cellspacing="1" class="myTable">
  <tr class="myTrH">
    <td><strong>Place</strong></td>
    <td><strong>Add Assets </strong></td>
    <td><strong>Edit</strong></td>
    <td><strong>Delete</strong></td>
  </tr>
  <?php do { ?>
    <tr class="myTrD">
      <td><?php echo $row_rsView['title']; ?></td>
      <td><a href="#" onclick="doAjaxLoadingText('home_product_management/add.php','GET','pid=0&id=<?php echo $row_rsView['id']; ?>','','centerDiv','yes','Loading...','0','','','','','0');">Add Assets</a> </td>
      <td>Edit</td>
      <td>Delete</td>
    </tr>
    <?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</table>
<div class="pagination"><?php echo $pagination; ?></div>
<?php include('menu.php'); ?>
	<?php
}
?>
	</div>
</div>


<?php
mysql_free_result($rsView);
?>

<?php
$layoutStructure = 'simple';
exit;
$content = file_get_contents(SITEDIR."/superadmin/category/taxonomy.csv");
$lines = explode("\r", $content);
$return = array();

$sortOrder = 0;
foreach ($lines as $k => $v) {
  $col = explode(",", $v);
  $col = array_filter($col);
  $count = count($col);
  $tmp = array();
  if (!empty($col[0])) {
    $col[0] = str_replace('"', '', $col[0]);
    if (!isset($return[$col[0]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[0];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = NULL;
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['id'] = $id;
      $return[$col[0]]['child'] = array();
    }
  }
  if (!empty($col[1])) {
    $col[1] = str_replace('"', '', $col[1]);
    if (!isset($return[$col[0]]['child'][$col[1]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[1];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = $return[$col[0]]['id'];
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['child'][$col[1]]['id'] = $id;
      $return[$col[0]]['child'][$col[1]]['child'] = array();
    }
  }
  if (!empty($col[2])) {
    $col[2] = str_replace('"', '', $col[2]);
    if (!isset($return[$col[0]]['child'][$col[1]]['child'][$col[2]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[2];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = $return[$col[0]]['child'][$col[1]]['id'];
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['id'] = $id;
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'] = array();
    }
  }
  if (!empty($col[3])) {
    $col[3] = str_replace('"', '', $col[3]);
    if (!isset($return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[3];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['id'];
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['id'] = $id;
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'] = array();
    }
  }
  if (!empty($col[4])) {
    $col[4] = str_replace('"', '', $col[4]);
    if (!isset($return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[4];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['id'];
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['id'] = $id;
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'] = array();
    }
  }
  if (!empty($col[5])) {
    $col[5] = str_replace('"', '', $col[5]);
    if (!isset($return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[5];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['id'];
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['id'] = $id;
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['child'] = array();
    }
  }
  if (!empty($col[6])) {
    $col[6] = str_replace('"', '', $col[6]);
    if (!isset($return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['child'][$col[6]]['id'])) {
      $id = guid();
      $d = array();
      $d['category_id'] = $id;
      $d['category'] = $col[6];
      $d['category_created_date'] = date('Y-m-d H:i:s');
      $d['category_created_id'] = 'system';
      $d['category_parent_id'] = $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['id'];
      $d['comments'] = implode(' > ', $col);
      $sortOrder++;
      $d['sorting'] = $sortOrder;
      $result = $modelGeneral->addDetails('z_store_categories', $d);
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['child'][$col[6]]['id'] = $id;
      $return[$col[0]]['child'][$col[1]]['child'][$col[2]]['child'][$col[3]]['child'][$col[4]]['child'][$col[5]]['child'][$col[6]]['child'] = array();
    }
  }
}
  pr($return);
exit;
$row = 1;
if (($handle = fopen(SITEDIR."/superadmin/category/taxonomy.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 500, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        pr($data);
        exit;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
    }
    fclose($handle);
}
?>

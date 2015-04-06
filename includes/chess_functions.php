<?php
function oppositewins($p='B')
{
  return ($p === 'B') ? 1 : 2;
}

function getlegalmoves($standard, $fen)
{
  $promotepiece = array('Q', 'R', 'B', 'N');
  $standard->resetGame($fen);
  $toMove = $standard->toMove();
  
  if ($standard->inCheckMate()) {
    return array('success' => 1, 'result' => oppositewins($toMove), 'msg' => 'wins');
  }
  
  
  if ($standard->inRepetitionDraw()) {
    return array('success' => 1, 'result' => 'Draw', 'msg' => 'in repetition');
  }
  
  if ($standard->inStaleMate()) {
    return array('success' => 1, 'result' => 'Draw', 'msg' => 'in stalemate');
  }
  
  if ($standard->inDraw()) {
    return array('success' => 1, 'result' => 'Draw', 'msg' => 'in draw');
  }
  
  if ($standard->gameOver()) {
    return array('success' => 1, 'result' => $standard->gameOver(), 'msg' => 'wins');
  }
  
  $ploc = $standard->getPieceLocations($toMove);
  $toArray = $standard->toArray();
  $newloc = array();
  if ($ploc) {
    foreach ($ploc as $loc) {
      $newloc[$loc] = $toArray[$loc];
    }
  }
  $list = array();
  if (empty($newloc)) {
    return false;
  } else {
    foreach ($newloc as $sq => $loc) {
      $move = $standard->getPossibleMoves(strtoupper($loc), $sq, $toMove, true);
      if (!empty($move)) {
        foreach ($move as $m) {
          $promotemove = 0;
          if (strtoupper($loc) == 'P') {
            $promotemove = $standard->isPromoteMove($sq, $m);
          }
  
          if (!empty($promotemove)) {
            foreach ($promotepiece as $piece) {
              $legalmove = $standard->_convertSquareToSAN($sq, $m, $piece);
              $parseMove = $standard->_parseMove($legalmove);
              $validMove = $standard->_validMove($parseMove);
              if ($validMove === true) {
                $standard->resetGame($fen);
                $standard->moveSAN($legalmove);
                $renderFen = $standard->renderFen();
                $result = '';
                if ($standard->inCheckMate()) {
                  $result = '#';
                } else if ($standard->inRepetitionDraw()) {
                  $result = ' Draw in Repetition';
                } else if ($standard->inStaleMate()) {
                  $result = ' Stalemate Draw';
                } else if ($standard->inDraw()) {
                  $result = ' Draw';
                } else if ($standard->gameOver()) {
                  $result = '#';
                }
                $list[] = array('move' => $legalmove, 'fen' => $renderFen, 'result' => $result);
                $standard->resetGame($fen);
              }
            }
          } else {
            $legalmove = $standard->_convertSquareToSAN($sq, $m);
            $parseMove = $standard->_parseMove($legalmove);
            $validMove = $standard->_validMove($parseMove);
            if ($validMove === true) {
              $standard->resetGame($fen);
              $standard->moveSAN($legalmove);
              $renderFen = $standard->renderFen();
              $result = '';
              if ($standard->inCheckMate()) {
                $result = '#';
              } else if ($standard->inRepetitionDraw()) {
                $result = ' Draw in Repetition';
              } else if ($standard->inStaleMate()) {
                $result = ' Stalemate Draw';
              } else if ($standard->inDraw()) {
                $result = ' Draw';
              } else if ($standard->gameOver()) {
                $result = '#';
              }
              $list[] = array('move' => $legalmove, 'fen' => $renderFen, 'result' => $result);
              $standard->resetGame($fen);
            }
          }
        }
      }
    }
  }

  return $list;
}


function chess_tree_add($tree, $parent_id, $object, $move_id)
{
    if($parent_id == 0 and $object['move_id'] == $move_id)
    {
        $tree[$object['move_id']] = $object;
        return $tree;
    }

    if($tree) {
        foreach($tree as $key => $value) {
            $current = $tree[$key];
            // If this is the parent, add the object to it's children array
            if($current['move_id'] == $parent_id) {
                $tree[$key]['children'][$object['move_id']] = $object;
            } else {
                // If it's not in this level, look a level deeper on the current object.
                $tree[$key]['children'] = chess_tree_add($current['children'], $parent_id, $object, $move_id);
            }
        }
    }

    return $tree;
}

function createTree($id, $sel, $array, $currentParent, $currLevel = 0, $prevLevel = -1) {
  foreach ($array as $categoryId => $category) {
  if ($currentParent == $category['parent_id']) {                       
      /*echo "<li>
      <label for='folder2'>Folder 2</label> <input type='checkbox' id='folder2' /> 
      <ol>
        <li class='file'><a href=''>File 1</a></li>
        <li>
          <label for='subfolder2'>Subfolder 1</label> <input type='checkbox' id='subfolder2' /> 
          <ol>
            <li class='file'><a href=''>Subfile 1</a></li>
            <li class='file'><a href=''>Subfile 2</a></li>
            <li class='file'><a href=''>Subfile 3</a></li>
            <li class='file'><a href=''>Subfile 4</a></li>
            <li class='file'><a href=''>Subfile 5</a></li>
            <li class='file'><a href=''>Subfile 6</a></li>
          </ol>
        </li>
      </ol>
    </li>";*/
    if ($currLevel > $prevLevel) echo " <ol class='tree'>"; 
    if ($currLevel == $prevLevel) echo "</li> ";
    $checked = '';
    if (in_array($category['details']['move_id'], $sel)) {
      $checked = 'checked';
    }
    $moveSideString = '';
    if ($category['details']['move_side'] === 'W') {
      $moveSideString .= $category['details']['move_number'].'. ';
    }
    echo '<li> <label for="subfolder2">'.$moveSideString.'<a href="'.HTTPPATH.'/chess/repertorie/detail?id='.$id.'&move_id='.$category['details']['move_id'].'&parent_id='.$category['details']['move_id'].'" style="text-decoration:none;color:white;">'.$category['name'].'</a></label> <input type="checkbox" id="subfolder2" '.$checked.' />';
    if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
    $currLevel++; 
    createTree ($id, $sel, $array, $categoryId, $currLevel, $prevLevel);
    $currLevel--;               
    }
  }
  if ($currLevel == $prevLevel) echo " </li></ol>";
}


/*more functions: http://salman-w.blogspot.com/2012/08/php-adjacency-list-hierarchy-tree-traversal.html*/

//Basic Traversal
/*
 * Recursive top-down tree traversal example:
 * Indent and print child nodes
 */
function display_child_nodes($parent_id, $level)
{
    global $data, $index;
    $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
        foreach ($index[$parent_id] as $id) {
            echo str_repeat("-", $level) . $data[$id]["name"] . "\n";
            display_child_nodes($id, $level + 1);
        }
    }
}
//display_child_nodes(NULL, 0);
/*
Electronics
-Cameras and Photography
--Accessories
--Camcorders
--Digital Cameras
-Cell Phones and Accessories
--Accessories
--Cell Phones
--Smartphones
-Computers and Tablets
--Desktops
--Laptops
--Netbooks
--Tablets
---Android
---iPad
-TV and Audio
--Home Audio
--Speakers and Subwoofers
--Televisions
---CRT
---LCD
---LED
---Plasma
*/

//Bottom-up Traversal

/*
 * Recursive bottom-up tree traversal example:
 * Delete child nodes
 */
function delete_child_nodes($parent_id, $index, $modelGeneral, $repertory_id, $uid)
{
    if (isset($index[$parent_id])) {
        foreach ($index as $id => $details) {
            if ($details['details']['parent_id'] == $parent_id) {
              $queryDel = "DELETE FROM chess_repertorie_moves WHERE move_id = ? AND repertory_id = ? AND uid = ?";
              $modelGeneral->deleteDetails($queryDel, array($id, $repertory_id, $uid));
              delete_child_nodes($id, $index, $modelGeneral, $repertory_id, $uid);
            }
        }
    }
}
//delete_child_nodes(NULL);

//DELETE FROM category WHERE id = 8   /* Accessories */
//DELETE FROM category WHERE id = 7   /* Camcorders */
//DELETE FROM category WHERE id = 6   /* Digital Cameras */
//DELETE FROM category WHERE id = 2   /* Cameras and Photography */
//DELETE FROM category WHERE id = 15  /* Accessories */
//DELETE FROM category WHERE id = 13  /* Cell Phones */
//DELETE FROM category WHERE id = 14  /* Smartphones */
//DELETE FROM category WHERE id = 4   /* Cell Phones and Accessories */
//DELETE FROM category WHERE id = 10  /* Desktops */
//DELETE FROM category WHERE id = 9   /* Laptops */
//DELETE FROM category WHERE id = 11  /* Netbooks */
//DELETE FROM category WHERE id = 23  /* Android */
//DELETE FROM category WHERE id = 24  /* iPad */
//DELETE FROM category WHERE id = 12  /* Tablets */
//DELETE FROM category WHERE id = 3   /* Computers and Tablets */
//DELETE FROM category WHERE id = 17  /* Home Audio */
//DELETE FROM category WHERE id = 18  /* Speakers and Subwoofers */
//DELETE FROM category WHERE id = 19  /* CRT */
//DELETE FROM category WHERE id = 20  /* LCD */
//DELETE FROM category WHERE id = 21  /* LED */
//DELETE FROM category WHERE id = 22  /* Plasma */
//DELETE FROM category WHERE id = 16  /* Televisions */
//DELETE FROM category WHERE id = 5   /* TV and Audio */
//DELETE FROM category WHERE id = 1   /* Electronics */


//Retrieving Nodes
/*
 * Retrieving nodes using variables passed as reference:
 * Get ids of child nodes
 */
function get_child_nodes1($parent_id, &$children)
{
    global $data, $index;
    $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
        foreach ($index[$parent_id] as $id) {
            $children[] = $id;
            get_child_nodes1($id, $children);
        }
    }
}
//$children = array();
//get_child_nodes1(5, $children); /* TV and Audio */
//echo implode("\n", $children);
//17 /* Home Audio */
//18 /* Speakers and Subwoofers */
//16 /* Televisions */
//19 /* CRT */
//20 /* LCD */
//21 /* LED */
//22 /* Plasma */

//You can make the recursive function return a value like some other PHP functions do (e.g. array_reverse and array_unique):
/*
 * Retrieving nodes using return statement:
 * Get ids of child nodes
 */
function get_child_nodes2($parent_id)
{
    $children = array();
    global $data, $index;
    $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
        foreach ($index[$parent_id] as $id) {
            $children[] = $id;
            $children = array_merge($children, get_child_nodes2($id));
        }
    }
    return $children;
}
//$children = get_child_nodes2(5); /* TV and Audio */
//echo implode("\n", $children);

//Breadcrumbs
/*
 * Display parent nodes
 */
function display_parent_nodes($id, $data)
{
    $current = $data[$id];
    $parent_id = $current["parent_id"] == 0 ? 0 : $current["parent_id"];
    $parents = array();
    $parentDetails = array();
    $parents[] = $current['details']['move_id'];
    $parentDetails[] = $current;
    while (isset($data[$parent_id])) {
        $current = $data[$parent_id];
        $parent_id = $current["parent_id"] == 0 ? 0 : $current["parent_id"];
        $parents[] = $current['details']['move_id'];
        $parentDetails[] = $current;
    }
    return array(array_reverse($parents), array_reverse($parentDetails));
}
//display_parent_nodes(24); /* iPad */


function addinrepertorie($uid, $repertory_id, $parent_id, $move, $move_number, $move_fen, $move_side)
{
  global $modelGeneral;
  if (empty($move)) {
    return false;
  }

  $data = array();
  $result = true;
  $data['repertory_id'] = $repertory_id;
  $data['uid'] = $uid;
  $data['parent_id'] = $parent_id;
  $data['move_number'] = $move_number;
  $data['move'] = $move;
  $data['move_fen'] = $move_fen;
  $data['move_side'] = $move_side;
  $queryCheck = "SELECT * FROM chess_repertorie_moves WHERE uid = ? AND repertory_id = ? AND parent_id = ? AND move = ?";
  $resultCheck = $modelGeneral->fetchRow($queryCheck, array($uid, $repertory_id, $parent_id, $move), 0);
  if (empty($resultCheck)) {
      $result = $modelGeneral->addDetails('chess_repertorie_moves', $data);
      $d = array();
      $d['flag'] = 1;
      $where = sprintf('move_id = %s', $modelGeneral->qstr($parent_id));
      $modelGeneral->updateDetails('chess_repertorie_moves', $d, $where);
  }
  return $result;
}



function addinrepertorieAll($uid, $repertory_id, $parent_id, $move, $move_number, $move_fen, $move_side)
{
  global $modelGeneral;
  if (empty($move)) {
    return false;
  }

  $data = array();
  $result = true;
  $data['repertory_id'] = $repertory_id;
  $data['uid'] = $uid;
  $data['parent_id'] = $parent_id;
  $data['move_number'] = $move_number;
  $data['move'] = $move;
  $data['move_fen'] = $move_fen;
  $data['move_side'] = $move_side;
  $queryCheck = "SELECT * FROM chess_repertorie_allmoves WHERE uid = ? AND repertory_id = ? AND parent_id = ? AND move = ?";
  $resultCheck = $modelGeneral->fetchRow($queryCheck, array($uid, $repertory_id, $parent_id, $move), 0);
  
  if (empty($resultCheck)) {
      $result = $modelGeneral->addDetails('chess_repertorie_allmoves', $data);
      if (!empty($parent_id)) {
        $d = array();
        $d['flag'] = 1;
        $where = sprintf('move_id = %s', $modelGeneral->qstr($parent_id));
        $modelGeneral->updateDetails('chess_repertorie_allmoves', $d, $where);
      }
  }
  return $result;
}
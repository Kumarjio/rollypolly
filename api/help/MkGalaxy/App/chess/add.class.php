<?php
//addinrepertorie($uid, $repertory_id, $parent_id, $move, $move_number, $move_fen, $move_side)
include(SITEDIR.'/includes/chess_functions.php');
//http://mkgalaxy.com/api/help/chess/add?uid=&repertory_id=&parent_id=&move=&move_number=&move_fen=&move_side=
class App_chess_add
{
    public function execute()
    {
        if (empty($_GET['uid'])) {
          throw new Exception('empty uid');
        }
        if (empty($_GET['repertory_id'])) {
          throw new Exception('empty repertory_id');
        }
        if (empty($_GET['parent_id'])) {
          throw new Exception('empty parent_id');
        }
        if (empty($_GET['move'])) {
          throw new Exception('empty move');
        }
        if (empty($_GET['move_number'])) {
          throw new Exception('empty move_number');
        }
        if (empty($_GET['move_fen'])) {
          throw new Exception('empty move_fen');
        }
        if (empty($_GET['move_side'])) {
          throw new Exception('empty move_side');
        }
        $return = addinrepertorie($_GET['uid'], $_GET['repertory_id'], $_GET['parent_id'], $_GET['move'], $_GET['move_number'], $_GET['move_fen'], $_GET['move_side']);
        $this->return = $return;
    }
}
?>
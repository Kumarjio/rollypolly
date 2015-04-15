<?php
class Chess
{
	public function find($fen, $fenfile='/Users/naveen/Downloads/stockfish-231-mac/Mac/fen.txt', $engine='/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64')
	{
		file_put_contents($fenfile, $fen);
		exec("$engine bench 128 1 20 $fenfile depth\n", $output, $returnvar);
		if (empty($output))
			return false;
		$subject = $output[count($output)-1];
		$match = preg_match('/^bestmove (.*) ponder (.*)$/siu', $subject, $matches);
		if (empty($matches[1]))
			return false;
		$this->ponder = !empty($matches[2]) ? $matches[2] : NULL;
		$move = !empty($matches[1]) ? $matches[1] : 'Not Found';
		return $move;
	}

	public function findfen($fen='', $table='games')
	{
		if (empty($fen))
			return array(0, '');

		global $database_conn, $conn;
		$colname_rsView = "-1";
		if (isset($fen)) {
		  $colname_rsView = $fen;
		}

		mysql_select_db($database_conn, $conn);
		$query_rsView = sprintf("SELECT * FROM $table WHERE fen = %s", GetSQLValueString($colname_rsView, "text"));
		$rsView = mysql_query($query_rsView, $conn) or die(mysql_error());
		$row_rsView = mysql_fetch_assoc($rsView);
		$totalRows_rsView = mysql_num_rows($rsView);

		return array($totalRows_rsView, $row_rsView);
	}
	
	public function findpos($table='finalgames')
	{

		global $database_conn, $conn;

		mysql_select_db($database_conn, $conn);
		$query_rsView = "SELECT * FROM $table WHERE process = 0";
		$rsView = mysql_query($query_rsView, $conn) or die(mysql_error());
		$row_rsView = mysql_fetch_assoc($rsView);
		$totalRows_rsView = mysql_num_rows($rsView);

		return array($totalRows_rsView, $row_rsView);
	}
	
	
	public function updatepos($id, $table='finalgames')
	{

		global $database_conn, $conn;

		mysql_select_db($database_conn, $conn);
		$query_rsView = "update $table set process = 1 where id = $id";
		$rsView = mysql_query($query_rsView, $conn) or die(mysql_error());

		return mysql_affected_rows();
	}

	public function process($standard, $fen)
	{
		$data = array();
		$data['fen'] = $fen;
		$toMove = $standard->toMove();
		$data['toMove'] = $toMove;
		$move = $this->find($fen);
		$from = substr($move, 0, 2);
		$to = substr($move, 2, 2);
		$move2 = $standard->_convertSquareToSAN($from, $to);
		$standard->moveSquare($from, $to);
		$renderFen = $standard->renderFen();
		$data['fenpost'] = $renderFen;
		$legalMoves = getlegalmoves($standard, $renderFen);
		$result = '';
		if (!empty($legalMoves['success']) && $legalMoves['success'] == 1) {
			$result = $legalMoves['result'];
		}
		$data['result'] = $result;
		$data['show'] = $move2;
		$data['ponder'] = $this->ponder;
		return $data;	
	}
}
?>
<?php
include('Chess.class.php');
$Chess = new Chess;
echo $Chess->find('r1bq4/1p4kp/3p1n2/5pB1/p1pQ4/8/1P4PP/4RRK1 w - - 0 1');
exit;
echo '<pre>';
file_put_contents('/Users/naveen/Downloads/stockfish-231-mac/Mac/fen.txt', 'r1bq4/1p4kp/3p1n2/5pB1/p1pQ4/8/1P4PP/4RRK1 w - - 0 1');
exec("/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 bench 128 1 20 /Users/naveen/Downloads/stockfish-231-mac/Mac/fen.txt depth\n", $output, $returnvar);

print_r($output);
echo $subject = $output[count($output)-1];
echo '<br>';
$match = preg_match('/^bestmove (.*) ponder (.*)$/siu', $subject, $matches);
$move = !empty($matches[1]) ? $matches[1] : 'Not Found';
echo $move;
exit;
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
);

$cwd = '/Users/naveen/Downloads/stockfish-231-mac/Mac/';
$env = array('some_option' => 'aeiou');

$process = proc_open('stockfish-231-64', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // Any error output will be appended to /tmp/error-output.txt

    fwrite($pipes[0], "uci\nisready\nposition startpos moves d2d4 e7e6\ngo depth 20\n");
    fclose($pipes[0]);

    $x = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
	fwrite('/Users/naveen/Downloads/stockfish-231-mac/Mac/tmp.txt', $x);
	print_r($x);
    // It is important that you close any pipes before calling
    // proc_close in order to avoid a deadlock
    $return_value = proc_close($process);
	
}
exit;
echo '<pre>';

exec("/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 uci
id name Manish\nid author Stefan MK\n", $output, $returnvar);
print_r($output);
exit;



exec("/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 uci\n", $output, $returnvar);
exec("/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 isready\n", $output, $returnvar);
exec("/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 position startpos moves e2e4\n", $output, $returnvar);
exec('/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 go depth 10', $output, $returnvar);
print_r($output);
exit;
exec("/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 position rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq - 1 2\ngo depth 10", $output, $returnvar);
print_r($output);
exec('/Users/naveen/Downloads/stockfish-231-mac/Mac/stockfish-231-64 go depth 10', $output, $returnvar);
print_r($output);
?>
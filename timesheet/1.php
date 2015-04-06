<?php
/*
Example 4-3. Unix include_path

include_path=".:/php/includes"
 
 


Example 4-4. Windows include_path

include_path=".;c:\php\includes"
 
 


*/
//$path = '.:pear';
//set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$include_path=".;c:\wamp\www\utilities\includes\pear";
ini_set("include_path", $include_path); //".:../:./include:../include");
require_once 'Spreadsheet/Excel/Writer.php';
$workbook = new Spreadsheet_Excel_Writer();
$format =& $workbook->addFormat(array('Size' => 20,
                                      'Align' => 'center',
                                      'Color' => 'white',
                                      'Pattern' => 1,
                                      'FgColor' => 'magenta'));
$worksheet =& $workbook->addWorksheet();
$worksheet->writeString(1, 0, "Magenta Hello", $format);
$workbook->send('test.xls');
$workbook->close();
?>
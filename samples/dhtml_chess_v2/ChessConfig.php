<?php
//--------------------------------------------------------------------------------------------------
//	CHESS CONFIG FILE FOR PHP
// --------------------------------------------------------------------------------------------------


// File cache
// This means that the script saves json details to files so that it doesn't have to convert pgn to json each time
// someone requests information
#define("FILE_CACHE_FOLDER","game_cache/");	// Leave empty if you don't want to enable file cache
define("FILE_CACHE_FOLDER","");	// Leave empty if you don't want to enable file cache
define("PGN_FOLDER", "pgn/");

define("CHESS_USERNAME", "chess");
define("CHESS_PASSWORD", "chess");
define("RECEIVE_LIVE_UPDATE_VALID_REFERER", "http://mkgxy.consultlawyers.us/samples/dhtml_chess_v2/receive-live-update.php");

define("CHESS_USE_DATABASE", 1);
define("CHESS_DB_HOST", 'localhost');
define("CHESS_DB_USERNAME", 'consultl_user');
define("CHESS_DB_PASSWORD", 'passwords123');
define("CHESS_DB_NAME", 'consultl_dhtmlchess');





?>
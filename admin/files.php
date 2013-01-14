<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
$pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)"; //valid image extensions
$files = array();
$curimage=0; 
if ($handle = opendir(Config::$site_path.'images/contractors')) {
	while (false !== ($file = readdir($handle))) { 
		if (eregi($pattern, $file)) {
			$response[$curimage]=$file;
			$curimage++;
		}
	} 
}
echo json_encode($response);
closedir($handle);
?>
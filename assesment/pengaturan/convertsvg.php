<?
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

// echo "ASd";exit;
// $type= httpFilterRequest("type");

makedirs('../upload/svg/');
$filename= httpFilterPost("filename");
$svg= httpFilterPost("options");
// print_r($svg);
// $svg= json_decode($svg, true);
$svg= urldecode($svg);
// print_r($svg);exit;
$h = fopen('../upload/svg/'.$filename.'.svg', 'w');
fwrite($h, $svg);
fclose($h);
?>
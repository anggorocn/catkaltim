<?php
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PhpShoutbox.php");

		
$reqId = httpFilterRequest("reqId");
$reqHalaman = httpFilterRequest("reqHalaman");
$reqKode = httpFilterRequest("reqKode");


  function replace(&$item, $key) {
	$item = str_replace('|', '-', $item);
  }
  
  if (!function_exists('file_put_contents')) {
		function file_put_contents($fileName, $data) {
			if (is_array($data)) {
				$data = join('', $data);
			}
			$res = @fopen($fileName, 'w+b');
			if ($res) {
				$write = @fwrite($res, $data);
				if($write === false) {
					return false;
				} else {
					return $write;
				}
			}
		}
	}
  
  //file_put_contents('debug.txt', print_r($_GET, true));
  switch($_GET['action']) {
	case 'add':
		array_walk($_POST, 'replace');
		$_POST['nickname'] = htmlentities($_POST['nickname']);
		$_POST['message'] = htmlentities($_POST['message']);
		$time = time();
		$arr[] = $time.'|'.$_POST['nickname'].'|'.$_POST['message'].'|'.$_SERVER['REMOTE_ADDR']."\n";
		
		$php_shoutbox = new PhpShoutbox();
		$php_shoutbox->setField("JAM", $time);
		$php_shoutbox->setField("NAMA", $_POST['nickname']);
		$php_shoutbox->setField("PESAN", formatTextToDb(strtoupper($_POST['message'])));
		$php_shoutbox->setField("IP_ADDRESS", $_SERVER['REMOTE_ADDR']);
		$php_shoutbox->setField("PELAMAR_ID", $reqId);
		$php_shoutbox->setField("HALAMAN", $reqHalaman);
		$php_shoutbox->setField("KODE", $reqKode);
		$php_shoutbox->insert();
  
		$data['response'] = 'Good work';
		$data['nickname'] = $_POST['nickname'];
		$data['message'] = $_POST['message'];
		$data['waktu'] = date("d/m/Y H:i:s");
		$data['time'] = $time;
	break;
	
	case 'view':
	  $data = array();
	  if(!$_GET['time'])
		$_GET['time'] = 0;
	  $php_shoutbox = new PhpShoutbox();
	  $php_shoutbox->selectByParams(array("PELAMAR_ID" => $reqId));
	  while($php_shoutbox->nextRow())
	  {
		$row = $php_shoutbox->getField("JAM")."|".$php_shoutbox->getField("WAKTU")."|".$php_shoutbox->getField("NAMA")."|".$php_shoutbox->getField("PESAN")."|".$php_shoutbox->getField("HALAMAN");  
		list($aTemp['time'], $aTemp['waktu'], $aTemp['nickname'], $aTemp['message'], $aTemp['halaman']) = explode('|', $row); 
		if($aTemp['message'] AND $aTemp['time'] > $_GET['time'])
		  $data[] = $aTemp;
	  }
	break;
  }
  
  require_once('../WEB/lib/JSON.php');
  $json = new Services_JSON();
  $out = $json->encode($data);
  print $out;
?>
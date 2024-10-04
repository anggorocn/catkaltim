<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pejabat.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$pejabat	= new Pejabat();

$reqMode 		= httpFilterRequest("reqMode");
$reqId			= httpFilterPost("reqId");

$reqNama 		= httpFilterPost("reqNama");
$reqAlamat 		= httpFilterPost("reqAlamat");
$reqEmail 		= httpFilterPost("reqEmail");
$reqTelepon 	= httpFilterPost("reqTelepon");
$reqUmur= httpFilterPost("reqUmur");
$reqNoSk= httpFilterPost("reqNoSk");

$pejabat->setField('NAMA_PEJABAT', $reqNama);
$pejabat->setField('ALAMAT_PEJABAT', $reqAlamat);
$pejabat->setField('EMAIL_PEJABAT', $reqEmail);
$pejabat->setField('TELEPON', $reqTelepon);
$pejabat->setField('UMUR', $reqUmur);
$pejabat->setField('NO_SK', $reqNoSk);

if($reqMode == "insert"){
	
	
	if($pejabat->insert())
	{
		$mode = 'Data berhasil disimpan';
		//echo $pejabat->query;exit;	
	}
	else
		$mode = 'Data gagal disimpan';
		//echo $pejabat->query;exit;		
	echo $mode;
}
elseif($reqMode == "update")
{
	
	$pejabat->setField('PEJABAT_ID',$reqId);
	
	if($pejabat->update())
	{
		$mode = 'Data berhasil disimpan';
	}
	else
		$mode = 'Data gagal disimpan';	
	
	echo $mode;
}
?>
<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Blacklist.php");
include_once("../WEB/classes/utils/UserLogin.php");

$blacklist = new Blacklist();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNoKtp 	= httpFilterPost("reqNoKtp");
$reqTanggal 	= httpFilterPost("reqTanggal");
$reqNama	= httpFilterPost("reqNama");
$reqTempatLahir	= httpFilterPost("reqTempatLahir");
$reqTanggalLahir	= httpFilterPost("reqTanggalLahir");

if($reqMode == "insert")
{
	$blacklist->setField("KTP_NO", $reqNoKtp);
	$blacklist->setField("TANGGAL", dateToDBCheck($reqTanggal));
	$blacklist->setField("NAMA", $reqNama);
	$blacklist->setField("TEMPAT_LAHIR", $reqTempatLahir);
	$blacklist->setField("TANGGAL_LAHIR", dateToDBCheck($reqTanggalLahir));
	$blacklist->setField("LAST_UPDATE_USER", $userLogin->nama);
    $blacklist->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	
	if($blacklist->insert())
		echo "Data berhasil disimpan.";
}
else
{
	$blacklist->setField("BLACKLIST_ID", $reqId);
	$blacklist->setField("KTP_NO", $reqNoKtp);
	$blacklist->setField("TANGGAL", dateToDBCheck($reqTanggal));
	$blacklist->setField("NAMA", $reqNama);
	$blacklist->setField("TEMPAT_LAHIR", $reqTempatLahir);
	$blacklist->setField("TANGGAL_LAHIR", dateToDBCheck($reqTanggalLahir));
	$blacklist->setField("LAST_UPDATE_USER", $userLogin->nama);
	$blacklist->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	if($blacklist->update())
		echo "Data berhasil disimpan.";
	
}
?>
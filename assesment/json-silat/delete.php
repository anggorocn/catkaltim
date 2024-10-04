<?
//include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

$reqId = $_GET['id'];
$reqRowDetilId= $_GET['reqRowDetilId'];
$reqMode = $_GET['reqMode'];

/* LOGIN CHECK */
/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/
if($reqMode == "beasiswa")
{
	include_once("../WEB/classes/base-silat/Beasiswa.php");
	$set = new Beasiswa();
	$set->setField("BEASISWA_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
elseif($reqMode == "beasiswa_sertifikat")
{
	include_once("../WEB/classes/base-silat/BeasiswaSertifikat.php");
	$set = new BeasiswaSertifikat();
	$set->setField("BEASISWA_SERTIFIKAT_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
elseif($reqMode == "training")
{
	include_once("../WEB/classes/base-silat/Training.php");
	$set = new Training();
	$set->setField("TRAINING_ID", $reqId);
	$set->setField("KOMPETENSI_TRAINING_ID", $reqRowDetilId);
	
	if($reqRowDetilId == "")
	{
		if($set->deleteAll())
			$alertMsg .= "Data berhasil dihapus";
		else
			$alertMsg .= "Error ".$set->getErrorMsg();
	}
	else
	{
		if($set->deleteKompetensi())
			$alertMsg .= "Data berhasil dihapus";
		else
			$alertMsg .= "Error ".$set->getErrorMsg();
	}
}
elseif($reqMode == "master_kategori")
{
	include_once("../WEB/classes/base-silat/KategoriPelatihan.php");
	$set = new KategoriPelatihan();
	$set->setField("KATEGORI_PELATIHAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
elseif($reqMode == "master_tipe")
{
	include_once("../WEB/classes/base-silat/TipePelatihan.php");
	$set = new TipePelatihan();
	$set->setField("TIPE_PELATIHAN_ID", $reqId);
	if($set->delete())
		$alertMsg .= "Data berhasil dihapus";
	else
		$alertMsg .= "Error ".$set->getErrorMsg();
}
?>
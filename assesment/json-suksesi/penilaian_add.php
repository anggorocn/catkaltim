<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterRequest('reqId');

$reqTanggalTes= httpFilterPost("reqTanggalTes");
$reqSatkerInfo= httpFilterPost("reqSatkerInfo");
$reqJabatanTesId= httpFilterPost("reqJabatanTesId");
$reqLokasi= httpFilterPost("reqLokasi");

$reqPenilaianId= $_POST["reqPenilaianId"];
$reqAspekId= $_POST["reqAspekId"];
$reqJpm= $_POST["reqJpm"];
$reqIkk= $_POST["reqIkk"];

$inforeturn= "";
for($i=0; $i < count($reqPenilaianId); $i++)
{
	$reqRowId= $reqPenilaianId[$i];
	$infoAspekId= $reqAspekId[$i];
	$infoJpm= $reqJpm[$i] / 100;
	$infoIkk= $reqIkk[$i] / 100;

	$set= new Penilaian();
	$set->setField("PEGAWAI_ID", $reqId);
	$set->setField("TANGGAL_TES", dateToDBCheck($reqTanggalTes));
	$set->setField("JABATAN_TES_ID", $reqJabatanTesId);
	$set->setField("ASPEK_ID", $infoAspekId);
	$set->setField("SATUAN_KERJA_INFO", $reqSatkerInfo);
	$set->setField("LOKASI", $reqLokasi);
	$set->setField("JPM", ValToNullDB($infoJpm));
	$set->setField("IKK", ValToNullDB($infoIkk));

	if(empty($reqRowId))
	{
		$set->setField("LAST_CREATE_USER", $userLogin->idUser);
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
		$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
		if($set->insertluar())
		{
			$mode = 'simpan';
		}
		else
			$mode = 'error';
		// echo $set->query;exit;

		$inforeturn= "-Data Tersimpan-".$mode;
	}
	elseif(!empty($reqRowId))
	{
		$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
		$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
		$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
		$set->setField("PENILAIAN_ID", $reqRowId);
		if($set->updateluar())
		{
			$mode = 'simpan';
		}
		else
			$mode = 'error';
		
		//echo $set->query;
		$inforeturn= $reqRowId."-Data Tersimpan-".$mode;
	}
}

echo $inforeturn;
?>
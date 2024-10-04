<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTesFormulaAssesmentUjianTahap.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterPost("reqId");

$reqStatus= $_POST["reqStatus"];
$reqDataJadwalTesId= $_POST["reqDataJadwalTesId"];
$reqDataFormulaUjianTahapId= $_POST["reqDataFormulaUjianTahapId"];
$reqDataFormulaId= $_POST["reqDataFormulaId"];
$reqDataTipeUjianId= $_POST["reqDataTipeUjianId"];

$reqMode= "insert";
if($reqMode == "insert")
{
	for($i=0; $i < count($reqStatus); $i++)
	{
		if($reqStatus[$i] == "1")
		{
			$set_detil= new JadwalTesFormulaAssesmentUjianTahap();
			$set_detil->setField('JADWAL_TES_ID', $reqDataJadwalTesId[$i]);
			$set_detil->setField('FORMULA_ASSESMENT_UJIAN_TAHAP_ID', $reqDataFormulaUjianTahapId[$i]);
			$set_detil->setField('FORMULA_ASSESMENT_ID', $reqDataFormulaId[$i]);
			$set_detil->setField('TIPE_UJIAN_ID', $reqDataTipeUjianId[$i]);
			$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			$set_detil->insert();
			// echo $set_detil->query;exit;
		}
	}
	echo "-Data berhasil disimpan";
}
?>
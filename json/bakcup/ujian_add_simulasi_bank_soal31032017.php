<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/UjianBankSoal.php");
include_once("../WEB/classes/utils/UserLogin.php");

$ujian_tahap = new UjianTahap();

$reqId 				= httpFilterPost("reqId");

$reqRowId			= $_POST["reqRowId"];
$reqTipeUjian		= $_POST["reqTipeUjian"];
$reqBobot= $_POST["reqBobot"];
$reqJumlahSoalUjianTahap= $_POST["reqJumlahSoalUjianTahap"];

for($i=0; $i<count($reqTipeUjian); $i++)
{
	if($reqTipeUjian[$i] == ""){}
	else
	{
		$ujian_tahap = new UjianTahap();
		$ujian_tahap->setField("UJIAN_ID", $reqId);
		$ujian_tahap->setField("TIPE_UJIAN_ID", ValToNullDB($reqTipeUjian[$i]));
		$ujian_tahap->setField("BOBOT", ValToNullDB($reqBobot[$i]));
		$ujian_tahap->setField("JUMLAH_SOAL_UJIAN_TAHAP", ValToNullDB($reqJumlahSoalUjianTahap[$i]));
		
		if($reqRowId[$i] == "")
		{
			$ujian_tahap->setField("LAST_CREATE_USER", $userLogin->nama);
			$ujian_tahap->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$ujian_tahap->insertJumlahSoal();
		}
		else
		{
			$ujian_tahap->setField("LAST_UPDATE_USER", $userLogin->nama);
			$ujian_tahap->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$ujian_tahap->setField("UJIAN_TAHAP_ID", $reqRowId[$i]);
			$ujian_tahap->updateJumlahSoal();
		}
		//echo $ujian_tahap->query;exit;
		unset($ujian_tahap);
	}
	
	$set_detil= new UjianBankSoal();
	$set_detil->setField("UJIAN_ID", $reqId);
	$set_detil->setField("LAST_CREATE_USER", $userLogin->nama);
	$set_detil->getCountByParamsSimulasiBankSoal();
	unset($set_detil);
}
echo $reqId."-Data Berhasil Disimpan.";
?>
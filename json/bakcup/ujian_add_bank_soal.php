<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianBankSoal.php");
include_once("../WEB/classes/utils/UserLogin.php");

$ujian_bank_soal = new UjianBankSoal();

$reqId 				= httpFilterPost("reqId");

$reqRowId			= $_POST["reqRowId"];
$reqBankSoalId		= $_POST["reqBankSoalId"];
$reqGrade			= $_POST["reqGrade"];
$reqStatusSoal		= $_POST["reqStatusSoal"];
$reqUjianTahapId		= $_POST["reqUjianTahapId"];

for($i=0; $i<count($reqBankSoalId); $i++)
{
	if($reqBankSoalId[$i] == ""){}
	else
	{
		$ujian_bank_soal = new UjianBankSoal();
		$ujian_bank_soal->setField("UJIAN_ID", $reqId);
		$ujian_bank_soal->setField("BANK_SOAL_ID", ValToNullDB($reqBankSoalId[$i]));
		$ujian_bank_soal->setField("GRADE", ValToNullDB($reqGrade[$i]));
		$ujian_bank_soal->setField("STATUS_SOAL", ValToNullDB($reqStatusSoal[$i]));
		$ujian_bank_soal->setField("UJIAN_TAHAP_ID", ValToNullDB($reqUjianTahapId));
		
		if($reqRowId[$i] == "")
		{
			$ujian_bank_soal->setField("LAST_CREATE_USER", $userLogin->nama);
			$ujian_bank_soal->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$ujian_bank_soal->insert();
		}
		else
		{
			$ujian_bank_soal->setField("LAST_UPDATE_USER", $userLogin->nama);
			$ujian_bank_soal->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$ujian_bank_soal->setField("UJIAN_BANK_SOAL_ID", $reqRowId[$i]);
			$ujian_bank_soal->update();
		}
		//echo $ujian_bank_soal->query;exit;
		unset($ujian_bank_soal);
	}
}
echo $reqId."-Data Berhasil Disimpan.";
?>
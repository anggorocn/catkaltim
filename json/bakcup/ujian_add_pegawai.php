<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/utils/UserLogin.php");

$ujian_bank_soal = new UjianPegawaiDaftar();

$reqId 				= httpFilterPost("reqId");

$reqRowId			= $_POST["reqRowId"];
$reqPegawaiId		= $_POST["reqPegawaiId"];

for($i=0; $i<count($reqPegawaiId); $i++)
{
	if($reqPegawaiId[$i] == ""){}
	else
	{
		$ujian_bank_soal = new UjianPegawaiDaftar();
		$ujian_bank_soal->setField("UJIAN_ID", $reqId);
		$ujian_bank_soal->setField("STATUS_LOGIN", "0");
		$ujian_bank_soal->setField("PEGAWAI_ID", ValToNullDB($reqPegawaiId[$i]));
		
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
			$ujian_bank_soal->setField("UJIAN_PEGAWAI_DAFTAR_ID", $reqRowId[$i]);
			$ujian_bank_soal->update();
		}
		//echo $ujian_bank_soal->query;exit;
		unset($ujian_bank_soal);
	}
}
echo $reqId."-Data Berhasil Disimpan.";
?>
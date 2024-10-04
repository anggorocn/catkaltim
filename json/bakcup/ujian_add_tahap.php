<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/utils/UserLogin.php");

$ujian_tahap = new UjianTahap();

$reqId 				= httpFilterPost("reqId");

$reqRowId			= $_POST["reqRowId"];
$reqTipeUjian		= $_POST["reqTipeUjian"];
$reqBobot			= $_POST["reqBobot"];

for($i=0; $i<count($reqTipeUjian); $i++)
{
	if($reqTipeUjian[$i] == ""){}
	else
	{
		$ujian_tahap = new UjianTahap();
		$ujian_tahap->setField("UJIAN_ID", $reqId);
		$ujian_tahap->setField("TIPE_UJIAN_ID", ValToNullDB($reqTipeUjian[$i]));
		$ujian_tahap->setField("BOBOT", ValToNullDB($reqBobot[$i]));
		
		if($reqRowId[$i] == "")
		{
			$ujian_tahap->setField("LAST_CREATE_USER", $userLogin->nama);
			$ujian_tahap->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$ujian_tahap->insert();
		}
		else
		{
			$ujian_tahap->setField("LAST_UPDATE_USER", $userLogin->nama);
			$ujian_tahap->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$ujian_tahap->setField("UJIAN_TAHAP_ID", $reqRowId[$i]);
			$ujian_tahap->update();
		}
		//echo $ujian_tahap->query;exit;
		unset($ujian_tahap);
	}
}
echo $reqId."-Data Berhasil Disimpan.";
?>
<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowonganDokumen.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");



$reqPelamarLowonganId = httpFilterPost("reqPelamarLowonganId");

$reqLowonganDokumenId = $_POST["reqLowonganDokumenId"];
$reqRowId = $_POST["reqRowId"];
$reqStatus = $_POST["reqStatus"];

		
for($i=0;$i<count($reqLowonganDokumenId);$i++)
{
	$set_data = new PelamarLowonganDokumen();
	if($reqLowonganDokumenId[$i] == "")
	{}
	else
	{
		$set_data->setField("PELAMAR_LOWONGAN_ID", $reqPelamarLowonganId);
		$set_data->setField("STATUS", $reqStatus[$i]);
		$set_data->setField("LOWONGAN_DOKUMEN_ID", $reqLowonganDokumenId[$i]);
		
		if($reqRowId[$i] == "")				
		{
			$set_data->insertStatus();
		}
		else
		{
			$set_data->setField("PELAMAR_LOWONGAN_DOKUMEN_ID", $reqRowId[$i]);
			$set_data->updateStatus();			
		}
		//echo $set_data->query;
		
	}		
	unset($set_data);
}

echo "Data Berhasil Disimpan.";
?>
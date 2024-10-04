<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/IndikatorPenilaian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqAtributId= httpFilterPost("reqAtributId");
$reqRowId= httpFilterPost("reqRowId");

$reqIndikatorId= $_POST["reqIndikatorId"];
$reqNamaIndikator= $_POST["reqNamaIndikator"];

if($reqMode == "insert")
{
	for($i=0; $i < count($reqIndikatorId); $i++)
	{
		if($reqNamaIndikator[$i] == ""){}
		else
		{
			$set_detil= new IndikatorPenilaian();
			$set_detil->setField('LEVEL_ID', $reqRowId);
			$set_detil->setField('NAMA_INDIKATOR', $reqNamaIndikator[$i]);
			$set_detil->setField('INDIKATOR_ID',$reqIndikatorId[$i]);
			
			if($reqIndikatorId[$i] == "")
			{
				$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
				$set_detil->insert();
			}
			else 
			{
				$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
				$set_detil->update();
			}
		}
	}
	echo "Data berhasil disimpan";
}
?>
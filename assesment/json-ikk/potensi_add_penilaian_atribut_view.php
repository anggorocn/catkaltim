<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId= httpFilterRequest('reqId');
$reqRowId= httpFilterPost("reqRowId");
$reqPenilaianDetilId= $_POST["reqPenilaianDetilId"];
$reqBukti= $_POST["reqBukti"];
$reqCatatan= $_POST["reqCatatan"];

$reqMode= "insert";
if($reqMode == "insert")
{
	for($i=0;$i<count($reqPenilaianDetilId);$i++)
	{
		if($reqPenilaianDetilId[$i] == ""){}
		else
		{
			$tempRowId= $reqPenilaianDetilId[$i];
			$set= new PenilaianDetil();
			$set->setField("BUKTI", $reqBukti[$i]);
			$set->setField("CATATAN", $reqCatatan[$i]);
			$set->setField("PENILAIAN_DETIL_ID", $tempRowId);
			
			if($tempRowId == "")
			{
			}
			else
			{
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
				$set->updateCatatan();
			}
			//echo $set->query;exit;
		}
	}
	
	echo "Data Tersimpan";
}
?>
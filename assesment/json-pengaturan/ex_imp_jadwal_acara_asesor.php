<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/functions/jexcelarray.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqData= $_POST["reqData"];
$reqJadwalTesId= $_POST["reqJadwalTesId"];
// $reqData= json_decode($reqData, true);
// print_r($reqData);exit();

foreach($reqData as $key=>$value)
{
	$reqJadwalAcaraId= $key;
	// echo $reqJadwalAcaraId;exit;

	$reqExcelData= "";
	$reqExcelData= array();
	$reqExcelData= json_decode($reqData[$key], true);
	// print_r($reqExcelData);exit;
	$jumlahdata= count($reqExcelData);

	$arrkolomdata= "";
	$arrkolomdata= getarray("jadwalacaraasesoradd");
	// print_r($arrkolomdata);exit();

	$nomor= 1;
	for($i=0; $i < $jumlahdata; $i++)
	{
		if(empty($reqExcelData[$i][array_search('reqAsesorId', array_column($arrkolomdata, 'val'))]))
		{
			continue;
		}
		else
		{
			$valcatalogid= $reqExcelData[$i][array_search('reqCatalogId', array_column($arrkolomdata, 'val'))];
			if($reqExcelData[$i][array_search('reqCheckDelete', array_column($arrkolomdata, 'val'))] == "1")
			{
				$set = new JadwalAsesor();
				$set->setField("JADWAL_ASESOR_ID", $valcatalogid);
				$set->delete();
				unset($set);
			}
			else
			{
				$set = new JadwalAsesor();
				$set->setField('JADWAL_TES_ID', $reqJadwalTesId);
				$set->setField('JADWAL_ACARA_ID', $reqJadwalAcaraId);
				$set->setField('JADWAL_KELOMPOK_RUANGAN_ID', ValToNullDB($req));

				for($iColumn=0;$iColumn<count($arrkolomdata);$iColumn++)
				{
					$dataArrVal= $arrkolomdata[$iColumn]["val"];
					$dataArrField= $arrkolomdata[$iColumn]["field"];

					if(empty($dataArrField))
						continue;

					if($dataArrVal == "reqBatasPegawai")
						$set->setField($dataArrField, ValToNullDB($reqExcelData[$i][array_search($dataArrVal, array_column($arrkolomdata, 'val'))]));
					else
						$set->setField($dataArrField, $reqExcelData[$i][array_search($dataArrVal, array_column($arrkolomdata, 'val'))]);
				}

				if($valcatalogid == "")
				{
					$set->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
					$set->insert();
				}
				else
				{
					$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
					$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
					$set->update();
				}
				// echo $set->query;exit;

			}

		}

	}
}
?>
<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
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

foreach($reqData as $key=>$value)
{
	$reqJadwalAcaraId= $key;
  // print_r($key);exit();
	$reqExcelData= "";
	$reqExcelData= array();
	$reqExcelData= json_decode($reqData[$key], true);
	// print_r($reqExcelData);exit;
	$jumlahdata= count($reqExcelData);
	$arrkolomdata= "";
	$arrkolomdata= getarray("jadwalacarapegawaiadd");
	// print_r($arrkolomdata);exit();

	$nomor= 1;
	for($i=0; $i < $jumlahdata; $i++)
	{
		if(empty($reqExcelData[$i][array_search('reqPegawaiId', array_column($arrkolomdata, 'val'))]))
		{
			continue;
		}
		else
		{
			$valcatalogid= $reqExcelData[$i][array_search('reqCatalogId', array_column($arrkolomdata, 'val'))];
			// echo $valasesorid;exit;
			$jadwal_asesor= new JadwalAsesor();
			$jadwal_asesor->selectByParamsMonitoring(array("A.JADWAL_ASESOR_ID"=> $reqJadwalAcaraId),-1,-1,'');
			$jadwal_asesor->firstRow();
			// echo $jadwal_asesor->query;exit;
			$tempAsesorId= $jadwal_asesor->getField('JADWAL_ASESOR_ID');
			$tempPenggalianId= $jadwal_asesor->getField('PENGGALIAN_ID');
			$valpenggalianid= $tempPenggalianId;
			$valasesorid= $tempAsesorId;


			if($reqExcelData[$i][array_search('reqCheckDelete', array_column($arrkolomdata, 'val'))] == "1")
			{
				$set = new JadwalPegawai();
				$set->setField("JADWAL_PEGAWAI_ID", $valcatalogid);
				$set->delete();
				unset($set);
			}
			else
			{

				$set = new JadwalPegawai();
				// $set->setField('PEGAWAI_ID', $valpegawaiid);
				$set->setField('PENGGALIAN_ID', $valpenggalianid);

				for($iColumn=0;$iColumn<count($arrkolomdata);$iColumn++)
				{
					$dataArrVal= $arrkolomdata[$iColumn]["val"];
					$dataArrField= $arrkolomdata[$iColumn]["field"];

					if(empty($dataArrField))
						continue;

					if($dataArrVal == "reqBatasPegawai")
						$set->setField($dataArrField, ValToNullDB($reqExcelData[$i][array_search($dataArrVal, array_column($arrkolomdata, 'val'))]));
					else if($dataArrVal == "reqAsesorId")
						$set->setField('JADWAL_ASESOR_ID', $reqJadwalAcaraId);
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
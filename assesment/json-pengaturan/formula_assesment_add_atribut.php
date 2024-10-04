<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaAtribut.php");
include_once("../WEB/classes/base/FormulaAtributBobot.php");
include_once("../WEB/classes/base/LevelAtribut.php");
include_once("../WEB/classes/base/AtributPenggalian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqMode= httpFilterRequest("reqMode");
$reqId= httpFilterPost("reqId");
$reqRowId= httpFilterPost("reqRowId");
$reqAspekId= httpFilterPost("reqAspekId");
$reqFormulaEselonPermenId= httpFilterPost("reqFormulaEselonPermenId");

$reqStatusAtributId= $_POST["reqStatusAtributId"];
$reqLevelId= $_POST["reqLevelId"];
$reqNilaiStandar= $_POST["reqNilaiStandar"];
$reqFormulaAtributId= $_POST["reqFormulaAtributId"];
$reqAtributPenggalianId= $_POST["reqAtributPenggalianId"];
$reqAtributId= $_POST["reqAtributId"];

$reqBobotStatusAtributId= $_POST["reqBobotStatusAtributId"];
$reqFormulaAtributBobotId= $_POST["reqFormulaAtributBobotId"];
$reqBobotAtributId= $_POST["reqBobotAtributId"];
$reqAtributNilaiStandar= $_POST["reqAtributNilaiStandar"];
$reqAtributBobot= $_POST["reqAtributBobot"];
$reqAtributSkor= $_POST["reqAtributSkor"];

//print_r($reqBobotStatusAtributId);
//print_r($reqAtributNilaiStandar);
//print_r($reqBobotAtributId);
//exit;
if($reqMode == "insert")
{
	for($i=0; $i < count($reqBobotStatusAtributId); $i++)
	{
		if($reqBobotStatusAtributId[$i] == "1")
		{
			$set_detil= new FormulaAtributBobot();
			$set_detil->setField('FORMULA_ESELON_ID',$reqRowId);
			$set_detil->setField('ASPEK_ID', $reqAspekId);
			$set_detil->setField('ATRIBUT_ID', $reqBobotAtributId[$i]);
			$set_detil->setField('ATRIBUT_NILAI_STANDAR', ValToNullDB($reqAtributNilaiStandar[$i]));
			$set_detil->setField('ATRIBUT_BOBOT', ValToNullDB($reqAtributBobot[$i]));
			$set_detil->setField('ATRIBUT_SKOR', ValToNullDB($reqAtributSkor[$i]));
			$set_detil->setField('FORMULA_ATRIBUT_BOBOT_ID', $reqFormulaAtributBobotId[$i]);

			if($reqFormulaEselonPermenId == "")
			{
				$set_detil->setField('PERMEN_ID', "(SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')");
			}
			else
			{
				$set_detil->setField('PERMEN_ID', $reqFormulaEselonPermenId);
			}

			if($reqFormulaAtributBobotId[$i] == "")
			{
				$set_detil->insert();
				// echo $set_detil->query;exit;
			}
			else
			{
				$set_detil->update();
				//echo $set_detil->query;exit;
			}
			
		}
	}
	
	for($i=0; $i < count($reqStatusAtributId); $i++)
	{
		//kalau d centang maka boleh simpan
		if($reqStatusAtributId[$i] == "1")
		{
			$tempFormulaAtributId= $reqFormulaAtributId[$i];
			$set_detil= new FormulaAtribut();
			$set_detil->setField('NILAI_STANDAR', ValToNullDB($reqNilaiStandar[$i]));
			$set_detil->setField('LEVEL_ID', $reqLevelId[$i]);
			$set_detil->setField('FORMULA_ESELON_ID',$reqRowId);
			
			$set_detil->setField('ASPEK_ID', $reqAspekId);
			$set_detil->setField('ATRIBUT_ID', $reqAtributId[$i]);
			$set_detil->setField('ATRIBUT_NILAI_STANDAR', ValToNullDB($reqAtributNilaiStandar[$i]));
			$set_detil->setField('ATRIBUT_BOBOT', ValToNullDB($reqAtributBobot[$i]));
			$set_detil->setField('ATRIBUT_SKOR', ValToNullDB($reqAtributSkor[$i]));
			$set_detil->setField('FORMULA_ATRIBUT_ID', $reqFormulaAtributId[$i]);
			
			if($reqFormulaAtributId[$i] == "")
			{
				$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
				
				// cek data pasti satu
				$statement= " AND A.FORMULA_ESELON_ID = ".$reqRowId." AND A.LEVEL_ID = ".$reqLevelId[$i];
				$set_cek= new FormulaAtribut();
				$tempJumlahFormulaAtribut= $set_cek->getCountByParams(array(), $statement);
				//echo $set_cek->query;exit;
				unset($set_cek);
				
				$tempLevelId= $reqLevelId[$i];
				
				if($tempJumlahFormulaAtribut > 0){}
				else
				{
					//kl aspek potensi dan baru maka simpan level 0 terlebih dahulu
					if($reqAspekId == "1")
					{
						$statement_level= " AND A.ATRIBUT_ID = '".$reqAtributId[$i]."' AND A.LEVEL = 0";
						// kondisi aktif permen
						$statement_level.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM PERMEN WHERE STATUS = '1') X WHERE AKTIF_PERMENT = PERMEN_ID)";
						$set_level= new LevelAtribut();
						$set_level->selectByParams(array(), -1,-1, $statement_level);
						// echo $set_level->query;exit();
						$set_level->firstRow();
						$tempLevelId= $set_level->getField("LEVEL_ID");

						if($tempLevelId == "")
						{
							$set_level= new LevelAtribut();
							$set_level->setField("ATRIBUT_ID", $reqAtributId[$i]);
							$set_level->setField("LEVEL", "0");
							$set_level->setField("KETERANGAN", "INTEGRASI");
							$set_level->insert();
							// echo $set_level->query;exit();
							$tempLevelId= $set_level->id;
							unset($set_level);
							// echo $tempLevelId;exit();
						}
						
						$set_detil->setField('LEVEL_ID', $tempLevelId);
					}
					
					//harus ada data level id
					if($tempLevelId == ""){}
					else
					{
					$set_detil->insert();
					// echo $set_detil->query;exit();
					$tempFormulaAtributId= $set_detil->id;
					}
				}
			}
			else 
			{
				$set_detil->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
				$set_detil->update();
			}
			
			if($reqAtributId[$i] == "04")
			{
				//echo $set_detil->query;exit;
			}
			
			//simpan formulapenggalian main delete insert
			if($tempFormulaAtributId == ""){}
			else
			{
				$set_detil= new AtributPenggalian();
				$set_detil->setField("FORMULA_ATRIBUT_ID", $tempFormulaAtributId);
				$set_detil->deleteFormulaAtribut();
				unset($set_detil);
				
				$arrAtributPenggalian= explode(",",$reqAtributPenggalianId[$i]);
				
				if(empty($arrAtributPenggalian)){}
				else
				{
					for($x=0; $x < count($arrAtributPenggalian); $x++)
					{
						$tempPenggalianId= $arrAtributPenggalian[$x];
						$statement= " AND A.FORMULA_ATRIBUT_ID = ".$tempFormulaAtributId." AND A.PENGGALIAN_ID = ".$tempPenggalianId;
						$set_cek= new AtributPenggalian();
						$tempJumlahAtributPenggalian= $set_cek->getCountByParams(array(), $statement);
						unset($set_cek);
						
						// cek data pasti satu
						if($tempJumlahAtributPenggalian > 0){}
						else
						{
							$set_detil= new AtributPenggalian();
							$set_detil->setField("FORMULA_ATRIBUT_ID", $tempFormulaAtributId);
							$set_detil->setField("PENGGALIAN_ID", $tempPenggalianId);
							$set_detil->insert();
							unset($set_detil);
						}
					}
				}
			}
		}
	}
	echo "Data berhasil disimpan";
}
?>
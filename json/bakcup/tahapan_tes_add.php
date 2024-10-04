<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/TahapanTes.php");
include_once("../WEB/classes/base/TahapanTesNilai.php");
include_once("../WEB/classes/utils/UserLogin.php");

$tahapan_tes = new TahapanTes();
$tahapan_tes_nilai = new TahapanTesNilai();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNama= httpFilterPost("reqNama");
$reqUrut= httpFilterPost("reqUrut");
$reqTahapanTesNilai = $_POST["reqTahapanTesNilai"];
$reqTahapanTesNilaiDelete = httpFilterPost("reqTahapanTesNilaiDelete");
$reqTahapanTesNilaiId = $_POST["reqTahapanTesNilaiId"];

$tahapan_tes->setField('TAHAPAN_TES_ID', $reqId);
$tahapan_tes->setField('NAMA', $reqNama);

if($reqMode == "insert")
{
	$tahapan_tes->setField("LAST_CREATE_USER", $userLogin->nama);
	$tahapan_tes->setField("LAST_CREATE_DATE", "CURRENT_DATE");		
	if($tahapan_tes->insert())
	{
		$tempStatus= 1;
		$reqDetilId = $tahapan_tes->id;
		
		for($i=0;$i<count($reqTahapanTesNilai);$i++)
		{
			$tahapan_tes_nilai = new TahapanTesNilai();
			if($reqTahapanTesNilai[$i] == "")
			{}
			else
			{
				$tahapan_tes_nilai->setField("TAHAPAN_TES_ID", $reqDetilId);
				$tahapan_tes_nilai->setField("NAMA", $reqTahapanTesNilai[$i]);
				$tahapan_tes_nilai->setField("URUT", $i+1);
				$tahapan_tes_nilai->setField("LAST_CREATE_USER", $userLogin->nama);
				$tahapan_tes_nilai->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$tahapan_tes_nilai->insert();
			}		
			unset($tahapan_tes_nilai);
		}	
	}
	echo "Data berhasil disimpan."; 
}
else
{
	$tahapan_tes->setField("LAST_UPDATE_USER", $userLogin->nama);
	$tahapan_tes->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$tahapan_tes->setField('TAHAPAN_TES_ID', $reqId); 
	if($tahapan_tes->update())
	{
		$tempStatus= 1;
		$reqDetilId = $reqId;
		
		$arrDeleteTahapan = explode(",", $reqTahapanTesNilaiDelete);
		for($d=0;$d<count($arrDeleteTahapan);$d++)
		{
			$lowongan_tahapan_delete = new TahapanTesNilai();
			$lowongan_tahapan_delete->setField("TAHAPAN_TES_NILAI_ID", $arrDeleteTahapan[$d]);
			$lowongan_tahapan_delete->delete();
		}
		
		for($i=0;$i<count($reqTahapanTesNilai);$i++)
		{
			$tahapan_tes_nilai = new TahapanTesNilai();
			if($reqTahapanTesNilai[$i] == "")
			{}
			else
			{
				if($reqTahapanTesNilaiId[$i] == "")				
				{
					$tahapan_tes_nilai->setField("TAHAPAN_TES_ID", $reqDetilId);
					$tahapan_tes_nilai->setField("NAMA", $reqTahapanTesNilai[$i]);
					$tahapan_tes_nilai->setField("LAST_CREATE_USER", $userLogin->nama);
					$tahapan_tes_nilai->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$tahapan_tes_nilai->insert();
				}
				else
				{
					$tahapan_tes_nilai->setField("TAHAPAN_TES_NILAI_ID", $reqTahapanTesNilaiId[$i]);
					$tahapan_tes_nilai->setField("NAMA", $reqTahapanTesNilai[$i]);
					$tahapan_tes_nilai->setField("LAST_UPDATE_USER", $userLogin->nama);
					$tahapan_tes_nilai->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
					$tahapan_tes_nilai->update();			
				}
			}		
			unset($tahapan_tes_nilai);
		}			
	}
	
	echo "Data berhasil disimpan."; 
}
	
?>
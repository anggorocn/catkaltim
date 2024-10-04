<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/utils/UserLogin.php");

/* LOGIN CHECK  */
if ($userLogin->checkUserLoginAdmin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$ujian_bank_soal = new UjianPegawaiDaftar();
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
$reqMode= httpFilterGet("reqMode");
$reqPangkatId= httpFilterGet("reqPangkatId");
$reqJabatanId= httpFilterGet("reqJabatanId");

if($reqMode == "all")
{
	//INSERT DATA KALAU DATA BARU YG BELUM MASUK KE TRANSAKSI DETIL
	$statement= " 
	PEGAWAI_ID IN
	(
		SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId."
	";
	
	if($reqPangkatId == ""){}
	else
	$statement.= " 
	AND PEGAWAI_ID IN (
	SELECT PEGAWAI_ID FROM PEGAWAI WHERE PANGKAT_ID = ".$reqPangkatId."
	)";
	
	if($reqJabatanId == ""){}
	else
	$statement.= " 
	AND PEGAWAI_ID IN (
	SELECT PEGAWAI_ID FROM PEGAWAI WHERE JABATAN_ID = ".$reqJabatanId."
	)";
	
	$statement.= " 
		AND PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId." AND STATUS_UJIAN = 1)
	)";
	
	$set_detil= new UjianPegawaiDaftar();
	$set_detil->deleteDetil($statement);
	//echo $set_detil->query;exit;
	unset($set_detil);
	//$statement= " AND A.PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId.")";
}
else
{
	if($reqRowId == ""){}
	else
	{
		//INSERT DATA KALAU DATA BARU YG BELUM MASUK KE TRANSAKSI DETIL
		$statement= " 
		PEGAWAI_ID IN
		(
			SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId." AND PEGAWAI_ID IN (".$reqRowId.")
			AND PEGAWAI_ID NOT IN (SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId." AND STATUS_UJIAN = 1)
		)";
		$set_detil= new UjianPegawaiDaftar();
		$set_detil->deleteDetil($statement);
		//echo $set_detil->query;exit;
		unset($set_detil);
		
	}
}
echo "1";
?>
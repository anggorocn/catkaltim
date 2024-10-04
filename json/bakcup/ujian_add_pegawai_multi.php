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
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqLowonganTahapanId= httpFilterGet("reqLowonganTahapanId");

//exit;
if($reqMode == "all")
{
	//INSERT DATA KALAU DATA BARU YG BELUM MASUK KE TRANSAKSI DETIL
	//$statement= " AND A.PELAMAR_ID NOT IN (SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId.")";
	$statement= " AND A.PELAMAR_ID NOT IN (SELECT A.PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR A INNER JOIN cat.UJIAN B ON A.UJIAN_ID = B.UJIAN_ID WHERE B.LOWONGAN_ID = ".$reqLowonganId." AND B.LOWONGAN_TAHAPAN_ID = ".$reqLowonganTahapanId.")";
	
	if($reqLowonganId == ""){}
	else
	$statement.= " AND PL.LOWONGAN_ID = ".$reqLowonganId;
	
	if($reqLowonganTahapanId == ""){}
	else
	$statement.= " AND PLT.LOWONGAN_TAHAPAN_ID = ".$reqLowonganTahapanId;

	if($reqPangkatId == ""){}
	else
	$statement.= " AND A.PANGKAT_ID = ".$reqPangkatId;
	
	if($reqJabatanId == ""){}
	else
	$statement.= " AND A.JABATAN_ID = ".$reqJabatanId;

	$set_detil= new UjianPegawaiDaftar();
	$set_detil->setField("UJIAN_ID", $reqId);
	$set_detil->setField("LAST_CREATE_USER", $userLogin->UID);
	$set_detil->setField("LAST_CREATE_DATE", "NOW()");
	$set_detil->insertDetil($statement);
	//echo $set_detil->query;exit;
	unset($set_detil);
	
	$statement= " AND A.UJIAN_ID = ".$reqId;
	
	if($reqPangkatId == ""){}
	else
	$statement.= " AND B.PANGKAT_ID = ".$reqPangkatId;
	
	if($reqJabatanId == ""){}
	else
	$statement.= " AND B.JABATAN_ID = ".$reqJabatanId;
	
	$set_detil= new UjianPegawaiDaftar();
	$set_detil->setField("LAST_CREATE_USER", $userLogin->UID);
	$set_detil->setField("LAST_CREATE_DATE", "NOW()");
	//$set_detil->insertUser($statement);
	//echo $set_detil->query;exit;
	unset($set_detil);
}
else
{
	if($reqRowId == ""){}
	else
	{
		//INSERT DATA KALAU DATA BARU YG BELUM MASUK KE TRANSAKSI DETIL
		$statement= " AND A.PELAMAR_ID IN (".$reqRowId.")
		AND A.PELAMAR_ID NOT IN (SELECT A.PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR A INNER JOIN cat.UJIAN B ON A.UJIAN_ID = B.UJIAN_ID WHERE B.LOWONGAN_ID = ".$reqLowonganId." AND B.LOWONGAN_TAHAPAN_ID = ".$reqLowonganTahapanId." AND PEGAWAI_ID IN (".$reqRowId."))";
		//AND A.PELAMAR_ID NOT IN (SELECT PEGAWAI_ID FROM cat.UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_ID = ".$reqId."
		if($reqLowonganId == ""){}
		else
		$statement.= " AND PL.LOWONGAN_ID = ".$reqLowonganId;
		
		if($reqLowonganTahapanId == ""){}
		else
		$statement.= " AND PLT.LOWONGAN_TAHAPAN_ID = ".$reqLowonganTahapanId;

		$set_detil= new UjianPegawaiDaftar();
		$set_detil->setField("UJIAN_ID", $reqId);
		$set_detil->setField("LAST_CREATE_USER", $userLogin->UID);
		$set_detil->setField("LAST_CREATE_DATE", "NOW()");
		$set_detil->insertDetil($statement);
		//echo $set_detil->query;exit;
		unset($set_detil);
		
		$statement= " AND A.PEGAWAI_ID IN (".$reqRowId.") AND A.UJIAN_ID = ".$reqId;
		$set_detil= new UjianPegawaiDaftar();
		$set_detil->setField("LAST_CREATE_USER", $userLogin->UID);
		$set_detil->setField("LAST_CREATE_DATE", "NOW()");
		//$set_detil->insertUser($statement);
		//echo $set_detil->query;exit;
		unset($set_detil);
	}
}
echo "1";
?>
<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqMode= httpFilterGet("reqMode");
$reqId= httpFilterGet("reqId");
$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianBankSoalId= httpFilterGet("reqUjianBankSoalId");
$reqBankSoalId= httpFilterGet("reqBankSoalId");
$reqBankSoalPilihanId= httpFilterGet("reqBankSoalPilihanId");
$reqUrut= httpFilterGet("reqUrut");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$statement= " AND A.UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianTahap();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
$set->firstRow();
$tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
unset($set);

$set= new UjianPegawaiDaftar();
$tempSystemTanggalNow= date("d-m-Y");
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();

if($tempTipeUjianId == 7)
$set->selectByParamsSoalPapi(array(), -1,-1, $statement);
else
$set->selectByParamsSoal(array(), -1,-1, $statement);

$set->firstRow();
$tempPegawaiId= $set->getField("PEGAWAI_ID");
unset($set);

if($tempPegawaiId == ""){}
else
{
	/*$set= new UjianPegawaiDaftar();
	$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND UP.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND UP.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
	//$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
	$set= new UjianPegawaiDaftar();
	
	if($tempTipeUjianId == 7)
	$set->selectByParamsSoalPapi(array(), -1,-1, $statement);
	else
	$set->selectByParamsSoal(array(), -1,-1, $statement);
	//echo $set->query;exit;
	$set->firstRow();
	$tempUjianPegawaiId= $set->getField("UJIAN_PEGAWAI_ID");
	unset($set);*/
	
	if($tempTipeUjianId == 7){}
	else
	{
	$set= new UjianPegawaiDaftar();
	$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND UP.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND UP.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
	//$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
	$set= new UjianPegawaiDaftar();
	$set->selectByParamsSoal(array(), -1,-1, $statement);
	//echo $set->query;exit;
	$set->firstRow();
	$tempUjianPegawaiId= $set->getField("UJIAN_PEGAWAI_ID");
	unset($set);
	}
	
	$set= new UjianPegawaiDaftar();
	//$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND UP.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND UP.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND UP.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId;
	$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND B.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND B.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
	//$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
	$set= new UjianPegawaiDaftar();
	
	if($tempTipeUjianId == 7)
	$set->selectByParamsSoalPapi(array(), -1,-1, $statement);
	else
	$set->selectByParamsSoal(array(), -1,-1, $statement);
	
	//echo $set->query;exit;
	$set->firstRow();
	if($tempTipeUjianId == 7)
	{
		$tempUjianPegawaiId= $set->getField("UJIAN_PEGAWAI_ID");
	}
	$tempUjianBankSoalPilihanPegawaiId= $set->getField("UJIAN_PEGAWAI_ID");
	$tempUrut= $set->getField("URUT");
	unset($set);
	
	//echo $reqMode."-".$tempUjianBankSoalPilihanPegawaiId;exit;
	$set= new UjianPegawai();
	$set->setField("UJIAN_ID", $reqUjianId);
	$set->setField("UJIAN_BANK_SOAL_ID", $reqUjianBankSoalId);
	$set->setField("BANK_SOAL_ID", $reqBankSoalId);
	$set->setField("BANK_SOAL_PILIHAN_ID", $reqBankSoalPilihanId);
	$set->setField("UJIAN_TAHAP_ID", $reqId);
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	$set->setField("TANGGAL", "NOW()");
	$set->setField("URUT", ValToNullDB($tempUrut));
	$set->setField("UJIAN_PEGAWAI_ID", $tempUjianPegawaiId);
	$set->setField("LAST_CREATE_USER", $userLogin->nama);
	$set->setField("LAST_CREATE_DATE", "NOW()");
	$set->setField("LAST_UPDATE_USER", $userLogin->nama);
	$set->setField("LAST_UPDATE_DATE", "NOW()");
		
	//echo $tempUjianBankSoalPilihanPegawaiId."--".$tempUjianBankSoalPilihanPegawaiId;
	//exit;
	$tempStatusSimpan= "";
	//kalau tidak ada maka simpan
	//if($tempUjianPegawaiId == "" || $reqMode == "multi")
	if($tempUjianBankSoalPilihanPegawaiId == "" || $reqMode == "multi")
	{
		if($reqMode == "multi")
		{
			$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND UP.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND UP.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND UP.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId;
			//$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
			$set_check= new UjianPegawaiDaftar();
			
			if($tempTipeUjianId == 7)
			$set_check->selectByParamsSoalPapi(array(), -1,-1, $statement);
			else
			$set_check->selectByParamsSoal(array(), -1,-1, $statement);
			
			//echo $set_check->query;exit;
			$set_check->firstRow();
			$tempUjianBankSoalPilihanPegawaiId= $set_check->getField("UJIAN_PEGAWAI_ID");
			unset($set_check);
	
			if($tempUjianBankSoalPilihanPegawaiId == "")
			{
				if($set->insert())
				$tempStatusSimpan= 1;
			}
			else
			{
				$set_delete= new UjianPegawai();
				$set_delete->setField("UJIAN_PEGAWAI_ID", $tempUjianBankSoalPilihanPegawaiId);
				if($set_delete->deleteId())
				$tempStatusSimpan= 1;
				//echo $set_delete->query;exit;
				unset($set_delete);
			}
			//exit;
		}
		else
		{
			if($set->insert())
			$tempStatusSimpan= 1;
		}
	}
	else
	{
		if($set->update())
		$tempStatusSimpan= 1;
	}
	//echo $set->query;exit;
	unset($set);
	
	if($tempStatusSimpan == "1")
	echo $reqBankSoalPilihanId;
}
?>
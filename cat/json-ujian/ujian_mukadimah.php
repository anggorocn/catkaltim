<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqUjianPegawaiDaftarId= httpFilterGet("reqUjianPegawaiDaftarId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$sOrder= "ORDER BY RANDOM()";
$index_loop=0;
$set= new UjianPegawaiDaftar();
$tempSystemTanggalNow= date("d-m-Y");
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$statement.= " AND A.UJIAN_ID IN( SELECT A.UJIAN_ID FROM cat.UJIAN A INNER JOIN cat.UJIAN_TAHAP B ON A.UJIAN_ID = B.UJIAN_ID WHERE TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI GROUP BY A.UJIAN_ID)";
$set= new UjianPegawaiDaftar();
$set->selectByParamsSoal(array(), -1,-1, $statement, $sOrder);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set->getField("UJIAN_BANK_SOAL_ID");
	$arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrJumlahSoalPegawai[$index_loop]["UJIAN_PEGAWAI_ID"]= $set->getField("UJIAN_PEGAWAI_ID");
	$arrJumlahSoalPegawai[$index_loop]["UJIAN_TAHAP_ID"]= $set->getField("UJIAN_TAHAP_ID");
	$index_loop++;
}
$tempJumlahSoalPegawai= $index_loop;
unset($set);

if($tempJumlahSoalPegawai == "0"){}
else
{
	for($index_loop=0; $index_loop < $tempJumlahSoalPegawai; $index_loop++)
	{
		$tempUjianId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"];
		$tempUjianBankSoalId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"];
		$tempBankSoalId= $arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"];
		$tempUjianPegawaiId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_PEGAWAI_ID"];
		$tempUjianTahapId= $arrJumlahSoalPegawai[$index_loop]["UJIAN_TAHAP_ID"];
		
		$set= new UjianPegawai();
		$set->setField("UJIAN_ID", $tempUjianId);
		$set->setField("UJIAN_BANK_SOAL_ID", $tempUjianBankSoalId);
		$set->setField("BANK_SOAL_ID", $tempBankSoalId);
		$set->setField("BANK_SOAL_PILIHAN_ID", ValToNullDB($req));
		$set->setField("UJIAN_TAHAP_ID", $tempUjianTahapId);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("URUT", ValToNullDB($req));
		$set->setField("TANGGAL", ValToNullDB($req));
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "NOW()");
		$tempStatusSimpan= "";
		//kalau tidak ada maka simpan
		if($tempUjianPegawaiId == "")
		{
			if($set->insert())
			$tempStatusSimpan= "1";
		}
		//echo $set->query;exit;
		unset($set);
	}
	
	// set STATUS_UJIAN
	$set_detil= new UjianPegawaiDaftar();
	$set_detil->setField("UJIAN_ID", $tempUjianId);
	$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
	$set_detil->setField("FIELD", "STATUS_UJIAN");
	$set_detil->setField("FIELD_VALUE", "1");
	$set_detil->updateStatusLog();
	unset($set_detil);
	
	// set TANGGAL
	$set_detil= new UjianPegawaiDaftar();
	$set_detil->setField("UJIAN_ID", $tempUjianId);
	$set_detil->setField("PEGAWAI_ID", $reqPegawaiId);
	$set_detil->setField("FIELD", "TANGGAL");
	$set_detil->setField("FIELD_VALUE", "NOW()");
	$set_detil->updateStatusLog();
	//echo $set_detil->query;exit;
	//STATUS_SETUJU, STATUS_LOGIN, STATUS_SETUJU, TANGGAL, STATUS_UJIAN, STATUS_SELESAI
	unset($set_detil);
	
	echo "1";
}
?>
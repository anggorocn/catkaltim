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

date_default_timezone_set('Asia/Jakarta');

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;
// echo $ujianPegawaiJadwalTesId;exit();

// $tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiLowonganId= $ujianPegawaiJadwalTesId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;

$reqMode= httpFilterGet("reqMode");
$reqId= httpFilterGet("reqId");
$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianBankSoalId= httpFilterGet("reqUjianBankSoalId");
$reqBankSoalId= httpFilterGet("reqBankSoalId");
$reqBankSoalPilihanId= httpFilterGet("reqBankSoalPilihanId");
$reqUrut= httpFilterGet("reqUrut");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$set= new UjianPegawaiDaftar();
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND A.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND A.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianPegawai();
$set->selectByParamsCheckLatihan(array(), -1,-1, $tempUjianPegawaiLowonganId, $statement);
// echo $set->query;exit;
$set->firstRow();

$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempPegawaiId= $set->getField("PEGAWAI_ID");
$tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
$tempUjianPegawaiId= $set->getField("UJIAN_PEGAWAI_ID");
$tempUrut= $set->getField("URUT");
unset($set);

// echo $tempUjianPegawaiId;exit;
//kalau tidak ada simpan soal
//echo $reqMode."-".$tempUjianBankSoalPilihanPegawaiId;exit;
$set= new UjianPegawai();
$set->setField("UJIAN_PEGAWAI_DAFTAR_ID", $tempUjianPegawaiDaftarId);
$set->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
$set->setField("LOWONGAN_ID", $tempUjianPegawaiLowonganId);
$set->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
$set->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
$set->setField("TIPE_UJIAN_ID", $tempTipeUjianId);
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

if($tempPegawaiId == "")
{
	if($set->insertLatihan($tempUjianPegawaiLowonganId))
	$tempStatusSimpan= 1;

	if($tempStatusSimpan == "1")
	echo $reqBankSoalPilihanId;
	// echo $set->query;exit();
}
else
{
	
	$tempStatusSimpan= "";
	if($reqMode == "multi")
	{
		// echo "asd";exit;
		/*$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND UP.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND UP.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND UP.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId;*/
		//$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";

		$statementujian= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND A.UJIAN_TAHAP_ID = ".$reqId." AND A.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId;
		$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND A.UJIAN_ID = ".$tempUjianId." AND UP.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId;

		$set_check= new UjianPegawaiDaftar();
		
		if($tempTipeUjianId == 7)
		{
			exit();
			$set_check->selectByParamsSoalPapi(array(), -1,-1, $tempUjianPegawaiLowonganId, $statement, $statementujian, $sOrder);
		}
		else
		{
			$set_check->selectByParamsSoalRevisiLatihan(array(), -1,-1, $tempUjianPegawaiLowonganId, $statement, $statementujian, $sOrder);

		}
		// echo $set_check->query;exit;

		$set_check->firstRow();
		$tempMultiUjianBankSoalPilihanPegawaiId= $set_check->getField("UJIAN_PEGAWAI_ID");
		unset($set_check);
		// echo $reqBankSoalPilihanId."--".$tempCheckUjianPegawaiId."--".$tempMultiUjianBankSoalPilihanPegawaiId;exit();
		if($tempMultiUjianBankSoalPilihanPegawaiId == "")
		{
			if($set->insertLatihan($tempUjianPegawaiLowonganId))
			$tempStatusSimpan= 1;

			// echo $set->query;exit();
		}
		else
		{
			$set_delete= new UjianPegawai();
			$set_delete->setField("UJIAN_PEGAWAI_ID", $tempMultiUjianBankSoalPilihanPegawaiId);
			if($set_delete->deleteIdLatihan($tempUjianPegawaiLowonganId))
			$tempStatusSimpan= 1;
			//echo $set_delete->query;exit;
			unset($set_delete);
		}
		//exit;
	}
	else
	{
		if($set->updateLatihan($tempUjianPegawaiLowonganId))
		$tempStatusSimpan= 1;
	}
	//echo $set->query;exit;
	unset($set);
	
	if($tempStatusSimpan == "1")
	echo $reqBankSoalPilihanId;
}
?>
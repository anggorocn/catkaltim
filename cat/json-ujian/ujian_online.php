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

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;
// echo $ujianPegawaiJadwalTesId;exit();
$tempUjianId= $ujianPegawaiUjianId;

$reqMode= httpFilterGet("reqMode");
$reqId= httpFilterGet("reqId");
$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianBankSoalId= httpFilterGet("reqUjianBankSoalId");
$reqBankSoalId= httpFilterGet("reqBankSoalId");
$reqBankSoalPilihanId= httpFilterGet("reqBankSoalPilihanId");
$reqUrut= httpFilterGet("reqUrut");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqKeterangan= httpFilterGet("reqKeterangan");

$set= new UjianPegawaiDaftar();
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND A.UJIAN_ID = ".$reqUjianId." AND A.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND A.BANK_SOAL_ID = ".$reqBankSoalId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianPegawai();
$set->selectByParamsCheck(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement);
// echo $set->query;exit;
$set->firstRow();
// , , , 
//        , , UJIAN_ID, , 
//        BANK_SOAL_ID, UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID, BANK_SOAL_PILIHAN_ID, 
//        TANGGAL, URUT

$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$temp= $set->getField("JADWAL_TES_ID");
$temp= $set->getField("FORMULA_ASSESMENT_ID");
$temp= $set->getField("FORMULA_ESELON_ID");
/*$temp= $set->getField("UJIAN_BANK_SOAL_ID");
$temp= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$temp= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$temp= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");*/
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
	if($set->insert($ujianPegawaiJadwalTesId))
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
		$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND A.UJIAN_ID = ".$tempUjianId." AND UP.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId;

		$set_check= new UjianPegawaiDaftar();
		
		if($tempTipeUjianId == 7)
		$set_check->selectByParamsSoalPapi(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $statementujian, $sOrder);
		else
		{
			$set_check->selectByParamsSoalRevisi(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $statementujian, $sOrder);

		}
		// echo $set_check->query;exit;

		$set_check->firstRow();
		$tempMultiUjianBankSoalPilihanPegawaiId= $set_check->getField("UJIAN_PEGAWAI_ID");
		unset($set_check);
		// echo $reqBankSoalPilihanId."--".$tempCheckUjianPegawaiId."--".$tempMultiUjianBankSoalPilihanPegawaiId;exit();
		if($tempMultiUjianBankSoalPilihanPegawaiId == "")
		{
			if($tempTipeUjianId == 47)
			{
				if($set->insertWpt($ujianPegawaiJadwalTesId))
					$tempStatusSimpan= 1;
			}
			else
			{
				if($set->insert($ujianPegawaiJadwalTesId))
					$tempStatusSimpan= 1;
			}

			// echo $set->query;exit();
		}
		else
		{
			$set_delete= new UjianPegawai();
			$set_delete->setField("UJIAN_PEGAWAI_ID", $tempMultiUjianBankSoalPilihanPegawaiId);
			if($set_delete->deleteId($ujianPegawaiJadwalTesId))
			$tempStatusSimpan= 1;
			//echo $set_delete->query;exit;
			unset($set_delete);
		}
		//exit;
	}
	elseif($reqMode == "keterangan")
	{
		if($tempTipeUjianId == 47)
		{
			$setdetil= new UjianPegawai();
			$setdetil->selectByParamsWptPilihan(array("A.WPT_SOAL_ID"=>$reqBankSoalId), -1,-1);
			$setdetil->firstRow();
			$infojawabanid= $setdetil->getField("WPT_PILIHAN_ID");
			$infojawaban= $setdetil->getField("JAWABAN");
		}

		$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND A.UJIAN_ID = ".$tempUjianId;
		$set_check= new UjianPegawai();
		$set_check->selectByParamsKeterangan(array(),-1,-1, $ujianPegawaiJadwalTesId, $statement);
		// echo $set_check->query;exit;
		$set_check->firstRow();
		$reqUjianPegawaiId= $set_check->getField("UJIAN_PEGAWAI_ID");

		$set_ket= new UjianPegawai();
		$set_ket->setField("UJIAN_PEGAWAI_DAFTAR_ID", $tempUjianPegawaiDaftarId);
		$set_ket->setField("JADWAL_TES_ID", $ujianPegawaiJadwalTesId);
		$set_ket->setField("LOWONGAN_ID", $tempUjianPegawaiLowonganId);
		$set_ket->setField("FORMULA_ASSESMENT_ID", $ujianPegawaiFormulaAssesmentId);
		$set_ket->setField("FORMULA_ESELON_ID", $ujianPegawaiFormulaEselonId);
		$set_ket->setField("TIPE_UJIAN_ID", $tempTipeUjianId);
		$set_ket->setField("UJIAN_ID", $reqUjianId);
		$set_ket->setField("UJIAN_BANK_SOAL_ID", $reqUjianBankSoalId);
		$set_ket->setField("BANK_SOAL_ID", $reqBankSoalId);
		$set_ket->setField("KETERANGAN", setQuote($reqKeterangan,1));
		$set_ket->setField("UJIAN_TAHAP_ID", $reqId);
		$set_ket->setField("PEGAWAI_ID", $reqPegawaiId);
		$set_ket->setField("TANGGAL", "NOW()");
		$set_ket->setField("URUT", ValToNullDB($tempUrut));
		$set_ket->setField("UJIAN_PEGAWAI_ID", $reqUjianPegawaiId);
		$set_ket->setField("LAST_CREATE_USER", $userLogin->nama);
		$set_ket->setField("LAST_CREATE_DATE", "NOW()");
		$set_ket->setField("LAST_UPDATE_USER", $userLogin->nama);
		$set_ket->setField("LAST_UPDATE_DATE", "NOW()");

		// validasi kertin
		$validasikertin= "";
		if($tempTipeUjianId == 48)
		{
			if($reqKeterangan >= 0 && $reqKeterangan <= 5){}
			else
			{
				$validasikertin= 1;
			}
		}

		$reqSimpan= "";
		if($reqUjianPegawaiId == "")
		{
			if($tempTipeUjianId == 47)
			{
				if($set_ket->insertWptKeterangan($ujianPegawaiJadwalTesId))
					$reqSimpan= "1";
			}
			elseif($tempTipeUjianId == 48)
			{
				if(empty($validasikertin))
				{
					if($set_ket->insertKertihKeterangan($ujianPegawaiJadwalTesId))
						$reqSimpan= "1";
				}
			}
			else
			{
				if($set_ket->insertKeterangan($ujianPegawaiJadwalTesId))
					$reqSimpan= "1";
			}
		}
		else
		{
			if(empty($validasikertin))
			{
				if($set_ket->updateKeterangan($ujianPegawaiJadwalTesId))
					$reqSimpan= "1";
			}
		}
		// echo $set_ket->query;exit;

		if($reqSimpan == "1")
		{
			if($reqBankSoalId == 27)
			{
				if($reqKeterangan == "1/30" || ($reqKeterangan >= 0.03 && $reqKeterangan < 0.033333334))
					$set->setField("BANK_SOAL_PILIHAN_ID", $infojawabanid);
				else
					$set->setField("BANK_SOAL_PILIHAN_ID", "-1");
			}
			elseif(strtolower($infojawaban) == strtolower($reqKeterangan))
				$set->setField("BANK_SOAL_PILIHAN_ID", $infojawabanid);
			else
				$set->setField("BANK_SOAL_PILIHAN_ID", "-1");

			if($set->update($ujianPegawaiJadwalTesId))
				$tempStatusSimpan= 1;
		}

	}
	elseif($reqMode == "isinourut")
	{
		$statementujian= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.UJIAN_ID = ".$tempUjianId." AND A.UJIAN_TAHAP_ID = ".$reqId." AND A.BANK_SOAL_PILIHAN_ID = ".$reqBankSoalPilihanId." AND A.BANK_SOAL_ID = ".$reqBankSoalId;
		$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND B.UJIAN_BANK_SOAL_ID = ".$reqUjianBankSoalId." AND A.UJIAN_ID = ".$tempUjianId;

		$set_check= new UjianPegawaiDaftar();
		$set_check->selectByParamsSoalRevisi(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $statementujian, $sOrder);
		// echo $set_check->query;exit;
		$set_check->firstRow();
		$tempMultiUjianBankSoalPilihanPegawaiId= $set_check->getField("UJIAN_PEGAWAI_ID");
		unset($set_check);
		// echo $tempMultiUjianBankSoalPilihanPegawaiId;exit();
		// $set->setField("URUT", ValToNullDB($req));
		$set->setField("LAST_CREATE_USER", $reqUrut);
		$set->setField("UJIAN_PEGAWAI_ID", $tempMultiUjianBankSoalPilihanPegawaiId);
		$set->setField("BANK_SOAL_ID", $reqBankSoalId);
		$set->setField("PEGAWAI_ID", $tempPegawaiId);
		$set->setField("BANK_SOAL_PILIHAN_ID", $reqBankSoalPilihanId);

		if($tempMultiUjianBankSoalPilihanPegawaiId == "")
		{
			if($set->insert($ujianPegawaiJadwalTesId))
				$tempStatusSimpan= 1;
		}
		else
		{
			if($set->updateNoUrut($ujianPegawaiJadwalTesId))
				$tempStatusSimpan= 1;
		}
		// echo $set->query;exit();

	}
	else
	{
		if($set->update($ujianPegawaiJadwalTesId))
		$tempStatusSimpan= 1;
	}
	//echo $set->query;exit;
	unset($set);
	
	if($tempStatusSimpan == "1")
	echo $reqBankSoalPilihanId;
}
?>
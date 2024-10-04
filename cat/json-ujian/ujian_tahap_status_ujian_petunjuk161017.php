<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjianPetunjuk.php");
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

$reqUjianId= httpFilterGet("reqUjianId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND B.PEGAWAI_ID IN (SELECT X.PEGAWAI_ID FROM cat.UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK X WHERE X.PEGAWAI_ID = B.PEGAWAI_ID AND X.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID AND X.UJIAN_ID = B.UJIAN_ID )
AND C.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set= new UjianTahapStatusUjianPetunjuk();
$tempNoUrut= $set->getCountByParamsInsert(array(), $statement);
//echo $set->query;exit;
if($tempNoUrut == 0)
{
	$statement= " AND C.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
	$set= new UjianTahapStatusUjianPetunjuk();
	$set->setField("LAST_CREATE_USER", $userLogin->nama);
	$set->setField("LAST_CREATE_DATE", "NOW()");
	if($set->insertQueryModif($statement))
	{
		$sOrder= "ORDER BY RANDOM()";
		$index_loop=0;
		$set_detil= new UjianPegawaiDaftar();
		$tempSystemTanggalNow= date("d-m-Y");
		$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
		$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
		$statement.= " AND B.UJIAN_TAHAP_ID IN (SELECT UJIAN_TAHAP_ID FROM cat.ujian_tahap WHERE UJIAN_ID = ".$reqUjianId." and TIPE_UJIAN_ID = ".$reqTipeUjianId.")";
		$set_detil= new UjianPegawaiDaftar();
		$set_detil->selectByParamsSoal(array(), -1,-1, $statement, $sOrder);
		//echo $set_detil->query;exit;
		while($set_detil->nextRow())
		{
			$nomor= $index_loop+1;
			$arrJumlahSoalPegawai[$index_loop]["NOMOR"]= $nomor;
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_ID"]= $set_detil->getField("UJIAN_ID");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_BANK_SOAL_ID"]= $set_detil->getField("UJIAN_BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["BANK_SOAL_ID"]= $set_detil->getField("BANK_SOAL_ID");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_PEGAWAI_ID"]= $set_detil->getField("UJIAN_PEGAWAI_ID");
			$arrJumlahSoalPegawai[$index_loop]["UJIAN_TAHAP_ID"]= $set_detil->getField("UJIAN_TAHAP_ID");
			$index_loop++;
		}
		$tempJumlahSoalPegawai= $index_loop;
		unset($set_detil);
		//print_r($arrJumlahSoalPegawai);exit;
		
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
				$tempNomor= $arrJumlahSoalPegawai[$index_loop]["NOMOR"];
				
				$set= new UjianPegawai();
				$set->setField("UJIAN_ID", $tempUjianId);
				$set->setField("UJIAN_BANK_SOAL_ID", $tempUjianBankSoalId);
				$set->setField("BANK_SOAL_ID", $tempBankSoalId);
				$set->setField("BANK_SOAL_PILIHAN_ID", ValToNullDB($req));
				$set->setField("UJIAN_TAHAP_ID", $tempUjianTahapId);
				$set->setField("PEGAWAI_ID", $tempPegawaiId);
				$set->setField("URUT", ValToNullDB($tempNomor));
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
			$set_detil->setField("UJIAN_ID", $reqUjianId);
			$set_detil->setField("PEGAWAI_ID", $tempPegawaiId);
			$set_detil->setField("FIELD", "STATUS_UJIAN");
			$set_detil->setField("FIELD_VALUE", "1");
			$set_detil->updateStatusLog();
			unset($set_detil);
			
			// set TANGGAL
			$set_detil= new UjianPegawaiDaftar();
			$set_detil->setField("UJIAN_ID", $reqUjianId);
			$set_detil->setField("PEGAWAI_ID", $tempPegawaiId);
			$set_detil->setField("FIELD", "TANGGAL");
			$set_detil->setField("FIELD_VALUE", "NOW()");
			$set_detil->updateStatusLog();
			//echo $set_detil->query;exit;
			//STATUS_SETUJU, STATUS_LOGIN, STATUS_SETUJU, TANGGAL, STATUS_UJIAN, STATUS_SELESAI
			unset($set_detil);
			
			echo "1";
		}

		echo "1";
	}
	else
	echo "0";
}
else
echo 1;
//echo $set->query;exit;
exit;
?>
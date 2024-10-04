<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-postgre/SqlKonversi.php");

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$set= new SqlKonversi();
$set->selectByParamsPegawai();
// echo $set->query;exit();
// echo $set->errorMsg;exit();
while($set->nextRow())
{
	// echo $set->getField("NAMA");exit();
	 $tempNama= $set->getField("NAMA");
	 $tempKey= $set->getField("NIP_BARU");
	 $tempPegawaiId= str_replace(" ", "", $tempKey);
	 $tempSatkerId= $set->getField("KODE_UNIT");
	 // echo $tempSatkerId;exit();
	 $tempNamaJabatan= $set->getField("NAMA_JABATAN");
	 $tempPangkatId= $set->getField("PANGKAT_ID");
	 $tempEselonId= $set->getField("ESELON_ID");

	 /*$tempJenisKelamin=$set->getField("JENIS_KELAMIN");
	 $tempTempatLahir=$set->getField("TEMPAT_LAHIR");
	 $tempAgama= $set->getField("AGAMA");
	 $tempTglLahir= $set->getField("TGL_LAHIR");
	 $tempLastPangkatId= $set->getField("LAST_PANGKAT_ID");
	 $tempLastTmtPangkat= $set->getField("LAST_TMT_PANGKAT");
	 $tempTmtCpns= $set->getField("TMT_CPNS");
	 $tempTmtPns= $set->getField("TMT_PNS");	 
	 $tempLastJabatan= $set->getField("LAST_JABATAN");
	 $tempLastEselonId= $set->getField("LAST_ESELON_ID");
	 $tempLastTmtJabatan= $set->getField("LAST_TMT_JABATAN");
	 $tempMasaJabTahun= $set->getField("MASA_JAB_TAHUN");
	 $tempMasaJabBulan= $set->getField("MASA_JAB_BULAN");
	 $tempMasaKerjaTahun= $set->getField("MASA_KERJA_TAHUN");
	 $tempMasaKerjaBulan= $set->getField("MASA_KERJA_BULAN");
	 $tempSatkerId= $set->getField("SATKER_ID");
	 $tempTipePegawaiId= $set->getField("TIPE_PEGAWAI_ID");
	 $tempStatusPegawaiId= $set->getField("STATUS_PEGAWAI_ID");
	 $tempLastDikJenjang= $set->getField("LAST_DIK_JENJANG");
	 $tempLastDikTahun= $set->getField("LAST_DIK_TAHUN");
	 $tempLastDikJurusang= $set->getField("LAST_DIK_JURUSAN");
	 $tempAlamat= $set->getField("ALAMAT");
	 $tempNamaJabatan= $set->getField("NAMA_JABATAN");
	 $tempKelasJabatan= $set->getField("KELAS_JABATAN");
	 $tempNilaiJabatan= $set->getField("NILAI_JABATAN");
	 $tempProsen= $set->getField("PROSEN");
	 $tempKodeUnit= $set->getField("KODE_UNIT");
	 $tempNamaUnit= $set->getField("NAMA_UNIT");
	 $tempNamaUpt= $set->getField("NAMA_UPT");
	 $tempNomorRekening= $set->getField("NOMOR_REKENING");
	 $tempKodeGolongan= $set->getField("KODE_GOLONGAN");
	 $tempNamaPangkat= $set->getField("NAMA_PANGKAT");
	 $tempKodeEselon= $set->getField("KODE_ESELON");
	 $tempTMTJabatan= $set->getField("TMT_JABATAN");
	 $tempUrut= $set->getField("URUT");
	 echo $tempKey;exit();*/

	 /*$tempPegawaiId= $arrData[$index_data]["PEGAWAI_ID"];
	 $tempNama= $arrData[$index_data]["NAMA"];
	 $tempJenisKelamin= $arrData[$index_data]["JENIS_KELAMIN"];
	 $tempTempatLahir= $arrData[$index_data]["TEMPAT_LAHIR"];
	 $tempTglLahir= $arrData[$index_data]["TGL_LAHIR"];
	 $tempAgama= $arrData[$index_data]["AGAMA"];
	 $tempLastPangkatId= $arrData[$index_data]["LAST_PANGKAT_ID"];
	 $tempLastTmtPangkat= $arrData[$index_data]["LAST_TMT_PANGKAT"];
	 $tempTmtCpns= $arrData[$index_data]["TMT_CPNS"];
	 $tempTmtPns= $arrData[$index_data]["TMT_PNS"];	 
	 $tempLastJabatan= $arrData[$index_data]["LAST_JABATAN"];
	 $tempLastEselonId= $arrData[$index_data]["LAST_ESELON_ID"];
	 $tempLastTmtJabatan= $arrData[$index_data]["LAST_TMT_JABATAN"];
	 $tempMasaJabTahun= $arrData[$index_data]["MASA_JAB_TAHUN"];
	 $tempMasaJabBulan= $arrData[$index_data]["MASA_JAB_BULAN"];
	 $tempMasaKerjaTahun= $arrData[$index_data]["MASA_KERJA_TAHUN"];
	 $tempMasaKerjaBulan= $arrData[$index_data]["MASA_KERJA_BULAN"];
	 $tempSatkerId= $arrData[$index_data]["SATKER_ID"];
	 $tempTipePegawaiId= $arrData[$index_data]["TIPE_PEGAWAI_ID"];
	 $tempStatusPegawaiId= $arrData[$index_data]["STATUS_PEGAWAI_ID"];
	 $tempLastDikJenjang= $arrData[$index_data]["LAST_DIK_JENJANG"];
	 $tempLastDikTahun= $arrData[$index_data]["LAST_DIK_TAHUN"];
	 $tempLastDikJurusang= $arrData[$index_data]["LAST_DIK_JURUSAN"];
	 $tempAlamat= $arrData[$index_data]["ALAMAT"];*/
	
	$set_detil= new SqlKonversi();
	
	$statement= " AND PEGAWAI_ID = '".$tempPegawaiId."'";
	$tempDataId= $set_detil->cariIdTable("PEGAWAI_ID", "simpeg.PEGAWAI", $statement);
	// echo $set_detil->query;exit;
	
	//CariProgPusatExist
	$set_data= new SqlKonversi();
	$set_data->setField("PEGAWAI_ID", $tempPegawaiId);
	$set_data->setField("NIP_BARU", $tempKey);
	$set_data->setField("SATKER_ID", $tempSatkerId);
	$set_data->setField("NAMA", setQuote($tempNama));
	$set_data->setField("LAST_JABATAN", setQuote($tempNamaJabatan));
	$set_data->setField('LAST_PANGKAT_ID', ValToNullDB($tempPangkatId));
	$set_data->setField('LAST_ESELON_ID', ValToNullDB($tempEselonId));

	if($tempDataId == "")
	{
		/*
		$set_data->setField("JENIS_KELAMIN", setQuote($tempJenisKelamin));
		$set_data->setField("TEMPAT_LAHIR", setQuote($tempTempatLahir));
		$set_data->setField("TGL_LAHIR", setQuote($tempTglLahir));
		$set_data->setField("AGAMA", setQuote($tempAgama));
		$set_data->setField("LAST_PANGKAT_ID", setQuote($tempLastPangkatId));
		$set_data->setField("LAST_TMT_PANGKAT", setQuote($tempLastTmtPangkat));
		$set_data->setField("TMT_CPNS", setQuote($tempTmtCpns));
		$set_data->setField("TMT_PNS", setQuote($tempTmtPns));
		$set_data->setField("LAST_JABATAN", setQuote($tempLastJabatan));
		$set_data->setField("LAST_ESELON_ID", setQuote($tempLastEselonId));
		$set_data->setField("LAST_TMT_JABATAN", setQuote($tempLastTmtJabatan));
		$set_data->setField("MASA_JAB_TAHUN", setQuote($tempMasaJabTahun));
		$set_data->setField("MASA_JAB_BULAN", setQuote($tempMasaJabBulan));
		$set_data->setField("MASA_KERJA_TAHUN", setQuote($tempMasaKerjaTahun));
		$set_data->setField("MASA_KERJA_BULAN", setQuote($tempMasaKerjaBulan));
		$set_data->setField("SATKER_ID", setQuote($tempSatkerId));
		$set_data->setField("TIPE_PEGAWAI_ID", setQuote($tempTipePegawaiId));
		$set_data->setField("STATUS_PEGAWAI_ID", setQuote($tempStatusPegawaiId));
		$set_data->setField("LAST_DIK_JENJANG", setQuote($tempLastDikJenjang));
		$set_data->setField("LAST_DIK_TAHUN", setQuote($tempLastDikTahun));
		$set_data->setField("LAST_DIK_JURUSAN", setQuote($tempLastDikJurusang));
		$set_data->setField("ALAMAT", setQuote($tempAlamat));
		*/
		$set_data->insertPegawai();
		unset($set_data);
	}
	else
	{
		/*
		$set_data->setField("NAMA_JABATAN", setQuote($tempNamaJabatan));
		$set_data->setField("NILAI_JABATAN", setQuote($tempNilaiJabatan));
		$set_data->setField("PROSEN", setQuote($tempProsen));
		$set_data->setField("KODE_UNIT", setQuote($tempKodeUnit));
		$set_data->setField("NAMA_UNIT", setQuote($tempNamaUnit));
		$set_data->setField("NAMA_UPT", setQuote($tempNamaUpt));
		$set_data->setField("NOMOR_REKENING", setQuote($tempNomorRekening));
		$set_data->setField("KODE_GOLONGAN", setQuote($tempKodeGolongan));
		$set_data->setField("NAMA_PANGKAT", setQuote($tempNamaPangkat));
		$set_data->setField("KODE_ESELON", setQuote($tempKodeEselon));
		$set_data->setField("TMT_JABATAN", dateToDBCheck($tempTMTJabatan));
		$set_data->setField("NIP_BARU", $tempKey);
		$set_data->setField("NAMA", setQuote($tempNama));
		$set_data->setField("JENIS_KELAMIN", setQuote($tempJenisKelamin));
		$set_data->setField("TEMPAT_LAHIR", setQuote($tempTempatLahir));
		$set_data->setField("TGL_LAHIR", setQuote($tempTglLahir));
		$set_data->setField("AGAMA", setQuote($tempAgama));
		$set_data->setField("LAST_PANGKAT_ID", setQuote($tempLastPangkatId));
		$set_data->setField("LAST_TMT_PANGKAT", setQuote($tempLastTmtPangkat));
		$set_data->setField("TMT_CPNS", setQuote($tempTmtCpns));
		$set_data->setField("TMT_PNS", setQuote($tempTmtPns));
		$set_data->setField("LAST_JABATAN", setQuote($tempLastJabatan));
		$set_data->setField("LAST_ESELON_ID", setQuote($tempLastEselonId));
		$set_data->setField("LAST_TMT_JABATAN", setQuote($tempLastTmtJabatan));
		$set_data->setField("MASA_JAB_TAHUN", setQuote($tempMasaJabTahun));
		$set_data->setField("MASA_JAB_BULAN", setQuote($tempMasaJabBulan));
		$set_data->setField("MASA_KERJA_TAHUN", setQuote($tempMasaKerjaTahun));
		$set_data->setField("MASA_KERJA_BULAN", setQuote($tempMasaKerjaBulan));
		$set_data->setField("SATKER_ID", setQuote($tempSatkerId));
		$set_data->setField("TIPE_PEGAWAI_ID", setQuote($tempTipePegawaiId));
		$set_data->setField("STATUS_PEGAWAI_ID", setQuote($tempStatusPegawaiId));
		$set_data->setField("LAST_DIK_JENJANG", setQuote($tempLastDikJenjang));
		$set_data->setField("LAST_DIK_TAHUN", setQuote($tempLastDikTahun));
		$set_data->setField("LAST_DIK_JURUSAN", setQuote($tempLastDikJurusang));
		$set_data->setField("ALAMAT", setQuote($tempAlamat));
		$set_data= new SqlKonversi();
		$set_data->setField("NIP_BARU", $tempKey);
		$set_data->setField("NAMA", setQuote($tempNama));
		$set_data->setField("JENIS_KELAMIN", setQuote($tempJenisKelamin));
		$set_data->setField("TEMPAT_LAHIR", setQuote($tempTempatLahir));
		$set_data->setField("TGL_LAHIR", setQuote($TGL_LAHIR));
		$set_data->setField("AGAMA", setQuote($tempAgama));
		$set_data->setField("LAST_PANGKAT_ID", setQuote($tempLastPangkatId));
		$set_data->setField("LAST_TMT_PANGKAT", setQuote($tempLastTmtPangkat));
		$set_data->setField("TMT_CPNS", dateToDBCheck($tempTmtCpns));
		$set_data->setField("TMT_PNS", dateToDBCheck($tempTmtPns));
		$set_data->setField("LAST_JABATAN", setQuote($tempLastJabatan));
		$set_data->setField("MASA_JAB_TAHUN", setQuote($tempMasaJabTahun));
		$set_data->setField("MASA_JAB_BULAN", setQuote($tempMasaJabBulan));
		$set_data->setField("MASA_KERJA_TAHUN", setQuote($tempMasaKerjaTahun));
		$set_data->setField("MASA_KERJA_BULAN", setQuote($tempMasaKerjaBulan));
		$set_data->setField("SATKER_ID", setQuote($tempSatkerId));
		$set_data->setField("TIPE_PEGAWAI_ID", setQuote($tempTipePegawaiId));
		$set_data->setField("STATUS_PEGAWAI_ID", setQuote($tempStatusPegawaiId));
		$set_data->setField("LAST_DIK_JENJANG", setQuote($tempLastDikJenjang));
		$set_data->setField("LAST_DIK_TAHUN", setQuote($tempLastDikTahun));
		$set_data->setField("LAST_DIK_JURUSAN", setQuote($tempLastDikJurusang));
		$set_data->setField("ALAMAT", setQuote($tempAlamat));*/
		$set_data->updatePegawai();
		unset($set_data);
	}
	// exit();
}
echo "1";
?>
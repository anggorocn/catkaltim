<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pegawai.php");
include_once("../WEB/classes/base/PegawaiDesa.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqNipBaru= httpFilterPost("reqNipBaru");
$reqNama= httpFilterPost("reqNama");
$reqJenisKelamin= httpFilterPost("reqJenisKelamin");
$reqTempatLahir= httpFilterPost("reqTempatLahir");
$reqTglLahir= httpFilterPost("reqTglLahir");
$reqJabatan= httpFilterPost("reqJabatan");
$reqJabatanId= httpFilterPost("reqJabatanId");
$reqPangkat= httpFilterPost("reqPangkat");
$reqPangkatId= httpFilterPost("reqPangkatId");
$reqTmtPangkat= httpFilterPost("reqTmtPangkat");
$reqPegawaiId= httpFilterPost("reqPegawaiId");

$reqPropinsiId= httpFilterPost("reqPropinsiId");
$reqKabupatenId= httpFilterPost("reqKabupatenId");
$reqKecamatanId= httpFilterPost("reqKecamatanId");
$reqDesaId= httpFilterPost("reqDesaId");
$reqPendidikan= httpFilterPost("reqPendidikan");


$reqTelp= httpFilterPost("reqTelp");
$reqEmail= httpFilterPost("reqEmail");

if($reqPegawaiId == ""){}
else
{
	$set= new Pegawai();
	$set->setField("PROPINSI_ID", $reqPropinsiId);
	$set->setField("KABUPATEN_ID", $reqKabupatenId);
	$set->setField("KECAMATAN_ID", $reqKecamatanId);
					
	$set->setField("NIP_BARU", $reqNipBaru);
	$set->setField("NAMA", $reqNama);
	$set->setField("TEMPAT_LAHIR", $reqTempatLahir);
	$set->setField("JENIS_KELAMIN", $reqJenisKelamin);
	
	$arrPangkat = explode("-", $reqPangkatId);
	$reqPangkatId = $arrPangkat[0];
	$reqPangkat = $arrPangkat[1];
	
	$arrJabatan = explode("-", $reqJabatanId);
	$reqJabatanId = $arrJabatan[0];
	$reqJabatan = $arrJabatan[1];
	
	$set->setField("JABATAN", $reqJabatan);
	$set->setField("PANGKAT", $reqPangkat);
	$set->setField("PENDIDIKAN", $reqPendidikan);
	$set->setField("LOKASI_KERJA", $req);
	
	$set->setField("PANGKAT_ID", $reqPangkatId);
	$set->setField("JABATAN_ID", $reqJabatanId);
	
	$set->setField("TELP", $reqTelp);
	$set->setField("EMAIL", $reqEmail);
	
	$set->setField("TGL_LAHIR", dateToDBCheck($reqTglLahir));
	$set->setField("TMT_PANGKAT", dateToDBCheck($reqTmtPangkat));
	$set->setField("PEGAWAI_ID", $reqPegawaiId);
	
	$tempStatusSimpan= "";
	//kalau tidak ada maka simpan
	if($reqPegawaiId == ""){}
	else
	{
		if($set->update())
		$tempStatusSimpan= 1;
	}
	//echo $set->query;exit;
	unset($set);
	
	if($tempStatusSimpan == 1)
	{
		$tempDesaId= $reqDesaId;
		
		if($tempDesaId == ""){}
		else
		{
			$arrDesa= explode(",", $tempDesaId);
			for($index_detil_array=0; $index_detil_array < count($arrDesa); $index_detil_array++)
			{
				$tempDesaId= $arrDesa[$index_detil_array];
				if($tempDesaId == ""){}
				else
				{
					if($index_detil_array == 0)
					{
						$set_detil= new PegawaiDesa();
						$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
						$set_detil->delete();
						unset($set_detil);
					}
					
					$set_detil= new PegawaiDesa();
					$set_detil->setField('PEGAWAI_ID', $reqPegawaiId);
					$set_detil->setField('DESA_ID', $tempDesaId);
					$set_detil->insert();
					unset($set_detil);
				}
			}
		}
	}
	unset($set);
		
	if($tempStatusSimpan == "1")
	{
		echo "1-Data berhasil disimpan";
	}
	else
	{
		echo "2-Data gagal disimpan";
	}
}
?>
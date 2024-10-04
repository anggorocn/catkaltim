<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Jabatan.php");
include_once("../WEB/classes/base/CabangP3.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/base/LowonganDokumen.php");
include_once("../WEB/classes/base/LowonganTahapan.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");

$FILE_DIR = "../uploads/lowongan/";
$_THUMB_PREFIX = "z__thumb_";

$jabatan = new Jabatan();
$cabang_p3 = new CabangP3();
$lowongan = new Lowongan();
$lowongan_dokumen = new LowonganDokumen();
$file = new FileHandler();


$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
//$reqKode= httpFilterPost("reqKode");
$reqJabatan= httpFilterPost("reqJabatan");
$reqTanggal= httpFilterPost("reqTanggal");
$reqTanggalAwal= httpFilterPost("reqTanggalAwal");
$reqTanggalAkhir= httpFilterPost("reqTanggalAkhir");
$reqJumlah= httpFilterPost("reqJumlah");
$reqPenempatan = $_POST["reqPenempatan"];
$reqPersyaratan = $_POST["reqPersyaratan"];
$reqDokumenWajib = $_POST["reqDokumenWajib"];
$reqDokumen = $_POST["reqDokumen"];
$reqDokumenId = $_POST["reqDokumenId"];
$reqTahapan = $_POST["reqTahapan"];
$reqTanggalTahapan = $_POST["reqTanggalTahapan"];
$reqTempatTahapan = $_POST["reqTempatTahapan"];
$reqTahapanId = $_POST["reqTahapanId"];
$reqDokumenDelete= httpFilterPost("reqDokumenDelete");
$reqTahapanDelete = httpFilterPost("reqTahapanDelete");
$reqManual = httpFilterPost("reqManual");
$reqKeterangan = httpFilterPost("reqKeterangan");
$reqCabangP3Id = httpFilterPost("reqCabangP3Id");

$jabatan->selectByParams(array("A.JABATAN_ID" => $reqJabatan));
$jabatan->firstRow();
$kode_jabatan = $jabatan->getField("KODE");

$cabang_p3->selectByParams(array("A.CABANG_P3_ID" => $reqCabangP3Id));
$cabang_p3->firstRow();
$kode_cabang = $cabang_p3->getField("KODE_CABANG");
$nama_cabang = $cabang_p3->getField("NAMA");

$reqKode = $kode_jabatan.'-'.$kode_cabang;

$reqLinkFile= $_FILES['reqLinkFile'];
$reqLinkFileTemp = httpFilterPost("reqLinkFileTemp");

$lowongan->setField('LOWONGAN_ID', $reqId);
$lowongan->setField('KODE', $reqKode);
$lowongan->setField('TANGGAL', dateTimeToDBCheck($reqTanggal));
$lowongan->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
$lowongan->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
$lowongan->setField('JABATAN_ID', $reqJabatan);
$lowongan->setField('JUMLAH', $reqJumlah);
$lowongan->setField('MANUAL', $reqManual);
$lowongan->setField('KETERANGAN', $reqKeterangan);

/* START UPDATE KE FORMASI DETIL -> PENEMPATAN */
/*
for($i=0;$i<count($reqPenempatan);$i++)
{
	if($reqPenempatan[$i] == "")
	{}
	else
	{
		if($i == 0)	
			$InsertPersyaratan = $reqPenempatan[$i];
		else
			$InsertPersyaratan .= "($$)".$reqPenempatan[$i];
	}		
}
*/
$lowongan->setField("PENEMPATAN", $nama_cabang);
$lowongan->setField('CABANG_P3_ID', $reqCabangP3Id);
/* END */

/* START UPDATE KE FORMASI DETIL -> PERSYARATAN */
for($i=0;$i<count($reqPersyaratan);$i++)
{
	if($reqPersyaratan[$i] == "")
	{}
	else
	{
		if($i == 0)	
			$InsertPersyaratan = $reqPersyaratan[$i];
		else
			$InsertPersyaratan .= "($$)".$reqPersyaratan[$i];
	}		
}
$lowongan->setField("PERSYARATAN", $InsertPersyaratan);	
/* END */

/* START UPDATE KE FORMASI DETIL -> PERSYARATAN */

for($i=0;$i<count($reqDokumen);$i++)
{
	if($reqDokumen[$i] == "")
	{}
	else
	{
		if($i == 0)	
		{
			$InsertDokumen = $reqDokumen[$i];
			$InsertDokumenWajib = $reqDokumenWajib[$i];
		}
		else
		{
			$InsertDokumen .= "($$)".$reqDokumen[$i];
			$InsertDokumenWajib .= "($$)".$reqDokumenWajib[$i];
		}
	}		
}
$lowongan->setField("DOKUMEN", $InsertDokumen);	
$lowongan->setField("DOKUMEN_WAJIB", $InsertDokumenWajib);	
/* END */

if($reqMode == "insert")
{
	$lowongan->setField("LAST_CREATE_USER", $userLogin->nama);
	$lowongan->setField("LAST_CREATE_DATE", "CURRENT_DATE");		
	if($lowongan->insert())
	{
		$tempStatus= 1;
		$reqDetilId = $lowongan->id;
		
		$lowongan_dokumen->setField("LOWONGAN_ID", $reqDetilId);
		$lowongan_dokumen->delete();
		for($i=0;$i<count($reqDokumen);$i++)
		{
			$lowongan_dokumen = new LowonganDokumen();
			if($reqDokumen[$i] == "")
			{}
			else
			{
				
				$lowongan_dokumen->setField("LOWONGAN_ID", $reqDetilId);
				$lowongan_dokumen->setField("NAMA", $reqDokumen[$i]);
				$lowongan_dokumen->setField("KETERANGAN", $reqDokumen[$i]);
				$lowongan_dokumen->insert();
				
			}		
			unset($lowongan_dokumen);
		}

		for($i=0;$i<count($reqTahapan);$i++)
		{
			$lowongan_tahapan = new LowonganTahapan();
			if($reqTahapan[$i] == "")
			{}
			else
			{
				$lowongan_tahapan->setField("LOWONGAN_ID", $reqDetilId);
				$lowongan_tahapan->setField("NAMA", $reqTahapan[$i]);
				$lowongan_tahapan->setField("URUT", $i+1);
				$lowongan_tahapan->setField("KETERANGAN", $reqTahapan[$i]);
				$lowongan_tahapan->setField("TANGGAL_TAHAPAN", dateToDBCheck($reqTanggalTahapan[$i]));
				$lowongan_tahapan->setField("TEMPAT_TAHAPAN", $reqTempatTahapan[$i]);
				$lowongan_tahapan->insert();
				
			}		
			unset($lowongan_tahapan);
		}	
		
	}
	//echo $lowongan->query;
}
else
{
	$lowongan->setField("LAST_UPDATE_USER", $userLogin->nama);
	$lowongan->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
	$lowongan->setField('LOWONGAN_ID', $reqId); 
	if($lowongan->update())
	{
		$tempStatus= 1;
		$reqDetilId = $reqId;
		
		$arrDeleteDokumen = explode(",", $reqDokumenDelete);
		for($d=0;$d<count($arrDeleteDokumen);$d++)
		{
			$lowongan_dokumen_delete = new LowonganDokumen();
			$lowongan_dokumen_delete->setField("LOWONGAN_DOKUMEN_ID", $arrDeleteDokumen[$d]);
			$lowongan_dokumen_delete->delete();
		}
		
		/*$lowongan_dokumen->setField("LOWONGAN_ID", $reqDetilId);
		$lowongan_dokumen->delete();*/
		for($i=0;$i<count($reqDokumen);$i++)
		{
			$lowongan_dokumen = new LowonganDokumen();
			if($reqDokumen[$i] == "")
			{}
			else
			{
				if($reqDokumenId[$i] == "")				
				{
					$lowongan_dokumen->setField("LOWONGAN_ID", $reqDetilId);
					$lowongan_dokumen->setField("NAMA", $reqDokumen[$i]);
					$lowongan_dokumen->setField("WAJIB", $reqDokumenWajib[$i]);
					$lowongan_dokumen->setField("KETERANGAN", $reqDokumen[$i]);
					$lowongan_dokumen->insert();
				}
				else
				{
					$lowongan_dokumen->setField("LOWONGAN_DOKUMEN_ID", $reqDokumenId[$i]);
					$lowongan_dokumen->setField("NAMA", $reqDokumen[$i]);
					$lowongan_dokumen->setField("WAJIB", $reqDokumenWajib[$i]);
					$lowongan_dokumen->setField("KETERANGAN", $reqDokumen[$i]);		
					$lowongan_dokumen->update();			
				}
				
			}		
			unset($lowongan_dokumen);
		}


		$arrDeleteTahapan = explode(",", $reqTahapanDelete);
		for($d=0;$d<count($arrDeleteTahapan);$d++)
		{
			$lowongan_tahapan_delete = new LowonganTahapan();
			$lowongan_tahapan_delete->setField("LOWONGAN_TAHAPAN_ID", $arrDeleteTahapan[$d]);
			$lowongan_tahapan_delete->delete();
		}
		
		for($i=0;$i<count($reqTahapan);$i++)
		{
			$lowongan_tahapan = new LowonganTahapan();
			if($reqTahapan[$i] == "")
			{}
			else
			{
				if($reqTahapanId[$i] == "")				
				{
					$lowongan_tahapan->setField("LOWONGAN_ID", $reqDetilId);
					$lowongan_tahapan->setField("NAMA", $reqTahapan[$i]);
					$lowongan_tahapan->setField("URUT", $i+1);
					$lowongan_tahapan->setField("KETERANGAN", $reqTahapan[$i]);
					$lowongan_tahapan->setField("TANGGAL_TAHAPAN", dateToDBCheck($reqTanggalTahapan[$i]));
					$lowongan_tahapan->setField("TEMPAT_TAHAPAN", $reqTempatTahapan[$i]);
					$lowongan_tahapan->insert();
				}
				else
				{
					$lowongan_tahapan->setField("LOWONGAN_TAHAPAN_ID", $reqTahapanId[$i]);
					$lowongan_tahapan->setField("NAMA", $reqTahapan[$i]);
					$lowongan_tahapan->setField("URUT", $i+1);
					$lowongan_tahapan->setField("KETERANGAN", $reqTahapan[$i]);
					$lowongan_tahapan->setField("TANGGAL_TAHAPAN", dateToDBCheck($reqTanggalTahapan[$i]));
					$lowongan_tahapan->setField("TEMPAT_TAHAPAN", $reqTempatTahapan[$i]);
					$lowongan_tahapan->update();			
				}
				
			}		
			unset($lowongan_tahapan);
		}
				
	}
	
}

if($tempStatus == 1)
{
	$cek = formatTextToDb($file->getFileName('reqLinkFile'));
	if($cek == "")
	{
	}
	else
	{
		$allowed = array(".exe");	$status_allowed='';
		foreach ($allowed as $file_cek) 
		{
			if(preg_match("/$file_cek\$/i", $_FILES['reqLinkFile']['name'])) 
			{
				$status_allowed = 'tidak_boleh';
			}
		}
		
		$renameFile = $reqDetilId.'~'.formatTextToDb($file->getFileName('reqLinkFile'));
		$renameFile = str_replace(" ", "", $renameFile);
		
		$varSource=$FILE_DIR.$reqLinkFileTemp;
		
		if($file->uploadToDir('reqLinkFile', $FILE_DIR, $renameFile))
		{
			if($reqStatus == "1")
			{	
				if($reqLinkFileTemp != ''){
					$file->delete($varSource);
				}
			}
			$insertLinkFile = $file->uploadedFileName;
			$set_file = new Lowongan();
			$set_file->setField('LOWONGAN_ID', $reqDetilId);	
			$set_file->setField('LINK_FILE', $insertLinkFile);
			$set_file->update_file();
		}
	}
	
	echo "Data berhasil disimpan."; 
}
?>
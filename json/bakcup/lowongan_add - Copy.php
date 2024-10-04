<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/base/LowonganDokumen.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");

$FILE_DIR = "../uploads/lowongan/";
$_THUMB_PREFIX = "z__thumb_";

$lowongan = new Lowongan();
$lowongan_dokumen = new LowonganDokumen();
$file = new FileHandler();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKode= httpFilterPost("reqKode");
$reqJabatan= httpFilterPost("reqJabatan");
$reqTanggal= httpFilterPost("reqTanggal");
$reqTanggalAwal= httpFilterPost("reqTanggalAwal");
$reqTanggalAkhir= httpFilterPost("reqTanggalAkhir");
$reqJumlah= httpFilterPost("reqJumlah");
$reqPenempatan = $_POST["reqPenempatan"];
$reqPersyaratan = $_POST["reqPersyaratan"];
$reqDokumenWajib = $_POST["reqDokumenWajib"];
$reqDokumen = $_POST["reqDokumen"];

$reqLinkFile= $_FILES['reqLinkFile'];
$reqLinkFileTemp = httpFilterPost("reqLinkFileTemp");

$lowongan->setField('LOWONGAN_ID', $reqId);
$lowongan->setField('KODE', $reqKode);
$lowongan->setField('TANGGAL', dateTimeToDBCheck($reqTanggal));
$lowongan->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
$lowongan->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
$lowongan->setField('JABATAN_ID', $reqJabatan);
$lowongan->setField('JUMLAH', $reqJumlah);

/* START UPDATE KE FORMASI DETIL -> PENEMPATAN */
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
$lowongan->setField("PENEMPATAN", $InsertPersyaratan);
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
				$lowongan_dokumen->setField("WAJIB", $reqDokumenWajib[$i]);
				$lowongan_dokumen->setField("KETERANGAN", $reqDokumen[$i]);
				$lowongan_dokumen->insert();
				
			}		
			unset($lowongan_dokumen);
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
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/KebutuhanAsesor.php");
require '../WEB/lib/phpexcelchart/PHPExcel/IOFactory.php';

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

$reqJadwalAsesorId= httpFilterGet("reqJadwalAsesorId");
// echo $reqJadwalAsesorId;exit;

$arrData= "";
$arrData= array();
$index_loop= 0;
$set= new KebutuhanAsesor();
$set->selectByParamsObservasi(array(), -1,-1, " AND A.JADWAL_ASESOR_ID = ".$reqJadwalAsesorId);
// echo $set->query;exit;
while($set->nextRow())
{
  $infogeneratefile= md5($set->getField("JADWAL_TES_ID")."-".$set->getField("JADWAL_ASESOR_ID"));
  $arrData[$index_loop]["NAMA_FOLDER"]= "../dokumenfile/".$infogeneratefile."/".str_replace(" ", "", $set->getField("PENGGALIAN_KODE"));
  $arrData[$index_loop]["NAMA_ZIP"]= "../dokumenfile/".$infogeneratefile."/".$infogeneratefile.".zip";
  $arrData[$index_loop]["NAMA_FILE"]= $set->getField("PEGAWAI_NIP").".xlsx";

  $FILE_DIR= $arrData[$index_loop]["NAMA_FOLDER"];
  // makedirs($FILE_DIR, 0777);
  makedirs($FILE_DIR, 0755);

  $arrData[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
  $arrData[$index_loop]["JADWAL_ASESOR_ID"]= $set->getField("JADWAL_ASESOR_ID");
  $arrData[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
  $arrData[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
  $arrData[$index_loop]["FORM_ATRIBUT_ID"]= $set->getField("FORM_ATRIBUT_ID");
  $arrData[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
  $arrData[$index_loop]["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
  $arrData[$index_loop]["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
  $arrData[$index_loop]["NAMA_FORMULA"]= $set->getField("NAMA_FORMULA");
  $arrData[$index_loop]["PEGAWAI_NAMA"]= $set->getField("PEGAWAI_NAMA");
  $arrData[$index_loop]["PEGAWAI_NIP"]= $set->getField("PEGAWAI_NIP");
  $arrData[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set->getField("PEGAWAI_JAB_STRUKTURAL");
  $arrData[$index_loop]["JT_TANGGAL_TES"]= $set->getField("JT_TANGGAL_TES");
  $arrData[$index_loop]["PUKUL1"]= $set->getField("PUKUL1");
  $arrData[$index_loop]["PUKUL2"]= $set->getField("PUKUL2");
  $arrData[$index_loop]["ASESOR_NAMA"]= $set->getField("ASESOR_NAMA");
  $arrData[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrData[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
  $arrData[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
  $arrData[$index_loop]["JUMLAH_LEVEL"]= $set->getField("JUMLAH_LEVEL");
  $index_loop++;
}
// print_r($arrData);exit;
$jumlahdata= $index_loop;

$indexZip=0;
$rowdetil= 13;
$checkdata= $checkdetildata= "";
for($index_loop= 0; $index_loop < $jumlahdata; $index_loop++)
{
	$datacheck= $arrData[$index_loop]["PEGAWAI_ID"];
	$datadetilcheck= $datacheck."-".$arrData[$index_loop]["ATRIBUT_ID"];

	// kalau tidak sama maka buat object data create export untuk header
	if($checkdata !== $datacheck)
	{
		// kalau data awal tidak perlu di unset object
		if($index_loop > 0)
		{
			// $outputFileName= '../template/tipeujian/hasil.xlsx';
			$outputFileName= $infofileoutput;
			// echo $outputFileName."<br/>";


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->setIncludeCharts(TRUE);
			$objWriter->save($outputFileName);

			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);

			$files_to_zip[$indexZip]= $outputFileName;
			$indexZip++;
		}

		// set nama link hasil
		$infofileoutput= $arrData[$index_loop]["NAMA_FOLDER"]."/".$arrData[$index_loop]["NAMA_FILE"];

		$inputFileType = 'Excel2007';
		$inputFileName = '../template/ikk/form_observasi.xlsx';

		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		// $objReader->setIncludeCharts(TRUE);
		$objPHPExcel = $objReader->load($inputFileName);

		$objWorksheet = $objPHPExcel->getActiveSheet();

		$colheader= 3;
		$rowheader= 3;
		$objWorksheet->setCellValue(toAlpha($colheader).$rowheader, $arrData[$index_loop]["PENGGALIAN_NAMA"]." (".$arrData[$index_loop]["PENGGALIAN_KODE"].")");$rowheader++;
		$objWorksheet->setCellValue(toAlpha($colheader).$rowheader, $arrData[$index_loop]["NAMA_FORMULA"]);$rowheader++;
		$objWorksheet->setCellValue(toAlpha($colheader).$rowheader, $arrData[$index_loop]["PEGAWAI_NAMA"]);$rowheader++;
		$objWorksheet->setCellValue(toAlpha($colheader).$rowheader, $arrData[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]);$rowheader++;
		$objWorksheet->setCellValue(toAlpha($colheader).$rowheader, getFormattedDate($arrData[$index_loop]["JT_TANGGAL_TES"])." ".$arrData[$index_loop]["PUKUL1"]." - ".$arrData[$index_loop]["PUKUL2"]);$rowheader++;
		$objWorksheet->setCellValue(toAlpha($colheader).$rowheader, $arrData[$index_loop]["ASESOR_NAMA"]);$rowheader++;

		$nomoratribut= 1;
	}

	// kalau tidak sama maka atribut detil
	if($checkdetildata !== $datadetilcheck)
	{
		$coldetil= 0;
		$objWorksheet->setCellValue(toAlpha($coldetil).$rowdetil, $nomoratribut);$coldetil++;
		$objWorksheet->setCellValue(toAlpha($coldetil).$rowdetil, $arrData[$index_loop]["ATRIBUT_NAMA"]);$coldetil++;
		$nomoratribut++;
		$rowdetil++;

		$nomorindikator= 0;
		$coldetil= 1;
		$objWorksheet->setCellValue(toAlpha($coldetil).$rowdetil, strtolower(toAlpha($nomorindikator)).". ".$arrData[$index_loop]["NAMA_INDIKATOR"]);$coldetil++;
		$rowdetil++;
		$nomorindikator++;
	}
	else
	{
		$coldetil= 1;
		$objWorksheet->setCellValue(toAlpha($coldetil).$rowdetil, strtolower(toAlpha($nomorindikator)).". ".$arrData[$index_loop]["NAMA_INDIKATOR"]);$coldetil++;
		$rowdetil++;
		$nomorindikator++;
	}

	$checkdetildata= $datadetilcheck;
	$checkdata= $datacheck;
}

// kalau data awal tidak perlu di unset object
if($index_loop > 0)
{
	$outputFileName= $infofileoutput;
	// echo $outputFileName."<br/>";

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->setIncludeCharts(TRUE);
	$objWriter->save($outputFileName);

	$objPHPExcel->disconnectWorksheets();
	unset($objPHPExcel);

	$files_to_zip[$indexZip]= $outputFileName;
	$indexZip++;
	// print_r($files_to_zip);exit;

	if($indexZip > 0)
	{
		$setLokasiZip= $arrData[0]["NAMA_ZIP"];
		// echo $setLokasiZip;exit;

		$foldersimpan= "../dokumenfile/".$infogeneratefile;
		// echo $foldersimpan;exit;

		if(file_exists($setLokasiZip))
		{
			unlink($setLokasiZip);
		}
		//if true, good; if false, zip creation failed
		$result = create_zip($files_to_zip,$setLokasiZip);

		ob_clean();
		ob_end_flush(); // more important function - (without - error corrupted zip)

		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header('Content-Type: application/zip;\n');
		header("Content-Transfer-Encoding: Binary");
		header("Content-Disposition: attachment; filename=\"".basename($setLokasiZip)."\"");

		if(readfile($setLokasiZip))
		{
			unlink($setLokasiZip);
			deleteNonEmptyDir($foldersimpan);
			// exit();
		}
	}

}

?>
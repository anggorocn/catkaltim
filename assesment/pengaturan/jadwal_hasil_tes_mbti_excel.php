<?
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/RekapSehat.php");
require '../WEB/lib/phpexcelchart/PHPExcel/IOFactory.php';

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqPegawaiId= httpFilterGet("reqRowId");

$inputFileType = 'Excel2007';
$inputFileName = '../template/tipeujian/mbti.xlsx';

$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setIncludeCharts(TRUE);
$objPHPExcel = $objReader->load($inputFileName);

$objWorksheet = $objPHPExcel->getActiveSheet();

$statement= " AND B.JADWAL_TES_ID = ".$reqId." AND B.PEGAWAI_ID = ".$reqPegawaiId;
$set = new RekapSehat();
$set->selectByParamsInfoPegawai(array(), -1, -1, $statement);
$set->firstRow();
$infopegawainama= $set->getField("NAMA_PEGAWAI");
$infopegawainip= $set->getField("NIP_BARU");
$infopegawaiumur= $set->getField("PEGAWAI_UMUR_NORMA");
$infopegawaijeniskelamin= $set->getField("JENIS_KELAMIN");
$infopegawaitanggalujian= $set->getField("TANGGAL_UJIAN");
unset($set);

$set = new RekapSehat();
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set->selectByParamsMonitoringMbtiNew(array(), -1,-1, $reqId, $statement, $sOrder);
$set->firstRow();
// echo $set->query;exit;NOMOR_URUT_GENERATE
$infopegawaino= $set->getField("NOMOR_URUT_GENERATE");
$infojenis= $set->getField("KONVERSI_INFO"); 
// echo $infojenis; exit;
unset($set);

$set = new RekapSehat(); 
$infohasil= $set->selectByParamsMBTI_Deskripsi(array(), $infojenis, $statement);
// $set->firstRow();
// echo $set->query;exit;
// $infohasil= $set->getField("ROWCOUNT"); 
// echo $infohasil; exit;
unset($set);

$arrpegawaidata= array($infopegawaino,$infopegawainip, $infopegawainama, $infopegawaitanggalujian, $infojenis, $infohasil);
// print_r($arrpegawaidata);exit();
$rowdatacolom= 2;
$rowdatarow=3;
for($x=0; $x<count($arrpegawaidata);$x++)
{
	$infokonversihasil= str_replace("<br>", "\n", $arrpegawaidata[$x]);
	$objWorksheet->setCellValue(toAlpha($rowdatacolom).$rowdatarow, $infokonversihasil);
	$objWorksheet->getStyle(toAlpha($rowdatacolom).$rowdatarow)->getAlignment()->setWrapText(true);
	$rowdatarow++;
}
   

$outputFileName= '../template/tipeujian/'.$infopegawainip.'-'.$infopegawainama.'-'.$infopegawaitanggalujian.'-'.'mbti.xlsx';

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save($outputFileName);

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename='.basename($outputFileName));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($outputFileName));
ob_clean(); flush();
readfile($outputFileName);
unlink($outputFileName);
exit;
?>

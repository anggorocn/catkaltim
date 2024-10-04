<?php 
// require the PHPExcel file 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

/* create objects */
$kelautan = new Kelautan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

/* VARIABLE */
$reqId = httpFilterGet("reqId");
$reqRuanganId = httpFilterGet("reqRuanganId");
$reqBulan = httpFilterGet("reqBulan");
$reqTahun = httpFilterGet("reqTahun");
$reqStatusPegawai= httpFilterGet("reqStatusPegawai");

$objPHPexcel = PHPExcel_IOFactory::load('../template/ikk/form_akhir.xlsx');
$styleArrayFontBold = array(
	'font' => array(
	  'bold' => FALSE
	),
);

$objWorksheet = $objPHPexcel->getActiveSheet();

$allRecord = $kelautan->getCountByParamsPegawaiCetakExcel(array(), $statement);
$kelautan->selectByParamsPegawaiCetakExcel(array());


$tempRowAwal = 5;
$row = 6;

if($allRecord > 1)
{
	$objWorksheet->insertNewRowBefore($row, $allRecord-1);
}
elseif($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($row, $allRecord);
}
elseif($allRecord == 0)
{
	$col = 'B';	$objWorksheet->setCellValue($col.$row,'-'); //$objWorksheet->mergeCells('A'.$row.':K'.$row.'');
}

$z=1;
while($kelautan->nextRow()){
	$col = 'A';	$objWorksheet->setCellValue($col.$row,$z);
	$col = 'B';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('nama_pegawai'));
	$col = 'C';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('kecerdasan_umum')); 
	$col = 'D';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('sistematika_kerja'));
	$col = 'E';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('motivasi_kerja'));
	$col = 'F';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('komitmen_kerja'));
	$col = 'G';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('peran_kepemimpinan'));
	$col = 'H';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('kerjasama'));
	$col = 'I';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('stabilitas_emosi'));
	$col = 'J';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('fungsi_kognisi'));
	$col = 'K';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('peran_sosial'));
	$col = 'L';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('monitoring_evaluasi_kebijakan'));	
	$col = 'M';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('inovasi'));	
	$col = 'N';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('berpikir_konseptual2'));	
	$col = 'O';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('adaptasi_terhadap_perubahan'));	
	$col = 'P';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('integritas'));	
	$col = 'Q';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('komitmen_terhadap_organisasi'));	
	$col = 'R';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('kepemimpinan'));	
	$col = 'S';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('membangun_networking'));	
	$col = 'T';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('negosiasi'));	
	$col = 'U';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('pengambilan_keputusan'));		
	$col = 'W';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('berorientasi_kualitas'));			
	$col = 'X';	$objWorksheet->setCellValue($col.$row,$kelautan->getField('manajemen_konflik'));				
	
	
		
	$row++; $z++;
}

if($allRecord > 1)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
}
elseif($allRecord > 0)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
	$objWorksheet->removeRow($tempRowAwal+1, 1);
	$objWorksheet->removeRow($tempRowAwal+2, 1);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/ikk/form_akhir.xls');

$down = '../template/ikk/form_akhir.xls';
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.basename($down));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
ob_clean();
flush();
readfile($down);
unlink($down);
unlink($save);
unset($oPrinter);
exit;
		
exit();
?>
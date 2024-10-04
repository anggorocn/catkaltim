<?php 
// require the PHPExcel file 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* create objects */
$set= new Kelautan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$objPHPexcel = PHPExcel_IOFactory::load('../template/silat/diklat_analisa_kompetensi_excel.xlsx');
$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

$objWorksheet = $objPHPexcel->getActiveSheet();

/* VARIABLE */
$reqTrainingId= httpFilterGet("reqTrainingId");
$reqAtributId= httpFilterGet("reqAtributId");
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId = httpFilterRequest("reqId");
$reqCari = httpFilterRequest("reqCari");
$reqSearch = httpFilterGet("reqSearch");

if($reqId == "")
	$statement='';
else
	//$statement .= " AND D.ID_TREE LIKE '".$reqId."%' ";
	$statement .= " AND S.KODE_UNKER LIKE '%".$reqId."'";

if($reqAtributId == "")
	$statement .='';
else
	$statement .= " AND D1.ATRIBUT_ID = '".$reqAtributId."' ";

if($reqTrainingId == "")
	$statement .='';
else
	$statement .= " AND F1.TRAINING_ID = '".$reqTrainingId."' ";

$field = array("NO", "NIP_LAMA", "NIP_BARU", "NAMA", "NAMA_GOL", "NAMA_JAB_STRUKTURAL", "SATKER");

$allRecord= $set->getCountByParamsMonitoringAnalisaPegawai(array(), $statement);
$set->selectByParamsMonitoringAnalisaPegawai(array(), -1, -1, $statement);

$row = 4;

if($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($row, $allRecord);
}
elseif($allRecord == 0)
{
	$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':F'.$row.'');
	$i++;
}

$objWorksheet->freezePane('A3');

$set_detil= new Kelautan();
$set_detil->selectByParamsMonitoringAnalisaPegawai(array("D1.ATRIBUT_ID"=>$reqAtributId, "F1.TRAINING_ID"=>$reqTrainingId),-1,-1);
$set_detil->firstRow();
$tempNama= $set_detil->getField("ATRIBUT_NAMA").", Training: ".$set_detil->getField("TRAINING_NAMA");
unset($set_detil);

$objWorksheet->setCellValueExplicit("A1","Laporan ".$tempNama, PHPExcel_Cell_DataType::TYPE_STRING);

$nomor=1;  	
while($set->nextRow())
{
	for($i=0; $i<count($field); $i++)
	{
		$kolom= getColoms($i+1);
		
		if($field[$i] == "NO")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "NO_ORDER")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
			$objWorksheet->getStyle($kolom.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
		}
		elseif($field[$i] == "TANGGAL_KIRIM" || $field[$i] == "TANGGAL_ORDER" || $field[$i] == "AMBIL_TANGGAL")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,dateToPageCheck($set->getField($field[$i])), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "HP_ORDER")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,setTelepon(array($set->getField("TELP_ORDER"), $set->getField("HP_ORDER"))), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "TAGIHAN" || $field[$i] == "TERBAYAR" || $field[$i] == "KEKURANGAN")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,numberToIna($set->getField($field[$i])), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		else
		{
			$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
		}	
	}
	$nomor++;
	$row++;
}
	
$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/silat/diklat_analisa_kompetensi_excel.xls');

$down = '../template/silat/diklat_analisa_kompetensi_excel.xls';
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
<?php 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqTahun= httpFilterGet("reqTahun");
$reqEselonId= httpFilterGet("reqEselonId");
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqTipe= httpFilterGet("reqTipe");
$reqId = httpFilterRequest("reqId");
$reqCari = httpFilterRequest("reqCari");
$reqSearch = httpFilterGet("reqSearch");
$reqStatusPeg= httpFilterGet("reqStatusPeg");
$reqSearch= httpFilterGet("reqSearch");

if($reqStatusPeg == "")
	$statement.="";
else
	$statement .= " AND A.STATUS_PEG = '".$reqStatusPeg."' ";

if($reqEselonId == "")
	$statement.="";
else
	$statement .= " AND A.KODE_ESELON = '".$reqEselonId."' ";

if($reqTipe == "1" || $reqTipe == "")
{
	if($reqTahun == "")
	$statement.="";
	else
	{
		$statement .= " AND (SELECT ".$reqTahun." - YEAR(A.TGL_LAHIR)) >= 58";
	}
}
elseif($reqTipe == "2")
{
	if($reqTahun == "")
	$statement.="";
	else
	{
		$statement .= " AND (SELECT ".$reqTahun." - YEAR(A.TGL_LAHIR)) >= 98";
	}
}

$statement.=" AND A.KODE_ESELON NOT IN (99, 88) ";
$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_LAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";

$objPHPexcel = PHPExcel_IOFactory::load('../template/suksesi/laporan_rekap_monitoring.xlsx');
$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

$objWorksheet = $objPHPexcel->getActiveSheet();

$tempRowAwal= $row = 2;
//$objWorksheet->freezePane('A3');

$field = array("NO", "NIP_LAMA", "NIP_BARU", "NAMA", "TEMPAT_LAHIR", "TGL_LAHIR", "STATUS", "NAMA_GOL", "TMT_GOL_AKHIR", "NAMA_ESELON", "NAMA_JAB_STRUKTURAL", "", "ALAMAT", "SATKER");

$set= new Kelautan();
$allRecord= $set->getCountByParamsMonitoringPegawai(array(), $statement.$searchJson);

$sOrder = " ORDER BY coalesce(C.KODE_ESELON,99) ASC, B.KODE_GOL DESC";
//echo $allRecord;exit;
$set->selectByParamsMonitoringPegawai(array(), -1, -1, $statement.$searchJson, $sOrder);
//echo $set->query;exit;
//echo $allRecord;exit;
if($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($row, $allRecord);
	//$objWorksheet->duplicateStyle($objWorksheet->g‌etStyle('A'.$row),'A'.$row.':A'.$allRecord-1);
}
elseif($allRecord == 0)
{
	$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':AD'.$row.'');
	$i++;
}

//echo $set->query;exit;
$nomor=1;

while($set->nextRow())
{
	//$type = PHPExcel_Cell_DataType::TYPE_STRING;
	//$sheet->getCellByColumnAndRow($column, $rowno)->setValueExplicit($value, $type);

	for($i=0; $i<count($field); $i++)
	{
		$kolom= getColoms($i+1);
		if($field[$i] == "NO")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "NIP_LAMA")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "TANGGAL_KIRIM" || $field[$i] == "LAST_PROSES_DATE" || $field[$i] == "LAST_CREATE_DATE")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,getFormattedDateTime($set->getField($field[$i])), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "TGL_LAHIR" || $field[$i] == "TMT_GOL_AKHIR")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,getFormattedDate($set->getField($field[$i])), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		else
		{
			$tempValue= str_replace("<br/>", "\n", $set->getField($field[$i]));
			//$tempValue= str_replace("&nbsp;", " ", $set->getField($field[$i]));

			$objWorksheet->setCellValue($kolom.$row,$tempValue);
		}
	}
	
	$nomor++;
	$row++;
}

if($allRecord == 1)
{
$objWorksheet->removeRow($row, 1);
$tempRowAkhir= $row-1;
}
elseif($allRecord > 1)
{
$objWorksheet->removeRow($row, 1);
$tempRowAkhir= $row-1;
$tempRowAkhir= $row-1;
}

if($allRecord > 0)
{
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/suksesi/laporan_rekap_monitoring.xls');

$down = '../template/suksesi/laporan_rekap_monitoring.xls';
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
//unlink($save);
unset($oPrinter);
exit;
		
exit();
?>
?>
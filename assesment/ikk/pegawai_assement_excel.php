<?php 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

/* create objects */
$penilaian = new Kelautan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqTahun= httpFilterGet("reqTahun");
$reqId= httpFilterGet("reqId");

// $objPHPexcel = PHPExcel_IOFactory::load('../template/ikk/general_view.xlsx');

//$newSheet = clone $objPHPexcel->getSheetByName("sheet_template");
//$newSheet->setTitle('New Sheet');
//$newSheetIndex = 1;
//$objPHPexcel->addSheet($newSheet,$newSheetIndex);

$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

$statement .= setAndKondisi($reqId, "A.KODE_UNKER");
$statement1 .= setAndKondisi($reqId, "SATKER_TES_ID");

//$statement= " AND A.KODE_UNKER = '0413309900'";
$field = array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "SATKER", "JPM", "IKK");
$sOrder = " ORDER BY ASPEK_ID ASC, JPM DESC, IKK DESC ";
$penilaian->selectByParamsMonitoringPenilaianCetak(array(), -1, -1, $statement, $statement1, $sOrder, $reqTahun);
//echo $penilaian->query;exit;

$index_data_array= 0;
$nomor=1;
$kodeTemp= $kodeRekeningTemp= "";
$indexKegiatan=$indexKodeRekening=0;
while($penilaian->nextRow())
{	
	// buat kegiatan
	for($i=0; $i<count($field); $i++)
	{
		$arrDataArray[$index_data_array][$field[$i]] = $penilaian->getField($field[$i]);
	}
	
	$arrDataArray[$index_data_array]["ASPEK_ID"] = $penilaian->getField("ASPEK_ID");
	$arrDataArray[$index_data_array]["JUMLAH_ASPEK1"] = $penilaian->getField("JUMLAH_ASPEK1");
	$arrDataArray[$index_data_array]["JUMLAH_ASPEK2"] = $penilaian->getField("JUMLAH_ASPEK2");

	$index_data_array++;
	$nomor++;
}
//print_r($arrDataArray);exit;
//echo $penilaian->query;exit;
//echo $allRecord;exit;

$sheetIndex= 0;
for($checkbox_index=0;$checkbox_index<$index_data_array;$checkbox_index++)
{
	if($tempKelasInfo == $arrDataArray[$checkbox_index]["ASPEK_ID"]){}
	else
	{
		if($arrDataArray[$checkbox_index]["ASPEK_ID"] == "1")
			$tempIndexRecord= $arrDataArray[$checkbox_index]["JUMLAH_ASPEK1"];
		else
			$tempIndexRecord= $arrDataArray[$checkbox_index]["JUMLAH_ASPEK2"];
	
		// set sheet
		$objPHPexcel->setActiveSheetIndex($sheetIndex);
		$objWorksheet = $objPHPexcel->getActiveSheet();
		
		$tempRowAwal= $row = 3;
		$objWorksheet->freezePane('A2');
		
		$allRecord= $tempIndexRecord;
		if($allRecord > 0)
		{
			$objWorksheet->insertNewRowBefore($row, $allRecord);
		}
		
		$nomor=1;
		/*elseif($allRecord == 0)
		{
			$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':F'.$row.'');
			$i++;
		}
		
		$nomor=1;
		
		$tempInfoPeriode= generateNamaTribulan($reqBulan)." ".$reqTahun;
		$objWorksheet->setCellValue("G4", $tempInfoPeriode);
		
		if($allRecord > 0)
		{
			$fieldKoordinator= array("WILAYAH", "KOORDINATOR", "ALAMAT_KOORDINATOR", "JUMLAH");
			$i_header=0;
			for($tempRow=6; $tempRow<10; $tempRow++)
			{
				if($fieldKoordinator[$i_header] == "JUMLAH")
					$objWorksheet->setCellValue("E".$tempRow, ": ".$tempIndexRecord." Anak");
				else
					$objWorksheet->setCellValue("E".$tempRow, ": ".$arrDataArray[0][$fieldKoordinator[$i_header]]);
				
				$i_header++;
			}
		}*/
		$sheetIndex++;
		//end sheet;
	}
	
	//set info data
	for($i_data=0; $i_data<count($field); $i_data++)
	{
		$kolom= getColoms($i_data+2);
		
		if($i_data == 0)
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($i_data == 1)
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$arrDataArray[$checkbox_index][$field[$i_data]], PHPExcel_Cell_DataType::TYPE_STRING);
			$objWorksheet->getStyle($kolom.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
		}
		elseif($i_data == 4)
		{
			$objWorksheet->setCellValue($kolom.$row,$arrDataArray[$checkbox_index][$field[$i_data]]);
		}
		else
		{
			$objWorksheet->setCellValue($kolom.$row,$arrDataArray[$checkbox_index][$field[$i_data]]);
		}	
	}
	
	$nomor++;
	$row++;
	
	/*if($tempKelasInfo == $arrDataArray[$checkbox_index]["ASPEK_ID"]){}
	else
	{
		//$S->setCellValue("J$row", "=SUM(J5:J".($row-1).")");
		$objWorksheet->removeRow($row, 1);
		$objWorksheet->setCellValue("A".$row,"asdsad".$row);
	}*/
	
	$tempKelasInfo= $arrDataArray[$checkbox_index]["ASPEK_ID"];
}
	
	
$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/ikk/general_view.xls');

$down = '../template/ikk/general_view.xls';
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
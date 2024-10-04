<?php
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base-ikk/CetakRekap.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php"); 
include_once("../WEB/classes/base/FormulaAssesment.php");


$reqJadwalTesId = httpFilterGet("reqJadwalTesId");

$statementeselon = " AND A.FORMULA_ID =".$reqJadwalTesId;

$set_eselon= new FormulaAssesment();
$set_eselon->selectByParams(array(), -1,-1, $statementeselon, "ORDER BY A.TAHUN DESC");
$set_eselon->firstRow();
$formula = $set_eselon->getField("FORMULA");
// echo $formula;exit;
 // echo $set_eselon->query;exit;


$set = new CetakRekap();
		// if(isset($reqPage))
		// {

$objPHPexcel = PHPExcel_IOFactory::load('../../Templates/cetak_rekap.xlsx');
$BStyle = array(
	'borders' => array(
		'allborders' => array(

			'style' => PHPExcel_Style_Border::BORDER_THIN
		)				
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
	)
);

// $styleNama = array(
// 	'borders' => array(
// 		'allborders' => array(

// 			'style' => PHPExcel_Style_Border::BORDER_THIN
// 		)				
// 	)			
// );


// $styleWrap = array(
// 	'alignment' => array(
// 		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
// 	)
// );

$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$objWorksheet= $objPHPexcel->getActiveSheet();
$objWorksheet->SetCellValue(B14,$formula);


$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('test_img');
$objDrawing->setDescription('test_img');
$objDrawing->setPath('../WEB/images/logokaltim.png');
$objDrawing->setCoordinates('G1');                      
//setOffsetX works properly
$objDrawing->setOffsetX(5); 
$objDrawing->setOffsetY(5);                
//set width, height
$objDrawing->setWidth(145); 
$objDrawing->setHeight(145); 
$objDrawing->setWorksheet($objWorksheet= $objPHPexcel->getActiveSheet());

$statementformula = " AND B.FORMULA_ID =".$reqJadwalTesId;
$search = " AND (UPPER(B.NAMA) LIKE '%%' OR UPPER(CAST(B.PEGAWAI_ID AS TEXT)) LIKE '%%' ) ";
$sOrder=" ORDER BY  JPM_TOTAL DESC, A.ID_KUADRAN DESC";

$set->selectByParams(array(), -1, -1, $statementformula.$search,$sOrder);

//echo $set->query;exit;


$row = 20;
$tempRowAwal= 1;
$field= "";
$field= array("NO","NAMA","NIP_BARU","NAMA_JAB_STRUKTURAL","NILAI_SKP","PSIKOLOGI_JPM", "KOMPETEN_JPM","JPM_TOTAL","KATEGORI","KODE_KUADRAN","NAMA_KUADRAN","REKOMENDASI","SARAN_PENEMPATAN","SARAN_PENGEMBANG");

$nomor=1;
$tempTotal= 0;
while($set->nextRow())
{
	$index_kolom= 1;
	for($i=0; $i<count($field); $i++)
	{
		$kolom= getColoms($index_kolom);
		if($field[$i] == "NO")
		{
			$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyle);

			$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		else if ($field[$i] == "NIP_BARU")
		{
			$objWorksheet->getStyle($kolom.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyle);
			$objWorksheet->setCellValueExplicit($kolom.$row,$set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
		}
		else if ($field[$i] == "NAMA")
		{
			$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyle);
			$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
		}
		else if ($field[$i] == "SARAN_PENGEMBANG" || $field[$i] == "LEMAH" || $field[$i] == "REKOMENDASI" || $field[$i] == "SARAN_PENEMPATAN")
		{
			// $tempValue= str_replace("</br>","\n",$set->getField($field[$i]));
			$tempValue= str_replace("&nbsp;", " ", str_replace("gantibaris","\n", $set->getField($field[$i])));
			// $tempValue= str_replace("a","aaa","jumlah");
			// print_r($tempValue);
			
			$objWorksheet->getStyle($kolom.$row)->getAlignment()->setWrapText(true);
			// $objWorksheet->getStyle($kolom.$row.$objWorksheet->getHighestRow())->getAlignment()->setWrapText(true);
			$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyle);  
			$objWorksheet->setCellValue($kolom.$row,$tempValue);
		}
		else
		{
			$objWorksheet->getStyle($kolom.$row)->applyFromArray($BStyle);
			$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
		}
		$index_kolom++;
	}
	$nomor++;
	$row++;
 // print_r($kolom);
}
// exit;


$index_kolom++;

$rowplus = $row+3;
$rowminus = $rowplus+1;

$objWorksheet->setCellValue("M".$rowplus, "sAMARINDA,           2021");
$objWorksheet->setCellValue("M".$rowminus, "KETUA PENYELENGGARA");
// $objWorksheet->mergeCells("A".$row.":B".$row.'');

// $objWorksheet->getStyle("A".$row.":N".$row)->applyFromArray($BStyle);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel2007');
$objWriter->save('../../Templates/download/cetak_rekap_download.xlsx');

$down = '../../Templates/download/cetak_rekap_download.xlsx';
$filename= 'cetak_rekap_download.xlsx';
ob_end_clean();
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename='.$filename);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, get-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
ob_end_clean();
readfile($down);
exit();
?>
<?
?>
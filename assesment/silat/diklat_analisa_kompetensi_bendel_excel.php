<?php 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-intranet_sunnah/RekapSunnahDetil.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

/* create objects */
$set = new RekapSunnahDetil();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");

$objPHPexcel = PHPExcel_IOFactory::load('../template/silat/diklat_analisa_kompetensi_bendel_excel.xlsx');

$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

$tempAnggotaKelompok= $userLogin->userAnggotaKelompok;

$reqId= httpFilterRequest("reqId");
$reqKelompokId= httpFilterRequest("reqKelompokId");

$reqTanggalAwalFilter= httpFilterGet("reqTanggalAwalFilter");
$reqTanggalAkhirFilter= httpFilterGet("reqTanggalAkhirFilter");

$sheetIndex=0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$objWorksheet = $objPHPexcel->getActiveSheet();

//$field= array("KELOMPOK_NAMA", "NAMA_ANGGOTA");
$field= array("NAMA_ANGGOTA");

$reqTanggalAwalFilter= dateToPageCheck($reqTanggalAwalFilter);
$reqTanggalAkhirFilter= dateToPageCheck($reqTanggalAkhirFilter);

$tempTanggalAwal= $reqTanggalAwalFilter;
$tempTanggalAkhir= $reqTanggalAkhirFilter;

$style['styleHeader'] = array(
	'font' => array(
		'size'  => 28,
        'name'  => 'Times New Roman'
	),
	'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);

$row= 4;
$indexColom=1;
$colIndex=1;
$tempAwal=$tempIndexAwal= date("Y-m-d",strtotime($tempTanggalAwal));
$tempAkhir= date("Y-m-d",strtotime($tempTanggalAkhir));
while (strtotime($tempAwal) <= strtotime($tempAkhir))
{
	$tempHari= getDay($tempAwal);
	$field[$colIndex]= "JUMLAH_SUNNAH".$indexColom;
	
	$kolom= getColoms($indexColom+2);
	$objWorksheet->setCellValue($kolom.$row,$tempHari);
	$objWorksheet->getStyle($kolom.$row)->applyFromArray($style['styleHeader']);
	
	//echo $tempAwal."--";
	$indexColom++;
	$colIndex++;
	$tempAwal= date ("Y-m-d", strtotime("+1 day", strtotime($tempAwal)));
}

$kolom= getColoms($indexColom+2);
$objWorksheet->setCellValue($kolom.$row,"Total");
$objWorksheet->getStyle($kolom.$row)->applyFromArray($style['styleHeader']);
$field[$colIndex]= "TOTAL";$colIndex++;$indexColom++;

$kolom= getColoms($indexColom+2);
$objWorksheet->setCellValue($kolom.$row,"Rata-rata");
$objWorksheet->getStyle($kolom.$row)->applyFromArray($style['styleHeader']);
$field[$colIndex]= "RATA_RATA";$colIndex++;

//print_r($field);exit;

if($reqId == ""){}
else
$statement= " AND A.ANGGOTA_ID = ".$reqId;

if($reqKelompokId == "")
	$statement.= " AND C.KELOMPOK_ID = ".$tempAnggotaKelompok;
elseif($reqKelompokId == "xxx"){}
else
	$statement.= " AND C.KELOMPOK_ID = ".$reqKelompokId;

//$statement="AND B.KELOMPOK_ID IN (23,22)";
//$statement="AND B.KELOMPOK_ID IN (23)";
$statement="";

//$set->selectByParamsSunnahTanggalNilai(array(), -1, -1, $statement, $reqTanggalAwalFilter, $reqTanggalAkhirFilter, " ORDER BY C.NAMA, B.KETUA DESC, A.NAMA");
//echo $set->query;exit;

$index_data_array= 0;
$nomor=1;
$kodeTemp= $kodeRekeningTemp= "";
$indexKegiatan=$indexKodeRekening=0;
$jumlah_anggota=0;
$jumlah_anggota_footer= "";
$tempKelompokNama="";
/*while($set->nextRow())
{	
	// buat kegiatan
	for($i=0; $i<count($field); $i++)
	{
		$arrDataArray[$index_data_array][$field[$i]] = $set->getField($field[$i]);
	}
	
	$arrDataArray[$index_data_array]["KELOMPOK_NAMA"] = $set->getField("KELOMPOK_NAMA");
	$arrDataArray[$index_data_array]["JUMLAH_ANGGOTA"] = $set->getField("JUMLAH_ANGGOTA");
	
	$index_data_array++;
	$nomor++;
}*/
//print_r($arrDataArray);exit;
//echo $set->query;exit;
//echo $allRecord;exit;
//echo $index_data_array;exit;

$tempRowAwal= $row = 6;

/*$allRecord= $index_data_array;
if($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($row+1, $allRecord);
}
elseif($allRecord == 0)
{
	$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':F'.$row.'');
	$i++;
}

$kolom= getColoms($indexColom+2);
foreach(range('B',$kolom) as $columnID)
{
    $objWorksheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheetIndex= 0;
for($checkbox_index=0;$checkbox_index<$index_data_array;$checkbox_index++)
{
	if($tempKelompokNama == $arrDataArray[$checkbox_index]["KELOMPOK_NAMA"]){}
	else
	{
		$jumlah_anggota=0;
		$kolom= getColoms($indexColom+2);
		$objWorksheet->setCellValue("B".$row,$arrDataArray[$checkbox_index]["KELOMPOK_NAMA"]);
		$objWorksheet->mergeCells("B".$row.':'.$kolom.$row.'');
		$row++;
	}
	//set info data
	for($i_data=0; $i_data<count($field); $i_data++)
	{
		$kolom= getColoms($i_data+2);
		$objWorksheet->setCellValue($kolom.$row,$arrDataArray[$checkbox_index][$field[$i_data]]);
		$objWorksheet->getStyle($kolom.$row)->applyFromArray($style['styleHeader']);
	}
	
	$tempRata+= $arrDataArray[$checkbox_index]["RATA_RATA"];
		
	$jumlah_anggota_footer_check= $jumlah_anggota+1;
	if($arrDataArray[$checkbox_index]["JUMLAH_ANGGOTA"] == $jumlah_anggota_footer_check)
	{
		$row++;
		$tempJumlahAnggota= $indexColom+1;
		$tempTotalRata= $tempRata/$arrDataArray[$checkbox_index]["JUMLAH_ANGGOTA"];
		//$tempTotalRata= $arrDataArray[$checkbox_index]["JUMLAH_ANGGOTA"];
		//$tempTotalRata= $tempRata;
		
		$kolom= getColoms($indexColom+1);
		$objWorksheet->setCellValue("B".$row,"Total = (Jumlah Rata2 / Jumlah Anggota)");
		$objWorksheet->mergeCells("B".$row.':'.$kolom.$row.'');
		$objWorksheet->getStyle("B".$row)->applyFromArray($style['styleHeader']);
		
		$kolom= getColoms($indexColom+2);
		$objWorksheet->setCellValue($kolom.$row,round($tempTotalRata,2));
		$objWorksheet->getStyle($kolom.$row)->applyFromArray($style['styleHeader']);
		
		$jumlah_anggota_footer=1;
		$tempRata=0;
	}
	
	$jumlah_anggota++;
	$tempKelompokNama= $arrDataArray[$checkbox_index]["KELOMPOK_NAMA"];
	$nomor++;
	$row++;
}*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/silat/diklat_analisa_kompetensi_bendel_excel.xls');

$down = '../template/silat/diklat_analisa_kompetensi_bendel_excel.xls';
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
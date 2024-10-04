<?php 
require '../WEB/lib/Classes-1.8.0/PHPExcel.php'; 
//require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}


/* VARIABLE */
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId = httpFilterRequest("reqId");
$reqTahun = httpFilterRequest("reqTahun");
$reqSearch = httpFilterGet("reqSearch");
$reqKuadranId= httpFilterGet("reqKuadranId");
$reqStatusPeg= httpFilterGet("reqStatusPeg");
$reqSearch= httpFilterGet("reqSearch");

$objPHPexcel = PHPExcel_IOFactory::load('../template/ikk/tabel_nine_box_talent.xlsx');
$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

$objWorksheet = $objPHPexcel->getActiveSheet();


$tempRowAwal= $row = 7;
$rowRecord= $row+1;
$objWorksheet->freezePane('A7');
 
$report = new Kelautan();

//echo $allRecord;exit;
if($reqId == "" || $reqId == "1")
{
	$statement='';
	$statement_satker= "";
}
else
{
	//$statement .= " AND D.ID_TREE LIKE '".$reqId."%' ";
	//$statement .= " AND D.ID_TREE = '".$reqId."' ";
	$statement .= " AND S.KODE_UNKER LIKE '".$reqId."%'";
	$statement_satker= " AND GetAncestry(A.ID) = '".$reqId."'";
}

$satuan_kerja= new Kelautan();
$satuan_kerja->selectByParamsSatuanKerja(array(), -1, -1, $statement_satker);
$satuan_kerja->firstRow();
$tempNamaSatker= $satuan_kerja->getField("NAMA");
//echo $satuan_kerja->query;exit;
unset($satuan_kerja);

if($reqKuadranId == "")
	$statementArray= array();
else
	$statementArray= array("A.ID_KUADRAN"=>$reqKuadranId);

if($reqStatusPeg == "")
	$statement.="";
else
	$statement .= " AND A.STATUS_PEG = '".$reqStatusPeg."' ";

$report->selectByParamsMonitoringTableTalentPoolMonitoring($statementArray, -1, -1, $statement, "", $reqTahun, $searchJson);
//echo $report->query;exit;

$field= array("NO", "NAMA", "NAMA_JAB_STRUKTURAL", "KOMPETEN_IKK", "PSIKOLOGI_IKK", "IKK", "JPM_TOTAL", "IKK_TOTAL", "NILAI", "KODE_KUADRAN", "NAMA_KUADRAN");

//set Header
//$infoSatker= "LAPORAN TARGET DAN REALISASI KEGIATAN BELANJA LANGSUNG TAHUN ANGGARAN ".$userLogin->userTahun;
//$objWorksheet->setCellValue("A1",$infoSatker);

$infoSatker= $tempNamaSatker.", Tahun ".$reqTahun;
$objWorksheet->setCellValue("A2",$tempNama.$infoSatker);

//$infoBulan= "BULAN : ".getNameMonth($reqBulan)." ".$reqTahun;
//$objWorksheet->setCellValue("I5",$infoBulan);

$index_kegiatan_sub= 0;
$nomor=1;
$kodeTemp= $kodeRekeningTemp= "";
$indexKegiatan=$indexKodeRekening=0;
while($report->nextRow())
{	
	// buat kegiatan
	for($i=0; $i<count($field); $i++)
	{
		if($field[$i] == "NO")
		{
			$arrKegiatanSubPerencanaan[$index_kegiatan_sub]["NO"] = $nomor;
		}
		else
		{
			$arrKegiatanSubPerencanaan[$index_kegiatan_sub][$field[$i]] = $report->getField($field[$i]);
		}
	}
	$nomor++;
	$index_kegiatan_sub++;
}
//print_r($arrKegiatanSubPerencanaan);exit;
//tambah row kegiatan dan kode rekening
$allRecord= $index_kegiatan_sub;
if($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($rowRecord, $allRecord);
}
elseif($allRecord == 0)
{
	$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':J'.$row.'');
}
//$objWorksheet->insertNewRowBefore($row, $tempIndexNewRow);

$tempIndexRow="";
for($checkbox_index=0;$checkbox_index<$index_kegiatan_sub;$checkbox_index++)
{
	$kolomIndex=1;
	for($i=0; $i<count($field); $i++)
	{
		$kolom= getColoms($kolomIndex);
		
		$objWorksheet->setCellValue($kolom.$row,$arrKegiatanSubPerencanaan[$checkbox_index][$field[$i]]);
		
		$kolomIndex++;
	}
	
	$row++;
}
//echo $tempIndexRow;exit;

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

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/ikk/tabel_nine_box_talent.xls');

$down = '../template/ikk/tabel_nine_box_talent.xls';
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
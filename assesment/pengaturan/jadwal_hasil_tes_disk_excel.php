<?
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/RekapSehat.php");
require '../WEB/lib/phpexcelchart/PHPExcel/IOFactory.php';

/* LOGIN CHECK */
// if ($userLogin->checkUserLogin()) 
// { 
// 	$userLogin->retrieveUserInfo();
// }

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqPegawaiId= httpFilterGet("reqRowId");
// echo "asasas";exit;

$inputFileType = 'Excel2007';
$inputFileName = '../template/tipeujian/disk.xlsx';

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

$arrpegawaidata= array($infopegawainama, $infopegawaiumur, $infopegawaijeniskelamin, $infopegawaitanggalujian);
// print_r($arrpegawaidata);exit();
$rowdatacolom= 2;
$rowdatarow=4;
for($x=0; $x<count($arrpegawaidata);$x++)
{
	$objWorksheet->setCellValue(toAlpha($rowdatacolom).$rowdatarow, $arrpegawaidata[$x]);
	$rowdatarow++;
}

$arrdata= array("D", "I", "S", "C");
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set = new RekapSehat();
$set->selectByParamsMonitoringDisc(array(), -1, -1, $reqId, $statement);
$set->firstRow();
// echo $set->query;exit();
$valdata= array();
$indexdata=0;

$rowdatarow=10;
$rowdatanextrow=7;
for($x=1; $x<=3;$x++)
{
	$rowdatacolom= 3;
	$rowdatanextcolom= 53;
	for($y=0;$y<count($arrdata);$y++)
	{
		$modestatus= $arrdata[$y];
		$modestatuskondisi= $modestatus.$x;

		$field= $modestatus."_".$x;
		$nilai= $set->getField($field);
		// $valdata[$indexdata][$field]= $nilai;

		$statementdetil= " AND STATUS_AKTIF = 1 AND MODE_STATUS = '".$modestatuskondisi."' AND NILAI = ".$nilai;
		$setdetil= new RekapSehat();
		$hasil= $setdetil->setkonversidisk(array(), $statementdetil);
		// echo $setdetil->query;exit;
		unset($setdetil);
		$valdata[$indexdata][$field."_KONVERSI"]= $hasil;

		$objWorksheet->setCellValue(toAlpha($rowdatacolom).$rowdatarow, $nilai);
		$rowdatacolom++;

		// kalau data terakhir ambil data * dan x bukan 3
		if($y == count($arrdata) - 1 && $x < 3)
		{
			$nilai= $set->getField("X_".$x);
			$objWorksheet->setCellValue(toAlpha($rowdatacolom).$rowdatarow, $nilai);
		}

		$objWorksheet->setCellValue(toAlpha($rowdatanextcolom).$rowdatanextrow, $hasil);
		$rowdatanextcolom++;
	}
	$rowdatarow++;
	$rowdatanextrow= $rowdatanextrow + 2;
}
unset($set);
// print_r($valdata);exit();

$indexdata= 0;
$nkesimpulan= "";
for($x=1; $x<=3;$x++)
{
	$d= $valdata[0]["D_".$x."_KONVERSI"];
	$i= $valdata[0]["I_".$x."_KONVERSI"];
	$s= $valdata[0]["S_".$x."_KONVERSI"];
	$c= $valdata[0]["C_".$x."_KONVERSI"];

	$setdetil= new RekapSehat();
	$hasil= $setdetil->setnkesimpulandisk($d, $i, $s, $c);
	// echo $setdetil->query;exit();
	unset($setdetil);

	$nkesimpulan[$indexdata]= $hasil;
	$indexdata++;
}
// print_r($nkesimpulan);exit();

$infoketerangan= array(
	  array("kolomindex"=>12, "rowindex"=>6)
	  , array("kolomindex"=>21, "rowindex"=>6)
	  , array("kolomindex"=>12, "rowindex"=>21, "deskripsikolomindex"=>11, "deskripsirowindex"=>44, "jobkolomindex"=>11, "jobrowindex"=>59)
);
// print_r($infoketerangan);exit();
// echo toAlpha(12);exit();

for($x=0; $x < count($infoketerangan); $x++)
{
	$statementdetil= " AND A.LINE = ".$nkesimpulan[$x]." AND A.STATUS_AKTIF = 1";
	$setdetil= new RekapSehat();
	$setdetil->selectByParamsDiscKesimpulan(array(), -1,-1, $statementdetil);
	$setdetil->firstRow();
	$infokesimpulanjudul= $setdetil->getField("JUDUL");
	$infokesimpulanjuduldetil= $setdetil->getField("JUDUL_DETIL");
	$infokesimpulandeskripsi= $setdetil->getField("DESKRIPSI");
	$infokesimpulansaran= $setdetil->getField("SARAN");
	unset($setdetil);

	$colkesimpulan= $infoketerangan[$x]["kolomindex"];
	$rowkesimpulan= $infoketerangan[$x]["rowindex"];
	$objWorksheet->setCellValue(toAlpha($colkesimpulan).$rowkesimpulan, $infokesimpulanjudul);

	$rowkesimpulan++;
	$arrinfokesimpulanjuduldetil= explode("<br/>", $infokesimpulanjuduldetil);
	// print_r($arrinfokesimpulanjuduldetil);exit();
	$jumlahkesimpulan= count($arrinfokesimpulanjuduldetil);
	for($k=0; $k<$jumlahkesimpulan; $k++)
	{
		$objWorksheet->setCellValue(toAlpha($colkesimpulan).$rowkesimpulan, $arrinfokesimpulanjuduldetil[$k]);
		// echo toAlpha($colkesimpulan).$rowkesimpulan.";;".$arrinfokesimpulanjuduldetil[$k]."<br/>";
		$rowkesimpulan++;
	}

	if($x == 2)
	{
		$hasildeskripsi= $infokesimpulandeskripsi;
		$colkesimpulan= $infoketerangan[$x]["deskripsikolomindex"];
		$rowkesimpulan= $infoketerangan[$x]["deskripsirowindex"];
		// echo toAlpha($colkesimpulan).$rowkesimpulan.";;".$hasildeskripsi."<br/>";exit();
		$objWorksheet->setCellValue(toAlpha($colkesimpulan).$rowkesimpulan, $hasildeskripsi);

		$hasilsaran= $infokesimpulansaran;
		$colkesimpulan= $infoketerangan[$x]["jobkolomindex"];
		$rowkesimpulan= $infoketerangan[$x]["jobrowindex"];
		// echo toAlpha($colkesimpulan).$rowkesimpulan.";;".$hasilsaran."<br/>";exit();
		$objWorksheet->setCellValue(toAlpha($colkesimpulan).$rowkesimpulan, $hasilsaran);
	}
		
}
// exit();

$infodetiloutput= $infopegawainip.'-'.$infopegawainama.'-'.$infopegawaitanggalujian;
$infodetiloutput= str_replace(",", ".", $infodetiloutput);
$outputFileName= '../template/tipeujian/'.$infodetiloutput.'-'.'disc.xlsx';
// echo $outputFileName;exit;

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

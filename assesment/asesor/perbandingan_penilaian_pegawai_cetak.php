<?php 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/classes/base/JadwalPegawaiDetil.php");
include_once("../WEB/classes/base/JadwalPegawaiDetilKomentar.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$tempAsesorId= $userLogin->userAsesorId;

if($tempAsesorId == "")
{
	echo '<script language="javascript">';
	echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
	echo 'top.location.href = "../main/login.php";';
	echo '</script>';		
	exit;
}

$set= new Asesor();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
$set->firstRow();
$tempAsesorNama= $set->getField("NAMA");
unset($set);

$dateNow= date("d-m-Y");

$reqPenggalianId= httpFilterGet("reqPenggalianId");
$reqJadwalPegawaiId= httpFilterGet("reqJadwalPegawaiId");

$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId;
$set= new JadwalPegawai();
$set->selectByParamsJadwalPegawaiInfo(array(), -1,-1, $statement);
//echo $set->query;exit;
$set->firstRow();
$tempPegawaiInfoNama= $set->getField("NAMA_PEGAWAI");
$tempPegawaiInfoJabatan= $set->getField("JABATAN_INI_TES");
$tempPegawaiInfoSatuanKerja= $set->getField("SATUAN_KERJA_INI_TES");
$tempPegawaiInfoNamaAsesi= $set->getField("NAMA_ASESI");
$tempPegawaiInfoMetode= $set->getField("METODE");
$tempPegawaiInfoTanggalTes= $set->getField("TANGGAL_TES");
$tempPegawaiInfoStatusPenilaian= $set->getField("STATUS_PENILAIAN");
$tempPegawaiInfoJadwalTesId= $set->getField("JADWAL_TES_ID");
$tempPegawaiInfoJadwalAsesorId= $set->getField("JADWAL_ASESOR_ID");
$tempPegawaiInfoId= $set->getField("PEGAWAI_ID");
unset($set);

$index_loop= 0;
$arrDataAtribut="";
//$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$set= new JadwalPegawai();
$set->selectByParamsAsesorPenilaianAtribut(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrDataAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrDataAtribut[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
	$index_loop++;
}
$jumlah_pegawai_atribut= $index_loop;

/*$index_loop= 0;
$arrDataAsesor="";
//$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." AND B.ASESOR_ID NOT IN (".$tempAsesorId.")";
$statement= "  AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$statement= $statement." AND F.atribut_id in 
(
SELECT F.ATRIBUT_ID
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID  
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				 AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." 
				 GROUP BY F.ATRIBUT_ID, F.NAMA
)";
$set= new JadwalPegawai();
$set->selectByParamsAsesorJumlah(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesor[$index_loop]["ASESOR_ID"]= $set->getField("ASESOR_ID");
	$arrDataAsesor[$index_loop]["NAMA_ASESOR"]= $set->getField("NAMA_ASESOR");
	$index_loop++;
}
$jumlah_data_asesor= $index_loop;

$index_loop= 0;
$arrDataAsesorPenilaian="";
$statement= "   AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$statement= $statement." AND A.atribut_id in 
(
SELECT F.ATRIBUT_ID
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID  
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				 AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." 
				 GROUP BY F.ATRIBUT_ID, F.NAMA
)";
$set= new JadwalPegawai();
$set->selectByParamsAsesorPenilaianDetil(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesorPenilaian[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID");
	$index_loop++;
}
*/

$index_loop= 0;
$arrDataAsesorNilai="";
$statement= "   AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
 $statement= $statement." AND F.atribut_id in 
(
SELECT F.ATRIBUT_ID
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID  
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				 AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND C.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId." 
				 GROUP BY F.ATRIBUT_ID, F.NAMA
)";
$set= new JadwalPegawai();
$set->selectByParamsAsesorNilai(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataAsesorNilai[$index_loop]["ID"]= $set->getField("ATRIBUT_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("KODE");
	$arrDataAsesorNilai[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
	$arrDataAsesorNilai[$index_loop]["NILAI_PEMBULATAN"]= $set->getField("NILAI_PEMBULATAN");
	$index_loop++;
}

/*
$index_loop= 0;
$arrDataPegawaiKomentar="";
$statement= "   AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataPegawaiKomentar[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("JADWAL_PEGAWAI_ID")."-".$set->getField("JADWAL_TES_ID");
	$arrDataPegawaiKomentar[$index_loop]["KETERANGAN"]= str_replace("\n","<br/>",$set->getField("KETERANGAN"));
	$index_loop++;
}

$index_loop= 0;
$arrDataPegawaiKomentarLain="";
$statement= "  AND A.PEGAWAI_ID = ".$tempPegawaiInfoId." AND A.JADWAL_TES_ID = ".$tempPegawaiInfoJadwalTesId;
 
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrDataPegawaiKomentarLain[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("PEGAWAI_ID")."-".$set->getField("JADWAL_TES_ID");
	$arrDataPegawaiKomentarLain[$index_loop]["KETERANGAN"]= str_replace("\n","<br/>",$set->getField("KETERANGAN"));
	$arrDataPegawaiKomentarLain[$index_loop]["ASESOR_KOMENTAR_NAMA"]= $set->getField("ASESOR_KOMENTAR_NAMA");
	$index_loop++;
}

$index_loop= 0;
$arrPegawaiNilai="";
//$statement= " AND F.ATRIBUT_ID = '0101' AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND B.ASESOR_ID = ".$tempAsesorId;
$statement= " AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND B.ASESOR_ID = ".$tempAsesorId;
$set= new JadwalPegawaiDetil();
$set->selectByParamsMonitoring(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrPegawaiNilai[$index_loop]["JADWAL_PEGAWAI_DETIL_ID"]= $set->getField("JADWAL_PEGAWAI_DETIL_ID");
	$arrPegawaiNilai[$index_loop]["ID"]= $set->getField("ASESOR_ID")."-".$set->getField("ATRIBUT_ID")."-".$set->getField("LEVEL_ID")."-".$set->getField("INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrPegawaiNilai[$index_loop]["INDIKATOR_ID"]= $set->getField("INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_INDIKATOR_ID"]= $set->getField("PEGAWAI_INDIKATOR_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_LEVEL_ID"]= $set->getField("PEGAWAI_LEVEL_ID");
	$arrPegawaiNilai[$index_loop]["PEGAWAI_KETERANGAN"]= $set->getField("PEGAWAI_KETERANGAN");
	$arrPegawaiNilai[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrPegawaiNilai[$index_loop]["NAMA_INDIKATOR"]= $set->getField("NAMA_INDIKATOR");
	$arrPegawaiNilai[$index_loop]["JUMLAH_LEVEL"]= $set->getField("JUMLAH_LEVEL");
	$arrPegawaiNilai[$index_loop]["NAMA_ASESOR"]= $set->getField("NAMA_ASESOR");
	$index_loop++;
}
$jumlah_pegawai_nilai= $index_loop;
//print_r($arrPegawaiNilai);exit;
if($jumlah_pegawai_nilai > 0)
{
	$tempPegawaiNamaAsesor= $arrPegawaiNilai[0]["NAMA_ASESOR"];
}*/

$objPHPexcel = PHPExcel_IOFactory::load('../template/asesor/cetak.xlsx');

$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

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

$row= 6;

$allRecord= $jumlah_pegawai_atribut;
if($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($row+1, $allRecord);
}
elseif($allRecord == 0)
{
	$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':F'.$row.'');
	$i++;
}

$nomor= 1;
for($index_atribut=0;$index_atribut < $jumlah_pegawai_atribut;$index_atribut++)
{
	$tempAtributId= $arrDataAtribut[$index_atribut]["ATRIBUT_ID"];
	$tempAtributNama= $arrDataAtribut[$index_atribut]["ATRIBUT_NAMA"];
	$tempNilaiStandar= $arrDataAtribut[$index_atribut]["NILAI_STANDAR"];
	
	$tempJumlahBagi= 0;
	$tempTotalJumlah= 0;
	
	$kolom= getColoms(1);
	$objWorksheet->setCellValue($kolom.$row,$nomor);
	
	$kolom= getColoms(2);
	$objWorksheet->setCellValue($kolom.$row,$tempAtributNama);
	
	$kolom= getColoms(3);
	$objWorksheet->setCellValue($kolom.$row,$tempNilaiStandar);
	
	$tempNilaiPembulatan= "";
	$tempNilaiId= $tempAtributId."-".$tempPegawaiInfoId."-AK";
	$arrayKey= '';
	$arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
	//print_r($arrayKey);exit;
	if($arrayKey == ''){}
	else
	{
		for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
		{
		$index_row= $arrayKey[$index_detil];
		$tempNilaiPembulatan= $arrDataAsesorNilai[$index_row]["NILAI_PEMBULATAN"];
		$tempJumlahBagi++;
		$tempTotalJumlah+= $tempNilaiPembulatan;
		}
	}
	$kolom= getColoms(4);
	$objWorksheet->setCellValue($kolom.$row,$tempNilaiPembulatan);
	
	$tempNilaiPembulatan= "";
	$tempNilaiId= $tempAtributId."-".$tempPegawaiInfoId."-TKB";
	$arrayKey= '';
	$arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
	//print_r($arrayKey);exit;
	if($arrayKey == ''){}
	else
	{
		for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
		{
		$index_row= $arrayKey[$index_detil];
		$tempNilaiPembulatan= $arrDataAsesorNilai[$index_row]["NILAI_PEMBULATAN"];
		$tempJumlahBagi++;
		$tempTotalJumlah+= $tempNilaiPembulatan;
		}
	}
	$kolom= getColoms(5);
	$objWorksheet->setCellValue($kolom.$row,$tempNilaiPembulatan);
	
	$tempNilaiPembulatan= "";
	$tempNilaiId= $tempAtributId."-".$tempPegawaiInfoId."-LGD";
	$arrayKey= '';
	$arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
	//print_r($arrayKey);exit;
	if($arrayKey == ''){}
	else
	{
		for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
		{
		$index_row= $arrayKey[$index_detil];
		$tempNilaiPembulatan= $arrDataAsesorNilai[$index_row]["NILAI_PEMBULATAN"];
		$tempJumlahBagi++;
		$tempTotalJumlah+= $tempNilaiPembulatan;
		}
	}
	$kolom= getColoms(6);
	$objWorksheet->setCellValue($kolom.$row,$tempNilaiPembulatan);
	
	$tempNilaiPembulatan= "";
	$tempNilaiId= $tempAtributId."-".$tempPegawaiInfoId."-PR";
	$arrayKey= '';
	$arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
	//print_r($arrayKey);exit;
	if($arrayKey == ''){}
	else
	{
		for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
		{
		$index_row= $arrayKey[$index_detil];
		$tempNilaiPembulatan= $arrDataAsesorNilai[$index_row]["NILAI_PEMBULATAN"];
		$tempJumlahBagi++;
		$tempTotalJumlah+= $tempNilaiPembulatan;
		}
	}
	$kolom= getColoms(7);
	$objWorksheet->setCellValue($kolom.$row,$tempNilaiPembulatan);
	
	$tempNilaiPembulatan= "";
	$tempNilaiId= $tempAtributId."-".$tempPegawaiInfoId."-CBI";
	$arrayKey= '';
	$arrayKey= in_array_column($tempNilaiId, "ID", $arrDataAsesorNilai);
	//print_r($arrayKey);exit;
	if($arrayKey == ''){}
	else
	{
		for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
		{
		$index_row= $arrayKey[$index_detil];
		$tempNilaiPembulatan= $arrDataAsesorNilai[$index_row]["NILAI_PEMBULATAN"];
		$tempJumlahBagi++;
		$tempTotalJumlah+= $tempNilaiPembulatan;
		}
	}
	$kolom= getColoms(8);
	$objWorksheet->setCellValue($kolom.$row,$tempNilaiPembulatan);
	
	$tempTotalJumlah= round($tempTotalJumlah / $tempJumlahBagi, 2);
	$kolom= getColoms(9);
	$objWorksheet->setCellValue($kolom.$row,$tempTotalJumlah);
	
	$nomor++;
	$row++;
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('../template/asesor/cetak.xls');

$down = '../template/asesor/cetak.xls';
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
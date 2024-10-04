<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianBankSoalId= httpFilterGet("reqUjianBankSoalId");
$reqBankSoalId= httpFilterGet("reqBankSoalId");
$reqBankSoalPilihanId= httpFilterGet("reqBankSoalPilihanId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqId = httpFilterGet("reqId");

/*$set= new UjianPegawaiDaftar();
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$statement.= " AND B.UJIAN_TAHAP_ID = ".$reqId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI AND UP.BANK_SOAL_PILIHAN_ID IS NOT NULL";
$set= new UjianPegawaiDaftar();
$tempJumlahUjian= $set->getCountByParamsSoal(array(), $statement);
//echo $set->query;exit;
unset($set);*/

$statement= " AND UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianTahap();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
unset($set);

$tempSystemTanggalNow= date("d-m-Y");

if($tempTipeUjianId == 7)
	$sOrder= "ORDER BY A.UJIAN_ID, B.BANK_SOAL_ID";
else
	$sOrder= "ORDER BY A.UJIAN_ID, B.BANK_SOAL_ID, UP.UJIAN_PEGAWAI_ID";

$index_loop=0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND B.UJIAN_TAHAP_ID = ".$reqId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();

if($tempTipeUjianId == 7)
$set->selectByParamsSoalPapiFinishData(array(), -1,-1, $statement, $sOrder);
else
$set->selectByParamsSoalFinish(array(), -1,-1, $statement, $sOrder);
//echo $set->query;exit;
$tempValueRowId= "";
while($set->nextRow())
{
	if($set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("UJIAN_TAHAP_ID") == $tempValueRowId){}
	else
	{
		//if($set->getField("BANK_SOAL_PILIHAN_ID") == ""){}
		//else
		$index_loop+= $set->getField("JUMLAH_DATA");
	}
	$tempValueRowId= $set->getField("UJIAN_ID")."-".$set->getField("BANK_SOAL_ID")."-".$set->getField("UJIAN_TAHAP_ID");
	
}
$tempJumlahUjian= $index_loop;
unset($set);

echo $tempJumlahUjian;
exit;
?>
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

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$reqUjianId= httpFilterGet("reqUjianId");
$reqUjianBankSoalId= httpFilterGet("reqUjianBankSoalId");
$reqBankSoalId= httpFilterGet("reqBankSoalId");
$reqBankSoalPilihanId= httpFilterGet("reqBankSoalPilihanId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqId = httpFilterGet("reqId");


$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ".$reqId;
$set= new UjianTahap();
$set->selectByParamsUjianPegawaiTahap(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit();
$tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
$tempUjianId= $ujianPegawaiUjianId;
// echo $tempTipeUjianId;exit();
unset($set);

$tempSystemTanggalNow= date("d-m-Y");

/*if($tempTipeUjianId == 7)
	$sOrder= "ORDER BY A.UJIAN_ID, B.BANK_SOAL_ID";*/

$index_loop=0;
// $set= new UjianPegawaiDaftar();

/*if($tempTipeUjianId == 7)
{
	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND B.UJIAN_TAHAP_ID = ".$reqId;
	// $statement.= " AND NOW() BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
	$statement.= " AND CAST(NOW() AS DATE) BETWEEN CAST(UJ.TGL_MULAI AS DATE) AND CAST(UJ.TGL_SELESAI AS DATE)";

	$set->selectByParamsSoalPapiFinishData(array(), -1,-1, $statement, $sOrder);
}
else
{*/
	$sOrder= "ORDER BY A.UJIAN_ID, A.UJIAN_TAHAP_ID";
	$set= new UjianPegawai();
	$statement= " AND A.UJIAN_TAHAP_ID = ".$reqId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$reqPegawaiId;
	$set->selectByParamsSoalFinishRevisi(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $sOrder);
	// echo $set->query;exit();
// }
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
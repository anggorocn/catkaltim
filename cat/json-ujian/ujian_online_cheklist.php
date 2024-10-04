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

// echo $reqUjianId;exit();
$i=0;

$set= new UjianPegawaiDaftar();
if($tempTipeUjianId == 7)
{
	$set->selectByParamsSoalPapiCheckList(array(), -1,-1, $statement, $sOrder);
	exit();
}
else
{
	// $set->selectByParamsSoalCheckList(array(), -1,-1, $statement, $sOrder);
	$sOrder= "ORDER BY A.UJIAN_ID, A.UJIAN_TAHAP_ID";
	$set= new UjianPegawai();
	$statement= " AND A.BANK_SOAL_PILIHAN_ID IS NOT NULL AND A.UJIAN_TAHAP_ID = ".$reqId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.BANK_SOAL_ID = ".$reqBankSoalId;
	$set->selectByParams(array(), -1,-1, $ujianPegawaiJadwalTesId, $statement, $sOrder);
	// echo $set->query;exit();
	
	// $sOrder= "ORDER BY UJIAN_ID, BANK_SOAL_ID, UJIAN_PEGAWAI_ID";
	// $statement= " AND UJIAN_ID = ".$reqUjianId." AND PEGAWAI_ID = ".$reqPegawaiId." AND UJIAN_TAHAP_ID = ".$reqId." AND BANK_SOAL_ID = ".$reqBankSoalId;
	// $set= new UjianPegawai();
	// $set->selectByParams(array(), -1,-1, $statement, $sOrder);
}
// echo $set->query;exit;
while($set->nextRow())
{
	$arrID[$i] = $set->getField("BANK_SOAL_PILIHAN_ID");
	$i += 1;
}
$arrFinal = array("arrID"=>$arrID);
echo json_encode($arrFinal);
exit;
?>
<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqUjianPegawaiDaftarId= httpFilterGet("reqUjianPegawaiDaftarId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$set= new UjianPegawaiDaftar();
$tempSystemTanggalNow= date("d-m-Y");
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$statement.= " AND A.UJIAN_ID IN( SELECT A.UJIAN_ID FROM cat.UJIAN A INNER JOIN cat.UJIAN_TAHAP B ON A.UJIAN_ID = B.UJIAN_ID WHERE TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI GROUP BY A.UJIAN_ID)";
$set= new UjianPegawaiDaftar();
$set->selectByParamsUjian(array(), -1,-1, $statement);
//echo $set->query;exit;
$set->firstRow();
$reqUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempPegawaiId= $set->getField("PEGAWAI_ID");
unset($set);

if($tempPegawaiId == ""){}
else
{
	$set= new UjianPegawaiDaftar();
	$set->setField("TABLE", "cat.UJIAN_PEGAWAI_DAFTAR");
	$set->setField("FIELD", "STATUS_SETUJU");
	$set->setField("FIELD_VALUE", "1");
	$set->setField("FIELD_ID", "UJIAN_PEGAWAI_DAFTAR_ID");
	$set->setField("FIELD_VALUE_ID", $reqUjianPegawaiDaftarId);
	$tempStatusSimpan= "";
	//kalau tidak ada maka simpan
	if($reqUjianPegawaiDaftarId == ""){}
	else
	{
		if($set->updateFormatDynamis())
		$tempStatusSimpan= 1;
	}
	//echo $set->query;exit;
	unset($set);
	
	if($tempStatusSimpan == "1")
	echo "1";
}
?>
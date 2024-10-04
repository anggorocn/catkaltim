<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UserGroupsBase.php");
include_once("../WEB/classes/utils/UserLogin.php");

$set = new UserGroupsBase();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNama = httpFilterPost("reqNama");
$reqTempListId = httpFilterPost("reqTempListId");
$reqPegawaiProses = httpFilterPost("reqPegawaiProses");
$reqMasterProses = httpFilterPost("reqMasterProses");
$reqLihatProses = httpFilterPost("reqLihatProses");
$reqIKK = httpFilterPost("reqIKK");
$reqPengembangan = httpFilterPost("reqPengembangan");
$reqPolaKarir = httpFilterPost("reqPolaKarir");
$reqEvaluasiKerja = httpFilterPost("reqEvaluasiKerja");
$reqTugasBelajar = httpFilterPost("reqTugasBelajar");
$reqRencanaSuksesi = httpFilterPost("reqRencanaSuksesi");
$reqPengaturanIKK = httpFilterPost("reqPengaturanIKK");

$set->setField("NAMA", $reqNama);
$set->setField("TEMP_LIST", $reqTempListId);
$set->setField("PEGAWAI_PROSES", setNULL($reqPegawaiProses));
$set->setField("MASTER_PROSES", setNULL($reqMasterProses));
$set->setField("LIHAT_PROSES", setNULL($reqLihatProses));
$set->setField("IKK_PROSES", setNULL($reqIKK));
$set->setField("PENGEMBANGAN_SDM_PROSES", setNULL($reqPengembangan));
$set->setField("POLA_KARIR_PROSES", setNULL($reqPolaKarir));
$set->setField("EVALUASI_KINERJA_PROSES", setNULL($reqEvaluasiKerja));
$set->setField("TUGAS_BELAJAR_PROSES", setNULL($reqTugasBelajar));
$set->setField("RENCANA_SUKSESI_PROSES", setNULL($reqRencanaSuksesi));
$set->setField("PENGATURAN_IKK", setNULL($reqPengaturanIKK));

if($reqMode == "insert")
{
	
	$set->setField("LAST_CREATE_USER", $userLogin->nama);
	$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");
	$set->setField("LAST_CREATE_USER", $userLogin->userSatkerId);
	if($set->insert())
		echo "Data berhasil disimpan.";
		//echo $set->query;exit;
}
else
{
	$set->setField("LAST_UPDATE_USER", $userLogin->nama);
	$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
	$set->setField("LAST_UPDATE_USER", $userLogin->userSatkerId);
	$set->setField("USER_GROUP_ID", $reqId);
	
	if($set->update())
		echo "Data berhasil disimpan.";
	
}

//echo $set->query;exit;
?>
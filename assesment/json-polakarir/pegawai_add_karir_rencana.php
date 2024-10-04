<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-polakarir/PerencanaanDetil.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pegawai_add_karir_rencana = new PerencanaanDetil();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId = httpFilterPost("reqRowId");

$reqTahun= httpFilterPost("reqTahun");
$reqTipeRencana= httpFilterPost("reqTipeRencana");
$reqUsiaRen= httpFilterPost("reqUsiaRen");
$reqPangkatIdRenc= httpFilterPost("reqPangkatIdRenc");
$reqSkpd= httpFilterPost("reqSkpd");
$reqJabatanIdRenc= httpFilterPost("reqJabatanIdRenc");
$reqTmtJabatanRen= httpFilterPost("reqTmtJabatanRen");
$reqPendidikanRenc= httpFilterPost("reqPendidikanRenc");
$reqKinerjaSkpRen= httpFilterPost("reqKinerjaSkpRen");
$reqKinerjaPkRen= httpFilterPost("reqKinerjaPkRen");
$reqDiklatStrukRen= httpFilterPost("reqDiklatStrukRen");
$reqDiklatTeknisRen= httpFilterPost("reqDiklatTeknisRen");

$pegawai_add_karir_rencana->setField("TAHUN", $reqTahun);
$pegawai_add_karir_rencana->setField("TIPE_RENCANA", $reqTipeRencana);
$pegawai_add_karir_rencana->setField("USIA_REN", setNULL($reqUsiaRen));
$pegawai_add_karir_rencana->setField("PANGKAT_ID_REN", setNULL($reqPangkatIdRenc));
$pegawai_add_karir_rencana->setField("SATKER_ID_REN", $reqSkpd);
//$pegawai_add_karir_rencana->setField("JABATAN_ID_REN", $reqJabatanIdRenc);
$pegawai_add_karir_rencana->setField("JABATAN_ID_REN", $reqSkpd);
$pegawai_add_karir_rencana->setField("TMT_JABATAN_REN", dateToDBCheck($reqTmtJabatanRen));
$pegawai_add_karir_rencana->setField("PENDIDIKAN_REN", $reqPendidikanRenc);
$pegawai_add_karir_rencana->setField("KINERJA_SKP_REN", setNULL($reqKinerjaSkpRen));
$pegawai_add_karir_rencana->setField("KINERJA_PK_REN", setNULL($reqKinerjaPkRen));
$pegawai_add_karir_rencana->setField("DIKLAT_STRUK_REN", $reqDiklatStrukRen);
$pegawai_add_karir_rencana->setField("DIKLAT_TEKNIS_REN", $reqDiklatTeknisRen);
$pegawai_add_karir_rencana->setField("PERENCANAAN_ID", setNULL($req));				   
$pegawai_add_karir_rencana->setField("USIA_CAPAI", setNULL($req));
$pegawai_add_karir_rencana->setField("PANGKAT_ID_CAPAI", setNULL($req));
$pegawai_add_karir_rencana->setField("JABATAN_ID_CAPAI", $req);
$pegawai_add_karir_rencana->setField("TMT_JABATAN_CAPAI", dateToDBCheck($req));
$pegawai_add_karir_rencana->setField("KINERJA_SKP_CAPAI", setNULL($req));
$pegawai_add_karir_rencana->setField("KINERJA_PK_CAPAI", setNULL($req));
$pegawai_add_karir_rencana->setField('PEGAWAI_ID', $reqId);


if($reqMode == "insert")
{
	if($pegawai_add_karir_rencana->insert()){
		$reqRowId= $pegawai_add_karir_rencana->id;
		echo $reqId."-Data berhasil disimpan.-".$reqRowId;
	}
	
	//echo $pegawai_add_karir_rencana->query;
}
else
{
	$pegawai_add_karir_rencana->setField('PERENCANAAN_DETIL_ID', $reqRowId);
	
	if($pegawai_add_karir_rencana->update()){
		echo $reqId."-Data berhasil disimpan.-".$reqRowId;
	}
	//echo $pegawai_add_karir_rencana->query;
}
?>
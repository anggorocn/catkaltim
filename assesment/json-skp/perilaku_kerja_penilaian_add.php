<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/PerilakuKerja.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");
include_once("../WEB/classes/utils/UserLogin.php");

$perilaku_kerja = new PerilakuKerja();
$penilaian_periode = new PeriodePenilaian();

$reqId = httpFilterPost("reqId");
$reqSaran = $_POST["reqSaran"];
$reqPerilakuKerjaId = $_POST["reqPerilakuKerjaId"];
$reqPertanyaanId = $_POST["reqPertanyaanId"];
$reqRangeNilai = $_POST["reqRangeNilai"];
$reqJawabanId = $_POST["reqJawabanId"];
$reqPeriode = $penilaian_periode->getMaxTahun();

for($i=0;$i<count($reqPertanyaanId);$i++)
{
	$perilaku_kerja = new PerilakuKerja();
	
	$perilaku_kerja->setField("PERILAKU_KERJA_ID", $reqPerilakuKerjaId[$i]);
	$perilaku_kerja->setField("JAWABAN_ID", $reqJawabanId[$i]);
	$perilaku_kerja->setField("RANGE", $reqRangeNilai[$i]);
	$perilaku_kerja->setField("PEGAWAI_ID_DINILAI", $reqId);
	$perilaku_kerja->setField("PEGAWAI_ID_PENILAI", $userLogin->pegawaiId);
	$perilaku_kerja->setField("PERTANYAAN_ID", $reqPertanyaanId[$i]);
	$perilaku_kerja->setField("TAHUN", $reqPeriode);
	if($reqPerilakuKerjaId[$i] == "")
		$perilaku_kerja->insert();
	else
		$perilaku_kerja->update();
	
				  
	unset($perilaku_kerja);
}

echo "Data berhasil disimpan.";
?>
<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base/AsesorPenilaianDetil.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqAsesorPenilaianDetilId= $_POST["reqAsesorPenilaianDetilId"];
$reqJadwalTesId= $_POST["reqJadwalTesId"];
$reqTanggalTes= $_POST["reqTanggalTes"];
$reqJabatanTesId= $_POST["reqJabatanTesId"];
$reqSatkerTesId= $_POST["reqSatkerTesId"];
$reqJadwalAsesorId= $_POST["reqJadwalAsesorId"];
$reqAspekId= $_POST["reqAspekId"];
$reqAsesorAtributId= $_POST["reqAsesorAtributId"];
$reqNilaiStandar= $_POST["reqNilaiStandar"];
$reqNilai= $_POST["reqNilai"];
$reqGap= $_POST["reqGap"];
$reqAsesorFormulaEselonId= $_POST["reqAsesorFormulaEselonId"];
$reqAsesorFormulaAtributId= $_POST["reqAsesorFormulaAtributId"];
$reqAsesorPenggalianId= $_POST["reqAsesorPenggalianId"];
$reqAsesorPegawaiId= $_POST["reqAsesorPegawaiId"];
$reqCatatan= $_POST["reqCatatan"];

$reqMode= httpFilterPost("reqMode");

if($reqMode == "insert")
{
	for($i=0; $i < count($reqAsesorPenilaianDetilId); $i++)
	{
			$set_detil= new AsesorPenilaianDetil();
			$set_detil->setField('ASESOR_PENILAIAN_DETIL_ID',$reqAsesorPenilaianDetilId[$i]);
			$set_detil->setField('JADWAL_TES_ID',$reqJadwalTesId[$i]);
			$set_detil->setField('TANGGAL_TES',dateToDBCheck($reqTanggalTes[$i]));
			$set_detil->setField('JABATAN_TES_ID',$reqJabatanTesId[$i]);
			$set_detil->setField('SATKER_TES_ID',$reqSatkerTesId[$i]);
			$set_detil->setField('JADWAL_ASESOR_ID',$reqJadwalAsesorId[$i]);
			$set_detil->setField('ASPEK_ID',$reqAspekId[$i]);
			$set_detil->setField('ASESOR_ATRIBUT_ID',$reqAsesorAtributId[$i]);
			$set_detil->setField('NILAI_STANDAR',$reqNilaiStandar[$i]);
			$set_detil->setField('NILAI',$reqNilai[$i]);
			$set_detil->setField('GAP',$reqGap[$i]);
			$set_detil->setField('ASESOR_FORMULA_ESELON_ID',$reqAsesorFormulaEselonId[$i]);
			$set_detil->setField('ASESOR_FORMULA_ATRIBUT_ID',$reqAsesorFormulaAtributId[$i]);
			$set_detil->setField('ASESOR_PENGGALIAN_ID',ValToNullDB($reqAsesorPenggalianId[$i]));
			$set_detil->setField('ASESOR_PEGAWAI_ID',$reqAsesorPegawaiId[$i]);
			$set_detil->setField('CATATAN',$reqCatatan[$i]);
			
			$set_detil->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");
			if($reqAsesorPenilaianDetilId[$i] == "")
			{
				$set_detil->insert();
			}
			else
			{
				$set_detil->update();
			}
			//echo $set_detil->query;exit;
			unset($set_detil);
	}
	echo "Data berhasil disimpan";
}
?>
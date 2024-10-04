<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarPeminatanJabatan.php");
include_once("../WEB/classes/base/PelamarPeminatanLokasi.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar_peminatan_jabatan = new PelamarPeminatanJabatan();
$pelamar_peminatan_lokasi = new PelamarPeminatanLokasi();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqRowId= httpFilterPost("reqRowId");

$reqJabatan = httpFilterPost("reqJabatan");

$reqCabangP3Id = httpFilterPost("reqCabangP3Id");

$pelamar_peminatan_jabatan->setField("PELAMAR_ID", $userLogin->userPelamarId);
$pelamar_peminatan_jabatan->delete();

$pelamar_peminatan_lokasi->setField("PELAMAR_ID", $userLogin->userPelamarId);
$pelamar_peminatan_lokasi->delete();

for($i=1;$i<4;$i++)
{
	if($reqJabatan[$i] == "")
	{}
	else
	{
		$pelamar_peminatan_jabatan->setField("PELAMAR_ID", $userLogin->userPelamarId);
		$pelamar_peminatan_jabatan->setField("JABATAN_ID", $reqJabatan[$i]);
		$pelamar_peminatan_jabatan->setField("URUT", $i);
		$pelamar_peminatan_jabatan->insert();
		
	}
}

for($i=1;$i<3;$i++)
{
	if($reqCabangP3Id[$i] == "")
	{}
	else
	{
		$pelamar_peminatan_lokasi->setField("PELAMAR_ID", $userLogin->userPelamarId);
		$pelamar_peminatan_lokasi->setField("CABANG_P3_ID", $reqCabangP3Id[$i]);
		$pelamar_peminatan_lokasi->setField("URUT", $i);
		$pelamar_peminatan_lokasi->insert();
		
	}
}
?>
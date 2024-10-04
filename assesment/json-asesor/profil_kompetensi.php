<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PenilaianKompetensi.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$tempAsesorId= $userLogin->userAsesorId;
if($tempAsesorId == "")
{
	exit;
}

$reqKompetensiDasarId= $_POST["reqKompetensiDasarId"];
$reqKompetensiNilai= $_POST["reqKompetensiNilai"];
$reqKompetensiKet= $_POST["reqKompetensiKet"];
$reqKompetensiPenilaianId= $_POST["reqKompetensiPenilaianId"];

for($i=0; $i < count($reqKompetensiDasarId); $i++)
{
	$set_detil= new PenilaianKompetensi();
	$set_detil->setField('penilaian_kompetensi_dasar_id',$reqKompetensiDasarId[$i]);
	$set_detil->setField('penilaian',$reqKompetensiNilai[$i]);
	$set_detil->setField('keterangan',$reqKompetensiKet[$i]);
	$set_detil->setField('penilaian_kompetensi_penilaian_id',$reqKompetensiPenilaianId[$i]);

	if($reqKompetensiPenilaianId[$i]==''){
		$set_detil->insert();
	}
	else{
		$set_detil->update();
	}
	unset($set_detil);
}

// echo "Data berhasil disimpan";
?>
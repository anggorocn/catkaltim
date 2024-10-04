<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pegawai.php");
include_once("../WEB/classes/base/PegawaiDesa.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/Kuisioner.php");


/* LOGIN CHECK */


// echo "Dadada"; exit;

$jawaban= $_POST["jawaban"];
$pegawai_id= $_POST["pegawai_id"];
$ujian_id= $_POST["ujian_id"];
$soal= $_POST["soal"];
$detil= $_POST["detil"];
$cekdetil= $_POST["cekdetil"];
$kuisionerjawaban= $_POST["kuisionerjawaban"];
// print_r($cekdetil); exit;
if(in_array(null,$jawaban)){
	echo "xxx-isi semua pertanyaan"; exit;
}
if(in_array(null,$detil)){
	$totaldataharusisi=array_count_values($cekdetil);
	$totaldatakosong=array_count_values($detil);
	$totaldata=count($detil);
	if($totaldataharusisi['1']!=$totaldata-$totaldatakosong['']){
	 echo "xxx-Isi semua pertanyaan";exit;
	}
}
// echo "mashok";exit;
$statementCekInsert= "and PEGAWAI_ID=".$pegawai_id." and ujian_id =".$ujian_id;
$cekInsert= new Kuisioner();
$cekada=$cekInsert->getCountByParams(array(),$statementCekInsert);
if($cekada==0){
	$mode="insert";
}
else{
	$mode="update";
}
// print_r($mode); exit;

// echo count($detil); exit;
for ($i=0; $i<count($detil); $i++){
	$set= new Kuisioner();
	$set->setField("KUISIONER_PERTANYAAN_ID", $soal[$i]);
	$set->setField("KUISIONER_JAWABAN_ID", $jawaban[$i]);
	$set->setField("KUISIONER_DETIL", $detil[$i]);
					
	$set->setField("PEGAWAI_ID", $pegawai_id);
	$set->setField("UJIAN_ID", $ujian_id);

	if($mode == "insert"){
		$set->insert();
	}
	else
	{
		$set->setField("KUISIONER_ID", $kuisionerjawaban[$i]);
		$set->update();
	}
}

		
	echo "1-Data berhasil disimpan";
?>
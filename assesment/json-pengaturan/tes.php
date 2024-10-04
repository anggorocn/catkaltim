<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Tes.php");

$set= new Tes();


$set->selectByParams(array(), -1,-1, $statement);

ini_set("memory_limit", "-1");
set_time_limit(0);

while($set->nextRow())
{
	$jadwal_tes_id= $set->getField("jadwal_tes_id");
	$penggalian_id= $set->getField("penggalian_id");
	$vtotalatribut= $set->getField("total_atribut");

	$count= new Tes();
	$vpegawaiindikator = $count->getCountByParams(array(), $jadwal_tes_id, $penggalian_id);
	$vpegawaiatribut = $count->getCountByParamsatribut(array(), $jadwal_tes_id, $penggalian_id);
	$pegawai_id=$count->currentRow["pegawai_id"];

	$ada_data =0;
	if(!empty($pegawai_id))
	{
		$ada_data = $count->getCountByParamCheck(array(), $jadwal_tes_id, $penggalian_id,$pegawai_id);
		// print_r($count->query.'</br>');
	}
	// print_r($pegawai_id.'</br>');
	
	// print_r($ada_data);

	$vstatus= '2';
	if ($vtotalatribut == $vpegawaiindikator  && $vtotalatribut == $vpegawaiatribut)
	{
		$vstatus= '1';
	}
	else if ($vpegawaiindikator == 0 && $vpegawaiatribut == 0 )
	{
		$vstatus= '';
	}
	// var_dump($vstatus.'</br>');

}

unset($set);
unset($count);

// data potensi


$set= new Tes();
$set->selectByParamsFormula(array(), -1,-1, $statement);

while($set->nextRow())
{
	$vformulaeselonid= $set->getField("formula_eselon_id");
	$vjadwaltesId= $set->getField("jadwal_tes_id");

	$count= new Tes();
	$vpenggalianid= 0;

	$vtotalatributPotensi = $count->getCountByParamsPotensi(array(), $vformulaeselonid, $vjadwaltesId);
	$vpotensiatribut = $count->getCountByParamsPotensiAtribut(array(), $vformulaeselonid, $vjadwaltesId);
	// print_r( $count->query);
	$vpotensideskripsi = $count->getCountByParamsPotensiDeskripsi(array(), $vformulaeselonid, $vjadwaltesId);
	// $vpegawaiatribut = $count->getCountByParamsatribut(array(), $jadwal_tes_id);
	// print_r( $vpotensideskripsi);
	

	$vstatus= '2';
	if ($vtotalatributPotensi == $vpotensiatribut  && $vpotensideskripsi > 0)
	{
		$vstatus= '1';
	}
	else if ($vpotensiatribut > 0 || $vpotensideskripsi > 0 )
	{
		$vstatus= '';
	}
	var_dump($vstatus);

}



?>
<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjianPetunjuk.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/UjianPegawai.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* LOGIN CHECK */
if($userLogin->ujianUid == "")
{
	exit;
}

$reqUjianId= httpFilterGet("reqUjianId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianId= $ujianPegawaiUjianId;
$tempSystemTanggalNow= date("d-m-Y");

if(($reqTipeUjianId >=12 && $reqTipeUjianId <= 15) || ($reqTipeUjianId >=8 && $reqTipeUjianId <= 11) || $reqTipeUjianId == 16 || $reqTipeUjianId == 43)
{
	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId;
	$set= new UjianTahapStatusUjianPetunjuk();
	$tempNoUrut= $set->getCountByParams(array(), $statement);
	// echo $set->query;exit;
	if($tempNoUrut == 0)
	{
		$statement= " AND B.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId;
	    $set= new UjianTahapStatusUjianPetunjuk();
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "NOW()");
		if($set->insertQueryModif($statement))
		{
			// echo "1";
		}
		// else
		// echo "0";
		
		//echo $set->query;exit;
	}
	// else
	// echo 1;
	//echo $set->query;exit;

	// cfit b mabil latihan cfit a
	if($reqTipeUjianId >=12 && $reqTipeUjianId <= 15)
	$reqTipeUjianIdLatihan= $reqTipeUjianId - 4;
	// cfit a mabil latihan cfit b
	elseif($reqTipeUjianId >=8 && $reqTipeUjianId <= 11)
	$reqTipeUjianIdLatihan= $reqTipeUjianId + 4;
	else
	$reqTipeUjianIdLatihan= $reqTipeUjianId;

	// echo $reqTipeUjianIdLatihan;exit();
	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$reqTipeUjianIdLatihan." AND A.UJIAN_ID = ".$tempUjianId;
	$set= new UjianTahapStatusUjianPetunjuk();
	$tempNoUrut= $set->getCountByParamsLatihan(array(), $statement);
	// echo $set->query;exit;
	if($tempNoUrut == 0)
	{
		$statement= " AND B.TIPE_UJIAN_ID = ".$reqTipeUjianIdLatihan." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId;
	    $set= new UjianTahapStatusUjianPetunjuk();
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "NOW()");
		if($set->insertQueryModifLatihan($statement))
		{
			echo "1";
		}
		else
		echo "0";
		
		// echo $set->query;exit;
	}
	else
	echo 1;
	//echo $set->query;exit;
}
else
{
	$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId;
	$set= new UjianTahapStatusUjianPetunjuk();
	$tempNoUrut= $set->getCountByParams(array(), $statement);
	 // echo $set->query;exit;
	if($tempNoUrut == 0)
	{
		$statement= " AND B.TIPE_UJIAN_ID = ".$reqTipeUjianId." AND A.UJIAN_ID = ".$tempUjianId." AND A.PEGAWAI_ID = ".$tempPegawaiId;
	    $set= new UjianTahapStatusUjianPetunjuk();
		$set->setField("LAST_CREATE_USER", $userLogin->nama);
		$set->setField("LAST_CREATE_DATE", "NOW()");
		if($set->insertQueryModif($statement))
		{
			echo "1";
		}
		else
		echo "0";
		
		//echo $set->query;exit;
	}
	else
	echo 1;
}
//echo $set->query;exit;
exit;
?>
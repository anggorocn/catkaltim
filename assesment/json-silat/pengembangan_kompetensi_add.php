<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PegawaiHcdp.php");
include_once("../WEB/classes/base/PegawaiHcdpDetil.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqRowId= httpFilterPost("reqRowId");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqFormulaId= httpFilterPost("reqFormulaId");
$reqJumlahJp= httpFilterPost("reqJumlahJp");
// $reqJp= httpFilterPost("reqJp");
// $reqTahun= httpFilterPost("reqTahun");



$reqAtributId= $_POST["reqAtributId"];
$reqPelatihanId= $_POST["reqPelatihanId"];
$reqPelatihanNama= $_POST["reqPelatihanNama"];
$reqJp= $_POST["reqJp"];
$reqTahun= $_POST["reqTahun"];
$reqKeterangan= $_POST["reqKeterangan"];

$reqRumpunPengembanganAtributId= $_POST["reqRumpunPengembanganAtributId"];
$reqRumpunPengembanganId= $_POST["reqRumpunPengembanganId"];
$reqRumpunPengembanganNama= $_POST["reqRumpunPengembanganNama"];
// print_r($reqJp);exit;

$set= new PegawaiHcdp();
$set->setField("PEGAWAI_HCDP_ID", $reqRowId);
$set->setField("JUMLAH_JP", $reqJumlahJp);
if($set->updatejp())
{
	$setdetil= new PegawaiHcdpDetil();
	$setdetil->setField("PEGAWAI_HCDP_ID", $reqRowId);
	$setdetil->delete();
	unset($setdetil);

	$setdetil= new PegawaiHcdpDetil();
	$setdetil->setField("PEGAWAI_HCDP_ID", $reqRowId);
	$setdetil->deleterumpun();
	unset($setdetil);

	$statement= "
	AND EXISTS
	(
		SELECT 1
		FROM
		(
			SELECT
			PERMEN_ID
			FROM PERMEN
			WHERE STATUS = '1'
		) XXX WHERE A.PERMEN_ID = XXX.PERMEN_ID
	)
	";

	for($i=0; $i < count($reqAtributId); $i++)
	{
		if(!empty($reqPelatihanId[$i]))
		{
			$arrdataid= explode(",", $reqPelatihanId[$i]);
			for($x=0; $x < count($arrdataid); $x++)
			{
				$infoid= $arrdataid[$x];
				$get= new PelatihanHcdp();
				$get->selectByParams(array("PELATIHAN_HCDP_ID" => $infoid), -1, -1, $statement);
				$get->firstRow();
				$infonama= $get->getField("NAMA");
				unset($get);

				$setdetil= new PegawaiHcdpDetil();
				$setdetil->setField("PEGAWAI_HCDP_ID", $reqRowId);
				$setdetil->setField("PEGAWAI_ID", $reqPegawaiId);
				$setdetil->setField("ATRIBUT_ID", $reqAtributId[$i]);
				$setdetil->setField("PELATIHAN_ID", $reqPelatihanId[$i]);
				$setdetil->setField("PELATIHAN_NAMA", $reqPelatihanNama[$i]);
				$setdetil->setField("PELATIHAN_HCDP_ID", $infoid);
				$setdetil->setField("NAMA_PELATIHAN", $infonama);
				$setdetil->setField("JP", ValToNullDB($reqJp[$i]));
				$setdetil->setField("TAHUN", ValToNullDB($reqTahun[$i]));
				$setdetil->setField("KETERANGAN", setQuote($reqKeterangan[$i]));
				$setdetil->insert();
				// echo $setdetil->query;exit;
				unset($setdetil);
			}
		}

		if(!empty($reqRumpunPengembanganId[$i]))
		{
			$arrdataid= explode(",", $reqRumpunPengembanganId[$i]);
			for($x=0; $x < count($arrdataid); $x++)
			{
				$infoid= $arrdataid[$x];
				$get= new PelatihanHcdp();
				$get->selectByParams(array("PELATIHAN_HCDP_ID" => $infoid), -1, -1, $statement);
				$get->firstRow();
				$infonama= $get->getField("NAMA");
				unset($get);

				$setdetil= new PegawaiHcdpDetil();
				$setdetil->setField("PEGAWAI_HCDP_ID", $reqRowId);
				$setdetil->setField("PEGAWAI_ID", $reqPegawaiId);
				$setdetil->setField("ATRIBUT_ID", $reqRumpunPengembanganAtributId[$i]);
				$setdetil->setField("PELATIHAN_ID", $reqRumpunPengembanganId[$i]);
				$setdetil->setField("PELATIHAN_NAMA", $reqRumpunPengembanganNama[$i]);
				$setdetil->setField("PELATIHAN_HCDP_ID", $infoid);
				$setdetil->setField("NAMA_PELATIHAN", $infonama);
				$setdetil->insertrumpun();
				// echo $setdetil->query;exit;
				unset($setdetil);
			}
		}

	}
}


$mode = 'Data berhasil disimpan';
echo $reqId."-".$mode;
?>
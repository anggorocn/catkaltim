<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPelatihan.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqId= httpFilterPost("reqId");

$reqRowPelatihanId= $_POST["reqRowPelatihanId"];
$reqLembaga= $_POST["reqLembaga"];
$reqTahun= $_POST["reqTahun"];
$reqNama= $_POST["reqNama"];
$reqNomor= $_POST["reqNomor"];
$reqPeriode= $_POST["reqPeriode"];

if($reqSubmit == "insert")
{
  //PelamarPelatihan
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
  unset($set);
  
  for($i=0; $i < count($reqRowPelatihanId); $i++)
  {
	  $reqRowId= $reqRowPelatihanId[$i];
	  
	  $set= new PelamarPelatihan();
	  $set->setField("PELAMAR_PELATIHAN_ID", $reqRowId);
	  $set->setField("PELAMAR_ID", $reqId);
	  
	  $set->setField("TANGGAL", dateToDBCheck($req));
	  $set->setField("TAHUN", $reqTahun[$i]);
	  $set->setField("NOMOR", $reqNomor[$i]);
	  $set->setField("NAMA", $reqNama[$i]);
	  $set->setField("LEMBAGA", $reqLembaga[$i]);
	  
	  if($reqRowId == "")
	  {
		  if($set->insert())
			$tempStatus++;
	  }
	  else
	  {
		  if($set->update())
			$tempStatus++;
	  }
	  //if($i == 1)
	  //{
	  	//echo $set->query;exit;
	  //}
	  unset($set);
  }
  
  if($tempIsStatusIsiFormulir == "4")
  {
	$set_status_formulir= new Pelamar();
	$set_status_formulir->setField("PELAMAR_ID", $reqId);
	//$set_status_formulir->setField("IS_STATUS_ISI_FORMULIR", 5);
	$set_status_formulir->setField("IS_STATUS_ISI_FORMULIR", 6);
	$set_status_formulir->updateStatusIsiFormulir();
	unset($set_status_formulir);
  }
  
  if($tempStatus > 0)
  	echo $id."-Data berhasil disimpan.";
  
  //echo $set->query;
}
?>
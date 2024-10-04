<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPendidikan.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqId= httpFilterPost("reqId");

$reqRowPendidikanId= $_POST["reqRowPendidikanId"];
$reqPendidikanId= $_POST["reqPendidikanId"];
$reqJurusan= $_POST["reqJurusan"];
$reqNama= $_POST["reqNama"];
$reqNomor= $_POST["reqNomor"];
$reqPeriode= $_POST["reqPeriode"];

if($reqSubmit == "insert")
{
  //PelamarPendidikan
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
  unset($set);
  
  for($i=0; $i < count($reqRowPendidikanId); $i++)
  {
	  $reqRowId= $reqRowPendidikanId[$i];
	  
	  $set= new PelamarPendidikan();
	  $set->setField("PELAMAR_PENDIDIKAN_ID", $reqRowId);
	  $set->setField("PELAMAR_ID", $reqId);
	  
	  $set->setField("TANGGAL", dateToDBCheck($req));
	  $set->setField("JURUSAN", $reqJurusan[$i]);
	  $set->setField("NOMOR", $reqNomor[$i]);
	  $set->setField("NAMA", $reqNama[$i]);
	  $set->setField("PENDIDIKAN_ID", ValToNullDB($reqPendidikanId[$i]));
	  
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
  
  if($tempIsStatusIsiFormulir == "3")
  {
	$set_status_formulir= new Pelamar();
	$set_status_formulir->setField("PELAMAR_ID", $reqId);
	$set_status_formulir->setField("IS_STATUS_ISI_FORMULIR", 4);
	$set_status_formulir->updateStatusIsiFormulir();
	unset($set_status_formulir);
  }
  
  if($tempStatus > 0)
  	echo $id."-Data berhasil disimpan.";
  
  //echo $set->query;
}
?>
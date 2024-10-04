<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPrestasi.php");
include_once("../WEB/classes/base/PelamarKaryaTulis.php");
include_once("../WEB/classes/base/PelamarKegiatanSosial.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqId= httpFilterPost("reqId");

$reqRowPrestasiId= $_POST["reqRowPrestasiId"];
$reqPrestasiTingkat= $_POST["reqPrestasiTingkat"];
$reqPrestasiTahun= $_POST["reqPrestasiTahun"];
$reqPrestasiNama= $_POST["reqPrestasiNama"];
$reqPrestasiPenghargaan= $_POST["reqPrestasiPenghargaan"];
$reqPeriode= $_POST["reqPeriode"];

$reqRowKaryaTulisId= $_POST["reqRowKaryaTulisId"];
$reqKaryaTulisTahun= $_POST["reqKaryaTulisTahun"];
$reqKaryaTulisNama= $_POST["reqKaryaTulisNama"];

$reqRowKegiatanSosialId= $_POST["reqRowKegiatanSosialId"];
$reqKegiatanSosialTahun= $_POST["reqKegiatanSosialTahun"];
$reqKegiatanSosialNama= $_POST["reqKegiatanSosialNama"];
$reqKegiatanSosialJabatan= $_POST["reqKegiatanSosialJabatan"];

if($reqSubmit == "insert")
{
  //PelamarPrestasi
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
  unset($set);
  
  for($i=0; $i < count($reqRowPrestasiId); $i++)
  {
	  $reqRowId= $reqRowPrestasiId[$i];
	  
	  $set= new PelamarPrestasi();
	  $set->setField("PELAMAR_PRESTASI_ID", $reqRowId);
	  $set->setField("PELAMAR_ID", $reqId);
	  
	  $set->setField("TANGGAL", dateToDBCheck($req));
	  $set->setField("TAHUN", $reqPrestasiTahun[$i]);
	  $set->setField("PENGHARGAAN", $reqPrestasiPenghargaan[$i]);
	  $set->setField("NAMA", $reqPrestasiNama[$i]);
	  $set->setField("TINGKAT", $reqPrestasiTingkat[$i]);
	  
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
  
  //PelamarKaryaTulis
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
  unset($set);
  
  for($i=0; $i < count($reqRowKaryaTulisId); $i++)
  {
	  $reqRowId= $reqRowKaryaTulisId[$i];
	  
	  if($reqKaryaTulisNama[$i] == "")
	  {
		  if($reqRowId == ""){}
		  else
		  {
			  $set= new PelamarKaryaTulis();
			  $set->setField("PELAMAR_KARYA_TULIS_ID", $reqRowId);
			  $set->setField("PELAMAR_ID", $reqId);
			  $set->delete();
			  unset($set);
		  }
	  }
	  else
	  {
		  $set= new PelamarKaryaTulis();
		  $set->setField("PELAMAR_KARYA_TULIS_ID", $reqRowId);
		  $set->setField("PELAMAR_ID", $reqId);
		  
		  $set->setField("TANGGAL", dateToDBCheck($req));
		  $set->setField("TAHUN", $reqKaryaTulisTahun[$i]);
		  $set->setField("NAMA", $reqKaryaTulisNama[$i]);
		  
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
  }
  
  //PelamarKegiatanSosial
  $reqId= $userLogin->userPelamarEnkripId;
  $set= new Pelamar();
  $set->selectByParams(array("md5(CAST(PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
  $set->firstRow();
  $reqId= $set->getField("PELAMAR_ID");
  $tempIsStatusIsiFormulir= $set->getField("IS_STATUS_ISI_FORMULIR");
  unset($set);
  
  for($i=0; $i < count($reqRowKegiatanSosialId); $i++)
  {
	  $reqRowId= $reqRowKegiatanSosialId[$i];
	  
	  if($reqKegiatanSosialNama[$i] == "")
	  {
		  if($reqRowId == ""){}
		  else
		  {
			  $set= new PelamarKegiatanSosial();
			  $set->setField("PELAMAR_KEGIATAN_SOSIAL_ID", $reqRowId);
			  $set->setField("PELAMAR_ID", $reqId);
			  $set->delete();
			  unset($set);
		  }
	  }
	  else
	  {
		  $set= new PelamarKegiatanSosial();
		  $set->setField("PELAMAR_KEGIATAN_SOSIAL_ID", $reqRowId);
		  $set->setField("PELAMAR_ID", $reqId);
		  
		  $set->setField("TANGGAL", dateToDBCheck($req));
		  $set->setField("TAHUN", $reqKegiatanSosialTahun[$i]);
		  $set->setField("JABATAN", $reqKegiatanSosialJabatan[$i]);
		  $set->setField("NAMA", $reqKegiatanSosialNama[$i]);
		  
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
  }
  
  if($tempIsStatusIsiFormulir == "6")
  {
	$set_status_formulir= new Pelamar();
	$set_status_formulir->setField("PELAMAR_ID", $reqId);
	$set_status_formulir->setField("IS_STATUS_ISI_FORMULIR", 7);
	$set_status_formulir->updateStatusIsiFormulir();
	unset($set_status_formulir);
  }
  
  if($tempStatus > 0)
  	echo $id."-Data berhasil disimpan.";
  
  //echo $set->query;
}
?>
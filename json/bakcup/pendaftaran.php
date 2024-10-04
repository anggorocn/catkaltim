<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/utils/FileHandler.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqRowId= httpFilterPost("reqRowId");

if($reqSubmit == "update")
{
  $pelamar= new Pelamar();
  $pelamar->setField("FIELD", "PAKTA_INTEGRITAS");
  $pelamar->setField("FIELD_VALUE", "1");
  $pelamar->setField("PELAMAR_ID", $userLogin->userPelamarId);
  if($pelamar->updateByField())
  {
		echo "Data berhasil disimpan.";
  }
  
}
?>
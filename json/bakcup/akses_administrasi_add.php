<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/AksesAdmRekrutmen.php");
include_once("../WEB/classes/base/AksesAdmRekrutmenMenu.php");
include_once("../WEB/classes/utils/UserLogin.php");

$akses_adm_intranet = new AksesAdmRekrutmen();
$akses_adm_intranet_menu = new AksesAdmRekrutmenMenu();

$reqMode = httpFilterPost("reqMode");
$reqMenuId = $_POST["reqMenuId"];
$reqCheck = $_POST["reqCheck"];
$reqNama = $_POST["reqNama"];
$reqAksesIntranet = $_POST["reqAksesIntranet"];
$reqTable = $_POST["reqTable"];

if($reqMode == "insert")
{	
	  $akses_adm_intranet->setField("NAMA", $reqNama);
	  $akses_adm_intranet->setField("TABLE", $reqTable);
	  $akses_adm_intranet->insert();

	  for($i=0;$i<count($reqMenuId);$i++)
	  {
		  $akses_adm_intranet_menu = new AksesAdmRekrutmenMenu();
	 	  $akses_adm_intranet_menu->setField("AKSES_ADM_REKRUTMEN_ID", $akses_adm_intranet->id);
	 	  $akses_adm_intranet_menu->setField("MENU_ID", $reqMenuId[$i]);
	 	  $akses_adm_intranet_menu->setField("AKSES", $reqCheck[$i]);
	 	  $akses_adm_intranet_menu->setField("TABLE", $reqTable);
		  $akses_adm_intranet_menu->insert();
		  unset($akses_adm_intranet_menu);	  
	  }
		  echo "Data berhasil disimpan.";
}
else
{
	  $akses_adm_intranet->setField("NAMA", $reqNama);
	  $akses_adm_intranet->setField("AKSES_ADM_REKRUTMEN_ID", $reqAksesIntranet);	  
	  $akses_adm_intranet->setField("TABLE", $reqTable);
	  $akses_adm_intranet->update();

	  $akses_adm_intranet_menu->setField("AKSES_ADM_REKRUTMEN_ID", $reqAksesIntranet);
	  $akses_adm_intranet_menu->setField("TABLE", $reqTable);
	  $akses_adm_intranet_menu->delete();
	
	  for($i=0;$i<count($reqMenuId);$i++)
	  {
		  $akses_adm_intranet_menu = new AksesAdmRekrutmenMenu();
	 	  $akses_adm_intranet_menu->setField("AKSES_ADM_REKRUTMEN_ID", $reqAksesIntranet);
	 	  $akses_adm_intranet_menu->setField("MENU_ID", $reqMenuId[$i]);
	 	  $akses_adm_intranet_menu->setField("AKSES", $reqCheck[$i]);
	 	  $akses_adm_intranet_menu->setField("TABLE", $reqTable);
		  $akses_adm_intranet_menu->insert();
		  unset($akses_adm_intranet_menu);	  
	  }
		  echo "Data berhasil disimpan.";
}
?>
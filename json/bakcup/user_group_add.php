<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UserGroup.php");
include_once("../WEB/classes/utils/UserLogin.php");

$user_group = new UserGroup();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNama = httpFilterPost("reqNama");
$reqAksesIntranet = httpFilterPost("reqAksesIntranet");
//echo "kode:".$reqAplikasiGalangan;
if(($reqMode == "add") || ($reqMode == "copy"))
{
	$user_group->setField("NAMA", $reqNama);
	$user_group->setField("AKSES_ADM_REKRUTMEN_ID", $reqAksesIntranet);
	
	if($user_group->insert())
	{	//echo $user_group->query;
		echo "Data berhasil disimpan.";
	}
}
elseif($reqMode == "edit")
{
	$user_group->setField("USER_GROUP_ID", $reqId);
	$user_group->setField("NAMA", $reqNama);
	$user_group->setField("AKSES_ADM_REKRUTMEN_ID", $reqAksesIntranet);

	if($user_group->update()) {
		echo "Data berhasil disimpan.";
	}
	
}
?>
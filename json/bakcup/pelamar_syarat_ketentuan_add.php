<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");

if($userLogin->userPelamarId)
{
	$set= new Pelamar();
	$set->setField("PELAMAR_ID", $userLogin->userPelamarId);
	if($set->updateStatusSyaratKetentuan())
	{
		echo "1";
	}
	unset($set);
}
?>
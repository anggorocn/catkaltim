<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/UpdateSkp.php");
ini_set('max_execution_time', 3000000);
// echo "Sasasa"; exit;
$reqNip 		= httpFilterRequest("reqNip");
$reqPegawaiId 		= httpFilterRequest("reqPegawaiId");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-skp/'.$reqNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);
for($i=0;$i<count($dataApi);$i++){
	$cekData= new UpdateSkp();
	$statement="and Pegawai_ID=".$reqPegawaiId." and skp_tahun='".$dataApi[$i]['tahun']."'";
	$cekData->selectByParamsCekData(array(),-1,-1, $statement);
	$cekData->firstRow();

	$MasukkanData= new UpdateSkp();
	$MasukkanData->setField("skp_TAHUN", $dataApi[$i]['tahun']);
	$MasukkanData->setField("nilai_skp", $dataApi[$i]['nilai_skp']);
	$MasukkanData->setField("pegawai_id", $reqPegawaiId);

	if($cekData->getField("riwayat_skp_id") == '')
	{
		if($MasukkanData->insert()){

		}
	}
	else
	{
		$MasukkanData->setField("riwayat_skp_id", $cekData->getField("riwayat_skp_id"));
		if($MasukkanData->update()){
		}
	}
}
echo '1';
exit;

?>
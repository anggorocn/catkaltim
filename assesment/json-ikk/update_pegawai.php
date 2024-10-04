<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-ikk/UpdatePegawaiApi.php");
ini_set('max_execution_time', 3000000);
ini_set('max_input_vars', 100000);
// echo "Sasasa"; exit;
$reqId 		= httpFilterRequest("reqId");
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

// if($reqId == '04'){
// 	echo '1';
// 	exit;
// }
 
$url = 'https://api-simpeg.kaltimbkd.info/data-pegawai/all/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa&opd='.$reqId;
$dataApi = json_decode(file_get_contents($url), true);

// print_r($dataApi);exit; 
for($i=0;$i<count($dataApi);$i++){
	$cekData= new UpdatePegawaiApi();
	$statement="and nip_baru='".$dataApi[$i]['nip_baru']."'";
	$cekData->selectByParamsCekData(array(),-1,-1, $statement);
	$cekData->firstRow();
	// echo $dataApi[$i]['nip_baru'];
	
	$MasukkanData= new UpdatePegawaiApi();
	$MasukkanData->setField("nip_baru",$dataApi[$i]['nip_baru'] );
	$MasukkanData->setField("nama",$dataApi[$i]['nama'] );
	$MasukkanData->setField("tempat_lahir",$dataApi[$i]['tempat_lahir'] );
	$MasukkanData->setField("tgl_lahir",$dataApi[$i]['tgl_lahir'] );
	$MasukkanData->setField("SATKER_ID",$reqId );

	if($dataApi[$i]['id_jenis_kelamin']=='Perempuan'){
		$id_jenis_kelamin=='P';
	}
	else{
		$id_jenis_kelamin=='L';
	}
	$MasukkanData->setField("jenis_kelamin",$id_jenis_kelamin);
	$MasukkanData->setField("alamat",$dataApi[$i]['alamat'] );
	$MasukkanData->setField("hp",$dataApi[$i]['no_hape'] );
	$MasukkanData->setField("email",$dataApi[$i]['email'] );
	$MasukkanData->setField("last_jabatan",$dataApi[$i]['jabatan'] );
	$MasukkanData->setField("last_eselon_id",$dataApi[$i]['id_golongan'] );

	// echo $cekData->getField("pegawai_id");exit;
	// print_r($MasukkanData);exit;

	if($cekData->getField("pegawai_id") == '')
	{
		if($MasukkanData->insert()){
			// $MasukkanData->query();exit;
		}
	}
	else
	{
		$MasukkanData->setField("pegawai_id", $cekData->getField("pegawai_id"));
		if($MasukkanData->update()){
			// $MasukkanData->query();exit;
		}
	}
	// echo $i;
	ob_flush();
    flush();
    sleep(10);
}

echo '1';
exit;

?>
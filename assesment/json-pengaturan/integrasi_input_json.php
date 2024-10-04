<?
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/IntegrasiBaru.php");

$reqMode = httpFilterGet("reqMode");

$set	= new IntegrasiBaru();
$set->selectByParamsMonitoring(array(), -1, -1);
if($reqMode == "skp"){
	$set->deleteskp();
}
else if($reqMode == "penghargaan"){
	$set->deletepenghargaan();
}
while($set->nextRow()){
	$reqNip= $set->getField("NIP_BARU");
	if($reqMode == "skp")
	{
		$data=file_get_contents('https://api-simpeg.kaltimbkd.info/pns/riwayat-skp/'.$reqNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa');
		$data=json_decode($data);
		for($i=0;$i<count($data);$i++){
			$datadetil=json_decode(json_encode($data[$i]), true);
			$insert	= new IntegrasiBaru();
			$insert->setField("TAHUN", $datadetil['tahun']);
			$insert->setField("NIP_BARU", $datadetil['nip_baru']);	
			$insert->setField("NILAI_PENGUKURAN", ValToNull($datadetil['nilai_pengukuran']));	
			$insert->setField("RATA_PERILAKU", ValToNull($datadetil['rata_perilaku']));	
			$insert->setField("NILAI_SKP", ValToNull($datadetil['nilai_skp']));	
			$insert->setField("NILAI_KINERJA", ValToNull($datadetil['nilai_kinerja']));	
			$insert->setField("HASIL_KARYA", ValToNull($datadetil['hasil_kerja']));	
			$insert->setField("PERILAKU_KERJA", ValToNull($datadetil['perilaku_kerja']));	
			$insert->setField("KINERJA_PEGAWAI", ValToNull($datadetil['kinerja_pegawai']));	
			$insert->setField("STATUS_FILE", $datadetil['status_file']);	
			$insert->setField("FILE_SKP", $datadetil['file_skp']);	
			$insert->setField("PENILAI", $datadetil['penilai']);	
			$insert->setField("NIP_PENILAI", $datadetil['nip_penilai']);	
			$insert->setField("ATASAN_PENILAI", $datadetil['atasan_penilai']);	
			$insert->setField("NIP_ATASAN_PENILAI", $datadetil['nip_atasan_penilai']);
			$insert->insertSKP();	
			// echo $i;
		}
	}
	
	else if($reqMode == "penghargaan"){
		$data=file_get_contents('https://api-simpeg.kaltimbkd.info/pns/riwayat-penghargaan/'.$reqNip.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa');
		$data=json_decode($data);
		for($i=0;$i<count($data);$i++){
			$datadetil=json_decode(json_encode($data[$i]), true);
			$insert	= new IntegrasiBaru();
			$insert->setField("NIP_BARU", $datadetil['nip_baru']);	
			$insert->setField("penghargaan", $datadetil['penghargaan']);	
			$insert->setField("id_penghargaan", ValToNullDB($datadetil['id_penghargaan']));	
			$insert->setField("tgl_penghargaan", ValToNullDB($datadetil['tgl_penghargaan']));	
			$insert->setField("STATUS_FILE", $datadetil['status_file']);	
			$insert->setField("FILE_penghargaan", $datadetil['file_penghargaan']);	
			$insert->insertPenghargaan();	
			// echo $i;
		}
	}

}
$alertMsg = "Data berhasil diintergasikan";

echo $alertMsg;exit;
?>
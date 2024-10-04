<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/UjianBankSoal.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/utils/UserLogin.php");

$ujian_tahap = new UjianTahap();

$reqId= httpFilterPost("reqId");
$reqTotalWaktu= httpFilterPost("reqTotalWaktu");

$reqRowId= $_POST["reqRowId"];
$reqTipeUjian= $_POST["reqTipeUjian"];
$reqMenitSoal= $_POST["reqMenitSoal"];
$reqBobot= $_POST["reqBobot"];
$reqJumlahSoalUjianTahap= $_POST["reqJumlahSoalUjianTahap"];
$reqStatusAnak= $_POST["reqStatusAnak"];
$reqIdTree= $_POST["reqIdTree"];
//print_r($reqIdTree);exit;

$tempTotalMenit=0;
for($i=0; $i<count($reqTipeUjian); $i++)
{
	if($reqTipeUjian[$i] == ""){}
	else
	{
		$tempTotalMenit+= $reqMenitSoal[$i];
		$ujian_tahap = new UjianTahap();
		$ujian_tahap->setField("UJIAN_ID", $reqId);
		$ujian_tahap->setField("TIPE_UJIAN_ID", ValToNullDB($reqTipeUjian[$i]));
		$ujian_tahap->setField("BOBOT", ValToNullDB($reqBobot[$i]));
		$ujian_tahap->setField("MENIT_SOAL", ValToNullDB($reqMenitSoal[$i]));
		$ujian_tahap->setField("JUMLAH_SOAL_UJIAN_TAHAP", ValToNullDB($reqJumlahSoalUjianTahap[$i]));
		
		if($reqRowId[$i] == "")
		{
			$ujian_tahap->setField("LAST_CREATE_USER", $userLogin->nama);
			$ujian_tahap->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$ujian_tahap->insertJumlahSoal();
			
			//kalau nilai 1 maka loop n insert data anak
			if($reqStatusAnak[$i] == "1")
			{
				$statement= " AND PARENT_ID = '".$reqIdTree[$i]."'";
				$set= new TipeUjian();
				$set->selectByParamsJumlahSoal(array(), -1,-1,$statement);
				while($set->nextRow())
				{
					$set_detil= new UjianTahap();
					$set_detil->setField("UJIAN_ID", $reqId);
					$set_detil->setField("TIPE_UJIAN_ID", ValToNullDB($set->getField("TIPE_UJIAN_ID")));
					$set_detil->setField("BOBOT", ValToNullDB($req));
					$set_detil->setField("MENIT_SOAL", ValToNullDB($set->getField("WAKTU")));
					$set_detil->setField("JUMLAH_SOAL_UJIAN_TAHAP", ValToNullDB($set->getField("JUMLAH_SOAL")));
					$set_detil->setField("LAST_CREATE_USER", $userLogin->nama);
					$set_detil->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_detil->insertJumlahSoal();
					unset($set_detil);
				}
				unset($set);
			}
		}
		else
		{
			$ujian_tahap->setField("LAST_UPDATE_USER", $userLogin->nama);
			$ujian_tahap->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
			$ujian_tahap->setField("UJIAN_TAHAP_ID", $reqRowId[$i]);
			$ujian_tahap->updateJumlahSoal();
		}
		//echo $ujian_tahap->query;exit;
		unset($ujian_tahap);
	}
}

$set_detil= new UjianBankSoal();
$set_detil->setField("UJIAN_ID", $reqId);
$set_detil->setField("LAST_CREATE_USER", $userLogin->nama);
$set_detil->getCountByParamsSimulasiBankSoal();
//echo $set_detil->query;exit;
unset($set_detil);

$reqTotalWaktu= $tempTotalMenit;
$set_detil= new Ujian();
$set_detil->setField("UJIAN_ID", $reqId);
$set_detil->setField("BATAS_WAKTU_MENIT", $reqTotalWaktu);
$set_detil->setField("LAST_UPDATE_USER", $userLogin->nama);
$set_detil->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
$set_detil->updateWaktu();
unset($set_detil);
echo $reqId."-Data Berhasil Disimpan.";
?>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/image.func.php");
include_once("../WEB/classes/base-portal/formulircritical.php");

$reqId= $userLogin->userPelamarId;

if($reqId == "")
{
	echo "autologin"; exit;
}

$reqJawaban= httpFilterPost("reqJawaban");
$reqPegawaiId= httpFilterPost("reqPegawaiId");
$reqBulan= httpFilterPost("reqBulan");
$reqTahun= httpFilterPost("reqTahun");
$reqSoalId= httpFilterPost("reqSoalId");

$reqTopik= httpFilterPost("reqTopik");
$reqSampai= httpFilterPost("reqSampai");
$reqTanggal= httpFilterPost("reqTanggal");

$reqJawabanTambahan= httpFilterPost("reqJawabanTambahan");
$reqSoalJawabanId= httpFilterPost("reqSoalJawabanId");

$reqSoalHeaderId= httpFilterPost("reqSoalHeaderId");

$statusKondisi1='<br>Membanggakan :';
$statusKondisi2='<br>Mengecewakan :';
$statusTopik1='<br>❌ Topik Bagian 1';
$statusTopik2='<br>❌ Topik Bagian 2';
$statusTanggal1='<br>❌ Tanggal Bagian 1 ';
$statusTanggal2='<br>❌ Tanggal Bagian 2 ';
$statusCerita1='<br>❌ Soal No 1 Bagian 1';
$statusAlasan1='<br>❌ Soal No 2 Bagian ';
$statusAnggota1='<br>❌ Soal No 3 Bagian 1';
$statusPerasaan1='<br>❌ Soal No 4 Bagian 1';
$statusUsaha1='<br>❌ Soal No 5 Bagian 1';
$statusAkhir1='<br>❌ Soal No 6 Bagian 1';
$statusCerita2='<br>❌ Soal No 1 Bagian 2';
$statusAlasan2='<br>❌ Soal No 2 Bagian 2';
$statusAnggota2='<br>❌ Soal No 3 Bagian 2';
$statusPerasaan2='<br>❌ Soal No 4 Bagian 2';
$statusUsaha2='<br>❌ Soal No 5 Bagian 2';
$statusAkhir2='<br>❌ Soal No 6 Bagian 2';

// print_r($reqSoalHeaderId);exit;


 
$set= new FormulirCritical();
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->delete();
unset($set);


$tempPesertaSimpan= "";

for ($i = 0; $i < count($reqTopik); $i++) {
	if ($reqTopik[$i] == "") {
	} else {
		$set= new FormulirCritical();
		// $set->setField("FORMULIR_CRITICAL_JAWABAN_ID", $reqSoalId[$i]);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("FORMULIR_SOAL_CRITICAL_HEADER_ID", $reqSoalId[$i]);
		$set->setField("TOPIK", $reqTopik[$i]);
		if($reqTopik[$i]!=''){
			if($i==0){
				$statusTopik1='';
			}
			else{
				$statusTopik2='';
			}
		}
		$set->setField("TANGGAL",  ValToNullDB($reqTanggal[$i]));
		$set->setField("BULAN", ValToNullDB($reqBulan[$i]));
		$set->setField("TAHUN", ValToNullDB($reqTahun[$i]));
		$set->setField("SAMPAI", ValToNullDB($reqSampai[$i]));
		
		if($reqTanggal[$i]!=''||$reqBulan[$i]!=''||$reqTahun[$i]!=''||$reqSampai[$i]!=''){
			if($i==0){
				$statusTanggal1='';
			}
			else{
				$statusTanggal2='';
			}
		}

		if($set->insert())
		{
			$tempPesertaSimpan =1;
		}
	}
}


$set= new FormulirCritical();
$set->setField("PEGAWAI_ID", $reqPegawaiId);
$set->deleteJawaban();
unset($set);

for ($i = 0; $i < count($reqJawabanTambahan); $i++) {
	if ($reqJawabanTambahan[$i] == "") {
	} else {
		$set= new FormulirCritical();
		// $set->setField("FORMULIR_CRITICAL_JAWABAN_ID", $reqSoalId[$i]);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID", $reqSoalJawabanId[$i]);
		$set->setField("FORMULIR_SOAL_CRITICAL_HEADER_ID", $reqSoalHeaderId[$i]);
		$set->setField("JAWABAN", $reqJawabanTambahan[$i]);
		
		if($reqJawabanTambahan[$i]!=''){
			if(($i+1) % 6==1){
				if(floor(($i+1)/6)==0){
					$statusCerita1='';
				}
				else{
					$statusCerita2='';
				}
			}
			else if (($i+1) % 6==2){
				if(floor(($i+1)/6)==0){
					$statusAlasan1='';
				}
				else{
					$statusAlasan2='';
				}
			}
			else if (($i+1) % 6==3){
				if(floor(($i+1)/6)==0){
					$statusAnggota1='';
				}
				else{
					$statusAnggota2='';
				}
			}
			else if (($i+1) % 6==4){
				if(floor(($i+1)/6)==0){
					$statusPerasaan1='';
				}
				else{
					$statusPerasaan2='';
				}
			}
			else if (($i+1) % 6==5){
				if(floor(($i+1)/6)==0){
					$statusUsaha1='';
				}
				else{
					$statusUsaha2='';
				}
			}
			else if (($i+1) % 6==0){
				if(floor(($i+1)/6)==2){
					$statusAkhir1='';
				}
				else{
					$statusAkhir2='';
				}
			}
		}

		if($set->insertJawaban())
		{
			$tempPesertaSimpan =1;
		}
	}
}

if($statusTopik1!=''||$statusTanggal1!=''||$statusCerita1!=''||$statusAlasan1!=''||$statusAnggota1!=''||$statusUsaha1!=''||$statusAkhir1!='')
{
	$status1=$statusKondisi1.$statusTopik1.$statusTanggal1.$statusCerita1.$statusAlasan1.$statusAnggota1.$statusUsaha1.$statusAkhir1;
}

if($statusTopik2!=''||$statusTanggal2!=''||$statusCerita2!=''||$statusAlasan2!=''||$statusAnggota2!=''||$statusUsaha2!=''||$statusAkhir2!='')
{
	$status2=$statusKondisi2.$statusTopik2.$statusTanggal2.$statusCerita2.$statusAlasan2.$statusAnggota2.$statusUsaha2.$statusAkhir2;
}



if($tempPesertaSimpan == 1)
{
	echo $tempPesertaId."-<center><h3><b>Data berhasil di simpan</b></h3>".$status1.$status2."</center>";

}
else
{
	echo $tempPesertaId."-Data gagal di simpan";
}

// else
// 	echo "xxx-Data gagal disimpan.";
// echo $set->query;exit;
unset($set);
?>
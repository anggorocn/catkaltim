<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/classes/utils/KMail.php");

$reqSubmit= httpFilterPost("reqSubmit");
$reqNPP= httpFilterPost("reqNPP");
$reqNoKtp= httpFilterPost("reqNoKtp");
$reqPassword= httpFilterPost("reqPassword");
$reqNama= httpFilterPost("reqNama");
$reqTelepon= httpFilterPost("reqTelepon");
$reqEmail= httpFilterPost("reqEmail");
$reqSecurity= httpFilterPost("reqSecurity");
$reqSumberInfo= httpFilterPost("reqSumberInfo");
$reqIsKerabat= httpFilterPost("reqIsKerabat");
$reqNamaKerabat= httpFilterPost("reqNamaKerabat");
$reqPosisiKerabat= httpFilterPost("reqPosisiKerabat");

$reqEmail = strtolower($reqEmail);
if($reqSubmit == "Daftar")
{
	if(md5($reqSecurity) == $_SESSION['security_code'])
	{
		  $pelamar= new Pelamar();
		  $user_base = new UsersBase();
		  
		  $reqNRP = $pelamar->getUrut();
			
		  $pelamar->setField('PELAMAR_ID', $reqId);
		  $pelamar->setField('DEPARTEMEN_ID', $reqDepartemen);
		  $pelamar->setField('NRP', $reqNRP);
		  $pelamar->setField('NIPP', $reqNPP);
		  $pelamar->setField('NAMA', setQuote(strtoupper($reqNama),1));
		  $pelamar->setField('AGAMA_ID', $reqAgamaId);
		  $pelamar->setField('JENIS_KELAMIN', $reqJenisKelamin);
		  $pelamar->setField('PELABUHAN_ID', $reqAsalPelabuhanId);
		  $pelamar->setField('TEMPAT_LAHIR', $reqTempat);
		  $pelamar->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggal));
		  $pelamar->setField('ALAMAT', $reqAlamat);
		  $pelamar->setField('TELEPON', $reqTelepon);
		  $pelamar->setField('EMAIL', $reqEmail);
		  $pelamar->setField('GOLONGAN_DARAH', $reqGolDarah);
		  $pelamar->setField('STATUS_KAWIN', $reqStatusPernikahan);
		  $pelamar->setField('STATUS_PELAMAR_ID', $reqStatusPegawai);
		  $pelamar->setField('BANK_ID', $reqBankId);
		  $pelamar->setField('REKENING_NO', $reqRekeningNo);
		  $pelamar->setField('REKENING_NAMA', $reqRekeningNama);
		  $pelamar->setField('NPWP', $reqNPWP);
		  $pelamar->setField('STATUS_KELUARGA_ID', $reqStatusKeluarga);
		  $pelamar->setField('JAMSOSTEK_NO', $reqJamsostek);
		  $pelamar->setField('JAMSOSTEK_TANGGAL', dateToDBCheck($reqJamsostekTanggal));
		  $pelamar->setField('KESEHATAN_NO', $reqKesehatan);
		  $pelamar->setField('KESEHATAN_TANGGAL', dateToDBCheck($reqKesehatanTanggal));
		  $pelamar->setField('HOBBY', $reqHobby);
		  $pelamar->setField('FINGER_ID', ValToNullDB($reqFingerId));
		  $pelamar->setField('TANGGAL_NPWP', dateToDBCheck($reqTanggalNpwp));
		  $pelamar->setField('TINGGI', $reqTinggi);
		  $pelamar->setField('BERAT_BADAN', $reqBeratBadan);
		  $pelamar->setField('NO_SEPATU', $reqNoSepatu);
		  $pelamar->setField('KTP_NO', $reqNoKtp);
		  $pelamar->setField('KELOMPOK_PEGAWAI', $reqKelompokPegawai);
		  $pelamar->setField('KK_NO', $reqKkNo);
		  $pelamar->setField('KESEHATAN_FASKES', $reqKesehatanFaskes);
		  $pelamar->setField('TANGGAL_PENSIUN', 'NULL');
		  $pelamar->setField('TANGGAL_MUTASI_KELUAR', 'NULL');
		  $pelamar->setField('TANGGAL_WAFAT', 'NULL');
		  $pelamar->setField('NO_MPP', 'NULL');
		  $pelamar->setField('TANGGAL_MPP', 'NULL');
		  $pelamar->setField('TGL_NON_AKTIF', 'NULL');
		  $pelamar->setField('TGL_DIKELUARKAN', 'NULL');
		  $pelamar->setField('TGL_KONTRAK_AKHIR', 'NULL');
		  $pelamar->setField("LAST_CREATE_USER", "PELAMAR");
		  $pelamar->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
		  $pelamar->setField('KOTA_ID', 'NULL');
		  $pelamar->setField('IS_KERABAT', $reqIsKerabat);
		  $pelamar->setField('NAMA_KERABAT', $reqNamaKerabat);
		  $pelamar->setField('POSISI_KERABAT', $reqPosisiKerabat);
		  $pelamar->setField('SUMBER_INFO', $reqSumberInfo);
		  
		  if($pelamar->insert()){
			  $id = $pelamar->id;
			  
			  $user_base->setField("NAMA", strtoupper($reqNama));
			  $user_base->setField("EMAIL", $reqEmail);
			  $user_base->setField("TELEPON", $reqTelepon);
			  $user_base->setField("USER_LOGIN", $reqEmail);
			  $user_base->setField("USER_PASS", $reqPassword);
			  $user_base->setField("STATUS", 1);
			  $user_base->setField("LAST_CREATE_USER", "PELAMAR");
			  $user_base->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			  $user_base->setField("PELAMAR_ID", $id);	
		  
			  if($user_base->insert())
			  {
					/*
					$xmlfile = "../WEB/weburl.xml";
					$data = simplexml_load_file($xmlfile);
					$linktemplate= $data->urlConfig->linkConfig->linktemplate;
					//$body = file_get_contents("http://".$_SERVER['SERVER_NAME']."/pds-rekrutmen/templates/konfirmasi_daftar.php?reqNama=".$reqNama."&reqUser=".$reqEmail."&reqPassword=".$reqPassword);
					$body = file_get_contents("http://".$_SERVER['SERVER_NAME'].$linktemplate."konfirmasi_daftar.php?reqNama=".$reqNama."&reqUser=".$reqEmail."&reqPassword=".$reqPassword);
					
					$mail = new KMail("backup");
					
					$mail->AddAddress($reqEmail , $reqNama);
					//	$mail->AddAddress("rosyidi.alhamdani@valsix.co.id" , "Rosyidi Alhamdani");
					//	$mail->AddAddress("riza@ptpds.co.id", "Riza Akhmad Juliantoko"); 
					$mail->Subject  =  "Konfirmasi Akun - Career and Recruitment Center PT Pelindo Daya Sejatera";
					$mail->MsgHTML($body);
					$mail->Send();*/
					
					echo "Data berhasil disimpan.";
					$userLogin->verifyUserLogin($reqEmail, $reqPassword);
			  }
	  
		  }
	}
	else
		echo "Masukkan captcha dengan benar.";
}
?>
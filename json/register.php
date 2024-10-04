<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/FileHandler.php");
// include_once("../WEB/classes/utils/KMail.php");
// include_once("../WEB/classes/base/Pelamar.php");
// include_once("../WEB/classes/base/UsersBase.php");

include_once("../WEB/classes/base-diklat/Peserta.php");


$reqSubmit= httpFilterPost("reqSubmit");
$reqStatusJenis= httpFilterPost("reqStatusJenis");
$reqNoKtp= httpFilterPost("reqNoKtp");
$reqPassword= httpFilterPost("reqPassword");
$reqNama= httpFilterPost("reqNama");
$reqEmail= httpFilterPost("reqEmail");
$reqSecurity= httpFilterPost("reqSecurity");
$reqEmail = strtolower($reqEmail);

if($reqSubmit == "Daftar")
{
	if(md5($reqSecurity) == $_SESSION['security_code'])
	{
		$statement= " AND (A.NIP_BARU = '".$reqNoKtp."' OR A.NIK = '".$reqNoKtp."')";
		$set= new Peserta();
		$set->selectByParams(array(), -1,-1, $statement);
		$set->firstRow();
		$tempPesertaId= $set->getField("PESERTA_ID");
		unset($set);
		
		if($tempPesertaId == "")
		{
			$set= new Peserta();
			$set->setField("NIP", $reqNoKtp);
			$set->setField("NAMA", ucwordsPertama($reqNama));
			$set->setField("EMAIL", $reqEmail);
			$set->setField("STATUS_JENIS", $reqStatusJenis);
			
			$tempPesertaSimpan= "";
			//kalau tidak ada maka simpan
			if($tempPesertaId == "")
			{
				if($reqStatusJenis == "1" )
					$set->insertRegistrasi();
				elseif($reqStatusJenis == "2" )
					$set->insertRegistrasiLuar();
				else
					$set->insertRegistrasiLain();

				$tempPesertaId= $set->id;
				$tempPesertaSimpan= 1;

				$setdetil= new Peserta();
				$setdetil->setField("PASSWORD_LOGIN", md5($reqPassword));
				$setdetil->setField("PESERTA_ID", $tempPesertaId);
				$setdetil->updatePassword();
				// echo $setdetil->query;exit();
			}
			// echo $set->query;exit;
			unset($set);

			if($tempPesertaSimpan == "1")
			{
				// echo "Data berhasil disimpan.";
				$userLogin->verifyUserLogin($reqNoKtp, $reqPassword);
			}
			else
			{
				echo $reqNoKtp.", Data Sudah Ada";
			}
		}
	}
	else
		echo "Masukkan captcha dengan benar.";
}
?>
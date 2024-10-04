<?
define ("MAX_SIZE","400");
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar = new Pelamar();
$user_login = new UserLogin();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqNamaDokumen = httpFilterPost('reqNamaDokumen');
$reqNamaLampiran = httpFilterPost('reqNamaLampiran');
$reqLampiran = $_FILES[$reqNamaLampiran];

if($reqNamaLampiran == "reqLampiranFoto" || $reqNamaLampiran == "reqLampiranKTP")
{
	$ekstensi = "jpg,jpeg,png";
	$ket = "JPG/JPEG/PNG";
}
else
{
	$ekstensi = "pdf";
	$ket = "PDF";
}

/* START UPLOAD FILE */
$insertLinkFile = "";
$FILE_DIR = "../uploads/";
for($i=0;$i<count($reqLampiran);$i++)
{	
	if($reqLampiran['name'][$i] == "")
	{}
	else			
	{
		$file_ext=strtolower(end(explode('.',$reqLampiran['name'][$i])));
		//if($file_ext != $ekstensi)
		if(findWord($ekstensi, $file_ext) == "")
		{
			echo "Ekstensi tidak diperbolehkan, silahkan pilih file ".$ket.".";
		}
		else
		{
			if($reqLampiran['size'][$i] > MAX_SIZE*1024)
			{
				echo "Data yang anda unggah terlalu besar";
			}
			else
			{
				$renameFile = md5(date("dmYHis").$reqLampiran['name'][$i]).".".getExtension($reqLampiran['name'][$i]);
				if (move_uploaded_file($reqLampiran['tmp_name'][$i], $FILE_DIR.$renameFile))
				{
					if($i == 0)	
						$insertLinkFile = $renameFile;
					else
						$insertLinkFile .= ",".$renameFile;
					
				}
			}
		}
	}	
}		

if($insertLinkFile == "")
{}
else
{
	$pelamar->setField("FIELD", $reqNamaDokumen);
	$pelamar->setField("FIELD_VALUE", $insertLinkFile);
	$pelamar->setField("PELAMAR_ID", $userLogin->userPelamarId);
	$pelamar->updateByField();
	
	//echo "Data berhasil disimpan.";
}


?>
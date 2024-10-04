<?
define ("MAX_SIZE","400");
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarDokumen.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pelamar = new Pelamar();
$user_login = new UserLogin();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");

$reqDokumenId = httpFilterPost('reqDokumenId');
$reqRowId = httpFilterPost('reqRowId');
$reqFormat = httpFilterPost('reqFormat');
$reqPelamarId = httpFilterPost('reqPelamarId');
$reqNamaLampiran = httpFilterPost('reqNamaLampiran');
$reqPenamaanFile = httpFilterPost('reqPenamaanFile');

//echo $reqNamaLampiran;exit;
$reqLampiran 	= $_FILES[$reqNamaLampiran];

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlfoto;
$urlfoto.="/".$reqPelamarId."/";

$insertLinkFile = "";
$FILE_DIR = $urlfoto;
makedirs($FILE_DIR);

for($i=0;$i<count($reqLampiran);$i++)
{
	if($reqLampiran['name'][$i] == "")
	{}
	else			
	{
		$file_ext=strtolower(end(explode('.',$reqLampiran['name'][$i])));
		//if($file_ext != $ekstensi)
		if(findWord($reqFormat, $file_ext) == "")
		{
			echo "Ekstensi tidak diperbolehkan, silahkan pilih file ".$reqFormat.".";
		}
		else
		{
				if($reqLampiran['size'][$i] > MAX_SIZE*1024)
			{
				echo "Data yang anda unggah terlalu besar".$reqPelamarId;
			}
			else
			{
				if($reqFormat == "jpg,jpeg,png")
				{
					$reqFormat= "png";
				}

				// $renameFile = md5(date("dmYHis").$reqLampiran['name'][$i]).".".getExtension($reqLampiran['name'][$i]);
				$renameFile = $reqPenamaanFile.".".$reqFormat;
				if (move_uploaded_file($reqLampiran['tmp_name'][$i], $FILE_DIR.$renameFile))
				{

				}
			}
		}
	}	
}
?>
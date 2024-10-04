<?
define ("MAX_SIZE","400");
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$reqId = httpFilterPost("reqId");
$reqPelamarId = httpFilterPost("reqPelamarId");
$reqMode = httpFilterPost("reqMode");
$reqNamaDokumen = httpFilterPost('reqNamaDokumen');
$reqFormat = httpFilterPost('reqFormat');
$reqNamaLampiran = httpFilterPost('reqNamaLampiran');
$reqLampiran = $_FILES[$reqNamaLampiran];
// $reqLampiran = $_FILES[$reqLampiran];

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlupload= $data->urlConfig->main->urlupload;
$urlupload.="diklatlampiran/".$reqId."/".$reqPelamarId."/";
$FILE_DIR = $urlupload;
// echo $FILE_DIR;exit();
makedirs($FILE_DIR, 0777);

for($i=0;$i<count($reqLampiran);$i++)
{	
	if($reqLampiran['name'][$i] == "")
	{}
	else			
	{
		$file_ext=strtolower(end(explode('.',$reqLampiran['name'][$i])));
		if(findWord($reqFormat, $file_ext) == "")
		{
			echo "Ekstensi tidak diperbolehkan, silahkan pilih file ".$reqFormat.".";
		}
		else
		{
			
			if($reqLampiran['size'][$i] > MAX_SIZE*1024)
			{
				echo "Data yang anda unggah terlalu besar";
			}
			else
			{
				if($reqFormat == "jpg,jpeg,png")
				{
					$reqFormat= "png";
				}

				// $renameFile = md5(date("dmYHis").$reqLampiran['name'][$i]).".".getExtension($reqLampiran['name'][$i]);
				$renameFile = $reqNamaDokumen.".".$reqFormat;
				if (move_uploaded_file($reqLampiran['tmp_name'][$i], $FILE_DIR.$renameFile))
				{
				}
			}
		}			
	}	
}
?>
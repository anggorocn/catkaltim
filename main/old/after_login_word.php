<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");
require_once("../WEB/lib/PHPWord/PHPWord.php");

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;

if($tempUserPelamarId == "")
{
    exit();
}

$reqId= httpFilterGet("reqId");

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlfoto;
$urlfoto.="/".$tempUserPelamarId."/";
$tempPegawaiFoto= $urlfoto.$tempUserPelamarNip.".png";


$statement= " AND A.PESERTA_ID = ".$tempUserPelamarId." AND DP.DIKLAT_PESERTA_ID= ".$reqId;
$set_peserta= new Peserta();
$set_peserta->selectByParamsWord(array(), -1,-1, $statement);
// echo $set_peserta->query;exit();
$set_peserta->firstRow();
// $tempFoto= $set_peserta->getField("FOTO_LINK");
// $tempKTP= $set_peserta->getField("NIP");

//declare image
$arrImagenes =  array(
    $tempPegawaiFoto
);
// print_r($arrImagenes);exit();

$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate('../Templates/biodata_1.docx');

if(file_exists("$tempPegawaiFoto"))
{
    //set Image
    $document->replaceStrToImg('Value1', $arrImagenes);
}

$tempPesertaNama= $set_peserta->getField("NAMA");
$tempPesertaNIP= $set_peserta->getField("NIP");
$tempPesertaJenisKelamin = $set_peserta->getField("JENIS_KELAMIN_NAMA");
$tempPesertaTempat= $set_peserta->getField("TEMPAT_LAHIR");
$tempPesertaTanggal= getFormattedDate($set_peserta->getField("TANGGAL_LAHIR"));
$tempPesertaAgama= $set_peserta->getField("AGAMA");
$tempPesertaPangkat= $set_peserta->getField("GOL_RUANG");
$tempPesertaJabatan= $set_peserta->getField("JABATAN");
$tempPesertaUnitKerja= $set_peserta->getField("UNIT_KERJA_KOTA");
$tempPesertaAlamatKantor= $set_peserta->getField("ALAMAT_KANTOR");
$tempPesertaKantorTelp= $set_peserta->getField("ALAMAT_KANTOR_TELP");
$tempPesertaKantorFax= $set_peserta->getField("ALAMAT_KANTOR_FAX");
$tempPesertaAlamatRumah= $set_peserta->getField("ALAMAT_RUMAH");
$tempPesertaRumahTelp= $set_peserta->getField("ALAMAT_RUMAH_TELP");
$tempPesertaRumahFax= $set_peserta->getField("ALAMAT_RUMAH_FAX");
$tempPesertaPendidikan= $set_peserta->getField("PENDIDIKAN_TERAKHIR");
$tempPesertaPelatihan= $set_peserta->getField("PELATIHAN");
$tempPesertaNPWP= $set_peserta->getField("NPWP");
$tempNamaDiklat= $set_peserta->getField("NAMA_DIKLAT");
$tempTanggal= getFormattedDate(date("Y-m-d"));

$document->setValue("REQNAMA", $tempPesertaNama);
$document->setValue("REQNIP", $tempPesertaNIP);
$document->setValue("REQJK", $tempPesertaJenisKelamin);
$document->setValue("REQTEMPATLAHIR", $tempPesertaTempat);
$document->setValue("REGTANGGALLAHIR", $tempPesertaTanggal);
$document->setValue("REQAGAMA", $tempPesertaAgama);
$document->setValue("REQPANGKAT", $tempPesertaPangkat);
$document->setValue("REQJABATAN", $tempPesertaJabatan);
$document->setValue("REQUNITKERJA", $tempPesertaUnitKerja);
$document->setValue("REQALAMATKANTOR", $tempPesertaAlamatKantor);
$document->setValue("REQKANTRORTELP", $tempPesertaKantorTelp);
$document->setValue("REQKANTORFAX", $tempPesertaKantorFax);
$document->setValue("REQALAMATRUMAH", $tempPesertaAlamatRumah);
$document->setValue("REQRUMAHTELP", $tempPesertaRumahTelp);
$document->setValue("REQRUMAAHFAX", $tempPesertaRumahFax);
$document->setValue("REQPENDIDIKAN", $tempPesertaPendidikan);
$document->setValue("REQPELATIHAN", $tempPesertaPelatihan);
$document->setValue("REQDIKLATNAMA", $tempNamaDiklat);
$document->setValue("REQNPWP", $tempPesertaNPWP);
$document->setValue("REQTANGGALNOW", $tempTanggal);
$document->setValue("REQFOTOLINK", $tempFoto);

$document->save('../Templates/biodata'.$tempPesertaNIP.'.docx');
$file = '../Templates/biodata'.$tempPesertaNIP.'.docx';

$down = $file;
header('Content-Description: File Transfer');
// header('Content-Type: application/octet-stream');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename='.basename($down));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
ob_clean();
flush();
readfile($down);
unlink($down);
unset($oPrinter);
exit;
        
exit();
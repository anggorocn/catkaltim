<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;

if($tempUserPelamarId == "")
{
    exit();
}

$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");
$tempSystemTanggalNow= date("d-m-Y");

$arrayJudul= "";
$arrayJudul= setJudul($lang);

$userLogin->checkLoginPelamar();

$reqId= httpFilterGet("reqId");

$statement= " AND A.JADWAL_AWAL_TES_SIMULASI_ID = ".$reqId;
$pelamar_lowongan = new PelamarLowongan ();
$pelamar_lowongan->selectByParamsDaftarLowonganPegawai($tempUserPelamarId, array(), -1,-1, $statement);
$pelamar_lowongan->firstRow();
$tempPesertaNama= $pelamar_lowongan->getField("nama_pegawai");
$tempPesertaNipBaru= $pelamar_lowongan->getField("nip_baru");
$tempPesertaJabatan= $pelamar_lowongan->getField("last_jabatan");
$reqDiklat= $pelamar_lowongan->getField("NAMA_DIKLAT");
$reqTglDiklat= $pelamar_lowongan->getField("tanggal_awal");
// echo $pelamar_lowongan->query;exit();

include_once("../WEB/lib/phpqrcode/qrlib.php");
$PNG_TEMP_DIR= '../WEB/lib/phpqrcode/uploads/';
$encrypt_text = $tempPesertaNipBaru;
$tempLink= $PNG_TEMP_DIR.$tempPesertaNipBaru.'.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 5;
QRcode::png($encrypt_text, $tempLink, $errorCorrectionLevel, $matrixPointSize, 2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html moznomarginboxes mozdisallowselectionprint>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Bukti Registrasi</title>
<style>
@page  
{ 
    size: auto;   /* auto is the initial value */ 

    /* this affects the margin in the printer settings */ 
    margin: 1mm 0mm 0mm 1mm;
    
} 
body{
    font-family:Arial, Helvetica, sans-serif;
    font-size:14px;
}
/*table.kartu_tanda_peserta{
    border-collapse:collapse;
    clear:both;
}*/

.kartu table{
    border-collapse:collapse;
    /*border:2px solid #6d82a2;*/
    width:100%;
}
.kartu table td{
    padding:2px 3px;
}
.kartu table td:nth-child(1){
    width:100px;
}
.kartu table td:nth-child(2){
    width:5px;
}

/****/

.kartu-nomor table td:nth-child(1),
.kartu-alamat table td:nth-child(1){
    width:110px;
}
/****/

.kartu{
    float:left;
    width:140mm;
    height:135mm;
    /*background:#990;*/
}
.kartu-kiri{
    float:left;
    width:50mm;
    height:80mm;
    /*background:#3FF;*/
}
.kartu-kanan{
    float:left;
    width:90mm;

}
.kartu-judul,
.kartu-nomor,
.kartu-alamat,
.kartu-kualifikasi,
.kartu-keterangan,
.kartu-register
{
    float:left;
    border:2px solid #6d82a2;
    
    padding:3px;
    
    width: -moz-calc(100% - 10px);
    width: -webkit-calc(100% - 10px);
    width: -o-calc(100% - 10px);
    width: calc(100% - 10px);
    
    margin-bottom:1mm;
}
.kartu-nomor table td{

}
.kartu-foto{
    float:left;
    border:2px solid #6d82a2;
    
    padding:9px 3px 9px;
    
    width: -moz-calc(100% - 15px);
    width: -webkit-calc(100% - 15px);
    width: -o-calc(100% - 15px);
    width: calc(100% - 15px);
    
    margin-bottom:1mm;
    margin-right:1mm;
}
.kartu-ttd{
    float:left;
    border:2px solid #6d82a2;
    
    padding:3px;
    
    width: -moz-calc(100% - 15px);
    width: -webkit-calc(100% - 15px);
    width: -o-calc(100% - 15px);
    width: calc(100% - 15px);
    
    margin-bottom:1mm;
    margin-right:1mm;
}
.kartu-foto img{
    width:40mm;
}

/****/

.kartu-foto{
    height:43mm;
}
.kartu-ttd{
    height:29mm;
    position:relative;
}
    .kartu-ttd span{
        position:absolute;
        bottom:10px;
    }
.kartu-kualifikasi{
    height:26mm;
}
.kartu-keterangan{
    height:14mm;
}
.kartu-register{
    height:63mm;
    width:89mm;
    padding:0 0;
}


.kartu-register table{
    border-collapse:collapse;
    
}
.kartu-register table td{
    
    height:14mm;
    
}

/****/

.bersih{
    clear:both;
}
.judul{
    background:#d90405;
    color:#FFF;
    text-align:center;
    padding:4px 0;
    font-weight:bold;
}
.center{
    text-align:center;
}
.bold{
    font-weight:bold;
}
@media print{
    
}
</style>
<style media="print">
@page{margin:0px auto;}
#ilang{ display:none;}
</style>
<script type='text/javascript' src='../WEB/js/jquery-1.9.1.js'></script>
<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
//  $(document).ready(function() {
//      $(window).resize(function() {
//          $(".kartu-ttd table").height($(window).height() - $(".kartu-foto table").height() - 442); 
//          $(".kartu-keterangan table").height($(window).height() - $(".kartu-kualifikasi table").height() - $(".kartu-register table").height() - 500); 
//      }).resize();
//  });
});//]]>  

</script>

</head>

<body>
    
    <div id="ilang">
        <button onclick="document.location.href='index.php?pg=daftar_lamaran'">Kembali</button>
    <input type="submit" value="print" onclick="javascript:window.print()" />
    </div>
    <div class="kartu">
        <div class="kartu-judul">
            <table>
                <tr>
                    <td class="center bold" style="font-size:18px; background:#d90405; color:#FFF; line-height:38px; padding:1px;">
                    BUKTI REGISTRASI
                    </td>
                </tr>
            </table>
        </div>
        <div class="kartu-nomor">
            <table>
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td><?=$tempPesertaNama?></td>
                  <td>&nbsp;</td>
              </tr>
              <tr>
                <td>NIP</td>
                <td>:</td>
                <td><?=$tempPesertaNipBaru?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?=$tempPesertaJabatan?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="width: 150px">Assesment</td>
                <td>:</td>
                <td><?=$reqDiklat?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="width: 150px">Tanggal</td>
                <td>:</td>
                <td><?=getFormattedDateJson($reqTglDiklat)?></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="vertical-align: top;">Kode</td>
                <td style="vertical-align: top;">:</td>
                <td><img src="<?=$tempLink?>" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
        </div>        
</div>


</body>
</html>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");

$lang = $_SESSION['lang'];

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;

$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");
$tempSystemTanggalNow= date("d-m-Y");

$arrayJudul= "";
$arrayJudul= setJudul($lang);

$userLogin->checkLoginPelamar();

$reqKonfirmasi = httpFilterGet("reqKonfirmasi");

$statement= " AND B.PEGAWAI_ID = ".$tempUserPelamarId." AND TO_DATE(TO_CHAR(A.TANGGAL_TES, 'YYYY-MM-DD'),'YYYY/MM/DD') >= TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD')";
$pelamar_lowongan = new PelamarLowongan();
$pelamar_lowongan->selectByParamsDaftarLamaran(array(), -1,-1, $statement);
// echo $pelamar_lowongan->query;exit();
?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["daftar_lamaran"]["judul"]?></div>
   	<div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                <!-- <th scope="col"><?=$arrayJudul["daftar_lamaran"]["kode"]?></th> -->
                <th scope="col"><?=$arrayJudul["daftar_lamaran"]["jabatan"]?></th>
                <th scope="col"><?=$arrayJudul["daftar_lamaran"]["tanggalkirim"]?></th>
                <th scope="col"><?=$arrayJudul["daftar_lamaran"]["aksi"]?></th> 
                </tr>
            </thead>
            <tbody>
            <?
            $i=0;
            while($pelamar_lowongan->nextRow())
            {
            ?>
                <tr>
                    <!-- <td><?=$pelamar_lowongan->getField("JENIS_DIKLAT")?></td> -->
                    <td><?=$pelamar_lowongan->getField("NAMA_DIKLAT")?></td>
                    <td><?=getFormattedDate($pelamar_lowongan->getField("TANGGAL_AWAL"))?></td>
                    <td> 
                        <a href="diklat_kartu_peserta.php?reqId=<?=$pelamar_lowongan->getField("JADWAL_AWAL_TES_SIMULASI_ID")?>" target="_blank"><img src="../WEB/images/icon-cetak.png"><br/>Cetak Berkas</a>
                    </td>
                </tr>    
            <?
            $i++;
            }
            ?>
            <?
            if($i == 0)
            {
            ?>
                <tr>
                    <td colspan="4">Tidak ada data</td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    
    </div>    
    
</div>
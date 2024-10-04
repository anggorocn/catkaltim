<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarLowongan.php");

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);exit();
$urlupload= $data->urlConfig->main->urlupload;

$lang = $_SESSION['lang'];

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserFasilitatorId= $userLogin->userFasilitatorId;
$tempUserPelamarNip= $userLogin->userNoRegister;

$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");
$tempSystemTanggalNow= date("d-m-Y");

$arrayJudul= "";
$arrayJudul= setJudul($lang);

$userLogin->checkLoginFasilitator();

$pelamar_lowongan = new PelamarLowongan();
// $statement= " AND TO_CHAR(C.TANGGAL_AWAL, 'MM') = '".generateZeroDate($tempBulanSekarang,2)."' AND C.TANGGAL_AWAL > TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD')";
// $statement= " AND C.TANGGAL_AWAL < CURRENT_DATE";
$statement= "";
$pelamar_lowongan->selectByParamsFasilitatorDikkat($tempUserFasilitatorId, array(), -1, -1, $statement, "ORDER BY C.TANGGAL_AWAL DESC");
// echo $pelamar_lowongan->query;exit();
?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["daftar_lowongan"]["daftarlowonganaktif"]?></div>
    <!-- <div class="konten-10">
	    <div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?=$arrayJudul["daftar_lowongan"]["info"]?></div>
    </div> -->
   	<div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col"><?=$arrayJudul["daftar_lowongan"]["kode"]?></th>
                <th scope="col"><?=$arrayJudul["daftar_lowongan"]["jabatan"]?></th>
                <th scope="col"><?=$arrayJudul["daftar_lowongan"]["batasakhir"]?></th>
                <th scope="col"><?=$arrayJudul["daftar_lowongan"]["aksi"]?></th>
                <th scope="col">Sertifikat</th>
                </tr>
            </thead>
            <tbody>
            <?
            $i=0;
            while($pelamar_lowongan->nextRow())
            {
            ?>
                <tr>
                    <td><?=$pelamar_lowongan->getField("JENIS_DIKLAT")?></td>
                    <td><?=$pelamar_lowongan->getField("NAMA_DIKLAT")?></td>
                    <td><?=getFormattedDate($pelamar_lowongan->getField("TANGGAL_AWAL"))?><br/>s/d <?=getFormattedDate($pelamar_lowongan->getField("TANGGAL_AKHIR"))?></td>
                    <td><?=$pelamar_lowongan->getField("LOKASI")?></td>
                    <td style="text-align: center;">
                        <?
                        $tempSertifikatUrlPesertaDiklat= $urlupload."sertifikatdiklat/".$pelamar_lowongan->getField("DIKLAT_ID")."/".$tempUserPelamarId.".pdf";

                        if(file_exists("$tempSertifikatUrlPesertaDiklat"))
                            echo "<img onclick=\"getsertifikat('".$pelamar_lowongan->getField("DIKLAT_ID")."', '".$tempUserPelamarId."')\" style=\"cursor:pointer\" src=\"../WEB/images/icon-download.png\" width=\"15\" height=\"15\">";
                        else
                            echo "-";
                        ?>
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

<script type="text/javascript">
    function getsertifikat(reqTempDiklatPesertaId, reqTempPesertaId)
    {
        varurl= "../../uploads/sertifikatdiklat/"+reqTempDiklatPesertaId+"/"+reqTempPesertaId+".pdf";
        newWindow = window.open(varurl, 'Cetak');
        newWindow.focus();
    }
</script>
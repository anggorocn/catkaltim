<?
include_once("../WEB/functions/infotipeujian.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");

date_default_timezone_set('Asia/Jakarta');

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianPegawaiLowonganId= $userLogin->ujianPegawaiLowonganId;
$tempUjianPegawaiDaftarId= $userLogin->ujianPegawaiUjianPegawaiDaftarId;

$tempUjianId= $ujianPegawaiUjianId;
$tempSystemTanggalNow= date("d-m-Y");

?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<script language="javascript">
//localStorage.clear();

$(function(){
});

</script>


<div class="container utama">
	<div class="row">
    	<?php /*?><div class="col-md-12">
            <div class="area-sisa-waktu">
                <div class="judul"><i class="fa fa-clock-o"></i> Sisa Waktu :</div>
                <div class="waktu">
                    <div id="divCounter"></div>
                </div>
            </div>
        </div><?php */?>
        
    	<div class="col-md-12">
			<div class="area-judul-halaman">Instruksi Tes</div>
        </div>
    </div>
    
	<div class="row">
        <div class="col-md-12">
            <div class="area-soal">
                <div class="area-sudah finish">
                	<div class="row">

                        <div class="col-md-12">
                        	<span class="pilih-data" style="float:left; margin-left:10px;">
                            <?
                            $set= new TipeUjian();
            // echo "Dasas"; exit;

							$set->selectByParamstes();
                            // echo $set->query;exit;
							while($set->nextRow())
							{
                                $tempTipeUjianId= $set->getField("TIPE_UJIAN_ID");
                                $tempKeterangan= $set->getField("KETERANGAN_UJIAN");
								$tempTipeUjian= $set->getField("TIPE");
                                if ($set->getField("parent_tipe")==''){
                                      $tempParentTipeUjian='';
                                }
                                else{
                                    $tempParentTipeUjian= $set->getField("parent_tipe")." - ";
                                }
                                if($tempKeterangan==''){
                                    $muncul =$tempTipeUjian;
                                }
                                else{
                                    $muncul=$tempKeterangan;
                                }
							?>
                            	<a href="#" onclick="setujian('<?=$tempUjianTahapId?>', '<?=$tempTipeUjianId?>')" class="tahap-belum" >
								<?=$tempParentTipeUjian?><?=$muncul?> &raquo;
                                </a>
                                <img src="../WEB/images-ujian/icn-faq2.png" title="Info <?=$tempTipeValInfo.$tempTipe?>" style="cursor:pointer" onclick="infoujian('<?=$tempTipeUjianId?>')" /><br>
                            <?
                        	}
                            ?>
                            </span>
                        </div>
                    </div>
                    <div id="reqTujuanTipeUjianId<?=$tempTipeUjianId?>" tabindex="-1"></div>

                </div>
            </div>
        </div>
        
        <div class="area-prev-next">
        	<div class="kembali-home">
        	<!-- <span class="ke-home"><a href="?pg=dashboard"><i class="fa fa-home"></i> Kembali ke halaman utama</a></span> -->
            <?php /*?><div class="prev"><a href="?pg=ujian_mukadimah"><i class="fa fa-chevron-left"></i></a></div>
            <div class="next"><a href="#"><i class="fa fa-chevron-right"></i></a></div><?php */?>
            </div>
        </div>
    
    </div>
</div>

<link rel="stylesheet" type="text/css" href="../WEB/css-ujian/gayainfo.css">

<script language="javascript">
function infoujian(id)
{
	$('#infoujian'+id).firstVisitPopup({
		//cookieName : 'homepage',
		showAgainSelector: '#show-message'
	});
}
</script>

<?
$set= new TipeUjian();
$set->selectByParams();
while($set->nextRow())
{
	$infotipeujianid= $set->getField("TIPE_UJIAN_ID");
?>
<div class="my-welcome-message" id="infoujian<?=$infotipeujianid?>">
    <div class="konten-welcome" style="height:100%;">
    <div class="row" style="height:100%;">
    	<div class="col-md-12" style="height:100%;">
        	<div class="area-judul-halaman">Instruksi Pengerjaan Soal Ujian</div>
        	<div class="area-tatacara" style="height:calc(100% - 60px); overflow:auto; padding:0 0;"> 
                <ul style="list-style:none !important; list-style-type:none !important;">
                    <li>
                    	<?=setinfo($infotipeujianid)?>
						<?php /*?><?=$set->getField("KETERANGAN")?><?php */?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </div>
</div>
<?
}
?>

<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>

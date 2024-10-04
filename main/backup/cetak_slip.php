<?
include_once("../WEB/classes/base-gaji/GajiPeriode.php");
include_once("../WEB/functions/encrypt.func.php");

$gaji_periode = new GajiPeriode();
$gaji_periode_tahun = new GajiPeriode();
$reqPeriode = httpFilterGet("reqPeriode");
$reqTahun = httpFilterGet("reqTahun");

$ada = 0;
$gaji_periode_tahun->selectByParamsTahun(array(), -1, -1, " AND EXISTS (SELECT 1 FROM PDS_GAJI.GAJI_AWAL_BULAN X WHERE X.BULANTAHUN = A.PERIODE AND X.PELAMAR_ID = '".$userLogin->pegawaiId."') ");
while($gaji_periode_tahun->nextRow())
{
	$arrTahun[] = $gaji_periode_tahun->getField("TAHUN");	
	$ada++;
}


if($reqTahun == "")
	$reqTahun = $arrTahun[0];

$gaji_periode->selectByParams(array("SUBSTR(PERIODE, 3,4)" => $reqTahun),-1,-1, " AND EXISTS (SELECT 1 FROM PDS_GAJI.GAJI_AWAL_BULAN X WHERE X.BULANTAHUN = A.PERIODE AND X.PELAMAR_ID = '".$userLogin->pegawaiId."') ","ORDER BY GAJI_PERIODE_ID DESC");
while($gaji_periode->nextRow())
{
	$arrPeriode[] = $gaji_periode->getField("PERIODE");	
}

if($reqPeriode == "")
	$reqPeriode = $arrPeriode[0];
	

if($ada == 0)	
{
?>

<script>
	alert("Proses penggajian anda belum melalui aplikasi.");
	document.location.href = 'index.php?pg=login';
</script>
<?
	exit;
}
?>
<!-- Pembuka row ada di index.php -->
    <div class="col-lg-12">
        <div class="bs-example">
            <ul class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li><a href="?pg=login">Dashboarding</a></li>
                <li class="active">Cetak Slip</li>
            </ul>
        </div>
    </div>
</div>

<div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-8">
    
        <div class="konten">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="pdf-area">
                        <!-- Bootstrap PDF Viewer -->
                        <!--<div id="viewer" class="pdf-viewer" data-url="../WEB/uploads/SLIP_KSO_CABANG.pdf"></div>-->
                        <div id="viewer" class="pdf-viewer" data-url="http://www.ptpds.co.id/pds/gaji/slip.php?reqJenisPegawaiId=4&reqId=<?=mencrypt($userLogin->pegawaiId)?>&reqPeriode=<?=$reqPeriode?>&reqStatus=CABANG"></div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="konten atas-10">
            <div class="row">
                <div class="col-md-12">
                    <div class="judul-halaman"><img src="../WEB/images-portal/icon-menu.png"> Main Menu</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="main-menu-item">
                        <a href="?pg=cetak_slip">
                        <div class="icon"><img src="../WEB/images-portal/icon-cetak-slip.png"></div>
                        <div class="nama"><span>Cetak Slip</span></div>
                        </a>
                    </div>
                    
                    <div class="main-menu-item">
                        <a href="?pg=laporan_absensi">
                        <div class="icon"><img src="../WEB/images-portal/icon-laporan-absensi.png"></div>
                        <div class="nama"><span>Laporan Absensi</span></div>
                        </a>
                    </div>
                    
                    <div class="main-menu-item">
                        <a href="?pg=pengajuan_cuti">
                        <div class="icon"><img src="../WEB/images-portal/icon-pengajuan-cuti.png"></div>
                        <div class="nama"><span>Pengajuan Cuti</span></div>
                        </a>
                    </div>
                    
                    <div class="main-menu-item">
                        <a href="?pg=komplain_kesejahteraan">
                        <div class="icon"><img src="../WEB/images-portal/icon-komplain-kesejahteraan.png"></div>
                        <div class="nama"><span>Komplain Kesejahteraan</span></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
    
    <!-- Blog Sidebar Widgets Column -->
    <div class="col-md-4">
    <div class="konten-half-kanan">
        <div class="konten main-menu-area">
            <div class="tombol-besar-biru"><a href="http://www.ptpds.co.id/pds/gaji/slip.php?reqJenisPegawaiId=4&reqId=<?=mencrypt($userLogin->pegawaiId)?>&reqPeriode=<?=$reqPeriode?>&reqStatus=CABANG" target="_blank"><img src="../WEB/images-portal/icon-cetak-besar.png"> <span>Download</span></a></div>
            <div class="judul-halaman2"><img src="../WEB/images-portal/icon-pilih-bulan.png"> 
            Tahun : 
            <select onChange="document.location.href='?pg=cetak_slip&reqTahun='+this.value;">
            	<?
                for($i=0;$i<count($arrTahun);$i++)				
				{
				?>
                <option value="<?=$arrTahun[$i]?>" <? if($arrTahun[$i] == $reqTahun) { ?> selected <? } ?>><?=$arrTahun[$i]?></option>
                <?
				}
				?>
            </select>
            </div>
            <div class="bulan-area">
            	<?	
					$statement="";
					
					for($i=0;$i<count($arrPeriode);$i++)		
					{
						$bulan = substr($arrPeriode[$i],0,2);
					?>
                        <div class="bulan-item">
                            <a href="?pg=cetak_slip&reqPeriode=<?=$arrPeriode[$i]?>" <? if($reqPeriode == $arrPeriode[$i]){ ?>class="aktif"<? } else {}?>>
                            <div class="bulan-angka-area">
                                <div class="bulan-angka"><?=$bulan?></div>
                            </div>
                            <div class="bulan-text"><?=getNamePeriode($bulan)?></div>
                            </a>
                        </div>
                    <?
					}
                ?>
                
            </div>
        </div>
	</div>
    </div>

<!-- Penutup row ada di index.php -->

        
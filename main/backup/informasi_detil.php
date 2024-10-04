<?
include_once("../WEB/classes/base/Informasi.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/functions/date.func.php");

$informasi = new Informasi();

$reqId = httpFilterGet("reqId");

$informasi->selectByParams(array("I.INFORMASI_ID" => $reqId));
$informasi->firstRow();

$tgl = getFormattedDateExt($informasi->getField("TANGGAL"), false);
$tgl = explode(" ", $tgl);
$tanggal = $tgl[0];
$bulan = $tgl[1];
$tahun = $tgl[2];
?>
<div class="col-lg-8">

    <div id="judul-halaman">Informasi Detil</div>
    <div class="konten-detil">
    	<div class="informasi-area">
        	<div class="list">
            	<div class="tgl">
                	<span class="tanggal"><?=$tanggal?></span> 
                    <span class="bulan"><?=$bulan?> <?=$tahun?></span>
                </div>
                <div class="data">
                	<div class="judul"><?=$informasi->getField("I_NAMA")?></div>
                    <div class="isi">
						<?=$informasi->getField("I_KETERANGAN")?>
                    </div>
                    <div class="kembali"><a href="?pg=informasi"><i class="fa fa-reply" aria-hidden="true"></i> Kembali ke daftar informasi </a></div>
                </div>
            </div>
            
            
            
        </div>
    </div>
    
</div>

<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/classes/base/Lowongan.php");

$lowongan= new Lowongan();

$reqId= httpFilterGet("reqId");

/* DEFAULT VALUES */
$pageView = "index.php?pg=kategori_list";
$showRecord = 6;

/* DATA VIEWING */
//$informasi->selectByParams(array("I.STATUS_AKTIF" => 1, "I.STATUS_INFORMASI" => $reqId),-1,-1, "","id", "ORDER BY TANGGAL DESC");

$allRecord = $lowongan->getCountByParams(array("A.STATUS" => 1, "A.JABATAN_ID" => $reqId));
$pageNumber->initialize($allRecord, $showRecord, $reqPage, $pageView);
$lowongan->selectByParams(array("A.STATUS" => 1, "A.JABATAN_ID" => $reqId), $pageNumber->limit, $pageNumber->from, "ORDER BY TANGGAL DESC");
//echo $lowongan->query;exit;
//$lowongan->selectByParams(array("FORMASI_ID" => 1),-1,-1, "", "ORDER BY A.TANGGAL DESC");

?>
<!-- TICKER -->
<link href="../WEB/lib/NewsTicker4line/global.css" rel="stylesheet" type="text/css">
<script src="../WEB/lib/NewsTicker4line/jquery.js"></script>
<link href="../WEB/lib/NewsTicker4line/css.css" rel="stylesheet" type="text/css">

<style>
.lowongan-area{
	*min-height:600px;
}
.navigasi-area{
	border:0px solid red; margin-top:-50px; 
	margin-bottom:-30px;
}
@media screen and (max-width:767px) {
	.navigasi-area{
		border:0px solid red; 
		margin-top:10px; 
		margin-bottom:-30px;
	}	
}
</style>



<div class="col-lg-8">
	
    <div class="informasi-ticker-area">
    	<div class="title">Lowongan <?=$lowongan->getField("NAMA_JABATAN")?></div>
    </div>

<div class="lowongan-area">
<? 
while($lowongan->nextRow()) 
{
	$tgl = getFormattedDateCheck($lowongan->getField("TANGGAL"));
	$tgl = explode(" ", $tgl);
	$tanggal = $tgl[0];
	$bulan = $tgl[1];
	$tahun = $tgl[2];
	$tempDetilPersyaratan= $lowongan->getField("PERSYARATAN");
	$tempDetilPersyaratanArr = explode("($$)", $tempDetilPersyaratan);
	$count_arr_persyaratan = count($tempDetilPersyaratanArr);
?>
<div class="list">
	<div class="tanggal">
		<div class="bulan"><?=$bulan?></div>
		<div class="tgl"><?=$tanggal?><br><span class="thn"><?=$tahun?></span></div>
	</div>
	<div class="data">
		<div class="judul"><a href="?pg=home_detil&reqId=<?=$lowongan->getField("LOWONGAN_ID")?>"><span class="penempatan"><?=$lowongan->getField("PENEMPATAN")?></span> - <?=$lowongan->getField("JABATAN")?> (<?=$lowongan->getField("KODE")?>)</a></div>
		<div class="isi">Syarat :
		<?
		for($i=0; $i<$count_arr_persyaratan; $i++)
		{
			if($i == 0 && $tempDetilPersyaratan == '')
			{ }
			else
			{
		?>
			-&nbsp;<?=$tempDetilPersyaratanArr[$i]?></li>
		<?
			}
		}
		?>
...</div>
	</div>
</div>
<? } ?>
        
    <!-- PAGING -->
    <div style="clear:both; padding-bottom:20px">
        <div class="pagesLL">
          <div class="pagesLL-margin">
            <?=$pageNumber->drawPageFlex()?>
          </div>
        </div>
    </div> 

        
<?php /*?>        <div class="partner-area" style="margin-top:1px;">
        	<div class="title">Featured Companies</div>
            <div class="data">
            	
                <div class="item"><img src="../WEB/images/logo-ap1.png" class="img-responsive" /></div>
                <div class="item"><img src="../WEB/images/logo-ap2.png" class="img-responsive" /></div>
                
            </div>
        </div>
<?php */?>    </div>
</div>

<!-- TICKER -->
<script>


	function tick2(){
		$('#ticker_02 li:first').slideUp( function () { $(this).appendTo($('#ticker_02')).slideDown(); });
	}
	setInterval(function(){ tick2 () }, 3000);



</script>

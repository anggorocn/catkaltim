<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/classes/base/Lowongan.php");

$lang = $_SESSION['lang'];
$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");
$tempSystemTanggalNow= date("d-m-Y");

$lowongan= new Lowongan();
$pageView = "index.php?pg=home";
$showRecord = 15;

if($userLogin->userPelamarId!=''){
	$statement= " AND TO_DATE(TO_CHAR(A.TANGGAL_TES, 'YYYY-MM-DD'), 'YYYY-MM-DD') > TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD')";
	$statement.= " AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tempTahunSekarang."'";
	$statement.= " and b.pegawai_id =".$userLogin->userPelamarId;
}

$allRecord = $lowongan->getCountByParams(array(), $statement);
// echo $lowongan->query;exit();
$pageNumber->initialize($allRecord, $showRecord, $reqPage, $pageView);
$lowongan->selectByParams(array(), $pageNumber->limit, $pageNumber->from, $statement, "ORDER BY A.TANGGAL_TES DESC");
// echo $lowongan->query;exit;

$arrayJudul= "";
$arrayJudul= setJudul($lang);
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



<div class="col-lg-12">
	
    <div class="informasi-ticker-area">
    	<div class="title"><?=$arrayJudul["home"]["lowonganaktif"]?></div>
    </div>

    <div class="lowongan-area">
    	<?if($userLogin->userPelamarId!=''){?>
	    	<form class="searching" action="">
			  <input type="text" placeholder="Search.." name="search">
			  <button type="submit"><i class="fa fa-search"></i></button>
			</form>


	    	<!-- <div class="searching">
	    		<input type="text" placeholder="Cari..." title="Cari Lowongan Kerja">
	    	</div> -->
	        <div class="list">
	            <div class="tanggal">
	                <div class="bulan">3 nov <span class="thn">2023</span></div>
	            </div>
	            <div class="data">
	                <div class="judul"><a href="?pg=home_detil&reqId=<?=$lowongan->getField("DIKLAT_ID")?>"><span class="penempatan">ujicoba </span></a></div>
	            </div>
	        </div>

	        <? 
			$indexData=0;
			while($lowongan->nextRow()) 
			{
				// echo $lowongan->getField("TANGGAL_AWAL");exit();
				$tgl = getFormattedDateTime($lowongan->getField("TANGGAL_AWAL"), false, true);
				$tgl = explode(" ", $tgl);
				$tanggal = $tgl[0];
				$bulan = $tgl[1];
				$tahun = $tgl[2];
				// $tempDetilPersyaratan= $lowongan->getField("PERSYARATAN");
				// $tempDetilPersyaratanArr = explode("($$)", $tempDetilPersyaratan);
				// $count_arr_persyaratan = count($tempDetilPersyaratanArr);
			?>
	        <div class="list">
	            <div class="tanggal">
	                <div class="bulan"><?=$tanggal?> <?=$bulan?> <span class="thn"><?=$tahun?></span></div>
	            </div>
	            <div class="data">
	                <div class="judul"><a href="?pg=home_detil&reqId=<?=$lowongan->getField("DIKLAT_ID")?>"><span class="penempatan"><?=$lowongan->getField("NAMA_DIKLAT")?></span></a></div>
	            </div>
	        </div>
	        <? 
			$indexData++;
			}
	        if($indexData == 0)
			{
	        ?>
	        <div class="list">
	        	<span style="text-align:center">Belum ada data jadwal</span>
	        </div>
	        <?
			}
			else
			{
	        ?>
	        
	        <!-- PAGING -->
	        <div style="clear:both; padding-bottom:20px">
	            <div class="pagesLL">
	              <div class="pagesLL-margin">
	                <?=$pageNumber->drawPageFlex()?>
	              </div>
	            </div>
	        </div> 
	        <?
			}
	    }
	    else{
	    ?>
	     <iframe width="100%" style="height: 45vh;" src="https://www.youtube.com/embed/rtdKpBmKb5U?si=7r-ZylixGfW6RiUF"></iframe> 
	    <?}?>
	        
    </div>

</div>

<!-- TICKER -->
<script>
	function tick2(){
		$('#ticker_02 li:first').slideUp( function () { $(this).appendTo($('#ticker_02')).slideDown(); });
	}
	setInterval(function(){ tick2 () }, 3000);
</script>

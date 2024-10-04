<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/classes/base/Informasi.php");

$informasi= new Informasi();


/* DEFAULT VALUES */
$pageView = "index.php?pg=informasi";
$showRecord = 5;


$allRecord = $informasi->getCountByParams(array("I.STATUS_AKTIF" => 1));
$pageNumber->initialize($allRecord, $showRecord, $reqPage, $pageView);
$informasi->selectByParams(array("I.STATUS_AKTIF" => 1), $pageNumber->limit, $pageNumber->from, "ORDER BY TANGGAL DESC");

?>
<div class="col-lg-8">

    <div id="judul-halaman">Informasi</div>
    <div class="konten-detil">
    	<div class="informasi-area">
        	<?
			$x = 0;
            while($informasi->nextRow())
			{
				$tgl = getFormattedDateExt($informasi->getField("TANGGAL"), false);
				$tgl = explode(" ", $tgl);
				$tanggal = $tgl[0];
				$bulan = $tgl[1];
				$tahun = $tgl[2];
			?>
        	<div class="list">
            	<div class="tgl">
                	<span class="tanggal"><?=$tanggal?></span> 
                    <span class="bulan"><?=$bulan?> <?=$tahun?></span>
                </div>
                <div class="data">
                	<div class="judul"><a href="?pg=informasi_detil&reqId=<?=$informasi->getField("INFORMASI_ID")?>"><?=$informasi->getField("I_NAMA")?></a></div>
                    <div class="isi"><?=truncate($informasi->getField("I_KETERANGAN"), 30)?> ...</div>
                </div>
            </div>
            <?
				$x++;
            }
			?>
        </div>
    </div>
    	<?
        if($x > 0)
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
		?>
</div>

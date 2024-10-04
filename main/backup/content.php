<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Konten.php");

$lang = $_SESSION['lang'];

$content = new Konten();
$reqId = httpFilterGet("reqId");
$content->selectByParams(array());

$arrayJudul= "";
$arrayJudul= setJudul($lang);
?>

<?
if($reqId == "1")
{
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
	function setSimpan()
	{
		$.messager.confirm('Konfirmasi', "Apakah anda telah membaca dan memahami seluruh petunjuk ?",function(r){
			if (r){
				linkFile= "../json/pelamar_syarat_ketentuan_add.php";
				var s_url= linkFile;
				//alert(s_url);
				
				$.ajax({'url': s_url,'success': function(dataJson) {
					document.location.href="?pg=content&reqId=1";
				}});
			}
		});
	}
</script>
<?
}
?>
<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["syaratketentuan"]?></div>
    <div id="konten" style="padding-left:30px; padding-top:0px;">
    <?
	if($reqId == "1")
	{
		if($tempStatusSyaratKetentuan == "")
		{
			if($userLogin->userPelamarId == "")
			{}
			else
			{
    ?>
    <span class="judul-section">
    Dengan klik tombol SETUJU, Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Seleksi untuk menggunakan data administrasi dalam proses Seleksi Angkasa Pura Support
    <a class="btn btn-warning" style="padding: 4px 12px !important;" href="#" onclick="setSimpan()"><i class="fa fa-send-o"></i> Setuju</a>
    </span>
    <?
			}
		}
	}
    ?>
    
	<?
    while($content->nextRow())
	{
		if ($lang=="en")
		{
			$tempNama= $content->getField("NAME");
			$tempKeterangan= $content->getField("DESCRIPTION");
		}
		else
		{
			$tempNama= $content->getField("NAMA");
			$tempKeterangan= $content->getField("KETERANGAN");
		}
	?>
        <span class="judul-section"><i class="fa fa-caret-right" aria-hidden="true"></i> <?=$tempNama?></span>
        <?=$tempKeterangan?>
	<?
    }
    ?>
    </div>
    
    <?php /*?><?
	if($reqId == "1")
	{
		if($tempStatusSyaratKetentuan == "")
		{
    ?>
    <span class="judul-section">
    Dengan klik tombol SETUJU, Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Seleksi untuk menggunakan data administrasi dalam proses Seleksi Angkasa Pura Support
    <a class="btn btn-warning" style="padding: 4px 12px !important;" href="#" onclick="setSimpan()"><i class="fa fa-send-o"></i> Setuju</a>
    </span>
    <?
		}
	}
    ?><?php */?>
</div>

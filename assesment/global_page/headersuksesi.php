<?
$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$rconf_url= $data->urlConfig->main->url;
?>
<div id="main-header">
	<div id="main-logo-suksesi"><span><?=$_GET["JUDUL"]?> <?=$_GET["DESKRIPSI"]?></span></div>
    <div id="main-judul"> 
        <!-- <span class="logo-kkp"></span> -->
        <span class="vr-header"></span>
        <a href="<?=$rconf_url?>"> 
        <span class="main-menu2"></span>
        </a>
    </div>
</div>

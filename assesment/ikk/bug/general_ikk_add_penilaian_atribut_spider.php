<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

$reqId= httpFilterRequest("reqId");
$reqRowId= httpFilterRequest("reqRowId");

/* create objects */
$pegawai = new Kelautan();
$set = new Penilaian();
$set_detil= new PenilaianDetil();

/* VALIDATION */
$pegawai->selectByParamsMonitoringPegawai(array("IDPEG" => $reqId)); 
$pegawai->firstRow();
$tempNama= $pegawai->getField("NAMA");
$tempJabatanSaatIni= $pegawai->getField("NAMA_JAB_STRUKTURAL");
$tempUnitKerjaSaatIni= $pegawai->getField("SATKER");

$set->selectByParams(array("A.PENILAIAN_ID"=>$reqRowId), -1, -1);
$set->firstRow();
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<style type="text/css" media="screen">
  label {
	/*font-size: 10px;
	font-weight: bold;
	text-transform: uppercase;
	margin-bottom: 3px;*/
	clear: both;
  }
</style>
<style type="text/css">
	/* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
	html, body {height:100%; margin:0; padding:0;}
	/* Set the position and dimensions of the background image. */
	#page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index value than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
	#content {position:relative; z-index:1;}
	/* prepares the background image to full capacity of the viewing area */
	#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* places the content ontop of the background image */
	#content {position:relative; z-index:1;}
</style>
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>  

<script type="text/javascript" src="plugin/zing/flash_scripts/zingchart-html5-min.js"></script>

<script type="text/javascript"> 
$(document).ready(function() {
	zingchart.render({	
		id : "zingchartPsycology",
		output : "canvas",
		//output : "img",
		//color : "transparent",
		//imageformat : 'png',
		width : 100 +"%",
		//width : 680,
		height : 340,
		dataurl : "../json-ikk/general_ikk_add_penilaian_atribut_spider_json.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>"
	});
});		

zingchart.exec('zingchartPsycology', 'saveasimage', {
        format : 'png',
        callback : function(sImageData) {
            console.log(sImageData);

    }
});
</script>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
   <div id="content" style="height:auto; margin-top:-4px; width:100%">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
    	<table class="table_list" cellspacing="1" width="100%">
        	<tr>
                <td colspan="6">
                <div id="header-tna-detil">INDEKS KESENJANGAN KOMPETENSI <span> <?=$tempAspekNama?></span></div>	                    
                </td>			
            </tr>
            <tr class="terang">
                <td width="20%">Nama</td>
                <td width="2%">:</td>
                <td>
                	<?=$tempNama?>
                </td>
            </tr>
            <tr class="gelap">
                <td>Jabatan Saat ini</td>
                <td>:</td>
                <td>
                	<?=$tempJabatanSaatIni?>
                </td>
            </tr>
       		<tr class="terang">
                <td>Unit Kerja Saat ini</td>
                <td>:</td>
                <td>
                	<?=$tempUnitKerjaSaatIni?>
                </td>
            </tr>  
       		<tr class="gelap">
                <td>Jabatan Saat Tes</td>
                <td>:</td>
                <td>
                	<?=$tempJabatanTes?>
                </td>
            </tr>   
       		<tr class="terang">
                <td>Unit Kerja Saat Tes</td>
                <td>:</td>
                <td>
                	<?=$tempSatkerTes?>
                </td>
            </tr>   
       		<tr class="gelap">
                <td>Tanggal Tes</td>
                <td>:</td>
                <td>
                    <?=$tempTanggalTes?>
                </td>
            </tr>
            <tr>
			      <td colspan="6" id="zingchartPsycology">
                  		<ol id="toc"> 
                            <li><a href="#" onclick="parent.setLoad('general_ikk_add_penilaian_monitoring.php?reqId=<?=$reqId?>', '');"><span>Kembali</span></a></li>
                            <li><a href="#" onclick="parent.setLoad('general_ikk_add_penilaian_atribut_view.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>', '1');"><span><?=$tempAspekNama?></span></a></li>
                            <li class="current"><a href="#"><span>Grafik<!--Spider Plot--></span></a></li>
                        </ol>
                  </td>
            </tr>
        </table>
    </form>
    </div>
</div>
</body>
</html>
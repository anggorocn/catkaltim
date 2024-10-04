<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new Penilaian();
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo(); 
}
/* VARIABLE */
$reqRowId = httpFilterRequest("reqRowId");
$reqMode = httpFilterRequest("reqMode");
$reqId = httpFilterRequest("reqId");  

/*if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi identitas pegawai terlebih dahulu.');";	
	echo "window.top.location.href = 'pegawai_edit.php?reqIdOrganisasi=".$reqIdOrganisasi."&reqKetOrganisasi=".$reqKetOrganisasi."';";
	echo '</script>';
}*/

$pegawai = new Kelautan();

/* VALIDATION */
$pegawai->selectByParamsMonitoringPegawai(array("A.PEGAWAI_ID" => $reqId)); 
$pegawai->firstRow();
$tempNama= $pegawai->getField("NAMA");
$tempJabatanSaatIni= $pegawai->getField("NAMA_JAB_STRUKTURAL");
$tempUnitKerjaSaatIni= $pegawai->getField("SATKER");

/* DATA VIEW */
$set->selectByParams(array(), -1, -1, " AND A.PEGAWAI_ID = '".$reqId."' AND A.ASPEK_ID = 2");

if($reqMode == 'simpan')	$alertStatus = "Data Berhasil Tersimpan";
elseif($reqMode == 'error')	$alertStatus = "Data Gagal Tersimpan";
elseif($reqMode == 'hapus')	$alertStatus = "Data Berhasil dihapus";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>

<style>
	/* UNTUK TABLE GRADIENT STYLE*/
	.gradient-style th {
	font-size: 12px;
	font-weight:400;
	background:#b9c9fe url(images/gradhead.png) repeat-x;
	border-top:2px solid #d3ddff;
	border-bottom:1px solid #fff;
	color:#039;
	/*padding:8px;*/
	}
	
	.gradient-style td {
	font-size: 12px;
	border-bottom:1px solid #fff;
	color:#669;
	border-top:1px solid #fff;
	background:#e8edff url(images/gradback.png) repeat-x;
	/*padding:8px;*/
	}
	
	.gradient-style tfoot tr td {
	background:#e8edff;
	font-size: 14px;
	color:#99c;
	}
	
	.gradient-style tbody tr:hover td {
	background:#d0dafd url(images/gradhover.png) repeat-x;
	color:#339;
	}
	
	.gradient-style {
	font-family: 'Open SansRegular';
	font-size: 14px;
	width:480px;
	text-align:left;
	border-collapse:collapse;
	margin:0px 0px 0px 0px;
	}
</style>
<style type="text/css" media="screen">
  label {
	font-size: 10px;
	font-weight: bold;
	text-transform: uppercase;
	margin-bottom: 3px;
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
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
	<table class="table_list" cellspacing="1" width="100%" border="0">
        <tr>
            <td colspan="3">
            <div id="header-tna-detil">INDEKS KESENJANGAN KOMPETENSI <span> ASPEK KOMPETENSI</span></div>	                    
            </td>			
        </tr>
        <tr class="terang">
            <td width="20%">Nama</td>
            <td width="2%">:</td>
            <td>
                <?=$tempNama?>
                <input name="reqNama" class="easyui-validatebox" title="Nama harus diisi" style="width:150px" type="hidden" value="<?=$tempNama?>" />
            </td>
        </tr>
        <tr class="gelap">
            <td>Jabatan Saat ini</td>
            <td>:</td>
            <td>
                <?=$tempJabatanSaatIni?>
                <input name="reqJabatanSaatIni" class="easyui-validatebox" style="width:150px" type="hidden" value="<?=$tempJabatanSaatIni?>" />
            </td>
        </tr>
        <tr class="terang">
            <td>Unit Kerja Saat ini</td>
            <td>:</td>
            <td>
                <?=$tempUnitKerjaSaatIni?>
                <input name="reqUnitkerjaSaatIni" class="easyui-validatebox" vstyle="width:150px" type="hidden" value="<?=$tempUnitKerjaSaatIni?>" />
            </td>
        </tr>  
        <tr>
        	<td colspan="3" width="100%">
            	<table class="gradient-style" cellspacing="1" style="width:100%" id="link-table">
                    <tr class="terang">
                      <th style="display:none">Nama Asesi</th>
                      <th>Metode</th>
                      <th>Tanggal Tes</th>
                      <th>Unit Kerja Saat Tes</th>
                      <th>Jabatan Saat Tes</th>
                    </tr>
                    <? 
					while($set->nextRow())
					{
					?>
                    <tr style="background-color:#FFF;cursor:pointer" <?php /*?>onmouseover="ChangeColor(this, true);"  onmouseout="ChangeColor(this, false);"<?php */?>>
                        <td><a href="kompetensi_add_penilaian.php?reqId=<?=$set->getField("PEGAWAI_ID")?>&reqRowId=<?=$set->getField("PENILAIAN_ID")?>&reqMode=view">link data</a></td>
                        <td style="display:none"><?=$set->getField("NAMA_ASESI")?></td>
                        <td><?=$set->getField("METODE")?></td>
                        <td><?=dateToPageCheck($set->getField("TANGGAL_TES"))?></td>
                        <td><?=$set->getField("SATKER_TES")?></td>
                        <td><?=$set->getField("JABATAN_TES")?></td>
                    </tr>
                    <? 
					}
					?>
                </table>
            </td>
        </tr>
    </table>
    
    <script type="text/javascript">
    $(function ()
    {
      // Hide the first cell for JavaScript enabled browsers.
      $('#link-table td:first-child').hide();

      // Apply a class on mouse over and remove it on mouse out.
      $('#link-table tr').hover(function ()
      {
        $(this).toggleClass('Highlight');
      });
  
      // Assign a click handler that grabs the URL 
      // from the first cell and redirects the user.
      $('#link-table tr').click(function ()
      {
		var id= $(this).find('td a').attr('href');
		if(typeof id == "undefined" || id == ''){}
		else
        parent.frames['mainFrameDetil'].location.href = $(this).find('td a').attr('href');
      });
    });
  </script>
</div>
</div>
<?
if($reqMode == 'simpan' || $reqMode == 'error' || $reqMode == 'hapus'){
	echo '<script language="javascript">';
	echo "$.jGrowl('".$alertStatus."');";
	echo '</script>';
	//$alertStatus = "Data Berhasil Tersimpan";
}
?>
</body>
</html>
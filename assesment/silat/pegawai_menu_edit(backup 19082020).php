<?
/* *******************************************************************************************************
MODUL NAME 			: SIMKeu
FILE NAME 			: index.php
AUTHOR				: Ridwan Rismanto
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: halaman index
***************************************************************************************************** */
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqPegawaiId = httpFilterRequest("reqPegawaiId");
$reqIdOrganisasi = httpFilterRequest("reqIdOrganisasi");
$reqNIP = httpFilterPost("reqNIP");
$reqNIPBaru = httpFilterPost("reqNIPBaru");
$reqNama = httpFilterPost("reqNama");
$reqMode = httpFilterRequest("reqMode");
$reqSource = httpFilterGet("reqSource");

$set= new Kelautan();
$set->selectByParamsMonitoring2(array('A.PEGAWAI_ID'=>$reqPegawaiId),-1,-1);
$set->firstRow();
//echo $set->query;exit;
//echo $set->errorMsg;exit;
$tempNama=$set->getField('NAMA');
$tempNipLama=$set->getField('NIP_LAMA');
$tempNipBaru=$set->getField('NIP_BARU');
$tempPangkatTerkahir= $set->getField('NAMA_GOL');
$tempJabatanTerkahir= $set->getField('NAMA_JAB_STRUKTURAL');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Destroydrop &raquo; Javascripts &raquo; Tree</title>

<style>
/*#box1 {
width: 580px;
padding: 9px 15px;
background-color: #ED8029;
color: white;
margin-bottom: 20px;
margin-top: 20px;
border-radius: 5px;
}

#box1:hover {
background-color: #A7B526;
}*/

/*#menu-kiri a{ text-decoration:none;}
#menu-kiri a:hover{ text-decoration:none; color:#FFF;}

#box2 {
width: 580px;
border-top:1px solid #ececec;
border-bottom:1px solid #c9c9c9;
padding: 9px 15px;
background-color: #e0e0e0;
color: #000;
text-shadow:1px 1px 1px #FFF;

border-radius: 5px;

-webkit-transition: background-color 1s;
-moz-transition: background-color 1s;
-o-transition: background-color 1s;
-ms-transition: background-color 1s;
transition: background-color 1s;
}

#box2:hover {
background-color: #a5a5a5;
border-top:1px solid #a5a5a5;
border-bottom:1px solid #9a9a9a;
color:#FFF;
text-shadow:1px 1px 1px #000;
} */
</style>

<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
<link rel="StyleSheet" href="../WEB/lib/dtree/dtree.css" type="text/css" />
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="button_satker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../jslib/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/dtree/dtree.js"></script>

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

<script type="text/javascript" trdetilsrc="jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function executeOnClick(varItem){
$("a").removeClass("menuAktifDynamis");

<? if($reqMode == 'search'){?>
parent.document.getElementById('FrameFIP').style.display = '';	
parent.document.getElementById('idMainFrame').style.display = 'none';	
<? }?>

if(varItem == 'idpegawai'){
	parent.setShowHideMenu(2);
	$('#idpegawai').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='identitas_edit<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	//parent.mainFrameDetil.location.href='identitas_detil.php';
	parent.document.getElementById('trdetil').style.display = 'none';	
}
else if(varItem == 'riwayatjabatan'){
	parent.setShowHideMenu(2);
	$('#riwayatjabatan').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='jabatan<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';	
	//parent.mainFrameDetil.location.href='jabatan_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	//parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'riwayatpangkat'){
	parent.setShowHideMenu(2);
	$('#riwayatpangkat').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='pangkat<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';	
	//parent.mainFrameDetil.location.href='pangkat_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	//parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'riwayatskp'){
	parent.setShowHideMenu(1);
	$('#beasiswa').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='skp<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='skp_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
	
}
else if(varItem == 'pendumum'){
	parent.setShowHideMenu(2);
	$('#pendumum').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='pendidikan_umum<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';	
	//parent.mainFrameDetil.location.href='pendidikan_umum_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	//parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'seleksidiklatstruktural'){
	$('#seleksidiklatstruktural').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='seleksi_diklat_struktural<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='seleksi_diklat_struktural_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'formdiklatstruktural'){
	$('#formdiklatstruktural').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='form_diklat_struktural<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='form_diklat_struktural_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'seleksidiklatfungsional'){
	$('#seleksidiklatfungsional').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='seleksi_diklat_fungsional<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='seleksi_diklat_fungsional_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'formdiklatfungsional'){
	$('#formdiklatfungsional').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='form_diklat_fungsional<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='form_diklat_fungsional_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'seleksidiklatteknis'){
	$('#seleksidiklatteknis').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='seleksi_diklat_teknis<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='seleksi_diklat_teknis_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'formdiklatteknis'){
	$('#formdiklatteknis').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='form_diklat_teknis<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='form_diklat_teknis_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'formdiklatsdpk'){
	$('#formdiklatsdpk').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='form_diklat_sdpk<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='form_diklat_sdpk_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'sertifikat'){
	$('#sertifikat').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='sertifikat<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='sertifikat_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'pelatihanluarnegeri'){
	$('#pelatihanluarnegeri').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='pelatihan_luar_negeri<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='pelatihan_luar_negeri_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'ujiandinas'){
	$('#ujiandinas').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='ujian_dinas_pegawai<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='ujian_dinas_pegawai_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'ukppi'){
	$('#ukppi').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='ukppi<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='ukppi_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'beasiswa'){
	parent.setShowHideMenu(1);
	$('#beasiswa').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='beasiswa<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='beasiswa_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';	
}
else if(varItem == 'penilaiankompetensijabatan'){
	$('#penilaiankompetensijabatan').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='penilaian_kompetensi_jabatan<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == 'penilaiankompetensianalisa'){
	parent.setShowHideMenu(2);
	$('#penilaiankompetensianalisa').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='penilaian_kompetensi_analisa<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}
else if(varItem == 'profilkompetensijabatan'){
	$('#profilkompetensijabatan').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='profil_kompetensi_jabatan<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';	
	parent.document.getElementById('trdetil').style.display = 'none';
}
<?php /*?>else if(varItem == 'penilaianLHKPN'){
	parent.setShowHideMenu(2);
	$('#penilaianLHKPN').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='penilaian_lhkpn_add_data<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';
}<?php */?>
else if(varItem == 'penilaianLHKPN'){
	parent.setShowHideMenu(1);
	$('#penilaianLHKPN').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='penilaian_lhkpn_monitoring<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.mainFrameDetil.location.href='penilaian_lhkpn_add_data<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = '';
}
else if(varItem == 'lampiran'){
	parent.setShowHideMenu(2);
	$('#lampiran').addClass("menuAktifDynamis");
	parent.mainFrame.location.href='lampiran<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	parent.document.getElementById('trdetil').style.display = 'none';	
	//parent.mainFrameDetil.location.href='jabatan_detil<?=$reqSource?>.php?reqPegawaiId=<?=$reqPegawaiId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>';
	//parent.document.getElementById('trdetil').style.display = '';	
}
return true;
}
</script> 
<?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
<script type="text/javascript">
 
$(document).ready(function(){
	$('#page_effect').fadeIn(2000);
	$('#tambmasakerja, #nikah, #bahasa, #riwayatpenugasan, #cuti, #hukuman, #catatanprestasi, #potensidiri, #dp3 ,#penghargaan, #organisasi, #saudara, #anak, #suamiistri, #mertua').click(
			function (e) {
				$('html, body').animate({scrollTop: '1000px'}, 800);
			}
		); 
});
 
</script>

<!-- SDMENU -->
<link rel="stylesheet" type="text/css" href="../WEB/lib/sdmenu/sdmenu.css" />
<script type="text/javascript" src="../WEB/lib/sdmenu/sdmenu.js">
	/***********************************************
	* Slashdot Menu script- By DimX
	* Submitted to Dynamic Drive DHTML code library: http://www.dynamicdrive.com
	* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
	***********************************************/
</script>
<script type="text/javascript">
// <![CDATA[
var myMenu;
window.onload = function() {
	myMenu = new SDMenu("my_menu");
	myMenu.init();
};
// ]]>
</script>

</head>

<!--<body leftmargin="5" rightmargin="0" bottommargin="0" topmargin="0" style="overflow:scroll" >-->
<body>
<div id="page_effect" style="display:none;">
<div id="bg"><img src="../WEB/images/wall-kiri.jpg" width="100%" height="100%" alt=""></div>
<div id="content">

    <!-- SDMENU -->
    <div>
        <div style="margin-top:5px; width:230px; margin-left:5px; float:left; position:relative; text-align:left;">
            <?php /*?><div style="border:2px solid #FFF; float:left; margin-right:4px; height:66px; width:50px; -webkit-box-shadow: 0 8px 6px -6px black; -moz-box-shadow: 0 8px 6px -6px black; box-shadow: 0 8px 6px -6px black; ">
                <img src="image_script.php?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=pegawai" width="50" height="66">
            </div><?php */?>

            <div style="float:left; position:relative; width:170px;"> 
            	<div style="color:#000; font-size:14px; text-shadow:1px 1px 1px #FFF;"><?=$tempNama?></div>
                <div style="color:#000; font-size:12px; text-shadow:1px 1px 1px #FFF; line-height:20px;"><strong>Nip : </strong><?=$tempNipBaru?> </div>
            </div>

        </div>
        <div style="clear:both"></div>
        <div style="color:#000; text-align:center; margin-left:5px; margin-top:10px; font-size:12px; line-height:34px; text-shadow:1px 1px 1px #FFF; border-top:1px dashed #FFF; ">
        <!--<img src="../WEB/images/chair.png" />&nbsp;-->
        -&nbsp;<?=$tempPangkatTerkahir?> / <?=$tempJabatanTerkahir?>&nbsp;-
        </div>
    </div>
    <div style="float: left" id="my_menu" class="sdmenu">
      <div> 
      	<span>Info Pegawai</span>
        <a href="#" id="idpegawai" onclick="executeOnClick('idpegawai');"  <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Identitas Pegawai</a>
        <a href="#" id="riwayatskp" onclick="executeOnClick('riwayatskp');">Riwayat SKP</a>
        <a href="#" id="riwayatpangkat" onclick="executeOnClick('riwayatpangkat');">Riwayat Pangkat</a>	
        <a href="#" id="riwayatjabatan" onclick="executeOnClick('riwayatjabatan');">Riwayat Jabatan</a>	
        <a href="#" id="pendumum" onclick="executeOnClick('pendumum');">Riwayat Pendidikan</a>	
      </div>
      
      <?php /*?>
      <div> 
      	<span>Data </span>
        <a href="#" id="seleksidiklatstruktural" onclick="executeOnClick('seleksidiklatstruktural');">Seleksi Diklat Struktural</a>
        <a href="#" id="formdiklatstruktural" onclick="executeOnClick('formdiklatstruktural');">Form Diklat Struktural</a>
        <a href="#" id="seleksidiklatfungsional" onclick="executeOnClick('seleksidiklatfungsional');">Seleksi Diklat Fungsional</a>
        <a href="#" id="formdiklatfungsional" onclick="executeOnClick('formdiklatfungsional');">Form Diklat Fungsional</a>
        <a href="#" id="seleksidiklatteknis" onclick="executeOnClick('seleksidiklatteknis');">Seleksi Diklat Teknis</a>
        <a href="#" id="formdiklatteknis" onclick="executeOnClick('formdiklatteknis');">Form Diklat Teknis</a>
        <a href="#" id="formdiklatsdpk" onclick="executeOnClick('formdiklatsdpk');">Form Diklat SDBK</a>
        <a href="#" id="sertifikat" onclick="executeOnClick('sertifikat');">Riwayat Sertifikat</a>
        <a href="#" id="pelatihanluarnegeri" onclick="executeOnClick('pelatihanluarnegeri');">Pelatihan Luar Negeri</a>
        <a href="#" id="ujiandinas" onclick="executeOnClick('ujiandinas');">Ujian Dinas</a>
        <a href="#" id="ukppi" onclick="executeOnClick('ukppi');">UKPPI</a>
        <a href="#" id="beasiswa" onclick="executeOnClick('beasiswa');">Tugas Belajar</a>
      </div>
      <?php */?>
      
      <div>
        <span>Analisa Training</span>
        <a href="#" id="penilaiankompetensianalisa" onclick="executeOnClick('penilaiankompetensianalisa');">Rekomendasi Training</a>
      </div>

      <div>
        <span>Lampiran</span>
        <a href="#" id="lampiran" onclick="executeOnClick('lampiran');">Upload Dokumen</a>
      </div>
      
      <?
	  if($reqMode == "1")
	  {
      ?>
      <div>
        <span>LHKPN / LHKSN</span>
         <a href="#" id="penilaianLHKPN" onclick="executeOnClick('penilaianLHKPN');">Penilaian LHKPN / LHKSN</a>
      </div>
      <?
	  }
      ?>
    </div>
    <!-- END SDMENU -->
		
</div>
</div>
</body>

</html>
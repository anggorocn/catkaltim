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


// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}


include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/Visitor.php");
include_once("../WEB/classes/base/Satker.php");
include_once("../WEB/functions/date.func.php");

$reqMode = httpFilterGet("reqMode");
$reqHalaman = httpFilterGet("reqHalaman");
header("Cache-Control: must-revalidate");

$visitor=new Visitor();
function _ip( )
{
    return ( preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
}

$ip = _ip(); // Get the users IP using the function above
$time = date( 'd-m-Y' ); // Get the current date, in the format of: 12-12-2006
$timestamp = time();
$getStats = $visitor->getOnline(dateToDB($time), $ip);
if($getStats == 0)
{
	$visitor->setField("IP", $ip);
	$visitor->setField("TANGGAL", dateToDBCheck($time));	
	$visitor->setField("HITS", 1);	
	$visitor->setField("STATUS", $timestamp);
	$visitor->insert();
}


$hitsToday = $visitor->hitsToday(dateToDB($time));
$totalHits = $visitor->totalHits();
$diff = time() - 300;
$countOnline = $visitor->countOnline($diff);
//echo $visitor->query;
//echo $hitsToday.'---'.$totalHits.'---'.$countOnline;

/*$set= new Satker();
$set->selectByParams(array("SATKER_ID"=>$userLogin->userSatkerId),-1,-1);
$set->firstRow();
//echo $set->query;
$parent_id_satker='admin-usulan-'.$set->getField("satker_id_parent");
$id_satker=$set->getField("satker_id");
$nama_satker_info=$set->getField("nama");

$nama_satker_info= $userLogin->nama;
unset($set);*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="icon" type="image/ico" href="../WEB/../WEB/images/favicon.ico" />
<title>Aplikasi Assessment Center - Pemerintah Provinsi Bali</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="StyleSheet" href="../WEB/lib/dtree/dtree.css" type="text/css" />
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../jslib/displayElement.js"></script>
<script language="JavaScript">
function openPopup(opUrl,opWidth,opHeight)
{
	opWidth = iecompattest().clientWidth - 5;
	opHeight = iecompattest().clientHeight - 95;
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars, top = 100, left = 150");
	newWindow.focus();
}
</script>

<!--DDSMOOTHMENU-->
<link rel="stylesheet" type="text/css" href="../WEB/lib/ddsmoothmenu/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css" href="../WEB/lib/ddsmoothmenu/ddsmoothmenu-v.css" />

<script type="text/javascript" src="../WEB/lib/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../WEB/lib/ddsmoothmenu/ddsmoothmenu.js"></script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>

<style>
.menuAktifDynamis
{
	background: #78b5b6;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	-moz-box-shadow:    inset 0 0 10px #488d8e;
    -webkit-box-shadow: inset 0 0 10px #488d8e;
    box-shadow:         inset 0 0 10px #488d8e;
}

/*****************************************************************************/

/*.iframe_khusus {margin: 0px; padding: 0px; width: height: 100%; border: none;}
.iframe_khusus {display: block; width: 100%; border: none; overflow-y: auto; overflow-x: hidden;}*/
</style>

<script language="javascript">
function reloadKgb(tmtSk, satkerId)
{
	var tmt=String(tmtSk);
	tmt= tmt.split('-'); 
	
	//
	//parent.frames['mainFrame'].location.href = 'kenaikan_gaji_berkala.php?reqId='+satkerId+'&reqBulan='+tmt[1]+'&reqTahun='+tmt[2]+'&reqTipeMode=proses';
	parent.frames['mainFrame'].location.href = 'monitoring.php?reqMode=kgb&reqId='+satkerId+'&reqBulan='+tmt[1]+'&reqTahun='+tmt[2]+'&reqTipeMode=proses';
	//$("iframe#mainFullFrame")[0].contentWindow.reloadKgb(tmtSk);
}

function setView(){
	parent.document.getElementById('idMainFrame').style.display = '';
	parent.document.getElementById('FrameFIP').style.display = 'none';
}

function disableMaster(){	
	$("#FrameValidator").hide(1);
}

function disableMenu(){
	//$("#menu,#menuAll,#menuFip,#menuSatuanKerja,#FrameFIP,#idMainFrame").hide(1);
	$("#menuAll,#menu,#menuFip,#menuSatuanKerja,#FrameFIP").hide(1);
}

function disableMenuFIP(){
	//$("#menu,#menuAll,#menuFip,#FrameValidator,#menuSatuanKerja,#idMainFrame").hide(1);
	$("#menu,#menuFip,#FrameValidator,#menuSatuanKerja").hide(1);
}

function menuHandler(setDisplay)
{
	if(
		setDisplay == "pegawai" || setDisplay == "anjab" || setDisplay == "statistik" || setDisplay == "duk" || 
		setDisplay == "kgb" || setDisplay == "kp" || setDisplay == "kp-satker" || setDisplay == "pensiun" || setDisplay == "ulangtahun" || 
		setDisplay == "usulan-flex" || setDisplay == "master-flex" || setDisplay == "home-flex" || setDisplay == "report-flex" ||
		setDisplay == "tools-flex" || setDisplay == "fip-flex" || setDisplay == "flexiport" || setDisplay == "validator" || setDisplay == "kelengkapan"
	  )
	{
		$('#kp-satker,#pegawai,#anjab,#statistik,#duk,#kgb,#kp,#pensiun,#ulangtahun,#kelengkapan,#validator,#flexiport,#home-flex,#fip-flex,#master-flex,#usulan-flex,#report-flex,#tools-flex,#master-flex').removeClass("menuAktifDynamis");
		$("#"+setDisplay).addClass("menuAktifDynamis");
		
		if(setDisplay == "fip-flex")
		{
			disableMenuFIP();
			$("#menuFip,#FrameFIP,#menu,#mainFrameDetil").show(1);
			$("#FrameFIP").css("display", "");
		}
		else if(setDisplay == "flexiport" || setDisplay == "master-flex" || setDisplay == "validator" || setDisplay == "kelengkapan" || setDisplay == "usulan-flex" || setDisplay == "kp-satker")
		{
			disableMenu();
			$("#FrameValidator").show(1);
		}
		else if(setDisplay == "validator")
		{
			$("#FrameFIP, #FrameValidator,#menuFip").hide(1);
			$("#menuSatuanKerja,#menuAll,#menu").show(1);
		}
		else
		{
			//$("#FrameFIP, #FrameValidator,#menuFip,#idMainFrame").hide(1);
			$("#FrameFIP, #FrameValidator,#trdetil,#menuFip").hide(1);
			$("#menuSatuanKerja,#menuAll,#menu").show(1);
		}
		parent.document.getElementById('menuFrame').style.display = '';			
		parent.document.getElementById('hover').style.display = '';		
	}
	else
	{
		parent.document.getElementById('menuFrame').style.display = 'none';	
		parent.document.getElementById('hover').style.display = 'none';				

	}	
}
</script>

<!-- FLEXMENU -->
<link rel="stylesheet" type="text/css" href="../WEB/lib/flexMenu/flexdropdown.css" />
<script type="text/javascript" src="../WEB/lib/flexMenu/jquery.min.js"></script>
<script type="text/javascript" src="../WEB/lib/flexMenu/flexdropdown.js">
/***********************************************
* Flex Level Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">
function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
{
	var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
	var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
	
	opWidth = iecompattest().clientWidth - 5;
	opHeight = iecompattest().clientHeight - 45;
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
}

function OpenDHTMLDetil(opAddress, opCaption, opWidth, opHeight)
{
	var left = (screen.width/2)-(opWidth/2);
	var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+'px,top='+top+'px,resize=1,scrolling=1,midle=1'); return false;
}

function OpenDHTMLCenter(opAddress, opCaption, opWidth, opHeight)
{
	opCaption= "Modul Pengaturan";
	var width  = opWidth;
	var height = opHeight;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=no';
	params += ', status=no';
	params += ', toolbar=no';
	params += ', center=yes';
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
}



function tess()
{
	//$("iframe#FrameFIP")[1].contentWindow.tess();
	alert('index');
}

function reload()
{
	//$("iframe#FrameFIP")[1].contentWindow.tess();
	// alert('index');
	$("#btnCari").click();		
}
</script>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0"<?php /*?> onLoad="setView();"<?php */?> style="overflow:hidden;">
 

<?
$_GET["JUDUL"] = "| Modul";
$_GET["DESKRIPSI"] = "HCDP";
//$_GET["DESKRIPSI"] = "Manajemen SDM";
include_once "../global_page/header.php";
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="89%" bgcolor="#F0F0F0" style="overflow:hidden">
	 
   	<tr> 
    	<td colspan="5" valign="middle" style="background:#ecf5ff; border-top:1px solid #deedfd;" height="30">
			<table cellpadding="0" cellspacing="0" width="100%"> 
				<tr>
                	<td>
                    <table cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                        	<?php /*?><td><span id="account-new"><a href="../main/index.php" ><img src="../WEB/../WEB/images/icon-main-menu.png" > </a></span></td><?php */?>
                    		<td>
                            	<span id="account-new"><a href="#" data-flexmenu="flex-account"><img src="../WEB/images/administrator.png" > <?=$userLogin->nama?></a></span>
                            </td>
                        </tr>
                    </table>
                    </td>
                    <td align="right">
                    <table cellpadding="0" cellspacing="0" border="0">
                    	<tr>
                        	<!-- MENU -->
                            <td>
                            	<div id="menu-2">
                                	 
                                    <div id="menu-2-konten" style="display:none"><a href="#" data-flexmenu="flex-laporan">Report</a></div>
                                    
                                    
                                    <!-- pindahan menu dari atas -->                                    
                                    <?
										$tempListInfo= $userLogin->userTempList;
									?>
                                    <?
									//if(findWord($tempListInfo, "Atribut") == 1 || findWord($tempListInfo, "Training") == 1 || $tempListInfo == "")
									//{
                                    ?>
                                    <?php /*?><div id="menu-2-konten"><a href="#" data-flexmenu="flex-indeks-master">Master</a></div><?php */?>
                                    <div id="menu-2-konten" ><a href="#" data-flexmenu="flex-master-main">Master</a></div>

                                    <div id="menu-2-konten"><a href="monitoring.php?reqRowId=1&reqMode=rekapitulasi_kompetensi" target="mainFrame" onClick="menuHandler('pegawai')">Rekapitulasi</a></div>

                                    <div id="menu-2-konten"><a href="monitoring.php?reqRowId=1&reqMode=pengembangan_kompetensi" target="mainFrame" onClick="menuHandler('pegawai')">Analisis Pengembangan</a></div>

                                

                                    <?
									//}
									if(findWord($tempListInfo, "Analisa Diklat") == 1 || $tempListInfo == "")
									{
                                    ?>
                                   <!--  <div id="menu-2-konten"><a href="monitoring.php?reqRowId=1&reqMode=diklat_analisa_kompetensi_bendel" target="mainFrame" onClick="menuHandler('pegawai')">HCDP</a></div> -->

                                    <?
									}
                                    ?>
									<?php /*?><div id="menu-2-konten"><a href="monitoring.php?reqMode=pegawai_beasiswa" target="mainFrame" onClick="menuHandler('pegawai')">Beasiswa</a></div><?php */?>
                                    <?
									if(findWord($tempListInfo, "Pegawai SDM") == 1 || $tempListInfo == "")
									{
                                    ?>
                                    <div id="menu-2-konten"><a href="monitoring.php?reqRowId=1&reqMode=pegawai" target="mainFrame" onClick="menuHandler('pegawai')">Pegawai</a></div>
                                    <?
									}
                                    ?>
                                    <!---->
                                    <div id="menu-2-konten"><a href="index.php">Home</a></div>

                                </div>
                                
                                <ul id="flex-indeks-master" class="flexdropdownmenu" style="background-color:#385f0e;">
                                	<?
									if(findWord($tempListInfo, "Atribut") == 1 || $tempListInfo == "")
									{
                                    ?>
                                		<li><a href="monitoring.php?reqRowId=1&reqMode=master_atribut" target="mainFullFrame" onClick="menuHandler('validator')">Atribut</a></li>
                                    <?
									}
									if(findWord($tempListInfo, "Training") == 1 || $tempListInfo == "")
									{
                                    ?>
                                    	<li><a href="monitoring.php?reqRowId=1&reqMode=master_traning" target="mainFullFrame" onClick="menuHandler('validator')">Training</a></li>
                                    <?
									}
                                    ?>
                                </ul>
                                
                                <!-- ACCOUNT -->
                                <ul id="flex-account" class="flexdropdownmenu">
                                    <li>
                                    <a href="index.php?reqMode=6" <? if($reqMode == 6) { ?> style="background-position: 0 -60px;" <? } ?>>Account</a>
                                    </li>
                                    <li>
                                    <a href="../main/login.php?reqMode=submitLogout">Logout</a>
                                    </li>
                                </ul>
                                
                                <!-- TOOLS -->
                                <ul id="flex-tools" class="flexdropdownmenu">
                                    <li>
                                    <a href="monitoring.php?reqMode=tools_cek_nip_ganda" target="mainFrame" onClick="menuHandler('tools')">Cek NIP Ganda</a>
                                    </li>
                                    <li>
                                    <a href="monitoring.php?reqMode=tools_cek_riwayat_ganda" target="mainFrame" onClick="menuHandler('tools')">Cek Riwayat Ganda</a>
                                    </li>
                                </ul>
                                
                                 
                                <!-- LAPORAN -->
                                <ul id="flex-laporan" class="flexdropdownmenu" style="background-color:#385f0e;">
                                	<li><a href="monitoring.php?reqMode=laporan_diklat" target="mainFrameValidator" onClick="menuHandler('validator')">Report Diklat</a></li>  
                                </ul>

                                <ul id="flex-master-main" class="flexdropdownmenu" style="background-color:#385f0e;">
                                	<!-- <li><a href="monitoring.php?reqMode=masterJenisDiklat" target="mainFullFrame" onClick="menuHandler('validator')">Master Analisis Kesenjangan</a></li> -->
                                	<!-- <li><a href="monitoring.php?reqMode=masterTipe" target="mainFullFrame" onClick="menuHandler('validator')">Tipe Pelatihan</a></li> -->
                                	<!-- <li><a href="monitoring.php?reqMode=masterKategoriPelatihan" target="mainFullFrame" onClick="menuHandler('validator')">Kategori Pelatihan</a></li> -->
                                	<li><a href="monitoring.php?reqMode=pelatihan_hcdp" target="mainFullFrame" onClick="menuHandler('validator')">Tipe Pelatihan</a></li>
                                </ul>
                                
                            </td>
                                
                                <!-- MASTER -->
                                <ul id="flex-master" class="flexdropdownmenu" style="background-color:#385f0e;">
                                	<li><a href="monitoring.php?reqMode=masterJenisDiklat" target="mainFrameValidator" onClick="menuHandler('validator')">Master Jenis Diklat</a></li>
                                	<li><a href="monitoring.php?reqMode=masterPerencanaanDiklat" target="mainFrameValidator" onClick="menuHandler('validator')">Master Diklat</a></li>
                                    <li><a href="monitoring.php?reqMode=masterStandarKompetensi" target="mainFrameValidator" onClick="menuHandler('validator')">Master SDBK</a></li>
                                    
                                    <li><a href="monitoring.php?reqMode=masterUser" target="mainFrameUser" onClick="menuHandler('masterUser')">Master User</a></li>
                                    <li><a href="monitoring.php?reqMode=masterUserGroup" target="mainFrameUserGroup" onClick="menuHandler('masterUserGroup')">Master User Group</a></li>
                                </ul>
                                
                            </td>
                            <td><span style="margin-right:15px;"><a href="javascript:displayElement('banner')"><img src="../WEB/images/openall.png" border="0"></a></span></td>
                    	</tr>
                    </table>
                    </td>               
				</tr>
			</table>
		</td>
  	</tr>
  	<tr> 
    <?
    /*if($reqMode == "")
	{}
	else
	{*/
	?>
    	<td height="100%" valign="top" class="menu" width="1" id="menu"> 
      		<table width="242" border="0" cellpadding="0" cellspacing="0" height="100%" id="menuFrame" style="display:none">
        		<tr> 
		  			<td height="100%"></td>
         			<td valign="top">
                    <!--<div id="demo2" style="overflow: scroll">-->
		  			<? 
					if($userLogin->userPegawaiId == "")
					{
					?>
                    	<table height="100%" id="menuFip" style="display:none">
                  <!--       	<tr style="height:2%">
                            	<td>					  	                                
                                <iframe src="pegawai_menu_search.php?reqMode=<?=$reqMode?>" name="menuFrame" width="100%" height="100%" style="overflow-x:hidden" frameborder="0"></iframe>		  
                                </td>
                            </tr> -->
                        	<tr height="98%">
                            	<td>					  	
                                <iframe src="pegawai_menu_edit.php?reqMode=<?=$reqMode?>" name="menuPegawai" width="100%" height="100%" style="overflow-x:hidden" frameborder="0"></iframe>		  
                                </td>
                            </tr>
                        </table>
				  	<!-- MENU -->                    
					  	<iframe src="satuan_kerja_menu.php?reqMode=<?=$reqMode?>" name="menuSatuanKerja" width="100%" height="100%" scrolling="auto" frameborder="0"  id="menuSatuanKerja" style="display:none"></iframe>
                    <?
					}
					else
					{
					?>
                    	<table height="100%" id="menuFip" style="display:none">
                        	<tr height="100%">
                            	<td>					  	
                                <iframe src="pegawai_menu_edit.php?reqPegawaiId=<?=$userLogin->userPegawaiId?>" name="menuPegawai" width="100%" height="100%" style="overflow-x:hidden" frameborder="0"></iframe>		  
                                </td>
                            </tr>
                        </table>
                    <?
					}
                    ?>
                    <!--</div>-->
		  			</td>
        		</tr>
      		</table>
		</td>
		<td width="3" background="../WEB/images/bg_menu_right.gif" align="right" id="hover" style="display:none">
			<a href="javascript:displayElement('menuFrame')"><img src="../WEB/images/btn_display_element.gif" title="Buka/Tutup Menu" border="0"></a>
		</td>
     <?
	//}
	 ?>
    	<td valign="top" height="100%" width="100%">
        	<?
			if($userLogin->userPegawaiId)
			{
            ?>
        	<?php /*?><table cellpadding="0" cellspacing="0"  width="100%" height="100%">
            	<tr height="10%">
                	<td><iframe src="home-atas.php" class="mainframe" id="FrameFIP" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe></td>
                </tr>
            	<tr height="20%" id="trdetil" style="display:none">
                	<td><iframe class="mainframe" id="idMainFrame" name="mainFrameDetil" width="100%" height="100%" scrolling="no" frameborder="0"></iframe></td>
                </tr>
            </table><?php */?>
            <?
			}
			else
			{
            ?>
            <!--LIHAT FLEXY VALIDATOR-->
			<iframe style="display:none" class="mainframe" id="FrameValidator" name="mainFullFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
            <?
			}
            ?>
            
            <table cellpadding="0" cellspacing="0"  width="100%" height="100%" id="menuAll">
            	<tr height="10%">
                	<td>
                    <? 
					if($reqMode == "6")
					{
					?>
					<iframe src="account.php" class="mainframe" id="idMainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
					<!--<script type="text/javascript">
					parent.document.getElementById('menu').style.display = 'none';	
					</script>-->
					<? 
					}
					else
					{
					?>
                    <iframe src="home-atas.php" class="mainframe" id="idMainFrame" name="mainFrame" width="100%" height="100%" scrolling="auto" frameborder="0"></iframe>
                    <?
					}
                    ?>
                    </td>
                </tr>
            	<tr height="20%" id="trdetil" style="display:none">
                	<td><iframe class="mainframe" id="idMainFrameDetil" name="mainFrameDetil" width="100%" height="100%" scrolling="no" frameborder="0"></iframe></td>
                </tr>
            </table>
            
            
		</td>
	</tr>
    <tr>
		<td background="../WEB/images/bg_menu_right.gif" colspan="3" align="center" valign="middle" height="5">
			<a href="javascript:displayElement('shortcut')"><img src="../WEB/images/btn_display_element_vertical.gif" title="Buka/Tutup Jalan Pintas" border="0"></a>
		</td>
    </tr>
   	<!-- <tr id="shortcut"> 
    	<td colspan="5" valign="middle" style=" background:#3379b7;" height="30">
        	<center>
			<span style="font-size:11px; color:#FFF; text-shadow:1px 1px 1px #286f71;">Copyright &copy; 2019 Pemerintah Provinsi Bali. All Rights Reserved.</span>
            </center>
		</td>
  	</tr>  -->   
</table>
</body>
</html>

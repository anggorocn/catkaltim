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
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasi.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

// $statement= " AND JADWAL_TES_ID = ".$reqId;
// $set_validasi= new JadwalTesSimulasiAsesor();
// $set_validasi->selectByParams(array(), -1,-1, $statement);
// $set_validasi->firstRow();
// $tempStatusValidasi= $set_validasi->getField("STATUS");
// unset($set_validasi);

$sOrder= " ORDER BY A.TANGGAL_TES";
$index= 0;
$arrJadwal= "";
$statement= " AND A.JADWAL_AWAL_TES_ID = ".$reqId;
$set= new JadwalAwalTesSimulasi();
$set->selectByParamsMenu(array(),-1,-1, $statement, $sOrder);
// echo $set->query;exit();
while($set->nextRow())
{
	$arrJadwal[$index]["JADWAL_AWAL_TES_SIMULASI_ID"] = $set->getField("JADWAL_AWAL_TES_SIMULASI_ID");
	$arrJadwal[$index]["TANGGAL_TES"] = getFormattedDateTime($set->getField("TANGGAL_TES"), false, true);
	$arrJadwal[$index]["ACARA"] = $set->getField("ACARA");
	$arrJadwal[$index]["TEMPAT"] = $set->getField("TEMPAT");
	$arrJadwal[$index]["ALAMAT"] = $set->getField("ALAMAT");
    $arrJadwal[$index]["KETERANGAN"] = $set->getField("KETERANGAN");
	$arrJadwal[$index]["JUMLAH_DATA"] = $set->getField("JUMLAH_DATA");

	$index++;
}
$jumlah_jadwal= $index;
// print_r($arrJadwal);exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
    <title>Destroydrop &raquo; Javascripts &raquo; Tree</title>
    <link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css">
    <link rel="StyleSheet" href="../WEB/lib/dtree/dtree.css" type="text/css" />
    <link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
    <link href="button_satker.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../jslib/jquery.js"></script>
    <script type="text/javascript" src="../WEB/lib/dtree/dtree.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>

    <script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/daterangepicker.js"></script>

    <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script> -->

    
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
			
			if(varItem == 'jadwal'){
				parent.setShowHideMenu(2);
				$('#jadwal').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='jadwal_awal_tes_add_data.php?reqId=<?=$reqId?>&reqMode=<?=$reqMode?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			
            else if(varItem == 'simulasi_pegawai_hapus'){
                parent.setShowHideMenu(2);
                $('#simulasi_pegawai_hapus').addClass("menuAktifDynamis");
                parent.mainFrame.location.href='jadwal_awal_tes_add_hapus_jadwal.php?reqId=<?=$reqId?>';
                parent.document.getElementById('trdetil').style.display = 'none';
            }

            else if(varItem == 'simulasi_undangan_pegawai'){
                parent.setShowHideMenu(2);
                $('#simulasi_undangan_pegawai').addClass("menuAktifDynamis");
                parent.mainFrame.location.href='jadwal_awal_tes_add_undangan_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$tempId?>';
                parent.document.getElementById('trdetil').style.display = 'none';
            }

			<?
            for($index_data=0; $index_data < $jumlah_jadwal; $index_data++)
			{
				$tempId= $arrJadwal[$index_data]["JADWAL_AWAL_TES_SIMULASI_ID"];
				$tempInfo= $arrJadwal[$index_data]["TANGGAL_TES"];
			?>
			else if(varItem == 'simulasi_pegawai<?=$tempId?>'){
				parent.setShowHideMenu(2);
				$('#simulasi_pegawai<?=$tempId?>').addClass("menuAktifDynamis");
				parent.mainFrame.location.href='jadwal_awal_tes_add_simulasi_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$tempId?>';
				parent.document.getElementById('trdetil').style.display = 'none';
			}
			<?
			}
			?>
			return true;
		}
		
		function hidenJadwalMenu()
		{
			//$('#simulasi_pegawai, #simulasi_asesor_tools, #simulasi_asesor_acara, #master_jadwal_add_jadwal_acara_potensi').hide();
			$('#simulasi_asesor_tools, #simulasi_asesor_acara, #master_jadwal_add_jadwal_acara_potensi').hide();
		}
		
		function showJadwalMenu()
		{
			$('#acara, #kelompok_ruang, #asesor, #pegawai,#jadwal_asesor_potensi,#jadwal_asesor_potensi_pegawai').hide();
		}
    </script> 
    <script type="text/javascript">
        $(document).ready(function(){
			<?
			//if($tempStatusValidasi == "1")
			//{
			?>
			hidenJadwalMenu();
			<?
			//}
			//else
			//{
			?>
			//showJadwalMenu();
			<?
			//}
			?>
			//hidenJadwalMenu();
            $('#page_effect').fadeIn(2000);
            $('#tambmasakerja, #nikah, #bahasa, #riwayatpenugasan, #cuti, #hukuman, #catatanprestasi, #potensidiri, #dp3 ,#penghargaan, #organisasi, #saudara, #anak, #suamiistri, #mertua').click(
                    function (e) {
                        $('html, body').animate({scrollTop: '1000px'}, 800);
                    }
                ); 
        }); 

        /*function hapusdata(id)
        {
            $.messager.defaults.ok = 'Ya';
            $.messager.defaults.cancel = 'Tidak';

            infoMode= "Apakah anda yakin menghapus data terpilih";
            //alert(statusaktif);return false;
            $.messager.confirm('Konfirmasi', infoMode+" ?",function(r){
                if (r){
                    parent.location.href = 'jadwal_awal_tes_add.php?reqId=<?=$reqId?>';

                    var s_url= "main/user_login_json/delete/?reqMode="+reqmode+"&reqId="+id;
                    //var request = $.get(s_url);
                    $.ajax({'url': s_url,'success': function(msg){
                        if(msg == ''){}
                        else
                        {
                            // setCariInfo();
                        }
                    }});
                }
            }); 
        }*/
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
    <body>
        <div id="page_effect" style="display:none;">
            <div id="bg"><img src="../WEB/images/wall-kiri.jpg" width="100%" height="100%" alt=""></div>
            <div id="content">
            
                <!-- SDMENU -->
                <div style="float: left" id="my_menu" class="sdmenu">
                  <div> 
                    <span>Jadwal</span>
                    <a href="#" id="jadwal" onclick="executeOnClick('jadwal');" <? if($reqMode == "fip") {} else { ?> class="menuAktifDynamis"  <? } ?>>Data</a>
                    <?
                    if($reqId == ""){}
                    else
                    {
                    ?>
                    <a href="#" id="simulasi_pegawai_hapus" onclick="executeOnClick('simulasi_pegawai_hapus');">Simulasi Tanggal Hapus</a>
                    <a href="#" id="simulasi_undangan_pegawai" onclick="executeOnClick('simulasi_undangan_pegawai');">Pegawai Undangan</a>
                    <?
                    for($index_data=0; $index_data < $jumlah_jadwal; $index_data++)
					{
						$tempId= $arrJadwal[$index_data]["JADWAL_AWAL_TES_SIMULASI_ID"];
						$tempInfo= $arrJadwal[$index_data]["TANGGAL_TES"];
                        // JUMLAH_DATA
                    ?>
                    <!-- <a href="#" onclick="hapusdata('<?=$tempId?>')"><img src="../WEB/images/delete-icon.png"></a> -->
                    <a href="#" id="simulasi_pegawai<?=$tempId?>" onclick="executeOnClick('simulasi_pegawai<?=$tempId?>');">Simulasi Tanggal<br/><?=$tempInfo?></a>
                    <?
                	}
                    ?>
                    <?
                    }
                    ?>
                  </div>
                <!-- END SDMENU -->
                    
            </div>
        </div>
    </body>
</html>
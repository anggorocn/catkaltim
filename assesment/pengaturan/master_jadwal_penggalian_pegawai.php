<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPenggalianPegawai.php");

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

/* VARIABLE */
$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data jadwal terlebih dahulu.');";	
	echo "window.parent.location.href = 'master_jadwal_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

$arrAsesor= [];
$statement= " AND JA.JADWAL_TES_ID = ".$reqId;
$set= new JadwalAsesor();
$set->selectByParamsDataAsesorPegawai($statement, $tempAsesorId);
// echo $set->query;exit;
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrdata["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrdata["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
    $arrdata["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrdata["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
    array_push($arrAsesor, $arrdata);
}
$jumlah_asesor= count($arrAsesor);

$statement= " AND A.JADWAL_TES_ID IN (".$reqId.")";
$set= new JadwalPenggalianPegawai();
$set->selectbyparamspenggalian(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrpenggalian=[];
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
    $arrdata["PENGGALIAN_NAMA"]= $set->getField("PENGGALIAN_NAMA");
    $arrdata["PENGGALIAN_KODE"]= $set->getField("PENGGALIAN_KODE");
    array_push($arrpenggalian, $arrdata);
}
$jumlahpenggalian= count($arrpenggalian);
// print_r($arrpenggalian);exit;

$statement= " AND A.JADWAL_TES_ID IN (".$reqId.")";
$set= new JadwalPenggalianPegawai();
$set->selectbyparams(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrpegawaipenggalian=[];
while($set->nextRow())
{
    $jadwaltesid= $set->getField("JADWAL_TES_ID");
    $penggalianid= $set->getField("PENGGALIAN_ID");
    $pegawaiid= $set->getField("PEGAWAI_ID");
    $arrdata= [];
    $arrdata["KEY"]= $jadwaltesid."-".$penggalianid."-".$pegawaiid;
    $arrdata["JADWAL_TES_ID"]= $jadwaltesid;
    $arrdata["PENGGALIAN_ID"]= $penggalianid;
    $arrdata["PEGAWAI_ID"]= $pegawaiid;
    $arrdata["STATUS"]= $set->getField("STATUS");
    array_push($arrpegawaipenggalian, $arrdata);
}
// print_r($arrpegawaipenggalian);exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
	<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../WEB/js/globalfunction.js"></script> 
    <script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>
    
    <style>
		/* UNTUK TABLE GRADIENT STYLE*/
		.gradient-style th {
		font-size: 12px;
		font-weight:400;
		background:#b9c9fe url(images/gradhead.png) repeat-x;
		border-top:2px solid #d3ddff;
		border-bottom:1px solid #fff;
		color:#039;
		padding:8px;
		}
		
		.gradient-style td {
		font-size: 12px;
		border-bottom:1px solid #fff;
		color:#669;
		border-top:1px solid #fff;
		background:#e8edff url(images/gradback.png) repeat-x;
		padding:8px;
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
		margin:0px 0px 0px 10px;
		}
	</style>
    
	<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
</head>
<body class="bg-form" style="overflow-x:scroll;">
	<div id="header-tna-detil">Rekap Asesor <span>Penilaian</span></div>
    <div id="konten">
        <!-- <input type="button" onclick="setcetak()" value="Cetak" /> -->

        <table class="gradient-style" id="link-table" style="width:100%; margin-left:-1px">
            <thead class="altrowstable">
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Nip</th>
                    <th rowspan="2">Nama Peserta</th>
                    <th colspan="<?=$jumlahpenggalian?>" style="text-align: center;">Penggalian</th>
                </tr>
                <tr>
                    <?
                    foreach ($arrpenggalian as $key => $value)
                    {
                        $infolabel= $value["PENGGALIAN_KODE"];
                    ?>
                    <th style="text-align: center;"><?=$infolabel?></th>
                    <?
                    }
                    ?>
                </tr>
           </thead>
           <tbody class="example altrowstable" id="alternatecolor"> 
            <?
            for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
            {
                $reqJadwalTesId= $arrAsesor[$checkbox_index]["JADWAL_TES_ID"];
                $reqPegawaiId= $arrAsesor[$checkbox_index]["PEGAWAI_ID"];
            ?>
                <tr>
                    <td><?=$arrAsesor[$checkbox_index]["NOMOR_URUT_GENERATE"]?></td>
                    <td><?=$arrAsesor[$checkbox_index]["NIP_BARU"]?></td>
                    <td><?=$arrAsesor[$checkbox_index]["NAMA_PEGAWAI"]?></td>
                    <?
                    foreach ($arrpenggalian as $key => $value)
                    {
                        $infocari= $reqJadwalTesId."-".$value["PENGGALIAN_ID"]."-".$reqPegawaiId;
                        $arraycari= in_array_column($infocari, "KEY", $arrpegawaipenggalian);
                        $infostatus= "";
                        if(!empty($arraycari))
                        {
                            $infostatus= $arrpegawaipenggalian[$arraycari[0]]["STATUS"];
                            
                        }
                        // echo $infostatus;exit;

                        $warna= "red";
                        if($infostatus == "1")
                            $warna= "green";
                        else if($infostatus == "2")
                            $warna= "yellow";

                    ?>
                    <td style="background-color: <?=$warna?> !important"></td>
                    <?
                    }
                    ?>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</body>

<script type="text/javascript">
    function setcetak()
    {
        opUrl= "jadwal_rekap_asesor_pegawai_excel.php?reqId=<?=$reqId?>";
        newWindow = window.open(opUrl, 'Cetak');
        newWindow.focus();
    }

    function setIsi(reqAsesorId, reqPegawaiId)
    {
        opUrl= "../asesor/penilaian_monitoring_isi_admin.php?reqJadwalTesId=<?=$reqId?>&reqAsesorId="+reqAsesorId+"&reqPegawaiId="+reqPegawaiId;

        OpenDHTML(opUrl);
        // newWindow = window.open(opUrl, 'Cetak');
        // newWindow.focus();
    }

    $(function ()
    {
    });

    function iecompattest(){
        return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
    }

    function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
    {
        opCaption= "Modul Pengaturan";
        //var left= top = "";
        var left = iecompattest().scrollLeft; //(screen.width/2)-(opWidth/2);
        var top = iecompattest().scrollTop; //(screen.width/2)-(opWidth/2);
        
        opWidth = iecompattest().clientWidth - 25;
        opHeight = iecompattest().clientHeight - 45;

        
        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+',top='+top+',resize=1,scrolling=1,midle=1'); return false;
    }
</script>

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

</html>
</html>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-polakarir/PerencanaanDetil.php");

$pegawai_add_karir_rencana = new PerencanaanDetil();

$reqId = httpFilterGet("reqId");
$reqDeleteId = httpFilterGet("reqDeleteId");
$reqMode = httpFilterGet("reqMode");

/*if($reqId == "")
{
	echo '<script language="javascript">';
	echo 'alert("Isi data pegawai terlebih dahulu.");';	
	echo 'window.parent.location.href = "pegawai_add.php";';
	echo '</script>';
	exit();
}*/
if($reqMode == "delete")
{
	$pegawai_add_karir_rencana->setField("PERENCANAAN_DETIL_ID", $reqDeleteId);
	$pegawai_add_karir_rencana->delete();	

	echo '<script language="javascript">';
	echo 'window.parent.frames["mainFrameDetilPop"].location.reload();';
	echo '</script>';
		
}

$pegawai_add_karir_rencana->selectByParams(array("A.PEGAWAI_ID" => $reqId));
//echo $pegawai_add_karir_rencana->query;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>jQuery treeTable Plugin Documentation</title>
    <link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
    <script language="JavaScript" src="../jslib/displayElement.js"></script>
    <link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
    <script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
    <script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>
    
    <script type="text/javascript" src="jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
    <link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
    <link rel="stylesheet" href="forms/css/uniform.default.css" type="text/css" media="screen">
     <style type="text/css" media="screen">
          label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
            clear: both;
          }
        </style>
    <!-- BEGIN Plugin Code -->
      <!-- END Plugin Code -->
      
      <!-- Popup -->  
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
    
    <link href="styles.css" rel="stylesheet" type="text/css" />
    <script language="Javascript">
    <? include_once "../jslib/formHandler.php"; ?>
    
    function openPopup(opUrl,opWidth,opHeight)
    {
        newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars");
        newWindow.focus();
    }
    </script>
    
    <script language="JavaScript" src="../jslib/displayElement.js"></script>  
    <script language="javascript">
	function openFile(id)
	{
		newWindow = window.open('../json/download.php?reqMode=jabatan&reqId='+id, 'Download');
		newWindow.focus();
	}
	</script>
</head>

<body>
<div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
    <table id="gradient-style" style="width:100%">
    <thead>
    <tr>
        <td colspan="7">
            <div id="header-tna-detil">Data <span>Pencapaian Karir dan Rencana Karir</span></div>
        </td>			
    </tr>
    <tr>
     <th scope="col">Rencana Karir</th>
    <?php /*?><th scope="col">Pangkat/Gol Ruang</th><?php */?>
    <th scope="col">Unit Kerja</th>
    <th scope="col">Nama Jabatan</th>
    <th scope="col">Usia</th>
    <th scope="col">Tahun Rencana</th>
    <th scope="col">Aksi</th>
    </tr>
    </thead>
    <tbody>
    
    <?
    while($pegawai_add_karir_rencana->nextRow())
	{
	?>
        <tr onClick="parent.frames['mainFrameDetilPop'].location.href = 'pegawai_add_karir_rencana.php?reqId=<?=$reqId?>&reqRowId=<?=$pegawai_add_karir_rencana->getField("PERENCANAAN_DETIL_ID")?>'">
            <td><?=$pegawai_add_karir_rencana->getField("TIPE_RENCANA_DETIL")?></td>
            <?php /*?><td><?=$pegawai_add_karir_rencana->getField("PANGKAT_REN")?></td><?php */?>
            <td><?=$pegawai_add_karir_rencana->getField("SATKER_REN")?></td>
            <td><?=$pegawai_add_karir_rencana->getField("JABATAN")?></td>
            <td><?=$pegawai_add_karir_rencana->getField("USIA_REN")?></td>
            <td><?=$pegawai_add_karir_rencana->getField("TAHUN")?></td>
            <td>
            <a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = 'pegawai_add_karir_rencana_monitoring.php?reqMode=delete&reqId=<?=$pegawai_add_karir_rencana->getField("PEGAWAI_ID")?>&reqDeleteId=<?=$pegawai_add_karir_rencana->getField("PERENCANAAN_DETIL_ID")?>' }"><img src="../WEB/images/delete-icon.png"></a>
            <?
			if($pegawai_add_karir_rencana->getField('UKURAN') == ""){}
			else
			{
			?>
				<a href="#" onclick="openFile('<?=$pegawai_add_karir_rencana->getField('JABATAN_ID')?>');" ><img src="images/download.png" width="15" height="15" title="Syarat"/></a>
			<?
			}
			?>
            </td>
        </tr>    
    <?
	}
	?>    
    </table>

</div>
</body>
</html>
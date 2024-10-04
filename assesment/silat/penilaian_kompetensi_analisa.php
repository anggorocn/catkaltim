<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-silat/Training.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* create objects */
$set = new Kelautan();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo(); 
}
/* VARIABLE */
$reqMode = httpFilterRequest("reqMode");
$reqTahun = httpFilterGet("reqTahun");
$reqPersen= httpFilterGet("reqPersen");
$reqPegawaiId = httpFilterRequest("reqPegawaiId");  
$reqIdOrganisasi = httpFilterGet("reqIdOrganisasi");  
$reqKetOrganisasi = httpFilterGet("reqKetOrganisasi");

$index_tahun= 0;
$arrTahun= "";
$set_tahun= new Training();
$set_tahun->selectByParamsCombo(array(), -1,-1, "", "TAHUN");
while($set_tahun->nextRow())
{
	$arrTahun[$index_tahun]= $set_tahun->getField("TAHUN");
	$index_tahun++;
}
//$arrTahun= setTahunLoop(5,1);

if($reqPegawaiId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi identitas pegawai terlebih dahulu.');";	
	echo "window.top.location.href = 'pegawai_edit.php?reqIdOrganisasi=".$reqIdOrganisasi."&reqKetOrganisasi=".$reqKetOrganisasi."';";
	echo '</script>';
}

/* DEFAULT VALUES */
$rcBright = "table_list_bright";
$rcDark = "table_list_dark";
$rcI = 0;

//if($reqTahun == "")
 //$reqTahun = date("Y");

if($reqPersen == "")
	$reqPersen = 0;
/* DATA VIEW */

if($reqTahun == ""){}
else
$statement= " AND TO_CHAR(A1.TANGGAL_TES, 'YYYY') = '".$reqTahun."' ";

$statement.= " AND C1.GAP < 0 ";

$sGroupBy= " GROUP BY TO_CHAR(A1.TANGGAL_TES, 'YYYY'), F1.NAMA";
$set->selectByParamsMonitoringAnalisaPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId), -1, -1, $statement);
//echo $set->query;exit;
if($reqMode == 'simpan')	$alertStatus = "Data Berhasil Tersimpan";
elseif($reqMode == 'error')	$alertStatus = "Data Gagal Tersimpan";
elseif($reqMode == 'hapus')	$alertStatus = "Data Berhasil dihapus";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css"><script src="popup.js" type="text/javascript"></script>
 <script language="JavaScript" src="../jslib/displayElement.js"></script>
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
<link type="text/css" href="validate/jquery-ui.datepickerValidate.css" rel="stylesheet" />
<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script src="forms/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
  $(function(){
	$("input, textarea, select, button").uniform();
	
	$("#reqTahun").change(function() { 
		parent.frames['mainFrame'].location.href = 'penilaian_kompetensi_analisa.php?reqPegawaiId=<?=$reqPegawaiId?>&reqTahun='+$("#reqTahun").val()+'&reqPersen='+$("#reqPersen").val();
	});
	
	$("#reqPersen").change(function() { 
		parent.frames['mainFrame'].location.href = 'penilaian_kompetensi_analisa.php?reqPegawaiId=<?=$reqPegawaiId?>&reqTahun='+$("#reqTahun").val()+'&reqPersen='+$("#reqPersen").val();
	});
	
  });
</script>
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
<?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
</head>
<script type="text/javascript">
 
$(document).ready(function(){
 
	$('#page_effect').fadeIn(2000);
 
});
 
</script>
<body>
<? if($alertMsg) include_once("../WEB/modules/alertMsg.php");?>
<script type="text/javascript">
    function ChangeColor(tableRow, highLight)
    {
    if (highLight)
    {
      tableRow.style.backgroundColor = '#dcfac9';
    }
    else
    {
      tableRow.style.backgroundColor = 'white';
    }
  }
  
  function getFile(url)
  {
	  newWindow = window.open(url, 'Download');
	  newWindow.focus();
  }
  
  function getFileJabatan()
  {
	  newWindow = window.open('tes.pdf', 'Cetak');
	  newWindow.focus();
  }
  
</script>

<div id="page_effect" style="display:none;">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
<? if($alertMsg) include_once("../WEB/modules/alertMsg.php");?>	    
    <table class="table_list" cellspacing="1" width="100%">
    <tr class="judul-halaman">
    
      <td colspan="12"><div id="header-tna-detil">Analisa <span>Training</span></div></td>
    </tr>
    <tr class="judul-halaman">
    	<td colspan="7">
            Tahun : <select name="reqTahun" id="reqTahun">
            <option value="">Semua</option>
           <?
           for($i=0;$i<count($arrTahun);$i++)
           {
           ?>
            <option value="<?=$arrTahun[$i]?>" <? if($reqTahun == $arrTahun[$i]) { ?> selected <? } ?>><?=$arrTahun[$i]?></option>
           <?	  
           }
           ?>
           </select>
           <?php /*?>Persen :
           <select id="reqPersen">
            <option value="0" <? if($reqPersen == "0") echo "selected";?>>0%</option>
            <option value="0.05" <? if($reqPersen == "0.05") echo "selected";?>>5%</option>
            <option value="0.1" <? if($reqPersen == "0.1") echo "selected";?>>10%</option> 
           </select><?php */?>
        </td>
    </tr>
    <tr>
      <th>No</th>
      <th>Rekomendasi Training</th>
      <?php /*?><th>Kamus kompetensi</th>
      <th>Kurikulum</th>
      <th>Gap</th><?php */?>
      </tr>
      <?
	  $tahun="";
	  $no=1;
	  while($set->nextRow())
	  {
		  if($reqTahun == "")
		  {
			  if($tahun == $set->getField("TAHUN")){}
			  else
			  {
      ?>
      			<th colspan="4">Tahun : <?=$set->getField("TAHUN")?></th>
      <?
		  	  }
		  }
      ?>
      <?php /*?><th>Gap</th><?php */?>
      </tr>
      <tr style="background-color:#FFF">
        <td width="10" align=center><?=$no?></td>
        <td><?=$set->getField("TRAINING_NAMA")?></td>
        <?php /*?><td>
			<?
            if($set->getField("KAMUS") == "")
                echo "-";
            else
                echo "<a href=\"#\" onclick=\"getFile('download.php?reqMode=kamus&reqRowId=".$set->getField("STANDAR_KOMPETENSI_ID")."');\" ><img src=\"../WEB/images/down.png\" width=\"16\" height=\"16\"/></a>";
            ?>
        </td>
        <td>
        	<?
            if($set->getField("KURIKULUM") == "")
                echo "-";
            else
                echo "<a href=\"#\" onclick=\"getFile('download.php?reqMode=kurikulum&reqRowId=".$set->getField("STANDAR_KOMPETENSI_ID")."');\" ><img src=\"../WEB/images/down.png\" width=\"16\" height=\"16\"/></a>";
			?>
		</td>
        <td align="center"><?=$set->getField("NILAI")?></td>
        <td align="center"><?=$set->getField("BOBOT")?></td>
        <td align="center"><?=(float)$set->getField("GAP")?></td><?php */?>
      </tr>
      <?
	  $tahun = $set->getField("TAHUN");
	  $no++;
	  }
      ?>
      <tr class="">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
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
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

//set order bayar
$arrDetil="";
$index_detil= 0;
//$set_detil->selectByParamsMonitoringPenilaian(array(), -1, -1, " AND B.ASPEK_ID = 2 AND A.JABATAN_ID = ".$tempJabatanTesId." AND A.SATKER_ID = '".$tempSatkerTesId."'", $reqRowId);
$set_detil->selectByParamsMonitoringPenilaian(array(), -1, -1, " AND B.ASPEK_ID = 2 AND A.SATKER_ID = '".$tempSatkerTesId."'", $reqRowId);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrDetil[$index_detil]["NAMA"] = $set_detil->getField("NAMA");
	$arrDetil[$index_detil]["NILAI_STANDAR"] = $set_detil->getField("NILAI_STANDAR");
	$arrDetil[$index_detil]["BOBOT"] = $set_detil->getField("BOBOT");
	$arrDetil[$index_detil]["ATRIBUT_ID"] = $set_detil->getField("ATRIBUT_ID");
	$arrDetil[$index_detil]["ATRIBUT_ID_PARENT"] = $set_detil->getField("ATRIBUT_ID_PARENT");
	$arrDetil[$index_detil]["ATRIBUT_GROUP"] = $set_detil->getField("ATRIBUT_GROUP");
	$arrDetil[$index_detil]["PENILAIAN_DETIL_ID"] = $set_detil->getField("PENILAIAN_DETIL_ID");
	$arrDetil[$index_detil]["PENILAIAN_ID"] = $set_detil->getField("PENILAIAN_ID");
	$arrDetil[$index_detil]["NILAI"] = $set_detil->getField("NILAI");
	$arrDetil[$index_detil]["GAP"] = $set_detil->getField("GAP");
	$index_detil++;
}
//print_r($arrDetil);exit;
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
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
   <div id="content" style="height:auto; margin-top:-4px; width:100%">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
    	<table class="table_list" cellspacing="1" width="100%">
        	<tr>
                <td colspan="6">
                <div id="header-tna-detil">INDEKS KESENJANGAN KOMPETENSI <span> ASPEK KOMPETENSI</span></div>	                    
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
			      <td colspan="6">
                  		<ol id="toc"> 
                            <li><a href="#" onclick="parent.setLoad('kompetensi_add_penilaian_monitoring.php?reqId=<?=$reqId?>', '');"><span>Kembali</span></a></li>
                            <li class="current"><a href="#"><span>ASPEK KOMPETENSI</span></a></li>
                            <li><a href="#" onclick="parent.setLoad('kompetensi_add_penilaian_atribut_spider.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>', '1');"><span>Grafik<!--Spider Plot--></span></a></li>
                        </ol>
                  		<table width="100%" border="0" cellspacing="1" cellpadding="2" style="margin-top:-10px;">
                        	<thead>
                              <tr class="">
                                <td rowspan="2" class="judul-kolom-2row">No</td>
                                <td rowspan="2" class="judul-kolom-2row">ATRIBUT & INDIKATOR</td>
                                <td rowspan="2" class="judul-kolom-2row">Bobot</td>
                                <td rowspan="2" class="judul-kolom-2row">Standar Level</td>
                                <td colspan="4" class="judul-kolom">Hasil Individu</td>
                                <td rowspan="2" class="judul-kolom-2row">Gap</td>
                              </tr>
                              <tr class="judul-kolom">
                                <td width="100px">1</td>
                                <td width="100px">2</td>
                                <td width="100px">3</td>
                                <td width="100px">4</td>
                              </tr>
                           </thead>
                           <tbody>
                           	  <?
							  $tempGroup= "";
							  $index_atribut_parent= 0;
							  for($checkbox_index=0; $checkbox_index < $index_detil; $checkbox_index++)
							  {
								$tempNama= $arrDetil[$checkbox_index]["NAMA"];
								$tempNilaiStandar= $arrDetil[$checkbox_index]["NILAI_STANDAR"];
								$tempBobot= $arrDetil[$checkbox_index]["BOBOT"];
								$tempAtributId= $arrDetil[$checkbox_index]["ATRIBUT_ID"];
								$tempAtributIdParent= $arrDetil[$checkbox_index]["ATRIBUT_ID_PARENT"];
								$tempAtributGroup= $arrDetil[$checkbox_index]["ATRIBUT_GROUP"];
								$tempPenilaianDetilId= $arrDetil[$checkbox_index]["PENILAIAN_DETIL_ID"];
								$tempPenilaianId= $arrDetil[$checkbox_index]["PENILAIAN_ID"];
								$tempNilai= $arrDetil[$checkbox_index]["NILAI"];
								$tempGap= $arrDetil[$checkbox_index]["GAP"];
								
								//kondisi parent
								if($tempGroup == $tempAtributGroup)
								{
									$index_atribut++;
								}
								else
								{
									$index_atribut_parent++;
									$index_atribut= 0;
								}
								
								$tempGroup= $tempAtributGroup;
								
								if($index_atribut_parent % 2 == 0)
									$css= "terang";
								else
									$css= "gelap";
							  ?>
								  <?
                                  if($tempAtributIdParent == "0")
                                  {
                                  ?>
                                  <tr class="<?=$css?>">
                                    <td width="10"><b><?=romanicNumber($index_atribut_parent)?></b></td>
                                    <td><b><?=$tempNama?></b></td>
                                    <td align="center"><?=NolToNone($tempBobot)?>&nbsp;</td>
                                    <td align="center"><?=NolToNone($tempNilaiStandar)?>&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                  </tr>
                                  <?
                                  }
								  else
								  {
									  $arrChecked= radioPenilaianInfo($tempNilai);
                                  ?>
                                  <tr class="<?=$css?>">
                                    <td width="10">
										<?=$index_atribut?>
                                    </td>
                                    <td><?=$tempNama?></td>
                                    <td align="center"><?=NolToNone($tempBobot)?>&nbsp;</td>
                                    <td align="center"><?=NolToNone($tempNilaiStandar)?>&nbsp;</td>
                                    <td align="center"><?=$arrChecked[0]?></td>
                                    <td align="center"><?=$arrChecked[1]?></td>
                                    <td align="center"><?=$arrChecked[2]?></td>
                                    <td align="center"><?=$arrChecked[3]?></td>
                                    <td align="center"><label id="reqGapInfo<?=$checkbox_index?>"><?=$tempGap?></label>&nbsp;</td>
                                  </tr>
                                  <?
								  }
                                  ?>
                              <?
							  $tempTotalBobot+= $tempBobot;
							  }
                              ?>
                            </tbody>
                            <tfoot>
                            	<?
								$index_atribut_parent++;
								if($index_atribut_parent % 2 == 0)
									$css= "terang";
								else
									$css= "gelap";
                                ?>
                           		<tr class="<?=$css?>">                                	
                                    <td width="10">&nbsp;</td>
                                    <td><b>SKOR ASPEK KOMPETENSI</b></td>
                                    <td align="center"><?=$tempTotalBobot?>&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                </tr>
                            </tfoot>
            			</table>
                  </td>
            </tr>    
        </table>
    </form>
    </div>
</div>
</body>
</html>
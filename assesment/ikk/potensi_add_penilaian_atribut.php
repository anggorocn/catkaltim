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
$reqTahun= httpFilterRequest("reqTahun");

/* create objects */
$pegawai = new Kelautan();
$set = new Penilaian();
$set_detil= new PenilaianDetil();

/* VALIDATION */
$pegawai->selectByParamsMonitoringPegawai(array("A.PEGAWAI_ID" => $reqId));
$pegawai->firstRow();
$tempNama= $pegawai->getField("NAMA");
$tempJabatanSaatIni= $pegawai->getField("NAMA_JAB_STRUKTURAL");
$tempUnitKerjaSaatIni= $pegawai->getField("SATKER");

$set->selectByParams(array("A.PENILAIAN_ID"=>$reqRowId), -1, -1);
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDateTime($set->getField("TANGGAL_TES"), false);
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempNamaAsesi= $set->getField("NAMA_ASESI");
$tempAspekNama= $set->getField("ASPEK_NAMA");
$tempMetode= $set->getField("METODE");
	
//set order bayar
$arrDetil="";
$index_detil= 0;
//$set_detil->selectByParamsMonitoringPenilaian(array(), -1, -1, " AND B.ASPEK_ID = 1 AND A.JABATAN_ID = ".$tempJabatanTesId." AND A.SATKER_ID = '".$tempSatkerTesId."'", $reqRowId);
//$set_detil->selectByParamsMonitoringPenilaianModif(array(), -1, -1, " AND B.ASPEK_ID = 1 AND A.JABATAN_ID = '".$tempJabatanTesId."' AND A.SATKER_ID = '".$tempSatkerTesId."'", $reqRowId);
// kondisi aktif permen
$statement= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM penilaian_detil WHERE 1=1 AND PENILAIAN_ID = ".$reqRowId." GROUP BY PERMEN_ID) X WHERE AKTIF_PERMENT = PERMEN_ID)";
$set_detil->selectByParamsMonitoringPenilaian(array(), -1, -1, $statement, $reqRowId);
// echo $set_detil->query;exit;
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
	$arrDetil[$index_detil]["BUKTI"] = $set_detil->getField("BUKTI");
	$arrDetil[$index_detil]["CATATAN"] = $set_detil->getField("CATATAN");
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
<!-- <script type="text/javascript" src="css/dropdowntabs.js"></script> -->

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<!-- <link href="styles.css" rel="stylesheet" type="text/css" /> -->

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">
	$.extend($.fn.validatebox.defaults.rules, {
		requireRadio: {  
			validator: function(value, param){  
				var input = $(param[0]);
				input.off('.requireRadio').on('click.requireRadio',function(){
					$(this).focus();
				});
				return $(param[0] + ':checked').val() != undefined;
			},  
			message: 'Please choose option for {1}.'  
		}  
	});
	$(function(){
		//$('input[name=reqRadio1][value=3]').attr('checked', 'checked');
		$('#ff').form({
			url:'../json-ikk/potensi_add_penilaian_atribut.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				document.location.href= "potensi_add_penilaian_atribut.php?reqId=<?=$reqId?>&reqTahun=<?=$reqTahun?>&reqRowId=<?=$reqRowId?>";
				//top.frames['mainFrame'].location.reload();
			}
		});
		
		$('input[id^="reqRadio"]').change(function(e) {
			var tempId= $(this).attr('id');
			var tempValId= $(this).val();
			tempId= tempId.split('reqRadio');
			tempId= tempId[1];
			
			$("#reqNilaiAtribut"+tempId).val(tempValId);
			var gap= parseInt(tempValId) - parseInt($("#reqNilaiStandar"+tempId).val());
			$("#reqGap"+tempId).val(gap);
			$("#reqGapInfo"+tempId).text(gap);
		});
	});
</script>
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
<!-- <div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div> -->
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
       		<tr style="display:none" class="terang">
                <td>Nama Asesi</td>
                <td>:</td>
                <td>
                    <?=$tempNamaAsesi?>
                </td>
            </tr>
            <tr style="display:none" class="gelap">
                <td>Metode</td>
                <td>:</td>
                <td>
                    <?=$tempMetode?>
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
                            <li><a href="#" onclick="parent.setLoad('general_ikk_add_penilaian_monitoring.php?reqId=<?=$reqId?>&reqTahun=<?=$reqTahun?>', '');"><span>Kembali</span></a></li>
                            <li class="current"><a href="#"><span><?=$tempAspekNama?></span></a></li>
                            <li><a href="#" onclick="parent.setLoad('potensi_add_penilaian_atribut_spider.php?reqId=<?=$reqId?>&reqTahun=<?=$reqTahun?>&reqRowId=<?=$reqRowId?>', '1');"><span>Grafik<!--Spider Plot--></span></a></li>
                        </ol>
                  		<table width="100%" border="0" cellspacing="1" cellpadding="2" style="margin-top:-10px;">
                        	<thead>
                              <tr class="">
                                <td rowspan="2" class="judul-kolom-2row">No</td>
                                <td rowspan="2" class="judul-kolom-2row">ATRIBUT & INDIKATOR</td>
                                <td style="display:none" rowspan="2" class="judul-kolom-2row">Bobot</td>
                                <td rowspan="2" class="judul-kolom-2row">Rating</td>
                                <td colspan="5" class="judul-kolom">Hasil Individu</td>
                                <td rowspan="2" class="judul-kolom-2row">Gap</td>
                                <td rowspan="2" style="display:none" class="judul-kolom-2row">Bukti Perilaku</td>
                                <td rowspan="2" class="judul-kolom-2row">Deskripsi</td>
                              </tr>
                              <tr class="judul-kolom">
                                <td width="100px">1</td>
                                <td width="100px">2</td>
                                <td width="100px">3</td>
                                <td width="100px">4</td>
                                <td width="100px">5</td>
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
								
								if($tempGap == "" || $tempGap == "0")
								$tempGap= 0;
								else
								$tempGap= $tempNilai-$tempNilaiStandar;
								
								$tempBukti= $arrDetil[$checkbox_index]["BUKTI"];
								$tempCatatan= $arrDetil[$checkbox_index]["CATATAN"];
								$tempCatatan= str_replace("<br>", "\n", $tempCatatan);
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
                                    <td style="display:none" align="center"><?=NolToNone($tempBobot)?>&nbsp;</td>
                                    <td align="center"><?=NolToNone($tempNilaiStandar)?>&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td style="display:none" align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                  </tr>
                                  <?
                                  }
								  else
								  {
									  $arrChecked= radioPenilaian($tempNilai);
                                  ?>
                                  <tr class="<?=$css?>">
                                    <td width="10">
										<?=$index_atribut?>
                                        <input type="hidden" name="reqPenilaianDetilId[]" id="reqPenilaianDetilId<?=$checkbox_index?>" value="<?=$tempPenilaianDetilId?>" />
                                        <input type="hidden" name="reqNilaiAtribut[]" id="reqNilaiAtribut<?=$checkbox_index?>" value="<?=$tempNilai?>" />
                                        <input type="hidden" name="reqAtributId[]" id="reqAtributId<?=$checkbox_index?>" value="<?=$tempAtributId?>" />
                                        <input type="hidden" name="reqGap[]" id="reqGap<?=$checkbox_index?>" value="<?=$tempGap?>" />
                                        <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar<?=$checkbox_index?>" value="<?=$tempNilaiStandar?>" />
                                        <?php /*?>reqNilaiStandar;reqGap;reqGapInfo;reqNilaiAtribut<?php */?>
                                    </td>
                                    <td><?=$tempNama?></td>
                                    <td style="display:none" align="center"><?=NolToNone($tempBobot)?>&nbsp;</td>
                                    <td align="center"><?=NolToNone($tempNilaiStandar)?>&nbsp;</td>
                                    <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[0]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio<?=$checkbox_index?>]\', \'Pilih nilai\']'"/></td>
                                    <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[1]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="2" /></td>
                                    <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[2]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="3" /></td>
                                    <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[3]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="4" /></td>
                                    <td align="center"><input type="radio" class="easyui-validatebox" <?=$arrChecked[4]?> name="reqRadio<?=$checkbox_index?>" id="reqRadio<?=$checkbox_index?>" value="5" /></td>
                                    <td align="center"><label id="reqGapInfo<?=$checkbox_index?>"><?=$tempGap?></label>&nbsp;</td>
                                    <td style="display:none"><textarea name="reqBukti[]" id="reqBukti<?=$checkbox_index?>" style="width:95%" rows="1" ><?=$tempBukti?></textarea></td>
                                    <td><textarea name="reqCatatan[]" id="reqCatatan<?=$checkbox_index?>" style="width:95%" rows="1" ><?=$tempCatatan?></textarea></td>
                                  </tr>
                                  <?
								  }
                                  ?>
                              <?
							  $tempTotalBobot+= $tempBobot;
							  }
                              ?>
                            </tbody>
                            <tfoot style="display:none">
                            	<?
								$index_atribut_parent++;
								if($index_atribut_parent % 2 == 0)
									$css= "terang";
								else
									$css= "gelap";
                                ?>
                           		<tr class="<?=$css?>">                                	
                                    <td width="10">&nbsp;</td>
                                    <td><b>SKOR ASPEK PSIKOLOGI</b></td>
                                    <td align="center"><?=$tempTotalBobot?>&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
                                    <td align="center">&nbsp;</td>
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
        	<tr>
            	<td>
                    <input type="hidden" id="reqRowId" name="reqRowId" value="<?=$reqRowId?>">
                    <input type="hidden" id="reqId" name="reqId" value="<?=$reqId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
                    <input type="reset" name="" value="Reset" />
                </td>
            </tr>        
        </table>
    </form>
    </div>
</div>
</body>
</html>
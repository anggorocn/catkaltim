<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/PegawaiPenilai.php");
include_once("../WEB/classes/base-skp/Pertanyaan.php");
include_once("../WEB/classes/base-skp/Jawaban.php");

$pegawai_penilai = new PegawaiPenilai();
$pertanyaan = new Pertanyaan();

$reqId = httpFilterGet("reqId");
$reqMode = httpFilterGet("reqMode");
$reqBulan = httpFilterGet("reqBulan");
$reqTahun = httpFilterGet("reqTahun");

if($reqBulan == "")
	$reqBulan = (int)date("m");

if($reqTahun == "")
	$reqTahun = (int)date("Y");


$pegawai_penilai->selectByParamsPenilai(array('A.IDPEG'=>$reqId));
$pegawai_penilai->firstRow();
//echo $pegawai_penilai->query;exit;

$tempNama= $pegawai_penilai->getField('NAMA');
$tempJabatan= $pegawai_penilai->getField('JABATAN');
$tempNipBaru= $pegawai_penilai->getField('NIP_BARU');
$tempDepartemen=$pegawai_penilai->getField('DEPARTEMEN');
$tempPangkat=$pegawai_penilai->getField('NMGOLRUANG');
$tempGolongan=$pegawai_penilai->getField('GOL_RUANG');


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
<script type="text/javascript">	
$(function(){
	$('#ff').form({
		url:'../json-skp/perilaku_kerja_penilaian_add.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);
			$.messager.alert('Info', data, 'info');
			document.location.href = 'perilaku_kerja_penilaian_add.php?reqId=<?=$reqId?>&reqBulan=<?=$reqBulan?>&reqTahun=<?=$reqTahun?>';
			top.frames['mainFrame'].location.reload();
		}
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
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
        <fieldset>
            <legend>Identitas Pegawai</legend>
            <div id="data-detil">
                <table>
                    <tr>
                        <td>NIP</td>
                        <td>:</td>
                        <td>
                            &nbsp;<?=$tempNipBaru?>
                        </td>
                        <td>&nbsp;</td>
                        <?php /*?><td rowspan="5"><img class="foto" src="../WEB/images/noimg.jpg"></td><?php */?>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>
                            &nbsp;<?=$tempNama?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Pangkat / Gol</td>
                        <td>:</td>
                        <td>
                            <?=$tempPangkat?> / <?=$tempGolongan?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>
                            &nbsp;<?=$tempJabatan?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>  
                    <tr>
                        <td>Satuan Kerja</td>
                        <td>:</td>
                        <td>
                            &nbsp;<?=$tempDepartemen?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Periode SKP</td>
                        <td>:</td>
                        <td>
                            &nbsp;<?=getNamePeriode(generateZero($reqBulan, 2).$reqTahun)?>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </fieldset>
    <fieldset>
    <legend>Penilaian Perilaku</legend>
    <div id="data-tabel-noborder">  
    <table class="example" id="dataTableRowDinamisMain">
    <!--<table class="example altrowstable" id="alternatecolor" >-->
        <thead class="altrowstable">
          <tr>
            <th align="center" class="bg-header"><strong>No.</strong></th>
            <th align="center" class="bg-header" width="50%"><strong>Kategori</strong></th>
            <th align="center" class="bg-header"><strong>Sebutan</strong></th>
            <th colspan="3" align="center" class="bg-header"><strong>Range Nilai</strong></th>
            <th align="center" class="bg-header" style="display:none"><strong>Keterangan Bukti Perilaku</strong></th>
          </tr>
        </thead>
        <tbody class="example altrowstable" id="alternatecolor"> 
          <?
		  $i=1;
		  $kategori = "";
		  
		  function getSurveyByParent($id_induk, $jawabanid, $range)
		  {
				$child = new Jawaban();
				$child->selectByParams(array("PERTANYAAN_ID" => $id_induk));
				while($child->nextRow())
				{
					$check1 = $check2 = $check3 = "";
					
					if($child->getField("RANGE_1") == $range)
						$check1 = "checked";
					elseif($child->getField("RANGE_2") == $range)
						$check2 = "checked";
					elseif($child->getField("RANGE_3") == $range)
						$check3 = "checked";
					
					
					
					echo "
					<tr class='bg-gelap'>
						<td>&nbsp;</td>
						<td>".$child->getField('KETERANGAN')."</td>
						<td>".$child->getField('JAWABAN')."</td>
						<td>&nbsp;<input type=\"radio\" name=\"reqKenyataan".$id_induk."\" onClick=\"document.getElementById('reqRangeNilai".$id_induk."').value= '".$child->getField("RANGE_1")."'; document.getElementById('reqJawabanId".$id_induk."').value= '".$child->getField("JAWABAN_ID")."'\" ".$check1.">".$child->getField("RANGE_1")."</td>
						<td>&nbsp;<input type=\"radio\" name=\"reqKenyataan".$id_induk."\" onClick=\"document.getElementById('reqRangeNilai".$id_induk."').value= '".$child->getField("RANGE_2")."'; document.getElementById('reqJawabanId".$id_induk."').value= '".$child->getField("JAWABAN_ID")."'\" ".$check2.">".$child->getField("RANGE_2")."</td>
						<td>&nbsp;<input type=\"radio\" name=\"reqKenyataan".$id_induk."\" onClick=\"document.getElementById('reqRangeNilai".$id_induk."').value= '".$child->getField("RANGE_3")."'; document.getElementById('reqJawabanId".$id_induk."').value= '".$child->getField("JAWABAN_ID")."'\" ".$check3.">".$child->getField("RANGE_3")."</td>			
					  </tr>
					";
				
				}
		  }		  
		
		  $pertanyaan->selectByParamsPenilaian(array("A.TAHUN" => $reqTahun));
		  
	      while($pertanyaan->nextRow())
		  {
			  if($kategori == $pertanyaan->getField('KATEGORI_NAMA'))
			  {
			  }
			  else
			  {
			  ?>
              <tr class="bg-gelap">
                <td colspan="7"><strong><?=$pertanyaan->getField('KATEGORI_NAMA')?></strong></td>
              </tr>              
              <?
			  }
			  ?>
              <tr class="bg-gelap">
                 <td><?=$i?>.</td>
                 <td colspan="5"><?=$pertanyaan->getField('PERTANYAAN')?></td>
                 <td rowspan="<?=$pertanyaan->getField("JUMLAH")+1?>" valign="top"  style="display:none">
                	<textarea style="height:<?=$pertanyaan->getField("JUMLAH") * 30?>px; margin-top:10px" name="reqSaran[]"></textarea>
                	<input type="hidden" name="reqPerilakuKerjaId[]" value="<?=$pertanyaan->getField("PERILAKU_KERJA_ID")?>">
                	<input type="hidden" name="reqPertanyaanId[]" value="<?=$pertanyaan->getField("PERTANYAAN_ID")?>">
                    <input type="hidden" name="reqRangeNilai[]" id="reqRangeNilai<?=$pertanyaan->getField('PERTANYAAN_ID')?>" value="<?=$pertanyaan->getField("RANGE")?>">
                    <input type="hidden" name="reqJawabanId[]" id="reqJawabanId<?=$pertanyaan->getField('PERTANYAAN_ID')?>" value="<?=$pertanyaan->getField("JAWABAN_ID")?>">                  
                </td>                 
              </tr>
          <?
			getSurveyByParent($pertanyaan->getField('PERTANYAAN_ID'), $pertanyaan->getField("JAWABAN_ID"), $pertanyaan->getField("RANGE"));
		  	$i++;
			$kategori = $pertanyaan->getField('KATEGORI_NAMA');
		  }
		  ?>              
        </tbody>   
    </table>  
    </div>
    </fieldset>
    <input type="hidden" name="reqId" value="<?=$reqId?>">
    <input type="submit" value="Submit">
    <input type="reset" id="rst_form">
    </form>
    </div>
       <script>
		$("input[id^='reqAK'], input[id^='reqKuantitas'], input[id^='reqKualitas'], input[id^='reqWaktu'], input[id^='reqKuantitasTambahan']").keypress(function(e) {
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			{
			return false;
			}
		});
		</script>
    </div>
</div>
</body>
</html>
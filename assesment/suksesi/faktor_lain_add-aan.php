<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaSuksesi.php");
include_once("../WEB/classes/base/FormulaFaktor.php");


$set= new FormulaSuksesi();
$setcheck= new FormulaFaktor();


$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode= "insert";
}
else
{
	$reqMode= "update";
	$set->selectByParams(array("FORMULA_ID"=> $reqId),-1,-1,'');
	$set->firstRow();
	$tempFormula= $set->getField('FORMULA');
	$tempTahun= $set->getField('TAHUN');
	$tempKeterangan= $set->getField('KETERANGAN');
	$tempTipeFormula= $set->getField('TIPE_FORMULA');

	$setcheck->selectByParams(array("A.FORMULA_ID"=> $reqId),-1,-1,'');
	$setcheck->firstRow();
	$tempFormulaNama= $setcheck->getField('FORMULA');
	$tempGrafikId= $setcheck->getField('ID_GRAFIK');
	$tempKuadranId= $setcheck->getField('ID_KUADRAN');
	$tempAssesment= $setcheck->getField('ASSESMENT');
	$tempKuadran= $setcheck->getField('KUADRAN_NAMA');
	$tempFormulaFaktorId= $setcheck->getField('FORMULA_FAKTOR_ID');
	
}
// echo $setcheck->query;exit;
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
	function lanjutForm()
	{
		parent.executeOnClick('formula_eselon');
	}
	
	$(function(){
		$('#ff').form({
			url:'../json-suksesi/faktor_lain_add_data.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= data[0];
				$('#rst_form').click();
				top.frames['mainFullFrame'].location.reload();
				parent.frames['menuFrame'].location.href = 'formula_suksesi_add_menu.php?reqId='+reqId;
				document.location.href = 'faktor_lain_add.php?reqId='+reqId;
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
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
			<table class="table_list" cellspacing="1" width="100%">
				<tr>
					<td colspan="6">
					<div id="header-tna-detil">Faktor <span>Lain</span></div>
					</td>			
				</tr>
				<tr>
					<td width="15%" ><input type="checkbox" name="checkbox" class="checkbox"  id="checkbox" value="<?=$tempAssesment?>" /> Assesment &emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;:</td>
					<td >&emsp;&emsp;
						<input type="text" name="reqAssesment" id="reqAssesment" class="easyui-validatebox" style="width:100px" value="<?=$tempAssesment?>" />
					</td>
				</tr>
				<tr>
					<td width="25%"><input type="checkbox" name="checkbox1" class="checkbox1" id="checkbox1" value="<?=$tempGrafikId?>" /> Grafik Talent pool &emsp;&emsp;: </td>        
					<td >&emsp;&emsp;
						<select name ="reqGrafikId" id="reqGrafikId" >
							<option value="">Semua</option>
							<option value="1" <? if($tempGrafikId == '1') echo 'selected';?> >Grafik Nine Box Potensi Kompetensi</option>
							<option value="2" <? if($tempGrafikId == '2') echo 'selected';?>>Grafik Nine Box Kompetensi Kinerja</option>
							<option value="3" <? if($tempGrafikId == '3') echo 'selected';?>>Grafik Nine Box JPM Kinerja</option>
						</select>
					</td>
		        </tr>
		        <tr> 
					<td width="15%"><input type="checkbox" name="checkbox2" class="checkbox2" id="checkbox2" value="<?=$tempKuadranId?>" /> Kuadran &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:</td>
					<td >&emsp;&emsp;
						<select id="reqKuadranId" name="reqKuadranId">
							<?
							if($tempGrafikId == 1)
							{
							?>
								<option value="">Semua</option>   
								<option value="11" <? if($tempKuadranId == '11') echo 'selected';?> data-pub="1">I. Tingkatkan Kompetensi</option>
								<option value="12" <? if($tempKuadranId == '12') echo 'selected';?> data-pub="1">II. Tingkatkan Peran Saat Ini</option>
								<option value="21" <? if($tempKuadranId == '21') echo 'selected';?> data-pub="1">III. Tingkatkan Peran Saat Ini</option>
								<option value="13" <? if($tempKuadranId == '13') echo 'selected';?> data-pub="1">IV. Tingkatkan Peran Saat Ini</option>
								<option value="22" <? if($tempKuadranId == '22') echo 'selected';?>  data-pub="1">V. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="31" <? if($tempKuadranId == '31') echo 'selected';?> data-pub="1">VI. Pertimbangkan (mutasi)</option>
								<option value="23" <? if($tempKuadranId == '23') echo 'selected';?> data-pub="1">VII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="32" <? if($tempKuadranId == '32') echo 'selected';?> data-pub="1">VIII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="33" <? if($tempKuadranId == '33') echo 'selected';?> data-pub="1">IX. Siap Untuk Peran Di Masa Depan</option>

								<option value="11" data-pub="2">I. Kinerja dibawah ekspektasi dan potensial rendah</option>
								<option value="12" data-pub="2">II. Kinerja sesuai ekspektasi dan potensial rendah</option>
								<option value="21" data-pub="2">III. Kinerja dibawah ekspektasi dan potensial menengah</option>
								<option value="13" data-pub="2">IV. Kinerja diatas ekspektasi dan potensial rendah</option>
								<option value="22" data-pub="2">V. Kinerja sesuai ekspektasi dan potensial menengah</option>
								<option value="31" data-pub="2">VI. Kinerja dibawah ekspektasi dan potensial tinggi</option>
								<option value="23" data-pub="2">VII. Kinerja diatas ekspektasi dan potensial menengah</option>
								<option value="32" data-pub="2">VIII. Kinerja sesuai ekspektasi dan potensial tinggi</option>
								<option value="33" data-pub="2">IX. Kinerja diatas ekspektasi dan potensial tinggi</option>

								<option value="11" data-pub="3">I. Kinerja dibawah ekspektasi dan JPM rendah</option>
								<option value="12" data-pub="3">II. Kinerja sesuai ekspektasi dan JPM rendah</option>
								<option value="21" data-pub="3">III. Kinerja dibawah ekspektasi dan JPM menengah</option>
								<option value="13" data-pub="3">IV. Kinerja diatas ekspektasi dan JPM rendah</option>
								<option value="22" data-pub="3">V. Kinerja sesuai ekspektasi dan JPM menengah</option>
								<option value="31" data-pub="3">VI. Kinerja dibawah ekspektasi dan JPM tinggi</option>
								<option value="23" data-pub="3">VII. Kinerja diatas ekspektasi dan JPM menengah</option>
								<option value="32" data-pub="3">VIII. Kinerja sesuai ekspektasi dan JPM tinggi</option>
								<option value="33" data-pub="3">IX. Kinerja diatas ekspektasi dan JPM tinggi</option>
							<?
							}
							elseif($tempGrafikId == 2)
							{
							?>

								<option value="11" data-pub="1">I. Tingkatkan Kompetensi</option>
								<option value="12" data-pub="1">II. Tingkatkan Peran Saat Ini</option>
								<option value="21" data-pub="1">III. Tingkatkan Peran Saat Ini</option>
								<option value="13" data-pub="1">IV. Tingkatkan Peran Saat Ini</option>
								<option value="22" data-pub="1">V. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="31" data-pub="1">VI. Pertimbangkan (mutasi)</option>
								<option value="23" data-pub="1">VII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="32" data-pub="1">VIII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="33" data-pub="1">IX. Siap Untuk Peran Di Masa Depan</option>

								<option value="11" <? if($tempKuadranId == '11') echo 'selected';?>  data-pub="2">I. Kinerja dibawah ekspektasi dan potensial rendah</option>
								<option value="12" <? if($tempKuadranId == '12') echo 'selected';?>  data-pub="2">II. Kinerja sesuai ekspektasi dan potensial rendah</option>
								<option value="21" <? if($tempKuadranId == '21') echo 'selected';?>  data-pub="2">III. Kinerja dibawah ekspektasi dan potensial menengah</option>
								<option value="13" <? if($tempKuadranId == '13') echo 'selected';?>  data-pub="2">IV. Kinerja diatas ekspektasi dan potensial rendah</option>
								<option value="22" <? if($tempKuadranId == '22') echo 'selected';?> data-pub="2">V. Kinerja sesuai ekspektasi dan potensial menengah</option>
								<option value="31" <? if($tempKuadranId == '31') echo 'selected';?> data-pub="2">VI. Kinerja dibawah ekspektasi dan potensial tinggi</option>
								<option value="23" <? if($tempKuadranId == '23') echo 'selected';?>  data-pub="2">VII. Kinerja diatas ekspektasi dan potensial menengah</option>
								<option value="32" <? if($tempKuadranId == '32') echo 'selected';?>  data-pub="2">VIII. Kinerja sesuai ekspektasi dan potensial tinggi</option>
								<option value="33" <? if($tempKuadranId == '33') echo 'selected';?> data-pub="2">IX. Kinerja diatas ekspektasi dan potensial tinggi</option>

								<option value="11" data-pub="3">I. Kinerja dibawah ekspektasi dan JPM rendah</option>
								<option value="12" data-pub="3">II. Kinerja sesuai ekspektasi dan JPM rendah</option>
								<option value="21" data-pub="3">III. Kinerja dibawah ekspektasi dan JPM menengah</option>
								<option value="13" data-pub="3">IV. Kinerja diatas ekspektasi dan JPM rendah</option>
								<option value="22" data-pub="3">V. Kinerja sesuai ekspektasi dan JPM menengah</option>
								<option value="31" data-pub="3">VI. Kinerja dibawah ekspektasi dan JPM tinggi</option>
								<option value="23" data-pub="3">VII. Kinerja diatas ekspektasi dan JPM menengah</option>
								<option value="32" data-pub="3">VIII. Kinerja sesuai ekspektasi dan JPM tinggi</option>
								<option value="33" data-pub="3">IX. Kinerja diatas ekspektasi dan JPM tinggi</option>

							<?
							}
							elseif($tempGrafikId == 3)
						    {
							?>
								<option selected="selected" value="">Semua</option>   
								<option value="11" data-pub="1">I. Tingkatkan Kompetensi</option>
								<option value="12" data-pub="1">II. Tingkatkan Peran Saat Ini</option>
								<option value="21" data-pub="1">III. Tingkatkan Peran Saat Ini</option>
								<option value="13" data-pub="1">IV. Tingkatkan Peran Saat Ini</option>
								<option value="22" data-pub="1">V. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="31" data-pub="1">VI. Pertimbangkan (mutasi)</option>
								<option value="23" data-pub="1">VII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="32" data-pub="1">VIII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="33" data-pub="1">IX. Siap Untuk Peran Di Masa Depan</option>

								<option value="11" data-pub="2">I. Kinerja dibawah ekspektasi dan potensial rendah</option>
								<option value="12" data-pub="2">II. Kinerja sesuai ekspektasi dan potensial rendah</option>
								<option value="21" data-pub="2">III. Kinerja dibawah ekspektasi dan potensial menengah</option>
								<option value="13" data-pub="2">IV. Kinerja diatas ekspektasi dan potensial rendah</option>
								<option value="22" data-pub="2">V. Kinerja sesuai ekspektasi dan potensial menengah</option>
								<option value="31" data-pub="2">VI. Kinerja dibawah ekspektasi dan potensial tinggi</option>
								<option value="23" data-pub="2">VII. Kinerja diatas ekspektasi dan potensial menengah</option>
								<option value="32" data-pub="2">VIII. Kinerja sesuai ekspektasi dan potensial tinggi</option>
								<option value="33" data-pub="2">IX. Kinerja diatas ekspektasi dan potensial tinggi</option>

								<option value="11" <? if($tempKuadranId == '11') echo 'selected';?> data-pub="3">I. Kinerja dibawah ekspektasi dan JPM rendah</option>
								<option value="12" <? if($tempKuadranId == '12') echo 'selected';?> data-pub="3">II. Kinerja sesuai ekspektasi dan JPM rendah</option>
								<option value="21" <? if($tempKuadranId == '21') echo 'selected';?> data-pub="3">III. Kinerja dibawah ekspektasi dan JPM menengah</option>
								<option value="13" <? if($tempKuadranId == '13') echo 'selected';?> data-pub="3">IV. Kinerja diatas ekspektasi dan JPM rendah</option>
								<option value="22" <? if($tempKuadranId == '22') echo 'selected';?> data-pub="3">V. Kinerja sesuai ekspektasi dan JPM menengah</option>
								<option value="31" <? if($tempKuadranId == '31') echo 'selected';?> data-pub="3">VI. Kinerja dibawah ekspektasi dan JPM tinggi</option>
								<option value="23" <? if($tempKuadranId == '23') echo 'selected';?> data-pub="3">VII. Kinerja diatas ekspektasi dan JPM menengah</option>
								<option value="32" <? if($tempKuadranId == '32') echo 'selected';?> data-pub="3">VIII. Kinerja sesuai ekspektasi dan JPM tinggi</option>
								<option value="33" <? if($tempKuadranId == '33') echo 'selected';?> data-pub="3">IX. Kinerja diatas ekspektasi dan JPM tinggi</option>
							<?
							}
							else
							{
							?>
								<option selected="selected" value="">Semua</option>   
								<option value="11" data-pub="1">I. Tingkatkan Kompetensi</option>
								<option value="12" data-pub="1">II. Tingkatkan Peran Saat Ini</option>
								<option value="21" data-pub="1">III. Tingkatkan Peran Saat Ini</option>
								<option value="13" data-pub="1">IV. Tingkatkan Peran Saat Ini</option>
								<option value="22" data-pub="1">V. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="31" data-pub="1">VI. Pertimbangkan (mutasi)</option>
								<option value="23" data-pub="1">VII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="32" data-pub="1">VIII. Siap Untuk Peran Masa Depan Dengan Pengembangan</option>
								<option value="33" data-pub="1">IX. Siap Untuk Peran Di Masa Depan</option>

								<option value="11" data-pub="2">I. Kinerja dibawah ekspektasi dan potensial rendah</option>
								<option value="12" data-pub="2">II. Kinerja sesuai ekspektasi dan potensial rendah</option>
								<option value="21" data-pub="2">III. Kinerja dibawah ekspektasi dan potensial menengah</option>
								<option value="13" data-pub="2">IV. Kinerja diatas ekspektasi dan potensial rendah</option>
								<option value="22" data-pub="2">V. Kinerja sesuai ekspektasi dan potensial menengah</option>
								<option value="31" data-pub="2">VI. Kinerja dibawah ekspektasi dan potensial tinggi</option>
								<option value="23" data-pub="2">VII. Kinerja diatas ekspektasi dan potensial menengah</option>
								<option value="32" data-pub="2">VIII. Kinerja sesuai ekspektasi dan potensial tinggi</option>
								<option value="33" data-pub="2">IX. Kinerja diatas ekspektasi dan potensial tinggi</option>

								<option value="11" data-pub="3">I. Kinerja dibawah ekspektasi dan JPM rendah</option>
								<option value="12" data-pub="3">II. Kinerja sesuai ekspektasi dan JPM rendah</option>
								<option value="21" data-pub="3">III. Kinerja dibawah ekspektasi dan JPM menengah</option>
								<option value="13" data-pub="3">IV. Kinerja diatas ekspektasi dan JPM rendah</option>
								<option value="22" data-pub="3">V. Kinerja sesuai ekspektasi dan JPM menengah</option>
								<option value="31" data-pub="3">VI. Kinerja dibawah ekspektasi dan JPM tinggi</option>
								<option value="23" data-pub="3">VII. Kinerja diatas ekspektasi dan JPM menengah</option>
								<option value="32" data-pub="3">VIII. Kinerja sesuai ekspektasi dan JPM tinggi</option>
								<option value="33" data-pub="3">IX. Kinerja diatas ekspektasi dan JPM tinggi</option>
							<?
							}
							?>


						</select>
					</td>
		        </tr>  
				<tr>
					<td colspan="3">
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input id="reqFormulaFaktorId" name="reqFormulaFaktorId" value="<?=$tempFormulaFaktorId?>" type="hidden" />
						<input type="submit" name="" value="Simpan" />
					</td>
				</tr> 
			</table>       
        </form>
    <script>

        $(function () {
        	$('input[name="reqAssesment"]').hide();
        	$("#reqGrafikId").hide();
        	$("#reqKuadranId").hide();
	        $('input[name="checkbox"]').on('click', function () {
	        	if ($(this).prop('checked')) {
	        		$('input[name="reqAssesment"]').fadeIn();
	        	} else {
	        		$('input[name="reqAssesment"]').hide();
	        	}
	        });
	        $('input[name="checkbox1"]').on('click', function () {
	        	if ($(this).prop('checked')) {
	        		$('#reqGrafikId').fadeIn();
	        	} else {
	        		$('#reqGrafikId').hide();
	        	}
	        });
	        $('input[name="checkbox2"]').on('click', function () {
	        	if ($(this).prop('checked')) {
	        		$('#reqKuadranId').fadeIn();
	        	} else {
	        		$('#reqKuadranId').hide();
	        	}
	        });
	        $('.checkbox').each(function(e){
	        	let checkval = $(this).val();
	        	// console.log(checkval);
	        	if(checkval){
	        		$(this).attr("checked", "checked");
	        		$('input[name="reqAssesment"]').show();
	        	}
	        });
	        $('.checkbox1').each(function(e){
	        	let checkval = $(this).val();
	        	// console.log(checkval);
	        	if(checkval){
	        		$(this).attr("checked", "checked");
	        		$('#reqGrafikId').show();
	        	}
	        });
	        $('.checkbox2').each(function(e){
	        	let checkval = $(this).val();
	        	// console.log(checkval);
	        	if(checkval){
	        		$(this).attr("checked", "checked");
	        		$('#reqKuadranId').show();
	        	}
	        });

	        let selector = '<?=$tempGrafikId?>';

	        if (selector)
	        {
	        	$("#reqKuadranId > option").hide();
	        	if(selector == "")
	        	{
	        		$("#reqKuadranId > option").show();
	        	}
	        	$("#reqKuadranId > option").filter(function(){return $(this).data('pub') == selector}).show();
	        }
        	// console.log(selector);
        	
	    });

        $('#reqGrafikId').on('change', function(e) {
        	let selector = $(this).val();
        	// console.log(selector);
        	$("#reqKuadranId > option").hide();
        	if(selector == "")
        	{
        		$("#reqKuadranId > option").show();
        	}
        	$("#reqKuadranId > option").filter(function(){return $(this).data('pub') == selector}).show();
        	
        });
    </script>
    </div>
</div>
</body>
</html>
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
	// echo $set->query;exit;
	$tempFormula= $set->getField('FORMULA');
	$tempTahun= $set->getField('TAHUN');
	$tempKeterangan= $set->getField('KETERANGAN');
	$tempTipeFormula= $set->getField('TIPE_FORMULA');

	$setcheck->selectByParams(array("A.FORMULA_ID"=> $reqId),-1,-1,'');
	$setcheck->firstRow();
	// echo $setcheck->query;exit;
	$tempFormulaNama= $setcheck->getField('FORMULA');
	$reqGrafikId= $setcheck->getField('ID_GRAFIK');
	$reqKuadranId= $setcheck->getField('ID_KUADRAN');
	// echo $reqKuadranId;exit;
	$reqAssesment= $setcheck->getField('ASSESMENT');
	$tempKuadran= $setcheck->getField('KUADRAN_NAMA');
	$reqFormulaFaktorId= $setcheck->getField('FORMULA_FAKTOR_ID');
	
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
					<td style="width: 20%">
						<input type="checkbox" name="checkbox" class="checkbox"  id="checkbox" value="<?=$reqAssesment?>" /> Assesment
					</td>
					<td style="width: 25px">:</td>
					<td>
						<input type="text" name="reqAssesment" id="reqAssesment" class="easyui-validatebox" style="width:100px" value="<?=$reqAssesment?>" />
					</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="checkbox1" class="checkbox1" id="checkbox1" value="<?=$reqGrafikId?>" /> Grafik Talent pool</td>
					<td>:</td>        
					<td>
						<select name ="reqGrafikId" id="reqGrafikId" >
							<option value="">Semua</option>
							<option value="1" <? if($reqGrafikId == '1') echo 'selected';?> >Grafik Nine Box Potensi Kompetensi</option>
							<option value="2" <? if($reqGrafikId == '2') echo 'selected';?>>Grafik Nine Box Kompetensi Kinerja</option>
							<option value="3" <? if($reqGrafikId == '3') echo 'selected';?>>Grafik Nine Box JPM Kinerja</option>
						</select>
					</td>
		        </tr>
		        <tr> 
					<td><input type="checkbox" name="checkbox2" class="checkbox2" id="checkbox2" value="<?=$reqKuadranId?>" /> Kuadran</td>
					<td>:</td>
					<td>
						<div id="reqKuadranInfoId">
							<input id="reqKuadranJsonId" class="easyui-combotree" data-options="
		                    onLoadSuccess: function (row, data) {
		                        $('#reqKuadranJsonId').combotree('tree').tree('collapseAll');
		                    },
		                    onClick: function(node){
		                        clickNode($('#reqKuadranJsonId'), node.id);
		                    },
		                    onCheck: function(node, checked){
		                        clickNode($('#reqKuadranJsonId'), node.id);
		                    },checkbox:true,cascadeCheck:true" multiple style="width:350px;" />
		                    <input type="hidden" name="reqKuadranId" id="reqKuadranId" value="<?=$reqKuadranId?>" />
		                </div>
					</td>
		        </tr>  
				<tr>
					<td colspan="3">
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input id="reqFormulaFaktorId" name="reqFormulaFaktorId" value="<?=$reqFormulaFaktorId?>" type="hidden" />
						<input type="submit" name="" value="Simpan" />
					</td>
				</tr> 
			</table>       
        </form>
    <script>

        $(function () {
        	reqAssesment= $("#reqAssesment").val();
        	if(reqAssesment == "")
        	{
        		$("#checkbox").attr('checked', '');
        		$("#checkbox").prop('checked', false);
        		$('input[name="reqAssesment"]').hide();
        	}

        	reqGrafikId= $("#reqGrafikId").val();
        	if(reqGrafikId == "")
        	{
        		$("#checkbox1").attr('checked', '');
        		$("#checkbox1").prop('checked', false);
        		$("#reqGrafikId").hide();
        	}

        	reqKuadranId= $("#reqKuadranId").val();
        	if(reqKuadranId == "")
        	{
        		$("#checkbox2").attr('checked', '');
        		$("#checkbox2").prop('checked', false);
        		$("#reqKuadranInfoId").hide();
        	}

	        $('input[name="checkbox"]').on('click', function () {
	        	if ($(this).prop('checked')) {
	        		$('input[name="reqAssesment"]').fadeIn();
	        	} else {
	        		$("#reqAssesment").val("");
	        		$('input[name="reqAssesment"]').hide();
	        	}
	        });

	        $('input[name="checkbox1"]').on('click', function () {
	        	if ($(this).prop('checked')) {
	        		$('#reqGrafikId').fadeIn();
	        	} else {
	        		$("#reqGrafikId").val("");
	        		$('#reqGrafikId').hide();
	        	}
	        });

	        $('input[name="checkbox2"]').on('click', function () {
	        	if ($(this).prop('checked')) {
	        		$('#reqKuadranInfoId').fadeIn();
	        	} else {
	        		$("#reqKuadranId").val("");
	        		$('#reqKuadranInfoId').hide();
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

	        setKuadranOption("");
			$('select[id^="reqGrafikId"]').change(function() {
				$("#reqKuadranId").val("");
				var id= $(this).attr('id');
				id= id.replace("reqGrafikId", "");
				//console.log('--'+id);
				setKuadranOption("1");
			});
        	
	    });

        // $('#reqKuadranJsonId').combotree('setValue', "<?=$reqKuadranId?>");
        // $('#reqKuadranJsonId').combotree('setValues', [12,13,22]);

	    function setKuadranOption(info)
		{
			var reqGrafikId= "";
			reqGrafikId= $("#reqGrafikId").val();

			if(info == "")
			{
				var array = "<?=$reqKuadranId?>".split(',');
				$('#reqKuadranJsonId').combotree('setValues', array);
				// console.log(array);
			}
			else
			{
				$('#reqKuadranJsonId').combotree('setValue', "");
			}

			if(reqGrafikId == ""){}
			else
			{
				var url = "../json-suksesi/faktor_lain_add_list.php?reqGrafikId="+reqGrafikId;
				// console.log(url);
				$('#reqKuadranJsonId').combotree('reload', url);
			}
		}
			
		function clickNode(cc, id)
		{
			var opts= cc.combotree('options');
			var values= cc.combotree('getValues');
			$("#reqKuadranId").val(values);
		}
    </script>
    </div>
</div>
</body>
</html>
<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/AtributPenggalian.php");
include_once("../WEB/classes/base/FormulaUnsur.php");

include_once("../WEB/classes/base/FormulaSuksesi.php");
include_once("../WEB/classes/base/FormulaEselon.php");
include_once("../WEB/classes/base/Penggalian.php");
include_once("../WEB/classes/base/LevelAtribut.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
$reqAspekId= httpFilterGet("reqAspekId");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data data terlebih dahulu.');";	
	echo "window.parent.location.href = 'formula_assesment_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

$statement= " AND A.FORMULA_ID = ".$reqId;
$set= new FormulaSuksesi();
$set->selectByParams(array("FORMULA_ID"=> $reqId),-1,-1,'');
$set->firstRow();
$tempFormula= $set->getField('FORMULA');
$tempFormulaTahun= $set->getField('TAHUN');
$tempFormulaKeterangan= $set->getField('KETERANGAN');
unset($set);

$statement= " AND B.FORMULA_ESELON_ID = '".$reqRowId."'";
$set= new FormulaEselon();
$set->selectByParamsMonitoring(array(), -1,-1, $reqId, $statement);
// echo $set->query;exit();
$set->firstRow();
$tempFormulaEselonId= $set->getField("FORMULA_ESELON_ID");
$tempFormulaEselonEselonId= $set->getField("ESELON_ID");
$tempFormulaEselonNamaEselon= $set->getField("NAMA_ESELON");
$tempFormulaEselonProsenPotensi= $set->getField("PROSEN_POTENSI");
$tempFormulaEselonProsenKompetensi= $set->getField("PROSEN_KOMPETENSI");
$tempFormulaEselonPermenId= $set->getField("FORM_PERMEN_ID");

if($reqAspekId == "")
$reqAspekId= 1;

// $statement= " AND A.ASPEK_ID = ".$reqAspekId;
$statement= "";

//$statement= "";

// kondisi aktif permen
if($tempFormulaEselonPermenId == "")
{
	$statement.= " AND EXISTS (SELECT 1 FROM (SELECT PERMEN_ID AKTIF_PERMENT FROM permen WHERE STATUS = '1') X WHERE AKTIF_PERMENT = A.PERMEN_ID)";
}
else
{
	$statement.= " AND A.PERMEN_ID = ".$tempFormulaEselonPermenId;
}

$index_loop= 0;
$arrAtributParent="";
$set= new FormulaUnsur();
$set->selectByParamsSuksesiFormulaAtribut(array(), -1,-1, $reqRowId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrAtributParent[$index_loop]["UNSUR_ID"]= $set->getField("UNSUR_ID");
	$arrAtributParent[$index_loop]["UNSUR_ID_PARENT"]= $set->getField("UNSUR_ID_PARENT");
	$arrAtributParent[$index_loop]["NAMA"]= $set->getField("NAMA");
	$arrAtributParent[$index_loop]["FORMULA_UNSUR_ID"]= $set->getField("FORMULA_UNSUR_ID");
	$arrAtributParent[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	
	$arrAtributParent[$index_loop]["UNSUR_BOBOT"]= $set->getField("UNSUR_BOBOT");
	$arrAtributParent[$index_loop]["FORMULA_UNSUR_BOBOT_ID"]= $set->getField("FORMULA_UNSUR_BOBOT_ID");
	
	// $tempValuePenggalianId= "";
	// $statement= " AND A.FORMULA_UNSUR_ID = ".$set->getField("FORMULA_UNSUR_ID");
	// $set_atribut_pengalian= new AtributPenggalian();
	// $set_atribut_pengalian->selectByParams(array(), -1,-1, $statement);
	// while($set_atribut_pengalian->nextRow())
	// {
	// 	if($tempValuePenggalianId == "")
	// 		$tempValuePenggalianId= $set_atribut_pengalian->getField("PENGGALIAN_ID");
	// 	else
	// 		$tempValuePenggalianId.= ",".$set_atribut_pengalian->getField("PENGGALIAN_ID");
	// }
	// unset($set_atribut_pengalian);
	// $arrAtributParent[$index_loop]["ATRIBUT_PENGGALIAN_ID"]= $tempValuePenggalianId;
	
	$index_loop++;
}
$jumlah_atribut_parent= $index_loop;
//print_r($arrAtributParent);exit;

$statement= " AND A.TAHUN = '".$tempFormulaTahun."'";
// $statement= " 1=2";
$index_loop= 0;
$arrPenggalian="";
$set= new Penggalian();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrPenggalian[$index_loop]["PENGGALIAN_ID"]= $set->getField("PENGGALIAN_ID");
	$arrPenggalian[$index_loop]["KODE"]= $set->getField("KODE");
	$index_loop++;
}
$jumlah_penggalian= $index_loop;
//print_r($arrPenggalian);exit;

$arrReqFilter= array("reqLevelId");
$arrReqJson= array("level_atribut_id_combo_json");
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
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>

<script type="text/javascript">	
function setLoad()
{
	var reqAspekId= setInfoPerubahan= tempAspekId= "";
	// setInfoPerubahan= $("#setInfoPerubahan").val();
	reqAspekId= 1;
	
	// tempAspekId= "1";
	// if(reqAspekId == "1")
	// tempAspekId= "2";
	
	// //alert(reqAspekId);return false;
	// if(setInfoPerubahan == "1")
	// {
	// 	$.messager.alert('Info', "Klik simpan terlebih dahulu, karena ada perubahan data", 'info');
	// 	$("#reqAspekId option:selected").attr("selected",false);
	// 	//$("#reqAspekId option[value="+tempAspekId+"]").prop("selected",'selected');
	// 	//$("#reqAspekId option[value=2]").attr("selected",'selected');
	// 	$("#reqAspekId option[value="+tempAspekId+"]").prop("selected",true);
	// }
	// else
	document.location.href= 'formula_suksesi_add_suksesi.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqAspekId='+reqAspekId;
}

// function setGeneralFilter(id)
// {
// 	<?
// 	for($indexReqFilter=0; $indexReqFilter < count($arrReqFilter); $indexReqFilter++)
// 	{
// 	?>
// 		$("#<?=$arrReqFilter[$indexReqFilter]?>"+id+" :selected").remove(); 
// 		$("#<?=$arrReqFilter[$indexReqFilter]?>"+id+" option").remove();
		
// 		var reqAtributId= reqLevelValId= reqLevelId= reqFormulaUnsurId= reqAtributParentLevelId= "";
// 		reqAtributId= $("#reqAtributId"+id).val();
// 		reqLevelId= $("#reqLevelId"+id).val();
// 		reqLevelValId= $("#reqLevelValId"+id).val();
		
// 		reqFormulaUnsurId= $("#reqFormulaUnsurId"+id).val();
// 		reqAtributParentLevelId= $("#reqAtributParentLevelId"+id).val();
		
// 		if(reqFormulaUnsurId == ""){}
// 		else
// 		setChecked(id, 1);
		
// 		var s_url= "../json-suksesi/<?=$arrReqJson[$indexReqFilter]?>.php?reqAtributId="+reqAtributId+"&reqFormulaUnsurId="+reqFormulaUnsurId+"&reqAtributParentLevelId="+reqAtributParentLevelId;
// 		var request = $.get(s_url);
// 		request.done(function(dataJson)
// 		{
// 			var data= JSON.parse(dataJson);
// 			if(data.arrID == null)
// 			{
// 				$("#reqCheckBoxAtributId"+id+", #reqLevelId"+id).hide();
// 			}
// 			else
// 			{
// 				for(i=0;i<data.arrID.length; i++)
// 				{
// 					var selected= "";
// 					valId= data.arrID[i]; valNama= data.arrNama[i];
// 					valArrFormulaAtributId= data.arrFormulaAtributId[i]; 
// 					valArrAtributParentLevelId= data.arrAtributParentLevelId[i]; 
					
// 					if(valArrFormulaAtributId == "")
// 					{
// 						$("#reqLevelValId"+id).val(data.arrID[0]);
// 					}
// 					else
// 					{
// 						if(valArrAtributParentLevelId == valId)
// 						{
// 							$("#reqLevelValId"+id).val(valId);
// 							selected= "selected";
// 						}
// 					}
					
// 					$("<option value='" + valId + "' "+selected+" >" + valNama + "</option>").appendTo("#<?=$arrReqFilter[$indexReqFilter]?>"+id);
// 				}
// 			}
// 		});
// 	<?
// 	}
// 	?>
// }

$(function(){
	$('#reqAspekId').bind('change', function(ev) {
		setLoad();
	});	
	
	$.extend($.fn.validatebox.defaults.rules, {  
		nilaiKurangSamaDengan: {  
			//alert('asdsad');
			validator: function(value, param){  
				return parseInt(value) <= parseInt(param[0]);
			},
			message: 'Nilai harus <= {0}.'
		}  
	});
	
	$('#ff').form({
		url:'../json-suksesi/formula_suksesi_add_suksesi.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			
			$("#setInfoPerubahan").val("");
			//parent.setShowHideMenu(3);
			setLoad();
		}
	});


	<?
    for($checkbox_index=0;$checkbox_index < $jumlah_atribut_parent;$checkbox_index++)
    {

    	$index_loop= $checkbox_index;
		$tempCheck= $arrAtributParent[$index_loop]["FORMULA_UNSUR_ID"];
		$tempCheckParent= $arrAtributParent[$index_loop]["FORMULA_UNSUR_ID_PARENT"];
		$tempCheckId= $arrAtributParent[$index_loop]["FORMULA_UNSUR_BOBOT_ID"];
		if ($tempCheck == "" && $tempCheckParent == 0)
		{
			continue;
		}
    ?>
 
    	checked = <?=$tempCheck?>;


    	checkedid = <?=$tempCheckId?>;
    	// console.log(checked);
    	$('input[id^="reqCheckBoxAtributId"]').each(function(){
    		var id= $(this).attr('id');
    		id= id.replace("reqCheckBoxAtributId", "")

    		if(checked) 
    		{
    			setChecked(checked, 1);
    		}
    		else
    		{
    			setChecked(checked, "");
    		}
    	});
    	
    	// // if (tempFormulaAtributBobotId)
    	// // {
     //    }

    <?
	}
		
    ?>
	
	
	
	<?
	if($reqAspekId == 2)
	{
	?>
	$('input[id^="reqNilaiStandar"]').keyup(function() {
		var id= $(this).attr('id');
		var valData= $(this).val();
		id= id.replace("reqNilaiStandar", "");
		$("#reqAtributNilaiStandar"+id).val(valData);
	});
	<?
	}
	?>
	
	$('select[id^="reqLevelId"]').change(function() {
		var id= $(this).attr('id');
		var valData= $(this).val();
		id= id.replace("reqLevelId", "");
		//alert(id);
		$("#reqLevelValId"+id).val(valData);
	});
	
	//reqLevelValId= $("#reqLevelValId"+id).val();
				   
	$('input[id^="reqCheckBoxAtributId"]').change(function() {
		var id= $(this).attr('id');
		id= id.replace("reqCheckBoxAtributId", "");
		$("#setInfoPerubahan").val("1");

		
		// alert('xxx'); return false;

		if($(this).prop('checked')) 
		{
			setChecked(id, 1);
		}
		else
		{
			//$("#setInfoPerubahan").val("");
			setChecked(id, "");
		}
		//alert(id);
	});
		
});


function setChecked(id, val)
{
	// console.log(id+'----'+val);
	if(val == 1)
	{
		$("#reqCheckBoxAtributId"+id).prop('checked', true);
		$("#reqStatusAtributId"+id).val(val);
		$("#reqBobotStatusAtributId"+id).val(val);
		
		$("#reqNilaiStandar"+id).validatebox({required: true});
	}
	else
	{
		$("#reqCheckBoxAtributId"+id).prop('checked', false);
		$("#reqStatusAtributId"+id).val("");
		$("#reqBobotStatusAtributId"+id).val("");
		$("#reqLevelValId"+id).val("");
		$("#reqNilaiStandar"+id).validatebox({required: false});
		$("#reqNilaiStandar"+id).removeClass('validatebox-invalid');
				
		var reqFormulaUnsurId= reqAtributId= reqAtributPenggalianId= "";
		reqFormulaUnsurId= $("#reqFormulaUnsurId"+id).val();
		reqAtributId= $("#reqAtributId"+id).val();
		reqAtributPenggalianId= $("#reqAtributPenggalianId"+id).val();
		<?
		//if($reqAspekId == 1){}
		//else
		//{
		?>
		//alert(reqFormulaUnsurId);return false;
		if(reqFormulaUnsurId == ""){}
		else
		{
		
				$.messager.confirm('Confirm','Apakah anda yakin ingin menghapus data terpilih ?',function(r){
					if (r){
						var reqAspekId= "";
						reqAspekId= $("#reqAspekId").val();
						$.getJSON('../json-suksesi/delete.php?reqMode=formula_atribut&id='+reqFormulaUnsurId+"&reqAspekId="+reqAspekId, function (data) 
						{
							$("#reqNilaiStandar"+id+", #reqAtributPenggalianId"+id).val("");
							$('[id^="reqInfoSimpan'+id+'-'+reqAtributId+'-"]').show();
							$('[id^="reqInfoHapus'+id+'-'+reqAtributId+'-"]').hide();
							//one catatan
						});
					}
					else
					{
						//$("#setInfoPerubahan").val("");
						$("#reqCheckBoxAtributId"+id).prop('checked', true);
					}
				});
			//}
		}
		<?
		//}
		?>
	}
}

function pencarian(term, _id, cellNr){
	var suche = term.value.toLowerCase();
	var table = document.getElementById(_id);
	var ele0;	var ele1;
	for (var r = 1; r < table.rows.length; r++){
		//ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^>]+>/g,"");
		ele0 = table.rows[r].cells[0].innerHTML.replace(/<[^>]+>/g,"");
		//ele2 = table.rows[r].cells[2].innerHTML.replace(/<[^>]+>/g,"");
		//if (ele0.toLowerCase().indexOf(suche)>=0 || ele2.toLowerCase().indexOf(suche)>=0 )
		if (ele0.toLowerCase().indexOf(suche)>=0 )
			table.rows[r].style.display = '';
		else table.rows[r].style.display = 'none';
	}
}

function getPenggalianId(indexId, reqAtributId)
{
	$("#setInfoPerubahan").val("1");
	var separator= reqAtributPenggalianId= "";
	$("#reqAtributPenggalianId"+indexId).val("");
	
	$('input[id^="reqPenggalianId'+indexId+'-'+reqAtributId+'-"]').each(function(){
		var id= $(this).attr('id');
		var value= $(this).val();
		if(value == ""){}
		else
		{
			separator= "";
			if(reqAtributPenggalianId == ""){}
			else
			separator= ",";
			
			reqAtributPenggalianId= reqAtributPenggalianId+separator+value;
		}
   });
   
   $("#reqAtributPenggalianId"+indexId).val(reqAtributPenggalianId);
}

function pilihatribut(indexId, reqAtributId, reqPenggalianId, reqmode)
{
	//alert(indexId+", "+reqAtributId+", "+reqPenggalianId);return false;
	if(reqmode == 'hapus')
	{
		$("#reqPenggalianId"+indexId+"-"+reqAtributId+"-"+reqPenggalianId).val("");
		$("#reqInfoSimpan"+indexId+"-"+reqAtributId+"-"+reqPenggalianId).show();
		$("#reqInfoHapus"+indexId+"-"+reqAtributId+"-"+reqPenggalianId).hide();
	}
	else
	{
		$("#reqPenggalianId"+indexId+"-"+reqAtributId+"-"+reqPenggalianId).val(reqPenggalianId);
		$("#reqInfoSimpan"+indexId+"-"+reqAtributId+"-"+reqPenggalianId).hide();
		$("#reqInfoHapus"+indexId+"-"+reqAtributId+"-"+reqPenggalianId).show();
	}
	
	getPenggalianId(indexId, reqAtributId);
}
</script>

<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<div id="content" style="height:auto; width:100%">
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td colspan="3">
            <div id="header-tna-detil">Suksesi <!-- <span><?=$tempFormulaEselonNamaEselon?></span> --></div>
            </td>			
        </tr>
        <tr>           
            <td style="width:150px">Tahun</td><td style="width:5px">:</td>
            <td><?=$tempFormulaTahun?></td>
        </tr>
        <tr>
            <td>Formula</td><td>:</td>
            <td><?=$tempFormula?></td>	
        </tr>
        <tr>
            <td>Keterangan</td><td>:</td>
            <td><?=$tempFormulaKeterangan?></td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
            <td>
            	<input type="hidden" id="setInfoPerubahan" />
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
                <input type="hidden" name="reqMode" value="insert">
                <input type="hidden" name="reqTahun" value="<?=$tempFormulaTahun?>">
                <input type="hidden" name="reqFormulaEselonPermenId" value="<?=$tempFormulaEselonPermenId?>">
                <input type="submit" name="" value="Simpan" />
            </td>
        </tr>
        <?
		}
		?>
    </table>
    
    <table class="gradient-style" style="width:99%; margin-left:2px">
    <tr>
    	<td style="width:100px">Pencarian</td><td style="width:10px">:</td>
        <td style="width:300px"><input name="filter" onkeyup="pencarian(this, 'tableKandidat', 0)" type="text" style="width:300px"></td>
       <!--  <td style="width:100px">Aspek</td><td style="width:10px">:</td>
        <td>
        <select id="reqAspekId" name="reqAspekId">
            <option value="1" <? if($reqAspekId == "1") echo "selected"?>>Potensi</option>
            <option value="2" <? if($reqAspekId == "2") echo "selected"?>>Kompetensi</option>
        </select>
        </td> -->
    </tr>
    </table>
    
    <table class="gradient-style" id="tableKandidat" style="width:99%; margin-left:2px">
    <thead>
  
    <tr>
    	<th style="width:30%">Suksesi</th>
        <th style="width:70%">Bobot</th>
        <th><th>
       <!--  <th style="width:7%">Bobot</th>
        <th style="width:7%">Skor</th> -->
    </tr>
    </thead>
    <tbody>
    <?
    for($checkbox_index=0;$checkbox_index < $jumlah_atribut_parent;$checkbox_index++)
    {
		$index_loop= $checkbox_index;
    	$tempAtributParentAtributId= $arrAtributParent[$index_loop]["UNSUR_ID"];
		$tempAtributParentAtributIdParent= $arrAtributParent[$index_loop]["UNSUR_ID_PARENT"];
		$tempAtributParentAspekId= $arrAtributParent[$index_loop]["ASPEK_ID"];
		$tempAtributParentAspekNama= $arrAtributParent[$index_loop]["ASPEK_NAMA"];
		$tempAtributParentNama= $arrAtributParent[$index_loop]["NAMA"];
		$tempAtributParentFormulaAtributId= $arrAtributParent[$index_loop]["FORMULA_UNSUR_ID"];
		$tempAtributParentLevelId= $arrAtributParent[$index_loop]["LEVEL_ID"];
		$tempAtributParentNilaiStandar= $arrAtributParent[$index_loop]["NILAI_STANDAR"];
		$tempAtributParentAtributPenggalianId= $arrAtributParent[$index_loop]["ATRIBUT_PENGGALIAN_ID"];
		
		$tempAtributNilaiStandar= $arrAtributParent[$index_loop]["UNSUR_BOBOT"];
		$tempAtributBobot= $arrAtributParent[$index_loop]["UNSUR_BOBOT"];
		$tempAtributSkor= $arrAtributParent[$index_loop]["ATRIBUT_SKOR"];
		$tempFormulaAtributBobotId= $arrAtributParent[$index_loop]["FORMULA_UNSUR_BOBOT_ID"];
		
		if($tempAtributParentAtributIdParent == "0")
		{
    ?>
        <tr>
        	<?
			if($reqAspekId == "1")
        	{
            ?>
            <td style="background-color:#D5FFAE" scope="col">
			<?=$tempAtributParentNama?>
            <input type="hidden" id="reqFormulaUnsurBobotId<?=$checkbox_index?>" name="reqFormulaUnsurBobotId[]" value="<?=$tempFormulaAtributBobotId?>" />
            <input type="hidden" id="reqBobotStatusAtributId<?=$checkbox_index?>" name="reqBobotStatusAtributId[]" value="1" />
            <input type="hidden" id="reqBobotAtributId<?=$checkbox_index?>" name="reqBobotAtributId[]" value="<?=$tempAtributParentAtributId?>" />
            </td>
            <td>
                <input type="text" name="reqAtributNilaiStandar[]" id="reqAtributNilaiStandar<?=$checkbox_index?>" class="easyui-validatebox" 
                data-options="validType:'nilaiKurangSamaDengan[100]'" 
                style="width:50px" value="<?=$tempAtributNilaiStandar?>" />
            </td>
            <td>
                <input type="hidden" name="reqAtributBobot[]" id="reqAtributBobot<?=$checkbox_index?>" class="easyui-validatebox" 
                style="width:50px" value="<?=$tempAtributBobot?>" />
            </td>
            <td>
                <input  type="hidden" name="reqAtributSkor[]" id="reqAtributSkor<?=$checkbox_index?>" class="easyui-validatebox" 
                style="width:50px" value="<?=$tempAtributSkor?>" />
            </td>
            <?
			}
			else
			{
            ?>
            <td style="background-color:#D5FFAE" scope="col" colspan="6">
			<?=$tempAtributParentNama?>
            <input type="hidden" id="reqFormulaUnsurBobotId<?=$checkbox_index?>" name="reqFormulaUnsurBobotId[]" value="" />
            <input type="hidden" name="reqBobotStatusAtributId[]" id="reqBobotStatusAtributId<?=$checkbox_index?>" value="" />
            <input type="hidden" name="reqAtributNilaiStandar[]" id="reqAtributNilaiStandar<?=$checkbox_index?>" value="" />
            <input type="hidden" name="reqAtributBobot[]" id="reqAtributBobot<?=$checkbox_index?>" value="" />
            <input type="hidden" name="reqAtributSkor[]" id="reqAtributSkor<?=$checkbox_index?>" value="" />
            <input type="hidden" id="reqBobotAtributId<?=$checkbox_index?>" name="reqBobotAtributId[]" value="<?=$tempAtributParentAtributId?>" />
            </td>
            <?
			}
            ?>
        </tr>
    <?
		}
		else
		{
    ?>
		<?
        if($reqAspekId == "1")
        {
        ?>
        <tr>
        	<td scope="col">
        	<input type="hidden" id="reqFormulaUnsurBobotId<?=$checkbox_index?>" name="reqFormulaUnsurBobotId[]" value="<?=$tempFormulaAtributBobotId?>" />
        	 <input type="hidden" id="reqBobotAtributId<?=$checkbox_index?>" name="reqBobotAtributId[]" value="<?=$tempAtributParentAtributId?>" />
            <input type="hidden" id="reqFormulaUnsurId<?=$checkbox_index?>" name="reqFormulaUnsurId[]" value="<?=$tempAtributParentFormulaAtributId?>" />
            <input type="hidden" id="reqAtributParentLevelId<?=$checkbox_index?>" value="<?=$tempAtributParentLevelId?>" />
            <input type="hidden" name="reqAtributPenggalianId[]" id="reqAtributPenggalianId<?=$checkbox_index?>" value="<?=$tempAtributParentAtributPenggalianId?>" />
           <!--  <input type="hidden" name="reqLevelId[]" id="reqLevelId<?=$checkbox_index?>" value="<?=$tempAtributParentLevelId?>" /> -->
            
        	<input type="hidden" name="reqAtributId[]" id="reqAtributId<?=$checkbox_index?>" value="<?=$tempAtributParentAtributId?>" />
        	<input type="hidden" id="reqBobotStatusAtributId<?=$checkbox_index?>" name="reqBobotStatusAtributId[]" />
            
            <input type="hidden" id="reqStatusAtributId<?=$checkbox_index?>" name="reqStatusAtributId[]" value="<?=$tempStatusAtributId?>" />
            <input type="checkbox" id="reqCheckBoxAtributId<?=$checkbox_index?>" class="easyui-validatebox" <?=$tempInfoChecked?> />
			<?=$tempAtributParentNama?>
            </td>
            <td scope="col">
            <input type="text" name="reqAtributNilaiStandar[]" id="reqAtributNilaiStandar<?=$checkbox_index?>" class="easyui-validatebox" 
            data-options="validType:'nilaiKurangSamaDengan[100]'" 
            style="width:50px" value="<?=$tempAtributNilaiStandar?>" />
            </td>
            <td colspan="3"></td>
        </tr>
		<?
		}
		else
		{
        ?>
    	<tr>
        	<td scope="col">
            <input type="hidden" id="reqFormulaUnsurId<?=$checkbox_index?>" name="reqFormulaUnsurId[]" value="<?=$tempAtributParentFormulaAtributId?>" />
            <input type="hidden" id="reqAtributParentLevelId<?=$checkbox_index?>" value="<?=$tempAtributParentLevelId?>" />
            
            <input type="hidden" name="reqAtributId[]" id="reqAtributId<?=$checkbox_index?>" value="<?=$tempAtributParentAtributId?>" />
            <input type="hidden" id="reqBobotAtributId<?=$checkbox_index?>" name="reqBobotAtributId[]" value="<?=$tempAtributParentAtributId?>" />
            <input type="hidden" id="reqFormulaUnsurBobotId<?=$checkbox_index?>" name="reqFormulaUnsurBobotId[]" value="<?=$tempFormulaAtributBobotId?>" />
            
            <input type="hidden" id="reqStatusAtributId<?=$checkbox_index?>" name="reqStatusAtributId[]" value="<?=$tempStatusAtributId?>" />
            <input type="hidden" id="reqBobotStatusAtributId<?=$checkbox_index?>" name="reqBobotStatusAtributId[]" />
            <input type="checkbox" id="reqCheckBoxAtributId<?=$checkbox_index?>" class="easyui-validatebox" <?=$tempInfoChecked?> />
			<?=$tempAtributParentNama?>
            </td>
            <td scope="col">
            <input type="hidden" name="reqLevelId[]" id="reqLevelValId<?=$checkbox_index?>" />
            <select id="reqLevelId<?=$checkbox_index?>" style="width:50px;"></select>
            </td>
            <td scope="col">
            <input type="text" name="reqNilaiStandar[]" id="reqNilaiStandar<?=$checkbox_index?>" class="easyui-validatebox" 
            data-options="validType:'nilaiKurangSamaDengan[100]'"
            style="width:50px" value="<?=$tempAtributParentNilaiStandar?>" />
            <input type="hidden" name="reqAtributNilaiStandar[]" id="reqAtributNilaiStandar<?=$checkbox_index?>" value="<?=$tempAtributParentNilaiStandar?>" />
            </td>
            <td scope="col">
            <input type="hidden" name="reqAtributPenggalianId[]" id="reqAtributPenggalianId<?=$checkbox_index?>" value="<?=$tempAtributParentAtributPenggalianId?>" />
            <?
				$statement= " AND A.UNSUR_ID = '".$tempAtributParentAtributId."'";
				$set_level_atribut= new LevelAtribut();
				$jumlah_level_atribut= $set_level_atribut->getCountByParams(array(), $statement);
				if($jumlah_level_atribut > 0)
				{
					$arrAtributParentAtributPenggalianId= "";
					$arrAtributParentAtributPenggalianId= explode(",",$tempAtributParentAtributPenggalianId);
					
					for($checkbox_index_detil=0;$checkbox_index_detil < $jumlah_penggalian;$checkbox_index_detil++)
					{
						$index_loop_detil= $checkbox_index_detil;
						$tempPenggalianId= $arrPenggalian[$index_loop_detil]["PENGGALIAN_ID"];
						$tempPenggalianKode= $arrPenggalian[$index_loop_detil]["KODE"];
						
						//cari penggalian id
						if(empty($arrAtributParentAtributPenggalianId)){}
						else
						{
							$tempValuePenggalianId= "";
							$displayNoneSimpan= "";
							$displayNoneHapus= "display:none";
							// kalau nilai ada di array maka lakukan
							if(in_array($tempPenggalianId, $arrAtributParentAtributPenggalianId))
							{
								$tempValuePenggalianId= $tempPenggalianId;
								
								$displayNoneSimpan= "display:none";
								$displayNoneHapus= "";
							}
						}
					?>
                    <input type="hidden" id="reqPenggalianId<?=$checkbox_index."-".$tempAtributParentAtributId."-".$tempPenggalianId?>" value="<?=$tempValuePenggalianId?>" />
					<a id="reqInfoSimpan<?=$checkbox_index."-".$tempAtributParentAtributId."-".$tempPenggalianId?>" style="cursor:pointer; <?=$displayNoneSimpan?>" title="Pilih"
                    onclick="pilihatribut('<?=$checkbox_index?>', '<?=$tempAtributParentAtributId?>', '<?=$tempPenggalianId?>', 'simpan')">
						<img src="../WEB/images/icon_uncheck.png" width="15px" heigth="15px">
					</a>
					<a id="reqInfoHapus<?=$checkbox_index."-".$tempAtributParentAtributId."-".$tempPenggalianId?>" style="cursor:pointer; <?=$displayNoneHapus?>" title="hapus"
                    onclick="pilihatribut('<?=$checkbox_index?>', '<?=$tempAtributParentAtributId?>', '<?=$tempPenggalianId?>', 'hapus')">
						<img src="../WEB/images/icon_check.png" width="15px" heigth="15px">
					</a>
                    <?=$tempPenggalianKode?>&nbsp;&nbsp;&nbsp;
            <?
					}
				}
            ?>
            </td>
            <td>
                <input type="hidden" name="reqAtributBobot[]" id="reqAtributBobot<?=$checkbox_index?>" class="easyui-validatebox" 
                style="width:50px" value="<?=$tempAtributBobot?>" />
            </td>
            <td>
                <input type="hidden" name="reqAtributSkor[]" id="reqAtributSkor<?=$checkbox_index?>" class="easyui-validatebox" 
                style="width:50px" value="<?=$tempAtributSkor?>" />
            </td>
        </tr>
        <?
		}
        ?>
    <?
		}
	}
    ?>
    </tbody>
    </table>
    </form>
    </div>
</div>
<script>
$('input[id^="reqNilaiStandar"]').keypress(function(e) {
	//alert(e.which);
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});

<?
for($checkbox_index=0;$checkbox_index < $jumlah_atribut_parent;$checkbox_index++)
{
	$index_loop= $checkbox_index;
	$tempAtributParentAtributIdParent= $arrAtributParent[$index_loop]["UNSUR_ID_PARENT"];
	if($tempAtributParentAtributIdParent == "0"){}
	else
	{
?>
	<?
	//if($reqAspekId == "1"){}
	//else
	//{
	?>
	
	var reqFormulaUnsurId= "";
	reqFormulaUnsurId= $("#reqFormulaUnsurId<?=$checkbox_index?>").val();
	if(reqFormulaUnsurId == ""){}
	else
	{
	// setGeneralFilter(<?=$checkbox_index?>);
	}
	<?
	//}
	?>
<?
	}
}
?>
</script>
</body>
</html>
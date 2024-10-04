<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaAssesment.php");
include_once("../WEB/classes/base/FormulaEselon.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data data terlebih dahulu.');";	
	echo "window.parent.location.href = 'formula_assesment_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

$statement= " AND A.FORMULA_ID = ".$reqId;
$set= new FormulaAssesment();
$set->selectByParams(array("FORMULA_ID"=> $reqId),-1,-1,'');
$set->firstRow();
$tempFormula= $set->getField('FORMULA');
$tempFormulaTahun= $set->getField('TAHUN');
$tempFormulaKeterangan= $set->getField('KETERANGAN');
unset($set);

$statement="";
$index_loop= 0;
$arrEselon="";
$set= new FormulaEselon();
$set->selectByParamsMonitoring(array(), -1,-1, $reqId, $statement);
// echo $set->query;exit();
while($set->nextRow())
{
	$arrEselon[$index_loop]["FORMULA_ESELON_ID"]= $set->getField("FORMULA_ESELON_ID");
	$arrEselon[$index_loop]["ESELON_ID"]= $set->getField("ESELON_ID");
	$arrEselon[$index_loop]["NAMA_ESELON"]= $set->getField("NAMA_ESELON");
	$arrEselon[$index_loop]["PROSEN_POTENSI"]= $set->getField("PROSEN_POTENSI");
	$arrEselon[$index_loop]["PROSEN_KOMPETENSI"]= $set->getField("PROSEN_KOMPETENSI");
	$arrEselon[$index_loop]["PROSEN_TOTAL"]= $set->getField("PROSEN_TOTAL");
	$index_loop++;
}
$jumlah_eselon= $index_loop;

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
	document.location.href= 'formula_assesment_add_eselon.php?reqId=<?=$reqId?>';
}

function lanjutForm()
{
	var reqEselonTerpilihId= "";
	reqEselonTerpilihId= $("#reqEselonTerpilihId").val();
	
	if(reqEselonTerpilihId == "")
	$.messager.alert('Info', "Pilih salah satu eselon terlebih dahulu", 'info');
	else
	{
		parent.executeOnClick('formula_atribut_'+reqEselonTerpilihId);
	}
}

function checkNan(value)
{
	if(typeof value == "undefined" || isNaN(value))
	return 0;
	else
	return value;
}
		
$(function(){
	$.extend($.fn.validatebox.defaults.rules, {  
		nilaiSama: {  
			//alert('asdsad');
			validator: function(value, param){  
				return value == param[0];
			},
			message: 'Total harus {0}.'
		}  
	});
			
	$('#ff').form({
		url:'../json-pengaturan/formula_assesment_add_eselon.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			//parent.setShowHideMenu(3);
			parent.frames['menuFrame'].location.href = 'formula_assesment_add_menu.php?reqId=<?=$reqId?>&reqMode=formula_eselon';
			//parent.executeOnClick('formula_eselon');
			parent.frames['mainFrame'].location.href = 'formula_assesment_add_eselon.php?reqId=<?=$reqId?>';
		}
	});
	
	$('input[id^="reqProsenPotensi"], input[id^="reqProsenKomptensi"]').keyup(function() {
		var id= $(this).attr('id');
		id= id.replace("reqProsenPotensi", "");
		id= id.replace("reqProsenKomptensi", "");
		//alert('--'+id);
		var reqProsenPotensi= reqProsenKomptensi= reqProsenTotal= "";
		reqProsenPotensi= $("#reqProsenPotensi"+id).val();
		reqProsenKomptensi= $("#reqProsenKomptensi"+id).val();
		
		reqProsenTotal= 0;
		if(parseInt(checkNan(reqProsenPotensi)) > 0)
		reqProsenTotal+= parseInt(checkNan(reqProsenPotensi));
		if(parseInt(checkNan(reqProsenKomptensi)) > 0)
		reqProsenTotal+= parseInt(checkNan(reqProsenKomptensi));
		
		if(parseInt(reqProsenTotal) > 0)
		{
			reqProsenTotal= parseInt(checkNan(reqProsenTotal));
			$("#reqProsenTotal"+id).val(reqProsenTotal);
		}
		else if(parseInt(reqProsenTotal) == 0)
		{
			$("#reqProsenTotal"+id).val("");
		}

		
	});
	
	$('#tableKandidat tr').click(function (){
		var id= $(this).attr('id');
		if(typeof id == "undefined" || id == ""){}
		else
		{
			id= id.replace("trkandidat", "");
			$("#reqIndexTerpilih").val(id);
			var reqEselonTerpilihInfo= reqEselonId= reqFormulaEselonId= "";
			
			reqEselonTerpilihInfo= $("#reqEselonNama"+id).html();
			reqEselonId= $("#reqEselonId"+id).val();
			reqFormulaEselonId= $("#reqFormulaEselonId"+id).val();
			
			if(reqFormulaEselonId == ""){}
			else
			{
				$('[id^="trkandidat"]').removeClass("terpilihcss");
				$("#trkandidat"+id).addClass("terpilihcss");
				$("#reqEselonTerpilihInfo").html(reqEselonTerpilihInfo);
				$("#reqEselonTerpilihId").val(reqFormulaEselonId);
			}
		}
	});
	
});

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
            <div id="header-tna-detil">Formula <span>Eselon</span></div>
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
        <tr>
            <td>Eselon Terpilih</td><td>:</td>
            <td>
            	<input id="reqEselonTerpilihId" type="hidden" />
                <label id="reqEselonTerpilihInfo"></label>
            </td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="insert">
                <input type="hidden" name="reqTahun" value="<?=$tempFormulaTahun?>">
                <input type="submit" name="" value="Simpan" />
                <?
                if($jumlah_eselon == 0){}
                else
                {
                ?>
                <input type="button" onClick="lanjutForm()" value="Lanjut">
                <?
                }
                ?>
            </td>
        </tr>
        <?
		}
		?>
    </table>
    
    <table class="gradient-style" id="tableKandidat" style="width:99%; margin-left:2px">
    <thead>
    <tr>
        <th scope="col" rowspan="2" style="width:50%; text-align:left;">Eselon</th>
        <th colspan="3" style="text-align:center">Prosentase</th>
    </tr>
    <tr>
        <th style="text-align:center">Potensi</th>
        <th style="text-align:center">Kompetensi</th>
        <th style="text-align:center">Total <label style="font-size:10px; font-weight:bold">(harus 100)</label></th>
    </tr>
    </thead>
    <tbody>
    <?
    for($checkbox_index=0;$checkbox_index < $jumlah_eselon;$checkbox_index++)
    {
        $index_loop= $checkbox_index;
		$tempFormulaEselonId= $arrEselon[$index_loop]["FORMULA_ESELON_ID"];
		$tempEselonId= $arrEselon[$index_loop]["ESELON_ID"];
		$tempEselonNama= $arrEselon[$index_loop]["NAMA_ESELON"];
		$tempEselonProsenPotensi= $arrEselon[$index_loop]["PROSEN_POTENSI"];
		$tempEselonProsenKompetensi= $arrEselon[$index_loop]["PROSEN_KOMPETENSI"];
		$tempEselonProsenTotal= $arrEselon[$index_loop]["PROSEN_TOTAL"];
    ?>
    <tr id="trkandidat<?=$checkbox_index?>" style="width:100% !important">
        <td style="width:50%;"><label id="reqEselonNama<?=$checkbox_index?>"><?=$tempEselonNama?></label></td>
        <td style="text-align:center">
        	<input type="text" name="reqProsenPotensi[]" id="reqProsenPotensi<?=$checkbox_index?>" style="width:50px" value="<?=$tempEselonProsenPotensi?>" />
        </td>
        <td style="text-align:center">
        	<input type="text" name="reqProsenKomptensi[]" id="reqProsenKomptensi<?=$checkbox_index?>" style="width:50px" value="<?=$tempEselonProsenKompetensi?>" />
        </td>
        <td style="text-align:center">
        	<input type="text" id="reqProsenTotal<?=$checkbox_index?>" readonly="readonly" style="width:50px; border:none" value="<?=$tempEselonProsenTotal?>"
            class="easyui-validatebox" data-options="validType:'nilaiSama[100]'" />
        </td>
        <input type="hidden" name="reqFormulaEselonId[]" id="reqFormulaEselonId<?=$checkbox_index?>" value="<?=$tempFormulaEselonId?>" />
        <input type="hidden" name="reqEselonId[]" id="reqEselonId<?=$checkbox_index?>" value="<?=$tempEselonId?>" />
    </tr>
    <?
    }
    ?>
    </tbody>
    </table>
    </form>
    </div>
</div>
<script>
$('input[id^="reqProsenPotensi"], input[id^="reqProsenKomptensi"]').keypress(function(e) {
	//alert(e.which);
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>
</body>
</html>
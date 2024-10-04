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

$statement= "";
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
$set->selectByParamsSuksesiFormulaAtribut(array(), -1,-1, $reqId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$checkjumlah= $set->getField("JUMLAH");
	$arrAtributParent[$index_loop]["UNSUR_ID"]= $set->getField("UNSUR_ID");
	$arrAtributParent[$index_loop]["UNSUR_ID_PARENT"]= $set->getField("UNSUR_ID_PARENT");
	$arrAtributParent[$index_loop]["NAMA"]= $set->getField("NAMA");
	$unsurbobot= $set->getField("UNSUR_BOBOT");
	if($checkjumlah == "1")
		$unsurbobot= 100;
	$arrAtributParent[$index_loop]["UNSUR_BOBOT"]= $unsurbobot;
	$arrAtributParent[$index_loop]["BOBOT"]= $set->getField("BOBOT");
	$arrAtributParent[$index_loop]["FORMULA_UNSUR_BOBOT_ID"]= $set->getField("FORMULA_UNSUR_BOBOT_ID");
	$index_loop++;
}
$jumlah_atribut_parent= $index_loop;
// print_r($arrAtributParent);exit;
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
	document.location.href= 'formula_suksesi_add_suksesi.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
}

$(function(){
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

			total=0;
			$('input[id^="reqAtributBobot"]').each(function(e){
				var id= $(this).attr('id');
				id= id.replace("reqAtributBobot", "");
				panjang= id.length;
				var checkval= $(this).val();

				if(checkval !== "")
					total= parseFloat(total) + parseFloat(checkval);
			});
			// console.log(total);

			// if(parseFloat(total) > 100)
			if(parseFloat(total) !== 100)
			{
				// $.messager.alert('Info', "Nilai tidak boleh lebih dari 100", 'error');
				$.messager.alert('Info', "Nilai harus sama dengan 100", 'error');
				return false;
			}
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
		
});

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
            <div id="header-tna-detil">Bobot Unsur Penilaian</div>
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
    </tr>
    </table>
    
    <table class="gradient-style" id="tableKandidat" style="width:99%; margin-left:2px">
    <thead>
  
    <tr>
    	<th style="width:30%">Suksesi</th>
        <th style="width:20%">Bobot</th>
        <th style="width:50%">Bobot Parent</th>
    </tr>
    </thead>
    <tbody>
    <?
    for($checkbox_index=0;$checkbox_index < $jumlah_atribut_parent;$checkbox_index++)
    {
		$index_loop= $checkbox_index;
    	$reqBobotAtributId= $arrAtributParent[$index_loop]["UNSUR_ID"];
		$reqBobotAtributIdParent= $arrAtributParent[$index_loop]["UNSUR_ID_PARENT"];
		$reqInfoNama= $arrAtributParent[$index_loop]["NAMA"];
		$reqAtributNilaiStandar= $arrAtributParent[$index_loop]["UNSUR_BOBOT"];
		$reqAtributBobot= $arrAtributParent[$index_loop]["BOBOT"];
		$reqFormulaUnsurBobotId= $arrAtributParent[$index_loop]["FORMULA_UNSUR_BOBOT_ID"];
		
		if($reqBobotAtributIdParent == "0")
		{
    ?>
        <tr>
        	<td style="background-color:#D5FFAE" scope="col">
			<?=$reqInfoNama?>
            <input type="hidden" id="reqBobotAtributId<?=$reqBobotAtributId?>" name="reqBobotAtributId[]" value="<?=$reqBobotAtributId?>" />
            <input type="hidden" id="reqFormulaUnsurBobotId<?=$reqBobotAtributId?>" name="reqFormulaUnsurBobotId[]" value="<?=$reqFormulaUnsurBobotId?>" />
            </td>
            <td>
                <input type="text" readonly name="reqAtributNilaiStandar[]" id="reqAtributNilaiStandar<?=$reqBobotAtributId?>" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" style="width:50px" value="<?=$reqAtributNilaiStandar?>" />
            </td>
            <td>
                <input type="text" name="reqAtributBobot[]" id="reqAtributBobot<?=$reqBobotAtributId?>" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" style="width:50px" value="<?=$reqAtributBobot?>" />
            </td>
        </tr>
    <?
		}
		else
		{
    ?>
		<tr>
        	<td scope="col">
    		<input type="hidden" id="reqBobotAtributId<?=$reqBobotAtributId?>" name="reqBobotAtributId[]" value="<?=$reqBobotAtributId?>" />
    		<input type="hidden" id="reqAtributBobot<?=$reqBobotAtributId?>" name="reqAtributBobot[]" value="<?=$reqAtributBobot?>" />
    		<input type="hidden" id="reqFormulaUnsurBobotId<?=$reqBobotAtributId?>" name="reqFormulaUnsurBobotId[]" value="<?=$reqFormulaUnsurBobotId?>" />
        	<input type="checkbox" id="reqCheckBoxAtributId<?=$reqBobotAtributId?>" class="easyui-validatebox" />
			<?=$reqInfoNama?>
            </td>
            <td scope="col" colspan="2">
            <input type="text" name="reqAtributNilaiStandar[]" id="reqAtributNilaiStandar<?=$reqBobotAtributId?>" class="easyui-validatebox" data-options="validType:'nilaiKurangSamaDengan[100]'" style="width:50px" value="<?=$reqAtributNilaiStandar?>" />
            </td>
        </tr>
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
$('input[id^="reqAtributNilaiStandar"]').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});

$('input[id^="reqAtributNilaiStandar"]').each(function(e){
	var id= $(this).attr('id');
	id= id.replace("reqAtributNilaiStandar", "");
	panjang= id.length;
	var checkval= $(this).val();
	
	if(panjang == "4" && checkval !== "" )
	{
		$("#reqCheckBoxAtributId"+id).attr('checked', 'checked');
		$("#reqCheckBoxAtributId"+id).prop('checked', true);
	}
	else
	{
		$("#reqCheckBoxAtributId"+id).attr('checked', '');
		$("#reqCheckBoxAtributId"+id).prop('checked', false);
	}
});

$('input[id^="reqAtributNilaiStandar"]').keyup(function() {
	var id= $(this).attr('id');
	id= id.replace("reqAtributNilaiStandar", "");
	parentid= id= id.substring(0, 2);
	// console.log('--'+id);

	valreturn= 0;
	$('input[id^="reqAtributNilaiStandar'+id+'"]').each(function(e){
		var id= $(this).attr('id');
		id= id.replace("reqAtributNilaiStandar", "");
		panjang= id.length;
    	var checkval= $(this).val();
    	
    	if(panjang == "4" && checkval !== "" )
    	{
    		$("#reqCheckBoxAtributId"+id).attr('checked', 'checked');
    		$("#reqCheckBoxAtributId"+id).prop('checked', true);
    		valreturn= parseFloat(valreturn) + parseFloat(checkval);
    	}
    });
    // console.log(valreturn);
    $("#reqAtributNilaiStandar"+parentid).val(valreturn);
});
</script>
</body>
</html>
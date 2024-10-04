<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaAssesment.php");
include_once("../WEB/classes/base/FormulaAssesmentAtributUrutan.php");
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

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
// echo $set->query;exit();
$set->firstRow();
$tempFormula= $set->getField('FORMULA');
$tempFormulaTahun= $set->getField('TAHUN');
$tempFormulaKeterangan= $set->getField('KETERANGAN');
unset($set);

$set= new FormulaAssesmentAtributUrutan();
$set->selectByParamsEselon(array(), -1,-1, $reqId);
// echo $set->query;exit();
$set->firstRow();
$tempFormulaEselonId= $set->getField("FORMULA_ESELON_ID");
$tempFormulaEselonEselonId= $set->getField("ESELON_ID");
$tempFormulaEselonNamaEselon= $set->getField("NAMA_ESELON");
$tempFormulaEselonProsenPotensi= $set->getField("PROSEN_POTENSI");
$tempFormulaEselonProsenKompetensi= $set->getField("PROSEN_KOMPETENSI");
$tempFormulaEselonPermenId= $set->getField("FORM_PERMEN_ID");

$index_loop= 0;
$arrAtributParent="";
$set= new FormulaAssesmentAtributUrutan();
$set->selectByParams(array(), -1,-1, $reqRowId);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrAtributParent[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrAtributParent[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
	$arrAtributParent[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrAtributParent[$index_loop]["ASPEK_NAMA"]= $set->getField("ASPEK_NAMA");
	$arrAtributParent[$index_loop]["NAMA"]= $set->getField("NAMA");
	$arrAtributParent[$index_loop]["FORMULA_ATRIBUT_ID"]= $set->getField("FORMULA_ATRIBUT_ID");
	$arrAtributParent[$index_loop]["LEVEL_ID"]= $set->getField("LEVEL_ID");
	$arrAtributParent[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
	$arrAtributParent[$index_loop]["ATRIBUT_NILAI_STANDAR"]= $set->getField("ATRIBUT_NILAI_STANDAR");
	$arrAtributParent[$index_loop]["ATRIBUT_BOBOT"]= $set->getField("ATRIBUT_BOBOT");
	$arrAtributParent[$index_loop]["ATRIBUT_SKOR"]= $set->getField("ATRIBUT_SKOR");
	$arrAtributParent[$index_loop]["FORMULA_ATRIBUT_BOBOT_ID"]= $set->getField("FORMULA_ATRIBUT_BOBOT_ID");

	$arrAtributParent[$index_loop]["FORMULA_ESELON_ID"]= $set->getField("FORMULA_ESELON_ID");
	$arrAtributParent[$index_loop]["PERMEN_ID"]= $set->getField("PERMEN_ID");
	$arrAtributParent[$index_loop]["URUT"]= $set->getField("URUT");
	$index_loop++;
}
$jumlah_atribut_parent= $index_loop;
// print_r($jumlah_atribut_parent);exit;
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
$(function(){
	$('#ff').form({
		url:'../json-pengaturan/formula_assesment_add_atribut_urutan.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			// alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			
			$("#setInfoPerubahan").val("");
			//parent.setShowHideMenu(3);
			setLoad();
		}
	});
		
});

function setLoad()
{
	document.location.href= 'formula_assesment_add_atribut_urutan.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';
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
            <div id="header-tna-detil">Atribut <span><?=$tempFormulaEselonNamaEselon?></span></div>
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
                <input type="hidden" name="reqMode" value="insert">
                <input type="submit" name="" value="Simpan" />
            </td>
        </tr>
        <?
		}
		?>
    </table>
    <?if($jumlah_atribut_parent==0){?>
    <span style="text-align: center;width: 100%;display: block;">POTENSI BELUM DI SET</span>
    <?}else{?>
   	<table class="gradient-style" style="width:100%; margin-left:2px">
    <tr>
    	<td style="width:80%; text-align: right;">Pencarian</td><td style="width:10px">:</td>
        <td><input name="filter" onkeyup="pencarian(this, 'tableKandidat', 0)" type="text" style="width:300px"></td>
    </tr>
    </table>
   
    
    <table class="gradient-style" id="tableKandidat" style="width:99%; margin-left:2px">
    <thead>
    <tr>
    	<th style="width:80%">Atribut</th>
        <th>Urutan</th>
    </tr>
    </thead>
    <tbody>
    <?
    for($checkbox_index=0;$checkbox_index < $jumlah_atribut_parent;$checkbox_index++)
    {
		$index_loop= $checkbox_index;
    	$tempAtributParentAtributId= $arrAtributParent[$index_loop]["ATRIBUT_ID"];
		$tempAtributParentAtributIdParent= $arrAtributParent[$index_loop]["ATRIBUT_ID_PARENT"];
		$tempAtributParentNama= $arrAtributParent[$index_loop]["NAMA"];
		$tempFormulaPermenId= $arrAtributParent[$index_loop]["PERMEN_ID"];
		$tempFormulaEselonId= $arrAtributParent[$index_loop]["FORMULA_ESELON_ID"];
		$tempFormulaUrut= $arrAtributParent[$index_loop]["URUT"];


		/*$tempAtributParentAspekId= $arrAtributParent[$index_loop]["ASPEK_ID"];
		$tempAtributParentAspekNama= $arrAtributParent[$index_loop]["ASPEK_NAMA"];
		$tempAtributParentFormulaAtributId= $arrAtributParent[$index_loop]["FORMULA_ATRIBUT_ID"];
		$tempAtributParentLevelId= $arrAtributParent[$index_loop]["LEVEL_ID"];
		$tempAtributParentNilaiStandar= $arrAtributParent[$index_loop]["NILAI_STANDAR"];
		$tempAtributParentAtributPenggalianId= $arrAtributParent[$index_loop]["ATRIBUT_PENGGALIAN_ID"];
		
		$tempAtributNilaiStandar= $arrAtributParent[$index_loop]["ATRIBUT_NILAI_STANDAR"];
		$tempAtributBobot= $arrAtributParent[$index_loop]["ATRIBUT_BOBOT"];
		$tempAtributSkor= $arrAtributParent[$index_loop]["ATRIBUT_SKOR"];
		$tempFormulaAtributBobotId= $arrAtributParent[$index_loop]["FORMULA_ATRIBUT_BOBOT_ID"];*/
    ?>
        <tr>
        	<?
        	if($tempAtributParentAtributIdParent == "0")
        	{
        	?>
            <td style="background-color:#D5FFAE" scope="col">
				<?=$tempAtributParentNama?>
			</td>
			<td>
            	<input type="hidden" id="reqFormulaEselonId<?=$checkbox_index?>" name="reqFormulaEselonId[]" value="<?=$tempFormulaEselonId?>" />
            	<input type="hidden" id="reqFormulaPermenId<?=$checkbox_index?>" name="reqFormulaPermenId[]" value="<?=$tempFormulaPermenId?>" />
            	<input type="hidden" id="reqFormulaAtributId<?=$checkbox_index?>" name="reqFormulaAtributId[]" value="<?=$tempAtributParentAtributId?>" />
                <input type="text" name="reqFormulaUrut[]" id="reqFormulaUrut<?=$checkbox_index?>" class="easyui-validatebox" 
                style="width:50px" value="<?=$tempFormulaUrut?>" />
            </td>
            <?
        	}
        	else
        	{
        	?>
        	<td>
        		<?=$tempAtributParentNama?>
        	</td>
        	<td>
            	<input type="hidden" id="reqFormulaEselonId<?=$checkbox_index?>" name="reqFormulaEselonId[]" value="<?=$tempFormulaEselonId?>" />
            	<input type="hidden" id="reqFormulaPermenId<?=$checkbox_index?>" name="reqFormulaPermenId[]" value="<?=$tempFormulaPermenId?>" />
            	<input type="hidden" id="reqFormulaAtributId<?=$checkbox_index?>" name="reqFormulaAtributId[]" value="<?=$tempAtributParentAtributId?>" />
                <input type="text" name="reqFormulaUrut[]" id="reqFormulaUrut<?=$checkbox_index?>" class="easyui-validatebox" 
                style="width:50px" value="<?=$tempFormulaUrut?>" />
            </td>
        	<?
        	}
            ?>
        </tr>
    <?
	}
    ?>
    </tbody>
    </table>
    <?}?>
    </form>
    </div>
</div>
<script>
$('input[id^="reqFormulaUrut"]').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>                                                                                              
                                                                                              

<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/LevelAtribut.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode= httpFilterRequest("reqMode");
$reqAtributId= httpFilterGet("reqAtributId");
$reqRowId= httpFilterGet("reqRowId");

$set = new LevelAtribut();
if($reqRowId == "")
{
	$reqMode= "insert";
}
else
{
	$reqMode= "update";
	$set->selectByParams(array("A.LEVEL_ID"=> $reqRowId),-1,-1,'');
	$set->firstRow();
	
	$tempLevel= $set->getField('LEVEL');
	$tempKeterangan= $set->getField('KETERANGAN');
}

$arrReqFilter= array("reqLevel");
$arrReqJson= array("level_atribut_combo_json");
$arrReqSelected= array($tempLevel);

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
function setLoad()
{
	document.location.href= 'indikator_penilaian_add_level_atribut.php?reqAtributId=<?=$reqAtributId?>';
}

function lanjutForm()
{
	<?
	if($tempLevel == ""){}
	else
	{
	?>
	parent.executeOnClick('level_'+<?=$tempLevel?>);
	<?
	}
	?>
}

function setGeneralFilter()
{
	<?
	for($indexReqFilter=0; $indexReqFilter < count($arrReqFilter); $indexReqFilter++)
	{
	?>
		$("#<?=$arrReqFilter[$indexReqFilter]?> :selected").remove(); 
		$("#<?=$arrReqFilter[$indexReqFilter]?> option").remove();
		var reqRowId= "";
		reqRowId= '<?=$tempLevel?>';
		
		var s_url= "../json-pengaturan/<?=$arrReqJson[$indexReqFilter]?>.php?reqAtributId=<?=$reqAtributId?>&reqRowId="+reqRowId;
		var request = $.get(s_url);
		request.done(function(dataJson)
		{
			var data= JSON.parse(dataJson);
			for(i=0;i<data.arrID.length; i++)
			{
				var selected= "";
				valId= data.arrID[i]; valNama= data.arrNama[i];
				
				<?
				if($arrReqSelected[$indexReqFilter] == ""){}
				else
				{
				?>
				if(<?=$arrReqSelected[$indexReqFilter]?> == valId)
				selected= "selected";
				<?
				}
				?>
				
				$("<option value='" + valId + "' "+selected+" >" + valNama + "</option>").appendTo("#<?=$arrReqFilter[$indexReqFilter]?>");
			}
		});
	<?
	}
	?>
}
				
$(function(){
	setGeneralFilter();
	$('#ff').form({
		url:'../json-pengaturan/indikator_penilaian_add_level_atribut.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			//parent.setShowHideMenu(3);
			parent.frames['menuFrame'].location.href = 'indikator_penilaian_add_menu.php?reqAtributId=<?=$reqAtributId?>';
			parent.frames['mainFrame'].location.href = 'indikator_penilaian_add_level_atribut_monitoring.php?reqAtributId=<?=$reqAtributId?>';
			parent.frames['mainFrameDetil'].location.href = 'indikator_penilaian_add_level_atribut.php?reqAtributId=<?=$reqAtributId?>';
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
                <td style="width:150px">Level</td><td style="width:5px">:</td>
                <td>
                    <select name="reqLevel" id="reqLevel" style="width:50px;"></select>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td><td>:</td>
                <td>
                	<textarea name="reqKeterangan" id="reqKeterangan" style="width:90%; resize: none;" rows="1"><?=$tempKeterangan?></textarea>
                </td>	
            </tr>
            <tr>
                <td colspan="3">
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
                    <input type="hidden" name="reqAtributId" value="<?=$reqAtributId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                    <input type="submit" name="" value="Simpan" />
                    <?
					if($reqRowId == ""){}
					else
					{
                    ?>
                    <input type="button" onClick="lanjutForm()" value="Lanjut">
                    <?
					}
                    ?>
                    <input type="button" onclick="setLoad()" value="Baru" />
                </td>
            </tr>
        </table>     
    </form>
    </div>
</div>
<script>
$('#reqKeterangan').keydown(function(e) {
     if(e.keyCode == 13) {
       e.preventDefault(); // Makes no difference
       //$(this).parent().submit(); // Submit form it belongs to
   }
});
</script>
</body>
</html>
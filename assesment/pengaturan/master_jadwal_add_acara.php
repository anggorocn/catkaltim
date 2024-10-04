<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Penggalian.php");
include_once("../WEB/classes/base/JadwalAcara.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode 	= httpFilterRequest("reqMode");
$reqId = httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

$jadwal_acara = new JadwalAcara();
if($reqRowId == "")
{
	$reqMode= "insert";
}
else
{
	$reqMode= "update";
	$jadwal_acara->selectByParams(array("JADWAL_ACARA_ID"=> $reqRowId),-1,-1,'');
	$jadwal_acara->firstRow();
	
	$tempJadwalTesId= $jadwal_acara->getField('JADWAL_TES_ID');
	$tempPenggalianId= $jadwal_acara->getField('PENGGALIAN_ID');
	$tempPukul1= $jadwal_acara->getField('PUKUL1');
	$tempPukul2= $jadwal_acara->getField('PUKUL2');
	$tempKeterangan= $jadwal_acara->getField('KETERANGAN');
}

$penggalian= new Penggalian();
$penggalian->selectByParamsJadwalTesNew(array(), -1,-1, "", $reqId);
// echo $penggalian->query;exit();

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

<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript" src="../WEB/lib/timepicker/jquery-ui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/timepicker/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../WEB/lib/timepicker/jquery-ui-sliderAccess.js"></script>
    
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	function setLoad()
	{
		document.location.href= 'master_jadwal_add_acara.php?reqId=<?=$reqId?>';
	}

	function setcetak()
	{
		newWindow= window.open("master_jadwal_add_acara_excel.php?reqRowId=<?=$reqRowId?>", 'Cetak');
		newWindow.focus();
	}
		
	$(function(){
		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_add_acara.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				//parent.setShowHideMenu(3);
				parent.frames['mainFrame'].location.href = 'master_jadwal_add_acara_monitoring.php?reqId=<?=$reqId?>';
				parent.frames['mainFrameDetil'].location.href = 'master_jadwal_add_acara.php?reqId=<?=$reqId?>';
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
<div id="content" style="height:auto; width:100%">
		<form id="ff" method="post" novalidate>
			<table class="table_list" cellspacing="1" width="100%">
				<tr>           
					<td style="width:150px">Penggalian</td><td style="width:5px">:</td>
					<td>
						<select name="reqPenggalianId" style="width:200px;">
                        <!-- <option value="0">Potensi</option> -->
						<?
                        while($penggalian->nextRow()) 
                        {
                        ?>
                        <option value="<?=$penggalian->getField("PENGGALIAN_ID")?>" <? if($penggalian->getField("PENGGALIAN_ID") == $tempPenggalianId) echo "selected"?>><?=$penggalian->getField("NAMA")?></option>
                        <?
                        }
                        ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Pukul</td><td>:</td>
					<td>
                    	<input type="text" id="reqPukul1" name="reqPukul1" value="<?=$tempPukul1?>" style="width:50px" /> 
                        s/d
                        <input type="text" id="reqPukul2" name="reqPukul2" value="<?=$tempPukul2?>" style="width:50px" /> 
						<script type="text/javascript">
                        $('#reqPukul1,#reqPukul2').timepicker();
                        </script>
					</td>	
				</tr>
				<tr>
					<td>Keterangan</td><td>:</td>
					<td>
						<input type="text" style="width:50%" name="reqKeterangan" value="<?=$tempKeterangan?>" />
					</td>	
				</tr>
				<tr>
					<td colspan="3">
                    	<input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input type="submit" name="" value="Simpan" />
                        <input type="button" onclick="setLoad()" value="Baru" />
                        <input type="button" onclick="setcetak()" value="Cetak" />
					</td>
				</tr>
			</table>     
        </form>
        <script>
		$("#reqUrut").keypress(function(e) {
			//alert(e.which);
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			{
			return false;
			}
		});
		</script>
    </div>
</div>
</body>
</html>
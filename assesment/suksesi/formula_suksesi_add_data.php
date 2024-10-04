<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaSuksesi.php");

$set= new FormulaSuksesi();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode= "insert";
	$tempTahun= date("Y");
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
}
//echo $user_group->query;exit;
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
		parent.executeOnClick('formula_suksesi');
	}
	
	$(function(){
		$('#ff').form({
			url:'../json-suksesi/formula_suksesi_add_data.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// alert(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= data[0];
				$('#rst_form').click();
				top.frames['mainFullFrame'].location.reload();
				parent.frames['menuFrame'].location.href = 'formula_suksesi_add_menu.php?reqId='+reqId;
				document.location.href = 'formula_suksesi_add_data.php?reqId='+reqId;
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
					<!-- <div id="header-tna-detil">Data <span>Pelaksanaan</span></div> -->
					<div id="header-tna-detil">Nama <span>Program Suksesi</span></div>
					</td>			
				</tr>
				<tr>
					<td width="100px">Tahun</td>
					<td width="2px">:</td>
					<td>
                    	<input type="text" name="reqTahun" id="reqTahun" class="easyui-validatebox" style="width:50px" value="<?=$tempTahun?>" />
				   </td>
				</tr>
                <tr>
					<td>Formula</td>
					<td>:</td>
					<td>        
						<input type="text" name="reqFormula" id="reqFormula" class="easyui-validatebox" style="width:60%" value="<?=$tempFormula?>" />
				   </td>
				</tr>
                <tr>
					<td>Keterangan</td>
					<td>:</td>
					<td>        
						<textarea name="reqKeterangan" style="width:98%" rows="4"><?=$tempKeterangan?></textarea>
				   </td>
				</tr>
				<!-- <tr>           
					<td width="5%">Tipe Formula</td><td width="2%">:</td>
					<td>
						<select name = "reqTipeFormula" >
							<option value="1" <? if($tempTipeFormula == '1') echo 'selected';?>>Tujuan Pengisian</option>
							<option value="2" <? if($tempTipeFormula == '2') echo 'selected';?>>Tujuan Pemetaan</option>
						</select>
					</td>
		        </tr> -->
				<tr>
					<td colspan="3">
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input type="submit" name="" value="Simpan" />
                        <?
						if($reqId == ""){}
						else
						{
                        ?>
						<input type="button" value="Lanjut" onclick="lanjutForm()" />
                        <?
						}
                        ?>
					</td>
				</tr> 
			</table>       
        </form>
        <script>
		$("#reqTahun").keypress(function(e) {
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
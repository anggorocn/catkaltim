<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");

$set= new JadwalTes();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode= "insert";
}
else
{
	$reqMode= "update";
	$set->selectByParams(array("JADWAL_TES_ID"=> $reqId),-1,-1,'');
	$set->firstRow();
	
	$tempTanggalTes	= dateToPageCheck($set->getField('TANGGAL_TES'));
	$tempKeterangan	= $set->getField('KETERANGAN');
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
	var tempNRP='';
		
	$(function(){
			
		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_tes_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				top.frames['mainFullFrame'].location.reload();
				<? 
				if($reqMode == "update") 
				{ 
				?>
					window.parent.divwin.close();
				<? 
				} 
				else
				{
				?>
					document.location.href= "master_jadwal_tes_add.php";
				<?
				}
				?>
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
					<div id="header-tna-detil">Tambah <span>Jadwal Tes</span></div>	                    
					</td>			
				</tr>
				<tr>
					<td width="200px">Tanggal Tes</td>
					<td width="2px">:</td>
					<td>        
						<input type="text" name="reqTanggalTes" required style="width:100px" class="easyui-datebox" data-options="validType:'date', panelHeight:'160'" value="<?=$tempTanggalTes?>"/>
				   </td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td>:</td>
					<td>        
						<textarea name="reqKeterangan" style="width:98%" rows="4"><?=$tempKeterangan?></textarea>
				   </td>
				</tr>	
				<tr>
					<td>
						<input type="hidden" name="reqId" value="<?=$reqId?>">
						<input type="hidden" name="reqMode" value="<?=$reqMode?>">
						<input type="submit" name="" value="Simpan" /> 
						<input type="reset" name="" value="Reset" />
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
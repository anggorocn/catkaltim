<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/RiwayatSkp.php");

// echo 'asdas';exit;

ini_set("memory_limit","100M");

/* create objects */
$riwayatskp= new RiwayatSkp();
$riwayatcheck= new RiwayatSkp();



//$file = new FileHandler();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqMode 			= httpFilterRequest("reqMode");
$reqRowMode 		= httpFilterGet("reqRowMode");
$reqPegawaiId 		= httpFilterGet("reqPegawaiId");
// $reqId 		= httpFilterGet("reqId");

$riwayatcheck->selectByParamsMonitoring(array("A.PEGAWAI_ID" => $reqPegawaiId));
$riwayatcheck->firstRow();
$reqId	= $riwayatcheck->getField('RIWAYAT_SKP_ID');


if($reqId == "")
{
	$reqMode = "insert";	
}
else
{
	$reqMode = "update";	
	/* VALIDATION */
	$riwayatskp->selectByParamsMonitoring(array("A.PEGAWAI_ID" => $reqPegawaiId));
	$riwayatskp->firstRow();
//echo $riwayatskp->query;exit;
	$tempNilaiSkp				= $riwayatskp->getField('NILAI_SKP');
	$tempTahun				= $riwayatskp->getField('SKP_TAHUN');
}

// echo $reqMode;exit;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>
<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css"><script language="JavaScript" src="../jslib/displayElement.js"></script>
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/js/jquery.validate.js"></script>


<!-- <script type="text/javascript" src="jquery-1.4.2.min.js"></script>
 -->
<!-- <script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="validate/jquery-ui.min.js"></script> 
<script type="text/javascript" src="validate/jquery.validate.js"></script>
<link type="text/css" href="validate/jquery-ui.datepickerValidate.css" rel="stylesheet" /> -->

<script type="text/javascript">	
	var tempNRP='';
		
	$(function(){
		$('#ff').form({
			url:'../json-silat/skp_json.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= data[0];
				$('#rst_form').click();
				// console.log(reqId);
				
				top.frames['mainFrame'].location.reload();
				parent.frames['menuFrame'].location.href = 'pegawai_menu_edit.php?reqPegawaiId='+reqId;
				document.location.href = 'skp.php?reqPegawaiId='+reqId;
			}
		});
		
	});
</script>

<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>


<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
 <style type="text/css" media="screen">
      label {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 3px;
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

    <link href="styles.css" rel="stylesheet" type="text/css" />
    <?php /*?><script language="JavaScript" src="../WEB/lib/easyui/DisableKlikKanan.js"></script><?php */?>
</head>
<body>
	<div id="page_effect">
		<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
		<form id="ff" method="post" novalidate>
			<div id="content" style="height:auto; margin-top:-4px; width:100%">

				<div class="content" style="height:97%; width:100%;overflow:hidden; overflow:-x:hidden; overflow-y:auto; position:fixed;">
					<table class="table_list" cellspacing="1" width="100%">
						<tr>
							<td colspan="6">
								<div id="header-tna-detil">Riwayat <span>SKP</span></div>	                    
							</td>			
						</tr>
						<tr>           
							<td>Nilai SKP</td><td>:</td>
							<td>
								<input type="text" style="width:318px" name="reqNilaiSkp" value="<?=$tempNilaiSkp?>" class="easyui-validatebox" title="Nama harus diisi"  />
							</td>
						</tr>
						<tr>           
							<td>Tahun</td><td>:</td>
							<td>
								<input type="text" style="width:318px" name="reqTahun" value="<?=$tempTahun?>" class="easyui-validatebox"    />
							</td>
						</tr>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="reqId" value="<?=$reqId?>">
							<input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
							<input type="hidden" name="reqMode" value="<?=$reqMode?>">
							<input type="submit" name="" value="Simpan" /> 
							<input type="reset" name="" value="Reset" />
						</td>
					</tr> 
				</table>
			</div>
		</div>
	</form>
</div>
</body>
</html>
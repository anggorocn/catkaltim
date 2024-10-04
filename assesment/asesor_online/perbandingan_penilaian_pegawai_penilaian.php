<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/JadwalPegawaiDetilKomentar.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$reqAsesorId= httpFilterGet("reqAsesorId");
$reqAtributId= httpFilterGet("reqAtributId");
$reqLevelId= httpFilterGet("reqLevelId");
$reqIndikatorId= httpFilterGet("reqIndikatorId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqJadwalPegawaiId= httpFilterGet("reqJadwalPegawaiId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

$tempAsesorId= $userLogin->userAsesorId;

if($tempAsesorId == "")
{
	echo '<script language="javascript">';
	echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
	echo 'top.location.href = "../main/login.php";';
	echo '</script>';		
	exit;
}

$statement= " AND A.ASESOR_ID = ".$reqAsesorId." AND A.ATRIBUT_ID = '".$reqAtributId."' AND A.LEVEL_ID = ".$reqLevelId." AND A.INDIKATOR_ID = ".$reqIndikatorId." AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_PEGAWAI_ID = ".$reqJadwalPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new JadwalPegawaiDetilKomentar();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempRowId= $set->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID");
$tempKeterangan= $set->getField("KETERANGAN");
unset($set);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Pelaporan Hasil Assesment</title>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
<link rel="stylesheet" href="../WEB/css/gaya-assesor.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib/Font-Awesome-4.5.0/css/font-awesome.css">
    
<!--<script type='text/javascript' src="../WEB/lib/bootstrap/jquery.js"></script> -->

    <style>
	.col-md-12{
		*padding-left:0px;
		*padding-right:0px;
	}
	</style>
    
    <script src="../WEB/lib/emodal/eModal.js"></script>
    <script>
	function openPopup() {
		//document.getElementById("demo").innerHTML = "Hello World";
		//alert('hhh');
		// Display a ajax modal, with a title
		eModal.ajax('konten.html', 'Judul Popup')
		//  .then(ajaxOnLoadCallback);
	}

	

	</script>
    
    <!-- FLUSH FOOTER -->
    <style>
	html, body {
		height: 100%;
	}
	
	#wrap-utama {
		min-height: 100%;
		*min-height: calc(100% - 10px);
	}
	
	#main {
		overflow:auto;
		padding-bottom:50px; /* this needs to be bigger than footer height*/
	}
	
	.footer {
		position: relative;
		margin-top: -50px; /* negative value of footer height */
		height: 50px;
		clear:both;
		padding-top:20px;
		*background:cyan;
		
		text-align:center;
		color:#FFF;
	}
	@media screen and (max-width:767px) {
		.footer {
			font-size:12px;
		}
	}

	</style>
    
    <style>
	.rbtn ul{
		list-style-type:none;
	}
	.rbtn ul li{
		cursor:pointer; 
		*display:inline-block; 
		display:inherit;
		width:100px; 
		border:1px solid #06345f; 
		padding:5px;
		margin:-5px;
		*margin-right:5px; 
		
		-moz-border-radius: 3px; 
		-webkit-border-radius: 3px; 
		-khtml-border-radius: 3px; 
		border-radius: 3px; 
		
		text-align:center;
		
	}
	.over{
		background: #063a69;
	}
	
	.sebelumselected{
		background: #063a69; 
		color:#fff;
		*margin:2px;
	}
	
	.sebelumselected:before{
		font-family:"FontAwesome";
		content:"\f096";
		*margin-right:10px;
		color:#f8a406;
		font-size:18px;
		*vertical-align:middle;
	}
	
	.selected{
		background: #063a69; 
		color:#fff;
	}
	.selected:before{
		font-family:"FontAwesome";
		content:"\f046";
		*margin-right:10px;
		color:#f8a406;
		font-size:18px;
		*vertical-align:middle;
	}
	</style>
</head>

<body>

<div id="wrap-utama" style="height:100%; ">
    <div id="main" class="container-fluid clear-top" style="height:100%;">
		
        <div class="row" style="height:calc(100% - 20px);">
        	<div class="col-md-12" style="height:100%;">
            	
                
                <div class="container area-menu-app">
                	<div class="row">
                        <div class="col-md-12">
                        	<div class="judul-halaman">Komentar</div>
                        	<div class="area-table-assesor">
                            <form id="ff" method="post" novalidate>
                            	<?php /*?><input name="submit1" type="submit" value="Simpan" /><?php */?>
                                <input name="submit1" type="submit" value="Simpan"/>
                                <input type="hidden" name="reqAsesorId" id="reqAsesorId" value="<?=$reqAsesorId?>"/>
                                <input type="hidden" name="reqAtributId" id="reqAtributId" value="<?=$reqAtributId?>"/>
                                <input type="hidden" name="reqLevelId" id="reqLevelId" value="<?=$reqLevelId?>"/>
                                <input type="hidden" name="reqIndikatorId" id="reqIndikatorId" value="<?=$reqIndikatorId?>"/>
                                <input type="hidden" name="reqPegawaiId" id="reqPegawaiId" value="<?=$reqPegawaiId?>"/>
                                <input type="hidden" name="reqJadwalPegawaiId" id="reqJadwalPegawaiId" value="<?=$reqJadwalPegawaiId?>"/>
                                <input type="hidden" name="reqJadwalTesId" id="reqJadwalTesId" value="<?=$reqJadwalTesId?>"/>
                                <input type="hidden" name="reqMode" id="reqMode" value="insert"/>
                            	<table>
                                <tbody>
                                	<tr>
                                        <td>
                                        <textarea name="reqKeterangan" id="reqKeterangan" style="color:#000 !important"><?=$tempKeterangan?></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                                </table>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
		</div>
        
        
        
    </div>
</div>

<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyuiasesor.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<?php /*?><script src="../WEB/lib/easyui/jquery.min.js" type="text/javascript"></script><?php */?>
<script>
function setReload()
{
	var val= $('#ff').find('.nicEdit-main').text();
	//alert(val);return false;
	var id= "<?=$reqAsesorId."-".$reqAtributId."-".$reqLevelId."-".$reqIndikatorId."-".$reqPegawaiId."-".$reqJadwalPegawaiId."-".$reqJadwalTesId?>";
	parent.setKomentar(id);
	//parent.setKomentar(id,val);
	//parent.divwin.close();
}
$(function(){
	//setReload();
	$('#ff').form({
		url:'../json-asesor/perbandingan_penilaian_pegawai_penilaian.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			if(data == "1")
			setReload();
			//$.messager.alert('Info', data, 'info');
			//$('#rst_form').click();
			//parent.setShowHideMenu(3);
			//document.location.href = 'perbandingan_penilaian_pegawai_penilaian.php?reqJadwalAsesorId=<?=$tempPegawaiInfoJadwalAsesorId?>&reqJadwalPegawaiId=<?=$reqJadwalPegawaiId?>';
		}
	});
});
</script>

<script type="text/javascript" src="../niceEdit/nicedit.js"></script>
<script type="text/javascript">
	//new nicEditor({fullPanel : true}).panelInstance('reqIsi');
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

</body>
</html>
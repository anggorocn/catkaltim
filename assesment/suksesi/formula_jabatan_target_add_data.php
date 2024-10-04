<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaJabatanTarget.php");
include_once("../WEB/classes/base/FormulaSuksesi.php");

$set= new FormulaJabatanTarget();

$reqId= httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
	$set->selectByParams(array('A.FORMULA_JABATAN_TARGET_ID'=>$reqId), -1, -1, $statement_tahun);
	$set->firstRow();
	
	$reqNama= $set->getField("NAMA");
	$reqFormulaSuksesiId= $set->getField("FORMULA_SUKSESI_ID");
	$reqJabatanId= $set->getField("JABATAN_ID");
	$reqJabatanNama= $set->getField("JABATAN_NAMA");
	$reqRumpunId= $set->getField("RUMPUN_ID");
	$reqRumpunNama= $set->getField("RUMPUN_NAMA");
	$reqSatkerId= $set->getField("SATKER_ID");
	$reqSatkerNama= $set->getField("SATKER_NAMA");
	$reqTarget= $set->getField("TARGET");
	$reqKeterangan= $set->getField("KETERANGAN");
}

$formulasuksesi= new FormulaSuksesi();
$formulasuksesi->selectByParams();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	$(function(){
		$.extend($.fn.validatebox.defaults.rules, {  
			nilaiLebihDengan: {  
				//alert('asdsad');
				validator: function(value, param){  
					return parseInt(value) > 0;
				},
				message: 'Nilai harus > 0.'
			}  
		});

		$('#ff').form({
			url:'../json-suksesi/formula_jabatan_target_add_data.php',
			onSubmit:function(){
				reqFormulaSuksesiId= $("#reqFormulaSuksesiId").val();

				if(reqFormulaSuksesiId == "")
				{
					$.messager.alert('Info', "Pilih salah satu Formula Grafik & Kuadran talent pool", 'error');
					return false;
				}

				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= data[0];
				if(reqId == "xxx"){}
				else
				{
					parent.frames['menuFrame'].location.href = 'formula_jabatan_target_add_menu.php?reqId='+reqId;

					if (typeof(window.top) == 'object' && typeof(window.top.mainFullFrame) !== "undefined")
					{
						top.frames['mainFullFrame'].location.reload();
					}

					document.location.href = 'formula_jabatan_target_add_data.php?reqId='+reqId;

					<? 
					if($reqMode == "update") 
					{
					?>
						// window.parent.divwin.close();
					<? 
					} 
					?>
				}

			}
		});

		setKuadranOption("");

		$('#reqTarget').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
		});
		
	});

	function setKuadranOption(info)
	{
		// $('#reqJabatanJsonId').combotree('setValue', "<?=$reqJabatanId?>");
		var url = "../json-suksesi/formula_jabatan_target_add_list.php";
		// console.log(url);
		$('#reqJabatanJsonId').combotree('reload', url);
	}

	function clickNode(cc, id)
	{
		var opts= cc.combotree('options');
		var values= cc.combotree('getValues');

		var t = $('#reqJabatanJsonId').combotree('tree');	// get the tree object
		var n = t.tree('getSelected');		// get selected node
		// console.log(n.satkernama);

		$(function(){
			document.getElementById("reqRumpunId").value = n.rumpunid;
			document.getElementById('reqRumpunNama').innerText = n.rumpunnama;
			document.getElementById("reqSatkerId").value = n.satkerid;
			document.getElementById('reqSatkerNama').innerText = n.satkernama;
			$("#reqJabatanId").val(values);
		});
	}
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
                <td colspan="3">
                	<div id="header-tna-detil">Jabatan <span>Target</span></div>
                </td>			
            </tr>
            <tr>
                <td style="width: 35%">Nama</td>
                <td style="width: 20px">:</td>
                <td>
                	<input id="reqNama" name="reqNama" class="easyui-validatebox" required style="width:50%" type="text" value="<?=$reqNama?>" />
                </td>
            </tr>
            <tr>
            	<td>Formula Grafik & Kuadran talent pool</td>
            	<td>:</td>
            	<td>
            		<select id="reqFormulaSuksesiId" name="reqFormulaSuksesiId">
            			<option value="">Pilih salah satu data</option>
            			<?
            			while($formulasuksesi->nextRow())
            			{
            				$infoid= $formulasuksesi->getField("FORMULA_ID");
            				$infonama= $formulasuksesi->getField("FORMULA");
            			?>
            			<option value="<?=$infoid?>" <? if($infoid == $reqFormulaSuksesiId) echo "selected";?>><?=$infonama?></option>
            			<?
            			}
            			?>
            		</select>
            	</td>
            </tr>
            <tr> 
				<td>Nama Jabatan</td>
				<td>:</td>
				<td>
					<div id="reqJabatanInfoId">
						<input id="reqJabatanJsonId" class="easyui-combotree" data-options="
	                    onLoadSuccess: function (row, data) {
	                        $('#reqJabatanJsonId').combotree('tree').tree('collapseAll');
	                    },
	                    onClick: function(node){
	                        clickNode($('#reqJabatanJsonId'), node.id);
	                    },
	                    onCheck: function(node, checked){
	                        clickNode($('#reqJabatanJsonId'), node.id);
	                    }" style="width:450px;" value="<?=$reqJabatanNama?>" />
	                    <input type="hidden" name="reqJabatanId" id="reqJabatanId" value="<?=$reqJabatanId?>" />
	                </div>
				</td>
	        </tr>
	        <tr>
            	<td>Rumpun Jabatan</td>
            	<td>:</td>
            	<td>
            		<input type="hidden" name="reqRumpunId" id="reqRumpunId" value="<?=$reqRumpunId?>" />
            		<label id="reqRumpunNama"><?=$reqRumpunNama?></label>
            	</td>
            </tr>
            <tr>
            	<td>Instansi</td>
            	<td>:</td>
            	<td>
            		<input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
            		<label id="reqSatkerNama"><?=$reqSatkerNama?></label>
            	</td>
            </tr>
            <tr>
            	<td>Jumlah Jabatan Target</td>
            	<td>:</td>
            	<td>
            		<input type="text" required name="reqTarget" id="reqTarget" class="easyui-validatebox" data-options="validType:'nilaiLebihDengan[100]'" style="width:50px" value="<?=$reqTarget?>" />
            	</td>
            </tr>
            <tr>
            	<td>Keterangan</td>
            	<td>:</td>
            	<td>
            		<textarea id="reqKeterangan" name="reqKeterangan" style="width:80%" row="5"><?=$reqKeterangan?></textarea>
            	</td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="reqId" value="<?=$reqId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
                </td>
            </tr> 
        </table>       
        </form>
    </div>
</div>
</body>
</html>
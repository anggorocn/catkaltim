<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PegawaiHcdp.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqFormulaId= httpFilterGet("reqFormulaId");
$readonly= httpFilterGet("readonly");


$set= new PegawaiHcdp();
$set->selectByParams(array('A.PEGAWAI_ID'=>$reqId, 'A.FORMULA_ID'=>$reqFormulaId), -1, -1);
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField("PEGAWAI_HCDP_ID");
$reqJumlahJp= $set->getField("JUMLAH_JP");
unset($set);

$set= new PegawaiHcdp();
$set->selectByParamsPenilaian(array('A.PEGAWAI_ID'=>$reqId, 'D.FORMULA_ID'=>$reqFormulaId), -1, -1);
$set->firstRow();
// echo $set->query;exit;
$infoikk= $set->getField("IKK");
$infojpm= $set->getField("JPM");
$infotahun= $set->getField("TAHUN");
$infometode= $set->getField("METODE");
$infosaran= $set->getField("SARAN_PENGEMBANGAN");
$inforingkasan= $set->getField("RINGKASAN_PROFIL_KOMPETENSI");
unset($set);

$set= new Kelautan();
$set->selectByParamsMonitoringTableTalentPoolJPMMonitoring(array(), -1, -1, "AND X.FORMULA_ID = ".$reqFormulaId." AND A.PEGAWAI_ID = ".$reqId, "", $infotahun);
// echo $set->query;exit;
$set->firstRow();
$infokuadran= $set->getField("ID_KUADRAN");
unset($set);
// echo $infokuadran;exit;

$set= new PegawaiHcdp();
$set->setField("PEGAWAI_HCDP_ID", $reqRowId);
$set->setField("FORMULA_ID", $reqFormulaId);
$set->setField("PEGAWAI_ID", $reqId);
$set->setField("JPM", ValToNullDB($infojpm));
$set->setField("IKK", ValToNullDB($infoikk));
$set->setField("METODE", $infometode);
$set->setField("TAHUN", $infotahun);
$set->setField("KUADRAN", ValToNullDB($infokuadran));
$set->setField("SARAN_PENGEMBANGAN", $infosaran);
$set->setField("RINGKASAN_PROFIL_KOMPETENSI", $inforingkasan);
if(empty($reqRowId))
{
	if($set->insert())
	{
		$reqRowId= $set->id;
	}
}
else
$set->update();
unset($set);

$set= new PegawaiHcdp();
$set->selectByParams(array('A.PEGAWAI_HCDP_ID'=>$reqRowId));
// echo $set->query;exit;
$set->firstRow();
$infopegawainama= $set->getField("PEGAWAI_NAMA");
$infopegawainip= $set->getField("PEGAWAI_NIP_BARU");
$infopegawaipangkat= $set->getField("PEGAWAI_PANGKAT_KODE")." / ".$set->getField("PEGAWAI_PANGKAT_NAMA");
$infopegawaijabatan= $set->getField("PEGAWAI_JABATAN_NAMA");
$infokodekuadran= $set->getField("KODE_KUADRAN");
unset($set);

$index_loop= 0;
$arrAtribut=[];
$statement= "
AND EXISTS
(
	SELECT 1
	FROM
	(
		SELECT A.PENILAIAN_ID
		FROM penilaian A
		INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
		INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
		WHERE 1=1 AND ASPEK_ID IN (1,2)
		AND D.FORMULA_ID = ".$reqFormulaId." AND PEGAWAI_ID = ".$reqId."
	) XXX WHERE A.PENILAIAN_ID = XXX.PENILAIAN_ID
)
";
$set= new PegawaiHcdp();
$set->selectByParamsAtribut(array(), -1,-1, $reqRowId, $reqId, $statement);
// echo $set->query;exit();
while($set->nextRow())
{
	$arrAtribut[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
	$arrAtribut[$index_loop]["ASPEK_NAMA"]= $set->getField("ASPEK_NAMA");
	$arrAtribut[$index_loop]["PERMEN_ID"]= $set->getField("PERMEN_ID");
	$arrAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrAtribut[$index_loop]["GAP"]= $set->getField("GAP");
	$arrAtribut[$index_loop]["PELATIHAN_ID"]= $set->getField("PELATIHAN_ID");
	$arrAtribut[$index_loop]["PELATIHAN_NAMA"]= $set->getField("PELATIHAN_NAMA");
	$arrAtribut[$index_loop]["RM_PELATIHAN_ID"]= $set->getField("RM_PELATIHAN_ID");
	$arrAtribut[$index_loop]["RM_PELATIHAN_NAMA"]= $set->getField("RM_PELATIHAN_NAMA");
	$arrAtribut[$index_loop]["JP"]= $set->getField("JP");
	$arrAtribut[$index_loop]["TAHUN"]= $set->getField("TAHUN");
	$arrAtribut[$index_loop]["KETERANGAN"]= $set->getField("KETERANGAN");

	$index_loop++;
}
$jumlahatribut= $index_loop;
// print_r($arrAtribut);exit;
$disabled='';
$editable='';
if($readonly==1){
	$disabled = 'disabled';
	$editable='editable:false';        
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">

	
	$(function(){
		$('#ff').form({
			url:'../json-silat/pengembangan_kompetensi_add.php',
			onSubmit:function(){
				reqJumlahJp= $("#reqJumlahJp").val();

				if(parseFloat(reqJumlahJp) > 0){}
				else
				{
					$.messager.alert('Info', "Isikan terlebih dahulu nilai JP", 'error');
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
					if (typeof(window.top) == 'object' && typeof(window.top.mainFullFrame) !== "undefined")
					{
						top.frames['mainFullFrame'].location.reload();
					}

					document.location.href = 'pengembangan_kompetensi_add.php?reqId=<?=$reqId?>&reqFormulaId=<?=$reqFormulaId?>';

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

		setKuadranOptionRumpun("");

		setKuadranOption("");

		$('#reqJumlahJp').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9\.]/g, '');
		});
		$('.numeric').bind('input propertychange', function () {
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
		});

		
	});

	function findTotal(){
		var arr = document.getElementsByName('reqJp[]');
		var tot=0;
		for(var i=0;i<arr.length;i++){
			if(parseInt(arr[i].value))
				tot += parseInt(arr[i].value);
		}
		document.getElementById('reqJumlahJp').value = tot;
	};
	document.addEventListener("DOMContentLoaded", function(event) {
		findTotal();
	});

	function setKuadranOption(info)
	{
		<?
		for($index_loop=0;$index_loop < $jumlahatribut;$index_loop++)
		{
			$infoid= $arrAtribut[$index_loop]["ATRIBUT_ID"];
			$infonama= $arrAtribut[$index_loop]["ATRIBUT_NAMA"];
			$infopelatihanid= $arrAtribut[$index_loop]["PELATIHAN_ID"];
		?>

		if(info == "")
		{
			var array = "<?=$infopelatihanid?>".split(',');
			$('#reqPelatihanHcdpJsonId<?=$infoid?>').combotree('setValues', array);
		}

		var url = "../json-silat/pengembangan_kompetensi_add_list.php";
		// console.log(url);
		$('#reqPelatihanHcdpJsonId<?=$infoid?>').combotree('reload', url);
		// $('#reqPelatihanHcdpJsonId<?=$infoid?>').combotree('loadData', items);
		// console.log("<?=$items?>");
		<?
		}
		?>
	}

	function clickNode(cc)
	{
		// var t = $("#"+cc).combotree('tree');	// get the tree object
		// var n = t.tree('getSelected');		// get selected node
		var valtree= $("#"+cc).combotree('getValues');
		var texttree= $("#"+cc).combotree('getText');
		treeid= String(cc).replace("reqPelatihanHcdpJsonId", "");
		// console.log(n);
		
		$(function(){
			$("#reqPelatihanId"+treeid).val(valtree);
			$("#reqPelatihanNama"+treeid).val(texttree);
		});
	}

	function setKuadranOptionRumpun(info)
	{
		<?
		for($index_loop=0;$index_loop < $jumlahatribut;$index_loop++)
		{
			$infoid= $arrAtribut[$index_loop]["ATRIBUT_ID"];
			$infonama= $arrAtribut[$index_loop]["ATRIBUT_NAMA"];
			$infopelatihanid= $arrAtribut[$index_loop]["RM_PELATIHAN_ID"];
		?>

		if(info == "")
		{
			var array = "<?=$infopelatihanid?>".split(',');
			$('#reqRumpunPengembanganJsonId<?=$infoid?>').combotree('setValues', array);
		}

		var url = "../json-silat/pengembangan_rumpun_add_list.php";
		// console.log(url);
		$('#reqRumpunPengembanganJsonId<?=$infoid?>').combotree('reload', url);
		// $('#reqPelatihanHcdpJsonId<?=$infoid?>').combotree('loadData', items);
		// console.log("<?=$items?>");
		<?
		}
		?>
	}

	function clickNodeRumpun(cc)
	{
		// var t = $("#"+cc).combotree('tree');	// get the tree object
		// var n = t.tree('getSelected');		// get selected node
		var valtree= $("#"+cc).combotree('getValues');
		var texttree= $("#"+cc).combotree('getText');
		treeid= String(cc).replace("reqRumpunPengembanganJsonId", "");
		// console.log(n);
		
		$(function(){
			$("#reqRumpunPengembanganId"+treeid).val(valtree);
			$("#reqRumpunPengembanganNama"+treeid).val(texttree);
		});
	}
</script>
</head>

<body>
<div id="page_effect">
<!-- <div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div> -->
<div id="content" style="height:auto; width:100%">
	<div id="header-tna-detil">Analisis <span>Program Pengembangan</span></div>
	<form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%" style="margin-bottom: 20px">
    	<tr>
    		<td style="vertical-align: top;">
    			<table class="gradient-style" id="tableKandidat" style="width:100%; margin-left:2px">
    				<thead>
	    				<tr>
	    					<th style="width: 10%; text-align: center;">Jenis Kompetensi</th>
	    					<th style="width: 10%;text-align: center;">Gap</th>
	    					<th style="width: 20%;text-align: center;">Jenis pengembangan</th>
	    					<th style="width: 20%;text-align: center;">Rumpun Pengembangan</th>
	    					<th style="width: 10%;text-align: center;">JP</th>
	    					<th style="width: 10%;text-align: center;">Tahun</th>
	    					<th style="width: 20%;text-align: center;">Analisa</th>
	    				</tr>
    				</thead>
    				<tbody>
    					<?
    					// $sum = 0;
    					$caspekid= "";
    					for($index_loop=0;$index_loop < $jumlahatribut;$index_loop++)
    					{
    						$infoid= $arrAtribut[$index_loop]["ATRIBUT_ID"];
    						$infonama= $arrAtribut[$index_loop]["ATRIBUT_NAMA"];
    						$infopelatihanid= $arrAtribut[$index_loop]["PELATIHAN_ID"];
    						$infopelatihannama= $arrAtribut[$index_loop]["PELATIHAN_NAMA"];
    						$informpelatihanid= $arrAtribut[$index_loop]["RM_PELATIHAN_ID"];
    						$informpelatihannama= $arrAtribut[$index_loop]["RM_PELATIHAN_NAMA"];
    						$infojp= $arrAtribut[$index_loop]["JP"];
    						$infotahunnew= $arrAtribut[$index_loop]["TAHUN"];
    						$infoketerangan= $arrAtribut[$index_loop]["KETERANGAN"];

    						$vaspekid= $arrAtribut[$index_loop]["ASPEK_ID"];
    						$vaspeknama= $arrAtribut[$index_loop]["ASPEK_NAMA"];
    					?>

    					<?
    					if($caspekid !== $vaspekid)
    					{
    					?>
    					<tr>
    						<th colspan="7"><?=$vaspeknama?></th>
    					</tr>
    					<?
    					}
    					$caspekid= $vaspekid;
    					?>
    					<tr >
    						<td><?=$arrAtribut[$index_loop]["ATRIBUT_NAMA"]?></td>
    						<td style="text-align: center;"><?=$arrAtribut[$index_loop]["GAP"]?></td>
    						<td>
    							<input id="reqPelatihanHcdpJsonId<?=$infoid?>" class="easyui-combotree" data-options="
			                    onLoadSuccess: function (row, data) {
			                        $('#reqPelatihanHcdpJsonId<?=$infoid?>').combotree('tree').tree('expandAll');
			                    },
			                    onCheck: function(node, checked){
				                	clickNode('reqPelatihanHcdpJsonId<?=$infoid?>');
				                },
			                    onClick: function(node){
			                        clickNode('reqPelatihanHcdpJsonId<?=$infoid?>');
			                    }, checkbox:true, cascadeCheck:true" multiple style="width:200px;" <?=$disabled?> />
			                    <input type="hidden" name="reqAtributId[]" value="<?=$infoid?>" />
			                    <input type="hidden" name="reqPelatihanId[]" id="reqPelatihanId<?=$infoid?>" value="<?=$infopelatihanid?>" />
			                    <input type="hidden" name="reqPelatihanNama[]" id="reqPelatihanNama<?=$infoid?>" value="<?=$infopelatihannama?>" />
    						</td>
    						<td>
    							<input id="reqRumpunPengembanganJsonId<?=$infoid?>" class="easyui-combotree" data-options="
			                    onLoadSuccess: function (row, data) {
			                        $('#reqRumpunPengembanganJsonId<?=$infoid?>').combotree('tree').tree('expandAll');
			                    },
			                    onCheck: function(node, checked){
				                	clickNodeRumpun('reqRumpunPengembanganJsonId<?=$infoid?>');
				                },
			                    onClick: function(node){
			                        clickNodeRumpun('reqRumpunPengembanganJsonId<?=$infoid?>');
			                    }, checkbox:true, cascadeCheck:true" multiple style="width:200px;" <?=$disabled?> />
			                    <input type="hidden" name="reqRumpunPengembanganAtributId[]" value="<?=$infoid?>" />
			                    <input type="hidden" name="reqRumpunPengembanganId[]" id="reqRumpunPengembanganId<?=$infoid?>" value="<?=$informpelatihanid?>" />
			                    <input type="hidden" name="reqRumpunPengembanganNama[]" id="reqRumpunPengembanganNama<?=$infoid?>" value="<?=$informpelatihannama?>" />
    						</td>
    						<td style="text-align: center;"><input type="text" style="width: 50%; text-align: right;"  name="reqJp[]" onkeyup="findTotal()" id="reqJp<?=$infoid?>" class="numeric" <?=$disabled?>  value="<?=$infojp?>" /></td>
    						<td style="text-align: center;"><input type="text" style="width: 50%;  text-align: right;" maxlength="4" class="numeric"  name="reqTahun[]" id="reqTahun<?=$infoid?>" <?=$disabled?>  value="<?=$infotahunnew?>" /></td>
    						<td style="text-align: center;"><input type="text" style="" name="reqKeterangan[]" id="reqKeterangan<?=$infoid?>" <?=$disabled?>  value="<?=$infoketerangan?>" /></td>
    					</tr>
    					<?
 						// $sum=$sum+$infojp;       					
    					}
    					?>
    					<tr>
    						<td colspan="4" style="text-align:center;">Jumlah JP</td>
    						<td style="text-align: center;"><input type="text" name="reqJumlahJp" id="reqJumlahJp" readonly="readonly" style="width: 50%; text-align: right;background-color : #dad8d8; " <?=$disabled?> value="<?=$reqJumlahJp?>" /></td>

    						<td></td>
    						<td></td>
    						
    					</tr>
    					<!-- <tr>
    						<td></td>
    						<td></td>
    						<td>Tahun</td>
    						<td style="text-align: center;"><?=$infotahun?></td>
    						<td></td>
    					</tr> -->
    				</tbody>
    			</table>
    		</td>
    	</tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqPegawaiId" value="<?=$reqId?>" />
                <input type="hidden" name="reqFormulaId" value="<?=$reqFormulaId?>" />
		        <?
		        if ($readonly == 1)
		        {}
		    	else
		    	{
		        ?> 
                <input type="submit" name="" value="Simpan" />
                <?
            	}
            	?>
            </td>
        </tr>
    </table>
	</form>
    </div>

</div>
</body>
</html>
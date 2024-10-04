<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/FormulaAssesment.php");
include_once("../WEB/classes/base/FormulaAssesmentUjianTahap.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");

if($reqId == "")
{
	echo '<script language="javascript">';
	echo "alert('isi data data terlebih dahulu.');";	
	echo "window.parent.location.href = 'formula_assesment_add.php?reqId=".$reqId."&reqMode=".$reqMode."';";
	echo '</script>';
}

$ujian_tahap= new FormulaAssesmentUjianTahap();
$cek_urutan= new FormulaAssesmentUjianTahap();
$tipe_ujian= new TipeUjian();
// $ujian_pegawai= new UjianPegawai();

$statement= " AND A.FORMULA_ID = ".$reqId;
$set= new FormulaAssesment();
$set->selectByParams(array("FORMULA_ID"=> $reqId),-1,-1,'');
$set->firstRow();
$tempFormula= $set->getField('FORMULA');
$tempFormulaTahun= $set->getField('TAHUN');
$tempFormulaKeterangan= $set->getField('KETERANGAN');
unset($set);

$index_data= 0;
$arrData = array();
$statement= " AND A.FORMULA_ASSESMENT_ID = ".$reqId;
$cek_urutan->selectByParamsMonitoring(array(), -1,-1, $statement);
$cek_urutan->firstRow();
if ($cek_urutan->getField("URUTAN_TES")=='')
{
$ujian_tahap->selectByParamsMonitoring(array(), -1,-1, $statement, "ORDER BY b.id ASC");
}
else{
$ujian_tahap->selectByParamsMonitoring(array(), -1,-1, $statement, "ORDER BY URUTAN_TES ASC");
}
// echo $ujian_tahap->query;exit;
while($ujian_tahap->nextRow())
{	
	$arrData[$index_data]["UJIAN_ID"]= $ujian_tahap->getField("UJIAN_ID");
	$arrData[$index_data]["TIPE_UJIAN_ID"]= $ujian_tahap->getField("TIPE_UJIAN_ID");
	$arrData[$index_data]["TIPE"]= $ujian_tahap->getField("TIPE");
	$arrData[$index_data]["UJIAN_TAHAP_ID"]= $ujian_tahap->getField("UJIAN_TAHAP_ID");
	$arrData[$index_data]["JUMLAH_SOAL_UJIAN_TAHAP"]= $ujian_tahap->getField("JUMLAH_SOAL_UJIAN_TAHAP");
	$arrData[$index_data]["BOBOT"]= $ujian_tahap->getField("BOBOT");
	$arrData[$index_data]["MENIT_SOAL"]= $ujian_tahap->getField("MENIT_SOAL");
	$arrData[$index_data]["JUMLAH_SOAL"]= $ujian_tahap->getField("JUMLAH_SOAL");
	$arrData[$index_data]["ID"]= $ujian_tahap->getField("ID");
	$arrData[$index_data]["PARENT_ID"]= $ujian_tahap->getField("PARENT_ID");
	$arrData[$index_data]["TIPE_READONLY"]= $ujian_tahap->getField("TIPE_READONLY");
	$arrData[$index_data]["STATUS_ANAK"]= $ujian_tahap->getField("STATUS_ANAK");
	$arrData[$index_data]["URUTAN_TES"]= $ujian_tahap->getField("URUTAN_TES");
	$index_data++;
}
$jumlah_data = $index_data;
// print_r($arrData);exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="../WEB/css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<!-- <link href="styles.css" rel="stylesheet" type="text/css" /> -->

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">
$(function(){
	$('#ff').form({
		url:'../json-pengaturan/formula_assesment_add_ujian_tahap_urutan.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			$.messager.alert('Info', data, 'info');
			// $('#rst_form').click();
			document.location.href= "formula_assesment_add_ujian_tahap_urutan.php?reqId=<?=$reqId?>";
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
            <td colspan="3">
            <div id="header-tna-detil">Urutan <span>Soal</span></div>
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
        <tr>
            <td>
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="insert">
                <input type="hidden" name="reqTahun" value="<?=$tempFormulaTahun?>">
                <input type="submit" value="Simpan" />
            </td>
        </tr>
    </table>
    
    <table class="gradient-style" id="tableOrder" style="width:99%; margin-left:2px">
        <thead class="altrowstable">
          <tr>
              <th style="width:20%">Tipe Ujian</th>
              <th style="text-align: center; width:90%">Urutan Soal</th>
          </tr>
        </thead>
        <tbody class="example altrowstable" id="alternatecolor"> 
          <?
		  $i = 1;
          $unit = 0;
		  $harga = 0;
		  $jumlah = 0;		  
          for($checkbox_index=0;$checkbox_index<$jumlah_data;$checkbox_index++)
          {
			  $tempId= $arrData[$checkbox_index]["ID"];
			  $tempParentId= $arrData[$checkbox_index]["PARENT_ID"];
			  $tempTipeUjianId= $arrData[$checkbox_index]["TIPE_UJIAN_ID"];
			  $tempTipeReadOnly= $arrData[$checkbox_index]["TIPE_READONLY"];
			  $tempStatusAnak= $arrData[$checkbox_index]["STATUS_ANAK"];
			  
			  $tempStyle= "text-align:center;";
			  if($tempParentId == "0")
			  {
				  $tempStyle= "text-align:left;";
			  }
          ?>                      
              <tr id="node-<?=$i?>">
                  <td style=" <?=$tempStyle?>"><?=$arrData[$checkbox_index]["TIPE"]?></td>
                  <td style="text-align:center">
	                  <input type="text" required name="reqUrutanTes[]" style="width:30px;" class="easyui-validatebox" id="reqUrutanTes<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["URUTAN_TES"]?>" />
	                  <input type="hidden" name="reqRowId[]" id="reqRowId<?=$checkbox_index?>" value="<?=$arrData[$checkbox_index]["UJIAN_TAHAP_ID"]?>">
                  </td>
              </tr>
		  <?		  
            $i++;
          }
		  ?>         
        </tbody>
    </table>
    <script>
	$("input[id^='reqUrutanTes']").keypress(function(e) {
		if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	</script>
    </form>
    </div>
</div>
</body>
</html>
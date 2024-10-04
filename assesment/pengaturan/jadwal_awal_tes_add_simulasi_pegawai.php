<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalAwalTes.php");
include_once("../WEB/classes/base/JadwalAwalTesPegawai.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasi.php");
include_once("../WEB/classes/base/JadwalAwalTesSimulasiPegawai.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}


/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");
$reqCariFilter= httpFilterGet("reqCariFilter");
// echo $reqCariFilter; exit;

$setdetil= new JadwalAwalTesSimulasiPegawai();
$sOrder='order by a.no_urut';
$reqsearch= " AND (UPPER(A1.NAMA) LIKE UPPER('%".$reqCariFilter."%') OR UPPER(A1.NIP_BARU) LIKE '%".$reqCariFilter."%') ";
$statement2= " AND A.JADWAL_AWAL_TES_ID = ".$reqId." AND A.JADWAL_AWAL_TES_SIMULASI_ID = ".$reqRowId;
$setdetil->selectByParamsPegawai(array(), -1, -1, $statement2.$reqsearch, $sOrder, $reqRowId);
$jumlah_data=0;
// echo $setdetil->query; exit;
while($setdetil->nextRow()){
	$reqPegawaiId[]= $setdetil->getField('PEGAWAI_ID');
	$reqPegawaiNama[]= $setdetil->getField('PEGAWAI_NAMA');
	$reqPegawaiNip[]= $setdetil->getField('PEGAWAI_NIP');
	$reqPegawaiGol[]= $setdetil->getField('PEGAWAI_GOL');
	$reqPegawaiEselon[]= $setdetil->getField('PEGAWAI_ESELON');
	$reqPegawaiJabatan[]= $setdetil->getField('PEGAWAI_JAB_STRUKTURAL');
	$reqPegawaiSatker[]= $setdetil->getField('SATKER');
	$reqPegawaiUrut[]= $setdetil->getField('NO_URUT');
	$reqPegawaiNoDelete[]= $setdetil->getField('jumlah_data');
	$jumlah_data++;
}
// print_r($reqPegawaiNama); exit;

$set= new JadwalAwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_AWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempTanggalTesAkhir= getFormattedDateTime($set->getField('TANGGAL_TES_AKHIR'), false);
// $tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), 'date');
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
$reqStatusValid= $set->getField('STATUS_VALID');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$tempStatusValidasi= $reqStatusValid;

$set= new JadwalAwalTesSimulasi();
$set->selectByParams(array("A.JADWAL_AWAL_TES_SIMULASI_ID"=> $reqRowId),-1,-1);
$set->firstRow();
// echo $set->query;exit;
$reqBatasPegawai= $set->getField("BATAS_PEGAWAI");
$tempTanggalTes= getFormattedDateTime($set->getField('TANGGAL_TES'), false);

$setdetil= new JadwalAwalTes();
$setdetil->selectByParams(array(), -1,-1, " AND JADWAL_AWAL_TES_ID = ".$reqId);
$setdetil->firstRow();
$reqStatusJenis= $setdetil->getField("STATUS_JENIS");




?>
<style>
#customers {
  font-family:'Open SansRegular';
  border-collapse: collapse;
  width: 100%;
  font-size: 9pt;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #e6e6e6;
  /*color: white;*/
}
</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

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

<style type="text/css" media="screen">
    @import "../WEB/lib/media/css/site_jui.css";
    @import "../WEB/lib/media/css/demo_table_jui.css";
    @import "../WEB/lib/media/css/themes/base/jquery-ui.css";
	
    /*
     * Override styles needed due to the mix of three different CSS sources! For proper examples
     * please see the themes example in the 'Examples' section of this site
     */
    .dataTables_info { padding-top: 0; }
    .dataTables_paginate { padding-top: 0; }
    .css_right { float: right; }
    #example_wrapper .fg-toolbar { font-size: 12px; }
    #theme_links span { float: left; padding: 2px 10px; }
	/*.transactionDebit { background-color:#6CF; }*/
	.hukumanStyle { background-color:#FC7370; }
</style>

<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/extensions/Responsive/css/dataTables.responsive.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="../WEB/lib/DataTables-1.10.6/examples/resources/demo.css">

<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/examples/resources/demo.js"></script>	
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>	
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>	
<script type="text/javascript" language="javascript" src="../WEB/lib/DataTables-1.10.6/extensions/Scroller/js/dataTables.scroller.min.js"></script>	

<script type="text/javascript" language="javascript" class="init">

  function btnHapusData(reqPegawaiId){
	  	$.messager.confirm('Konfirmasi',"Apakah anda yakin hapus, data terpilih?",function(r){
	  		if (r)
	  		{
	  			$.getJSON("../json-pengaturan/jadwal_awal_tes_add_simulasi_pegawai_hapus.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqPegawaiId="+reqPegawaiId,
  				function(data){
  					// setCariInfo();
				document.location.href = 'jadwal_awal_tes_add_simulasi_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>';

  				});
	  		}
	  	});
  }

	function btnTambahData(){
	  		parent.OpenDHTML('pegawai_jadwal_simulasi_monitoring_pencarian.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>', 'Pencarian Pegawai', 780, 500);
	}
	function setCariInfo(){
	  		document.location.href = 'jadwal_awal_tes_add_simulasi_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqCariFilter=<?=$reqCariFilter?>';
	}

  $(function(){
		$('#ff').form({
			url:'../json-pengaturan/jadwal_awal_tes_add_simulasi_pegawai.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// alert(data);return false;
				data = data.split("-");
				rowid= data[0];
				infodata= data[1];

				$.messager.alert('Info', infodata, 'info');
				if(rowid == "xxx")
				{
					return false;
				}
				else
				{
					// $('#rst_form').click();
					//parent.setShowHideMenu(3);
					document.location.href = 'jadwal_awal_tes_add_simulasi_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqCariFilter=<?=$reqCariFilter?>';
				}
			}
		});
	});

	$(function(){
			$('#fff').form({
				url:'../json-pengaturan/tambah_no_urut.php',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					// alert(data);return false;
					data = data.split("-");
					rowid= data[0];
					infodata= data[1];

					$.messager.alert('Info', infodata, 'info');
					if(rowid == "xxx")
					{
						return false;
					}
					else
					{
						// $('#rst_form').click();
						//parent.setShowHideMenu(3);
						document.location.href = 'jadwal_awal_tes_add_simulasi_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqCariFilter=<?=$reqCariFilter?>';
					}
				}
			});
		});

  $(function(){
		$('#search-form').form({
			onSubmit:function(){
			  	reqCariFilter= $("#reqCariFilter").val();

					document.location.href = 'jadwal_awal_tes_add_simulasi_pegawai.php?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqCariFilter='+reqCariFilter;
			}
		});
	});

</script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />

<style>
#example td:nth-child(4) {
    text-align : center;
    *font-weight: bold;
	*color:#F00 !important
}
</style>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; width:100%">
	<div id="header-tna-detil">Simulasi <span>Pegawai</span></div>
	<form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="100%">
        <tr>
            <td width="200px">Formula</td>
            <td width="2px">:</td>
            <td><label id="reqFormulaEselon"><?=$tempFormulaEselon?></label></td>
        </tr>
        <tr>
            <td>Tanggal Tes</td>
            <td>:</td>
            <td>
            	<?=$tempTanggalTes?>
            </td>
        </tr>
        <tr>
            <td>Acara</td>
            <td>:</td>
            <td><?=$tempAcara?></td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td><?=$tempTempat?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?=$tempAlamat?></td>
        </tr>
        <!-- <tr>
            <td>Total Peserta</td>
            <td>:</td>
            <td>
            	<label id="reqInfoTotalPeserta"><?=$index_loop?></label>
            </td>
        </tr> -->
        <tr>
            <td>Batas Peserta</td>
            <td>:</td>
            <td>
            	<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" />
            	<input name="reqBatasPegawai" id="reqBatasPegawai" class="easyui-validatebox" required style="width:85px" type="text" value="<?=$reqBatasPegawai?>" />
            </td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
            <td>
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqMode" value="insert" />
                <?
				if($tempStatusValidasi == "1"){}
				else
				{
				?>
                <input type="submit" name="" value="Simpan" />
                <?
				}
                ?>
            </td>
        </tr>
        <?
		}
        ?>
    </table>
	</form>
    </div>

		<form id="search-form" method="post" novalidate>

	    <div style="position: relative; float:right; z-index:9999; font-size:12px; margin-top: -35px;padding-right: 100px;">
    		<table style="margin-top: 10px;">
    			<tr>
    				<td>Pencarian </td>
    				<td><input type="text" id="reqCariFilter" style="width:150%" value="<?=$reqCariFilter?>" /></td>
    			</tr>
    		</table>
    	</div>
    </form>
    <form id="fff" method="post" novalidate>

	    <div style="background-color: #d4d4d4; height: 30px" class="bluetabs">
        <li><a style="background: inherit !important;" href="#" title="Tambah" id="btnTambahData" onclick="btnTambahData()"><img src="../WEB/images/icn_add.gif" style="width: 10px" />&nbsp;Tambah</a></li>
        <li><input type="submit" name="" value="Simpan No Urut" /></li>
	    </div>
    	
		    <input name="reqRowId" type="hidden" value="<?=$reqId?>" />


		    <table id='customers' width="100%">
		    	<tr>
		    		<th>No Urut</th>
		    		<th>Nama Pegawai</th>
		    		<th>NIP</th>
		    		<th>Gol Ruang</th>
		    		<th>Eselon</th>
		    		<th>Jabatan</th>
		    		<th>Satker</th>
		    		<th>Hapus</th>
		    	</tr>
		    	<?
		    	if ($jumlah_data==0){?>
		    		<tr><td colspan="8"><center>Data Tidak Ada</center></td></tr>
		    	<?}
		    	else{
			    	for($i=0;$i<$jumlah_data;$i++){?>
			    		<tr>
			    			<td>
			    				<input name="reqPegawaiId[]" type="hidden" value="<?=$reqPegawaiId[$i]?>" />
			    				<input name="reqPegawaiUrut[]" class="easyui-validatebox" style="width:85px" type="text" value="<?=$reqPegawaiUrut[$i]?>" /></td>
			    			<td><?=$reqPegawaiNama[$i]?></td>
			    			<td><?=$reqPegawaiNip[$i]?></td>
			    			<td><?=$reqPegawaiGol[$i]?></td>
			    			<td><?=$reqPegawaiEselon[$i]?></td>
			    			<td><?=$reqPegawaiJabatan[$i]?></td>
			    			<td><?=$reqPegawaiSatker[$i]?></td>
			    			<td>
			    				<!-- <?if ($reqPegawaiNoDelete[$i]=='0'){?> -->
			    				<a style="background: inherit !important;" onclick="btnHapusData(<?=$reqPegawaiId[$i]?>)" title="Hapus" id="btnHapusData"><img src="../WEB/images/delete-icon.png" style="width: 10px" /></td>
			    					<!-- <?}?> -->
			    		</tr>
			    	<?}
			    }?>
		    </table>
		  </div>
		</form>
<script>
//$('input[id^="reqAnakNama"]').keyup(function(e) {
$('#reqBatasPegawai').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>
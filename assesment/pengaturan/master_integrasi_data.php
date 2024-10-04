<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base/IntegrasiBaru.php");

$set= new IntegrasiBaru();

$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

	ini_set("memory_limit","500M");
	ini_set('max_execution_time', 520);	
	
/*if($reqId == "")
	$reqId= $userLogin->userSatkerId;*/

$arrTahun= setTahunLoop(5,1);
//$reqTahun= date("Y");

if($reqTahun == "")
	$reqTahun= 2015;

$lengthSatkerId= strlen($userLogin->userSatkerId);
$tinggi = 213;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title></title>
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
	function trim(str)
	{
		if(!str || typeof str != 'string')
			return null;
	
		return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
	}

    $(document).ready( function () {		
	 var table = $('#example').DataTable({
	 		"pageLength": 5,
	 		"lengthMenu": [5, 20, 50, 100], 
	        select: false,
	        "columnDefs": [{

	            className: "Name", 
	            "visible": false,
	            "searchable":false
	        }]
	    });//End of create main table
							
	} );
</script>

<!--RIGHT CLICK EVENT-->		
<style>
	.vmenu{
	border:1px solid #aaa;
	position:absolute;
	background:#fff;
	display:none;font-size:0.75em;}
	.first_li{}
	.first_li span{width:100px;display:block;padding:5px 10px;cursor:pointer}
	.inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	.sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	.fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}

	.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody{
		height: 63vh !important;
	}

</style>
<!--RIGHT CLICK EVENT-->		
<!--<link href="themes/main_datatables.css" rel="stylesheet" type="text/css" /> -->

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body id="index" class="grid_2_3" style="overflow:hidden">
    <div class="full_width" style="width:100%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    	<?if($reqMode=='riwayat_pendidikan'){?>
           <div id="header-tna">Riwayat <span>Pendidikan</span></div>
        <?}
        else if($reqMode=='riwayat_hukdis'){?>
           <div id="header-tna">Riwayat <span>Hukdis</span></div>
        <?}
        else if($reqMode=='riwayat_skp'){?>
           <div id="header-tna">Riwayat <span>SKP</span></div>
        <?}
        else if($reqMode=='riwayat_penghargaan'){?>
           <div id="header-tna">Riwayat <span>Penghargaan</span></div>
        <?}?>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">    
    </div>
      
    <!--2nd drop down menu -->
    <div id="dropmenu2_b" class="dropmenudiv_b" style="width: 250px;">
    <a href="#" title="FIP 01" id="btnLembarFIP01Row">FIP 01</a>
    </div>
    
    <script type="text/javascript">
    //SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
    tabdropdown.init("bluemenu")
    </script>
    
    </div>  
    
    <div id="bar-status">
    </div>
    <div id="rightclickarea"> <!--RIGHT CLICK EVENT -->
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
    	<?if($reqMode=='riwayat_pendidikan'){?>
	        <tr>
	            <th width="">NIP</th>
	            <th width="">Nama</th>
	            <th width="">Pendidikan</th>
	        </tr>
        <?}
        else if($reqMode=='riwayat_hukdis'){?>
	        <tr>
	            <th width="">NIP</th>
	            <th width="">Nama</th>
	            <th width="">Tahun</th>
	        </tr>
        <?}
        else if($reqMode=='riwayat_skp'){?>
	        <tr>
	            <th width="">Tahun</th>
	            <th width="">Nilai Pengukuran</th>
	            <th width="">Rata Perilaku</th>
	            <th width="">Nilai SKP</th>
	            <th width="">Nilai Kinerja</th>
	            <th width="">Hasil Karya</th>
	            <th width="">Perilaku Kerja</th>
	            <th width="">Kinerja Pegawai</th>
	            <th width="">File SKP</th>
	            <th width="">Penilai</th>
	            <th width="">Atasan Penilai</th>
	        </tr>
        <?}
        else if($reqMode=='riwayat_penghargaan'){?>
	        <tr>
	            <th width="">Nama Penghargaan</th>
	            <th width="">Tanggal</th>
	            <th width="">File Penghargaan</th>
	        </tr>
        <?}?>
    </thead>
    <tbody>
    	<?if($reqMode=='riwayat_pendidikan'){
			$set->selectByParamsRiwayatPendidikan(array(), -1,-1, "and a.pegawai_id=".$reqId);
			// echo $set->query;exit;
			while($set->nextRow()){
    		?>
		    <tr>
		        <td><?=$set->getField("nip_pegawai")?></td>
		        <td><?=$set->getField("nama_pegawai")?></td>
		        <td><?=$set->getField("pendidikan_nama")?></td>
		    </tr>
		    <?}
		}
	 	else if($reqMode=='riwayat_hukdis'){
	 		$set->selectByParamsRiwayatHukdis(array(), -1,-1, "and a.pegawai_id=".$reqId);
			// echo $set->query;exit;
			while($set->nextRow()){
    		?>
		    <tr>
		        <td><?=$set->getField("nip_pegawai")?></td>
		        <td><?=$set->getField("nama_pegawai")?></td>
		        <td><?=$set->getField("tahun")?></td>
		    </tr>
		    <?}
	    }
	    else if($reqMode=='riwayat_skp'){
	 		$set->selectByParamsRiwayatSKP(array(), -1,-1, "and a.pegawai_id=".$reqId);
			// echo $set->query;exit;
			while($set->nextRow()){
    		?>
		    <tr>
		        <td><?=$set->getField("tahun")?></td>
		        <td><?=$set->getField("nilai_pengukuran")?></td>
		        <td><?=$set->getField("rata_perilaku")?></td>
		        <td><?=$set->getField("nilai_skp")?></td>
		        <td><?=$set->getField("nilai_kinerja")?></td>
		        <td><?=$set->getField("hasil_karya")?></td>
		        <td><?=$set->getField("perilaku_kerja")?></td>
		        <td><?=$set->getField("kinerja_pegawai")?></td>
				<?if($set->getField("stataus_file")=='ada'){?>
			        <td><a href=" <?=$set->getField("file_skp")?> " target="_blank"> File </td>
			    <?}
			    else{?>
			        <td></td>
			    <?}?>
   		        <td><?=$set->getField("penilai")?><br> (<?=$set->getField("nip_penilai")?>)</td>
		        <td><?=$set->getField("atasan_penilai")?><br> (<?=$set->getField("nip_atasan_penilai")?>)</td>
		    </tr>
		    <?}
		}
   	    else if($reqMode=='riwayat_penghargaan'){
	 		$set->selectByParamsRiwayatPenghargaan(array(), -1,-1, "and a.pegawai_id=".$reqId);
			// echo $set->query;exit;
			while($set->nextRow()){
    		?>
		    <tr>
		        <td><?=$set->getField("nama_penghargaan")?></td>
		        <td><?=$set->getField("TANGGAL")?></td>
		        <?if($set->getField("stataus_file")=='ada'){?>
			        <td><a href=" <?=$set->getField("file_penghargaan")?> " target="_blank"> File </td>
			    <?}
			    else{?>
			        <td></td>
			    <?}?>
		    </tr>
		    <?}
	    }?>

	</tbody>
    </table>
    </div> <!--RIGHT CLICK EVENT -->
    
    
    <div class="vmenu">
        <div class="first_li"><span>Detail Data</span></div>
    </div>
</body>
</html>
<style type="text/css">
	#rightclickarea{
		margin-top: 10px;
  padding: 0px 10px;
	}
</style>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base/JadwalAsesorPotensiPegawai.php");
include_once("../WEB/classes/base/Rekap.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin())
{
	$userLogin->retrieveUserInfoKhusus($reqId);
}

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$reqId= httpFilterGet("reqId");
$reqCari= httpFilterGet("reqCari");
$reqLowonganId= $reqId;
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$statement= " AND A.JADWAL_TES_ID = ".$reqId."and  cast(d.id as varchar) not in (select parent_id from cat.tipe_ujian)";
$set= new JadwalTesSimulasiAsesor();
$set->selectByParamsMonitoringtipecek(array(),-1,-1,$reqId);
// echo $set->query;exit;
$arrData= array("No.","NIP", "Nama");
$index_data=3;
while($set->nextRow())
{
	$arrData[$index_data]=$set->getField("tipe");
	$arrDataparent[$index_data]=$set->getField("tipeparent");
	$arrUjianId[]=$set->getField("TIPE_UJIAN_ID");
	$index_data++;
}
// print_r($arrUjianId); exit;
$jumlah_data = $index_data+3;
unset($set);
$tinggi = 213;

// $arrData= array("ID", "No Peserta", "NIP", "Nama", "Jumlah Soal", "Jumlah Benar", "Nilai Hasil");
// echo $arrData; exit;

// print_r($arrData);exit();
// $tinggi = 213;

// $tinggi = 179;
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
	.checkstyle { background-color:#F9C; }
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

<!-- <script type="text/javascript" src="../WEB/lib/window/js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="../WEB/lib/window/js/jquery/window/jquery.window.js"></script>
 -->


<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<!-- Flex Menu -->
<link rel="stylesheet" type="text/css" href="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.css" />
<script type="text/javascript" src="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/jquery.min.js"></script>
<script type="text/javascript" src="../WEB/lib/Flex-Level-Drop-Down-Menu-v1.3/flexdropdown.js"></script>


<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>
</head>

<body id="index" class="grid_2_3" style="overflow:scroll;">
    <div class="full_width" style="width:150%;">
    <form id="formAddNewRow" action="#" title="Add a new browser" style="width:600px;min-width:600px">
    </form>
    <div id="header-tna">
    	Progress <span>Peserta</span>
    	<input type="text" name="reqCari" id="reqCari" style="float: right; height: 25px;width: 300px" placeholder="cari disini...">
    </div>
    <div id="bluemenu" class="bluetabs" style="background:url(css/media/bluetab.gif)">
    <input type="hidden" id="reqPegawaiId" name="reqPegawaiId" />
      <table cellpadding="0" cellspacing="0" border="0" id="example" width="100%">
    <thead style="background-color: #e6e6e6;">
	    <tr style="border: solid gray 1px;">
	        <?
	        for($i=0; $i < count($arrData); $i++)
	        {
	            $width= "10";
	            if($i == 0)
	                $width= "100";
	            elseif($i == 1)
	                $width= "500";
	                // $width= "250";



	        	if($i<=2){
	        	?>
	            <th style="border: solid gray 1px; width:<?=$width?> ;" rowspan="2"><center><?=$arrData[$i]?></center></th>
	        	<?
	    		}
	    		else{
	    		?>
	            <th style="border: solid gray 1px;" colspan="2"><center><?=$arrDataparent[$i]?> <?=$arrData[$i]?></center></th>
	       		<?
	    		}
	        }
	        ?>
	    </tr>
	    <tr>
	    	<?
	        for($i=0; $i < count($arrData); $i++)
	        {
	        	if($i>2){
	        	?>
	            <th style="border: solid gray 1px;"><center>Soal</center></th>
	            <th style="border: solid gray 1px;"><center>Terjawab</center></th>
	       		<?
	    		}
	        }
	        ?>
	    </tr>
    </thead>
    <tbody>
    	<?
    	$statementPegawai= " AND EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT PEGAWAI_ID
				FROM jadwal_asesor_potensi_pegawai
				WHERE JADWAL_TES_ID = ".$reqId."
			) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID
		)";
		if ($reqCari)
		{
			$statementPegawai.= " AND (UPPER(A.NAMA) LIKE UPPER('%".$reqCari."%') OR UPPER(A.NIP_BARU) LIKE UPPER('%".$reqCari."%'))";
		}
		$setPegawai = new JadwalTesSimulasiAsesor();
		$statementPegawai.= "and jadwal_awal_tes_simulasi_id = ".$reqId." group by A.PEGAWAI_ID, A.NIP_BARU, A.NAMA, A.EMAIL,chk.jumlah_data,jp.no_urut";
		$setPegawai->selectByParamsCari(array(), $dsplyRange, $dsplyStart, $reqId, $statementPegawai);
		// echo $setPegawai->query;exit;
		while($setPegawai->nextRow()){
			$NamaPegawai=$setPegawai->getField("NAMA");
			$NipPegawai=$setPegawai->getField("NIP_BARU");
			$IdPegawai=$setPegawai->getField("PEGAWAI_ID");
			$no_urut=$setPegawai->getField("no_urut");
			?>
			<tr>
				<td><?=$no_urut?></td>
				<td><?=$NipPegawai?></td>
				<td><?=$NamaPegawai?></td>
				<?
				for($i=0; $i<=count($arrUjianId); $i++){
				// print_r($arrUjianId); exit;
				if($arrUjianId[$i] == '78'||$arrUjianId[$i] == '86'){
					$setUjian = new JadwalTesSimulasiAsesor();
					$statementUjian= " AND A.UJIAN_ID = ".$reqId."AND A.Pegawai_ID =".$IdPegawai;
					$setUjian->selectByParamsMonitoringAssesmentMandiri(array(), $dsplyRange, $dsplyStart, $reqLowonganId, $searchJson.$statementUjian, $sOrder);
					// echo $setUjian->query; exit;
					$setUjian->firstRow();
					$JumlahSoal=$setUjian->getField("JUMLAH_SOAL");
					$JumlahJawaban=$setUjian->getField("jawaban_sudah");
					if($JumlahJawaban==''){
						$JumlahJawaban='0';
					}
					if($JumlahJawaban==$JumlahSoal){
						$style='style="background-color:green; color: white"';
					}else{
						$style='style="background-color:red; color: white"';
					}
					// $JumlahJawaban=$JumlahSoal-$JumlahJawabanBelum;
					$setUjian->firstRow();
					?>
					<td <?=$style?>><center><?=$JumlahSoal?></center></td>
					<td <?=$style?>><center><?=$JumlahJawaban?></center></td>
					<?
				}
				else{
					// $statementUjian= " AND A.UJIAN_ID = ".$reqId."AND A.Pegawai_ID =".$IdPegawai;

					$setUjian = new JadwalTesSimulasiAsesor();
					$statementUjian='and Pegawai_ID='.$IdPegawai.' and tipe_ujian_id='.$arrUjianId[$i];
					$setUjian->selectByParamsMonitoringGeneral(array(), $dsplyRange, $dsplyStart, $arrUjianId[$i], $reqId,$statementUjian.$searchJson, $sOrder);
					// echo $setUjian->query;exit;

					$setUjian->firstRow();
					$JumlahSoal=$setUjian->getField("JUMLAH_SOAL");
					$JumlahJawaban=$setUjian->getField("JUMLAH_JAWABAN");
					if($JumlahJawaban==$JumlahSoal){
						$style='style="background-color:green; color: white"';
					}else{
						$style='style="background-color:red; color: white"';
					}
					?>
					<td <?=$style?>><center><?=$JumlahSoal?></center></td>
					<td <?=$style?>><center><?=$JumlahJawaban?></center></td>
					<?}
				}
					?>
			</tr>
			<?
		}
		?>
    </tbody>
    </table>
    </div> <!--RIGHT CLICK EVENT -->

    <script type="text/javascript">
    	var input = document.getElementById("reqCari");
		input.addEventListener("keyup", function(event) {
		  if (event.keyCode === 13) {
		   event.preventDefault();
		   reload();
		  }
		});

		function reload()
		{
			cari = $('#reqCari').val();
			parent.mainFrame.location.href='progress_peserta.php?reqId=<?=$reqId?>&reqCari='+cari;
		}
    </script>
</body>
</html>

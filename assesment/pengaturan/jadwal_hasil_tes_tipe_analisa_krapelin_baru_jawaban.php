<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/RekapSehat.php");

$reqId= httpFilterGet("reqId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqMode= httpFilterGet("reqMode");
$reqNo= httpFilterGet("reqNo");

if(empty($reqNo))
	$reqNo= 1;
// $statement_detil= " AND 1=2";

$set= new RekapSehat();
$index_data= 0;
$arrPesertaJawaban="";
$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.X_DATA = ".$reqNo;
$set->selectByParamsBaruKrapelinPesertaJawaban(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrPesertaJawaban[$index_data]["KEY"]= $set->getField("X_DATA")."-".$set->getField("Y_DATA");
	$arrPesertaJawaban[$index_data]["X_DATA"]= $set->getField("X_DATA");
	$arrPesertaJawaban[$index_data]["Y_DATA"]= $set->getField("Y_DATA");
	$arrPesertaJawaban[$index_data]["NILAI"]= $set->getField("NILAI");
	$index_data++;
}
$jumlahpesertajawaban = $index_data;
// print_r($arrPesertaJawaban);exit();

$set= new RekapSehat();
$index_data= 0;
$arrSoal="";
$statement= " AND A.X_DATA = ".$reqNo;
$set->selectByParamsBaruKrapelinSoal(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrSoal[$index_data]["KEY"]= $set->getField("X_DATA")."-".$set->getField("Y_DATA");
	$arrSoal[$index_data]["X_DATA"]= $set->getField("X_DATA");
	$arrSoal[$index_data]["Y_DATA"]= $set->getField("Y_DATA");
	$arrSoal[$index_data]["NILAI"]= $set->getField("NILAI");
	$index_data++;
}
$jumlahsoal = $index_data;
// print_r($arrSoal);exit();

$set= new RekapSehat();
$index_data= 0;
$arrJawaban="";
$statement= " AND A.X_DATA = ".$reqNo;
$set->selectByParamsBaruKrapelinJawaban(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrJawaban[$index_data]["KEY"]= $set->getField("X_DATA")."-".$set->getField("Y_DATA");
	$arrJawaban[$index_data]["X_DATA"]= $set->getField("X_DATA");
	$arrJawaban[$index_data]["Y_DATA"]= $set->getField("Y_DATA");
	$arrJawaban[$index_data]["NILAI"]= $set->getField("NILAI");
	$index_data++;
}
$jumlahjawaban = $index_data;
// print_r($arrJawaban);exit();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Lihat Jawaban - Krapelin</title>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="../WEB/lib/treeTable/doc/stylesheets/master2.css" rel="stylesheet" type="text/css" />
<link href="../WEB/lib/treeTable/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />       

<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>   
<script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script>

<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>    

<!-- SIMPLE TAB -->
<script type="text/javascript" src="../WEB/lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
<link href="../WEB/lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

<script type="text/javascript"> 

$(function(){
  
  $("#reqNo").change(function() {
  	document.location.href= "jadwal_hasil_tes_tipe_analisa_krapelin_baru_jawaban.php?reqMode=<?=$reqMode?>&reqLowonganId=<?=$reqLowonganId?>&reqId=<?=$reqId?>&reqNo="+$(this).val();
  });

});
</script>

<style type="text/css">
#popup-tabel2 table td {
	/*background-color: red;*/
	background-color: #e7f3f3;
	text-align: center;
	/*border: 1px solid !important;*/
}
#popup-tabel2 table td.kosong {
	background-color: white;
	/*border: 1px solid !important;*/
}

#popup-tabel2 table td.garis {
	background-color: red !important;
}
</style>
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">ChecK Jawaban</div>
    <div id="konten">
    <div id="popup-tabel2">    

    <div class="simpleTabs" style="width:100%; margin-left:0px; border:none">

        <div class="simpleTabsContent">
        	<table class="example" style="width:100%; overflow:auto !important; margin-bottom: 10px">
                <tbody class="example altrowstable"> 
                  <tr>
                  		<td style="text-align: left; padding-left: 5px; width: 100px">No Soal</td>
                  		<td style="width: 5px">:</td>
                  		<td style="width: 20px">
                  			<select id="reqNo">
                  				<?
                  				$jmlsoal= 40;
                  				for($nosoal=1; $nosoal <= $jmlsoal; $nosoal++)
                  				{
                  				?>
                  				<option value="<?=$nosoal?>" <? if($nosoal == $reqNo) echo "selected";?>><?=$nosoal?></option>
                  				<?
                  				}
                  				?>
                  			</select>
                  		<td></td>
                  </tr>
                  <!-- <tr>
                  		<td colspan="4" style="text-align: left;">
                  			<button>Lihat Data</button>
                  		</td>
                  </tr> -->
            	</tbody>
            </table>

        	<table class="example" style="width:100%; overflow:auto !important">
                <tbody class="example altrowstable" id="alternatecolor"> 
                  <tr>
                  		<!-- <th>No</th> -->
                  		<th>Soal</th>
                  		<th>Jawaban Peserta</th>
                  		<th>Kunci</th>
                  </tr>
                  <?
                  // $jmlsoal= 45;
                  $jmlsoal= 1;
                  for($soal=0; $soal < $jmlsoal; $soal++)
                  {
                  	// $nosoal= $soal + 1;
                  	$nosoal= $reqNo;

                  	$infosoal= $infojawabanpeserta= $infokuncijawaban= "";

                  	for($y= 1; $y <= 60; $y++)
                  	// for($y= 1; $y <= 10; $y++)
                  	// for($y= 1; $y <= 2; $y++)
                  	{
                  		// ambil data soal
	                  	$set= new RekapSehat();
	                  	$statement= " AND A.X_DATA = ".$nosoal." AND A.Y_DATA = ".$y;
						$set->selectByParamsBaruKrapelinSoal(array(), -1,-1, $statement);
						// echo $set->query;exit;
						$set->firstRow();

						$separator= "";
                		if(!empty($infosoal))
                			$separator= "<br/>";

                		$infosoal.= $separator.$set->getField("NILAI");

	                  	/*$keycari= $nosoal."-".$y;
	                  	$arrayKey= '';
	                	$arrayKey= in_array_column($keycari, "KEY", $arrSoal);
	                	// print_r($arrayKey);exit;
	                	if($arrayKey == ''){}
	                	else
	                	{
	                		$indexkey= $arrayKey[0];

	                		$separator= "";
	                		if(!empty($infosoal))
	                			$separator= "<br/>";

	                		$infosoal.= $separator.$arrSoal[$indexkey]["NILAI"];
	                	}*/

	                	$ynext= $y+1;
	                	$set= new RekapSehat();
	                  	$statement= " AND A.X_DATA = ".$nosoal." AND A.Y_DATA = ".$ynext;
						$set->selectByParamsBaruKrapelinSoal(array(), -1,-1, $statement);
						// echo $set->query;exit;
						$set->firstRow();

						$separator= "";
                		if(!empty($infosoal))
                			$separator= "<br/>";

                		$infosoal.= " + ".$set->getField("NILAI")." = ";

	                	/*$ynext= $y+1;
	                	$keycari= $nosoal."-".$ynext;
	                  	$arrayKey= '';
	                	$arrayKey= in_array_column($keycari, "KEY", $arrSoal);
	                	// print_r($arrayKey);exit;
	                	if($arrayKey == ''){}
	                	else
	                	{
	                		$indexkey= $arrayKey[0];
	                		$infosoal.= " + ".$arrSoal[$indexkey]["NILAI"]." = ";
	                	}*/
	                	// echo $infosoal;exit();

	                	// ambil data jawaban peserta
	                	$set= new RekapSehat();
	                	$statement= " AND A.PEGAWAI_ID = ".$reqId;
	                	$statement.= " AND A.X_DATA = ".$nosoal." AND A.Y_DATA = ".$y;
	                	$set->selectByParamsBaruKrapelinPesertaJawaban(array(), -1,-1, $reqLowonganId, $statement);
	                	// echo $set->query;exit;
						$set->firstRow();
						$infonilai= $set->getField("NILAI");

						if(empty($infonilai))
							$infonilai= "-";

	                	$separator= "";
	                	if(!empty($infojawabanpeserta))
	                		$separator= "<br/>";

	                	$infojawabanpeserta.= $separator.$infonilai;

	                  	/*$keycari= $nosoal."-".$y;
	                  	$arrayKey= '';
	                	$arrayKey= in_array_column($keycari, "KEY", $arrPesertaJawaban);
	                	// print_r($arrayKey);exit;
	                	if($arrayKey == ''){}
	                	else
	                	{
	                		$indexkey= $arrayKey[0];

	                		$separator= "";
	                		if(!empty($infojawabanpeserta))
	                			$separator= "<br/>";

	                		$infojawabanpeserta.= $separator.$arrPesertaJawaban[$indexkey]["NILAI"];
	                	}*/

	                	// ambil data kunci jawaban
	                	$set= new RekapSehat();
						$statement= " AND A.X_DATA = ".$nosoal." AND A.Y_DATA = ".$y;
	                	$set->selectByParamsBaruKrapelinJawaban(array(), -1,-1, $statement);
						// echo $set->query;exit;
						$set->firstRow();

						$separator= "";
                		if(!empty($infokuncijawaban))
                			$separator= "<br/>";

                		$infokuncijawaban.= $separator.$set->getField("NILAI");

	                  	/*$keycari= $nosoal."-".$y;
	                  	$arrayKey= '';
	                	$arrayKey= in_array_column($keycari, "KEY", $arrJawaban);
	                	// print_r($arrayKey);exit;
	                	if($arrayKey == ''){}
	                	else
	                	{
	                		$indexkey= $arrayKey[0];

	                		$separator= "";
	                		if(!empty($infokuncijawaban))
	                			$separator= "<br/>";

	                		$infokuncijawaban.= $separator.$arrJawaban[$indexkey]["NILAI"];
	                	}*/
	                }
                  ?>
                  <tr>
                  	<!-- <td style="vertical-align: top"><?=$nosoal?></td> -->
                  	<td><?=$infosoal?></td>
                  	<td><?=$infojawabanpeserta?></td>
                  	<td><?=$infokuncijawaban?></td>
                  </tr>
                  <?
              	  }
                  ?>
                </tbody>
            </table>
        </div>
        
    </div>
    
    </div>
</div>
</body>
</html>
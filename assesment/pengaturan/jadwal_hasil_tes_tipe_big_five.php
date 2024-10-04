<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/RekapSehat.php");

$reqId= httpFilterGet("reqId");
$reqLowonganId= httpFilterGet("reqLowonganId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");

$statement_detil= " AND 1=2";

 $statement_detil= " AND B.TIPE_UJIAN_ID = 45";
	// $statement_detil= " AND B.TIPE_UJIAN_ID IN (20)"; 
 
function RemoveBS($Str) {  
  $StrArr = str_split($Str); $NewStr = '';
  foreach ($StrArr as $Char) {    
    $CharNo = ord($Char);
    if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £ 
    if ($CharNo > 31 && $CharNo < 127) {
      $NewStr .= $Char;    
    }
  }  
  return $NewStr;
}

$set= new RekapSehat();
$index_data= 0;
$arrData="";
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsSoalBig(array(), -1,-1, $reqLowonganId, $statement);
 // echo $set->query;exit;
while($set->nextRow())
{
	$arrData[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrData[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrData[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrData[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrData[$index_data]["PERTANYAAN"]= RemoveBS($set->getField("PERTANYAAN"));
	// $arrData[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	// $arrData[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	// $arrData[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
	$arrData[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
	$arrData[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
	$arrData[$index_data]["URUT"]= $set->getField("URUT");
	$index_data++;
}
$jumlah_data = $index_data;
// print_r($arrData);exit();
unset($set);

$set= new RekapSehat();
$index_data= 0;
$arrDataJawaban="";
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsJawabanBig(array(), -1,-1, $reqLowonganId, $statement);
 // echo $set->query;exit;
while($set->nextRow())
{
	$arrDataJawaban[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrDataJawaban[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrDataJawaban[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataJawaban[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataJawaban[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
	// $arrDataJawaban[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	// $arrDataJawaban[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	// $arrDataJawaban[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
	$arrDataJawaban[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
	$arrDataJawaban[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
	$arrDataJawaban[$index_data]["URUT"]= $set->getField("URUT");
	$index_data++;
}
$jumlah_jawaban_data = $index_data;
// print_r($arrDataJawaban);exit();
unset($set);

$set= new RekapSehat();
$index_data= 0;
$arrDataJawabanPegawai="";
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsJawabanPegawaiBig(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataJawabanPegawai[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrDataJawabanPegawai[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrDataJawabanPegawai[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataJawabanPegawai[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataJawabanPegawai[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
	// $arrDataJawabanPegawai[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	// $arrDataJawabanPegawai[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	// $arrDataJawabanPegawai[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
	$arrDataJawabanPegawai[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
	$arrDataJawabanPegawai[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
	$arrDataJawabanPegawai[$index_data]["URUT"]= $set->getField("URUT");
	$index_data++;
}
$jumlah_jawaban_pegawai_data = $index_data;
// print_r($arrDataJawabanPegawai);exit();
unset($set);

// $set= new RekapSehat();
// $index_data= 0;
// $arrCheckDataJawabanPegawai="";
// $statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
// $set->selectByParamsCheckJawabanPegawai(array(), -1,-1, $reqLowonganId, $statement);
// // echo $set->query;exit;
// while($set->nextRow())
// {
// 	$arrCheckDataJawabanPegawai[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
// 	$index_data++;
// }
// $jumlah_check_jawaban_pegawai_data = $index_data;
// // print_r($arrCheckDataJawabanPegawai);exit();
// unset($set);

// $set= new RekapSehat();
// $index_data= 0;
// $arrDataSoalBenarJawaban="";
// $statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
// $set->selectByParamsJawabanBenarSoal(array(), -1,-1, $reqLowonganId, $statement);
// // echo $set->query;exit;
// while($set->nextRow())
// {
// 	$arrDataSoalBenarJawaban[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
// 	$arrDataSoalBenarJawaban[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
// 	$arrDataSoalBenarJawaban[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
// 	$arrDataSoalBenarJawaban[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
// 	$arrDataSoalBenarJawaban[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
// 	$arrDataSoalBenarJawaban[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
// 	$arrDataSoalBenarJawaban[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
// 	$arrDataSoalBenarJawaban[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
// 	$arrDataSoalBenarJawaban[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
// 	$arrDataSoalBenarJawaban[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
// 	$arrDataSoalBenarJawaban[$index_data]["URUT"]= $set->getField("URUT");
// 	$index_data++;
// }
// $jumlah_soal_jawaban_benar_data = $index_data;
// // print_r($arrDataJawabanPegawai);exit();
// unset($set);

// $set= new RekapSehat();
// $index_data= 0;
// $arrDataIstJawaban="";
// $statement= " AND A.PEGAWAI_ID = ".$reqId;
// $set->selectByParamsJawabanIstPegawai(array(), -1,-1, $reqLowonganId, $statement);
// // echo $set->query;exit;
// while($set->nextRow())
// {
// 	$arrDataIstJawaban[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
// 	$arrDataIstJawaban[$index_data]["JAWABAN_KETERANGAN"]= $set->getField("KETERANGAN")." (".$set->getField("NILAI").")";
// 	$index_data++;
// }
// $jumlah_jawaban_ist = $index_data;
// // print_r($arrDataIstJawaban);exit();
// unset($set);

// $set= new RekapSehat();
// $index_data= 0;
// $arrDataIstKunciJawaban="";
// $statement= " ";
// $set->selectByParamsJawabanIstKunci(array(), -1,-1, $statement);
// // echo $set->query;exit;
// while($set->nextRow())
// {
// 	$arrDataIstKunciJawaban[$index_data]["ROW_ID"]= $set->getField("BANK_SOAL_ID");
// 	$arrDataIstKunciJawaban[$index_data]["JAWABAN_KETERANGAN"]= $set->getField("KETERANGAN")." (".$set->getField("NILAI").")";
// 	$index_data++;
// }
// $jumlah_jawaban_ist = $index_data;
// // print_r($arrDataIstKunciJawaban);exit();
// unset($set);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Form Validation - jQuery EasyUI Demo</title>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">

<!-- <link rel="stylesheet" type="text/css" href="../WEB/css/gaya-pds.css"> -->
<!-- <link href="../WEB/lib/treeTable/doc/stylesheets/master2.css" rel="stylesheet" type="text/css" /> -->
<link href="../WEB/lib/treeTable/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />       

<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>   
<!-- <script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script> -->
<script type="text/javascript" src="../WEB/lib/easyui/kalender.js"></script>

<!-- SIMPLE TAB -->
<script type="text/javascript" src="../WEB/lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
<link href="../WEB/lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

<style type="text/css">
#popup-tabel2 table td {
	border: 1px solid !important;
}
</style>
</head>

<body class="bg-kanan-full">
	<div id="judul-popup">ChecK Jawaban</div>
    <div id="konten">
    <div id="popup-tabel2">    

    <div class="simpleTabs" style="width:100%; margin-left:-10px; border:none">

        <div class="simpleTabsContent">
        	<?
        	$chektablebaru= $tempCheckValue= "";
        	for($index_data=0; $index_data < $jumlah_data; $index_data++)
            {
            	$reqBankSoalRowId= $arrData[$index_data]["ROW_ID"];
            	$req= $arrData[$index_data]["UJIAN_ID"];
            	$req= $arrData[$index_data]["PEGAWAI_ID"];
            	$reqBankSoalId= $arrData[$index_data]["BANK_SOAL_ID"];
            	$reqBankSoalPertanyaan= $arrData[$index_data]["PERTANYAAN"]; 
            	$reqBankSoalTipeUjianId= $arrData[$index_data]["TIPE_UJIAN_ID"];
            	$reqBankSoalTipeUjianNama= $arrData[$index_data]["TIPE_UJIAN_NAMA"];
            	$req= $arrData[$index_data]["URUT"];

            	if($tempCheckValue == $reqBankSoalTipeUjianId)
            		$chektablebaru= "";
            	else
            		$chektablebaru= "1";
            ?>

            <?
            if($index_data > 0 && $chektablebaru == "1")
            {
            ?>
                </tbody>
            </table>
            <?
        	}
            ?>

            <?
            if($chektablebaru == "1")
            {
            ?>
	            <table class="example" style="width:100%; overflow:auto !important">
	            <tbody class="example altrowstable" id="alternatecolor">                  
	    	  	  <tr>
	                  <th colspan="3" style="text-align: left;"><?=$reqBankSoalTipeUjianNama?></th>
	              </tr>
	              <tr>
	                  <th style="width: 70%">Soal</th>
	                  <th style="width: 30%">Jawaban Peserta</th> 
	              </tr>            	 
            <?
        	}
            ?>
            
            <tr>
            	 
            	<td style="text-align: center;">        		 
        			<label><?=$reqBankSoalPertanyaan?></label> 
            	</td>          	 
           		<td style="text-align: center; <?=$stylebenarcheck?>">
	            	<?
	            		$arrayKey= "";
	        			$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawabanPegawai);
						// print_r($arrayKey);exit;

						if($arrayKey == ""){}
						else
						{
							for($i_detil=0; $i_detil < count($arrayKey); $i_detil++)
							{
								$index_data_detil= $arrayKey[$i_detil]; 
								$reqBankSoalJawaban= $arrDataJawabanPegawai[$index_data_detil]["JAWABAN"];	 
								?>
									<label><?=$reqBankSoalJawaban?></label>
								<?
								 
							}
						}
	            	?>
            	</td>            	 

            </tr>

            <?
            	$tempCheckValue= $reqBankSoalTipeUjianId;
        	}
            ?>

            <?
            if($index_data > 0)
            {
            ?>
                </tbody>
            </table>
            <?
        	}
            ?>

        </div>
        
    </div>
    
    </div>
</div>
</body>
</html>
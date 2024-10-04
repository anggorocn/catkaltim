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
if($reqTipeUjianId == "1")
{
	$statement_detil= " AND B.TIPE_UJIAN_ID IN (8,9,10,11)";
}
elseif($reqTipeUjianId == "2")
{
	$statement_detil= " AND B.TIPE_UJIAN_ID IN (12,13,14,15)";
}
elseif($reqTipeUjianId == "18")
{
	$statement_detil= " AND B.TIPE_UJIAN_ID IN (19,20,21,22,23,24,25,26,27)";
	// $statement_detil= " AND B.TIPE_UJIAN_ID IN (20)"; 
}
elseif($reqTipeUjianId == "4" || $reqTipeUjianId = "46" || $reqTipeUjianId == "50" || $reqTipeUjianId == "51" || $reqTipeUjianId == "52" || $reqTipeUjianId == "53" || $reqTipeUjianId == "54" || $reqTipeUjianId == "55" || $reqTipeUjianId == "56" || $reqTipeUjianId == "57" || $reqTipeUjianId == "58" || $reqTipeUjianId == "59"|| $reqTipeUjianId == "60" || $reqTipeUjianId == "61" || $reqTipeUjianId == "62" || $reqTipeUjianId == "63" || $reqTipeUjianId == "64" || $reqTipeUjianId == "65")
{
	$statement_detil= " AND (B.TIPE_UJIAN_ID IN (4,46) OR B.TIPE_UJIAN_ID > 49) ";
	// $statement_detil= " AND B.TIPE_UJIAN_ID IN (73)"; 
}

// echo $statement_detil;exit;
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
$set->selectByParamsSoal(array(), -1,-1, $reqLowonganId, $statement);
 // echo $set->query;exit;
while($set->nextRow())
{
	$arrData[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrData[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrData[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrData[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrData[$index_data]["PERTANYAAN"]= RemoveBS($set->getField("PERTANYAAN"));
	$arrData[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	$arrData[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	$arrData[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
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
$set->selectByParamsJawaban(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataJawaban[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrDataJawaban[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrDataJawaban[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataJawaban[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataJawaban[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
	$arrDataJawaban[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	$arrDataJawaban[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	$arrDataJawaban[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
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
$set->selectByParamsJawabanPegawai(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataJawabanPegawai[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrDataJawabanPegawai[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrDataJawabanPegawai[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataJawabanPegawai[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataJawabanPegawai[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
	$arrDataJawabanPegawai[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	$arrDataJawabanPegawai[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	$arrDataJawabanPegawai[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
	$arrDataJawabanPegawai[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
	$arrDataJawabanPegawai[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
	$arrDataJawabanPegawai[$index_data]["URUT"]= $set->getField("URUT");
	$index_data++;
}
$jumlah_jawaban_pegawai_data = $index_data;
// print_r($arrDataJawabanPegawai);exit();
unset($set);

//tkd 5r
$set= new RekapSehat();
$index_data= 0;
$arrDataJawabanPegawai56="";
$arrCheckDataJawabanPegawai56=array();
$i=1;
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsJawabanPegawaiTKD56R(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataJawabanPegawai56[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	if( $set->getField("STATUS_BENAR")=='1'){
		$arrCheckDataJawabanPegawai56[$i]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
		$i++;
	}
	$arrDataJawabanPegawai56[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrDataJawabanPegawai56[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataJawabanPegawai56[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataJawabanPegawai56[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
	$arrDataJawabanPegawai56[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	$arrDataJawabanPegawai56[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	$arrDataJawabanPegawai56[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
	$arrDataJawabanPegawai56[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
	$arrDataJawabanPegawai56[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
	$arrDataJawabanPegawai56[$index_data]["URUT"]= $set->getField("URUT");
	$index_data++;
}
$jumlah_jawaban_pegawai_data56 = $index_data;
// print_r($arrCheckDataJawabanPegawai56);exit();
unset($set);

//tkd 6r
$set= new RekapSehat();
$index_data= 0;
$arrDataJawabanPegawai6r="";
$arrCheckDataJawabanPegawai6r=array();
$i=1;
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsJawabanPegawaiTKD6R(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;
while($set->nextRow())
{
	if ($set->getField("JAWABAN1")!='' and $set->getField("JAWABAN2")!=''){
		$arrDataJawabanPegawai6r[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");

		if( $set->getField("cekjawaban_1")!='' and $set->getField("cekjawaban_2")!=''){
			$arrCheckDataJawabanPegawai6r[$i]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
		$i++;
		}
		$arrDataJawabanPegawai6r[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
		$arrDataJawabanPegawai6r[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
		$arrDataJawabanPegawai6r[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
		$arrDataJawabanPegawai6r[$index_data]["JAWABAN"]= $set->getField("JAWABAN1")." ".$set->getField("JAWABAN2");
		$arrDataJawabanPegawai6r[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
		$arrDataJawabanPegawai6r[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
		$arrDataJawabanPegawai6r[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
		$arrDataJawabanPegawai6r[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
		$arrDataJawabanPegawai6r[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
		$arrDataJawabanPegawai6r[$index_data]["URUT"]= $set->getField("URUT");
		$index_data++;
	}
}
$jumlah_jawaban_pegawai_data56 = $index_data;
// print_r($arrCheckDataJawabanPegawai6r);exit();
unset($set);

$set= new RekapSehat();
$index_data= 0;
$arrCheckDataJawabanPegawai="";
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsCheckJawabanPegawai(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrCheckDataJawabanPegawai[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$index_data++;
}
$jumlah_check_jawaban_pegawai_data = $index_data;
// print_r($arrCheckDataJawabanPegawai);
unset($set);

$set= new RekapSehat();
$index_data= 0;
$arrDataSoalBenarJawaban="";
$statement= " AND A.PEGAWAI_ID = ".$reqId.$statement_detil;
$set->selectByParamsJawabanBenarSoal(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataSoalBenarJawaban[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrDataSoalBenarJawaban[$index_data]["UJIAN_ID"]= $set->getField("UJIAN_ID");
	$arrDataSoalBenarJawaban[$index_data]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrDataSoalBenarJawaban[$index_data]["BANK_SOAL_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataSoalBenarJawaban[$index_data]["JAWABAN"]= $set->getField("JAWABAN");
	$arrDataSoalBenarJawaban[$index_data]["PATH_GAMBAR"]= "../".$set->getField("PATH_GAMBAR");
	$arrDataSoalBenarJawaban[$index_data]["PATH_SOAL"]= $set->getField("PATH_SOAL");
	$arrDataSoalBenarJawaban[$index_data]["TIPE_SOAL"]= $set->getField("TIPE_SOAL");
	$arrDataSoalBenarJawaban[$index_data]["TIPE_UJIAN_ID"]= $set->getField("TIPE_UJIAN_ID");
	$arrDataSoalBenarJawaban[$index_data]["TIPE_UJIAN_NAMA"]= $set->getField("TIPE_UJIAN_NAMA");
	$arrDataSoalBenarJawaban[$index_data]["URUT"]= $set->getField("URUT");
	$index_data++;
}
$jumlah_soal_jawaban_benar_data = $index_data;
// print_r($arrDataSoalBenarJawaban);exit();
unset($set);

$set= new RekapSehat();
$index_data= 0;
$arrDataIstJawaban="";
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set->selectByParamsJawabanIstPegawai(array(), -1,-1, $reqLowonganId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataIstJawaban[$index_data]["ROW_ID"]= $set->getField("UJIAN_ID")."-".$set->getField("PEGAWAI_ID").$set->getField("BANK_SOAL_ID");
	$arrDataIstJawaban[$index_data]["JAWABAN_KETERANGAN"]= $set->getField("KETERANGAN")." (".$set->getField("NILAI").")";
	$index_data++;
}
$jumlah_jawaban_ist = $index_data;
// print_r($arrDataIstJawaban);exit();
unset($set);

$set= new RekapSehat();
$index_data= 0;
$arrDataIstKunciJawaban="";
$statement= " ";
$set->selectByParamsJawabanIstKunci(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrDataIstKunciJawaban[$index_data]["ROW_ID"]= $set->getField("BANK_SOAL_ID");
	$arrDataIstKunciJawaban[$index_data]["JAWABAN_KETERANGAN"]= $set->getField("KETERANGAN")." (".$set->getField("NILAI").")";
	$index_data++;
}
$jumlah_jawaban_ist = $index_data;
// print_r($arrDataIstKunciJawaban);exit();
unset($set);
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
	<!-- <div id="judul-popup">ChecK Jawaban</div> -->
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
            	$reqBankSoalPertanyaan=str_replace("&emsp;","  ",$reqBankSoalPertanyaan);
            	// echo $reqBankSoalPertanyaan;

            	$reqBankSoalPathGambar= $arrData[$index_data]["PATH_GAMBAR"];
            	$reqBankSoalPathSoal= $arrData[$index_data]["PATH_SOAL"];
            	$reqBankSoalTipeSoal= $arrData[$index_data]["TIPE_SOAL"];
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
                  <?
                  // echo $reqBankSoalTipeSoal;
                  if($reqBankSoalTipeUjianId == "20" || $reqBankSoalTipeSoal == "3" || $reqBankSoalTipeSoal == "8" || $reqBankSoalTipeSoal == "9"|| $reqBankSoalTipeSoal == "10"|| $reqBankSoalTipeSoal == "5")
            	  {
            	  ?>
            	  	  <tr>
	                      <th colspan="3" style="text-align: left;"><?=$reqBankSoalTipeUjianNama?></th>
	                  </tr>
	                  <tr>
	                      <th style="width: 30%">Soal</th>
	                      <th>Jawaban Peserta</th>
	                      <th style="width: 20%">Kunci Jawaban</th>
	                  </tr>
            	  <?
            	  }
            	  else
            	  {
                  ?>
	                  <tr>
	                      <th colspan="4" style="text-align: left;"><?=$reqBankSoalTipeUjianNama?></th>
	                  </tr>
	                  <tr>
	                      <th style="width: 30%">Soalsss</th>
	                      <th style="width: 30%">Jawaban</th>
	                      <th>Jawaban Peserta</th>
	                      <th style="width: 20%">Kunci Jawaban</th>
	                  </tr>
                  <?
              	  }
                  ?>
            <?
        	}
            ?>
            
            <tr>
            	<?
            	if($reqBankSoalTipeUjianId == "20"){}
            	else
            	{
            	?>
            	<td style="text-align: center;">
            		<?
            		// echo $reqBankSoalTipeSoal;
            		if($reqBankSoalTipeSoal == "1" || $reqBankSoalTipeSoal == "8" || $reqBankSoalTipeSoal == "9" || $reqBankSoalTipeSoal == 10)
            		{
            		?>
            			<label><?=$reqBankSoalPertanyaan?></label>
	            	<?
	            	}
            		elseif($reqBankSoalTipeSoal == "2"||$reqBankSoalTipeSoal == "5")
            		{
			            if($reqBankSoalPathSoal == ""){}
			            else
			            {
		            		if(file_exists($reqBankSoalPathGambar.$reqBankSoalPathSoal))
							{
						?>
	            			<!-- <img src="<?=$reqBankSoalPathGambar.$reqBankSoalPathSoal?>" style="max-width:100%; height:auto; display: block; text-align: center;" /> -->
	            			<img src="<?=$reqBankSoalPathGambar.$reqBankSoalPathSoal?>" style="max-width:100%; display: block; text-align: center;" height="153" />
	            		<?
	            			}
	            		}
	            	}
	            	elseif($reqBankSoalTipeSoal == "3")
            		{
            			$arrayKey= "";
            			$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawaban);
						// print_r($arrayKey);exit;

						if($arrayKey == ""){}
						else
						{
							for($i_detil=0; $i_detil < count($arrayKey); $i_detil++)
							{
								$index_data_detil= $arrayKey[$i_detil];
								$reqBankSoalPathGambar= $arrDataJawaban[$index_data_detil]["PATH_GAMBAR"];
								$reqBankSoalPathSoal= $arrDataJawaban[$index_data_detil]["PATH_SOAL"];

								if($reqBankSoalPathSoal == ""){}
					            else
					            {
				            		if(file_exists($reqBankSoalPathGambar.$reqBankSoalPathSoal))
									{
								?>
			            			<img src="<?=$reqBankSoalPathGambar.$reqBankSoalPathSoal?>" width="55" height="53" />
			            		<?
			            			}
			            		}
							}
						}
	            	}
	            	?>
            	</td>
            	<?
            	}
            	?>

            	<?
            	if($reqBankSoalTipeSoal == "1" || $reqBankSoalTipeSoal == "2" || $reqBankSoalTipeSoal == "10000")
            	{
            	?>
            	<td style="text-align: center;">
            	<?
            		if($reqBankSoalTipeSoal == "1000")
            		{
            			$arrayKey= "";
	        			$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataIstJawaban);
						// print_r($arrayKey);exit;

						if($arrayKey == ""){}
						else
						{
							for($i_detil=0; $i_detil < count($arrayKey); $i_detil++)
							{
								$index_data_detil= $arrayKey[$i_detil];
								$reqBankSoalJawaban= $arrDataIstJawaban[$index_data_detil]["JAWABAN_KETERANGAN"];
					?>
								<label><?=$reqBankSoalJawaban?></label><br/>
					<?
							}
						}
            		}
            		else
            		{
	            		$arrayKey= "";
	        			$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawaban);
						// print_r($arrayKey);exit;

						if($arrayKey == ""){}
						else
						{
							for($i_detil=0; $i_detil < count($arrayKey); $i_detil++)
							{
								$index_data_detil= $arrayKey[$i_detil];
								$reqBankSoalPathGambar= $arrDataJawaban[$index_data_detil]["PATH_GAMBAR"];
								$reqBankSoalPathSoal= $arrDataJawaban[$index_data_detil]["PATH_SOAL"];
								$reqBankSoalJawaban= $arrDataJawaban[$index_data_detil]["JAWABAN"];

								if($reqBankSoalPathSoal == "")
								{
								?>
									<label>- <?=$reqBankSoalJawaban?></label><br/>
								<?
								}
					            else
					            {
				            		if(file_exists($reqBankSoalPathGambar.$reqBankSoalPathSoal))
									{
								?>
			            			<img src="<?=$reqBankSoalPathGambar.$reqBankSoalPathSoal?>" width="55" height="53" />
			            		<?
			            			}
			            		}
							}
						}
					}
            	?>
            	</td>
            	<?
            	}
            	?>

            	<?
            	$stylebenarcheck= "";
            	$arrayKey= "";

            	if($reqBankSoalTipeSoal == "1000"){}
            	else
            	{
	    			// $arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrCheckDataJawabanPegawai);
					if($reqBankSoalTipeSoal == "8"){
	            			// $arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrCheckDataJawabanPegawai56);
						$arrayKey=array_search($reqBankSoalRowId,$arrCheckDataJawabanPegawai56);

	            	// print_r($reqBankSoalRowId); echo "xxxx->";print_r($arrCheckDataJawabanPegawai); echo "<br>";

					}
					else if($reqBankSoalTipeSoal == "10"){
						$arrayKey=array_search($reqBankSoalRowId,$arrCheckDataJawabanPegawai6r);
					}
	            	else	{
        				$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrCheckDataJawabanPegawai);
            		}

					if($arrayKey == ""){
					}
					else
					{
	            		$stylebenarcheck= ";background-color: pink;";
	            	}
            	?>

            		<td style="text-align: center; <?=$stylebenarcheck?>">
            	<?	
	            		$arrayKey= "";
	            		if($reqBankSoalTipeSoal == "8"){
	            			$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawabanPegawai56);	

	            			// echo $arrayKey;
	            		}
	            		else if($reqBankSoalTipeSoal == "10"){
	            			$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawabanPegawai6r);
	            			// print_r($arrayKey);
						}
	            		else	
	        				$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawabanPegawai);

						if($arrayKey == ""){}
						else
						{
							for($i_detil=0; $i_detil < count($arrayKey); $i_detil++)
							{
								$index_data_detil= $arrayKey[$i_detil];
								if($reqBankSoalTipeSoal == "8")
								{
									$reqBankSoalPathGambar= $arrDataJawabanPegawai56[$index_data_detil]["PATH_GAMBAR"];
									$reqBankSoalPathSoal= $arrDataJawabanPegawai56[$index_data_detil]["PATH_SOAL"];
									$reqBankSoalJawaban= $arrDataJawabanPegawai56[$index_data_detil]["JAWABAN"];
								}
								else if($reqBankSoalTipeSoal == "10")
								{
									$reqBankSoalPathGambar= $arrDataJawabanPegawai6r[$index_data_detil]["PATH_GAMBAR"];
									$reqBankSoalPathSoal= $arrDataJawabanPegawai6r[$index_data_detil]["PATH_SOAL"];
									$reqBankSoalJawaban= $arrDataJawabanPegawai6r[$index_data_detil]["JAWABAN"];
								}
								else
								{
									$reqBankSoalPathGambar= $arrDataJawabanPegawai[$index_data_detil]["PATH_GAMBAR"];
									$reqBankSoalPathSoal= $arrDataJawabanPegawai[$index_data_detil]["PATH_SOAL"];
									$reqBankSoalJawaban= $arrDataJawabanPegawai[$index_data_detil]["JAWABAN"];
									
								}
								
								// echo "xxxx".$reqBankSoalPathSoal;
								if($reqBankSoalPathSoal == "")
								{
								?>
									<label><?=$reqBankSoalJawaban?></label>
								<?
								}
					            else
					            {
				            		if(file_exists($reqBankSoalPathGambar.$reqBankSoalPathSoal))
									{
								?>
			            			<img src="<?=$reqBankSoalPathGambar.$reqBankSoalPathSoal?>" width="55" height="53" />
			            		<?
			            			}
			            		}
							}
						}
            	?>
            		</td>
            	<?
            	}
            	?>

            	<td style="text-align: center;">
            	<?
            		$arrayKey= "";

            		// if($reqBankSoalTipeSoal == "8")
            		// 	$arrayKey= in_array_column($reqBankSoalId, "ROW_ID", $arrDataIstKunciJawaban);
            		// else
            			// $arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataJawabanPegawai);
        				$arrayKey= in_array_column($reqBankSoalRowId, "ROW_ID", $arrDataSoalBenarJawaban);

					// print_r($arrayKey);exit;

					if($arrayKey == ""){}
					else
					{
						for($i_detil=0; $i_detil < count($arrayKey); $i_detil++)
						{
							$index_data_detil= $arrayKey[$i_detil];

							if($reqBankSoalTipeSoal == "8")
							{
								$reqBankSoalPathSoal= "";

								$separator= "";
								if($i_detil > 0)
									$separator= "<br/>";

								$reqBankSoalJawaban= $separator.$arrDataIstKunciJawaban[$index_data_detil]["JAWABAN_KETERANGAN"];
								$reqBankSoalJawaban= $arrDataSoalBenarJawaban[$index_data_detil]["JAWABAN"];
							}
							else
							{
								$reqBankSoalPathGambar= $arrDataSoalBenarJawaban[$index_data_detil]["PATH_GAMBAR"];
								$reqBankSoalPathSoal= $arrDataSoalBenarJawaban[$index_data_detil]["PATH_SOAL"];
								$reqBankSoalJawaban= $arrDataSoalBenarJawaban[$index_data_detil]["JAWABAN"];
							}

							if($reqBankSoalPathSoal == "")
							{
							?>
								<label><?=$reqBankSoalJawaban?></label>
							<?
							}
				            else
				            {
			            		if(file_exists($reqBankSoalPathGambar.$reqBankSoalPathSoal))
								{
							?>
		            			<img src="<?=$reqBankSoalPathGambar.$reqBankSoalPathSoal?>" width="55" height="53" />
		            		<?
		            			}
		            		}
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
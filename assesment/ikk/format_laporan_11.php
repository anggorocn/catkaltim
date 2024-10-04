<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");

$statement= " AND A.PEGAWAI_ID= ".$reqId;
$set = new Penilaian();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));

$statement= " AND A.PEGAWAI_ID= ".$reqId;
$set= new Kelautan();
$set->selectByParamsMonitoringPegawai(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempPegawaiNama= $set->getField("NAMA");
$tempPegawaiNip= $set->getField("NIP_BARU");
$tempPegawaiJabatanSaatIni= $set->getField("NAMA_JAB_STRUKTURAL");
$tempPegawaiGol= $set->getField("NAMA_GOL");
$tempUnitKerjaSaatIni= $set->getField("SATKER");
unset($set);

$statement= " AND A.ASPEK_ID = 2 AND A.PEGAWAI_ID= ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'";
$set = new Penilaian();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqRowId= $set->getField("PENILAIAN_ID");

$statement= " AND C.ATRIBUT_ID_PARENT != '0'";
//$statement.= " AND C.ATRIBUT_ID = '0101'";
$arrAtributPenilaian= "";
$index_psikologi=0;
$set_penilaian= new PenilaianDetil();
$set_penilaian->selectByParamsMonitoringIndikatorPenilaian(array(), -1, -1, $statement, $reqRowId);
//echo $set_penilaian->query;exit;
while($set_penilaian->nextRow())
{
	$arrAtributPenilaian[$index_psikologi]["NAMA_ATRIBUT_PARENT"] = $set_penilaian->getField("NAMA_ATRIBUT_PARENT");
	$arrAtributPenilaian[$index_psikologi]["NAMA"] = $set_penilaian->getField("NAMA");
	$arrAtributPenilaian[$index_psikologi]["KETERANGAN"] = $set_penilaian->getField("KETERANGAN");
	$arrAtributPenilaian[$index_psikologi]["LEVEL_ID"] = $set_penilaian->getField("LEVEL_ID");
	$arrAtributPenilaian[$index_psikologi]["LEVEL"] = $set_penilaian->getField("LEVEL");
	$arrAtributPenilaian[$index_psikologi]["NILAI"] = $set_penilaian->getField("NILAI");
	$arrAtributPenilaian[$index_psikologi]["LEVEL_KETERANGAN"] = $set_penilaian->getField("LEVEL_KETERANGAN");
	$index_psikologi++;
}
unset($set_penilaian);
//print_r($arrAtributPenilaian);exit;
$jumlah_data_penilaian= $index_psikologi;

$statement= " AND C.ATRIBUT_ID_PARENT != '0'";
//$statement.= " AND C.ATRIBUT_ID = '0101'";
$arrDetilAtributPenilaian= "";
$index_psikologi=0;
$set_penilaian= new PenilaianDetil();
$set_penilaian->selectByParamsMonitoringDetilIndikatorPenilaian(array(), -1, -1, $statement, $reqRowId);
//echo $set_penilaian->query;exit;
while($set_penilaian->nextRow())
{
	$arrDetilAtributPenilaian[$index_psikologi]["NAMA"] = $set_penilaian->getField("NAMA");
	$arrDetilAtributPenilaian[$index_psikologi]["KETERANGAN"] = $set_penilaian->getField("KETERANGAN");
	$arrDetilAtributPenilaian[$index_psikologi]["LEVEL_ID"] = $set_penilaian->getField("LEVEL_ID");
	$arrDetilAtributPenilaian[$index_psikologi]["LEVEL"] = $set_penilaian->getField("LEVEL");
	$arrDetilAtributPenilaian[$index_psikologi]["NAMA_INDIKATOR"] = $set_penilaian->getField("NAMA_INDIKATOR");
	$arrDetilAtributPenilaian[$index_psikologi]["NILAI"] = $set_penilaian->getField("NILAI");
	$arrDetilAtributPenilaian[$index_psikologi]["LEVEL_KETERANGAN"] = $set_penilaian->getField("LEVEL_KETERANGAN");
	$index_psikologi++;
}
unset($set_penilaian);
//print_r($arrDetilAtributPenilaian);exit;
?>
<?
for($checkbox_index=0; $checkbox_index < $jumlah_data_penilaian; $checkbox_index++)
{
	$tempNamaParentAtribut= $arrAtributPenilaian[$checkbox_index]["NAMA_ATRIBUT_PARENT"];
	$tempNamaAtribut= $arrAtributPenilaian[$checkbox_index]["NAMA"];
	$tempNilai= $arrAtributPenilaian[$checkbox_index]["NILAI"];
	$tempKeterangan= $arrAtributPenilaian[$checkbox_index]["KETERANGAN"];
	$tempInfo1= str_replace(strtok($tempKeterangan,  ' '),"", $tempKeterangan);
	$tempLevelId= $arrAtributPenilaian[$checkbox_index]["LEVEL_ID"];
	$tempLevel= $arrAtributPenilaian[$checkbox_index]["LEVEL"];
	$tempLevelKeterangan= $arrAtributPenilaian[$checkbox_index]["LEVEL_KETERANGAN"];
	
	if($checkbox_index == 0){}
	else
	echo "<pagebreak />";
?>
<div style="width:100%; text-align:center; font-weight:600; font-size: 32px;">RINGKASAN ASESMEN SETIAP KOMPETENSI</div>
<br />
<table style="width:100%;">
	<tr>
    	<td width="95%"><?=$tempNamaParentAtribut?></td>
    	<td width="5%">Rating</td>
    </tr>
    <tr>
    	<td><?=$tempNamaAtribut?></td>
        <td style="background-color:#FFFE0B" class="border txtcenter"><?=$tempNilai?></td>
    </tr>
</table>
<br />
<table style="width:100%;" class="bordertable">
    	<tr>
        	<td colspan="2" class="border"><?=$tempKeterangan?></td>
        </tr>
        <tr>
        	<td style="width:50%;" class="atasborder kananborder kiriborder">Level <?=$tempLevel?></td>
        	<td style="width:50%;" class="atasborder kananborder kiriborder">Indikator Perilaku</td>
        </tr>
        <tr>
        	<td class="kananborder valigntop"><?=$tempLevelKeterangan?></td>
        	<td class="kananborder valigntop">
            <?
            $arrayIndikatorKey= '';
            $arrayIndikatorKey= in_array_column($tempLevelId, "LEVEL_ID", $arrDetilAtributPenilaian);
            
            if($arrayIndikatorKey == ''){}
            else
            {
                for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayIndikatorKey); $index_detil_asesor_kelompok++)
                {
                    $index_row= $arrayIndikatorKey[$index_detil_asesor_kelompok];
                    $tempNamaIndikator= $arrDetilAtributPenilaian[$index_row]["NAMA_INDIKATOR"];
					
					if($index_detil_asesor_kelompok == 0){}
					else
					echo "<br/>";
					
					echo "- ".$tempNamaIndikator;
				}
			}
			
			$tempKeteranganDetil= radioKeteranganPenilaianInfo($tempNilai);
			?>
            </td>
        </tr>
        <tr style="background-color:#FFFE0B">
        	<td style="width:50%;" class="txtcenter">Rating</td>
        	<td style="width:50%;" class="txtcenter">Kompetensi<br/><?=$tempKeteranganDetil?></td>
        </tr>
</table>
<br />
<div style="width:100%; font-weight:600; font-size: 12px;">SARAN</div><br />
<?=$tempPegawaiNama?> saat ini <?=setInfoKemmpuanSaran($tempNilai)?> <?=$tempInfo1?>
<?
$arrayIndikatorKey= '';
$arrayIndikatorKey= in_array_column($tempLevelId, "LEVEL_ID", $arrDetilAtributPenilaian);

if($arrayIndikatorKey == ''){}
else
{
	for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayIndikatorKey); $index_detil_asesor_kelompok++)
	{
		$index_row= $arrayIndikatorKey[$index_detil_asesor_kelompok];
		$tempNamaIndikator= $arrDetilAtributPenilaian[$index_row]["NAMA_INDIKATOR"];
		
		echo "<br/><br/>".$tempPegawaiNama." juga ".setInfoKemmpuanSaran($tempNilai)." menampilkan perilaku dengan ".$tempNamaIndikator;
	}
}
?>
<?
}
?>
<?="<br/><br/>".$tempPegawaiNama." diharapkan"?>
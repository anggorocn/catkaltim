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
$tempTanggalTes= getFormattedDate($set->getField("TANGGAL_TES"));
$tempPegawaiEselon= "Pejabat Struktural Eselon ".romanicNumber($set->getField("ESELON"));
$reqRowId= $set->getField("PENILAIAN_ID");

$tempSatkerTes= $set->getField("SATKER_TES");
$tempSatkerTesId= $set->getField("SATKER_TES_ID");
$tempJabatanTes= $set->getField("JABATAN_TES");
$tempJabatanTesId= $set->getField("JABATAN_TES_ID");
$tempAspekNama= strtoupper($set->getField("ASPEK_NAMA"));
$tempAspekId= strtoupper($set->getField("ASPEK_ID"));

$statement= " AND A.ATRIBUT_ID_PARENT != '0'";
$index_psikologi=0;
$set_penilaian= new PenilaianDetil();
$set_penilaian->selectByParamsMonitoringPenilaian(array(), -1, -1, $statement, $reqRowId);
//echo $set_penilaian->query;exit;
while($set_penilaian->nextRow())
{
	$arrAtributPenilaian[$index_psikologi]["NAMA"] = $set_penilaian->getField("NAMA")." Level ".$set_penilaian->getField("LEVEL");
	$arrAtributPenilaian[$index_psikologi]["NILAI_STANDAR"] = $set_penilaian->getField("NILAI_STANDAR");
	$arrAtributPenilaian[$index_psikologi]["BOBOT"] = $set_penilaian->getField("BOBOT");
	$arrAtributPenilaian[$index_psikologi]["PROSENTASE"] = $set_penilaian->getField("PROSENTASE");
	$arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID"] = $set_penilaian->getField("ATRIBUT_ID");
	$arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID_PARENT"] = $set_penilaian->getField("ATRIBUT_ID_PARENT");
	$arrAtributPenilaian[$index_psikologi]["ATRIBUT_GROUP"] = $set_penilaian->getField("ATRIBUT_GROUP");
	$arrAtributPenilaian[$index_psikologi]["PENILAIAN_DETIL_ID"] = $set_penilaian->getField("PENILAIAN_DETIL_ID");
	$arrAtributPenilaian[$index_psikologi]["PENILAIAN_ID"] = $set_penilaian->getField("PENILAIAN_ID");
	$arrAtributPenilaian[$index_psikologi]["NILAI"] = $set_penilaian->getField("NILAI");
	$arrAtributPenilaian[$index_psikologi]["GAP"] = $set_penilaian->getField("GAP");
	$arrAtributPenilaian[$index_psikologi]["JUMLAH_PENILAIAN_DETIL"] = 10;
	$arrAtributPenilaian[$index_psikologi]["LEVEL_KETERANGAN"] = $set_penilaian->getField("LEVEL_KETERANGAN");
	
	$tempNilaiStandar= $arrAtributPenilaian[$index_psikologi]["NILAI_STANDAR"];
	$tempAtributId= $arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID"];
	$tempAtributIdParent= $arrAtributPenilaian[$index_psikologi]["ATRIBUT_ID_PARENT"];
	$tempAtributGroup= $arrAtributPenilaian[$index_psikologi]["ATRIBUT_GROUP"];
	$tempNilai= $arrAtributPenilaian[$index_psikologi]["NILAI"];
	$tempGap= $arrAtributPenilaian[$index_psikologi]["GAP"];
	
	if($tempAtributIdParent == 0){}
	else
	{
		if($tempNilaiStandar == ""){}
		else
		$tempJpm= round($tempNilai/$tempNilaiStandar,2);
		
		//echo $tempJpm."<br/>";
		//kolom IKK (jika gap <= 0, ikk-> 1-jpm) (jika gap >0, ikk = jpm)
		if($tempGap <= 0)
			$tempIkk= 1 - $tempJpm;
		elseif($tempGap > 0)
			$tempIkk= 0;
		else//if($tempGap > 0)
			$tempIkk= $tempJpm;
		//echo $tempIkk."<br/>";
		//- total JPM (total jpm / total atribut) -> ditaruh di pojok kanan atas
		//- total IKK (total ikk / total atribut)  -> ditaruh di pojok kanan atas
		
		if($tempGap <= 0)
			$tempIkkLama= 1 - $tempJpm;
		else//if($tempGap > 0)
			$tempIkkLama= $tempJpm;

		if($tempIkkLama < 1)
			$tempKeteranganIkk= "dibawah standar";
		elseif($tempIkkLama == 0)
			$tempKeteranganIkk= "sesuai standar";
		else
		{
			$tempSuperior= $tempJpm-$tempGap;
			$tempKeteranganIkk= "superior lebih ".$tempSuperior." %";
		}
												
		$tempTotalIkk+= $tempIkk;
		$tempTotalJpm+= $tempJpm;
	}
	
	$arrAtributPenilaian[$index_psikologi]["KETERANGAN_IKK"] = $tempKeteranganIkk;
	$index_psikologi++;
}
unset($set_penilaian);
//print_r($arrAtributPenilaian);exit;

if($index_psikologi > 0)
{
	$statement_ikk= " AND D.PENILAIAN_ID = ".$reqRowId." AND B.ASPEK_ID = 2 AND X.KODE_UNKER = '".$tempSatkerTesId."'";
	$set_nilai_ikk= new PenilaianDetil();
	$set_nilai_ikk->selectByParamsPersonalIkkJpm(array(), -1,-1, $statement_ikk);
	//echo $set_nilai_ikk->query;exit;
	$set_nilai_ikk->firstRow();
	$tempTotalIkk= $set_nilai_ikk->getField("NILAI_IKK_PERSEN");
	$tempTotalJpm= $set_nilai_ikk->getField("NILAI_JPM_PERSEN");
	unset($set_nilai_ikk);
	if($tempTotalJpm > 100)
	$tempTotalJpm= 100;
}

?>

<div class="detil">
<div style="width:100%; text-align:center; font-weight:600; font-size: 32px;">GENERAL COMPETENCY MATCHING</div><br />
<table style="width:100%" class="bordertable">
  <tr>
    <td style="width:20%">Nomor Tes</td>
    <td>:</td>
    <td style="width:30%"></td>
    <td style="width:20%">Standar / Standart</td>
    <td>:</td>
    <td><?=$tempPegawaiEselon?></td>
  </tr>
  <tr>
    <td>NIP</td>
    <td>:</td>
    <td><?=$tempPegawaiNip?></td>
    <td>Matrix</td>
    <td>:</td>
    <td>1</td>
  </tr>
  <tr>
    <td>Nama</td>
    <td>:</td>
    <td><?=$tempPegawaiNama?></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Jabatan Saat Ini</td>
    <td>:</td>
    <td><?=$tempPegawaiJabatanSaatIni?></td>
    <td colspan="3" class="border txtcenter">JOB PERSON MATCH</td>
  </tr>
  <tr>
    <td>Standar Penilaian</td>
    <td>:</td>
    <td><?=$tempPegawaiEselon?></td>
    <td colspan="3">
    <table style="background: linear-gradient(to left, #F2F817, #517C3D); width:<?=$tempTotalJpm?>%"><tr><td style="text-align:right"><?=$tempTotalJpm?>%</td></tr></table>
    </td>
  </tr>
  <tr>
    <td>Golongan / Pangkat</td>
    <td>:</td>
    <td><?=$tempPegawaiGol?></td>
    <td colspan="3" rowspan="2"></td>
  </tr>
  <tr>
    <td>Tanggal Tes</td>
    <td>:</td>
    <td><?=$tempTanggalTes?></td>
  </tr>
</table>
<br/>
<table style="width:100%;" class="bordertable">
	<thead>
    	<tr>
        	<td rowspan="2" style="width:50%;" class="border">ATRIBUT & INDIKATOR</td>
            <td colspan="5" class="border txtcenter">Range Scale</td>
        </tr>
        <tr>
        	<td class="border">1</td>
        	<td class="border">2</td>
        	<td class="border">3</td>
        	<td class="border">4</td>
        	<td class="border">5</td>
        </tr>
    </thead>
    <tbody>
    	<tr>
        	<td>ASPEK KOMPETENSI MANAGERIAL</td>
            <td class="border">&nbsp;</td>
            <td class="border">&nbsp;</td>
            <td class="border">&nbsp;</td>
            <td class="border">&nbsp;</td>
            <td class="border">&nbsp;</td>
        </tr>
      <?
      $tempGroup= "";
	  $index_atribut_parent= 0;
	  for($checkbox_index=0; $checkbox_index < $index_psikologi; $checkbox_index++)
	  {
		$tempNama= $arrAtributPenilaian[$checkbox_index]["NAMA"];
		$tempNilaiStandar= $arrAtributPenilaian[$checkbox_index]["NILAI_STANDAR"];
		$tempBobot= $arrAtributPenilaian[$checkbox_index]["BOBOT"];
		$tempProsentase= $arrAtributPenilaian[$checkbox_index]["PROSENTASE"];
		$tempAtributId= $arrAtributPenilaian[$checkbox_index]["ATRIBUT_ID"];
		$tempAtributIdParent= $arrAtributPenilaian[$checkbox_index]["ATRIBUT_ID_PARENT"];
		$tempAtributGroup= $arrAtributPenilaian[$checkbox_index]["ATRIBUT_GROUP"];
		$tempPenilaianDetilId= $arrAtributPenilaian[$checkbox_index]["PENILAIAN_DETIL_ID"];
		$tempPenilaianId= $arrAtributPenilaian[$checkbox_index]["PENILAIAN_ID"];
		$tempNilai= $arrAtributPenilaian[$checkbox_index]["NILAI"];
		$tempGap= $arrAtributPenilaian[$checkbox_index]["GAP"];
		$tempJumlahPenilaianDetil= $arrAtributPenilaian[$checkbox_index]["JUMLAH_PENILAIAN_DETIL"];
		$tempLevelKeterangan= $arrAtributPenilaian[$checkbox_index]["LEVEL_KETERANGAN"];
		
		$index_atribut_parent++;
		$tempGroup= $tempAtributGroup;
		//if($tempAtributIdParent == "0")
		//{
	  ?>
      	<tr>
        	<td><?=romanicNumber($index_atribut_parent).". ".$tempNama?></td>
            <td class="border" colspan="5">
            <table style="background: linear-gradient(to left, #F2F817, #517C3D); width:<?=$tempProsentase?>%"><tr><td style="text-align:right"><?=$tempProsentase?>%</td></tr></table>
            </td>
        </tr>
      <?
		//}
		//else
		//{
			$arrChecked= radioPenilaianInfo($tempNilai);
	  ?>
      	<tr>
        	<td class="paddingleftdetil">1<?=". ".$tempLevelKeterangan?></td>
            <td class="border txtcenter"><?=$arrChecked[0]?></td>
            <td class="border txtcenter"><?=$arrChecked[1]?></td>
            <td class="border txtcenter"><?=$arrChecked[2]?></td>
            <td class="border txtcenter"><?=$arrChecked[3]?></td>
            <td class="border txtcenter"><?=$arrChecked[4]?></td>
        </tr>
      <?
		//}
	  }
	  ?>
    </tbody>
</table>
<div style="width:100%; font-weight:600; font-size: 12px; font-style:italic;">NIP : </div><br />
</div>
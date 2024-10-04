<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

$reqId= httpFilterGet("reqId");
$reqTahun= httpFilterGet("reqTahun");

$pegawai = new Kelautan();
$pegawai->selectByParamsMonitoringPegawai(array("A.PEGAWAI_ID" => $reqId)); 
$pegawai->firstRow();
//echo $pegawai->query;exit;
$tempNama= $pegawai->getField("NAMA");
$tempJabatanSaatIni= $pegawai->getField("NAMA_JAB_STRUKTURAL");
$tempUnitKerjaSaatIni= $pegawai->getField("SATKER");

//set penilain pegawai
$statement= " AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = ".$reqTahun." AND A.PEGAWAI_ID = ".$reqId;
$set_detil= new Penilaian();
$arrPsikologi=$arrKompetensi="";
$index_psikologi= $index_kompotensi= 0;
$set_detil->selectByParams(array(), -1, -1, $statement, " ORDER BY A.ASPEK_ID");
//echo $set_detil->errorMsg;exit;
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$reqRowId= $set_detil->getField("PENILAIAN_ID");
	$tempSatkerTesId= $set_detil->getField("SATKER_TES_ID");
	$tempAspekId= $set_detil->getField("ASPEK_ID");
	//echo $tempAspekId;exit;
	if($tempAspekId == "1")
	{
		$set_penilaian= new PenilaianDetil();
		$set_penilaian->selectByParamsMonitoringPenilaian(array(), -1, -1, "", $reqRowId);
		//$set_penilaian->selectByParamsMonitoringPenilaianModif(array(), -1, -1, " AND B.ASPEK_ID = ".$tempAspekId." AND D.SATKER_TES_ID = '".$tempSatkerTesId."'", $reqRowId);
		//echo $set_penilaian->errorMsg;exit;
		//echo $set_penilaian->query;exit;
		
		while($set_penilaian->nextRow())
		{
			$arrPsikologi[$index_psikologi]["NAMA"] = $set_penilaian->getField("NAMA");
			$arrPsikologi[$index_psikologi]["NILAI_STANDAR"] = $set_penilaian->getField("NILAI_STANDAR");
			$arrPsikologi[$index_psikologi]["BOBOT"] = $set_penilaian->getField("BOBOT");
			$arrPsikologi[$index_psikologi]["ATRIBUT_ID"] = $set_penilaian->getField("ATRIBUT_ID");
			$arrPsikologi[$index_psikologi]["ATRIBUT_ID_PARENT"] = $set_penilaian->getField("ATRIBUT_ID_PARENT");
			$arrPsikologi[$index_psikologi]["ATRIBUT_GROUP"] = $set_penilaian->getField("ATRIBUT_GROUP");
			$arrPsikologi[$index_psikologi]["PENILAIAN_DETIL_ID"] = $set_penilaian->getField("PENILAIAN_DETIL_ID");
			$arrPsikologi[$index_psikologi]["PENILAIAN_ID"] = $set_penilaian->getField("PENILAIAN_ID");
			$arrPsikologi[$index_psikologi]["NILAI"] = $set_penilaian->getField("NILAI");
			$arrPsikologi[$index_psikologi]["GAP"] = $set_penilaian->getField("GAP");
			$arrPsikologi[$index_psikologi]["JUMLAH_PENILAIAN_DETIL"] = 10;
			
			$tempNilaiStandar= $arrPsikologi[$index_psikologi]["NILAI_STANDAR"];
			$tempAtributId= $arrPsikologi[$index_psikologi]["ATRIBUT_ID"];
			$tempAtributIdParent= $arrPsikologi[$index_psikologi]["ATRIBUT_ID_PARENT"];
			$tempAtributGroup= $arrPsikologi[$index_psikologi]["ATRIBUT_GROUP"];
			$tempNilai= $arrPsikologi[$index_psikologi]["NILAI"];
			$tempGap= $arrPsikologi[$index_psikologi]["GAP"];
			
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
			
			$arrPsikologi[$index_psikologi]["KETERANGAN_IKK"] = $tempKeteranganIkk;
			$index_psikologi++;
		}
		unset($set_penilaian);
		
		$statement_ikk= " AND D.PENILAIAN_ID = ".$reqRowId." AND B.ASPEK_ID = ".$tempAspekId." AND X.KODE_UNKER = '".$tempSatkerTesId."'";
		$set_nilai_ikk= new PenilaianDetil();
		$set_nilai_ikk->selectByParamsPersonalIkkJpm(array(), -1,-1, $statement_ikk);
		//echo $set_nilai_ikk->query;exit;
		$set_nilai_ikk->firstRow();
		$tempTotalIkk= $set_nilai_ikk->getField("NILAI_IKK_PERSEN");
		$tempTotalJpm= $set_nilai_ikk->getField("NILAI_JPM_PERSEN");
		unset($set_nilai_ikk);
		
		$arrPsikologi[0]["JPM"] = $tempTotalJpm;
		$arrPsikologi[0]["IKK"] = $tempTotalIkk;
	}
	elseif($tempAspekId == "2")
	{
		$set_penilaian= new PenilaianDetil();
		$set_penilaian->selectByParamsMonitoringPenilaian(array(), -1, -1, "", $reqRowId);
		//$set_penilaian->selectByParamsMonitoringPenilaianModif(array(), -1, -1, " AND B.ASPEK_ID = ".$tempAspekId." AND D.SATKER_TES_ID = '".$tempSatkerTesId."'", $reqRowId);
		//echo $set_penilaian->errorMsg;exit;
		//echo $set_penilaian->query;exit;
		
		while($set_penilaian->nextRow())
		{
			$arrKompetensi[$index_kompotensi]["NAMA"] = $set_penilaian->getField("NAMA");
			$arrKompetensi[$index_kompotensi]["NILAI_STANDAR"] = $set_penilaian->getField("NILAI_STANDAR");
			$arrKompetensi[$index_kompotensi]["BOBOT"] = $set_penilaian->getField("BOBOT");
			$arrKompetensi[$index_kompotensi]["ATRIBUT_ID"] = $set_penilaian->getField("ATRIBUT_ID");
			$arrKompetensi[$index_kompotensi]["ATRIBUT_ID_PARENT"] = $set_penilaian->getField("ATRIBUT_ID_PARENT");
			$arrKompetensi[$index_kompotensi]["ATRIBUT_GROUP"] = $set_penilaian->getField("ATRIBUT_GROUP");
			$arrKompetensi[$index_kompotensi]["PENILAIAN_DETIL_ID"] = $set_penilaian->getField("PENILAIAN_DETIL_ID");
			$arrKompetensi[$index_kompotensi]["PENILAIAN_ID"] = $set_penilaian->getField("PENILAIAN_ID");
			$arrKompetensi[$index_kompotensi]["NILAI"] = $set_penilaian->getField("NILAI");
			$arrKompetensi[$index_kompotensi]["GAP"] = $set_penilaian->getField("GAP");
			$arrKompetensi[$index_kompotensi]["JUMLAH_PENILAIAN_DETIL"] = 10;
			
			$tempNilaiStandar= $arrKompetensi[$index_kompotensi]["NILAI_STANDAR"];
			$tempAtributId= $arrKompetensi[$index_kompotensi]["ATRIBUT_ID"];
			$tempAtributIdParent= $arrKompetensi[$index_kompotensi]["ATRIBUT_ID_PARENT"];
			$tempAtributGroup= $arrKompetensi[$index_kompotensi]["ATRIBUT_GROUP"];
			$tempNilai= $arrKompetensi[$index_kompotensi]["NILAI"];
			$tempGap= $arrKompetensi[$index_kompotensi]["GAP"];
			
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
			
			$arrKompetensi[$index_kompotensi]["KETERANGAN_IKK"] = $tempKeteranganIkk;
			$index_kompotensi++;
		}
		unset($set_penilaian);
		
		$statement_ikk= " AND D.PENILAIAN_ID = ".$reqRowId." AND B.ASPEK_ID = ".$tempAspekId." AND X.KODE_UNKER = '".$tempSatkerTesId."'";
		$set_nilai_ikk= new PenilaianDetil();
		$set_nilai_ikk->selectByParamsPersonalIkkJpm(array(), -1,-1, $statement_ikk);
		//echo $set_nilai_ikk->query;exit;
		$set_nilai_ikk->firstRow();
		$tempTotalIkk= $set_nilai_ikk->getField("NILAI_IKK_PERSEN");
		$tempTotalJpm= $set_nilai_ikk->getField("NILAI_JPM_PERSEN");
		unset($set_nilai_ikk);
		
		$arrKompetensi[0]["JPM"] = $tempTotalJpm;
		$arrKompetensi[0]["IKK"] = $tempTotalIkk;
	}
}
unset($set_detil);
//echo "asd";exit;
//print_r($arrKompetensi);exit;
//print_r($arrPsikologi);exit;
//start report
$html_potensi= "
<div style='margin-top:18px;' id='header'>
	<p style='text-decoration:underline; text-align:center; width:950px;'><strong>LAPORAN HASIL POTENSI </strong></p>
</div>
<div id='detil'> 
<p style='font-size:12px'><strong>NAMA 				: ".$tempNama."</strong></p>
<p style='font-size:12px'><strong>JABATAN 			: ".$tempJabatanSaatIni."</strong></p>
<p style='font-size:12px'><strong>UNIT KERJA 		: ".$tempUnitKerjaSaatIni."</strong></p>
<p style='font-size:12px'><strong>JPM  PERJABATAN 	: ";
if(empty($arrPsikologi)){}
else
{
$html_potensi.= $arrPsikologi[0]["JPM"];
}
$html_potensi.= "</strong></p>
<p style='font-size:12px'><strong>IKK  PERJABATAN	: ";
if(empty($arrPsikologi)){}
else
{
$html_potensi.= $arrPsikologi[0]["IKK"];
}
$html_potensi.= "</strong></p>

	<table style='margin-bottom:30px;'>
		<tr>
			<td align='center' rowspan='2'>No</td>
			<td align='center' rowspan='2'>ASPEK POTENSI</td>
			<td align='center' rowspan='2'>STANDART JABATAN</td>
			<td align='center' colspan='4'>NAMA</td>
			<td align='center' rowspan='2'>GAP</td>
			<td align='center' rowspan='2'>JPM</td>
			<td align='center' rowspan='2'>IKK</td>
		</tr>
		<tr>
        	<td align='center' width='60px' >A</td>
        	<td align='center' width='60px' >B</td>
            <td align='center' width='60px' >C</td>
        	<td align='center' width='60px' >D</td>
        </tr>
";
//echo $html_potensi;exit;
	  $tempGroup= "";
	  $index_atribut_parent= 0;
	  for($checkbox_index=0; $checkbox_index < $index_psikologi; $checkbox_index++)
	  {
		$tempNama= $arrPsikologi[$checkbox_index]["NAMA"];
		$tempNilaiStandar= $arrPsikologi[$checkbox_index]["NILAI_STANDAR"];
		$tempBobot= $arrPsikologi[$checkbox_index]["BOBOT"];
		$tempAtributId= $arrPsikologi[$checkbox_index]["ATRIBUT_ID"];
		$tempAtributIdParent= $arrPsikologi[$checkbox_index]["ATRIBUT_ID_PARENT"];
		$tempAtributGroup= $arrPsikologi[$checkbox_index]["ATRIBUT_GROUP"];
		$tempPenilaianDetilId= $arrPsikologi[$checkbox_index]["PENILAIAN_DETIL_ID"];
		$tempPenilaianId= $arrPsikologi[$checkbox_index]["PENILAIAN_ID"];
		$tempNilai= $arrPsikologi[$checkbox_index]["NILAI"];
		$tempGap= $arrPsikologi[$checkbox_index]["GAP"];
		$tempJumlahPenilaianDetil= $arrPsikologi[$checkbox_index]["JUMLAH_PENILAIAN_DETIL"];
		
		if($tempNilaiStandar == "" || $tempNilaiStandar == "0"){}
		else
		$tempJpm= round($tempNilai/$tempNilaiStandar,2);
		
		if($tempNilai == "0")
			$tempIkk= "";
		else
		{
			//kolom IKK (jika gap <= 0, ikk-> 1-jpm) (jika gap >0, ikk = jpm)
			if($tempGap <= 0)
				$tempIkk= 1 - $tempJpm;
			elseif($tempGap > 0)
				$tempIkk= 0;
			else//if($tempGap > 0)
				$tempIkk= $tempJpm;
				
			
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
						
		}
		
		//kondisi parent
		if($tempGroup == $tempAtributGroup)
		{
			$index_atribut++;
		}
		else
		{
			$index_atribut_parent++;
			$index_atribut= 0;
		}
		
		//- total JPM (total jpm / total atribut) -> ditaruh di pojok kanan atas
		//- total IKK (total ikk / total atribut)  -> ditaruh di pojok kanan atas
		
		$tempGroup= $tempAtributGroup;
		
		  if($tempAtributIdParent == "0")
		  {
	$html_potensi.="
		  <tr>
			<td width='10'><b>".romanicNumber($index_atribut_parent)."</b></td>
			<td><b>".$tempNama."</b></td>
	";
			if($tempJumlahPenilaianDetil == 1)
			{
				$temp_checkbox_index= $checkbox_index+1;
				$tempNama= $arrPsikologi[$temp_checkbox_index]["NAMA"];
				$tempNilaiStandar= $arrPsikologi[$temp_checkbox_index]["NILAI_STANDAR"];
				$tempBobot= $arrPsikologi[$temp_checkbox_index]["BOBOT"];
				$tempAtributId= $arrPsikologi[$temp_checkbox_index]["ATRIBUT_ID"];
				$tempAtributIdParent= $arrPsikologi[$temp_checkbox_index]["ATRIBUT_ID_PARENT"];
				$tempAtributGroup= $arrPsikologi[$temp_checkbox_index]["ATRIBUT_GROUP"];
				$tempPenilaianDetilId= $arrPsikologi[$temp_checkbox_index]["PENILAIAN_DETIL_ID"];
				$tempPenilaianId= $arrPsikologi[$temp_checkbox_index]["PENILAIAN_ID"];
				$tempNilai= $arrPsikologi[$temp_checkbox_index]["NILAI"];
				$tempGap= $arrPsikologi[$temp_checkbox_index]["GAP"];
				$tempJumlahPenilaianDetil= $arrPsikologi[$temp_checkbox_index]["JUMLAH_PENILAIAN_DETIL"];
				
				if($tempNilaiStandar == ""){}
				else
				$tempJpm= round($tempNilai/$tempNilaiStandar,2);
				
				if($tempNilai == "0")
					$tempIkk= "";
				else
				{
					//kolom IKK (jika gap <= 0, ikk-> 1-jpm) (jika gap >0, ikk = jpm)
					if($tempGap <= 0)
						$tempIkk= 1 - $tempJpm;
					elseif($tempGap > 0)
						$tempIkk= 0;
					else//if($tempGap > 0)
						$tempIkk= $tempJpm;
						
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
				}
				
				$arrChecked= radioPenilaianInfo($tempNilai);
			$html_potensi.="
			<td align='center'>".NolToNone($tempNilaiStandar)."&nbsp;</td>
			<td align='center'>".$arrChecked[0]."</td>
			<td align='center'>".$arrChecked[1]."</td>
			<td align='center'>".$arrChecked[2]."</td>
			<td align='center'>".$arrChecked[3]."</td>
			<td align='center'>".$tempGap."</td>
			<td align='center'>".NolToNone($tempJpm)."&nbsp;</td>
			<td align='center'>".$tempIkk."&nbsp;</td>
			";
			}
			else
			{
			$html_potensi.="
			<td align='center'>".NolToNone($tempNilaiStandar)."&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			";
			}
			$html_potensi.="
		  </tr>
		  ";
		  }
		  else
		  {
			  if($tempJumlahPenilaianDetil <= 1){}
			  else
			  {
			  $arrChecked= radioPenilaianInfo($tempNilai);
          $html_potensi.="
		  <tr>
			<td width='10'>
				".$index_atribut."
			</td>
			<td>".$tempNama."</td>
			<td align='center'>".NolToNone($tempNilaiStandar)."&nbsp;</td>
			<td align='center'>".$arrChecked[0]."</td>
			<td align='center'>".$arrChecked[1]."</td>
			<td align='center'>".$arrChecked[2]."</td>
			<td align='center'>".$arrChecked[3]."</td>
			<td align='center'>".$tempGap."</td>
			<td align='center'>".NolToNone($tempJpm)."&nbsp;</td>
			<td align='center'>".$tempIkk."&nbsp;</td>
		  </tr>
		  ";
			  }
		  }
	  $tempTotalBobot+= $tempBobot;
	  }
                              
	$html_potensi.="
	</table>
	</div> <!-- END DETIL -->
";

//==============================================================

$html_kompetensi= "
<div style='margin-top:18px;' id='header'>
	<p style='text-decoration:underline; text-align:center; width:950px;'><strong>LAPORAN HASIL KOMPETENSI </strong></p>
</div>
<div id='detil'> 
<p style='font-size:12px'><strong>NAMA 				: ".$tempNama."</strong></p>
<p style='font-size:12px'><strong>JABATAN 			: ".$tempJabatanSaatIni."</strong></p>
<p style='font-size:12px'><strong>UNIT KERJA 		: ".$tempUnitKerjaSaatIni."</strong></p>
<p style='font-size:12px'><strong>JPM  PERJABATAN 	: ";
if(empty($arrKompetensi)){}
else
{
$html_kompetensi.= $arrKompetensi[0]["JPM"];
}
$html_kompetensi.= "</strong></p>
<p style='font-size:12px'><strong>IKK  PERJABATAN	: ";
if(empty($arrKompetensi)){}
else
{
$html_kompetensi.= $arrKompetensi[0]["IKK"];
}
$html_kompetensi.= "</strong></p>
	<table style='margin-bottom:30px;'>
		<tr>
			<td align='center' rowspan='2'>No</td>
			<td align='center' rowspan='2'>ASPEK KOMPETENSI</td>
			<td align='center' rowspan='2'>STANDART JABATAN</td>
			<td align='center' colspan='4'>NAMA</td>
			<td align='center' rowspan='2'>GAP</td>
			<td align='center' rowspan='2'>JPM</td>
			<td align='center' rowspan='2'>IKK</td>
		</tr>
		<tr>
        	<td align='center' width='60px' >A</td>
        	<td align='center' width='60px' >B</td>
            <td align='center' width='60px' >C</td>
        	<td align='center' width='60px' >D</td>
        </tr>
";

	  $tempGroup= "";
	  $index_atribut_parent= 0;
	  for($checkbox_index=0; $checkbox_index < $index_kompotensi; $checkbox_index++)
	  {
		$tempNama= $arrKompetensi[$checkbox_index]["NAMA"];
		$tempNilaiStandar= $arrKompetensi[$checkbox_index]["NILAI_STANDAR"];
		$tempBobot= $arrKompetensi[$checkbox_index]["BOBOT"];
		$tempAtributId= $arrKompetensi[$checkbox_index]["ATRIBUT_ID"];
		$tempAtributIdParent= $arrKompetensi[$checkbox_index]["ATRIBUT_ID_PARENT"];
		$tempAtributGroup= $arrKompetensi[$checkbox_index]["ATRIBUT_GROUP"];
		$tempPenilaianDetilId= $arrKompetensi[$checkbox_index]["PENILAIAN_DETIL_ID"];
		$tempPenilaianId= $arrKompetensi[$checkbox_index]["PENILAIAN_ID"];
		$tempNilai= $arrKompetensi[$checkbox_index]["NILAI"];
		$tempGap= $arrKompetensi[$checkbox_index]["GAP"];
		$tempJumlahPenilaianDetil= $arrKompetensi[$checkbox_index]["JUMLAH_PENILAIAN_DETIL"];
		
		if($tempNilaiStandar == "" || $tempNilaiStandar == "0"){}
		else
		$tempJpm= round($tempNilai/$tempNilaiStandar,2);
		
		if($tempNilai == "0")
			$tempIkk= "";
		else
		{
			//kolom IKK (jika gap <= 0, ikk-> 1-jpm) (jika gap >0, ikk = jpm)
			if($tempGap <= 0)
				$tempIkk= 1 - $tempJpm;
			elseif($tempGap > 0)
				$tempIkk= 0;
			else//if($tempGap > 0)
				$tempIkk= $tempJpm;
				
			
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
						
		}
		
		//kondisi parent
		if($tempGroup == $tempAtributGroup)
		{
			$index_atribut++;
		}
		else
		{
			$index_atribut_parent++;
			$index_atribut= 0;
		}
		
		//- total JPM (total jpm / total atribut) -> ditaruh di pojok kanan atas
		//- total IKK (total ikk / total atribut)  -> ditaruh di pojok kanan atas
		
		$tempGroup= $tempAtributGroup;
		
		  if($tempAtributIdParent == "0")
		  {
	$html_kompetensi.="
		  <tr>
			<td width='10'><b>".romanicNumber($index_atribut_parent)."</b></td>
			<td><b>".$tempNama."</b></td>
	";
			if($tempJumlahPenilaianDetil == 1)
			{
				$temp_checkbox_index= $checkbox_index+1;
				$tempNama= $arrKompetensi[$temp_checkbox_index]["NAMA"];
				$tempNilaiStandar= $arrKompetensi[$temp_checkbox_index]["NILAI_STANDAR"];
				$tempBobot= $arrKompetensi[$temp_checkbox_index]["BOBOT"];
				$tempAtributId= $arrKompetensi[$temp_checkbox_index]["ATRIBUT_ID"];
				$tempAtributIdParent= $arrKompetensi[$temp_checkbox_index]["ATRIBUT_ID_PARENT"];
				$tempAtributGroup= $arrKompetensi[$temp_checkbox_index]["ATRIBUT_GROUP"];
				$tempPenilaianDetilId= $arrKompetensi[$temp_checkbox_index]["PENILAIAN_DETIL_ID"];
				$tempPenilaianId= $arrKompetensi[$temp_checkbox_index]["PENILAIAN_ID"];
				$tempNilai= $arrKompetensi[$temp_checkbox_index]["NILAI"];
				$tempGap= $arrKompetensi[$temp_checkbox_index]["GAP"];
				$tempJumlahPenilaianDetil= $arrKompetensi[$temp_checkbox_index]["JUMLAH_PENILAIAN_DETIL"];
				
				if($tempNilaiStandar == ""){}
				else
				$tempJpm= round($tempNilai/$tempNilaiStandar,2);
				
				if($tempNilai == "0")
					$tempIkk= "";
				else
				{
					//kolom IKK (jika gap <= 0, ikk-> 1-jpm) (jika gap >0, ikk = jpm)
					if($tempGap <= 0)
						$tempIkk= 1 - $tempJpm;
					elseif($tempGap > 0)
						$tempIkk= 0;
					else//if($tempGap > 0)
						$tempIkk= $tempJpm;
						
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
				}
				
				$arrChecked= radioPenilaianInfo($tempNilai);
			$html_kompetensi.="
			<td align='center'>".NolToNone($tempNilaiStandar)."&nbsp;</td>
			<td align='center'>".$arrChecked[0]."</td>
			<td align='center'>".$arrChecked[1]."</td>
			<td align='center'>".$arrChecked[2]."</td>
			<td align='center'>".$arrChecked[3]."</td>
			<td align='center'>".$tempGap."</td>
			<td align='center'>".NolToNone($tempJpm)."&nbsp;</td>
			<td align='center'>".$tempIkk."&nbsp;</td>
			";
			}
			else
			{
			$html_kompetensi.="
			<td align='center'>".NolToNone($tempNilaiStandar)."&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			<td align='center'>&nbsp;</td>
			";
			}
			$html_kompetensi.="
		  </tr>
		  ";
		  }
		  else
		  {
			  if($tempJumlahPenilaianDetil <= 1){}
			  else
			  {
			  $arrChecked= radioPenilaianInfo($tempNilai);
          $html_kompetensi.="
		  <tr>
			<td width='10'>
				".$index_atribut."
			</td>
			<td>".$tempNama."</td>
			<td align='center'>".NolToNone($tempNilaiStandar)."&nbsp;</td>
			<td align='center'>".$arrChecked[0]."</td>
			<td align='center'>".$arrChecked[1]."</td>
			<td align='center'>".$arrChecked[2]."</td>
			<td align='center'>".$arrChecked[3]."</td>
			<td align='center'>".$tempGap."</td>
			<td align='center'>".NolToNone($tempJpm)."&nbsp;</td>
			<td align='center'>".$tempIkk."&nbsp;</td>
		  </tr>
		  ";
			  }
		  }
	  $tempTotalBobot+= $tempBobot;
	  }
                              
	$html_kompetensi.="
	</table>
	</div> <!-- END DETIL -->
";

//==============================================================
//==============================================================
//==============================================================
include("../WEB/lib/MPDF60/mpdf.php");

//$mpdf=new mPDF('c','A4'); 
$mpdf = new mPDF('',    // mode - default ''
 '',    // format - A4, for example, default ''
 0,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/cetak_assesment.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

if($index_psikologi > 0)
{
	$mpdf->WriteHTML($html_potensi,2);
}

if($index_kompotensi > 0)
{
	$mpdf->AddPage();
	$mpdf->WriteHTML($html_kompetensi,2);
}

$mpdf->Output('cetak_assesment.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
?>
<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalTesSimulasiAsesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");
include_once("../WEB/classes/base/JadwalPegawai.php");
include_once("../WEB/lib/MPDF60/mpdf.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$urlPengaturan= $data->urlConfig->main->urlPengaturan;
	  
$reqId = httpFilterGet("reqId");

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;

$tempTanggalTes= getFormattedDate($set->getField('TANGGAL_TES'));
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$tempJumlahPeserta= $set->getField('JUMLAH_PEGAWAI');
$tempJumlahAsesor= $set->getField('JUMLAH_ASESOR');

$mpdf = new mPDF('c','LEGAL');
$mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            2, // margin_left
            2, // margin right
            2, // margin top
            2, // margin bottom
            2, // margin header
            2);  
//$mpdf=new mPDF('c','A4'); 
//$mpdf=new mPDF('utf-8', array(297,420));

$mpdf->mirroMargins = true;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/simulasihasil.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
//
$html= "
<table width='100%'>
<tr>
	<td colspan='3' style='text-align:center; background-color:#CCC; border:none !important'>&nbsp;</td>
</tr>
</table>

<table width='100%'>
<tr>
	<td width='200px'>Formula</td>
	<td width='2px'>:</td>
	<td><label id='reqFormulaEselon'>".$tempFormulaEselon."</label></td>
</tr>
<tr>
	<td>Tanggal Tes</td>
	<td>:</td>
	<td>".$tempTanggalTes."</td>
</tr>
<tr>
	<td>Acara</td>
	<td>:</td>
	<td>".$tempAcara."</td>
</tr>
<tr>
	<td>Peserta</td>
	<td>:</td>
	<td>".$tempJumlahPeserta." Peserta</td>
</tr>
<tr>
	<td>Tempat</td>
	<td>:</td>
	<td>".$tempTempat."</td>
</tr>
<tr>
	<td>Alamat</td>
	<td>:</td>
	<td>".$tempAlamat."</td>
</tr>
<tr>
	<td>Jumlah Asesor</td>
	<td>:</td>
	<td>".$tempJumlahAsesor." Asesor</td>
</tr>
</table>
<table class='gradient-style' style='width:100%; margin-left:-1px'>
    <thead>
    <tr>
        <th scope='col' style='width:30px; text-align:center'>No</th>
        <th scope='col' style='width:120px; text-align:center'>Waktu</th>
        <th scope='col' style='text-align:center'>Keterangan</th>
    </tr>
    </thead>
    <tbody id='reqAddInfoTable'>
";

$reqNo=1;
//master_jadwal_add_simulasi_hasil_tanpa_penggalian
$set = new JadwalTesSimulasiAsesor();
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$statement.= " AND A.PENGGALIAN_ID IS NULL";

$set->selectByParamsMonitoring(array(), -1, -1, $statement);
while($set->nextRow())
{
	$tempRowId= $set->getField("PUKUL_AWAL");
	$tempSimulasiNama= $set->getField("NAMA_SIMULASI");
	$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");

$html.="
<tr>
	<td style='text-align:center'>".$reqNo."</td>
    <td style='text-align:center'>".$tempJam."</td>
    <td style='text-align:center'>".$tempSimulasiNama."</td>
</tr>";
$reqNo++;
}
unset($set);

//master_jadwal_add_simulasi_hasil_penggalian_tanpa_group
$set= new JadwalTesSimulasiAsesor();
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND (A.STATUS_GROUP IS NULL OR A.STATUS_GROUP = '')";

$set->selectByParamsMonitoring(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempRowId= $set->getField("PUKUL_AWAL");
$tempSimulasiNama= $set->getField("NAMA_SIMULASI");
$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");
$tempJamAwal= $set->getField("PUKUL_AWAL");
$tempKelompokJumlah= $set->getField("KELOMPOK_JUMLAH");
$tempPenggalianId= $set->getField("PENGGALIAN_ID");
unset($set);

$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND B.PENGGALIAN_ID = ".$tempPenggalianId;
$set= new JadwalTesSimulasiAsesor();
$set->selectByParamsJumlahRowAsesorKelompok($statement);
$set->firstRow();
$tempJumlahRowKelompok= $set->getField("JUMLAH_DATA");

$statement= " AND A.ID_JADWAL = $reqId AND D.PENGGALIAN_ID = ".$tempPenggalianId;
$set= new JadwalTesSimulasiAsesor();
$set->selectByParamsJumlahRowAsesorPegawaiKelompok($statement);
$set->firstRow();
$tempJumlahRowPegawaiKelompok= $set->getField("JUMLAH_DATA");

$index_loop=0;
$arrAsesorDalamKelompok="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND B.PENGGALIAN_ID = ".$tempPenggalianId;
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsAsesorDalamKelompok($statement);
//echo $set_detil->query;exit;
$index_kelompok=0;
$tempKelompoInfoKondisi= "";
while($set_detil->nextRow())
{
	$tempKelompoInfo= $set_detil->getField("KELOMPOK_INFO");
	
	if($tempKelompoInfoKondisi == $tempKelompoInfo)
	{
		$index_kelompok++;
	}
	else
	{
		$index_kelompok=0;
	}
	
	$arrAsesorDalamKelompok[$index_loop]["KELOMPOK_INFO_ID"]= $tempKelompoInfo."-".$index_kelompok;
	$arrAsesorDalamKelompok[$index_loop]["KELOMPOK_INFO"]= $tempKelompoInfo;
	$arrAsesorDalamKelompok[$index_loop]["NAMA_ASESOR"]= $set_detil->getField("NAMA_ASESOR");
	$index_loop++;
	$tempKelompoInfoKondisi= $tempKelompoInfo;
}
//print_r($arrAsesorDalamKelompok);exit;

$index_loop=0;
$arrPegawaiDalamKelompok="";
$statement= " AND A.ID_JADWAL = ".$reqId." AND D.PENGGALIAN_ID = ".$tempPenggalianId;
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsPegawaiDalamKelompok($statement);
//echo $set_detil->query;exit;
$index_kelompok=0;
$tempKelompoInfoKondisi= "";
while($set_detil->nextRow())
{
	$tempKelompoInfo= $set_detil->getField("KELOMPOK_INFO");
	
	if($tempKelompoInfoKondisi == $tempKelompoInfo)
	{
		$index_kelompok++;
	}
	else
	{
		$index_kelompok=0;
	}
	
	$arrPegawaiDalamKelompok[$index_loop]["KELOMPOK_INFO_ID"]= $tempKelompoInfo."-".$index_kelompok;
	$arrPegawaiDalamKelompok[$index_loop]["KELOMPOK_INFO"]= $tempKelompoInfo;
	$arrPegawaiDalamKelompok[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$index_loop++;
	$tempKelompoInfoKondisi= $tempKelompoInfo;
}

$html.="
<tr>
    <td style='text-align:center'>".$reqNo."</td>
    <td style='text-align:center'>".$tempJam."</td>
    ";
	
		if($tempKelompokJumlah == 'Tidak Ada'){}
	else
	{
		$tempRowTanggal= $tempKelompokJumlah + $tempJumlahRowPegawaiKelompok;
    $html.="
    <td>
        <table style='width:100%; border:none !important'>
        <tr>
            <td colspan='".$tempRowTanggal."' style='text-align:center; background-color:#CCC; border:none !important'>".$tempSimulasiNama."</td>
        </tr>
        <tr>
		";
		
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
            $html.="
            <td style='text-align:center; background-color:#09F; color:#FFF; border:none !important'>Kel. ".$tempNo."</td>
            ";
			}
            $html.="
        </tr>
        <tr>
        	";
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
            $html.="
            <td style='text-align:center; border:none !important'>Ruang ".$tempNo."</td>
            ";
			}
            $html.="
        </tr>
        ";
		// buat asesor dalam kelompok
		for($x=0; $x < $tempJumlahRowKelompok; $x++)
		{
        $html.="
        <tr>
        	";
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
				$tempNamaKelompoAsesor= 'Kel. '.$tempNo.'-'.$x;
				
				$arrayAsesorKelompokKey= '';
				$arrayAsesorKelompokKey= in_array_column($tempNamaKelompoAsesor, 'KELOMPOK_INFO_ID', $arrAsesorDalamKelompok);
				
				if($arrayAsesorKelompokKey == '')
				{
			$html.="
            <td style='text-align:center; background-color:#6F6; border:none !important'></td>
            ";
				}
				else
				{
					for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayAsesorKelompokKey); $index_detil_asesor_kelompok++)
					{
						$index_row= $arrayAsesorKelompokKey[$index_detil_asesor_kelompok];
						$tempNamaAsesorDalamKelompok= $arrAsesorDalamKelompok[$index_row]['NAMA_ASESOR'];
			$html.="
            <td style='text-align:center; background-color:#6F6; border:none !important'>".$tempNamaAsesorDalamKelompok."</td>
            ";
					}
				}
			}
			$html.="
        </tr>
        ";
		}
        
		// buat pegawai dalam kelompok
		for($x=0; $x < $tempJumlahRowKelompok; $x++)
		{
        $html.="
        <tr>
        	";
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
				$tempNamaKelompoAsesor= 'Kel. '.$tempNo.'-'.$x;
				
				$arrayPegawaiKelompokKey= '';
				$arrayPegawaiKelompokKey= in_array_column($tempNamaKelompoAsesor, 'KELOMPOK_INFO_ID', $arrPegawaiDalamKelompok);
				
				if($arrayPegawaiKelompokKey == '')
				{
			$html.="
            <td style='text-align:center; background-color:#F66; border:none !important'></td>
            ";
				}
				else
				{
					for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayPegawaiKelompokKey); $index_detil_asesor_kelompok++)
					{
						$index_row= $arrayPegawaiKelompokKey[$index_detil_asesor_kelompok];
						$tempNamaPegawaiDalamKelompok= $arrPegawaiDalamKelompok[$index_row]['PEGAWAI_NAMA'];
			$html.="
            <td style='border:none !important'>".$tempNamaPegawaiDalamKelompok."</td>
            ";
					}
				}
			}
			
		}
        $html.="
        </table>
    </td>
    ";
	}

    $html.="
</tr>
";

//master_jadwal_add_simulasi_hasil_penggalian_group
$set = new JadwalTesSimulasiAsesor();
$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$statement.= " AND A.PENGGALIAN_ID IS NOT NULL AND A.STATUS_GROUP = '1'";
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempRowId= $set->getField("PUKUL_AWAL");
$tempSimulasiNama= $set->getField("NAMA_SIMULASI");
$tempJam= $set->getField("PUKUL_AWAL")." s/d ".$set->getField("PUKUL_AKHIR");
$tempJamAwal= $set->getField("PUKUL_AWAL");
$tempKelompokJumlah= $set->getField("KELOMPOK_JUMLAH");
$tempPenggalianId= $set->getField("PENGGALIAN_ID");
$tempJumlahRowKelompok= $tempKelompokJumlah;

$index_loop=0;
$arrAsesorDalamKelompok="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND B.PENGGALIAN_ID = ".$tempPenggalianId;
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsAsesorDalamKelompok($statement);
//echo $set_detil->query;exit;
$index_kelompok=0;
$tempKelompoInfoKondisi= "";
while($set_detil->nextRow())
{
	$tempKelompoInfo= $set_detil->getField("KELOMPOK_INFO");
	
	if($tempKelompoInfoKondisi == $tempKelompoInfo)
	{
		$index_kelompok++;
	}
	else
	{
		$index_kelompok=0;
	}
	
	$arrAsesorDalamKelompok[$index_loop]["KELOMPOK_INFO_ID"]= $tempKelompoInfo."-".$index_kelompok;
	$arrAsesorDalamKelompok[$index_loop]["KELOMPOK_INFO"]= $tempKelompoInfo;
	$arrAsesorDalamKelompok[$index_loop]["NAMA_ASESOR"]= $set_detil->getField("NAMA_ASESOR");
	$index_loop++;
	$tempKelompoInfoKondisi= $tempKelompoInfo;
}
//print_r($arrAsesorDalamKelompok);exit;

$index_loop=0;
$arrPegawaiDalamKelompok="";
$statement= " AND A.ID_JADWAL = ".$reqId." AND D.PENGGALIAN_ID = ".$tempPenggalianId;
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsPegawaiGroupDalamKelompok($statement);
//echo $set_detil->query;exit;
$index_kelompok=0;
$tempKelompoInfoKondisi= "";
while($set_detil->nextRow())
{
	$tempKelompoInfo= $set_detil->getField("KELOMPOK_INFO");
	$arrPegawaiDalamKelompok[$index_loop]["KELOMPOK_INFO_ID"]= $tempKelompoInfo."-".$set_detil->getField("PUKUL1");
	$arrPegawaiDalamKelompok[$index_loop]["KELOMPOK_INFO"]= $tempKelompoInfo;
	$arrPegawaiDalamKelompok[$index_loop]["PEGAWAI_NAMA"]= $set_detil->getField("PEGAWAI_NAMA");
	$index_loop++;
}
//print_r($arrPegawaiDalamKelompok);exit;

$index_loop=0;
$arrJadwalWaktuAsesor="";
$statement= " AND A.JADWAL_TES_ID = ".$reqId." AND B.STATUS_GROUP = '1'";
$set_detil= new JadwalTesSimulasiAsesor();
$set_detil->selectByParamsAsesorPukulMonitoring(array(), -1,-1, $statement);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrJadwalWaktuAsesor[$index_loop]["JADWAL_TES_ID"]= $set_detil->getField("JADWAL_TES_ID");
	$arrJadwalWaktuAsesor[$index_loop]["KELOMPOK_JUMLAH"]= $set_detil->getField("KELOMPOK_JUMLAH");
	$arrJadwalWaktuAsesor[$index_loop]["PUKUL_AWAL"]= $set_detil->getField("PUKUL_AWAL");
	$arrJadwalWaktuAsesor[$index_loop]["PUKUL_AKHIR"]= $set_detil->getField("PUKUL_AKHIR");
	$index_loop++;
}
$jumlah_waktu_asesor= $index_loop;

$html.="
<tr>
    <td style='text-align:center'>&nbsp;</td>
    <td style='text-align:center'>&nbsp;</td>
    ";
	
	if($tempKelompokJumlah == 'Tidak Ada'){}
	else
	{
		$tempRowTanggal= $tempKelompokJumlah + $tempJumlahRowPegawaiKelompok;
		
    $html.="
    <td>
        <table style='width:100%; border:none !important'>
        <tr>
            <td colspan='".$tempRowTanggal."' style='text-align:center; background-color:#CCC; border:none !important'>".$tempSimulasiNama."</td>
        </tr>
        <tr>
		";
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
            $html.="
            <td style='text-align:center; background-color:#09F; color:#FFF; border:none !important; width:200px'>Kel. ".$tempNo."</td>
			";
			}
        $html.="
        </tr>
		<tr>
		";
			$x=0;
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
				$tempNamaKelompoAsesor= 'Kel. '.$tempNo.'-'.$x;
				
				$arrayAsesorKelompokKey= '';
				$arrayAsesorKelompokKey= in_array_column($tempNamaKelompoAsesor, 'KELOMPOK_INFO_ID', $arrAsesorDalamKelompok);
				
				if($arrayAsesorKelompokKey == '')
				{
			$html.="
			<td style='text-align:center; background-color:#6F6; border:none !important'></td>
			";
				}
				else
				{
					for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < count($arrayAsesorKelompokKey); $index_detil_asesor_kelompok++)
					{
						$index_row= $arrayAsesorKelompokKey[$index_detil_asesor_kelompok];
						$tempNamaAsesorDalamKelompok= $arrAsesorDalamKelompok[$index_row]['NAMA_ASESOR'];
			$html.="
			<td style='text-align:center; background-color:#6F6; border:none !important'>".$tempNamaAsesorDalamKelompok."</td>
			";
					}
				}
			}
			$html.="
		</tr>
        </table>
    </td>
    ";
	}
$html.="
</tr>
";

// buat jadwal waktu group
for($index_waktu=0; $index_waktu < $jumlah_waktu_asesor; $index_waktu++)
{
    $tempPukulAwal= $arrJadwalWaktuAsesor[$index_waktu]['PUKUL_AWAL'];
	$tempPukulAkhir= $arrJadwalWaktuAsesor[$index_waktu]['PUKUL_AKHIR'];
	$tempJam= $tempPukulAwal.' s/d '.$tempPukulAkhir;
$html.="
<tr>
    <td style='text-align:center'>".$reqNo."</td>
    <td style='text-align:center'>".$tempJam."</td>
    <td>
        <table style='width:100%; border:none !important'>
        <tr>
";
			for($i=0; $i < $tempKelompokJumlah; $i++)
			{
				$tempNo= $i+1;
				$tempNamaKelompoAsesor= 'Kel. '.$tempNo.'-'.$tempPukulAwal;
				
				$arrayPegawaiKelompokKey= '';
				$arrayPegawaiKelompokKey= in_array_column($tempNamaKelompoAsesor, 'KELOMPOK_INFO_ID', $arrPegawaiDalamKelompok);
				if($arrayPegawaiKelompokKey == '')
				{
			$html.="
            <td style='text-align:center; background-color:#F66; border:none !important; width:200px'></td>
            ";
				}
				else
				{
					for($index_detil_asesor_kelompok=0; $index_detil_asesor_kelompok < 1; $index_detil_asesor_kelompok++)
					{
						$index_row= $arrayPegawaiKelompokKey[$index_detil_asesor_kelompok];
						$tempNamaPegawaiDalamKelompok= $arrPegawaiDalamKelompok[$index_row]['PEGAWAI_NAMA'];
			$html.="
            <td style='border:none !important; width:200px'>".$tempNamaPegawaiDalamKelompok."</td>
            ";
					}
				}
			}
			$html.="
        </tr>
        </table>
    </td>
</tr>
";
	$reqNo++;
}
$tempJam= $tempPukulAkhir.' - Selesai';

$html.="
<tr>
    <td style='text-align:center'>".$reqNo."</td>
    <td style='text-align:center'>".$tempJam."</td>
    <td style='text-align:center; background-color:#CCC; border:none !important'>Meeting Asesor</td>
</tr>
";

$html.="
    </tbody>
</table>
";
$mpdf->WriteHTML($html,2);

$mpdf->Output('simulasi_hasil.pdf','I');
exit;
?>
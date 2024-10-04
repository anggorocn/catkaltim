<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Kategori.php");
include_once("../WEB/classes/base-skp/PegawaiPenilai.php");
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");

$pegawai_dinilai = new PegawaiPenilai();
$pegawai_penilai = new PegawaiPenilai();
$pegawai_atasan = new PegawaiPenilai();
$periode_penilaian = new PeriodePenilaian();

$reqId = httpFilterGet("reqId");
$reqTahun = $periode_penilaian->getMaxTahun();

$statement = " AND PEGAWAI_ID_DINILAI = ".$reqId;
$pegawai_dinilai->selectByParamsMonitoringPencapaianCetak($reqTahun, array(), -1, -1, $statement);
$pegawai_dinilai->firstRow();
$tempPegawaiPenilai = $pegawai_dinilai->getField("PEGAWAI_ID_PENILAI");

$pegawai_penilai->selectByParamsPenilaiCetakPrestasi(array("A.IDPEG"=>$tempPegawaiPenilai));
$pegawai_penilai->firstRow();
$tempPegawaiAtasan = $pegawai_penilai->getField("PEGAWAI_ID_PENILAI");
//echo $pegawai_penilai->query;exit;

$pegawai_atasan->selectByParamsPenilaiCetakPrestasi(array("A.IDPEG"=>$tempPegawaiAtasan));
$pegawai_atasan->firstRow();

?>
<?php
//start report
$html = "
<div class='logo-garuda tengah'><img src='../WEB/images/Garuda.bmp' /></div>
<div style='margin-top:18px;' id='header'>
	<p style='text-align:center; width:1450px;'><strong>PENILAIAN PRESTASI KERJA  <br> PEGAWAI NEGERI SIPIL TUGAS BELAJAR <br> KEMENTERIAN DALAM NEGERI <br> REPUBLIK INDONESIA</strong></p>
</div>
<br />
<div id='detil'>
	<table style='margin-bottom:30px;'>
		<tr>
			<td align='center' width='75px'>1</td>
			<td colspan='2'><b>PNS YANG DINILAI</b></td>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan='6'>&nbsp;</td>
			<td align='right' width='40px'>a.</td>
			<td>Nama</td>
			<td colspan='3'>".$pegawai_dinilai->getField("NAMA")."</td>			
		</tr>
		<tr>
			<td align='right' width='40px'>b.</td>
			<td>NIP</td>
			<td colspan='3'>".$pegawai_dinilai->getField("NIP_BARU")."</td>
		</tr>
		<tr>
			<td align='right' width='40px'>c.</td>			
			<td>Pangkat/Gol. Ruang</td>
			<td colspan='3'>".$pegawai_dinilai->getField("NMGOLRUANG").", ".$pegawai_dinilai->getField("GOL_RUANG")."</td>
		</tr>
		<tr>
			<td align='right' width='40px'>d.</td>			
			<td>Jabatan/Pekerjaan</td>
			<td colspan='3'>".$pegawai_dinilai->getField("JABATAN")."</td>
		</tr>
		<tr>
			<td align='right' width='40px'>e.</td>			
			<td>Unit Kerja/Instansi</td>
			<td colspan='3'>".$pegawai_dinilai->getField("SATKER")." KAB. PROBOLINGGO</td>
		</tr>
		<tr>
			<td align='right' width='40px'>g.</td>			
			<td>Jangka Waktu Penilaian</td>
			<td colspan='3'>2 Januari ".$reqTahun." s.d. 31 Desember ".$reqTahun."</td>
		</tr>
		<tr>
			<td align='center' width='75px' rowspan='2' valign='top'>2</td>			
			<td>a.</td>
			<td>Nama Lembaga/Perguruan Tinggi</td>
			<td colspan='3'>-</td>
		</tr>
		<tr>
			<td>b.</td>
			<td>Alamat/Telp</td>
		</tr>
		<tr>
			<td align='center'>3</td>
			<td colspan='5'><b>NILAI PRESTASI KERJA/AKADEMIK</b></td>
		</tr>
		<tr>
			<td rowspan='7'>&nbsp;</td>
			<td colspan='3' rowspan='2' align='center'>Interval Nilai Yang Diberikan</td>
			<td colspan='2' align='center'><b>NILAI YANG DIBERIKAN</b></td>
		</tr>
		<tr>
			<td align='center'><b>ANGKA</b></td>
			<td align='center'><b>SEBUTAN*)</b></td>
		</tr>
		<tr>
			<td>a.</td>
			<td>91 ke atas </td>
			<td>= &nbsp;Amat Baik</td>
			<td rowspan='5' valign='middle' align='center'>&nbsp;</td>
			<td><b>- Amat Baik</b></td>
		</tr>
		<tr>
			<td>b.</td>
			<td>76 - 90 </td>
			<td>= &nbsp;Baik</td>
			<td><b>- Baik</b></td>
		</tr>
		<tr>
			<td>c.</td>
			<td>61 - 75 </td>
			<td>= &nbsp;Cukup</td>
			<td><b>- Cukup</b></td>
		</tr>
		<tr>
			<td>d.</td>
			<td>51 - 60 </td>
			<td>= &nbsp;Kurang</td>
			<td><b>- Kurang</b></td>
		</tr>
		<tr>
			<td>e.</td>
			<td>50 ke bawah </td>
			<td>= &nbsp;Buruk</td>
			<td><b>- Buruk</b></td>
		</tr>
	</table>
</div>
<div id='rekomendasi' style='margin-top:-31px'>
	<table>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>9</td>
			<td>DIBUAT TANGGAL, 31 DESEMBER ".$pegawai_dinilai->getField("TAHUN")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>PEJABAT PENILAI</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>".$pegawai_penilai->getField("NAMA")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>NIP : ".$pegawai_penilai->getField("NIP_BARU")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan='6' valign='top' width='5%'>10.</td>
			<td width='60%'>DITERIMA TANGGAL, 5 JANUARI ".$pegawai_dinilai->getField("TAHUN")."</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td valign='top'>PEGAWAI NEGERI SIPIL <br> YANG DINILAI</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>".$pegawai_dinilai->getField("NAMA")."</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>NIP : ".$pegawai_dinilai->getField("NIP_BARU")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>11</td>
			<td>DITERIMA TANGGAL, 7 JANUARI ".$pegawai_dinilai->getField("TAHUN")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>ATASAN PEJABAT PENILAI</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>".$pegawai_atasan->getField("NAMA")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>NIP : ".$pegawai_atasan->getField("NIP_BARU")."</td>
		</tr>
		
	</table>
</div>
	
	"
?>
<?
	$html .="
 <!-- END DETIL -->

"
?>
<?
//==============================================================
//==============================================================
//==============================================================
include("../WEB/lib/MPDF60/mpdf.php");

//$mpdf=new mPDF('c','LEGAL-L');
$mpdf = new mPDF('c','A3');
$mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            5, // margin_left
            5, // margin right
            10, // margin top
            15, // margin bottom
            10, // margin header
            10);  

// Double-side document - mirror margins
//$mpdf->mirrorMargins = 1;

// Set a simple Footer including the page number
//$mpdf->setFooter('{PAGENO}');

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/pelaporan_pns_tubel.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('pelaporan_pns_tubel.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>
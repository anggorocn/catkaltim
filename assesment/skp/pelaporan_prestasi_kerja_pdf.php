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
$kategori = new Kategori();

$reqId = httpFilterGet("reqId");

$jumlah_kategori = $kategori->getCountByParams();
$kategori->selectByParams(array(), -1, -1, "", " ORDER BY URUT ASC");

$reqTahun = $periode_penilaian->getMaxTahun();

$statement = " AND PEGAWAI_ID_DINILAI = ".$reqId;
$pegawai_dinilai->selectByParamsMonitoringPencapaianCetak($reqTahun, array(), -1, -1, $statement);
$pegawai_dinilai->firstRow();
//echo $pegawai_dinilai->query;exit;
$tempPegawaiPenilai = $pegawai_dinilai->getField("PEGAWAI_ID_PENILAI");

/*$nilai = 0;
$pembagi = 0;
for($i=1;$i<=12;$i++)
{
	$nilai += $pegawai_dinilai->getField("BL".$i);

	if($pegawai_dinilai->getField("BL".$i) == "" || $pegawai_dinilai->getField("BL".$i) == 0)
	{}
	else
		$pembagi += 1;
	//echo $pegawai_dinilai->getField("BL".$i);
}

if($pembagi == 0)
	$nilai = 0;
else
	$nilai = $nilai / $pembagi;

	
$nilai_skp = round((($nilai * 60) / 100), 2);	*/

$pegawai_penilai->selectByParamsPenilaiCetakPrestasi(array("A.IDPEG"=>$tempPegawaiPenilai));
$pegawai_penilai->firstRow();
$tempPegawaiAtasan = $pegawai_penilai->getField("PEGAWAI_ID_PENILAI");

$pegawai_atasan->selectByParamsPenilaiCetakPrestasi(array("A.IDPEG"=>$tempPegawaiAtasan));
$pegawai_atasan->firstRow();
?>
<?php
//start report
$html = "
<div class='logo-garuda tengah'><img src='../WEB/images/Garuda.bmp' /></div>
<div style='margin-top:18px;' id='header'>
	<p style='text-align:center; width:1450px;'><strong>RAHASIA <br> PENILAIAN PRESTASI KERJA <br> PEGAWAI NEGERI SIPIL</strong></p>
</div>
<div id='kop'>
	<table>
		  <tr>
			<td width='300px' align='center'>PEMERINTAH</td>
			<td width='600px'>&nbsp;</td>
			<td width='300px' align='center'>Jangka Waktu Penilaian</td>
		  </tr>
		  <tr>
			<td width='300px' align='center'>KEMENTERIAN DALAM NEGERI <br> REPUBLIK INDONESIA</td>
			<td width='600px'>&nbsp;</td> 	
			<td width='300px' align='center'>2 Januari ".$pegawai_dinilai->getField("TAHUN")." s.d. 31 Desember ".$pegawai_dinilai->getField("TAHUN")."</td>
		  </tr>
	</table>
</div>
<br />
<div id='detil'>
	<table style='margin-bottom:30px;'>
		<tr>
			<td align='center' width='75px'>1</td>
			<td colspan='2'>YANG DINILAI</td>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan='5'>&nbsp;</td>
			<td align='right' width='40px'>a.</td>
			<td width='300px;'>Nama</td>		
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
			<td>Unit Organisasi</td>
			<td colspan='3'>".$pegawai_dinilai->getField("SATKER")." KEMENTERIAN DALAM NEGERI <br> REPUBLIK INDONESIA</td>	
		</tr>
		<tr>
			<td align='center' width='75px'>2</td>
			<td colspan='2'>PEJABAT PENILAI</td>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan='5'>&nbsp;</td>
			<td align='right' width='40px'>a.</td>
			<td>Nama</td>		
			<td colspan='3'>".$pegawai_penilai->getField("NAMA")."</td>		
		</tr>
		<tr>
			<td align='right' width='40px'>b.</td>
			<td>NIP</td>
			<td colspan='3'>".$pegawai_penilai->getField("NIP_BARU")."</td>		
		</tr>
		<tr>
			<td align='right' width='40px'>c.</td>			
			<td>Pangkat/Gol. Ruang</td>
			<td colspan='3'>".$pegawai_penilai->getField("NMGOLRUANG").", ".$pegawai_penilai->getField("GOL_RUANG")."</td>	
		</tr>
		<tr>
			<td align='right' width='40px'>d.</td>			
			<td>Jabatan/Pekerjaan</td>
			<td colspan='3'>".$pegawai_penilai->getField("JABATAN")."</td>	
		</tr>
		<tr>
			<td align='right' width='40px'>e.</td>			
			<td>Unit Organisasi</td>
			<td colspan='3'>".$pegawai_penilai->getField("SATKER")." KEMENTERIAN DALAM NEGERI <br> REPUBLIK INDONESIA</td>	
		</tr>
		<tr>
			<td align='center' width='75px'>3</td>
			<td colspan='2'>ATASAN PEJABAT PENILAI</td>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan='5'>&nbsp;</td>
			<td align='right' width='40px'>a.</td>
			<td>Nama</td>		
			<td colspan='3'>".$pegawai_atasan->getField("NAMA")."</td>		
		</tr>
		<tr>
			<td align='right' width='40px'>b.</td>
			<td>NIP</td>
			<td colspan='3'>".$pegawai_atasan->getField("NIP_BARU")."</td>		
		</tr>
		<tr>
			<td align='right' width='40px'>c.</td>			
			<td>Pangkat/Gol. Ruang</td>
			<td colspan='3'>".$pegawai_atasan->getField("NMGOLRUANG").", ".$pegawai_atasan->getField("GOL_RUANG")."</td>	
		</tr>
		<tr>
			<td align='right' width='40px'>d.</td>			
			<td>Jabatan/Pekerjaan</td>
			<td colspan='3'>".$pegawai_atasan->getField("JABATAN")."</td>	
		</tr>
		<tr>
			<td align='right' width='40px'>e.</td>			
			<td>Unit Organisasi</td>
			<td colspan='3'>".$pegawai_atasan->getField("SATKER")." KEMENTERIAN DALAM NEGERI <br> REPUBLIK INDONESIA</td>	
		</tr>
	</table>
	
	<pagebreak orientation='portrait'/>
	
	<table>
		<tr>
			<td colspan='7' align='center' style='font-size:15px'>R   A   H   A   S   I   A</td>
		</tr>
		<tr>
			<td align='center' width='75px' rowspan='".($jumlah_kategori+7)."' valign='top'>4</td>
			<td colspan='5'>Unsur yang Dinilai</td>
			<td align='center'>Jumlah</td>
		</tr>
		<tr>
			<td align='right' width='40px'>a.</td>
			<td colspan='2'>Sasaran Kerja Pegawai (SKP)</td>
			<td colspan='2'>".$nilai." x 60 % </td>
			<td align='center'>".$nilai_skp."</td>
		</tr>
		<tr>
			<td>Jumlah</td>
			<td>".$nilai."</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Nilai Rata-rata</td>
			<td>".$rata."</td>
			<td>".$penilaian."</td>
		</tr>
		<tr>
			<td>Nilai Perilaku Kerja</td>
			<td>".$rata."</td>
			<td>X 40%</td>
			<td align='center'>".$nilai_pk."</td>
		</tr>
		<tr>
			<td colspan='5' rowspan='2' align='center'>NILAI PRESTASI KERJA</td>
			<td align='center'>".($nilai_skp + $nilai_pk)."</td>
		</tr>
		<tr>
			<td align='center'>".(($nilai_skp + $nilai_pk))."</td>
		</tr>
		</table>
	</div>
	<div id='hal3'>
	<table>		
		<tr>
			<td colspan='2'>5 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KEBERATAN DARI PEGAWAI NEGERI SIPIL YANG DINILAI (APABILA ADA)</td>
		</tr>
	</table>
	<table>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td width='70%'>&nbsp;</td>
			<td>Tanggal. ………………</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
	</table>
	</div>
		
	<pagebreak orientation='portrait'/>
	<div id='hal3'>
	<table>
		<tr>
			<td colspan='7' align='center' style='font-size:15px'>R   A   H   A   S   I   A</td>
		</tr>
	</table>
	<table>
		<tr>
			<td colspan='2'>6 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TANGGAPAN PEJABAT PENILAI ATAS KEBERATAN</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td width='70%'>&nbsp;</td>
			<td>Tanggal. ………………</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
	</table>
	<table>
		<tr>
			<td colspan='2'>7 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KEPUTUSAN ATASAN PEJABAT PENILAI ATAS KEBERATAN</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
		<tr>
			<td width='70%'>&nbsp;</td>
			<td>Tanggal. ………………</td>
		</tr>
		<tr>
			<td colspan='2'>&nbsp;</td>
		</tr>
	</table>
	</div>
	
	<pagebreak orientation='portrait'/>
	<div id='rekomendasi'>
	<table>
		<tr>
			<td colspan='7' align='center' style='font-size:15px'>R   A   H   A   S   I   A</td>
		</tr>
	</table>
	<table>
		<tr>
			<td align='center'>8</td>
			<td colspan='6'>REKOMENDASI</td>
		</tr>
		<tr>
			<td colspan='7'>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan='5' bgcolor='#CCCCCC'>v Untuk peningkatan kemampuan perlu mengikutsertakan diklat teknis, (<font color='#FF0000'> misalnya: seperti <br> diklat komputer, kenaikan pangkat, pensiun, kehumasan, sekretaris, </font> dsb.)</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='7'>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan='5' bgcolor='#FFCC33'>v Untuk menambah wawasan pengetahuan dalam bidang pekerjaan: <font color='#FF0000'>perlu dilakukan <br> rotasi pegawai dsb.</font>)</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='7'>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan='5' bgcolor='#66CC66'>v Untuk kebutuhan pengembangan perlu: peningkatan pendidikan (melalui Tugas <br> Belajar, Izin Belajar, dsb); peningkatan karier (promosi) dsb.</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='7'>&nbsp;</td>
		</tr>		
	</table>
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
			<td align='center'>PEJABAT PENILAI</td>
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
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align='center'>".$pegawai_penilai->getField("NAMA")."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td rowspan='7' valign='top' width='5%'>10.</td>
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
	"
?>
<?
	$html .="
</div> <!-- END DETIL -->

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
$stylesheet = file_get_contents('../WEB/css/pelaporan_prestasi_kerja.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('pelaporan_prestasi_kerja.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>
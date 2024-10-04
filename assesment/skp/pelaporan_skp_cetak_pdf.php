<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/PegawaiPenilai.php");
include_once("../WEB/classes/base-skp/Kegiatan.php");
include_once("../WEB/classes/base-skp/KegiatanTambahan.php");

$pegawai_penilai = new PegawaiPenilai();
$pegawai_dinilai = new PegawaiPenilai();
$kegiatan = new Kegiatan();
$kegiatan_tambahan = new KegiatanTambahan();

$reqBulan = httpFilterGet("reqBulan");
$reqTahun = httpFilterGet("reqTahun");
$reqId = httpFilterGet("reqId");

$pegawai_dinilai->selectByParamsDinilaiCetak(array('B.IDPEG'=>$reqId));
$pegawai_dinilai->firstRow();
$tempPegawaiPenilai = $pegawai_dinilai->getField("PEGAWAI_ID_PENILAI");

$pegawai_penilai->selectByParamsPenilaiCetak(array('B.IDPEG'=>$tempPegawaiPenilai));
$pegawai_penilai->firstRow();

$kegiatan->selectByParams(array("PEGAWAI_ID"=>$reqId, "BULAN" => $reqBulan, "TAHUN" => $reqTahun));
$kegiatan_tambahan->selectByParams(array("PEGAWAI_ID"=>$reqId, "BULAN" => $reqBulan, "TAHUN" => $reqTahun));

?>
<?php
//start report
$html = "
<div id='kop'>
	<table width='100%'>
	  <tr>
		<td>Capaian Sasaran Kerja PNS</td> 
	  </tr>
	  <tr>
	  	<td>Waktu Pengisian : ".getNamaHari(date("d"), date("m"), date("Y")).", ".date('d')." ".getNameMonth((int)date('m'))." ".date('Y').", pukul ".date("H:i",time())."</td>	
	  </tr>
	</table>
</div>
<br />
<div id='detil'>
	<table style='margin-bottom:30px;'>
		<thead>
			<tr>
				<th align='center' style='width:15px;'>No</th>
				<th colspan='2' align='center'>I. Pejabat Penilai</th>
				<th align='center' style='width:15px;'>No</th>
				<th colspan='2' align='center'>II. Pejabat Yang dinilai</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td style='width:150px;'>Nama</td>
				<td>".$pegawai_penilai->getField('NAMA')."</td>
				<td>1</td>
				<td style='width:150px;'>Nama</td>
				<td>".$pegawai_dinilai->getField('NAMA')."</td>
			</tr>
			<tr>
				<td>2</td>
				<td style='width:150px;'>NIP</td>
				<td>".$pegawai_penilai->getField('NIP_BARU')."</td>
				<td>2</td>
				<td style='width:150px;'>NIP</td>
				<td>".$pegawai_dinilai->getField('NIP_BARU')."</td>
			</tr>
			<tr>
				<td>3</td>
				<td style='width:150px;'>Pangkat / Gol</td>
				<td>".$pegawai_penilai->getField('PANGKAT_GOL')."</td>
				<td>3</td>
				<td style='width:150px;'>Pangkat / Gol</td>
				<td>".$pegawai_dinilai->getField('PANGKAT_GOL')."</td>
			</tr>
			<tr>
				<td>4</td>
				<td style='width:150px;'>Jabatan</td>
				<td>".$pegawai_penilai->getField('JABATAN')."</td>
				<td>4</td>
				<td style='width:150px;'>Jabatan</td>
				<td>".$pegawai_dinilai->getField('JABATAN')."</td>
			</tr>
			<tr>
				<td>5</td>
				<td style='width:150px;'>Unit Kerja</td>
				<td>".$pegawai_penilai->getField('DEPARTEMEN')."</td>
				<td>5</td>
				<td style='width:150px;'>Unit Kerja</td>
				<td>".$pegawai_dinilai->getField('DEPARTEMEN')."</td>
			</tr>
		</tbody>
	</table>
	<table>
		<thead>
			<tr>
				<th align='center' style='width:15px;' rowspan='2'>No</th>
              	<th style='width:500px' rowspan='2'>III. Kegiatan Tugas Pokok Jabatan</th>
              	<th colspan='4' align='center'>Target</th> 	
              	<th colspan='5' align='center'>Pencapaian</th>
			</tr>
			<tr>
				<th>Kuantitas</th>
				<th>Kualitas</th>
				<th>Waktu</th>
				<th>Biaya</th>
				<th>Kuantitas</th>
				<th>Kualitas</th>
				<th>Waktu</th>
				<th>Biaya</th>
				<th>Perhitungan</th>
			</tr>
		</thead>
		<tbody>
	"
?>
<?
	$i = 1;
	while($kegiatan->nextRow())
	{
	
	$html .="
		<tr>
			<td align='center'>".$i."</td>
			<td>".$kegiatan->getField("NAMA")."</td>
			<td>".$kegiatan->getField("KUANTITAS")." ".$kegiatan->getField("KUANTITAS_SATUAN")."</td>
			<td>".$kegiatan->getField("KUALITAS")." %"."</td>
			<td>".$kegiatan->getField("WAKTU")." ".$kegiatan->getField("WAKTU_SATUAN")."</td>
			<td>".$kegiatan->getField("BIAYA")."</td>
			<td>".$kegiatan->getField("KUANTITAS_REALISASI")." ".$kegiatan->getField("KUANTITAS_SATUAN")."</td>
			<td>".$kegiatan->getField("KUALITAS_REALISASI")." %"."</td>
			<td>".$kegiatan->getField("WAKTU_REALISASI")." ".$kegiatan->getField("WAKTU_SATUAN")."</td>
			<td>".$kegiatan->getField("BIAYA_REALISASI")."</td>
			<td>".$kegiatan->getField("PERHITUNGAN")."</td>
		</tr>
		"
?>
<?
		$i++;
	}
?>
<?
	$html .="	
	</tbody>
	</table><br/>
</div> <!-- END DETIL -->

<div id='kop-tambahan'>
	<table>
		<tr>
			<td>Rincian tugas tambahan dan kreatifitas</td>
		</tr>
	</table>
</div>
<div id='detil-tambahan'>
	<table>
		<thead>
			<tr>
				<th align='center' style='width:15px;'>No</th>
				<th align='center' style='width:450px;'>Rincian Tugas</th>
				<th align='center' style='width:100px;'>Jenis Tugas</th>
				<th align='center' style='width:50px;'>Kuantitas</th>
				<th align='center' style='width:100px;'>Hasil Kuantitas</th>
				<th align='center' style='width:50px;'>Perhitungan</th>
			</tr>
		</thead>
		<tbody>
"
?>
<?
	$i = 1;
	while($kegiatan_tambahan->nextRow())
	{
	
	$html .="
		<tr>
			<td align='center'>".$i."</td>
			<td>".$kegiatan_tambahan->getField("NAMA")."</td>
			<td>Tambahan</td>
			<td>".$kegiatan_tambahan->getField("KUANTITAS")."</td>
			<td>".$kegiatan_tambahan->getField("KUANTITAS_REALISASI")."</td>
			<td>".$kegiatan_tambahan->getField("PERHITUNGAN")."</td>
		</tr>
		"
?>
<?
		$i++;
	}
?>
<?
	$html .="	
		</tbody>
	</table>
	<br/>
</div>
<div id='footer'>
	<table>
	<tr>
		<td>NILAI CAPAIAN SKP : 99</td>
	</tr>
	<tr>
		<td>Predikat : Sangat Baik</td>
	</tr>
	</table>
	<table>
	<tr>
	  	<td width='75%'>&nbsp;</td>	
		<td align='center'>Jakarta, ".date('d')." ".getNameMonth((int)date('m'))." ".date('Y')."</td>
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
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	</table>
</div>
"
?>
<?
//==============================================================
//==============================================================
//==============================================================
include("../WEB/lib/MPDF60/mpdf.php");

//$mpdf=new mPDF('c','LEGAL-L');
$mpdf = new mPDF('c','LEGAL');
$mpdf->AddPage('L', // L - landscape, P - portrait
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
$stylesheet = file_get_contents('../WEB/css/pelaporan_skp_cetak.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('pelaporan_skp_cetak.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>
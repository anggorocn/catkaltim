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
$periode_penilaian = new PeriodePenilaian();
$kategori = new Kategori();

$reqId = httpFilterGet("reqId");

$kategori->selectByParams(array(), -1, -1, "", " ORDER BY URUT ASC");

$reqTahun = $periode_penilaian->getMaxTahun();

$statement = " AND PEGAWAI_ID_DINILAI = ".$reqId;
$pegawai_dinilai->selectByParamsMonitoringPencapaian($reqTahun, array(), -1, -1, $statement);
$pegawai_dinilai->firstRow();
$tempPegawaiPenilai = $pegawai_dinilai->getField("PEGAWAI_ID_PENILAI");

$nilai = 0;
$pembagi = 0;
for($i=1;$i<=12;$i++)
{
	$nilai += $pegawai_dinilai->getField("BL".$i);

	if($pegawai_dinilai->getField("BL".$i) == "" || $pegawai_dinilai->getField("BL".$i) == 0)
	{}
	else
		$pembagi += 1;
}

if($pembagi == 0)
	$nilai = 0;
else
	$nilai = $nilai / $pembagi;

$pegawai_penilai->selectByParamsPenilai(array("A.IDPEG"=>$tempPegawaiPenilai));
$pegawai_penilai->firstRow();
?>
<?php
//start report
$html = "
<div style='margin-top:18px;' id='header'>
	<p style='text-decoration:underline; text-align:center; width:950px;'><strong>BUKU CATATAN PENILAIAN PERILAKU PNS</strong></p>
</div>
<div id='kop'>
	<table>
		  <tr>
			<td>Nama</td>
			<td>:</td>
			<td>".$pegawai_dinilai->getField("NAMA")."</td> 
		  </tr>
		  <tr>
			<td>NIP</td>
			<td>:</td>
			<td>".$pegawai_dinilai->getField("NIP_BARU")."</td> 	
		  </tr>
	</table>
</div>
<br />
	<table style='margin-bottom:30px;' id='detil'>
		<thead>
			<tr>
				<th align='center' style='width:15px;'>No</th>
				<th align='center' style='width:100px;'>Tanggal</th>
				<th align='center' style='width:350px;'>Uraian</th>
				<th align='center' style='width:150px;'>Nama/NIP dan Paraf <br />Pejabat Penilai</th>
			</tr>
			<tr>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td valign='top' align='center' style='padding:10px;'>1</td>
				<td valign='top' style='padding:10px 0px;'>&nbsp;2 Januari ".$reqTahun." s.d. <br> &nbsp;31 Desember ".$reqTahun."</td>
				<td style='width:350px;'>
					<table id='nilai'>
					<tr>
					<td colspan='3'>
					Penilaian SKP sampai dengan akhir Desember ".$reqTahun." = <br> ".$nilai." sedangkan penilaian perilaku kerjanya adalah sebagai berikut :
					</td>
					</tr>
					"
					?>
                    <?
						$nilai = 0;
						$pembagi = 0;
						while($kategori->nextRow())
						{
                    ?>
                    <?
					$html .= "
						<tr><td>".$kategori->getField("KETERANGAN")."</td><td>=</td><td>".$pegawai_dinilai->getField("PK".$kategori->getField("URUT"))."</td></tr>
					"
					?>
                    <?
							$nilai += $pegawai_dinilai->getField("PK".$kategori->getField("URUT"));
							if($pegawai_dinilai->getField("PK".$kategori->getField("URUT")) == "" || $pegawai_dinilai->getField("PK".$kategori->getField("URUT")) == 0)
							{}
							else
								$pembagi += 1;							

						}					
					$html .= "
						<tr><td colspan='3' style='border-top:1px solid;'></td></tr>
						<tr><td>Jumlah</td><td>=</td><td>".$nilai."</td></tr> 
						<tr><td>Nilai Rata - Rata</td><td>=</td><td>".$rata." ".$penilaian."</td></tr> 
					</table>		
				</td>
				<td>
					<center>".$pegawai_penilai->getField("JABATAN")."</center><br><br><br><br><br>
					<center><u>".$pegawai_penilai->getField("NAMA")."<u></center>
					<center>".$pegawai_penilai->getField("NIP_BARU")."</center>
				</td>
				
			</tr>
	"
?>
<?
	$html .="	
		</tbody>
	</table>
 <!-- END DETIL -->

"
?>
<?
//==============================================================
//==============================================================
//==============================================================
include("../WEB/lib/MPDF60/mpdf.php");

//$mpdf=new mPDF('c','LEGAL-L');
$mpdf = new mPDF('c','A4');
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
$stylesheet = file_get_contents('../WEB/css/pelaporan_catatan_perilaku.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('pelaporan_catatan_perilaku.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>
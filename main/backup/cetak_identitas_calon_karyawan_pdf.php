<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarDokumen.php");
include_once("../WEB/classes/base/PelamarPendidikan.php");
include_once("../WEB/classes/base/PelamarKeluarga.php");
include_once("../WEB/classes/base/PelamarSertifikat.php");
include_once("../WEB/classes/base/PelamarPelatihan.php");
include_once("../WEB/classes/base/PelamarPengalaman.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/default.func.php");

$pelamar = new Pelamar();
$pelamar_keluarga = new PelamarKeluarga();
$pelamar_pendidikan = new PelamarPendidikan();
$pelamar_pengalaman = new PelamarPengalaman();
$pelamar_pelatihan = new PelamarPelatihan();
$pelamar_sertifikat = new PelamarSertifikat();
$pelamar_dokumen = new PelamarDokumen();

$reqId = httpFilterGet("reqId");
$reqKode = httpFilterGet("reqKode");
$reqJabatan= httpFilterGet("reqJabatan");

$pelamar->selectByParamsCV(array('A.PELAMAR_ID'=>$reqId),-1,-1);
$pelamar->firstRow();
//echo $pelamar->query;exit;

$pelamar_keluarga->selectByParams(array('PELAMAR_ID'=>$reqId),-1,-1);
$pelamar_pendidikan->selectByParams(array('PELAMAR_ID'=>$reqId),-1,-1, "", " ORDER BY A.PENDIDIKAN_ID");
$pelamar_sertifikat->selectByParams(array('PELAMAR_ID'=>$reqId),-1,-1);
$pelamar_pelatihan->selectByParams(array('PELAMAR_ID'=>$reqId),-1,-1);
$pelamar_pengalaman->selectByParams(array('PELAMAR_ID'=>$reqId),-1,-1);

$reqNRP = $pelamar->getField("NRP");
$reqNama = $pelamar->getField("NAMA");
$reqKTP = $pelamar->getField("KTP_NO");
$reqAgamaNama = $pelamar->getField("AGAMA_NAMA");
$reqJenisKelamin = $pelamar->getField("JENIS_KELAMIN");
$reqTTL = $pelamar->getField("TTL");
$reqTinggiBB = $pelamar->getField("TINGGIBB");
$reqAlamat = $pelamar->getField("ALAMAT");
$reqDomisili = $pelamar->getField("DOMISILI");
$reqTelepon = $pelamar->getField("TELEPON");
$reqEmail = $pelamar->getField("EMAIL");
$reqGolonganDarah = $pelamar->getField("GOLONGAN_DARAH");
$reqStatusPernikahan = $pelamar->getField("STATUS_PERNIKAHAN");
$reqNPWP = $pelamar->getField("NPWP");
$reqTanggalNPWP = $pelamar->getField("TANGGAL_NPWP");
$reqLampiranFoto = $pelamar->getField("LAMPIRAN_FOTO");

$statement = " AND A.PELAMAR_ID = ".$reqId." AND upper(NAMA) = upper('Pas Foto ukuran 4x6 Berwarna')";
$pelamar_dokumen->selectByParamsData(array(), -1, -1, $statement);
$pelamar_dokumen->firstRow();
$reqLampiranFoto = $pelamar_dokumen->getField("LAMPIRAN");
	
if($reqLampiranFoto == "")
{}
else
{
	$frame_foto = "<td style='border: 1px solid black' rowspan='6'><img src='../uploads/".$reqLampiranFoto."' width='113' height='170'></td>";
}

?>
<?php
//start report
$html = "
<h2 align='center' style='font-family: Arial; background-color:#ECECEC'>IDENTITAS CALON KARYAWAN</h2>
<div style='font-family: Arial; font-size:10px; color:#a42e32;'>ICK harus diprint dua kali dan dibawa pada saat verifikasi atau panggilan berikutnya.</div>
<table width='100%' style='font-family: Arial;'>
	<tr>
		<td align='center' style='width:240px;'>
			<table style='width:180px !important; background:#ffeeee; border:3px solid #ff979a; border-width:3px 3px 3px 3px; border-collapse:collapse;'>
			<tr>
			<td style='text-align:center;'>
			NO REGSITRASI:
			</td>
			</tr>
			<tr>
			<td style='background:#ffeeee; text-align:center;'>
			".$reqNRP."
			</td>
			</tr>
			</table>
		</td>
		<td align='center' >
			<span style='border: 4px solid grey;'><img src='../uploads/".$reqLampiranFoto."' width='113' height='170'></span>
		</td>
		<td align='center' style='width:240px;'>
			<table style='width:180px !important; background:#ffeeee; border:3px solid #ff979a; border-width:3px 3px 3px 3px; border-collapse:collapse;'>
			<tr>
			<td style='text-align:center;'>
			".$reqKode."
			</td>
			</tr>
			<tr>
			<td style='background:#ffeeee; text-align:center;'>
			".$reqJabatan."
			</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<table width='100%' style='font-family: Arial; font-size:12px; border:1px solid #e1e1e1;'>
	<tr>
		<td colspan='3' style='background-color:#AADDAA'><strong>IDENTITAS</strong></td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>No KTP</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqKTP."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Nama</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'><strong>".$reqNama."</strong></td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Jenis Kelamin</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqJenisKelamin."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Status Pernikahan</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqStatusPernikahan."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Alamat Sekarang</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqAlamat."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Domisili</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqDomisili."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>No. Telepon</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqTelepon."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Tempat / Tgl.Lahir</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqTTL."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Agama</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqAgamaNama."</td>
	</tr>
	<tr>
		<td style='border:1px solid #e1e1e1;'>Tinggi / Berat Badan</td>
		<td style='border:1px solid #e1e1e1;'>:</td>
		<td style='border:1px solid #e1e1e1;'>".$reqTinggiBB."</td>
	</tr>
</table>

";


$html .= "<div style='page-break-after:always'></div>";

$html .= "
<h2 style='font-family: Arial;'>HUBUNGAN KELUARGA</h2>
<table style='font-family: Arial; font-size:12px;'>
	<tr>
		<th style='border:1px solid #e1e1e1;'>Hubungan Keluarga</th>
		<th style='border:1px solid #e1e1e1;'>Nama</th>
		<th style='border:1px solid #e1e1e1;'>Jenis Kelamin</th>
		<th style='border:1px solid #e1e1e1;'>Pendidikan Akhir</th>
		<th style='border:1px solid #e1e1e1;'>Pekerjaan</th>
	</tr>
	"; 
	while($pelamar_keluarga->nextRow())
	{
		$html .= "
		<tr>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_keluarga->getField("HUBUNGAN_KELUARGA_NAMA")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_keluarga->getField("NAMA")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_keluarga->getField("JENIS_KELAMIN_DESC")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_keluarga->getField("PENDIDIKAN_NAMA")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_keluarga->getField("PEKERJAAN")."</td>
		</tr>
		";
	} 
$html .= "
</table>
";

$html .= "
<h2 style='font-family: Arial;'>RIWAYAT PENDIDIKAN</h2>
<table style='font-family: Arial; font-size:12px;'>
	<tr>
		<th style='border:1px solid #e1e1e1;'>Jenjang Pendidikan</th>
		<th style='border:1px solid #e1e1e1;'>Nama Sekolah</th>
		<th style='border:1px solid #e1e1e1;'>Jurusan</th>
		<th style='border:1px solid #e1e1e1;'>Kota</th>
		<th style='border:1px solid #e1e1e1;'>Lulus</th>
		<th style='border:1px solid #e1e1e1;'>No. Ijasah</th>
	</tr>
	"; 
	while($pelamar_pendidikan->nextRow())
	{
		$html .= "
		<tr>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pendidikan->getField("PENDIDIKAN_NAMA")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pendidikan->getField("NAMA")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pendidikan->getField("JURUSAN")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pendidikan->getField("KOTA")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pendidikan->getField("LULUS")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pendidikan->getField("NO_IJASAH")."</td>
		</tr>
		";
	} 
$html .= "
</table>
";

$html .= "
<h2 style='font-family: Arial;'>SERTIFIKAT</h2>
<table style='font-family: Arial; font-size:12px;'>
	<tr>
		<th style='border:1px solid #e1e1e1;'>Nama Sertifikat</th>
		<th style='border:1px solid #e1e1e1;'>Tanggal Terbit</th>
		<th style='border:1px solid #e1e1e1;'>Tanggal Kadaluarsa</th>
		<th style='border:1px solid #e1e1e1;'>Keterangan</th>
	</tr>
	"; 
	while($pelamar_sertifikat->nextRow())
	{
		$html .= "
		<tr>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_sertifikat->getField("NAMA")."</td>
			<td style='border:1px solid #e1e1e1;'>".dateToPageCheck($pelamar_sertifikat->getField("TANGGAL_TERBIT"))."</td>
			<td style='border:1px solid #e1e1e1;'>".dateToPageCheck($pelamar_sertifikat->getField("TANGGAL_KADALUARSA"))."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_sertifikat->getField("KETERANGAN")."</td>
		</tr>
		";
	} 
$html .= "
</table>
";

$html .= "
<h2 style='font-family: Arial;'>PELATIHAN</h2>
<table style='font-family: Arial; font-size:12px;'>
	<tr>
		<th style='border:1px solid #e1e1e1;'>Nama Pelatihan</th>
		<th style='border:1px solid #e1e1e1;'>Lama(hari)</th>
		<th style='border:1px solid #e1e1e1;'>Tahun</th>
		<th style='border:1px solid #e1e1e1;'>Instruktur</th>
	</tr>
	"; 
	while($pelamar_pelatihan->nextRow())
	{
		$html .= "
		<tr>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pelatihan->getField("JENIS")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pelatihan->getField("WAKTU")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pelatihan->getField("TAHUN")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pelatihan->getField("PELATIH")."</td>
		</tr>
		";
	} 
$html .= "
</table>
";

$html .= "
<h2 style='font-family: Arial;'>RIWAYAT PENGALAMAN KERJA</h2>
<table style='font-family: Arial; font-size:12px;'>
	<tr>
		<th style='border:1px solid #e1e1e1;'>Jabatan</th>
		<th style='border:1px solid #e1e1e1;'>Perusahaan</th>
		<th style='border:1px solid #e1e1e1;'>Durasi</th>
	</tr>
	"; 
	while($pelamar_pengalaman->nextRow())
	{
		$html .= "
		<tr>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pengalaman->getField("JABATAN")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pengalaman->getField("PERUSAHAAN")."</td>
			<td style='border:1px solid #e1e1e1;'>".$pelamar_pengalaman->getField("TAHUN")." &nbsp; tahun &nbsp;".$pelamar_pengalaman->getField("DURASI")."&nbsp; bulan &nbsp;</td>	
		</tr>
	";
	} 
$html .= "
</table>

<br>
<br>
<strong style='font-family: Arial;'>&raquo; Persetujuan</strong>
<br>
<br>
<p style='font-family: Arial; font-size:12px;'>
Formulir ini saya isi dengan sebenarnya & digunakan untuk keperluan proses seleksi dalam masa tertentu. Selama masa berlakunya data saya tersebut, dengan ini saya menyatakan akan mematuhi persyaratan/ketentuan yang telah ditetapkan DEMI KEBAIKAN NAMA SAYA SENDIRI. sebagai berikut :  1. Bersedia memenuhi panggilan wawancara / seleksi di klien yang ditunjuk  <br>
2. Menginformasikan apabila memutuskan berhenti menjadi Bank Data kami  <br>
3. Menginformasikan apabila sudah diterima bekerja  <br>
4. Menjadi daftar Black List jika menyalahi ketentuan diatas/merugikan nama kantor kami 
</p>

<p style='font-family: Arial; font-size:12px;'>
&#10004; Data yang saya isi diatas adalah benar adanya dan saya bersedia mematuhi ketentuan yang telah tersebut diatas.
</p>
";

?>
<?
include_once("../WEB/lib/MPDF60/mpdf.php");

$mpdf = new mPDF('c','LEGAL',0,'',15,15,16,16,9,9, 'L');
//$mpdf=new mPDF('c','A4'); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('../WEB/css/laporan-pdf.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('identitas_calon_karyawan.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
?>
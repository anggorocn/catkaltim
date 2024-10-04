<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/FormasiDetil.php");

$set= new Pelamar();

$reqRowId= httpFilterGet("reqRowId");
$reqId= $userLogin->userPelamarEnkripId;
$set->selectByParamsData(array("md5(CAST(A.PELAMAR_ID as TEXT))"=>$reqId),-1,-1);
//echo $set->query;exit;
$set->firstRow();
$tempId= $set->getField("PELAMAR_ID");
$tempNoKtp= $set->getField("NO_KTP");
$tempNama= $set->getField("NAMA");
$tempAlamat= $set->getField("ALAMAT_DOMISILI");
$tempNoKtp= $set->getField("NO_KTP");
$tempNoHp= $set->getField("NO_HP");
$tempEmail1= $set->getField("EMAIL1");
$tempTanggalLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
$tempIsKirimLamaran= $set->getField("IS_KIRIM_LAMARAN");

$jejak= new Pelamar();
$jejak->selectByParamsInfoJejak($tempId);
$jejak->firstRow();
$tempJejakDataPribadi= $jejak->getField("JEJAK_SIMPAN_DAFTAR");
$tempJejakPendidikanFormal= $jejak->getField("JEJAK_SIMPAN_PENDIDIKAN");
$tempJejakPengalamanBekerja= $jejak->getField("JEJAK_SIMPAN_PENGALAMAN");
$tempJejakPelatihan= $jejak->getField("JEJAK_SIMPAN_SERTIFIKASI");
$tempJejakArahMinat= $jejak->getField("JEJAK_SIMPAN_MINAT");

if($tempIsKirimLamaran == "1")
{
	echo '<script language="javascript">';
	echo 'alert("Anda Sudah pernah daftar formasi.");';	
	echo 'top.location.href = "index.php?pg=formasi_lowongan";';
	echo '</script>';
	exit();
}
elseif($tempJejakDataPribadi == "icon-belum" || $tempJejakPendidikanFormal == "icon-belum")
//elseif($tempJejakDataPribadi == "icon-belum" || $tempJejakPendidikanFormal == "icon-belum" || $tempJejakPengalamanBekerja == "icon-belum" || $tempJejakPelatihan == "icon-belum" || $tempJejakArahMinat == "icon-belum")
{
	echo '<script language="javascript">';
	echo 'alert("Anda belum mengisi biodata secara lengkap. Untuk dapat mengirim lamaran, Anda harus melengkapi biodata secara lengkap. Pastikan tahapan biodata Anda di samping kanan sudah mendapat tanda (v).");';	
	echo 'top.location.href = "?pg=formasi_lowongan";';
	echo '</script>';
	exit();
}

$formasi_detil= new FormasiDetil();
$formasi_detil->selectByParams(array("md5(CAST(FORMASI_DETIL_ID as TEXT))"=>$reqRowId),-1,-1);
$formasi_detil->firstRow();
//echo $formasi_detil->query;exit;
$tempDetilRowId= $formasi_detil->getField("FORMASI_DETIL_ID");
$tempDetilNama= $formasi_detil->getField("NAMA");
$tempDetilKode= $formasi_detil->getField("KODE");
$tempDetilTugas= $formasi_detil->getField("TUGAS");
$tempDetilPendidikanInfo= $formasi_detil->getField("PENDIDIKAN_INFO");
$tempDetilPengalamanMinimal= $formasi_detil->getField("PENGALAMAN_MINIMAL");
$tempDetilKeahlian= $formasi_detil->getField("KEAHLIAN");
$tempDetilUsia= $formasi_detil->getField("USIA");
$tempDetilJumlahKebutuhan= $formasi_detil->getField("JUMLAH_KEBUTUHAN");
unset($formasi_detil);

$arrData= array("Apabila di kemudian hari ternyata diketahui bahwa data dan informasi yang saya berikan pada proses rekrutmen dan seleksi ini tidak benar/atau tidak dapat dibuktikan, maka demi tanggung jawab moral sebagai calon pegawai/pegawai, saya bersedia mengundurkan diri dari seluruh proses rekrutmen dan seleksi sekaligus mengundurkan diri dari Instansi terkait.");
?>

<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>

<script type="text/javascript">
	$(function(){
		setSimpan();
		$('#ff').form({
			//url:'<?=$tempLinkSecurity?>json/formasi_lowongan_daftar',
			url:'<?=$tempLinkSecurity?>json/formasi_lowongan_daftar.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				alert("Anda Sukses Mengirim Lamaran. Cek Email Anda Sebagai Bukti Anda Telah Mengirim Lamaran");
				//$.messager.alert('Info', data, 'info');
				//data = data.split("-");
				//$.messager.alert('Info', data[1], 'info');
				//$('#rst_form').click();
				document.location.href = '?pg=formasi_lowongan';
			}
		});
		
		$('input[id^="reqInfo"]').click(function() {
			setSimpan();
		});
	
	});
	
	function setSimpan()
	{
		var reqJumlahInfo= "";
		reqJumlahInfo= 0;
		$('input[id^="reqInfo"]').each(function(){
			var id= $(this).attr('id');
			id= id.replace("reqInfo", "")
			
			if($(this).prop('checked'))
			{
				reqJumlahInfo= parseInt(reqJumlahInfo) + 1;
			}
	   });
		
		$("#reqJumlahInfo").val(reqJumlahInfo);
		var jumlahData= "<?=count($arrData)?>"
		
		if(reqJumlahInfo == jumlahData)
		{
			$("#reqSimpanInfo").show();
			$("#reqSimpan").show();
		}
		else
		{
			$("#reqSimpanInfo").hide();
			$("#reqSimpan").hide();
		}
	}
</script>
<div class="col-lg-8">
	
    <div id="judul-halaman">Pendaftaran > Halaman Pernyataan</div>
    
    <div id="pendaftaran">
    	Untuk mendaftar dalam proses rekrutmen dan seleksi, maka saya menyatakan bahwa:
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table>
            <tr class="formasi-konten">
                <td colspan="3">
                	Sebelum Anda mengirim lamaran, periksa kembali biodata Anda dan memastikan data di bawah ini benar
                </td>
            </tr>
            <tr class="formasi-konten">
            	<td>Nama Jabatan</td>
                <td>:</td>
                <td><?=$tempDetilNama?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>Kode Posisi</td>
                <td>:</td>
                <td><?=$tempDetilKode?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>Nama</td>
                <td>:</td>
                <td><?=$tempNama?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>Alamat</td>
                <td>:</td>
                <td><?=$tempAlamat?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>No KTP</td>
                <td>:</td>
                <td><?=$tempNoKtp?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>No Hp</td>
                <td>:</td>
                <td><?=$tempNoHp?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>Email</td>
                <td>:</td>
                <td><?=$tempEmail1?></td>
            </tr>
            <tr class="formasi-konten">
            	<td>Tanggal Lahir</td>
                <td>:</td>
                <td><?=$tempTanggalLahir?></td>
            </tr>
        </table>
        <table>
        	<input type="hidden" name="reqJumlahInfo" id="reqJumlahInfo" value="0" />
        	<?
			for($i_data=0; $i_data < count($arrData); $i_data++)
			{
            ?>
        	<tr>
            	<td><input type="checkbox" name="reqInfo<?=$i_data?>" value="1" id="reqInfo<?=$i_data?>" /></td>
                <td><?=$arrData[$i_data]?></td>
            </tr>
            <?
			}
            ?>
            <tr id="reqSimpanInfo" style="display:none">
            	<td colspan="2"><p class="keterangan">Dengan klik tombol SETUJU, Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Seleksi untuk menggunakan data administrasi dalam proses Seleksi PT. Pelindo Jaya</p></td>
            </tr>
            <tr id="reqSimpan" style="display:none">
            	<td colspan="2">
                	<input name="reqRowId" type="hidden" value="<?=$tempDetilRowId?>" />
                	<input name="reqSubmit" type="hidden" value="update" />
                	<input type="submit" value="Setuju" />
                </td>
            </tr>          
        </table>
        </form>
    </div>
    
    
</div>
<?
include_once("../WEB/classes/utils/UserLogin.php");
if($userLogin->ujianUid == "")
{
	if($pg == "" || $pg == "home"){}
	else
	{
		echo '<script language="javascript">';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}
}

$tempPegawaiId= $userLogin->pegawaiId;

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();
$set->selectByParamsJawabanSoal(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempStatusSetuju= $set->getField("STATUS_SETUJU");

?>
<script language="javascript">
function nextModulLink()
{
	if("<?=$tempStatusSetuju?>" == "")
	{
		var s_url= "../json-ujian/form_persetujuan.php?reqUjianPegawaiDaftarId=<?=$tempUjianPegawaiDaftarId?>&reqPegawaiId=<?=$tempPegawaiId?>";
		$.ajax({'url': s_url,'success': function(msg) {
			document.location.href= "?pg=form_persetujuan";
		}});
	}
	else
	{
		document.location.href= "?pg=form_persetujuan";
	}
}
</script>
<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Tata Cara Tes CAT Online</div>
        </div>
    </div>
    
	<div class="row">
        <div class="col-md-12">
        	<div class="area-tatacara"> 
                <ul>
                	<li>Peserta Tes Online adalah calon pegawai.</li>
					<li>Bacalah Panduan Tes Online<a href="buku_panduan.pdf" target="_blank">(download panduan)</a></li>
					<li>Tahapan Waktu Pelaksanaan CAT Online :
                    	<ul style="padding-left:50px;"> 
							<li>1 Mei – 31 Juli 2017 : Pelaksanaan Ujian CAT Online</li>
							<li>1-30 Agustus 2017 : Evaluasi Penilaian Hasil Ujian</li>
							<li>31 Agustus 2017 : Pengumuman hasil ujian</li>
                        </ul>
                    </li>
					<li>Bacalah setiap petunjuk dalam aplikasi CAT Online ini.</li>
					<li>Saudara wajib Pengisi Form Persetujuan ( dalam bentuk Pop Up). Form Persetujuan</li>  

                <?php /*?><li>Isikan kelengkapan identitas diri anda.</li>
                <li>Menyetujui form persetujuan yang telah disiapkan.</li>
                <li>Persiapkan diri anda untuk mengikuti Sertifikasi Online sesuai waktu yang telah diberikan.</li>
                <li>Kerjakan tes online dengan teliti, dan selesaikan tepat waktu sesuai dengan waktu yang diberikan.</li> <?php */?>
                </ul>
            </div>
        </div>
        
        <div class="area-prev-next">
            <div class="prev"><a href="?pg=dashboard"><i class="fa fa-chevron-left"></i></a></div>
            <div class="next"><a href="#" onclick="nextModulLink()"><i class="fa fa-chevron-right"></i></a></div>
        </div>
    
    </div>
</div>

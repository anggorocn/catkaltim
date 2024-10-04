<?php
// page registering
	$regURL[0]		= 'home';								$regPage[0] = 'home.php';		// => also as default page
	
	$regURL[]= 'content'; $regPage[]= 'content.php';
	$regURL[]= 'pendaftaran'; $regPage[]= 'pendaftaran.php';
	$regURL[]= 'data_pribadi'; $regPage[]= 'data_pribadi.php';
	$regURL[]= 'daftar_riwayat_hidup'; $regPage[]= 'data_pribadi.php';
	$regURL[]= 'data_pendidikan_formal'; $regPage[]= 'data_pendidikan_formal.php';
	$regURL[]= 'data_pribadi_pelatihan'; $regPage[]= 'data_pribadi_pelatihan.php';
	$regURL[]= 'data_pribadi_lain'; $regPage[]= 'data_pribadi_lain.php';
	$regURL[]= 'data_lampiran'; $regPage[]= 'data_lampiran.php';
	$regURL[]= 'data_peminatan'; $regPage[]= 'data_peminatan.php';
	$regURL[]= 'data_password'; $regPage[]= 'data_password.php';
	$regURL[]= 'upload_dokumen'; $regPage[]= 'upload_dokumen.php';
	$regURL[]= 'download_dokumen'; $regPage[]= 'download_dokumen.php';
	$regURL[]= 'formulir_critical_incident'; $regPage[]= 'formulir_critical_incident.php';
	$regURL[]= 'formulir_ci_pelaksana'; $regPage[]= 'formulir_ci_pelaksana.php';
	$regURL[]= 'formulir_q_kompetensi_pelaksana'; $regPage[]= 'formulir_q_kompetensi_pelaksana.php';
	$regURL[]= 'formulir_q_kompetensi_eselon'; $regPage[]= 'formulir_q_kompetensi_eselon.php';
	$regURL[]= 'formulir_q_inta'; $regPage[]= 'formulir_q_inta.php';


	$regURL[]= 'data_koreksi_nip'; $regPage[]= 'data_koreksi_nip.php';

	$regURL[]= 'fasilitator_diklat'; $regPage[]= 'fasilitator_diklat.php';
	$regURL[]= 'fasilitator_dokumen'; $regPage[]= 'fasilitator_dokumen.php';
	
	$regURL[]= 'data_pribadi_pendidikan'; $regPage[]= 'data_pribadi_pendidikan.php';
	$regURL[]= 'data_pelatihan'; $regPage[]= 'data_pelatihan.php';
	$regURL[]= 'data_pengalaman'; $regPage[]= 'data_pengalaman.php';
	$regURL[]= 'data_sertifikat'; $regPage[]= 'data_sertifikat.php';
	$regURL[]= 'data_keluarga'; $regPage[]= 'data_keluarga.php';
	$regURL[]= 'lamaran'; 		$regPage[]= 'lamaran.php';
	$regURL[]= 'lamaran_dokumen'; 		$regPage[]= 'lamaran_dokumen.php';

	$regURL[]= 'formasi_lowongan'; $regPage[]= 'formasi_lowongan.php';
	$regURL[]= 'formasi_lowongan_daftar'; $regPage[]= 'formasi_lowongan_daftar.php';
	
	$regURL[]= 'register'; $regPage[]= 'register.php';
	$regURL[]= 'password'; $regPage[]= 'password.php';
	$regURL[]= 'reset_password'; $regPage[]= 'reset_password.php';
	$regURL[]= 'konfirmasi_kehadiran'; $regPage[]= 'konfirmasi_kehadiran.php';
	
	$regURL[]= 'jadwal_sahli'; $regPage[]= 'jadwal_sahli.php';
	$regURL[]= 'pengumuman_seleksi_administrasi'; 					$regPage[]= 'pengumuman_seleksi_administrasi.php';
	$regURL[]= 'pengumuman_seleksi_administrasi_detil'; 			$regPage[]= 'pengumuman_seleksi_administrasi_detil.php';

	$regURL[]= 'home_detil1';										$regPage[]= 'home_detil1.php';
	$regURL[]= 'home_detil2';										$regPage[]= 'home_detil2.php';
	$regURL[]= 'home_detil';										$regPage[]= 'home_detil.php';
	$regURL[]= 'home2';												$regPage[]= 'home.php';
	
	$regURL[]= 'kategori_list';										$regPage[]= 'kategori_list.php';
	$regURL[]= 'daftar_lamaran';									$regPage[]= 'daftar_lamaran.php';
	$regURL[]= 'daftar_lowongan';									$regPage[]= 'daftar_lowongan.php';

	$regURL[]= 'faq';												$regPage[]= 'faq.php';
	$regURL[]= 'tanya_jawab';										$regPage[]= 'tanya_jawab.php';
	$regURL[]= 'informasi';											$regPage[]= 'informasi.php';
	$regURL[]= 'informasi_detil';									$regPage[]= 'informasi_detil.php';
	
	$regURL[]		= 'dashboard';							$regPage[] = 'dashboard.php';
	$regURL[]		= 'tata_cara';							$regPage[] = 'tata_cara.php';
	$regURL[]		= 'lengkapi_data';						$regPage[] = 'lengkapi_data.php';
	$regURL[]		= 'form_persetujuan';					$regPage[] = 'form_persetujuan.php';
	$regURL[]		= 'ujian_mukadimah';						$regPage[] = 'ujian_mukadimah.php';
	$regURL[]		= 'ujian_online';						$regPage[] = 'ujian_online.php';
	$regURL[]		= 'ujian_pilihan';						$regPage[] = 'ujian_pilihan.php';
	$regURL[]		= 'ujian_onlineBak';						$regPage[] = 'ujian_onlineBak.php';
	$regURL[]		= 'finish';								$regPage[] = 'finish.php';
	

// page translation, $pg = dari URL 
class pageToLoad
{
	var $regURL;
	var $regPage;
	var $pg;
	
	function pageToLoad()
	{
		$this->pg = $_GET['pg'];
	}
	
	function loadPage()
	{
		if(in_array($this->pg, $this->regURL))
		{
			foreach($this->regURL as $key => $value)
			{
				if($value == $this->pg)
				{
					$pageIndex = $key;
				}
			}
			
			$loadPage = $this->regPage[$pageIndex];
		}
		else
		{
			$loadPage = $this->regPage[0];
		}
		
		return $loadPage;
	}
}

// instantiate
$page_to_load = new pageToLoad();
$page_to_load->regURL = $regURL;
$page_to_load->regPage = $regPage;
?>
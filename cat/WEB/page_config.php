<?php
// page registering
	$regURL[0]		= 'home';								$regPage[0] = 'home.php';		// => also as default page
	$regURL[]		= 'tes';								$regPage[] = 'tes.php';		// => also as default page
	
	$regURL[]= 'content'; $regPage[]= 'content.php';
	$regURL[]= 'pendaftaran'; $regPage[]= 'pendaftaran.php';
	$regURL[]= 'data_pribadi'; $regPage[]= 'data_pribadi.php';
	$regURL[]= 'data_pendidikan_formal'; $regPage[]= 'data_pendidikan_formal.php';
	$regURL[]= 'data_pribadi_pelatihan'; $regPage[]= 'data_pribadi_pelatihan.php';
	$regURL[]= 'data_pribadi_lain'; $regPage[]= 'data_pribadi_lain.php';
	$regURL[]= 'data_lampiran'; $regPage[]= 'data_lampiran.php';
	$regURL[]= 'data_peminatan'; $regPage[]= 'data_peminatan.php';
	$regURL[]= 'data_password'; $regPage[]= 'data_password.php';
	$regURL[]= 'upload_ujian'; $regPage[]= 'upload_ujian.php';
	$regURL[]= 'kuisioner'; $regPage[]= 'kuisioner.php';


	
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
	$regURL[]		= 'ujian_kraepelin';						$regPage[] = 'ujian_kraepelin.php';
	$regURL[]		= 'ujian_kraepelinbak';						$regPage[] = 'ujian_kraepelin20190121.php';
	$regURL[]		= 'ujian_onlineBak';						$regPage[] = 'ujian_onlineBak.php';
	$regURL[]		= 'finish';								$regPage[] = 'finish.php';

	$regURL[]= 'ujian_new_kraepelin'; $regPage[]= 'ujian_new_kraepelin.php';
	$regURL[]= 'ujian_new_kraepelin_latihan'; $regPage[]= 'ujian_new_kraepelin_latihan.php';
	$regURL[]= 'ujian_pauli'; $regPage[]= 'ujian_pauli.php';
	$regURL[]= 'ujian_pauli_latihan'; $regPage[]= 'ujian_pauli_latihan.php';
	$regURL[]= 'ujian_pilihan_ingat'; $regPage[]= 'ujian_pilihan_ingat.php';

	$regURL[]= 'ujian_online_latihan'; $regPage[]= 'ujian_online_latihan.php';
	$regURL[]= 'finish_latihan'; $regPage[]= 'finish_latihan.php';
	
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
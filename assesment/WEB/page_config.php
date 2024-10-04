<?php
// page registering
	$regURL[0]		= 'home';								$regPage[0] = 'home.php';		// => also as default page
	
	$regURL[]		= 'berita';								$regPage[] = 'berita.php';
	$regURL[]		= 'agenda';								$regPage[] = 'agenda.php';
	$regURL[]		= 'galeri';								$regPage[] = 'galeri.php';
	$regURL[]		= 'artikel';							$regPage[] = 'artikel.php';
	
	$regURL[]		= 'kontak';								$regPage[] = 'kontak.php';
	$regURL[]		= 'slhd';								$regPage[] = 'slhd.php';
	$regURL[]		= 'mlh';								$regPage[] = 'mlh.php';
	
	$regURL[]		= 'visimisi';							$regPage[] = 'visimisi.php';
	$regURL[]		= 'tujuansasaran';						$regPage[] = 'tujuansasaran.php';
	$regURL[]		= 'rencanastrategis';					$regPage[] = 'rencanastrategis.php';
	$regURL[]		= 'strukturorganisasi';					$regPage[] = 'strukturorganisasi.php';
	$regURL[]		= 'tugaspokok';							$regPage[] = 'tugaspokok.php';
	$regURL[]		= 'program';							$regPage[] = 'program.php';
	
	$regURL[]		= 'undangundang';						$regPage[] = 'undangundang.php';
	$regURL[]		= 'peraturanpemerintah';				$regPage[] = 'peraturanpemerintah.php';
	$regURL[]		= 'peraturanmenteri';					$regPage[] = 'peraturanmenteri.php';
	$regURL[]		= 'keputusanmenteri';					$regPage[] = 'keputusanmenteri.php';
	$regURL[]		= 'peraturangubernur';					$regPage[] = 'peraturangubernur.php';
	$regURL[]		= 'keputusangubernur';					$regPage[] = 'keputusangubernur.php';
	$regURL[]		= 'peraturandaerah';					$regPage[] = 'peraturandaerah.php';
	$regURL[]		= 'peraturanbupati';					$regPage[] = 'peraturanbupati.php';
	
	$regURL[]		= 'umumkepegawaian';					$regPage[] = 'umumkepegawaian.php';
	$regURL[]		= 'keuangan';							$regPage[] = 'keuangan.php';
	$regURL[]		= 'perencanaan';						$regPage[] = 'perencanaan.php';
	$regURL[]		= 'pengkajiandampak';					$regPage[] = 'pengkajiandampak.php';
	$regURL[]		= 'pengembangankapasitaskelembagaan';	$regPage[] = 'pengembangankapasitaskelembagaan.php'; 	
	$regURL[]		= 'pelestariankonservasi';				$regPage[] = 'pelestariankonservasi.php';
	$regURL[]		= 'pengendalian';						$regPage[] = 'pengendalian.php';
	$regURL[]		= 'penanggulanganlimbahdomestik';		$regPage[] = 'penanggulanganlimbahdomestik.php';
	$regURL[]		= 'pemanfaatanpemusnahan';				$regPage[] = 'pemanfaatanpemusnahan.php';
	$regURL[]		= 'pengelolaanpertamanan';				$regPage[] = 'pengelolaanpertamanan.php';
	$regURL[]		= 'peneranganjalanumum';				$regPage[] = 'peneranganjalanumum.php';
	
	$regURL[]		= 'sanitasiumum';						$regPage[] = 'sanitasiumum.php';
	$regURL[]		= 'persampahan';						$regPage[] = 'persampahan.php';
	$regURL[]		= 'ijinHO';								$regPage[] = 'ijinHO.php';
	$regURL[]		= 'ijinamdal';							$regPage[] = 'ijinamdal.php';
	$regURL[]		= 'dataPJU';							$regPage[] = 'dataPJU.php';
	$regURL[]		= 'integrasi';							$regPage[] = 'integrasi.php';
	
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
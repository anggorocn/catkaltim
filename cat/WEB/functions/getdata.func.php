<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: date.func.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle date operations
***************************************************************************************************** */
	include_once("../WEB/classes/base/Satker.php");
	include_once("../WEB/classes/base/TingkatHukuman.php");
	include_once("../WEB/classes/base/Agama.php");
	include_once("../WEB/classes/base/Bank.php");
	include_once("../WEB/classes/base/TipePegawai.php");
	include_once("../WEB/classes/base/StatusPegawai.php");
	include_once("../WEB/classes/base/JenisPegawai.php");
	include_once("../WEB/classes/base/Kedudukan.php");
	include_once("../WEB/classes/base/JenisHukuman.php");
	include_once("../WEB/classes/base/PejabatPenetap.php");
	include_once("../WEB/classes/base/Propinsi.php");
	include_once("../WEB/classes/base/Kabupaten.php");
	include_once("../WEB/classes/base/Kecamatan.php");
	include_once("../WEB/classes/base/Kelurahan.php");
	include_once("../WEB/classes/base/Pendidikan.php");
	include_once("../WEB/classes/base/Diklat.php");
	include_once("../WEB/classes/base/JurusanPendidikan.php");
	include_once("../WEB/classes/base/Pangkat.php");
	include_once("../WEB/classes/base/Eselon.php");
	
	function getSatker($id){
		$satker= new Satker();
		$satker->selectByParams(array("SATKER_ID" => $id),-1,-1,'');
		$satker->firstRow();
		$value = $satker->getField('NAMA');
		return $value;
	}
	
	function getJenisBahasa($id){
		switch($id){
		case "1"	:
			$value = "Asing";
			break;
		case "2":
			$value = "Daerah";
			break;
		}
		return $value;
	}
	
	function getKemampuanBicara($id){
		switch($id){
		case "1"	:
			$value = "Aktif";
			break;
		case "2":
			$value = "Pasif";
			break;
		}
		return $value;
	}
	
	function getJenisTugas($id){
		switch($id){
		case "1"	:
			$value = "Dalam Negeri";
			break;
		case "2":
			$value = "Luar Negeri";
			break;
		}
		return $value;
	}
	
	function getJenisCuti($id){
		switch($id){
		case "1"	:
			$value = "Cuti Tahunan";
			break;
		case "2":
			$value = "Cuti Besar";
			break;
		case "3":
			$value = "Cuti Sakit";
			break;
		case "4":
			$value = "Cuti Bersalin";
			break;
		case "5":
			$value = "CLTN";
			break;
		case "6":
			$value = "Perpanjangan CLTN";
			break;
		case "7":
			$value = "Cuti Menikah";
			break;
        case "8":
			$value = "Cuti karena alasan penting";
			break;
		}
		return $value;
	}
	
	function getTingkatHukuman($id){
		$tingkat_hukuman = new TingkatHukuman();
		$tingkat_hukuman->selectByParams(array("TINGKAT_HUKUMAN_ID" => $id),-1,-1,'');
		$tingkat_hukuman->firstRow();
		$value = $tingkat_hukuman->getField('NAMA');
		return $value;
	}
	
	function getJenisPegawai($id){
		$jenis_pegawai= new JenisPegawai();
		$jenis_pegawai->selectByParams(array("JENIS_PEGAWAI_ID" => $id),-1,-1,'');
		$jenis_pegawai->firstRow();
		$value = $jenis_pegawai->getField('NAMA');
		return $value;
	}
	
	function getStatusPernikahan($id){
		switch($id){
		case "1"	:
			$value = "Belum Kawin";
			break;
		case "2":
			$value = "Kawin";
			break;
        case "3":
			$value = "Janda";
			break;
        case "4":
			$value = "Duda";
			break;
		}
		return $value;
	}
	
	function getStatusPegawai($id){
		$status_pegawai= new StatusPegawai();
		$status_pegawai->selectByParams(array("STATUS_PEGAWAI_ID" => $id),-1,-1,'');
		$status_pegawai->firstRow();
		$value = $status_pegawai->getField('NAMA');
		return $value;
	}
	
	function getTipePegawai($id){
		$tipe_pegawai= new TipePegawai();
		$tipe_pegawai->selectByParams(array("TIPE_PEGAWAI_ID" => $id),-1,-1,'');
		$tipe_pegawai->firstRow();
		if($tipe_pegawai->getField('TIPE_PEGAWAI_ID_PARENT') == 0)  $value= $tipe_pegawai->getField('TIPE_PEGAWAI_ID').'.'.$tipe_pegawai->getField('NAMA');
		else														$value= substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),0,1).'.'.substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),1).'.'.$tipe_pegawai->getField('NAMA');
		
		
		return $value;
	}
	
	function getBank($id){
		$bank= new Bank();
		$bank->selectByParams(array("BANK_ID" => $id),-1,-1,'');
		$bank->firstRow();
		$value = $bank->getField('NAMA');
		return $value;
	}
	
	function getAgama($id){
		$agama= new Agama();
		$agama->selectByParams(array("AGAMA_ID" => $id),-1,-1,'');
		$agama->firstRow();
		$value = $agama->getField('NAMA');
		return $value;
	}
	
	function getKedudukan($id){
		$kedudukan= new Kedudukan();
		$kedudukan->selectByParams(array("KEDUDUKAN_ID" => $id),-1,-1,'');
		$kedudukan->firstRow();
		$value = $kedudukan->getField('NAMA');
		return $value;
	}
	
	function getJenisHukuman($id){
		$jenis_hukuman = new JenisHukuman();
		$jenis_hukuman->selectByParams(array("JENIS_HUKUMAN_ID" => $id),-1,-1,'');
		$jenis_hukuman->firstRow();
		$value = $jenis_hukuman->getField('NAMA');
		return $value;
	}
	
	function getPejabat($id){
		$pejabat_penetap = new PejabatPenetap();
		$pejabat_penetap->selectByParams(array("PEJABAT_PENETAP_ID" => $id),-1,-1,'');
		$pejabat_penetap->firstRow();
		$value = $pejabat_penetap->getField('NAMA');
		return $value;
	}
	
	function getPejabatPenetap($id){
		$pejabat_penetap = new PejabatPenetap();
		$pejabat_penetap->selectByParams(array("PEJABAT_PENETAP_ID" => $id),-1,-1,'');
		$pejabat_penetap->firstRow();
		$value = $pejabat_penetap->getField('JABATAN');
		return $value;
	}
	
	function getNamaPenghargaan($id){
		switch($id){
		case "1"	:
			$value = "Satya Lencana Karya Satya X (Perunggu)";
			break;
		case "2":
			$value = "Satya Lencana Karya Satya XX (Perak)";
			break;
		case "3":
			$value = "Satya Lencana Karya Satya XXX (Emas)";
			break;
		}
		return $value;
	}
	
	function getPropinsi($id){
		$propinsi = new Propinsi();
		$propinsi->selectByParams(array("PROPINSI_ID" => $id),-1,-1,'');
		$propinsi->firstRow();
		$value = $propinsi->getField('NAMA');
		return $value;
	}
	
	function getKabupaten($id_prop,$id){
		$kabupaten = new Kabupaten();
		$kabupaten->selectByParams(array("PROPINSI_ID" => $id_prop,"KABUPATEN_ID" => $id),-1,-1,'');
		$kabupaten->firstRow();
		$value = $kabupaten->getField('NAMA');
		return $value;
	}
	
	function getKecamatan($id_prop, $id_kab, $id){
		$kecamatan = new Kecamatan();
		$kecamatan->selectByParams(array("PROPINSI_ID" => $id_prop,"KABUPATEN_ID" => $id_kab,"KECAMATAN_ID" => $id),-1,-1,'');
		$kecamatan->firstRow();
		$value = $kecamatan->getField('NAMA');
		return $value;
	}
	
	function getKelurahan($id_prop, $id_kab, $id_kel, $id){
		$kelurahan = new Kelurahan();
		$kelurahan->selectByParams(array("PROPINSI_ID" => $id_prop,"KABUPATEN_ID" => $id_kab,"KECAMATAN_ID" => $id_kel, "KELURAHAN_ID" => $id),-1,-1,'');
		$kelurahan->firstRow();
		$value = $kelurahan->getField('NAMA');
		return $value;
	}
	
	function getStatusAnak($id){
		switch($id){
		case "1"	:
			$value = "Kandung";
			break;
		case "2":
			$value = "Tiri";
			break;
		case "3":
			$value = "Angkat";
			break;
		}
		return $value;
	}
	
	function getPendidikan($id){
		$pendidikan = new Pendidikan();
		$pendidikan->selectByParams(array("PENDIDIKAN_ID" => $id),-1,-1,'');
		$pendidikan->firstRow();
		$value = $pendidikan->getField('NAMA');
		return $value;
	}
	
	function getStatusDapatTunjangan($id){
		switch($id){
		case "1"	:
			$value = "Dapat";
			break;
		case "2":
			$value = "Tidak";
			break;
		}
		return $value;
	}
	
	function getDiklat($id){
		$diklat = new Diklat();
		$diklat->selectByParams(array("DIKLAT_ID" => $id),-1,-1,'');
		$diklat->firstRow();
		$value = $diklat->getField('NAMA');
		return $value;
	}
	
	function getJurusanPendidikan($id){
		$jurusan_pendidikan = new JurusanPendidikan();
		$jurusan_pendidikan->selectByParams(array("JURUSAN_PENDIDIKAN_ID" => $id),-1,-1,'');
		$jurusan_pendidikan->firstRow();
		$value = $jurusan_pendidikan->getField('NAMA');
		return $value;
	}
	
	function getPangkat($id){
		$pangkat = new Pangkat();
		$pangkat->selectByParams(array("PANGKAT_ID" => $id),-1,-1,'');
		$pangkat->firstRow();
		$value = $pangkat->getField('KODE');
		return $value;
	}
	
	function getEselon($id){
		$eselon = new Eselon();
		$eselon->selectByParams(array("ESELON_ID" => $id),-1,-1,'');
		$eselon->firstRow();
		$value = $eselon->getField('NAMA');
		return $value;
	}
	
	function getJenisKenaikanGaji($id){
		switch($id){
		case "1"	:
			$value = "Kenaikan Pangkat";
			break;
		case "2":
			$value = "Gaji Berkala";
			break;
		case "3"	:
			$value = "Penyesuaian Tabel Gaji Pokok";
			break;
		case "4":
			$value = "SK Lain-lain";
			break;
		
		}
		
		return $value;
	}
	
	function getSTLUD($id){
		switch($id){
		case "1"	:
			$value = "Tingkat I";
			break;
		case "2":
			$value = "Tingkat II";
			break;
		case "3"	:
			$value = "Tingkat III";
			break;		
		}
		
		return $value;
	}
	
	function getJenisKenaikanPangkat($id){
		switch($id){
		case "1"	:
			$value = "Reguler";
			break;
		case "2":
			$value = "Pilihan";
			break;
		case "3"	:
			$value = "Anumerta";
			break;		
		case "4":
			$value = "Pengabdian";
			break;
		case "5"	:
			$value = "SK lain-lain";
			break;
		}
		
		return $value;
	}
	function getStatusPns($id){
		switch($id){
		case "1"	:
			$value = "PNS";
			break;
		default:
			$value = "Non-PNS";
		}
		
		return $value;
	}
	
	
?>

<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel PELAMAR.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Pelamar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pelamar()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_ID", $this->getAdminNextId("PELAMAR_ID","pds_rekrutmen.PELAMAR")); 		
		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR (
				   PELAMAR_ID, NAMA, NIPP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, 
				   STATUS_KAWIN, GOLONGAN_DARAH, ALAMAT, 
   				   TELEPON, EMAIL, NRP, REKENING_NO, REKENING_NAMA, NPWP, TANGGAL_PENSIUN, 
				   TANGGAL_MUTASI_KELUAR, TANGGAL_WAFAT, TANGGAL_MPP, NO_MPP, LAST_CREATE_USER, LAST_CREATE_DATE,
				   JAMSOSTEK_NO, JAMSOSTEK_TANGGAL, KESEHATAN_NO, KESEHATAN_TANGGAL, HOBBY, FINGER_ID, TANGGAL_NPWP, TINGGI, BERAT_BADAN, NO_SEPATU, KTP_NO, TGL_NON_AKTIF, TGL_DIKELUARKAN, 
				   TGL_KONTRAK_AKHIR, KELOMPOK_PELAMAR, KESEHATAN_FASKES, KK_NO, DOMISILI, KOTA_ID, IS_KERABAT, SUMBER_INFO, NAMA_KERABAT, POSISI_KERABAT
				   ) 
 			  	VALUES (
				  ".$this->getField("PELAMAR_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("NIPP")."',
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR").",
				  '".$this->getField("STATUS_KAWIN")."',
				  '".$this->getField("GOLONGAN_DARAH")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("TELEPON")."',
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("NRP")."',
				  '".$this->getField("REKENING_NO")."',
				  '".$this->getField("REKENING_NAMA")."',
				  '".$this->getField("NPWP")."',
				  ".$this->getField("TANGGAL_PENSIUN").",
				  ".$this->getField("TANGGAL_MUTASI_KELUAR").",
				  ".$this->getField("TANGGAL_WAFAT").",
				  ".$this->getField("TANGGAL_MPP").",
				  '".$this->getField("NO_MPP")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("JAMSOSTEK_NO")."',
				  ".$this->getField("JAMSOSTEK_TANGGAL").",
				  '".$this->getField("KESEHATAN_NO")."',
				  ".$this->getField("KESEHATAN_TANGGAL").",
				  '".$this->getField("HOBBY")."',
				  ".$this->getField("FINGER_ID").",
				  ".$this->getField("TANGGAL_NPWP").",
				  '".$this->getField("TINGGI")."',
				  '".$this->getField("BERAT_BADAN")."',
				  '".$this->getField("NO_SEPATU")."',
				  '".$this->getField("KTP_NO")."',
				  ".$this->getField("TGL_NON_AKTIF").",
				  ".$this->getField("TGL_DIKELUARKAN").",
				  ".$this->getField("TGL_KONTRAK_AKHIR").",
				  '".$this->getField("KELOMPOK_PELAMAR")."',
				  '".$this->getField("KESEHATAN_FASKES")."',
				  '".$this->getField("KK_NO")."',
				  '".$this->getField("DOMISILI")."',
				  ".$this->getField("KOTA_ID").",
				  '".$this->getField("IS_KERABAT")."',
				  '".$this->getField("SUMBER_INFO")."',
				  '".$this->getField("NAMA_KERABAT")."',
				  '".$this->getField("POSISI_KERABAT")."'
				)"; 
		$this->id = $this->getField("PELAMAR_ID");
		//echo $str;
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR
				SET    
					   NAMA           			= '".$this->getField("NAMA")."',
					   NIPP      				= '".$this->getField("NIPP")."',
					   JENIS_KELAMIN    		= '".$this->getField("JENIS_KELAMIN")."',
					   TEMPAT_LAHIR     		= '".$this->getField("TEMPAT_LAHIR")."',
					   TANGGAL_LAHIR			= ".$this->getField("TANGGAL_LAHIR").",
					   STATUS_KAWIN				= '".$this->getField("STATUS_KAWIN")."',
					   GOLONGAN_DARAH			= '".$this->getField("GOLONGAN_DARAH")."',
					   ALAMAT					= '".$this->getField("ALAMAT")."',
					   TELEPON					= '".$this->getField("TELEPON")."',
					   EMAIL					= '".$this->getField("EMAIL")."',
					   NRP						= '".$this->getField("NRP")."',
					   DEPARTEMEN_ID			= ".$this->getField("DEPARTEMEN_ID").",
					   AGAMA_ID					= '".$this->getField("AGAMA_ID")."',
					   REKENING_NAMA			= '".$this->getField("REKENING_NAMA")."',
					   NPWP						= '".$this->getField("NPWP")."',
					   TANGGAL_PENSIUN			= ".$this->getField("TANGGAL_PENSIUN").",
					   TANGGAL_MUTASI_KELUAR	= ".$this->getField("TANGGAL_MUTASI_KELUAR").",
					   TANGGAL_WAFAT			= ".$this->getField("TANGGAL_WAFAT").",
					   TANGGAL_MPP				= ".$this->getField("TANGGAL_MPP").",
				  	   NO_MPP					= '".$this->getField("NO_MPP")."',
					   STATUS_KELUARGA_ID		= ".$this->getField("STATUS_KELUARGA_ID").",
					   LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
					   JAMSOSTEK_NO				= '".$this->getField("JAMSOSTEK_NO")."',
					   JAMSOSTEK_TANGGAL		= ".$this->getField("JAMSOSTEK_TANGGAL").",
					   KESEHATAN_NO				= '".$this->getField("KESEHATAN_NO")."',
					   KESEHATAN_TANGGAL		= ".$this->getField("KESEHATAN_TANGGAL").",
					   KESEHATAN_FASKES			= '".$this->getField("KESEHATAN_FASKES")."',
					   KK_NO					= '".$this->getField("KK_NO")."',
					   HOBBY					= '".$this->getField("HOBBY")."',
					   FINGER_ID				= ".$this->getField("FINGER_ID").",
					   TANGGAL_NPWP				= ".$this->getField("TANGGAL_NPWP").",
					   TINGGI					= '".$this->getField("TINGGI")."',
				  	   BERAT_BADAN				= '".$this->getField("BERAT_BADAN")."',
				  	   NO_SEPATU				= '".$this->getField("NO_SEPATU")."',
					   KTP_NO					= '".$this->getField("KTP_NO")."',
				  	   TGL_DIKELUARKAN			= ".$this->getField("TGL_DIKELUARKAN").",
				  	   TGL_KONTRAK_AKHIR		= ".$this->getField("TGL_KONTRAK_AKHIR").",
					   TGL_NON_AKTIF 			= ".$this->getField("TGL_NON_AKTIF").",
					   DOMISILI		 			= '".$this->getField("DOMISILI")."',
					   KOTA_ID		 			= '".$this->getField("KOTA_ID")."'
				WHERE  PELAMAR_ID     			= '".$this->getField("PELAMAR_ID")."'
			 "; //FOTO= '".$this->getField("FOTO")."',
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePelamar()
	{
		//JABATAN_TERAKHIR;JUMLAH_ANAK;TANGGAL_LAHIR;PROPINSI_ID;KOTA_ID;PROPINSI_DOMISILI_ID;KOTA_DOMISILI_ID;IS_ALAMAT_KTP;AGAMA;STATUS_KAWIN;KTP_BERLAKU;PENDIDIKAN_ID;TOTAL_TAHUN_BEKERJA;TINGGI;BERAT_BADAN
  		//NO_KTP;NPP;NAMA;TEMPAT_LAHIR;ALAMAT;KOTA;JENIS_KELAMIN;TELP_RUMAH;KODE_TELP_RUMAH;ALAMAT_DOMISILI;KODE_POS;NPWP;KK_NOMOR;NO_HP;PENGALAMAN_BEKERJA_TERAKHIR
		$str = "
				UPDATE pds_rekrutmen.PELAMAR
				SET    
					   TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
					   TANGGAL_NPWP= ".$this->getField("TANGGAL_NPWP").",
					   AGAMA_ID= ".$this->getField("AGAMA_ID").",
					   TINGGI= ".$this->getField("TINGGI").",
					   BERAT_BADAN= ".$this->getField("BERAT_BADAN").",
					   KOTA_ID= ".$this->getField("KOTA_ID").",
					   STATUS_KELUARGA_ID= ".$this->getField("STATUS_KELUARGA_ID").",
					   KTP_NO= '".$this->getField("KTP_NO")."',
					   KK_NO= '".$this->getField("KK_NO")."',
					   JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
					   ALAMAT= '".$this->getField("ALAMAT")."',
					   GOLONGAN_DARAH= '".$this->getField("GOLONGAN_DARAH")."',
					   STATUS_KAWIN= '".$this->getField("STATUS_KAWIN")."',
					   NPWP= '".$this->getField("NPWP")."',
					   TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
					   STATUS_KACAMATA= ".$this->getField("STATUS_KACAMATA")."
				WHERE  PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
			 "; //FOTO= '".$this->getField("FOTO")."',
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function verifikasi()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR
				SET    
					   VERIFIKASI     			= '".$this->getField("VERIFIKASI")."',
					   LAST_VERIFIED_USER     	= '".$this->getField("LAST_VERIFIED_USER")."',
					   LAST_VERIFIED_DATE     	= ".$this->getField("LAST_VERIFIED_DATE")."
				WHERE  PELAMAR_ID     			= '".$this->getField("PELAMAR_ID")."'
			 "; //FOTO= '".$this->getField("FOTO")."',
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function resetPassword()
	{
		$str = "
				UPDATE pds_rekrutmen.USER_LOGIN
				SET    
					   USER_PASS     			= '".$this->getField("USER_PASS")."'
				WHERE  PELAMAR_ID     			= '".$this->getField("PELAMAR_ID")."'
			 "; //FOTO= '".$this->getField("FOTO")."',
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusSyaratKetentuan()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR
				SET
					STATUS_SYARAT_KETENTUAN= 1
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
			 ";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PELAMAR_ID = ".$this->getField("PELAMAR_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
		
				
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function delete()
	{
        $str = "UPDATE pds_rekrutmen.PELAMAR
				SET
					   STATUS_PELAMAR_ID	 = 6,
					   TGL_NON_AKTIF 	     = TO_DATE('01-01-1990', 'DD-MM-YYYY'),
					   STATUS_HAPUS 		 = 1
				WHERE  PELAMAR_ID     		 = '".$this->getField("PELAMAR_ID")."' "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT PELAMAR_ID, NRP, NIPP, A.NAMA, JENIS_KELAMIN, TEMPAT_LAHIR,
					   TANGGAL_LAHIR, STATUS_KAWIN, GOLONGAN_DARAH, ALAMAT, TELEPON, EMAIL,
					   FOTO, DEPARTEMEN_ID, A.AGAMA_ID, STATUS_PELAMAR_ID, BANK_ID, REKENING_NO,
					   REKENING_NAMA, NPWP, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR,
					   TANGGAL_WAFAT, TANGGAL_MPP, NO_MPP, STATUS_KELUARGA_ID, FINGER_ID,
					   JAMSOSTEK_NO, JAMSOSTEK_TANGGAL, HOBBY, NIS, TANGGAL_NPWP, TINGGI,
					   BERAT_BADAN, MAGANG_TIPE, KTP_NO, TGL_NON_AKTIF, KELOMPOK_PELAMAR,
					   REKENING_NO2, PENDIDIKAN_TERAKHIR, STATUS_HAPUS, TGL_DIKELUARKAN,
					   LINK_BLANKO_KGB1, LINK_BLANKO_KGB2, LINK_BLANKO_KGB3,
					   TGL_KONTRAK_AKHIR, KESEHATAN_NO, KESEHATAN_TANGGAL, KESEHATAN_FASKES,
					   KK_NO, NO_SEPATU, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER,
					   A.LAST_UPDATE_DATE, PAKTA_INTEGRITAS, LAMPIRAN_CV, LAMPIRAN_KTP,
					   LAMPIRAN_FOTO, LAMPIRAN_IJASAH, LAMPIRAN_TRANSKRIP, LAMPIRAN_SKCK,
					   LAMPIRAN_SKS,
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   pds_rekrutmen.AMBIL_UMUR(TANGGAL_LAHIR) UMUR, VERIFIKASI, LAST_VERIFIED_USER, LAST_VERIFIED_DATE, B.NAMA AGAMA, 
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_NIKAH, DOMISILI, A.KOTA_ID, C.NAMA NAMA_KOTA
					   , A.STATUS_KACAMATA, CASE A.STATUS_KACAMATA WHEN 1 THEN 'Ya' ELSE 'Tidak' END STATUS_KACAMATA_NAMA
					   , A.STATUS_SYARAT_KETENTUAN, IS_KERABAT, SUMBER_INFO, NAMA_KERABAT, POSISI_KERABAT
				  FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN cat.KABUPATEN C ON A.KOTA_ID = C.KABUPATEN_ID
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sOrder;
		
		//" ORDER BY A.NAMA ASC"
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsCutOff($cutOff, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, NIPP, A.NAMA, JENIS_KELAMIN, TEMPAT_LAHIR,
					   TANGGAL_LAHIR, STATUS_KAWIN, GOLONGAN_DARAH, ALAMAT, TELEPON, EMAIL,
					   FOTO, DEPARTEMEN_ID, A.AGAMA_ID, STATUS_PELAMAR_ID, BANK_ID, REKENING_NO,
					   REKENING_NAMA, NPWP, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR,
					   TANGGAL_WAFAT, TANGGAL_MPP, NO_MPP, STATUS_KELUARGA_ID, FINGER_ID,
					   JAMSOSTEK_NO, JAMSOSTEK_TANGGAL, HOBBY, NIS, TANGGAL_NPWP, TINGGI,
					   BERAT_BADAN, MAGANG_TIPE, KTP_NO, TGL_NON_AKTIF, KELOMPOK_PELAMAR,
					   REKENING_NO2, PENDIDIKAN_TERAKHIR, STATUS_HAPUS, TGL_DIKELUARKAN,
					   LINK_BLANKO_KGB1, LINK_BLANKO_KGB2, LINK_BLANKO_KGB3,
					   TGL_KONTRAK_AKHIR, KESEHATAN_NO, KESEHATAN_TANGGAL, KESEHATAN_FASKES,
					   KK_NO, NO_SEPATU, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER,
					   A.LAST_UPDATE_DATE, PAKTA_INTEGRITAS, LAMPIRAN_CV, LAMPIRAN_KTP,
					   LAMPIRAN_FOTO, LAMPIRAN_IJASAH, LAMPIRAN_TRANSKRIP, LAMPIRAN_SKCK,
					   LAMPIRAN_SKS,
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, VERIFIKASI, LAST_VERIFIED_USER, LAST_VERIFIED_DATE, B.NAMA AGAMA, 
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_NIKAH, DOMISILI, A.KOTA_ID, C.NAMA NAMA_KOTA,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_JABATAN(A.PELAMAR_ID) PEMINATAN_JABATAN,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_LOKASI(A.PELAMAR_ID) PEMINATAN_LOKASI,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT_SIMPLE(A.PELAMAR_ID) SERTIFIKAT,
					   D.PENDIDIKAN || '-' || D.JURUSAN_PENDIDIKAN PENDIDIKAN,
					   E.JABATAN, E.DURASI, E.PERUSAHAAN
				  FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN cat.KABUPATEN C ON A.KOTA_ID = C.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN E ON A.PELAMAR_ID = E.PELAMAR_ID
				WHERE 1 = 1 AND NOT EXISTS(SELECT 1 FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.HADIR = 1)
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sOrder;
		
		//" ORDER BY A.NAMA ASC"
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsTahapanCutOff($cutOff, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, NIPP, A.NAMA, JENIS_KELAMIN, TEMPAT_LAHIR,
					   TANGGAL_LAHIR, STATUS_KAWIN, GOLONGAN_DARAH, ALAMAT, TELEPON, A.EMAIL,
					   FOTO, DEPARTEMEN_ID, A.AGAMA_ID, STATUS_PELAMAR_ID, BANK_ID, REKENING_NO,
					   REKENING_NAMA, NPWP, TANGGAL_PENSIUN, TANGGAL_MUTASI_KELUAR,
					   TANGGAL_WAFAT, TANGGAL_MPP, NO_MPP, STATUS_KELUARGA_ID, FINGER_ID,
					   JAMSOSTEK_NO, JAMSOSTEK_TANGGAL, HOBBY, NIS, TANGGAL_NPWP, TINGGI,
					   BERAT_BADAN, MAGANG_TIPE, KTP_NO, TGL_NON_AKTIF, KELOMPOK_PELAMAR,
					   REKENING_NO2, PENDIDIKAN_TERAKHIR, STATUS_HAPUS, TGL_DIKELUARKAN,
					   LINK_BLANKO_KGB1, LINK_BLANKO_KGB2, LINK_BLANKO_KGB3,
					   TGL_KONTRAK_AKHIR, KESEHATAN_NO, KESEHATAN_TANGGAL, KESEHATAN_FASKES,
					   KK_NO, NO_SEPATU, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER,
					   A.LAST_UPDATE_DATE, PAKTA_INTEGRITAS, LAMPIRAN_CV, LAMPIRAN_KTP,
					   LAMPIRAN_FOTO, LAMPIRAN_IJASAH, LAMPIRAN_TRANSKRIP, LAMPIRAN_SKCK,
					   LAMPIRAN_SKS,
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, VERIFIKASI, LAST_VERIFIED_USER, LAST_VERIFIED_DATE, B.NAMA AGAMA, 
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_NIKAH, DOMISILI, A.KOTA_ID, C.NAMA NAMA_KOTA,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_JABATAN(A.PELAMAR_ID) PEMINATAN_JABATAN,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_LOKASI(A.PELAMAR_ID) PEMINATAN_LOKASI,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT_SIMPLE(A.PELAMAR_ID) SERTIFIKAT,
					   D.PENDIDIKAN || '-' || D.JURUSAN_PENDIDIKAN PENDIDIKAN,
					   E.JABATAN, E.DURASI, E.PERUSAHAAN
				  FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN cat.KABUPATEN C ON A.KOTA_ID = C.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN E ON A.PELAMAR_ID = E.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN PL ON A.PELAMAR_ID = PL.PELAMAR_ID AND PL.TANGGAL_KIRIM IS NOT NULL
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN PLT ON A.PELAMAR_ID = PLT.PELAMAR_ID AND PLT.LOWONGAN_ID = PL.LOWONGAN_ID
				WHERE 1 = 1 AND NOT EXISTS(SELECT 1 FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.HADIR = 1)
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sOrder;
		
		//" ORDER BY A.NAMA ASC"
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsDaftarEntrian($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder=" ORDER BY DAFTAR_ENTRIAN_ID ASC ")
	{
		$str = "
				SELECT DAFTAR_ENTRIAN_ID, PELAMAR_ID, A.NAMA, LINK_FILE, ADA, WAJIB_ISI
				FROM pds_rekrutmen.DAFTAR_ENTRIAN_PELAMAR A
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sOrder;
		
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
	function selectByParamsComboNoKTP($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM pds_rekrutmen.PELAMAR A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsCV($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT PELAMAR_ID, NRP, KTP_NO, NIPP, A.NAMA, KK_NO, ALAMAT, TELEPON, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END JENIS_KELAMIN,
					   TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD-MM-YYYY') TTL, B.NAMA AGAMA_NAMA,
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) || ', ' || SUBSTR(C.KODE, 3,1) || ' Anak' STATUS_PERNIKAHAN,
					   GOLONGAN_DARAH, LAMPIRAN_FOTO, EMAIL, D.NAMA DOMISILI, NPWP, TANGGAL_NPWP, TINGGI || ' Cm / ' || BERAT_BADAN || ' Kg' TINGGIBB 
				  FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN pds_rekrutmen.STATUS_KELUARGA C ON A.STATUS_KELUARGA_ID = C.STATUS_KELUARGA_ID
				LEFT JOIN cat.KABUPATEN D ON A.KOTA_ID = D.KABUPATEN_ID
				WHERE 1 = 1
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sOrder;
		
		//" ORDER BY A.NAMA ASC"
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParamsDaftarEntrian($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) ROWCOUNT
				FROM pds_rekrutmen.DAFTAR_ENTRIAN_PELAMAR A
				WHERE 1 = 1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
		
	function getCountByParamsTahapanCutOff($paramsArray=array(), $statement="")
	{
		$str = "SELECT 
				COUNT(1) ROWCOUNT
                FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN cat.KABUPATEN C ON A.KOTA_ID = C.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN E ON A.PELAMAR_ID = E.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN PL ON A.PELAMAR_ID = PL.PELAMAR_ID AND PL.TANGGAL_KIRIM IS NOT NULL
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN PLT ON A.PELAMAR_ID = PLT.PELAMAR_ID AND PLT.LOWONGAN_ID = PL.LOWONGAN_ID
				WHERE 1 = 1 AND NOT EXISTS(SELECT 1 FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.HADIR = 1) ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT 
				COUNT(A.PELAMAR_ID) ROWCOUNT
               FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN cat.KABUPATEN C ON A.KOTA_ID = C.KABUPATEN_ID
                WHERE 1 = 1  ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsCutOff($cutOff, $paramsArray=array(), $statement="")
	{
		$str = "SELECT 
				COUNT(1) ROWCOUNT
                FROM pds_rekrutmen.PELAMAR A
				LEFT JOIN pds_rekrutmen.AGAMA B ON A.AGAMA_ID = B.AGAMA_ID
				LEFT JOIN cat.KABUPATEN C ON A.KOTA_ID = C.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN E ON A.PELAMAR_ID = E.PELAMAR_ID
				WHERE 1 = 1 AND NOT EXISTS(SELECT 1 FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.HADIR = 1) ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getFieldById($field, $id)
	{
		$str = "SELECT 
				".$field." ROWCOUNT
               FROM pds_rekrutmen.PELAMAR A 
                WHERE 1 = 1 AND PELAMAR_ID = '".$id."' "; 
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }


    function getUrut()
	{
		$str = "SELECT TO_CHAR(CURRENT_DATE, 'YYYYMM') || LPAD((COALESCE(MAX(TO_NUMBER(SUBSTR(NRP, 7, 5), 'FM99999')), 0) + 1)::VARCHAR, 5, '0') ROWCOUNT FROM pds_rekrutmen.PELAMAR "; 
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
	
  } 
?>
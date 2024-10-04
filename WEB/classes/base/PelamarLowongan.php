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
  * Entity-base class untuk mengimplementasikan tabel pds_rekrutmen.PELAMAR_LOWONGAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarLowongan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowongan()
	{
      $this->Entity(); 
    }

    function selectByParamsDaftarLamaran($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder= "ORDER BY A.TANGGAL_TES")
	{
		$str = "
		SELECT
		A.JADWAL_AWAL_TES_SIMULASI_ID, A.JADWAL_AWAL_TES_ID, TO_DATE(TO_CHAR(A.TANGGAL_TES, 'YYYY-MM-DD'),'YYYY/MM/DD') TANGGAL_AWAL
		, A.ACARA NAMA_DIKLAT, A.TEMPAT, A.ALAMAT, A.KETERANGAN, COALESCE(A.BATAS_PEGAWAI,0) BATAS_PEGAWAI
		FROM jadwal_awal_tes_simulasi A
		INNER JOIN jadwal_awal_tes_simulasi_pegawai B ON A.JADWAL_AWAL_TES_SIMULASI_ID = B.JADWAL_AWAL_TES_SIMULASI_ID
		WHERE 1=1 ".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDaftarLowongan($pelamarId, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder= "ORDER BY A.TANGGAL_TES")
	{
		$str = "
		SELECT
		A.JADWAL_AWAL_TES_SIMULASI_ID, A.JADWAL_AWAL_TES_ID, TO_DATE(TO_CHAR(A.TANGGAL_TES, 'YYYY-MM-DD'),'YYYY/MM/DD') TANGGAL_AWAL
		, A.ACARA NAMA_DIKLAT, A.TEMPAT, A.ALAMAT, A.KETERANGAN, COALESCE(A.BATAS_PEGAWAI,0) BATAS_PEGAWAI
		FROM jadwal_awal_tes_simulasi A
		INNER JOIN jadwal_awal_tes_simulasi_pegawai B ON A.JADWAL_AWAL_TES_SIMULASI_ID = B.JADWAL_AWAL_TES_SIMULASI_ID
		WHERE 1=1
		AND B.PEGAWAI_ID = ".$pelamarId.$statement;
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDaftarLowonganPegawai($pelamarId, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder= "ORDER BY A.TANGGAL_TES")
	{
		$str = "
		SELECT
		A.JADWAL_AWAL_TES_SIMULASI_ID, A.JADWAL_AWAL_TES_ID, TO_DATE(TO_CHAR(A.TANGGAL_TES, 'YYYY-MM-DD'),'YYYY/MM/DD') TANGGAL_AWAL
		, A.ACARA NAMA_DIKLAT, A.TEMPAT, A.ALAMAT, A.KETERANGAN, COALESCE(A.BATAS_PEGAWAI,0) BATAS_PEGAWAI,c.nip_baru, c.nama nama_pegawai, c.last_jabatan
		FROM jadwal_awal_tes_simulasi A
		INNER JOIN jadwal_awal_tes_simulasi_pegawai B ON A.JADWAL_AWAL_TES_SIMULASI_ID = B.JADWAL_AWAL_TES_SIMULASI_ID
		INNER JOIN simpeg.pegawai c on b.pegawai_id = c.pegawai_id
		WHERE 1=1
		AND B.PEGAWAI_ID = ".$pelamarId.$statement;
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsFasilitatorDikkat($pelamarId, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder= "ORDER BY C.TANGGAL_AWAL")
	{
		$str = "
		SELECT 
			C.NAMA_DIKLAT, C.NAMA_DIKLAT GROUP_INFO,
			C.TANGGAL_AWAL, C.TANGGAL_AKHIR, C.LOKASI,
			A.DIKLAT_FASILITATOR_ID, A.DIKLAT_ID, A.UNIT_KERJA_NAMA, A.NAMA, A.NIP, A.JENIS_KELAMIN
			, CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA, A.MATERI
			, A.FASILITATOR_ID
			, D.JENIS_DIKLAT
		FROM diklat_fasilitator A
		INNER JOIN diklat C ON A.DIKLAT_ID = C.DIKLAT_ID
		INNER JOIN jenis_diklat D ON C.JENIS_DIKLAT_ID = D.JENIS_DIKLAT_ID
		WHERE 1=1
		AND A.FASILITATOR_ID = ".$pelamarId.$statement;
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$sOrder;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM jadwal_awal_tes_simulasi_pegawai
		WHERE 1=1 ".$statement; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_LOWONGAN_ID", $this->getAdminNextId("PELAMAR_LOWONGAN_ID","pds_rekrutmen.PELAMAR_LOWONGAN"));

		$str = "
					INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN (
					   PELAMAR_LOWONGAN_ID, PELAMAR_ID, TANGGAL, TANGGAL_KIRIM, LOWONGAN_ID, STATUS_PELAMAR)
 			  	VALUES (
				  ".$this->getField("PELAMAR_LOWONGAN_ID").",
				  '".$this->getField("PELAMAR_ID")."',
				  CURRENT_DATE,
				  ".$this->getField("TANGGAL_KIRIM").",
				  ".$this->getField("LOWONGAN_ID").",
				  '".$this->getField("STATUS_PELAMAR")."'
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function insertUndangan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_LOWONGAN_ID", $this->getNextId("PELAMAR_LOWONGAN_ID","pds_rekrutmen.PELAMAR_LOWONGAN"));

		$str = "
					INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN (
					   PELAMAR_LOWONGAN_ID, PELAMAR_ID, TANGGAL, TANGGAL_UNDANGAN, TANGGAL_KIRIM, LOWONGAN_ID, STATUS_PELAMAR)
 			  	VALUES (
				  ".$this->getField("PELAMAR_LOWONGAN_ID").",
				  '".$this->getField("PELAMAR_ID")."',
				  CURRENT_DATE,
				  CURRENT_DATE,
				  CURRENT_DATE,
				  ".$this->getField("LOWONGAN_ID").",
				  '".$this->getField("STATUS_PELAMAR")."'
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }	
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN
				SET    
					   PELAMAR_ID          = '".$this->getField("PELAMAR_ID")."',
					   LOWONGAN_ID= ".$this->getField("LOWONGAN_ID")."
				WHERE  PELAMAR_LOWONGAN_ID     = '".$this->getField("PELAMAR_LOWONGAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PELAMAR_LOWONGAN_ID = ".$this->getField("PELAMAR_LOWONGAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

    function updateByField2()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN A SET
				  ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE PELAMAR_LOWONGAN_ID = ".$this->getField("PELAMAR_LOWONGAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN
                WHERE 
                  PELAMAR_LOWONGAN_ID = ".$this->getField("PELAMAR_LOWONGAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT   PELAMAR_LOWONGAN_ID, PELAMAR_ID, TANGGAL, LOWONGAN_ID 
 	 	 			FROM pds_rekrutmen.PELAMAR_LOWONGAN A 
		 		WHERE 1 = 1
				"; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ORDER BY TANGGAL DESC";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDaftarPelamar($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_UMUR(TANGGAL_LAHIR) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || ' ' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, DURASI,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE 'Tidak' END SHORTLIST,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, EMAIL, LAMPIRAN_FOTO, F.NAMA NAMA_KOTA
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID AND B.TANGGAL_KIRIM IS NOT NULL
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
		 		WHERE 1 = 1		 	
				".$statement; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDaftarPelamarMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_UMUR(TANGGAL_LAHIR) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || ' ' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, DURASI,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE 'Tidak' END SHORTLIST,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, EMAIL, LAMPIRAN_FOTO, F.NAMA NAMA_KOTA
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
				LEFT JOIN (
					 SELECT COUNT(1) JUMLAH_DATA, PELAMAR_ID, LOWONGAN_ID
					 FROM
					 (
						 SELECT C.PELAMAR_ID, A.LOWONGAN_ID 
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_DOKUMEN C ON A.DOKUMEN_ID = C.DOKUMEN_ID AND B.PELAMAR_ID = C.PELAMAR_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LAMPIRAN IS NULL OR LAMPIRAN = '')
						 UNION ALL
						 SELECT B.PELAMAR_ID, A.LOWONGAN_ID
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND B.PELAMAR_LOWONGAN_ID = C.PELAMAR_LOWONGAN_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LINK_FILE IS NULL OR LINK_FILE = '')
					 ) A
					 WHERE 1=1
					 GROUP BY PELAMAR_ID, LOWONGAN_ID
				) X ON A.PELAMAR_ID = X.PELAMAR_ID AND B.LOWONGAN_ID = X.LOWONGAN_ID
				LEFT JOIN (SELECT COUNT(1) JUMLAH_WAJIB, LOWONGAN_ID FROM pds_rekrutmen.LOWONGAN_DOKUMEN WHERE WAJIB = '1' GROUP BY LOWONGAN_ID) Y ON B.LOWONGAN_ID = Y.LOWONGAN_ID
		 		WHERE 1 = 1		 	
				".$statement; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDaftarPelamarCutoff($cutOff, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || '-' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, D.DURASI, A.ALAMAT,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT_SIMPLE(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE '' END SHORTLIST,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, EMAIL, LAMPIRAN_FOTO, F.NAMA NAMA_KOTA,
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, VERIFIKASI, LAST_VERIFIED_USER, LAST_VERIFIED_DATE, E.NAMA AGAMA, 
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_NIKAH, DOMISILI, A.KOTA_ID, F.NAMA NAMA_KOTA,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_JABATAN(A.PELAMAR_ID) PEMINATAN_JABATAN,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_LOKASI(A.PELAMAR_ID) PEMINATAN_LOKASI,
					   G.JABATAN, G.DURASI DURASI_JABATAN, G.PERUSAHAAN
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN G ON A.PELAMAR_ID = G.PELAMAR_ID
		 		WHERE 1 = 1	
				".$statement; 
				
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDaftarPelamarCutoffMonitoring($cutOff, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || '-' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, D.DURASI, A.ALAMAT,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT_SIMPLE(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE '' END SHORTLIST,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, EMAIL, LAMPIRAN_FOTO, F.NAMA NAMA_KOTA,
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, VERIFIKASI, LAST_VERIFIED_USER, LAST_VERIFIED_DATE, E.NAMA AGAMA, 
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_NIKAH, DOMISILI, A.KOTA_ID, F.NAMA NAMA_KOTA,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_JABATAN(A.PELAMAR_ID) PEMINATAN_JABATAN,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_LOKASI(A.PELAMAR_ID) PEMINATAN_LOKASI,
					   G.JABATAN, G.DURASI DURASI_JABATAN, G.PERUSAHAAN, B.TANGGAL_KIRIM, CASE WHEN JUMLAH_WAJIB > JUMLAH_DATA THEN 0 ELSE 1 END STATUS_KIRIM, B.LOWONGAN_ID
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN G ON A.PELAMAR_ID = G.PELAMAR_ID
				LEFT JOIN (
					 SELECT COUNT(1) JUMLAH_DATA, PELAMAR_ID, LOWONGAN_ID
					 FROM
					 (
						 SELECT C.PELAMAR_ID, A.LOWONGAN_ID 
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_DOKUMEN C ON A.DOKUMEN_ID = C.DOKUMEN_ID AND B.PELAMAR_ID = C.PELAMAR_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LAMPIRAN IS NULL OR LAMPIRAN = '')
						 UNION ALL
						 SELECT B.PELAMAR_ID, A.LOWONGAN_ID
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND B.PELAMAR_LOWONGAN_ID = C.PELAMAR_LOWONGAN_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LINK_FILE IS NULL OR LINK_FILE = '')
					 ) A
					 WHERE 1=1
					 GROUP BY PELAMAR_ID, LOWONGAN_ID
				) X ON A.PELAMAR_ID = X.PELAMAR_ID AND B.LOWONGAN_ID = X.LOWONGAN_ID
				LEFT JOIN (SELECT COUNT(1) JUMLAH_WAJIB, LOWONGAN_ID FROM pds_rekrutmen.LOWONGAN_DOKUMEN WHERE WAJIB = '1' GROUP BY LOWONGAN_ID) Y ON B.LOWONGAN_ID = Y.LOWONGAN_ID
		 		WHERE 1 = 1	
				".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDaftarAddPelamarCutoff($cutOff, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		//CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE '' END SHORTLIST,
		//(SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || '-' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, D.DURASI, A.ALAMAT,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT_SIMPLE(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, EMAIL, LAMPIRAN_FOTO, F.NAMA NAMA_KOTA,
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   pds_rekrutmen.AMBIL_MASA_KERJA(TANGGAL_LAHIR, TO_DATE('".$cutOff."', 'DD-MM-YYYY')) UMUR, VERIFIKASI, LAST_VERIFIED_USER, LAST_VERIFIED_DATE, E.NAMA AGAMA, 
					   pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_NIKAH, DOMISILI, A.KOTA_ID, F.NAMA NAMA_KOTA,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_JABATAN(A.PELAMAR_ID) PEMINATAN_JABATAN,
					   pds_rekrutmen.AMBIL_PELAMAR_PEMINATAN_LOKASI(A.PELAMAR_ID) PEMINATAN_LOKASI,
					   G.JABATAN, G.DURASI DURASI_JABATAN, G.PERUSAHAAN
				FROM pds_rekrutmen.PELAMAR A 
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN G ON A.PELAMAR_ID = G.PELAMAR_ID
		 		WHERE 1 = 1	
				".$statement; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDaftarPelamarPersyaratan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT A.LOWONGAN_ID, A.NAMA DOKUMEN
				FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
				WHERE 1=1 AND A.WAJIB = '1' AND COALESCE(CAST(A.DOKUMEN_ID AS VARCHAR), NAMA) NOT IN (SELECT DOKUMEN
					 FROM
					 (
						 SELECT C.PELAMAR_ID, A.LOWONGAN_ID, COALESCE(CAST(A.DOKUMEN_ID AS VARCHAR), NAMA) DOKUMEN
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_DOKUMEN C ON A.DOKUMEN_ID = C.DOKUMEN_ID AND B.PELAMAR_ID = C.PELAMAR_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LAMPIRAN IS NULL OR LAMPIRAN = '')
						 UNION ALL
						 SELECT B.PELAMAR_ID, A.LOWONGAN_ID, COALESCE(CAST(A.DOKUMEN_ID AS VARCHAR), NAMA) DOKUMEN
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND B.PELAMAR_LOWONGAN_ID = C.PELAMAR_LOWONGAN_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LINK_FILE IS NULL OR LINK_FILE = '')
					 ) X
					 WHERE 1=1 AND X.LOWONGAN_ID = A.LOWONGAN_ID AND X.PELAMAR_ID = B.PELAMAR_ID)
				".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsDaftarPelamarShortlist($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_UMUR(TANGGAL_LAHIR) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || ' ' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, DURASI,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE 'Tidak' END SHORTLIST,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, A.EMAIL, LAMPIRAN_FOTO, F.EMAIL EMAIL_SHORTLIST, F.SMS, CASE WHEN F.HADIR = 1 THEN 'Ya' WHEN F.HADIR = 2 THEN 'Tidak' ELSE '' END HADIR, TO_CHAR(F.TANGGAL_HADIR, 'DD-MM-YYYY HH24:MI') TANGGAL_HADIR
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST F ON A.PELAMAR_ID = F.PELAMAR_ID AND F.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
		 		WHERE 1 = 1		 	
				".$statement; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }	

    function selectByParamsDaftarPelamarDiterima($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_UMUR(TANGGAL_LAHIR) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					   C.PENDIDIKAN || ' ' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, DURASI,
					   pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT(A.PELAMAR_ID) SERTIFIKAT,
					   pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					   CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE 'Tidak' END SHORTLIST,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					   TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, A.EMAIL, LAMPIRAN_FOTO, F.EMAIL EMAIL_SHORTLIST, F.SMS, CASE WHEN F.HADIR = 1 THEN 'Ya' ELSE '' END HADIR, TO_CHAR(F.TANGGAL_HADIR, 'DD-MM-YYYY HH24:MI') TANGGAL_HADIR,
					   F.NO_BERITA_ACARA, F.DOKUMEN_BERITA_ACARA
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA F ON A.PELAMAR_ID = F.PELAMAR_ID AND F.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
		 		WHERE 1 = 1		 	
				".$statement; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsDaftarPelamarTahapan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.NAMA DESC")
	{
		$str = "
				SELECT 
					CASE WHEN T1.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_1,
					CASE WHEN T2.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_2,
					CASE WHEN T3.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_3,
					CASE WHEN T4.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_4,
					CASE WHEN T5.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_5,
					CASE WHEN T6.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_6,
					CASE WHEN T7.PEGAWAI_ID IS NULL THEN 'Belum' ELSE 'Sudah' END INFO_TIPE_7,
					A.PELAMAR_ID, NRP, KTP_NO, A.NAMA, E.NAMA AGAMA, JENIS_KELAMIN, pds_rekrutmen.AMBIL_UMUR(TANGGAL_LAHIR) UMUR, TINGGI, BERAT_BADAN, pds_rekrutmen.AMBIL_STATUS_NIKAH(STATUS_KAWIN) STATUS_KAWIN,					   
					C.PENDIDIKAN || ' ' || C.JURUSAN_PENDIDIKAN PENDIDIKAN_TERAKHIR, DURASI,
					pds_rekrutmen.AMBIL_PELAMAR_SERTIFIKAT(A.PELAMAR_ID) SERTIFIKAT,
					pds_rekrutmen.AMBIL_PELAMAR_PENDIDIKAN(A.PELAMAR_ID) PENDIDIKAN,
					CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND X.LOWONGAN_ID = B.LOWONGAN_ID) > 0 THEN 'Ya' ELSE 'Tidak' END SHORTLIST,
					(SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.PELAMAR_ID = A.PELAMAR_ID AND NOT X.LOWONGAN_ID = B.LOWONGAN_ID) LAMARAN_LAIN,
					TEMPAT_LAHIR, TANGGAL_LAHIR, TELEPON, A.EMAIL, LAMPIRAN_FOTO, F.EMAIL EMAIL_SHORTLIST, F.SMS, F.NILAI, TO_CHAR(F.TANGGAL_HADIR, 'DD-MM-YYYY HH24:MI') TANGGAL_HADIR,
					CASE WHEN F.LOLOS = 1 THEN 'Ya' WHEN F.LOLOS = 2 THEN 'Tidak' ELSE '' END LOLOS, WAWANCARA_RATA_NILAI, WAWANCARA_RATA_REKOM, PSIKOTES_NILAI, PSIKOTES_REKOM, KESEHATAN_KESIMPULAN, KESEHATAN_KETERANGAN, KESEHATAN_SARAN,
					CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI X WHERE X.PELAMAR_ID = A.PELAMAR_ID  AND X.LOWONGAN_ID = B.LOWONGAN_ID  AND X.LOWONGAN_TAHAPAN_ID = F.LOWONGAN_TAHAPAN_ID AND X.NILAI IS NOT NULL) > 0 THEN 'Sudah' ELSE 'Belum' END SUDAH_NILAI,
					TGL_MULAI
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN F ON A.PELAMAR_ID = F.PELAMAR_ID AND F.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN (SELECT TGL_MULAI, A.UJIAN_ID, LOWONGAN_ID, PEGAWAI_ID
				  FROM cat.UJIAN A
				  LEFT JOIN cat.UJIAN_PEGAWAI_DAFTAR B ON A.UJIAN_ID = B.UJIAN_ID) UJ ON A.PELAMAR_ID = UJ.PEGAWAI_ID AND UJ.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T1 ON A.PELAMAR_ID = T1.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T1.LOWONGAN_TAHAPAN_ID AND T1.TIPE_UJIAN_ID = 1
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T2 ON A.PELAMAR_ID = T2.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T2.LOWONGAN_TAHAPAN_ID AND T2.TIPE_UJIAN_ID = 2
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T3 ON A.PELAMAR_ID = T3.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T3.LOWONGAN_TAHAPAN_ID AND T3.TIPE_UJIAN_ID = 3
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T4 ON A.PELAMAR_ID = T4.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T4.LOWONGAN_TAHAPAN_ID AND T4.TIPE_UJIAN_ID = 4
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T5 ON A.PELAMAR_ID = T5.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T5.LOWONGAN_TAHAPAN_ID AND T5.TIPE_UJIAN_ID = 5
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T6 ON A.PELAMAR_ID = T6.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T6.LOWONGAN_TAHAPAN_ID AND T6.TIPE_UJIAN_ID = 6
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T7 ON A.PELAMAR_ID = T7.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T7.LOWONGAN_TAHAPAN_ID AND T7.TIPE_UJIAN_ID = 7
		 		WHERE 1 = 1			 	
				".$statement; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
		
    function selectByParamsDaftarBerkas($lowonganId, $pelamarId)
	{
		$str = "
				SELECT A.NAMA, D.LINK_FILE FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN C ON C.LOWONGAN_ID = A.LOWONGAN_ID 
				LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN D ON D.PELAMAR_LOWONGAN_ID = C.PELAMAR_LOWONGAN_ID AND A.LOWONGAN_DOKUMEN_ID = D.LOWONGAN_DOKUMEN_ID 
				WHERE 1=1 AND A.LOWONGAN_ID = '".$lowonganId."' AND C.PELAMAR_ID = '".$pelamarId."'
				"; 

		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getPelamarLowonganId($paramsArray=array(), $statement="")
	{
		$str = "SELECT PELAMAR_LOWONGAN_ID AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_LOWONGAN A
		        WHERE PELAMAR_LOWONGAN_ID IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	

    function getValidasiKirimLamaran($lowongan_id, $pelamar_lowongan_id)
	{
		$str = "SELECT CASE WHEN (SELECT COUNT(1) FROM pds_rekrutmen.LOWONGAN_DOKUMEN A WHERE LOWONGAN_ID = ".$lowongan_id." AND WAJIB = '1') = (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN A INNER JOIN pds_rekrutmen.LOWONGAN_DOKUMEN B ON A.LOWONGAN_DOKUMEN_ID = B.LOWONGAN_DOKUMEN_ID WHERE A.PELAMAR_LOWONGAN_ID = ".$pelamar_lowongan_id." AND B.WAJIB = '1') THEN 1 ELSE 0 END ROWCOUNT ".$statement; 
		
		$this->select($str); 
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }

	
    function getCountByParamsDaftarPelamar($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID AND B.TANGGAL_KIRIM IS NOT NULL
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
		 		WHERE 1 = 1			 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
    function getCountByParamsDaftarPelamarMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
				LEFT JOIN (
					 SELECT COUNT(1) JUMLAH_DATA, PELAMAR_ID, LOWONGAN_ID
					 FROM
					 (
						 SELECT C.PELAMAR_ID, A.LOWONGAN_ID 
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_DOKUMEN C ON A.DOKUMEN_ID = C.DOKUMEN_ID AND B.PELAMAR_ID = C.PELAMAR_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LAMPIRAN IS NULL OR LAMPIRAN = '')
						 UNION ALL
						 SELECT B.PELAMAR_ID, A.LOWONGAN_ID
						 FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
						 LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
						 INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND B.PELAMAR_LOWONGAN_ID = C.PELAMAR_LOWONGAN_ID
						 WHERE 1=1 AND A.WAJIB = '1' AND NOT (LINK_FILE IS NULL OR LINK_FILE = '')
					 ) A
					 WHERE 1=1
					 GROUP BY PELAMAR_ID, LOWONGAN_ID
				) X ON A.PELAMAR_ID = X.PELAMAR_ID AND B.LOWONGAN_ID = X.LOWONGAN_ID
				LEFT JOIN (SELECT COUNT(1) JUMLAH_WAJIB, LOWONGAN_ID FROM pds_rekrutmen.LOWONGAN_DOKUMEN WHERE WAJIB = '1' GROUP BY LOWONGAN_ID) Y ON B.LOWONGAN_ID = Y.LOWONGAN_ID
		 		WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }

    function getCountByParamsDaftarPelamarShortlist($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST F ON A.PELAMAR_ID = F.PELAMAR_ID AND F.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
		 		WHERE 1 = 1			 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }

    function getCountByParamsDaftarPelamarDiterima($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA F ON A.PELAMAR_ID = F.PELAMAR_ID AND F.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
		 		WHERE 1 = 1			 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
    function getCountByParamsDaftarPelamarTahapan($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR A 
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN F ON A.PELAMAR_ID = F.PELAMAR_ID AND F.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN (SELECT TGL_MULAI, A.UJIAN_ID, LOWONGAN_ID, PEGAWAI_ID
				  FROM cat.UJIAN A
				  LEFT JOIN cat.UJIAN_PEGAWAI_DAFTAR B ON A.UJIAN_ID = B.UJIAN_ID) UJ ON A.PELAMAR_ID = UJ.PEGAWAI_ID AND UJ.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T1 ON A.PELAMAR_ID = T1.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T1.LOWONGAN_TAHAPAN_ID AND T1.TIPE_UJIAN_ID = 1
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T2 ON A.PELAMAR_ID = T2.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T2.LOWONGAN_TAHAPAN_ID AND T2.TIPE_UJIAN_ID = 2
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T3 ON A.PELAMAR_ID = T3.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T3.LOWONGAN_TAHAPAN_ID AND T3.TIPE_UJIAN_ID = 3
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T4 ON A.PELAMAR_ID = T4.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T4.LOWONGAN_TAHAPAN_ID AND T4.TIPE_UJIAN_ID = 4
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T5 ON A.PELAMAR_ID = T5.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T5.LOWONGAN_TAHAPAN_ID AND T5.TIPE_UJIAN_ID = 5
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T6 ON A.PELAMAR_ID = T6.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T6.LOWONGAN_TAHAPAN_ID AND T6.TIPE_UJIAN_ID = 6
				LEFT JOIN pds_rekrutmen.PELAMAR_TIPE_UJIAN T7 ON A.PELAMAR_ID = T7.PEGAWAI_ID AND F.LOWONGAN_TAHAPAN_ID = T7.LOWONGAN_TAHAPAN_ID AND T7.TIPE_UJIAN_ID = 7
		 		WHERE 1 = 1		 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }	
	
	function getCountByParamsDaftarAddPelamarCutoff($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR A 
				LEFT JOIN pds_rekrutmen.PELAMAR_PENDIDIKAN_TERAKHIR C ON A.PELAMAR_ID = C.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PENGALAMAN_DURASI D ON A.PELAMAR_ID = D.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.AGAMA E ON A.AGAMA_ID = E.AGAMA_ID
				LEFT JOIN cat.KABUPATEN F ON A.KOTA_ID = F.KABUPATEN_ID
				LEFT JOIN pds_rekrutmen.PELAMAR_PEKERJAAN G ON A.PELAMAR_ID = G.PELAMAR_ID
		 		WHERE 1 = 1	".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
		
  } 
?>
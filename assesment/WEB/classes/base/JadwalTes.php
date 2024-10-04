<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class JadwalTes extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalTes()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity();  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_TES_ID", $this->getNextId("JADWAL_TES_ID","jadwal_tes")); 

		$str = "INSERT INTO jadwal_tes (
				   JADWAL_TES_ID, FORMULA_ESELON_ID, TANGGAL_TES, BATCH, ACARA, TEMPAT, ALAMAT, NIP_ASESOR, TTD_ASESOR, NIP_PIMPINAN, TTD_PIMPINAN, TTD_TANGGAL, KETERANGAN, STATUS_PENILAIAN, JUMLAH_RUANGAN, LAST_CREATE_USER, LAST_CREATE_DATE, LINK_SOAL) 
				VALUES (
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("FORMULA_ESELON_ID").",
				  ".$this->getField("TANGGAL_TES").",
				  '".$this->getField("BATCH")."',
				  '".$this->getField("ACARA")."',
				  '".$this->getField("TEMPAT")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("NIP_ASESOR")."',
				  '".$this->getField("TTD_ASESOR")."',
				  '".$this->getField("NIP_PIMPINAN")."',
				  '".$this->getField("TTD_PIMPINAN")."',
				  ".$this->getField("TTD_TANGGAL").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("STATUS_PENILAIAN")."',
				  ".$this->getField("JUMLAH_RUANGAN").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LINK_SOAL")."'

				)"; 
		$this->id= $this->getField("JADWAL_TES_ID");
		$this->query= $str;
				// echo $str;exit();
		
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE jadwal_tes SET
				  FORMULA_ESELON_ID= ".$this->getField("FORMULA_ESELON_ID").",
				  TANGGAL_TES= ".$this->getField("TANGGAL_TES").",
				  BATCH= '".$this->getField("BATCH")."',
				  ACARA= '".$this->getField("ACARA")."',
				  TEMPAT= '".$this->getField("TEMPAT")."',
				  ALAMAT= '".$this->getField("ALAMAT")."',
				  TTD_ASESOR= '".$this->getField("TTD_ASESOR")."',
				  NIP_ASESOR= '".$this->getField("NIP_ASESOR")."',
				  TTD_PIMPINAN= '".$this->getField("TTD_PIMPINAN")."',
				  NIP_PIMPINAN= '".$this->getField("NIP_PIMPINAN")."',
				  TTD_TANGGAL= ".$this->getField("TTD_TANGGAL").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
				  STATUS_PENILAIAN= '".$this->getField("STATUS_PENILAIAN")."',
				  STATUS_VALID= ".$this->getField("STATUS_VALID").",
				  JUMLAH_RUANGAN= ".$this->getField("JUMLAH_RUANGAN").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
				  LINK_SOAL='".$this->getField("LINK_SOAL")."'

				WHERE JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
				"; 
				$this->query = $str;
				// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_tes
                WHERE 
                  JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_TES_ID ASC")
	{
		$str = "SELECT JADWAL_TES_ID, TANGGAL_TES, BATCH, ACARA, TEMPAT, ALAMAT, KETERANGAN, STATUS_PENILAIAN
				, STATUS_VALID, TTD_ASESOR, TTD_PIMPINAN, NIP_ASESOR, NIP_PIMPINAN, TTD_TANGGAL
				FROM jadwal_tes WHERE JADWAL_TES_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsJadwalAsesmen($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.JADWAL_TES_ID ASC")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, A.KETERANGAN, A.STATUS_PENILAIAN, B.JUMLAH, A.TTD_ASESOR, A.TTD_PIMPINAN, A.NIP_ASESOR, A.NIP_PIMPINAN, A.TTD_TANGGAL
		FROM jadwal_tes A
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, COUNT(A.PEGAWAI_ID) JUMLAH
			FROM
			(
			  SELECT
				JT.JADWAL_TES_ID, A.PEGAWAI_ID
			  FROM jadwal_tes JT
				INNER JOIN formula_eselon FE ON JT.FORMULA_ESELON_ID = FE.FORMULA_ESELON_ID
				INNER JOIN penilaian A ON JT.JADWAL_TES_ID = A.JADWAL_TES_ID
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID
			) A WHERE 1=1
			GROUP BY A.JADWAL_TES_ID
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJadwalAsesmenInfo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY ROUND(COALESCE(A.HASIL_POTENSI,0) + COALESCE(A.HASIL_KOMPETENSI,0),2) DESC")
	{
		$str = "
		SELECT
		A.*, ROUND(COALESCE(A.HASIL_POTENSI,0) + COALESCE(A.HASIL_KOMPETENSI,0),2) GENERAL_JPM,
		CASE WHEN ROUND(COALESCE(A.HASIL_POTENSI,0) + COALESCE(A.HASIL_KOMPETENSI,0),2) > 100
		THEN 0 ELSE 100 - ROUND(COALESCE(A.HASIL_POTENSI,0) + COALESCE(A.HASIL_KOMPETENSI,0),2) END GENERAL_IKK
		FROM
		(
			SELECT
			A.JADWAL_TES_ID, A.PEGAWAI_ID, A.TANGGAL_TES, A.ACARA, A.TEMPAT, A.ALAMAT
			, A.PROSEN_POTENSI, A.PROSEN_KOMPETENSI
			, A.PEGAWAI_NAMA, A.PEGAWAI_NIP, A.PEGAWAI_GOL, A.PEGAWAI_ESELON, A.PEGAWAI_JAB_STRUKTURAL,
			COALESCE(A1.POTENSI_JPM,0) POTENSI_JPM, COALESCE(A2.KOMPETENSI_JPM,0) KOMPETENSI_JPM,
			ROUND(COALESCE(A1.POTENSI_JPM,0) * (COALESCE(PROSEN_POTENSI,0) / 100),2) HASIL_POTENSI,
			ROUND(COALESCE(A2.KOMPETENSI_JPM,0) * (COALESCE(PROSEN_KOMPETENSI,0) / 100),2) HASIL_KOMPETENSI,
			ROUND(COALESCE((A1.POTENSI_JPM / (A1.POTENSI_JPM + A2.KOMPETENSI_JPM)),0) * COALESCE(PROSEN_POTENSI,0),2) HASIL_POTENSIBAK,
			ROUND(COALESCE((A2.KOMPETENSI_JPM / (A1.POTENSI_JPM + A2.KOMPETENSI_JPM)),0) * COALESCE(PROSEN_KOMPETENSI,0),2) HASIL_KOMPETENSIBAK
			FROM
			(
				SELECT
				JT.JADWAL_TES_ID, A.PEGAWAI_ID, JT.TANGGAL_TES, JT.ACARA, JT.TEMPAT, JT.ALAMAT
				, S0.NAMA PEGAWAI_NAMA, S0.NIP_BARU PEGAWAI_NIP
				, S1.KODE PEGAWAI_GOL, S2.NAMA PEGAWAI_ESELON, A.JABATAN_TES_ID PEGAWAI_JAB_STRUKTURAL
				, COALESCE(FE.PROSEN_KOMPETENSI,0) PROSEN_KOMPETENSI, COALESCE(FE.PROSEN_POTENSI,0) PROSEN_POTENSI
				FROM jadwal_tes JT
				INNER JOIN formula_eselon FE ON JT.FORMULA_ESELON_ID = FE.FORMULA_ESELON_ID
				INNER JOIN penilaian A ON JT.JADWAL_TES_ID = A.JADWAL_TES_ID
				INNER JOIN ".$this->db.".pegawai S0 ON A.PEGAWAI_ID = S0.PEGAWAI_ID
				LEFT JOIN ".$this->db.".pangkat S1 ON S0.LAST_PANGKAT_ID = S1.PANGKAT_ID
				LEFT JOIN ".$this->db.".eselon S2 ON S0.LAST_ESELON_ID = S2.ESELON_ID
				WHERE 1=1
				GROUP BY JT.JADWAL_TES_ID, A.PEGAWAI_ID, JT.TANGGAL_TES, JT.ACARA, JT.TEMPAT, JT.ALAMAT
				, FE.PROSEN_POTENSI, FE.PROSEN_KOMPETENSI
				, S0.NAMA, S0.NIP_BARU, S1.KODE, S2.NAMA, A.JABATAN_TES_ID
			) A
			LEFT JOIN
			(
				SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, (COALESCE(A.JPM,0) * 100) POTENSI_JPM, (COALESCE(A.IKK,0) * 100) POTENSI_IKK
				FROM jadwal_tes JT
				INNER JOIN penilaian A ON JT.JADWAL_TES_ID = A.JADWAL_TES_ID
				WHERE 1=1
				AND A.ASPEK_ID = 1
			) A1 ON A.JADWAL_TES_ID = A1.JADWAL_TES_ID AND A.PEGAWAI_ID = A1.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, (COALESCE(A.JPM,0) * 100) KOMPETENSI_JPM, (COALESCE(A.IKK,0) * 100) KOMPETENSI_IKK
				FROM jadwal_tes JT
				INNER JOIN penilaian A ON JT.JADWAL_TES_ID = A.JADWAL_TES_ID
				WHERE 1=1
				AND A.ASPEK_ID = 2
			) A2 ON A.JADWAL_TES_ID = A2.JADWAL_TES_ID AND A.PEGAWAI_ID = A2.PEGAWAI_ID
			WHERE 1=1
		) A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsFormulaEselon($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_TES_ID ASC")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, A.KETERANGAN, A.STATUS_PENILAIAN
		, A.FORMULA_ESELON_ID, D.FORMULA || ' untuk (' || COALESCE((C.NOTE || ' ' || C.NAMA), C.NAMA) || ')' NAMA_FORMULA_ESELON
		, COALESCE(JUMLAH_ASESOR,0) JUMLAH_ASESOR, COALESCE(JUMLAH_PEGAWAI,0) JUMLAH_PEGAWAI
		, A.JUMLAH_RUANGAN, A.STATUS_VALID, A.TTD_ASESOR, A.TTD_PIMPINAN, A.NIP_ASESOR, A.NIP_PIMPINAN
		, A.TTD_TANGGAL,A.LINK_SOAL
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN eselon C ON C.ESELON_ID = B.ESELON_ID
		INNER JOIN formula_assesment D ON D.FORMULA_ID = B.FORMULA_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, COUNT(A.ASESOR_ID) JUMLAH_ASESOR
			FROM
			(
			SELECT A.JADWAL_TES_ID, A.ASESOR_ID
			FROM jadwal_tes_simulasi_asesor A
			GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
			) A
			GROUP BY A.JADWAL_TES_ID
		) JML_ASESOR ON JML_ASESOR.JADWAL_TES_ID = A.JADWAL_TES_ID
		LEFT JOIN
		(
		SELECT A.JADWAL_TES_ID, COUNT(A.PEGAWAI_ID) JUMLAH_PEGAWAI
		FROM jadwal_tes_simulasi_pegawai A
		GROUP BY A.JADWAL_TES_ID
		) JML_PEGAWAI ON JML_PEGAWAI.JADWAL_TES_ID = A.JADWAL_TES_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsJadwalAsesmen($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM jadwal_tes A
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, COUNT(1) JUMLAH
			FROM
			(
				SELECT A.JADWAL_TES_ID, C.PEGAWAI_ID
				FROM jadwal_acara A
				INNER JOIN jadwal_asesor B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
				INNER JOIN jadwal_pegawai C ON C.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, C.PEGAWAI_ID
			) A WHERE 1=1
			GROUP BY A.JADWAL_TES_ID
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
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
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_tes WHERE 1=1 ".$statement;
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

    function selectByParamsAbsenJadwal($paramsArray=array(),$limit=-1,$from=-1, $pegawaid=0, $statement='', $sOrder="ORDER BY A.PUKUL1")
	{
		$str = "
		SELECT
			A.JADWAL_TES_ID, A.JADWAL_ACARA_ID, A.PUKUL1, A.PUKUL2, A.PENGGALIAN_ID, PN.KODE, PN.NAMA PENGGALIAN_NAMA
			, COALESCE(A1.ASESOR_NAMA, A2.ASESOR_NAMA) ASESOR_NAMA, A1.JADWAL_PEGAWAI_ID, A1.JADWAL_ASESOR_ID
			, A2.JADWAL_ASESOR_POTENSI_PEGAWAI_ID, A2.JADWAL_ASESOR_POTENSI_ID, A2.ASESOR_POTENSI_ID
		FROM jadwal_acara A
		INNER JOIN penggalian PN ON A.PENGGALIAN_ID = PN.PENGGALIAN_ID
		INNER JOIN
		(
			SELECT *
			FROM
			(
				SELECT A.JADWAL_TES_ID, A.JADWAL_ACARA_ID
				FROM jadwal_asesor A
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, A.JADWAL_ACARA_ID
				UNION ALL
				SELECT A.JADWAL_TES_ID, A.JADWAL_ACARA_ID
				FROM jadwal_asesor_potensi A
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, A.JADWAL_ACARA_ID
			) A 
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ACARA_ID
		) JW ON A.JADWAL_TES_ID = JW.JADWAL_TES_ID AND A.JADWAL_ACARA_ID = JW.JADWAL_ACARA_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ACARA_ID, B.NAMA ASESOR_NAMA, C.JADWAL_PEGAWAI_ID, A.JADWAL_ASESOR_ID
			FROM jadwal_asesor A
			INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
			INNER JOIN jadwal_pegawai C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
			WHERE 1=1
			AND C.PEGAWAI_ID = ".$pegawaid."
		) A1 ON A.JADWAL_TES_ID = A1.JADWAL_TES_ID AND A.JADWAL_ACARA_ID = A1.JADWAL_ACARA_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ACARA_ID, A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID, A.JADWAL_ASESOR_POTENSI_ID
			, B.NAMA ASESOR_NAMA, A.ASESOR_ID ASESOR_POTENSI_ID
			FROM jadwal_asesor_potensi_pegawai A
			INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
			WHERE 1=1
			AND A.PEGAWAI_ID = ".$pegawaid."
		) A2 ON A.JADWAL_TES_ID = A2.JADWAL_TES_ID AND A.JADWAL_ACARA_ID = A2.JADWAL_ACARA_ID
		WHERE 1=1
		"; 
		// AND A.JADWAL_TES_ID = 24
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPegawaiAbsen($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_AWAL_TES_SIMULASI_ID, JA.LAST_UPDATE_DATE, A.NAMA")
	{
		$str = "
		SELECT
			JADWAL_AWAL_TES_SIMULASI_ID
			, A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP, A.PEGAWAI_ID
			, B.KODE PEGAWAI_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA PEGAWAI_ESELON
			, A.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
			, D.NAMA SATKER
			, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
			, JA.TANGGAL_TES, JA.LAST_UPDATE_DATE
		FROM simpeg.pegawai A
		LEFT JOIN simpeg.pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN simpeg.eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
		LEFT JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(PARTITION BY JADWAL_AWAL_TES_SIMULASI_ID ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, B.TANGGAL_TES, A.*
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		"; 
		// AND A.JADWAL_TES_ID = 24
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsCekDrh($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder="")
	{
		$str = "
			SELECT 
				COALESCE( NULLIF(B.ROWATASAN,NULL), 0) ROWATASAN
				, COALESCE( NULLIF(C.ROWSAUDARA,NULL), 0) ROWSAUDARA
				, COALESCE( NULLIF(D.ROWRIPEND,NULL),0) ROWRIPEND
				, COALESCE( NULLIF(E.ROWRIPENDNON,NULL), 0) ROWRIPENDNON
				, COALESCE( NULLIF(F.ROWRIJAB,NULL), 0) ROWRIJAB
				, COALESCE( NULLIF(G.ROWBIDANGPEK,NULL), 0) ROWBIDANGPEK 
				, COALESCE( NULLIF(H.ROWDATAPEK,NULL), 0) ROWDATAPEK
				, COALESCE( NULLIF(I.ROWKONKERJA,NULL), 0) ROWKONKERJA
				, COALESCE( NULLIF(J.ROWMINHARAP,NULL), 0) ROWMINHARAP
				, COALESCE( NULLIF(K.ROWKEKKEL,NULL), 0) ROWKEKKEL
			FROM simpeg.PEGAWAI A
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWATASAN,PEGAWAI_ID
				FROM simpeg.PEGAWAI A
				WHERE 1=1
				AND LENGTH(A.LAST_ATASAN_LANGSUNG_NAMA) > 0 AND LENGTH(A.LAST_ATASAN_LANGSUNG_JABATAN) > 0 
				GROUP BY PEGAWAI_ID
			) B ON B.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWSAUDARA,PEGAWAI_ID 
				FROM simpeg.saudara A
				WHERE 1=1
				GROUP BY PEGAWAI_ID
			) C ON C.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWRIPEND,PEGAWAI_ID 
				FROM simpeg.riwayat_pendidikan A 
				WHERE 1=1 AND A.PENDIDIKAN_ID IS NOT NULL 
				GROUP BY PEGAWAI_ID
			) D ON D.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWRIPENDNON ,PEGAWAI_ID 
				FROM simpeg.riwayat_pendidikan A
				WHERE 1=1 AND A.PENDIDIKAN_ID IS NULL  
				GROUP BY PEGAWAI_ID
			) E ON E.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWRIJAB,PEGAWAI_ID  
				FROM simpeg.riwayat_jabatan A
				WHERE 1=1 
				GROUP BY PEGAWAI_ID
			) F ON F.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWBIDANGPEK,PEGAWAI_ID 
				FROM simpeg.riwayat_jabatan_info A
				WHERE 1=1
				GROUP BY PEGAWAI_ID
			) G ON G.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWDATAPEK,PEGAWAI_ID 
				FROM simpeg.DATA_PEKERJAAN A
				WHERE 1=1
				AND LENGTH(A.GAMBARAN) > 0 AND LENGTH(A.TANGGUNG_JAWAB) > 0 AND LENGTH(A.URAIKAN) > 0
				GROUP BY PEGAWAI_ID
			) H ON H.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWKONKERJA,PEGAWAI_ID 
				FROM simpeg.KONDISI_KERJA A
				WHERE 1=1 AND A.BAIK_ID IS NOT NULL OR A.CUKUP_ID IS NOT NULL OR A.PERLU_ID IS NOT NULL AND LENGTH(A.KONDISI) > 0 AND LENGTH(A.ASPEK) > 0
				GROUP BY A.PEGAWAI_ID 
			) I ON I.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWMINHARAP,PEGAWAI_ID 
				FROM simpeg.MINAT_HARAPAN A
				WHERE 1=1 AND LENGTH(A.SUKAI) > 0 AND LENGTH(A.TIDAK_SUKAI) > 0
				GROUP BY A.PEGAWAI_ID 
			) J ON J.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWKEKKEL,PEGAWAI_ID
				FROM simpeg.KEKUATAN_KELEMAHAN A
				WHERE 1=1 AND LENGTH(A.KEKUATAN) > 0 AND LENGTH(A.KELEMAHAN) > 0
				GROUP BY A.PEGAWAI_ID 
			) K ON K.PEGAWAI_ID =A.PEGAWAI_ID
			WHERE 1=1

		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str,$limit,$from); 
    }

     function getCountByParamsCriticalSoal($paramsArray=array(), $statement="")
    {
    	$str = "SELECT COUNT(1) AS ROWCOUNT
    	FROM PORTAL.FORMULIR_SOAL_CRITICAL_TAMBAHAN A
    	LEFT JOIN PORTAL.FORMULIR_CRITICAL_JAWABAN B ON A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID = B.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID
    	WHERE JAWABAN IS NOT NULL ".$statement; 

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

    function getCountByParamsCriticalJawaban($paramsArray=array(), $statement="")
    {
    	$str = "SELECT COUNT(1) AS ROWCOUNT
		FROM PORTAL.FORMULIR_JAWABAN_CRITICAL_HEADER A
		LEFT JOIN PORTAL.FORMULIR_SOAL_CRITICAL_HEADER B ON A.FORMULIR_SOAL_CRITICAL_HEADER_ID = B.FORMULIR_SOAL_CRITICAL_HEADER_ID
		WHERE TOPIK IS NOT NULL AND TANGGAL IS NOT NULL AND BULAN IS NOT NULL AND TAHUN IS NOT NULL AND SAMPAI IS NOT NULL ".$statement; 

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

    function getCountByParamsQInta($paramsArray=array(), $statement="")
    {
    	$str = "
    	SELECT COUNT(1) AS ROWCOUNT
		FROM portal.FORMULIR_SOAL A
		INNER JOIN portal.TIPE_FORMULIR B ON A.TIPE_FORMULIR_ID = B.TIPE_FORMULIR_ID
		LEFT JOIN portal.FORMULIR_JAWABAN C ON A.FORMULIR_SOAL_ID = C.FORMULIR_SOAL_ID
		WHERE C.JAWABAN IS NOT NULL ".$statement; 

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

  } 
?>
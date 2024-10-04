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

  class JadwalAsesor extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalAsesor()
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
		$this->setField("JADWAL_ASESOR_ID", $this->getNextId("JADWAL_ASESOR_ID","jadwal_asesor")); 

		$str = "INSERT INTO jadwal_asesor (
				   JADWAL_ASESOR_ID, JADWAL_TES_ID, JADWAL_ACARA_ID, ASESOR_ID, KELOMPOK, RUANG, JADWAL_KELOMPOK_RUANGAN_ID, KETERANGAN, BATAS_PEGAWAI, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_ASESOR_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("ASESOR_ID").",
				  '".$this->getField("KELOMPOK")."',
				  '".$this->getField("RUANG")."',
				  ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("BATAS_PEGAWAI").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
				// echo $str; exit;
		$this->id= $this->getField("JADWAL_ASESOR_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }

    function insertCopas()
	{
		$str = "
		INSERT INTO jadwal_asesor (
		JADWAL_ASESOR_ID, JADWAL_TES_ID, JADWAL_ACARA_ID, ASESOR_ID, KELOMPOK, RUANG, JADWAL_KELOMPOK_RUANGAN_ID, KETERANGAN, BATAS_PEGAWAI, LAST_CREATE_USER, LAST_CREATE_DATE, JADWAL_ASESOR_COPAS_ID)
		SELECT
			COALESCE((SELECT MAX(JADWAL_ASESOR_ID) FROM jadwal_asesor),0) + NOMOR JADWAL_ASESOR_ID
			, JADWAL_TES_ID, ".$this->getField("JADWAL_ACARA_ID")." JADWAL_ACARA_ID, ASESOR_ID, KELOMPOK, RUANG
			, JADWAL_KELOMPOK_RUANGAN_ID, KETERANGAN, BATAS_PEGAWAI, '".$this->getField("LAST_CREATE_USER")."' LAST_CREATE_USER, ".$this->getField("LAST_CREATE_DATE")." LAST_CREATE_DATE
			, A.JADWAL_ASESOR_ID JADWAL_ASESOR_COPAS_ID
		FROM
		(
			SELECT 
			ROW_NUMBER () OVER (PARTITION BY A.JADWAL_TES_ID ORDER BY JADWAL_ASESOR_ID) NOMOR
			, A.*
			FROM jadwal_asesor A
			WHERE 1=1
			AND A.JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
			AND EXISTS (SELECT 1 FROM jadwal_acara X WHERE X.JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")." AND X.PENGGALIAN_ID = ".$this->getField("PENGGALIAN_ID")." AND A.JADWAL_ACARA_ID = X.JADWAL_ACARA_ID)
			AND NOT EXISTS (SELECT 1 FROM jadwal_asesor XX WHERE XX.JADWAL_ACARA_ID = ".$this->getField("JADWAL_ACARA_ID")." AND XX.JADWAL_TES_ID = A.JADWAL_TES_ID AND XX.ASESOR_ID = A.ASESOR_ID AND A.JADWAL_ACARA_ID = XX.JADWAL_ACARA_ID)
		) A
		"; 
		$this->query= $str;
		// echo $str;exit();
		$this->execQuery($str);

		$str1= "
		INSERT INTO jadwal_pegawai (
		JADWAL_PEGAWAI_ID, JADWAL_ASESOR_ID, PEGAWAI_ID, PENGGALIAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE)
		SELECT
			COALESCE((SELECT MAX(JADWAL_PEGAWAI_ID) FROM jadwal_pegawai),0) + NOMOR JADWAL_PEGAWAI_ID
			, JADWAL_ASESOR_ID, PEGAWAI_ID
			, (SELECT PENGGALIAN_ID FROM jadwal_acara WHERE JADWAL_ACARA_ID = ".$this->getField("JADWAL_ACARA_ID").") PENGGALIAN_ID
			, '".$this->getField("LAST_CREATE_USER")."' LAST_CREATE_USER
			, ".$this->getField("LAST_CREATE_DATE")." LAST_CREATE_DATE
		FROM
		(

			SELECT 
			ROW_NUMBER () OVER (PARTITION BY AA.PENGGALIAN_ID ORDER BY AA.JADWAL_ASESOR_ID) NOMOR
			, AAAA.JADWAL_ASESOR_ID, AA.PEGAWAI_ID, AA.PENGGALIAN_ID, AA.LAST_CREATE_USER, AA.LAST_CREATE_DATE
			FROM jadwal_pegawai AA
			INNER JOIN
			(
				SELECT AAA.JADWAL_ASESOR_ID, AAA.JADWAL_ASESOR_COPAS_ID
				FROM jadwal_asesor AAA
				WHERE 1=1
				AND AAA.JADWAL_ACARA_ID = ".$this->getField("JADWAL_ACARA_ID")."
			) AAAA ON AA.JADWAL_ASESOR_ID = AAAA.JADWAL_ASESOR_COPAS_ID
			WHERE 1=1
			ORDER BY AA.JADWAL_ASESOR_ID
		) A
		"; 
		$this->query= $str1;
		// echo $str1;exit();
		return $this->execQuery($str1);

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
		$str = "UPDATE jadwal_asesor SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  JADWAL_ACARA_ID= ".$this->getField("JADWAL_ACARA_ID").",
				  ASESOR_ID= ".$this->getField("ASESOR_ID").",
				  KELOMPOK= '".$this->getField("KELOMPOK")."',
				  RUANG= '".$this->getField("RUANG")."',
				  JADWAL_KELOMPOK_RUANGAN_ID= ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
				  BATAS_PEGAWAI= ".$this->getField("BATAS_PEGAWAI").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_ASESOR_ID = ".$this->getField("JADWAL_ASESOR_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_asesor
                WHERE 
                  JADWAL_ASESOR_ID = '".$this->getField("JADWAL_ASESOR_ID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_ID ASC")
	{
		$str = "SELECT JADWAL_ASESOR_ID, JADWAL_ACARA_ID, ASESOR_ID, KETERANGAN
				FROM jadwal_asesor WHERE JADWAL_ASESOR_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		A.JADWAL_TES_ID, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, B.JUMLAH_PESERTA, A.TANGGAL_TES, B.JADWAL_ASESOR_ID, B.PENGGALIAN_ID, B.KODE, B.KELOMPOK_RUANGAN_NAMA
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor A
			LEFT JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			INNER JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
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
	
	function selectByParamsPegawaiAsesor($paramsArray=array(),$limit=-1,$from=-1, $statement='', $asesorId= "", $sOrder="")
	{
		$str = "
		SELECT 
		A.JADWAL_TES_ID, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, B.JUMLAH_PESERTA, A.TANGGAL_TES, B.JADWAL_ASESOR_ID, B.PENGGALIAN_ID, B.KODE, B.KELOMPOK_RUANGAN_NAMA
		, B.ASPEK_ID
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT 1 ASPEK_ID, A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID JADWAL_ASESOR_ID, NULL PENGGALIAN_ID, 'Potensi' KODE,
			F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA, COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor_potensi A 
			INNER JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			WHERE A.ASESOR_ID = ".$asesorId."
			GROUP BY A.JADWAL_TES_ID, 'Potensi', F.NAMA || ' - ' || G.NAMA
			UNION ALL
			SELECT 2 ASPEK_ID, A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor A
			LEFT JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			INNER JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			INNER JOIN
			(
				SELECT A.JADWAL_TES_ID, B.JADWAL_ASESOR_ID
				FROM jadwal_acara A
				INNER JOIN jadwal_asesor B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
				INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN penggalian D ON A.PENGGALIAN_ID = D.PENGGALIAN_ID
				WHERE 1=1
				AND B.ASESOR_ID = ".$asesorId."
				GROUP BY A.JADWAL_TES_ID, B.JADWAL_ASESOR_ID
			) XX ON A.JADWAL_TES_ID = XX.JADWAL_TES_ID AND A.JADWAL_ASESOR_ID = XX.JADWAL_ASESOR_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahTanggal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		A.JADWAL_TES_ID, A.TANGGAL_TES, COUNT(1) JUMLAH
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor A
			LEFT JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			INNER JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.JADWAL_TES_ID, A.TANGGAL_TES ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahTanggalAsesor($paramsArray=array(),$limit=-1,$from=-1, $statement='', $asesorId="", $sOrder="")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, COALESCE(SUM(A.JUMLAH),0) JUMLAH
		FROM
		(
			SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, COALESCE(A.JUMLAH,0) JUMLAH
			FROM
			(
				SELECT 
				A.JADWAL_TES_ID, A.TANGGAL_TES, COUNT(1) JUMLAH
				FROM jadwal_tes A
				LEFT JOIN 
				(
					SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
					FROM jadwal_asesor A
					LEFT JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
					INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
					INNER JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
					LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
					LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
					LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
					INNER JOIN
					(
						SELECT A.JADWAL_TES_ID, B.JADWAL_ASESOR_ID
						FROM jadwal_acara A
						INNER JOIN jadwal_asesor B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
						INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
						INNER JOIN penggalian D ON A.PENGGALIAN_ID = D.PENGGALIAN_ID
						WHERE 1=1
						AND B.ASESOR_ID = ".$asesorId."
						GROUP BY A.JADWAL_TES_ID, B.JADWAL_ASESOR_ID
					) XX ON A.JADWAL_TES_ID = XX.JADWAL_TES_ID AND A.JADWAL_ASESOR_ID = XX.JADWAL_ASESOR_ID
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
				) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				WHERE 1=1
				".$statement."
				GROUP BY A.JADWAL_TES_ID, A.TANGGAL_TES
			) A
			UNION ALL
			SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, COALESCE(A.JUMLAH,0) JUMLAH
			FROM
			(
				SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, 1 JUMLAH
				FROM jadwal_tes A
				LEFT JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				WHERE B.ASESOR_ID = ".$asesorId."
				GROUP BY A.JADWAL_TES_ID, A.TANGGAL_TES
			) A
		) A
		WHERE 1=1
		GROUP BY A.JADWAL_TES_ID, A.TANGGAL_TES
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPenggalian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, B.JADWAL_ASESOR_ID, B.ASESOR_ID, A.PENGGALIAN_ID, D.NAMA, D.KODE, B.KELOMPOK, B.RUANG, asesor_nama_jadwal(A.JADWAL_ACARA_ID, A.JADWAL_TES_ID, '<br/>') ASESOR
		, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA
		FROM jadwal_acara A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN penggalian D ON A.PENGGALIAN_ID = D.PENGGALIAN_ID
		LEFT JOIN jadwal_kelompok_ruangan E ON B.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
		LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
		LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.JADWAL_TES_ID, A.PENGGALIAN_ID, D.NAMA, D.KODE, B.KELOMPOK, B.RUANG, F.NAMA || ' - ' || G.NAMA ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.PUKUL1, A.PUKUL2, D.NIP_BARU NIP, D.NAMA, C.JADWAL_PEGAWAI_ID, E.TANGGAL_TES
		, P.STATUS_CBI, C.PEGAWAI_ID
		FROM jadwal_acara A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN jadwal_pegawai C ON B.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
		INNER JOIN ".$this->db.".pegawai D ON C.PEGAWAI_ID = D.PEGAWAI_ID
		INNER JOIN jadwal_tes E ON A.JADWAL_TES_ID = E.JADWAL_TES_ID
		INNER JOIN penggalian P ON A.PENGGALIAN_ID = P.PENGGALIAN_ID
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
	
	function selectByParamsAcaraJam($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "SELECT
				TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', A.PUKUL2), CONCAT('1985-10-21 ', A.PUKUL1)), '%H:%i') JAM
				FROM jadwal_acara A
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAcaraJamAsesor($paramsArray=array(),$limit=-1,$from=-1, $jam= "", $statement="", $sOrder="")
	{
		$str = "SELECT ADDTIME(A.TOTAL_JAM_ASESOR, '".$jam."' ) JAM_ASESOR
				FROM
				(
					SELECT 
					SEC_TO_TIME(
						SUM(
							time_to_sec(
							TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', C.PUKUL2), CONCAT('1985-10-21 ', C.PUKUL1)), '%H:%i')
							)
						)
					) TOTAL_JAM_ASESOR
					FROM jadwal_asesor A
					INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
					INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
					WHERE 1=1 
					".$statement."
					GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
				) A
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_ID ASC")
	{
		// TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', B.PUKUL2), CONCAT('1985-10-21 ', B.PUKUL1)), '%H:%i') 
		$str = "SELECT A.JADWAL_ASESOR_ID, A.JADWAL_ACARA_ID, A.ASESOR_ID, A.KETERANGAN AS KETERANGAN_JADWAL, C.NAMA, B.KETERANGAN AS KETERANGAN_ACARA
				, C.NAMA ASESOR_NAMA, B.PUKUL1, B.PUKUL2, B.PENGGALIAN_ID, D.NAMA PENGGALIAN_NAMA
				, A.KELOMPOK, A.RUANG
				, CASE WHEN jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') = '' THEN 'Belum di tentukan' ELSE jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') END JADWAL_KELOMPOK_RUANG_DATA
				, FROM_SECONDS(CAST(TIME_TO_SEC(B.PUKUL2) - TIME_TO_SEC(B.PUKUL1) AS INTEGER)) SELISIH_JAM
				, JML.TOTAL_JAM_ASESOR
				, A.JADWAL_KELOMPOK_RUANGAN_ID, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA
				, A.JADWAL_TES_ID, A.BATAS_PEGAWAI
				FROM jadwal_asesor A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID=B.JADWAL_ACARA_ID
				LEFT JOIN asesor C ON A.ASESOR_ID=C.ASESOR_ID
				INNER JOIN penggalian D ON D.PENGGALIAN_ID = B.PENGGALIAN_ID
				LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
				LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
				LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
				LEFT JOIN
				(
					SELECT 
					A.JADWAL_TES_ID, A.ASESOR_ID, FROM_SECONDS(CAST(SUM(TIME_TO_SEC(C.PUKUL2) - TIME_TO_SEC(C.PUKUL1)) AS INTEGER)) TOTAL_JAM_ASESOR
					FROM jadwal_asesor A
					INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
					INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
					WHERE 1=1 ".$statement."
					GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
				) JML ON A.JADWAL_TES_ID = JML.JADWAL_TES_ID AND A.ASESOR_ID = JML.ASESOR_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		// SEC_TO_TIME(
		// 				SUM(
		// 					time_to_sec(
		// 					TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', C.PUKUL2), CONCAT('1985-10-21 ', C.PUKUL1)), '%H:%i')
		// 					)
		// 				)
		// 			)
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_ID ASC")
	{
		// TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', B.PUKUL2), CONCAT('1985-10-21 ', B.PUKUL1)), '%H:%i') 
		$str = "SELECT A.JADWAL_ASESOR_ID, A.JADWAL_ACARA_ID, A.ASESOR_ID, A.KETERANGAN AS KETERANGAN_JADWAL, C.NAMA, B.KETERANGAN AS KETERANGAN_ACARA
				, C.NAMA ASESOR_NAMA, B.PUKUL1, B.PUKUL2, B.PENGGALIAN_ID, D.NAMA PENGGALIAN_NAMA
				, A.KELOMPOK, A.RUANG
				, CASE WHEN jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') = '' THEN 'Belum di tentukan' ELSE jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') END JADWAL_KELOMPOK_RUANG_DATA
				, FROM_SECONDS(CAST(TIME_TO_SEC(B.PUKUL2) - TIME_TO_SEC(B.PUKUL1) AS INTEGER)) SELISIH_JAM
				, JML.TOTAL_JAM_ASESOR
				, A.JADWAL_KELOMPOK_RUANGAN_ID, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA
				, A.JADWAL_TES_ID, A.BATAS_PEGAWAI
				FROM jadwal_asesor A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID=B.JADWAL_ACARA_ID
				LEFT JOIN asesor C ON A.ASESOR_ID=C.ASESOR_ID
				INNER JOIN penggalian D ON D.PENGGALIAN_ID = B.PENGGALIAN_ID
				LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
				LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
				LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
				LEFT JOIN
				(
					SELECT 
					A.JADWAL_TES_ID, A.ASESOR_ID, FROM_SECONDS(CAST(SUM(TIME_TO_SEC(C.PUKUL2) - TIME_TO_SEC(C.PUKUL1)) AS INTEGER)) TOTAL_JAM_ASESOR
					FROM jadwal_asesor A
					INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
					INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
					WHERE 1=1 ".$statement."
					GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
				) JML ON A.JADWAL_TES_ID = JML.JADWAL_TES_ID AND A.ASESOR_ID = JML.ASESOR_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
	 
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_asesor WHERE 1=1 ".$statement;
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_asesor A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID=B.JADWAL_ACARA_ID
				LEFT JOIN asesor C ON A.ASESOR_ID=C.ASESOR_ID
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

 //    function selectByParamsJumlahAsesorPegawai($statement='', $asesorId="", $sOrder="")
	// {
	// 	$str = "
	// 	SELECT A.TANGGAL_TES, COALESCE(JUMLAH,0) JUMLAH
	// 	FROM
	// 	(
	// 		SELECT A.TANGGAL_TES, COUNT(1) JUMLAH
	// 		FROM
	// 		(
	// 			SELECT
	// 			JT.TANGGAL_TES, B.PEGAWAI_ID
	// 			FROM jadwal_asesor A
	// 			INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
	// 			INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
	// 			INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
	// 			INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
	// 			WHERE FA.TIPE_FORMULA IN ('1','2') AND A.ASESOR_ID = ".$asesorId."
	// 			GROUP BY JT.TANGGAL_TES, B.PEGAWAI_ID
	// 		) A
	// 		GROUP BY A.TANGGAL_TES
	// 	) A
	// 	WHERE 1=1
	// 	"; 
		
	// 	$str .= $sOrder;
	// 	$this->query = $str;
				
	// 	return $this->selectLimit($str, -1, -1); 
 //    }

function selectByParamsJumlahAsesorPegawai($statement='', $asesorId="", $sOrder="")
	{
		$str = "
		SELECT A.TANGGAL_TES, COALESCE(JUMLAH,0) JUMLAH
		FROM
		(
			SELECT A.TANGGAL_TES, COUNT(1) JUMLAH
			FROM
			(
				SELECT
				JT.TANGGAL_TES, B.PEGAWAI_ID
				FROM jadwal_asesor A
				INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
				INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
				INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
				WHERE  A.ASESOR_ID = ".$asesorId."
				GROUP BY JT.TANGGAL_TES, B.PEGAWAI_ID
			) A
			GROUP BY A.TANGGAL_TES
		) A
		WHERE 1=1
		"; 
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }

    function selectByParamsJumlahAsesorPegawaiSuper($statement='', $asesorId="", $sOrder="")
	{
		$str = "
		SELECT A.TANGGAL_TES, COALESCE(JUMLAH,0) JUMLAH
		FROM
		(
			SELECT A.TANGGAL_TES, COUNT(1) JUMLAH
			FROM
			(
				SELECT
				JT.TANGGAL_TES, B.PEGAWAI_ID
				FROM jadwal_asesor A
				INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
				INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
				INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
				WHERE jt.ttd_asesor = cast(".$asesorId." as varchar)
				GROUP BY JT.TANGGAL_TES, B.PEGAWAI_ID
			) A
			GROUP BY A.TANGGAL_TES
		) A
		WHERE 1=1
		"; 
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }


    function selectByParamsDataAsesorPegawai($statement='', $asesorId="", $sOrder="ORDER BY JA.NOMOR_URUT")
	{
		$str = "
		SELECT
		a.last_eselon_id,A.PEGAWAI_ID, A.NAMA NAMA_PEGAWAI, A.NIP_BARU, JA.JADWAL_TES_ID
		, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM simpeg.pegawai A
		INNER JOIN
		(
			SELECT A.*,x.TANGGAL_TES
			FROM
			(
				SELECT
				A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID
				FROM jadwal_asesor A
				INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
				INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
				INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
				WHERE A.ASESOR_ID = ".$asesorId."
				GROUP BY A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID
			) X
			INNER JOIN
			(
				SELECT a.no_urut NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				, JADWAL_TES_ID
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			) A ON A.JADWAL_TES_ID = X.JADWAL_TES_ID AND A.PEGAWAI_ID = X.PEGAWAI_ID
			WHERE 1=1
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement; 
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }

  function selectByParamsDataAsesorPegawaiSuper($statement='', $asesorId="", $sOrder="ORDER BY JA.NOMOR_URUT")
	{
		$str = "
		SELECT
		a.last_eselon_id,A.PEGAWAI_ID, A.NAMA NAMA_PEGAWAI, A.NIP_BARU, JA.JADWAL_TES_ID, JA.asesor_id
		, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM simpeg.pegawai A
		INNER JOIN
		(
			SELECT A.*,x.TANGGAL_TES,x.asesor_id
			FROM
			(
				SELECT
				A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID,a.asesor_id
				FROM jadwal_asesor A
				INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
				INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
				INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
				GROUP BY A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID, a.asesor_id
			) X
			INNER JOIN
			(
				SELECT a.no_urut NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				, JADWAL_TES_ID
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			) A ON A.JADWAL_TES_ID = X.JADWAL_TES_ID AND A.PEGAWAI_ID = X.PEGAWAI_ID
			WHERE 1=1
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement; 
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }

 //    function selectByParamsDataAsesorPegawai($statement='', $asesorId="", $sOrder="ORDER BY JA.NOMOR_URUT")
	// {
	// 	$str = "
	// 	SELECT
	// 	A.PEGAWAI_ID, A.NAMA NAMA_PEGAWAI, A.NIP_BARU, JA.JADWAL_TES_ID
	// 	, 
	// 	CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
	// 	FROM simpeg.pegawai A
	// 	INNER JOIN
	// 	(
	// 		SELECT A.*
	// 		FROM
	// 		(
	// 			SELECT
	// 			A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID
	// 			FROM jadwal_asesor A
	// 			INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
	// 			INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
	// 			INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
	// 			INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
	// 			WHERE FA.TIPE_FORMULA IN ('1','2') AND A.ASESOR_ID = ".$asesorId."
	// 			GROUP BY A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID
	// 		) X
	// 		INNER JOIN
	// 		(
	// 			SELECT ROW_NUMBER() OVER(PARTITION BY B.JADWAL_TES_ID ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
	// 			, JADWAL_TES_ID
	// 			FROM jadwal_awal_tes_simulasi_pegawai A
	// 			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
	// 		) A ON A.JADWAL_TES_ID = X.JADWAL_TES_ID AND A.PEGAWAI_ID = X.PEGAWAI_ID
	// 		WHERE 1=1
	// 	) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
	// 	WHERE 1=1
	// 	".$statement; 
		
	// 	$str .= $sOrder;
	// 	$this->query = $str;
				
	// 	return $this->selectLimit($str, -1, -1); 
 //    }


    function selectByParamsPenggalianAsesorPegawai($statement='', $statementdetil="", $sOrder="ORDER BY C.KODE")
	{
		$str = "
		SELECT
			A.JADWAL_ASESOR_ID, A.ASESOR_ID,  z.nama nama_asesor, z.no_sk nip_asesor,  B.PENGGALIAN_ID
			, CASE B.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE C.NAMA END PENGGALIAN_NAMA
			, CASE B.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE C.KODE END PENGGALIAN_KODE
			, PENGGALIAN_KODE_ID
			, CASE WHEN PENGGALIAN_KODE_ID IS NOT NULL THEN 1 ELSE 0 END PENGGALIAN_KODE_STATUS
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		LEFT JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
		LEFT JOIN asesor z ON z.asesor_ID = a.Asesor_ID
		LEFT JOIN
		(
			SELECT
				A.JADWAL_TES_ID JT_ID, A.PENGGALIAN_ID PENGGALIAN_KODE_ID
			FROM jadwal_acara A INNER JOIN penggalian C ON C.PENGGALIAN_ID = A.PENGGALIAN_ID
			WHERE 1=1
			AND UPPER(C.KODE) = 'CBI'
			".$statementdetil."
		) JT ON B.JADWAL_TES_ID = JT.JT_ID
		WHERE 1=1
		".$statement;
		// AND A.ASESOR_ID = 25 AND A.JADWAL_TES_ID = 25
		
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str, -1, -1); 
    }

    function getCountByParamsPenggalianAsesorPegawai($statement='', $statementdetil="", $sOrder="ORDER BY C.KODE")
	{
		$str = "
		SELECT
			COUNT(1) AS ROWCOUNT
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		LEFT JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
		LEFT JOIN
		(
			SELECT
				A.JADWAL_TES_ID JT_ID, A.PENGGALIAN_ID PENGGALIAN_KODE_ID
			FROM jadwal_acara A INNER JOIN penggalian C ON C.PENGGALIAN_ID = A.PENGGALIAN_ID
			WHERE 1=1
			AND UPPER(C.KODE) = 'CBI'
			".$statementdetil."
		) JT ON B.JADWAL_TES_ID = JT.JT_ID
		WHERE 1=1
		".$statement;

		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsPenggalianPegawai($statement='', $sOrder="ORDER BY B.KODE")
	{
		$str = "
		SELECT
			B.PENGGALIAN_ID, B.NAMA PENGGALIAN_NAMA, B.KODE PENGGALIAN_KODE
		FROM jadwal_pegawai A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		WHERE 1=1
		".$statement;
		// AND A.ASESOR_ID = 25 AND A.JADWAL_TES_ID = 25
		
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str, -1, -1); 
    }

    function selectByParamsAsesorPotensi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.ASESOR_ID
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
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

    function selectByParamsAsesorKompetensi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
			F.ATRIBUT_ID, B.ASESOR_ID, C1.PENGGALIAN_ID, B1.NAMA NAMA_ASESOR, F.NAMA ATRIBUT_NAMA
			, A.JADWAL_PEGAWAI_ID, A.JADWAL_ASESOR_ID, F.ASPEK_ID, D.FORM_PERMEN_ID, D.NILAI_STANDAR
		FROM jadwal_pegawai A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
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

    function selectByParamsTugas($statement='')
	{
		$str = "
		SELECT A.JADWAL_TES_ID, JT.TANGGAL_TES, JA.keterangan, B.PEGAWAI_ID, p.kode 
		FROM jadwal_asesor A 
		INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID 
		INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID 
		INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID 
		INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID 
		INNER JOIN jadwal_acara JA ON A.JADWAL_ACARA_ID=JA.JADWAL_ACARA_ID
		INNER JOIN penggalian p ON b.penggalian_id=p.penggalian_id
		WHERE 1=1
		".$statement."GROUP BY A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID,JA.keterangan, p.kode "; 
		
		$str .= $sOrder;
		// echo $str; exit;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }
	
  } 
?>
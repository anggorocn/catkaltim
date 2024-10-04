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

  class JadwalAsesorPotensi extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalAsesorPotensi()
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
		$this->setField("JADWAL_ASESOR_POTENSI_ID", $this->getNextId("JADWAL_ASESOR_POTENSI_ID","jadwal_asesor_potensi")); 

		$str = "INSERT INTO jadwal_asesor_potensi (
				   JADWAL_ASESOR_POTENSI_ID, JADWAL_TES_ID, JADWAL_ACARA_ID, ASESOR_ID, KELOMPOK, RUANG, JADWAL_KELOMPOK_RUANGAN_ID, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_ASESOR_POTENSI_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("ASESOR_ID").",
				  '".$this->getField("KELOMPOK")."',
				  '".$this->getField("RUANG")."',
				  ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_ASESOR_POTENSI_ID");
		$this->query= $str;
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
		$str = "UPDATE jadwal_asesor_potensi SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  JADWAL_ACARA_ID= ".$this->getField("JADWAL_ACARA_ID").",
				  ASESOR_ID= ".$this->getField("ASESOR_ID").",
				  KELOMPOK= '".$this->getField("KELOMPOK")."',
				  RUANG= '".$this->getField("RUANG")."',
				  JADWAL_KELOMPOK_RUANGAN_ID= ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_ASESOR_POTENSI_ID = ".$this->getField("JADWAL_ASESOR_POTENSI_ID")."
				"; 
				$this->query = $str;
				// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_asesor_potensi
                WHERE 
                  JADWAL_ASESOR_POTENSI_ID = '".$this->getField("JADWAL_ASESOR_POTENSI_ID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_POTENSI_ID ASC")
	{
		$str = "SELECT JADWAL_ASESOR_POTENSI_ID, JADWAL_ACARA_ID, ASESOR_ID, KETERANGAN
				FROM jadwal_asesor_potensi WHERE JADWAL_ASESOR_POTENSI_ID IS NOT NULL"; 
		
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
		A.JADWAL_TES_ID, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, B.JUMLAH_PESERTA, A.TANGGAL_TES, B.JADWAL_ASESOR_POTENSI_ID, B.PENGGALIAN_ID, B.KODE, B.KELOMPOK_RUANGAN_NAMA
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor_potensi A
			LEFT JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
			INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			LEFT JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
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
		A.JADWAL_TES_ID, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, B.JUMLAH_PESERTA, A.TANGGAL_TES, B.JADWAL_ASESOR_POTENSI_ID, B.PENGGALIAN_ID, B.KODE, B.KELOMPOK_RUANGAN_NAMA
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor_potensi A
			LEFT JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
			LEFT JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			INNER JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			INNER JOIN
			(
				SELECT A.JADWAL_TES_ID, B.JADWAL_ASESOR_POTENSI_ID
				FROM jadwal_acara A
				INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
				INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN penggalian D ON A.PENGGALIAN_ID = D.PENGGALIAN_ID
				WHERE 1=1
				AND B.ASESOR_ID = ".$asesorId."
				GROUP BY A.JADWAL_TES_ID, B.JADWAL_ASESOR_POTENSI_ID
			) XX ON A.JADWAL_TES_ID = XX.JADWAL_TES_ID AND A.JADWAL_ASESOR_POTENSI_ID = XX.JADWAL_ASESOR_POTENSI_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
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
	
	function selectByParamsJumlahTanggal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		A.JADWAL_TES_ID, A.TANGGAL_TES, COUNT(1) JUMLAH
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor_potensi A
			LEFT JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
			INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			LEFT JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
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
		SELECT 
		A.JADWAL_TES_ID, A.TANGGAL_TES, COUNT(1) JUMLAH
		FROM jadwal_tes A
		LEFT JOIN 
		(
			SELECT A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA,  COUNT(B.PEGAWAI_ID) JUMLAH_PESERTA
			FROM jadwal_asesor_potensi A
			LEFT JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
			INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			LEFT JOIN penggalian D ON C.PENGGALIAN_ID = D.PENGGALIAN_ID
			LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
			LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
			LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
			INNER JOIN
			(
				SELECT A.JADWAL_TES_ID, B.JADWAL_ASESOR_POTENSI_ID
				FROM jadwal_acara A
				INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
				INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN penggalian D ON A.PENGGALIAN_ID = D.PENGGALIAN_ID
				WHERE 1=1
				AND B.ASESOR_ID = ".$asesorId."
				GROUP BY A.JADWAL_TES_ID, B.JADWAL_ASESOR_POTENSI_ID
			) XX ON A.JADWAL_TES_ID = XX.JADWAL_TES_ID AND A.JADWAL_ASESOR_POTENSI_ID = XX.JADWAL_ASESOR_POTENSI_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.JADWAL_ASESOR_POTENSI_ID, C.PENGGALIAN_ID, D.KODE, F.NAMA || ' - ' || G.NAMA
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
	
	function selectByParamsPenggalian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, B.JADWAL_ASESOR_POTENSI_ID, B.ASESOR_ID, A.PENGGALIAN_ID, D.NAMA, D.KODE, B.KELOMPOK, B.RUANG, asesor_nama_jadwal(A.JADWAL_ACARA_ID, A.JADWAL_TES_ID, '<br/>') ASESOR
		, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA
		FROM jadwal_acara A
		INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
		LEFT JOIN penggalian D ON A.PENGGALIAN_ID = D.PENGGALIAN_ID
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
		SELECT A.PUKUL1, A.PUKUL2, D.NIP_BARU NIP, D.NAMA, C.JADWAL_ASESOR_POTENSI_PEGAWAI_ID JADWAL_PEGAWAI_ID, E.TANGGAL_TES
		, P.STATUS_CBI, C.PEGAWAI_ID
		FROM jadwal_acara A
		INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN jadwal_asesor_potensi_pegawai C ON B.JADWAL_ASESOR_POTENSI_ID = C.JADWAL_ASESOR_POTENSI_ID
		INNER JOIN ".$this->db.".pegawai D ON C.PEGAWAI_ID = D.PEGAWAI_ID
		INNER JOIN jadwal_tes E ON A.JADWAL_TES_ID = E.JADWAL_TES_ID
		LEFT JOIN penggalian P ON A.PENGGALIAN_ID = P.PENGGALIAN_ID
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
					FROM jadwal_asesor_potensi A
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_POTENSI_ID ASC")
	{

		// , TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', B.PUKUL2), CONCAT('1985-10-21 ', B.PUKUL1)), '%H:%i') SELISIH_JAM
		$str = "SELECT A.JADWAL_ASESOR_POTENSI_ID, A.JADWAL_ACARA_ID, A.ASESOR_ID, A.KETERANGAN AS KETERANGAN_JADWAL, C.NAMA, B.KETERANGAN AS KETERANGAN_ACARA
				, C.NAMA ASESOR_NAMA, B.PUKUL1, B.PUKUL2, B.PENGGALIAN_ID, D.NAMA PENGGALIAN_NAMA
				, A.KELOMPOK, A.RUANG
				, CASE WHEN jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') = '' THEN 'Belum di tentukan' ELSE jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') END JADWAL_KELOMPOK_RUANG_DATA
				, FROM_SECONDS(CAST(TIME_TO_SEC(B.PUKUL2) - TIME_TO_SEC(B.PUKUL1) AS INTEGER)) SELISIH_JAM
				, JML.TOTAL_JAM_ASESOR
				, A.JADWAL_KELOMPOK_RUANGAN_ID, F.NAMA || ' - ' || G.NAMA KELOMPOK_RUANGAN_NAMA
				, A.JADWAL_TES_ID
				FROM jadwal_asesor_potensi A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID=B.JADWAL_ACARA_ID
				LEFT JOIN asesor C ON A.ASESOR_ID=C.ASESOR_ID
				LEFT JOIN penggalian D ON D.PENGGALIAN_ID = B.PENGGALIAN_ID
				LEFT JOIN jadwal_kelompok_ruangan E ON A.JADWAL_KELOMPOK_RUANGAN_ID = E.JADWAL_KELOMPOK_RUANGAN_ID
				LEFT JOIN kelompok F ON E.KELOMPOK_ID = F.KELOMPOK_ID
				LEFT JOIN ruangan G ON E.RUANGAN_ID = G.RUANGAN_ID
				LEFT JOIN
				(
					SELECT 
					A.JADWAL_TES_ID, A.ASESOR_ID, FROM_SECONDS(CAST(SUM(TIME_TO_SEC(C.PUKUL2) - TIME_TO_SEC(C.PUKUL1)) AS INTEGER)) TOTAL_JAM_ASESOR
					FROM jadwal_asesor_potensi A
					INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
					INNER JOIN jadwal_acara C ON A.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
				) JML ON A.JADWAL_TES_ID = JML.JADWAL_TES_ID AND A.ASESOR_ID = JML.ASESOR_ID
				WHERE 1=1 "; 
				// SEC_TO_TIME(
				// 		SUM(
				// 			time_to_sec(
				// 			TIME_FORMAT(TIMEDIFF(CONCAT('1985-10-21 ', C.PUKUL2), CONCAT('1985-10-21 ', C.PUKUL1)), '%H:%i')
				// 			)
				// 		)
				// 	) TOTAL_JAM_ASESOR
		
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
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_asesor_potensi WHERE 1=1 ".$statement;
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_asesor_potensi A
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
	
  } 
?>
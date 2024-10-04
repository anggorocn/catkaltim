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

  class JadwalTesSimulasiAsesor extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalTesSimulasiAsesor()
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
		$str = "INSERT INTO jadwal_tes_simulasi_asesor (
				   JADWAL_TES_ID, KELOMPOK_JUMLAH, ASESOR_ID, JADWAL_NAMA, PENGGALIAN_ID, WAKTU, PUKUL_AWAL, PUKUL_AKHIR, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("KELOMPOK_JUMLAH").",
				  ".$this->getField("ASESOR_ID").",
				  '".$this->getField("JADWAL_NAMA")."',
				  ".$this->getField("PENGGALIAN_ID").",
				  '".$this->getField("WAKTU")."',
				  '".$this->getField("PUKUL_AWAL")."',
				  '".$this->getField("PUKUL_AKHIR")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)";
		$this->query= $str;
		//echo $str;exit;
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
	
	function updateStatusSetuju()
	{
		$strpegawai= "UPDATE jadwal_tes_simulasi_pegawai SET STATUS = '1' WHERE JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."";
		$this->query = $strpegawai;
		$this->execQuery($strpegawai);
		
		$str = "UPDATE jadwal_tes_simulasi_asesor SET STATUS = '1' WHERE JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_tes_simulasi_asesor
                WHERE 
                  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID")." AND PUKUL_AWAL = '".$this->getField("PUKUL_AWAL")."'"; 
				  
		$this->query = $str;
		//echo $str;exit;
        return $this->execQuery($str);
    }
	
	function deletePenggalian()
	{
        $str = "DELETE FROM jadwal_tes_simulasi_asesor
                WHERE 
                  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID")." AND PENGGALIAN_ID = ".$this->getField("PENGGALIAN_ID")."
				  AND PENGGALIAN_ID > 0
				  "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deletePenggalianPotensi()
	{
        $str = "DELETE FROM jadwal_tes_simulasi_asesor
                WHERE 
                  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID")." AND PENGGALIAN_ID = ".$this->getField("PENGGALIAN_ID")."
				  "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function prosesSimulasi()
	{
        $str = "call simulasi_jadwal_acara(".$this->getField("JADWAL_TES_ID").")";
				  
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
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PUKUL_AWAL")
	{
		$str = "SELECT A.JADWAL_TES_ID, A.NAMA_SIMULASI, A.PENGGALIAN_ID, A.WAKTU, A.STATUS_GROUP, COALESCE(A.KELOMPOK_JUMLAH, 'Tidak Ada') KELOMPOK_JUMLAH, A.PUKUL_AWAL, A.PUKUL_AKHIR
				FROM
				(
					SELECT A.JADWAL_TES_ID, COALESCE(B.NAMA, A.JADWAL_NAMA) NAMA_SIMULASI, A.PENGGALIAN_ID, COALESCE(A.WAKTU, '') WAKTU, B.STATUS_GROUP, A.KELOMPOK_JUMLAH, A.PUKUL_AWAL, A.PUKUL_AKHIR
					FROM jadwal_tes_simulasi_asesor A
					LEFT JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
					WHERE 1=1 AND B.STATUS_GROUP IS NULL
					GROUP BY A.JADWAL_TES_ID, COALESCE(B.NAMA, A.JADWAL_NAMA), A.PENGGALIAN_ID, COALESCE(A.WAKTU, ''), B.STATUS_GROUP, A.KELOMPOK_JUMLAH, A.PUKUL_AWAL, A.PUKUL_AKHIR
					UNION ALL
					SELECT A.JADWAL_TES_ID, B.NAMA NAMA_SIMULASI, A.PENGGALIAN_ID, A.WAKTU, B.STATUS_GROUP, A.KELOMPOK_JUMLAH, MIN(A.PUKUL_AWAL) PUKUL_AWAL, MAX(A.PUKUL_AKHIR) PUKUL_AKHIR
					FROM jadwal_tes_simulasi_asesor A
					INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
					WHERE 1=1 AND B.STATUS_GROUP = '1'
					GROUP BY A.JADWAL_TES_ID, B.NAMA, A.KELOMPOK_JUMLAH, A.PENGGALIAN_ID, COALESCE(A.WAKTU, ''), B.STATUS_GROUP
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
	
	function selectByParamsJumlahRowAsesorPegawaiKelompok($statement='')
	{
		$str = "
		SELECT MAX(JUMLAH_DATA) JUMLAH_DATA, A.JADWAL_TES_ID
		FROM
		(
			SELECT COUNT(E.NAMA) JUMLAH_DATA, E.NAMA, A.ID_JADWAL JADWAL_TES_ID
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_kelompok_ruangan C ON B.JADWAL_KELOMPOK_RUANGAN_ID = C.JADWAL_KELOMPOK_RUANGAN_ID
			INNER JOIN jadwal_acara D ON D.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID
			INNER JOIN kelompok E ON C.KELOMPOK_ID = E.KELOMPOK_ID
			WHERE 1=1 ".$statement."
			GROUP BY E.NAMA, A.ID_JADWAL
		) A
		GROUP BY A.JADWAL_TES_ID ";
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsPegawaiDalamKelompok($statement='', $sOrder="ORDER BY E.NAMA")
	{
		$str = "
		SELECT A1.NAMA PEGAWAI_NAMA, E.NAMA KELOMPOK_INFO
		FROM jadwal_tes_simulasi_pegawai JA
		INNER JOIN jadwal_pegawai A ON JA.PEGAWAI_ID = A.PEGAWAI_ID AND A.ID_JADWAL = JA.JADWAL_TES_ID
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN jadwal_kelompok_ruangan C ON B.JADWAL_KELOMPOK_RUANGAN_ID = C.JADWAL_KELOMPOK_RUANGAN_ID
		INNER JOIN jadwal_acara D ON D.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID AND A.PENGGALIAN_ID = D.PENGGALIAN_ID
		INNER JOIN kelompok E ON C.KELOMPOK_ID = E.KELOMPOK_ID
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		WHERE 1=1 ";
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsPegawaiGroupDalamKelompok($statement='', $sOrder="ORDER BY E.NAMA")
	{
		$str = "
		SELECT A1.NAMA PEGAWAI_NAMA, E.NAMA KELOMPOK_INFO, D.PUKUL1
		FROM jadwal_tes_simulasi_pegawai JA
		INNER JOIN jadwal_pegawai A ON JA.PEGAWAI_ID = A.PEGAWAI_ID AND A.ID_JADWAL = JA.JADWAL_TES_ID
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN jadwal_kelompok_ruangan C ON B.JADWAL_KELOMPOK_RUANGAN_ID = C.JADWAL_KELOMPOK_RUANGAN_ID
		INNER JOIN jadwal_acara D ON D.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID AND A.PENGGALIAN_ID = D.PENGGALIAN_ID
		INNER JOIN kelompok E ON C.KELOMPOK_ID = E.KELOMPOK_ID
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		WHERE 1=1 ";
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsJumlahRowAsesorKelompok($statement='')
	{
		$str = "
		SELECT MAX(JUMLAH_DATA) JUMLAH_DATA, A.JADWAL_TES_ID
		FROM
		(
			SELECT COUNT(D.NAMA) JUMLAH_DATA, D.NAMA, A.JADWAL_TES_ID
			FROM jadwal_asesor A
			INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
			INNER JOIN jadwal_kelompok_ruangan C ON A.JADWAL_KELOMPOK_RUANGAN_ID = C.JADWAL_KELOMPOK_RUANGAN_ID
			INNER JOIN kelompok D ON D.KELOMPOK_ID = C.KELOMPOK_ID
			INNER JOIN asesor E ON A.ASESOR_ID = E.ASESOR_ID
			WHERE 1=1 ".$statement."
			GROUP BY D.NAMA, A.JADWAL_TES_ID
		) A
		GROUP BY A.JADWAL_TES_ID ";
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsAsesorDalamKelompok($statement='', $sOrder="ORDER BY D.NAMA")
	{
		$str = "
		SELECT D.NAMA KELOMPOK_INFO, E.NAMA NAMA_ASESOR
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN jadwal_kelompok_ruangan C ON A.JADWAL_KELOMPOK_RUANGAN_ID = C.JADWAL_KELOMPOK_RUANGAN_ID
		INNER JOIN kelompok D ON D.KELOMPOK_ID = C.KELOMPOK_ID
		INNER JOIN asesor E ON A.ASESOR_ID = E.ASESOR_ID
		WHERE 1=1 ";
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsAsesorMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PUKUL_AWAL")
	{
		$str = "SELECT A.JADWAL_TES_ID, A.NAMA_SIMULASI, A.PENGGALIAN_ID, A.WAKTU, A.STATUS_GROUP, COALESCE(A.KELOMPOK_JUMLAH, 'Tidak Ada') KELOMPOK_JUMLAH, A.PUKUL_AWAL, A.PUKUL_AKHIR, A.ASESOR_ID, B.NAMA ASESOR_NAMA
				FROM
				(
				SELECT A.JADWAL_TES_ID, B.NAMA NAMA_SIMULASI, A.PENGGALIAN_ID, A.WAKTU, B.STATUS_GROUP, A.KELOMPOK_JUMLAH, A.ASESOR_ID, MIN(A.PUKUL_AWAL) PUKUL_AWAL, MAX(A.PUKUL_AKHIR) PUKUL_AKHIR
				FROM jadwal_tes_simulasi_asesor A
				INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, B.NAMA, A.KELOMPOK_JUMLAH, A.PENGGALIAN_ID, COALESCE(A.WAKTU, ''), B.STATUS_GROUP, A.ASESOR_ID
				) A
				LEFT JOIN asesor B ON A.ASESOR_ID=B.ASESOR_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPukulMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "SELECT A.JADWAL_TES_ID, A.KELOMPOK_JUMLAH, A.PUKUL_AWAL, A.PUKUL_AKHIR
				FROM jadwal_tes_simulasi_asesor A
				INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.JADWAL_TES_ID, A.KELOMPOK_JUMLAH, A.PUKUL_AWAL, A.PUKUL_AKHIR ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "SELECT JADWAL_TES_ID, KELOMPOK_JUMLAH, ASESOR_ID, JADWAL_NAMA, PENGGALIAN_ID, WAKTU, PUKUL_AWAL, PUKUL_AKHIR, STATUS
				FROM jadwal_tes_simulasi_asesor 
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJadwalTahap($statement='', $sOrder="ORDER BY A.JADWAL_TES_ID, C.URUTAN_TES ASC")
	{
		$str = "
		SELECT
			A.JADWAL_TES_ID, A.FORMULA_ESELON_ID, B.FORMULA_ID
			, C.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, C.FORMULA_ASSESMENT_ID
			, C.TIPE_UJIAN_ID, C.BOBOT, D.TIPE, D.TIPE || ' (' || CAST(COALESCE(C.BOBOT, 0) AS TEXT) || ')' TIPE_INFO
			, D.ID, D.PARENT_ID
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN formula_assesment_ujian_tahap C ON C.FORMULA_ASSESMENT_ID = B.FORMULA_ID
		INNER JOIN cat.tipe_ujian D ON D.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1 ";
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsJadwalTahapbaru($statement='', $sOrder="ORDER BY c.tipe_ujian_id ASC")
	{
		$str = "
		SELECT
			A.JADWAL_TES_ID, A.FORMULA_ESELON_ID, B.FORMULA_ID
			, C.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, C.FORMULA_ASSESMENT_ID
			, C.TIPE_UJIAN_ID, C.BOBOT, D.TIPE, D.TIPE || ' (' || CAST(COALESCE(C.BOBOT, 0) AS TEXT) || ')' TIPE_INFO
			, D.ID, D.PARENT_ID, e.tipe tipeparent
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN formula_assesment_ujian_tahap C ON C.FORMULA_ASSESMENT_ID = B.FORMULA_ID
		INNER JOIN cat.tipe_ujian D ON D.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		INNER JOIN cat.tipe_ujian E ON E.id = d.PARENT_ID
		WHERE 1=1 ";
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,-1,-1); 
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_tes_simulasi_asesor WHERE 1=1 ".$statement;
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

    function selectByParamsCari($paramsArray=array(),$limit=-1,$from=-1, $ujianid, $statement='', $sOrder="order by jp.no_urut asc")
	{
		$str = "
		SELECT
		jp.no_urut no_urut,A.PEGAWAI_ID, A.NIP_BARU, A.NAMA, A.EMAIL
		, COALESCE(JUMLAH_DATA,0) JUMLAH_DATA
		FROM JADWAL_AWAL_TES_SIMULASI_PEGAWAI JP
		INNER JOIN ".$this->db.".PEGAWAI A ON A.PEGAWAI_ID = JP.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT PEGAWAI_ID, CASE WHEN COALESCE(JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
			FROM
			(
			SELECT PEGAWAI_ID, COUNT(1) JUMLAH_DATA
			FROM cat_pegawai.ujian_pegawai_".$ujianid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL GROUP BY PEGAWAI_ID
			) A
		) CHK ON CHK.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1);  
    }

    function selectByParamsMonitoringGeneral($paramsArray=array(),$limit=-1,$from=-1, $ujianid, $reqId, $statement='', $sOrder="")
	{
		$str = "
	select tipe_ujian_id, pegawai_id, count (tipe_ujian_id) JUMLAH_SOAL, 
count (case when bank_soal_pilihan_id is not null then 1 end ) JUMLAH_JAWABAN
					FROM cat_pegawai.ujian_pegawai_".$reqId." A
					where 1=1 ".$statement."
				group by tipe_ujian_id, pegawai_id
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		// $str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str."<br>";
		return $this->selectLimit($str,-1,-1);  
    }

   function selectByParamsMonitoringtipecek($paramsArray=array(),$limit=-1,$from=-1, $ujianid, $reqId, $statement='', $sOrder="")
	{
		$str = "
			select a.tipe_ujian_id, b.tipe 
					FROM cat_pegawai.ujian_pegawai_".$ujianid." A
					left join cat.tipe_ujian b on a.tipe_ujian_id =b.tipe_ujian_id
				group by a.tipe_ujian_id,b.tipe,b.urutan order by b.urutan
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str."<br>";
		return $this->selectLimit($str,-1,-1);  
    }

	
  } 
?>
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

  class JadwalAwalTesPegawai extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalAwalTesPegawai()
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
		$str = "INSERT INTO jadwal_awal_tes_pegawai (
				   JADWAL_AWAL_TES_ID, PEGAWAI_ID, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_AWAL_TES_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("STATUS").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)";
				// echo $str;exit;
				//echo $set->query;exit;
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
	
	function delete()
	{
        $str = "DELETE FROM jadwal_awal_tes_pegawai
                WHERE 
                  JADWAL_AWAL_TES_ID= ".$this->getField("JADWAL_AWAL_TES_ID")."
                  AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
                  "; 
				  
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
				FROM jadwal_awal_tes_pegawai WHERE JADWAL_ASESOR_ID IS NOT NULL"; 
		
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
		SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID
		FROM jadwal_awal_tes_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM jadwal_awal_tes_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
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

    function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, A1.NIK
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
		, D1.SATKER_ID SATKER_TES_ID, s.nama satker, COALESCE(JD.JUMLAH_DATA,0) JUMLAH_DATA
		FROM jadwal_awal_tes_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".satker s ON A1.satker_id = s.satker_id
		LEFT JOIN
		(
			SELECT JADWAL_AWAL_TES_ID ROW_CHECK_ID, A.PEGAWAI_ID ROW_CHECK_PEGAWAI_ID, COUNT(1) JUMLAH_DATA
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN cat.ujian_pegawai_daftar B ON A.JADWAL_AWAL_TES_SIMULASI_ID = B.JADWAL_TES_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID
			GROUP BY JADWAL_AWAL_TES_ID, A.PEGAWAI_ID
		) JD ON JADWAL_AWAL_TES_ID = ROW_CHECK_ID AND A.PEGAWAI_ID = ROW_CHECK_PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsMonitoringPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM jadwal_awal_tes_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_awal_tes_pegawai WHERE 1=1 ".$statement;
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
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

  class JadwalTesSimulasiPegawai extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalTesSimulasiPegawai()
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
		$str = "INSERT INTO jadwal_tes_simulasi_pegawai (
				   JADWAL_TES_ID, PEGAWAI_ID, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_TES_ID").",
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
        $str = "DELETE FROM jadwal_tes_simulasi_pegawai
                WHERE 
                  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").""; 
				  
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
				FROM jadwal_tes_simulasi_pegawai WHERE JADWAL_ASESOR_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER by e1.no_urut asc")
	{
		$str = "
		SELECT e1.no_urut no_urut,A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, a1.last_eselon_id eselon_id
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
		, D1.SATKER_ID SATKER_TES_ID, D1.NAMA SATKER, U.LINK_FILE1,U.LINK_FILE2,U.LINK_FILE3,U.LINK_FOTO
		FROM jadwal_tes_simulasi_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".UPLOAD_FILE U on A.PEGAWAI_ID = U.PEGAWAI_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		LEFT JOIN JADWAL_AWAL_TES_SIMULASI_pegawai e1 ON a.JADWAL_TES_ID = e1.JADWAL_AWAL_tes_simulasi_ID and a.pegawai_id=e1.pegawai_id
		
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_tes_simulasi_pegawai WHERE 1=1 ".$statement;
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

    function selectByParamsTanggalPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by f1.no_urut asc")
	{
		$str = "
		SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID, U.LINK_FILE1,U.LINK_FILE2,U.LINK_FILE3,U.LINK_FOTO, TO_CHAR(E1.LAST_UPDATE_DATE, 'yyyy-mm-dd hh24:mi:ss') as LAST_UPDATE_DATE, f1.no_urut no_urut
		FROM jadwal_tes_simulasi_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".UPLOAD_FILE U on A.PEGAWAI_ID = U.PEGAWAI_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		LEFT JOIN jadwal_awal_tes_simulasi_pegawai E1 ON E1.JADWAL_AWAL_TES_SIMULASI_ID = A.JADWAL_TES_ID AND E1.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN JADWAL_AWAL_TES_SIMULASI_pegawai f1 ON a.JADWAL_TES_ID = f1.JADWAL_AWAL_tes_simulasi_ID and a.pegawai_id=f1.pegawai_id
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

    function selectByParamsTanggalCetakPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder=" ORDER BY E1.NO_URUT ASC")
	{
		$str = "
		SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID, U.LINK_FILE1,U.LINK_FILE2,U.LINK_FILE3,U.LINK_FOTO, TO_CHAR(E1.LAST_UPDATE_DATE, 'yyyy-mm-dd hh24:mi:ss') as LAST_UPDATE_DATE
		, E1.NO_URUT
		FROM jadwal_tes_simulasi_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".UPLOAD_FILE U on A.PEGAWAI_ID = U.PEGAWAI_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		LEFT JOIN jadwal_awal_tes_simulasi_pegawai E1 ON E1.JADWAL_AWAL_TES_SIMULASI_ID = A.JADWAL_TES_ID AND E1.PEGAWAI_ID = A.PEGAWAI_ID
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
  } 
?>
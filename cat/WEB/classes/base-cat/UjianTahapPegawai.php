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

  class UjianTahapPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianTahapPegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		INSERT INTO cat.ujian_tahap_pegawai
		(
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, 
            FORMULA_ESELON_ID, UJIAN_ID, UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID, 
            WAKTU_UJIAN, WAKTU_UJIAN_LOG
		) 
		VALUES (
		  ".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").",
		  ".$this->getField("JADWAL_TES_ID").",
		  ".$this->getField("FORMULA_ASSESMENT_ID").",
		  ".$this->getField("FORMULA_ESELON_ID").",
		  ".$this->getField("UJIAN_ID").",
		  ".$this->getField("UJIAN_TAHAP_ID").",
		  ".$this->getField("TIPE_UJIAN_ID").",
		  ".$this->getField("PEGAWAI_ID").",
		  ".$this->getField("WAKTU_UJIAN").",
		  ".$this->getField("WAKTU_UJIAN")."
		)"; 
				
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.ujian_tahap_pegawai SET
				WAKTU_UJIAN_LOG= ".$this->getField("WAKTU_UJIAN_LOG")."
				WHERE PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")." AND UJIAN_ID= ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID= ".$this->getField("UJIAN_TAHAP_ID")."
				AND TIPE_UJIAN_ID= ".$this->getField("TIPE_UJIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateLog()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE cat.ujian_tahap_pegawai SET
		WAKTU_UJIAN_LOG = (SELECT WAKTU_UJIAN + interval '".$this->getField("LOG_WAKTU")." minute' FROM cat.ujian_tahap_pegawai WHERE 1=1 AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID")." AND TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID").")
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID")." AND TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateLogMenit()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE cat.ujian_tahap_pegawai SET
		WAKTU_UJIAN_LOG = (SELECT WAKTU_UJIAN + interval '".$this->getField("LOG_WAKTU")." minute' FROM cat.ujian_tahap_pegawai WHERE 1=1 AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID")." AND TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID").")
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID")." AND TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID")."
		"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	/** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="")
	{
		$str = "
		SELECT 
		PEGAWAI_ID, UJIAN_ID, UJIAN_TAHAP_ID, TIPE_UJIAN_ID, WAKTU_UJIAN
		, ROUND(ROUND((SELECT CAST(EXTRACT(EPOCH FROM (WAKTU_UJIAN_LOG - WAKTU_UJIAN)) AS NUMERIC)),0) / 60) LOG_WAKTU
		FROM cat.ujian_tahap_pegawai WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	/** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.ujian_tahap_pegawai WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
    
  } 
?>
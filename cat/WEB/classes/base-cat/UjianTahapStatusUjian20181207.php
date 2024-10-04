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

  class UjianTahapStatusUjian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianTahapStatusUjian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_TAHAP_STATUS_UJIAN_ID", $this->getNextId("UJIAN_TAHAP_STATUS_UJIAN_ID","cat.UJIAN_TAHAP_STATUS_UJIAN")); 
		$str = "INSERT INTO cat.UJIAN_TAHAP_STATUS_UJIAN (
				   UJIAN_TAHAP_STATUS_UJIAN_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, STATUS,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_TAHAP_STATUS_UJIAN_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("UJIAN_TAHAP_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("STATUS").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_TAHAP_STATUS_UJIAN SET
				  UJIAN_ID 				= '".$this->getField("UJIAN_ID")."',
				  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_TAHAP_STATUS_UJIAN_ID 	= ".$this->getField("UJIAN_TAHAP_STATUS_UJIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN
                WHERE 
                  UJIAN_TAHAP_STATUS_UJIAN_ID = ".$this->getField("UJIAN_TAHAP_STATUS_UJIAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","UJIAN_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT UJIAN_TAHAP_STATUS_UJIAN_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, STATUS
				FROM cat.UJIAN_TAHAP_STATUS_UJIAN WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY UJIAN_ID ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","UJIAN_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(UJIAN_TAHAP_STATUS_UJIAN_ID) AS ROWCOUNT FROM cat.UJIAN_TAHAP_STATUS_UJIAN WHERE 1=1 ".$statement;
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
	
	function getPegawaiUjianTahapSelesai($statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.UJIAN A
		INNER JOIN cat.UJIAN_PEGAWAI_DAFTAR B ON A.UJIAN_ID = B.UJIAN_ID
		INNER JOIN cat.UJIAN_TAHAP C ON A.UJIAN_ID = C.UJIAN_ID
		INNER JOIN cat.UJIAN_TAHAP_STATUS_UJIAN D ON C.UJIAN_TAHAP_ID = D.UJIAN_TAHAP_ID AND A.UJIAN_ID = D.UJIAN_ID AND B.PEGAWAI_ID = D.PEGAWAI_ID
		WHERE 1=1 ".$statement;
		$this->select($str); 
		$this->query = $str;		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>
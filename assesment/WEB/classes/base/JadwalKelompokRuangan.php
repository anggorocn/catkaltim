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

  class JadwalKelompokRuangan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JadwalKelompokRuangan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_KELOMPOK_RUANGAN_ID", $this->getNextId("JADWAL_KELOMPOK_RUANGAN_ID","jadwal_kelompok_ruangan")); 

		$str = "INSERT INTO jadwal_kelompok_ruangan (
				   JADWAL_KELOMPOK_RUANGAN_ID, JADWAL_TES_ID, JADWAL_ACARA_ID, KELOMPOK_ID, RUANGAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("KELOMPOK_ID").",
				  ".$this->getField("RUANGAN_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_KELOMPOK_RUANGAN_ID");
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
		$str = "UPDATE jadwal_kelompok_ruangan SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  JADWAL_ACARA_ID= ".$this->getField("JADWAL_ACARA_ID").",
				  KELOMPOK_ID= ".$this->getField("KELOMPOK_ID").",
				  RUANGAN_ID= ".$this->getField("RUANGAN_ID").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_KELOMPOK_RUANGAN_ID = ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_kelompok_ruangan
                WHERE 
                  JADWAL_KELOMPOK_RUANGAN_ID = '".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_KELOMPOK_RUANGAN_ID ASC")
	{
		$str = "SELECT JADWAL_KELOMPOK_RUANGAN_ID, JADWAL_ACARA_ID, KELOMPOK_ID, RUANGAN_ID
				FROM jadwal_kelompok_ruangan WHERE JADWAL_KELOMPOK_RUANGAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_KELOMPOK_RUANGAN_ID ASC")
	{
		$str = "SELECT A.JADWAL_KELOMPOK_RUANGAN_ID, A.JADWAL_ACARA_ID
        		, B.PUKUL1, B.PUKUL2, B.PENGGALIAN_ID, C.NAMA PENGGALIAN_NAMA
				, A.KELOMPOK_ID, D.NAMA KELOMPOK_NAMA, A.RUANGAN_ID, E.NAMA RUANGAN_NAMA
				, CONCAT(D.NAMA, ' - ', E.NAMA) KELOMPOK_RUANGAN_NAMA
				FROM jadwal_kelompok_ruangan A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID=B.JADWAL_ACARA_ID
				LEFT JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
				LEFT JOIN kelompok D ON A.KELOMPOK_ID = D.KELOMPOK_ID
				LEFT JOIN ruangan E ON A.RUANGAN_ID = E.RUANGAN_ID
				WHERE 1=1 "; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_kelompok_ruangan WHERE 1=1 ".$statement;
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
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM jadwal_kelompok_ruangan A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID=B.JADWAL_ACARA_ID
				LEFT JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
				LEFT JOIN kelompok D ON A.KELOMPOK_ID = D.KELOMPOK_ID
				LEFT JOIN ruangan E ON A.RUANGAN_ID = E.RUANGAN_ID
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
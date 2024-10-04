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

  class Kabupaten extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Kabupaten()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KABUPATEN_ID", $this->getNextId("KABUPATEN_ID","cat.KABUPATEN")); 

		$str = "INSERT INTO cat.KABUPATEN (
				   KABUPATEN_ID, PROPINSI_ID, NAMA, 
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("KABUPATEN_ID").",
				  ".$this->getField("PROPINSI_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE cat.KABUPATEN
				SET    
					   PROPINSI_ID      = ".$this->getField("PROPINSI_ID").",
					   NAMA    			= '".$this->getField("NAMA")."',
				  	   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
				       LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE  KABUPATEN_ID     = ".$this->getField("KABUPATEN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.KABUPATEN
                WHERE 
                  KABUPATEN_ID = ".$this->getField("KABUPATEN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT KABUPATEN_ID, A.PROPINSI_ID, A.NAMA, B.NAMA NAMA_PROPINSI
				FROM cat.KABUPATEN A LEFT JOIN cat.PROPINSI B ON A.PROPINSI_ID= B.PROPINSI_ID
				WHERE KABUPATEN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT A.KABUPATEN_ID, A.PROPINSI_ID, A.NAMA, B.NAMA NAMA_PROPINSI  
				  FROM cat.KABUPATEN A
				  LEFT JOIN cat.PROPINSI B ON A.PROPINSI_ID=B.PROPINSI_ID
				 WHERE KABUPATEN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
	
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT KABUPATEN_ID, PROPINSI_ID, NAMA
				FROM cat.KABUPATEN WHERE KABUPATEN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(KABUPATEN_ID) AS ROWCOUNT FROM cat.KABUPATEN WHERE KABUPATEN_ID IS NOT NULL "; 
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
	

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(KABUPATEN_ID) AS ROWCOUNT FROM cat.KABUPATEN WHERE KABUPATEN_ID IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>
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

  class Desa extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Desa()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DESA_ID", $this->getNextId("DESA_ID","cat.DESA")); 

		$str = "INSERT INTO cat.DESA (
				   DESA_ID, KECAMATAN_ID, KABUPATEN_ID, 
				   NAMA, PROPINSI_ID, 
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("DESA_ID").",
				  ".$this->getField("KECAMATAN_ID").",
				  ".$this->getField("KABUPATEN_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("PROPINSI_ID").",
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
				UPDATE cat.DESA
				SET    
					   KECAMATAN_ID       = ".$this->getField("KECAMATAN_ID").",
					   KABUPATEN_ID    	  = ".$this->getField("KABUPATEN_ID").",
					   NAMA               = '".$this->getField("NAMA")."',
					   PROPINSI_ID     	  = ".$this->getField("PROPINSI_ID").",
					  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE  DESA_ID       = ".$this->getField("DESA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.DESA
                WHERE 
                  DESA_ID = ".$this->getField("DESA_ID").""; 
				  
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
		$str = "SELECT DESA_ID, KECAMATAN_ID, KABUPATEN_ID, NAMA, PROPINSI_ID
				FROM cat.DESA WHERE DESA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	 function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT A.DESA_ID, A.KECAMATAN_ID, A.KABUPATEN_ID, A.PROPINSI_ID, A.NAMA, 
				   B.NAMA NAMA_KECAMATAN, C.NAMA NAMA_KABUPATEN, D.NAMA NAMA_PROPINSI
			    FROM cat.DESA A
			    LEFT JOIN cat.KECAMATAN B ON A.KECAMATAN_ID=B.KECAMATAN_ID AND A.KABUPATEN_ID=B.KABUPATEN_ID AND A.PROPINSI_ID=B.PROPINSI_ID
			    LEFT JOIN cat.KABUPATEN C ON A.KABUPATEN_ID=C.KABUPATEN_ID AND A.PROPINSI_ID=C.PROPINSI_ID
			    LEFT JOIN cat.PROPINSI D ON A.PROPINSI_ID=D.PROPINSI_ID 
				WHERE DESA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.NAMA ASC";
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT DESA_ID, KECAMATAN_ID, KABUPATEN_ID, NAMA, PROPINSI_ID
				FROM cat.DESA WHERE DESA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(DESA_ID) AS ROWCOUNT FROM cat.DESA WHERE DESA_ID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(DESA_ID) AS ROWCOUNT FROM cat.DESA WHERE DESA_ID IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
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
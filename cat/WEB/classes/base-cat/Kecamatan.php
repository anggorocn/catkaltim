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

  class Kecamatan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Kecamatan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KECAMATAN_ID", $this->getNextId("KECAMATAN_ID","cat.KECAMATAN")); 

		$str = "INSERT INTO cat.KECAMATAN (
				   KECAMATAN_ID, KABUPATEN_ID, PROPINSI_ID, 
				   NAMA, LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("KECAMATAN_ID").",
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
				UPDATE cat.KECAMATAN
				SET    
					   KABUPATEN_ID       	= ".$this->getField("KABUPATEN_ID").",
					   PROPINSI_ID    		= ".$this->getField("PROPINSI_ID").",
					   NAMA             	= '".$this->getField("NAMA")."',
				  	   LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE").",
				  	   LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE  KECAMATAN_ID         = ".$this->getField("KECAMATAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.KECAMATAN
                WHERE 
                  KECAMATAN_ID = ".$this->getField("KECAMATAN_ID").""; 
				  
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
		$str = "SELECT A.KECAMATAN_ID, A.KABUPATEN_ID, A.PROPINSI_ID, A.NAMA, B.NAMA NAMA_KABUPATEN, C.NAMA NAMA_PROPINSI
				  FROM cat.KECAMATAN A
				  LEFT JOIN cat.KABUPATEN B ON A.KABUPATEN_ID = B.KABUPATEN_ID AND A.PROPINSI_ID = B.PROPINSI_ID
				  LEFT JOIN cat.PROPINSI C ON A.PROPINSI_ID=C.PROPINSI_ID
				WHERE KECAMATAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT A.KECAMATAN_ID, A.KABUPATEN_ID, A.PROPINSI_ID, A.NAMA, B.NAMA NAMA_KABUPATEN, C.NAMA NAMA_PROPINSI
				  FROM cat.KECAMATAN A
				  LEFT JOIN cat.KABUPATEN B ON A.KABUPATEN_ID = B.KABUPATEN_ID AND A.PROPINSI_ID = B.PROPINSI_ID
				  LEFT JOIN cat.PROPINSI C ON A.PROPINSI_ID=C.PROPINSI_ID
				WHERE KECAMATAN_ID IS NOT NULL"; 
		
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
		$str = "SELECT KECAMATAN_ID, KABUPATEN_ID, PROPINSI_ID, NAMA
				FROM cat.KECAMATAN WHERE KECAMATAN_ID IS NOT NULL"; 
		
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
		$str = "SELECT COUNT(KECAMATAN_ID) AS ROWCOUNT FROM cat.KECAMATAN WHERE KECAMATAN_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(KECAMATAN_ID) AS ROWCOUNT FROM cat.KECAMATAN WHERE KECAMATAN_ID IS NOT NULL "; 
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
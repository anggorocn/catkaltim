<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel WILAYAH.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Wilayah extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Wilayah()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("WILAYAH_ID", $this->getAdminNextId("WILAYAH_ID","pds_rekrutmen.WILAYAH")); 		

		$str = "
				INSERT INTO pds_rekrutmen.WILAYAH (
				   WILAYAH_ID, NAMA, KODE, LAST_CREATE_USER, LAST_CREATE_DATE) 
 			  	VALUES (
				  ".$this->getField("WILAYAH_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KODE")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.WILAYAH
				SET    
					   NAMA           	= '".$this->getField("NAMA")."',
					   KODE      		= '".$this->getField("KODE")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  WILAYAH_ID     	= '".$this->getField("WILAYAH_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.WILAYAH
                WHERE 
                  WILAYAH_ID = ".$this->getField("WILAYAH_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
		//echo $str;
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY WILAYAH_ID ASC ")
	{
		$str = "
				SELECT WILAYAH_ID, NAMA, KODE
				FROM pds_rekrutmen.WILAYAH A
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY WILAYAH_ID ASC ")
	{
		$str = "
				SELECT A.WILAYAH_ID, A.NAMA, A.KODE
				FROM pds_rekrutmen.WILAYAH A
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT WILAYAH_ID, NAMA, KODE
				FROM pds_rekrutmen.WILAYAH
				WHERE 1 = 1
			    "; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(WILAYAH_ID) AS ROWCOUNT FROM pds_rekrutmen.WILAYAH A
		        WHERE WILAYAH_ID IS NOT NULL ".$statement; 
		
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pds_rekrutmen.WILAYAH A
		WHERE 1 = 1 ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(WILAYAH_ID) AS ROWCOUNT FROM pds_rekrutmen.WILAYAH
		        WHERE WILAYAH_ID IS NOT NULL ".$statement; 
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
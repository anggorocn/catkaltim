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
  * Entity-base class untuk mengimplementasikan tabel WAKTU_SATUAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class WaktuSatuan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function WaktuSatuan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("WAKTU_SATUAN_ID", $this->getNextId("WAKTU_SATUAN_ID","WAKTU_SATUAN")); 		

		$str = "
				INSERT INTO WAKTU_SATUAN (
				    WAKTU_SATUAN_ID, KODE, NAMA)  
 			  	VALUES (
				  ".$this->getField("WAKTU_SATUAN_ID").",
   				  '".$this->getField("KODE")."',
  				  '".$this->getField("NAMA")."'
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE WAKTU_SATUAN
				SET    
   				  		KODE	= '".$this->getField("KODE")."',	
						NAMA		= '".$this->getField("NAMA")."'				   
				WHERE  WAKTU_SATUAN_ID  = '".$this->getField("WAKTU_SATUAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE WAKTU_SATUAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE WAKTU_SATUAN_ID = ".$this->getField("WAKTU_SATUAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM WAKTU_SATUAN
                WHERE 
                  WAKTU_SATUAN_ID = ".$this->getField("WAKTU_SATUAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
					SELECT 
						   WAKTU_SATUAN_ID, KODE, NAMA
					FROM WAKTU_SATUAN A WHERE WAKTU_SATUAN_ID IS NOT NULL
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
		$str = "	SELECT
						   WAKTU_SATUAN_ID, KODE, NAMA
					FROM WAKTU_SATUAN A WHERE WAKTU_SATUAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(WAKTU_SATUAN_ID) AS ROWCOUNT FROM WAKTU_SATUAN A
		        WHERE WAKTU_SATUAN_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(WAKTU_SATUAN_ID) AS ROWCOUNT FROM WAKTU_SATUAN A
		        WHERE WAKTU_SATUAN_ID IS NOT NULL ".$statement; 
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
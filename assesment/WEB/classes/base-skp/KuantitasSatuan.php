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
  * Entity-base class untuk mengimplementasikan tabel KUANTITAS_SATUAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class KuantitasSatuan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KuantitasSatuan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KUANTITAS_SATUAN_ID", $this->getNextId("KUANTITAS_SATUAN_ID","KUANTITAS_SATUAN")); 		

		$str = "
				INSERT INTO KUANTITAS_SATUAN (
				    KUANTITAS_SATUAN_ID, KODE, NAMA)  
 			  	VALUES (
				  ".$this->getField("KUANTITAS_SATUAN_ID").",
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
				UPDATE KUANTITAS_SATUAN
				SET    
   				  		KODE	= '".$this->getField("KODE")."',	
						NAMA		= '".$this->getField("NAMA")."'				   
				WHERE  KUANTITAS_SATUAN_ID  = '".$this->getField("KUANTITAS_SATUAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KUANTITAS_SATUAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE KUANTITAS_SATUAN_ID = ".$this->getField("KUANTITAS_SATUAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM KUANTITAS_SATUAN
                WHERE 
                  KUANTITAS_SATUAN_ID = ".$this->getField("KUANTITAS_SATUAN_ID").""; 
				  
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
						   KUANTITAS_SATUAN_ID, KODE, NAMA
					FROM KUANTITAS_SATUAN A WHERE KUANTITAS_SATUAN_ID IS NOT NULL
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
						   KUANTITAS_SATUAN_ID, KODE, NAMA
					FROM KUANTITAS_SATUAN A WHERE KUANTITAS_SATUAN_ID IS NOT NULL
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
		$str = "SELECT COUNT(KUANTITAS_SATUAN_ID) AS ROWCOUNT FROM KUANTITAS_SATUAN A
		        WHERE KUANTITAS_SATUAN_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(KUANTITAS_SATUAN_ID) AS ROWCOUNT FROM KUANTITAS_SATUAN A
		        WHERE KUANTITAS_SATUAN_ID IS NOT NULL ".$statement; 
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
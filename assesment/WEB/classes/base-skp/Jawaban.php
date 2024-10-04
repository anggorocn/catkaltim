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
  * Entity-base class untuk mengimplementasikan tabel JAWABAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Jawaban extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Jawaban()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JAWABAN_ID", $this->getNextId("JAWABAN_ID","JAWABAN")); 		

		$str = "
				INSERT INTO JAWABAN (
				   JAWABAN_ID, PERTANYAAN_ID, URUT, 
				   JAWABAN, KETERANGAN, RANGE_1, 
				   RANGE_2, RANGE_3)  
 			  	VALUES (
				  ".$this->getField("JAWABAN_ID").",
				  ".$this->getField("PERTANYAAN_ID").",
  				  ".$this->getField("URUT").",
				  '".$this->getField("JAWABAN")."',
   				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("RANGE_1")."',
				  '".$this->getField("RANGE_2")."',
				  '".$this->getField("RANGE_3")."'
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE JAWABAN
				SET    
						PERTANYAAN_ID	= ".$this->getField("PERTANYAAN_ID").",
  				  		URUT			= ".$this->getField("URUT").",
				  		JAWABAN			= '".$this->getField("JAWABAN")."',
   				  		KETERANGAN		= '".$this->getField("KETERANGAN")."',
				  		RANGE_1			= '".$this->getField("RANGE_1")."',
				  		RANGE_2			= '".$this->getField("RANGE_2")."',
				  		RANGE_3			= '".$this->getField("RANGE_3")."'					   
				WHERE  JAWABAN_ID     	= '".$this->getField("JAWABAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE JAWABAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE JAWABAN_ID = ".$this->getField("JAWABAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM jawaban
                WHERE 
                  JAWABAN_ID = ".$this->getField("JAWABAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteParent()
	{
        $str = "DELETE FROM jawaban
                WHERE 
                  PERTANYAAN_ID= '".$this->getField("PERTANYAAN_ID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.URUT ASC ")
	{
		$str = "
					SELECT 
						   JAWABAN_ID, PERTANYAAN_ID, URUT, 
						   JAWABAN, KETERANGAN, RANGE_1, 
						   RANGE_2, RANGE_3
					FROM jawaban A WHERE JAWABAN_ID IS NOT NULL
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
						   JAWABAN_ID, PERTANYAAN_ID, URUT, 
						   JAWABAN, KETERANGAN, RANGE_1, 
						   RANGE_2, RANGE_3
					FROM jawaban A WHERE JAWABAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.JAWABAN ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(JAWABAN_ID) AS ROWCOUNT FROM jawaban A
		        WHERE JAWABAN_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(JAWABAN_ID) AS ROWCOUNT FROM jawaban A
		        WHERE JAWABAN_ID IS NOT NULL ".$statement; 
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
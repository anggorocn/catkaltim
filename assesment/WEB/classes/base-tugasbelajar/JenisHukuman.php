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

  class JenisHukuman extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JenisHukuman()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JENIS_HUKUMAN_ID", $this->getNextId("JENIS_HUKUMAN_ID","jenis_hukuman")); 

		$str = "INSERT INTO jenis_hukuman (
				   JENIS_HUKUMAN_ID, TINGKAT_HUKUMAN_ID, NAMA) 
				VALUES (
				  ".$this->getField("JENIS_HUKUMAN_ID").",
				  ".$this->getField("TINGKAT_HUKUMAN_ID").",
				  '".$this->getField("NAMA")."'
				)"; 
		
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE jenis_hukuman
				SET    
					   TINGKAT_HUKUMAN_ID       = ".$this->getField("TINGKAT_HUKUMAN_ID").",
					   NAMA    					= '".$this->getField("NAMA")."'
				WHERE  JENIS_HUKUMAN_ID         = ".$this->getField("JENIS_HUKUMAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jenis_hukuman
                WHERE 
                  JENIS_HUKUMAN_ID = ".$this->getField("JENIS_HUKUMAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder='')
	{
		/*$str = "
        SELECT JENIS_HUKUMAN_ID, A.TINGKAT_HUKUMAN_ID, A.NAMA JENIS_HUKUMAN, B.NAMA TINGKAT_HUKUMAN
                FROM JENIS_HUKUMAN A LEFT JOIN TINGKAT_HUKUMAN B ON A.TINGKAT_HUKUMAN_ID = B.TINGKAT_HUKUMAN_ID
				"; */

		$str = "
		SELECT JENIS_HUKUMAN_ID, A.TINGKAT_HUKUMAN_ID, A.NAMA JENIS_HUKUMAN,
        (SELECT X.NAMA FROM tingkat_hukuman X WHERE A.TINGKAT_HUKUMAN_ID = X.TINGKAT_HUKUMAN_ID) TINGKAT_HUKUMAN
				FROM jenis_hukuman A WHERE 1 = 1
		";						
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
 	function selectByParamsEdit($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT JENIS_HUKUMAN_ID, TINGKAT_HUKUMAN_ID, NAMA
                FROM jenis_hukuman WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY JENIS_HUKUMAN_ID ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }		

    function selectByParamsStatus($paramsArray=array(),$limit=-1,$from=-1)
	{		
		$str = "
				SELECT TINGKAT_HUKUMAN_ID, NAMA 
				FROM tingkat_hukuman WHERE 1 = 1
				".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }		
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT JENIS_HUKUMAN_ID, TINGKAT_HUKUMAN_ID, NAMA
				FROM jenis_hukuman WHERE JENIS_HUKUMAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TINGKAT_HUKUMAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(JENIS_HUKUMAN_ID) AS ROWCOUNT FROM jenis_hukuman A WHERE JENIS_HUKUMAN_ID IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(JENIS_HUKUMAN_ID) AS ROWCOUNT FROM jenis_hukuman WHERE JENIS_HUKUMAN_ID IS NOT NULL "; 
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
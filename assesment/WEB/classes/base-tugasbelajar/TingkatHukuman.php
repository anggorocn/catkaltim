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

  class TingkatHukuman extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TingkatHukuman()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TINGKAT_HUKUMAN_ID", $this->getNextId("TINGKAT_HUKUMAN_ID","tingkat_hukuman")); 

		$str = "INSERT INTO tingkat_hukuman (
				   TINGKAT_HUKUMAN_ID, NAMA, PERATURAN_ID) 
				VALUES (
				  ".$this->getField("TINGKAT_HUKUMAN_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("PERATURAN_ID")."
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE tingkat_hukuman SET
				  NAMA = '".$this->getField("NAMA")."',
				  PERATURAN_ID = ".$this->getField("PERATURAN_ID")."
				WHERE TINGKAT_HUKUMAN_ID = ".$this->getField("TINGKAT_HUKUMAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM tingkat_hukuman
                WHERE 
                  TINGKAT_HUKUMAN_ID = ".$this->getField("TINGKAT_HUKUMAN_ID").""; 
				  
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
		$str = "
				SELECT TINGKAT_HUKUMAN_ID, A.NAMA TINGKAT_HUKUMAN, A.PERATURAN_ID, 
        			B.NAMA PERATURAN
				FROM tingkat_hukuman A
				LEFT JOIN peraturan B ON A.PERATURAN_ID = B.PERATURAN_ID 
				WHERE 1=1
		";		
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY TINGKAT_HUKUMAN_ID ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
 	function selectByParamsEdit($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT TINGKAT_HUKUMAN_ID, NAMA, PERATURAN_ID
                FROM tingkat_hukuman WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TINGKAT_HUKUMAN_ID ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }		
	
    function selectByParamsStatus($paramsArray=array(),$limit=-1,$from=-1)
	{		
		$str = "
				SELECT PERATURAN_ID, NAMA, KETERANGAN 
				FROM peraturan WHERE 1 = 1
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
		$str = "SELECT TINGKAT_HUKUMAN_ID, NAMA, PERATURAN_ID
				FROM tingkat_hukuman WHERE TINGKAT_HUKUMAN_ID IS NOT NULL"; 
		
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
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(TINGKAT_HUKUMAN_ID) AS ROWCOUNT 
				FROM tingkat_hukuman A
				LEFT JOIN peraturan B ON A.PERATURAN_ID = B.PERATURAN_ID 
				WHERE 1=1 ".$statement; 
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
		$str = "SELECT COUNT(TINGKAT_HUKUMAN_ID) AS ROWCOUNT FROM tingkat_hukuman WHERE TINGKAT_HUKUMAN_ID IS NOT NULL "; 
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
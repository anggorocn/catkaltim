<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");
  //include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/Entity.php");

  class Konten extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Konten()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KONTEN_ID", $this->getNextId("KONTEN_ID","pds_rekrutmen.KONTEN")); 
		
		$str = "INSERT INTO pds_rekrutmen.KONTEN(KONTEN_ID, NAMA, KETERANGAN) 
				VALUES(
				  ".$this->getField("KONTEN_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.KONTEN SET
				  KETERANGAN = '".$this->getField("KETERANGAN")."'
				WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateKonten()
	{
		$str = "UPDATE pds_rekrutmen.KONTEN SET
					  NAMA = '".$this->getField("NAMA")."',
					  KETERANGAN = '".$this->getField("KETERANGAN")."'
				WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
	}
	
	
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.KONTEN
                WHERE 
                  KONTEN_ID = '".$this->getField("KONTEN_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT KONTEN_ID, NAMA, KETERANGAN, NAME, DESCRIPTION
				FROM pds_rekrutmen.KONTEN WHERE KONTEN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY KONTEN_ID ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT KONTEN_ID, NAMA, KETERANGAN
				FROM pds_rekrutmen.KONTEN WHERE KONTEN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY urut ASC, NAMA ASC";
				
		return $this->selectLimit($str,$limit,$from);
    }	
   
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(KONTEN_ID) AS ROWCOUNT FROM pds_rekrutmen.KONTEN WHERE KONTEN_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(KONTEN_ID) AS ROWCOUNT FROM pds_rekrutmen.KONTEN WHERE KONTEN_ID IS NOT NULL "; 
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
	
	function getKontenTitle($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		
		return $this->getField('NAMA');
	}
	
	function getKontenText($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		
		return $this->getField('KETERANGAN');
	}
	
  } 
?>
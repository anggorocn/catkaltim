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

  class MEselon extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MEselon()
	{
      $this->Entity(); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT A.M_ESELON_ID, A.NAMA
		FROM M_ESELON A
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
    
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("M_ESELON_ID", $this->getNextId("M_ESELON_ID","M_ESELON")); 
		$str = "
		INSERT INTO M_ESELON
		(
			M_ESELON_ID, NAMA
		)
		VALUES (
		".$this->getField("M_ESELON_ID").",
		'".$this->getField("NAMA")."'
		)
		";

		$this->id = $this->getField("M_ESELON_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE M_ESELON
		SET
		NAMA= '".$this->getField("NAMA")."'
		WHERE M_ESELON_ID = ".$this->getField("M_ESELON_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM M_ESELON
        WHERE 
        M_ESELON_ID = '".$this->getField("M_ESELON_ID")."'
        ";	  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM M_ESELON A
		WHERE 1 = 1 ".$statement; 
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

  } 
?>
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

  class MPendidikan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MPendidikan()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT A.M_PENDIDIKAN_ID, A.NAMA
		FROM M_PENDIDIKAN A
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
		$this->setField("M_PENDIDIKAN_ID", $this->getNextId("M_PENDIDIKAN_ID","M_PENDIDIKAN")); 
		$str = "
		INSERT INTO M_PENDIDIKAN
		(
			M_PENDIDIKAN_ID, NAMA
		)
		VALUES (
		".$this->getField("M_PENDIDIKAN_ID").",
		'".$this->getField("NAMA")."'
		)
		";

		$this->id = $this->getField("M_PENDIDIKAN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE M_PENDIDIKAN
		SET
		NAMA= '".$this->getField("NAMA")."'
		WHERE M_PENDIDIKAN_ID = ".$this->getField("M_PENDIDIKAN_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM M_PENDIDIKAN
        WHERE 
        M_PENDIDIKAN_ID = '".$this->getField("M_PENDIDIKAN_ID")."'
        ";	  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM M_PENDIDIKAN A
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
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

  class MPangkat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function MPangkat()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.M_PANGKAT_ID ASC")
	{
		$str = "
		SELECT A.M_PANGKAT_ID, A.NAMA, A.KETERANGAN
		, A.KETERANGAN || ' (' || A.NAMA || ')' INFO_DATA
		FROM M_PANGKAT A
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
		$this->setField("M_PANGKAT_ID", $this->getNextId("M_PANGKAT_ID","M_PANGKAT")); 
		$str = "
		INSERT INTO M_PANGKAT
		(
			M_PANGKAT_ID, NAMA
		)
		VALUES (
		".$this->getField("M_PANGKAT_ID").",
		'".$this->getField("NAMA")."'
		)
		";

		$this->id = $this->getField("M_PANGKAT_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE M_PANGKAT
		SET
		NAMA= '".$this->getField("NAMA")."'
		WHERE M_PANGKAT_ID = ".$this->getField("M_PANGKAT_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM M_PANGKAT
        WHERE 
        M_PANGKAT_ID = '".$this->getField("M_PANGKAT_ID")."'
        ";	  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM M_PANGKAT A
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
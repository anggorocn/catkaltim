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

  class KraepelinSoal extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KraepelinSoal()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO cat.KRAEPELIN_SOAL (
		KRAEPELIN_SOAL_ID, PAKAI_KRAEPELIN_ID, X_DATA, Y_DATA, NILAI)
		VALUES (
			".$this->getField("KRAEPELIN_SOAL_ID").",
			".$this->getField("PAKAI_KRAEPELIN_ID").",
			".$this->getField("X_DATA").",
			".$this->getField("Y_DATA").",
			".$this->getField("NILAI")."
		)"; 
				
		$this->query = $str;
		// $this->id = $this->getField("KRAEPELIN_SOAL_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE cat.KRAEPELIN_SOAL SET
		NILAI= ".$this->getField("NILAI")."
		WHERE PAKAI_KRAEPELIN_ID= ".$this->getField("PAKAI_KRAEPELIN_ID")."
		AND X_DATA= ".$this->getField("X_DATA").",
		AND Y_DATA= ".$this->getField("Y_DATA")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
		$str = "
		DELETE FROM cat.KRAEPELIN_SOAL
		WHERE PAKAI_KRAEPELIN_ID= ".$this->getField("PAKAI_KRAEPELIN_ID")."
		AND X_DATA= ".$this->getField("X_DATA").",
		AND Y_DATA= ".$this->getField("Y_DATA")."
		"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByXbarisYbarisParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT A.PAKAI_KRAEPELIN_ID, MAX(A.X_DATA) X_DATA, MAX(A.Y_DATA) Y_DATA
		, MIN(A.X_DATA) MIN_X_DATA, MAX(A.X_DATA) - MIN(A.X_DATA) CHECK_MIN_X_DATA
		FROM cat.KRAEPELIN_SOAL A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.PAKAI_KRAEPELIN_ID ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="ORDER BY X_DATA, Y_DATA DESC")
	{
		$str = "
		SELECT A.PAKAI_KRAEPELIN_ID, A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat.KRAEPELIN_SOAL A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.KRAEPELIN_SOAL A WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str;

		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>
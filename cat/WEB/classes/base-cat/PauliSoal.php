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

  class PauliSoal extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PauliSoal()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO cat.PAULI_SOAL (
		PAULI_SOAL_ID, PAKAI_PAULI_ID, X_DATA, Y_DATA, NILAI)
		VALUES (
			".$this->getField("PAULI_SOAL_ID").",
			".$this->getField("PAKAI_PAULI_ID").",
			".$this->getField("X_DATA").",
			".$this->getField("Y_DATA").",
			".$this->getField("NILAI")."
		)"; 
				
		$this->query = $str;
		// $this->id = $this->getField("PAULI_SOAL_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE cat.PAULI_SOAL SET
		NILAI= ".$this->getField("NILAI")."
		WHERE PAKAI_PAULI_ID= ".$this->getField("PAKAI_PAULI_ID")."
		AND X_DATA= ".$this->getField("X_DATA").",
		AND Y_DATA= ".$this->getField("Y_DATA")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
		$str = "
		DELETE FROM cat.PAULI_SOAL
		WHERE PAKAI_PAULI_ID= ".$this->getField("PAKAI_PAULI_ID")."
		AND X_DATA= ".$this->getField("X_DATA").",
		AND Y_DATA= ".$this->getField("Y_DATA")."
		"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByXbarisYbarisParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT A.PAKAI_PAULI_ID, MAX(A.X_DATA) X_DATA, MAX(A.Y_DATA) Y_DATA
		, MIN(A.X_DATA) MIN_X_DATA, MAX(A.X_DATA) - MIN(A.X_DATA) CHECK_MIN_X_DATA
		FROM cat.PAULI_SOAL A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.PAKAI_PAULI_ID ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByXbarisYbarisParamsLatihan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT A.PAKAI_PAULI_ID, MAX(A.X_DATA) X_DATA, MAX(A.Y_DATA) Y_DATA
		, MIN(A.X_DATA) MIN_X_DATA, MAX(A.X_DATA) - MIN(A.X_DATA) CHECK_MIN_X_DATA
		FROM cat.PAULI_SOAL_LATIHAN A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.PAKAI_PAULI_ID ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="ORDER BY X_DATA, Y_DATA DESC")
	{
		$str = "
		SELECT A.PAKAI_PAULI_ID, A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat.PAULI_SOAL A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsLatihan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="ORDER BY X_DATA, Y_DATA DESC")
	{
		$str = "
		SELECT A.PAKAI_PAULI_ID, A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat.PAULI_SOAL_LATIHAN A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanLatihan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="ORDER BY X_DATA, Y_DATA DESC")
	{
		$str = "
		SELECT A.PAKAI_PAULI_ID, A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat.PAULI_JAWAB_LATIHAN A
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.PAULI_SOAL A WHERE 1=1 ".$statement; 
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
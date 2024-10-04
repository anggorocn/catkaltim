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

  class FormulaEselon extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaEselon()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_ESELON_ID", $this->getNextId("FORMULA_ESELON_ID","formula_eselon")); 

		$str = "INSERT INTO formula_eselon (
				   FORMULA_ESELON_ID, FORMULA_ID, ESELON_ID, TAHUN, PROSEN_KOMPETENSI, PROSEN_POTENSI, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("FORMULA_ESELON_ID").",
				  ".$this->getField("FORMULA_ID").",
				  ".$this->getField("ESELON_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("PROSEN_KOMPETENSI").",
				  ".$this->getField("PROSEN_POTENSI").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("FORMULA_ESELON_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str= "
				UPDATE formula_eselon SET
				  FORMULA_ID= ".$this->getField("FORMULA_ID").",
				  ESELON_ID= ".$this->getField("ESELON_ID").",
				  TAHUN= ".$this->getField("TAHUN").",
				  PROSEN_KOMPETENSI= ".$this->getField("PROSEN_KOMPETENSI").",
				  PROSEN_POTENSI= ".$this->getField("PROSEN_POTENSI")."
				WHERE FORMULA_ESELON_ID = ".$this->getField("FORMULA_ESELON_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM formula_eselon
                WHERE 
                  FORMULA_ESELON_ID = ".$this->getField("FORMULA_ESELON_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_ESELON_ID ASC")
	{
		$str = "SELECT FORMULA_ESELON_ID, FORMULA_ID, ESELON_ID, TAHUN, PROSEN_KOMPETENSI, PROSEN_POTENSI
				FROM formula_eselon A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $formulaid="", $statement='', $sOrder="ORDER BY A.ESELON_ID ASC")
	{
		$str = "
		SELECT B.FORMULA_ESELON_ID, A.ESELON_ID, COALESCE((A.NOTE || ' ' || A.NAMA), A.NAMA) NAMA_ESELON, B.PROSEN_POTENSI, B.PROSEN_KOMPETENSI
		, COALESCE(COALESCE(B.PROSEN_POTENSI,0) + COALESCE(B.PROSEN_KOMPETENSI), 100) PROSEN_TOTALbak
		, B.PROSEN_POTENSI + B.PROSEN_KOMPETENSI PROSEN_TOTAL
		, FORM_PERMEN_ID, b.tahun
		FROM eselon A
		LEFT JOIN formula_eselon B ON A.ESELON_ID = B.ESELON_ID AND B.FORMULA_ID = ".$formulaid."
		LEFT JOIN
		(
			SELECT FORMULA_ESELON_ID FORM_ESELON_ID, FORM_PERMEN_ID
			FROM formula_atribut
			GROUP BY FORMULA_ESELON_ID, FORM_PERMEN_ID
		) PA ON FORM_ESELON_ID = B.FORMULA_ESELON_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLookupMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.FORMULA_ESELON_ID ASC, A.ESELON_ID ASC")
	{
		$str = "
		SELECT A.FORMULA_ESELON_ID, C.FORMULA, COALESCE((B.NOTE || ' ' || B.NAMA), B.NAMA) NAMA_ESELON
		, C.FORMULA || ' untuk (' || COALESCE((B.NOTE || ' ' || B.NAMA), B.NAMA) || ')' NAMA_FORMULA_ESELON
		FROM formula_eselon A
		INNER JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
		INNER JOIN formula_assesment C ON A.FORMULA_ID = C.FORMULA_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsLookupMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM formula_eselon A
		INNER JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
		INNER JOIN formula_assesment C ON A.FORMULA_ID = C.FORMULA_ID
		WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_eselon WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>
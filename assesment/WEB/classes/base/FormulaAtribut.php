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

  class FormulaAtribut extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaAtribut()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_ATRIBUT_ID", $this->getNextId("FORMULA_ATRIBUT_ID","formula_atribut")); 

		$str = "INSERT INTO formula_atribut (
				   FORMULA_ATRIBUT_ID, FORMULA_ESELON_ID, LEVEL_ID, NILAI_STANDAR, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("FORMULA_ATRIBUT_ID").",
				  ".$this->getField("FORMULA_ESELON_ID").",
				  ".$this->getField("LEVEL_ID").",
				  ".$this->getField("NILAI_STANDAR").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("FORMULA_ATRIBUT_ID");
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
				UPDATE formula_atribut SET
				  FORMULA_ESELON_ID= ".$this->getField("FORMULA_ESELON_ID").",
				  LEVEL_ID= ".$this->getField("LEVEL_ID").",
				  NILAI_STANDAR= ".$this->getField("NILAI_STANDAR")."
				WHERE FORMULA_ATRIBUT_ID = ".$this->getField("FORMULA_ATRIBUT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteLevel()
	{
		$strLevel= "DELETE FROM level_atribut
                WHERE 
                  LEVEL_ID = ".$this->getField("LEVEL_ID")."";
				  
		$this->query = $strLevel;
        $this->execQuery($strLevel);
		
		$strLain= "DELETE FROM atribut_penggalian
                WHERE 
                  FORMULA_ATRIBUT_ID = ".$this->getField("FORMULA_ATRIBUT_ID").""; 
				  
		$this->query = $strLain;
        $this->execQuery($strLain);
		
        $str = "DELETE FROM formula_atribut
                WHERE 
                  FORMULA_ATRIBUT_ID = ".$this->getField("FORMULA_ATRIBUT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete()
	{
		$strLain= "DELETE FROM atribut_penggalian
                WHERE 
                  FORMULA_ATRIBUT_ID = ".$this->getField("FORMULA_ATRIBUT_ID").""; 
				  
		$this->query = $strLain;
        $this->execQuery($strLain);
		
        $str = "DELETE FROM formula_atribut
                WHERE 
                  FORMULA_ATRIBUT_ID = ".$this->getField("FORMULA_ATRIBUT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_ESELON_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_ATRIBUT_ID ASC")
	{
		$str = "SELECT FORMULA_ATRIBUT_ID, FORMULA_ESELON_ID, LEVEL_ID, NILAI_STANDAR
				FROM formula_atribut A WHERE 1=1 "; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_ESELON_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_atribut A WHERE 1=1 ".$statement;
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
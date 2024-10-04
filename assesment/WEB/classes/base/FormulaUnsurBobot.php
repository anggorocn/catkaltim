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

  class FormulaUnsurBobot extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaUnsurBobot()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("FORMULA_UNSUR_BOBOT_ID", $this->getNextId("FORMULA_UNSUR_BOBOT_ID","formula_unsur_bobot")); 

		$str = "
		INSERT INTO formula_unsur_bobot (
		FORMULA_UNSUR_BOBOT_ID, FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, BOBOT, PERMEN_ID)
		VALUES (
			".$this->getField("FORMULA_UNSUR_BOBOT_ID").",
			".$this->getField("FORMULA_UNSUR_ID").",
			'".$this->getField("UNSUR_ID")."',
			".$this->getField("UNSUR_BOBOT").",
			".$this->getField("BOBOT").",
			(SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
		)";
		$this->id= $this->getField("FORMULA_UNSUR_BOBOT_ID");
		$this->query= $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str= "
		UPDATE formula_unsur_bobot SET
			FORMULA_UNSUR_ID= ".$this->getField("FORMULA_UNSUR_ID")."
			, UNSUR_ID= '".$this->getField("UNSUR_ID")."'
			, UNSUR_BOBOT= ".$this->getField("UNSUR_BOBOT")."
			, BOBOT= ".$this->getField("BOBOT")."
		WHERE FORMULA_UNSUR_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_BOBOT_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
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
	
	function deleteLevel()
	{
		$strLevel= "DELETE FROM level_atribut
                WHERE 
                  ASPEK_ID = ".$this->getField("ASPEK_ID")."";
				  
		$this->query = $strLevel;
        $this->execQuery($strLevel);
		
		$strLain= "DELETE FROM atribut_penggalian
                WHERE 
                  FORMULA_UNSUR_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_BOBOT_ID").""; 
				  
		$this->query = $strLain;
        $this->execQuery($strLain);
		
        $str = "DELETE FROM formula_unsur_bobot
                WHERE 
                  FORMULA_UNSUR_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_BOBOT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete()
	{
		$strLain= "DELETE FROM atribut_penggalian
                WHERE 
                  FORMULA_UNSUR_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_BOBOT_ID").""; 
				  
		$this->query = $strLain;
        $this->execQuery($strLain);
		
        $str = "DELETE FROM formula_unsur_bobot
                WHERE 
                  FORMULA_UNSUR_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_BOBOT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_UNSUR_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_UNSUR_BOBOT_ID ASC")
	{
		$str = "
		SELECT FORMULA_UNSUR_BOBOT_ID, FORMULA_UNSUR_ID, ASPEK_ID, UNSUR_ID
		FROM formula_unsur_bobot A WHERE 1=1 "; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_UNSUR_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_unsur_bobot A WHERE 1=1 ".$statement;
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
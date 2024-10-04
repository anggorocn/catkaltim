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

  class FormulaUnsurPegawaiBobot extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaUnsurPegawaiBobot()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_UNSUR_PEGAWAI_BOBOT_ID", $this->getNextId("FORMULA_UNSUR_PEGAWAI_BOBOT_ID","formula_unsur_pegawai_bobot")); 

		$str = "INSERT INTO formula_unsur_pegawai_bobot (
				   FORMULA_UNSUR_PEGAWAI_BOBOT_ID,  UNSUR_ID, UNSUR_BOBOT, PEGAWAI_ID, PERMEN_ID)
				VALUES (
				  ".$this->getField("FORMULA_UNSUR_PEGAWAI_BOBOT_ID").",
				  '".$this->getField("UNSUR_ID")."',
				  ".$this->getField("UNSUR_BOBOT").",
				  ".$this->getField("PEGAWAI_ID").",
				  (SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
				)"; 
		$this->id= $this->getField("FORMULA_UNSUR_PEGAWAI_BOBOT_ID");
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
				UPDATE formula_unsur_pegawai_bobot SET
				  UNSUR_ID= '".$this->getField("UNSUR_ID")."',
				  UNSUR_BOBOT= ".$this->getField("UNSUR_BOBOT").",
				  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				WHERE FORMULA_UNSUR_PEGAWAI_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_PEGAWAI_BOBOT_ID")."
				AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
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
                  FORMULA_UNSUR_PEGAWAI_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_PEGAWAI_BOBOT_ID").""; 
				  
		$this->query = $strLain;
        $this->execQuery($strLain);
		
        $str = "DELETE FROM formula_unsur_pegawai_bobot
                WHERE 
                  FORMULA_UNSUR_PEGAWAI_BOBOT_ID = ".$this->getField("FORMULA_UNSUR_PEGAWAI_BOBOT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete()
	{
		
		
        $str = "DELETE FROM formula_unsur_pegawai_bobot
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_UNSUR_PEGAWAI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_UNSUR_PEGAWAI_BOBOT_ID ASC")
	{
		$str = "SELECT FORMULA_UNSUR_PEGAWAI_BOBOT_ID, FORMULA_UNSUR_PEGAWAI_ID, PEGAWAI_ID, UNSUR_ID,PERMEN_ID
				FROM formula_unsur_pegawai_bobot A WHERE 1=1 "; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA_UNSUR_PEGAWAI_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_unsur_pegawai_bobot A WHERE 1=1 ".$statement;
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
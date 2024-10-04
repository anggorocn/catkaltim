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

  class KompetensiTraining extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KompetensiTraining()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KOMPETENSI_TRAINING_ID", $this->getNextId("KOMPETENSI_TRAINING_ID","kompetensi_training"));
		
		$str = "INSERT INTO kompetensi_training (
				   KOMPETENSI_TRAINING_ID, TAHUN, ATRIBUT_ID, TRAINING_ID, PERMEN_ID)
				VALUES (
				  '".$this->getField("KOMPETENSI_TRAINING_ID")."',
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("ATRIBUT_ID")."',
				  ".$this->getField("TRAINING_ID").",
				  (SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("KOMPETENSI_TRAINING_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE kompetensi_training
				SET
				   TAHUN= '".$this->getField("TAHUN")."',
				   ATRIBUT_ID= '".$this->getField("ATRIBUT_ID")."'
				WHERE KOMPETENSI_TRAINING_ID= '".$this->getField("KOMPETENSI_TRAINING_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateTrainingTahun()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE kompetensi_training
				SET
				   TAHUN= ".$this->getField("TAHUN")."
				WHERE TRAINING_ID= '".$this->getField("TRAINING_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE kompetensi_training
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  KOMPETENSI_TRAINING_ID = '".$this->getField("KOMPETENSI_TRAINING_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM kompetensi_training
                WHERE 
                  KOMPETENSI_TRAINING_ID = '".$this->getField("KOMPETENSI_TRAINING_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.KOMPETENSI_TRAINING_ID ASC")
	{
		$str = "
				SELECT A.KOMPETENSI_TRAINING_ID, A.TAHUN, A.ATRIBUT_ID, A.TRAINING_ID
				FROM kompetensi_training A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM kompetensi_training A
		WHERE 1=1 "; 
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
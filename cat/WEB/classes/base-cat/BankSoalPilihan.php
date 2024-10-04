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

  class BankSoalPilihan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function BankSoalPilihan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BANK_SOAL_PILIHAN_ID", $this->getNextId("BANK_SOAL_PILIHAN_ID","cat.BANK_SOAL_PILIHAN")); 

		$str = "INSERT INTO cat.BANK_SOAL_PILIHAN (
				   BANK_SOAL_PILIHAN_ID, BANK_SOAL_ID, JAWABAN, GRADE_PROSENTASE, 
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("BANK_SOAL_PILIHAN_ID").",
				  ".$this->getField("BANK_SOAL_ID").",
				  '".$this->getField("JAWABAN")."',
				  ".$this->getField("GRADE_PROSENTASE").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->id = $this->getField("BANK_SOAL_PILIHAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.BANK_SOAL_PILIHAN SET
				  BANK_SOAL_ID				= ".$this->getField("BANK_SOAL_ID").",
				  JAWABAN					= '".$this->getField("JAWABAN")."',
				  GRADE_PROSENTASE			= ".$this->getField("GRADE_PROSENTASE").",
				  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE BANK_SOAL_PILIHAN_ID 	= ".$this->getField("BANK_SOAL_PILIHAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function uploadFile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.BANK_SOAL_PILIHAN SET
				  	PATH_GAMBAR				= '".$this->getField("PATH_GAMBAR")."'
				WHERE BANK_SOAL_PILIHAN_ID 	= ".$this->getField("BANK_SOAL_PILIHAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function uploadJawabanFile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.BANK_SOAL_PILIHAN SET
				  	JAWABAN= '".$this->getField("JAWABAN")."'
				WHERE BANK_SOAL_PILIHAN_ID 	= ".$this->getField("BANK_SOAL_PILIHAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.BANK_SOAL_PILIHAN
                WHERE 
                  BANK_SOAL_PILIHAN_ID = ".$this->getField("BANK_SOAL_PILIHAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT A.BANK_SOAL_ID, A.KEMAMPUAN, A.KATEGORI, A.PERTANYAAN, B.JAWABAN, B.GRADE_PROSENTASE, B.BANK_SOAL_PILIHAN_ID, B.PATH_GAMBAR
					FROM cat.BANK_SOAL A 
					LEFT JOIN cat.BANK_SOAL_PILIHAN B ON A.BANK_SOAL_ID=B.BANK_SOAL_ID
				 WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsPapi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT A.SOAL_PAPI_ID BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, A.PERTANYAAN, B.JAWABAN, '' GRADE_PROSENTASE, B.PAPI_PILIHAN_ID BANK_SOAL_PILIHAN_ID, '' PATH_GAMBAR
					FROM cat.SOAL_PAPI A
					LEFT JOIN cat.PAPI_PILIHAN B ON A.SOAL_PAPI_ID = B.SOAL_PAPI_ID
				 WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = " SELECT BANK_SOAL_PILIHAN_ID, BANK_SOAL_ID, JAWABAN, GRADE_PROSENTASE
				 FROM BANK_SOAL_PILIHAN
				 WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(BANK_SOAL_PILIHAN_ID) AS ROWCOUNT FROM cat.BANK_SOAL_PILIHAN WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(BANK_SOAL_PILIHAN_ID) AS ROWCOUNT FROM BANK_SOAL_PILIHAN WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>
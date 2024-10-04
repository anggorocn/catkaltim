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

  class BankSoal extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function BankSoal()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BANK_SOAL_ID", $this->getNextId("BANK_SOAL_ID","cat.BANK_SOAL")); 

		$str = "INSERT INTO cat.BANK_SOAL (
				   BANK_SOAL_ID, KEMAMPUAN, KATEGORI, PERTANYAAN, TIPE_SOAL, TIPE_UJIAN_ID,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("BANK_SOAL_ID").",
				  '".$this->getField("KEMAMPUAN")."',
				  '".$this->getField("KATEGORI")."',
				  '".$this->getField("PERTANYAAN")."',
				  ".$this->getField("TIPE_SOAL").",
				  ".$this->getField("TIPE_UJIAN_ID").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("BANK_SOAL_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.BANK_SOAL SET
					 KEMAMPUAN				= '".$this->getField("KEMAMPUAN")."',
					 KATEGORI				=  '".$this->getField("KATEGORI")."',
					 PERTANYAAN				=  '".$this->getField("PERTANYAAN")."',
				  	 TIPE_SOAL				= ".$this->getField("TIPE_SOAL").",
				  	 TIPE_UJIAN_ID			= ".$this->getField("TIPE_UJIAN_ID").",
					 LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE").",
					 LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE BANK_SOAL_ID 			= ".$this->getField("BANK_SOAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function uploadFile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.BANK_SOAL SET
				  	PATH_GAMBAR				= '".$this->getField("PATH_GAMBAR")."'
				WHERE BANK_SOAL_ID 			= ".$this->getField("BANK_SOAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function uploadSoalFile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.BANK_SOAL SET
				  	PATH_SOAL= '".$this->getField("PATH_SOAL")."'
				WHERE BANK_SOAL_ID= ".$this->getField("BANK_SOAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.BANK_SOAL
                WHERE 
                  BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteAll()
	{
        $str1 = "DELETE FROM cat.ujian_pegawai
                WHERE 
                  BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID").""; 
		$this->execQuery($str1);
		
        $str1 = "DELETE FROM cat.ujian_pegawai_ujicoba
                WHERE 
                  BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID").""; 
		$this->execQuery($str1);
		
        $str1 = "DELETE FROM cat.ujian_bank_soal
                WHERE 
                  BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID").""; 
		$this->execQuery($str1);
		
        $str1 = "DELETE FROM cat.BANK_SOAL_PILIHAN
                WHERE 
                  BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID").""; 
		$this->execQuery($str1);
				  
        $str = "DELETE FROM cat.BANK_SOAL
                WHERE 
                  BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID").""; 
				  
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
		$str = " SELECT BANK_SOAL_ID, KEMAMPUAN, KATEGORI, PERTANYAAN, TIPE_SOAL, cat.format_text(PERTANYAAN) PERTANYAAN_FORMAT, PATH_GAMBAR,
					   PATH_GAMBAR, CASE WHEN KEMAMPUAN = 'K' THEN 'Kompetensi' WHEN KEMAMPUAN = 'U' THEN 'Umum' END KEMAMPUAN_INFO
				 FROM cat.BANK_SOAL A
				 WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT BANK_SOAL_ID, KEMAMPUAN, KATEGORI, PERTANYAAN, TIPE_SOAL, cat.format_text(PERTANYAAN) PERTANYAAN_FORMAT, PATH_GAMBAR, PATH_SOAL,
					   CASE WHEN KEMAMPUAN = 'K' THEN 'Kompetensi' WHEN KEMAMPUAN = 'U' THEN 'Umum' END KEMAMPUAN_INFO, TIPE, A.TIPE_UJIAN_ID,
					   CASE WHEN TIPE_SOAL = 1 THEN 'Soal Text' WHEN TIPE_SOAL = 2 THEN 'Soal Gambar' WHEN TIPE_SOAL = 3 THEN 'Gambar lebih dari Satu Jawaban'
					    WHEN TIPE_SOAL = 4 THEN 'Soal text jawaban gambar' WHEN TIPE_SOAL = 5 THEN 'Soal gambar jawaban text' END TIPE_SOAL_INFO
				 FROM cat.BANK_SOAL A
				 LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
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
		$str = "SELECT SELECT BANK_SOAL_ID, KEMAMPUAN, KATEGORI, PERTANYAAN, LAST_CREATE_DATE, 
							   LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER, TIPE_SOAL, 
							   PATH_GAMBAR
				FROM BANK_SOAL WHERE 1=1"; 
		
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
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(BANK_SOAL_ID) AS ROWCOUNT FROM cat.BANK_SOAL A WHERE 1=1 ".$statement; 
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
		$str = "SELECT COUNT(BANK_SOAL_ID) AS ROWCOUNT FROM BANK_SOAL WHERE 1=1 "; 
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
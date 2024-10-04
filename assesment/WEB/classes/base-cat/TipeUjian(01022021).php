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

  class TipeUjian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TipeUjian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TIPE_UJIAN_ID", $this->getNextId("TIPE_UJIAN_ID","cat.tipe_ujian")); 
		$str = "INSERT INTO cat.tipe_ujian (
				   TIPE_UJIAN_ID, TIPE, LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("TIPE_UJIAN_ID").",
				  '".$this->getField("TIPE")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.tipe_ujian SET
				  TIPE= '".$this->getField("TIPE")."',
				  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE TIPE_UJIAN_ID 	= ".$this->getField("TIPE_UJIAN_ID")."
				"; 
				$this->query = $str;
				//echo $str;

		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.tipe_ujian
                WHERE 
                  TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID").""; 
				  
		$this->query = $str;

        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TIPE"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT TIPE_UJIAN_ID, TIPE, WAKTU, TOTAL_SOAL, KETERANGAN
				FROM cat.tipe_ujian WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY TIPE_UJIAN_ID ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsJumlahSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT
			A.TIPE_UJIAN_ID, A.TIPE, COALESCE(A.TOTAL_SOAL,0) JUMLAH_SOALBAK, WAKTU
			, CASE WHEN A.ID LIKE '01%' OR A.ID LIKE '02%' OR A.TIPE_UJIAN_ID = 7 OR A.TIPE_UJIAN_ID = 17 OR A.TIPE_UJIAN_ID = 41 OR A.TIPE_UJIAN_ID = 42 OR A.TIPE_UJIAN_ID = 45 OR A.TIPE_UJIAN_ID = 47 OR A.TIPE_UJIAN_ID = 48 THEN 1 ELSE 0 END TIPE_READONLY
			, CASE WHEN A.ID LIKE '01%' OR A.ID LIKE '02%' OR A.TIPE_UJIAN_ID = 7 OR A.TIPE_UJIAN_ID = 17 OR A.TIPE_UJIAN_ID = 41 OR A.TIPE_UJIAN_ID = 42 OR A.TIPE_UJIAN_ID = 45 OR A.TIPE_UJIAN_ID = 47 OR A.TIPE_UJIAN_ID = 48 THEN COALESCE(A.TOTAL_SOAL,0) 
			ELSE CASE WHEN COALESCE(JUMLAH_DATA,0) > COALESCE(A.TOTAL_SOAL,0) THEN COALESCE(A.TOTAL_SOAL,0) ELSE COALESCE(JUMLAH_DATA,0) END
			END JUMLAH_SOAL
			, ID, COALESCE(B.STATUS_ANAK,0) STATUS_ANAK, JUMLAH_DATA
		FROM cat.tipe_ujian A
		LEFT JOIN
		(
			SELECT
			A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
			FROM cat.tipe_ujian A
			WHERE 1=1
			GROUP BY A.PARENT_ID
		) B ON A.ID = B.ID_ROW
		LEFT JOIN
		(
			SELECT TIPE_UJIAN_ID B_ID, COUNT(1) JUMLAH_DATA
			FROM cat.bank_soal
			GROUP BY TIPE_UJIAN_ID
		) C ON B_ID = A.TIPE_UJIAN_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY ID ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT TIPE_UJIAN_ID, TIPE
				FROM tipe_ujian WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TIPE ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TIPE"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.tipe_ujian WHERE 1=1 "; 
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM tipe_ujian WHERE 1=1 "; 
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
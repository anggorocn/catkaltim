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
		$this->setField("TIPE_UJIAN_ID", $this->getNextId("TIPE_UJIAN_ID","cat.TIPE_UJIAN")); 
		$str = "INSERT INTO cat.TIPE_UJIAN (
				   TIPE_UJIAN_ID, TIPE, WAKTU, TOTAL_SOAL, KETERANGAN
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("TIPE_UJIAN_ID").",
				  '".$this->getField("TIPE")."',
				  ".$this->getField("WAKTU").",
				  ".$this->getField("TOTAL_SOAL").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.TIPE_UJIAN SET
				  TIPE= '".$this->getField("TIPE")."',
				  WAKTU= ".$this->getField("WAKTU").",
				  TOTAL_SOAL= ".$this->getField("TOTAL_SOAL").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
				  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE TIPE_UJIAN_ID 	= ".$this->getField("TIPE_UJIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.TIPE_UJIAN
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
		$str = "SELECT TIPE_UJIAN_ID, TIPE, WAKTU, TOTAL_SOAL, KETERANGAN, KETERANGAN_UJIAN
				FROM cat.TIPE_UJIAN WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY urutan ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamstes($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT a.TIPE_UJIAN_ID, a.TIPE, a.WAKTU, a.TOTAL_SOAL, a.KETERANGAN, a.KETERANGAN_UJIAN, b.KETERANGAN_UJIAN parent_tipe
				FROM cat.TIPE_UJIAN a
				left join cat.tipe_ujian b on a.parent_id = b.id WHERE a.id not in (select parent_id from cat.TIPE_UJIAN where parent_id != '0')"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY a.urutan ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsJumlahSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT
			A.TIPE_UJIAN_ID, A.TIPE, COALESCE(A.TOTAL_SOAL,0) JUMLAH_SOAL, WAKTU
			, CASE WHEN A.ID LIKE '01%' OR A.ID LIKE '02%' OR A.ID LIKE '07%' THEN 1 ELSE 0 END TIPE_READONLY
			, ID, COALESCE(B.STATUS_ANAK,0) STATUS_ANAK
		FROM cat.TIPE_UJIAN A
		LEFT JOIN
		(
			SELECT
			A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
			FROM cat.TIPE_UJIAN A
			WHERE 1=1
			GROUP BY A.PARENT_ID
		) B ON A.ID = B.ID_ROW
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
				FROM TIPE_UJIAN WHERE 1=1"; 
		
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
		$str = "SELECT COUNT(TIPE_UJIAN_ID) AS ROWCOUNT FROM cat.TIPE_UJIAN WHERE 1=1 "; 
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
		$str = "SELECT COUNT(TIPE_UJIAN_ID) AS ROWCOUNT FROM TIPE_UJIAN WHERE 1=1 "; 
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
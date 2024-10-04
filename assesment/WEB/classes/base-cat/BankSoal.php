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
		$this->setField("TIPE_UJIAN_ID", $this->getAdminNextId("TIPE_UJIAN_ID","cat.TIPE_UJIAN")); 
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

     function selectByParamsBankSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
 				SELECT BANK_SOAL_ID, KEMAMPUAN, KATEGORI, PERTANYAAN, TIPE_SOAL, (PERTANYAAN) PERTANYAAN_FORMAT, PATH_GAMBAR, PATH_SOAL,
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
		
		$str .= $statement." ORDER BY TIPE ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

     function selectByParamsBankSoalUpdate($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
 				SELECT BANK_SOAL_ID, KEMAMPUAN, KATEGORI, PERTANYAAN, TIPE_SOAL, (PERTANYAAN) PERTANYAAN_FORMAT, PATH_GAMBAR, PATH_SOAL,
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
		
		$str .= $statement." ORDER BY TIPE ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT TIPE_UJIAN_ID, TIPE, WAKTU, TOTAL_SOAL, KETERANGAN
				FROM cat.TIPE_UJIAN WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY ID ASC";
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
		$str = "SELECT BANK_SOAL_ID, PERTANYAAN
				FROM cat.BANK_SOAL WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PERTANYAAN ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TIPE"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(BANK_SOAL_ID) AS ROWCOUNT FROM cat.BANK_SOAL WHERE 1=1 "; 
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
		$str = "SELECT COUNT(BANK_SOAL_ID) AS ROWCOUNT FROM cat.BANK_SOAL WHERE 1=1 "; 
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

    function selectByParamsBankSoalPilihan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT
		A.*
		FROM cat.BANK_SOAL_PILIHAN A
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

    function selectByParamsBankTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.BANK_SOAL_ID, A.KEMAMPUAN, A.KATEGORI, A.PERTANYAAN, A.TIPE_SOAL, (A.PERTANYAAN) PERTANYAAN_FORMAT
		,
		CASE
		WHEN TIPE_SOAL = 2 OR TIPE_SOAL = 5 THEN REPLACE(A.PATH_GAMBAR, '../main', '../../cat/main') || A.PATH_SOAL
		WHEN TIPE_SOAL = 3 THEN REPLACE(A.PATH_GAMBAR, '../main', '../../cat/main')
		ELSE A.PERTANYAAN END INFO_SOAL
		, REPLACE(A.PATH_GAMBAR, '../main', '../../cat/main') PATH_GAMBAR, A.PATH_SOAL, A.TIPE_SOAL
		, CASE WHEN KEMAMPUAN = 'K' THEN 'Kompetensi' WHEN KEMAMPUAN = 'U' THEN 'Umum' END KEMAMPUAN_INFO, TIPE, A.TIPE_UJIAN_ID
		, CASE WHEN TIPE_SOAL = 1 THEN 'Soal Text' WHEN TIPE_SOAL = 2 THEN 'Soal Gambar' WHEN TIPE_SOAL = 3 THEN 'Gambar lebih dari Satu Jawaban'
		WHEN TIPE_SOAL = 4 THEN 'Soal text jawaban gambar' WHEN TIPE_SOAL = 5 THEN 'Soal gambar jawaban text' END TIPE_SOAL_INFO
		FROM cat.BANK_SOAL A
		LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
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

    function getCountByParamsBankTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.BANK_SOAL A
		LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
		WHERE 1=1 ".$statement;

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

    function selectByParamsBankPapiTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.*
		FROM cat.soal_papi A
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

    function getCountByParamsBankPapiTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.soal_papi A
		WHERE 1=1 ".$statement;

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

    function selectByParamsPapiSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY PAPI_PILIHAN_ID")
	{
		$str = "
		SELECT 
		*
		FROM cat.papi_pilihan A
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

    function selectByParamsBankMbtiTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.*
		FROM cat.mbti_soal A
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

    function getCountByParamsBankMbtiTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.mbti_soal A
		WHERE 1=1 ".$statement;

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

    function selectByParamsMbtiSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY MBTI_PILIHAN_ID")
	{
		$str = "
		SELECT 
		*
		FROM cat.mbti_pilihan A
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

    function selectByParamsBankDiscTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.*
		FROM (select disk_soal_id from cat.disk_pilihan where disk_soal_id % 2 = 1 group by disk_soal_id) A
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

    function getCountByParamsBankDiscTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM (select disk_soal_id from cat.disk_pilihan where disk_soal_id % 2 = 1 group by disk_soal_id) A
		WHERE 1=1 ".$statement;

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

    function selectByParamsDiscSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY DISK_PILIHAN_ID")
	{
		$str = "
		SELECT 
		*
		FROM cat.disk_pilihan A
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

    function selectByParamsBankBigFiveTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.*
		FROM cat.big_five_soal A
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

    function getCountByParamsBankBigFiveTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.big_five_soal A
		WHERE 1=1 ".$statement;

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

    function selectByParamsBigFiveSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY BIG_FIVE_PILIHAN_ID")
	{
		$str = "
		SELECT 
		*
		, CASE A.JAWABAN WHEN '1' THEN 'Sangat Tidak Sesuai' WHEN '2' THEN 'Tidak Sesuai' WHEN '3' THEN 'Ragu-ragu' WHEN '4' THEN 'Sesuai' WHEN '5' THEN 'Sangat Sesuai' ELSE '' END JAWABAN_INFO
		FROM cat.big_five_pilihan A
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

    function selectByParamsBankWptTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.*
		FROM cat.wpt_soal A
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

    function getCountByParamsBankWptTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.wpt_soal A
		WHERE 1=1 ".$statement;

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

    function selectByParamsWptSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY WPT_PILIHAN_ID")
	{
		$str = "
		SELECT 
		*
		FROM cat.wpt_pilihan A
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

    function selectByParamsBankHolandTipeSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "")
	{
		$str = "
		SELECT 
		A.*
		FROM cat.holand_soal A
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

    function getCountByParamsBankHolandTipeSoal($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.holand_soal A
		WHERE 1=1 ".$statement;

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

    function selectByParamsHolandSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY HOLAND_PILIHAN_ID")
	{
		$str = "
		SELECT 
		*
		FROM cat.holand_pilihan A
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

  } 
?>
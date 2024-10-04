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

  class UjianPauli extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianPauli()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO cat_pegawai.UJIAN_PAULI_".$this->getField("LOWONGAN_ID")." (
        UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID
        , UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID
		, PAKAI_PAULI_ID, X_DATA, Y_DATA, NILAI
		, LAST_CREATE_DATE, LAST_CREATE_USER
		)
		VALUES (
			".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").",
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("TIPE_UJIAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			(SELECT PAKAI_PAULI_ID FROM cat.PAULI_PAKAI WHERE COALESCE(NULLIF(STATUS, ''), NULL) IS NULL),
			".$this->getField("X_DATA").",
			".$this->getField("Y_DATA").",
			".$this->getField("NILAI").",
			".$this->getField("LAST_CREATE_DATE").",
			".$this->getField("LAST_CREATE_USER")."
		)"; 
				
		$this->query = $str;
		// echo $str;exit();
		// $this->id = $this->getField("UJIAN_PAULI_ID");
		return $this->execQuery($str);
    }

    function insertBatas()
	{
		$str = "
		INSERT INTO cat_pegawai.UJIAN_PAULI_TANDA_".$this->getField("LOWONGAN_ID")." (
        UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID
        , UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID
		, PAKAI_PAULI_ID, X_DATA, Y_DATA, NOMOR
		, KOLOM1, KOLOM2, KOLOM3
		, LAST_CREATE_DATE, LAST_CREATE_USER
		)
		VALUES (
			".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").",
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("TIPE_UJIAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			(SELECT PAKAI_PAULI_ID FROM cat.PAULI_PAKAI WHERE COALESCE(NULLIF(STATUS, ''), NULL) IS NULL),
			".$this->getField("X_DATA").",
			".$this->getField("Y_DATA").",
			(SELECT COUNT(1) + 1 FROM cat_pegawai.UJIAN_PAULI_TANDA_".$this->getField("LOWONGAN_ID")." WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("KOLOM1").",
			".$this->getField("KOLOM2").",
			".$this->getField("KOLOM3").",
			".$this->getField("LAST_CREATE_DATE").",
			".$this->getField("LAST_CREATE_USER")."
		)";
		// , NILAI
		// ".$this->getField("NILAI").",

		$this->query = $str;
		// echo $str;exit();
		// $this->id = $this->getField("UJIAN_PAULI_ID");
		return $this->execQuery($str);
    }

    function insertLatihan()
	{
		$str = "
		INSERT INTO cat_pegawai.UJIAN_PAULI_LATIHAN_".$this->getField("LOWONGAN_ID")." (
        UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID
        , UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID
		, PAKAI_PAULI_ID, X_DATA, Y_DATA, NILAI
		, LAST_CREATE_DATE, LAST_CREATE_USER
		)
		VALUES (
			".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").",
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("TIPE_UJIAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			(SELECT PAKAI_PAULI_ID FROM cat.PAULI_PAKAI WHERE COALESCE(NULLIF(STATUS, ''), NULL) IS NULL),
			".$this->getField("X_DATA").",
			".$this->getField("Y_DATA").",
			".$this->getField("NILAI").",
			".$this->getField("LAST_CREATE_DATE").",
			".$this->getField("LAST_CREATE_USER")."
		)"; 
				
		$this->query = $str;
		// echo $str;exit();
		// $this->id = $this->getField("UJIAN_PAULI_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE cat.UJIAN_PAULI SET
		NILAI= ".$this->getField("NILAI").",
		LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
		LAST_UPDATE_USER= ".$this->getField("LAST_UPDATE_USER")."
		WHERE UJIAN_PAULI_ID= ".$this->getField("UJIAN_PAULI_ID")."
		";
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
		$str = "
		DELETE FROM cat.UJIAN_PAULI
		WHERE PAKAI_PAULI_ID= ".$this->getField("PAKAI_PAULI_ID")."
		AND X_DATA= ".$this->getField("X_DATA").",
		AND Y_DATA= ".$this->getField("Y_DATA")."
		"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByTanda($reqId, $statement='', $sorder= "ORDER BY UJIAN_PAULI_TANDA_ID DESC")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, UJIAN_PAULI_TANDA_ID
			, UJIAN_ID, UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID, PAKAI_PAULI_ID
			, X_DATA, Y_DATA, KOLOM1, KOLOM2, KOLOM3, NOMOR
		FROM cat_pegawai.UJIAN_PAULI_TANDA_".$reqId." A
		WHERE 1=1 "; 
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByXbarisYbarisParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT A.UJIAN_ID, A.PEGAWAI_ID, A.PAKAI_PAULI_ID, MAX(A.X_DATA) X_DATA, MAX(A.Y_DATA) Y_DATA
		FROM cat.UJIAN_PAULI A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.PAKAI_PAULI_ID ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="ORDER BY X_DATA, Y_DATA DESC")
	{
		$str = "
		SELECT 
		A.UJIAN_PEGAWAI_DAFTAR_ID, A.JADWAL_TES_ID, A.FORMULA_ASSESMENT_ID, 
        A.FORMULA_ESELON_ID, A.UJIAN_PAULI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID, 
        A.TIPE_UJIAN_ID, A.PEGAWAI_ID, A.PAKAI_PAULI_ID, A.X_DATA, A.Y_DATA, 
        A.NILAI, A.LAST_CREATE_DATE, A.LAST_CREATE_USER, A.LAST_UPDATE_DATE, A.LAST_UPDATE_USER
		FROM cat.UJIAN_PAULI A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.UJIAN_PAULI A WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str;

		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>
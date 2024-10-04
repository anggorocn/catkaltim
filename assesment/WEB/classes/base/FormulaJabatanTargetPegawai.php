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

  class FormulaJabatanTargetPegawai extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function FormulaJabatanTargetPegawai()
	{
	  $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO formula_jabatan_target_pegawai (
		FORMULA_JABATAN_TARGET_ID, PEGAWAI_ID, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
		VALUES (
			".$this->getField("FORMULA_JABATAN_TARGET_ID").",
			".$this->getField("PEGAWAI_ID").",
			1,
			'".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE")."
		)";
		// echo $str;exit;
		// echo $set->query;exit;
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
	
	function delete()
	{
        $str = "DELETE FROM formula_jabatan_target_pegawai
                WHERE 
                  FORMULA_JABATAN_TARGET_ID= ".$this->getField("FORMULA_JABATAN_TARGET_ID")."
                  AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
                  "; 
				  
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

	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID
		FROM formula_jabatan_target_pegawai A
		INNER JOIN simpeg.pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN simpeg.pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN simpeg.eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN simpeg.satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM formula_jabatan_target_pegawai A
		INNER JOIN simpeg.pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN simpeg.pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN simpeg.eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN simpeg.satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		WHERE 1=1 ".$statement;
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

    function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
			A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, A1.NIK
			, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
			, D1.SATKER_ID SATKER_TES_ID, 0 JUMLAH_DATA
		FROM formula_jabatan_target_pegawai A
		INNER JOIN simpeg.pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN simpeg.pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN simpeg.eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN simpeg.satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsMonitoringPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM formula_jabatan_target_pegawai A
		INNER JOIN simpeg.pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN simpeg.pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN simpeg.eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN simpeg.satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		WHERE 1=1 ".$statement;
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
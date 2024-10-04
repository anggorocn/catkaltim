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

  class JadwalTesFormulaAssesmentUjianTahap extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JadwalTesFormulaAssesmentUjianTahap()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO jadwal_tes_formula_assesment_ujian_tahap 
		(
			JADWAL_TES_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID, FORMULA_ASSESMENT_ID, 
			TIPE_UJIAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE
		) 
		VALUES 
		(
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("TIPE_UJIAN_ID").",
			'".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE")."
		)"; 
		$this->id= $this->getField("FORMULA_ESELON_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		JADWAL_TES_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID, FORMULA_ASSESMENT_ID, 
		TIPE_UJIAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
		LAST_UPDATE_DATE
		FROM jadwal_tes_formula_assesment_ujian_tahap
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
    
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_ASSESMENT_UJIAN_TAHAP_ID")
	{
		$str = "
		SELECT T.TIPE, T.WAKTU
		, A.JADWAL_TES_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID, B.FORMULA_ASSESMENT_ID
		, B.TIPE_UJIAN_ID, X.LAST_CREATE_DATE
		FROM
		(
			SELECT JADWAL_TES_ID, FORMULA_ASSESMENT_ID 
			FROM cat.ujian_pegawai_daftar 
			GROUP BY JADWAL_TES_ID, FORMULA_ASSESMENT_ID
		) A
		INNER JOIN formula_assesment_ujian_tahap B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.tipe_ujian T ON T.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
		LEFT JOIN jadwal_tes_formula_assesment_ujian_tahap X ON 
		X.JADWAL_TES_ID= A.JADWAL_TES_ID AND X.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID
		AND X.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID AND X.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
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
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_tes_formula_assesment_ujian_tahap WHERE 1=1 ".$statement;
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
<? 
include_once("../WEB/classes/db/Entity.php");
class FormulaAssesmentUjianTahap extends Entity{ 

	var $query;
	function FormulaAssesmentUjianTahap()
	{
		$this->Entity(); 
	}

	function insertJumlahSoal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID", $this->getNextId("FORMULA_ASSESMENT_UJIAN_TAHAP_ID","formula_assesment_ujian_tahap")); 

		$str = "INSERT INTO formula_assesment_ujian_tahap (
				   FORMULA_ASSESMENT_UJIAN_TAHAP_ID, FORMULA_ASSESMENT_ID, TIPE_UJIAN_ID, BOBOT, MENIT_SOAL, JUMLAH_SOAL_UJIAN_TAHAP,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID").",
				  ".$this->getField("FORMULA_ASSESMENT_ID").",
				  ".$this->getField("TIPE_UJIAN_ID").",
				  ".$this->getField("BOBOT").",
				  ".$this->getField("MENIT_SOAL").",
				  ".$this->getField("JUMLAH_SOAL_UJIAN_TAHAP").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function updateJumlahSoal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE formula_assesment_ujian_tahap SET
		  FORMULA_ASSESMENT_ID= ".$this->getField("FORMULA_ASSESMENT_ID").",
		  TIPE_UJIAN_ID= ".$this->getField("TIPE_UJIAN_ID").",
		  BOBOT= ".$this->getField("BOBOT").",
		  MENIT_SOAL= ".$this->getField("MENIT_SOAL").",
		  JUMLAH_SOAL_UJIAN_TAHAP= ".$this->getField("JUMLAH_SOAL_UJIAN_TAHAP").",
		  LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
		  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		WHERE FORMULA_ASSESMENT_UJIAN_TAHAP_ID= ".$this->getField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function updateUrutanTes()
	{
		$str = "
		UPDATE formula_assesment_ujian_tahap SET
		  URUTAN_TES= ".$this->getField("URUTAN_TES").",
		  LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
		  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		WHERE FORMULA_ASSESMENT_UJIAN_TAHAP_ID= ".$this->getField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function delete()
	{
        $str = "
        DELETE FROM formula_assesment_ujian_tahap
        WHERE 
        FORMULA_ASSESMENT_UJIAN_TAHAP_ID= '".$this->getField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID")."'
        ";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="ORDER BY ID ASC")
	{
		$str = "
		SELECT
			A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, A.FORMULA_ASSESMENT_ID UJIAN_ID, A.TIPE_UJIAN_ID
			, BOBOT, TIPE, TIPE || ' (' || BOBOT || ')' TIPE_INFO
			, C.JUMLAH_SOAL, A.JUMLAH_SOAL_UJIAN_TAHAP, A.MENIT_SOAL
			, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' OR B.ID LIKE '07%' THEN 1 ELSE 0 END TIPE_READONLY
			, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' THEN 1 ELSE 0 END TIPE_RESET
			, B.ID, B.PARENT_ID, COALESCE(D.STATUS_ANAK,0) STATUS_ANAK
			, A.URUTAN_TES
		FROM formula_assesment_ujian_tahap A
		LEFT JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
		LEFT JOIN (
			SELECT FORMULA_ASSESMENT_ID UJIAN_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, COUNT(1) JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap_bank_soal
			GROUP BY FORMULA_ASSESMENT_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID
		) C ON A.FORMULA_ASSESMENT_ID = C.UJIAN_ID AND A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
		LEFT JOIN
		(
			SELECT
			A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
			FROM cat.tipe_ujian A
			WHERE 1=1
			GROUP BY A.PARENT_ID
		) D ON B.ID = D.ID_ROW
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM formula_assesment_ujian_tahap WHERE 1=1 "; 
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
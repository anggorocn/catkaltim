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
  include_once("../WEB-INF/classes/db/Entity.php");

  class FormulaAssesmentAtributUrutan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaAssesmentAtributUrutan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO formula_assesment_atribut_urutan (FORMULA_ESELON_ID, FORMULA_ID, ATRIBUT_ID, PERMEN_ID, URUT) 
		VALUES 
		(
			".$this->getField("FORMULA_ESELON_ID").",
			".$this->getField("FORMULA_ID").",
			'".$this->getField("ATRIBUT_ID")."',
			".$this->getField("PERMEN_ID").",
			".$this->getField("URUT")."
		)"; 
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM formula_assesment_atribut_urutan
        WHERE 
        FORMULA_ID = ".$this->getField("FORMULA_ID")."
        ";

        // FORMULA_ESELON_ID = ".$this->getField("FORMULA_ESELON_ID")." AND 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParamsEselon($paramsArray=array(),$limit=-1,$from=-1, $formulaid="", $statement='', $sOrder="ORDER BY A.ESELON_ID ASC")
	{
		$str = "
		SELECT B.FORMULA_ESELON_ID, A.ESELON_ID, COALESCE((A.NOTE || ' ' || A.NAMA), A.NAMA) NAMA_ESELON, B.PROSEN_POTENSI, B.PROSEN_KOMPETENSI
		, COALESCE(COALESCE(B.PROSEN_POTENSI,0) + COALESCE(B.PROSEN_KOMPETENSI), 100) PROSEN_TOTALbak
		, B.PROSEN_POTENSI + B.PROSEN_KOMPETENSI PROSEN_TOTAL
		, FORM_PERMEN_ID
		FROM eselon A
		INNER JOIN formula_eselon B ON A.ESELON_ID = B.ESELON_ID AND B.FORMULA_ID = ".$formulaid."
		LEFT JOIN
		(
			SELECT FORMULA_ESELON_ID FORM_ESELON_ID, FORM_PERMEN_ID
			FROM formula_atribut
			GROUP BY FORMULA_ESELON_ID, FORM_PERMEN_ID
		) PA ON FORM_ESELON_ID = B.FORMULA_ESELON_ID
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $formulaid, $statement='', $sOrder="ORDER BY ur.urut asc, A.ASPEK_ID, A.ATRIBUT_ID asc")
	{
		$str = "
		SELECT
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID
			, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Komptensi' END ASPEK_NAMA
			, A.NAMA, A.KETERANGAN, B.FORMULA_ATRIBUT_ID, B.LEVEL_ID, B.NILAI_STANDAR
			, C.ATRIBUT_NILAI_STANDAR, C.ATRIBUT_BOBOT, C.ATRIBUT_SKOR, C.FORMULA_ATRIBUT_BOBOT_ID
			, A.PERMEN_ID, B.FORMULA_ESELON_ID, UR.URUT
		FROM atribut A
		INNER JOIN
		(
			SELECT A.FORMULA_ATRIBUT_ID, A.LEVEL_ID, B.ATRIBUT_ID, A.NILAI_STANDAR, A.FORMULA_ESELON_ID
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			WHERE 1=1 AND A.FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
			UNION ALL
			SELECT NULL FORMULA_ATRIBUT_ID, NULL LEVEL_ID, SUBSTRING(B.ATRIBUT_ID,1,2) ATRIBUT_ID, NULL NILAI_STANDAR, A.FORMULA_ESELON_ID
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			WHERE 1=1 AND A.FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
			GROUP BY SUBSTRING(B.ATRIBUT_ID,1,2), A.FORMULA_ESELON_ID
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		LEFT JOIN
		(
			SELECT A.ASPEK_ID, A.ATRIBUT_ID, A.ATRIBUT_NILAI_STANDAR, A.ATRIBUT_BOBOT, A.ATRIBUT_SKOR
			, A.FORMULA_ATRIBUT_BOBOT_ID
			FROM formula_atribut_bobot A
			WHERE 1=1 AND A.FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
		) C ON A.ATRIBUT_ID = C.ATRIBUT_ID
		LEFT JOIN 
		( 
			SELECT * FROM formula_assesment_atribut_urutan WHERE FORMULA_ID = ".$formulaid."
		) UR ON A.ATRIBUT_ID = UR.ATRIBUT_ID AND A.PERMEN_ID = UR.PERMEN_ID
		WHERE 1=1
		AND EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT FORM_PERMEN_ID
				FROM formula_atribut
				WHERE 1=1 AND FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
				GROUP BY FORM_PERMEN_ID
			) X WHERE A.PERMEN_ID = X.FORM_PERMEN_ID
		)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParams($paramsArray=array(), $formulaid, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM atribut A
		INNER JOIN
		(
			SELECT A.FORMULA_ATRIBUT_ID, A.LEVEL_ID, B.ATRIBUT_ID, A.NILAI_STANDAR, A.FORMULA_ESELON_ID
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			WHERE 1=1 AND A.FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
			UNION ALL
			SELECT NULL FORMULA_ATRIBUT_ID, NULL LEVEL_ID, SUBSTRING(B.ATRIBUT_ID,1,2) ATRIBUT_ID, NULL NILAI_STANDAR, A.FORMULA_ESELON_ID
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			WHERE 1=1 AND A.FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
			GROUP BY SUBSTRING(B.ATRIBUT_ID,1,2), A.FORMULA_ESELON_ID
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		LEFT JOIN
		(
			SELECT A.ASPEK_ID, A.ATRIBUT_ID, A.ATRIBUT_NILAI_STANDAR, A.ATRIBUT_BOBOT, A.ATRIBUT_SKOR
			, A.FORMULA_ATRIBUT_BOBOT_ID
			FROM formula_atribut_bobot A
			WHERE 1=1 AND A.FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
		) C ON A.ATRIBUT_ID = C.ATRIBUT_ID
		LEFT JOIN 
		( 
			SELECT * FROM formula_assesment_atribut_urutan WHERE FORMULA_ID = ".$formulaid."
		) UR ON A.ATRIBUT_ID = UR.ATRIBUT_ID AND A.PERMEN_ID = UR.PERMEN_ID
		WHERE 1=1
		AND EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT FORM_PERMEN_ID
				FROM formula_atribut
				WHERE 1=1 AND FORMULA_ESELON_ID = (SELECT FORMULA_ESELON_ID FROM formula_eselon WHERE FORMULA_ID = ".$formulaid." LIMIT 1)
				GROUP BY FORM_PERMEN_ID
			) X WHERE A.PERMEN_ID = X.FORM_PERMEN_ID
		) ".$statement;
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
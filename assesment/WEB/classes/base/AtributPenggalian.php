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

  class AtributPenggalian extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function AtributPenggalian()
	{
       //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ATRIBUT_PENGGALIAN_ID", $this->getNextId("ATRIBUT_PENGGALIAN_ID","atribut_penggalian"));
		
		$str = "INSERT INTO atribut_penggalian (
				   ATRIBUT_PENGGALIAN_ID, FORMULA_ATRIBUT_ID, PENGGALIAN_ID)
				VALUES (
				  ".$this->getField("ATRIBUT_PENGGALIAN_ID").",
				  ".$this->getField("FORMULA_ATRIBUT_ID").",
				  ".$this->getField("PENGGALIAN_ID")."
				)"; 
		//echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("ATRIBUT_PENGGALIAN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE atribut_penggalian
				SET
				   FORMULA_ATRIBUT_ID= ".$this->getField("FORMULA_ATRIBUT_ID").",
				   PENGGALIAN_ID= ".$this->getField("PENGGALIAN_ID")."
				WHERE ATRIBUT_PENGGALIAN_ID= '".$this->getField("ATRIBUT_PENGGALIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE atribut_penggalian
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  ATRIBUT_PENGGALIAN_ID = ".$this->getField("ATRIBUT_PENGGALIAN_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM atribut_penggalian
                WHERE 
                  ATRIBUT_PENGGALIAN_ID = ".$this->getField("ATRIBUT_PENGGALIAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteFormulaAtribut()
	{
        $str = "DELETE FROM atribut_penggalian
                WHERE 
                  FORMULA_ATRIBUT_ID = ".$this->getField("FORMULA_ATRIBUT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM atribut_penggalian A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ATRIBUT_PENGGALIAN_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_PENGGALIAN_ID, A.FORMULA_ATRIBUT_ID, A.PENGGALIAN_ID
				FROM atribut_penggalian A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAtributFormulaPenggalian($paramsArray=array(),$limit=-1,$from=-1, $reqrowid="", $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID
			, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Komptensi' END ASPEK_NAMA
			, A.NAMA, A.KETERANGAN, B.FORMULA_ATRIBUT_ID, B.LEVEL_ID, B.NILAI_STANDAR
			, C.ATRIBUT_NILAI_STANDAR, C.ATRIBUT_BOBOT, C.ATRIBUT_SKOR, C.FORMULA_ATRIBUT_BOBOT_ID
		FROM atribut A
		LEFT JOIN
		(
			SELECT
				MAX(A.FORMULA_ATRIBUT_ID) FORMULA_ATRIBUT_ID
				, A.FORMULA_ESELON_ID, A.LEVEL_ID, A.FORM_ATRIBUT_ID
				, A.FORM_PERMEN_ID, A.FORM_LEVEL, nilai_standar
			FROM formula_atribut A
			WHERE 1=1 AND A.FORMULA_ESELON_ID = '".$reqrowid."'
			GROUP BY A.FORMULA_ESELON_ID, A.LEVEL_ID, A.FORM_ATRIBUT_ID, A.FORM_PERMEN_ID, A.FORM_LEVEL,nilai_standar
		) B ON A.ATRIBUT_ID = B.FORM_ATRIBUT_ID
		LEFT JOIN
		(
			SELECT A.ASPEK_ID, A.ATRIBUT_ID, A.ATRIBUT_NILAI_STANDAR, A.ATRIBUT_BOBOT, A.ATRIBUT_SKOR
			, A.FORMULA_ATRIBUT_BOBOT_ID
			FROM formula_atribut_bobot A
			WHERE 1=1 AND A.FORMULA_ESELON_ID = '".$reqrowid."'
		) C ON A.ATRIBUT_ID = C.ATRIBUT_ID
		WHERE 1=1
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;

		/*$str = "
		SELECT
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID
			, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Komptensi' END ASPEK_NAMA
			, A.NAMA, A.KETERANGAN, B.FORMULA_ATRIBUT_ID, B.LEVEL_ID, B.NILAI_STANDAR
			, C.ATRIBUT_NILAI_STANDAR, C.ATRIBUT_BOBOT, C.ATRIBUT_SKOR, C.FORMULA_ATRIBUT_BOBOT_ID
		FROM atribut A
		LEFT JOIN
		(
			SELECT A.FORMULA_ATRIBUT_ID, A.LEVEL_ID, B.ATRIBUT_ID, A.NILAI_STANDAR
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			WHERE 1=1 AND A.FORMULA_ESELON_ID = '".$reqrowid."'
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		LEFT JOIN
		(
			SELECT A.ASPEK_ID, A.ATRIBUT_ID, A.ATRIBUT_NILAI_STANDAR, A.ATRIBUT_BOBOT, A.ATRIBUT_SKOR
			, A.FORMULA_ATRIBUT_BOBOT_ID
			FROM formula_atribut_bobot A
			WHERE 1=1 AND A.FORMULA_ESELON_ID = '".$reqrowid."'
		) C ON A.ATRIBUT_ID = C.ATRIBUT_ID
		WHERE 1=1
		";*/
		return $this->selectLimit($str,$limit,$from); 
    }
	
	/** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM atribut_penggalian A
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
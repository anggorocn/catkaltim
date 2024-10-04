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

  class FormulaFaktor extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaFaktor()
	{
      $this->Entity(); 
    }

    function insertAssement()
	{
		$this->setField("FORMULA_FAKTOR_ID", $this->getNextId("FORMULA_FAKTOR_ID","formula_faktor")); 

		$str = "
		INSERT INTO formula_faktor (
		FORMULA_FAKTOR_ID, LAST_CREATE_USER, LAST_CREATE_DATE, ASSESMENT)
		VALUES (
			".$this->getField("FORMULA_FAKTOR_ID")."
			, '".$this->getField("LAST_CREATE_USER")."'
			, ".$this->getField("LAST_CREATE_DATE")."
			, '".$this->getField("ASSESMENT")."'
		)";
		$this->id= $this->getField("FORMULA_FAKTOR_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }

    function updateAssement()
	{
		$str = "
		UPDATE formula_faktor SET
			ASSESMENT= '".$this->getField("ASSESMENT")."'
			, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
			, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		WHERE FORMULA_FAKTOR_ID = ".$this->getField("FORMULA_FAKTOR_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insert()
	{
		$this->setField("FORMULA_FAKTOR_ID", $this->getNextId("FORMULA_FAKTOR_ID","formula_faktor")); 

		$str = "
		INSERT INTO formula_faktor (
		FORMULA_FAKTOR_ID, LAST_CREATE_USER, LAST_CREATE_DATE, ID_KUADRAN, ID_GRAFIK, FORMULA_ID,ASSESSMENT_ID)
		VALUES (
			".$this->getField("FORMULA_FAKTOR_ID")."
			, '".$this->getField("LAST_CREATE_USER")."'
			, ".$this->getField("LAST_CREATE_DATE")."
			, '".$this->getField("ID_KUADRAN")."'
			, ".$this->getField("ID_GRAFIK")."
			, ".$this->getField("FORMULA_ID")."
			, ".$this->getField("ASSESSMENT_ID")."
		)";
		$this->id= $this->getField("FORMULA_FAKTOR_ID");
		$this->query= $str;
		return $this->execQuery($str);
		// '".$this->getField("ASSESMENT")."'
    }

    function update()
	{
		$str = "
		UPDATE formula_faktor SET
			ID_KUADRAN= '".$this->getField("ID_KUADRAN")."'
			, ID_GRAFIK= ".$this->getField("ID_GRAFIK")."
			, FORMULA_ID= ".$this->getField("FORMULA_ID")."
			, ASSESSMENT_ID= ".$this->getField("ASSESMENT_ID")."
			, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
			, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		WHERE FORMULA_FAKTOR_ID = ".$this->getField("FORMULA_FAKTOR_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
		// ASSESMENT= '".$this->getField("ASSESMENT")."',
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
        $str = "DELETE FROM formula_faktor
                WHERE 
                  FORMULA_FAKTOR_ID = ".$this->getField("FORMULA_FAKTOR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParamsBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_FAKTOR_ID ASC")
    {
    	$str = "
    	SELECT 
    	A.FORMULA_ID
    	, B.FORMULA
    	, A.FORMULA_FAKTOR_ID
    	, A.ASSESMENT
    	, A.ID_GRAFIK 
    	, CASE WHEN
	    	A.ID_KUADRAN = 11 
	    	THEN 'I. Tingkatkan Peran Saat Ini'
	    	WHEN
	    	A.ID_KUADRAN = 12 
	    	THEN 'II. Tingkatkan Peran Saat Ini'
	    	WHEN
	    	A.ID_KUADRAN = 21 
	    	THEN 'III. Tingkatkan Peran Saat Ini'
	    	WHEN
	    	A.ID_KUADRAN = 13 
	    	THEN 'IV. Tingkatkan Peran Saat Ini'
	    	WHEN
	    	A.ID_KUADRAN = 22 
	    	THEN 'V. Siap Untuk Peran Masa Depan Dengan Pengembangan'
	    	WHEN
	    	A.ID_KUADRAN = 31
	    	THEN 'VI. Pertimbangkan (mutasi)'
	    	WHEN
	    	A.ID_KUADRAN = 23 
	    	THEN 'VII. Siap Untuk Peran Masa Depan Dengan Pengembangan'
	    	WHEN
	    	A.ID_KUADRAN = 32 
	    	THEN 'VIII. Siap Untuk Peran Masa Depan Dengan Pengembangan'
	    	WHEN
	    	A.ID_KUADRAN = 33 
	    	THEN 'IX. Kinerja diatas ekspektasi dan potensial tinggi'
	    	END KUADRAN_NAMA
    	, A.ID_KUADRAN  
    	, C.NAMA_KUADRAN KUADRAN_KOMPETENSI
    	, D.NAMA_KUADRAN KUADRAN_JPM
    	FROM FORMULA_FAKTOR A
    	LEFT JOIN FORMULA_SUKSESI B ON A.FORMULA_ID = B.FORMULA_ID 
    	LEFT JOIN P_KUADRAN_INFO() C ON A.ID_KUADRAN = C.ID_KUADRAN
    	LEFT JOIN P_KUADRAN_INFOJPM() D ON D.ID_KUADRAN = A.ID_KUADRAN 
    	WHERE 1=1  "; 

    	while(list($key,$val) = each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$sOrder;
    	$this->query = $str;

    	return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_FAKTOR_ID ASC")
    {
    	$str = "
    	SELECT 
    	A.FORMULA_ID
    	, B.FORMULA, A.FORMULA_FAKTOR_ID
    	, A.ASSESMENT, A.ID_GRAFIK, A.ID_KUADRAN,A.ASSESSMENT_ID
    	FROM FORMULA_FAKTOR A
    	LEFT JOIN FORMULA_SUKSESI B ON A.FORMULA_ID = B.FORMULA_ID
    	LEFT JOIN FORMULA_ASSESMENT C ON A.ASSESSMENT_ID = C.FORMULA_ID
    	WHERE 1=1  "; 

    	while(list($key,$val) = each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$sOrder;
    	$this->query = $str;

    	return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParamsPegawaiJadwalFormula($statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.ujian_pegawai_daftar WHERE 1=1 ".$statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_faktor WHERE 1=1 ".$statement;
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
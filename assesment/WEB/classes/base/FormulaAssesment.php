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

  class FormulaAssesment extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaAssesment()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_ID", $this->getNextId("FORMULA_ID","formula_assesment")); 

		$str = "INSERT INTO formula_assesment (
				   FORMULA_ID, FORMULA, TAHUN, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE, TIPE_FORMULA) 
				VALUES (
				  ".$this->getField("FORMULA_ID").",
				  '".$this->getField("FORMULA")."',
				  ".$this->getField("TAHUN").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("TIPE_FORMULA")."'
				)"; 
		$this->id= $this->getField("FORMULA_ID");
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
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$streselon= "UPDATE formula_eselon SET
				  TAHUN= '".$this->getField("TAHUN")."'
				WHERE FORMULA_ID = ".$this->getField("FORMULA_ID")."
				"; 
		$this->query = $streselon;
		$this->execQuery($streselon);
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE formula_assesment SET
				  FORMULA= '".$this->getField("FORMULA")."',
				  TIPE_FORMULA= '".$this->getField("TIPE_FORMULA")."',
				  TAHUN= ".$this->getField("TAHUN").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE FORMULA_ID = ".$this->getField("FORMULA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM formula_assesment
                WHERE 
                  FORMULA_ID = ".$this->getField("FORMULA_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_ID ASC")
	{
		$str = "SELECT FORMULA_ID, FORMULA, TAHUN, KETERANGAN, TIPE_FORMULA, 
				CASE 
				WHEN TIPE_FORMULA = '1' THEN 'Tujuan Pengisian' 
				WHEN TIPE_FORMULA = '2' THEN 'Tujuan Pemetaan'
				WHEN TIPE_FORMULA = '3' THEN 'Uji Coba'
				else 
					'-'
				END TIPE 
				FROM formula_assesment A WHERE 1=1 "; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_assesment WHERE 1=1 ".$statement;
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
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.LAST_JABATAN JABATAN_NAMA, D.NAMA SATKER
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		WHERE 1=1 "; 
		
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
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		WHERE 1=1 ".$statement;

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>
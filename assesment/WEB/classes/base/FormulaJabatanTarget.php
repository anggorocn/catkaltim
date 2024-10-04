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

  class FormulaJabatanTarget extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaJabatanTarget()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_JABATAN_TARGET_ID", $this->getNextId("FORMULA_JABATAN_TARGET_ID","formula_jabatan_target")); 

		$str = "
		INSERT INTO formula_jabatan_target (
		FORMULA_JABATAN_TARGET_ID, NAMA, TARGET, KETERANGAN
		, FORMULA_SUKSESI_ID, JABATAN_ID, RUMPUN_ID, SATKER_ID
		, LAST_CREATE_USER, LAST_CREATE_DATE) 
		VALUES (
			".$this->getField("FORMULA_JABATAN_TARGET_ID")."
			, '".$this->getField("NAMA")."'
			, ".$this->getField("TARGET")."
			, '".$this->getField("KETERANGAN")."'
			, ".$this->getField("FORMULA_SUKSESI_ID")."
			, '".$this->getField("JABATAN_ID")."'
			, '".$this->getField("RUMPUN_ID")."'
			, '".$this->getField("SATKER_ID")."'
			, '".$this->getField("LAST_CREATE_USER")."'
			, ".$this->getField("LAST_CREATE_DATE")."
		)"; 
		$this->id= $this->getField("FORMULA_JABATAN_TARGET_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE formula_jabatan_target SET
			NAMA= '".$this->getField("NAMA")."'
			, TARGET= ".$this->getField("TARGET")."
			, KETERANGAN= '".$this->getField("KETERANGAN")."'
			, FORMULA_SUKSESI_ID= ".$this->getField("FORMULA_SUKSESI_ID")."
			, JABATAN_ID= '".$this->getField("JABATAN_ID")."'
			, RUMPUN_ID= '".$this->getField("RUMPUN_ID")."'
			, SATKER_ID= '".$this->getField("SATKER_ID")."'
			, LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
			, LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
		WHERE FORMULA_JABATAN_TARGET_ID = ".$this->getField("FORMULA_JABATAN_TARGET_ID")."
		"; 
		$this->query = $str;
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
        $str = "DELETE FROM formula_jabatan_target
                WHERE 
                  FORMULA_JABATAN_TARGET_ID = ".$this->getField("FORMULA_JABATAN_TARGET_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_JABATAN_TARGET_ID ASC")
	{
		$str = "
		SELECT
			A.FORMULA_JABATAN_TARGET_ID, A.NAMA, A.TARGET, A.KETERANGAN
			, A.FORMULA_SUKSESI_ID, F.FORMULA FORMULA_SUKSESI_NAMA
			, A.JABATAN_ID, B.KODE_JABATAN JABATAN_KODE, B.NAMA_JABATAN JABATAN_NAMA
			, A.RUMPUN_ID, C.KODE_RUMPUN RUMPUN_KODE, C.NAMA_RUMPUN RUMPUN_NAMA
			, A.SATKER_ID, D.NAMA SATKER_NAMA
			, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
		FROM formula_jabatan_target A
		INNER JOIN simpeg.jabatan B ON A.JABATAN_ID = B.JABATAN_ID
		LEFT JOIN simpeg.rumpun C ON A.RUMPUN_ID = C.RUMPUN_ID
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		INNER JOIN formula_suksesi F ON A.FORMULA_SUKSESI_ID = F.FORMULA_ID
		WHERE 1=1 "; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM formula_jabatan_target A
		INNER JOIN simpeg.jabatan B ON A.JABATAN_ID = B.JABATAN_ID
		LEFT JOIN simpeg.rumpun C ON A.RUMPUN_ID = C.RUMPUN_ID
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		INNER JOIN formula_suksesi F ON A.FORMULA_SUKSESI_ID = F.FORMULA_ID
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
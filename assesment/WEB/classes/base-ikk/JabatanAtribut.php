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

  class JabatanAtribut extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JabatanAtribut()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_ATRIBUT_ID", $this->getNextId("JABATAN_ATRIBUT_ID","JABATAN_ATRIBUT"));
		
		$str = "INSERT INTO JABATAN_ATRIBUT (
				   JABATAN_ATRIBUT_ID, ATRIBUT_ID, TAHUN, JABATAN_ID, SATKER_ID) 
				VALUES (
				  ".$this->getField("JABATAN_ATRIBUT_ID").",
				  '".$this->getField("ATRIBUT_ID")."',
				  ".$this->getField("TAHUN").",
				  '".$this->getField("JABATAN_ID")."',
				  '".$this->getField("SATKER_ID")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("JABATAN_ATRIBUT_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE JABATAN_ATRIBUT
				SET
				   ATRIBUT_ID= '".$this->getField("ATRIBUT_ID")."',
				   TAHUN= ".$this->getField("TAHUN").",
				   JABATAN_ID= '".$this->getField("JABATAN_ID")."',
				   SATKER_ID= '".$this->getField("SATKER_ID")."'
				WHERE JABATAN_ATRIBUT_ID= '".$this->getField("JABATAN_ATRIBUT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE JABATAN_ATRIBUT
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  JABATAN_ATRIBUT_ID = '".$this->getField("JABATAN_ATRIBUT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM JABATAN_ATRIBUT
                WHERE 
                  JABATAN_ATRIBUT_ID = '".$this->getField("JABATAN_ATRIBUT_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.JABATAN_ATRIBUT_ID ASC")
	{
		$str = "
				SELECT A.JABATAN_ATRIBUT_ID, A.ATRIBUT_ID, A.TAHUN, A.JABATAN_ID, A.SATKER_ID
				FROM JABATAN_ATRIBUT A
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
	
	function selectByParamsJabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.JABATAN_ID ASC")
	{
		$str = "
				SELECT A.TAHUN, A.JABATAN_ID, B.NAMA JABATAN_NAMA, A.SATKER_ID 
				FROM JABATAN_ATRIBUT A
				INNER JOIN dbsimpeg.JABATAN B ON A.JABATAN_ID = B.JABATAN_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.JABATAN_ID, A.TAHUN ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM JABATAN_ATRIBUT A
		WHERE 1=1 "; 
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
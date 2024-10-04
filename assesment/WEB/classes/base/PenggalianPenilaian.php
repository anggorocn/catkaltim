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

  class PenggalianPenilaian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PenggalianPenilaian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENGGALIAN_PENILAIAN_ID", $this->getNextId("PENGGALIAN_PENILAIAN_ID","penggalian_penilaian")); 

		$str = "INSERT INTO penggalian_penilaian (
				   PENGGALIAN_PENILAIAN_ID, TAHUN, PENGGALIAN_ID, JABATAN_ESELON_ATRIBUT_ID, ATRIBUT_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("PENGGALIAN_PENILAIAN_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("PENGGALIAN_ID").",
				  ".$this->getField("JABATAN_ESELON_ATRIBUT_ID").",
				  '".$this->getField("ATRIBUT_ID")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("PENGGALIAN_PENILAIAN_ID");
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
		$str = "UPDATE penggalian_penilaian SET
				  TAHUN= ".$this->getField("TAHUN").",
				  PENGGALIAN_ID= ".$this->getField("PENGGALIAN_ID").",
				  JABATAN_ESELON_ATRIBUT_ID= ".$this->getField("JABATAN_ESELON_ATRIBUT_ID").",
				  ATRIBUT_ID= '".$this->getField("ATRIBUT_ID")."',
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE PENGGALIAN_PENILAIAN_ID = ".$this->getField("PENGGALIAN_PENILAIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM penggalian_penilaian
                WHERE 
                  PENGGALIAN_PENILAIAN_ID = ".$this->getField("PENGGALIAN_PENILAIAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteAll()
	{
        $str = "DELETE FROM penggalian_penilaian
                WHERE 
				  PENGGALIAN_ID= ".$this->getField("PENGGALIAN_ID")." AND
				  JABATAN_ESELON_ATRIBUT_ID= ".$this->getField("JABATAN_ESELON_ATRIBUT_ID"); 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY PENGGALIAN_PENILAIAN_ID ASC")
	{
		$str = "SELECT PENGGALIAN_PENILAIAN_ID, TAHUN, PENGGALIAN_ID, JABATAN_ESELON_ATRIBUT_ID, ATRIBUT_ID
				FROM penggalian_penilaian WHERE PENGGALIAN_PENILAIAN_ID IS NOT NULL"; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM penggalian_penilaian WHERE 1=1 ".$statement;
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
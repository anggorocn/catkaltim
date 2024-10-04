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

  class KontenInformasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KontenInformasi()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT KONTEN_INFORMASI_ID, PATH, KETERANGAN, STATUS
		FROM KONTEN_INFORMASI A
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KONTEN_INFORMASI_ID", $this->getNextId("KONTEN_INFORMASI_ID","KONTEN_INFORMASI")); 
		$str = "INSERT INTO KONTEN_INFORMASI(
						KONTEN_INFORMASI_ID, PATH, KETERANGAN, STATUS)
				VALUES (
					".$this->getField("KONTEN_INFORMASI_ID").",
					'".$this->getField("PATH")."',
					'".$this->getField("KETERANGAN")."',
					".$this->getField("STATUS")."
					)
		
		"; 
		$this->id = $this->getField("KONTEN_INFORMASI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KONTEN_INFORMASI
				   SET 
					   PATH=	'".$this->getField("PATH")."',
					   KETERANGAN=	'".$this->getField("KETERANGAN")."',
					   STATUS= ".$this->getField("STATUS")."
				WHERE KONTEN_INFORMASI_ID = ".$this->getField("KONTEN_INFORMASI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE ".$this->getField("FIELD_ID")." = '".$this->getField("FIELD_VALUE_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM KONTEN_INFORMASI
                WHERE 
                  KONTEN_INFORMASI_ID LIKE '".$this->getField("KONTEN_INFORMASI_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM KONTEN_INFORMASI A
		LEFT JOIN DIKLAT B ON A.DIKLAT_ID = B.DIKLAT_ID
		WHERE 1 = 1 ".$statement; 
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
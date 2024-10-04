<? 
/* *******************************************************************************************************
MODUL NAME 			: PERPUSTAKAAN
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
  //include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/Entity.php");

  class InformasiHeader extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function InformasiHeader()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("INFORMASI_HEADER_ID", $this->getNextId("INFORMASI_HEADER_ID","INFORMASI_HEADER")); 

		$str = "INSERT INTO INFORMASI_HEADER(INFORMASI_HEADER_ID, NAMA, TANGGAL, STATUS) 
				VALUES(
				  ".$this->getField("INFORMASI_HEADER_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("STATUS")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("INFORMASI_HEADER_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE INFORMASI_HEADER SET
				  NAMA= '".$this->getField("NAMA")."',
				  TANGGAL= ".$this->getField("TANGGAL").",
				  STATUS= '".$this->getField("STATUS")."'
				WHERE INFORMASI_HEADER_ID= '".$this->getField("INFORMASI_HEADER_ID")."'
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
        $str = "
				DELETE FROM INFORMASI_HEADER
                WHERE 
                  INFORMASI_HEADER_ID = '".$this->getField("INFORMASI_HEADER_ID")."'
			"; 
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT INFORMASI_HEADER_ID, md5(CAST(INFORMASI_HEADER_ID as TEXT)) INFORMASI_HEADER_ID_ENKRIP, NAMA, TANGGAL, STATUS,
				CASE STATUS WHEN '1' THEN 'Aktif' ELSE 'Tidak Aktif' END STATUS_INFO
				FROM INFORMASI_HEADER WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM INFORMASI_HEADER WHERE 1=1 ".$statement; 
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
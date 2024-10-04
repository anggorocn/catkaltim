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

  class Konfigurasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Konfigurasi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KONFIGURASI_ID", $this->getNextId("KONFIGURASI_ID","KONFIGURASI")); 

		$str = "INSERT INTO KONFIGURASI(KONFIGURASI_ID, BIDANG) 
				VALUES(
				  ".$this->getField("KONFIGURASI_ID").",
				  '".$this->getField("BIDANG")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("KONFIGURASI_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KONFIGURASI SET
				  TANGGAL_PENUTUPAN= ".$this->getField("TANGGAL_PENUTUPAN").",
				  INFO_LINK= '".$this->getField("INFO_LINK")."',
				  INFO_EMAIL_FROM= '".$this->getField("INFO_EMAIL_FROM")."'
				WHERE KONFIGURASI_ID= '".$this->getField("KONFIGURASI_ID")."'
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
				DELETE FROM KONFIGURASI
                WHERE 
                  KONFIGURASI_ID = '".$this->getField("KONFIGURASI_ID")."'
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
		$str = "SELECT KONFIGURASI_ID, md5(CAST(KONFIGURASI_ID as TEXT)) KONFIGURASI_ID_ENKRIP, TANGGAL_PENUTUPAN, INFO_LINK, INFO_EMAIL_FROM, INFO_INDONESIA_MEMANGGIL
				FROM KONFIGURASI WHERE 1=1"; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM KONFIGURASI WHERE 1=1 ".$statement; 
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
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

  class Lowongan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Lowongan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOWONGAN_ID", $this->getNextId("LOWONGAN_ID","cat.LOWONGAN")); 

		$str = "
				INSERT INTO cat.LOWONGAN(
						LOWONGAN_ID, KODE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, KETERANGAN,  
						LAST_CREATE_USER, LAST_CREATE_DATE)
				VALUES ('".$this->getField("LOWONGAN_ID")."', 
						'".$this->getField("KODE")."', 
						".$this->getField("TANGGAL").", 
						".$this->getField("TANGGAL_AWAL").", 
						".$this->getField("TANGGAL_AKHIR").", 
						'".$this->getField("KETERANGAN")."', 
						'".$this->getField("LAST_CREATE_USER")."',
						".$this->getField("LAST_CREATE_DATE")."
				)
				"; 
		$this->query = $str;
		$this->id = $this->getField("LOWONGAN_ID");
		return $this->execQuery($str);
    }
		
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE cat.LOWONGAN 
				SET 
					KODE				= '".$this->getField("KODE")."', 
					TANGGAL 			= ".$this->getField("TANGGAL").",
					TANGGAL_AWAL 		= ".$this->getField("TANGGAL_AWAL").",
					TANGGAL_AKHIR 		= ".$this->getField("TANGGAL_AKHIR").", 
					KETERANGAN			= '".$this->getField("KETERANGAN")."'
			 	WHERE LOWONGAN_ID		= '".$this->getField("LOWONGAN_ID")."'
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

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.LOWONGAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }
		
	function delete()
	{
        $str = "
				DELETE FROM cat.LOWONGAN
                WHERE 
                  LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'
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
		$str = "SELECT LOWONGAN_ID, A.KODE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, A.KETERANGAN
				  FROM cat.LOWONGAN A
				 WHERE 1=1 "; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM cat.LOWONGAN A 
				WHERE 1=1 ".$statement; 
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
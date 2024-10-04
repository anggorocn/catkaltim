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

  class PelamarKaryaTulis extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarKaryaTulis()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_KARYA_TULIS_ID", $this->getNextId("PELAMAR_KARYA_TULIS_ID","PELAMAR_KARYA_TULIS")); 

		$str = "INSERT INTO PELAMAR_KARYA_TULIS(PELAMAR_KARYA_TULIS_ID, PELAMAR_ID, TAHUN, NAMA, TANGGAL)
				VALUES(
				  ".$this->getField("PELAMAR_KARYA_TULIS_ID").",
				  ".$this->getField("PELAMAR_ID").",
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("NAMA")."',
				  ".$this->getField("TANGGAL")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_KARYA_TULIS_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR_KARYA_TULIS SET
				  TAHUN= '".$this->getField("TAHUN")."',
				  NAMA= '".$this->getField("NAMA")."',
				  TANGGAL= ".$this->getField("TANGGAL")."
				WHERE PELAMAR_KARYA_TULIS_ID= '".$this->getField("PELAMAR_KARYA_TULIS_ID")."'
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
				DELETE FROM PELAMAR_KARYA_TULIS
                WHERE 
                  PELAMAR_KARYA_TULIS_ID= ".$this->getField("PELAMAR_KARYA_TULIS_ID")."
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
		$str = "SELECT PELAMAR_KARYA_TULIS_ID, md5(CAST(PELAMAR_ID as TEXT)) PELAMAR_ID_ENKRIP, PELAMAR_ID, TAHUN, NAMA, TANGGAL
				FROM PELAMAR_KARYA_TULIS WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.PELAMAR_KARYA_TULIS_ID, md5(CAST(A.PELAMAR_ID as TEXT)) PELAMAR_ID_ENKRIP, A.PELAMAR_ID, A.TAHUN, A.NAMA
				, A.TANGGAL
				FROM PELAMAR_KARYA_TULIS A WHERE 1=1 "; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PELAMAR_KARYA_TULIS WHERE 1=1 ".$statement; 
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
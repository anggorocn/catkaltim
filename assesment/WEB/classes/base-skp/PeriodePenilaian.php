<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel PERIODE_PENILAIAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PeriodePenilaian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PeriodePenilaian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("TAHUN", $this->getNextId("TAHUN","PERIODE_PENILAIAN")); 		

		$str = "
				INSERT INTO periode_penilaian (
				    TAHUN, TANGGAL_AWAL, TANGGAL_AKHIR)  
 			  	VALUES (
				  ".$this->getField("TAHUN").",
				  ".$this->getField("TANGGAL_AWAL").",
				  ".$this->getField("TANGGAL_AKHIR")."
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function update()
	{
		$str = "
				UPDATE periode_penilaian
				SET    
						TAHUN			= ".$this->getField("TAHUN").",
				  		TANGGAL_AWAL	= ".$this->getField("TANGGAL_AWAL").",
				 		TANGGAL_AKHIR	= ".$this->getField("TANGGAL_AKHIR")."			   
				WHERE  TAHUN  = '".$this->getField("TAHUN")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }


    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE periode_penilaian A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE TAHUN = ".$this->getField("TAHUN")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM periode_penilaian
                WHERE 
                  TAHUN = ".$this->getField("TAHUN").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.TAHUN DESC ")
	{
		$str = "
					SELECT 
						   TAHUN, TANGGAL_AWAL, TANGGAL_AKHIR
					FROM periode_penilaian A WHERE TAHUN IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT
						   TAHUN, TANGGAL_AWAL, TANGGAL_AKHIR
					FROM periode_penilaian A WHERE TAHUN IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TAHUN) AS ROWCOUNT FROM periode_penilaian A
		        WHERE TAHUN IS NOT NULL ".$statement; 
		
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

    function getMaxTahun($paramsArray=array(), $statement="")
	{
		$str = "SELECT MAX(TAHUN) AS ROWCOUNT FROM periode_penilaian A
		        WHERE TAHUN IS NOT NULL ".$statement; 
		
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
	
    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TAHUN) AS ROWCOUNT FROM periode_penilaian A
		        WHERE TAHUN IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>
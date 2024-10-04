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
  * Entity-base class untuk mengimplementasikan tabel PELAMAR_DOKUMEN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarDokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarDokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_DOKUMEN_ID", $this->getAdminNextId("PELAMAR_DOKUMEN_ID","pds_rekrutmen.PELAMAR_DOKUMEN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_DOKUMEN (
				   PELAMAR_DOKUMEN_ID, PELAMAR_ID, DOKUMEN_ID) 
 			  	VALUES (
				  ".$this->getField("PELAMAR_DOKUMEN_ID").",
				  ".$this->getField("PELAMAR_ID").",
				  ".$this->getField("DOKUMEN_ID")."
				)"; 
		$this->id = $this->getField("PELAMAR_DOKUMEN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_DOKUMEN
				SET    
					   PELAMAR_ID           = ".$this->getField("PELAMAR_ID").",
					   DOKUMEN_ID      		= ".$this->getField("DOKUMEN_ID")."
				WHERE  PELAMAR_DOKUMEN_ID   = '".$this->getField("PELAMAR_DOKUMEN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR_DOKUMEN SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PELAMAR_DOKUMEN_ID = ".$this->getField("PELAMAR_DOKUMEN_ID")."
				"; 
		
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_DOKUMEN
                WHERE 
                  PELAMAR_DOKUMEN_ID = ".$this->getField("PELAMAR_DOKUMEN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
		//echo $str;
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY PELAMAR_DOKUMEN_ID ASC ")
	{
		$str = "
				SELECT PELAMAR_DOKUMEN_ID, PELAMAR_ID, DOKUMEN_ID, LINK_FILE
				FROM pds_rekrutmen.PELAMAR_DOKUMEN A
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
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT A.PELAMAR_DOKUMEN_ID, A.PELAMAR_ID, A.DOKUMEN_ID, NAMA, FORMAT, WAJIB, LAMPIRAN
				FROM pds_rekrutmen.PELAMAR_DOKUMEN A
				LEFT JOIN pds_rekrutmen.DOKUMEN B ON A.DOKUMEN_ID = B.DOKUMEN_ID
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $pelamarId="", $statement="", $order="")
	{
		$str = "
				SELECT B.PELAMAR_DOKUMEN_ID, B.PELAMAR_ID, A.DOKUMEN_ID, NAMA, FORMAT, WAJIB, LAMPIRAN
				FROM pds_rekrutmen.DOKUMEN A
				LEFT JOIN pds_rekrutmen.PELAMAR_DOKUMEN B ON A.DOKUMEN_ID = B.DOKUMEN_ID AND B.PELAMAR_ID = '".$pelamarId."'
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
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT PELAMAR_DOKUMEN_ID, PELAMAR_ID, DOKUMEN_ID
				FROM pds_rekrutmen.PELAMAR_DOKUMEN
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PELAMAR_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_DOKUMEN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_DOKUMEN A
		        WHERE PELAMAR_DOKUMEN_ID IS NOT NULL ".$statement; 
		
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pds_rekrutmen.PELAMAR_DOKUMEN A
		WHERE 1 = 1 ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_DOKUMEN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_DOKUMEN
		        WHERE PELAMAR_DOKUMEN_ID IS NOT NULL ".$statement; 
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
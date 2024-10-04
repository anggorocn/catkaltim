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
  * Entity-base class untuk mengimplementasikan tabel pds_rekrutmen.BLACKLIST.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Blacklist extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Blacklist()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BLACKLIST_ID", $this->getNextId("BLACKLIST_ID","pds_rekrutmen.BLACKLIST"));

		$str = "
				INSERT INTO pds_rekrutmen.BLACKLIST (
				   BLACKLIST_ID, KTP_NO, TANGGAL, LAST_UPDATE_USER, LAST_UPDATE_DATE,
				   NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR) 
 			  	VALUES (
				  ".$this->getField("BLACKLIST_ID").",
				  '".$this->getField("KTP_NO")."',
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("LAST_UPDATE_USER")."',
				  ".$this->getField("LAST_UPDATE_DATE").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR")."
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.BLACKLIST
				SET    
					   KTP_NO				= '".$this->getField("KTP_NO")."',
					   TANGGAL	 			= ".$this->getField("TANGGAL").",
					   NAMA	 				= '".$this->getField("NAMA")."',
					   TEMPAT_LAHIR	 		= '".$this->getField("TEMPAT_LAHIR")."',
					   TANGGAL_LAHIR	 	= ".$this->getField("TANGGAL_LAHIR").",
					   LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  BLACKLIST_ID  		= '".$this->getField("BLACKLIST_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.BLACKLIST
                WHERE 
                  BLACKLIST_ID = ".$this->getField("BLACKLIST_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY TANGGAL DESC")
	{
		$str = "
					SELECT BLACKLIST_ID, A.KTP_NO, A.TANGGAL, B.NAMA, A.LAST_CREATE_USER,
					A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
					FROM pds_rekrutmen.BLACKLIST A 
					LEFT JOIN pds_rekrutmen.PELAMAR B ON B.KTP_NO = A.KTP_NO
					WHERE 1=1
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
					SELECT BLACKLIST_ID, A.KTP_NO, A.TANGGAL, B.NAMA, A.LAST_CREATE_USER,
					A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
					FROM pds_rekrutmen.BLACKLIST A 
					LEFT JOIN pds_rekrutmen.PELAMAR B ON B.KTP_NO = A.KTP_NO
					WHERE 1=1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TANGGAL DESC";
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(BLACKLIST_ID) AS ROWCOUNT FROM pds_rekrutmen.BLACKLIST A
				pds_rekrutmen.BLACKLIST B ON B.KTP_NO = A.KTP_NO
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(BLACKLIST_ID) AS ROWCOUNT FROM pds_rekrutmen.BLACKLIST A
				pds_rekrutmen.BLACKLIST B ON B.KTP_NO = A.KTP_NO
		        WHERE 1=1 ".$statement; 
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
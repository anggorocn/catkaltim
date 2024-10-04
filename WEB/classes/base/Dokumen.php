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
  * Entity-base class untuk mengimplementasikan tabel DOKUMEN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Dokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Dokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DOKUMEN_ID", $this->getAdminNextId("DOKUMEN_ID","pds_rekrutmen.DOKUMEN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.DOKUMEN (
				   DOKUMEN_ID, NAMA, KETERANGAN, WAJIB, FORMAT, FORMAT, STATUS_AKTIF, LAST_CREATE_USER, LAST_CREATE_DATE) 
 			  	VALUES (
				  ".$this->getField("DOKUMEN_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("WAJIB")."',
				  '".$this->getField("FORMAT")."',
				  '".$this->getField("STATUS_AKTIF")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.DOKUMEN
				SET    
					   NAMA           	= '".$this->getField("NAMA")."',
					   KETERANGAN      	= '".$this->getField("KETERANGAN")."',
				  	   WAJIB			= '".$this->getField("WAJIB")."',
				  	   FORMAT			= '".$this->getField("FORMAT")."',
				  	   STATUS_AKTIF		= '".$this->getField("STATUS_AKTIF")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  DOKUMEN_ID     	= '".$this->getField("DOKUMEN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.DOKUMEN
                WHERE 
                  DOKUMEN_ID = ".$this->getField("DOKUMEN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY DOKUMEN_ID ASC ")
	{
		$str = "
				SELECT DOKUMEN_ID, NAMA, KETERANGAN, WAJIB, FORMAT, STATUS_AKTIF, CASE WHEN STATUS_AKTIF = '1' THEN 'Aktif' ELSE 'Tidak Aktif' END STATUS_AKTIF_INFO
				FROM pds_rekrutmen.DOKUMEN A
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY DOKUMEN_ID ASC ")
	{
		$str = "
				SELECT A.DOKUMEN_ID, A.NAMA, A.KETERANGAN, WAJIB, FORMAT, CASE WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, 
					STATUS_AKTIF, CASE WHEN STATUS_AKTIF = '1' THEN 'Aktif' ELSE 'Tidak Aktif' END STATUS_AKTIF_INFO
				FROM pds_rekrutmen.DOKUMEN A
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
				SELECT DOKUMEN_ID, NAMA, KETERANGAN
				FROM pds_rekrutmen.DOKUMEN
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(DOKUMEN_ID) AS ROWCOUNT FROM pds_rekrutmen.DOKUMEN A
		        WHERE DOKUMEN_ID IS NOT NULL ".$statement; 
		
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
		FROM pds_rekrutmen.DOKUMEN A
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
		$str = "SELECT COUNT(DOKUMEN_ID) AS ROWCOUNT FROM pds_rekrutmen.DOKUMEN
		        WHERE DOKUMEN_ID IS NOT NULL ".$statement; 
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
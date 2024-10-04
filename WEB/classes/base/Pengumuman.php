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
  * Entity-base class untuk mengimplementasikan tabel PENGUMUMAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Pengumuman extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pengumuman()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENGUMUMAN_ID", $this->getAdminNextId("PENGUMUMAN_ID","pds_rekrutmen.PENGUMUMAN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PENGUMUMAN (
				   PENGUMUMAN_ID, NAMA, KETERANGAN, LOWONGAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
 			  	VALUES (
				  ".$this->getField("PENGUMUMAN_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("LOWONGAN_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PENGUMUMAN_ID");
		//echo $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PENGUMUMAN
				SET    
					   NAMA           		= '".$this->getField("NAMA")."',
					   KETERANGAN      		= '".$this->getField("KETERANGAN")."',
					   LOWONGAN_ID			= ".$this->getField("LOWONGAN_ID").",
				  	   LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  PENGUMUMAN_ID     	= '".$this->getField("PENGUMUMAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PENGUMUMAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PENGUMUMAN_ID = ".$this->getField("PENGUMUMAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function update_file()
	{
		$str = "UPDATE pds_rekrutmen.PENGUMUMAN SET
				  LINK_FILE = '".$this->getField("LINK_FILE")."'
				WHERE PENGUMUMAN_ID = '".$this->getField("PENGUMUMAN_ID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PENGUMUMAN
                WHERE 
                  PENGUMUMAN_ID = ".$this->getField("PENGUMUMAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY PENGUMUMAN_ID ASC ")
	{
		$str = "
				SELECT PENGUMUMAN_ID, NAMA, KETERANGAN, LOWONGAN_ID, LINK_FILE, PUBLISH
				FROM pds_rekrutmen.PENGUMUMAN A
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY PENGUMUMAN_ID ASC ")
	{
		$str = "
				SELECT A.PENGUMUMAN_ID, A.NAMA, A.KETERANGAN, A.LOWONGAN_ID, A.LINK_FILE, A.PUBLISH, B.JABATAN_ID, B.KODE LOWONGAN_KODE, C.NAMA JABATAN_NAMA,
					CASE WHEN A.PUBLISH = '1' THEN 'Ya' ELSE 'Tidak' END PUBLISH_KETERANGAN,
					CONCAT(B.KODE, ' - ', C.NAMA) LOWONGAN_INFO
				FROM pds_rekrutmen.PENGUMUMAN A
				LEFT JOIN pds_rekrutmen.LOWONGAN B ON A.LOWONGAN_ID =  B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.JABATAN C ON B.JABATAN_ID = C.JABATAN_ID
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
				SELECT PENGUMUMAN_ID, NAMA, KETERANGAN
				FROM pds_rekrutmen.PENGUMUMAN
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
		$str = "SELECT COUNT(PENGUMUMAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PENGUMUMAN A
		        WHERE PENGUMUMAN_ID IS NOT NULL ".$statement; 
		
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
		FROM pds_rekrutmen.PENGUMUMAN A
		LEFT JOIN pds_rekrutmen.LOWONGAN B ON A.LOWONGAN_ID =  B.LOWONGAN_ID
		LEFT JOIN pds_rekrutmen.JABATAN C ON B.JABATAN_ID = C.JABATAN_ID
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
		$str = "SELECT COUNT(PENGUMUMAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PENGUMUMAN
		        WHERE PENGUMUMAN_ID IS NOT NULL ".$statement; 
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
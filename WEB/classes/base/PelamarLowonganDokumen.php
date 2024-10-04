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
  * Entity-base class untuk mengimplementasikan tabel pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarLowonganDokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowonganDokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_LOWONGAN_DOKUMEN_ID", $this->getNextId("PELAMAR_LOWONGAN_DOKUMEN_ID","pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN"));

		$str = "
					INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN (
					   PELAMAR_LOWONGAN_DOKUMEN_ID, PELAMAR_LOWONGAN_ID, TANGGAL, LOWONGAN_DOKUMEN_ID, LINK_FILE)
 			  	VALUES (
				  ".$this->getField("PELAMAR_LOWONGAN_DOKUMEN_ID").",
				  '".$this->getField("PELAMAR_LOWONGAN_ID")."',
				  CURRENT_DATE,
				  ".$this->getField("LOWONGAN_DOKUMEN_ID").",
				  '".$this->getField("LINK_FILE")."'
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_LOWONGAN_DOKUMEN_ID", $this->getNextId("PELAMAR_LOWONGAN_DOKUMEN_ID","pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN"));

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN (
					   PELAMAR_LOWONGAN_DOKUMEN_ID, PELAMAR_LOWONGAN_ID, TANGGAL, LOWONGAN_DOKUMEN_ID, STATUS)
 			  	VALUES (
				  ".$this->getField("PELAMAR_LOWONGAN_DOKUMEN_ID").",
				  '".$this->getField("PELAMAR_LOWONGAN_ID")."',
				  CURRENT_DATE,
				  ".$this->getField("LOWONGAN_DOKUMEN_ID").",
				  '".$this->getField("STATUS")."'
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN
				SET    
					   PELAMAR_LOWONGAN_ID         = '".$this->getField("PELAMAR_LOWONGAN_ID")."',
					   LOWONGAN_DOKUMEN_ID		   = ".$this->getField("LOWONGAN_DOKUMEN_ID")."
				WHERE  PELAMAR_LOWONGAN_DOKUMEN_ID = '".$this->getField("PELAMAR_LOWONGAN_DOKUMEN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateStatus()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN
				SET    
					   STATUS  = '".$this->getField("STATUS")."',
					   TANGGAL = CURRENT_DATE
				WHERE  PELAMAR_LOWONGAN_DOKUMEN_ID = '".$this->getField("PELAMAR_LOWONGAN_DOKUMEN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PELAMAR_LOWONGAN_DOKUMEN_ID = ".$this->getField("PELAMAR_LOWONGAN_DOKUMEN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN
                WHERE 
                  PELAMAR_LOWONGAN_DOKUMEN_ID = ".$this->getField("PELAMAR_LOWONGAN_DOKUMEN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT   PELAMAR_LOWONGAN_DOKUMEN_ID, PELAMAR_LOWONGAN_ID, TANGGAL, LOWONGAN_DOKUMEN_ID, LINK_FILE
 	 	 			FROM pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN A 
		 		WHERE 1 = 1
				"; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= " ORDER BY TANGGAL DESC";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
   	function selectByParamsPelamarLowongan($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.LOWONGAN_DOKUMEN_ID, A.LOWONGAN_ID, A.NAMA, A.KETERANGAN, D.LINK_FILE, A.WAJIB, PELAMAR_LOWONGAN_DOKUMEN_ID,
						CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, FORMAT, D.STATUS
				  FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				  LEFT JOIN pds_rekrutmen.LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN C ON C.LOWONGAN_ID = A.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN D ON D.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND C.PELAMAR_LOWONGAN_ID = D.PELAMAR_LOWONGAN_ID
				 WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPelamarLowonganCheck($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.LOWONGAN_DOKUMEN_ID, A.LOWONGAN_ID, A.NAMA, A.KETERANGAN, COALESCE(D.LINK_FILE, LAMPIRAN) LINK_FILE, A.WAJIB, PELAMAR_LOWONGAN_DOKUMEN_ID,
						CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, FORMAT, D.STATUS
				  FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				  LEFT JOIN pds_rekrutmen.LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN C ON C.LOWONGAN_ID = A.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN D ON D.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND C.PELAMAR_LOWONGAN_ID = D.PELAMAR_LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_DOKUMEN E ON A.DOKUMEN_ID = E.DOKUMEN_ID AND C.PELAMAR_ID = E.PELAMAR_ID
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
		
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_DOKUMEN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN
		        WHERE PELAMAR_LOWONGAN_DOKUMEN_ID IS NOT NULL ".$statement; 
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
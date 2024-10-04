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
  * Entity-base class untuk mengimplementasikan tabel JABATAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Sertifikat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Sertifikat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SERTIFIKAT_ID", $this->getNextId("SERTIFIKAT_ID","pds_rekrutmen.SERTIFIKAT")); 		

		$str = "
				INSERT INTO pds_rekrutmen.SERTIFIKAT
							(SERTIFIKAT_ID, KODE, NAMA, 
							 KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE
							)
					 VALUES ('".$this->getField("SERTIFIKAT_ID")."', '".$this->getField("KODE")."', '".$this->getField("NAMA")."',
					 		 '".$this->getField("KETERANGAN")."', '".$this->getField("LAST_CREATE_USER")."', ".$this->getField("LAST_CREATE_DATE")."
							)
				
				"; 
		$this->id = $this->getField("SERTIFIKAT_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.SERTIFIKAT
				   SET KODE = '".$this->getField("KODE")."',
					   NAMA = '".$this->getField("NAMA")."',
					   KETERANGAN = '".$this->getField("KETERANGAN")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  SERTIFIKAT_ID     = '".$this->getField("SERTIFIKAT_ID")."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.SERTIFIKAT
                WHERE 
                  SERTIFIKAT_ID = ".$this->getField("SERTIFIKAT_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY SERTIFIKAT_ID ASC ")
	{
		$str = "
				SELECT SERTIFIKAT_ID, KODE, NAMA, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE,
					   LAST_UPDATE_USER, LAST_UPDATE_DATE
				  FROM pds_rekrutmen.SERTIFIKAT A
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
				SELECT JABATAN_ID, NAMA, KODE, NO_URUT, KELAS, STATUS, pds_rekrutmen.AMBIL_STATUS_CHEKLIST(STATUS) STATUS_NAMA, pds_simpeg.AMBIL_STATUS_KELOMPOK_JABATAN(KELOMPOK) KELOMPOK
				FROM pds_rekrutmen.JABATAN
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
		$str = "SELECT COUNT(SERTIFIKAT_ID) AS ROWCOUNT FROM pds_rekrutmen.SERTIFIKAT A
		        WHERE SERTIFIKAT_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(SERTIFIKAT_ID) AS ROWCOUNT FROM pds_rekrutmen.SERTIFIKAT
		        WHERE SERTIFIKAT_ID IS NOT NULL ".$statement; 
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
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

  class PelamarLowonganTahapanNilai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowonganTahapanNilai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI
							(PELAMAR_ID, LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, 
							 TAHAPAN_TES_NILAI_ID, PENILAI_KE, NILAI, LAST_CREATE_USER, LAST_CREATE_DATE
							)
					 VALUES ('".$this->getField("PELAMAR_ID")."', '".$this->getField("LOWONGAN_ID")."', '".$this->getField("LOWONGAN_TAHAPAN_ID")."',
					 		 '".$this->getField("TAHAPAN_TES_NILAI_ID")."', '".$this->getField("PENILAI_KE")."', '".$this->getField("NILAI")."', 
							 '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
							)
				
				"; 
		$this->id = $this->getField("PELAMAR_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


	function deleteData()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI
                WHERE  
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND
                  LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND
                  LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 
				  
		$this->query = $str;
		//echo $str;
        return $this->execQuery($str);
    }
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY PELAMAR_ID, PENILAI_KE, TAHAPAN_TES_NILAI_ID ASC ")
	{
		$str = "
				SELECT PELAMAR_ID, LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, NILAI, PENILAI_KE, 
					   TAHAPAN_TES_NILAI_ID
				  FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI A
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

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI A
		        WHERE PELAMAR_ID IS NOT NULL ".$statement; 
		
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
	
	function getMaxByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT MAX(PENILAI_KE) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI A
		        WHERE PELAMAR_ID IS NOT NULL ".$statement; 
		
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
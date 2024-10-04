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
  * Entity-base class untuk mengimplementasikan tabel pds_rekrutmen.TAHAPAN_TES_NILAI.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class TahapanTesNilai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TahapanTesNilai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TAHAPAN_TES_NILAI_ID", $this->getAdminNextId("TAHAPAN_TES_NILAI_ID","pds_rekrutmen.TAHAPAN_TES_NILAI"));

		$str = "
				INSERT INTO pds_rekrutmen.TAHAPAN_TES_NILAI (
				   TAHAPAN_TES_NILAI_ID, TAHAPAN_TES_ID, NAMA, LAST_UPDATE_USER, LAST_UPDATE_DATE) 
 			  	VALUES (
				  ".$this->getField("TAHAPAN_TES_NILAI_ID").",
				  '".$this->getField("TAHAPAN_TES_ID")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.TAHAPAN_TES_NILAI
				SET    
					   NAMA	 				= '".$this->getField("NAMA")."',
					   LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  TAHAPAN_TES_NILAI_ID  		= '".$this->getField("TAHAPAN_TES_NILAI_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.TAHAPAN_TES_NILAI
                WHERE 
                  TAHAPAN_TES_NILAI_ID = ".$this->getField("TAHAPAN_TES_NILAI_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY TAHAPAN_TES_NILAI_ID ASC")
	{
		$str = "
					SELECT TAHAPAN_TES_NILAI_ID, A.TAHAPAN_TES_ID, A.NAMA, A.LAST_CREATE_USER,
					A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
					FROM pds_rekrutmen.TAHAPAN_TES_NILAI A
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

    function selectByParamsNilai($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY B.PENILAI_KE, A.TAHAPAN_TES_NILAI_ID ASC ")
	{
		$str = "
					SELECT A.TAHAPAN_TES_NILAI_ID, B.PENILAI_KE, A.NAMA, B.NILAI 
					FROM pds_rekrutmen.TAHAPAN_TES_NILAI A
					INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN_NILAI B ON A.TAHAPAN_TES_NILAI_ID = B.TAHAPAN_TES_NILAI_ID
					WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
        
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
					SELECT TAHAPAN_TES_NILAI_ID, A.TAHAPAN_TES_ID, A.NAMA, B.NAMA, A.LAST_CREATE_USER,
					A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
					FROM pds_rekrutmen.TAHAPAN_TES_NILAI A 
					LEFT JOIN pds_rekrutmen.PELAMAR B ON B.TAHAPAN_TES_ID = A.TAHAPAN_TES_ID
					WHERE 1=1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA DESC";
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TAHAPAN_TES_NILAI_ID) AS ROWCOUNT FROM pds_rekrutmen.TAHAPAN_TES_NILAI A
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
		$str = "SELECT COUNT(TAHAPAN_TES_NILAI_ID) AS ROWCOUNT FROM pds_rekrutmen.TAHAPAN_TES_NILAI A
				pds_rekrutmen.TAHAPAN_TES_NILAI B ON B.TAHAPAN_TES_ID = A.TAHAPAN_TES_ID
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
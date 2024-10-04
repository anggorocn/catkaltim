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
  * Entity-base class untuk mengimplementasikan tabel PELAMAR_SERTIFIKAT.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarSertifikat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarSertifikat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_SERTIFIKAT_ID", $this->getAdminNextId("PELAMAR_SERTIFIKAT_ID","pds_rekrutmen.PELAMAR_SERTIFIKAT"));

		$str = "
					INSERT INTO pds_rekrutmen.PELAMAR_SERTIFIKAT (
					   PELAMAR_SERTIFIKAT_ID, NAMA, PELAMAR_ID, TANGGAL_TERBIT, TANGGAL_KADALUARSA, GROUP_SERTIFIKAT, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE, SERTIFIKAT_ID)
 			  	VALUES (
				  ".$this->getField("PELAMAR_SERTIFIKAT_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("PELAMAR_ID")."',
				  ".$this->getField("TANGGAL_TERBIT").",
				  ".$this->getField("TANGGAL_KADALUARSA").",
				  '".$this->getField("GROUP_SERTIFIKAT")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("SERTIFIKAT_ID")."
				)"; 
		$this->id=$this->getField("PELAMAR_SERTIFIKAT_ID");
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_SERTIFIKAT
				SET    
				   NAMA= '".$this->getField("NAMA")."',
				   TANGGAL_TERBIT= ".$this->getField("TANGGAL_TERBIT").",
				   TANGGAL_KADALUARSA= ".$this->getField("TANGGAL_KADALUARSA").",
				   GROUP_SERTIFIKAT= '".$this->getField("GROUP_SERTIFIKAT")."',
				   KETERANGAN= '".$this->getField("KETERANGAN")."',
				   LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
				   LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
				   SERTIFIKAT_ID= ".$this->getField("SERTIFIKAT_ID")."
				WHERE PELAMAR_SERTIFIKAT_ID= '".$this->getField("PELAMAR_SERTIFIKAT_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_SERTIFIKAT
                WHERE 
                  PELAMAR_SERTIFIKAT_ID= ".$this->getField("PELAMAR_SERTIFIKAT_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function setuju()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_SERTIFIKAT_ID_GEN", $this->getNextId("PELAMAR_SERTIFIKAT_ID","pds_rekrutmen.PELAMAR_SERTIFIKAT")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_SERTIFIKAT (
				   PELAMAR_SERTIFIKAT_ID, NAMA, PELAMAR_ID, TANGGAL_TERBIT, TANGGAL_KADALUARSA, GROUP_SERTIFIKAT, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				SELECT ".$this->getField("PELAMAR_SERTIFIKAT_ID_GEN")." PELAMAR_SERTIFIKAT_ID, NAMA, PELAMAR_ID, TANGGAL_TERBIT, TANGGAL_KADALUARSA, GROUP_SERTIFIKAT, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE
				FROM pds_validasi.PELAMAR_SERTIFIKAT WHERE PELAMAR_SERTIFIKAT_ID = ".$this->getField("PELAMAR_SERTIFIKAT_ID").""; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_SERTIFIKAT_ID");
		
		if($this->execQuery($str))
		{
			$str = "DELETE FROM pds_validasi.PELAMAR_SERTIFIKAT
					WHERE 
					  PELAMAR_SERTIFIKAT_ID = ".$this->getField("PELAMAR_SERTIFIKAT_ID").""; 		
			return $this->execQuery($str);
		}
    }
	
	function tolak()
	{
        $str = "DELETE FROM pds_validasi.PELAMAR_SERTIFIKAT
                WHERE 
                  PELAMAR_SERTIFIKAT_ID = ".$this->getField("PELAMAR_SERTIFIKAT_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.NAMA ASC")
	{
		$str = "
					SELECT 
					PELAMAR_SERTIFIKAT_ID, PELAMAR_ID, 
					CASE	
						WHEN A.NAMA != '' THEN A.NAMA 
						WHEN A.NAMA = '' THEN B.NAMA 
					END NAMA, A.KETERANGAN, TANGGAL_TERBIT, TANGGAL_KADALUARSA, GROUP_SERTIFIKAT, A.SERTIFIKAT_ID
					FROM pds_rekrutmen.PELAMAR_SERTIFIKAT A
					LEFT JOIN pds_rekrutmen.SERTIFIKAT B ON B.SERTIFIKAT_ID = A.SERTIFIKAT_ID
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

   function selectByParamsValidasi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY NAMA ASC")
	{
		$str = "
					SELECT * FROM
					(
					SELECT 'Validasi' STATUS, 
					PELAMAR_SERTIFIKAT_ID, PELAMAR_ID, NAMA, KETERANGAN, TANGGAL_TERBIT, TANGGAL_KADALUARSA, GROUP_SERTIFIKAT
					FROM pds_validasi.PELAMAR_SERTIFIKAT A
					UNION
					SELECT 'Master' STATUS, 
					PELAMAR_SERTIFIKAT_ID, PELAMAR_ID, NAMA, KETERANGAN, TANGGAL_TERBIT, TANGGAL_KADALUARSA, GROUP_SERTIFIKAT
					FROM pds_rekrutmen.PELAMAR_SERTIFIKAT A
					) A 
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
					SELECT 
					PELAMAR_SERTIFIKAT_ID, PELAMAR_ID, SERTIFIKAT_PELAMAR_ID
					FROM pds_rekrutmen.PELAMAR_SERTIFIKAT A WHERE PELAMAR_SERTIFIKAT_ID IS NOT NULL
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
		$str = "SELECT COUNT(PELAMAR_SERTIFIKAT_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_SERTIFIKAT
		        WHERE PELAMAR_SERTIFIKAT_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(PELAMAR_SERTIFIKAT_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_SERTIFIKAT
		        WHERE PELAMAR_SERTIFIKAT_ID IS NOT NULL ".$statement; 
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
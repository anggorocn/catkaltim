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
  * Entity-base class untuk mengimplementasikan tabel PELAMAR_PENGALAMAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarPengalaman extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarPengalaman()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PENGALAMAN_ID", $this->getAdminNextId("PELAMAR_PENGALAMAN_ID","pds_rekrutmen.PELAMAR_PENGALAMAN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PENGALAMAN (
				   PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, DURASI, TAHUN) 
 			  	VALUES (
				  ".$this->getField("PELAMAR_PENGALAMAN_ID").",
				  ".$this->getField("PELAMAR_ID").",
				  '".$this->getField("JABATAN")."',
				  '".$this->getField("PERUSAHAAN")."',
				  '".$this->getField("DURASI")."',
				  ".$this->getField("TAHUN")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_PENGALAMAN_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_PENGALAMAN
				SET
					    PELAMAR_ID	= ".$this->getField("PELAMAR_ID").",
				        JABATAN     = '".$this->getField("JABATAN")."',
				  		PERUSAHAAN	= '".$this->getField("PERUSAHAAN")."',
				  		DURASI		= '".$this->getField("DURASI")."',
				  		TAHUN 		= '".$this->getField("TAHUN")."'
				WHERE  PELAMAR_PENGALAMAN_ID = '".$this->getField("PELAMAR_PENGALAMAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_PENGALAMAN
                WHERE 
                  PELAMAR_PENGALAMAN_ID = ".$this->getField("PELAMAR_PENGALAMAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function setuju()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PENGALAMAN_ID_GEN", $this->getNextId("PELAMAR_PENGALAMAN_ID","pds_rekrutmen.PELAMAR_PENGALAMAN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PENGALAMAN (
				   PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, DURASI, 
       				TANGGAL_MASUK, TAHUN) 
				SELECT ".$this->getField("PELAMAR_PENGALAMAN_ID_GEN")." PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, DURASI, 
       				TANGGAL_MASUK, TAHUN
				FROM pds_validasi.PELAMAR_PENGALAMAN WHERE PELAMAR_PENGALAMAN_ID = ".$this->getField("PELAMAR_PENGALAMAN_ID").""; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_PENGALAMAN_ID");
		
		if($this->execQuery($str))
		{
			$str = "DELETE FROM pds_validasi.PELAMAR_PENGALAMAN
					WHERE 
					  PELAMAR_PENGALAMAN_ID = ".$this->getField("PELAMAR_PENGALAMAN_ID").""; 		
			return $this->execQuery($str);
		}
    }
	
	function tolak()
	{
        $str = "DELETE FROM pds_validasi.PELAMAR_PENGALAMAN
                WHERE 
                  PELAMAR_PENGALAMAN_ID = ".$this->getField("PELAMAR_PENGALAMAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, COALESCE(DURASI, '0') DURASI, 
       			TANGGAL_MASUK, COALESCE(TAHUN, '0') TAHUN
				FROM pds_rekrutmen.PELAMAR_PENGALAMAN
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

    function selectByParamsValidasi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT * FROM 
				(
				SELECT 'Validasi' STATUS, PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, DURASI, 
       			TANGGAL_MASUK, TAHUN
				FROM pds_validasi.PELAMAR_PENGALAMAN
				UNION
				SELECT 'Master' STATUS, PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, DURASI, 
       			TANGGAL_MASUK, TAHUN
				FROM pds_rekrutmen.PELAMAR_PENGALAMAN
				) A 
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
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT PELAMAR_PENGALAMAN_ID, PELAMAR_ID, JABATAN, PERUSAHAAN, DURASI, 
       			TANGGAL_MASUK, TAHUN
				FROM pds_rekrutmen.PELAMAR_PENGALAMAN
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ".$order;
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_PENGALAMAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PENGALAMAN
		        WHERE PELAMAR_PENGALAMAN_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(PELAMAR_PENGALAMAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PENGALAMAN
		        WHERE PELAMAR_PENGALAMAN_ID IS NOT NULL ".$statement; 
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
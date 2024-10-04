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
  * Entity-base class untuk mengimplementasikan tabel PELAMAR_PELATIHAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarPelatihan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarPelatihan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PELATIHAN_ID", $this->getAdminNextId("PELAMAR_PELATIHAN_ID","pds_rekrutmen.PELAMAR_PELATIHAN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PELATIHAN (
				   PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN, 
				   NOMOR_LISENSI, JENIS_LISENSI, TANGGAL_MULAI, TANGGAL_SELESAI) 
 			  	VALUES (
				  ".$this->getField("PELAMAR_PELATIHAN_ID").",
				  ".$this->getField("PELAMAR_ID").",
				  '".$this->getField("JENIS")."',
				  '".$this->getField("JUMLAH")."',
				  '".$this->getField("WAKTU")."',
				  '".$this->getField("PELATIH")."',
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("NOMOR_LISENSI")."',
				  '".$this->getField("JENIS_LISENSI")."',
				  ".$this->getField("TANGGAL_MULAI").",
				  ".$this->getField("TANGGAL_SELESAI")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_PELATIHAN_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_PELATIHAN
				SET
					   PELAMAR_ID		= ".$this->getField("PELAMAR_ID").",
				  	   JENIS			= '".$this->getField("JENIS")."',
				       JUMLAH			= '".$this->getField("JUMLAH")."',
				  	   WAKTU			= '".$this->getField("WAKTU")."',
				  	   PELATIH			= '".$this->getField("PELATIH")."',
				  	   TAHUN			= '".$this->getField("TAHUN")."',
				  	   NOMOR_LISENSI	= '".$this->getField("NOMOR_LISENSI")."',
				  	   JENIS_LISENSI	= '".$this->getField("JENIS_LISENSI")."',
				  	   TANGGAL_MULAI	= ".$this->getField("TANGGAL_MULAI").",
				  	   TANGGAL_SELESAI	= ".$this->getField("TANGGAL_SELESAI")."
				WHERE  PELAMAR_PELATIHAN_ID     	= '".$this->getField("PELAMAR_PELATIHAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_PELATIHAN
                WHERE 
                  PELAMAR_PELATIHAN_ID = ".$this->getField("PELAMAR_PELATIHAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function setuju()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PELATIHAN_ID_GEN", $this->getNextId("PELAMAR_PELATIHAN_ID","pds_rekrutmen.PELAMAR_PELATIHAN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PELATIHAN (
				   PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN) 
				SELECT ".$this->getField("PELAMAR_PELATIHAN_ID_GEN")." PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN
				FROM pds_validasi.PELAMAR_PELATIHAN WHERE PELAMAR_PELATIHAN_ID = ".$this->getField("PELAMAR_PELATIHAN_ID").""; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_PELATIHAN_ID");
		
		if($this->execQuery($str))
		{
			$str = "DELETE FROM pds_validasi.PELAMAR_PELATIHAN
					WHERE 
					  PELAMAR_PELATIHAN_ID = ".$this->getField("PELAMAR_PELATIHAN_ID").""; 		
			return $this->execQuery($str);
		}
    }
	
	function tolak()
	{
        $str = "DELETE FROM pds_validasi.PELAMAR_PELATIHAN
                WHERE 
                  PELAMAR_PELATIHAN_ID = ".$this->getField("PELAMAR_PELATIHAN_ID").""; 
				  
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
				SELECT PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN,
					NOMOR_LISENSI, JENIS_LISENSI, TANGGAL_MULAI, TANGGAL_SELESAI
				FROM pds_rekrutmen.PELAMAR_PELATIHAN
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
				SELECT 'Validasi' STATUS, PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN
				FROM pds_validasi.PELAMAR_PELATIHAN
				UNION
				SELECT 'Master' STATUS, PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN
				FROM pds_rekrutmen.PELAMAR_PELATIHAN
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
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT PELAMAR_PELATIHAN_ID, PELAMAR_ID, JENIS, JUMLAH, WAKTU, PELATIH, TAHUN
				FROM pds_rekrutmen.PELAMAR_PELATIHAN
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY TANGGAL_AWAL DESC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_PELATIHAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PELATIHAN
		        WHERE PELAMAR_PELATIHAN_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(PELAMAR_PELATIHAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PELATIHAN
		        WHERE PELAMAR_PELATIHAN_ID IS NOT NULL ".$statement; 
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
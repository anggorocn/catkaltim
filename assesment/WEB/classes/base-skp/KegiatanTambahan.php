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
  * Entity-base class untuk mengimplementasikan tabel KEGIATAN_TAMBAHAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class KegiatanTambahan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KegiatanTambahan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KEGIATAN_TAMBAHAN_ID", $this->getNextId("KEGIATAN_TAMBAHAN_ID","KEGIATAN_TAMBAHAN")); 		

		$str = "
				INSERT INTO KEGIATAN_TAMBAHAN (
				    KEGIATAN_TAMBAHAN_ID, PEGAWAI_ID, TAHUN, BULAN, 
				    NAMA, KUANTITAS, KUANTITAS_SATUAN, 
					KUANTITAS_REALISASI, STATUS)  
 			  	VALUES (
				  ".$this->getField("KEGIATAN_TAMBAHAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("BULAN").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("KUANTITAS").",
  				  '".$this->getField("KUANTITAS_SATUAN")."',
				  ".$this->getField("KUANTITAS_REALISASI").",
				  '".$this->getField("STATUS")."'
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function insertBackup()
	{
        $str = "DELETE FROM KEGIATAN_TAMBAHAN_BACKUP
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." 
				  AND TAHUN = '".$this->getField("TAHUN")."'
				  AND BULAN = '".$this->getField("BULAN")."' "; 
		$this->execQuery($str);		  

		$str = "
				   INSERT INTO KEGIATAN_TAMBAHAN_BACKUP (
					   PEGAWAI_ID, TAHUN, BULAN, 
					   NAMA, KUANTITAS, KUANTITAS_SATUAN, 
					   KUANTITAS_REALISASI, STATUS) 
				   SELECT 
				   PEGAWAI_ID, TAHUN, BULAN, 
					   NAMA, KUANTITAS, KUANTITAS_SATUAN, 
					   KUANTITAS_REALISASI, STATUS
				   FROM KEGIATAN_TAMBAHAN
				   WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." 
				  AND TAHUN = '".$this->getField("TAHUN")."'
				  AND BULAN = '".$this->getField("BULAN")."' "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
		
	function insertPelaporan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KEGIATAN_TAMBAHAN_ID", $this->getNextId("KEGIATAN_TAMBAHAN_ID","KEGIATAN_TAMBAHAN")); 		

		$str = "
				INSERT INTO KEGIATAN_TAMBAHAN (
				    KEGIATAN_TAMBAHAN_ID, PEGAWAI_ID, TAHUN, BULAN,
				    NAMA, KUANTITAS, KUANTITAS_SATUAN)  
 			  	VALUES (
				  ".$this->getField("KEGIATAN_TAMBAHAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("BULAN").",
  				  '".$this->getField("NAMA")."',
				  ".$this->getField("KUANTITAS").",
  				  '".$this->getField("KUANTITAS_SATUAN")."'
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE KEGIATAN_TAMBAHAN
				SET    
						  PEGAWAI_ID	= ".$this->getField("PEGAWAI_ID").",
						  TAHUN			= ".$this->getField("TAHUN").",
						  BULAN			= ".$this->getField("BULAN").",
						  NAMA			= '".$this->getField("NAMA")."',
						  KUANTITAS		= ".$this->getField("KUANTITAS").",
						  KUANTITAS_SATUAN = '".$this->getField("KUANTITAS_SATUAN")."',
						  KUANTITAS_REALISASI = ".$this->getField("KUANTITAS_REALISASI").",
						  STATUS			  = '".$this->getField("STATUS")."'				   
				WHERE  KEGIATAN_TAMBAHAN_ID  = '".$this->getField("KEGIATAN_TAMBAHAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updatePelaporan()
	{
		$str = "
				UPDATE KEGIATAN_TAMBAHAN
				SET    
						  KUANTITAS_REALISASI = ".$this->getField("KUANTITAS_REALISASI").",
						  STATUS			  = '".$this->getField("STATUS")."'				   
				WHERE  KEGIATAN_TAMBAHAN_ID  = '".$this->getField("KEGIATAN_TAMBAHAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KEGIATAN_TAMBAHAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE KEGIATAN_TAMBAHAN_ID = ".$this->getField("KEGIATAN_TAMBAHAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM KEGIATAN_TAMBAHAN
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." 
				  AND TAHUN = '".$this->getField("TAHUN")."'
				  AND BULAN = '".$this->getField("BULAN")."' "; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.KEGIATAN_TAMBAHAN_ID DESC  ")
	{
		$str = "
					SELECT 
						   KEGIATAN_TAMBAHAN_ID, PEGAWAI_ID, TAHUN, BULAN,
						   NAMA, KUANTITAS, KUANTITAS_SATUAN, 
						   KUANTITAS_REALISASI, STATUS
					FROM KEGIATAN_TAMBAHAN A WHERE KEGIATAN_TAMBAHAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.KEGIATAN_TAMBAHAN_ID DESC ")
	{
		$str = "
					SELECT 
						   KEGIATAN_TAMBAHAN_ID, PEGAWAI_ID, TAHUN, BULAN,
						   NAMA, KUANTITAS, KUANTITAS_SATUAN, CONCAT(KUANTITAS, ' ', KUANTITAS_SATUAN) KUANTITAS_MERGE,
						   KUANTITAS_REALISASI, STATUS
                    FROM KEGIATAN_TAMBAHAN A WHERE KEGIATAN_TAMBAHAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
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
		$str = "	SELECT
						   KEGIATAN_TAMBAHAN_ID, PEGAWAI_ID, TAHUN, 
						   URUT, NAMA, AK, 
						   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
						   WAKTU, WAKTU_SATUAN, BIAYA, 
						   KUANTITAS_REALISASI, KUALITAS_REALISASI, WAKTU_REALISASI, 
						   BIAYA_REALISASI, PERHITUNGAN, NILAI_CAPAIAN, 
						   STATUS
					FROM KEGIATAN_TAMBAHAN A WHERE KEGIATAN_TAMBAHAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(KEGIATAN_TAMBAHAN_ID) AS ROWCOUNT FROM KEGIATAN_TAMBAHAN A
		        WHERE KEGIATAN_TAMBAHAN_ID IS NOT NULL AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian) ".$statement; 
		
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
		$str = "SELECT COUNT(KEGIATAN_TAMBAHAN_ID) AS ROWCOUNT FROM KEGIATAN_TAMBAHAN A
		        WHERE KEGIATAN_TAMBAHAN_ID IS NOT NULL ".$statement; 
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
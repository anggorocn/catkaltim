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
  * Entity-base class untuk mengimplementasikan tabel KEGIATAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Kegiatan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Kegiatan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KEGIATAN_ID", $this->getNextId("KEGIATAN_ID","KEGIATAN")); 		

		$str = "
				INSERT INTO KEGIATAN (
				    KEGIATAN_ID, PEGAWAI_ID, TAHUN, BULAN, 
				    URUT, NAMA, AK, 
				    KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
				    WAKTU, WAKTU_SATUAN, BIAYA, 
				    KUANTITAS_REALISASI, KUALITAS_REALISASI, WAKTU_REALISASI, 
				    BIAYA_REALISASI, PERHITUNGAN, NILAI_CAPAIAN, STATUS)  
 			  	VALUES (
				  ".$this->getField("KEGIATAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("BULAN").",
				  ".$this->getField("URUT").",
  				  '".$this->getField("NAMA")."',
				  ".$this->getField("AK").",
				  ".$this->getField("KUANTITAS").",
  				  '".$this->getField("KUANTITAS_SATUAN")."',
				  ".$this->getField("KUALITAS").",
				  ".$this->getField("WAKTU").",
  				  '".$this->getField("WAKTU_SATUAN")."',
				  ".$this->getField("BIAYA").",
				  ".$this->getField("KUANTITAS_REALISASI").",
				  ".$this->getField("KUALITAS_REALISASI").",
				  ".$this->getField("WAKTU_REALISASI").",
				  ".$this->getField("BIAYA_REALISASI").",
				  ".$this->getField("PERHITUNGAN").",
				  ".$this->getField("NILAI_CAPAIAN").",
  				  '".$this->getField("STATUS")."'
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	


	function insertBackup()
	{
        $str = "DELETE FROM KEGIATAN_BACKUP
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." 
				  AND TAHUN = '".$this->getField("TAHUN")."'
				  AND BULAN = '".$this->getField("BULAN")."' "; 
		$this->execQuery($str);		  

		$str = "
				   INSERT INTO KEGIATAN_BACKUP (
				   PEGAWAI_ID, TAHUN, BULAN, 
				   URUT, NAMA, AK, 
				   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
				   WAKTU, WAKTU_SATUAN, BIAYA) 
				   SELECT 
				   PEGAWAI_ID, TAHUN, BULAN, 
				   URUT, NAMA, AK, 
				   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
				   WAKTU, WAKTU_SATUAN, BIAYA
				   FROM KEGIATAN
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
		$this->setField("KEGIATAN_ID", $this->getNextId("KEGIATAN_ID","KEGIATAN")); 		

		$str = "
				INSERT INTO KEGIATAN (
				    KEGIATAN_ID, PEGAWAI_ID, TAHUN, BULAN,
				    URUT, NAMA, AK, 
				    KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
				    WAKTU, WAKTU_SATUAN, BIAYA)  
 			  	VALUES (
				  ".$this->getField("KEGIATAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("BULAN").",
				  ".$this->getField("URUT").",
  				  '".$this->getField("NAMA")."',
				  ".$this->getField("AK").",
				  ".$this->getField("KUANTITAS").",
  				  '".$this->getField("KUANTITAS_SATUAN")."',
				  ".$this->getField("KUALITAS").",
				  ".$this->getField("WAKTU").",
  				  '".$this->getField("WAKTU_SATUAN")."',
				  ".$this->getField("BIAYA")."
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE KEGIATAN
				SET    
						  PEGAWAI_ID	= ".$this->getField("PEGAWAI_ID").",
						  TAHUN			= ".$this->getField("TAHUN").",
						  URUT			= ".$this->getField("URUT").",
						  NAMA			= '".$this->getField("NAMA")."',
						  AK			= ".$this->getField("AK").",
						  KUANTITAS		= ".$this->getField("KUANTITAS").",
						  KUANTITAS_SATUAN = '".$this->getField("KUANTITAS_SATUAN")."',
						  KUALITAS		= ".$this->getField("KUALITAS").",
						  WAKTU			= ".$this->getField("WAKTU").",
						  WAKTU_SATUAN	= '".$this->getField("WAKTU_SATUAN")."',
						  BIAYA			= ".$this->getField("BIAYA").",
						  KUANTITAS_REALISASI = ".$this->getField("KUANTITAS_REALISASI").",
						  KUALITAS_REALISASI  = ".$this->getField("KUALITAS_REALISASI").",
						  WAKTU_REALISASI	  = ".$this->getField("WAKTU_REALISASI").",
						  BIAYA_REALISASI	  = ".$this->getField("BIAYA_REALISASI").",
						  PERHITUNGAN		  = ".$this->getField("PERHITUNGAN").",
						  NILAI_CAPAIAN		  = ".$this->getField("NILAI_CAPAIAN").",
						  STATUS			  = '".$this->getField("STATUS")."'				   
				WHERE  KEGIATAN_ID  = '".$this->getField("KEGIATAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updatePelaporan()
	{
		$str = "
				UPDATE KEGIATAN
				SET    
						  KUANTITAS_REALISASI = ".$this->getField("KUANTITAS_REALISASI").",
						  KUALITAS_REALISASI  = ".$this->getField("KUALITAS_REALISASI").",
						  WAKTU_REALISASI	  = ".$this->getField("WAKTU_REALISASI").",
						  BIAYA_REALISASI	  = ".$this->getField("BIAYA_REALISASI").",
						  STATUS			  = '".$this->getField("STATUS")."'				   
				WHERE  KEGIATAN_ID = ".$this->getField("KEGIATAN_ID")."

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KEGIATAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE KEGIATAN_ID = ".$this->getField("KEGIATAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM KEGIATAN
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.URUT ASC ")
	{
		$str = "
					SELECT 
						   KEGIATAN_ID, PEGAWAI_ID, TAHUN, 
						   URUT, NAMA, AK, 
						   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
						   WAKTU, WAKTU_SATUAN, BIAYA, 
                           KUANTITAS_REALISASI, 
						   COALESCE(KUALITAS_REALISASI, CASE WHEN KUANTITAS = 0 THEN 0 ELSE ROUND((COALESCE(KUANTITAS_REALISASI, 0) / COALESCE(KUANTITAS, 1)) * KUALITAS, 2) END) KUALITAS_REALISASI, WAKTU_REALISASI, 
                           BIAYA_REALISASI, PERHITUNGAN, NILAI_CAPAIAN, 
                           STATUS
                    FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsCapaian($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.URUT ASC ")
	{
		$str = "
					SELECT 
						   KEGIATAN_ID, PEGAWAI_ID, TAHUN, 
						   URUT, NAMA, AK, 
						   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
						   WAKTU, WAKTU_SATUAN, BIAYA, 
                           KUANTITAS_REALISASI, 
						   COALESCE(KUALITAS_REALISASI, CASE WHEN KUANTITAS = 0 THEN 0 ELSE ROUND((COALESCE(KUANTITAS_REALISASI, 0) / COALESCE(KUANTITAS, 1)) * KUALITAS, 2) END) KUALITAS_REALISASI, WAKTU_REALISASI, 
                           BIAYA_REALISASI, PERHITUNGAN, NILAI_CAPAIAN, 
                           STATUS, HITUNG_PERHITUNGAN_CAPAIAN(A.KEGIATAN_ID) CAPAIAN
                    FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.KEGIATAN_ID DESC ")
	{
		$str = "
					SELECT 
						   KEGIATAN_ID, PEGAWAI_ID, TAHUN, 
						   URUT, NAMA, AK, 
						   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, CONCAT(KUANTITAS, ' ', KUANTITAS_SATUAN) KUANTITAS_MERGE, 
						   WAKTU, WAKTU_SATUAN, BIAYA, CONCAT(WAKTU, ' ', WAKTU_SATUAN) WAKTU_MERGE,
						   KUANTITAS_REALISASI, KUALITAS_REALISASI, WAKTU_REALISASI, 
                           BIAYA_REALISASI, PERHITUNGAN, NILAI_CAPAIAN, STATUS
                    FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsMonitoringPeriode($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY BULAN, TAHUN ASC ")
	{
		$str = "
					SELECT   A.PEGAWAI_ID, BULAN, TAHUN, CONCAT(LPAD(BULAN, 2, '0'), TAHUN) PERIODE,
					 COUNT(1) AS JUMLAH_ENTRI,
					 COALESCE ((SELECT CASE
										  WHEN STATUS = 'S'
											 THEN 'DISETUJUI'
										  WHEN STATUS = 'R'
											 THEN 'REVISI'
										  WHEN STATUS = 'P'
											 THEN 'POSTING'
										  WHEN STATUS = 'V'
											 THEN 'VALIDASI PENCAPAIAN'
										  WHEN STATUS = 'F'
											 THEN 'FINAL'
									   END
								  FROM KEGIATAN_PERSETUJUAN X
								 WHERE X.PEGAWAI_ID = A.PEGAWAI_ID
								   AND X.TAHUN = A.TAHUN
								   AND X.BULAN = A.BULAN),
							   'BELUM DIPOSTING'
							  ) PERSETUJUAN
                    FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY PEGAWAI_ID, BULAN, TAHUN ".$order;

		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    

	function selectByParamsMonitoringPeriodePencapaian($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY BULAN, TAHUN ASC ")
	{
		$str = "
					SELECT   A.PEGAWAI_ID, BULAN, TAHUN, CONCAT(LPAD(BULAN, 2, '0'), TAHUN) PERIODE,
					 COUNT(1) AS JUMLAH_ENTRI,
                              (SELECT COUNT(1) FROM KEGIATAN X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID
								   AND X.TAHUN = A.TAHUN
								   AND X.BULAN = A.BULAN
                                   AND X.KUANTITAS_REALISASI IS NULL) BELUM_TERISI,
								   COALESCE ((SELECT CASE
										  WHEN STATUS = 'S'
											 THEN 'PROSES ENTRI'
										  WHEN STATUS = 'V'
											 THEN 'VALIDASI'
										  WHEN STATUS = 'F'
											 THEN 'FINAL'
									   END
								  FROM KEGIATAN_PERSETUJUAN X
								 WHERE X.PEGAWAI_ID = A.PEGAWAI_ID
								   AND X.TAHUN = A.TAHUN
								   AND X.BULAN = A.BULAN),
							   'PROSES ENTRI'
							  ) PERSETUJUAN
                    FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian)
                    AND EXISTS (SELECT 1 FROM KEGIATAN_PERSETUJUAN X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID
								   AND X.TAHUN = A.TAHUN
								   AND X.BULAN = A.BULAN
                                   AND X.STATUS IN ('S', 'V', 'F'))
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY PEGAWAI_ID, BULAN, TAHUN ".$order;

		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
		    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT
						   KEGIATAN_ID, PEGAWAI_ID, TAHUN, 
						   URUT, NAMA, AK, 
						   KUANTITAS, KUANTITAS_SATUAN, KUALITAS, 
						   WAKTU, WAKTU_SATUAN, BIAYA, 
						   KUANTITAS_REALISASI, KUALITAS_REALISASI, WAKTU_REALISASI, 
						   BIAYA_REALISASI, PERHITUNGAN, NILAI_CAPAIAN, 
						   STATUS
					FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL
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
		$str = "SELECT COUNT(KEGIATAN_ID) AS ROWCOUNT FROM KEGIATAN A
		        WHERE KEGIATAN_ID IS NOT NULL AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian) ".$statement; 
		
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

    function getCountByParamsMonitoringPeriode($paramsArray=array(), $statement="")
	{
		$str = "
		
				SELECT COUNT(1) AS ROWCOUNT FROM 
				(SELECT A.PEGAWAI_ID
                 FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian) ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " GROUP BY PEGAWAI_ID, BULAN, TAHUN) A ";
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsMonitoringPeriodePencapaian($paramsArray=array(), $statement="")
	{
		$str = "
		
				SELECT COUNT(1) AS ROWCOUNT FROM 
				(SELECT A.PEGAWAI_ID
                 FROM KEGIATAN A WHERE KEGIATAN_ID IS NOT NULL  AND TAHUN = (SELECT MAX(TAHUN) FROM periode_penilaian) 
				 AND EXISTS (SELECT 1 FROM KEGIATAN_PERSETUJUAN X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID
								   AND X.TAHUN = A.TAHUN
								   AND X.BULAN = A.BULAN
                                   AND X.STATUS = 'S')".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " GROUP BY PEGAWAI_ID, BULAN, TAHUN) A ";
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
		
    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(KEGIATAN_ID) AS ROWCOUNT FROM KEGIATAN A
		        WHERE KEGIATAN_ID IS NOT NULL ".$statement; 
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
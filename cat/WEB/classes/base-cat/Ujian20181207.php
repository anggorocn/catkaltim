<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Ujian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Ujian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_ID", $this->getNextId("UJIAN_ID","cat.UJIAN")); 

		$str = "INSERT INTO cat.UJIAN (
				   UJIAN_ID, TGL_MULAI, TGL_SELESAI, STATUS, NILAI_LULUS, BATAS_WAKTU_MENIT, 
				   LOWONGAN_ID, LOWONGAN_TAHAPAN_ID,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("TGL_MULAI").",
				  ".$this->getField("TGL_SELESAI").",
				  '".$this->getField("STATUS")."',
				  ".$this->getField("NILAI_LULUS").",
				  ".$this->getField("BATAS_WAKTU_MENIT").",
				  ".$this->getField("LOWONGAN_ID").",
				  ".$this->getField("LOWONGAN_TAHAPAN_ID").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_ID");
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN SET
				  TGL_MULAI					= ".$this->getField("TGL_MULAI").",
				  TGL_SELESAI				= ".$this->getField("TGL_SELESAI").",
				  STATUS					= '".$this->getField("STATUS")."',
				  NILAI_LULUS				= ".$this->getField("NILAI_LULUS").",
				  BATAS_WAKTU_MENIT			= ".$this->getField("BATAS_WAKTU_MENIT").",
				  LOWONGAN_ID= ".$this->getField("LOWONGAN_ID").",
				  LOWONGAN_TAHAPAN_ID= ".$this->getField("LOWONGAN_TAHAPAN_ID").",
				  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_ID 				= ".$this->getField("UJIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateWaktu()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN SET
				  BATAS_WAKTU_MENIT= ".$this->getField("BATAS_WAKTU_MENIT").",
				  LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_ID= ".$this->getField("UJIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.UJIAN
                WHERE 
                  UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_ID, TGL_MULAI, TGL_SELESAI, 
							  CASE WHEN  STATUS='0' THEN 'Belum Selesai' 
								   WHEN STATUS='1' THEN 'Sudah Selesai'
							  END STATUS_UJIAN, STATUS, NILAI_LULUS, BATAS_WAKTU_MENIT, 
						LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER
				 FROM cat.UJIAN
				 WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsLowonganUjian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "SELECT A.LOWONGAN_ID, B.KODE || ' - ' || C.NAMA LOWONGAN_INFO, TGL_MULAI
				FROM cat.UJIAN A
				INNER JOIN pds_rekrutmen.LOWONGAN B ON A.LOWONGAN_ID = B.LOWONGAN_ID
				LEFT JOIN pds_rekrutmen.JABATAN C ON B.JABATAN_ID = C.JABATAN_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT 
					A.UJIAN_ID, A.TGL_MULAI, A.TGL_SELESAI
					, CASE A.STATUS WHEN '0' THEN 'Sudah Selesai' WHEN '1' THEN 'Belum Selesai' END STATUS_UJIAN, A.STATUS
					, CASE A.STATUS WHEN '0' THEN 'Belum Selesai' WHEN '1' THEN 'Sudah Selesai' END STATUS_UJIANBAK
					, A.NILAI_LULUS, A.BATAS_WAKTU_MENIT
					, A.LAST_CREATE_DATE, A.LAST_CREATE_USER, A.LAST_UPDATE_DATE, A.LAST_UPDATE_USER
					, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH_PEGAWAI
				 FROM cat.UJIAN A
				 LEFT JOIN
				 (
					SELECT UJIAN_ID, COUNT(PEGAWAI_ID) JUMLAH_PEGAWAI FROM cat.UJIAN_PEGAWAI_DAFTAR GROUP BY UJIAN_ID
				 ) B ON A.UJIAN_ID = B.UJIAN_ID
				 WHERE 1=1 ";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT A.UJIAN_ID, A.TGL_MULAI, A.TGL_SELESAI, A.STATUS, A.NILAI_LULUS, A.BATAS_WAKTU_MENIT
				FROM cat.UJIAN A
				INNER JOIN cat.ujian_pegawai_daftar B ON A.UJIAN_ID = B.UJIAN_ID
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
	
	function selectByParamsPegawaiLog($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT A.UJIAN_ID, A.TGL_MULAI, A.TGL_SELESAI, A.STATUS, A.NILAI_LULUS, A.BATAS_WAKTU_MENIT
				FROM cat.UJIAN A
				INNER JOIN cat.ujian_pegawai_daftar B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.UJIAN_TAHAP C ON A.UJIAN_ID = C.UJIAN_ID
				LEFT JOIN cat.UJIAN_TAHAP_PEGAWAI UTP ON C.TIPE_UJIAN_ID = UTP.TIPE_UJIAN_ID AND B.PEGAWAI_ID = UTP.PEGAWAI_ID AND C.UJIAN_TAHAP_ID = UTP.UJIAN_TAHAP_ID
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
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT UJIAN_ID, TGL_MULAI, TGL_SELESAI, STATUS, NILAI_LULUS, BATAS_WAKTU_MENIT, 
       					LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER
				FROM UJIAN WHERE UJIAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsMonitoring($paramsArray=array(), $statement= "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM cat.UJIAN A
		LEFT JOIN
		(
			SELECT UJIAN_ID, COUNT(PEGAWAI_ID) JUMLAH_PEGAWAI FROM cat.UJIAN_PEGAWAI_DAFTAR GROUP BY UJIAN_ID
		) B ON A.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsLowonganUjian($paramsArray=array(), $statement= "")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM cat.UJIAN A
		INNER JOIN pds_rekrutmen.LOWONGAN B ON A.LOWONGAN_ID = B.LOWONGAN_ID
		LEFT JOIN pds_rekrutmen.JABATAN C ON B.JABATAN_ID = C.JABATAN_ID
		WHERE 1=1 ".$statement; 
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
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_ID) AS ROWCOUNT FROM cat.UJIAN WHERE UJIAN_ID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_ID) AS ROWCOUNT FROM UJIAN WHERE UJIAN_ID IS NOT NULL "; 
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
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

  class UjianTahap extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianTahap()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_TAHAP_ID", $this->getNextId("UJIAN_TAHAP_ID","cat.UJIAN_TAHAP")); 

		$str = "INSERT INTO cat.UJIAN_TAHAP (
				   UJIAN_TAHAP_ID, UJIAN_ID, TIPE_UJIAN_ID, BOBOT,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_TAHAP_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("TIPE_UJIAN_ID").",
				  ".$this->getField("BOBOT").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_TAHAP SET
				  UJIAN_ID				= ".$this->getField("UJIAN_ID").",
				  TIPE_UJIAN_ID			= ".$this->getField("TIPE_UJIAN_ID").",
				  BOBOT					= ".$this->getField("BOBOT").",
				  MENIT_SOAL= ".$this->getField("MENIT_SOAL").",
				  LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_TAHAP_ID 	= ".$this->getField("UJIAN_TAHAP_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertJumlahSoal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_TAHAP_ID", $this->getNextId("UJIAN_TAHAP_ID","cat.UJIAN_TAHAP")); 

		$str = "INSERT INTO cat.UJIAN_TAHAP (
				   UJIAN_TAHAP_ID, UJIAN_ID, TIPE_UJIAN_ID, BOBOT, MENIT_SOAL, JUMLAH_SOAL_UJIAN_TAHAP,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_TAHAP_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("TIPE_UJIAN_ID").",
				  ".$this->getField("BOBOT").",
				  ".$this->getField("MENIT_SOAL").",
				  ".$this->getField("JUMLAH_SOAL_UJIAN_TAHAP").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateJumlahSoal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_TAHAP SET
				  UJIAN_ID				= ".$this->getField("UJIAN_ID").",
				  TIPE_UJIAN_ID			= ".$this->getField("TIPE_UJIAN_ID").",
				  BOBOT= ".$this->getField("BOBOT").",
				  MENIT_SOAL= ".$this->getField("MENIT_SOAL").",
				  JUMLAH_SOAL_UJIAN_TAHAP= ".$this->getField("JUMLAH_SOAL_UJIAN_TAHAP").",
				  LAST_UPDATE_DATE		= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER		= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_TAHAP_ID 	= ".$this->getField("UJIAN_TAHAP_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
		$str1= "DELETE FROM cat.UJIAN_BANK_SOAL
                WHERE 
                  UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID")."";
		$this->query = $str1;
        $this->execQuery($str1);
		
        $str = "DELETE FROM cat.UJIAN_TAHAP
                WHERE 
                  UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="")
	{
		$str = "SELECT UJIAN_TAHAP_ID, UJIAN_ID, TIPE_UJIAN_ID, BOBOT
				FROM cat.UJIAN_TAHAP WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="ORDER BY ID ASC")
	{
		$str = "SELECT A.UJIAN_TAHAP_ID, A.UJIAN_ID, A.TIPE_UJIAN_ID, BOBOT, TIPE
				, TIPE || ' (' || BOBOT || ')' TIPE_INFO, JUMLAH_SOAL
				, A.JUMLAH_SOAL_UJIAN_TAHAP, A.MENIT_SOAL
				, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' OR B.ID LIKE '07%' THEN 1 ELSE 0 END TIPE_READONLY
				, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' THEN 1 ELSE 0 END TIPE_RESET
				, B.ID, B.PARENT_ID, COALESCE(D.STATUS_ANAK,0) STATUS_ANAK
				FROM cat.UJIAN_TAHAP A
				INNER JOIN (SELECT LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, UJIAN_ID FROM cat.UJIAN) UJL ON UJL.UJIAN_ID = A.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				LEFT JOIN (SELECT UJIAN_ID, UJIAN_TAHAP_ID, COUNT(1) JUMLAH_SOAL FROM cat.UJIAN_BANK_SOAL GROUP BY UJIAN_ID, UJIAN_TAHAP_ID) C ON A.UJIAN_ID = C.UJIAN_ID AND A.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
				LEFT JOIN
				(
					SELECT
					A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
					FROM cat.TIPE_UJIAN A
					WHERE 1=1
					GROUP BY A.PARENT_ID
				) D ON B.ID = D.ID_ROW
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsStatusMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $pegawaiid="", $ujiantahapid="", $order="ORDER BY ID ASC")
	{
		$str = "SELECT A.UJIAN_TAHAP_ID, A.UJIAN_ID, A.TIPE_UJIAN_ID, BOBOT, TIPE
				, TIPE || ' (' || BOBOT || ')' TIPE_INFO, JUMLAH_SOAL
				, A.JUMLAH_SOAL_UJIAN_TAHAP, A.MENIT_SOAL
				, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' OR B.ID LIKE '07%' THEN 1 ELSE 0 END TIPE_READONLY
				, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' THEN 1 ELSE 0 END TIPE_RESET
				, B.ID, B.PARENT_ID, COALESCE(D.STATUS_ANAK,0) STATUS_ANAK
				, E.STATUS STATUS_TAHAP_UJIAN
				, F.STATUS_UJIAN, F.STATUS_SELESAI
				FROM cat.UJIAN_TAHAP A
				INNER JOIN (SELECT LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, UJIAN_ID FROM cat.UJIAN) UJL ON UJL.UJIAN_ID = A.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				LEFT JOIN (SELECT UJIAN_ID, UJIAN_TAHAP_ID, COUNT(1) JUMLAH_SOAL FROM cat.UJIAN_BANK_SOAL GROUP BY UJIAN_ID, UJIAN_TAHAP_ID) C ON A.UJIAN_ID = C.UJIAN_ID AND A.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
				LEFT JOIN
				(
					SELECT
					A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
					FROM cat.TIPE_UJIAN A
					WHERE 1=1
					GROUP BY A.PARENT_ID
				) D ON B.ID = D.ID_ROW
				LEFT JOIN
				(
					SELECT 
					UJIAN_TAHAP_STATUS_UJIAN_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, STATUS
					FROM cat.UJIAN_TAHAP_STATUS_UJIAN WHERE 1=1
					AND PEGAWAI_ID = ".$pegawaiid." AND UJIAN_TAHAP_ID = ".$ujiantahapid."
				) E ON A.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
				INNER JOIN
				(
					SELECT UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_ID, PEGAWAI_ID, STATUS_SETUJU, STATUS_LOGIN, STATUS_SETUJU, TANGGAL, STATUS_UJIAN, STATUS_SELESAI
					FROM cat.UJIAN_PEGAWAI_DAFTAR A
					WHERE 1=1
					AND A.PEGAWAI_ID = ".$pegawaiid."
				) F ON A.UJIAN_ID = F.UJIAN_ID
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringHasil($paramsArray=array(),$limit=-1,$from=-1, $pelamarId='', $statement='', $order="ORDER BY ID ASC")
	{
		$str = "SELECT A.UJIAN_TAHAP_ID, A.UJIAN_ID, A.TIPE_UJIAN_ID, BOBOT, TIPE, TIPE || ' (' || BOBOT || ')' TIPE_INFO, JUMLAH_SOAL
				, A.JUMLAH_SOAL_UJIAN_TAHAP, A.MENIT_SOAL
				, CASE WHEN B.ID LIKE '01%' OR B.ID LIKE '02%' OR B.ID LIKE '07%' THEN 1 ELSE 0 END TIPE_READONLY
				, B.ID, B.PARENT_ID, COALESCE(D.STATUS_ANAK,0) STATUS_ANAK, COALESCE(JUMLAH_SELESAI,0) JUMLAH_SELESAI, COALESCE(JUMLAH_SELESAI_STATUS,0) JUMLAH_SELESAI_STATUS
				, COALESCE(JUMLAH_SELESAI_UJIAN, 0) JUMLAH_SELESAI_UJIAN
				FROM cat.UJIAN_TAHAP A
				INNER JOIN (SELECT LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, UJIAN_ID FROM cat.UJIAN) UJL ON UJL.UJIAN_ID = A.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				LEFT JOIN (SELECT UJIAN_ID, UJIAN_TAHAP_ID, COUNT(1) JUMLAH_SOAL FROM cat.UJIAN_BANK_SOAL GROUP BY UJIAN_ID, UJIAN_TAHAP_ID) C ON A.UJIAN_ID = C.UJIAN_ID AND A.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
				LEFT JOIN
				(
					SELECT
					A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
					FROM cat.TIPE_UJIAN A
					WHERE 1=1
					GROUP BY A.PARENT_ID
				) D ON B.ID = D.ID_ROW
				LEFT JOIN
				(
					SELECT UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, COUNT(1) JUMLAH_SELESAI
					FROM
					(
						SELECT UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, SUM(1) JUMLAH_SELESAI, BANK_SOAL_ID 
						FROM cat.UJIAN_PEGAWAI
						WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
						GROUP BY UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, BANK_SOAL_ID
					) A
					GROUP BY UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID
				) E ON A.UJIAN_ID = E.UJIAN_ID AND A.UJIAN_TAHAP_ID = E.UJIAN_TAHAP_ID AND CAST(E.PEGAWAI_ID AS TEXT) = '".$pelamarId."'
				LEFT JOIN
				(
					SELECT UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, COUNT(1) JUMLAH_SELESAI_STATUS
					FROM cat.UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK A
					GROUP BY UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID
				) F ON A.UJIAN_ID = F.UJIAN_ID AND A.UJIAN_TAHAP_ID = F.UJIAN_TAHAP_ID AND CAST(F.PEGAWAI_ID AS TEXT) = '".$pelamarId."'
				LEFT JOIN
				(
					SELECT UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, COUNT(1) JUMLAH_SELESAI_UJIAN
					FROM cat.UJIAN_TAHAP_STATUS_UJIAN A
					GROUP BY UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID
				) G ON A.UJIAN_ID = G.UJIAN_ID AND A.UJIAN_TAHAP_ID = G.UJIAN_TAHAP_ID AND CAST(G.PEGAWAI_ID AS TEXT) = '".$pelamarId."'
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsFilterTahapLowongan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="ORDER BY ID ASC")
	{
		$str = "SELECT ID, A.TIPE_UJIAN_ID, TIPE
				FROM cat.UJIAN_TAHAP A
				INNER JOIN (SELECT LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, UJIAN_ID FROM cat.UJIAN) UJL ON UJL.UJIAN_ID = A.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				LEFT JOIN (SELECT UJIAN_ID, UJIAN_TAHAP_ID, COUNT(1) JUMLAH_SOAL FROM cat.UJIAN_BANK_SOAL GROUP BY UJIAN_ID, UJIAN_TAHAP_ID) C ON A.UJIAN_ID = C.UJIAN_ID AND A.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
				LEFT JOIN
				(
					SELECT
					A.PARENT_ID ID_ROW, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_ANAK
					FROM cat.TIPE_UJIAN A
					WHERE 1=1
					GROUP BY A.PARENT_ID
				) D ON B.ID = D.ID_ROW
				WHERE 1=1
				".$statement."
				GROUP BY ID, A.TIPE_UJIAN_ID, TIPE "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawaiMenu($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="")
	{
		$str = "SELECT A.UJIAN_TAHAP_ID, A.UJIAN_ID, A.TIPE_UJIAN_ID, A.BOBOT, B.TIPE
				, B.TIPE || ' (' || A.BOBOT || ')' TIPE_INFO,
				C.JUMLAH_SOAL, A.JUMLAH_SOAL_UJIAN_TAHAP, A.MENIT_SOAL
				FROM cat.UJIAN_PEGAWAI_DAFTAR A1
				LEFT JOIN pds_rekrutmen.PELAMAR B1 ON A1.PEGAWAI_ID=B1.PELAMAR_ID
				INNER JOIN cat.UJIAN UJ ON A1.UJIAN_ID = UJ.UJIAN_ID
				INNER JOIN cat.UJIAN_TAHAP A ON A.UJIAN_ID = A1.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				LEFT JOIN (SELECT UJIAN_ID, UJIAN_TAHAP_ID, COUNT(1) JUMLAH_SOAL FROM cat.UJIAN_BANK_SOAL GROUP BY UJIAN_ID, UJIAN_TAHAP_ID) C ON A.UJIAN_ID = C.UJIAN_ID AND A.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawaiTahap($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT A.UJIAN_ID, A.TGL_MULAI, A.TGL_SELESAI, A.STATUS, A.NILAI_LULUS, A.BATAS_WAKTU_MENIT, UJIAN_TAHAP_ID
				, TIPE || ' (' || BOBOT || ')' TIPE_INFO, TIPE, E.TIPE_INFO
				, C.MENIT_SOAL, C.TIPE_UJIAN_ID, LENGTH(PARENT_ID) LENGTH_PARENT
				, (SELECT 1 FROM cat.UJIAN_TAHAP_STATUS_UJIAN X WHERE 1=1 AND X.UJIAN_ID = A.UJIAN_ID AND X.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID AND X.PEGAWAI_ID = B.PEGAWAI_ID) TIPE_STATUS
				FROM cat.UJIAN A
				INNER JOIN cat.ujian_pegawai_daftar B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.UJIAN_TAHAP C ON A.UJIAN_ID = C.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN D ON C.TIPE_UJIAN_ID = D.TIPE_UJIAN_ID
				LEFT JOIN
				(
					SELECT
					A.ID ID_ROW, A.TIPE TIPE_INFO
					FROM cat.TIPE_UJIAN A
					WHERE 1=1 AND PARENT_ID = '0'
				) E ON D.PARENT_ID = E.ID_ROW
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
	
	function selectByParamsPegawaiTahapLog($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT A.UJIAN_ID, A.TGL_MULAI, A.TGL_SELESAI, A.STATUS, A.NILAI_LULUS, A.BATAS_WAKTU_MENIT, C.UJIAN_TAHAP_ID
				, TIPE || ' (' || BOBOT || ')' TIPE_INFO, TIPE, E.TIPE_INFO
				, C.MENIT_SOAL, C.TIPE_UJIAN_ID, LENGTH(PARENT_ID) LENGTH_PARENT
				, (SELECT 1 FROM cat.UJIAN_TAHAP_STATUS_UJIAN X WHERE 1=1 AND X.UJIAN_ID = A.UJIAN_ID AND X.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID AND X.PEGAWAI_ID = B.PEGAWAI_ID) TIPE_STATUS,
				UTP.PEGAWAI_ID UJIAN_PEGAWAI_RESET
				FROM cat.UJIAN A
				INNER JOIN cat.ujian_pegawai_daftar B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.UJIAN_TAHAP C ON A.UJIAN_ID = C.UJIAN_ID
				LEFT JOIN cat.TIPE_UJIAN D ON C.TIPE_UJIAN_ID = D.TIPE_UJIAN_ID
				LEFT JOIN cat.UJIAN_TAHAP_PEGAWAI UTP ON C.TIPE_UJIAN_ID = UTP.TIPE_UJIAN_ID AND B.PEGAWAI_ID = UTP.PEGAWAI_ID AND C.UJIAN_TAHAP_ID = UTP.UJIAN_TAHAP_ID
				LEFT JOIN
				(
					SELECT
					A.ID ID_ROW, A.TIPE TIPE_INFO
					FROM cat.TIPE_UJIAN A
					WHERE 1=1 AND PARENT_ID = '0'
				) E ON D.PARENT_ID = E.ID_ROW
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
	
	function selectByParamsPegawaiSelesaiTahap($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT A.UJIAN_ID, A.JUMLAH_TAHAP, B.JUMLAH_SELESAI_TAHAP, CASE WHEN COALESCE(A.JUMLAH_TAHAP,0) = COALESCE(B.JUMLAH_SELESAI_TAHAP,0) THEN 0 ELSE 1 END JUMLAH_PEGAWAI_SELESAI_TAHAP
				FROM
				(
					SELECT Y.UJIAN_ID, COUNT(1) JUMLAH_TAHAP
					FROM cat.UJIAN_TAHAP Y
					GROUP BY Y.UJIAN_ID
				) A
				LEFT JOIN
				(
					SELECT UJIAN_ID, PEGAWAI_ID, COUNT(1) JUMLAH_SELESAI_TAHAP 
					FROM cat.UJIAN_TAHAP_STATUS_UJIAN WHERE STATUS = 1 GROUP BY UJIAN_ID, PEGAWAI_ID
				) B ON A.UJIAN_ID = B.UJIAN_ID
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
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT UJIAN_TAHAP_ID, UJIAN_ID, TIPE_UJIAN_ID, BOBOT
				FROM UJIAN_TAHAP WHERE 1=1"; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_TAHAP_ID) AS ROWCOUNT FROM cat.UJIAN_TAHAP WHERE 1=1 "; 
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
		$str = "SELECT COUNT(UJIAN_TAHAP_ID) AS ROWCOUNT FROM UJIAN_TAHAP WHERE 1=1 "; 
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
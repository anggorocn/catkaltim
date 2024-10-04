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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
		A.UJIAN_ID, A.TIPE_UJIAN_ID, A.UJIAN_TAHAP_ID
		FROM cat.ujian_tahap A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsLatihan($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
		A.UJIAN_ID, A.TIPE_UJIAN_ID, A.UJIAN_TAHAP_ID
		FROM cat.ujian_tahap_latihan A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawaiSelesaiTahap($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT A.UJIAN_ID, A.JUMLAH_TAHAP, B.JUMLAH_SELESAI_TAHAP, CASE WHEN COALESCE(A.JUMLAH_TAHAP,0) = COALESCE(B.JUMLAH_SELESAI_TAHAP,0) THEN 0 ELSE 1 END JUMLAH_PEGAWAI_SELESAI_TAHAP
				FROM
				(
					SELECT A.UJIAN_ID, COUNT(1) JUMLAH_TAHAP
					FROM cat.ujian_pegawai_daftar A
					INNER JOIN formula_assesment_ujian_tahap B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
					GROUP BY A.UJIAN_ID
				) A
				LEFT JOIN
				(
					SELECT UJIAN_ID, PEGAWAI_ID, COUNT(1) JUMLAH_SELESAI_TAHAP 
					FROM cat.ujian_tahap_status_ujian WHERE STATUS = 1 GROUP BY UJIAN_ID, PEGAWAI_ID
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

    function selectByParamsPegawaiSelesaiTahapLatihan($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
		A.UJIAN_ID, A.JUMLAH_TAHAP, B.JUMLAH_SELESAI_TAHAP
		, CASE WHEN COALESCE(A.JUMLAH_TAHAP,0) = COALESCE(B.JUMLAH_SELESAI_TAHAP,0) THEN 0 ELSE 1 END JUMLAH_PEGAWAI_SELESAI_TAHAP
		FROM
		(
			SELECT A.UJIAN_ID, A.PEGAWAI_ID, COUNT(1) JUMLAH_TAHAP
			FROM cat.ujian_pegawai_daftar A
			INNER JOIN cat.ujian_tahap_latihan B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
			GROUP BY A.UJIAN_ID, A.PEGAWAI_ID
		) A
		LEFT JOIN
		(
			SELECT UJIAN_ID, PEGAWAI_ID, COUNT(1) JUMLAH_SELESAI_TAHAP 
			FROM cat.ujian_tahap_status_ujian_latihan WHERE STATUS = 1 GROUP BY UJIAN_ID, PEGAWAI_ID
		) B ON A.UJIAN_ID = B.UJIAN_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsUjianPegawaiTahap($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT 
			A.UJIAN_ID, A.UJIAN_PEGAWAI_DAFTAR_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, C.TIPE
			, case
			when c.KETERANGAN_UJIAN is null then c.tipe
			else C.KETERANGAN_UJIAN
				end KETERANGAN_UJIAN, D.TIPE_INFO
			, B.MENIT_SOAL, C.TIPE_UJIAN_ID, LENGTH(C.PARENT_ID) LENGTH_PARENT, C.PARENT_ID
			, (SELECT 1 FROM cat.UJIAN_TAHAP_STATUS_UJIAN X WHERE 1=1 AND X.UJIAN_ID = A.UJIAN_ID AND X.UJIAN_TAHAP_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID AND X.PEGAWAI_ID = A.PEGAWAI_ID AND X.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID) TIPE_STATUS
			, CASE C.TIPE_UJIAN_ID WHEN 16 THEN 50 ELSE B.JUMLAH_SOAL END JUMLAH_SOAL
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN 
		(
			SELECT A.*, JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap A
			LEFT JOIN 
			(
				SELECT FORMULA_ASSESMENT_UJIAN_TAHAP_ID ROWID, COUNT(1) JUMLAH_SOAL
				FROM formula_assesment_ujian_tahap_bank_soal
				GROUP BY FORMULA_ASSESMENT_UJIAN_TAHAP_ID
			) B ON FORMULA_ASSESMENT_UJIAN_TAHAP_ID = ROWID
		) B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		LEFT JOIN cat.TIPE_UJIAN C ON B.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT
			A.ID ID_ROW, A.KETERANGAN_UJIAN TIPE_INFO
			FROM cat.TIPE_UJIAN A
			WHERE 1=1 AND PARENT_ID = '0'
		) D ON C.PARENT_ID = D.ID_ROW
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsUjianPegawaiTahapLatihan($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT 
			A.UJIAN_ID, A.UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_TAHAP_ID
			, C.TIPE, D.TIPE_INFO
			, B.MENIT_SOAL, C.TIPE_UJIAN_ID, LENGTH(C.PARENT_ID) LENGTH_PARENT, C.PARENT_ID
			, (SELECT 1 FROM cat.UJIAN_TAHAP_STATUS_UJIAN_LATIHAN X WHERE 1=1 AND X.UJIAN_ID = A.UJIAN_ID AND X.UJIAN_TAHAP_ID = B.UJIAN_TAHAP_ID AND X.PEGAWAI_ID = A.PEGAWAI_ID) TIPE_STATUS
			, CASE C.TIPE_UJIAN_ID WHEN 16 THEN 50 ELSE B.JUMLAH_SOAL END JUMLAH_SOAL
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN 
		(
			SELECT A.*, JUMLAH_SOAL
			FROM cat.ujian_tahap_latihan A
			LEFT JOIN 
			(
				SELECT UJIAN_TAHAP_ID ROWID, COUNT(1) JUMLAH_SOAL
				FROM cat.ujian_bank_soal_latihan
				GROUP BY UJIAN_TAHAP_ID
			) B ON UJIAN_TAHAP_ID = ROWID
		) B ON A.UJIAN_ID = B.UJIAN_ID
		LEFT JOIN cat.TIPE_UJIAN C ON B.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT
			A.ID ID_ROW, A.TIPE TIPE_INFO
			FROM cat.TIPE_UJIAN A
			WHERE 1=1 AND PARENT_ID = '0'
		) D ON C.PARENT_ID = D.ID_ROW
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
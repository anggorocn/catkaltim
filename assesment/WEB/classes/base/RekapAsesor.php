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

  class RekapAsesor extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RekapAsesor()
	{
      $this->Entity(); 
    }
	
	function selectByParamsPenggalianAsesorPegawai($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sorder="ORDER BY A.PENGGALIAN_ID")
	{
		$str = "
		SELECT
			A.PENGGALIAN_ID, B.NAMA PENGGALIAN_NAMA, CASE A.PENGGALIAN_ID WHEN 0 THEN 'Potensi' ELSE B.KODE END PENGGALIAN_KODE
		FROM jadwal_acara A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		WHERE 1=1
		";
		// AND A.PENGGALIAN_ID > 0
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPenggalianPegawai($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sorder="ORDER BY NOMOR_URUT_GENERATE, A.ASESOR_ID")
	{
		$str = "
		SELECT
			JP.PEGAWAI_ID, P.NAMA NAMA_PEGAWAI, P.NIP_BARU
			, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
			, A.ASESOR_ID, D.NAMA NAMA_ASESOR, MAX(B.PENGGALIAN_ID) PENGGALIAN_ID
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN asesor D ON A.ASESOR_ID = D.ASESOR_ID
		INNER JOIN jadwal_pegawai JP ON A.JADWAL_ASESOR_ID = JP.JADWAL_ASESOR_ID
		INNER JOIN simpeg.pegawai P ON JP.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN
		(
			SELECT JADWAL_AWAL_TES_SIMULASI_ID, ROW_NUMBER() OVER(PARTITION BY JADWAL_AWAL_TES_SIMULASI_ID ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			ORDER BY JADWAL_AWAL_TES_SIMULASI_ID
		) JA ON JA.PEGAWAI_ID = P.PEGAWAI_ID AND A.JADWAL_TES_ID = JA.JADWAL_AWAL_TES_SIMULASI_ID
		WHERE 1=1
		";
		// AND B.PENGGALIAN_ID > 0
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY JP.PEGAWAI_ID, P.NAMA, P.NIP_BARU, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END , A.ASESOR_ID, D.NAMA ".$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPenggalianPegawaibAK($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sorder="ORDER BY NOMOR_URUT_GENERATE, A.ASESOR_ID, C.KODE")
	{
		// ORDER BY NOMOR_URUT_GENERATE, C.KODE, A.ASESOR_ID
		$str = "
		SELECT
			JP.PEGAWAI_ID, P.NAMA NAMA_PEGAWAI, P.NIP_BARU
			, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
			, A.ASESOR_ID, D.NAMA NAMA_ASESOR, B.PENGGALIAN_ID, A.JADWAL_ASESOR_ID
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN asesor D ON A.ASESOR_ID = D.ASESOR_ID
		INNER JOIN jadwal_pegawai JP ON A.JADWAL_ASESOR_ID = JP.JADWAL_ASESOR_ID
		INNER JOIN simpeg.pegawai P ON JP.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN
		(
			SELECT JADWAL_AWAL_TES_SIMULASI_ID, ROW_NUMBER() OVER(PARTITION BY JADWAL_AWAL_TES_SIMULASI_ID ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			ORDER BY JADWAL_AWAL_TES_SIMULASI_ID
		) JA ON JA.PEGAWAI_ID = P.PEGAWAI_ID AND A.JADWAL_TES_ID = JA.JADWAL_AWAL_TES_SIMULASI_ID
		WHERE 1=1
		";
		// AND B.PENGGALIAN_ID > 0
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsNilaiAsesorPegawai($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sorder="ORDER BY JP.PEGAWAI_ID, A.ASESOR_ID, B.PENGGALIAN_ID")
	{
		$str = "
		SELECT
			JP.PEGAWAI_ID, P.NAMA NAMA_PEGAWAI, P.NIP_BARU,
			D.NAMA, B.PENGGALIAN_ID, C.NAMA, A.JADWAL_ASESOR_ID, COALESCE(JUMLAH_DATA,0) JUMLAH_DATA
			, A.ASESOR_ID
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		LEFT JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN asesor D ON A.ASESOR_ID = D.ASESOR_ID
		INNER JOIN jadwal_pegawai JP ON A.JADWAL_ASESOR_ID = JP.JADWAL_ASESOR_ID
		INNER JOIN simpeg.pegawai P ON JP.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT 
			A.PENGGALIAN_ID, A.JADWAL_TES_ID, A.PEGAWAI_ID, A.JADWAL_ASESOR_ID
			, CASE WHEN COALESCE(A.JUMLAH_DATA,0) = COALESCE(A.JUMLAH_ISI_DATA,0) THEN 1 ELSE 0 END JUMLAH_DATA
			FROM
			(
				SELECT 
					C1.PENGGALIAN_ID, C.JADWAL_TES_ID, A.PEGAWAI_ID, A.JADWAL_ASESOR_ID
					, COUNT(A.JADWAL_ASESOR_ID) JUMLAH_DATA
					, COUNT(H1.JADWAL_PEGAWAI_DETIL_ATRIBUT_ID) JUMLAH_ISI_DATA
				FROM jadwal_pegawai A
				INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
				LEFT JOIN jadwal_pegawai_detil_atribut H1 ON H1.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H1.ATRIBUT_ID = D.FORM_ATRIBUT_ID AND D.FORM_PERMEN_ID = H1.FORM_PERMEN_ID
				WHERE 1=1  
				AND C1.PENGGALIAN_ID > 0 
				AND F.ASPEK_ID = 2 
				AND EXISTS (SELECT 1 FROM atribut_penggalian X WHERE D.FORMULA_ATRIBUT_ID = X.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = X.PENGGALIAN_ID) 
				GROUP BY C1.PENGGALIAN_ID, C.JADWAL_TES_ID, A.PEGAWAI_ID, A.JADWAL_ASESOR_ID
			) A
		) A1 ON A.JADWAL_TES_ID = A1.JADWAL_TES_ID AND A.JADWAL_ASESOR_ID = A1.JADWAL_ASESOR_ID AND P.PEGAWAI_ID = A1.PEGAWAI_ID
		WHERE 1=1
		AND B.PENGGALIAN_ID > 0
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsNilaiAsesorPotensiPegawai($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sorder="ORDER BY A.JADWAL_ASESOR_ID")
	{
		$str = "
		SELECT
			A.JADWAL_ASESOR_ID, A.ASESOR_ID, C.PEGAWAI_ID, B.PENGGALIAN_ID, A1.NAMA ASESOR_NAMA
			, CASE WHEN COALESCE(X1.JUMLAH_DATA,0) = COALESCE(X2.JUMLAH_DATA,0) THEN 1 ELSE 0 END JUMLAH_DATA
		FROM JADWAL_ASESOR A
		INNER JOIN ASESOR A1 ON A.ASESOR_ID = A1.ASESOR_ID
		INNER JOIN JADWAL_ACARA B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		INNER JOIN JADWAL_PEGAWAI C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
		LEFT JOIN
		(
			SELECT
			A.JADWAL_TES_ID, A.PEGAWAI_ID, COUNT(1) JUMLAH_DATA
			FROM 
			(
				SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID
				FROM penilaian A
				INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
				INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
				INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
				INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
				WHERE 1=1
				AND 
				EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT 
						MAX(C.ATRIBUT_ID) ATRIBUT_ID, C.ATRIBUT_ID_PARENT, A.JADWAL_TES_ID, A.PEGAWAI_ID
						FROM penilaian A
						INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
						INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
						INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
						INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
						WHERE 1=1
						AND A.ASPEK_ID = 1
						GROUP BY C.ATRIBUT_ID_PARENT, A.JADWAL_TES_ID, A.PEGAWAI_ID
					) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND A.JADWAL_TES_ID = X.JADWAL_TES_ID AND C.ATRIBUT_ID = X.ATRIBUT_ID
				)
			) A
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID
		) X1 ON A.JADWAL_TES_ID = X1.JADWAL_TES_ID AND C.PEGAWAI_ID = X1.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			A.JADWAL_TES_ID, A.PEGAWAI_ID, COUNT(1) JUMLAH_DATA
			FROM 
			(
				SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, B.NILAI
				FROM penilaian A
				INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
				INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
				INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
				INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
				WHERE 1=1
				AND 
				EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT 
						MAX(C.ATRIBUT_ID) ATRIBUT_ID, C.ATRIBUT_ID_PARENT, A.JADWAL_TES_ID, A.PEGAWAI_ID
						FROM penilaian A
						INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
						INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
						INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
						INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
						WHERE 1=1
						AND A.ASPEK_ID = 1
						GROUP BY C.ATRIBUT_ID_PARENT, A.JADWAL_TES_ID, A.PEGAWAI_ID
					) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND A.JADWAL_TES_ID = X.JADWAL_TES_ID AND C.ATRIBUT_ID = X.ATRIBUT_ID
				)
			) A
			WHERE 1=1
			AND COALESCE(A.NILAI,0) > 0
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID
		) X2 ON A.JADWAL_TES_ID = X2.JADWAL_TES_ID AND C.PEGAWAI_ID = X2.PEGAWAI_ID
		WHERE 1=1
		AND B.PENGGALIAN_ID = 0
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }


  } 
?>
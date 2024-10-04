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

  class CetakanPdf extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function CetakanPdf()
	{
	  $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
      $this->Entity(); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, case A.status_pegawai_id when 1 then 'CPNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
 			, B.KODE NAMA_GOL, B.NAMA NAMA_PANGKAT, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR
 			, A.SATKER_ID, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN
			, SUBSTR(CAST(A.LAST_ESELON_ID AS CHAR),1,1) ESELON_PARENT
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			WHERE 1=1
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, A.PEGAWAI_ID, A.TANGGAL_TES, A.JABATAN_TES_ID, A.JABATAN_TES_ID JABATAN_TES, A.ESELON,
				A.SATKER_TES_ID,
				B.NAMA SATKER_TES,
				A.ASPEK_ID, CASE WHEN A.ASPEK_ID = '1' THEN 'Aspek Potensi' WHEN A.ASPEK_ID = '2' THEN 'Aspek Kompetensi' ELSE '' END ASPEK_NAMA
				, NAMA_ASESI, METODE, A.JADWAL_TES_ID
				FROM penilaian A
				LEFT JOIN ".$this->db.".satker B ON A.SATKER_TES_ID = B.SATKER_ID
				WHERE 1=1
				"; 
		//INNER JOIN ".$this->db.".JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPenggalianAtribut($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.PENGGALIAN_ID, A.NAMA PENGGALIAN_NAMA, A.KODE PENGGALIAN_KODE
		FROM PENGGALIAN A
		INNER JOIN
		(
			SELECT A.PENGGALIAN_ID
			FROM atribut_penggalian A
			WHERE 1=1 AND A.FORMULA_ATRIBUT_ID
			IN
			(
				SELECT A.FORMULA_ATRIBUT_ID
				FROM formula_atribut A
				INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
				WHERE 1=1 AND A.FORMULA_ESELON_ID
				IN
				(
					SELECT PD.FORMULA_ESELON_ID
					FROM penilaian_detil PD
					INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
					WHERE 1=1 ".$statementdetil."
					GROUP BY PD.FORMULA_ESELON_ID
				)
			)
			GROUP BY A.PENGGALIAN_ID
		) B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAtributPegawaiPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT 
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA, B.NILAI
			, CASE A.ASPEK_ID WHEN 2 THEN 'Aspek Kompetensi' ELSE 'Aspek Psikologi' END ASPEK_NAMA
			, CASE WHEN B.NILAI = 1 THEN 'Belum Kompeten' WHEN B.NILAI = 2 THEN 'Hampir Kompeten'
			  WHEN B.NILAI = 3 THEN 'Cukup Kompeten' WHEN B.NILAI = 4 THEN 'Kompeten'
			  WHEN B.NILAI = 5 THEN 'Sangat Kompeten' END KESIMPULAN
		FROM atribut A
		INNER JOIN
		(
			SELECT A.ATRIBUT_ID, A.NILAI
			FROM
			(
				SELECT A.ATRIBUT_ID, A.NILAI
				FROM
				(
					SELECT PD.ATRIBUT_ID, PD.NILAI
					FROM penilaian_detil PD
					INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
					WHERE 1=1 ".$statementdetil."
				) A
				UNION ALL
				SELECT A.ATRIBUT_ID, A.NILAI
				FROM
				(
					SELECT A.ATRIBUT_ID, NULL NILAI
					FROM
					(
						SELECT SUBSTRING(PD.ATRIBUT_ID,1,2) ATRIBUT_ID, PD.NILAI
						FROM penilaian_detil PD
						INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
						WHERE 1=1 ".$statementdetil."
						GROUP BY SUBSTRING(PD.ATRIBUT_ID,1,2)
					) A
				) A
			) A
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAtributPegawaiPotensiPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT 
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA
		  , B.ATRIBUT_BOBOT, B.STANDAR_RATING, B.STANDAR_SKOR
		  , B.INDIVIDU_RATING, B.INDIVIDU_SKOR, B.GAP
		FROM atribut A
		INNER JOIN
		(
			SELECT B.ATRIBUT_ID, A.ATRIBUT_BOBOT, A.ATRIBUT_NILAI_STANDAR STANDAR_RATING, A.ATRIBUT_SKOR STANDAR_SKOR
			, B.SKOR INDIVIDU_RATING, A.ATRIBUT_BOBOT * B.SKOR INDIVIDU_SKOR
			, ROUND((A.ATRIBUT_BOBOT * B.SKOR) - A.ATRIBUT_SKOR,2) GAP
			FROM formula_atribut_bobot A
			INNER JOIN
			(
				SELECT
				PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID
				, SUM(NILAI) / COUNT(1) SKOR
				FROM penilaian_detil PD
				INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
				WHERE 1=1 AND P.ASPEK_ID = 1 ".$statementdetil."
				GROUP BY PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2)
			) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
			WHERE 1=1
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAtributPegawaiKompetensiPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT 
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA
		  , B.ATRIBUT_BOBOT, B.STANDAR_RATING, B.STANDAR_SKOR
		  , B.INDIVIDU_RATING, B.INDIVIDU_SKOR, B.GAP
		FROM atribut A
		INNER JOIN
		(
			SELECT B.ATRIBUT_ID, A.ATRIBUT_BOBOT, A.ATRIBUT_NILAI_STANDAR STANDAR_RATING, A.ATRIBUT_SKOR STANDAR_SKOR
			, B.SKOR INDIVIDU_RATING, A.ATRIBUT_BOBOT * B.SKOR INDIVIDU_SKOR
			, ROUND((A.ATRIBUT_BOBOT * B.SKOR) - A.ATRIBUT_SKOR,2) GAP
			FROM formula_atribut_bobot A
			INNER JOIN
			(
				SELECT
				PD.FORMULA_ESELON_ID, PD.ATRIBUT_ID
				, NILAI SKOR
				FROM penilaian_detil PD
				INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
			  WHERE 1=1 ".$statementdetil."
			) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
			WHERE 1=1
			UNION ALL
			SELECT SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID, NULL ATRIBUT_BOBOT, NULL STANDAR_RATING, NULL STANDAR_SKOR
			, NULL INDIVIDU_RATING, NULL INDIVIDU_SKOR, NULL GAP
			FROM penilaian_detil PD
			INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
			WHERE 1=1 ".$statementdetil."
			GROUP BY SUBSTR(PD.ATRIBUT_ID, 1,2)
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAtributPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT 
		A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA, B.LEVEL, B.RATING
		, CASE A.ASPEK_ID WHEN 2 THEN 'Aspek Kompetensi' ELSE 'Aspek Psikologi' END ASPEK_NAMA
		FROM atribut A
		INNER JOIN
		(
			SELECT 
			B.ATRIBUT_ID, B.LEVEL, COALESCE(A.NILAI_STANDAR, 0) RATING
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			INNER JOIN
			(
				SELECT PD.FORMULA_ATRIBUT_ID
				FROM penilaian_detil PD
				INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
				WHERE 1=1 ".$statementdetil."
				GROUP BY PD.FORMULA_ATRIBUT_ID
			) FA ON FA.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
			UNION ALL
			SELECT 
			SUBSTRING(B.ATRIBUT_ID,1,2) ATRIBUT_ID, NULL LEVEL, NULL RATING
			FROM formula_atribut A
			INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
			INNER JOIN
			(
				SELECT PD.FORMULA_ATRIBUT_ID
				FROM penilaian_detil PD
				INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
				WHERE 1=1 ".$statementdetil."
				GROUP BY PD.FORMULA_ATRIBUT_ID
			) FA ON FA.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
			GROUP BY SUBSTRING(B.ATRIBUT_ID,1,2) 
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsFormulaPenggalian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil="", $statement='', $sOrder="")
	{
		$str = "
		SELECT 
			B.ATRIBUT_ID, AP.PENGGALIAN_ID
		FROM formula_atribut A
		INNER JOIN level_atribut B ON A.LEVEL_ID = B.LEVEL_ID
		INNER JOIN
		(
			SELECT PD.FORMULA_ATRIBUT_ID
			FROM penilaian_detil PD
			INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
			WHERE 1=1 ".$statementdetil."
			GROUP BY PD.FORMULA_ATRIBUT_ID
		) FA ON FA.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
		INNER JOIN
		(
			SELECT A.ATRIBUT_PENGGALIAN_ID, A.FORMULA_ATRIBUT_ID, A.PENGGALIAN_ID
			FROM atribut_penggalian A
			WHERE 1=1
		) AP ON AP.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
		GROUP BY B.ATRIBUT_ID, AP.PENGGALIAN_ID
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRekapNilai($paramsArray=array(),$limit=-1,$from=-1, $statementdetil="", $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		A.PROSEN_KOMPETENSI, A.PROSEN_POTENSI
		, C1.ATRIBUT_SKOR STANDAR_SKOR1, D1.SKOR_INDIVIDU SKOR_INDIVIDU1
		, C2.ATRIBUT_SKOR STANDAR_SKOR2, D2.SKOR_INDIVIDU SKOR_INDIVIDU2
		, A.PROSEN_POTENSI * (C1.ATRIBUT_SKOR / 100 ) STANDAR_SKOR_AKHIR1
		, A.PROSEN_POTENSI * (D1.SKOR_INDIVIDU / 100 ) SKOR_INDIVIDU_AKHIR1
		, A.PROSEN_KOMPETENSI * (C2.ATRIBUT_SKOR / 100 ) STANDAR_SKOR_AKHIR2
		, A.PROSEN_KOMPETENSI * (D2.SKOR_INDIVIDU / 100 ) SKOR_INDIVIDU_AKHIR2
		FROM formula_eselon A
		INNER JOIN
		(
			SELECT PD.FORMULA_ESELON_ID
			FROM penilaian_detil PD
			INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
			WHERE 1=1 ".$statementdetil."
			GROUP BY PD.FORMULA_ESELON_ID
		) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		LEFT JOIN
		(
		  SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_SKOR) ATRIBUT_SKOR
		  FROM formula_atribut_bobot A
		  WHERE 1=1
		  AND A.ASPEK_ID = 1
		  GROUP BY A.FORMULA_ESELON_ID, A.ASPEK_ID
		) C1 ON A.FORMULA_ESELON_ID = C1.FORMULA_ESELON_ID
		LEFT JOIN
		(
		  SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_SKOR) ATRIBUT_SKOR
		  FROM formula_atribut_bobot A
		  WHERE 1=1
		  AND A.ASPEK_ID = 2
		  GROUP BY A.FORMULA_ESELON_ID, A.ASPEK_ID
		) C2 ON A.FORMULA_ESELON_ID = C2.FORMULA_ESELON_ID
		LEFT JOIN
		(
			SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_BOBOT * B.SKOR) SKOR_INDIVIDU
			FROM formula_atribut_bobot A
			INNER JOIN
			(
				SELECT
				PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID
				, SUM(NILAI) / SUBSTR(PD.ATRIBUT_ID, 1,2) SKOR
				FROM penilaian_detil PD
				INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
				WHERE 1=1 ".$statementdetil."
				AND P.ASPEK_ID = 1
				GROUP BY PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2)
			) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
			WHERE 1=1
			GROUP BY A.FORMULA_ESELON_ID
		) D1 ON A.FORMULA_ESELON_ID = D1.FORMULA_ESELON_ID
		LEFT JOIN
		(
			SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_BOBOT * B.SKOR) SKOR_INDIVIDU
			FROM formula_atribut_bobot A
			INNER JOIN
			(
				SELECT
				PD.FORMULA_ESELON_ID, PD.ATRIBUT_ID
				, NILAI SKOR
				FROM penilaian_detil PD
				INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
				WHERE 1=1 ".$statementdetil."
				AND P.ASPEK_ID = 2
			) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
			WHERE 1=1
			GROUP BY A.FORMULA_ESELON_ID
		) D2 ON A.FORMULA_ESELON_ID = D2.FORMULA_ESELON_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
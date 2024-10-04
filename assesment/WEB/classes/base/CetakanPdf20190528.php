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
	
	function selectByParamsAsesorCbi($statement='', $reqTahun="", $orderby='')
	{
		$str = "
		SELECT C.ASESOR_NAMA, REPLACE(A1.NAMA, ' ','') PEGAWAI_NAMA
		FROM jadwal_pegawai A
		INNER JOIN(
			SELECT PENGGALIAN_ID 
			FROM penggalian
			WHERE STATUS_CBI = 1 AND TAHUN = '".$reqTahun."'
		) B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN
		(
			SELECT A.JADWAL_ASESOR_ID, A.ASESOR_ID, REPLACE(B.NAMA, ' ','') ASESOR_NAMA
			FROM jadwal_asesor A
			INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
		) C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		WHERE 1=1
	 	"; 
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR
 			, CAST(COALESCE(A.TGL_LAHIR, TO_DATE(SUBSTRING(A.NIP_BARU,1,8), 'YYYYMMDD')) AS DATE) TGL_LAHIR
 			, case A.status_pegawai_id when 1 then 'CPNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
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
		FROM penggalian A
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
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA, B.NILAI_STANDAR, B.NILAI
			, CASE A.ASPEK_ID WHEN 2 THEN 'Aspek Kompetensi' ELSE 'Aspek Psikologi' END ASPEK_NAMA
			, CASE WHEN B.NILAI = 1 THEN 'Belum Kompeten' WHEN B.NILAI = 2 THEN 'Hampir Kompeten'
			  WHEN B.NILAI = 3 THEN 'Cukup Kompeten' WHEN B.NILAI = 4 THEN 'Kompeten'
			  WHEN B.NILAI = 5 THEN 'Sangat Kompeten' END KESIMPULAN
		FROM atribut A
		INNER JOIN
		(
			SELECT A.ATRIBUT_ID, A.NILAI, A.PERMEN_ID, A.NILAI_STANDAR
			FROM
			(
				SELECT A.ATRIBUT_ID, A.NILAI, A.PERMEN_ID, A.NILAI_STANDAR
				FROM
				(
					SELECT PD.ATRIBUT_ID, PD.NILAI, PD.PERMEN_ID, B1.NILAI_STANDAR
					FROM penilaian_detil PD
					INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
					INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = PD.FORMULA_ATRIBUT_ID
					WHERE 1=1 ".$statementdetil."
				) A
				UNION ALL
				SELECT A.ATRIBUT_ID, A.NILAI, A.PERMEN_ID, A.NILAI_STANDAR
				FROM
				(
					SELECT A.ATRIBUT_ID, NULL NILAI, A.PERMEN_ID, NULL NILAI_STANDAR
					FROM
					(
						SELECT SUBSTRING(PD.ATRIBUT_ID,1,2) ATRIBUT_ID, PD.PERMEN_ID
						FROM penilaian_detil PD
						INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
						WHERE 1=1 ".$statementdetil."
						GROUP BY SUBSTRING(PD.ATRIBUT_ID,1,2), PD.PERMEN_ID
					) A
				) A
			) A
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.PERMEN_ID = B.PERMEN_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAtributPegawaiPotensiPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT 
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA
		  , B.ATRIBUT_BOBOT, B.STANDAR_RATING, B.STANDAR_SKOR
		  , B.INDIVIDU_RATING
		  , B.INDIVIDU_SKOR + (FLOOR(B.INDIVIDU_SKOR + 0.01)-FLOOR(B.INDIVIDU_SKOR))* 0.01 INDIVIDU_SKOR
		  , B.GAP + (FLOOR(B.GAP + 0.01)-FLOOR(B.GAP))* 0.01 GAP
		FROM atribut A
		INNER JOIN
		(
			SELECT B.ATRIBUT_ID, A.ATRIBUT_BOBOT, A.ATRIBUT_NILAI_STANDAR STANDAR_RATING, A.ATRIBUT_SKOR STANDAR_SKOR
			, B.SKOR INDIVIDU_RATING, ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) INDIVIDU_SKOR
			, ROUND((ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2)) - A.ATRIBUT_SKOR,2) GAP
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
		  , B.INDIVIDU_RATING, B.INDIVIDU_SKOR + (FLOOR(B.INDIVIDU_SKOR + 0.01)-FLOOR(B.INDIVIDU_SKOR))* 0.01 INDIVIDU_SKOR
		  , B.GAP + (FLOOR(B.GAP + 0.01)-FLOOR(B.GAP))* 0.01 GAP
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
	
	function selectByParamsAtributPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ASPEK_ID DESC, A.ATRIBUT_ID ASC")
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
			SUBSTRING(B.ATRIBUT_ID,1,2) ATRIBUT_ID, NULL AS LEVEL, NULL RATING
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
		  AND A.ASPEK_ID = '1'
		  GROUP BY A.FORMULA_ESELON_ID, A.ASPEK_ID
		) C1 ON A.FORMULA_ESELON_ID = C1.FORMULA_ESELON_ID
		LEFT JOIN
		(
		  SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_SKOR) ATRIBUT_SKOR
		  FROM formula_atribut_bobot A
		  WHERE 1=1
		  AND A.ASPEK_ID = '2'
		  GROUP BY A.FORMULA_ESELON_ID, A.ASPEK_ID
		) C2 ON A.FORMULA_ESELON_ID = C2.FORMULA_ESELON_ID
		LEFT JOIN
		(
			SELECT A.FORMULA_ESELON_ID
			, SUM(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) + (FLOOR(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) + 0.01)-FLOOR(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2)))* 0.01) SKOR_INDIVIDU
			FROM formula_atribut_bobot A
			INNER JOIN
			(
				SELECT
				PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID
				, SUM(NILAI) / COUNT(1) SKOR
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
			SELECT A.FORMULA_ESELON_ID
			, SUM(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2) + (FLOOR(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2) + 0.01)-FLOOR(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2)))* 0.01) SKOR_INDIVIDU
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

    function selectByParamsRekap($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
		SELECT 
			A.JADWAL_TES_ID, A.TANGGAL_TES, A.BATCH, A.ACARA, B.PEGAWAI_ID
			, P.NAMA, P.NIP_BARU, B.JABATAN_TES_ID JABATAN_NAMA, S.NAMA SATKER
			, COALESCE(PN.STANDAR_SKOR_AKHIR,0) STANDAR_SKOR_AKHIR, COALESCE(PN.SKOR_INDIVIDU_AKHIR,0) SKOR_INDIVIDU_AKHIR
		FROM jadwal_tes A
		INNER JOIN
		(
			SELECT B.JABATAN_TES_ID, B.JADWAL_TES_ID, B.PEGAWAI_ID
			FROM penilaian B
			GROUP BY B.JABATAN_TES_ID, B.JADWAL_TES_ID, B.PEGAWAI_ID
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		INNER JOIN ".$this->db.".pegawai P ON P.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN ".$this->db.".satker S ON S.SATKER_ID = P.SATKER_ID
		INNER JOIN
		(
		  SELECT 
		  B.PEGAWAI_ID, B.TAHUN, A.FORMULA_ID
		  , (A.PROSEN_POTENSI * (C1.ATRIBUT_SKOR / 100 )) + (A.PROSEN_KOMPETENSI * (C2.ATRIBUT_SKOR / 100 )) STANDAR_SKOR_AKHIR
		  , (A.PROSEN_POTENSI * (D1.SKOR_INDIVIDU / 100 )) + (A.PROSEN_KOMPETENSI * (D2.SKOR_INDIVIDU / 100 )) SKOR_INDIVIDU_AKHIR
		  FROM formula_eselon A
		  INNER JOIN
		  (
		  	SELECT PD.FORMULA_ESELON_ID, P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN
		  	FROM penilaian_detil PD
		  	INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
		  	WHERE 1=1
		  	GROUP BY PD.FORMULA_ESELON_ID, P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY')
		  ) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		  LEFT JOIN
		  (
		    SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_SKOR) ATRIBUT_SKOR
		    FROM formula_atribut_bobot A
		    WHERE 1=1
		    AND A.ASPEK_ID = '1'
		    GROUP BY A.FORMULA_ESELON_ID, A.ASPEK_ID
		  ) C1 ON A.FORMULA_ESELON_ID = C1.FORMULA_ESELON_ID
		  LEFT JOIN
		  (
		    SELECT A.FORMULA_ESELON_ID, SUM(A.ATRIBUT_SKOR) ATRIBUT_SKOR
		    FROM formula_atribut_bobot A
		    WHERE 1=1
		    AND A.ASPEK_ID = '2'
		    GROUP BY A.FORMULA_ESELON_ID, A.ASPEK_ID
		  ) C2 ON A.FORMULA_ESELON_ID = C2.FORMULA_ESELON_ID
		  LEFT JOIN
		  (
		  	SELECT A.FORMULA_ESELON_ID
		  	, SUM(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) + (FLOOR(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) + 0.01)-FLOOR(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2)))* 0.01) SKOR_INDIVIDU
		    , B.PEGAWAI_ID, B.TAHUN
		  	FROM formula_atribut_bobot A
		  	INNER JOIN
		  	(
		  		SELECT
		  		PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID
		  		, SUM(NILAI) / COUNT(1) SKOR
		      , P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN
		  		FROM penilaian_detil PD
		  		INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
		  		WHERE 1=1 
		  		AND P.ASPEK_ID = 1
		  		GROUP BY PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2), P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY')
		  	) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
		  	WHERE 1=1
		  	GROUP BY A.FORMULA_ESELON_ID, B.PEGAWAI_ID, B.TAHUN
		  ) D1 ON A.FORMULA_ESELON_ID = D1.FORMULA_ESELON_ID
		  AND B.PEGAWAI_ID = D1.PEGAWAI_ID AND B.TAHUN = D1.TAHUN
		  LEFT JOIN
		  (
		  	SELECT A.FORMULA_ESELON_ID
		  	, SUM(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2) + (FLOOR(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2) + 0.01)-FLOOR(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2)))* 0.01) SKOR_INDIVIDU
		    , B.PEGAWAI_ID, B.TAHUN
		  	FROM formula_atribut_bobot A
		  	INNER JOIN
		  	(
		  		SELECT
		  		PD.FORMULA_ESELON_ID, PD.ATRIBUT_ID
		  		, NILAI SKOR, P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN
		  		FROM penilaian_detil PD
		  		INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
		  		WHERE 1=1 
		  		AND P.ASPEK_ID = 2
		  	) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
		  	WHERE 1=1
		  	GROUP BY A.FORMULA_ESELON_ID, B.PEGAWAI_ID, B.TAHUN
		  ) D2 ON A.FORMULA_ESELON_ID = D2.FORMULA_ESELON_ID
		  AND B.PEGAWAI_ID = D2.PEGAWAI_ID AND B.TAHUN = D2.TAHUN
		  WHERE 1=1
		) PN ON PN.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY') AND B.PEGAWAI_ID = PN.PEGAWAI_ID
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

    function getCountByParamsRekap($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM jadwal_tes A
		INNER JOIN
		(
			SELECT B.JABATAN_TES_ID, B.JADWAL_TES_ID, B.PEGAWAI_ID
			FROM penilaian B
			GROUP BY B.JABATAN_TES_ID, B.JADWAL_TES_ID, B.PEGAWAI_ID
		) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		INNER JOIN ".$this->db.".pegawai P ON P.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN ".$this->db.".satker S ON S.SATKER_ID = P.SATKER_ID
		INNER JOIN
		(
		  SELECT 
		  B.PEGAWAI_ID, B.TAHUN, A.FORMULA_ID
		  , (A.PROSEN_POTENSI * (C1.ATRIBUT_SKOR / 100 )) + (A.PROSEN_KOMPETENSI * (C2.ATRIBUT_SKOR / 100 )) STANDAR_SKOR_AKHIR
		  , (A.PROSEN_POTENSI * (D1.SKOR_INDIVIDU / 100 )) + (A.PROSEN_KOMPETENSI * (D2.SKOR_INDIVIDU / 100 )) SKOR_INDIVIDU_AKHIR
		  FROM formula_eselon A
		  INNER JOIN
		  (
		  	SELECT PD.FORMULA_ESELON_ID, P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN
		  	FROM penilaian_detil PD
		  	INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
		  	WHERE 1=1
		  	GROUP BY PD.FORMULA_ESELON_ID, P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY')
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
		  	SELECT A.FORMULA_ESELON_ID
		  	, SUM(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) + (FLOOR(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2) + 0.01)-FLOOR(ROUND(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2),2)))* 0.01) SKOR_INDIVIDU
		    , B.PEGAWAI_ID, B.TAHUN
		  	FROM formula_atribut_bobot A
		  	INNER JOIN
		  	(
		  		SELECT
		  		PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID
		  		, SUM(NILAI) / COUNT(1) SKOR
		      , P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN
		  		FROM penilaian_detil PD
		  		INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
		  		WHERE 1=1 
		  		AND P.ASPEK_ID = 1
		  		GROUP BY PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2), P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY')
		  	) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
		  	WHERE 1=1
		  	GROUP BY A.FORMULA_ESELON_ID, B.PEGAWAI_ID, B.TAHUN
		  ) D1 ON A.FORMULA_ESELON_ID = D1.FORMULA_ESELON_ID
		  AND B.PEGAWAI_ID = D1.PEGAWAI_ID AND B.TAHUN = D1.TAHUN
		  LEFT JOIN
		  (
		  	SELECT A.FORMULA_ESELON_ID
		  	, SUM(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2) + (FLOOR(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2) + 0.01)-FLOOR(A.ATRIBUT_BOBOT * ROUND(B.SKOR,2)))* 0.01) SKOR_INDIVIDU
		    , B.PEGAWAI_ID, B.TAHUN
		  	FROM formula_atribut_bobot A
		  	INNER JOIN
		  	(
		  		SELECT
		  		PD.FORMULA_ESELON_ID, PD.ATRIBUT_ID
		  		, NILAI SKOR, P.PEGAWAI_ID, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN
		  		FROM penilaian_detil PD
		  		INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
		  		WHERE 1=1 
		  		AND P.ASPEK_ID = 2
		  	) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID AND A.ATRIBUT_ID = B.ATRIBUT_ID
		  	WHERE 1=1
		  	GROUP BY A.FORMULA_ESELON_ID, B.PEGAWAI_ID, B.TAHUN
		  ) D2 ON A.FORMULA_ESELON_ID = D2.FORMULA_ESELON_ID
		  AND B.PEGAWAI_ID = D2.PEGAWAI_ID AND B.TAHUN = D2.TAHUN
		  WHERE 1=1
		) PN ON PN.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY') AND B.PEGAWAI_ID = PN.PEGAWAI_ID
		WHERE 1=1
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsDeskripsiIndividuAtribut20190328($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY C.JADWAL_TES_ID, A.PEGAWAI_ID, F.ASPEK_ID, F.ATRIBUT_ID')
	{
		$str = "
		SELECT 
			H.JADWAL_PEGAWAI_DETIL_ID, A.JADWAL_PEGAWAI_ID, A.JADWAL_ASESOR_ID
			, F.ATRIBUT_ID, F.ASPEK_ID, C1.PENGGALIAN_ID,
			G.INDIKATOR_ID, G.LEVEL_ID, D.FORM_PERMEN_ID,
			H.INDIKATOR_ID PEGAWAI_INDIKATOR_ID, H.LEVEL_ID PEGAWAI_LEVEL_ID,
			F.NAMA ATRIBUT_NAMA, F.KETERANGAN ATRIBUT_KETERANGAN, G.NAMA_INDIKATOR, G1.JUMLAH_LEVEL
			, H.KETERANGAN PEGAWAI_KETERANGAN, B1.NAMA NAMA_ASESOR, B.ASESOR_ID
			, D.NILAI_STANDAR, H1.NILAI, H1.GAP, H1.CATATAN
			, H1.JADWAL_PEGAWAI_DETIL_ATRIBUT_ID
		FROM jadwal_pegawai A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
		INNER JOIN indikator_penilaian G ON G.LEVEL_ID = D.LEVEL_ID AND F.ATRIBUT_ID = G.INDIKATOR_ATRIBUT_ID AND F.PERMEN_ID = G.INDIKATOR_PERMEN_ID
		INNER JOIN
		(
			SELECT PENGGALIAN_ID PG_ID, CAST(TAHUN AS TEXT) PGI_TAHUN
			FROM penggalian
			WHERE UPPER(KODE) = 'CBI'
		) PG ON PG.PG_ID = C1.PENGGALIAN_ID AND PGI_TAHUN = TO_CHAR(C.TANGGAL_TES, 'YYYY')
		INNER JOIN
		(
			SELECT A.JADWAL_PEGAWAI_ID, G.LEVEL_ID, COUNT(1) JUMLAH_LEVEL
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
			INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
			INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
			INNER JOIN indikator_penilaian G ON G.LEVEL_ID = D.LEVEL_ID AND F.ATRIBUT_ID = G.INDIKATOR_ATRIBUT_ID AND F.PERMEN_ID = G.INDIKATOR_PERMEN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_PEGAWAI_ID, G.LEVEL_ID
		) G1 ON G1.LEVEL_ID = G.LEVEL_ID AND A.JADWAL_PEGAWAI_ID = G1.JADWAL_PEGAWAI_ID
		LEFT JOIN jadwal_pegawai_detil H ON H.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID AND D.FORM_PERMEN_ID = H.FORM_PERMEN_ID
		LEFT JOIN jadwal_pegawai_detil_atribut H1 ON H1.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H1.ATRIBUT_ID = D.FORM_ATRIBUT_ID AND D.FORM_PERMEN_ID = H1.FORM_PERMEN_ID
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

    function selectByParamsDeskripsiIndividuAtribut($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY C.JADWAL_TES_ID, A.PEGAWAI_ID, F.ASPEK_ID, F.ATRIBUT_ID, G.NAMA_INDIKATOR, C1.PENGGALIAN_ID')
	{
		$str = "
		SELECT 
			H.JADWAL_PEGAWAI_DETIL_ID, A.JADWAL_PEGAWAI_ID, A.JADWAL_ASESOR_ID
			, F.ATRIBUT_ID, F.ASPEK_ID, C1.PENGGALIAN_ID,
			G.INDIKATOR_ID, G.LEVEL_ID, D.FORM_PERMEN_ID,
			H.INDIKATOR_ID PEGAWAI_INDIKATOR_ID, H.LEVEL_ID PEGAWAI_LEVEL_ID
			, F.NAMA || '-' || F.ATRIBUT_ID ATRIBUT_NAMA1
			, F.NAMA ATRIBUT_NAMA
			, F.NAMA ATRIBUT_NAMA1
			, F.KETERANGAN ATRIBUT_KETERANGAN, G.NAMA_INDIKATOR, G1.JUMLAH_LEVEL
			, H.KETERANGAN PEGAWAI_KETERANGAN, B1.NAMA NAMA_ASESOR, B.ASESOR_ID
			, D.NILAI_STANDAR, H1.NILAI, H1.GAP
			, STRIP_TAGS(H1.CATATAN) CATATAN
			, H1.JADWAL_PEGAWAI_DETIL_ATRIBUT_ID
		FROM jadwal_pegawai A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
		INNER JOIN indikator_penilaian G ON G.LEVEL_ID = D.LEVEL_ID AND F.ATRIBUT_ID = G.INDIKATOR_ATRIBUT_ID AND F.PERMEN_ID = G.INDIKATOR_PERMEN_ID
		INNER JOIN
		(
			SELECT PENGGALIAN_ID PG_ID, CAST(TAHUN AS TEXT) PGI_TAHUN
			FROM penggalian
		) PG ON PG.PG_ID = C1.PENGGALIAN_ID AND PGI_TAHUN = TO_CHAR(C.TANGGAL_TES, 'YYYY')
		INNER JOIN
		(
			SELECT A.JADWAL_PEGAWAI_ID, G.LEVEL_ID, COUNT(1) JUMLAH_LEVEL
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
			INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
			INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
			INNER JOIN indikator_penilaian G ON G.LEVEL_ID = D.LEVEL_ID AND F.ATRIBUT_ID = G.INDIKATOR_ATRIBUT_ID AND F.PERMEN_ID = G.INDIKATOR_PERMEN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_PEGAWAI_ID, G.LEVEL_ID
		) G1 ON G1.LEVEL_ID = G.LEVEL_ID AND A.JADWAL_PEGAWAI_ID = G1.JADWAL_PEGAWAI_ID
		LEFT JOIN jadwal_pegawai_detil H ON A.JADWAL_ASESOR_ID = H.JADWAL_ASESOR_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID AND D.FORM_ATRIBUT_ID = H.ATRIBUT_ID AND D.FORM_PERMEN_ID = H.FORM_PERMEN_ID AND A.PEGAWAI_ID = H.PEGAWAI_ID
		LEFT JOIN jadwal_pegawai_detil_atribut H1 ON H1.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H1.ATRIBUT_ID = D.FORM_ATRIBUT_ID AND D.FORM_PERMEN_ID = H1.FORM_PERMEN_ID
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

    function selectByParamsDeskripsiIndividuPenilaianAtribut($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ASPEK_ID, A.ATRIBUT_ID')
	{
		$str = "
		SELECT
			A.PENILAIAN_ID, A.PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT
			, A.NAMA, A.ATRIBUT_GROUP, ATRIBUT_KETERANGAN
			, A.NILAI_STANDAR, A.NILAI, A.GAP
			, CASE WHEN A.PROSENTASE > 100 THEN 100 ELSE A.PROSENTASE END PROSENTASE
			, STRIP_TAGS(A.BUKTI) BUKTI, STRIP_TAGS(A.CATATAN) CATATAN
			, A.LEVEL, A.LEVEL_KETERANGAN, A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ASPEK_ID
		FROM 
		(
			SELECT
			A.PENILAIAN_ID, B.PENILAIAN_DETIL_ID, C.ATRIBUT_ID, C.ATRIBUT_ID_PARENT
			, C.NAMA, C.ATRIBUT_ID_PARENT ATRIBUT_GROUP, C.KETERANGAN ATRIBUT_KETERANGAN
			, B1.NILAI_STANDAR
			, LA.LEVEL, LA.KETERANGAN LEVEL_KETERANGAN
			, CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END NILAI, COALESCE(B.GAP,0) GAP, B.BUKTI, B.CATATAN
			, ROUND((B.NILAI / B1.NILAI_STANDAR) * 100,2) PROSENTASE
			, B.PERMEN_ID, A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ASPEK_ID
			FROM penilaian A
			INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
			INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
			INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
			INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
			WHERE 1=1
		) A
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

    function selectByParamsAtributPegawaiPotensiDeskripsiPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
		SELECT 
			A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA
			, B.STANDAR_RATING, B.INDIVIDU_RATING
			, CASE WHEN B.GAP - FLOOR(B.GAP) > .00 THEN B.GAP ELSE CAST(B.GAP AS INTEGER) END GAP
			, STRIP_TAGS(B.BUKTI) BUKTI, STRIP_TAGS(B.CATATAN) CATATAN
		FROM atribut A
		INNER JOIN
		(
			SELECT
			B.ATRIBUT_ID, A.ATRIBUT_NILAI_STANDAR STANDAR_RATING, B.SKOR INDIVIDU_RATING
			, B.SKOR - A.ATRIBUT_NILAI_STANDAR GAP
			, B.BUKTI, B.CATATAN
			FROM formula_atribut_bobot A
			INNER JOIN
			(
				SELECT
					A.FORMULA_ESELON_ID, A.ATRIBUT_ID
					, CASE WHEN A.SKOR - FLOOR(A.SKOR) > .00 THEN A.SKOR ELSE CAST(A.SKOR AS INTEGER) END SKOR
					, B.BUKTI, B.CATATAN
				FROM
				(
					SELECT
					PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_ID
					, SUM(NILAI) / COUNT(1) SKOR
					FROM penilaian_detil PD
					INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
					WHERE 1=1 AND P.ASPEK_ID = 1 ".$statementdetil."
					GROUP BY PD.FORMULA_ESELON_ID, SUBSTR(PD.ATRIBUT_ID, 1,2)
				) A
				INNER JOIN
				(
					SELECT ATRIBUT_PARENT_ID, BUKTI, CATATAN
					FROM penilaian_detil A
					INNER JOIN
					(
						SELECT
						SUBSTR(PD.ATRIBUT_ID, 1,2) ATRIBUT_PARENT_ID, MAX(PD.ATRIBUT_ID) ATRIBUT_ID, PD.PENILAIAN_ID, P.PEGAWAI_ID
						FROM penilaian_detil PD
						INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
						WHERE 1=1 AND P.ASPEK_ID = 1 ".$statementdetil."
						GROUP BY SUBSTR(PD.ATRIBUT_ID, 1,2), PD.PENILAIAN_ID, P.PEGAWAI_ID
					) B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.PENILAIAN_ID = B.PENILAIAN_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID
				) B ON A.ATRIBUT_ID = B.ATRIBUT_PARENT_ID
				WHERE 1=1
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
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
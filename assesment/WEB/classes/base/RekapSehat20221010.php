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

  class RekapSehat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RekapSehat()
	{
      $this->Entity(); 
    }
	
	function selectByParamsMonitoringCfitHasilRekap($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementdetil='', $sorder="")
	{
		$str = "
		SELECT A.*
		, CASE
		WHEN STATUS_KESIMPULAN = '1' THEN 'Sangat Superior'
		WHEN STATUS_KESIMPULAN = '2' THEN 'Superior'
		WHEN STATUS_KESIMPULAN = '3' THEN 'Diatas Rata - Rata'
		WHEN STATUS_KESIMPULAN = '4' THEN 'Rata - Rata'
		WHEN STATUS_KESIMPULAN = '5' THEN 'Dibawah Rata - Rata'
		WHEN STATUS_KESIMPULAN = '6' THEN 'Borderline'
		WHEN STATUS_KESIMPULAN = '7' THEN 'Intellectual Deficient'
		END KESIMPULAN
		FROM
		(
			SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0101,0) AS NUMERIC) JUMLAH_BENAR_0101
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0102,0) AS NUMERIC) JUMLAH_BENAR_0102
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0103,0) AS NUMERIC) JUMLAH_BENAR_0103
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0104,0) AS NUMERIC) JUMLAH_BENAR_0104
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID
				, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) NILAI_HASIL
				, CASE
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 130 THEN '1'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 130 THEN '2'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 120 THEN '3'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 110 THEN '4'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 90 THEN '5'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 80 THEN '6'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) <= 69 THEN '7'
				END STATUS_KESIMPULAN
				, 
				JA.NOMOR_URUT NOMOR_URUT_GENERATE
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
				, COALESCE(A1.D_BN,0) JUMLAH_BENAR_0101
				, COALESCE(A2.D_BN,0) JUMLAH_BENAR_0102
				, COALESCE(A3.D_BN,0) JUMLAH_BENAR_0103
				, COALESCE(A4.D_BN,0) JUMLAH_BENAR_0104
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0101'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A1 ON A.JADWAL_TES_ID = A1.D_JTI AND A.PEGAWAI_ID = A1.D_PID AND A.FORMULA_ASSESMENT_ID = A1.D_FAI AND A.ID = A1.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0102'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A2 ON A.JADWAL_TES_ID = A2.D_JTI AND A.PEGAWAI_ID = A2.D_PID AND A.FORMULA_ASSESMENT_ID = A2.D_FAI AND A.ID = A2.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0103'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A3 ON A.JADWAL_TES_ID = A3.D_JTI AND A.PEGAWAI_ID = A3.D_PID AND A.FORMULA_ASSESMENT_ID = A3.D_FAI AND A.ID = A3.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0104'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A4 ON A.JADWAL_TES_ID = A4.D_JTI AND A.PEGAWAI_ID = A4.D_PID AND A.FORMULA_ASSESMENT_ID = A4.D_FAI AND A.ID = A4.D_ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
			INNER JOIN
			(
				SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		";
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." 
		) A
		LEFT JOIN 
		(
			SELECT
				X.FORMULA_ASSESMENT_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID, SUM(1) AS JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap_bank_soal X
			GROUP BY X.FORMULA_ASSESMENT_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID
		) B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
		WHERE 1=1
		".$statementdetil.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringRekapLain($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementdetil='', $sorder="", $norma="")
	{
		$str = "
		SELECT
				HSL.JADWAL_TES_ID, UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR 
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID  
				, JA.NOMOR_URUT NOMOR_URUT_GENERATE
			".$norma."
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID 
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A				    
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID				
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID			 
			INNER JOIN
			(
				SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 
		";
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$statementdetil.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
  }

  function selectByParamsMonitoringRekapLain73($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementdetil='', $sorder="", $norma="")
	{
		$str = "
				SELECT
				HSL.JADWAL_TES_ID, UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID  
				, JA.NOMOR_URUT NOMOR_URUT_GENERATE
				, zz.JUMLAH_BENAR
			".$norma."
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID 
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A				    
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID				
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID			 
			INNER JOIN
			(
				SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			INNER JOIN 
			(
				select 
				count(case when cekjawaban_1 is not null and cekjawaban_2 is not null then 1 end) JUMLAH_BENAR
				, A.PEGAWAI_ID, A.JADWAL_TES_ID, a.TIPE_UJIAN_ID
				from 
				(
					SELECT
						A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, B.TIPE_UJIAN_ID, cekjawaban_1, cekjawaban_2
					FROM
					(
						SELECT * FROM cat.ujian_pegawai_daftar A
					) A
					INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
					INNER JOIN
					(
						SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT, b.LAST_CREATE_USER jawaban_1, c.LAST_CREATE_USER jawaban_2 ,d.jawaban cekjawaban_1,e.jawaban cekjawaban_2
						FROM cat_pegawai.ujian_pegawai_163 A
						left join cat_pegawai.ujian_pegawai_163 b on b.pegawai_id = a.pegawai_id and A.BANK_SOAL_ID=b.BANK_SOAL_ID and b.bank_soal_pilihan_id=1 and a.bank_soal_pilihan_id is not null
						left join cat_pegawai.ujian_pegawai_163 c on c.pegawai_id = a.pegawai_id and A.BANK_SOAL_ID=c.BANK_SOAL_ID and c.bank_soal_pilihan_id=2 and a.bank_soal_pilihan_id is not null
						left join 	
						(select *,  ROW_NUMBER () OVER ( PARTITION BY bank_soal_id ORDER BY bank_soal_pilihan_id) NOMOR from cat.bank_soal_pilihan d 
			 			)d on a.BANK_SOAL_ID=d.BANK_SOAL_ID and b.LAST_CREATE_USER= d.jawaban  and d.NOMOR =1
						left join 
						(select *,  ROW_NUMBER () OVER ( PARTITION BY bank_soal_id ORDER BY bank_soal_pilihan_id) NOMOR from cat.bank_soal_pilihan e 
						 )e on a.BANK_SOAL_ID=e.BANK_SOAL_ID and c.LAST_CREATE_USER= e.jawaban  and e.NOMOR =2
									WHERE 1=1  
						group by A.PEGAWAI_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT, b.LAST_CREATE_USER, c.LAST_CREATE_USER, c.bank_soal_pilihan_id, b.bank_soal_pilihan_id,d.jawaban,e.jawaban
					)  UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID		 
					WHERE 1=1
					--AND A.JADWAL_TES_ID = 163 AND B.TIPE_UJIAN_ID = 73 AND A.PEGAWAI_ID = 2249 
					AND (B.TIPE_UJIAN_ID IN (4,46) OR B.TIPE_UJIAN_ID > 49)  
					group by A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, B.TIPE_UJIAN_ID, cekjawaban_1, cekjawaban_2 
					ORDER BY A.UJIAN_ID, A.PEGAWAI_ID
					desc
				) a group by A.PEGAWAI_ID, A.JADWAL_TES_ID, a.TIPE_UJIAN_ID
			) zz on zz.pegawai_id = b.pegawai_id and zz.jadwal_tes_id = hsl.jadwal_tes_id and zz.tipe_ujian_id = hsl.tipe_ujian_id
			WHERE 1=1 

		";
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$statementdetil.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
  }

    function selectByParamsMonitoringRekapLain72($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementdetil='', $sorder="",$norma='',$reqTipeUjianId='')
	{
		$str = "
		select c.nip_baru NIP_BARU, c.nama NAMA_PEGAWAI, b.pegawai_id,count(b.ujian_pegawai_daftar_id) JUMLAH_BENAR,
		(
			select count(a.ujian_pegawai_daftar_id) 
			from cat_pegawai.ujian_pegawai_".$jadwaltesid." a 
			where a.tipe_ujian_id=".$reqTipeUjianId." and a.pegawai_id=b.pegawai_id
		) JUMLAH_SOAL".$norma."
		from cat_pegawai.ujian_pegawai_".$jadwaltesid." b 
		left join simpeg.pegawai c on b.pegawai_id = c.pegawai_id
		left join cat.bank_soal_pilihan d on b.bank_soal_pilihan_id = d.bank_soal_pilihan_id and d.grade_prosentase='100'
		where b.tipe_ujian_id=".$reqTipeUjianId." and b.bank_soal_pilihan_id is not null ".$statement." ".$statementdetil."
		group by b.pegawai_id,c.nip_baru,c.nama

		";
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		// $str .= $statement.$statementdetil.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
  }

  function selectByParamsMonitoringRekapLain72Khusus($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementdetil='', $sorder="",$norma='',$reqTipeUjianId='')
	{
		$str = "
		select c.nip_baru NIP_BARU, c.nama NAMA_PEGAWAI, b.pegawai_id,count(b.ujian_pegawai_daftar_id) JUMLAH_BENAR,
		(
			select count(a.ujian_pegawai_daftar_id) 
			from cat_pegawai.ujian_pegawai_".$jadwaltesid." a 
			where a.tipe_ujian_id=".$reqTipeUjianId." and a.pegawai_id=b.pegawai_id
		) JUMLAH_SOAL".$norma."
		, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		from cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." b 
		left join simpeg.pegawai c on b.pegawai_id = c.pegawai_id
		inner join cat.bank_soal_pilihan d on b.bank_soal_id = d.bank_soal_id and lower(trim(d.jawaban)) = lower(trim(b.keterangan))
		LEFT JOIN
			(
				SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = b.PEGAWAI_ID
		where b.tipe_ujian_id=".$reqTipeUjianId." ".$statement." ".$statementdetil."
		group by b.pegawai_id,c.nip_baru,c.nama,JA.NOMOR_URUT

		";
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
  }


    function selectByParamsMonitoringRekapWPTNew($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementdetil='', $sorder="")
	{
		$str = "
		SELECT
				HSL.JADWAL_TES_ID, UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				-- , CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR 
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID  
				, cat.wptbenar(B.PEGAWAI_UMUR_NORMA, COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) JUMLAH_BENAR
				, COALESCE(HSL.JUMLAH_SOAL,0) - cat.wptbenar(B.PEGAWAI_UMUR_NORMA, COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) JUMLAH_BELUM_SKOR_BENAR
				--, Q.IQ IQ_NILAI
				--, cat.wptiq(Q.IQ) IQ_KETERANGAN
				, cat.wptiq(Q.IQ) || ' (' || Q.IQ || ')'  IQ_KETERANGAN
				, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '1' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
			FROM (select *, cat.NORMA_UMUR(B.UJIAN_ID, B.PEGAWAI_ID) PEGAWAI_UMUR_NORMA from cat.ujian_pegawai_daftar B) B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID 
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT WPT_SOAL_ID BANK_SOAL_ID, WPT_PILIHAN_ID BANK_SOAL_PILIHAN_ID, JAWABAN, CAST(KUNCI_JAWABAN AS NUMERIC) GRADE_PROSENTASE
									FROM cat.wpt_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE 1=1 ".$statementdetil."
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT WPT_SOAL_ID BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.wpt_pilihan
								WHERE CAST(KUNCI_JAWABAN AS NUMERIC) > 0
								GROUP BY WPT_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A				    
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1 ".$statementdetil."
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					WHERE 1=1 ".$statementdetil."
					AND PARENT_ID = '0'
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID				
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID		
			LEFT JOIN cat.wpt_rs Q ON COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) = Q.NILAI	 
			INNER JOIN
			(
				SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 
		";
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.JADWAL_TES_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringRekapWPTNew($paramsArray=array(), $jadwaltesid, $statement="", $statementdetil="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				HSL.JADWAL_TES_ID, UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR 
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID  
				, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '1' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID 
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT WPT_SOAL_ID BANK_SOAL_ID, WPT_PILIHAN_ID BANK_SOAL_PILIHAN_ID, JAWABAN, CAST(KUNCI_JAWABAN AS NUMERIC) GRADE_PROSENTASE
									FROM cat.wpt_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE 1=1 ".$statementdetil."
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT WPT_SOAL_ID BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.wpt_pilihan
								WHERE CAST(KUNCI_JAWABAN AS NUMERIC) > 0
								GROUP BY WPT_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A				    
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1 ".$statementdetil."
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					WHERE 1=1 ".$statementdetil."
					AND PARENT_ID = '0'
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID				
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID			 
			INNER JOIN
			(
				SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1  "; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement.") A";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function getCountByParamsMonitoringRekapLain($paramsArray=array(), $jadwaltesid, $statement="", $statementdetil="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				HSL.JADWAL_TES_ID, UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR 
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID  
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID 
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A				    
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID			 
			WHERE 1=1  "; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement.$statementdetil.") A";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
  }

   function getCountByParamsMonitoringRekapLain72($paramsArray=array(), $jadwaltesid, $statement="", $statementdetil="")
	{
		$str = "
		select count(distinct(b.ujian_pegawai_daftar_id))
		from cat_pegawai.ujian_pegawai_".$jadwaltesid." b where 
		b.tipe_ujian_id=72

		"; 

		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		// $str .= $statement.$statementdetil.") A";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
  }

    function getCountByParamsMonitoringCfitHasilRekap($paramsArray=array(), $jadwaltesid, $statement="", $statementdetil="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
				, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID
				, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) NILAI_HASIL
				, CASE
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 130 THEN '1'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 130 THEN '2'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 120 THEN '3'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 110 THEN '4'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 90 THEN '5'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 80 THEN '6'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) <= 69 THEN '7'
				END STATUS_KESIMPULAN
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
									WHERE GRADE_PROSENTASE > 0
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
							SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
							FROM cat.bank_soal_pilihan
							WHERE GRADE_PROSENTASE > 0
							GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap a
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		LEFT JOIN 
		(
			SELECT
				X.FORMULA_ASSESMENT_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID, SUM(1) AS JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap_bank_soal X
			GROUP BY X.FORMULA_ASSESMENT_ID, FORMULA_ASSESMENT_UJIAN_TAHAP_ID
		) B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID AND B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringCfitHasilRekapA($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $tipeujianid, $statement='', $statementdetil='', $sorder="order by NOMOR_URUT_GENERATE asc")
	{
		$str = "
		SELECT A.*
		, CASE
		WHEN STATUS_KESIMPULAN = '1' THEN 'Sangat Superior'
		WHEN STATUS_KESIMPULAN = '2' THEN 'Superior'
		WHEN STATUS_KESIMPULAN = '3' THEN 'Diatas Rata - Rata'
		WHEN STATUS_KESIMPULAN = '4' THEN 'Rata - Rata'
		WHEN STATUS_KESIMPULAN = '5' THEN 'Dibawah Rata - Rata'
		WHEN STATUS_KESIMPULAN = '6' THEN 'Borderline'
		WHEN STATUS_KESIMPULAN = '7' THEN 'Intellectual Deficient'
		END KESIMPULAN
		FROM
		(
			SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0101,0) AS NUMERIC) JUMLAH_BENAR_0101
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0102,0) AS NUMERIC) JUMLAH_BENAR_0102
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0103,0) AS NUMERIC) JUMLAH_BENAR_0103
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0104,0) AS NUMERIC) JUMLAH_BENAR_0104
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID
				, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) NILAI_HASIL
				, CASE
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 130 THEN '1'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 130 THEN '2'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 120 THEN '3'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 110 THEN '4'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 90 THEN '5'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 80 THEN '6'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) <= 69 THEN '7'
				END STATUS_KESIMPULAN
				, 
				JA.NOMOR_URUT NOMOR_URUT_GENERATE
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
				, COALESCE(A1.D_BN,0) JUMLAH_BENAR_0101
				, COALESCE(A2.D_BN,0) JUMLAH_BENAR_0102
				, COALESCE(A3.D_BN,0) JUMLAH_BENAR_0103
				, COALESCE(A4.D_BN,0) JUMLAH_BENAR_0104
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0101'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A1 ON A.JADWAL_TES_ID = A1.D_JTI AND A.PEGAWAI_ID = A1.D_PID AND A.FORMULA_ASSESMENT_ID = A1.D_FAI AND A.ID = A1.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0102'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A2 ON A.JADWAL_TES_ID = A2.D_JTI AND A.PEGAWAI_ID = A2.D_PID AND A.FORMULA_ASSESMENT_ID = A2.D_FAI AND A.ID = A2.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0103'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A3 ON A.JADWAL_TES_ID = A3.D_JTI AND A.PEGAWAI_ID = A3.D_PID AND A.FORMULA_ASSESMENT_ID = A3.D_FAI AND A.ID = A3.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0104'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A4 ON A.JADWAL_TES_ID = A4.D_JTI AND A.PEGAWAI_ID = A4.D_PID AND A.FORMULA_ASSESMENT_ID = A4.D_FAI AND A.ID = A4.D_ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
				AND C.TIPE_UJIAN_ID = ".$tipeujianid."
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
			INNER JOIN
			(
				SELECT a.NO_URUT NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		";
		// AND B.UJIAN_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.UJIAN_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." 
		) A
		LEFT JOIN 
		(
			SELECT
				X.LOWONGAN_ID, UJIAN_TAHAP_ID, SUM(1) AS JUMLAH_SOAL
			FROM cat.ujian_bank_soal X
			GROUP BY X.LOWONGAN_ID, UJIAN_TAHAP_ID
		) B ON A.FORMULA_ASSESMENT_ID = B.LOWONGAN_ID AND B.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
		WHERE 1=1
		".$statementdetil.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringCfitHasilRekapA($paramsArray=array(), $jadwaltesid, $tipeujianid, $statement="", $statementdetil="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID
				, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) NILAI_HASIL
				, CASE
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 130 THEN '1'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 130 THEN '2'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 120 THEN '3'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 110 THEN '4'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 90 THEN '5'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 80 THEN '6'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) <= 69 THEN '7'
				END STATUS_KESIMPULAN
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
									WHERE GRADE_PROSENTASE > 0
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
							SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
							FROM cat.bank_soal_pilihan
							WHERE GRADE_PROSENTASE > 0
							GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
				AND C.TIPE_UJIAN_ID = ".$tipeujianid."
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		LEFT JOIN 
		(
			SELECT
				X.LOWONGAN_ID, UJIAN_TAHAP_ID, SUM(1) AS JUMLAH_SOAL
			FROM cat.ujian_bank_soal X
			GROUP BY X.LOWONGAN_ID, UJIAN_TAHAP_ID
		) B ON A.FORMULA_ASSESMENT_ID = B.LOWONGAN_ID AND B.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringCfitHasilRekapB($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $tipeujianid, $statement='', $statementdetil='',  $sorder="order by NOMOR_URUT_GENERATE asc")
	{
		$str = "
		SELECT A.*
		, CASE
		WHEN STATUS_KESIMPULAN = '1' THEN 'Sangat Superior'
		WHEN STATUS_KESIMPULAN = '2' THEN 'Superior'
		WHEN STATUS_KESIMPULAN = '3' THEN 'Diatas Rata - Rata'
		WHEN STATUS_KESIMPULAN = '4' THEN 'Rata - Rata'
		WHEN STATUS_KESIMPULAN = '5' THEN 'Dibawah Rata - Rata'
		WHEN STATUS_KESIMPULAN = '6' THEN 'Borderline'
		WHEN STATUS_KESIMPULAN = '7' THEN 'Intellectual Deficient'
		END KESIMPULAN
		FROM
		(
			SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0101,0) AS NUMERIC) JUMLAH_BENAR_0101
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0102,0) AS NUMERIC) JUMLAH_BENAR_0102
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0103,0) AS NUMERIC) JUMLAH_BENAR_0103
				, CAST(COALESCE(HSL.JUMLAH_BENAR_0104,0) AS NUMERIC) JUMLAH_BENAR_0104
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID
				, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) NILAI_HASIL
				, CASE
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 130 THEN '1'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 130 THEN '2'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 120 THEN '3'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 110 THEN '4'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 90 THEN '5'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 80 THEN '6'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) <= 69 THEN '7'
				END STATUS_KESIMPULAN
				, 
				JA.NOMOR_URUT NOMOR_URUT_GENERATE
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
				, COALESCE(A1.D_BN,0) JUMLAH_BENAR_0101
				, COALESCE(A2.D_BN,0) JUMLAH_BENAR_0102
				, COALESCE(A3.D_BN,0) JUMLAH_BENAR_0103
				, COALESCE(A4.D_BN,0) JUMLAH_BENAR_0104
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0201'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A1 ON A.JADWAL_TES_ID = A1.D_JTI AND A.PEGAWAI_ID = A1.D_PID AND A.FORMULA_ASSESMENT_ID = A1.D_FAI AND A.ID = A1.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0202'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A2 ON A.JADWAL_TES_ID = A2.D_JTI AND A.PEGAWAI_ID = A2.D_PID AND A.FORMULA_ASSESMENT_ID = A2.D_FAI AND A.ID = A2.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0203'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A3 ON A.JADWAL_TES_ID = A3.D_JTI AND A.PEGAWAI_ID = A3.D_PID AND A.FORMULA_ASSESMENT_ID = A3.D_FAI AND A.ID = A3.D_ID
				LEFT JOIN
				(
					SELECT
						A.JADWAL_TES_ID D_JTI, A.PEGAWAI_ID D_PID
						, A.FORMULA_ASSESMENT_ID D_FAI, A.PARENT_ID D_ID, COUNT(A.PEGAWAI_ID) D_BN
					FROM
					(
						SELECT
						A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2) PARENT_ID, A.BANK_SOAL_ID
						, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
						, COUNT(1) JUMLAH_CHECK
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
						INNER JOIN 
						(
							SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
							FROM cat.bank_soal_pilihan
						) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
						WHERE 1=1
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID
						, A.TIPE_UJIAN_ID, TU.ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
					) A
					INNER JOIN
					(
						SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
						FROM cat.bank_soal_pilihan
						WHERE GRADE_PROSENTASE > 0
						GROUP BY BANK_SOAL_ID
					) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
					WHERE GRADE_PROSENTASE = 100
					AND A.ID = '0204'
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.PARENT_ID
				) A4 ON A.JADWAL_TES_ID = A4.D_JTI AND A.PEGAWAI_ID = A4.D_PID AND A.FORMULA_ASSESMENT_ID = A4.D_FAI AND A.ID = A4.D_ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
				AND C.TIPE_UJIAN_ID = ".$tipeujianid."
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
			INNER JOIN
			(
				SELECT a.NO_URUT NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
			) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		";
		// AND B.UJIAN_ID = 6 AND HSL.TIPE_UJIAN_ID = 1
		// AND B.UJIAN_ID = 6 AND HSL.TIPE_UJIAN_ID = 4
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." 
		) A
		LEFT JOIN 
		(
			SELECT
				X.LOWONGAN_ID, UJIAN_TAHAP_ID, SUM(1) AS JUMLAH_SOAL
			FROM cat.ujian_bank_soal X
			GROUP BY X.LOWONGAN_ID, UJIAN_TAHAP_ID
		) B ON A.FORMULA_ASSESMENT_ID = B.LOWONGAN_ID AND B.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
		WHERE 1=1
		".$statementdetil.$sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringCfitHasilRekapB($paramsArray=array(), $jadwaltesid, $tipeujianid, $statement="", $statementdetil="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				, CAST(COALESCE(HSL.JUMLAH_SOAL,0) AS NUMERIC) JUMLAH_SOAL
				, CAST(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) AS NUMERIC) JUMLAH_BENAR
				, HSL.UJIAN_TAHAP_ID, HSL.TIPE_UJIAN_ID
				, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) NILAI_HASIL
				, CASE
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 130 THEN '1'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 130 THEN '2'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 120 THEN '3'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 110 THEN '4'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 90 THEN '5'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) < 80 THEN '6'
				WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) AS NUMERIC) <= 69 THEN '7'
				END STATUS_KESIMPULAN
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
				, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
				, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
				FROM
				(
					SELECT 
					A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
									WHERE GRADE_PROSENTASE > 0
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
							SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
							FROM cat.bank_soal_pilihan
							WHERE GRADE_PROSENTASE > 0
							GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
				) A
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1
					AND PARENT_ID = '0'
				) C ON A.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID AND A.ID = C.ID
				INNER JOIN
				(
					SELECT A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2) ID, SUM(A.JUMLAH_SOAL_UJIAN_TAHAP) JUMLAH_SOAL
					FROM formula_assesment_ujian_tahap A
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					GROUP BY A.FORMULA_ASSESMENT_ID, SUBSTR(TU.ID,1,2)
				) D ON A.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID AND A.ID = D.ID
				WHERE 1=1
				AND C.TIPE_UJIAN_ID = ".$tipeujianid."
			) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		LEFT JOIN 
		(
			SELECT
				X.LOWONGAN_ID, UJIAN_TAHAP_ID, SUM(1) AS JUMLAH_SOAL
			FROM cat.ujian_bank_soal X
			GROUP BY X.LOWONGAN_ID, UJIAN_TAHAP_ID
		) B ON A.FORMULA_ASSESMENT_ID = B.LOWONGAN_ID AND B.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringPapiHasil($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="order by NOMOR_URUT_GENERATE asc")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
			, COALESCE(HSL.NILAI_W,0) NILAI_W
			, CASE 
			WHEN COALESCE(HSL.NILAI_W,0) < 4 THEN 'Hanya butuh gambaran ttg kerangka tugas scr garis besar, berpatokan pd tujuan, dpt bekerja dlm suasana yg kurang berstruktur, berinsiatif, mandiri. Tdk patuh, cenderung mengabaikan/tdk paham pentingnya peraturan/prosedur, suka membuat peraturan sendiri yg bisa bertentangan dg yg telah ada.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 4 AND COALESCE(HSL.NILAI_W,0) < 6 THEN 'Perlu pengarahan awal dan tolok ukur keberhasilan.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 6 AND COALESCE(HSL.NILAI_W,0) < 8 THEN 'Membutuhkan uraian rinci mengenai tugas, dan batasan tanggung jawab serta wewenang.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 8 AND COALESCE(HSL.NILAI_W,0) < 10 THEN 'Patuh pada kebijaksanaan, peraturan dan struktur organisasi. Ingin segala sesuatunya diuraikan secara rinci, kurang memiliki inisiatif, tdk fleksibel, terlalu tergantung pada organisasi, berharap `disuapi`.'
			END INFO_W
			, COALESCE(HSL.NILAI_F,0) NILAI_F
			, CASE 
			WHEN COALESCE(HSL.NILAI_F,0) < 4 THEN 'Otonom, dapat bekerja sendiri tanpa campur tangan orang lain, motivasi timbul krn pekerjaan itu sendiri - bukan krn pujian dr otoritas. Mempertanyakan otoritas, cenderung tidak puas thdp atasan, loyalitas lebih didasari kepentingan pribadi.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 4 AND COALESCE(HSL.NILAI_F,0) < 7 THEN 'Loyal pada Perusahaan.'
			WHEN COALESCE(HSL.NILAI_F,0) = 7 THEN 'Loyal pada pribadi atasan.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 8 AND COALESCE(HSL.NILAI_F,0) < 10 THEN 'Loyal, berusaha dekat dg pribadi atasan, ingin menyenangkan atasan, sadar akan harapan atasan akan dirinya.  Terlalu memperhatikan cara menyenangkan atasan, tidak berani berpendirian lain, tidak mandiri.'
			END INFO_F
			, COALESCE(HSL.NILAI_K,0) NILAI_K
			, CASE 
			WHEN COALESCE(HSL.NILAI_K,0) < 2 THEN 'Sabar, tidak menyukai konflik. Mengelak atau menghindar dari konflik, pasif, menekan atau menyembunyikan perasaan sesungguhnya,  menghindari konfrontasi, lari dari konflik, tidak mau mengakui adanya konflik.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 2 AND COALESCE(HSL.NILAI_K,0) < 4 THEN 'Lebih suka menghindari konflik, akan mencari rasionalisasi untuk  dapat menerima situasi dan melihat permasalahan dari sudut pandang orang lain.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 4 AND COALESCE(HSL.NILAI_K,0) < 6 THEN 'Tidak mencari atau menghindari konflik, mau mendengarkan pandangan orang lain tetapi dapat menjadi keras kepala saat mempertahankan pandangannya.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 6 AND COALESCE(HSL.NILAI_K,0) < 8 THEN 'Akan menghadapi konflik, mengungkapkan serta memaksakan pandangan dengan cara positif.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 8 AND COALESCE(HSL.NILAI_K,0) < 10 THEN 'Terbuka, jujur, terus terang, asertif, agresif, reaktif, mudah tersinggung, mudah meledak, curiga, berprasangka, suka berkelahi atau berkonfrontasi, berpikir negatif.'
			END INFO_K
			, COALESCE(HSL.NILAI_Z,0) NILAI_Z
			, CASE 
			WHEN COALESCE(HSL.NILAI_Z,0) < 2 THEN 'Mudah beradaptasi dg pekerjaan rutin tanpa merasa bosan, tidak membutuhkan variasi, menyukai lingkungan stabil dan tidak berubah. Konservatif, menolak perubahan, sulit menerima hal-hal baru, tidak dapat beradaptasi dengan situasi yg  berbeda-beda.'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 2 AND COALESCE(HSL.NILAI_Z,0) < 4 THEN 'Enggan berubah, tidak siap untuk beradaptasi, hanya mau menerima perubahan jika alasannya jelas dan meyakinkan.'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 4 AND COALESCE(HSL.NILAI_Z,0) < 6 THEN 'Mudah beradaptasi, cukup menyukai perubahan.'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 6 AND COALESCE(HSL.NILAI_Z,0) < 8 THEN 'Antusias terhadap perubahan dan akan mencari hal-hal baru, tetapi masih selektif ( menilai kemanfaatannya ).'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 8 AND COALESCE(HSL.NILAI_Z,0) < 10 THEN 'Sangat menyukai perubahan, gagasan baru/variasi, aktif mencari perubahan, antusias dg hal-hal baru, fleksibel dlm berpikir, mudah beradaptasi pd situasi yg berbeda-beda. Gelisah, frustasi, mudah bosan, sangat membutuhkan variasi, tidak menyukai tugas/situasi yg rutin-monoton.'
			END INFO_Z
			, COALESCE(HSL.NILAI_O,0) NILAI_O
			, CASE 
			WHEN COALESCE(HSL.NILAI_O,0) < 3 THEN 'Menjaga jarak, lebih memperhatikan hal - hal kedinasan, tdk mudah dipengaruhi oleh individu tertentu, objektif & analitis. Tampil dingin, tdk acuh, tdk ramah, suka berahasia, mungkin tdk sadar akan pe- rasaan org lain, & mungkin sulit menyesuaikan diri.'
			WHEN COALESCE(HSL.NILAI_O,0) >= 3 AND COALESCE(HSL.NILAI_O,0) < 6 THEN 'Tidak mencari atau menghindari hubungan antar pribadi di  lingkungan kerja, masih mampu menjaga jarak.'
			WHEN COALESCE(HSL.NILAI_O,0) >= 6 AND COALESCE(HSL.NILAI_O,0) < 10 THEN 'Peka akan kebutuhan org lain, sangat memikirkan hal - hal yg dibutuhkan org lain, suka menjalin hub persahabatan yg hangat & tulus. Sangat pe- rasa, mudah tersinggung, cenderung subjektif, dpt terlibat terlalu dlm/intim dg individu tertentu dlm pekerjaan, sangat tergantung pd individu tertentu.'
			END INFO_O
			, COALESCE(HSL.NILAI_B,0) NILAI_B
			, CASE 
			WHEN COALESCE(HSL.NILAI_B,0) < 3 THEN 'Mandiri ( dari segi emosi ) , tdk mudah dipengaruhi oleh tekanan kelompok. Penyendiri, kurang peka akan sikap & kebutuhan kelom- pok, mungkin sulit menyesuaikan diri.'
			WHEN COALESCE(HSL.NILAI_B,0) >= 3 AND COALESCE(HSL.NILAI_B,0) < 6 THEN 'Selektif dlm bergabung dg kelompok, hanya mau berhubungan dg kelompok di lingkungan kerja apabila bernilai & sesuai minat, tdk terlalu mudah dipengaruhi.'
			WHEN COALESCE(HSL.NILAI_B,0) >= 6 AND COALESCE(HSL.NILAI_B,0) < 10 THEN 'Suka bergabung dlm kelompok, sadar akan sikap & kebutuhan ke- lompok, suka bekerja sama, ingin menjadi bagian dari kelompok, ingin disukai & diakui oleh lingkungan; sangat tergantung pd kelom- pok, lebih memperhatikan kebutuhan kelompok daripada pekerjaan.'
			END INFO_B
			, COALESCE(HSL.NILAI_X,0) NILAI_X
			, CASE 
			WHEN COALESCE(HSL.NILAI_X,0) < 2 THEN 'Sederhana, rendah hati, tulus, tidak sombong dan tidak suka menam- pilkan diri. Terlalu sederhana, cenderung merendahkan kapasitas diri, tidak percaya diri, cenderung menarik diri dan pemalu.'
			WHEN COALESCE(HSL.NILAI_X,0) >= 2 AND COALESCE(HSL.NILAI_X,0) < 4 THEN 'Sederhana, cenderung diam, cenderung pemalu, tidak suka menon- jolkan diri.'
			WHEN COALESCE(HSL.NILAI_X,0) >= 4 AND COALESCE(HSL.NILAI_X,0) < 6 THEN 'Mengharapkan pengakuan lingkungan dan tidak mau diabaikan tetapi tidak mencari-cari perhatian.'
			WHEN COALESCE(HSL.NILAI_X,0) >= 6 AND COALESCE(HSL.NILAI_X,0) < 10 THEN 'Bangga akan diri dan gayanya sendiri, senang menjadi pusat perha- tian, mengharapkan penghargaan dari lingkungan. Mencari-cari perhatian dan suka menyombongkan diri.'
			END INFO_X
			, COALESCE(HSL.NILAI_P,0) NILAI_P
			, CASE 
			WHEN COALESCE(HSL.NILAI_P,0) < 2 THEN 'Permisif, akan memberikan kesempatan pada orang lain untuk memimpin. Tidak mau mengontrol orang lain dan tidak mau mempertanggung jawabkan hasil kerja bawahannya.'
			WHEN COALESCE(HSL.NILAI_P,0) >= 2 AND COALESCE(HSL.NILAI_P,0) < 4 THEN 'Enggan mengontrol org lain & tidak mau mempertanggung jawabkan hasil kerja bawahannya, lebih memberi kebebasan kpd bawahan utk memilih cara sendiri dlm penyelesaian tugas dan meminta bawahan  utk mempertanggungjawabkan hasilnya masing-masing.'
			WHEN COALESCE(HSL.NILAI_P,0) = 4 THEN 'Cenderung enggan melakukan fungsi pengarahan, pengendalian dan pengawasan, kurang aktif memanfaatkan kapasitas bawahan secara optimal, cenderung bekerja sendiri dalam mencapai tujuan kelompok.'
			WHEN COALESCE(HSL.NILAI_P,0) = 5 THEN 'Bertanggung jawab, akan melakukan fungsi pengarahan, pengendalian dan pengawasan, tapi tidak mendominasi.'
			WHEN COALESCE(HSL.NILAI_P,0) > 5 AND COALESCE(HSL.NILAI_P,0) < 8 THEN 'Dominan dan bertanggung jawab, akan melakukan fungsi pengarahan, pengendalian dan pengawasan.'
			WHEN COALESCE(HSL.NILAI_P,0) >= 8 AND COALESCE(HSL.NILAI_P,0) < 10 THEN 'Sangat dominan, sangat mempengaruhi & mengawasi org lain, bertanggung jawab atas tindakan & hasil kerja bawahan. Posesif, tdk ingin berada di  bawah pimpinan org lain, cemas bila tdk berada di posisi pemimpin,  mungkin sulit utk bekerja sama dgn rekan yg sejajar kedudukannya.'
			END INFO_P
			, COALESCE(HSL.NILAI_A,0) NILAI_A
			, CASE 
			WHEN COALESCE(HSL.NILAI_A,0) < 5 THEN 'Tidak kompetitif, mapan, puas. Tidak terdorong untuk menghasilkan prestasi, tdk berusaha utk mencapai sukses, membutuhkan dorongan dari luar diri, tidak berinisiatif, tidak memanfaatkan kemampuan diri secara optimal, ragu akan tujuan diri, misalnya sbg akibat promosi / perubahan struktur jabatan.'
			WHEN COALESCE(HSL.NILAI_A,0) >= 5 AND COALESCE(HSL.NILAI_A,0) < 8 THEN 'Tahu akan tujuan yang ingin dicapainya dan dapat merumuskannya, realistis akan kemampuan diri, dan berusaha untuk mencapai target.'
			WHEN COALESCE(HSL.NILAI_A,0) >= 8 AND COALESCE(HSL.NILAI_A,0) < 10 THEN 'Sangat berambisi utk berprestasi dan menjadi yg terbaik, menyukai tantangan, cenderung mengejar kesempurnaan, menetapkan target yg tinggi, self-starter merumuskan kerja dg baik. Tdk realistis akan kemampuannya, sulit dipuaskan, mudah kecewa, harapan yg tinggi mungkin mengganggu org lain.'
			END INFO_A
			, COALESCE(HSL.NILAI_N,0) NILAI_N
			, CASE 
			WHEN COALESCE(HSL.NILAI_N,0) < 3 THEN 'Tidak terlalu merasa perlu untuk menuntaskan sendiri tugas-tugasnya, senang	menangani beberapa pekerjaan sekaligus, mudah mendelegasikan tugas.	Komitmen rendah, cenderung meninggalkan tugas sebelum tuntas, konsentrasi mudah buyar, mungkin suka berpindah pekerjaan.'
			WHEN COALESCE(HSL.NILAI_N,0) >= 3 AND COALESCE(HSL.NILAI_N,0) < 6 THEN 'Cukup memiliki komitmen untuk menuntaskan tugas, akan tetapi jika memungkinkan akan mendelegasikan sebagian dari pekerjaannya kepada orang lain.'
			WHEN COALESCE(HSL.NILAI_N,0) >= 6 AND COALESCE(HSL.NILAI_N,0) < 8 THEN 'Komitmen tinggi, lebih suka menangani pekerjaan satu demi satu, akan tetapi masih dapat mengubah prioritas jika terpaksa.'
			WHEN COALESCE(HSL.NILAI_N,0) >= 8 AND COALESCE(HSL.NILAI_N,0) < 10 THEN 'Memiliki komitmen yg sangat tinggi thd tugas, sangat ingin menyelesaikan tugas, tekun dan tuntas dlm menangani pekerjaan satu demi satu hingga tuntas. Perhatian terpaku pada satu tugas, sulit utk menangani beberapa	pekerjaan sekaligus, sulit di interupsi, tidak melihat masalah sampingan.'
			END INFO_N
			, COALESCE(HSL.NILAI_G,0) NILAI_G
			, CASE 
			WHEN COALESCE(HSL.NILAI_G,0) < 3 THEN 'Santai, kerja adalah sesuatu yang menyenangkan-bukan beban yg membutuhkan usaha besar. Mungkin termotivasi utk mencari cara atau sistem yg dpt mempermudah dirinya dlm menyelesaikan pekerjaan, akan berusaha menghindari kerja keras, sehingga dapat memberi kesan malas.'
			WHEN COALESCE(HSL.NILAI_G,0) >= 3 AND COALESCE(HSL.NILAI_G,0) < 5 THEN 'Bekerja keras sesuai tuntutan, menyalurkan usahanya untuk hal-hal yang bermanfaat / menguntungkan.'
			WHEN COALESCE(HSL.NILAI_G,0) >= 5 AND COALESCE(HSL.NILAI_G,0) < 8 THEN 'Bekerja keras, tetapi jelas tujuan yg ingin dicapainya.'
			WHEN COALESCE(HSL.NILAI_G,0) >= 8 AND COALESCE(HSL.NILAI_G,0) < 10 THEN 'Ingin tampil sbg pekerja keras, sangat suka bila orang lain memandangnya sbg pekerja keras. Cenderung menciptakan pekerjaan	yang tidak perlu agar terlihat tetap sibuk, kadang kala tanpa tujuan yang jelas.'
			END INFO_G
			, COALESCE(HSL.NILAI_L,0) NILAI_L
			, CASE 
			WHEN COALESCE(HSL.NILAI_L,0) < 2 THEN 'Puas dengan peran sebagai bawahan, memberikan kesempatan  pada orang lain untuk memimpin, tidak dominan. Tidak percaya diri; sama sekali tidak berminat untuk berperan sebagai pemimpin; ber- sikap pasif dalam kelompok.'
			WHEN COALESCE(HSL.NILAI_L,0) >= 2 AND COALESCE(HSL.NILAI_L,0) < 4 THEN 'Tidak percaya diri dan tidak ingin memimpin atau mengawasi orang lain.'
			WHEN COALESCE(HSL.NILAI_L,0) = 4 THEN 'Kurang percaya diri dan kurang berminat utk menjadi pemimpin'
			WHEN COALESCE(HSL.NILAI_L,0) = 5 THEN 'Cukup percaya diri, tidak secara aktif mencari posisi kepemimpinan akan tetapi juga tidak akan menghindarinya.'
			WHEN COALESCE(HSL.NILAI_L,0) > 5 AND COALESCE(HSL.NILAI_L,0) < 8 THEN 'Percaya diri dan ingin berperan sebagai pemimpin.'
			WHEN COALESCE(HSL.NILAI_L,0) >= 8 AND COALESCE(HSL.NILAI_L,0) < 10 THEN 'Sangat percaya diri utk berperan sbg atasan & sangat mengharapkan posisi tersebut. Lebih mementingkan citra & status kepemimpinannya dari pada efektifitas kelompok, mungkin akan tampil angkuh atau terlalu percaya diri.'
			END INFO_L
			, COALESCE(HSL.NILAI_I,0) NILAI_I
			, CASE 
			WHEN COALESCE(HSL.NILAI_I,0) < 2 THEN 'Sangat berhati - hati, memikirkan langkah- langkahnya secara ber- sungguh - sungguh. Lamban dlm mengambil keputusan, terlalu lama merenung, cenderung menghindar mengambil keputusan.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 2 AND COALESCE(HSL.NILAI_I,0) < 4 THEN 'Enggan mengambil keputusan.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 4 AND COALESCE(HSL.NILAI_I,0) < 6 THEN 'Berhati - hati dlm pengambilan keputusan.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 6 AND COALESCE(HSL.NILAI_I,0) < 8 THEN 'Cukup percaya diri dlm pengambilan keputusan, mau mengambil resiko, dpt memutuskan dgn cepat, mengikuti alur logika.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 8 AND COALESCE(HSL.NILAI_I,0) < 10 THEN 'Sangat yakin dl mengambil keputusan, cepat tanggap thd situasi, berani mengambil resiko, mau memanfaatkan kesempatan. Impulsif, dpt mem- buat keputusan yg tdk praktis, cenderung lebih mementingkan kecepatan daripada akurasi, tdk sabar, cenderung meloncat pd keputusan.'
			END INFO_I
			, COALESCE(HSL.NILAI_T,0) NILAI_T
			, CASE 
			WHEN COALESCE(HSL.NILAI_T,0) < 4 THEN 'Santai. Kurang peduli akan waktu, kurang memiliki rasa urgensi,membuang-buang waktu, bukan pekerja yang tepat waktu.'
			WHEN COALESCE(HSL.NILAI_T,0) >= 4 AND COALESCE(HSL.NILAI_T,0) < 7 THEN 'Cukup aktif dalam segi mental, dapat menyesuaikan tempo kerjanya dengan tuntutan pekerjaan / lingkungan.'
			WHEN COALESCE(HSL.NILAI_T,0) >= 7 AND COALESCE(HSL.NILAI_T,0) < 10 THEN 'Cekatan, selalu siaga, bekerja cepat, ingin segera menyelesaikantugas.  Negatifnya : Tegang, cemas, impulsif, mungkin ceroboh,banyak gerakan yang tidak perlu.'
			END INFO_T
			, COALESCE(HSL.NILAI_V,0) NILAI_V
			, CASE 
			WHEN COALESCE(HSL.NILAI_V,0) < 3 THEN 'Cocok untuk pekerjaan  di belakang meja. Cenderung lamban,tidak tanggap, mudah lelah, daya tahan lemah.'
			WHEN COALESCE(HSL.NILAI_V,0) >= 3 AND COALESCE(HSL.NILAI_V,0) < 7 THEN 'Dapat bekerja di belakang meja dan senang jika sesekali harusterjun ke lapangan atau melaksanakan tugas-tugas yang bersifat mobile.'
			WHEN COALESCE(HSL.NILAI_V,0) >= 7 AND COALESCE(HSL.NILAI_V,0) < 10 THEN 'Menyukai aktifitas fisik ( a.l. : olah raga), enerjik, memiliki staminauntuk menangani tugas-tugas berat, tidak mudah lelah. Tidak betahduduk lama, kurang dapat konsentrasi di belakang meja.'
			END INFO_V
			, COALESCE(HSL.NILAI_S,0) NILAI_S
			, CASE 
			WHEN COALESCE(HSL.NILAI_S,0) < 3 THEN 'Dpt. bekerja sendiri, tdk membutuhkan kehadiran org lain. Menarik diri, kaku dlm bergaul, canggung dlm situasi sosial, lebih memperha- tikan hal - hal lain daripada manusia.'
			WHEN COALESCE(HSL.NILAI_S,0) >= 3 AND COALESCE(HSL.NILAI_S,0) < 5 THEN 'Kurang percaya diri & kurang aktif dlm menjalin hubungan sosial.'
			WHEN COALESCE(HSL.NILAI_S,0) >= 5 AND COALESCE(HSL.NILAI_S,0) < 10 THEN 'Percaya diri & sangat senang bergaul, menyukai interaksi sosial, bisa men- ciptakan suasana yg menyenangkan, mempunyai inisiatif & mampu men- jalin hubungan & komunikasi, memperhatikan org lain. Mungkin membuang- buang waktu utk aktifitas sosial, kurang peduli akan penyelesaian tugas.'
			END INFO_S
			, COALESCE(HSL.NILAI_R,0) NILAI_R
			, CASE 
			WHEN COALESCE(HSL.NILAI_R,0) < 4 THEN 'Tipe pelaksana, praktis - pragmatis, mengandalkan pengalaman masa lalu dan intuisi. Bekerja tanpa perencanaan, mengandalkanperasaan.'
			WHEN COALESCE(HSL.NILAI_R,0) >= 4 AND COALESCE(HSL.NILAI_R,0) < 6 THEN 'Pertimbangan mencakup aspek teoritis ( konsep atau pemikiran baru ) dan aspek praktis ( pengalaman ) secara berimbang.'
			WHEN COALESCE(HSL.NILAI_R,0) >= 6 AND COALESCE(HSL.NILAI_R,0) < 8 THEN 'Suka memikirkan suatu problem secara mendalam, merujuk pada teori dan konsep.'
			WHEN COALESCE(HSL.NILAI_R,0) >= 8 AND COALESCE(HSL.NILAI_R,0) < 10 THEN 'Tipe pemikir, sangat berminat pada gagasan, konsep, teori,menca-ri alternatif baru, menyukai perencanaan. Mungkin sulit dimengerti oleh orang lain, terlalu teoritis dan tidak praktis, mengawang-awangdan berbelit-belit.'
			END INFO_R
			, COALESCE(HSL.NILAI_D,0) NILAI_D
			, CASE 
			WHEN COALESCE(HSL.NILAI_D,0) < 2 THEN 'Melihat pekerjaan scr makro, membedakan hal penting dari yg kurang penting,	mendelegasikan detil pd org lain, generalis. Menghindari detail, konsekuensinya mungkin bertindak tanpa data yg cukup/akurat, bertindak ceroboh pd hal yg butuh kecermatan. Dpt mengabaikan proses yg vital dlm evaluasi data.'
			WHEN COALESCE(HSL.NILAI_D,0) >= 2 AND COALESCE(HSL.NILAI_D,0) < 4 THEN 'Cukup peduli akan akurasi dan kelengkapan data.'
			WHEN COALESCE(HSL.NILAI_D,0) >= 4 AND COALESCE(HSL.NILAI_D,0) < 7 THEN 'Tertarik untuk menangani sendiri detail.'
			WHEN COALESCE(HSL.NILAI_D,0) >= 7 AND COALESCE(HSL.NILAI_D,0) < 10 THEN 'Sangat menyukai detail, sangat peduli akan akurasi dan kelengkapan data. Cenderung terlalu terlibat dengan detail sehingga melupakan tujuan utama.'
			END INFO_D
			, COALESCE(HSL.NILAI_C,0) NILAI_C
			, CASE 
			WHEN COALESCE(HSL.NILAI_C,0) < 3 THEN 'Lebih mementingkan fleksibilitas daripada struktur, pendekatan kerja lebih ditentukan oleh situasi daripada oleh perencanaan sebelumnya, mudah beradaptasi. Tidak mempedulikan keteraturan	atau kerapihan, ceroboh.'
			WHEN COALESCE(HSL.NILAI_C,0) >= 3 AND COALESCE(HSL.NILAI_C,0) < 5 THEN 'Fleksibel tapi masih cukup memperhatikan keteraturan atau sistematika kerja.'
			WHEN COALESCE(HSL.NILAI_C,0) >= 5 AND COALESCE(HSL.NILAI_C,0) < 7 THEN 'Memperhatikan keteraturan dan sistematika kerja, tapi cukup fleksibel.'
			WHEN COALESCE(HSL.NILAI_C,0) >= 7 AND COALESCE(HSL.NILAI_C,0) < 10 THEN 'Sistematis, bermetoda, berstruktur, rapi dan teratur, dapat menata tugas dengan baik. Cenderung kaku, tidak fleksibel.'
			END INFO_C
			, COALESCE(HSL.NILAI_E,0) NILAI_E
			, CASE 
			WHEN COALESCE(HSL.NILAI_E,0) < 2 THEN 'Sangat terbuka, terus terang, mudah terbaca (dari air muka, tindakan, perkataan, sikap). Tidak dapat mengendalikan emosi, cepat  bereaksi, kurang mengindahkan/tidak mempunyai nilai yg meng- haruskannya menahan emosi.'
			WHEN COALESCE(HSL.NILAI_E,0) >= 2 AND COALESCE(HSL.NILAI_E,0) < 4 THEN 'Terbuka, mudah mengungkap pendapat atau perasaannya menge- nai suatu hal kepada org lain.'
			WHEN COALESCE(HSL.NILAI_E,0) >= 4 AND COALESCE(HSL.NILAI_E,0) < 7 THEN 'Mampu mengungkap atau menyimpan perasaan, dapat mengen- dalikan emosi.'
			WHEN COALESCE(HSL.NILAI_E,0) >= 7 AND COALESCE(HSL.NILAI_E,0) < 10 THEN 'Mampu menyimpan pendapat atau perasaannya, tenang, dapat  mengendalikan emosi, menjaga jarak. Tampil pasif dan tidak acuh, mungkin sulit mengungkapkan emosi/perasaan/pandangan.'
			END INFO_E
			, COALESCE(HSL.NILAI_G,0) + COALESCE(HSL.NILAI_L,0) + COALESCE(HSL.NILAI_I,0) + COALESCE(HSL.NILAI_T,0) + COALESCE(HSL.NILAI_V,0) + COALESCE(HSL.NILAI_S,0) + COALESCE(HSL.NILAI_R,0) + COALESCE(HSL.NILAI_D,0) + COALESCE(HSL.NILAI_C,0) + COALESCE(HSL.NILAI_E,0) TOTAL_1
			, COALESCE(HSL.NILAI_N,0) + COALESCE(HSL.NILAI_A,0) + COALESCE(HSL.NILAI_P,0) + COALESCE(HSL.NILAI_X,0) + COALESCE(HSL.NILAI_B,0) + COALESCE(HSL.NILAI_O,0) + COALESCE(HSL.NILAI_Z,0) + COALESCE(HSL.NILAI_K,0) + COALESCE(HSL.NILAI_F,0) + COALESCE(HSL.NILAI_W,0) TOTAL_2
			, COALESCE(HSL.JUMLAH_TOTAL,0) TOTAL
			, CASE WHEN HSL.JUMLAH_RATA - FLOOR(HSL.JUMLAH_RATA) > .00 THEN HSL.JUMLAH_RATA ELSE CAST(HSL.JUMLAH_RATA AS INTEGER) END RATA_RATA
			,  JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN
		(
			SELECT a.no_urut NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			AA.UJIAN_ID, AA.PEGAWAI_ID, AA.UJIAN_TAHAP_ID
			, COALESCE(W.NILAI,0) NILAI_W, COALESCE(F.NILAI,0) NILAI_F, COALESCE(K.NILAI,0) NILAI_K, COALESCE(Z.NILAI,0) NILAI_Z, COALESCE(O.NILAI,0) NILAI_O, COALESCE(B.NILAI,0) NILAI_B, COALESCE(X.NILAI,0) NILAI_X, COALESCE(P.NILAI,0) NILAI_P, COALESCE(A.NILAI,0) NILAI_A, COALESCE(N.NILAI,0) NILAI_N
			, COALESCE(G.NILAI,0) NILAI_G, COALESCE(L.NILAI,0) NILAI_L, COALESCE(I.NILAI,0) NILAI_I, COALESCE(T.NILAI,0) NILAI_T, COALESCE(V.NILAI,0) NILAI_V, COALESCE(S.NILAI,0) NILAI_S, COALESCE(R.NILAI,0) NILAI_R, COALESCE(D.NILAI,0) NILAI_D, COALESCE(C.NILAI,0) NILAI_C, COALESCE(E.NILAI,0) NILAI_E
			, COALESCE(W.NILAI,0) + COALESCE(F.NILAI,0) + COALESCE(K.NILAI,0) + COALESCE(Z.NILAI,0) + COALESCE(O.NILAI,0) + COALESCE(B.NILAI,0) + COALESCE(X.NILAI,0) + COALESCE(P.NILAI,0) + COALESCE(A.NILAI,0) +COALESCE(N.NILAI,0) + COALESCE(G.NILAI,0) + COALESCE(L.NILAI,0) + COALESCE(I.NILAI,0) + COALESCE(T.NILAI,0) + COALESCE(V.NILAI,0) + COALESCE(S.NILAI,0) + COALESCE(R.NILAI,0) + COALESCE(D.NILAI,0) + COALESCE(C.NILAI,0) + COALESCE(E.NILAI,0) JUMLAH_TOTAL
			, 
			ROUND
			(
				(
				COALESCE(W.NILAI,0) + COALESCE(F.NILAI,0) + COALESCE(K.NILAI,0) + COALESCE(Z.NILAI,0) + COALESCE(O.NILAI,0) + COALESCE(B.NILAI,0) + COALESCE(X.NILAI,0) + COALESCE(P.NILAI,0) + COALESCE(A.NILAI,0) +COALESCE(N.NILAI,0) + COALESCE(G.NILAI,0) + COALESCE(L.NILAI,0) + COALESCE(I.NILAI,0) + COALESCE(T.NILAI,0) + COALESCE(V.NILAI,0) + COALESCE(S.NILAI,0) + COALESCE(R.NILAI,0) + COALESCE(D.NILAI,0) + COALESCE(C.NILAI,0) + COALESCE(E.NILAI,0) 
				) / 20
			,2
			) JUMLAH_RATA
			FROM
			(
				SELECT 
				A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
			) AA
			LEFT JOIN
			(
				SELECT 
				A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(GRADE_A),0) NILAI
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				AND B.SOAL_PAPI_ID IN (10, 20, 30, 40, 50, 60, 70, 80, 90)
				GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
			) W ON AA.PEGAWAI_ID = W.PEGAWAI_ID AND AA.UJIAN_ID = W.UJIAN_ID AND AA.UJIAN_TAHAP_ID = W.UJIAN_TAHAP_ID AND AA.UJIAN_ID = W.UJIAN_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (9, 19, 29, 39, 49, 59, 69, 79)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (10)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) F ON AA.PEGAWAI_ID = F.PEGAWAI_ID AND AA.UJIAN_ID = F.UJIAN_ID AND AA.UJIAN_TAHAP_ID = F.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (8, 18, 28, 38, 48, 58, 68)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (9, 20)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) K ON AA.PEGAWAI_ID = K.PEGAWAI_ID AND AA.UJIAN_ID = K.UJIAN_ID AND AA.UJIAN_TAHAP_ID = K.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (7, 17, 27, 37, 47, 57)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (8, 19, 30)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) Z ON AA.PEGAWAI_ID = Z.PEGAWAI_ID AND AA.UJIAN_ID = Z.UJIAN_ID AND AA.UJIAN_TAHAP_ID = Z.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (6, 16, 26, 36, 46)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (7, 18, 29, 40)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) O ON AA.PEGAWAI_ID = O.PEGAWAI_ID AND AA.UJIAN_ID = O.UJIAN_ID AND AA.UJIAN_TAHAP_ID = O.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (5, 15, 25, 35)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (6, 17, 28, 39, 50)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) B ON AA.PEGAWAI_ID = B.PEGAWAI_ID AND AA.UJIAN_ID = B.UJIAN_ID AND AA.UJIAN_TAHAP_ID = B.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (4, 14, 24)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (5, 16, 27, 38, 49, 60)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) X ON AA.PEGAWAI_ID = X.PEGAWAI_ID AND AA.UJIAN_ID = X.UJIAN_ID AND AA.UJIAN_TAHAP_ID = X.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (3, 13)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (4, 15, 26, 37, 48, 59, 70)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) P ON AA.PEGAWAI_ID = P.PEGAWAI_ID AND AA.UJIAN_ID = P.UJIAN_ID AND AA.UJIAN_TAHAP_ID = P.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (2)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (3, 14, 25, 36, 47, 58, 69, 80)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) A ON AA.PEGAWAI_ID = A.PEGAWAI_ID AND AA.UJIAN_ID = A.UJIAN_ID AND AA.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (2, 13, 24, 35, 46, 57, 68, 79, 90)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) N ON AA.PEGAWAI_ID = N.PEGAWAI_ID AND AA.UJIAN_ID = N.UJIAN_ID AND AA.UJIAN_TAHAP_ID = N.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (81, 71, 61, 51, 41, 31, 21, 11, 1)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) G ON AA.PEGAWAI_ID = G.PEGAWAI_ID AND AA.UJIAN_ID = G.UJIAN_ID AND AA.UJIAN_TAHAP_ID = G.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (82, 72, 62, 52, 42, 32, 22, 12)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (81)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) L ON AA.PEGAWAI_ID = L.PEGAWAI_ID AND AA.UJIAN_ID = L.UJIAN_ID AND AA.UJIAN_TAHAP_ID = L.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (83, 73, 63, 53, 43, 33, 23)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (71, 82)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) I ON AA.PEGAWAI_ID = I.PEGAWAI_ID AND AA.UJIAN_ID = I.UJIAN_ID AND AA.UJIAN_TAHAP_ID = I.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (84, 74, 64, 54, 44, 34)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (61, 72, 83)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) T ON AA.PEGAWAI_ID = T.PEGAWAI_ID AND AA.UJIAN_ID = T.UJIAN_ID AND AA.UJIAN_TAHAP_ID = T.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (85, 75, 65, 55, 45)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (51, 62, 73, 84)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) V ON AA.PEGAWAI_ID = V.PEGAWAI_ID AND AA.UJIAN_ID = V.UJIAN_ID AND AA.UJIAN_TAHAP_ID = V.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (56, 66, 76, 86)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (41, 52, 63, 74, 85)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) S ON AA.PEGAWAI_ID = S.PEGAWAI_ID AND AA.UJIAN_ID = S.UJIAN_ID AND AA.UJIAN_TAHAP_ID = S.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (67, 77, 87)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (31, 42, 53, 64, 75, 86)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) R ON AA.PEGAWAI_ID = R.PEGAWAI_ID AND AA.UJIAN_ID = R.UJIAN_ID AND AA.UJIAN_TAHAP_ID = R.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (78, 88)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (21, 32, 43, 54, 65, 76, 87)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) D ON AA.PEGAWAI_ID = D.PEGAWAI_ID AND AA.UJIAN_ID = D.UJIAN_ID AND AA.UJIAN_TAHAP_ID = D.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (89)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (11, 22, 33, 44, 55, 66, 77, 88)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) C ON AA.PEGAWAI_ID = C.PEGAWAI_ID AND AA.UJIAN_ID = C.UJIAN_ID AND AA.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (1, 12, 23, 34, 45, 56, 67, 78, 89)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) E ON AA.PEGAWAI_ID = E.PEGAWAI_ID AND AA.UJIAN_ID = E.UJIAN_ID AND AA.UJIAN_TAHAP_ID = E.UJIAN_TAHAP_ID
			WHERE 1=1
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1
		";
		// AND A.PEGAWAI_ID = 886

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

    function getCountByParamsMonitoringPapiHasil($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(

			SELECT AA.UJIAN_ID, AA.PEGAWAI_ID, AA.UJIAN_TAHAP_ID
			FROM
			(
				SELECT 
				A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
			) AA
			WHERE 1=1
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		// echo $str;exit();

		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringPf16($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
			, A.NAMA_PEGAWAI, A.NIP_BARU
			, NORMA_NILAI_MD NILAI_MD
			, CASE WHEN NORMA_NILAI_MD <= 7 THEN NORMA_NILAI_A ELSE NORMA_NILAI_A - 1 END NILAI_A
			, NORMA_NILAI_B NILAI_B
			, CASE WHEN NORMA_NILAI_MD <= 6 THEN NORMA_NILAI_C WHEN NORMA_NILAI_MD <= 9 THEN NORMA_NILAI_C - 1 ELSE NORMA_NILAI_C - 2 END NILAI_C
			, NORMA_NILAI_E NILAI_E
			, NORMA_NILAI_F NILAI_F
			, CASE WHEN NORMA_NILAI_MD <= 7 THEN NORMA_NILAI_G ELSE NORMA_NILAI_G - 1 END NILAI_G
			, CASE WHEN NORMA_NILAI_MD <= 7 THEN NORMA_NILAI_H ELSE NORMA_NILAI_H - 1 END NILAI_H
			, NORMA_NILAI_I NILAI_I
			, CASE WHEN NORMA_NILAI_MD <= 7 THEN NORMA_NILAI_L ELSE NORMA_NILAI_L + 1 END NILAI_L
			, NORMA_NILAI_M NILAI_M
			, CASE WHEN NORMA_NILAI_MD <= 7 THEN NORMA_NILAI_N ELSE NORMA_NILAI_N + 1 END NILAI_N
			, CASE WHEN NORMA_NILAI_MD <= 6 THEN NORMA_NILAI_O WHEN NORMA_NILAI_MD <= 9 THEN NORMA_NILAI_O + 1 ELSE NORMA_NILAI_O + 2 END NILAI_O
			, NORMA_NILAI_Q1 NILAI_Q1
			, CASE WHEN NORMA_NILAI_MD <= 7 THEN NORMA_NILAI_Q2 ELSE NORMA_NILAI_Q2 + 1 END NILAI_Q2
			, CASE WHEN NORMA_NILAI_MD <= 6 THEN NORMA_NILAI_Q3 WHEN NORMA_NILAI_MD <= 9 THEN NORMA_NILAI_Q3 - 1 ELSE NORMA_NILAI_Q3 - 2 END NILAI_Q3
			, CASE WHEN NORMA_NILAI_MD <= 6 THEN NORMA_NILAI_Q4 WHEN NORMA_NILAI_MD <= 9 THEN NORMA_NILAI_Q4 + 1 ELSE NORMA_NILAI_Q4 + 2 END NILAI_Q4
			, 
			JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM
		(
			SELECT
			A.* 
			, cat.pf16_sw(NORMA_MD, RW_MD_NILAI) NORMA_NILAI_MD
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_A'), RW_A_NILAI) NORMA_NILAI_A
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_B'), RW_B_NILAI) NORMA_NILAI_B
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_C'), RW_C_NILAI) NORMA_NILAI_C
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_E'), RW_E_NILAI) NORMA_NILAI_E
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_F'), RW_F_NILAI) NORMA_NILAI_F
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_G'), RW_G_NILAI) NORMA_NILAI_G
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_H'), RW_H_NILAI) NORMA_NILAI_H
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_I'), RW_I_NILAI) NORMA_NILAI_I
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_L'), RW_L_NILAI) NORMA_NILAI_L
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_M'), RW_M_NILAI) NORMA_NILAI_M
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_N'), RW_N_NILAI) NORMA_NILAI_N
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_O'), RW_O_NILAI) NORMA_NILAI_O
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_Q1'), RW_Q1_NILAI) NORMA_NILAI_Q1
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_Q2'), RW_Q2_NILAI) NORMA_NILAI_Q2
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_Q3'), RW_Q3_NILAI) NORMA_NILAI_Q3
			, cat.pf16_sw((NORMA_PENDIDIKAN || '_Q4'), RW_Q4_NILAI) NORMA_NILAI_Q4
			FROM
			(
				SELECT A.*
				, 'MD_' || PEGAWAI_JENIS_KELAMIN NORMA_MD
				, PEGAWAI_PENDIDIKAN || '_' || PEGAWAI_JENIS_KELAMIN NORMA_PENDIDIKAN
				, COALESCE(RW_MD.NILAI,0) RW_MD_NILAI, COALESCE(RW_A.NILAI,0) RW_A_NILAI, COALESCE(RW_B.NILAI,0) RW_B_NILAI
				, COALESCE(RW_C.NILAI,0) RW_C_NILAI, COALESCE(RW_E.NILAI,0) RW_E_NILAI, COALESCE(RW_F.NILAI,0) RW_F_NILAI
				, COALESCE(RW_G.NILAI,0) RW_G_NILAI, COALESCE(RW_H.NILAI,0) RW_H_NILAI, COALESCE(RW_I.NILAI,0) RW_I_NILAI
				, COALESCE(RW_L.NILAI,0) RW_L_NILAI, COALESCE(RW_M.NILAI,0) RW_M_NILAI, COALESCE(RW_N.NILAI,0) RW_N_NILAI
				, COALESCE(RW_O.NILAI,0) RW_O_NILAI, COALESCE(RW_Q1.NILAI,0) RW_Q1_NILAI, COALESCE(RW_Q2.NILAI,0) RW_Q2_NILAI
				, COALESCE(RW_Q3.NILAI,0) RW_Q3_NILAI, COALESCE(RW_Q4.NILAI,0) RW_Q4_NILAI
				FROM
				(
					SELECT
					UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
					, A.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN
					, CASE WHEN COALESCE(NULLIF(C.KODE, ''), NULL) IS NULL THEN 'MU' ELSE C.KODE END PEGAWAI_PENDIDIKAN
					, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
					FROM cat.ujian_pegawai_daftar B
					INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
					LEFT JOIN simpeg.pendidikan C ON A.PENDIDIKAN = CAST(C.PENDIDIKAN_ID AS character varying)
					WHERE 1=1
				) A
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (664, 681, 698, 715, 732, 749, 766)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_MD ON A.UJIAN_ID = RW_MD.UJIAN_ID AND A.PEGAWAI_ID = RW_MD.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_MD.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (665, 682, 699, 716, 733, 750, 767)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_A ON A.UJIAN_ID = RW_A.UJIAN_ID AND A.PEGAWAI_ID = RW_A.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_A.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (666, 683, 700, 717, 734, 751, 768)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_B ON A.UJIAN_ID = RW_B.UJIAN_ID AND A.PEGAWAI_ID = RW_B.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_B.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (667, 684, 701, 718, 735, 752)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_C ON A.UJIAN_ID = RW_C.UJIAN_ID AND A.PEGAWAI_ID = RW_C.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_C.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (668, 685, 702, 719, 736, 753)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_E ON A.UJIAN_ID = RW_E.UJIAN_ID AND A.PEGAWAI_ID = RW_E.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_E.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (669, 686, 703, 720, 737, 754)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_F ON A.UJIAN_ID = RW_F.UJIAN_ID AND A.PEGAWAI_ID = RW_F.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_F.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (670, 687, 704, 721, 738, 755)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_G ON A.UJIAN_ID = RW_G.UJIAN_ID AND A.PEGAWAI_ID = RW_G.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_G.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (671, 688, 705, 722, 739, 756)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_H ON A.UJIAN_ID = RW_H.UJIAN_ID AND A.PEGAWAI_ID = RW_H.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_H.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (672, 689, 706, 723, 740, 757)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_I ON A.UJIAN_ID = RW_I.UJIAN_ID AND A.PEGAWAI_ID = RW_I.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_I.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (673, 690, 707, 724, 741, 758)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_L ON A.UJIAN_ID = RW_L.UJIAN_ID AND A.PEGAWAI_ID = RW_L.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_L.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (674, 691, 708, 725, 742, 759)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_M ON A.UJIAN_ID = RW_M.UJIAN_ID AND A.PEGAWAI_ID = RW_M.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_M.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (675, 692, 709, 726, 743, 760)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_N ON A.UJIAN_ID = RW_N.UJIAN_ID AND A.PEGAWAI_ID = RW_N.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_N.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (676, 693, 710, 727, 744, 761)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_O ON A.UJIAN_ID = RW_O.UJIAN_ID AND A.PEGAWAI_ID = RW_O.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_O.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (677, 694, 711, 728, 745, 762)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_Q1 ON A.UJIAN_ID = RW_Q1.UJIAN_ID AND A.PEGAWAI_ID = RW_Q1.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_Q1.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (678, 695, 712, 729, 746, 763)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_Q2 ON A.UJIAN_ID = RW_Q2.UJIAN_ID AND A.PEGAWAI_ID = RW_Q2.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_Q2.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (679, 696, 713, 730, 747, 764)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_Q3 ON A.UJIAN_ID = RW_Q3.UJIAN_ID AND A.PEGAWAI_ID = RW_Q3.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_Q3.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(GRADE_PROSENTASE) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.bank_soal_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID
					WHERE A.BANK_SOAL_ID IN (680, 697, 714, 731, 748, 765)
					GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
				) RW_Q4 ON A.UJIAN_ID = RW_Q4.UJIAN_ID AND A.PEGAWAI_ID = RW_Q4.PEGAWAI_ID AND A.JADWAL_TES_ID = RW_Q4.JADWAL_TES_ID
				WHERE 1=1
			) A
		) A
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringPf16($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NAMA_PEGAWAI, A.NIP_BARU
			FROM
			(
				SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				FROM cat.ujian_pegawai_daftar B
				INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
			) A
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringMbti($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$arrayData= array(
			array("WHERE"=>"60,52,45, 38,35, 31, 29,28, 20, 15, 11, 10,7,5, 2", "BAGI"=>15, "DIMENSI_KIRI"=>"I", "DIMENSI_KANAN"=>"E", "DIMENSI_KIRI_VALUE"=>5, "DIMENSI_KANAN_VALUE"=>9)
			, array("WHERE"=>"53, 51, 46, 43, 41, 36, 34, 27, 25, 22, 18, 16, 13, 8, 6", "BAGI"=>15, "DIMENSI_KIRI"=>"S", "DIMENSI_KANAN"=>"N", "DIMENSI_KIRI_VALUE"=>19, "DIMENSI_KANAN_VALUE"=>14)
			, array("WHERE"=>"58, 57, 55, 49, 48, 42, 39, 37, 23, 32, 30, 17, 9, 4, 14", "BAGI"=>15, "DIMENSI_KIRI"=>"T", "DIMENSI_KANAN"=>"F", "DIMENSI_KIRI_VALUE"=>20, "DIMENSI_KANAN_VALUE"=>6)
			, array("WHERE"=>"59, 56, 54, 50, 47, 44, 40, 33, 26, 24, 21, 19, 12, 3, 1", "BAGI"=>15, "DIMENSI_KIRI"=>"J", "DIMENSI_KANAN"=>"P", "DIMENSI_KIRI_VALUE"=>10, "DIMENSI_KANAN_VALUE"=>16)
		);
		// echo $arrayData[0]["WHERE"];exit();
		// print_r($arrayData);exit();

		$str = "
		SELECT A.*";

		for($i=0; $i < count($arrayData); $i++)
		{
			$indexData= $i+1;
			$separator= " || ' ' || ";
			if($i == 0)
				$separator= ", ";

			$str .= $separator." KONVERSI_".$indexData;
		}
		$str .= " KONVERSI_INFO";

		for($i=0; $i < count($arrayData); $i++)
		{
			$indexData= $i+1;
			$separator= " + ";
			if($i == 0)
				$separator= ", ";

			$str .= $separator." KONVERSI_NILAI_".$indexData;
		}
		$str .= " KONVERSI_JUMLAH";

		// for($i=0; $i < count($arrayData); $i++)
		// {
		// 	$indexData= $i+1;
		// 	$str .= " , KONVERSI_".$indexData.", KONVERSI_NILAI_".$indexData;
		// }

		$str .= " 
		, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM
		(
			SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN
			, CASE WHEN COALESCE(NULLIF(A.PENDIDIKAN, ''), NULL) IS NULL THEN 'MU' ELSE PENDIDIKAN END PEGAWAI_PENDIDIKAN
			, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		";

		for($i=0; $i < count($arrayData); $i++)
		{
			$indexData= $i+1;
			$str .= " LEFT JOIN
			(
				SELECT
				A.UJIAN_ID, A.PEGAWAI_ID
				, CASE WHEN NILAI_A > NILAI_B THEN '".$arrayData[$i]["DIMENSI_KIRI"]."' ELSE '".$arrayData[$i]["DIMENSI_KANAN"]."' END KONVERSI_".$indexData."
				, CASE WHEN NILAI_A > NILAI_B THEN ".$arrayData[$i]["DIMENSI_KIRI_VALUE"]." ELSE ".$arrayData[$i]["DIMENSI_KANAN_VALUE"]." END KONVERSI_NILAI_".$indexData."
				FROM
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
					, GENERATE_PERSEN(NILAI_A) NILAI_A, GENERATE_PERSEN(NILAI_B) NILAI_B
					FROM
					(
						SELECT 
						A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
						, (COALESCE(SUM(GRADE_A),0) / ".$arrayData[$i]["BAGI"].") * 100 NILAI_A, (COALESCE(SUM(GRADE_B),0) / ".$arrayData[$i]["BAGI"].") * 100 NILAI_B
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.mbti_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.MBTI_PILIHAN_ID
						WHERE 1=1 AND A.TIPE_UJIAN_ID = 41
						AND B.MBTI_SOAL_ID IN (".$arrayData[$i]["WHERE"].")
						GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
					) A
				) A
			) K_".$indexData." ON A.UJIAN_ID = K_".$indexData.".UJIAN_ID AND A.PEGAWAI_ID = K_".$indexData.".PEGAWAI_ID";
		}

		$str .= " WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringMbtiNew($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$arrayData= array(
			array("WHERE"=>"60,52,45, 38,35, 31, 29,28, 20, 15, 11, 10,7,5, 2", "BAGI"=>15, "DIMENSI_KIRI"=>"I", "DIMENSI_KANAN"=>"E", "DIMENSI_KIRI_VALUE"=>5, "DIMENSI_KANAN_VALUE"=>9)
			, array("WHERE"=>"53, 51, 46, 43, 41, 36, 34, 27, 25, 22, 18, 16, 13, 8, 6", "BAGI"=>15, "DIMENSI_KIRI"=>"S", "DIMENSI_KANAN"=>"N", "DIMENSI_KIRI_VALUE"=>19, "DIMENSI_KANAN_VALUE"=>14)
			, array("WHERE"=>"58, 57, 55, 49, 48, 42, 39, 37, 23, 32, 30, 17, 9, 4, 14", "BAGI"=>15, "DIMENSI_KIRI"=>"T", "DIMENSI_KANAN"=>"F", "DIMENSI_KIRI_VALUE"=>20, "DIMENSI_KANAN_VALUE"=>6)
			, array("WHERE"=>"59, 56, 54, 50, 47, 44, 40, 33, 26, 24, 21, 19, 12, 3, 1", "BAGI"=>15, "DIMENSI_KIRI"=>"J", "DIMENSI_KANAN"=>"P", "DIMENSI_KIRI_VALUE"=>10, "DIMENSI_KANAN_VALUE"=>16)
		);
		// echo $arrayData[0]["WHERE"];exit();
		// print_r($arrayData);exit();

		$str = "
		SELECT A.*";

		for($i=0; $i < count($arrayData); $i++)
		{
			$indexData= $i+1;
			$separator= " || '' || ";
			if($i == 0)
				$separator= ", ";

			$str .= $separator." KONVERSI_".$indexData;
		}
		$str .= " KONVERSI_INFO";

		for($i=0; $i < count($arrayData); $i++)
		{
			$indexData= $i+1;
			$separator= " + ";
			if($i == 0)
				$separator= ", ";

			$str .= $separator." KONVERSI_NILAI_".$indexData;
		}
		$str .= " KONVERSI_JUMLAH";
 

		// for($i=0; $i < count($arrayData); $i++)
		// {
		// 	$indexData= $i+1;
		// 	$str .= " , KONVERSI_".$indexData.", KONVERSI_NILAI_".$indexData;
		// }

		$str .= " 
		, NILAI_I, NILAI_E, NILAI_S, NILAI_N, NILAI_T, NILAI_F, NILAI_J, NILAI_P 
		, JA.NOMOR_URUT NOMOR_URUT_GENERATE 
		FROM
		(
			SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN
			, CASE WHEN COALESCE(NULLIF(A.PENDIDIKAN, ''), NULL) IS NULL THEN 'MU' ELSE PENDIDIKAN END PEGAWAI_PENDIDIKAN
			, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		";

		for($i=0; $i < count($arrayData); $i++)
		{
			$indexData= $i+1;
			$str .= " LEFT JOIN
			(
				SELECT
				A.UJIAN_ID, A.PEGAWAI_ID
				, CASE WHEN NILAI_A > NILAI_B THEN '".$arrayData[$i]["DIMENSI_KIRI"]."' ELSE '".$arrayData[$i]["DIMENSI_KANAN"]."' END KONVERSI_".$indexData."
				, CASE WHEN NILAI_A > NILAI_B THEN ".$arrayData[$i]["DIMENSI_KIRI_VALUE"]." ELSE ".$arrayData[$i]["DIMENSI_KANAN_VALUE"]." END KONVERSI_NILAI_".$indexData."
				, NILAI_A AS NILAI_".$arrayData[$i]["DIMENSI_KIRI"]."
				, NILAI_B AS NILAI_".$arrayData[$i]["DIMENSI_KANAN"]."
				FROM
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
					, GENERATE_PERSEN(NILAI_A) NILAI_A, GENERATE_PERSEN(NILAI_B) NILAI_B
					FROM
					(
						SELECT 
						A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
						, ROUND((COALESCE(SUM(GRADE_A),0) / ".$arrayData[$i]["BAGI"].") * 100,0) NILAI_A
						, ROUND((COALESCE(SUM(GRADE_B),0) / ".$arrayData[$i]["BAGI"].") * 100,0) NILAI_B
						FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
						INNER JOIN cat.mbti_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.MBTI_PILIHAN_ID
						WHERE 1=1 AND A.TIPE_UJIAN_ID = 41
						AND B.MBTI_SOAL_ID IN (".$arrayData[$i]["WHERE"].")
						GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
					) A
				) A
			) K_".$indexData." ON A.UJIAN_ID = K_".$indexData.".UJIAN_ID AND A.PEGAWAI_ID = K_".$indexData.".PEGAWAI_ID";
		}

		$str .= " WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMBTI_Deskripsi($paramsArray=array(), $jenis, $statement="")
	{
		 
		$str = "SELECT cat.mbtikesimpulan('".$jenis."') AS ROWCOUNT ";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
	}


    function getCountByParamsMonitoringMbti($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NAMA_PEGAWAI, A.NIP_BARU
			FROM
			(
				SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				FROM cat.ujian_pegawai_daftar B
				INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
			) A
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringDisc($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="order by NOMOR_URUT_GENERATE asc")
	{

		$arrPk= array("P", "K");

		$arrDisk= array(
			array("LABEL"=>"D", "KONDISI"=>"D")
			, array("LABEL"=>"I", "KONDISI"=>"I")
			, array("LABEL"=>"S", "KONDISI"=>"S")
			, array("LABEL"=>"C", "KONDISI"=>"C")
			, array("LABEL"=>"X", "KONDISI"=>"*")
		);

		$arrayData= array(
			array("LOOP_AWAL"=>1, "LOOP_AKHIR"=>8, "LINE"=>"1")
			, array("LOOP_AWAL"=>9, "LOOP_AKHIR"=>16, "LINE"=>"2")
			, array("LOOP_AWAL"=>17, "LOOP_AKHIR"=>24, "LINE"=>"3")
		);

		$str = "
		SELECT A.*
		,  JA.NOMOR_URUT NOMOR_URUT_GENERATE
		, R.NILAI_P_1, R.D_1, R.I_1, R.S_1, R.C_1, R.X_1
		, R.D_2, R.I_2, R.S_2, R.C_2, R.X_2
		, R.D_3, R.I_3, R.S_3, R.C_3";

		$str .= " FROM
		(
			SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		INNER JOIN
		(
			SELECT no_urut NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		";
			$str .= " 
			LEFT JOIN(
			SELECT
			A.*
			, D_1 - D_2 D_3, I_1 - I_2 I_3, S_1 - S_2 S_3
			, C_1 - C_2 C_3
			FROM
			(
			";

				$str .= " 
				SELECT
				A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID, A.NILAI_P_1
				";
				for($y=1; $y<=2; $y++)
				{
					$ylabel= $y - 1;
					for($dc=0; $dc < count($arrDisk); $dc++)
					{
						for($l=1; $l<=3; $l++)
						{
							$separator= " + ";
							if($l == 1)
								$separator= ", ";
							$str .= $separator.$arrDisk[$dc]["LABEL"]."_".$arrPk[$ylabel]."_".$l;
						}
						$str .= " ".$arrDisk[$dc]["LABEL"]."_".$y;
					}
				}

				$str .= " FROM
				(
		 		";

			 		$str .= " 
					SELECT
					A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID, A.NILAI_P_1
					";
						for($pk=0; $pk < count($arrPk); $pk++)
						{
							for($dc=0; $dc < count($arrDisk); $dc++)
							{
								for($i=0; $i < count($arrayData); $i++)
								{
									for($x=$arrayData[$i]["LOOP_AWAL"]; $x <= $arrayData[$i]["LOOP_AKHIR"]; $x++)
									{
										$separator= " + ";
										if($x == $arrayData[$i]["LOOP_AWAL"])
											$separator= ", ";

										$str .= $separator."(CASE WHEN KONVERSI_NILAI_".$arrPk[$pk]."_".$x." = '".$arrDisk[$dc]["KONDISI"]."' THEN 1 ELSE 0 END)";
									}
									$str .= " ".$arrDisk[$dc]["LABEL"]."_".$arrPk[$pk]."_".$arrayData[$i]["LINE"];
								}
							}
						}

					$str .= " FROM
					(
					";

						$str .= " SELECT A.* ";
							for($n=1; $n <= 24; $n++)
							{
								$str .= ", cat.disk_konversi(".$n.", COALESCE(NILAI_P_".$n.",0), 'P') KONVERSI_NILAI_P_".$n."
					 		, cat.disk_konversi(".$n.", COALESCE(NILAI_K_".$n.",0), 'K') KONVERSI_NILAI_K_".$n;
					 		}

							$str .= " FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
							";

							for($n=1; $n <= 24; $n++)
							{
								$str .= " , SUM(CASE WHEN B.TIPE = 1 AND C.NOMOR = ".$n." THEN GRADE_A ELSE 0 END) NILAI_P_".$n."
								, SUM(CASE WHEN B.TIPE = 2 AND C.NOMOR = ".$n." THEN GRADE_B ELSE 0 END) NILAI_K_".$n."
								";
							}

							$str .= " FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
							INNER JOIN cat.disk_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.DISK_PILIHAN_ID
							INNER JOIN cat.disk_soal C ON C.DISK_SOAL_ID = B.DISK_SOAL_ID
							WHERE 1=1 AND A.TIPE_UJIAN_ID = 42
							GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.UJIAN_TAHAP_ID
							) A
						) A
				) A
			) A
			) R ON A.UJIAN_ID = R.UJIAN_ID AND A.PEGAWAI_ID = R.PEGAWAI_ID
		WHERE 1=1 
		".$statement;

		// WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringDisc($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NAMA_PEGAWAI, A.NIP_BARU
			FROM
			(
				SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				FROM cat.ujian_pegawai_daftar B
				INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
			) A
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringEppsHasil($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT * ";
		for($data=1; $data <= 15; $data++)
		{
			$separator= ", ";
			if($data == 1){}
			else
			$separator= "+ ";

			$str .= $separator."JUMLAH_".$data." ";
		}
		$str .= " TOTAL_DATA";

		for($data=1; $data <= 15; $data++)
		{
			$separator= ", ";
			if($data == 1){}
			else
			$separator= "+ ";

			$str .= $separator."ROW_".$data." ";
		}
		$str .= " CONS_DATA";

		for($data=1; $data <= 15; $data++)
		{
			$str .= " 
			, CASE 
			WHEN COALESCE(S".$data.",0) >= 0 AND COALESCE(S".$data.",0) <= 4 THEN '---'
			WHEN COALESCE(S".$data.",0) >= 5 AND COALESCE(S".$data.",0) <= 8 THEN '--'
			WHEN COALESCE(S".$data.",0) = 9 THEN '-'
			WHEN COALESCE(S".$data.",0) >= 10 AND COALESCE(S".$data.",0) <= 11 THEN '0'
			WHEN COALESCE(S".$data.",0) = 12 THEN '+'
			WHEN COALESCE(S".$data.",0) >= 13 AND COALESCE(S".$data.",0) <= 16 THEN '++'
			ELSE '+++'
			END S_KETERANGAN_".$data." "
			;
		}
		$str .= " FROM
		(
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
		";
		for($data=1; $data <= 15; $data++)
		{
			$str .= " , COALESCE(R_".$data.",0) R_".$data.", COALESCE(C_".$data.",0) C_".$data."
			, COALESCE(ROW_".$data.",0) ROW_".$data."
			, COALESCE(R_".$data.",0) + COALESCE(C_".$data.",0) JUMLAH_".$data."
			, (SELECT Y_DATA FROM cat.epps_norma XX WHERE XX.X_DATA = ".$data." AND (COALESCE(R_".$data.",0) + COALESCE(C_".$data.",0)) BETWEEN MIN_DATA AND MAX_DATA) S".$data
			;
		}
		$str .= "
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			XX.PEGAWAI_ID, XX.UJIAN_ID
		";

			// ambil data untuk kolom sebaris ambil data
			$x1= 1;
			for($r=1; $r <= 15; $r++)
			{
				if($r == 6 || $r == 11)
					$x1= 1;

				if($r > 5 && $r <= 10)
				{
					$kolom= 2;
					$x= $x1 + (1 * 75);
					// echo "b-".$x."<br/>";
				}
				elseif($r > 10 && $r <= 15)
				{
					$kolom= 3;
					$x= $x1 + (2 * 75);
					// echo "c-".$x."<br/>";
				}
				else
				{
					$kolom= 1;
					$x= $x1;
					// echo "a-".$x."<br/>";
				}

				$tempValue= "";
				$str .= ", ";
				for($y=0; $y < 15; $y++)
				{
					$separator= "";
					if($y == 0){}
					else
					$separator= " + ";

					if($kolom == 1)
					{
						$tempKondisi= $x+ ($y * 5);
						// echo $tempKondisi."<br/>";
						if(
						$tempKondisi == 1 || $tempKondisi == 7 || $tempKondisi == 13 || $tempKondisi == 19 || $tempKondisi == 25

						)
							continue;
					}
					elseif($kolom == 2)
					{
						$tempKondisi= $x+ ($y * 5);
						// echo $tempKondisi."<br/>";
						if(
						$tempKondisi == 101 || $tempKondisi == 107 || $tempKondisi == 113 || $tempKondisi == 119 || $tempKondisi == 125
						)
							continue;
					}
					elseif($kolom == 3)
					{
						$tempKondisi= $x+ ($y * 5);
						// echo $tempKondisi."<br/>";
						if(
						// $tempKondisi == 151 || $tempKondisi == 201 || $tempKondisi == 157 ||
						// $tempKondisi == 207 || $tempKondisi == 163 || $tempKondisi == 213 ||
						// $tempKondisi == 169 || $tempKondisi == 219 || $tempKondisi == 175 || $tempKondisi == 225

						$tempKondisi == 201 || $tempKondisi == 207 || $tempKondisi == 213 || $tempKondisi == 219 || $tempKondisi == 225

						)
							continue;
					}

					$tempValue= $separator." SUM(CASE WHEN XX.BANK_SOAL_ID = ".$x." + (".$y." * 5) AND Y.GRADE_A = 1 THEN 1 ELSE 0 END) ";

					// echo $tempValue."<br>";
					$str .= $tempValue;
				}
				// echo ""
				$str .= " R_".$r;
				// echo " R_".$r."<br>";
				$x1++;
			}
			// exit();

			// ambil data untuk row turun kebawah ambil data
			for($r=0; $r < 15; $r++)
			{
				$x1= $r + 1;

				$tempValue= "";
				$str .= ", ";
				for($y=0; $y < 15; $y++)
				{
					$y1= $y + 1;

					$separator= "";
					if($y == 0){}
					else
					$separator= " + ";

					if($y >= 5 && $y < 10)
					{
						$tempValueKondisi= ($y1 - 5) + (1 * 75) + ($r * 5);
					}
					elseif($y >= 10 && $y < 15)
					{
						$tempValueKondisi= ($y1 - 10) + (2 * 75) + ($r * 5);
					}
					else
					{
						$tempValueKondisi= $y1 + ($r * 5);
					}

					if(
					$tempValueKondisi == 1 || $tempValueKondisi == 7 || $tempValueKondisi == 13 || $tempValueKondisi == 19 || $tempValueKondisi == 25 ||
					$tempValueKondisi == 101 || $tempValueKondisi == 107 || $tempValueKondisi == 113 ||
					$tempValueKondisi == 119 || $tempValueKondisi == 125 ||
					$tempValueKondisi == 201 || $tempValueKondisi == 207 || $tempValueKondisi == 213 || $tempValueKondisi == 219 || $tempValueKondisi == 225
					)
						continue;

					$tempValue= $separator." SUM(CASE WHEN XX.BANK_SOAL_ID = ".$tempValueKondisi."  AND Y.GRADE_B = 1 THEN 1 ELSE 0 END) ";

					// echo $tempValue."<br>";
					$str .= $tempValue;
				}
				// echo ""
				$str .= " C_".$x1;
				// echo " C_".$x1."<br>";
			}

			// exit();

			$str .= " FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." XX
			INNER JOIN cat.epps_pilihan Y ON Y.SOAL_EPPS_ID = XX.BANK_SOAL_ID AND Y.EPPS_PILIHAN_ID = XX.BANK_SOAL_PILIHAN_ID
			WHERE 1=1
			AND XX.TIPE_UJIAN_ID = 17
			GROUP BY XX.PEGAWAI_ID, XX.UJIAN_ID, XX.JADWAL_TES_ID
		) XX ON XX.PEGAWAI_ID = B.PEGAWAI_ID AND XX.UJIAN_ID = B.UJIAN_ID
		LEFT JOIN
		(
			SELECT 
				XX1.PEGAWAI_ID, XX1.UJIAN_ID";
			for($data=1; $data <= 15; $data++)
			{
			$str .= "
			, CASE WHEN ROW".$data." = 1 THEN 1 ELSE 0 END ROW_".$data."
			";
			}
		$str .= "
			FROM
			(
				SELECT
				XX.PEGAWAI_ID, XX.UJIAN_ID
			";

			$arrCheckGarisData= array(
				array("row"=>1, "data1"=>1, "data2"=>151)
				, array("row"=>2, "data1"=>7, "data2"=>157)
				, array("row"=>3, "data1"=>13, "data2"=>163)
				, array("row"=>4, "data1"=>19, "data2"=>169)
				, array("row"=>5, "data1"=>25, "data2"=>175)
				, array("row"=>6, "data1"=>26, "data2"=>101)
				, array("row"=>7, "data1"=>32, "data2"=>107)
				, array("row"=>8, "data1"=>38, "data2"=>113)
				, array("row"=>9, "data1"=>44, "data2"=>119)
				, array("row"=>10, "data1"=>50, "data2"=>125)
				, array("row"=>11, "data1"=>51, "data2"=>201)
				, array("row"=>12, "data1"=>57, "data2"=>207)
				, array("row"=>13, "data1"=>63, "data2"=>213)
				, array("row"=>14, "data1"=>69, "data2"=>219)
				, array("row"=>15, "data1"=>75, "data2"=>225)
			);

			for($data=0; $data < 15; $data++)
			{
			$str .= ", CASE WHEN SUM(CASE WHEN XX.BANK_SOAL_ID = ".$arrCheckGarisData[$data]["data1"]." THEN Y.GRADE_A END) = SUM(CASE WHEN XX.BANK_SOAL_ID = ".$arrCheckGarisData[$data]["data2"]." THEN Y.GRADE_A END) THEN 1 ELSE 0 END ROW".$arrCheckGarisData[$data]["row"];
			}

			$str .= " FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." XX
				INNER JOIN cat.epps_pilihan Y ON Y.SOAL_EPPS_ID = XX.BANK_SOAL_ID AND Y.EPPS_PILIHAN_ID = XX.BANK_SOAL_PILIHAN_ID
				WHERE 1=1
				AND XX.TIPE_UJIAN_ID = 17
				AND XX.BANK_SOAL_ID IN (1, 151, 7, 157, 13, 163, 19, 169, 25, 175, 26, 101, 32, 107, 38, 113, 44, 119, 50, 125, 51, 201, 57, 207, 63, 213, 69, 219, 75, 225)
				GROUP BY XX.PEGAWAI_ID, XX.UJIAN_ID, XX.JADWAL_TES_ID
			) XX1
		) XX1 ON XX1.PEGAWAI_ID = B.PEGAWAI_ID AND XX1.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." 
		) A
		WHERE 1=1
		".$sorder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

    function getCountByParamsMonitoringEppsHasil($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				B.PEGAWAI_ID, B.JADWAL_TES_ID
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringBaruKraepelin($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "

		SELECT
			A.*
			, cat.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KETELITIAN_SS, 'KESIMPULANKETELITIAN') KETELITIAN_KESIMPULAN
			, cat.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KECEPATAN_SS, 'KESIMPULANKECEPATAN') KECEPATAN_KESIMPULAN
			, 
			JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM
		(
			SELECT
				A.*
				, CAST(cat.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KETELITIAN_RS, 'KETELITIAN') AS NUMERIC) KETELITIAN_SS
				, CAST(cat.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KECEPATAN_RS, 'KECEPATAN') AS NUMERIC) KECEPATAN_SS
			FROM
			(
				SELECT
					A.*
					, A.TOTAL_KESALAHAN_1 + A.TOTAL_KESALAHAN_2 + A.TOTAL_KESALAHAN_3 + A.TOTAL_TDK_ISI_1 + A.TOTAL_TDK_ISI_2 + A.TOTAL_TDK_ISI_3 KETELITIAN_RS
					, A.PUNCAK_TERTINGGI + A.PUNCAK_TERENDAH KECEPATAN_RS
				FROM
				(
					SELECT
					UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
					, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
					, COALESCE(SALAH1.TOTAL_KESALAHAN,0) TOTAL_KESALAHAN_1, COALESCE(SALAH2.TOTAL_KESALAHAN,0) TOTAL_KESALAHAN_2, COALESCE(SALAH3.TOTAL_KESALAHAN,0) TOTAL_KESALAHAN_3
					, COALESCE(TDKISI1.TOTAL_TDK_ISI,0) TOTAL_TDK_ISI_1, COALESCE(TDKISI2.TOTAL_TDK_ISI,0) TOTAL_TDK_ISI_2, COALESCE(TDKISI3.TOTAL_TDK_ISI,0) TOTAL_TDK_ISI_3
					, COALESCE(TINGGI.PUNCAK_TERTINGGI,0) PUNCAK_TERTINGGI, RENDAH.PUNCAK_TERENDAH
					, cat.P_AREA_TINGGI(".$jadwaltesid.", B.PEGAWAI_ID) LIST_PUNCAK_TERTINGGI
					, cat.P_AREA_RENDAH(".$jadwaltesid.", B.PEGAWAI_ID) LIST_PUNCAK_TERENDAH
					, to_number(A.PENDIDIKAN,'9999') PENDIDIKAN
					FROM cat.ujian_pegawai_daftar B
					INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_KESALAHAN('".$jadwaltesid."', 1)) SALAH1 ON SALAH1.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_KESALAHAN('".$jadwaltesid."', 2)) SALAH2 ON SALAH2.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_KESALAHAN('".$jadwaltesid."', 3)) SALAH3 ON SALAH3.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_TDK_ISI('".$jadwaltesid."', 1)) TDKISI1 ON TDKISI1.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_TDK_ISI('".$jadwaltesid."', 2)) TDKISI2 ON TDKISI2.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_TDK_ISI('".$jadwaltesid."', 3)) TDKISI3 ON TDKISI3.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_TINGGI('".$jadwaltesid."')) TINGGI ON TINGGI.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat.P_KRAPELIN_RENDAH('".$jadwaltesid."')) RENDAH ON RENDAH.PEGAWAI_ID = B.PEGAWAI_ID
					WHERE 1=1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		// $str .= $statement." ".$sorder;
		$str .= $statement." 
				) A
			) A
		) A
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		";
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

    function getCountByParamsMonitoringBaruKraepelin($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringIst($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="order by a.no_urut asc")
	{
		$str = "
		SELECT A.*, COALESCE(B.IQ,0) IQ
		FROM
		(
			SELECT
			A.*
			, A.RW_SE + A.RW_WA + A.RW_AN + A.RW_GE + A.RW_ME + A.RW_RA + A.RW_ZR + A.RW_FA + A.RW_WU RW_JUMLAH
			, cat.IST_JENIS_SW(10, A.PEGAWAI_UMUR_NORMA, COALESCE((A.RW_SE + A.RW_WA + A.RW_AN + A.RW_GE + A.RW_ME + A.RW_RA + A.RW_ZR + A.RW_FA + A.RW_WU),0)) SW_JUMLAH
			FROM
			(
				SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
					, A.NAMA_PEGAWAI, A.NIP_BARU, A.PEGAWAI_UMUR_NORMA
					, COALESCE(SE.JUMLAH_BENAR,0) RW_SE
					, cat.IST_JENIS_SW(1, A.PEGAWAI_UMUR_NORMA, COALESCE(SE.JUMLAH_BENAR,0)) SW_SE
					, COALESCE(WA.JUMLAH_BENAR,0) RW_WA
					, cat.IST_JENIS_SW(2, A.PEGAWAI_UMUR_NORMA, COALESCE(WA.JUMLAH_BENAR,0)) SW_WA
					, COALESCE(AN.JUMLAH_BENAR,0) RW_AN
					, cat.IST_JENIS_SW(3, A.PEGAWAI_UMUR_NORMA, COALESCE(AN.JUMLAH_BENAR,0)) SW_AN
					, COALESCE(GE.JUMLAH_BENAR,0) RW_GE
					, cat.IST_JENIS_SW(4, A.PEGAWAI_UMUR_NORMA, COALESCE(GE.JUMLAH_BENAR,0)) SW_GE
					, COALESCE(ME.JUMLAH_BENAR,0) RW_ME
					, cat.IST_JENIS_SW(5, A.PEGAWAI_UMUR_NORMA, COALESCE(ME.JUMLAH_BENAR,0)) SW_ME
					, COALESCE(RA.JUMLAH_BENAR,0) RW_RA
					, cat.IST_JENIS_SW(6, A.PEGAWAI_UMUR_NORMA, COALESCE(RA.JUMLAH_BENAR,0)) SW_RA
					, COALESCE(ZR.JUMLAH_BENAR,0) RW_ZR
					, cat.IST_JENIS_SW(7, A.PEGAWAI_UMUR_NORMA, COALESCE(ZR.JUMLAH_BENAR,0)) SW_ZR
					, COALESCE(FA.JUMLAH_BENAR,0) RW_FA
					, cat.IST_JENIS_SW(8, A.PEGAWAI_UMUR_NORMA, COALESCE(FA.JUMLAH_BENAR,0)) SW_FA
					, COALESCE(WU.JUMLAH_BENAR,0) RW_WU
					, cat.IST_JENIS_SW(9, A.PEGAWAI_UMUR_NORMA, COALESCE(WU.JUMLAH_BENAR,0)) SW_WU
					, xx.no_urut NOMOR_URUT_GENERATE
				FROM
				(
					SELECT
					UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
					, cat.NORMA_UMUR(B.UJIAN_ID, B.PEGAWAI_ID) PEGAWAI_UMUR_NORMA
					, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
					FROM cat.ujian_pegawai_daftar B
					INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
					WHERE 1=1
				) A
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 188 AND A.BANK_SOAL_ID <= 207
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) SE ON A.UJIAN_ID = SE.UJIAN_ID AND A.PEGAWAI_ID = SE.PEGAWAI_ID AND A.JADWAL_TES_ID = SE.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 208 AND A.BANK_SOAL_ID <= 227
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) WA ON A.UJIAN_ID = WA.UJIAN_ID AND A.PEGAWAI_ID = WA.PEGAWAI_ID AND A.JADWAL_TES_ID = WA.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 228 AND A.BANK_SOAL_ID <= 247
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) AN ON A.UJIAN_ID = AN.UJIAN_ID AND A.PEGAWAI_ID = AN.PEGAWAI_ID AND A.JADWAL_TES_ID = AN.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
					, cat.norma_ge(NILAI) JUMLAH_BENAR
					FROM
					(
						SELECT
						A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, SUM(B.NILAI) NILAI
						FROM cat_pegawai.UJIAN_PEGAWAI_KETERANGAN_".$jadwaltesid." A
						INNER JOIN cat.IST_KUNCI_4 B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND REPLACE(UPPER(A.KETERANGAN), ' ', '') = REPLACE(UPPER(B.NAMA), ' ', '')
						WHERE A.BANK_SOAL_ID >= 248 AND A.BANK_SOAL_ID <= 263
						GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
					) A
				) GE ON A.UJIAN_ID = GE.UJIAN_ID AND A.PEGAWAI_ID = GE.PEGAWAI_ID AND A.JADWAL_TES_ID = GE.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 344 AND A.BANK_SOAL_ID <= 363
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) ME ON A.UJIAN_ID = ME.UJIAN_ID AND A.PEGAWAI_ID = ME.PEGAWAI_ID AND A.JADWAL_TES_ID = ME.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 264 AND A.BANK_SOAL_ID <= 283
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) RA ON A.UJIAN_ID = RA.UJIAN_ID AND A.PEGAWAI_ID = RA.PEGAWAI_ID AND A.JADWAL_TES_ID = RA.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 284 AND A.BANK_SOAL_ID <= 303
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) ZR ON A.UJIAN_ID = ZR.UJIAN_ID AND A.PEGAWAI_ID = ZR.PEGAWAI_ID AND A.JADWAL_TES_ID = ZR.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 304 AND A.BANK_SOAL_ID <= 323
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) FA ON A.UJIAN_ID = FA.UJIAN_ID AND A.PEGAWAI_ID = FA.PEGAWAI_ID AND A.JADWAL_TES_ID = FA.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT 
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT A.*
							FROM
							(
								SELECT
								A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
								, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
								, COUNT(1) JUMLAH_CHECK
								FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
								INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
								INNER JOIN 
								(
									SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
									FROM cat.bank_soal_pilihan
								) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
								WHERE A.BANK_SOAL_ID >= 324 AND A.BANK_SOAL_ID <= 343
								GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
							) A
							INNER JOIN
							(
								SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
								FROM cat.bank_soal_pilihan
								WHERE GRADE_PROSENTASE > 0
								GROUP BY BANK_SOAL_ID
							) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
							WHERE GRADE_PROSENTASE = 100
							ORDER BY A.BANK_SOAL_ID
						) A
					) A
					WHERE 1=1
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.ID
				) WU ON A.UJIAN_ID = WU.UJIAN_ID AND A.PEGAWAI_ID = WU.PEGAWAI_ID AND A.JADWAL_TES_ID = WU.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT jadwal_awal_tes_simulasi_id, pegawai_id, no_urut FROM public.jadwal_awal_tes_simulasi_pegawai
				) xx on A.PEGAWAI_ID = xx.pegawai_id AND A.JADWAL_TES_ID = xx.jadwal_awal_tes_simulasi_id
				WHERE 1=1
				".$statement."
			) A
		) A
		LEFT JOIN cat.IST_IQ B ON B.SW = A.SW_JUMLAH
		WHERE 1=1
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= "order by nomor_urut_generate asc";
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringIst($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NAMA_PEGAWAI, A.NIP_BARU
			FROM
			(
				SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				FROM cat.ujian_pegawai_daftar B
				INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
			) A
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringPauli($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			A.*
			, COALESCE(RW1,0) RW1, COALESCE(RW2,0) RW2, COALESCE(RW3,0) RW3, COALESCE(RW4,0) RW4, COALESCE(RW5,0) RW5
			, COALESCE(RW6,0) RW6, COALESCE(RW7,0) RW7, COALESCE(RW8,0) RW8, COALESCE(RW9,0) RW9, COALESCE(RW10,0) RW10
			, COALESCE(RW11,0) RW11, COALESCE(RW12,0) RW12, COALESCE(RW13,0) RW13, COALESCE(RW14,0) RW14, COALESCE(RW15,0) RW15
			, COALESCE(RW16,0) RW16, COALESCE(RW17,0) RW17, COALESCE(RW18,0) RW18, COALESCE(RW19,0) RW19, COALESCE(RW20,0) RW20
			, 
			JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM
		(
			SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN
			, CASE WHEN COALESCE(NULLIF(A.PENDIDIKAN, ''), NULL) IS NULL THEN 'MU' ELSE PENDIDIKAN END PEGAWAI_PENDIDIKAN
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		LEFT JOIN
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
			, SUM(CASE WHEN A.NOMOR = 1 THEN JUMLAH ELSE 0 END) RW1
			, SUM(CASE WHEN A.NOMOR = 2 THEN JUMLAH ELSE 0 END) RW2
			, SUM(CASE WHEN A.NOMOR = 3 THEN JUMLAH ELSE 0 END) RW3
			, SUM(CASE WHEN A.NOMOR = 4 THEN JUMLAH ELSE 0 END) RW4
			, SUM(CASE WHEN A.NOMOR = 5 THEN JUMLAH ELSE 0 END) RW5
			, SUM(CASE WHEN A.NOMOR = 6 THEN JUMLAH ELSE 0 END) RW6
			, SUM(CASE WHEN A.NOMOR = 7 THEN JUMLAH ELSE 0 END) RW7
			, SUM(CASE WHEN A.NOMOR = 8 THEN JUMLAH ELSE 0 END) RW8
			, SUM(CASE WHEN A.NOMOR = 9 THEN JUMLAH ELSE 0 END) RW9
			, SUM(CASE WHEN A.NOMOR = 10 THEN JUMLAH ELSE 0 END) RW10
			, SUM(CASE WHEN A.NOMOR = 11 THEN JUMLAH ELSE 0 END) RW11
			, SUM(CASE WHEN A.NOMOR = 12 THEN JUMLAH ELSE 0 END) RW12
			, SUM(CASE WHEN A.NOMOR = 13 THEN JUMLAH ELSE 0 END) RW13
			, SUM(CASE WHEN A.NOMOR = 14 THEN JUMLAH ELSE 0 END) RW14
			, SUM(CASE WHEN A.NOMOR = 15 THEN JUMLAH ELSE 0 END) RW15
			, SUM(CASE WHEN A.NOMOR = 16 THEN JUMLAH ELSE 0 END) RW16
			, SUM(CASE WHEN A.NOMOR = 17 THEN JUMLAH ELSE 0 END) RW17
			, SUM(CASE WHEN A.NOMOR = 18 THEN JUMLAH ELSE 0 END) RW18
			, SUM(CASE WHEN A.NOMOR = 19 THEN JUMLAH ELSE 0 END) RW19
			, SUM(CASE WHEN A.NOMOR = 20 THEN JUMLAH ELSE 0 END) RW20
			FROM
			(
				SELECT
				A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NOMOR
				, KOLOM1 + KOLOM2 + KOLOM3 JUMLAH
				FROM cat_pegawai.ujian_pauli_tanda_".$jadwaltesid." A
				WHERE 1=1
			) A
			GROUP BY A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID
		) RW ON A.UJIAN_ID = RW.UJIAN_ID AND A.PEGAWAI_ID = RW.PEGAWAI_ID AND A.JADWAL_TES_ID = RW.JADWAL_TES_ID
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringPauli($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NAMA_PEGAWAI, A.NIP_BARU
			FROM
			(
				SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				FROM cat.ujian_pegawai_daftar B
				INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
			) A
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function setkonversidisk($paramsArray=array(), $statement="")
    {
    	$str = "
    	SELECT HASIL ROWCOUNT
    	FROM cat.DISK_GRAFIK
    	WHERE 1=1
    	"; 
    	while(list($key,$val)=each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str.= $statement;
    	$this->query = $str;
    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return "";
    }

    function setnkesimpulandisk($d, $i, $s, $c)
    {
    	$str = "
    	SELECT cat.DISK_N_KESIMPULAN(".$d.", ".$i.", ".$s.", ".$c.") ROWCOUNT
    	"; 

    	$this->query = $str;
    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return "";
    }

    function selectByParamsDiscKesimpulan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.PERIODE_ID, A.LINE, A.JUDUL, A.JUDUL_DETIL, A.DESKRIPSI, A.SARAN, A.STATUS_AKTIF
		FROM cat.DISK_KESIMPULAN A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsInfoPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
			B.UJIAN_ID, B.PEGAWAI_ID, TO_CHAR(B1.TGL_MULAI, 'YYYY-MM-DD') TANGGAL_UJIAN
			, cat.NORMA_UMUR(B.UJIAN_ID, B.PEGAWAI_ID) PEGAWAI_UMUR_NORMA
			, A.NAMA NAMA_PEGAWAI, A.EMAIL, A.NIP_BARU, A.JENIS_KELAMIN
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN cat.ujian B1 ON B.UJIAN_ID = B1.UJIAN_ID
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsBaruKrapelinSoal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY A.X_DATA, A.Y_DATA")
	{
		$str = "
		SELECT A.PAKAI_KRAEPELIN_ID, A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat.N_KRAEPELIN_SOAL A
		WHERE 1=1
		AND EXISTS
		(
		SELECT 1 FROM cat.N_KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
		AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
		)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsBaruKrapelinJawaban($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY A.X_DATA, A.Y_DATA")
	{
		$str = "
		SELECT A.PAKAI_KRAEPELIN_ID, A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat.N_KRAEPELIN_JAWAB A
		WHERE 1=1
		AND EXISTS
		(
		SELECT 1 FROM cat.N_KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
		AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
		)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsBaruKrapelinPesertaJawaban($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.X_DATA, A.Y_DATA")
	{
		$str = "
		SELECT A.X_DATA, A.Y_DATA, A.NILAI
		FROM cat_pegawai.UJIAN_KRAEPELIN_N_".$parseid." A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsGrafikKraepelinBaru($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$soal= 40;
		$str = "
	    SELECT
		UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
		, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU
		";

		for($x=1; $x <= $soal; $x++)
		{
			$str .= ", COALESCE(XX.Y_DATA".$x.",0) Y_DATA".$x;
		}
		
		$str .= "
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			B.PEGAWAI_ID
		";
			for($x=1; $x <= $soal; $x++)
			{
				$str .= ", SUM(CASE WHEN A.X_DATA = ".$x." THEN COALESCE(B.Y_DATA,0) END) Y_DATA".$x;
			}

		$str .= "
			FROM 
			(
				SELECT X_DATA
				FROM cat.N_KRAEPELIN_JAWAB A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1 FROM cat.N_KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
					AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
				)
				GROUP BY X_DATA
			) A
			LEFT JOIN
			(
				SELECT XX.UJIAN_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
				FROM cat_pegawai.UJIAN_KRAEPELIN_N_".$jadwaltesid." XX
				WHERE 1=1
				AND XX.NILAI IS NOT NULL
				GROUP BY XX.UJIAN_ID, XX.PEGAWAI_ID, XX.X_DATA
				ORDER BY XX.UJIAN_ID, XX.PEGAWAI_ID, XX.X_DATA
			) B ON A.X_DATA = B.X_DATA
			GROUP BY B.PEGAWAI_ID
		) XX ON XX.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE 1=1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function selectByParamsSoal($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID, C.PERTANYAAN
			, REPLACE(C.PATH_GAMBAR, 'main', 'cat/main') PATH_GAMBAR, C.PATH_SOAL
			, C.TIPE_SOAL, B.TIPE_UJIAN_ID, TU.TIPE TIPE_UJIAN_NAMA, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.tipe_ujian TU ON B.TIPE_UJIAN_ID = TU.TIPE_UJIAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid."
		AND B.TIPE_UJIAN_ID NOT IN (7, 17)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalWPT($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID, C.PERTANYAAN
			, B.TIPE_UJIAN_ID, TU.TIPE TIPE_UJIAN_NAMA, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.wpt_soal C ON B.BANK_SOAL_ID = C.wpt_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.tipe_ujian TU ON B.TIPE_UJIAN_ID = TU.TIPE_UJIAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalKertih($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID, C.PERTANYAAN
			, B.TIPE_UJIAN_ID, TU.TIPE TIPE_UJIAN_NAMA, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.kertih_soal C ON B.BANK_SOAL_ID = C.KERTIH_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.tipe_ujian TU ON B.TIPE_UJIAN_ID = TU.TIPE_UJIAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanPegawaiKertih($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, UP.KETERANGAN JAWABAN
			, JENIS || ' - ' || cat.kertihkonversi(JENIS, CAST(UP.KETERANGAN AS NUMERIC)) JAWABAN_KONVERSI
			, B.TIPE_UJIAN_ID
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.kertih_soal C ON B.BANK_SOAL_ID = C.KERTIH_SOAL_ID
		LEFT JOIN cat_pegawai.ujian_pegawai_keterangan_".$parseid." UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
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

    function selectByParamsSoalBig($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID, C.PERTANYAAN 
			, B.TIPE_UJIAN_ID, TU.TIPE TIPE_UJIAN_NAMA, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.big_five_soal C ON B.BANK_SOAL_ID = C.big_five_soal_id
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.tipe_ujian TU ON B.TIPE_UJIAN_ID = TU.TIPE_UJIAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanBig($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, UP.URUT, B.BANK_SOAL_ID, C1.big_five_soal_id")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.big_five_soal C ON B.BANK_SOAL_ID = C.big_five_soal_id
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.big_five_pilihan C1 ON B.BANK_SOAL_ID = C1.big_five_soal_id
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawaban($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, UP.URUT, B.BANK_SOAL_ID, C1.BANK_SOAL_PILIHAN_ID, C1.PATH_GAMBAR")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN
			, REPLACE(C.PATH_GAMBAR, 'main', 'cat/main') PATH_GAMBAR, C1.PATH_GAMBAR PATH_SOAL
			, C.TIPE_SOAL, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.bank_soal_pilihan C1 ON B.BANK_SOAL_ID = C1.BANK_SOAL_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid."
		AND B.TIPE_UJIAN_ID NOT IN (7, 17)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanWpt($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, UP.URUT, B.BANK_SOAL_ID, C1.WPT_PILIHAN_ID ")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.wpt_soal  C ON B.BANK_SOAL_ID = C.wpt_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.wpt_pilihan C1 ON B.BANK_SOAL_ID = C1.wpt_SOAL_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanPegawaiBig($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT ")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.big_five_soal C ON B.BANK_SOAL_ID = C.big_five_soal_id
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			AND A.BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.big_five_pilihan C1 ON B.BANK_SOAL_ID = C1.big_five_soal_id AND UP.BANK_SOAL_PILIHAN_ID = C1.big_five_pilihan_id
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 	
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanPegawai($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT, C1.PATH_GAMBAR")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN
			, REPLACE(C.PATH_GAMBAR, 'main', 'cat/main') PATH_GAMBAR, C1.PATH_GAMBAR PATH_SOAL
			, C.TIPE_SOAL, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			AND A.BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.bank_soal_pilihan C1 ON B.BANK_SOAL_ID = C1.BANK_SOAL_ID AND UP.BANK_SOAL_PILIHAN_ID = C1.BANK_SOAL_PILIHAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid."
		AND B.TIPE_UJIAN_ID NOT IN (7, 17)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

     function selectByParamsJawabanPegawaiTKD56R($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, up.keterangan JAWABAN 
			, '' PATH_GAMBAR, '' PATH_SOAL
			, c.TIPE_SOAL, B.TIPE_UJIAN_ID, UP.URUT
			,case  when d.jawaban = up.keterangan then 1
			else 0 end status_benar
			, UP.LAST_CREATE_USER LAST_CREATE_USER
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN cat.bank_soal_pilihan d ON B.BANK_SOAL_ID = d.BANK_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT, a.keterangan, A.LAST_CREATE_USER
			FROM cat_pegawai.ujian_pegawai_keterangan_".$parseid." A
			WHERE 1=1
			AND A.keterangan IS NOT NULL
		)  UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID		 
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid."
		AND B.TIPE_UJIAN_ID IN (72)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanPegawaiTKD6R($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT, up.jawaban_1 desc")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, up.jawaban_1 JAWABAN1
			, up.jawaban_2 JAWABAN2,
			cekjawaban_1, cekjawaban_2
			, '' PATH_GAMBAR, '' PATH_SOAL
			, c.TIPE_SOAL, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN cat.bank_soal_pilihan d ON B.BANK_SOAL_ID = d.BANK_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT, b.LAST_CREATE_USER jawaban_1, c.LAST_CREATE_USER jawaban_2 ,d.jawaban cekjawaban_1,e.jawaban cekjawaban_2
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			left join cat_pegawai.ujian_pegawai_".$parseid." b on b.pegawai_id = a.pegawai_id and A.BANK_SOAL_ID=b.BANK_SOAL_ID and b.bank_soal_pilihan_id=1 and a.bank_soal_pilihan_id is not null
			left join cat_pegawai.ujian_pegawai_".$parseid." c on c.pegawai_id = a.pegawai_id and A.BANK_SOAL_ID=c.BANK_SOAL_ID and c.bank_soal_pilihan_id=2 and a.bank_soal_pilihan_id is not null
			left join 
			(select *,  ROW_NUMBER () OVER ( PARTITION BY bank_soal_id ORDER BY bank_soal_pilihan_id) NOMOR from cat.bank_soal_pilihan d 
			 )d on a.BANK_SOAL_ID=d.BANK_SOAL_ID and b.LAST_CREATE_USER= d.jawaban  and d.NOMOR =1
			left join 
			(select *,  ROW_NUMBER () OVER ( PARTITION BY bank_soal_id ORDER BY bank_soal_pilihan_id) NOMOR from cat.bank_soal_pilihan e 
			 )e on a.BANK_SOAL_ID=e.BANK_SOAL_ID and c.LAST_CREATE_USER= e.jawaban  and e.NOMOR =2
			WHERE 1=1  
			group by A.PEGAWAI_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT, b.LAST_CREATE_USER, c.LAST_CREATE_USER, c.bank_soal_pilihan_id, b.bank_soal_pilihan_id,d.jawaban,e.jawaban
		)  UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID		 
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid."
		AND B.TIPE_UJIAN_ID IN (73)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." group by A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID, up.jawaban_1, up.jawaban_2, c.TIPE_SOAL, B.TIPE_UJIAN_ID, UP.URUT, cekjawaban_1, cekjawaban_2 ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanPegawaiWPT($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, UP.URUT ")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.wpt_soal C ON B.BANK_SOAL_ID = C.wpt_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			AND A.BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN cat.wpt_pilihan C1 ON B.BANK_SOAL_ID = C1.WPT_SOAL_ID AND UP.BANK_SOAL_PILIHAN_ID = C1.WPT_PILIHAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid." 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsCheckJawabanPegawai($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY B.TIPE_UJIAN_ID, UP.URUT")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID, B.TIPE_UJIAN_ID, UP.URUT
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
			FROM cat_pegawai.ujian_pegawai_".$parseid." A
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.JADWAL_TES_ID, A.UJIAN_ID, A.BANK_SOAL_ID, A.TIPE_UJIAN_ID, A.URUT
		) UP ON A.PEGAWAI_ID = UP.PEGAWAI_ID AND A.JADWAL_TES_ID = UP.JADWAL_TES_ID AND A.UJIAN_ID = UP.UJIAN_ID AND B.BANK_SOAL_ID = UP.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = UP.TIPE_UJIAN_ID
		INNER JOIN
		(
			SELECT A.*
			FROM
			(
				SELECT A.*
				FROM
				(
					SELECT
					A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
					, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
					, COUNT(1) JUMLAH_CHECK
					FROM cat_pegawai.ujian_pegawai_".$parseid." A
					INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
					INNER JOIN 
					(
						SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN, GRADE_PROSENTASE
						FROM cat.bank_soal_pilihan
					) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
					GROUP BY A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
				) A
				INNER JOIN
				(
					SELECT BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
					FROM cat.bank_soal_pilihan
					WHERE GRADE_PROSENTASE > 0
					GROUP BY BANK_SOAL_ID
				) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
				WHERE GRADE_PROSENTASE = 100
				ORDER BY A.BANK_SOAL_ID
			) A
			WHERE 1=1
		) JWB ON A.PEGAWAI_ID = JWB.PEGAWAI_ID AND A.JADWAL_TES_ID = JWB.JADWAL_TES_ID AND A.UJIAN_ID = JWB.UJIAN_ID AND B.BANK_SOAL_ID = JWB.BANK_SOAL_ID AND B.TIPE_UJIAN_ID = JWB.TIPE_UJIAN_ID
		WHERE 1=1
		AND A.JADWAL_TES_ID = ".$parseid."
		AND B.TIPE_UJIAN_ID NOT IN (7, 17)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanBenarSoal($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID, C1.PATH_GAMBAR, c1.bank_soal_pilihan_id")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN
			, REPLACE(C.PATH_GAMBAR, 'main', 'cat/main') PATH_GAMBAR, C1.PATH_GAMBAR PATH_SOAL
			, C.TIPE_SOAL, B.TIPE_UJIAN_ID, C1.GRADE_PROSENTASE
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN cat.bank_soal_pilihan C1 ON B.BANK_SOAL_ID = C1.BANK_SOAL_ID
		WHERE 1=1
		AND COALESCE(C1.GRADE_PROSENTASE,0) > 0
		AND B.TIPE_UJIAN_ID NOT IN (7, 17)
		AND A.JADWAL_TES_ID = ".$parseid."
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanBenarSoalWPT($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.UJIAN_ID, A.PEGAWAI_ID, B.TIPE_UJIAN_ID, B.BANK_SOAL_ID")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.PEGAWAI_ID, B.BANK_SOAL_ID
			, C1.JAWABAN, B.TIPE_UJIAN_ID, CAST(C1.KUNCI_JAWABAN AS NUMERIC) GRADE_PROSENTASE
		FROM
		(
			SELECT * FROM cat.ujian_pegawai_daftar A
		) A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.wpt_soal C ON B.BANK_SOAL_ID = C.WPT_SOAL_ID
		INNER JOIN cat.wpt_pilihan C1 ON B.BANK_SOAL_ID = C1.WPT_SOAL_ID
		WHERE 1=1
		AND CAST(KUNCI_JAWABAN AS NUMERIC) > 0
		AND A.JADWAL_TES_ID = ".$parseid."
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanIstPegawai($paramsArray=array(),$limit=-1,$from=-1, $parseid, $statement='', $sOrder= "ORDER BY A.BANK_SOAL_ID")
	{
		$str = "
		SELECT
		A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.BANK_SOAL_ID, A.KETERANGAN, COALESCE(B.NILAI,0) NILAI
		FROM cat_pegawai.UJIAN_PEGAWAI_KETERANGAN_".$parseid." A
		LEFT JOIN cat.IST_KUNCI_4 B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND REPLACE(UPPER(A.KETERANGAN), ' ', '') = REPLACE(UPPER(B.NAMA), ' ', '')
		WHERE A.BANK_SOAL_ID >= 248 AND A.BANK_SOAL_ID <= 263
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanIstKunci($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY A.BANK_SOAL_ID, A.NILAI DESC")
	{
		$str = "
		SELECT A.BANK_SOAL_ID, REPLACE(UPPER(A.NAMA), ' ', '') KETERANGAN, NILAI
		FROM cat.IST_KUNCI_4 A
		WHERE 1=1 AND A.NILAI > 0
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringBigFive($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT A.*
		, 
		JA.NOMOR_URUT NOMOR_URUT_GENERATE
		, B1.PERSENTASE PERSEN_AGREEABLENESS, B2.PERSENTASE PERSEN_CONSCIENTIOUSNESS, B3.PERSENTASE PERSEN_EXTRAVERSION
		, B4.PERSENTASE PERSEN_NEUROTICISM, B5.PERSENTASE PERSEN_OPENNESS
		FROM
		(
			SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1
		) A
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN (SELECT * FROM pbigfive('".$jadwaltesid."') WHERE KATEGORI = 'Agreeableness') B1 ON B1.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN (SELECT * FROM pbigfive('".$jadwaltesid."') WHERE KATEGORI = 'Conscientiousness') B2 ON B2.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN (SELECT * FROM pbigfive('".$jadwaltesid."') WHERE KATEGORI = 'Extraversion') B3 ON B3.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN (SELECT * FROM pbigfive('".$jadwaltesid."') WHERE KATEGORI = 'Neuroticism') B4 ON B4.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN (SELECT * FROM pbigfive('".$jadwaltesid."') WHERE KATEGORI = 'Openness') B5 ON B5.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;

		// WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringBigFive($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.NAMA_PEGAWAI, A.NIP_BARU
			FROM
			(
				SELECT
				UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
				, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU1, A.NIP_BARU
				FROM cat.ujian_pegawai_daftar B
				INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
			) A
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		".$statementdetil;
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringWpt($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str="
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL, A.NIP_BARU
			, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
			, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
			, COALESCE(HSL1.JUMLAH_CHECK,0) JUMLAH_JAWAB
			, COALESCE(D.JUMLAH_SOAL,0) - COALESCE(HSL1.JUMLAH_CHECK,0) JUMLAH_BELUM_JAWAB
			, cat.wptbenar(B.PEGAWAI_UMUR_NORMA, COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) JUMLAH_SKOR_BENAR
			, COALESCE(D.JUMLAH_SOAL,0) - cat.wptbenar(B.PEGAWAI_UMUR_NORMA, COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0)) JUMLAH_BELUM_SKOR_BENAR
			--, Q.IQ IQ_NILAI
			--, cat.wptiq(Q.IQ) IQ_KETERANGAN
			, cat.wptiq(Q.IQ) || ' (' || Q.IQ || ')'  IQ_KETERANGAN
			, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM (select *, cat.NORMA_UMUR(B.UJIAN_ID, B.PEGAWAI_ID) PEGAWAI_UMUR_NORMA from cat.ujian_pegawai_daftar B) B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
			FROM formula_assesment_ujian_tahap A
			INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
			WHERE 1=1
			AND PARENT_ID = '0'
		) C ON B.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap a
			INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
			GROUP BY A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP
		) D ON B.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, COUNT(1) JUMLAH_CHECK
			FROM
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, A.BANK_SOAL_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, A.BANK_SOAL_ID
			) A
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
		) HSL1 ON HSL1.PEGAWAI_ID = B.PEGAWAI_ID AND HSL1.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN
		(
			SELECT
			A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
			, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
			FROM
			(
				SELECT 
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
				FROM
				(
					SELECT A.*
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT
							A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
							, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
							, COUNT(1) JUMLAH_CHECK
							FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
							INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
							INNER JOIN 
							(
								SELECT WPT_SOAL_ID BANK_SOAL_ID, WPT_PILIHAN_ID BANK_SOAL_PILIHAN_ID, JAWABAN, CAST(KUNCI_JAWABAN AS NUMERIC) GRADE_PROSENTASE
								FROM cat.wpt_pilihan
							) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
							GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
						) A
						INNER JOIN
						(
							SELECT WPT_SOAL_ID BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
							FROM cat.wpt_pilihan
							WHERE CAST(KUNCI_JAWABAN AS NUMERIC) > 0
							GROUP BY WPT_SOAL_ID
						) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
						WHERE GRADE_PROSENTASE = 100
						ORDER BY A.BANK_SOAL_ID
					) A
				) A
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
			) A
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN cat.wpt_rs Q ON COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) = Q.NILAI
		LEFT JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountByParamsMonitoringWpt($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM (select *, cat.NORMA_UMUR(B.UJIAN_ID, B.PEGAWAI_ID) PEGAWAI_UMUR_NORMA from cat.ujian_pegawai_daftar B) B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
			FROM formula_assesment_ujian_tahap A
			INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
			WHERE 1=1
			AND PARENT_ID = '0'
		) C ON B.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap a
			INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
			GROUP BY A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP
		) D ON B.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, COUNT(1) JUMLAH_CHECK
			FROM
			(
				SELECT
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, A.BANK_SOAL_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, A.BANK_SOAL_ID
			) A
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
		) HSL1 ON HSL1.PEGAWAI_ID = B.PEGAWAI_ID AND HSL1.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN
		(
			SELECT
			A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
			, COALESCE(A.JUMLAH_BENAR,0) JUMLAH_BENAR_PEGAWAI
			FROM
			(
				SELECT 
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID, COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
				FROM
				(
					SELECT A.*
					FROM
					(
						SELECT A.*
						FROM
						(
							SELECT
							A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2) ID, A.BANK_SOAL_ID
							, SUM(GRADE_PROSENTASE) GRADE_PROSENTASE
							, COUNT(1) JUMLAH_CHECK
							FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
							INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
							INNER JOIN 
							(
								SELECT WPT_SOAL_ID BANK_SOAL_ID, WPT_PILIHAN_ID BANK_SOAL_PILIHAN_ID, JAWABAN, CAST(KUNCI_JAWABAN AS NUMERIC) GRADE_PROSENTASE
								FROM cat.wpt_pilihan
							) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
							GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID, SUBSTR(TU.ID,1,2), A.BANK_SOAL_ID
						) A
						INNER JOIN
						(
							SELECT WPT_SOAL_ID BANK_SOAL_ID, COUNT(1) JUMLAH_CHECK
							FROM cat.wpt_pilihan
							WHERE CAST(KUNCI_JAWABAN AS NUMERIC) > 0
							GROUP BY WPT_SOAL_ID
						) B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID AND A.JUMLAH_CHECK = B.JUMLAH_CHECK
						WHERE GRADE_PROSENTASE = 100
						ORDER BY A.BANK_SOAL_ID
					) A
				) A
				WHERE 1=1
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.FORMULA_ASSESMENT_ID, A.ID
			) A
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN cat.wpt_rs Q ON COALESCE(HSL.JUMLAH_BENAR_PEGAWAI,0) = Q.NILAI
		LEFT JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}

	function selectByParamsMonitoringKertih($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str="
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL, A.NIP_BARU
			, C.UJIAN_TAHAP_ID, C.TIPE_UJIAN_ID
			, COALESCE(D.JUMLAH_SOAL,0) JUMLAH_SOAL
			, cat.kertihkesimpulan('1', COALESCE(D.JUMLAH_SOAL,0), COALESCE(HSL.NILAI,0)) NILAI
			, cat.kertihkesimpulan('2', COALESCE(D.JUMLAH_SOAL,0), COALESCE(HSL.NILAI,0)) NILAI_KESIMPULAN
			, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
			FROM formula_assesment_ujian_tahap A
			INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
			WHERE 1=1
			AND PARENT_ID = '0' AND A.TIPE_UJIAN_ID = 48
		) C ON B.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap a
			INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
			WHERE A.TIPE_UJIAN_ID = 48
			GROUP BY A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP
		) D ON B.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID
		LEFT JOIN
		(
			SELECT
			A.JADWAL_TES_ID, PEGAWAI_ID
			, SUM(cat.kertihkonversi(JENIS, CAST(A.KETERANGAN AS NUMERIC))) NILAI
			FROM cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." A
			INNER JOIN cat.kertih_soal B ON A.BANK_SOAL_ID = B.KERTIH_SOAL_ID
			WHERE 1=1 AND A.TIPE_UJIAN_ID = 48
			GROUP BY A.JADWAL_TES_ID, PEGAWAI_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountByParamsMonitoringKertih($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.ID, A.FORMULA_ASSESMENT_ID, A.TIPE_UJIAN_ID
			FROM formula_assesment_ujian_tahap A
			INNER JOIN cat.tipe_ujian B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
			WHERE 1=1
			AND PARENT_ID = '0' AND A.TIPE_UJIAN_ID = 48
		) C ON B.FORMULA_ASSESMENT_ID = C.FORMULA_ASSESMENT_ID
		INNER JOIN
		(
			SELECT A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP JUMLAH_SOAL
			FROM formula_assesment_ujian_tahap a
			INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = A.TIPE_UJIAN_ID
			WHERE A.TIPE_UJIAN_ID = 48
			GROUP BY A.FORMULA_ASSESMENT_ID, A.JUMLAH_SOAL_UJIAN_TAHAP
		) D ON B.FORMULA_ASSESMENT_ID = D.FORMULA_ASSESMENT_ID
		LEFT JOIN
		(
			SELECT
			A.JADWAL_TES_ID, PEGAWAI_ID
			, SUM(cat.kertihkonversi(JENIS, CAST(A.KETERANGAN AS NUMERIC))) NILAI
			FROM cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." A
			INNER JOIN cat.kertih_soal B ON A.BANK_SOAL_ID = B.KERTIH_SOAL_ID
			WHERE 1=1 AND A.TIPE_UJIAN_ID = 48
			GROUP BY A.JADWAL_TES_ID, PEGAWAI_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 ".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}

	function selectByParamsMonitoringDataHolland($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="ORDER BY A.BANK_SOAL_ID")
	{
		$str="
		SELECT (row_number() over(ORDER BY A.BANK_SOAL_ID)) NOMOR, C.PERTANYAAN, B.JAWABAN
		FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
		LEFT JOIN cat.holand_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.HOLAND_PILIHAN_ID
		INNER JOIN cat.holand_soal C ON A.BANK_SOAL_ID = C.HOLAND_SOAL_ID
		WHERE 1=1
		".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsMonitoringHolland($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementDetil='', $sorder="")
	{
		$str="
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL, A.NIP_BARU
			, COALESCE(NILAI_R,0) NILAI_R, COALESCE(NILAI_I,0) NILAI_I
			, COALESCE(NILAI_A,0) NILAI_A, COALESCE(NILAI_S,0) NILAI_S
			, COALESCE(NILAI_E,0) NILAI_E, COALESCE(NILAI_C,0) NILAI_C
			, cat.hollandkesimpulan('R', NILAI_R, 'I', NILAI_I, 'A', NILAI_A, 'S', NILAI_S, 'E', NILAI_E, 'C', NILAI_C)  HASIL
			, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID
			, COUNT(CASE WHEN UPPER(C.KODE_SOAL)=UPPER('R') and b.grade_a = 1 THEN 1 END) NILAI_R
			, COUNT(CASE WHEN UPPER(C.KODE_SOAL)=UPPER('I') and b.grade_a = 1 THEN 1 END) NILAI_I
			, COUNT(CASE WHEN UPPER(C.KODE_SOAL)=UPPER('A') and b.grade_a = 1 THEN 1 END) NILAI_A
			, COUNT(CASE WHEN UPPER(C.KODE_SOAL)=UPPER('S') and b.grade_a = 1 THEN 1 END) NILAI_S
			, COUNT(CASE WHEN UPPER(C.KODE_SOAL)=UPPER('E') and b.grade_a = 1 THEN 1 END) NILAI_E
			, COUNT(CASE WHEN UPPER(C.KODE_SOAL)=UPPER('C') and b.grade_a = 1 THEN 1 END) NILAI_C
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			INNER JOIN cat.holand_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.HOLAND_PILIHAN_ID
			INNER JOIN cat.holand_soal C ON A.BANK_SOAL_ID = C.HOLAND_SOAL_ID
			WHERE 1=1 ". $statementDetil ."
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsMonitoringHollandBackup($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $statementDetil='', $sorder="")
	{
		$str="
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.JADWAL_TES_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL, A.NIP_BARU
			, COALESCE(HSL.AK_S,0) AK_S, COALESCE(HSL.AK_TS,0) AK_TS
			, COALESCE(HSL.KOMP_Y,0) KOMP_Y, COALESCE(HSL.KOMP_T,0) KOMP_T
			, COALESCE(HSL.PEKJ_Y,0) PEKJ_Y, COALESCE(HSL.PEKJ_T,0) PEKJ_T
			, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID
			, COUNT(CASE WHEN UPPER(C.GROUP_SOAL)=UPPER('AKTIVITAS') AND UPPER(JAWABAN) = UPPER('SUKA') THEN 1 END) AK_S
			, COUNT(CASE WHEN UPPER(C.GROUP_SOAL)=UPPER('AKTIVITAS') AND UPPER(JAWABAN) = UPPER('TIDAK SUKA') THEN 1 END) AK_TS
			, COUNT(CASE WHEN UPPER(C.GROUP_SOAL)=UPPER('KOMPETENSI') AND UPPER(JAWABAN) = UPPER('YA') THEN 1 END) KOMP_Y
			, COUNT(CASE WHEN UPPER(C.GROUP_SOAL)=UPPER('KOMPETENSI') AND UPPER(JAWABAN) = UPPER('TIDAK') THEN 1 END) KOMP_T
			, COUNT(CASE WHEN UPPER(C.GROUP_SOAL)=UPPER('PEKERJAAN') AND UPPER(JAWABAN) = UPPER('YA') THEN 1 END) PEKJ_Y
			, COUNT(CASE WHEN UPPER(C.GROUP_SOAL)=UPPER('PEKERJAAN') AND UPPER(JAWABAN) = UPPER('TIDAK') THEN 1 END) PEKJ_T
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			INNER JOIN cat.holand_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.HOLAND_PILIHAN_ID
			INNER JOIN cat.holand_soal C ON A.BANK_SOAL_ID = C.HOLAND_SOAL_ID
			WHERE 1=1 ". $statementDetil ."
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sorder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountByParamsMonitoringHolland($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM
		(
			SELECT
				B.PEGAWAI_ID, B.JADWAL_TES_ID
			FROM cat.ujian_pegawai_daftar B
			INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement." 
		) A
		WHERE 1=1
		";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>
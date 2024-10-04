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

  class Rekap extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Rekap()
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
				CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
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

    function selectByParamsMonitoringPapiHasil($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
			, COALESCE(HSL.NILAI_W,0) NILAI_W
			, CASE 
			WHEN COALESCE(HSL.NILAI_W,0) < 4 THEN 'Hanya butuh gambaran ttg kerangka tugas scr garis besar, berpatokan pd tujuan, dpt bekerja dlm suasana yg kurang berstruktur, berinsiatif, mandiri. Tdk patuh, cenderung mengabaikan/tdk paham pentingnya peraturan/prosedur, suka membuat peraturan sendiri yg bisa bertentangan dg yg telah ada.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 4 AND COALESCE(HSL.NILAI_W,0) < 6 THEN 'Perlu pengarahan awal dan tolok ukur keberhasilan.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 6 AND COALESCE(HSL.NILAI_W,0) < 8 THEN 'Membutuhkan uraian rinci mengenai tugas, dan batasan tanggung  jawab serta wewenang.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 8 AND COALESCE(HSL.NILAI_W,0) < 10 THEN 'Patuh pada kebijaksanaan, peraturan dan struktur organisasi. Ingin segala sesuatunya diuraikan secara rinci, kurang memiliki inisiatif, tdk fleksibel, terlalu tergantung pada organisasi, berharap disuapi.'
			END INFO_W
			, COALESCE(HSL.NILAI_F,0) NILAI_F
			, CASE 
			WHEN COALESCE(HSL.NILAI_F,0) < 4 THEN 'Otonom, dapat bekerja sendiri tanpa campur tangan orang lain, motivasi timbul krn pekerjaan itu sendiri - bukan krn pujian dr otoritas. Mempertanyakan otoritas, cenderung tidak puas thdp atasan, loya- litas lebih didasari kepentingan pribadi.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 4 AND COALESCE(HSL.NILAI_F,0) < 7 THEN 'Loyal pada Perusahaan.'
			WHEN COALESCE(HSL.NILAI_F,0) = 7 THEN 'Loyal pada pribadi atasan.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 8 AND COALESCE(HSL.NILAI_F,0) < 10 THEN 'Loyal, berusaha dekat dg pribadi atasan, ingin menyenangkan atasan, sadar akan harapan atasan akan dirinya.  Terlalu memper- hatikan cara menyenangkan atasan, tidak berani berpendirian lain, tidak mandiri.'
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
			, CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			AA.JADWAL_TES_ID, AA.PEGAWAI_ID, AA.UJIAN_ID, AA.UJIAN_TAHAP_ID
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
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) AA
			LEFT JOIN
			(
				SELECT 
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(GRADE_A),0) NILAI
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				AND B.SOAL_PAPI_ID IN (10, 20, 30, 40, 50, 60, 70, 80, 90)
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) W ON AA.PEGAWAI_ID = W.PEGAWAI_ID AND AA.UJIAN_ID = W.UJIAN_ID AND AA.UJIAN_TAHAP_ID = W.UJIAN_TAHAP_ID AND AA.JADWAL_TES_ID = W.JADWAL_TES_ID
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

			SELECT AA.JADWAL_TES_ID, AA.PEGAWAI_ID, AA.UJIAN_ID, AA.UJIAN_TAHAP_ID
			FROM
			(
				SELECT 
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) AA
			WHERE 1=1
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
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

      function selectByParamsMonitoringBaruKraepelin($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			A.*
			, cat_rekrutmen.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KETELITIAN_SS, 'KESIMPULANKETELITIAN') KETELITIAN_KESIMPULAN
			, cat_rekrutmen.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KECEPATAN_SS, 'KESIMPULANKECEPATAN') KECEPATAN_KESIMPULAN
		FROM
		(
			SELECT
				A.*
				, CAST(cat_rekrutmen.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KETELITIAN_RS, 'KETELITIAN') AS NUMERIC) KETELITIAN_SS
				, CAST(cat_rekrutmen.KRAPELIN_KONVERSI(A.PENDIDIKAN, A.KECEPATAN_RS, 'KECEPATAN') AS NUMERIC) KECEPATAN_SS
			FROM
			(
				SELECT
					A.*
					, A.TOTAL_KESALAHAN_1 + A.TOTAL_KESALAHAN_2 + A.TOTAL_KESALAHAN_3 + A.TOTAL_TDK_ISI_1 + A.TOTAL_TDK_ISI_2 + A.TOTAL_TDK_ISI_3 KETELITIAN_RS
					, A.PUNCAK_TERTINGGI + A.PUNCAK_TERENDAH KECEPATAN_RS
				FROM
				(
					SELECT
					UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.LOWONGAN_ID
					, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU
					, COALESCE(SALAH1.TOTAL_KESALAHAN,0) TOTAL_KESALAHAN_1, COALESCE(SALAH2.TOTAL_KESALAHAN,0) TOTAL_KESALAHAN_2, COALESCE(SALAH3.TOTAL_KESALAHAN,0) TOTAL_KESALAHAN_3
					, COALESCE(TDKISI1.TOTAL_TDK_ISI,0) TOTAL_TDK_ISI_1, COALESCE(TDKISI2.TOTAL_TDK_ISI,0) TOTAL_TDK_ISI_2, COALESCE(TDKISI3.TOTAL_TDK_ISI,0) TOTAL_TDK_ISI_3
					, COALESCE(TINGGI.PUNCAK_TERTINGGI,0) PUNCAK_TERTINGGI, RENDAH.PUNCAK_TERENDAH
					, cat_rekrutmen.P_AREA_TINGGI(".$jadwaltesid.", B.PEGAWAI_ID) LIST_PUNCAK_TERTINGGI
					, cat_rekrutmen.P_AREA_RENDAH(".$jadwaltesid.", B.PEGAWAI_ID) LIST_PUNCAK_TERENDAH
					, to_number(A.PENDIDIKAN,'9999') PENDIDIKAN
					FROM cat_rekrutmen.ujian_pegawai_daftar B
					INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_KESALAHAN('".$jadwaltesid."', 1)) SALAH1 ON SALAH1.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_KESALAHAN('".$jadwaltesid."', 2)) SALAH2 ON SALAH2.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_KESALAHAN('".$jadwaltesid."', 3)) SALAH3 ON SALAH3.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_TDK_ISI('".$jadwaltesid."', 1)) TDKISI1 ON TDKISI1.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_TDK_ISI('".$jadwaltesid."', 2)) TDKISI2 ON TDKISI2.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_TDK_ISI('".$jadwaltesid."', 3)) TDKISI3 ON TDKISI3.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_TINGGI('".$jadwaltesid."')) TINGGI ON TINGGI.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN (SELECT * FROM cat_rekrutmen.P_KRAPELIN_RENDAH('".$jadwaltesid."')) RENDAH ON RENDAH.PEGAWAI_ID = B.PEGAWAI_ID
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
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.LOWONGAN_ID
			, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU
			FROM cat_rekrutmen.ujian_pegawai_daftar B
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

    function selectByParamsGrafikKraepelinBaru($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$soal= 40;
		$str = "
	    SELECT
		UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.LOWONGAN_ID
		, A.NAMA NAMA_PEGAWAI, A.EMAIL NIP_BARU
		";

		for($x=1; $x <= $soal; $x++)
		{
			$str .= ", COALESCE(XX.Y_DATA".$x.",0) Y_DATA".$x;
		}
		
		$str .= "
		FROM cat_rekrutmen.ujian_pegawai_daftar B
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
				FROM cat_rekrutmen.N_KRAEPELIN_JAWAB A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1 FROM cat_rekrutmen.N_KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
					AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
				)
				GROUP BY X_DATA
			) A
			LEFT JOIN
			(
				SELECT XX.UJIAN_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
				FROM cat_rekrutmen_pegawai.UJIAN_KRAEPELIN_N_".$jadwaltesid." XX
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

    function selectByParamsMonitoringKraepelin($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
	    SELECT
		UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
		, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
		, COALESCE(JS.JUMLAH_SALAH,0) JUMLAH_SALAH
		, COALESCE(JL.JUMLAH_TERLONCATI,0) JUMLAH_TERLONCATI
		, XX.Y_DATA1, XX.Y_DATA2, XX.Y_DATA3, XX.Y_DATA4, XX.Y_DATA5
		, XX.Y_DATA6, XX.Y_DATA7, XX.Y_DATA8, XX.Y_DATA9, XX.Y_DATA10
		, XX.Y_DATA11, XX.Y_DATA12, XX.Y_DATA13, XX.Y_DATA14, XX.Y_DATA15
		, XX.Y_DATA16, XX.Y_DATA17, XX.Y_DATA18, XX.Y_DATA19, XX.Y_DATA20
		, XX.Y_DATA21, XX.Y_DATA22, XX.Y_DATA23, XX.Y_DATA24, XX.Y_DATA25
		, XX.Y_DATA26, XX.Y_DATA27, XX.Y_DATA28, XX.Y_DATA29, XX.Y_DATA30
		, XX.Y_DATA31, XX.Y_DATA32, XX.Y_DATA33, XX.Y_DATA34, XX.Y_DATA35
		, XX.Y_DATA36, XX.Y_DATA37, XX.Y_DATA38, XX.Y_DATA39, XX.Y_DATA40
		, XX.Y_DATA41, XX.Y_DATA42, XX.Y_DATA43, XX.Y_DATA44, XX.Y_DATA45
		, XX.Y_DATA46, XX.Y_DATA47, XX.Y_DATA48, XX.Y_DATA49, XX.Y_DATA50
		, ROUND(CAST(( COALESCE(XX.Y_DATA1,0) + COALESCE(XX.Y_DATA2,0) + COALESCE(XX.Y_DATA3,0) + COALESCE(XX.Y_DATA4,0) + COALESCE(XX.Y_DATA5,0) + COALESCE(XX.Y_DATA6,0) + COALESCE(XX.Y_DATA7,0) + COALESCE(XX.Y_DATA8,0) + COALESCE(XX.Y_DATA9,0) + COALESCE(XX.Y_DATA10,0) + COALESCE(XX.Y_DATA11,0) + COALESCE(XX.Y_DATA12,0) + COALESCE(XX.Y_DATA13,0) + COALESCE(XX.Y_DATA14,0) + COALESCE(XX.Y_DATA15,0) + COALESCE(XX.Y_DATA16,0) + COALESCE(XX.Y_DATA17,0) + COALESCE(XX.Y_DATA18,0) + COALESCE(XX.Y_DATA19,0) + COALESCE(XX.Y_DATA20,0) + COALESCE(XX.Y_DATA21,0) + COALESCE(XX.Y_DATA22,0) + COALESCE(XX.Y_DATA23,0) + COALESCE(XX.Y_DATA24,0) + COALESCE(XX.Y_DATA25,0) + COALESCE(XX.Y_DATA26,0) + COALESCE(XX.Y_DATA27,0) + COALESCE(XX.Y_DATA28,0) + COALESCE(XX.Y_DATA29,0) + COALESCE(XX.Y_DATA30,0) + COALESCE(XX.Y_DATA31,0) + COALESCE(XX.Y_DATA32,0) + COALESCE(XX.Y_DATA33,0) + COALESCE(XX.Y_DATA34,0) + COALESCE(XX.Y_DATA35,0) + COALESCE(XX.Y_DATA36,0) + COALESCE(XX.Y_DATA37,0) + COALESCE(XX.Y_DATA38,0) + COALESCE(XX.Y_DATA39,0) + COALESCE(XX.Y_DATA40,0) + COALESCE(XX.Y_DATA41,0) + COALESCE(XX.Y_DATA42,0) + COALESCE(XX.Y_DATA43,0) + COALESCE(XX.Y_DATA44,0) + COALESCE(XX.Y_DATA45,0) + COALESCE(XX.Y_DATA46,0) + COALESCE(XX.Y_DATA47,0) + COALESCE(XX.Y_DATA48,0) + COALESCE(XX.Y_DATA49,0) + COALESCE(XX.Y_DATA50,0) ) AS NUMERIC) / 50,2) RATA_RATA
		, COALESCE(XX.Y_DATA1,0) + COALESCE(XX.Y_DATA2,0) + COALESCE(XX.Y_DATA3,0) + COALESCE(XX.Y_DATA4,0) + COALESCE(XX.Y_DATA5,0) + COALESCE(XX.Y_DATA6,0) + COALESCE(XX.Y_DATA7,0) + COALESCE(XX.Y_DATA8,0) + COALESCE(XX.Y_DATA9,0) + COALESCE(XX.Y_DATA10,0) + COALESCE(XX.Y_DATA11,0) + COALESCE(XX.Y_DATA12,0) + COALESCE(XX.Y_DATA13,0) + COALESCE(XX.Y_DATA14,0) + COALESCE(XX.Y_DATA15,0) + COALESCE(XX.Y_DATA16,0) + COALESCE(XX.Y_DATA17,0) + COALESCE(XX.Y_DATA18,0) + COALESCE(XX.Y_DATA19,0) + COALESCE(XX.Y_DATA20,0) + COALESCE(XX.Y_DATA21,0) + COALESCE(XX.Y_DATA22,0) + COALESCE(XX.Y_DATA23,0) + COALESCE(XX.Y_DATA24,0) + COALESCE(XX.Y_DATA25,0) + COALESCE(XX.Y_DATA26,0) + COALESCE(XX.Y_DATA27,0) + COALESCE(XX.Y_DATA28,0) + COALESCE(XX.Y_DATA29,0) + COALESCE(XX.Y_DATA30,0) + COALESCE(XX.Y_DATA31,0) + COALESCE(XX.Y_DATA32,0) + COALESCE(XX.Y_DATA33,0) + COALESCE(XX.Y_DATA34,0) + COALESCE(XX.Y_DATA35,0) + COALESCE(XX.Y_DATA36,0) + COALESCE(XX.Y_DATA37,0) + COALESCE(XX.Y_DATA38,0) + COALESCE(XX.Y_DATA39,0) + COALESCE(XX.Y_DATA40,0) + COALESCE(XX.Y_DATA41,0) + COALESCE(XX.Y_DATA42,0) + COALESCE(XX.Y_DATA43,0) + COALESCE(XX.Y_DATA44,0) + COALESCE(XX.Y_DATA45,0) + COALESCE(XX.Y_DATA46,0) + COALESCE(XX.Y_DATA47,0) + COALESCE(XX.Y_DATA48,0) + COALESCE(XX.Y_DATA49,0) + COALESCE(XX.Y_DATA50,0) TOTAL
		, 
		CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			B.PEGAWAI_ID
			, SUM(CASE WHEN A.X_DATA = 1 THEN COALESCE(B.Y_DATA,0) END) Y_DATA1
			, SUM(CASE WHEN A.X_DATA = 2 THEN COALESCE(B.Y_DATA,0) END) Y_DATA2
			, SUM(CASE WHEN A.X_DATA = 3 THEN COALESCE(B.Y_DATA,0) END) Y_DATA3
			, SUM(CASE WHEN A.X_DATA = 4 THEN COALESCE(B.Y_DATA,0) END) Y_DATA4
			, SUM(CASE WHEN A.X_DATA = 5 THEN COALESCE(B.Y_DATA,0) END) Y_DATA5
			, SUM(CASE WHEN A.X_DATA = 6 THEN COALESCE(B.Y_DATA,0) END) Y_DATA6
			, SUM(CASE WHEN A.X_DATA = 7 THEN COALESCE(B.Y_DATA,0) END) Y_DATA7
			, SUM(CASE WHEN A.X_DATA = 8 THEN COALESCE(B.Y_DATA,0) END) Y_DATA8
			, SUM(CASE WHEN A.X_DATA = 9 THEN COALESCE(B.Y_DATA,0) END) Y_DATA9
			, SUM(CASE WHEN A.X_DATA = 10 THEN COALESCE(B.Y_DATA,0) END) Y_DATA10
			, SUM(CASE WHEN A.X_DATA = 11 THEN COALESCE(B.Y_DATA,0) END) Y_DATA11
			, SUM(CASE WHEN A.X_DATA = 12 THEN COALESCE(B.Y_DATA,0) END) Y_DATA12
			, SUM(CASE WHEN A.X_DATA = 13 THEN COALESCE(B.Y_DATA,0) END) Y_DATA13
			, SUM(CASE WHEN A.X_DATA = 14 THEN COALESCE(B.Y_DATA,0) END) Y_DATA14
			, SUM(CASE WHEN A.X_DATA = 15 THEN COALESCE(B.Y_DATA,0) END) Y_DATA15
			, SUM(CASE WHEN A.X_DATA = 16 THEN COALESCE(B.Y_DATA,0) END) Y_DATA16
			, SUM(CASE WHEN A.X_DATA = 17 THEN COALESCE(B.Y_DATA,0) END) Y_DATA17
			, SUM(CASE WHEN A.X_DATA = 18 THEN COALESCE(B.Y_DATA,0) END) Y_DATA18
			, SUM(CASE WHEN A.X_DATA = 19 THEN COALESCE(B.Y_DATA,0) END) Y_DATA19
			, SUM(CASE WHEN A.X_DATA = 20 THEN COALESCE(B.Y_DATA,0) END) Y_DATA20
			, SUM(CASE WHEN A.X_DATA = 21 THEN COALESCE(B.Y_DATA,0) END) Y_DATA21
			, SUM(CASE WHEN A.X_DATA = 22 THEN COALESCE(B.Y_DATA,0) END) Y_DATA22
			, SUM(CASE WHEN A.X_DATA = 23 THEN COALESCE(B.Y_DATA,0) END) Y_DATA23
			, SUM(CASE WHEN A.X_DATA = 24 THEN COALESCE(B.Y_DATA,0) END) Y_DATA24
			, SUM(CASE WHEN A.X_DATA = 25 THEN COALESCE(B.Y_DATA,0) END) Y_DATA25
			, SUM(CASE WHEN A.X_DATA = 26 THEN COALESCE(B.Y_DATA,0) END) Y_DATA26
			, SUM(CASE WHEN A.X_DATA = 27 THEN COALESCE(B.Y_DATA,0) END) Y_DATA27
			, SUM(CASE WHEN A.X_DATA = 28 THEN COALESCE(B.Y_DATA,0) END) Y_DATA28
			, SUM(CASE WHEN A.X_DATA = 29 THEN COALESCE(B.Y_DATA,0) END) Y_DATA29
			, SUM(CASE WHEN A.X_DATA = 30 THEN COALESCE(B.Y_DATA,0) END) Y_DATA30
			, SUM(CASE WHEN A.X_DATA = 31 THEN COALESCE(B.Y_DATA,0) END) Y_DATA31
			, SUM(CASE WHEN A.X_DATA = 32 THEN COALESCE(B.Y_DATA,0) END) Y_DATA32
			, SUM(CASE WHEN A.X_DATA = 33 THEN COALESCE(B.Y_DATA,0) END) Y_DATA33
			, SUM(CASE WHEN A.X_DATA = 34 THEN COALESCE(B.Y_DATA,0) END) Y_DATA34
			, SUM(CASE WHEN A.X_DATA = 35 THEN COALESCE(B.Y_DATA,0) END) Y_DATA35
			, SUM(CASE WHEN A.X_DATA = 36 THEN COALESCE(B.Y_DATA,0) END) Y_DATA36
			, SUM(CASE WHEN A.X_DATA = 37 THEN COALESCE(B.Y_DATA,0) END) Y_DATA37
			, SUM(CASE WHEN A.X_DATA = 38 THEN COALESCE(B.Y_DATA,0) END) Y_DATA38
			, SUM(CASE WHEN A.X_DATA = 39 THEN COALESCE(B.Y_DATA,0) END) Y_DATA39
			, SUM(CASE WHEN A.X_DATA = 40 THEN COALESCE(B.Y_DATA,0) END) Y_DATA40
			, SUM(CASE WHEN A.X_DATA = 41 THEN COALESCE(B.Y_DATA,0) END) Y_DATA41
			, SUM(CASE WHEN A.X_DATA = 42 THEN COALESCE(B.Y_DATA,0) END) Y_DATA42
			, SUM(CASE WHEN A.X_DATA = 43 THEN COALESCE(B.Y_DATA,0) END) Y_DATA43
			, SUM(CASE WHEN A.X_DATA = 44 THEN COALESCE(B.Y_DATA,0) END) Y_DATA44
			, SUM(CASE WHEN A.X_DATA = 45 THEN COALESCE(B.Y_DATA,0) END) Y_DATA45
			, SUM(CASE WHEN A.X_DATA = 46 THEN COALESCE(B.Y_DATA,0) END) Y_DATA46
			, SUM(CASE WHEN A.X_DATA = 47 THEN COALESCE(B.Y_DATA,0) END) Y_DATA47
			, SUM(CASE WHEN A.X_DATA = 48 THEN COALESCE(B.Y_DATA,0) END) Y_DATA48
			, SUM(CASE WHEN A.X_DATA = 49 THEN COALESCE(B.Y_DATA,0) END) Y_DATA49
			, SUM(CASE WHEN A.X_DATA = 50 THEN COALESCE(B.Y_DATA,0) END) Y_DATA50
			FROM 
			(
				SELECT X_DATA
				FROM cat.KRAEPELIN_JAWAB A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1 FROM cat.KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
					AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
				)
				GROUP BY X_DATA
			) A
			LEFT JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
				FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
				WHERE 1=1
				AND XX.NILAI IS NOT NULL
				GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
			) B ON A.X_DATA = B.X_DATA
			GROUP BY B.PEGAWAI_ID
		) XX ON XX.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID
			, COUNT(1) JUMLAH_TERLONCATI
			FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
			WHERE 1=1
			AND XX.NILAI IS NULL
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
					FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
					WHERE 1=1
					AND XX.NILAI IS NOT NULL
					GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
				) X WHERE XX.JADWAL_TES_ID = X.JADWAL_TES_ID AND XX.PEGAWAI_ID = X.PEGAWAI_ID AND XX.X_DATA = X.X_DATA AND XX.Y_DATA <= X.Y_DATA
			)
			GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID
		) JL ON JL.JADWAL_TES_ID = B.JADWAL_TES_ID AND JL.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT B.JADWAL_TES_ID, B.PEGAWAI_ID, COUNT(1) JUMLAH_SALAH
			FROM cat.KRAEPELIN_JAWAB A
			INNER JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, X_DATA, Y_DATA, NILAI
				FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
				WHERE 1=1
			) B ON A.X_DATA = B.X_DATA AND A.Y_DATA = B.Y_DATA AND A.NILAI != B.NILAI
			WHERE 1=1
			GROUP BY B.JADWAL_TES_ID, B.PEGAWAI_ID
		) JS ON JS.JADWAL_TES_ID = B.JADWAL_TES_ID AND JS.PEGAWAI_ID = B.PEGAWAI_ID
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		AND B.JADWAL_TES_ID = ".$jadwaltesid."
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

	function getCountByParamsMonitoringKraepelin($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			B.PEGAWAI_ID
			, SUM(CASE WHEN A.X_DATA = 1 THEN COALESCE(B.Y_DATA,0) END) Y_DATA1
			, SUM(CASE WHEN A.X_DATA = 2 THEN COALESCE(B.Y_DATA,0) END) Y_DATA2
			, SUM(CASE WHEN A.X_DATA = 3 THEN COALESCE(B.Y_DATA,0) END) Y_DATA3
			, SUM(CASE WHEN A.X_DATA = 4 THEN COALESCE(B.Y_DATA,0) END) Y_DATA4
			, SUM(CASE WHEN A.X_DATA = 5 THEN COALESCE(B.Y_DATA,0) END) Y_DATA5
			, SUM(CASE WHEN A.X_DATA = 6 THEN COALESCE(B.Y_DATA,0) END) Y_DATA6
			, SUM(CASE WHEN A.X_DATA = 7 THEN COALESCE(B.Y_DATA,0) END) Y_DATA7
			, SUM(CASE WHEN A.X_DATA = 8 THEN COALESCE(B.Y_DATA,0) END) Y_DATA8
			, SUM(CASE WHEN A.X_DATA = 9 THEN COALESCE(B.Y_DATA,0) END) Y_DATA9
			, SUM(CASE WHEN A.X_DATA = 10 THEN COALESCE(B.Y_DATA,0) END) Y_DATA10
			, SUM(CASE WHEN A.X_DATA = 11 THEN COALESCE(B.Y_DATA,0) END) Y_DATA11
			, SUM(CASE WHEN A.X_DATA = 12 THEN COALESCE(B.Y_DATA,0) END) Y_DATA12
			, SUM(CASE WHEN A.X_DATA = 13 THEN COALESCE(B.Y_DATA,0) END) Y_DATA13
			, SUM(CASE WHEN A.X_DATA = 14 THEN COALESCE(B.Y_DATA,0) END) Y_DATA14
			, SUM(CASE WHEN A.X_DATA = 15 THEN COALESCE(B.Y_DATA,0) END) Y_DATA15
			, SUM(CASE WHEN A.X_DATA = 16 THEN COALESCE(B.Y_DATA,0) END) Y_DATA16
			, SUM(CASE WHEN A.X_DATA = 17 THEN COALESCE(B.Y_DATA,0) END) Y_DATA17
			, SUM(CASE WHEN A.X_DATA = 18 THEN COALESCE(B.Y_DATA,0) END) Y_DATA18
			, SUM(CASE WHEN A.X_DATA = 19 THEN COALESCE(B.Y_DATA,0) END) Y_DATA19
			, SUM(CASE WHEN A.X_DATA = 20 THEN COALESCE(B.Y_DATA,0) END) Y_DATA20
			, SUM(CASE WHEN A.X_DATA = 31 THEN COALESCE(B.Y_DATA,0) END) Y_DATA31
			, SUM(CASE WHEN A.X_DATA = 32 THEN COALESCE(B.Y_DATA,0) END) Y_DATA32
			, SUM(CASE WHEN A.X_DATA = 33 THEN COALESCE(B.Y_DATA,0) END) Y_DATA33
			, SUM(CASE WHEN A.X_DATA = 34 THEN COALESCE(B.Y_DATA,0) END) Y_DATA34
			, SUM(CASE WHEN A.X_DATA = 35 THEN COALESCE(B.Y_DATA,0) END) Y_DATA35
			, SUM(CASE WHEN A.X_DATA = 36 THEN COALESCE(B.Y_DATA,0) END) Y_DATA36
			, SUM(CASE WHEN A.X_DATA = 37 THEN COALESCE(B.Y_DATA,0) END) Y_DATA37
			, SUM(CASE WHEN A.X_DATA = 38 THEN COALESCE(B.Y_DATA,0) END) Y_DATA38
			, SUM(CASE WHEN A.X_DATA = 39 THEN COALESCE(B.Y_DATA,0) END) Y_DATA39
			, SUM(CASE WHEN A.X_DATA = 40 THEN COALESCE(B.Y_DATA,0) END) Y_DATA40
			, SUM(CASE WHEN A.X_DATA = 41 THEN COALESCE(B.Y_DATA,0) END) Y_DATA41
			, SUM(CASE WHEN A.X_DATA = 42 THEN COALESCE(B.Y_DATA,0) END) Y_DATA42
			, SUM(CASE WHEN A.X_DATA = 43 THEN COALESCE(B.Y_DATA,0) END) Y_DATA43
			, SUM(CASE WHEN A.X_DATA = 44 THEN COALESCE(B.Y_DATA,0) END) Y_DATA44
			, SUM(CASE WHEN A.X_DATA = 45 THEN COALESCE(B.Y_DATA,0) END) Y_DATA45
			, SUM(CASE WHEN A.X_DATA = 46 THEN COALESCE(B.Y_DATA,0) END) Y_DATA46
			, SUM(CASE WHEN A.X_DATA = 47 THEN COALESCE(B.Y_DATA,0) END) Y_DATA47
			, SUM(CASE WHEN A.X_DATA = 48 THEN COALESCE(B.Y_DATA,0) END) Y_DATA48
			, SUM(CASE WHEN A.X_DATA = 49 THEN COALESCE(B.Y_DATA,0) END) Y_DATA49
			, SUM(CASE WHEN A.X_DATA = 50 THEN COALESCE(B.Y_DATA,0) END) Y_DATA50
			FROM 
			(
				SELECT X_DATA
				FROM cat.KRAEPELIN_JAWAB A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1 FROM cat.KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
					AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
				)
				GROUP BY X_DATA
			) A
			LEFT JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
				FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
				WHERE 1=1
				AND XX.NILAI IS NOT NULL
				GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
			) B ON A.X_DATA = B.X_DATA
			GROUP BY B.PEGAWAI_ID
		) XX ON XX.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID
			, COUNT(1) JUMLAH_TERLONCATI
			FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
			WHERE 1=1
			AND XX.NILAI IS NULL
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
					FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
					WHERE 1=1
					AND XX.NILAI IS NOT NULL
					GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
				) X WHERE XX.JADWAL_TES_ID = X.JADWAL_TES_ID AND XX.PEGAWAI_ID = X.PEGAWAI_ID AND XX.X_DATA = X.X_DATA AND XX.Y_DATA <= X.Y_DATA
			)
			GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID
		) JL ON JL.JADWAL_TES_ID = B.JADWAL_TES_ID AND JL.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT B.JADWAL_TES_ID, B.PEGAWAI_ID, COUNT(1) JUMLAH_SALAH
			FROM cat.KRAEPELIN_JAWAB A
			INNER JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, X_DATA, Y_DATA, NILAI
				FROM cat_pegawai.UJIAN_KRAEPELIN_".$jadwaltesid." XX
				WHERE 1=1
			) B ON A.X_DATA = B.X_DATA AND A.Y_DATA = B.Y_DATA AND A.NILAI != B.NILAI
			WHERE 1=1
			GROUP BY B.JADWAL_TES_ID, B.PEGAWAI_ID
		) JS ON JS.JADWAL_TES_ID = B.JADWAL_TES_ID AND JS.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE 1=1
		AND B.JADWAL_TES_ID = ".$jadwaltesid.$statement;

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

    function selectByParamsMonitoringKraepelinBak($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
	    SELECT
		UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, B.PEGAWAI_ID, B.FORMULA_ASSESMENT_ID
		, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
		, COALESCE(JS.JUMLAH_SALAH,0) JUMLAH_SALAH
		, COALESCE(JL.JUMLAH_TERLONCATI,0) JUMLAH_TERLONCATI
		, XX.Y_DATA1, XX.Y_DATA2, XX.Y_DATA3, XX.Y_DATA4, XX.Y_DATA5
		, XX.Y_DATA6, XX.Y_DATA7, XX.Y_DATA8, XX.Y_DATA9, XX.Y_DATA10
		, XX.Y_DATA11, XX.Y_DATA12, XX.Y_DATA13, XX.Y_DATA14, XX.Y_DATA15
		, XX.Y_DATA16, XX.Y_DATA17, XX.Y_DATA18, XX.Y_DATA19, XX.Y_DATA20
		, XX.Y_DATA21, XX.Y_DATA22, XX.Y_DATA23, XX.Y_DATA24, XX.Y_DATA25
		, XX.Y_DATA26, XX.Y_DATA27, XX.Y_DATA28, XX.Y_DATA29, XX.Y_DATA30
		, XX.Y_DATA31, XX.Y_DATA32, XX.Y_DATA33, XX.Y_DATA34, XX.Y_DATA35
		, XX.Y_DATA36, XX.Y_DATA37, XX.Y_DATA38, XX.Y_DATA39, XX.Y_DATA40
		, XX.Y_DATA41, XX.Y_DATA42, XX.Y_DATA43, XX.Y_DATA44, XX.Y_DATA45
		, XX.Y_DATA46, XX.Y_DATA47, XX.Y_DATA48, XX.Y_DATA49, XX.Y_DATA50
		, 
		CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			B.PEGAWAI_ID
			, SUM(CASE WHEN A.X_DATA = 1 THEN COALESCE(B.Y_DATA,0) END) Y_DATA1
			, SUM(CASE WHEN A.X_DATA = 2 THEN COALESCE(B.Y_DATA,0) END) Y_DATA2
			, SUM(CASE WHEN A.X_DATA = 3 THEN COALESCE(B.Y_DATA,0) END) Y_DATA3
			, SUM(CASE WHEN A.X_DATA = 4 THEN COALESCE(B.Y_DATA,0) END) Y_DATA4
			, SUM(CASE WHEN A.X_DATA = 5 THEN COALESCE(B.Y_DATA,0) END) Y_DATA5
			, SUM(CASE WHEN A.X_DATA = 6 THEN COALESCE(B.Y_DATA,0) END) Y_DATA6
			, SUM(CASE WHEN A.X_DATA = 7 THEN COALESCE(B.Y_DATA,0) END) Y_DATA7
			, SUM(CASE WHEN A.X_DATA = 8 THEN COALESCE(B.Y_DATA,0) END) Y_DATA8
			, SUM(CASE WHEN A.X_DATA = 9 THEN COALESCE(B.Y_DATA,0) END) Y_DATA9
			, SUM(CASE WHEN A.X_DATA = 10 THEN COALESCE(B.Y_DATA,0) END) Y_DATA10
			, SUM(CASE WHEN A.X_DATA = 11 THEN COALESCE(B.Y_DATA,0) END) Y_DATA11
			, SUM(CASE WHEN A.X_DATA = 12 THEN COALESCE(B.Y_DATA,0) END) Y_DATA12
			, SUM(CASE WHEN A.X_DATA = 13 THEN COALESCE(B.Y_DATA,0) END) Y_DATA13
			, SUM(CASE WHEN A.X_DATA = 14 THEN COALESCE(B.Y_DATA,0) END) Y_DATA14
			, SUM(CASE WHEN A.X_DATA = 15 THEN COALESCE(B.Y_DATA,0) END) Y_DATA15
			, SUM(CASE WHEN A.X_DATA = 16 THEN COALESCE(B.Y_DATA,0) END) Y_DATA16
			, SUM(CASE WHEN A.X_DATA = 17 THEN COALESCE(B.Y_DATA,0) END) Y_DATA17
			, SUM(CASE WHEN A.X_DATA = 18 THEN COALESCE(B.Y_DATA,0) END) Y_DATA18
			, SUM(CASE WHEN A.X_DATA = 19 THEN COALESCE(B.Y_DATA,0) END) Y_DATA19
			, SUM(CASE WHEN A.X_DATA = 20 THEN COALESCE(B.Y_DATA,0) END) Y_DATA20
			, SUM(CASE WHEN A.X_DATA = 21 THEN COALESCE(B.Y_DATA,0) END) Y_DATA21
			, SUM(CASE WHEN A.X_DATA = 22 THEN COALESCE(B.Y_DATA,0) END) Y_DATA22
			, SUM(CASE WHEN A.X_DATA = 23 THEN COALESCE(B.Y_DATA,0) END) Y_DATA23
			, SUM(CASE WHEN A.X_DATA = 24 THEN COALESCE(B.Y_DATA,0) END) Y_DATA24
			, SUM(CASE WHEN A.X_DATA = 25 THEN COALESCE(B.Y_DATA,0) END) Y_DATA25
			, SUM(CASE WHEN A.X_DATA = 26 THEN COALESCE(B.Y_DATA,0) END) Y_DATA26
			, SUM(CASE WHEN A.X_DATA = 27 THEN COALESCE(B.Y_DATA,0) END) Y_DATA27
			, SUM(CASE WHEN A.X_DATA = 28 THEN COALESCE(B.Y_DATA,0) END) Y_DATA28
			, SUM(CASE WHEN A.X_DATA = 29 THEN COALESCE(B.Y_DATA,0) END) Y_DATA29
			, SUM(CASE WHEN A.X_DATA = 30 THEN COALESCE(B.Y_DATA,0) END) Y_DATA30
			, SUM(CASE WHEN A.X_DATA = 31 THEN COALESCE(B.Y_DATA,0) END) Y_DATA31
			, SUM(CASE WHEN A.X_DATA = 32 THEN COALESCE(B.Y_DATA,0) END) Y_DATA32
			, SUM(CASE WHEN A.X_DATA = 33 THEN COALESCE(B.Y_DATA,0) END) Y_DATA33
			, SUM(CASE WHEN A.X_DATA = 34 THEN COALESCE(B.Y_DATA,0) END) Y_DATA34
			, SUM(CASE WHEN A.X_DATA = 35 THEN COALESCE(B.Y_DATA,0) END) Y_DATA35
			, SUM(CASE WHEN A.X_DATA = 36 THEN COALESCE(B.Y_DATA,0) END) Y_DATA36
			, SUM(CASE WHEN A.X_DATA = 37 THEN COALESCE(B.Y_DATA,0) END) Y_DATA37
			, SUM(CASE WHEN A.X_DATA = 38 THEN COALESCE(B.Y_DATA,0) END) Y_DATA38
			, SUM(CASE WHEN A.X_DATA = 39 THEN COALESCE(B.Y_DATA,0) END) Y_DATA39
			, SUM(CASE WHEN A.X_DATA = 40 THEN COALESCE(B.Y_DATA,0) END) Y_DATA40
			, SUM(CASE WHEN A.X_DATA = 41 THEN COALESCE(B.Y_DATA,0) END) Y_DATA41
			, SUM(CASE WHEN A.X_DATA = 42 THEN COALESCE(B.Y_DATA,0) END) Y_DATA42
			, SUM(CASE WHEN A.X_DATA = 43 THEN COALESCE(B.Y_DATA,0) END) Y_DATA43
			, SUM(CASE WHEN A.X_DATA = 44 THEN COALESCE(B.Y_DATA,0) END) Y_DATA44
			, SUM(CASE WHEN A.X_DATA = 45 THEN COALESCE(B.Y_DATA,0) END) Y_DATA45
			, SUM(CASE WHEN A.X_DATA = 46 THEN COALESCE(B.Y_DATA,0) END) Y_DATA46
			, SUM(CASE WHEN A.X_DATA = 47 THEN COALESCE(B.Y_DATA,0) END) Y_DATA47
			, SUM(CASE WHEN A.X_DATA = 48 THEN COALESCE(B.Y_DATA,0) END) Y_DATA48
			, SUM(CASE WHEN A.X_DATA = 49 THEN COALESCE(B.Y_DATA,0) END) Y_DATA49
			, SUM(CASE WHEN A.X_DATA = 50 THEN COALESCE(B.Y_DATA,0) END) Y_DATA50
			FROM 
			(
				SELECT X_DATA
				FROM cat.KRAEPELIN_JAWAB A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1 FROM cat.KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
					AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
				)
				GROUP BY X_DATA
			) A
			LEFT JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
				FROM cat.UJIAN_KRAEPELIN XX
				WHERE 1=1
				AND XX.NILAI IS NOT NULL
				AND XX.JADWAL_TES_ID = ".$jadwaltesid."
				GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
			) B ON A.X_DATA = B.X_DATA
			GROUP BY B.PEGAWAI_ID
		) XX ON XX.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID
			, COUNT(1) JUMLAH_TERLONCATI
			FROM cat.UJIAN_KRAEPELIN XX
			WHERE 1=1
			AND XX.NILAI IS NULL
			AND XX.JADWAL_TES_ID = ".$jadwaltesid."
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
					FROM cat.UJIAN_KRAEPELIN XX
					WHERE 1=1
					AND XX.NILAI IS NOT NULL
					AND XX.JADWAL_TES_ID = ".$jadwaltesid."
					GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
				) X WHERE XX.JADWAL_TES_ID = X.JADWAL_TES_ID AND XX.PEGAWAI_ID = X.PEGAWAI_ID AND XX.X_DATA = X.X_DATA AND XX.Y_DATA <= X.Y_DATA
			)
			GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID
		) JL ON JL.JADWAL_TES_ID = B.JADWAL_TES_ID AND JL.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT B.JADWAL_TES_ID, B.PEGAWAI_ID, COUNT(1) JUMLAH_SALAH
			FROM cat.KRAEPELIN_JAWAB A
			INNER JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, X_DATA, Y_DATA, NILAI
				FROM cat.UJIAN_KRAEPELIN XX
				WHERE 1=1
				AND XX.JADWAL_TES_ID = ".$jadwaltesid."
			) B ON A.X_DATA = B.X_DATA AND A.Y_DATA = B.Y_DATA AND A.NILAI != B.NILAI
			WHERE 1=1
			GROUP BY B.JADWAL_TES_ID, B.PEGAWAI_ID
		) JS ON JS.JADWAL_TES_ID = B.JADWAL_TES_ID AND JS.PEGAWAI_ID = B.PEGAWAI_ID
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		AND B.JADWAL_TES_ID = ".$jadwaltesid."
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

	function getCountByParamsMonitoringKraepelinBak($paramsArray=array(), $jadwaltesid, $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			B.PEGAWAI_ID
			, SUM(CASE WHEN A.X_DATA = 1 THEN COALESCE(B.Y_DATA,0) END) Y_DATA1
			, SUM(CASE WHEN A.X_DATA = 2 THEN COALESCE(B.Y_DATA,0) END) Y_DATA2
			, SUM(CASE WHEN A.X_DATA = 3 THEN COALESCE(B.Y_DATA,0) END) Y_DATA3
			, SUM(CASE WHEN A.X_DATA = 4 THEN COALESCE(B.Y_DATA,0) END) Y_DATA4
			, SUM(CASE WHEN A.X_DATA = 5 THEN COALESCE(B.Y_DATA,0) END) Y_DATA5
			, SUM(CASE WHEN A.X_DATA = 6 THEN COALESCE(B.Y_DATA,0) END) Y_DATA6
			, SUM(CASE WHEN A.X_DATA = 7 THEN COALESCE(B.Y_DATA,0) END) Y_DATA7
			, SUM(CASE WHEN A.X_DATA = 8 THEN COALESCE(B.Y_DATA,0) END) Y_DATA8
			, SUM(CASE WHEN A.X_DATA = 9 THEN COALESCE(B.Y_DATA,0) END) Y_DATA9
			, SUM(CASE WHEN A.X_DATA = 10 THEN COALESCE(B.Y_DATA,0) END) Y_DATA10
			, SUM(CASE WHEN A.X_DATA = 11 THEN COALESCE(B.Y_DATA,0) END) Y_DATA11
			, SUM(CASE WHEN A.X_DATA = 12 THEN COALESCE(B.Y_DATA,0) END) Y_DATA12
			, SUM(CASE WHEN A.X_DATA = 13 THEN COALESCE(B.Y_DATA,0) END) Y_DATA13
			, SUM(CASE WHEN A.X_DATA = 14 THEN COALESCE(B.Y_DATA,0) END) Y_DATA14
			, SUM(CASE WHEN A.X_DATA = 15 THEN COALESCE(B.Y_DATA,0) END) Y_DATA15
			, SUM(CASE WHEN A.X_DATA = 16 THEN COALESCE(B.Y_DATA,0) END) Y_DATA16
			, SUM(CASE WHEN A.X_DATA = 17 THEN COALESCE(B.Y_DATA,0) END) Y_DATA17
			, SUM(CASE WHEN A.X_DATA = 18 THEN COALESCE(B.Y_DATA,0) END) Y_DATA18
			, SUM(CASE WHEN A.X_DATA = 19 THEN COALESCE(B.Y_DATA,0) END) Y_DATA19
			, SUM(CASE WHEN A.X_DATA = 20 THEN COALESCE(B.Y_DATA,0) END) Y_DATA20
			, SUM(CASE WHEN A.X_DATA = 31 THEN COALESCE(B.Y_DATA,0) END) Y_DATA31
			, SUM(CASE WHEN A.X_DATA = 32 THEN COALESCE(B.Y_DATA,0) END) Y_DATA32
			, SUM(CASE WHEN A.X_DATA = 33 THEN COALESCE(B.Y_DATA,0) END) Y_DATA33
			, SUM(CASE WHEN A.X_DATA = 34 THEN COALESCE(B.Y_DATA,0) END) Y_DATA34
			, SUM(CASE WHEN A.X_DATA = 35 THEN COALESCE(B.Y_DATA,0) END) Y_DATA35
			, SUM(CASE WHEN A.X_DATA = 36 THEN COALESCE(B.Y_DATA,0) END) Y_DATA36
			, SUM(CASE WHEN A.X_DATA = 37 THEN COALESCE(B.Y_DATA,0) END) Y_DATA37
			, SUM(CASE WHEN A.X_DATA = 38 THEN COALESCE(B.Y_DATA,0) END) Y_DATA38
			, SUM(CASE WHEN A.X_DATA = 39 THEN COALESCE(B.Y_DATA,0) END) Y_DATA39
			, SUM(CASE WHEN A.X_DATA = 40 THEN COALESCE(B.Y_DATA,0) END) Y_DATA40
			, SUM(CASE WHEN A.X_DATA = 41 THEN COALESCE(B.Y_DATA,0) END) Y_DATA41
			, SUM(CASE WHEN A.X_DATA = 42 THEN COALESCE(B.Y_DATA,0) END) Y_DATA42
			, SUM(CASE WHEN A.X_DATA = 43 THEN COALESCE(B.Y_DATA,0) END) Y_DATA43
			, SUM(CASE WHEN A.X_DATA = 44 THEN COALESCE(B.Y_DATA,0) END) Y_DATA44
			, SUM(CASE WHEN A.X_DATA = 45 THEN COALESCE(B.Y_DATA,0) END) Y_DATA45
			, SUM(CASE WHEN A.X_DATA = 46 THEN COALESCE(B.Y_DATA,0) END) Y_DATA46
			, SUM(CASE WHEN A.X_DATA = 47 THEN COALESCE(B.Y_DATA,0) END) Y_DATA47
			, SUM(CASE WHEN A.X_DATA = 48 THEN COALESCE(B.Y_DATA,0) END) Y_DATA48
			, SUM(CASE WHEN A.X_DATA = 49 THEN COALESCE(B.Y_DATA,0) END) Y_DATA49
			, SUM(CASE WHEN A.X_DATA = 50 THEN COALESCE(B.Y_DATA,0) END) Y_DATA50
			FROM 
			(
				SELECT X_DATA
				FROM cat.KRAEPELIN_JAWAB A
				WHERE 1=1
				AND EXISTS
				(
					SELECT 1 FROM cat.KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
					AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
				)
				GROUP BY X_DATA
			) A
			LEFT JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
				FROM cat.UJIAN_KRAEPELIN XX
				WHERE 1=1
				AND XX.NILAI IS NOT NULL
				AND XX.JADWAL_TES_ID = ".$jadwaltesid."
				GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
			) B ON A.X_DATA = B.X_DATA
			GROUP BY B.PEGAWAI_ID
		) XX ON XX.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID
			, COUNT(1) JUMLAH_TERLONCATI
			FROM cat.UJIAN_KRAEPELIN XX
			WHERE 1=1
			AND XX.NILAI IS NULL
			AND XX.JADWAL_TES_ID = ".$jadwaltesid."
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA, MAX(XX.Y_DATA) Y_DATA
					FROM cat.UJIAN_KRAEPELIN XX
					WHERE 1=1
					AND XX.NILAI IS NOT NULL
					AND XX.JADWAL_TES_ID = ".$jadwaltesid."
					GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID, XX.X_DATA
				) X WHERE XX.JADWAL_TES_ID = X.JADWAL_TES_ID AND XX.PEGAWAI_ID = X.PEGAWAI_ID AND XX.X_DATA = X.X_DATA AND XX.Y_DATA <= X.Y_DATA
			)
			GROUP BY XX.JADWAL_TES_ID, XX.PEGAWAI_ID
		) JL ON JL.JADWAL_TES_ID = B.JADWAL_TES_ID AND JL.PEGAWAI_ID = B.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT B.JADWAL_TES_ID, B.PEGAWAI_ID, COUNT(1) JUMLAH_SALAH
			FROM cat.KRAEPELIN_JAWAB A
			INNER JOIN
			(
				SELECT XX.JADWAL_TES_ID, XX.PEGAWAI_ID, X_DATA, Y_DATA, NILAI
				FROM cat.UJIAN_KRAEPELIN XX
				WHERE 1=1
				AND XX.JADWAL_TES_ID = ".$jadwaltesid."
			) B ON A.X_DATA = B.X_DATA AND A.Y_DATA = B.Y_DATA AND A.NILAI != B.NILAI
			WHERE 1=1
			GROUP BY B.JADWAL_TES_ID, B.PEGAWAI_ID
		) JS ON JS.JADWAL_TES_ID = B.JADWAL_TES_ID AND JS.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE 1=1
		AND B.JADWAL_TES_ID = ".$jadwaltesid.$statement;

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

    function selectByParamsJadwalAcara($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.JADWAL_TES_ID, A.PUKUL1, PD.ASESOR_ID, NOMOR_URUT_GENERATE")
	{
		$str = "
		SELECT 
			A.JADWAL_TES_ID, JT.TANGGAL_TES
			, 
			CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
			, COALESCE(B.NAMA, 'Potensi') PENGGALIAN_NAMA, A.PUKUL1, A.PUKUL2
			, PD.* 
		FROM jadwal_acara A
		INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
		LEFT JOIN penggalian B ON B.PENGGALIAN_ID = A.PENGGALIAN_ID
		LEFT JOIN
		(
			SELECT
				A.JADWAL_ACARA_ID, A.ASESOR_ID, B.NAMA ASESOR_NAMA, C.PEGAWAI_ID
				, P.NAMA NAMA_PEGAWAI, P.NIP_BARU, JUMLAH_DATA
			FROM jadwal_asesor A
			INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
			LEFT JOIN jadwal_pegawai C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
			LEFT JOIN (SELECT A.JADWAL_ASESOR_ID, COUNT(1) JUMLAH_DATA FROM jadwal_pegawai A GROUP BY A.JADWAL_ASESOR_ID ) C1 ON A.JADWAL_ASESOR_ID = C1.JADWAL_ASESOR_ID
			LEFT JOIN simpeg.pegawai P ON C.PEGAWAI_ID = P.PEGAWAI_ID
			UNION ALL
			SELECT 
				A.JADWAL_ACARA_ID, A.ASESOR_ID, B.NAMA ASESOR_NAMA, C.PEGAWAI_ID
				, P.NAMA NAMA_PEGAWAI, P.NIP_BARU, JUMLAH_DATA
			FROM jadwal_asesor_potensi A
			INNER JOIN asesor B ON A.ASESOR_ID = B.ASESOR_ID
			LEFT JOIN jadwal_asesor_potensi_pegawai C ON A.JADWAL_ASESOR_POTENSI_ID = C.JADWAL_ASESOR_POTENSI_ID
			LEFT JOIN (SELECT A.JADWAL_ASESOR_POTENSI_ID, COUNT(1) JUMLAH_DATA FROM jadwal_asesor_potensi_pegawai A GROUP BY A.JADWAL_ASESOR_POTENSI_ID) C1 ON A.JADWAL_ASESOR_POTENSI_ID = C1.JADWAL_ASESOR_POTENSI_ID
			LEFT JOIN simpeg.pegawai P ON C.PEGAWAI_ID = P.PEGAWAI_ID
		) PD ON A.JADWAL_ACARA_ID = PD.JADWAL_ACARA_ID
		INNER JOIN
		(
			SELECT JADWAL_AWAL_TES_SIMULASI_ID, ROW_NUMBER() OVER(PARTITION BY JADWAL_AWAL_TES_SIMULASI_ID ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			ORDER BY JADWAL_AWAL_TES_SIMULASI_ID
		) JA ON JA.PEGAWAI_ID = PD.PEGAWAI_ID AND A.JADWAL_TES_ID = JA.JADWAL_AWAL_TES_SIMULASI_ID
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

    function selectByParamsMonitoringPapiHasilBak($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.NIP_BARU
			, COALESCE(HSL.NILAI_W,0) NILAI_W
			, CASE 
			WHEN COALESCE(HSL.NILAI_W,0) < 4 THEN 'Hanya butuh gambaran ttg kerangka tugas scr garis besar, berpatokan pd tujuan, dpt bekerja dlm suasana yg kurang berstruktur, berinsiatif, mandiri. Tdk patuh, cenderung mengabaikan/tdk paham pentingnya peraturan/prosedur, suka membuat peraturan sendiri yg bisa bertentangan dg yg telah ada.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 4 AND COALESCE(HSL.NILAI_W,0) < 6 THEN 'Perlu pengarahan awal dan tolok ukur keberhasilan.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 6 AND COALESCE(HSL.NILAI_W,0) < 8 THEN 'Membutuhkan uraian rinci mengenai tugas, dan batasan tanggung  jawab serta wewenang.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 8 AND COALESCE(HSL.NILAI_W,0) < 10 THEN 'Patuh pada kebijaksanaan, peraturan dan struktur organisasi. Ingin segala sesuatunya diuraikan secara rinci, kurang memiliki inisiatif, tdk fleksibel, terlalu tergantung pada organisasi, berharap disuapi.'
			END INFO_W
			, COALESCE(HSL.NILAI_F,0) NILAI_F
			, CASE 
			WHEN COALESCE(HSL.NILAI_F,0) < 4 THEN 'Otonom, dapat bekerja sendiri tanpa campur tangan orang lain, motivasi timbul krn pekerjaan itu sendiri - bukan krn pujian dr otoritas. Mempertanyakan otoritas, cenderung tidak puas thdp atasan, loya- litas lebih didasari kepentingan pribadi.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 4 AND COALESCE(HSL.NILAI_F,0) < 7 THEN 'Loyal pada Perusahaan.'
			WHEN COALESCE(HSL.NILAI_F,0) = 7 THEN 'Loyal pada pribadi atasan.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 8 AND COALESCE(HSL.NILAI_F,0) < 10 THEN 'Loyal, berusaha dekat dg pribadi atasan, ingin menyenangkan atasan, sadar akan harapan atasan akan dirinya.  Terlalu memper- hatikan cara menyenangkan atasan, tidak berani berpendirian lain, tidak mandiri.'
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
			,
			COALESCE(HSL.NILAI_N,0) + COALESCE(HSL.NILAI_A,0) + COALESCE(HSL.NILAI_P,0) + COALESCE(HSL.NILAI_X,0) + COALESCE(HSL.NILAI_B,0) + COALESCE(HSL.NILAI_O,0) + COALESCE(HSL.NILAI_Z,0) + COALESCE(HSL.NILAI_K,0) + COALESCE(HSL.NILAI_F,0) + COALESCE(HSL.NILAI_W,0) TOTAL_2
		FROM cat.ujian_pegawai_daftar B
		INNER JOIN simpeg.pegawai A ON B.PEGAWAI_ID = A.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
			AA.JADWAL_TES_ID, AA.PEGAWAI_ID, AA.UJIAN_ID, AA.UJIAN_TAHAP_ID
			, COALESCE(W.NILAI,0) NILAI_W, COALESCE(F.NILAI,0) NILAI_F, COALESCE(K.NILAI,0) NILAI_K, COALESCE(Z.NILAI,0) NILAI_Z, COALESCE(O.NILAI,0) NILAI_O, COALESCE(B.NILAI,0) NILAI_B, COALESCE(X.NILAI,0) NILAI_X, COALESCE(P.NILAI,0) NILAI_P, COALESCE(A.NILAI,0) NILAI_A, COALESCE(N.NILAI,0) NILAI_N
			, COALESCE(G.NILAI,0) NILAI_G, COALESCE(L.NILAI,0) NILAI_L, COALESCE(I.NILAI,0) NILAI_I, COALESCE(T.NILAI,0) NILAI_T, COALESCE(V.NILAI,0) NILAI_V, COALESCE(S.NILAI,0) NILAI_S, COALESCE(R.NILAI,0) NILAI_R, COALESCE(D.NILAI,0) NILAI_D, COALESCE(C.NILAI,0) NILAI_C, COALESCE(E.NILAI,0) NILAI_E
			FROM
			(
				SELECT 
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) AA
			LEFT JOIN
			(
				SELECT 
				A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(GRADE_A),0) NILAI
				FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
				WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
				AND B.SOAL_PAPI_ID IN (90, 80, 70, 60, 50, 40, 30, 20, 10)
				GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) W ON AA.PEGAWAI_ID = W.PEGAWAI_ID AND AA.UJIAN_ID = W.UJIAN_ID AND AA.UJIAN_TAHAP_ID = W.UJIAN_TAHAP_ID AND AA.JADWAL_TES_ID = W.JADWAL_TES_ID
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
					AND B.SOAL_PAPI_ID IN (79, 69, 59, 49, 39, 29, 19, 9)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (20)
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
					AND B.SOAL_PAPI_ID IN (68, 58, 48, 38, 28, 18, 8)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
					INNER JOIN cat.papi_pilihan B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE 1=1 AND A.TIPE_UJIAN_ID = 7
					AND B.SOAL_PAPI_ID IN (19, 30)
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
					AND B.SOAL_PAPI_ID IN (57, 47, 37, 27, 17, 7)
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
					AND B.SOAL_PAPI_ID IN (46, 36, 26, 16, 6)
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
					AND B.SOAL_PAPI_ID IN (35, 25, 15, 5)
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
					AND B.SOAL_PAPI_ID IN (24, 14, 4)
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
					AND B.SOAL_PAPI_ID IN (13, 3)
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
					AND B.SOAL_PAPI_ID IN (86, 76, 66, 56)
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
					AND B.SOAL_PAPI_ID IN (87, 77, 67)
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
					AND B.SOAL_PAPI_ID IN (88, 78)
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
					AND B.SOAL_PAPI_ID IN (11, 22, 33, 44, 55, 56, 66, 77, 88)
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
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.JADWAL_TES_ID = B.JADWAL_TES_ID
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

  } 
?>
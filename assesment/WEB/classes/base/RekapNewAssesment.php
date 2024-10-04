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

  class RekapNewAssesment extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RekapNewAssesment()
	{
	  $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
      $this->Entity(); 
    }

    function selectByParamsFormula($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='ORDER BY B.ATRIBUT_ID')
	{
		$str = "
		SELECT A.FORMULA_ATRIBUT_ID, B.ATRIBUT_ID, B.NAMA ATRIBUT_NAMA, A.NILAI_STANDAR
		FROM formula_atribut A
		INNER JOIN atribut B ON A.FORM_ATRIBUT_ID = B.ATRIBUT_ID AND A.FORM_PERMEN_ID = B.PERMEN_ID
		WHERE 1=1
		AND A.FORM_LEVEL > 0
		AND EXISTS
		(
			SELECT 1
			FROM formula_eselon XXX
			WHERE 1=1 AND XXX.FORMULA_ID = ".$reqJadwalTesId." AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID
		)
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsNewRekap($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='order by c.no_urut asc')
	{
		$str = "
		SELECT
		B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, B.JPM JPM_TOTAL, B.IKK IKK_TOTAL
		, NAMA_KUADRAN, c.no_urut
		, JT_TANGGAL_TES, JT_ACARA, JT_KETERANGAN
		FROM
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.SATKER, A.NAMA_JAB_STRUKTURAL JABATAN_NAMA,
			JT_TANGGAL_TES, JT_ACARA, JT_KETERANGAN,
			A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
			A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
			A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
			CASE 
			WHEN A.TIPE_FORMULA = '1'
				THEN 
					CASE WHEN A.JPM >= 80 THEN  'Memenuhi Syarat'
						WHEN (A.JPM >= 68 AND A.JPM < 80)  THEN  'Masih Memenuhi Syarat'
						WHEN A.JPM < 68  THEN  'Kurang Memenuhi Syarat'
						ELSE '' END

			WHEN A.TIPE_FORMULA = '2'
				THEN 
					CASE WHEN A.JPM >= 90 THEN  'Optimal'
						WHEN (A.JPM >= 78 AND A.JPM < 90)  THEN  'Cukup Optimal'
						WHEN A.JPM < 78  THEN  'Kurang Optimal'
						ELSE '' END
			ELSE '' END NAMA_KUADRAN
			FROM
			(
				SELECT 
				X1.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, D.NAMA SATKER, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
				X1.KOMPETEN_IKK, X1.PSIKOLOGI_IKK, X1.IKK, 0 NILAI,
				X1.KOMPETEN_JPM, X1.PSIKOLOGI_JPM, X1.JPM,
				CAST
				(
					CASE WHEN
					COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 
					THEN '1'
					WHEN 
					COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2
					THEN '2'
					ELSE '3' END
					||
					CASE 
					WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
					WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
					ELSE '3' END
				AS INTEGER) KUADRAN_PEGAWAI,
				CASE 
				WHEN COALESCE(X.NILAI_POTENSI,0) >= 0 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 THEN '1'
				WHEN COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2 THEN '2'
				ELSE '3' END KUADRAN_X
				, COALESCE(X.NILAI_POTENSI,0) NILAI_X
				, CASE 
				WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
				WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
				ELSE '3' END KUADRAN_Y
				, COALESCE(Y.NILAI_KOMPETENSI,0) NILAI_Y
				, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
				, X1.TIPE_FORMULA
				, X1.JT_TANGGAL_TES, X1.JT_ACARA, X1.JT_KETERANGAN
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT JT.TANGGAL_TES JT_TANGGAL_TES, JT.ACARA JT_ACARA, JT.KETERANGAN JT_KETERANGAN
					, A.*
					FROM jadwal_tes JT
					INNER JOIN
					(
						SELECT A.JADWAL_TES_ID, PEGAWAI_ID, TIPE_FORMULA,  
						SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, 
						SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, 
						SUM(KOMPETEN_JPM) KOMPETEN_JPM, 
						SUM(KOMPETEN_IKK) KOMPETEN_IKK,
						ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM,
						100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
						FROM
						(
							SELECT 
							A.JADWAL_TES_ID, PEGAWAI_ID, D.TIPE_FORMULA, 
							round((SUM(JPM))*100,2) JPM, 
							round((SUM(IKK))*100,2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN round((SUM(JPM))*100,2) ELSE 0 END PSIKOLOGI_JPM 
							, CASE WHEN ASPEK_ID = 1 THEN round((SUM(IKK))*100,2) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN round((SUM(JPM))*100,2) ELSE 0 END KOMPETEN_JPM 
							, CASE WHEN ASPEK_ID = 2 THEN round((SUM(IKK))*100,2) ELSE 0 END KOMPETEN_IKK
							,C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
							FROM penilaian A
							INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID
							WHERE 1=1
							AND D.FORMULA_ID = ".$reqJadwalTesId."
							AND A.ASPEK_ID IN (1,2)
							GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, ASPEK_ID, D.TIPE_FORMULA, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
						) A
						GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.TIPE_FORMULA, PROSEN_POTENSI, PROSEN_KOMPETENSI
					) A ON JT.JADWAL_TES_ID = A.JADWAL_TES_ID
				) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
					, JADWAL_TES_ID
					FROM penilaian 
					WHERE 1=1 AND ASPEK_ID IN (1)
					AND 
					TO_CHAR(TANGGAL_TES, 'YYYY') = 
					(
						SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
						FROM JADWAL_TES A
						WHERE 
						EXISTS
						(
							SELECT 1 
							FROM formula_eselon XXX 
							WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
						)
						GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
					)
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID AND X1.JADWAL_TES_ID = X.JADWAL_TES_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
					, JADWAL_TES_ID
					FROM penilaian 
					WHERE 1=1 AND ASPEK_ID IN (2)
					AND 
					TO_CHAR(TANGGAL_TES, 'YYYY') = 
					(
						SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
						FROM JADWAL_TES A
						WHERE 
						EXISTS
						(
							SELECT 1 
							FROM formula_eselon XXX 
							WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
						)
						GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
					)
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID AND X1.JADWAL_TES_ID = Y.JADWAL_TES_ID
				, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3) KD
				, 
				(
					SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
					FROM toleransi_talent_pool WHERE 1=1 
					AND 
					CAST(TAHUN AS TEXT) = 
					(
						SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
						FROM JADWAL_TES A
						WHERE 
						EXISTS
						(
							SELECT 1 
							FROM formula_eselon XXX 
							WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
						)
						GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
					)
				) KD_Y,
				(
					SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
					FROM toleransi_talent_pool WHERE 1=1 
					AND 
					CAST(TAHUN AS TEXT) = 
					(
						SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
						FROM JADWAL_TES A
						WHERE 
						EXISTS
						(
							SELECT 1 
							FROM formula_eselon XXX 
							WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
						)
						GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
					)
				) KD_X
				WHERE 1=1
			) A
		) B

		LEFT join 
		(
			select a.pegawai_id, a.no_urut from jadwal_awal_tes_simulasi_pegawai a
			left join jadwal_awal_tes b on a.jadwal_awal_tes_id =b.jadwal_awal_tes_id
			where formula_eselon_id = ".$reqJadwalTesId."
		) c on b. pegawai_id = c.pegawai_id
		WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsNewRekap($paramsArray=array(), $statement='', $reqJadwalTesId)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM 
		(
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, B.JPM JPM_TOTAL, B.IKK IKK_TOTAL
			, NAMA_KUADRAN
			, JT_TANGGAL_TES, JT_ACARA, JT_KETERANGAN
			FROM
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.SATKER, A.NAMA_JAB_STRUKTURAL JABATAN_NAMA,
				JT_TANGGAL_TES, JT_ACARA, JT_KETERANGAN,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
				CASE 
				WHEN A.TIPE_FORMULA = '1'
					THEN 
						CASE WHEN A.JPM >= 80 THEN  'Memenuhi Syarat'
							WHEN (A.JPM >= 68 AND A.JPM < 80)  THEN  'Masih Memenuhi Syarat'
							WHEN A.JPM < 68  THEN  'Kurang Memenuhi Syarat'
							ELSE '' END

				WHEN A.TIPE_FORMULA = '2'
					THEN 
						CASE WHEN A.JPM >= 90 THEN  'Optimal'
							WHEN (A.JPM >= 78 AND A.JPM < 90)  THEN  'Cukup Optimal'
							WHEN A.JPM < 78  THEN  'Kurang Optimal'
							ELSE '' END
				ELSE '' END NAMA_KUADRAN
				FROM
				(
					SELECT 
					X1.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, D.NAMA SATKER, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
					X1.KOMPETEN_IKK, X1.PSIKOLOGI_IKK, X1.IKK, 0 NILAI,
					X1.KOMPETEN_JPM, X1.PSIKOLOGI_JPM, X1.JPM,
					CAST
					(
						CASE WHEN
						COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 
						THEN '1'
						WHEN 
						COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2
						THEN '2'
						ELSE '3' END
						||
						CASE 
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
						ELSE '3' END
					AS INTEGER) KUADRAN_PEGAWAI,
					CASE 
					WHEN COALESCE(X.NILAI_POTENSI,0) >= 0 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X1 THEN '1'
					WHEN COALESCE(X.NILAI_POTENSI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI_POTENSI,0) <= KD_X.KUADRAN_X2 THEN '2'
					ELSE '3' END KUADRAN_X
					, COALESCE(X.NILAI_POTENSI,0) NILAI_X
					, CASE 
					WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
					WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
					ELSE '3' END KUADRAN_Y
					, COALESCE(Y.NILAI_KOMPETENSI,0) NILAI_Y
					, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
					, X1.TIPE_FORMULA
					, X1.JT_TANGGAL_TES, X1.JT_ACARA, X1.JT_KETERANGAN
					FROM simpeg.pegawai A
					INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
					INNER JOIN
					(
						SELECT JT.TANGGAL_TES JT_TANGGAL_TES, JT.ACARA JT_ACARA, JT.KETERANGAN JT_KETERANGAN
						, A.*
						FROM jadwal_tes JT
						INNER JOIN
						(
							SELECT A.JADWAL_TES_ID, PEGAWAI_ID, TIPE_FORMULA,  
							SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, 
							SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, 
							SUM(KOMPETEN_JPM) KOMPETEN_JPM, 
							SUM(KOMPETEN_IKK) KOMPETEN_IKK,
							ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM,
							100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
							FROM
							(
								SELECT 
								A.JADWAL_TES_ID, PEGAWAI_ID, D.TIPE_FORMULA, 
								round((SUM(JPM)/2)*100,2) JPM, 
								round((SUM(IKK)/2)*100,2) IKK
								, CASE WHEN ASPEK_ID = 1 THEN round((SUM(JPM)/2)*100,2) ELSE 0 END PSIKOLOGI_JPM 
								, CASE WHEN ASPEK_ID = 1 THEN round((SUM(IKK)/2)*100,2) ELSE 0 END PSIKOLOGI_IKK
								, CASE WHEN ASPEK_ID = 2 THEN round((SUM(JPM)/2)*100,2) ELSE 0 END KOMPETEN_JPM 
								, CASE WHEN ASPEK_ID = 2 THEN round((SUM(IKK)/2)*100,2) ELSE 0 END KOMPETEN_IKK
								,C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
								FROM penilaian A
								INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
								INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
								INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID
								WHERE 1=1
								AND D.FORMULA_ID = ".$reqJadwalTesId."
								AND A.ASPEK_ID IN (1,2)
								GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, ASPEK_ID, D.TIPE_FORMULA, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
							) A
							GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.TIPE_FORMULA, PROSEN_POTENSI, PROSEN_KOMPETENSI
						) A ON JT.JADWAL_TES_ID = A.JADWAL_TES_ID
					) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
					LEFT JOIN
					(
						SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
						, JADWAL_TES_ID
						FROM penilaian 
						WHERE 1=1 AND ASPEK_ID IN (1)
						AND 
						TO_CHAR(TANGGAL_TES, 'YYYY') = 
						(
							SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
							FROM JADWAL_TES A
							WHERE 
							EXISTS
							(
								SELECT 1 
								FROM formula_eselon XXX 
								WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
							)
							GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
						)
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID AND X1.JADWAL_TES_ID = X.JADWAL_TES_ID
					LEFT JOIN
					(
						SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
						, JADWAL_TES_ID
						FROM penilaian 
						WHERE 1=1 AND ASPEK_ID IN (2)
						AND 
						TO_CHAR(TANGGAL_TES, 'YYYY') = 
						(
							SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
							FROM JADWAL_TES A
							WHERE 
							EXISTS
							(
								SELECT 1 
								FROM formula_eselon XXX 
								WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
							)
							GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
						)
					) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID AND X1.JADWAL_TES_ID = Y.JADWAL_TES_ID
					, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3) KD
					, 
					(
						SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
						FROM toleransi_talent_pool WHERE 1=1 
						AND 
						CAST(TAHUN AS TEXT) = 
						(
							SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
							FROM JADWAL_TES A
							WHERE 
							EXISTS
							(
								SELECT 1 
								FROM formula_eselon XXX 
								WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
							)
							GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
						)
					) KD_Y,
					(
						SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
						FROM toleransi_talent_pool WHERE 1=1 
						AND 
						CAST(TAHUN AS TEXT) = 
						(
							SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY')
							FROM JADWAL_TES A
							WHERE 
							EXISTS
							(
								SELECT 1 
								FROM formula_eselon XXX 
								WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
							)
							GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
						)
					) KD_X
					WHERE 1=1
				) A
			) B
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ) A";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsPenilaianDetil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='')
	{
		$str = "
		SELECT A.PEGAWAI_ID, A.FORMULA_ATRIBUT_ID, A.NILAI
		FROM penilaian_detil A
		WHERE EXISTS
		(
			SELECT 1 
			FROM formula_eselon XXX 
			WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
		)
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPenilaianDetilCetakan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='')
	{
		$str = "
		SELECT A.PEGAWAI_ID, A.FORMULA_ATRIBUT_ID, A.NILAI
		FROM penilaian_detil A
		WHERE  1=1
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_ID ASC")
	{
		$str = "
		SELECT A.PENILAIAN_ID, A.PEGAWAI_ID, to_char(A.TANGGAL_TES, 'YYYY-MM-DD') TANGGAL_TES
		, A.JABATAN_TES_ID, A.JABATAN_TES_ID JABATAN_TES, A.ESELON
		, A.SATKER_TES_ID, D.FORMULA_ID,
		B.NAMA SATKER_TES,
		A.ASPEK_ID, CASE WHEN A.ASPEK_ID = '1' THEN 'Aspek Potensi' WHEN A.ASPEK_ID = '2' THEN 'Aspek Kompetensi' ELSE '' END ASPEK_NAMA
		, NAMA_ASESI, METODE, A.JADWAL_TES_ID, E.TIPE_FORMULA, E.FORMULA,
		CASE WHEN E.TIPE_FORMULA = '1' THEN 'Pengisian Jabatan' 
		     WHEN E.TIPE_FORMULA = '2' THEN 'Pemetaan Kompetensi' 
		     ELSE '-' END TIPE, C.TEMPAT
		FROM penilaian A
		LEFT JOIN ".$this->db.".satker B ON A.SATKER_TES_ID = B.SATKER_ID				
		INNER JOIN JADWAL_TES C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN FORMULA_ESELON D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN FORMULA_ASSESMENT E ON E.FORMULA_ID = D.FORMULA_ID
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

    function selectByParamsJadwalTes($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='ORDER BY B.ATRIBUT_ID')
	{
		$str = "
		SELECT A.FORMULA_ATRIBUT_ID, B.ATRIBUT_ID, B.NAMA ATRIBUT_NAMA, A.NILAI_STANDAR
		FROM formula_atribut A
		INNER JOIN atribut B ON A.FORM_ATRIBUT_ID = B.ATRIBUT_ID AND A.FORM_PERMEN_ID = B.PERMEN_ID
		WHERE 1=1
		AND A.FORM_LEVEL > 0
		AND EXISTS
		(
			SELECT 
			1
			FROM jadwal_tes XXX
			WHERE A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID 
			AND XXX.JADWAL_TES_ID = ".$reqJadwalTesId."
		)
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsNomorJadwalTes($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
		SELECT
		A.*
		, CASE WHEN A.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(A.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
		FROM
		(
		SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE, A.JADWAL_AWAL_TES_SIMULASI_ID
		FROM jadwal_awal_tes_simulasi_pegawai A
		INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
		) A
		WHERE 1=1
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
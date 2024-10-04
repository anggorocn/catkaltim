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

  class RekapAssesment extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RekapAssesment()
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
		SELECT A.FORMULA_ATRIBUT_ID, b.aspek_id, B.ATRIBUT_ID, B.NAMA ATRIBUT_NAMA, A.NILAI_STANDAR
		FROM formula_atribut A
		INNER JOIN atribut B ON A.FORM_ATRIBUT_ID = B.ATRIBUT_ID AND A.FORM_PERMEN_ID = B.PERMEN_ID
		WHERE 1=1
		-- AND A.FORM_LEVEL > 0
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

    function selectByParamsPenilaianDetil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='')
	{
		$str = "
		SELECT A.PEGAWAI_ID, A.FORMULA_ATRIBUT_ID, A.NILAI, a.gap
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

    function selectByParamsNewRekapBackup($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='')
	{
		$str = "
		SELECT
		B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, ROUND2DIGIT(B.JPM) JPM_TOTAL, ROUND2DIGIT(B.IKK) IKK_TOTAL
		, NAMA_KUADRAN
		FROM
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.SATKER, A.NAMA_JAB_STRUKTURAL JABATAN_NAMA,
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
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID, TIPE_FORMULA, SUM(JPM) JPM, SUM(IKK) IKK
					, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
					FROM
					(
						SELECT 
						PEGAWAI_ID, D.TIPE_FORMULA, (SUM(JPM) / 2) JPM, (SUM(IKK)/2) IKK
						, CASE WHEN ASPEK_ID = 1 THEN SUM(JPM) ELSE 0 END PSIKOLOGI_JPM
						, CASE WHEN ASPEK_ID = 1 THEN SUM(IKK) ELSE 0 END PSIKOLOGI_IKK
						, CASE WHEN ASPEK_ID = 2 THEN SUM(JPM) ELSE 0 END KOMPETEN_JPM
						, CASE WHEN ASPEK_ID = 2 THEN SUM(IKK) ELSE 0 END KOMPETEN_IKK
						FROM penilaian A
						INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
						INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
						INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID
						WHERE 1=1
						AND D.FORMULA_ID = ".$reqJadwalTesId."
						AND A.ASPEK_ID IN (1,2)
						GROUP BY A.PEGAWAI_ID, ASPEK_ID, D.TIPE_FORMULA
					) A
					GROUP BY A.PEGAWAI_ID, A.TIPE_FORMULA
				) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
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
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
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
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
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
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

     function selectByParamsNewRekap($paramsArray=array(),$limit=2,$from=-1, $statement='', $reqJadwalTesId, $orderby='order by c.no_urut asc')
	{
		$str = "
		SELECT
		B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, B.JPM JPM_TOTAL, B.IKK IKK_TOTAL
		, NAMA_KUADRAN, c.no_urut
		FROM
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.SATKER, A.NAMA_JAB_STRUKTURAL JABATAN_NAMA,
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
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID, TIPE_FORMULA,  
					SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, 
					SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, 
					SUM(KOMPETEN_JPM) KOMPETEN_JPM, 
					SUM(KOMPETEN_IKK) KOMPETEN_IKK,
					ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM,
					100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
						FROM
						(
							SELECT 
							PEGAWAI_ID, D.TIPE_FORMULA, 
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
							GROUP BY A.PEGAWAI_ID, ASPEK_ID, D.TIPE_FORMULA, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
						) A
						GROUP BY A.PEGAWAI_ID, A.TIPE_FORMULA, PROSEN_POTENSI, PROSEN_KOMPETENSI
				) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
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
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
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
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
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
		
		$str .= $statement." group by B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, B.JPM , B.IKK , b.nama_kuadran,  c.no_urut ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRekap($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId, $orderby='')
	{
		$str = "
		SELECT
		B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, ROUND2DIGIT(B.JPM) JPM_TOTAL, ROUND2DIGIT(B.IKK) IKK_TOTAL
		, A.ID_KUADRAN, A.NAMA_KUADRAN, A.KODE_KUADRAN
		FROM
		(
			SELECT * FROM p_kuadran_info()
		) A
		LEFT JOIN
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.SATKER, A.NAMA_JAB_STRUKTURAL JABATAN_NAMA,
			A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
			A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
			A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
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
				FROM simpeg.pegawai A
				INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID, SUM(JPM) JPM, SUM(IKK) IKK
					, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
					FROM
					(
						SELECT 
						PEGAWAI_ID, (SUM(JPM) / 2) JPM, (SUM(IKK)/2) IKK
						, CASE WHEN ASPEK_ID = 1 THEN SUM(JPM) ELSE 0 END PSIKOLOGI_JPM
						, CASE WHEN ASPEK_ID = 1 THEN SUM(IKK) ELSE 0 END PSIKOLOGI_IKK
						, CASE WHEN ASPEK_ID = 2 THEN SUM(JPM) ELSE 0 END KOMPETEN_JPM
						, CASE WHEN ASPEK_ID = 2 THEN SUM(IKK) ELSE 0 END KOMPETEN_IKK
						FROM penilaian A
						WHERE 1=1
						AND EXISTS
						(
							SELECT 1
							FROM
							(
								SELECT A.JADWAL_TES_ID
								FROM JADWAL_TES A
								WHERE 
								EXISTS
								(
									SELECT 1 
									FROM formula_eselon XXX 
									WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
								)
							) XXX WHERE A.JADWAL_TES_ID = XXX.JADWAL_TES_ID
						)
						AND A.ASPEK_ID IN (1,2)
						GROUP BY PEGAWAI_ID, ASPEK_ID
					) A
					GROUP BY A.PEGAWAI_ID
				) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
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
				) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
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
				) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
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
		) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
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

    function getCountByParamsRekap($paramsArray=array(), $statement='', $reqJadwalTesId)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM 
		(
			SELECT B.PEGAWAI_ID
			FROM
			(
				SELECT * FROM p_kuadran_info()
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
					SELECT 
					X1.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
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
					FROM simpeg.pegawai A
					INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
					INNER JOIN
					(
						SELECT PEGAWAI_ID, SUM(JPM) JPM, SUM(IKK) IKK
						, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
						FROM
						(
							SELECT 
							PEGAWAI_ID, (SUM(JPM) / 2) JPM, (SUM(IKK)/2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN SUM(JPM) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN SUM(IKK) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN SUM(JPM) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN SUM(IKK) ELSE 0 END KOMPETEN_IKK
							FROM penilaian A
							WHERE 1=1
							AND EXISTS
							(
								SELECT 1
								FROM
								(
									SELECT A.JADWAL_TES_ID
									FROM JADWAL_TES A
									WHERE 
									EXISTS
									(
										SELECT 1 
										FROM formula_eselon XXX 
										WHERE 1=1 AND A.FORMULA_ESELON_ID = XXX.FORMULA_ESELON_ID AND XXX.FORMULA_ID = ".$reqJadwalTesId."
									)
								) XXX WHERE A.JADWAL_TES_ID = XXX.JADWAL_TES_ID
							)
							AND A.ASPEK_ID IN (1,2)
							GROUP BY PEGAWAI_ID, ASPEK_ID
						) A
						GROUP BY A.PEGAWAI_ID
					) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
					LEFT JOIN
					(
						SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
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
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
					LEFT JOIN
					(
						SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
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
					) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
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
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ) A";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }


    function selectByParamsPenilaianNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId)
		{

			$str = "
			select a.pegawai_id, a.aspek_id, c.atribut_id, coalesce(c.nilai,0) nilai, coalesce(c.gap,0) gap 
			from penilaian a 
			left join penilaian_detil c on a.penilaian_id =c.penilaian_id 
			left join formula_eselon d on c.formula_eselon_id =d.formula_eselon_id
			where formula_id=".$reqJadwalTesId."
		 	"; 
			
			while(list($key,$val) = each($paramsArray))
			{
				$str .= " AND $key = '$val' ";
			}
			
			$str .= $statement." order by a.pegawai_id";
			// echo $str; exit;		
			$this->query = $str;		
			return $this->selectLimit($str,$limit,$from); 
	  }

	  function selectByParamsPenilaianjpm($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqJadwalTesId)
		{

			$str = "
			select a.pegawai_id, a.aspek_id, a.jpm *100 jpm, CAST
						(
							CASE
							WHEN COALESCE(a.jpm *100,0) <= ttp.skp_x0 THEN '1' 
							WHEN COALESCE(a.jpm *100,0) > ttp.skp_x0 AND COALESCE(a.jpm *100,0) <= ttp.skp_x1 THEN '2' 
							ELSE '3' END
						AS INTEGER) KUADRAN_PEGAWAI
			from penilaian a 
			left join jadwal_tes c on a.jadwal_tes_id =c.jadwal_tes_id 
			left join formula_eselon d on c.formula_eselon_id =d.formula_eselon_id
			left join toleransi_talent_pool ttp on d.tahun = ttp.tahun
			where 1=1  and d.formula_id=".$reqJadwalTesId."
		 	"; 
			
			while(list($key,$val) = each($paramsArray))
			{
				$str .= " AND $key = '$val' ";
			}
			
			$str .= $statement." order by a.pegawai_id";
			// echo $str; exit;		
			$this->query = $str;		
			return $this->selectLimit($str,$limit,$from); 
	  }

	  function selectByParamsPenilaianHasil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_REKOMENDASI_ID ASC")
	{
		$str = "
		SELECT A.PENILAIAN_REKOMENDASI_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.KETERANGAN,A.TIPE,A.NO_URUT
		FROM penilaian_rekomendasi A
		left join JADWAL_TES b on a.JADWAL_TES_ID =b.JADWAL_TES_ID
		left join formula_eselon c on b.formula_eselon_id = c.formula_eselon_id
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


  } 
?>
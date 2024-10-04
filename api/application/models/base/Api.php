<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Api extends Entity{ 

		var $query;
    /**
    * Class constructor.
    **/
    function Api()
		{
      $this->Entity(); 
    }

    function selectByParamsApi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY JPM_TOTAL DESC', $reqTahun, $searcJson= "",$orderby="")
		{
			$str = "
			
				SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL, c.nip_baru, 
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, b.jadwal_tes_id,
			CASE WHEN B.IKK < 0 THEN 0 ELSE B.IKK END IKK, d.tanggal_tes,
			B.NILAI
			, ROUND(COALESCE(B.KOMPETEN_JPM,0),2) KOMPETEN_JPM
			, ROUND(COALESCE(B.PSIKOLOGI_JPM,0),2) PSIKOLOGI_JPM 
			, ROUND(COALESCE(B.JPM,0),2) JPM
			, ROUND(COALESCE(B.JPM,0),2) JPM_TOTAL
			, CASE WHEN B.IKK < 0 THEN 0 ELSE B.IKK END  IKK_TOTAL
			, A.*
			, B.FORMULA
			, B.FORMULA_ID
			FROM
			(
				SELECT A.* FROM
				( 
				SELECT 11 ID_KUADRAN, 'Tingkatkan Kompetensi' NAMA_KUADRAN, 'I' KODE_KUADRAN 
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'II' KODE_KUADRAN 
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'III' KODE_KUADRAN 
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'IV' KODE_KUADRAN 
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'V' KODE_KUADRAN 
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Pertimbangkan (Mutasi)' NAMA_KUADRAN, 'VI' KODE_KUADRAN 
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'VII' KODE_KUADRAN 
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'VIII' KODE_KUADRAN 
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Siap Untuk Peran Di Masa Depan' NAMA_KUADRAN, 'IX' KODE_KUADRAN 
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM,
				A.JPM, A.IKK,a.jadwal_tes_id,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID, A.FORMULA, A.FORMULA_ID
				FROM
				(
							SELECT 
						X1.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
						X1.KOMPETEN_IKK, X1.PSIKOLOGI_IKK, X1.IKK, 0 NILAI,
						X1.KOMPETEN_JPM, X1.PSIKOLOGI_JPM, X1.JPM,X1.FORMULA,X1.FORMULA_ID,
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
						, x1.jadwal_tes_id
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
							SELECT PEGAWAI_ID, jadwal_tes_id, 
							ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM
							, 100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							, A.FORMULA
							, A.FORMULA_ID 
							FROM
							(
							SELECT PEGAWAI_ID, b.jadwal_tes_id,
							round((SUM(JPM))*100,2) JPM, 
							round((SUM(IKK))*100,2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN round((SUM(JPM))*100,2) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN round((SUM(IKK))*100,2) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN round((SUM(JPM))*100,2) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN round((SUM(IKK))*100,2) ELSE 0 END KOMPETEN_IKK
							, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
							, D.FORMULA
							, D.FORMULA_ID
							FROM penilaian A
							INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
							WHERE 1=1 AND ASPEK_ID in (1,2) 
							GROUP BY PEGAWAI_ID, ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA
							, D.FORMULA_ID,b.jadwal_tes_id
							) A
							GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
							, A.FORMULA_ID, a.jadwal_tes_id
						) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
							FROM penilaian 
							WHERE 1=1 AND ASPEK_ID in (1)
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
						  SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
						  FROM penilaian 
							WHERE 1=1 AND ASPEK_ID in (2)
						) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
						, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3) KD
						, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1
						) KD_X
						WHERE 1=1
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			left join simpeg.pegawai c on c.pegawai_id=b.pegawai_id
			left join jadwal_tes d on b.jadwal_tes_id=d.jadwal_tes_id
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		 		"; 
			
			while(list($key,$val) = each($paramsArray))
			{
				$str .= " AND $key = '$val' ";
			}
			
			$str .= $searcJson;
			$str .= " group by B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL, c.nip_baru,
				B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK ,B.NILAI ,B.KOMPETEN_JPM, b.psikologi_jpm,
				b.jpm,  A.*, a.id_kuadran, a.nama_kuadran, a.kode_kuadran, b.formula, b.formula_id, b.jadwal_tes_id,d.tanggal_tes";
			$str .= $orderby;
			$this->query = $str;
			// echo $str;exit();
			return $this->selectLimit($str,$limit,$from);
		}

		function selectByParamsApiPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='')
		{
			$str = "
				select * from penilaian_rekomendasi
				where 1=1
		 		"; 
			
			while(list($key,$val) = each($paramsArray))
			{
				$str .= " AND $key = '$val' ";
			}
			
			$str .= $statement;
			$str .= $searcJson;
			$str .= $orderby;
			$this->query = $str;
			// echo $str;exit();
			return $this->selectLimit($str,$limit,$from);
		}

  } 
?>
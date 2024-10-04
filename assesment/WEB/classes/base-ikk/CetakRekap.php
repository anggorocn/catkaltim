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

  class CetakRekap extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function CetakRekap()
	{
      $this->Entity(); 
    }
	
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder=" ORDER BY JPM_TOTAL DESC, ID_KUADRAN DESC")
	{
		$str = "
			SELECT A.ID_KUADRAN,
			B.JADWAL_TES_ID,
			B.FORMULA_ID,
			B.FORMULA,
			B.TIPE_FORMULA,
			B.PEGAWAI_ID, b.nip_baru,  B.NAMA, B.NAMA_JAB_STRUKTURAL, B.NILAI_SKP
			,ROUND(COALESCE(B.KOMPETEN_JPM,0),2) KOMPETEN_JPM
			,ROUND(COALESCE(B.PSIKOLOGI_JPM,0),2) PSIKOLOGI_JPM
			,ROUND(COALESCE(B.JPM,0),2) JPM_TOTAL
			,CASE 
			WHEN B.TIPE_FORMULA = '1'
				THEN 
					CASE WHEN B.JPM >= 80 THEN  'Memenuhi Syarat'
						WHEN (B.JPM >= 68 AND B.JPM < 80)  THEN  'Masih Memenuhi Syarat'
						WHEN B.JPM < 68  THEN  'Kurang Memenuhi Syarat'
						ELSE '' END

			WHEN B.TIPE_FORMULA = '2'
				THEN 
					CASE WHEN B.JPM >= 90 THEN  'Optimal'
						WHEN (B.JPM >= 78 AND B.JPM < 90)  THEN  'Cukup Optimal'
						WHEN B.JPM < 78  THEN  'Kurang Optimal'
						ELSE '' END
			ELSE '' END KATEGORI
			, A.KODE_KUADRAN
			, A.NAMA_KUADRAN
			, B.KOMPETEN_IKK, B.PSIKOLOGI_IKK 
			, B.KUAT
			, B.lemah 
			, B.rekomendasi
			, B.saran_pengembang
			, B.saran_penempatan
			, B.profil_kepribadian
			, B.profil_kompetensi
			, B.area_pengembangan			
			FROM
			(
				SELECT A.* FROM
				( 
					SELECT * FROM P_KUADRAN_INFOJPM()				
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NIP_BARU, A.NAMA, A.NAMA_JAB_STRUKTURAL, A.NILAI_SKP,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM,
				A.JPM, A.IKK,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID, A.FORMULA, A.FORMULA_ID, A.TIPE_FORMULA
				,A.JADWAL_TES_ID
				, A.KUAT
				, A.lemah 
				, A.rekomendasi
				, A.saran_pengembang
				, A.saran_penempatan
				, A.profil_kepribadian
				, A.profil_kompetensi
				, A.area_pengembangan
				FROM
				(
						SELECT 
						X1.PEGAWAI_ID, A.NIP_BARU, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, SKP.NILAI_SKP,
						X1.KOMPETEN_IKK, X1.PSIKOLOGI_IKK, X1.IKK, 0 NILAI,
						X1.KOMPETEN_JPM, X1.PSIKOLOGI_JPM, X1.JPM,X1.FORMULA,X1.FORMULA_ID, X1.TIPE_FORMULA, X1.JADWAL_TES_ID,
						CAST
						(
							CASE WHEN
							COALESCE(X1.JPM,0) <= KD_X.KUADRAN_X1 
							THEN '1'
							WHEN 
							COALESCE(X1.JPM,0) > KD_X.KUADRAN_X1 AND COALESCE(X1.JPM,0) <= KD_X.KUADRAN_X2
							THEN '2'
							ELSE '3' END
							||
							CASE 
							WHEN (COALESCE(SKP.NILAI_SKP,0) >= 0) AND COALESCE(SKP.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
							WHEN (COALESCE(SKP.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(SKP.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
							ELSE '3' END
						AS INTEGER) KUADRAN_PEGAWAI,
						CASE 
						WHEN COALESCE(X1.JPM,0) >= 0 AND COALESCE(X1.JPM,0) <= KD_X.KUADRAN_X1 THEN '1'
						WHEN COALESCE(X1.JPM,0) > KD_X.KUADRAN_X1 AND COALESCE(X1.JPM,0) <= KD_X.KUADRAN_X2 THEN '2'
						ELSE '3' END KUADRAN_X
						, COALESCE(X1.JPM,0) NILAI_X
						, CASE 
						WHEN (COALESCE(SKP.NILAI_SKP,0) >= 0) AND COALESCE(SKP.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
						WHEN (COALESCE(SKP.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(SKP.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
						ELSE '3' END KUADRAN_Y
						, COALESCE(SKP.NILAI_SKP,0) NILAI_Y
						, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3 
						, x.kuat 
						, x.lemah 
						, x.rekomendasi
						, x.saran_pengembang
						, x.saran_penempatan
						, x.profil_kepribadian
						, x.profil_kompetensi
						, x.area_pengembangan
						FROM simpeg.pegawai A
						LEFT JOIN simpeg.riwayat_skp skp ON A.PEGAWAI_ID = skp.PEGAWAI_ID
						INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, 
							ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM
							, 100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							, A.FORMULA
							, A.FORMULA_ID
							, A.TIPE_FORMULA
							, A.JADWAL_TES_ID
							FROM
							(
							SELECT PEGAWAI_ID, 
							round((SUM(JPM))*100,2) JPM, 
							round((SUM(IKK))*100,2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN round((SUM(JPM))*100,2) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN round((SUM(IKK))*100,2) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN round((SUM(JPM))*100,2) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN round((SUM(IKK))*100,2) ELSE 0 END KOMPETEN_IKK
							, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
							, D.FORMULA
							, D.FORMULA_ID
							, D.TIPE_FORMULA
							, B.JADWAL_TES_ID							
							FROM penilaian A
							INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
							WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '2020' AND ASPEK_ID in (1,2) 
							GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA, D.FORMULA_ID, B.JADWAL_TES_ID, D.TIPE_FORMULA
							) A
							GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
							, A.FORMULA_ID, A.TIPE_FORMULA, A.JADWAL_TES_ID
						) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT JADWAL_TES_ID, PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_kekuatan') kuat
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_kelemahan') lemah
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_rekomendasi') rekomendasi
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_saran_pengembangan') saran_pengembang
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_saran_penempatan') saran_penempatan
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_kepribadian') profil_kepribadian
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'profil_kompetensi') profil_kompetensi
							, gettext_kesimpulan(JADWAL_TES_ID, PEGAWAI_ID, 'area_pengembangan') area_pengembangan
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '2020' AND ASPEK_ID in (1)
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
						  SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
						  FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '2020' AND ASPEK_ID in (2)
						) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
						, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3) KD
						, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '2020'
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '2020'
						) KD_X
						WHERE 1=1
						
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		 
				"; 
		//INNER JOIN dbsimpeg.JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM penilaian A
		WHERE 1=1 "; 
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

  } 
?>
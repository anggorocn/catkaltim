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
	   //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
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

      function selectByParamsMonitoringTableTalentPoolJPMMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $statement2='', $orderby='', $reqTahun='2020', $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM,
			A.*
			FROM
			(
				SELECT * FROM
				(
					SELECT * FROM P_KUADRAN_INFOJPM()
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
				A.NILAI_Y KOMPETEN_IKK, A.NILAI_X PSIKOLOGI_IKK, '' IKK, '' NILAI,
				'' KOMPETEN_JPM, '' PSIKOLOGI_JPM, '' JPM,
				'' JPM_TOTAL, '' IKK_TOTAL,
				A.*
				FROM
				(
					SELECT A.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
					(CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) NILAI_X,
					(CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) NILAI_Y,
					CAST
							(
								CASE WHEN
								COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN 
								COALESCE(X.NILAI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X2
								THEN '2'
								ELSE '3' END
								||
								CASE 
								WHEN (COALESCE(Y.NILAI,0) >= 0) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
							AS INTEGER) KUADRAN_PEGAWAI
					FROM ".$this->db.".pegawai A
					INNER JOIN
					(
						
						SELECT PEGAWAI_ID, 
							ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  NILAI
							, 100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							, A.FORMULA
							, A.FORMULA_ID 
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
							FROM penilaian A
							INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
							WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
							".$statement."
							GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA
							, D.FORMULA_ID
							) A
							GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
							, A.FORMULA_ID 
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
					INNER JOIN
					(
						SELECT PEGAWAI_ID, NILAI_SKP NILAI
						FROM
						(
							SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
							FROM
							(
								SELECT 
								ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
								, PEGAWAI_ID, TAHUN, NILAI_SKP
								FROM
								(
									SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.pegawai A
									UNION ALL
									SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.riwayat_skp A 
									WHERE SKP_TAHUN = '".$reqTahun."'
								) A
							) A
							WHERE NOMOR = 1
						) A
					) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
					, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_X
					WHERE 1=1					
				) A
			) B ON CAST(B.KUADRAN_PEGAWAI_ID AS INTEGER) = A.ID_KUADRAN 
			WHERE 1=1
			AND B.PEGAWAI_ID IS NOT NULL 
			".$statement2."
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson." ORDER BY B.PSIKOLOGI_IKK DESC, B.KOMPETEN_IKK DESC";
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringTableTalentPoolMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $statement2='', $orderby='ORDER BY JPM_TOTAL DESC', $reqTahun, $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, 
			CASE WHEN B.IKK < 0 THEN 0 ELSE B.IKK END IKK, 
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
				A.JPM, A.IKK,
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
						, COALESCE(X.NILAI_POTENSI,0) NILAI_X
						, CASE 
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) >= 0) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
						WHEN (COALESCE(Y.NILAI_KOMPETENSI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_KOMPETENSI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
						ELSE '3' END KUADRAN_Y
						, COALESCE(Y.NILAI_KOMPETENSI,0) NILAI_Y
						, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3 
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, 
							ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM
							, 100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							, A.FORMULA
							, A.FORMULA_ID 
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
							FROM penilaian A
							INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
							WHERE 1=1 
							AND ASPEK_ID in (1,2)
							".$statement."							 
							GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA
							, D.FORMULA_ID
							) A
							GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
							, A.FORMULA_ID
						) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN, JPM, IKK
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1)
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
						  SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
						  FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2)
						) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
						, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3) KD
						, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_X
						WHERE 1=1					
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
			".$statement2."
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	
	function selectByParamsPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, A.PEGAWAI_ID, to_char(A.TANGGAL_TES, 'YYYY-MM-DD') TANGGAL_TES, A.JABATAN_TES_ID, A.JABATAN_TES_ID JABATAN_TES, A.ESELON,
				A.SATKER_TES_ID,
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
				INNER JOIN FORMULA_ASSESMENT E ON D.FORMULA_ID = E.FORMULA_ID
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

    function selectByParamsSumPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
				select a.pegawai_id, sum(b.nilai) INDIVIDU_RATING, sum(b.gap), sum((b.nilai - b.gap)) STANDAR_RATING
				from penilaian a
				inner join penilaian_detil b on a.penilaian_id = b.penilaian_id
				where 1=1 ".$statement."
				group by a.pegawai_id
				"; 
		//INNER JOIN ".$this->db.".JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		 
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
			CASE WHEN A.ATRIBUT_ID = '0407' THEN '0401'
			WHEN A.ATRIBUT_ID = '0401' THEN '0402'
			WHEN A.ATRIBUT_ID = '0410' THEN '0403'
			WHEN A.ATRIBUT_ID = '0406' THEN '0404'
			WHEN A.ATRIBUT_ID = '0402' THEN '0405'
			WHEN A.ATRIBUT_ID = '0606' THEN '0601'
			WHEN A.ATRIBUT_ID = '0607' THEN '0602'
			WHEN A.ATRIBUT_ID = '0608' THEN '0603'
			WHEN A.ATRIBUT_ID = '0610' THEN '0604'
			WHEN A.ATRIBUT_ID = '0609' THEN '0605'
			ELSE A.ATRIBUT_ID
			END ATRIBUT_KONDISI_ID
		  	, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA, B.NILAI_STANDAR, B.NILAI
			, CASE A.ASPEK_ID WHEN 2 THEN 'Aspek Kompetensi' ELSE 'Aspek Psikologi' END ASPEK_NAMA
			, CASE WHEN B.NILAI = 1 THEN 'Belum Kompeten' WHEN B.NILAI = 2 THEN 'Hampir Kompeten'
			  WHEN B.NILAI = 3 THEN 'Cukup Kompeten' WHEN B.NILAI = 4 THEN 'Kompeten'
			  WHEN B.NILAI = 5 THEN 'Sangat Kompeten' END KESIMPULAN
			 ,B.LEVEL, B.CATATAN, ur.urut
		FROM atribut A
		INNER JOIN
		(
			SELECT a.FORMULA_ESELON_ID,A.ATRIBUT_ID, A.NILAI, A.PERMEN_ID, A.NILAI_STANDAR, A.LEVEL, A.CATATAN 
			FROM
			(
				SELECT a.FORMULA_ESELON_ID, A.ATRIBUT_ID, A.NILAI, A.PERMEN_ID, A.NILAI_STANDAR, A.LEVEL, A.CATATAN
				FROM
				(
					SELECT fe.FORMULA_ESELON_ID, PD.ATRIBUT_ID, PD.NILAI, PD.PERMEN_ID, B1.NILAI_STANDAR, BL.LEVEL, PD.CATATAN
					FROM penilaian_detil PD
					left JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
					left JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = PD.FORMULA_ATRIBUT_ID
					left JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = B1.FORMULA_ESELON_ID
					left JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
					left JOIN level_atribut BL ON B1.LEVEL_ID = BL.LEVEL_ID
					WHERE 1=1 ".$statementdetil."
				) A
				UNION ALL
				SELECT a.FORMULA_ESELON_ID, A.ATRIBUT_ID, A.NILAI, A.PERMEN_ID, A.NILAI_STANDAR, NULL AS LEVEL, null AS CATATAN
				FROM
				(
					SELECT a.FORMULA_ESELON_ID, A.ATRIBUT_ID, 0 NILAI, A.PERMEN_ID, 0 NILAI_STANDAR
					FROM
					(
						SELECT SUBSTRING(PD.ATRIBUT_ID,1,2) ATRIBUT_ID, PD.PERMEN_ID,fe.FORMULA_ESELON_ID
						FROM penilaian_detil PD
						INNER JOIN penilaian P ON PD.PENILAIAN_ID = P.PENILAIAN_ID
						INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = PD.FORMULA_ATRIBUT_ID
						INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = B1.FORMULA_ESELON_ID
						INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
						WHERE 1=1 ".$statementdetil."
						GROUP BY SUBSTRING(PD.ATRIBUT_ID,1,2), PD.PERMEN_ID,fe.FORMULA_ESELON_ID
					) A
				) A
			) A
		) B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.PERMEN_ID = B.PERMEN_ID
		LEFT JOIN 
		( 
			SELECT * FROM formula_assesment_atribut_urutan
		) UR ON a.ATRIBUT_ID = UR.ATRIBUT_ID AND UR.PERMEN_ID = b.PERMEN_ID and  UR.FORMULA_ESELON_ID = b.FORMULA_ESELON_ID
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

    function selectByParamsPenilaianJpmAkhir($paramsArray=array(),$limit=-1,$from=-1, $statementdetil='', $statement='', $sOrder="")
	{
		$str = "
		SELECT PEGAWAI_ID, 
		ROUND(COALESCE(PSIKOLOGI_JPM,0),2) PSIKOLOGI_JPM, 
		ROUND(COALESCE(KOMPETEN_JPM,0),2) KOMPETEN_JPM, 
		(CASE WHEN COALESCE(PSIKOLOGI_IKK,0) <= 0 THEN 0 ELSE ROUND(COALESCE(PSIKOLOGI_IKK,0),2) END) PSIKOLOGI_IKK, 
		(CASE WHEN COALESCE(KOMPETEN_IKK,0) <= 0 THEN 0 ELSE ROUND(COALESCE(KOMPETEN_IKK,0),2) END) KOMPETEN_IKK, 
		ROUND(COALESCE(JPM,0),2) JPM, 
		(CASE WHEN COALESCE(IKK,0) <= 0 THEN 0 ELSE ROUND(COALESCE(IKK,0),2) END) IKK
		FROM 
		(
			SELECT PEGAWAI_ID,   
			SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, 
			SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, 
			SUM(KOMPETEN_JPM) KOMPETEN_JPM, 
			SUM(KOMPETEN_IKK) KOMPETEN_IKK,
			ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  JPM,
			100 - ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2) IKK
			FROM
			(
				SELECT 
				PEGAWAI_ID,  
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
				WHERE 1=1
				".$statementdetil."
				AND A.ASPEK_ID IN (1,2)
				GROUP BY A.PEGAWAI_ID, ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI
			) A
			GROUP BY A.PEGAWAI_ID,   PROSEN_POTENSI, PROSEN_KOMPETENSI
		) A
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
		CASE WHEN A.ATRIBUT_ID = '0407' THEN '0401'
		WHEN A.ATRIBUT_ID = '0401' THEN '0402'
		WHEN A.ATRIBUT_ID = '0410' THEN '0403'
		WHEN A.ATRIBUT_ID = '0406' THEN '0404'
		WHEN A.ATRIBUT_ID = '0402' THEN '0405'
		WHEN A.ATRIBUT_ID = '0606' THEN '0601'
		WHEN A.ATRIBUT_ID = '0607' THEN '0602'
		WHEN A.ATRIBUT_ID = '0608' THEN '0603'
		WHEN A.ATRIBUT_ID = '0610' THEN '0604'
		WHEN A.ATRIBUT_ID = '0609' THEN '0605'
		ELSE A.ATRIBUT_ID
		END ATRIBUT_KONDISI_ID
		, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID, A.NAMA ATRIBUT_NAMA, B.LEVEL, B.RATING
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

    function selectByParamsJadwalFormula($statement='', $sOrder="")
	{
		$str = "
			SELECT a.jadwal_tes_id, B.FORMULA_ID ,c.nama TTD_ASESOR, A.TTD_PIMPINAN, c.no_sk NIP_ASESOR, A.NIP_PIMPINAN, 
			to_char(A.TTD_TANGGAL, 'YYYY-MM-DD') TTD_TANGGAL,
			to_char(A.TANGGAL_TES, 'YYYY-MM-DD') TANGGAL_TES, A.KETERANGAN
			FROM jadwal_tes A
			INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
			left JOIN asesor c ON cast(A.TTD_ASESOR as int) = cast(c.asesor_id as int)
			left join jadwal_tes_simulasi_pegawai jpd on a.jadwal_tes_id=jpd.jadwal_tes_id
			WHERE 1=1
		"; 

		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

     function selectByParamsSaudara($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.TANGGAL_LAHIR DESC")
	{
		$str = "
		SELECT 
		A.*
		FROM simpeg.saudara A
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

    function selectByParamsDataPegawai($statement='', $sOrder="")
	{
		$str = "
			SELECT a.nip_baru
			FROM simpeg.pegawai A
			WHERE 1=1
		"; 

		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsKeseluruhanAsesor($statement='', $sOrder="")
		{
		$str = "
			select ases.nama, ases.no_sk from jadwal_asesor a
			inner join jadwal_acara ja on ja.jadwal_acara_id=a.jadwal_acara_id
			inner join penggalian p on p.penggalian_id=ja.penggalian_id
			inner join asesor ases on a.asesor_id=ases.asesor_id
			inner join JADWAL_TES jt  on a.JADWAL_TES_id=jt.JADWAL_TES_id
			where 1=1
		"; 

		$str .= $statement." group by ases.nama, ases.no_sk ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsKeseluruhanJadwalTes($statement='', $sOrder=" order by tanggal_tes asc")
		{
		$str = "
			select * from jadwal_tes
			where 1=1
		"; 

		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

  } 
?>
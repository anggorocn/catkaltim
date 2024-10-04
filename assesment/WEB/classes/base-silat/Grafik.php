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

  class Grafik extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Grafik()
	{
   //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity(); 
    }
	
	function selectByParamsMonitoringTableTalentPoolSkpMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY JPM_TOTAL DESC', $reqTahun, $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			(B.KOMPETEN_JPM*100) KOMPETEN_JPM, (B.PSIKOLOGI_JPM*100) PSIKOLOGI_JPM, B.JPM, 
			COALESCE(B.JPM,0) * 100 JPM_TOTAL,
			CASE WHEN COALESCE(B.IKK,0) * 100 < 0 THEN 0 ELSE COALESCE(B.IKK,0) * 100 END IKK_TOTAL,
			A.*
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 11 ID_KUADRAN, 1 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial rendah' NAMA_KUADRAN, 'I' KODE_KUADRAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 2 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial rendah' NAMA_KUADRAN, 'II' KODE_KUADRAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 3 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial menengah' NAMA_KUADRAN, 'III' KODE_KUADRAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 4 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial rendah' NAMA_KUADRAN, 'IV' KODE_KUADRAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 5 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial menengah' NAMA_KUADRAN, 'V' KODE_KUADRAN
					UNION ALL					
					SELECT 31 ID_KUADRAN, 6 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VI' KODE_KUADRAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 7 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial menengah' NAMA_KUADRAN, 'VII' KODE_KUADRAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 8 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VIII' KODE_KUADRAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 9 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'IX' KODE_KUADRAN
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				, KD_ESELON, A.PEGAWAI_ID
				FROM
				(
							
							SELECT 
							X.PEGAWAI_ID, A.NAMA, J.NM_JABATAN NAMA_JAB_STRUKTURAL,
							X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, COALESCE(Y.NILAI_SKP,0) NILAI,
							X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM, A.KD_ESELON,


							CONCAT(
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD_X.KUADRAN_X1
								AND (COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN COALESCE(X.JPM,0) * 100 >= 0 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X1 THEN '1'
							WHEN COALESCE(X.JPM,0) * 100 > KD_X.KUADRAN_X1 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X2 THEN '2'
							ELSE '3' END KUADRAN_X
							, COALESCE(X.JPM,0) * 100  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
							, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							, A.PEGAWAI_ID
						FROM ".$this->db.".pegawai A
						LEFT JOIN ".$this->db.".anjab_jab J ON A.anjab_jab = J.kd_jabatan
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, SUM(JPM) JPM, SUM(IKK) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							FROM
							(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN sum(JPM) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN sum(IKK) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN sum(JPM) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN sum(IKK) ELSE 0 END KOMPETEN_IKK
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
							GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
							) A
							GROUP BY A.PEGAWAI_ID
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT PEGAWAI_ID, NILAI_SKP, TAHUN
							FROM
							(
								SELECT PEGAWAI_ID PEGAWAI_ID, NILAI NILAI_SKP, THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja
								WHERE THN_PENILAIAN = '".$reqTahun."'
								UNION ALL
								SELECT A.PEGAWAI_ID PEGAWAI_ID, A.NILAI NILAI_SKP, A.THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja A
								INNER JOIN
								(
									SELECT A.PEGAWAI_ID PEGAWAI_ID, MAX(A.THN_PENILAIAN) TAHUN
									FROM ".$this->db.".riwayat_kinerja A
									GROUP BY A.PEGAWAI_ID
								) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.THN_PENILAIAN = B.TAHUN
							) A
							GROUP BY PEGAWAI_ID, NILAI_SKP, TAHUN
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringTableTalentPoolSkpMonitoring($paramsArray=array(), $statement="", $reqTahun, $searcJson= "")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT 
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 11 ID_KUADRAN, 1 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial rendah' NAMA_KUADRAN, 'I' KODE_KUADRAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 2 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial rendah' NAMA_KUADRAN, 'II' KODE_KUADRAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 3 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial menengah' NAMA_KUADRAN, 'III' KODE_KUADRAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 4 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial rendah' NAMA_KUADRAN, 'IV' KODE_KUADRAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 5 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial menengah' NAMA_KUADRAN, 'V' KODE_KUADRAN
					UNION ALL					
					SELECT 31 ID_KUADRAN, 6 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VI' KODE_KUADRAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 7 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial menengah' NAMA_KUADRAN, 'VII' KODE_KUADRAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 8 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VIII' KODE_KUADRAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 9 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'IX' KODE_KUADRAN
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				, A.PEGAWAI_ID
				FROM
				(
							
							SELECT 
							X.PEGAWAI_ID, A.NAMA, J.NM_JABATAN NAMA_JAB_STRUKTURAL,
							X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, COALESCE(Y.NILAI_SKP,0) NILAI,
							X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM, 
							CONCAT(
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD_X.KUADRAN_X1
								AND (COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN COALESCE(X.JPM,0) * 100 >= 0 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X1 THEN '1'
							WHEN COALESCE(X.JPM,0) * 100 > KD_X.KUADRAN_X1 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X2 THEN '2'
							ELSE '3' END KUADRAN_X
							, COALESCE(X.JPM,0) * 100  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
							, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							, A.PEGAWAI_ID
						FROM ".$this->db.".pegawai A
						LEFT JOIN ".$this->db.".anjab_jab J ON A.anjab_jab = J.kd_jabatan
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, SUM(JPM) JPM, SUM(IKK) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							FROM
							(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN sum(JPM) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN sum(IKK) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN sum(JPM) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN sum(IKK) ELSE 0 END KOMPETEN_IKK
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
							GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
							) A
							GROUP BY A.PEGAWAI_ID
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT PEGAWAI_ID, NILAI_SKP, TAHUN
							FROM
							(
								SELECT PEGAWAI_ID PEGAWAI_ID, NILAI NILAI_SKP, THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja
								WHERE THN_PENILAIAN = '".$reqTahun."'
								UNION ALL
								SELECT A.PEGAWAI_ID PEGAWAI_ID, A.NILAI NILAI_SKP, A.THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja A
								INNER JOIN
								(
									SELECT A.PEGAWAI_ID PEGAWAI_ID, MAX(A.THN_PENILAIAN) TAHUN
									FROM ".$this->db.".riwayat_kinerja A
									GROUP BY A.PEGAWAI_ID
								) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.THN_PENILAIAN = B.TAHUN
							) A
							GROUP BY PEGAWAI_ID, NILAI_SKP, TAHUN
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL ";
				
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		//echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

	function selectByParamsMonitoringTalentPoolSkp($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY COALESCE(X.JPM,0) * 100 DESC', $reqTahun)
	{
		$str = "
			SELECT 
				A.PEGAWAI_ID, A.KD_ORGANISASI, A.NAMA, (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) NILAI_YBAK
				, COALESCE(Y.NILAI_SKP,0) NILAI_XBAK
				, (COALESCE(X.JPM,0) * 100) NILAI_Y
				, COALESCE(Y.NILAI_SKP,0) NILAI_X
			FROM ".$this->db.".pegawai A
			INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			INNER JOIN
			(
				SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, NILAI_SKP, TAHUN
				FROM
				(
					SELECT PEGAWAI_ID PEGAWAI_ID, NILAI NILAI_SKP, THN_PENILAIAN TAHUN
					FROM ".$this->db.".riwayat_kinerja
					WHERE THN_PENILAIAN = '".$reqTahun."'
					UNION ALL
					SELECT A.PEGAWAI_ID PEGAWAI_ID, A.NILAI NILAI_SKP, A.THN_PENILAIAN TAHUN
					FROM ".$this->db.".riwayat_kinerja A
					INNER JOIN
					(
						SELECT A.PEGAWAI_ID PEGAWAI_ID, MAX(A.THN_PENILAIAN) TAHUN
						FROM ".$this->db.".riwayat_kinerja A
						GROUP BY A.PEGAWAI_ID
					) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.THN_PENILAIAN = B.TAHUN
				) A
				GROUP BY PEGAWAI_ID, NILAI_SKP, TAHUN
			) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
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

    function selectByParamsMonitoringTableTalentPoolSkp($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY ORDER_KUADRAN', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 1 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial rendah' NAMA_KUADRAN, 'I' KODE_KUADRAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 2 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial rendah' NAMA_KUADRAN, 'II' KODE_KUADRAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 3 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial menengah' NAMA_KUADRAN, 'III' KODE_KUADRAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 4 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial rendah' NAMA_KUADRAN, 'IV' KODE_KUADRAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 5 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial menengah' NAMA_KUADRAN, 'V' KODE_KUADRAN
					UNION ALL					
					SELECT 31 ID_KUADRAN, 6 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VI' KODE_KUADRAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 7 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial menengah' NAMA_KUADRAN, 'VII' KODE_KUADRAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 8 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VIII' KODE_KUADRAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 9 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'IX' KODE_KUADRAN
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							
						SELECT 
							CONCAT(
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD_X.KUADRAN_X1
								AND (COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN COALESCE(X.JPM,0) * 100 >= 0 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X1 THEN '1'
							WHEN COALESCE(X.JPM,0) * 100 > KD_X.KUADRAN_X1 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X2 THEN '2'
							ELSE '3' END KUADRAN_X
							, COALESCE(X.JPM,0) * 100  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT PEGAWAI_ID, NILAI_SKP, TAHUN
							FROM
							(
								SELECT PEGAWAI_ID PEGAWAI_ID, NILAI NILAI_SKP, THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja
								WHERE THN_PENILAIAN = '".$reqTahun."'
								UNION ALL
								SELECT A.PEGAWAI_ID PEGAWAI_ID, A.NILAI NILAI_SKP, A.THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja A
								INNER JOIN
								(
									SELECT A.PEGAWAI_ID PEGAWAI_ID, MAX(A.THN_PENILAIAN) TAHUN
									FROM ".$this->db.".riwayat_kinerja A
									GROUP BY A.PEGAWAI_ID
								) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.THN_PENILAIAN = B.TAHUN
							) A
							GROUP BY PEGAWAI_ID, NILAI_SKP, TAHUN
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
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 ".$orderby; 
			
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringTableTalentPoolSkp13082018($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY ORDER_KUADRAN', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 1 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial rendah' NAMA_KUADRAN, 'I' KODE_KUADRAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 2 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial rendah' NAMA_KUADRAN, 'II' KODE_KUADRAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 3 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial menengah' NAMA_KUADRAN, 'III' KODE_KUADRAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 4 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial rendah' NAMA_KUADRAN, 'IV' KODE_KUADRAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 5 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial menengah' NAMA_KUADRAN, 'V' KODE_KUADRAN
					UNION ALL					
					SELECT 31 ID_KUADRAN, 6 ORDER_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VI' KODE_KUADRAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 7 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial menengah' NAMA_KUADRAN, 'VII' KODE_KUADRAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 8 ORDER_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VIII' KODE_KUADRAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 9 ORDER_KUADRAN, 'Kinerja diatas ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'IX' KODE_KUADRAN
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							
						SELECT 
							CONCAT(
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD_X.KUADRAN_X1
								AND (COALESCE(X.JPM,0) * 100) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN COALESCE(X.JPM,0) * 100 >= 0 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X1 THEN '1'
							WHEN COALESCE(X.JPM,0) * 100 > KD_X.KUADRAN_X1 AND COALESCE(X.JPM,0) * 100 <= KD_X.KUADRAN_X2 THEN '2'
							ELSE '3' END KUADRAN_X
							, COALESCE(X.JPM,0) * 100  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
						) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
						LEFT JOIN
						(
							SELECT PEGAWAI_ID, NILAI_SKP, TAHUN
							FROM
							(
								SELECT PEGAWAI_ID PEGAWAI_ID, NILAI NILAI_SKP, THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja
								WHERE THN_PENILAIAN = '".$reqTahun."'
								UNION ALL
								SELECT A.PEGAWAI_ID PEGAWAI_ID, A.NILAI NILAI_SKP, A.THN_PENILAIAN TAHUN
								FROM ".$this->db.".riwayat_kinerja A
								INNER JOIN
								(
									SELECT A.PEGAWAI_ID PEGAWAI_ID, MAX(A.THN_PENILAIAN) TAHUN
									FROM ".$this->db.".riwayat_kinerja A
									GROUP BY A.PEGAWAI_ID
								) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.THN_PENILAIAN = B.TAHUN
							) A
							GROUP BY PEGAWAI_ID, NILAI_SKP, TAHUN
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
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 ".$orderby; 
			
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsMonitoringTalentPoolNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC', $reqTahun)
	{
		$str = "
			SELECT 
				A.PEGAWAI_ID, A.SATKER_ID KD_ORGANISASI, A.NAMA
				, CASE WHEN COALESCE(X.NILAI_POTENSI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI_POTENSI,0) END NILAI_X
				, CASE WHEN COALESCE(Y.NILAI_KOMPETENSI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI_KOMPETENSI,0) END NILAI_Y
				, COALESCE(X.NILAI_POTENSI,0) NILAI_XBAK
				, COALESCE(Y.NILAI_KOMPETENSI,0) NILAI_YBAK
				, X.FORMULA
				, X.FORMULA_ID
				, X.JADWAL_TES_ID
			FROM ".$this->db.".pegawai A
			INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			INNER JOIN
			(
				SELECT PEGAWAI_ID
				, D.FORMULA
				, D.FORMULA_ID 
			    FROM penilaian A
			    INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID   
				WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), D.FORMULA
				, D.FORMULA_ID
			) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
				, D.FORMULA
				, D.FORMULA_ID, A.JADWAL_TES_ID
				FROM penilaian A
				INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID   
				WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1)
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
			  SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
			  , D.FORMULA
			  , D.FORMULA_ID
			  FROM penilaian A
			  INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
			  INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
			  INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID  
			  WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2)
			) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
			,
			(
				SELECT COALESCE(GM_Y2,0) MAX_DATA_Y
				FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
			) KD_Y,
			(
				SELECT COALESCE(SKP_X2,0) MAX_DATA_X
				FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
			) KD_X
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

    function selectByParamsMonitoringTalentPoolNewBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) DESC', $reqTahun)
	{
		$str = "
			SELECT 
				A.PEGAWAI_ID, A.KD_ORGANISASI, A.NAMA, (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) NILAI_YBAK
				, COALESCE(Y.NILAI_SKP,0) NILAI_XBAK
				, (
				CASE WHEN COALESCE(X.JPM,0) * 100 > 
				(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
				THEN 
				(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
				ELSE COALESCE(X.JPM,0) * 100 END
				) NILAI_Y
				, CASE WHEN COALESCE(Y.NILAI_SKP,0) >
				(SELECT TOLERANSI_X + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
				THEN 
				(SELECT TOLERANSI_X + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
				ELSE 
				COALESCE(Y.NILAI_SKP,0)
				END
				NILAI_X
			FROM ".$this->db.".pegawai A
			INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			INNER JOIN
			(
				SELECT PEGAWAI_ID
			  FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
			  SELECT PEGAWAI_ID, (sum(JPM) / 2) * 100 NILAI_SKP, (sum(IKK)/2) IKK, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
			  FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
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

  function selectByParamsMonitoringTableTalentPoolNewBak2($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY ORDER_KUADRAN', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
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
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
						SELECT 
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
							, X.FORMULA
							, X.FORMULA_ID
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
				        (
				        	SELECT PEGAWAI_ID
				        	, D.FORMULA
				        	, D.FORMULA_ID
				          	FROM penilaian A
				          	INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
						    INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				            INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
				        	WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
				        	, D.FORMULA
				        	, D.FORMULA_ID
				        ) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				        LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
				        	, D.FORMULA
				        	, D.FORMULA_ID
				        	FROM penilaian A
				        	INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
						    INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				              INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID  
				        	WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1)
				        ) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				        LEFT JOIN
				        (
				          SELECT PEGAWAI_ID, JPM * 100 NILAI_KOMPETENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
				          , D.FORMULA
				          , D.FORMULA_ID
				          FROM penilaian A
				          INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
						  INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				          INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID  
				          WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2)
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
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
			
		// echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
  }

  function selectByParamsMonitoringTableTalentPoolNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
		select a.*, count(b.pegawai_id) JUMLAH
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
							WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$str .= " group by B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK ,B.NILAI ,B.KOMPETEN_JPM, b.psikologi_jpm,
			b.jpm,  A.*, a.id_kuadran, a.nama_kuadran, a.kode_kuadran, b.formula, b.formula_id";
		// $str .= $orderby;
		$str .= ") B ON B.ID_KUADRAN = A.ID_KUADRAN 
				group by a.id_kuadran,a.nama_kuadran,a.kode_kuadran order by a.id_kuadran asc";
			
		// echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
  }

    function selectByParamsMonitoringTableTalentPoolNewBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'I' KODE_KUADRAN 
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'II' KODE_KUADRA 
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'III' KODE_KUADRAN 
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Potential Leter' NAMA_KUADRAN, 'IV' KODE_KUADRAN 
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Performance Leter' NAMA_KUADRAN, 'V' KODE_KUADRAN 
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN 
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN 
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN 
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN 
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							
						SELECT 
							CONCAT(
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									(SELECT TOLERANSI_Y + 100 FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."')
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(
									CASE WHEN 
									COALESCE(X.JPM,0) * 100 > 100 THEN
										100
									ELSE
										COALESCE(X.JPM,0) * 100 
									END
								) <= KD_Y.KUADRAN_Y1 
								THEN '1'
								WHEN 
								(
									CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
										100
									ELSE 
										COALESCE(X.JPM,0) * 100
									END
								) > KD_Y.KUADRAN_Y1 
								AND 
								(
									CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 
										100
									ELSE 
										COALESCE(X.JPM,0) * 100 
									END
								) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_X.KUADRAN_X1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_X.KUADRAN_X1) AND COALESCE(Y.NILAI_SKP,0) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) >= 0 AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) <= KD_X.KUADRAN_X1 THEN '1'
							WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) > KD_X.KUADRAN_X1 AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) <= KD_X.KUADRAN_X2 THEN '2'
							ELSE '3' END KUADRAN_X
							, (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END)  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI_SKP,0) <= KD_Y.KUADRAN_Y2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
				        (
				        	SELECT PEGAWAI_ID
				          FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				        ) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				        LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				        ) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				        LEFT JOIN
				        (
				          SELECT PEGAWAI_ID, (sum(JPM) / 2) * 100 NILAI_SKP, (sum(IKK)/2) IKK, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
				          FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
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
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
			
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringTableTalentPoolMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY JPM_TOTAL DESC', $reqTahun, $searcJson= "",$orderby="")
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
							WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$str .= " group by B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK ,B.NILAI ,B.KOMPETEN_JPM, b.psikologi_jpm,
			b.jpm,  A.*, a.id_kuadran, a.nama_kuadran, a.kode_kuadran, b.formula, b.formula_id";
		$str .= $orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	function getCountByParamsMonitoringTableTalentPoolMonitoring($paramsArray=array(), $statement="", $reqTahun, $searcJson= "",$orderby="")
	{
		$str = "
			SELECT COUNT(1) ROWCOUNT 
			from(
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
							WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
				
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$str .= " group by B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK ,B.NILAI ,B.KOMPETEN_JPM, b.psikologi_jpm,
			b.jpm,  A.*, a.id_kuadran, a.nama_kuadran, a.kode_kuadran, b.formula, b.formula_id";
		$str .= $orderby;
		$str.=")x";
		// echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringTableTalentPoolMonitoringBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY JPM_TOTAL DESC', $reqTahun, $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			(B.KOMPETEN_JPM*100) KOMPETEN_JPM, (B.PSIKOLOGI_JPM*100) PSIKOLOGI_JPM, B.JPM, 
			CASE WHEN COALESCE(B.JPM,0) * 100 > 100 THEN 0 ELSE COALESCE(B.JPM,0) * 100 END JPM_TOTAL,
			CASE WHEN COALESCE(B.IKK,0) * 100 > 100 THEN 0 ELSE COALESCE(B.IKK,0) * 100 END IKK_TOTAL,
			A.*
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 11 ID_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial rendah' NAMA_KUADRAN, 'I' KODE_KUADRAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial rendah' NAMA_KUADRAN, 'II' KODE_KUADRAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial menengah' NAMA_KUADRAN, 'III' KODE_KUADRAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Kinerja diatas ekspektasi dan potensial rendah' NAMA_KUADRAN, 'IV' KODE_KUADRAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial menengah' NAMA_KUADRAN, 'V' KODE_KUADRAN
					UNION ALL					
					SELECT 31 ID_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VI' KODE_KUADRAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Kinerja diatas ekspektasi dan potensial menengah' NAMA_KUADRAN, 'VII' KODE_KUADRAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VIII' KODE_KUADRAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Kinerja diatas ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'IX' KODE_KUADRAN
				) A
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
							X1.PEGAWAI_ID, A.NAMA, J.NM_JABATAN NAMA_JAB_STRUKTURAL,
							X1.KOMPETEN_IKK, X1.PSIKOLOGI_IKK, X.IKK, COALESCE(Y.NILAI_SKP,0) NILAI,
							X1.KOMPETEN_JPM, X1.PSIKOLOGI_JPM, X.JPM,
							CONCAT(
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									100
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(
									CASE WHEN 
									COALESCE(X.JPM,0) * 100 > 100 THEN
										100
									ELSE
										COALESCE(X.JPM,0) * 100 
									END
								) <= KD_Y.KUADRAN_Y1 
								THEN '1'
								WHEN 
								(
									CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
										100
									ELSE 
										COALESCE(X.JPM,0) * 100
									END
								) > KD_Y.KUADRAN_Y1 
								AND 
								(
									CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 
										100
									ELSE 
										COALESCE(X.JPM,0) * 100 
									END
								) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_X.KUADRAN_X1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_X.KUADRAN_X1) AND COALESCE(Y.NILAI_SKP,0) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) >= 0 AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) <= KD.KUADRAN_1 THEN '1'
							WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) > KD.KUADRAN_1 AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X
							, (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END)  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD.KUADRAN_1) AND COALESCE(Y.NILAI_SKP,0) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
							, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
						FROM ".$this->db.".pegawai A
						LEFT JOIN ".$this->db.".anjab_jab J ON A.anjab_jab = J.kd_jabatan
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, SUM(JPM) JPM, SUM(IKK) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							FROM
							(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN sum(JPM) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN sum(IKK) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN sum(JPM) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN sum(IKK) ELSE 0 END KOMPETEN_IKK
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
							GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
							) A
							GROUP BY A.PEGAWAI_ID
						) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
						LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				        ) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				        LEFT JOIN
				        (
				          SELECT PEGAWAI_ID, (sum(JPM) / 2) * 100 NILAI_SKP, (sum(IKK)/2) IKK, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
				          FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	function getCountByParamsMonitoringTableTalentPoolMonitoringBak($paramsArray=array(), $statement="", $reqTahun, $searcJson= "")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT 
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 11 ID_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial rendah' NAMA_KUADRAN, 'I' KODE_KUADRAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial rendah' NAMA_KUADRAN, 'II' KODE_KUADRAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial menengah' NAMA_KUADRAN, 'III' KODE_KUADRAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Kinerja diatas ekspektasi dan potensial rendah' NAMA_KUADRAN, 'IV' KODE_KUADRAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial menengah' NAMA_KUADRAN, 'V' KODE_KUADRAN
					UNION ALL					
					SELECT 31 ID_KUADRAN, 'Kinerja dibawah ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VI' KODE_KUADRAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Kinerja diatas ekspektasi dan potensial menengah' NAMA_KUADRAN, 'VII' KODE_KUADRAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Kinerja sesuai ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'VIII' KODE_KUADRAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Kinerja diatas ekspektasi dan potensial tinggi' NAMA_KUADRAN, 'IX' KODE_KUADRAN
				) A
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
							X.PEGAWAI_ID, A.NAMA, J.NM_JABATAN NAMA_JAB_STRUKTURAL,
							X1.KOMPETEN_IKK, X1.PSIKOLOGI_IKK, X.IKK, COALESCE(Y.NILAI_SKP,0) NILAI,
							X1.KOMPETEN_JPM, X1.PSIKOLOGI_JPM, X.JPM,
							CONCAT(
								CASE 
								WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
									100
								ELSE 
								COALESCE(X.JPM,0) * 100 END) >= 0 AND 
								(
									CASE WHEN 
									COALESCE(X.JPM,0) * 100 > 100 THEN
										100
									ELSE
										COALESCE(X.JPM,0) * 100 
									END
								) <= KD_Y.KUADRAN_Y1 
								THEN '1'
								WHEN 
								(
									CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN
										100
									ELSE 
										COALESCE(X.JPM,0) * 100
									END
								) > KD_Y.KUADRAN_Y1 
								AND 
								(
									CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 
										100
									ELSE 
										COALESCE(X.JPM,0) * 100 
									END
								) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD_X.KUADRAN_X1 THEN '1'
								WHEN (COALESCE(Y.NILAI_SKP,0) > KD_X.KUADRAN_X1) AND COALESCE(Y.NILAI_SKP,0) <= KD_X.KUADRAN_X2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) >= 0 AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) <= KD.KUADRAN_1 THEN '1'
							WHEN (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) > KD.KUADRAN_1 AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X
							, (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END)  NILAI_X
							, CASE 
							WHEN (COALESCE(Y.NILAI_SKP,0) >= 0) AND COALESCE(Y.NILAI_SKP,0) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(Y.NILAI_SKP,0) > KD.KUADRAN_1) AND COALESCE(Y.NILAI_SKP,0) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y
							, COALESCE(Y.NILAI_SKP,0) NILAI_Y
							, KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
						FROM ".$this->db.".pegawai A
						LEFT JOIN ".$this->db.".anjab_jab J ON A.anjab_jab = J.kd_jabatan
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
						(
							SELECT PEGAWAI_ID, SUM(JPM) JPM, SUM(IKK) IKK
							, SUM(PSIKOLOGI_JPM) PSIKOLOGI_JPM, SUM(PSIKOLOGI_IKK) PSIKOLOGI_IKK, SUM(KOMPETEN_JPM) KOMPETEN_JPM, SUM(KOMPETEN_IKK) KOMPETEN_IKK
							FROM
							(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK
							, CASE WHEN ASPEK_ID = 1 THEN sum(JPM) ELSE 0 END PSIKOLOGI_JPM
							, CASE WHEN ASPEK_ID = 1 THEN sum(IKK) ELSE 0 END PSIKOLOGI_IKK
							, CASE WHEN ASPEK_ID = 2 THEN sum(JPM) ELSE 0 END KOMPETEN_JPM
							, CASE WHEN ASPEK_ID = 2 THEN sum(IKK) ELSE 0 END KOMPETEN_IKK
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
							GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
							) A
							GROUP BY A.PEGAWAI_ID
						) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
						LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				        ) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				        LEFT JOIN
				        (
				          SELECT PEGAWAI_ID, (sum(JPM) / 2) * 100 NILAI_SKP, (sum(IKK)/2) IKK, TO_CHAR(TANGGAL_TES, 'YYYY') TAHUN
				          FROM penilaian 
				        	WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
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
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL ";
				
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		//echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringTalentPoolSkpPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC', $reqTahun)
	{
		$str = "
			SELECT 
				A.PEGAWAI_ID, A.SATKER_ID KD_ORGANISASI, A.NAMA
				, CASE WHEN COALESCE(X.NILAI_POTENSI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI_POTENSI,0) END NILAI_X
				, CASE WHEN COALESCE(Y.NILAI_KOMPETENSI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI_KOMPETENSI,0) END NILAI_Y
				, COALESCE(X.NILAI_POTENSI,0) NILAI_XBAK
				, COALESCE(Y.NILAI_KOMPETENSI,0) NILAI_YBAK
			FROM ".$this->db.".pegawai A
			INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			INNER JOIN
			(
				SELECT PEGAWAI_ID
			    FROM penilaian A
			    INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID    
				WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
			) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
				, D.FORMULA
				, D.FORMULA_ID
				FROM penilaian A
				INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
				INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID   
				WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2)
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
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
							SELECT PEGAWAI_ID, 9999 TAHUN, CAST((case when LAST_SKP = '' then '0' else LAST_SKP end) AS NUMERIC) NILAI_SKP
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
				SELECT COALESCE(GM_Y2,0) MAX_DATA_Y
				FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
			) KD_Y,
			(
				SELECT COALESCE(SKP_X2,0) MAX_DATA_X
				FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
			) KD_X
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

    function selectByParamsMonitoringTalentPoolSkpJPMPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC', $reqTahun)
	{
		$str = "
			SELECT 
				A.PEGAWAI_ID, A.SATKER_ID KD_ORGANISASI, A.NAMA
				, CASE WHEN COALESCE(X.JPM,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.JPM,0) END NILAI_X
				, CASE WHEN COALESCE(Y.NILAI_KOMPETENSI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI_KOMPETENSI,0) END NILAI_Y
				, COALESCE(X.JPM,0) NILAI_XBAK
				, COALESCE(Y.NILAI_KOMPETENSI,0) NILAI_YBAK
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
				WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) 
				GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA
				, D.FORMULA_ID
				) A
				GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
				, A.FORMULA_ID
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
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
				SELECT COALESCE(GM_Y2,0) MAX_DATA_Y
				FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
			) KD_Y,
			(
				SELECT COALESCE(SKP_X2,0) MAX_DATA_X
				FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
			) KD_X
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

    function selectByParamsMonitoringTableTalentPoolSkpPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY ORDER_KUADRAN', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT * FROM P_KUADRAN_INFO()
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
						SELECT 
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
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
						INNER JOIN
				        (
				        	SELECT PEGAWAI_ID
				            FROM penilaian A
				            INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
				        	WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
				        ) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
				        LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, JPM * 100 NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
				        	, D.FORMULA
				        	, D.FORMULA_ID
				        	FROM penilaian A
				        	INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
							INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
							INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
				        	WHERE 1=1 AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2)
				        ) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				        LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
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
										SELECT PEGAWAI_ID, 9999 TAHUN, CAST((case when LAST_SKP = '' then '0' else LAST_SKP end) AS NUMERIC) NILAI_SKP
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
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
			
		// echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

     function selectByParamsMonitoringTableTalentPoolSkpJPMPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY ORDER_KUADRAN', $reqTahun)
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT * FROM p_kuadran_infojpm()
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
						SELECT 
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
						FROM ".$this->db.".pegawai A
						INNER JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID				
				        INNER JOIN
					(
						SELECT PEGAWAI_ID, 
						ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  NILAI_POTENSI
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
						GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA
						, D.FORMULA_ID
						) A
						GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
						, A.FORMULA_ID
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
				        LEFT JOIN
				        (
				        	SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
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
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
			
		// echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPersonalTalentPoolSkpJPMPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY COALESCE(X.NILAI_POTENSI,0) DESC, COALESCE(Y.NILAI_KOMPETENSI,0) DESC')
	{
		$str = "
		SELECT 
			A.PEGAWAI_ID, A.SATKER_ID KD_ORGANISASI, A.NAMA
			, CASE WHEN COALESCE(X.NILAI_POTENSI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI_POTENSI,0) END NILAI_X
			, CASE WHEN COALESCE(Y.NILAI_KOMPETENSI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI_KOMPETENSI,0) END NILAI_Y
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID		
		INNER JOIN
		(
			SELECT PEGAWAI_ID, A.JADWAL_TES_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
			FROM penilaian A
			INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
			INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
			INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID   
			WHERE 1=1
			".$statement."
			AND ASPEK_ID in (1,2)
			GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), A.JADWAL_TES_ID
		) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
		INNER JOIN
		(
			SELECT PEGAWAI_ID, 
			ROUND(((SUM(PSIKOLOGI_JPM) * PROSEN_POTENSI) /100) + ((SUM(KOMPETEN_JPM) * PROSEN_KOMPETENSI) /100),2)  NILAI_POTENSI
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
			WHERE 1=1 
			".$statement."
			AND ASPEK_ID in (1,2) 
			GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), ASPEK_ID, C.PROSEN_POTENSI, C.PROSEN_KOMPETENSI, D.FORMULA, D.FORMULA_ID, B.JADWAL_TES_ID, D.TIPE_FORMULA
			) A
			GROUP BY A.PEGAWAI_ID, PROSEN_POTENSI, PROSEN_KOMPETENSI, A.FORMULA
			, A.FORMULA_ID, A.TIPE_FORMULA, A.JADWAL_TES_ID
		) X ON A.PEGAWAI_ID = X.PEGAWAI_ID 
		LEFT JOIN
		(
			SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
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
						WHERE SKP_TAHUN = (SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN FROM penilaian A WHERE 1=1 ".$statement." GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY'))
					) A
				) A
				WHERE NOMOR = 1
			) A
		) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT COALESCE(GM_Y2,0) MAX_DATA_Y, CAST(TAHUN AS TEXT) TAHUN
			FROM toleransi_talent_pool WHERE 1=1
		) KD_Y ON X1.TAHUN = KD_Y.TAHUN
		LEFT JOIN
		(
			SELECT COALESCE(SKP_X2,0) MAX_DATA_X, CAST(TAHUN AS TEXT) TAHUN
			FROM toleransi_talent_pool WHERE 1=1
		) KD_X ON X1.TAHUN = KD_X.TAHUN
		WHERE 1=1
	 	"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPersonalKuadranPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
	    SELECT
	    A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
	    FROM
	    (
	    	SELECT * FROM
	    	(
	    		SELECT * FROM p_kuadran_infojpm()
	    	) A
	    ) A
	    INNER JOIN
	    (
	    	SELECT
	    	COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
	    	FROM
	    	(
	    		SELECT 
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
	    		FROM simpeg.pegawai A
	    		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
	    		INNER JOIN
	    		(
	    			SELECT PEGAWAI_ID, A.JADWAL_TES_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
	    			FROM penilaian A
	    			INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
	    			INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
	    			INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID   
	    			WHERE 1=1
	    			".$statement."
	    			AND ASPEK_ID IN (1,2)
	    			GROUP BY PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), A.JADWAL_TES_ID
	    		) X1 ON A.PEGAWAI_ID = X1.PEGAWAI_ID
	    		LEFT JOIN
	    		(
	    			SELECT PEGAWAI_ID, round(sum(JPM*100)/2,2) NILAI_POTENSI, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
	    			, D.FORMULA
	    			, D.FORMULA_ID
	    			FROM penilaian A
	    			INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
	    			INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
	    			INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID  
	    			WHERE 1=1
	    			".$statement."
	    			AND ASPEK_ID IN (1,2)
	    			group by PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY') , D.FORMULA
	    			, D.FORMULA_ID
	    		) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
	    		LEFT JOIN
	    		(
	    			SELECT PEGAWAI_ID, TAHUN, NILAI_SKP NILAI_KOMPETENSI
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
	    						WHERE SKP_TAHUN = (SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN FROM penilaian A WHERE 1=1 ".$statement." GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY'))
	    					) A
	    				) A
	    				WHERE NOMOR = 1
	    			) A
	    		) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
	    		LEFT JOIN
	    		(
	    			SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2, CAST(TAHUN AS TEXT) TAHUN
	    			FROM toleransi_talent_pool WHERE 1=1
	    		) KD_Y ON X1.TAHUN = KD_Y.TAHUN
	    		LEFT JOIN
	    		(
	    			SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2, CAST(TAHUN AS TEXT) TAHUN
	    			FROM toleransi_talent_pool WHERE 1=1
	    		) KD_X ON X1.TAHUN = KD_X.TAHUN
	    		WHERE 1=1

	    	) A
	    	GROUP BY A.KUADRAN_PEGAWAI
	    ) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
		WHERE 1=1 ";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $orderby;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

  } 
?>
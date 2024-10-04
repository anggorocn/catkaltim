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

  class PenilaianDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PenilaianDetil()
	{
	  $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
      $this->Entity(); 
    }
	
	function insert()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENILAIAN_DETIL_ID", $this->getNextId("PENILAIAN_DETIL_ID","penilaian_detil"));
		
		$str = "INSERT INTO penilaian_detil (
				   PENILAIAN_DETIL_ID, PENILAIAN_ID, ATRIBUT_ID, NILAI, GAP, BUKTI, CATATAN)
				VALUES (
				  ".$this->getField("PENILAIAN_DETIL_ID").",
				  ".$this->getField("PENILAIAN_ID").",
				  '".$this->getField("ATRIBUT_ID")."',
				  ".$this->getField("NILAI").",
				  ".$this->getField("GAP").",
				  '".$this->getField("BUKTI")."',
				  '".$this->getField("CATATAN")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("PENILAIAN_DETIL_ID");
		return $this->execQuery($str);
    }
	
	function insertTrigerPotensi()
	{
		$strDelete= "DELETE FROM penilaian_detil WHERE PENILAIAN_ID = ".$this->getField("PENILAIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID"); 
		$this->execQuery($strDelete);
		
		$str = "
		INSERT INTO penilaian_detil 
		(PENILAIAN_DETIL_ID, PENILAIAN_ID, ATRIBUT_ID, FORMULA_ESELON_ID, FORMULA_ATRIBUT_ID, PEGAWAI_ID)
		SELECT (SELECT COALESCE(MAX(PENILAIAN_DETIL_ID),0) + (@ROW := @ROW + 1) FROM penilaian_detil) PENILAIAN_DETIL_ID, ".$this->getField("PENILAIAN_ID")." PENILAIAN_ID, D.ATRIBUT_ID, A.FORMULA_ESELON_ID, C.FORMULA_ATRIBUT_ID, ".$this->getField("PEGAWAI_ID")." PEGAWAI_ID
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN formula_atribut C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
		INNER JOIN level_atribut D ON C.LEVEL_ID = D.LEVEL_ID
		, (SELECT @ROW := 0) r
		WHERE 1=1 AND A.JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")." AND LEVEL = 0"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian_detil
				SET
				   PENILAIAN_ID= ".$this->getField("PENILAIAN_ID").",
				   ATRIBUT_ID= '".$this->getField("ATRIBUT_ID")."',
				   NILAI= ".$this->getField("NILAI").",
				   GAP= ".$this->getField("GAP").",
				   BUKTI= '".$this->getField("BUKTI")."',
				   CATATAN= '".$this->getField("CATATAN")."'
				WHERE PENILAIAN_DETIL_ID= ".$this->getField("PENILAIAN_DETIL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateCatatan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian_detil
				SET
				   BUKTI= '".$this->getField("BUKTI")."',
				   CATATAN= '".$this->getField("CATATAN")."'
				WHERE PENILAIAN_DETIL_ID= '".$this->getField("PENILAIAN_DETIL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE penilaian_detil
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  PENILAIAN_DETIL_ID = ".$this->getField("PENILAIAN_DETIL_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM penilaian_detil
                WHERE 
                  PENILAIAN_DETIL_ID = ".$this->getField("PENILAIAN_DETIL_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_DETIL_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_DETIL_ID, A.PENILAIAN_ID, A.ATRIBUT_ID, A.NILAI, A.GAP
				FROM penilaian_detil A
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
	
	function selectByParamsMonitoringPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $penilaianId="", $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, A.PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.NAMA, A.ATRIBUT_GROUP
				, A.NILAI_STANDAR, A.NILAI, A.GAP, CASE WHEN A.PROSENTASE > 100 THEN 100 ELSE A.PROSENTASE END PROSENTASE, A.BUKTI, A.CATATAN
				, A.LEVEL, A.LEVEL_KETERANGAN
				FROM 
				(
					SELECT A.PENILAIAN_ID, B.PENILAIAN_DETIL_ID, C.ATRIBUT_ID, C.ATRIBUT_ID_PARENT, C.NAMA, C.ATRIBUT_ID_PARENT ATRIBUT_GROUP
					, B1.NILAI_STANDAR
					, LA.LEVEL, LA.KETERANGAN LEVEL_KETERANGAN
					, CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END NILAI, COALESCE(B.GAP,0) GAP, B.BUKTI, B.CATATAN
					, ROUND((B.NILAI / B1.NILAI_STANDAR) * 100,2) PROSENTASE
					, B.PERMEN_ID
					FROM penilaian A
					INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
					INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
					INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
					INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
					WHERE 1=1
					UNION ALL
					SELECT B.PENILAIAN_ID, NULL PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.NAMA, A.ATRIBUT_ID ATRIBUT_GROUP
					, NULL NILAI_STANDAR
					, NULL AS LEVEL, '' LEVEL_KETERANGAN
					, NULL NILAI, NULL GAP, '' BUKTI, '' CATATAN
					, B.PROSENTASE, A.PERMEN_ID
					FROM atribut A
					LEFT JOIN
					(
						SELECT B.PENILAIAN_ID, SUBSTR(B.ATRIBUT_ID, 1, 2) ATRIBUT_ID, COUNT(1) JUMLAH_PENILAIAN_DETIL
						, ROUND((SUM(B.NILAI) / SUM(B1.NILAI_STANDAR)) * 100,2) PROSENTASE, PERMEN_ID
						FROM penilaian_detil B
						INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
						WHERE 1=1
						GROUP BY B.PENILAIAN_ID, SUBSTR(B.ATRIBUT_ID, 1, 2), PERMEN_ID
					) B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.PERMEN_ID = B.PERMEN_ID
					WHERE 1=1
				) A
				WHERE 1=1
				AND A.PENILAIAN_ID = ".$penilaianId."
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
	
	function selectByParamsMonitoringIndikatorPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $penilaianId="", $sOrder="ORDER BY C.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, C.ATRIBUT_ID, C.ATRIBUT_ID_PARENT, C.NAMA, C.KETERANGAN
				, B1.NILAI_STANDAR, B.NILAI, AP.NAMA NAMA_ATRIBUT_PARENT
				, LA.LEVEL_ID, LA.LEVEL, LA.KETERANGAN LEVEL_KETERANGAN
				, CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END NILAI, COALESCE(B.GAP,0) GAP, B.BUKTI, B.CATATAN
				FROM penilaian A
				INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
				INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
				INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID
				INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
				INNER JOIN atribut AP ON AP.ATRIBUT_ID = SUBSTR(C.ATRIBUT_ID,1,2)
				WHERE 1=1
				AND A.PENILAIAN_ID = ".$penilaianId."
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
	
	function selectByParamsMonitoringDetilIndikatorPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $penilaianId="", $sOrder="ORDER BY C.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, C.ATRIBUT_ID, C.ATRIBUT_ID_PARENT, C.NAMA, C.KETERANGAN
				, B1.NILAI_STANDAR, B.NILAI
				, LA.LEVEL_ID, LA.LEVEL, LA.KETERANGAN LEVEL_KETERANGAN, IP.NAMA_INDIKATOR
				, CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END NILAI, COALESCE(B.GAP,0) GAP, B.BUKTI, B.CATATAN
				FROM penilaian A
				INNER JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
				INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
				INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID
				INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
				INNER JOIN indikator_penilaian IP ON LA.LEVEL_ID = IP.LEVEL_ID
				WHERE 1=1
				AND A.PENILAIAN_ID = ".$penilaianId."
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
	
	function selectByParamsMonitoringPenilaianBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $penilaianId="", $sOrder="ORDER BY B.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT
					B.NAMA , CASE C.ESELON WHEN 4 THEN B.NILAI_ES4 WHEN 3 THEN B.NILAI_ES3 WHEN 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END NILAI_STANDAR
					, B.BOBOT, B.ATRIBUT_ID, B.ATRIBUT_ID_PARENT, SUBSTR(B.ATRIBUT_ID, 1, 2) ATRIBUT_GROUP
					, C.PENILAIAN_DETIL_ID, C.PENILAIAN_ID
					, CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END NILAI
					, COALESCE(D.JUMLAH_PENILAIAN_DETIL,0) JUMLAH_PENILAIAN_DETIL
					, CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE C.ESELON WHEN 4 THEN B.NILAI_ES4 WHEN 3 THEN B.NILAI_ES3 WHEN 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END GAP
					, C.BUKTI, C.CATATAN
				FROM jabatan_eselon_atribut A
				LEFT JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID
				LEFT JOIN
				(
					SELECT A1.ESELON, A1.PENILAIAN_ID, A2.PENILAIAN_DETIL_ID, A2.NILAI, A2.GAP, A2.ATRIBUT_ID
					, A2.BUKTI, A2.CATATAN
					FROM penilaian A1
					LEFT JOIN penilaian_detil A2 ON A1.PENILAIAN_ID = A2.PENILAIAN_ID
					WHERE 1=1 AND A1.PENILAIAN_ID = ".$penilaianId."
				) C ON C.ATRIBUT_ID = B.ATRIBUT_ID
				LEFT JOIN
				(
					SELECT PENILAIAN_ID, SUBSTR(ATRIBUT_ID, 1, 2) ATRIBUT_ID, COUNT(1) JUMLAH_PENILAIAN_DETIL
					FROM penilaian_detil
					WHERE 1=1
					GROUP BY PENILAIAN_ID, SUBSTR(ATRIBUT_ID, 1, 2)
				) D ON D.PENILAIAN_ID = C.PENILAIAN_ID AND SUBSTR(B.ATRIBUT_ID, 1, 2) = D.ATRIBUT_ID
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
	
	function selectByParamsMonitoringPenilaianModif($paramsArray=array(),$limit=-1,$from=-1, $statement='', $penilaianId="", $sOrder="ORDER BY ATRIBUT_ID ASC")
	{
		$str = "
				SELECT B.NAMA, B.NILAI_STANDAR, B.BOBOT, B.ATRIBUT_ID, 0 as ATRIBUT_ID_PARENT, B.ATRIBUT_ID as ATRIBUT_GROUP, 0 PENILAIAN_DETIL_ID, ".$penilaianId." PENILAIAN_ID, 0 NILAI, 0 GAP
				FROM atribut B
				where atribut_id in (SELECT SUBSTR(ATRIBUT_ID, 1, 2) ATRIBUT_ID FROM penilaian_detil
				WHERE PENILAIAN_ID = ".$penilaianId."
				GROUP BY  SUBSTR(ATRIBUT_ID, 1, 2))
				union all
				SELECT
					B.NAMA, CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END NILAI_STANDAR, B.BOBOT, B.ATRIBUT_ID, B.ATRIBUT_ID_PARENT, SUBSTR(B.ATRIBUT_ID, 1, 2) ATRIBUT_GROUP,
					C.PENILAIAN_DETIL_ID, C.PENILAIAN_ID,
					CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END NILAI, 
					CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END GAP
				FROM  atribut B , penilaian_detil C , penilaian D
				WHERE 1=1				
				 and B.ATRIBUT_ID = C.ATRIBUT_ID 
				 AND C.penilaian_id = D.penilaian_id  
				 and D.PENILAIAN_ID = ".$penilaianId."
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
	
	function selectByParamsPersonalIkkJpm($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="GROUP BY X.KODE_UNKER")
	{
		$str = "
				SELECT
					COALESCE(X.NILAI_IKK,0) NILAI_IKK, COALESCE(ROUND(100 * X.NILAI_IKK / COUNT(1),2),0) NILAI_IKK_PERSEN
					, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END - COALESCE(C.GAP,0)),2)) NILAI_JPM
					, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END - COALESCE(C.GAP,0),0),2)) / COUNT(1),2) NILAI_JPM_PERSEN
					, X.KODE_UNKER
				FROM  atribut B 
				INNER JOIN penilaian_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
				INNER JOIN penilaian D ON C.PENILAIAN_ID = D.PENILAIAN_ID
				INNER JOIN 
				(
					SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
					FROM ".$this->db.".pegawai A
				) AA ON AA.ID = D.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT
						SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END - COALESCE(C.GAP,0),0),2)) NILAI_IKK
						, D.PENILAIAN_ID, B.ASPEK_ID, D.SATKER_TES_ID KODE_UNKER
					FROM  atribut B , penilaian_detil C , penilaian D
					WHERE 1=1				
					AND B.ATRIBUT_ID = C.ATRIBUT_ID 
					AND C.PENILAIAN_ID = D.PENILAIAN_ID
					GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, D.SATKER_TES_ID
				) X ON X.PENILAIAN_ID = D.PENILAIAN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
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
	
	function selectByParamsPersonalIkkJpmBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="GROUP BY A.KODE_UNKER")
	{
		$str = "
				SELECT
					SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
					, ROUND(100 * SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / X.JUMLAH_PERSONAL_PERPENILAIAN,2) NILAI_IKK_PERSEN
					, X.NILAI_JPM, ROUND(100 * X.NILAI_JPM / X.JUMLAH_PERSONAL_PERPENILAIAN,2) NILAI_JPM_PERSEN
					, A.KODE_UNKER
				FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A
				,
				(
					SELECT
						SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
						,COUNT(1) JUMLAH_PERSONAL_PERPENILAIAN
						, D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
					FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A  
					WHERE 1=1				
					AND B.ATRIBUT_ID = C.ATRIBUT_ID 
					AND C.PENILAIAN_ID = D.PENILAIAN_ID
					AND D.SATKER_TES_ID = A.KODE_UNKER
					GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
				) X
				WHERE 1=1				
				and B.ATRIBUT_ID = C.ATRIBUT_ID 
				AND C.PENILAIAN_ID = D.PENILAIAN_ID
				AND D.SATKER_TES_ID = A.KODE_UNKER
				AND X.PENILAIAN_ID = D.PENILAIAN_ID
				AND X.ASPEK_ID = D.ASPEK_ID
				AND X.KODE_UNKER = D.SATKER_TES_ID
				AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
				AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
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
	
	function selectByParamsSpiderPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.NAMA, B.NILAI,   B.PENILAIAN_ID
				, B1.NILAI_STANDAR
				, (B.NILAI + COALESCE(B.GAP,0)) NILAI_STANDAR_old
				FROM atribut A
				LEFT JOIN penilaian_detil B ON A.ATRIBUT_ID = B.ATRIBUT_ID
				INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
				INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
				WHERE A.ATRIBUT_ID_PARENT NOT IN ('0') AND B.PENILAIAN_ID IS NOT NULL
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
	
	function selectByParamsSpiderPenilaian_temp($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.NAMA, B.NILAI, B.NILAI_STANDAR, B.PENILAIAN_ID
				FROM atribut A
				LEFT JOIN
				(
					SELECT A.ATRIBUT_ID_PARENT ATRIBUT_ID, B.PENILAIAN_ID
					, ROUND(SUM(CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END) / COUNT(1),2) NILAI
					, ROUND(SUM(CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END - COALESCE(B.GAP,0)) / COUNT(1),2) NILAI_STANDAR
					FROM atribut A
					LEFT JOIN penilaian_detil B ON A.ATRIBUT_ID = B.ATRIBUT_ID
					LEFT JOIN penilaian C ON C.PENILAIAN_ID = B.PENILAIAN_ID
					WHERE A.ATRIBUT_ID_PARENT != 0
					GROUP BY A.ATRIBUT_ID_PARENT, B.PENILAIAN_ID
				) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
				WHERE 1=1 AND B.PENILAIAN_ID IS NOT NULL
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
	
	function selectByParamsSpiderPenilaianBak1($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.NAMA, B.NILAI, B.NILAI_STANDAR, B.PENILAIAN_ID
				FROM atribut A
				LEFT JOIN
				(
					SELECT A.ATRIBUT_ID_PARENT ATRIBUT_ID, B.PENILAIAN_ID, 
					ROUND(CASE WHEN C.ESELON = 4 THEN SUM(A.NILAI_ES4) WHEN C.ESELON = 3 THEN SUM(A.NILAI_ES3) WHEN C.ESELON = 2 THEN SUM(A.NILAI_ES2) ELSE SUM(A.NILAI_STANDAR) END / COUNT(1),2) - ROUND(SUM(B.GAP) / COUNT(1),2) NILAI,
					ROUND(CASE WHEN C.ESELON = 4 THEN SUM(A.NILAI_ES4) WHEN C.ESELON = 3 THEN SUM(A.NILAI_ES3) WHEN C.ESELON = 2 THEN SUM(A.NILAI_ES2) ELSE SUM(A.NILAI_STANDAR) END / COUNT(1),2) NILAI_STANDAR
					FROM atribut A
					LEFT JOIN penilaian_detil B ON A.ATRIBUT_ID = B.ATRIBUT_ID
					LEFT JOIN penilaian C ON C.PENILAIAN_ID = B.PENILAIAN_ID
					WHERE A.ATRIBUT_ID_PARENT != 0
					GROUP BY A.ATRIBUT_ID_PARENT, B.PENILAIAN_ID
				) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
				WHERE 1=1 AND B.PENILAIAN_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsSpiderPenilaianBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.NAMA, B.NILAI, B.NILAI_STANDAR, B.PENILAIAN_ID
				FROM atribut A
				LEFT JOIN
				(
					SELECT A.ATRIBUT_ID_PARENT ATRIBUT_ID, B.PENILAIAN_ID, ROUND(SUM(A.NILAI_STANDAR) / COUNT(1),2) - ROUND(SUM(B.GAP) / COUNT(1),2) NILAI,
					ROUND(SUM(A.NILAI_STANDAR) / COUNT(1),2) NILAI_STANDAR
					FROM atribut A
					LEFT JOIN penilaian_detil B ON A.ATRIBUT_ID = B.ATRIBUT_ID
					WHERE A.ATRIBUT_ID_PARENT != 0
					GROUP BY A.ATRIBUT_ID_PARENT, B.PENILAIAN_ID
				) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
				WHERE 1=1 AND B.PENILAIAN_ID IS NOT NULL
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT
					B.NAMA, B.NILAI_STANDAR, B.BOBOT, B.ATRIBUT_ID, B.ATRIBUT_ID_PARENT, SUBSTR(B.ATRIBUT_ID, 1, 2) ATRIBUT_GROUP,
					C.PENILAIAN_DETIL_ID, C.PENILAIAN_ID, CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END NILAI,
					CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(B.NILAI_STANDAR,0) ELSE C.GAP END GAP
				FROM jabatan_atribut A
				LEFT JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID
				LEFT JOIN penilaian_detil C ON C.ATRIBUT_ID = B.ATRIBUT_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParamsNilaiIkk($paramsArray=array(),$statement="", $groupBy="GROUP BY C.PENILAIAN_ID")
	{
		$str = "
		SELECT
			SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) AS ROWCOUNT
		FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A  
		WHERE 1=1				
		and B.ATRIBUT_ID = C.ATRIBUT_ID 
		AND C.penilaian_id = D.penilaian_id  
		AND D.satker_tes_id = A.kode_unker
		AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
		AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
		"; 
		
		//and D.PENILAIAN_ID = 852 AND B.ASPEK_ID = 2 AND A.KODE_UNKER = '0802000000'
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$groupBy;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsJumlahNilai($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COALESCE(SUM(NILAI),0) AS ROWCOUNT 
		FROM penilaian_detil A
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM penilaian_detil A
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
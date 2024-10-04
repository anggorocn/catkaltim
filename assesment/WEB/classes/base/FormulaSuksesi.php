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

  class FormulaSuksesi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulaSuksesi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULA_ID", $this->getNextId("FORMULA_ID","formula_suksesi")); 

		$str = "INSERT INTO formula_suksesi (
				   FORMULA_ID, FORMULA, TAHUN, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE, TIPE_FORMULA) 
				VALUES (
				  ".$this->getField("FORMULA_ID").",
				  '".$this->getField("FORMULA")."',
				  ".$this->getField("TAHUN").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("TIPE_FORMULA")."'
				)"; 
		$this->id= $this->getField("FORMULA_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		/*$streselon= "UPDATE formula_eselon SET
				  TAHUN= '".$this->getField("TAHUN")."'
				WHERE FORMULA_ID = ".$this->getField("FORMULA_ID")."
				"; 
		$this->query = $streselon;
		$this->execQuery($streselon);*/
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE formula_suksesi SET
				  FORMULA= '".$this->getField("FORMULA")."',
				  TIPE_FORMULA= '".$this->getField("TIPE_FORMULA")."',
				  TAHUN= ".$this->getField("TAHUN").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE FORMULA_ID = ".$this->getField("FORMULA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM formula_suksesi
                WHERE 
                  FORMULA_ID = ".$this->getField("FORMULA_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_ID ASC")
	{
		$str = "SELECT FORMULA_ID, FORMULA, TAHUN, KETERANGAN, TIPE_FORMULA, 
				CASE 
				WHEN TIPE_FORMULA = '1' THEN 'Tujuan Pengisian' 
				WHEN TIPE_FORMULA = '2' THEN 'Tujuan Pemetaan'
				else 
					'-'
				END TIPE 
				FROM formula_suksesi A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","FORMULA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParamsPegawaiJadwalFormula($statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.ujian_pegawai_daftar WHERE 1=1 ".$statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM formula_suksesi WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsMonitoringUnsurFormula($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqid, $sOrder="")
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.LAST_JABATAN JABATAN_NAMA, D.NAMA SATKER, UNS.NILAI
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		INNER JOIN
		(
			SELECT A.FORMULA_UNSUR_ID, P.PEGAWAI_ID, SUM(PEMBULATAN(((A.UNSUR_BOBOT / A1.UNSUR_BOBOT) * P.UNSUR_BOBOT),2)) NILAI
			FROM
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, SUBSTRING(UNSUR_ID,1,2) UNSUR_PARENT_ID
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 4 AND UNSUR_BOBOT IS NOT NULL
			) A
			INNER JOIN
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 2 AND UNSUR_BOBOT IS NOT NULL
			) A1 ON A.FORMULA_UNSUR_ID = A1.FORMULA_UNSUR_ID AND A.UNSUR_PARENT_ID = A1.UNSUR_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, UNSUR_ID, COALESCE(UNSUR_BOBOT,0) UNSUR_BOBOT
				FROM formula_unsur_pegawai_bobot
			) P ON A.UNSUR_ID = P.UNSUR_ID
			WHERE 1=1 AND A.FORMULA_UNSUR_ID = ".$reqid."
			GROUP BY A.FORMULA_UNSUR_ID, P.PEGAWAI_ID
		) UNS ON UNS.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringUnsurFormula($paramsArray=array(), $statement='', $reqid)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		INNER JOIN
		(
			SELECT A.FORMULA_UNSUR_ID, P.PEGAWAI_ID, SUM(PEMBULATAN(((A.UNSUR_BOBOT / A1.UNSUR_BOBOT) * P.UNSUR_BOBOT),2)) NILAI
			FROM
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, SUBSTRING(UNSUR_ID,1,2) UNSUR_PARENT_ID
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 4 AND UNSUR_BOBOT IS NOT NULL
			) A
			INNER JOIN
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 2 AND UNSUR_BOBOT IS NOT NULL
			) A1 ON A.FORMULA_UNSUR_ID = A1.FORMULA_UNSUR_ID AND A.UNSUR_PARENT_ID = A1.UNSUR_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, UNSUR_ID, COALESCE(UNSUR_BOBOT,0) UNSUR_BOBOT
				FROM formula_unsur_pegawai_bobot
			) P ON A.UNSUR_ID = P.UNSUR_ID
			WHERE 1=1 AND A.FORMULA_UNSUR_ID = ".$reqid."
			GROUP BY A.FORMULA_UNSUR_ID, P.PEGAWAI_ID
		) UNS ON UNS.PEGAWAI_ID = A.PEGAWAI_ID
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

    function selectByParamsUnsurFormula($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.FORMULA_UNSUR_ID, A.NAMA, A.UNSUR_ID
		FROM
		(
			SELECT A.FORMULA_UNSUR_ID, X.NAMA, A.UNSUR_PARENT_ID UNSUR_ID
			FROM unsur_penilaian X
			INNER JOIN
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, SUBSTRING(UNSUR_ID,1,2) UNSUR_PARENT_ID
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 4 AND UNSUR_BOBOT IS NOT NULL
			) A ON A.UNSUR_PARENT_ID = X.UNSUR_ID
			INNER JOIN
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 2 AND UNSUR_BOBOT IS NOT NULL
			) A1 ON A.FORMULA_UNSUR_ID = A1.FORMULA_UNSUR_ID AND A.UNSUR_PARENT_ID = A1.UNSUR_ID
			WHERE 1=1
			GROUP BY A.FORMULA_UNSUR_ID, X.NAMA, A.UNSUR_PARENT_ID
			UNION ALL
			SELECT A.FORMULA_UNSUR_ID, X.NAMA, A.UNSUR_PARENT_ID UNSUR_ID
			FROM UNSUR_PENILAIAN X
			INNER JOIN
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, SUBSTRING(UNSUR_ID,1,2) UNSUR_PARENT_ID
				FROM formula_unsur_bobot
				WHERE 1=1
			) A ON A.UNSUR_PARENT_ID = X.UNSUR_ID
			WHERE 1=1
			AND EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT ID_PARENT, PERMENT_PARENT
					FROM
					(
						SELECT SUBSTRING(UNSUR_ID,1,2) ID_PARENT, PERMEN_ID PERMENT_PARENT, COUNT(1) JUMLAH
						FROM UNSUR_PENILAIAN A
						WHERE 1=1
						GROUP BY SUBSTRING(UNSUR_ID,1,2), PERMEN_ID
					) XX WHERE JUMLAH = 1
				) XX WHERE X.UNSUR_ID = ID_PARENT AND X.PERMEN_ID = PERMENT_PARENT
			)
		) A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsAmbilNilaiUnsurPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.FORMULA_UNSUR_ID, A.PEGAWAI_ID, A.UNSUR_ID, A.NILAI
		FROM
		(
			SELECT A.FORMULA_UNSUR_ID, P.PEGAWAI_ID, A.UNSUR_PARENT_ID UNSUR_ID
			, SUM(PEMBULATAN((((A.UNSUR_BOBOT / A1.UNSUR_BOBOT) * P.UNSUR_BOBOT) * PERSEN_BOBOT),2)) NILAI
			FROM
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, SUBSTRING(UNSUR_ID,1,2) UNSUR_PARENT_ID
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 4 AND UNSUR_BOBOT IS NOT NULL
			) A
			INNER JOIN
			(
				SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, BOBOT / 100 PERSEN_BOBOT
				FROM formula_unsur_bobot
				WHERE 1=1 AND LENGTH(UNSUR_ID) = 2 AND UNSUR_BOBOT IS NOT NULL
			) A1 ON A.FORMULA_UNSUR_ID = A1.FORMULA_UNSUR_ID AND A.UNSUR_PARENT_ID = A1.UNSUR_ID
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, UNSUR_ID, COALESCE(UNSUR_BOBOT,0) UNSUR_BOBOT
				FROM formula_unsur_pegawai_bobot
			) P ON A.UNSUR_ID = P.UNSUR_ID
			WHERE 1=1
			GROUP BY A.FORMULA_UNSUR_ID, P.PEGAWAI_ID, A.UNSUR_PARENT_ID
		) A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsAmbilNilaiJpmPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		PEGAWAI_ID, round((SUM(JPM))*100,2) JPM
		FROM penilaian A
		WHERE EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT PEGAWAI_ID, MAX(TANGGAL_TES) TANGGAL_TES
				FROM penilaian A 
				GROUP BY PEGAWAI_ID
			) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND A.TANGGAL_TES = X.TANGGAL_TES
		)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY PEGAWAI_ID ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqid, $sOrder="")
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.SATKER_ID, A.LAST_JABATAN JABATAN_NAMA, D.NAMA SATKER
			, DT.NILAI
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		LEFT JOIN
		(
			SELECT PEGAWAI_ID, SUM(NILAI) NILAI
			FROM
			(
				SELECT
				PEGAWAI_ID, round((SUM(JPM))*100,2) NILAI
				FROM penilaian A
				WHERE EXISTS
				(
					SELECT 1
					FROM
					(
						SELECT PEGAWAI_ID, MAX(TANGGAL_TES) TANGGAL_TES
						FROM penilaian A 
						GROUP BY PEGAWAI_ID
					) X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND A.TANGGAL_TES = X.TANGGAL_TES
				)
				GROUP BY PEGAWAI_ID 
				UNION ALL
				SELECT A.PEGAWAI_ID, A.NILAI
				FROM
				(
					SELECT A.FORMULA_UNSUR_ID, P.PEGAWAI_ID, A.UNSUR_PARENT_ID UNSUR_ID
					, SUM(PEMBULATAN((((A.UNSUR_BOBOT / A1.UNSUR_BOBOT) * P.UNSUR_BOBOT) * PERSEN_BOBOT),2)) NILAI
					FROM
					(
						SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, SUBSTRING(UNSUR_ID,1,2) UNSUR_PARENT_ID
						FROM formula_unsur_bobot
						WHERE 1=1 AND LENGTH(UNSUR_ID) = 4 AND UNSUR_BOBOT IS NOT NULL
					) A
					INNER JOIN
					(
						SELECT FORMULA_UNSUR_ID, UNSUR_ID, UNSUR_BOBOT, BOBOT / 100 PERSEN_BOBOT
						FROM formula_unsur_bobot
						WHERE 1=1 AND LENGTH(UNSUR_ID) = 2 AND UNSUR_BOBOT IS NOT NULL
					) A1 ON A.FORMULA_UNSUR_ID = A1.FORMULA_UNSUR_ID AND A.UNSUR_PARENT_ID = A1.UNSUR_ID
					LEFT JOIN
					(
						SELECT PEGAWAI_ID, UNSUR_ID, COALESCE(UNSUR_BOBOT,0) UNSUR_BOBOT
						FROM formula_unsur_pegawai_bobot
					) P ON A.UNSUR_ID = P.UNSUR_ID
					WHERE 1=1
					GROUP BY A.FORMULA_UNSUR_ID, P.PEGAWAI_ID, A.UNSUR_PARENT_ID
				) A WHERE 1=1
				AND A.FORMULA_UNSUR_ID = ".$reqid."
			) A
			GROUP BY PEGAWAI_ID
		) DT ON A.PEGAWAI_ID = DT.PEGAWAI_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoringPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM simpeg.pegawai A
		INNER JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
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

    function selectByParamsGetBobot($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY FORMULA_UNSUR_ID, UNSUR_ID, PERMEN_ID")
	{
		$str = "
		SELECT
		FORMULA_UNSUR_ID, UNSUR_ID, PERMEN_ID, SUM(UNSUR_BOBOT) UNSUR_BOBOT, SUM(BOBOT) / 100 BOBOT
		FROM formula_unsur_bobot A
		WHERE LENGTH(UNSUR_ID) = 2 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY FORMULA_UNSUR_ID, UNSUR_ID, PERMEN_ID ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
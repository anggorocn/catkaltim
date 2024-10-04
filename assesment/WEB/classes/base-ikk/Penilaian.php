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

  class Penilaian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Penilaian()
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
		$this->setField("PENILAIAN_ID", $this->getNextId("PENILAIAN_ID","penilaian"));
		
		$str = "INSERT INTO penilaian (
				   PENILAIAN_ID, JADWAL_TES_ID, PEGAWAI_ID, TANGGAL_TES, JABATAN_TES_ID, SATKER_TES_ID, ASPEK_ID, ESELON, NAMA_ASESI, METODE) 
				VALUES (
				  ".$this->getField("PENILAIAN_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TANGGAL_TES").",
				  '".$this->getField("JABATAN_TES_ID")."',
				  '".$this->getField("SATKER_TES_ID")."',
				  ".$this->getField("ASPEK_ID").",
				  ".$this->getField("ESELON").",
				  '".$this->getField("NAMA_ASESI")."',
				  '".$this->getField("METODE")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("PENILAIAN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian
				SET
				   JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				   PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				   TANGGAL_TES= ".$this->getField("TANGGAL_TES").",
				   JABATAN_TES_ID= '".$this->getField("JABATAN_TES_ID")."',
				   SATKER_TES_ID= '".$this->getField("SATKER_TES_ID")."',
				   ASPEK_ID= ".$this->getField("ASPEK_ID").",
				   NAMA_ASESI= '".$this->getField("NAMA_ASESI")."',
				   METODE= '".$this->getField("METODE")."'
				WHERE PENILAIAN_ID= ".$this->getField("PENILAIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function insertluar()
	{
		$this->setField("PENILAIAN_ID", $this->getNextId("PENILAIAN_ID","penilaian"));
		
		$str = "
		INSERT INTO penilaian (
			PENILAIAN_ID, PEGAWAI_ID, SATKER_TES_ID, JADWAL_TES_ID, TANGGAL_TES, JABATAN_TES_ID, ASPEK_ID, STATUS_PENILAIAN
			, SATUAN_KERJA_INFO, LOKASI, JPM, IKK
		) 
		VALUES 
		(
			".$this->getField("PENILAIAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			'luar', -1,
			".$this->getField("TANGGAL_TES").",
			'".$this->getField("JABATAN_TES_ID")."',
			".$this->getField("ASPEK_ID").",
			1,
			'".$this->getField("SATUAN_KERJA_INFO")."',
			'".$this->getField("LOKASI")."',
			".$this->getField("JPM").",
			".$this->getField("IKK")."
		)
		";
				
		$this->query = $str;
		$this->id = $this->getField("PENILAIAN_ID");
		return $this->execQuery($str);
    }
	
    function updateluar()
	{
		$str = "
		UPDATE penilaian
		SET
			TANGGAL_TES= ".$this->getField("TANGGAL_TES").",
			JABATAN_TES_ID= '".$this->getField("JABATAN_TES_ID")."',
			SATUAN_KERJA_INFO= '".$this->getField("SATUAN_KERJA_INFO")."',
			LOKASI= '".$this->getField("LOKASI")."',
			JPM= ".$this->getField("JPM").",
			IKK= ".$this->getField("IKK")."
		WHERE PENILAIAN_ID= ".$this->getField("PENILAIAN_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE penilaian
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  PENILAIAN_ID = ".$this->getField("PENILAIAN_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
		$str1= "DELETE FROM penilaian_detil
                WHERE 
                  PENILAIAN_ID = ".$this->getField("PENILAIAN_ID").""; 
		$this->query = $str1;
        $this->execQuery($str1);
				  
        $str = "DELETE FROM penilaian
                WHERE 
                  PENILAIAN_ID = ".$this->getField("PENILAIAN_ID").""; 
				  
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
	function selectByParamsTahunPenilaian($statement="")
	{
		$str = "
		SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY') AS TAHUN
		FROM penilaian A
		WHERE 1=1 ";
		
		$str .= $statement." GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY') ORDER BY TO_CHAR(A.TANGGAL_TES, 'YYYY') DESC";
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1);
    }
	
	function selectByParamsPersonalJkmIkk($statement="")
	{
		$str = "
		SELECT
			COALESCE(X.NILAI_IKK,0) NILAI_IKK, COALESCE(ROUND(100 * X.NILAI_IKK / COUNT(1),2),0) NILAI_IKK_PERSEN
			, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
			, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) NILAI_JPM_PERSEN
			, D.PEGAWAI_ID, AA.NAMA, AA.NAMA_JAB_STRUKTURAL
		FROM  atribut B 
		INNER JOIN penilaian_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
		INNER JOIN penilaian D ON C.PENILAIAN_ID = D.PENILAIAN_ID
		INNER JOIN 
		(
			SELECT 
				A.ID, A.NAME NAMA, A.POSITION NAMA_JAB_STRUKTURAL, GetAncestry((CASE A.SUBBAG_ID WHEN '0' THEN (CASE A.SUBDIT_ID WHEN '0' THEN (CASE A.DITJEN_ID WHEN '0' THEN (CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END) ELSE A.DITJEN_ID END) ELSE A.SUBDIT_ID END) ELSE A.SUBBAG_ID END) ) KODE_UNKER
			FROM ".$this->db.".user A
		) AA ON AA.ID = D.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
				SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
				, D.PENILAIAN_ID, B.ASPEK_ID, D.SATKER_TES_ID KODE_UNKER
			FROM  atribut B, penilaian_detil C , penilaian D
			WHERE 1=1				
			AND B.ATRIBUT_ID = C.ATRIBUT_ID 
			AND C.PENILAIAN_ID = D.PENILAIAN_ID
			AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
			AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
			GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, D.SATKER_TES_ID, SUBSTRING_INDEX(SUBSTRING_INDEX(D.SATKER_TES_ID, '-', 1), '-', -1)
		) X ON X.PENILAIAN_ID = D.PENILAIAN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
		WHERE 1=1
  		";
		
		$str .= $statement." 
		GROUP BY D.PEGAWAI_ID, AA.NAMA, AA.NAMA_JAB_STRUKTURAL
		ORDER BY ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) DESC
		";
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, A.PEGAWAI_ID, A.TANGGAL_TES, A.JABATAN_TES_ID, A.JABATAN_TES_ID JABATAN_TES, A.ESELON,
				A.SATKER_TES_ID,
				B.NAMA SATKER_TES,
				A.ASPEK_ID, CASE WHEN A.ASPEK_ID = '1' THEN 'Aspek Potensi' WHEN A.ASPEK_ID = '2' THEN 'Aspek Kompetensi' ELSE '' END ASPEK_NAMA
				, NAMA_ASESI, METODE, A.JADWAL_TES_ID
				, A.SATUAN_KERJA_INFO, A.LOKASI, A.JPM, A.IKK
				FROM penilaian A
				LEFT JOIN ".$this->db.".satker B ON A.SATKER_TES_ID = B.SATKER_ID
				WHERE 1=1
				"; 
		//INNER JOIN ".$this->db.".JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsLuar($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		A.PEGAWAI_ID, A.TANGGAL_TES, A.JABATAN_TES_ID JABATAN_TES
		, A.SATUAN_KERJA_INFO, A.LOKASI, MAX(A.PENILAIAN_ID) PENILAIAN_ID
		FROM penilaian A
		WHERE 1=1 "; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY  A.PEGAWAI_ID, A.TANGGAL_TES, A.JABATAN_TES_ID, A.SATUAN_KERJA_INFO, A.LOKASI ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPenilaianAtributPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.ASPEK_ID DESC, A.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT B.PENILAIAN_ID, A.ATRIBUT_ID, A.NAMA ATRIBUT_NAMA, B.ASPEK_NAMA, B.ASPEK_ID, B.NILAI_STANDAR
				FROM atribut A
				INNER JOIN
				(
					SELECT A.PEGAWAI_ID, A.PENILAIAN_ID, A.ATRIBUT_ID
					, TO_CHAR(B.TANGGAL_TES, 'YYYY') TAHUN, B.ASPEK_ID
					, CASE WHEN B.ASPEK_ID = '1' THEN 'Aspek Psikologi' WHEN B.ASPEK_ID = '2' THEN 'Kompetensi Teknis' ELSE '' END ASPEK_NAMA
					, B1.NILAI_STANDAR
					FROM penilaian_detil A
					INNER JOIN penilaian B ON A.PENILAIAN_ID = B.PENILAIAN_ID
					INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
					WHERE 1=1
					GROUP BY A.PEGAWAI_ID, A.PENILAIAN_ID, A.ATRIBUT_ID, TO_CHAR(B.TANGGAL_TES, 'YYYY'), B.ASPEK_ID, B1.NILAI_STANDAR
				) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
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
	
	function selectByParamsPenilaianAtributPegawaiBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.ASPEK_ID DESC, A.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT B.PENILAIAN_ID, A.ATRIBUT_ID, A.NAMA ATRIBUT_NAMA, B.ASPEK_NAMA, B.ASPEK_ID, B.NILAI_STANDAR
				FROM atribut A
				INNER JOIN
				(
					SELECT A.PEGAWAI_ID, A.PENILAIAN_ID, SUBSTR(A.ATRIBUT_ID, 1, 2) ATRIBUT_ID
					, TO_CHAR(B.TANGGAL_TES, 'YYYY') TAHUN, B.ASPEK_ID
					, CASE WHEN B.ASPEK_ID = '1' THEN 'Aspek Psikologi' WHEN B.ASPEK_ID = '2' THEN 'Kompetensi Teknis' ELSE '' END ASPEK_NAMA
					, B1.NILAI_STANDAR
					FROM penilaian_detil A
					INNER JOIN penilaian B ON A.PENILAIAN_ID = B.PENILAIAN_ID
					INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
					WHERE 1=1
					GROUP BY A.PEGAWAI_ID, A.PENILAIAN_ID, SUBSTR(A.ATRIBUT_ID, 1, 2), TO_CHAR(B.TANGGAL_TES, 'YYYY'), B.ASPEK_ID, B1.NILAI_STANDAR
				) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
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
	
	function selectByParamsPenilaianAtributPegawaiHasil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY a.ASPEK_ID DESC, A.ATRIBUT_ID ASC")
	{
		$str = "
					SELECT b.PEGAWAI_ID, b.PENILAIAN_ID,  A.ATRIBUT_ID  
					, TO_CHAR(P.TANGGAL_TES, 'YYYY') TAHUN, a.ASPEK_ID, a.NAMA ATRIBUT_NAMA
					, CASE WHEN a.ASPEK_ID = '1' THEN 'Aspek Psikologi' WHEN a.ASPEK_ID = '2' THEN 'Kompetensi Managerial' ELSE '' END ASPEK_group
					, B1.NILAI_STANDAR,  b.NILAI, b.GAP,
					CASE 
					WHEN ROUND(B.GAP,2) > 0 THEN 'Sangat Memenuhi / Above Expection'
					WHEN ROUND(B.GAP,2) < 0 THEN 'Kurang Memenuhi / Below Requirement'
					ELSE 'Memenuhi / Meet Requirement' END KETERANGAN
				FROM atribut A
					LEFT JOIN penilaian_detil B ON A.ATRIBUT_ID = B.ATRIBUT_ID 
					inner JOIN penilaian P ON p.penilaian_id = B.penilaian_id 
					INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
					INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID
					INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
					WHERE A.ATRIBUT_ID_PARENT <> 0  AND B.PENILAIAN_ID IS NOT NULL 
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
	
	
	function selectByParamsPenilaianAtributPegawaiHasilBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.ASPEK_ID DESC, A.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT B.PENILAIAN_ID, A.ATRIBUT_ID, A.NAMA ATRIBUT_NAMA, B.ASPEK_NAMA, B.ASPEK_ID
				, ROUND(B.NILAI_STANDAR,2) NILAI_STANDAR, ROUND(B.NILAI,2) NILAI, ROUND(B.GAP,2) GAP
				, ROUND(B.NILAI_STANDAR,2) NILAI_STANDAR, ROUND(B.NILAI,2) NILAI, ROUND(B.GAP,2) GAP
				, CASE 
				WHEN ROUND(B.GAP,2) > 0 THEN 'Sangat Memenuhi / Above Expection'
				WHEN ROUND(B.GAP,2) < 0 THEN 'Kurang Memenuhi / Below Requirement'
				ELSE 'Memenuhi / Meet Requirement' END KETERANGAN
				FROM atribut A
				INNER JOIN
				(
					SELECT A.PEGAWAI_ID, A.PENILAIAN_ID, SUBSTR(A.ATRIBUT_ID, 1, 2) ATRIBUT_ID
					, TO_CHAR(B.TANGGAL_TES, 'YYYY') TAHUN, B.ASPEK_ID
					, CASE WHEN B.ASPEK_ID = '1' THEN 'Aspek Psikologi' WHEN B.ASPEK_ID = '2' THEN 'Kompetensi Managerial' ELSE '' END ASPEK_NAMA
					, B1.NILAI_STANDAR, SUM(A.NILAI) / COUNT(SUBSTR(A.ATRIBUT_ID, 1, 2)) NILAI, SUM(A.GAP) / COUNT(SUBSTR(A.ATRIBUT_ID, 1, 2)) GAP
					FROM penilaian_detil A
					INNER JOIN penilaian B ON A.PENILAIAN_ID = B.PENILAIAN_ID
					INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = A.FORMULA_ATRIBUT_ID
					WHERE 1=1
					GROUP BY A.PEGAWAI_ID, A.PENILAIAN_ID, SUBSTR(A.ATRIBUT_ID, 1, 2), TO_CHAR(B.TANGGAL_TES, 'YYYY'), B.ASPEK_ID, B1.NILAI_STANDAR
				) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
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
	
	function selectByParamsPenilaianTools($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY F.ATRIBUT_ID")
	{
		$str = "
				 SELECT 
				 F.NAMA ATRIBUT_NAMA, C.JADWAL_ACARA_ID
				 , info_asesmen_kode(B.JADWAL_TES_ID, A.FORMULA_ESELON_ID, A.ATRIBUT_ID, ' ') KODE
				 FROM penilaian A1
				 INNER JOIN penilaian_detil A ON A1.PENILAIAN_ID = A.PENILAIAN_ID
				 INNER JOIN jadwal_tes B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
				 INNER JOIN jadwal_acara C ON C.JADWAL_TES_ID = B.JADWAL_TES_ID AND C.PENGGALIAN_ID = A.PENGGALIAN_ID  
				 INNER JOIN atribut F ON A.ATRIBUT_ID = F.ATRIBUT_ID 
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
	
	function selectByParamsPenilaianGroupTools($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="GROUP BY G.KODE, G.NAMA ORDER BY G.KODE, G.NAMA")
	{
		$str = "
				SELECT 
					G.KODE, G.NAMA
				FROM penilaian A1 
				INNER JOIN penilaian_detil A ON A1.PENILAIAN_ID = A.PENILAIAN_ID 
				INNER JOIN jadwal_tes B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID 
				INNER JOIN jadwal_acara C ON C.JADWAL_TES_ID = B.JADWAL_TES_ID AND C.PENGGALIAN_ID = A.PENGGALIAN_ID 
				INNER JOIN jadwal_asesor D ON D.JADWAL_ACARA_ID = C.JADWAL_ACARA_ID 
				INNER JOIN jadwal_pegawai E ON E.JADWAL_ASESOR_ID = D.JADWAL_ASESOR_ID
				INNER JOIN atribut F ON A.ATRIBUT_ID = F.ATRIBUT_ID 
				INNER JOIN penggalian G ON C.PENGGALIAN_ID = G.PENGGALIAN_ID 
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
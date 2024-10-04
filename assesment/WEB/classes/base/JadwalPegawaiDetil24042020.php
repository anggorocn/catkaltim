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

  class JadwalPegawaiDetil extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalPegawaiDetil()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity();  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_PEGAWAI_DETIL_ID", $this->getNextId("JADWAL_PEGAWAI_DETIL_ID","jadwal_pegawai_detil")); 

		$str = "
		INSERT INTO jadwal_pegawai_detil
		(
			JADWAL_PEGAWAI_DETIL_ID, JADWAL_TES_ID, PENGGALIAN_ID, LEVEL_ID, 
			INDIKATOR_ID, JADWAL_PEGAWAI_ID, JADWAL_ASESOR_ID, ATRIBUT_ID, PEGAWAI_ID, ASESOR_ID, FORM_PERMEN_ID,
			LAST_CREATE_USER, LAST_CREATE_DATE 
		)
		VALUES (
			".$this->getField("JADWAL_PEGAWAI_DETIL_ID").",
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("PENGGALIAN_ID").",
			".$this->getField("LEVEL_ID").",
			".$this->getField("INDIKATOR_ID").",
			".$this->getField("JADWAL_PEGAWAI_ID").",
			".$this->getField("JADWAL_ASESOR_ID").",
			'".$this->getField("ATRIBUT_ID")."',
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("ASESOR_ID").",
			".$this->getField("FORM_PERMEN_ID").",
			'".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE")."
		)"; 
		$this->id= $this->getField("JADWAL_PEGAWAI_DETIL_ID");
		$this->query= $str;
		// return $str;;exit();
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function insertdetil()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_PEGAWAI_DETIL_ATRIBUT_ID", $this->getNextId("JADWAL_PEGAWAI_DETIL_ATRIBUT_ID","jadwal_pegawai_detil_atribut")); 

		$str = "
		INSERT INTO jadwal_pegawai_detil_atribut
		(
            JADWAL_PEGAWAI_DETIL_ATRIBUT_ID, JADWAL_TES_ID, PENGGALIAN_ID, 
            JADWAL_PEGAWAI_ID, JADWAL_ASESOR_ID, ATRIBUT_ID, PEGAWAI_ID, 
            ASESOR_ID, FORM_PERMEN_ID, NILAI_STANDAR, NILAI, GAP, CATATAN, 
            LAST_CREATE_USER, LAST_CREATE_DATE 
		)
		VALUES (
			".$this->getField("JADWAL_PEGAWAI_DETIL_ATRIBUT_ID").",
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("PENGGALIAN_ID").",
			".$this->getField("JADWAL_PEGAWAI_ID").",
			".$this->getField("JADWAL_ASESOR_ID").",
			'".$this->getField("ATRIBUT_ID")."',
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("ASESOR_ID").",
			".$this->getField("FORM_PERMEN_ID").",
			".$this->getField("NILAI_STANDAR").",
			".$this->getField("NILAI").",
			".$this->getField("GAP").",
			'".$this->getField("CATATAN")."',
			'".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE")."
		)"; 
		$this->id= $this->getField("JADWAL_PEGAWAI_DETIL_ID");
		$this->query= $str;
		// return $str;;exit();
		//echo $str;exit;
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
		//NILAI= ".$this->getField("NILAI").", KETERANGAN= '".$this->getField("KETERANGAN")."',
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE jadwal_pegawai_detil SET
				  LEVEL_ID= ".$this->getField("LEVEL_ID").",
				  INDIKATOR_ID= ".$this->getField("INDIKATOR_ID").",
				  JADWAL_PEGAWAI_ID= ".$this->getField("JADWAL_PEGAWAI_ID").",
				  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_PEGAWAI_DETIL_ID = ".$this->getField("JADWAL_PEGAWAI_DETIL_ID")."
				"; 
				$this->query = $str;
				//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_pegawai_detil
                WHERE 
                  JADWAL_PEGAWAI_DETIL_ID = ".$this->getField("JADWAL_PEGAWAI_DETIL_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletePenggalianTesPegawai()
	{
        $str = "
        DELETE FROM jadwal_pegawai_detil
        WHERE 
        JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID")."
        AND PENGGALIAN_ID= ".$this->getField("PENGGALIAN_ID")."
        AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
        "; 
				  
		$this->query = $str;
		// echo $str;exit();
        return $this->execQuery($str);
    }

    function deletePenggalianTesAtributPegawai()
	{
        $str = "
        DELETE FROM jadwal_pegawai_detil_atribut
        WHERE 
        JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID")."
        AND PENGGALIAN_ID= ".$this->getField("PENGGALIAN_ID")."
        AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
        "; 
				  
		$this->query = $str;
		// echo $str;exit();
        return $this->execQuery($str);
    }

	function deletePegawai()
	{
        $str = "DELETE FROM jadwal_pegawai_detil
                WHERE 
                  JADWAL_PEGAWAI_ID= ".$this->getField("JADWAL_PEGAWAI_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_PEGAWAI_DETIL_ID ASC")
	{
		$str = "SELECT JADWAL_PEGAWAI_DETIL_ID, JADWAL_ASESOR_ID, PEGAWAI_ID, PENGGALIAN_ID, NILAI, KETERANGAN
				FROM jadwal_pegawai_detil WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
			info_asesmen_metode(A.PEGAWAI_ID, DATE_FORMAT(E.TANGGAL_TES, '%d-%m-%Y'), ', ') METODE
			, E.TANGGAL_TES, A.PEGAWAI_ID, F.NAMA NAMA_PEGAWAI, F.LAST_JABATAN JABATAN_INI_TES, G.NAMA SATUAN_KERJA_INI_TES, I.ASPEK_ID
			, SUBSTR(CAST(F.LAST_ESELON_ID AS CHAR),1,1) ESELON_ID, G.SATKER_ID SATKER_TES_ID
		FROM jadwal_pegawai A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN jadwal_asesor C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
		INNER JOIN asesor D ON C.ASESOR_ID = D.ASESOR_ID
		INNER JOIN jadwal_tes E ON E.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN ".$this->db.".pegawai F ON A.PEGAWAI_ID = F.PEGAWAI_ID
		INNER JOIN ".$this->db.".satker G ON F.SATKER_ID = G.SATKER_ID
		INNER JOIN ".$this->db.".eselon H ON F.LAST_ESELON_ID = H.ESELON_ID
		LEFT JOIN
		(
			SELECT 
			F.ASPEK_ID, JP.PEGAWAI_ID
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
			INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
			INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
			INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
			INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
			INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
			INNER JOIN jadwal_pegawai JP ON JP.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			LEFT JOIN jadwal_pegawai_detil H ON H.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID
			WHERE 1=1
			GROUP BY F.ASPEK_ID, JP.PEGAWAI_ID
		) I ON A.PEGAWAI_ID = I.PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY 
		info_asesmen_metode(A.PEGAWAI_ID, DATE_FORMAT(E.TANGGAL_TES, '%d-%m-%Y'), ', ')
		, E.TANGGAL_TES, A.PEGAWAI_ID, F.NAMA, F.LAST_JABATAN, G.NAMA, I.ASPEK_ID
		, SUBSTR(CAST(F.LAST_ESELON_ID AS CHAR),1,1), G.SATKER_ID ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringAspek1($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
			'' JADWAL_PEGAWAI_DETIL_ID, F.ATRIBUT_ID,
			'' INDIKATOR_ID, '' LEVEL_ID,
			'' PEGAWAI_INDIKATOR_ID, '' PEGAWAI_LEVEL_ID,
			F.NAMA ATRIBUT_NAMA, '' NAMA_INDIKATOR, 1 JUMLAH_LEVEL
			, '' PEGAWAI_KETERANGAN, B1.NAMA NAMA_ASESOR, B.ASESOR_ID
		FROM jadwal_asesor_potensi_pegawai A
		INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
		INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
		WHERE 1=1 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
			H.JADWAL_PEGAWAI_DETIL_ID, F.ATRIBUT_ID,
			G.INDIKATOR_ID, G.LEVEL_ID,
			H.INDIKATOR_ID PEGAWAI_INDIKATOR_ID, H.LEVEL_ID PEGAWAI_LEVEL_ID,
			F.NAMA ATRIBUT_NAMA, G.NAMA_INDIKATOR, G1.JUMLAH_LEVEL
			, H.KETERANGAN PEGAWAI_KETERANGAN, B1.NAMA NAMA_ASESOR, B.ASESOR_ID
		FROM jadwal_pegawai A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
 		INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
		INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
		INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
		INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
		INNER JOIN
		(
			SELECT G.LEVEL_ID, COUNT(E.ATRIBUT_ID) JUMLAH_LEVEL
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
			INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
			INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
			INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
			INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
			INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID AND G.LEVEL_ID = E.LEVEL_ID
			INNER JOIN jadwal_pegawai JP ON JP.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PEGAWAI_ID = JP.PEGAWAI_ID
			LEFT JOIN jadwal_pegawai_detil H ON H.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID
			WHERE 1=1 ".$statement."
			GROUP BY G.LEVEL_ID
		) G1 ON G1.LEVEL_ID = G.LEVEL_ID
		INNER JOIN jadwal_pegawai JP ON JP.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PEGAWAI_ID = JP.PEGAWAI_ID
 		LEFT JOIN jadwal_pegawai_detil H ON H.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID
		WHERE 1=1 
		"; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_pegawai_detil WHERE 1=1 ".$statement;
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY C1.PENGGALIAN_ID, D.FORM_ATRIBUT_ID, G.INDIKATOR_ID")
	{
		$str = "
		SELECT 
			H.JADWAL_PEGAWAI_DETIL_ID, A.JADWAL_PEGAWAI_ID, A.JADWAL_ASESOR_ID
			, F.ATRIBUT_ID, F.ASPEK_ID, C1.PENGGALIAN_ID,
			G.INDIKATOR_ID, G.LEVEL_ID, D.FORM_PERMEN_ID,
			H.INDIKATOR_ID PEGAWAI_INDIKATOR_ID, H.LEVEL_ID PEGAWAI_LEVEL_ID,
			F.NAMA ATRIBUT_NAMA, G.NAMA_INDIKATOR, G1.JUMLAH_LEVEL
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
		WHERE 1=1 ".$statement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= " ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPenilaianAsesor($statement='', $sOrder="ORDER BY A.ASPEK_ID, A.ATRIBUT_ID")
	{
		$str = "
		SELECT
			A.PENILAIAN_ID, A.PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.NAMA, A.ATRIBUT_GROUP
			, A.NILAI_STANDAR, A.NILAI, A.GAP
			, CASE WHEN A.PROSENTASE > 100 THEN 100 ELSE A.PROSENTASE END PROSENTASE
			, STRIP_TAGS(A.BUKTI) BUKTI, STRIP_TAGS(A.CATATAN) CATATAN
			, A.LEVEL, A.LEVEL_KETERANGAN, A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ASPEK_ID
		FROM 
		(
			SELECT A.PENILAIAN_ID, B.PENILAIAN_DETIL_ID, C.ATRIBUT_ID, C.ATRIBUT_ID_PARENT, C.NAMA, C.ATRIBUT_ID_PARENT ATRIBUT_GROUP
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
			UNION ALL
			SELECT B.PENILAIAN_ID, NULL PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.NAMA, A.ATRIBUT_ID ATRIBUT_GROUP
			, NULL NILAI_STANDAR
			, NULL AS LEVEL, '' LEVEL_KETERANGAN
			, NULL NILAI, NULL GAP, '' BUKTI, '' CATATAN
			, B.PROSENTASE, A.PERMEN_ID, B.JADWAL_TES_ID, B.PEGAWAI_ID, B.ASPEK_ID
			FROM atribut A
			LEFT JOIN
			(
			SELECT B.PENILAIAN_ID, SUBSTR(B.ATRIBUT_ID, 1, 2) ATRIBUT_ID, COUNT(1) JUMLAH_PENILAIAN_DETIL
			, ROUND((SUM(B.NILAI) / SUM(B1.NILAI_STANDAR)) * 100,2) PROSENTASE, PERMEN_ID, B2.JADWAL_TES_ID, B2.PEGAWAI_ID, B2.ASPEK_ID
			FROM penilaian_detil B
			INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
			INNER JOIN penilaian B2 ON B.PENILAIAN_ID = B2.PENILAIAN_ID
			WHERE 1=1
			GROUP BY B.PENILAIAN_ID, SUBSTR(B.ATRIBUT_ID, 1, 2), PERMEN_ID, B2.JADWAL_TES_ID, B2.PEGAWAI_ID, B2.ASPEK_ID
			) B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.PERMEN_ID = B.PERMEN_ID
			WHERE 1=1
		) A
		WHERE 1=1
		".$statement;
		// AND A.ASESOR_ID = 25 AND A.JADWAL_TES_ID = 25
		
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str, -1, -1); 
    }

    function updatePenilaian()
	{
		$str = "
		UPDATE penilaian_detil
		SET
			NILAI= ".$this->getField("NILAI").",
			GAP= ".$this->getField("GAP").",
			CATATAN= '".$this->getField("CATATAN")."',
			BUKTI= '".$this->getField("BUKTI")."'
		WHERE PENILAIAN_DETIL_ID= ".$this->getField("PENILAIAN_DETIL_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    } 

    function updatePenilaianText()
	{
		$str = "
		UPDATE penilaian
		SET
			NILAI= ".$this->getField("NILAI").",
			GAP= ".$this->getField("GAP").",
			CATATAN= '".$this->getField("CATATAN")."',
			BUKTI= '".$this->getField("BUKTI")."'
		WHERE PENILAIAN_ID= ".$this->getField("PENILAIAN_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    } 

    function selectByParamsPenilaianPegawaiAtribut($statement='', $sOrder="")
	{
		$str = "
		SELECT
			A.JADWAL_PEGAWAI_DETIL_ATRIBUT_ID, A.JADWAL_TES_ID, A.PENGGALIAN_ID, 
			A.JADWAL_PEGAWAI_ID, A.JADWAL_ASESOR_ID, A.ATRIBUT_ID, A.PEGAWAI_ID, 
			A.ASESOR_ID, A.FORM_PERMEN_ID, A.NILAI_STANDAR, A.NILAI, A.GAP, A.CATATAN, 
			A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE
		FROM jadwal_pegawai_detil_atribut A
		WHERE 1=1
		".$statement;
		// AND A.ASESOR_ID = 25 AND A.JADWAL_TES_ID = 25
		
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str, -1, -1); 
    }

  } 
?>
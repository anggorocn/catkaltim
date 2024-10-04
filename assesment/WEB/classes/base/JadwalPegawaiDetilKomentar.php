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

  class JadwalPegawaiDetilKomentar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JadwalPegawaiDetilKomentar()
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
		$this->setField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID", $this->getNextId("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID","jadwal_pegawai_detil_komentar")); 

		$str = "INSERT INTO jadwal_pegawai_detil_komentar (
				   JADWAL_PEGAWAI_DETIL_KOMENTAR_ID, LEVEL_ID, INDIKATOR_ID, JADWAL_PEGAWAI_ID, JADWAL_TES_ID, KETERANGAN, ATRIBUT_ID, PEGAWAI_ID, ASESOR_ID, ASESOR_KOMENTAR_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID").",
				  ".$this->getField("LEVEL_ID").",
				  ".$this->getField("INDIKATOR_ID").",
				  ".$this->getField("JADWAL_PEGAWAI_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("KETERANGAN").",
				  '".$this->getField("ATRIBUT_ID")."',
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("ASESOR_ID").",
				  ".$this->getField("ASESOR_KOMENTAR_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID");
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
		$str = "UPDATE jadwal_pegawai_detil_komentar SET
				  LEVEL_ID= ".$this->getField("LEVEL_ID").",
				  INDIKATOR_ID= ".$this->getField("INDIKATOR_ID").",
				  JADWAL_PEGAWAI_ID= ".$this->getField("JADWAL_PEGAWAI_ID").",
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  KETERANGAN= ".$this->getField("KETERANGAN").",
				  ATRIBUT_ID= '".$this->getField("ATRIBUT_ID")."',
				  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  ASESOR_ID= ".$this->getField("ASESOR_ID").",
				  ASESOR_KOMENTAR_ID= ".$this->getField("ASESOR_KOMENTAR_ID").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_PEGAWAI_DETIL_KOMENTAR_ID= ".$this->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_pegawai_detil_komentar
                WHERE 
                  JADWAL_PEGAWAI_DETIL_KOMENTAR_ID = ".$this->getField("JADWAL_PEGAWAI_DETIL_KOMENTAR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","LEVEL_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_PEGAWAI_DETIL_KOMENTAR_ID ASC")
	{
		$str = "SELECT JADWAL_PEGAWAI_DETIL_KOMENTAR_ID, A.LEVEL_ID, A.INDIKATOR_ID, A.JADWAL_PEGAWAI_ID, A.JADWAL_TES_ID, A.KETERANGAN, A.ATRIBUT_ID
				, A.PEGAWAI_ID, A.ASESOR_ID, A.ASESOR_KOMENTAR_ID, B.NAMA ASESOR_KOMENTAR_NAMA
				FROM jadwal_pegawai_detil_komentar A 
				INNER JOIN asesor B ON A.ASESOR_KOMENTAR_ID = B.ASESOR_ID
				WHERE 1=1 ";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsAsessmenMeeting($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, A.TANGGAL_TES, A.KETERANGAN
				, P.NAMA PEGAWAI_NAMA, P.NIP_BARU PEGAWAI_NIP, PN.KODE PEGAWAI_GOL, P.LAST_JABATAN PEGAWAI_JABATAN
				FROM
				(
					SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, B.TANGGAL_TES, B.KETERANGAN
					FROM jadwal_pegawai_detil_komentar A
					INNER JOIN jadwal_tes B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, B.TANGGAL_TES, B.KETERANGAN
				) A
				INNER JOIN ".$this->db.".pegawai P ON A.PEGAWAI_ID = P.PEGAWAI_ID
				LEFT JOIN ".$this->db.".pangkat PN ON P.LAST_PANGKAT_ID = PN.PANGKAT_ID
				WHERE 1=1 ";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPenilaianAtribut($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY F.ATRIBUT_ID")
	{
		$str = "SELECT F.ATRIBUT_ID, F.NAMA ATRIBUT_NAMA
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_pegawai A ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				INNER JOIN
				(
					SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ATRIBUT_ID
					FROM jadwal_pegawai_detil_komentar A
					INNER JOIN jadwal_tes B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
					WHERE 1=1
					GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ATRIBUT_ID
				) JK ON C.JADWAL_TES_ID = JK.JADWAL_TES_ID AND F.ATRIBUT_ID = JK.ATRIBUT_ID
				WHERE 1=1 ";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY F.ATRIBUT_ID, F.NAMA ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPenilaianAtributKomentar($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "SELECT A.LEVEL_ID, A.INDIKATOR_ID, A.JADWAL_TES_ID, A.ATRIBUT_ID
				, A.PEGAWAI_ID, A.ASESOR_KOMENTAR_ID, B.NAMA ASESOR_KOMENTAR_NAMA
				FROM jadwal_pegawai_detil_komentar A 
				INNER JOIN asesor B ON A.ASESOR_KOMENTAR_ID = B.ASESOR_ID
				WHERE 1=1 ";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.LEVEL_ID, A.INDIKATOR_ID, A.JADWAL_TES_ID, A.ATRIBUT_ID
				, A.PEGAWAI_ID, A.ASESOR_KOMENTAR_ID, B.NAMA ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPenilaianAtributKomentarDetil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "SELECT JADWAL_PEGAWAI_DETIL_KOMENTAR_ID, A.LEVEL_ID, A.INDIKATOR_ID, A.JADWAL_TES_ID, A.KETERANGAN, A.ATRIBUT_ID
				, A.PEGAWAI_ID, A.ASESOR_ID, C.NAMA ASESOR_DIKOMENTAR, A.ASESOR_KOMENTAR_ID, B.NAMA ASESOR_KOMENTAR_NAMA
				FROM jadwal_pegawai_detil_komentar A 
				INNER JOIN asesor B ON A.ASESOR_KOMENTAR_ID = B.ASESOR_ID
				INNER JOIN asesor C ON A.ASESOR_ID = C.ASESOR_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","LEVEL_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParamsAsessmenMeeting($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT A.JADWAL_TES_ID, A.PEGAWAI_ID, B.TANGGAL_TES, B.KETERANGAN
			FROM jadwal_pegawai_detil_komentar A
			INNER JOIN jadwal_tes B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
			WHERE 1=1
			GROUP BY A.JADWAL_TES_ID, A.PEGAWAI_ID, B.TANGGAL_TES, B.KETERANGAN
		) A
		INNER JOIN ".$this->db.".pegawai P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat PN ON P.LAST_PANGKAT_ID = PN.PANGKAT_ID
		WHERE 1=1 ".$statement;
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
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_pegawai_detil_komentar A WHERE 1=1 ".$statement;
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
	
  } 
?>
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

  class JadwalAsesorPotensiPegawai extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalAsesorPotensiPegawai()
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
		$this->setField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID", $this->getNextId("JADWAL_ASESOR_POTENSI_PEGAWAI_ID","jadwal_asesor_potensi_pegawai")); 

		$str = "INSERT INTO jadwal_asesor_potensi_pegawai (
				   JADWAL_ASESOR_POTENSI_PEGAWAI_ID, JADWAL_ASESOR_POTENSI_ID, PEGAWAI_ID
				   , JADWAL_TES_ID, JADWAL_ACARA_ID, ASESOR_ID
				   , LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID").",
				  ".$this->getField("JADWAL_ASESOR_POTENSI_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("ASESOR_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID");
		$this->query= $str;
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
		$str = "UPDATE jadwal_asesor_potensi_pegawai SET
				  JADWAL_ASESOR_POTENSI_ID= ".$this->getField("JADWAL_ASESOR_POTENSI_ID").",
				  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  JADWAL_ACARA_ID= ".$this->getField("JADWAL_ACARA_ID").",
				  ASESOR_ID= ".$this->getField("ASESOR_ID").",
				  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_ASESOR_POTENSI_PEGAWAI_ID = ".$this->getField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID")."
				"; 
				$this->query = $str;
				// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_asesor_potensi_pegawai
                WHERE 
                  JADWAL_ASESOR_POTENSI_PEGAWAI_ID = ".$this->getField("JADWAL_ASESOR_POTENSI_PEGAWAI_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_POTENSI_PEGAWAI_ID ASC")
	{
		$str = "SELECT JADWAL_ASESOR_POTENSI_PEGAWAI_ID, JADWAL_ASESOR_POTENSI_ID, PEGAWAI_ID, PENGGALIAN_ID, NILAI, KETERANGAN
				FROM jadwal_asesor_potensi_pegawai WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLookupPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP, A.PEGAWAI_ID ID
		, B.KODE PEGAWAI_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA PEGAWAI_ESELON, A.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
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
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID, A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, E.TANGGAL_TES, D1.SATKER_ID SATKER_TES_ID
		, A.JADWAL_ASESOR_POTENSI_ID
		FROM jadwal_asesor_potensi_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		INNER JOIN jadwal_asesor_potensi C ON A.JADWAL_ASESOR_POTENSI_ID = C.JADWAL_ASESOR_POTENSI_ID
		INNER JOIN jadwal_acara D ON C.JADWAL_ACARA_ID = D.JADWAL_ACARA_ID
		INNER JOIN jadwal_tes E ON D.JADWAL_TES_ID = E.JADWAL_TES_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
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
	
	function selectByParamsJadwalPegawaiInfo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT D.NAMA NAMA_ASESI, B.NAMA METODE, E.TANGGAL_TES, F.NAMA NAMA_PEGAWAI, F.LAST_JABATAN JABATAN_INI_TES, G.NAMA SATUAN_KERJA_INI_TES, E.JADWAL_TES_ID
		, E.STATUS_PENILAIAN, A.JADWAL_ASESOR_POTENSI_ID, A.PEGAWAI_ID
		FROM jadwal_asesor_potensi_pegawai A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN jadwal_asesor_potensi C ON A.JADWAL_ASESOR_POTENSI_ID = C.JADWAL_ASESOR_POTENSI_ID
		INNER JOIN asesor D ON C.ASESOR_ID = D.ASESOR_ID
		INNER JOIN jadwal_tes E ON E.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN ".$this->db.".pegawai F ON A.PEGAWAI_ID = F.PEGAWAI_ID
		left JOIN ".$this->db.".satker G ON F.SATKER_ID = G.SATKER_ID
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.PUKUL1, D.NAMA, B.KETERANGAN ASC")
	{
		$str = "SELECT A.JADWAL_ASESOR_POTENSI_ID, A.JADWAL_ACARA_ID, A.ASESOR_ID, A.KETERANGAN AS KETERANGAN_JADWAL, C.NAMA, B.KETERANGAN AS KETERANGAN_ACARA
				, C.NAMA ASESOR_NAMA, B.PUKUL1, B.PUKUL2, D.NAMA PENGGALIAN_NAMA
				FROM jadwal_asesor_potensi A
				INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
				LEFT JOIN asesor C ON A.ASESOR_ID = C.ASESOR_ID
				LEFT JOIN penggalian D ON D.PENGGALIAN_ID = B.PENGGALIAN_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPenilaianAtribut($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY F.ATRIBUT_ID")
	{
		$str = "SELECT F.ATRIBUT_ID, F.NAMA ATRIBUT_NAMA, D.NILAI_STANDAR
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID -- AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor_potensi B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_asesor_potensi_pegawai A ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY F.ATRIBUT_ID, F.NAMA, D.NILAI_STANDAR ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJadwalPegawaiPenggalian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.NAMA")
	{
		$str = "SELECT A.PEGAWAI_ID, A.PENGGALIAN_ID, B.NAMA PENGGALIAN_NAMA, B.KODE PENGGALIAN_KODE
				FROM jadwal_asesor_potensi_pegawai A
				INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
				INNER JOIN jadwal_tes C ON A.ID_JADWAL = C.JADWAL_TES_ID
				WHERE 1=1
				"; 
		// AND A.PEGAWAI_ID = 182 AND TO_CHAR(C.TANGGAL_TES, 'YYYY') = '2016'
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.PEGAWAI_ID, A.PENGGALIAN_ID, B.NAMA, B.KODE ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJadwalPegawaiPenggalianDetil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.NAMA")
	{
		$str = "SELECT A.PEGAWAI_ID, A.PENGGALIAN_ID, B.NAMA PENGGALIAN_NAMA, B.KODE PENGGALIAN_KODE, TO_CHAR(C.TANGGAL_TES, 'YYYY') TAHUN_JADWAL
				, D.ATRIBUT_ID
				FROM jadwal_asesor_potensi_pegawai A
				INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
				INNER JOIN jadwal_tes C ON A.ID_JADWAL = C.JADWAL_TES_ID
				INNER JOIN jadwal_asesor_potensi_pegawai_detil D ON A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID = D.JADWAL_ASESOR_POTENSI_PEGAWAI_ID
				WHERE 1=1
				"; 
		// AND A.PEGAWAI_ID = 182 AND TO_CHAR(C.TANGGAL_TES, 'YYYY') = '2016'
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.PEGAWAI_ID, A.PENGGALIAN_ID, B.NAMA, B.KODE, TO_CHAR(C.TANGGAL_TES, 'YYYY'), D.ATRIBUT_ID ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJadwalPegawaiPenggalianDetilBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.NAMA")
	{
		$str = "SELECT A.PEGAWAI_ID, A.PENGGALIAN_ID, B.NAMA PENGGALIAN_NAMA, B.KODE PENGGALIAN_KODE, TO_CHAR(C.TANGGAL_TES, 'YYYY') TAHUN_JADWAL
				, SUBSTR(D.ATRIBUT_ID,1,2) ATRIBUT_ID
				FROM jadwal_asesor_potensi_pegawai A
				INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
				INNER JOIN jadwal_tes C ON A.ID_JADWAL = C.JADWAL_TES_ID
				INNER JOIN jadwal_asesor_potensi_pegawai_detil D ON A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID = D.JADWAL_ASESOR_POTENSI_PEGAWAI_ID
				WHERE 1=1
				"; 
		// AND A.PEGAWAI_ID = 182 AND TO_CHAR(C.TANGGAL_TES, 'YYYY') = '2016'
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY A.PEGAWAI_ID, A.PENGGALIAN_ID, B.NAMA, B.KODE, TO_CHAR(C.TANGGAL_TES, 'YYYY'), SUBSTR(D.ATRIBUT_ID,1,2) ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorPenilaianDetil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ATRIBUT_ID, A.INDIKATOR_ID, A.LEVEL_ID")
	{
		$str = "SELECT A.ATRIBUT_ID, A.INDIKATOR_ID, A.LEVEL_ID, C.ASESOR_ID
				FROM jadwal_asesor_potensi_pegawai_detil A
				INNER JOIN jadwal_asesor_potensi_pegawai B ON A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID = B.JADWAL_ASESOR_POTENSI_PEGAWAI_ID
				INNER JOIN jadwal_asesor_potensi C ON B.JADWAL_ASESOR_POTENSI_ID = C.JADWAL_ASESOR_POTENSI_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorJumlah($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B1.NAMA")
	{
		$str = "SELECT B.ASESOR_ID, B1.NAMA NAMA_ASESOR
				FROM jadwal_tes C
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID -- AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				INNER JOIN jadwal_asesor_potensi B ON B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID AND B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN jadwal_asesor_potensi_pegawai A ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID AND A.PENGGALIAN_ID = C1.PENGGALIAN_ID
				INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
				WHERE 1=1 
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY B.ASESOR_ID, B1.NAMA ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAsesorNilai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.ASESOR_ID, A.PEGAWAI_ID, F.ATRIBUT_ID")
	{
		$str = "SELECT 
				B.ASESOR_ID, A.PEGAWAI_ID, F.ATRIBUT_ID, PP.KODE, D.NILAI_STANDAR
				, (5 / G1.JUMLAH_LEVEL) * COUNT(H.JADWAL_PEGAWAI_DETIL_ID) NILAI_RAW, ROUND((5 / G1.JUMLAH_LEVEL) * COUNT(H.JADWAL_PEGAWAI_DETIL_ID)) NILAI_PEMBULATAN
				FROM jadwal_asesor_potensi_pegawai A
				INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
				INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
				INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
				INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
				INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
				LEFT JOIN penggalian PP ON D1.PENGGALIAN_ID = PP.PENGGALIAN_ID
				INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
				INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
				INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
				LEFT JOIN jadwal_asesor_potensi_pegawai_detil H ON H.JADWAL_ASESOR_POTENSI_PEGAWAI_ID = A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID
				INNER JOIN
				(
					SELECT A.PEGAWAI_ID, A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID, G.LEVEL_ID, COUNT(1) JUMLAH_LEVEL
					FROM jadwal_asesor_potensi_pegawai A
					INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
					INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
					INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
					INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
					INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
					INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
					INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
					INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
					WHERE 1=1
					GROUP BY A.PEGAWAI_ID, A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID, G.LEVEL_ID
				) G1 ON G1.LEVEL_ID = G.LEVEL_ID AND A.JADWAL_ASESOR_POTENSI_PEGAWAI_ID = G1.JADWAL_ASESOR_POTENSI_PEGAWAI_ID AND G1.PEGAWAI_ID = A.PEGAWAI_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY B.ASESOR_ID, A.PEGAWAI_ID, F.ATRIBUT_ID, PP.KODE, D1.PENGGALIAN_ID, D.NILAI_STANDAR ".$sOrder;
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_asesor_potensi_pegawai WHERE 1=1 ".$statement;
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
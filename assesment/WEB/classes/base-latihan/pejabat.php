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

  class Pejabat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pejabat()
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
		$this->setField("PEJABAT_ID", $this->getNextId("PEJABAT_ID","pejabat")); 

		$str = "INSERT INTO pejabat (
				   PEJABAT_ID, NAMA_PEJABAT, ALAMAT_PEJABAT, EMAIL_PEJABAT, TELEPON, NO_SK, UMUR) 
				VALUES (
				  ".$this->getField("PEJABAT_ID").",
				  '".$this->getField("NAMA_PEJABAT")."',
				  '".$this->getField("ALAMAT_PEJABAT")."',
				  '".$this->getField("EMAIL_PEJABAT")."',
				  '".$this->getField("TELEPON")."',
				  '".$this->getField("NO_SK")."',
				  '".$this->getField("UMUR")."'
				)"; 
		$this->id= $this->getField("PEJABAT_ID");
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
		$str = "UPDATE pejabat SET
				  NAMA= '".$this->getField("NAMA_PEJABAT")."',
				  ALAMAT= '".$this->getField("ALAMAT_PEJABAT")."',
				  EMAIL= '".$this->getField("EMAIL_PEJABAT")."',
				  TELEPON= '".$this->getField("TELEPON")."',
				  NO_SK= '".$this->getField("NO_SK")."',
				  UMUR= '".$this->getField("UMUR")."'  
				WHERE PEJABAT_ID = ".$this->getField("PEJABAT_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM pejabat
                WHERE 
                  PEJABAT_ID = ".$this->getField("PEJABAT_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY PEJABAT_ID ASC")
	{
		$str = "SELECT PEJABAT_ID, NAMA_PEJABAT, ALAMAT_PEJABAT, EMAIL_PEJABAT, TELEPON, NO_SK, UMUR
				FROM pejabat A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsHistori($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY D.NAMA, E.TANGGAL_TES DESC")
	{
		$str = "
		SELECT C.PEJABAT_ID, D.NAMA NAMA_ASESI, B.KODE, B.NAMA METODE, E.TANGGAL_TES, E.JADWAL_TES_ID, A.PENGGALIAN_ID
		FROM jadwal_pegawai A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN jadwal_asesor C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
		INNER JOIN pejabat D ON C.JABATAN_ID = D.JABATAN_ID
		INNER JOIN jadwal_tes E ON E.JADWAL_TES_ID = C.JADWAL_TES_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY C.PEJABAT_ID, A.PENGGALIAN_ID, D.NAMA, B.KODE, B.NAMA, E.TANGGAL_TES ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsHistoriTanggal($statement='', $sOrder="ORDER BY B1.NAMA, A.PEGAWAI_ID, F.ATRIBUT_ID")
	{
		$str = "
		SELECT 
			H.JADWAL_PEGAWAI_DETIL_ID, F.ATRIBUT_ID,
			G.INDIKATOR_ID, G.LEVEL_ID,
			H.INDIKATOR_ID PEGAWAI_INDIKATOR_ID, H.LEVEL_ID PEGAWAI_LEVEL_ID,
			F.NAMA ATRIBUT_NAMA, G.NAMA_INDIKATOR, G1.JUMLAH_LEVEL
			, CASE WHEN H.KETERANGAN = '' OR H.KETERANGAN IS NULL THEN 'Tidak Ada' ELSE H.KETERANGAN END PEGAWAI_KETERANGAN
			, PG.NAMA, B1.NAMA ASESOR_NAMA
		FROM jadwal_pegawai A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN pejabat B1 ON B.PEJABAT_ID = B1.PEJABAT_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
		INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
		INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
		INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID
		INNER JOIN jadwal_pegawai_detil H ON H.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID
		INNER JOIN
		(
			SELECT G.LEVEL_ID, E.ATRIBUT_ID, A.PEGAWAI_ID, COUNT(E.ATRIBUT_ID) JUMLAH_LEVEL
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
			INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
			INNER JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
			INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
			INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
			INNER JOIN indikator_penilaian G ON G.LEVEL_ID = E.LEVEL_ID AND G.LEVEL_ID = E.LEVEL_ID
			INNER JOIN jadwal_pegawai_detil H ON H.JADWAL_PEGAWAI_ID = A.JADWAL_PEGAWAI_ID AND H.INDIKATOR_ID = G.INDIKATOR_ID
			WHERE 1=1
			GROUP BY G.LEVEL_ID, E.ATRIBUT_ID, A.PEGAWAI_ID
		) G1 ON G1.LEVEL_ID = G.LEVEL_ID AND G1.PEGAWAI_ID = A.PEGAWAI_ID AND E.ATRIBUT_ID = G1.ATRIBUT_ID
		INNER JOIN 
		(
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID ID, A.NIP_BARU NIP, A.NAMA
			FROM ".$this->db.".pegawai A
			WHERE 1=1
		) PG ON A.PEGAWAI_ID = PG.ID
		WHERE 1=1 ".$statement; 
		
		$str .= " ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsHistoriTanggalbAK($statement='', $sOrder="")
	{
		$str = "
		SELECT A.JADWAL_PEGAWAI_ID, E.TAHUN, E.NAMA ATRIBUT_NAMA, COALESCE(F.NILAI, 3) NILAI, F.KETERANGAN
		, C.NAMA, G.METODE, G.KODE
		FROM jadwal_pegawai A
		INNER JOIN penggalian_satker_penilaian B ON B.PENGGALIAN_ID = A.PENGGALIAN_ID
		INNER JOIN 
		(
			SELECT 
			A.ID, A.ORG_ID KODE_UNKER
			, A.NIP, A.NAME NAMA
			FROM ".$this->db.".user A
			WHERE 1=1
		)
		C ON A.PEGAWAI_ID = C.ID
		INNER JOIN satker_eselon_atribut D ON D.SATKER_ESELON_ATRIBUT_ID = B.SATKER_ESELON_ATRIBUT_ID AND B.ATRIBUT_ID = D.ATRIBUT_ID AND C.KODE_UNKER = D.SATUAN_KERJA_ID
		INNER JOIN atribut E ON E.ATRIBUT_ID = D.ATRIBUT_ID
		LEFT JOIN jadwal_pegawai_detil F ON A.JADWAL_PEGAWAI_ID = F.JADWAL_PEGAWAI_ID AND E.ATRIBUT_ID = F.ATRIBUT_ID
		INNER JOIN
		(
			SELECT B.JADWAL_PEGAWAI_ID, D.NAMA METODE, D.KODE
			FROM jadwal_asesor A
			INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN jadwal_tes C ON A.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN penggalian D ON B.PENGGALIAN_ID = D.PENGGALIAN_ID
			WHERE 1=1 ".$statement."
		) G ON A.JADWAL_PEGAWAI_ID = G.JADWAL_PEGAWAI_ID
		WHERE 1=1
		"; 
		
		$str .= $sOrder;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM pejabat WHERE 1=1 ".$statement;
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
	
	function getCountByParamsHistori($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		(
		SELECT C.JABATAN_ID, D.NAMA NAMA_ASESI, B.NAMA METODE, E.TANGGAL_TES, A.PENGGALIAN_ID
		FROM jadwal_pegawai A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		INNER JOIN jadwal_asesor C ON A.JADWAL_ASESOR_ID = C.JADWAL_ASESOR_ID
		INNER JOIN pejabat D ON C.PEJABAT_ID = D.PEJABAT_ID
		INNER JOIN jadwal_tes E ON E.JADWAL_TES_ID = C.JADWAL_TES_ID
		WHERE 1=1
		".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY C.PEJABAT_ID, A.PENGGALIAN_ID, D.NAMA, B.NAMA, E.TANGGAL_TES) A ";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>
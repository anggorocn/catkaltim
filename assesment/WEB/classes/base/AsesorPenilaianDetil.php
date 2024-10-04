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

  class AsesorPenilaianDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function AsesorPenilaianDetil()
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
		$this->setField("ASESOR_PENILAIAN_DETIL_ID", $this->getNextId("ASESOR_PENILAIAN_DETIL_ID","asesor_penilaian_detil")); 

		$str = "INSERT INTO asesor_penilaian_detil (
				   ASESOR_PENILAIAN_DETIL_ID, JADWAL_TES_ID, TANGGAL_TES
				   , JABATAN_TES_ID, SATKER_TES_ID, JADWAL_ASESOR_ID, ASPEK_ID, ASESOR_ATRIBUT_ID
				   , NILAI_STANDAR, NILAI, GAP, ASESOR_FORMULA_ESELON_ID, ASESOR_FORMULA_ATRIBUT_ID, ASESOR_PENGGALIAN_ID, ASESOR_PEGAWAI_ID, CATATAN
				)
				VALUES (
				  ".$this->getField("ASESOR_PENILAIAN_DETIL_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("TANGGAL_TES").",
				  '".$this->getField("JABATAN_TES_ID")."',
				  '".$this->getField("SATKER_TES_ID")."',
				  ".$this->getField("JADWAL_ASESOR_ID").",
				  ".$this->getField("ASPEK_ID").",
				  '".$this->getField("ASESOR_ATRIBUT_ID")."',
				  ".$this->getField("NILAI_STANDAR").",
				  ".$this->getField("NILAI").",
				  ".$this->getField("GAP").",
				  ".$this->getField("ASESOR_FORMULA_ESELON_ID").",
				  ".$this->getField("ASESOR_FORMULA_ATRIBUT_ID").",
				  ".$this->getField("ASESOR_PENGGALIAN_ID").",
				  ".$this->getField("ASESOR_PEGAWAI_ID").",
				  '".$this->getField("CATATAN")."
				)"; 
		$this->id= $this->getField("ASESOR_PENILAIAN_DETIL_ID");
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
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE asesor_penilaian_detil SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  TANGGAL_TES= ".$this->getField("TANGGAL_TES").",
				  JABATAN_TES_ID= '".$this->getField("JABATAN_TES_ID")."',
				  SATKER_TES_ID= '".$this->getField("SATKER_TES_ID")."',
				  JADWAL_ASESOR_ID= ".$this->getField("JADWAL_ASESOR_ID").",
				  ASPEK_ID= ".$this->getField("ASPEK_ID").",
				  ASESOR_ATRIBUT_ID= '".$this->getField("ASESOR_ATRIBUT_ID")."',
				  NILAI_STANDAR= ".$this->getField("NILAI_STANDAR").",
				  NILAI= ".$this->getField("NILAI").",
				  GAP= ".$this->getField("GAP").",
				  ASESOR_FORMULA_ESELON_ID= ".$this->getField("ASESOR_FORMULA_ESELON_ID").",
				  ASESOR_FORMULA_ATRIBUT_ID= ".$this->getField("ASESOR_FORMULA_ATRIBUT_ID").",
				  ASESOR_PENGGALIAN_ID= ".$this->getField("ASESOR_PENGGALIAN_ID").",
				  ASESOR_PEGAWAI_ID= ".$this->getField("ASESOR_PEGAWAI_ID").",
				  CATATAN= '".$this->getField("CATATAN")."'
				WHERE ASESOR_PENILAIAN_DETIL_ID = '".$this->getField("ASESOR_PENILAIAN_DETIL_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM asesor_penilaian_detil
                WHERE 
                  ASESOR_PENILAIAN_DETIL_ID = '".$this->getField("ASESOR_PENILAIAN_DETIL_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JADWAL_TES_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY ASESOR_PENILAIAN_DETIL_ID ASC")
	{
		$str = "SELECT
				ASESOR_PENILAIAN_DETIL_ID
				, JADWAL_TES_ID , TANGGAL_TES , JABATAN_TES_ID , SATKER_TES_ID , JADWAL_ASESOR_ID , ASPEK_ID , ASESOR_ATRIBUT_ID
				, NILAI_STANDAR , NILAI , GAP, ASESOR_FORMULA_ESELON_ID, ASESOR_FORMULA_ATRIBUT_ID, ASESOR_PENGGALIAN_ID, ASESOR_PEGAWAI_ID, CATATAN
				FROM asesor_penilaian_detil A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsMonitoringAspek1($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY F.ASPEK_ID, F.ATRIBUT_ID")
	{
		//AND A.JADWAL_ASESOR_ID = 59 AND A.PEGAWAI_ID = 90 AND B.ASESOR_ID = 34 AND F.ASPEK_ID = 1
		$str = "
		SELECT 
			AA.ASESOR_PENILAIAN_DETIL_ID
			, B.JADWAL_TES_ID, C.TANGGAL_TES, C1.LAST_JABATAN JABATAN_TES_ID, C1.SATKER_ID SATKER_TES_ID
			, A.JADWAL_ASESOR_POTENSI_ID JADWAL_ASESOR_ID, F.ASPEK_ID, F.ATRIBUT_ID ASESOR_ATRIBUT_ID, D.NILAI_STANDAR
			, COALESCE(AA.NILAI, D.NILAI_STANDAR) NILAIBAK
			, COALESCE(AA.NILAI, 1) NILAI, COALESCE(AA.GAP,0) GAP
			, C.FORMULA_ESELON_ID ASESOR_FORMULA_ESELON_ID
			, D.FORMULA_ATRIBUT_ID ASESOR_FORMULA_ATRIBUT_ID
			, NULL ASESOR_PENGGALIAN_ID, A.PEGAWAI_ID ASESOR_PEGAWAI_ID
			, AA.CATATAN
		FROM jadwal_asesor_potensi_pegawai A
		INNER JOIN ".$this->db.".pegawai C1 ON A.PEGAWAI_ID = C1.PEGAWAI_ID
		INNER JOIN jadwal_asesor_potensi B ON A.JADWAL_ASESOR_POTENSI_ID = B.JADWAL_ASESOR_POTENSI_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
		INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
		LEFT JOIN asesor_penilaian_detil AA ON
		B.JADWAL_TES_ID = AA.JADWAL_TES_ID AND A.JADWAL_ASESOR_POTENSI_ID = AA.JADWAL_ASESOR_ID
		AND F.ASPEK_ID = AA.ASPEK_ID AND F.ATRIBUT_ID = AA.ASESOR_ATRIBUT_ID
		AND C.FORMULA_ESELON_ID = AA.ASESOR_FORMULA_ESELON_ID AND D.FORMULA_ATRIBUT_ID = AA.ASESOR_FORMULA_ATRIBUT_ID
		AND AA.ASESOR_PENGGALIAN_ID IS NULL AND A.PEGAWAI_ID = AA.ASESOR_PEGAWAI_ID
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY F.ASPEK_ID, F.ATRIBUT_ID")
	{
		//AND A.JADWAL_ASESOR_ID = 59 AND A.PEGAWAI_ID = 90 AND B.ASESOR_ID = 34 AND F.ASPEK_ID = 1
		$str = "
		SELECT 
			AA.ASESOR_PENILAIAN_DETIL_ID
			, B.JADWAL_TES_ID, C.TANGGAL_TES, C1.LAST_JABATAN JABATAN_TES_ID, C1.SATKER_ID SATKER_TES_ID
			, A.JADWAL_ASESOR_ID, F.ASPEK_ID, F.ATRIBUT_ID ASESOR_ATRIBUT_ID, D.NILAI_STANDAR
			, COALESCE(AA.NILAI, D.NILAI_STANDAR) NILAIBAK
			, COALESCE(AA.NILAI, 1) NILAI, COALESCE(AA.GAP,0) GAP
			, C.FORMULA_ESELON_ID ASESOR_FORMULA_ESELON_ID
			, D.FORMULA_ATRIBUT_ID ASESOR_FORMULA_ATRIBUT_ID
			, D1.PENGGALIAN_ID ASESOR_PENGGALIAN_ID, A.PEGAWAI_ID ASESOR_PEGAWAI_ID
			, AA.CATATAN
		FROM jadwal_pegawai A
		INNER JOIN ".$this->db.".pegawai C1 ON A.PEGAWAI_ID = C1.PEGAWAI_ID
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		LEFT JOIN atribut_penggalian D1 ON D1.FORMULA_ATRIBUT_ID = D.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = D1.PENGGALIAN_ID
		INNER JOIN level_atribut E ON D.LEVEL_ID = E.LEVEL_ID
		INNER JOIN atribut F ON E.ATRIBUT_ID = F.ATRIBUT_ID
		LEFT JOIN asesor_penilaian_detil AA ON
		B.JADWAL_TES_ID = AA.JADWAL_TES_ID AND A.JADWAL_ASESOR_ID = AA.JADWAL_ASESOR_ID
		AND F.ASPEK_ID = AA.ASPEK_ID AND F.ATRIBUT_ID = AA.ASESOR_ATRIBUT_ID
		AND C.FORMULA_ESELON_ID = AA.ASESOR_FORMULA_ESELON_ID AND D.FORMULA_ATRIBUT_ID = AA.ASESOR_FORMULA_ATRIBUT_ID
		AND D1.PENGGALIAN_ID = AA.ASESOR_PENGGALIAN_ID AND A.PEGAWAI_ID = AA.ASESOR_PEGAWAI_ID
		WHERE 1=1
		AND (CASE WHEN (F.ASPEK_ID = 2 AND D1.PENGGALIAN_ID IS NOT NULL) OR F.ASPEK_ID = 1 THEN 1 ELSE 0 END) = 1
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JADWAL_TES_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM asesor_penilaian_detil WHERE 1=1 ".$statement;
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
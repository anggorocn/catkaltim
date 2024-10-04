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

  class SqlKonversi extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function SqlKonversi()
	{
	  $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
      $this->Entity(); 
    }
	
	function insertPegawai()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			INSERT INTO ".$this->db.".pegawai(
				PEGAWAI_ID, NIP_BARU, SATKER_ID, NAMA, LAST_PANGKAT_ID, LAST_ESELON_ID, LAST_JABATAN
			)
			VALUES ( 
			'".$this->getField("PEGAWAI_ID")."',
			'".$this->getField("NIP_BARU")."',
			'".$this->getField("SATKER_ID")."',
			'".$this->getField("NAMA")."',
			".$this->getField("LAST_PANGKAT_ID").",
			".$this->getField("LAST_ESELON_ID").",
			'".$this->getField("LAST_JABATAN")."'
		)"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function updatePegawai()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "   
				UPDATE ".$this->db.".pegawai
				SET
				   	NAMA= '".$this->getField("NAMA")."',
				   	SATKER_ID= '".$this->getField("SATKER_ID")."',
					LAST_PANGKAT_ID= ".$this->getField("LAST_PANGKAT_ID").",
					LAST_ESELON_ID= ".$this->getField("LAST_ESELON_ID").",
					LAST_JABATAN= '".$this->getField("LAST_JABATAN")."'
				WHERE PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."'
				"; 
				$this->query = $str;
		//echo $str;;exit;
		return $this->execQuery($str);
    }

    function insertPegawaiDetil()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
			INSERT INTO PEGAWAI(
			            PEGAWAI_ID, NIP, NIP_BARU, NAMA, TEMPAT_LAHIR, TGL_LAHIR, JENIS_KELAMIN, 
			            AGAMA, LAST_PANGKAT_ID, LAST_TMT_PANGKAT, TMT_CPNS, TMT_PNS, 
			            LAST_JABATAN, LAST_ESELON_ID, LAST_TMT_JABATAN, MASA_JAB_TAHUN, 
			            MASA_JAB_BULAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, SATKER_ID, 
			            TIPE_PEGAWAI_ID, STATUS_PEGAWAI_ID, LAST_DIK_JENJANG, LAST_DIK_TAHUN, 
			            LAST_DIK_JURUSAN, ALAMAT)

				VALUES ( 
				   '".$this->getField("PEGAWAI_ID")."',
				   '".$this->getField("NIP")."',
				   '".$this->getField("NIP_BARU")."',
				   '".$this->getField("NAMA")."',
				   ".$this->getField("TEMPAT_LAHIR").",
				   ".$this->getField("TGL_LAHIR").",
				   '".$this->getField("JENIS_KELAMIN")."',
				   '".$this->getField("AGAMA")."',
				   ".$this->getField("LAST_PANGKAT_ID").",
				   ".$this->getField("LAST_TMT_PANGKAT").",
				   ".$this->getField("TMT_CPNS").",
				   ".$this->getField("TMT_PNS").",
				   ".$this->getField("LAST_JABATAN").",
				   ".$this->getField("LAST_ESELON_ID")."',
				   ".$this->getField("LAST_TMT_PANGKAT").",
				   ".$this->getField("MASA_JAB_TAHUN").",
				   ".$this->getField("MASA_JAB_BULAN").",
				   ".$this->getField("MASA_KERJA_TAHUN").",
				   ".$this->getField("MASA_KERJA_BULAN").",
				   '".$this->getField("SATKER_ID")."',
				   ".$this->getField("TIPE_PEGAWAI_ID").",
				   ".$this->getField("STATUS_PEGAWAI_ID").",
				   ".$this->getField("LAST_DIK_JENJANG").",
				   '".$this->getField("LAST_DIK_TAHUN")."',
				   '".$this->getField("LAST_DIK_JURUSAN")."',
				   '".$this->getField("ALAMAT")."'
				)"; 
		$this->query = $str;
		
		return $this->execQuery($str);
    }
	
	function updatePegawaiDetil()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "   
				UPDATE pegawai
				SET
				   	NAMA				= '".$this->getField("NAMA")."',
				   	TEMPAT_LAHIR		= '".$this->getField("TEMPAT_LAHIR")."',
				   	TGL_LAHIR			= ".$this->getField("TGL_LAHIR").",
				   	JENIS_KELAMIN		= '".$this->getField("JENIS_KELAMIN")."',
				   	AGAMA				= '".$this->getField("AGAMA")."',
				   	LAST_PANGKAT_ID		= ".$this->getField("LAST_PANGKAT_ID").",
				   	LAST_TMT_PANGKAT	= ".$this->getField("LAST_TMT_PANGKAT").",
				   	TMT_CPNS			= ".$this->getField("TMT_CPNS").",
				   	TMT_PNS				= ".$this->getField("TMT_PNS").",
				   	LAST_JABATAN		= ".$this->getField("LAST_JABATAN").",
				   	LAST_ESELON_ID		= ".$this->getField("LAST_ESELON_ID").",
				   	LAST_JAB_TAHUN		= ".$this->getField("MASA_JAB_TAHUN").",
				    MASA_JAB_BULAN		= ".$this->getField("MASA_JAB_BULAN").",
				    MASA_KERJA_TAHUN	= ".$this->getField("MASA_KERJA_TAHUN").",
				    MASA_KERJA_BULAN	= ".$this->getField("MASA_KERJA_BULAN").",
				    SATKER_ID			= '".$this->getField("SATKER_ID")."',
				    TIPE_PEGAWAI_ID		= ".$this->getField("TIPE_PEGAWAI_ID").",
				    STATUS_PEGAWAI_ID	= ".$this->getField("STATUS_PEGAWAI_ID").",
				   	LAST_DIK_JENJANG	= ".$this->getField("LAST_DIK_JENJANG").",
				    LAST_DIK_TAHUN		= '".$this->getField("LAST_DIK_TAHUN")."',
				    LAST_DIK_JURUSAN	= '".$this->getField("LAST_DIK_JURUSAN")."',
				    ALAMAT				= '".$this->getField("ALAMAT")."'
				WHERE NIP_BARU			= '".$this->getField("NIP_BARU")."'
				"; 
				$this->query = $str;
		//echo $str;;exit;
		return $this->execQuery($str);
    }

	function cariIdTable($id, $table, $statement="")
	{
		$str = "SELECT ".$id." AS ROWCOUNT FROM ".$table." WHERE 1 = 1 ".$statement;
		
		$this->select($str); 
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "";
    }
	
	function deleteIdTable($table, $statement="")
	{
		$str = "DELETE FROM ".$table." WHERE 1 = 1 ".$statement;
		
		$this->select($str); 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.NIP_BARU, A.NAMA, A.KODE_UNIT, A.NAMA_JABATAN, B.PANGKAT_ID, C.ESELON_ID
		FROM ".$this->db.".pegawai_intregasi A
		LEFT JOIN ".$this->db.".pangkat B ON A.KODE_GOLONGAN = B.KODE
		LEFT JOIN ".$this->db.".eselon C ON A.KODE_ESELON = C.NAMA
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
	
  } 
?>
<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel PEGAWAI.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Pegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pegawai()
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
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID", $this->db.".pegawai")); 		

		$str = "
				INSERT INTO ".$this->db.".pegawai(
					  PEGAWAI_ID, NIP, NIP_BARU, NAMA
					  , TEMPAT_LAHIR, TGL_LAHIR, JENIS_KELAMIN
					  , AGAMA,LAST_PANGKAT_ID, LAST_TMT_PANGKAT
					  , TMT_CPNS, TMT_PNS, LAST_JABATAN, LAST_ESELON_ID, LAST_TMT_JABATAN 
					  , MASA_JAB_TAHUN, MASA_JAB_BULAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN
  					  , SATKER_ID, TIPE_PEGAWAI_ID,STATUS_PEGAWAI_ID, LAST_DIK_JENJANG
  					  , LAST_DIK_TAHUN, LAST_DIK_JURUSAN, ALAMAT) 
 			  	VALUES (
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("NIP")."',
  				  '".$this->getField("NIP_BARU")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
   				  ".$this->getField("TGL_LAHIR").",
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("AGAMA")."',
				  ".$this->getField("LAST_PANGKAT_ID").",
				  ".$this->getField("LAST_TMT_PANGKAT").",
				  ".$this->getField("TMT_CPNS").",
				  ".$this->getField("TMT_PNS").",
				  '".$this->getField("LAST_JABATAN")."',
				  ".$this->getField("LAST_ESELON_ID").",
				  ".$this->getField("LAST_TMT_JABATAN").",
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
		$this->id = $this->getField("PEGAWAI_ID");
		// echo $str; exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE ".$this->db.".pegawai
				SET    
					  NIP				= '".$this->getField("NIP")."',
					  NIP_BARU			= '".$this->getField("NIP_BARU")."',
					  NAMA				= '".$this->getField("NAMA")."',
					  TEMPAT_LAHIR		= '".$this->getField("TEMPAT_LAHIR")."',
					  TGL_LAHIR			= ".$this->getField("TGL_LAHIR").",
					  JENIS_KELAMIN		= '".$this->getField("JENIS_KELAMIN")."',
					  AGAMA				= '".$this->getField("AGAMA")."',
					  LAST_PANGKAT_ID	= ".$this->getField("LAST_PANGKAT_ID").",
					  LAST_TMT_PANGKAT	= ".$this->getField("LAST_TMT_PANGKAT").",
					  TMT_CPNS			= ".$this->getField("TMT_CPNS").",
					  TMT_PNS			= ".$this->getField("TMT_PNS").",
					  LAST_JABATAN		= '".$this->getField("LAST_JABATAN")."',
					  LAST_ESELON_ID	= ".$this->getField("LAST_ESELON_ID").",
					  LAST_TMT_JABATAN	= ".$this->getField("LAST_TMT_JABATAN").",
					  MASA_JAB_TAHUN	= ".$this->getField("MASA_JAB_TAHUN").",
					  MASA_JAB_BULAN	= ".$this->getField("MASA_JAB_BULAN").",
					  MASA_KERJA_TAHUN	= ".$this->getField("MASA_KERJA_TAHUN").",
					  MASA_KERJA_BULAN	= ".$this->getField("MASA_KERJA_BULAN").",
					  SATKER_ID			= '".$this->getField("SATKER_ID")."',
					  TIPE_PEGAWAI_ID	= ".$this->getField("TIPE_PEGAWAI_ID").",
					  STATUS_PEGAWAI_ID	= ".$this->getField("STATUS_PEGAWAI_ID").",
					  LAST_DIK_JENJANG	= ".$this->getField("LAST_DIK_JENJANG").",
					  LAST_DIK_TAHUN	= '".$this->getField("LAST_DIK_TAHUN")."',
					  LAST_DIK_JURUSAN	= '".$this->getField("LAST_DIK_JURUSAN")."',
					  ALAMAT			= '".$this->getField("ALAMAT")."'					   
				WHERE PEGAWAI_ID     	= '".$this->getField("PEGAWAI_ID")."'

			 "; 
		$this->query = $str;
		// echo $str; exit;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PEGAWAI A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM ".$this->db.".pegawai
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.PEGAWAI_ID DESC ")
	{
		$str = "
					SELECT 
						   PEGAWAI_ID, NIP, NIP_BARU, NAMA, TEMPAT_LAHIR, TGL_LAHIR, JENIS_KELAMIN, AGAMA, 
						   LAST_PANGKAT_ID, LAST_TMT_PANGKAT, TMT_CPNS, TMT_PNS, LAST_JABATAN, LAST_ESELON_ID, 
						   LAST_TMT_JABATAN, MASA_JAB_TAHUN, MASA_JAB_BULAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, 
						   SATKER_ID, TIPE_PEGAWAI_ID, STATUS_PEGAWAI_ID, LAST_DIK_JENJANG, LAST_DIK_TAHUN, LAST_DIK_JURUSAN, ALAMAT
					FROM ".$this->db.".pegawai A 
					WHERE PEGAWAI_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT 
				A.SATKER_ID, A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
				, AGAMA, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, CASE A.STATUS_PEGAWAI_ID WHEN 1 THEN 'PNS' WHEN 2 THEN 'PNS' when 3 THEN 'Pensiun' ELSE '' END STATUS
				, B.KODE NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN, A.LAST_TMT_JABATAN, '' TELP
				, E.NAMA PENDIDIKAN_NAMA, A.LAST_DIK_JURUSAN, LAST_PANGKAT_ID, D.NAMA SATKER, A.LAST_ESELON_ID
				, TMT_CPNS, TMT_PNS, MASA_JAB_TAHUN, MASA_JAB_BULAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN
				, TIPE_PEGAWAI_ID, STATUS_PEGAWAI_ID, LAST_DIK_JENJANG, LAST_DIK_TAHUN, ALAMAT
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			LEFT JOIN ".$this->db.".pendidikan E ON A.LAST_DIK_JENJANG = E.PENDIDIKAN_ID
			WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT 
						   PEGAWAI_ID, DEPARTEMEN_ID, USER_LOGIN_ID, 
						   NAMA, TANGGAL, KETERANGAN, 
						   FILE_UPLOAD, STATUS
					FROM ".$this->db.".pegawai A 
					WHERE PEGAWAI_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT 
				FROM ".$this->db.".pegawai A
		        WHERE PEGAWAI_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT 
				FROM ".$this->db.".pegawai A
		        WHERE PEGAWAI_ID IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>
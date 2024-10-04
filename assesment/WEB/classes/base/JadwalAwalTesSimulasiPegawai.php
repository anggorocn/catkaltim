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

  class JadwalAwalTesSimulasiPegawai extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalAwalTesSimulasiPegawai()
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
		$str = "INSERT INTO jadwal_awal_tes_simulasi_pegawai (
				   JADWAL_AWAL_TES_SIMULASI_ID, JADWAL_AWAL_TES_ID, PEGAWAI_ID, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_AWAL_TES_SIMULASI_ID").",
				  ".$this->getField("JADWAL_AWAL_TES_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("STATUS").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)";
				// echo $str;exit;
				//echo $set->query;exit;
		$this->query= $str;
		return $this->execQuery($str);
    }

    function insertJadwal()
	{
		$str = "
		INSERT INTO jadwal_awal_tes_simulasi_pegawai(
            JADWAL_AWAL_TES_SIMULASI_ID, JADWAL_AWAL_TES_ID, PEGAWAI_ID, LAST_CREATE_DATE)
        VALUES(
            ".$this->getField("JADWAL_AWAL_TES_SIMULASI_ID").",
            (SELECT JADWAL_AWAL_TES_ID FROM jadwal_awal_tes_simulasi WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$this->getField("JADWAL_AWAL_TES_SIMULASI_ID")."),
            ".$this->getField("PEGAWAI_ID").",
            NOW()
        )"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function updateAbsen()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE jadwal_awal_tes_simulasi_pegawai
				SET    
					   LAST_UPDATE_DATE = NOW()
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$this->getField("JADWAL_TES_ID")."
				AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
				$this->query = $str;
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
	
	function delete()
	{
        $str = "DELETE FROM jadwal_awal_tes_simulasi_pegawai
                WHERE 
                  JADWAL_AWAL_TES_ID= ".$this->getField("JADWAL_AWAL_TES_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletepegawai()
	{
        $str = "
        DELETE FROM jadwal_awal_tes_simulasi_pegawai
        WHERE 
        JADWAL_AWAL_TES_ID= ".$this->getField("JADWAL_AWAL_TES_ID")."
        AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
        "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletemultipegawai()
	{
        $str = "
        DELETE FROM jadwal_awal_tes_simulasi_pegawai
        WHERE 
        JADWAL_AWAL_TES_ID= ".$this->getField("JADWAL_AWAL_TES_ID")."
        AND PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").")
        "; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ASESOR_ID ASC")
	{
		$str = "SELECT JADWAL_ASESOR_ID, JADWAL_ACARA_ID, ASESOR_ID, KETERANGAN
				FROM jadwal_awal_tes_simulasi_pegawai WHERE JADWAL_ASESOR_ID IS NOT NULL"; 
		
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
	SELECT distinct(A.PEGAWAI_ID), A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, A1.NIK, a.no_urut no_urut
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID, D1.NAMA SATKER, COALESCE(JD.JUMLAH_DATA,0) JUMLAH_DATA
		FROM jadwal_awal_tes_simulasi_pegawai A
		LEFT JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		LEFT join jadwal_asesor_potensi_pegawai e on e.jadwal_tes_id =a.JADWAL_AWAL_TES_SIMULASI_ID
		LEFT JOIN
		(
			SELECT a.pegawai_id, b.jadwal_tes_id,count(1) JUMLAH_DATA
			FROM jadwal_pegawai A
			left join jadwal_asesor b on a.JADWAL_ASESOR_ID=b.JADWAL_ASESOR_ID
				WHERE 1=1
			 group by a.pegawai_id,b.jadwal_tes_id
		) JD ON JADWAL_AWAL_TES_SIMULASI_ID = jd.jadwal_tes_id AND A.PEGAWAI_ID = jd.pegawai_id
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

 //    function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="",$jadwal='')
	// {
	// 	$str = "
	// 	SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, A1.NIK, a.no_urut no_urut
	// 	, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID, D1.NAMA SATKER, COALESCE(JD.JUMLAH_DATA,0) JUMLAH_DATA
	// 	FROM jadwal_awal_tes_simulasi_pegawai A
	// 	INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
	// 	LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
	// 	LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
	// 	LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
	// 	LEFT JOIN
	// 	(
	// 		SELECT a.pegawai_id, b.jadwal_tes_id,count(c.pegawai_id) JUMLAH_DATA
	// 		FROM jadwal_pegawai A
	// 		left join jadwal_asesor b on a.JADWAL_ASESOR_ID=b.JADWAL_ASESOR_ID
	// 		left join cat_pegawai.ujian_pegawai_".$jadwal." c on a.pegawai_id=c.pegawai_id and b.jadwal_tes_id=c.jadwal_tes_id and bank_soal_pilihan_id is not null
	// 			WHERE 1=1
	// 		 group by a.pegawai_id,b.jadwal_tes_id
	// 	) JD ON JADWAL_AWAL_TES_SIMULASI_ID = jd.jadwal_tes_id AND A.PEGAWAI_ID = jd.pegawai_id
	// 	WHERE 1=1
	// 	"; 
		
	// 	while(list($key,$val) = each($paramsArray))
	// 	{
	// 		$str .= " AND $key = '$val' ";
	// 	}
		
	// 	$str .= $statement." ".$sOrder;
	// 	$this->query = $str;
	// 	// echo $str;exit;
	// 	return $this->selectLimit($str,$limit,$from); 
 //    }
	
	function getCountByParamsPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM jadwal_awal_tes_simulasi_pegawai A
		INNER JOIN ".$this->db.".pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN ".$this->db.".pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN ".$this->db.".eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN ".$this->db.".satker D1 ON A1.SATKER_ID = D1.SATKER_ID
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

    function getCountByParamsCekCetakanNew($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM public.jadwal_asesor a
		left join jadwal_acara ja on a.jadwal_acara_id =ja.jadwal_acara_id
		left join Penggalian p on ja.penggalian_id=p.penggalian_id
		WHERE 1=1".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		// echo $str; exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsCekCetakanNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT distinct(A.PEGAWAI_ID), A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, A1.NIK, a.no_urut no_urut
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID, D1.NAMA SATKER, COALESCE(JD.JUMLAH_DATA,0) JUMLAH_DATA
		FROM public.jadwal_asesor a
		left join jadwal_acara ja on a.jadwal_acara_id =ja.jadwal_acara_id
		left join Penggalian p on ja.penggalian_id=p.penggalian_id
		left join simpeg.pegawai sp on ja.penggalian_id=p.penggalian_id
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_awal_tes_simulasi_pegawai WHERE 1=1 ".$statement;
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

    function selectByParamsDataPribadi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
		{
		$str= "
		SELECT 
			A.PEGAWAI_ID PESERTA_ID, NAMA, NIK KTP, NIP_BARU NIP, JENIS_KELAMIN, a.jenjang_jabatan
			, CASE JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA,
			TEMPAT_LAHIR, TGL_LAHIR TANGGAL_LAHIR, AGAMA, LAST_JABATAN JABATAN,
			 A.HP ALAMAT_RUMAH_TELP, A.EMAIL,
			 TMT_CPNS, TMT_PNS
			, A.STATUS_JENIS
			, A.STATUS_KAWIN
			, A.STATUS_PEGAWAI_ID
			, A.ALAMAT
			, A.SOSIAL_MEDIA
			, A.ALAMAT_TEMPAT_KERJA
			, A.TEMPAT_KERJA
			, A.SOSIAL_MEDIA
			, A.AUTO_ANAMNESA
			,A.LAST_PANGKAT_ID
			,A.LAST_ATASAN_LANGSUNG_NAMA
			,A.LAST_ATASAN_LANGSUNG_JABATAN
			, CASE A.STATUS_KAWIN WHEN '1' THEN 'Belum Kawin' WHEN '2' THEN 'Kawin' WHEN '3' THEN 'Janda' WHEN '4' THEN 'Duda'  ELSE 'Belum ada Data' END STATUS_KAWIN_INFO
			, CASE A.STATUS_PEGAWAI_ID WHEN '1' THEN 'CPNS' WHEN '2' THEN 'PNS' END STATUS_PEGAWAI_INFO
			, A.LAST_ESELON_ID
		FROM simpeg.PEGAWAI A
		WHERE 1=1
		"; 
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query= $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }
	
  } 
?>
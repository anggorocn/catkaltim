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

  class Beasiswa extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Beasiswa()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BEASISWA_ID", $this->getNextId("BEASISWA_ID","beasiswa")); 

		$str = "INSERT INTO beasiswa (
				   BEASISWA_ID, PEGAWAI_ID, JENIS, 
				   IPK, SERTIFIKAT_INGGRIS, UNIVERSITAS_ASAL, JURUSAN_ASAL, 
				   AKREDITASI, PDA, PFS, PASCA_SARJANA, UNIVERSITAS,
				   JURUSAN, ORGANISASI_DONOR, NEGARA, TAHUN, STATUS,
				   TANGGAL_MULAI, TANGGAL_SELESAI, 
				   JUDUL, NOMOR, KETERANGAN,
				   LAST_CREATE_USER, LAST_CREATE_DATE, LAST_CREATE_SATKER) 
				VALUES (
				  ".$this->getField("BEASISWA_ID").",
				  '".$this->getField("PEGAWAI_ID")."',
				  '".$this->getField("JENIS")."',
				  '".$this->getField("IPK")."',
				  '".$this->getField("SERTIFIKAT_INGGRIS")."',
				  '".$this->getField("UNIVERSITAS_ASAL")."',
				  '".$this->getField("JURUSAN_ASAL")."',
				  '".$this->getField("AKREDITASI")."',
				  '".$this->getField("PDA")."',
				  '".$this->getField("PFS")."',
				  '".$this->getField("PASCA_SARJANA")."',
				  '".$this->getField("UNIVERSITAS")."',
				  '".$this->getField("JURUSAN")."',
				  '".$this->getField("ORGANISASI_DONOR")."',
				  '".$this->getField("NEGARA")."',
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("STATUS")."',
				  ".$this->getField("TANGGAL_MULAI").",
				  ".$this->getField("TANGGAL_SELESAI").",
				  '".$this->getField("JUDUL")."',
				  '".$this->getField("NOMOR")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_SATKER")."'
				)"; 
				  //'".$this->getField("AKREDITASI")."'
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE beasiswa
				SET    
					   PEGAWAI_ID       = '".$this->getField("PEGAWAI_ID")."',
					   JENIS    = '".$this->getField("JENIS")."',
					   IPK    = '".$this->getField("IPK")."',
					   SERTIFIKAT_INGGRIS             = '".$this->getField("SERTIFIKAT_INGGRIS")."',
					   UNIVERSITAS_ASAL     = '".$this->getField("UNIVERSITAS_ASAL")."',
					   JURUSAN_ASAL    = '".$this->getField("JURUSAN_ASAL")."',
					   AKREDITASI= '".$this->getField("AKREDITASI")."',
					   PDA= '".$this->getField("PDA")."',
					   PFS= '".$this->getField("PFS")."',
					   PASCA_SARJANA= '".$this->getField("PASCA_SARJANA")."',
					   UNIVERSITAS= '".$this->getField("UNIVERSITAS")."',
					   JURUSAN= '".$this->getField("JURUSAN")."',
					   ORGANISASI_DONOR= '".$this->getField("ORGANISASI_DONOR")."',
					   NEGARA= '".$this->getField("NEGARA")."',
					   TAHUN= '".$this->getField("TAHUN")."',
					   STATUS= '".$this->getField("STATUS")."',
					   TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
					   TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI").",
					   JUDUL= '".$this->getField("JUDUL")."',
				  	   NOMOR= '".$this->getField("NOMOR")."',
				  	   KETERANGAN= '".$this->getField("KETERANGAN")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					   LAST_UPDATE_SATKER	= '".$this->getField("LAST_UPDATE_SATKER")."'
				WHERE  BEASISWA_ID          = '".$this->getField("BEASISWA_ID")."'
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }
	
	function update_format()
	{
		$str = "
				UPDATE beasiswa
				SET
					   UKURAN= ".$this->getField("UKURAN").",
					   FORMAT= '".$this->getField("FORMAT")."'
				WHERE  BEASISWA_ID = '".$this->getField("BEASISWA_ID")."' AND PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function selectByParamsBlob($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT FOTO_BLOB, FORMAT
				FROM beasiswa WHERE BEASISWA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement."";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function delete()
	{
		$str1= "DELETE FROM BEASISWA_SERTIFIKAT
                WHERE 
                  BEASISWA_ID = '".$this->getField("BEASISWA_ID")."'"; 
				  
		$this->query = $str1;
        $this->execQuery($str1);
		
        $str = "DELETE FROM beasiswa
                WHERE 
                  BEASISWA_ID = '".$this->getField("BEASISWA_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","SERTIFIKAT_INGGRIS"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM beasiswa A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLookupTahun()
	{
		$str = "SELECT TAHUN FROM beasiswa WHERE 1=1 GROUP BY TAHUN ORDER BY TAHUN ASC"; 
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsLookupNegara()
	{
		$str = "SELECT NEGARA FROM beasiswa WHERE 1=1 GROUP BY NEGARA ORDER BY NEGARA ASC"; 
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsLookupOrganisasiDonor()
	{
		$str = "SELECT ORGANISASI_DONOR FROM beasiswa WHERE 1=1 GROUP BY ORGANISASI_DONOR ORDER BY ORGANISASI_DONOR ASC"; 
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT 
		   BEASISWA_ID, PEGAWAI_ID, JENIS, CASE WHEN JENIS = '1' THEN 'Dalam negeri' WHEN JENIS = '2' THEN 'Luar negeri' ELSE '' END JENIS_NAMA,
		   IPK, SERTIFIKAT_INGGRIS, CASE WHEN SERTIFIKAT_INGGRIS = '1' THEN 'TOEFL' WHEN SERTIFIKAT_INGGRIS = '2' THEN 'IELTS' ELSE '' END SERTIFIKAT_INGGRIS_NAMA,
		   UNIVERSITAS_ASAL, JURUSAN_ASAL, AKREDITASI, PDA, PFS, PASCA_SARJANA, CASE WHEN PASCA_SARJANA = '1' THEN 'S1' WHEN PASCA_SARJANA = '2' THEN 'S2' ELSE '' END PASCA_SARJANA_NAMA,
		   UNIVERSITAS, JURUSAN, ORGANISASI_DONOR, NEGARA, TAHUN,
		   STATUS, CASE WHEN STATUS = '1' THEN 'Pelamar' WHEN STATUS = '2' THEN 'Shortlisting(Luar Negeri)' WHEN STATUS = '3' THEN 'Lulus' WHEN STATUS = '4' THEN 'Tidak Lulus' WHEN STATUS = '5' THEN 'Belum Berangkat(Luar Negeri)' WHEN STATUS = '6' THEN 'Masa Pendidikan' WHEN STATUS = '7' THEN 'Telah Kembali(Luar Negeri)' ELSE '' END STATUS_NAMA,
		   TANGGAL_MULAI, TANGGAL_SELESAI, 
		   JUDUL, NOMOR, KETERANGAN
		FROM beasiswa WHERE BEASISWA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY IPK ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsLaporan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY IPK ASC")
	{
		$str = "
		SELECT 
			CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN , '. ') END) , B.NAMA , (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ' , GELAR_BELAKANG) END)) NAMA_PEGAWAI,
		   B.ALAMAT, B.TELEPON,
		   BEASISWA_ID, A.PEGAWAI_ID, JENIS, CASE WHEN JENIS = '1' THEN 'Dalam negeri' WHEN JENIS = '2' THEN 'Luar negeri' ELSE '' END JENIS_NAMA,
		   IPK, SERTIFIKAT_INGGRIS, CASE WHEN SERTIFIKAT_INGGRIS = '1' THEN 'TOEFL' WHEN SERTIFIKAT_INGGRIS = '2' THEN 'IELTS' ELSE '' END SERTIFIKAT_INGGRIS_NAMA,
		   UNIVERSITAS_ASAL, JURUSAN_ASAL, AKREDITASI, PDA, PFS, PASCA_SARJANA, CASE WHEN PASCA_SARJANA = '1' THEN 'S1' WHEN PASCA_SARJANA = '2' THEN 'S2' ELSE '' END PASCA_SARJANA_NAMA,
		   UNIVERSITAS, JURUSAN, ORGANISASI_DONOR, NEGARA, TAHUN,
		   STATUS, CASE WHEN STATUS = '1' THEN 'Pelamar' WHEN STATUS = '2' THEN 'Shortlisting(Luar Negeri)' WHEN STATUS = '3' THEN 'Lulus' WHEN STATUS = '4' THEN 'Tidak Lulus' WHEN STATUS = '5' THEN 'Belum Berangkat(Luar Negeri)' WHEN STATUS = '6' THEN 'Masa Pendidikan' WHEN STATUS = '7' THEN 'Telah Kembali(Luar Negeri)' ELSE '' END STATUS_NAMA,
		   TANGGAL_MULAI, TANGGAL_SELESAI, 
		   JUDUL, NOMOR, KETERANGAN
		FROM beasiswa A
		LEFT JOIN pegawai B ON A.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE BEASISWA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT BEASISWA_ID, PEGAWAI_ID, JENIS, 
				   SERTIFIKAT_INGGRIS, UNIVERSITAS_ASAL, JURUSAN_ASAL, 
				   IPK
				FROM beasiswa WHERE BEASISWA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY SERTIFIKAT_INGGRIS ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","SERTIFIKAT_INGGRIS"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsLaporan($paramsArray=array())
	{
		$str = "
		SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
		FROM beasiswa A
		LEFT JOIN pegawai B ON A.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE BEASISWA_ID IS NOT NULL "; 
		
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
		
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(BEASISWA_ID) AS ROWCOUNT FROM beasiswa WHERE BEASISWA_ID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(BEASISWA_ID) AS ROWCOUNT FROM beasiswa WHERE BEASISWA_ID IS NOT NULL "; 
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
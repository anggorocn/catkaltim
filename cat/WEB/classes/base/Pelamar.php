<? 
/* *******************************************************************************************************
MODUL NAME 			: PERPUSTAKAAN
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

  class Pelamar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pelamar()
	{
      $this->Entity(); 
    }
	
	function callScrening()
	{
		$str = "SELECT proses_screning()"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_ID", $this->getNextId("PELAMAR_ID","PELAMAR")); 

		$str = "INSERT INTO PELAMAR(
				  PELAMAR_ID, NO_REGISTER, NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, ALAMAT, KOTA,
				  JENIS_KELAMIN, NO_KTP, KTP_FILE, FOTO_FILE, EMAIL1, EMAIL2, NO_HP, STATUS_PELAMAR,
				  TANGGAL_DAFTAR, FORMASI_ID, PASSWORD, NPWP, KTP_BERLAKU, STATUS_KAWIN, AGAMA, KODE_POS, TELP_RUMAH,
				  IS_ALAMAT_KTP, ALAMAT_DOMISILI) 
				VALUES(
				  ".$this->getField("PELAMAR_ID").",
				  '".$this->getField("NO_REGISTER")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR").",
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("KOTA")."',
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("NO_KTP")."',
				  '".$this->getField("KTP_FILE")."',
				  '".$this->getField("FOTO_FILE")."',
				  '".$this->getField("EMAIL1")."',
				  '".$this->getField("EMAIL2")."',
				  '".$this->getField("NO_HP")."',
				  ".$this->getField("STATUS_PELAMAR").",
				  ".$this->getField("TANGGAL_DAFTAR").",
				  ".$this->getField("FORMASI_ID").",
				  '".$this->getField("NPWP")."',
				  ".$this->getField("KTP_BERLAKU").",
				  ".$this->getField("STATUS_KAWIN").",
				  ".$this->getField("AGAMA").",
				  '".$this->getField("KODE_POS")."',
				  '".$this->getField("TELP_RUMAH")."',
				  '".$this->getField("IS_ALAMAT_KTP")."',
				  '".$this->getField("ALAMAT_DOMISILI")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_ID");
		return $this->execQuery($str);
    }
	
	function insertPelamar()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_ID", $this->getNextId("PELAMAR_ID","PELAMAR")); 

		$str = "INSERT INTO PELAMAR(PELAMAR_ID, NO_REGISTER, NAMA, NO_KTP, EMAIL1,EMAIL2, NO_HP, STATUS_PELAMAR, TANGGAL_DAFTAR, PASSWORD)
				VALUES(
				  ".$this->getField("PELAMAR_ID").",
				  '".$this->getField("NO_REGISTER")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("NO_KTP")."',
				  '".$this->getField("EMAIL1")."',
				  '".$this->getField("EMAIL2")."',
				  '".$this->getField("NO_HP")."',
				  ".$this->getField("STATUS_PELAMAR").",
				  ".$this->getField("TANGGAL_DAFTAR").",
				  '".$this->getField("PASSWORD")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  NO_REGISTER= '".$this->getField("NO_REGISTER")."',
				  NAMA= '".$this->getField("NAMA")."',
				  TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
				  TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
				  ALAMAT= '".$this->getField("ALAMAT")."',
				  KOTA= '".$this->getField("KOTA")."',
				  JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
				  NO_KTP= '".$this->getField("NO_KTP")."',
				  KTP_FILE= '".$this->getField("KTP_FILE")."',
				  FOTO_FILE= '".$this->getField("FOTO_FILE")."',
				  PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID").",
				  SEKOLAH_ID= ".$this->getField("SEKOLAH_ID").",
				  JURUSAN_PENDIDIKAN= '".$this->getField("JURUSAN_PENDIDIKAN")."',
				  EMAIL1= '".$this->getField("EMAIL1")."',
				  EMAIL2= '".$this->getField("EMAIL2")."',
				  NO_HP= '".$this->getField("NO_HP")."',
				  IJAZAH_FILE= '".$this->getField("IJAZAH_FILE")."',
				  STATUS_PELAMAR= ".$this->getField("STATUS_PELAMAR").",
				  TRANSKIP_NILAI_FILE= '".$this->getField("TRANSKIP_NILAI_FILE")."',
				  KONVERSI_IPK_LUAR_NEGRI_FILE= '".$this->getField("KONVERSI_IPK_LUAR_NEGRI_FILE")."',
				  IPK= ".$this->getField("IPK").",
				  IS_LULUSAN_LUAR_NEGRI= ".$this->getField("IS_LULUSAN_LUAR_NEGRI").",
				  TANGGAL_DAFTAR= ".$this->getField("TANGGAL_DAFTAR").",
				  FORMASI_ID= ".$this->getField("FORMASI_ID")."
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailManual()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_MANUAL= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailLengkapiData()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_LENGKAPI_DATA= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatusKirimEmail()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  ".$this->getField("FIELD")." = '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailKonfirmasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_KONFIRMASI= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailPengumuman()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_PENGUMUMAN= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailAsesmen()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_ASESMEN= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailWawancara()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_WAWANCARA= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailFinal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_FINAL= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatusKirimEmailSetuju()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  STATUS_KIRIM_EMAIL_SETUJU= '1'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateDaftar()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  IS_DAFTAR= 1
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePelamar()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  NO_KTP= '".$this->getField("NO_KTP")."',
				  NAMA= '".$this->getField("NAMA")."',
				  TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
				  TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
				  ALAMAT= '".$this->getField("ALAMAT")."',
				  KOTA= '".$this->getField("KOTA")."',
				  JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
				  PROPINSI_ID= ".$this->getField("PROPINSI_ID").",
				  TELP_RUMAH= '".$this->getField("TELP_RUMAH")."',
				  ALAMAT_DOMISILI= '".$this->getField("ALAMAT_DOMISILI")."',
				  IS_ALAMAT_KTP= ".$this->getField("IS_ALAMAT_KTP").",
				  KODE_POS= '".$this->getField("KODE_POS")."',
				  AGAMA= ".$this->getField("AGAMA").",
				  STATUS_KAWIN= ".$this->getField("STATUS_KAWIN").",
				  NPWP= '".$this->getField("NPWP")."',
				  KTP_BERLAKU= ".$this->getField("KTP_BERLAKU")."
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateValidasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PELAMAR SET
				  VALIDASI_STATUS= '".$this->getField("VALIDASI_STATUS")."',
				  VALIDASI_KETERANGAN= '".$this->getField("VALIDASI_KETERANGAN")."'
				WHERE PELAMAR_ID= '".$this->getField("PELAMAR_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE ".$this->getField("FIELD_ID")." = '".$this->getField("FIELD_VALUE_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM PELAMAR
                WHERE 
                  PELAMAR_ID = '".$this->getField("PELAMAR_ID")."'
			"; 
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT A.PEGAWAI_ID PELAMAR_ID, A.NIP_BARU, md5(CAST(A.PEGAWAI_ID as TEXT)) PELAMAR_ID_ENKRIP, NULL NO_REGISTER, NAMA, TEMPAT_LAHIR, ALAMAT, NULL KOTA,
		JENIS_KELAMIN, CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA,TGL_LAHIR
		FROM simpeg.pegawai A
		WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT 
				  A.PELAMAR_ID, A.NO_REGISTER, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.NPP,
				  REPLACE(REPLACE(REPLACE(CAST(age(A.TANGGAL_LAHIR) as text), 'years', 'Tahun'), 'mons', 'Bulan'), 'day', 'Hari') UMUR,
				  A.ALAMAT, A.KOTA, A.KK_NOMOR,
				  A.JENIS_KELAMIN, A.NO_KTP, A.KTP_FILE, A.FOTO_FILE, A.EMAIL1, A.EMAIL2, A.NO_HP, A.STATUS_PELAMAR,
				  A.TANGGAL_DAFTAR, A.FORMASI_ID, A.PASSWORD, A.NPWP, A.KTP_BERLAKU, A.STATUS_KAWIN, A.AGAMA, A.KODE_POS, A.TELP_RUMAH,
				  A.IS_ALAMAT_KTP, A.ALAMAT_DOMISILI, A.PROPINSI_ID, B.PROPINSI, A.KOTA_ID, A.PROPINSI_DOMISILI_ID, A.KOTA_DOMISILI_ID,
				  A.IS_DAFTAR, A.IS_STATUS_ISI_FORMULIR,
				  CASE WHEN A.IS_PERNYATAAN = '' OR A.IS_PERNYATAAN IS NULL THEN 'pendaftaran'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 1 THEN 'data_pribadi_pangkat'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 2 THEN 'data_pribadi_jabatan'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 3 THEN 'data_pribadi_pendidikan'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 4 THEN 'data_pribadi_pelatihan'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 5 THEN 'data_pribadi_penugasan'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 6 THEN 'data_pribadi_lain'
				  WHEN A.IS_STATUS_ISI_FORMULIR = 7 THEN 'dokumen_download'
				  ELSE 'data_pribadi' END IS_STATUS_ISI_FORMULIR_INFO, A.PELAMAR_KE, A.IS_KIRIM_LAMARAN, A.IS_LOGIN_PERTAMA, A.IS_PERNYATAAN
				  , B.PROPINSI PROPINSI_NAMA, C.KABUPATEN KOTA_NAMA, D.PROPINSI PROPINSI_DOMISILI_NAMA, E.KABUPATEN KOTA_DOMISILI_NAMA
				  , CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' ELSE 'Perempuan' END JENIS_KELAMIN_NAMA
				  , CASE A.STATUS_KAWIN WHEN '1' THEN 'Menikah' WHEN '2' THEN 'Janda' WHEN '3' THEN 'Duda' WHEN '0' THEN 'Belum Menikah' ELSE 'Belum Dipilih' END STATUS_KAWIN_NAMA
				  , CASE A.AGAMA WHEN '1' THEN 'Islam' WHEN '2' THEN 'Katolik' WHEN '3' THEN 'Protestan' WHEN '4' THEN 'Hindu' WHEN '5' THEN 'Budha' WHEN '6' THEN 'Konghucu' ELSE 'Belum Dipilih' END AGAMA_NAMA
				  , CASE A.JABATAN_TERAKHIR WHEN '11' THEN 'I/a' WHEN '12' THEN 'I/b' WHEN '13' THEN 'I/c' WHEN '14' THEN 'I/d' WHEN '21' THEN 'II/a' WHEN '22' THEN 'II/b' WHEN '23' THEN 'II/c' WHEN '24' THEN 'II/d' ELSE 'Belum Dipilih' END JABATAN_TERAKHIR_NAMA
				  , CASE A.PANGKAT_ID WHEN 11 THEN 'I/a' WHEN 12 THEN 'I/b' WHEN 13 THEN 'I/c' WHEN 14 THEN 'I/d' 
				    WHEN 21 THEN 'II/a' WHEN 22 THEN 'II/b' WHEN 23 THEN 'II/c' WHEN 24 THEN 'II/d' 
					WHEN 31 THEN 'III/a' WHEN 32 THEN 'III/b' WHEN 33 THEN 'III/c' WHEN 34 THEN 'III/d' 
					WHEN 41 THEN 'IV/a' WHEN 42 THEN 'IV/b' WHEN 43 THEN 'IV/c' WHEN 44 THEN 'IV/d' WHEN 45 THEN 'IV/e' 
					ELSE 'Belum Dipilih' 
					END PANGKAT_NAMA
				  , CASE A.IS_ALAMAT_KTP WHEN '1' THEN 'Ya' ELSE 'Tidak' END IS_ALAMAT_KTP_NAMA, A.PASSWORD_DAFTAR, A.PASSWORD_DAFTAR_SMS
				  , A.PENDIDIKAN_ID, A.PANGKAT_ID, A.TOTAL_TAHUN_BEKERJA
				  , A.TMT_CPNS, A.JABATAN_TERAKHIR, A.JUMLAH_ANAK, A.VALIDASI_STATUS, A.VALIDASI_KETERANGAN
				  , A.STATUS_NON_PNS
				FROM PELAMAR A 
				LEFT JOIN PROPINSI B ON B.PROPINSI_ID = A.PROPINSI_ID
				LEFT JOIN KABUPATEN C ON C.KABUPATEN_ID = A.KOTA_ID AND B.PROPINSI_ID = C.PROPINSI_ID
				LEFT JOIN PROPINSI D ON D.PROPINSI_ID = A.PROPINSI_DOMISILI_ID
				LEFT JOIN KABUPATEN E ON E.KABUPATEN_ID = A.KOTA_DOMISILI_ID AND D.PROPINSI_ID = E.PROPINSI_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.PELAMAR_ID, A.NO_REGISTER, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.NO_KTP,
              	  A.EMAIL1, A.EMAIL2, A.NO_HP, A.STATUS_PELAMAR, A.TANGGAL_DAFTAR, REPLACE(REPLACE(REPLACE(REPLACE(CAST(age(A.TANGGAL_LAHIR) as text), 'years', 'Tahun'), 'mons', 'Bulan'), 'days', 'Hari'), 'mon', 'Bulan') UMUR,
            	  B.INFO_STATUS_PELAMAR, D.NAMA POSISI,
				  CASE WHEN B.INFO_STATUS_PELAMAR = '1' THEN 'Baru belum validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '2' THEN 'Sudah validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '3' THEN 'Sedang melengkapi data'
				  WHEN B.INFO_STATUS_PELAMAR = '4' THEN 'Biodata lengkap'
				  WHEN B.INFO_STATUS_PELAMAR = '5' THEN 'Selesai melamar'
				  END STATUS_PELAMAR_INFO
				  , A.VALIDASI_STATUS, A.VALIDASI_KETERANGAN
				  , CASE A.VALIDASI_STATUS WHEN '1' THEN 'Oke Verifikasi' WHEN '2' THEN 'Tidak Oke Verifikasi' ELSE 'Belum Verifikasi' END VALIDASI_STATUS_INFO
				  , A.STATUS_KIRIM_EMAIL_MANUAL, A.STATUS_KIRIM_EMAIL_LENGKAPI_DATA, A.STATUS_KIRIM_EMAIL_SETUJU
				  , A.STATUS_KIRIM_EMAIL_PENDIDIKAN
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				LEFT JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringKonfirmasi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.PELAMAR_ID, A.NO_REGISTER, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.NO_KTP,
              	  A.EMAIL1, A.EMAIL2, A.NO_HP, A.STATUS_PELAMAR, A.TANGGAL_DAFTAR, REPLACE(REPLACE(REPLACE(REPLACE(CAST(age(A.TANGGAL_LAHIR) as text), 'years', 'Tahun'), 'mons', 'Bulan'), 'days', 'Hari'), 'mon', 'Bulan') UMUR,
            	  B.INFO_STATUS_PELAMAR, D.NAMA POSISI,
				  CASE WHEN B.INFO_STATUS_PELAMAR = '1' THEN 'Baru belum validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '2' THEN 'Sudah validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '3' THEN 'Sedang melengkapi data'
				  WHEN B.INFO_STATUS_PELAMAR = '4' THEN 'Biodata lengkap'
				  WHEN B.INFO_STATUS_PELAMAR = '5' THEN 'Selesai melamar'
				  END STATUS_PELAMAR_INFO
				  , CASE WHEN C.IS_STATUS_LOLOS = '1' THEN 'Lolos' ELSE 'Tidak Lolos' END IS_STATUS_LOLOS_NAMA
				  , A.VALIDASI_STATUS, A.VALIDASI_KETERANGAN
				  , CASE A.VALIDASI_STATUS WHEN '1' THEN 'Oke Verifikasi' WHEN '2' THEN 'Tidak Oke Verifikasi' ELSE 'Belum Verifikasi' END VALIDASI_STATUS_INFO
				  , A.STATUS_KIRIM_EMAIL_MANUAL, A.STATUS_KIRIM_EMAIL_LENGKAPI_DATA, A.STATUS_KIRIM_EMAIL_SETUJU
				  , A.STATUS_KIRIM_EMAIL_KONFIRMASI
				  , PENDIDIKAN_NAMA
				  , PPJR.DATA_TAHUN TAHUN_PENGALAMAN_RELEVAN, PPJR.DATA_BULAN BULAN_PENGALAMAN_RELEVAN
				  , COALESCE(PPJR.DATA_BULAN % 12,0) BULAN_SISA_PENGALAMAN_RELEVAN
				  , PPJ.DATA_TAHUN TAHUN_PENGALAMAN, PPJ.DATA_BULAN BULAN_PENGALAMAN
				  , COALESCE(PPJ.DATA_BULAN % 12,0) BULAN_SISA_PENGALAMAN
				  , CASE WHEN A.PANGKAT_ID = 41 THEN 'IV/a' WHEN A.PANGKAT_ID = 42 THEN 'IV/b' WHEN A.PANGKAT_ID = 43 THEN 'IV/c' WHEN A.PANGKAT_ID = 44 THEN 'IV/d' WHEN A.PANGKAT_ID = 45 THEN 'IV/e' END PANGKAT_NAMA
				  , A.STATUS_NON_PNS, AMBIL_PELAMAR_JABATAN_RELEVAN_TERAKHIR(A.PELAMAR_ID) JABATAN_TERAKHIR
				  , TAHUN_AWAL_KERJA
				  , C.KELOMPOK_TES, C.NO_TES, A.STATUS_KIRIM_EMAIL_PENGUMUMAN, A.STATUS_KIRIM_EMAIL_ASESMEN, A.TANGGAL_WAWANCARA, A.STATUS_KIRIM_EMAIL_WAWANCARA
				  , A.KODE_WAWANCARA, A.RUANG_WAWANCARA, A.TANGGAL_KESEHATAN, A.TANGGAL_KELAYAKAN, A.JAM_KELAYAKAN, A.STATUS_KIRIM_EMAIL_FINAL
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				INNER JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				LEFT JOIN PELAMAR_PENGALAMAN_JABATAN PPJ ON PPJ.PELAMAR_ID = A.PELAMAR_ID
				LEFT JOIN PELAMAR_PENGALAMAN_JABATAN_RELEVAN PPJR ON PPJR.PELAMAR_ID = A.PELAMAR_ID
				LEFT JOIN 
				(
					SELECT PELAMAR_ID, MIN(TO_CHAR(TANGGAL_AWAL, 'YYYY')) TAHUN_AWAL_KERJA
					FROM PELAMAR_JABATAN
					GROUP BY PELAMAR_ID
				) PJTA ON PJTA.PELAMAR_ID = A.PELAMAR_ID
				LEFT JOIN
				(
					SELECT PELAMAR_ID, PENDIDIKAN_ID
					, CASE WHEN PENDIDIKAN_ID = 7 THEN 'D4' WHEN PENDIDIKAN_ID = 8 THEN 'S1' WHEN PENDIDIKAN_ID = 9 THEN 'S2' WHEN PENDIDIKAN_ID = 10 THEN 'S3' END PENDIDIKAN_NAMA
					FROM PELAMAR_PENDIDIKAN 
				) PPEN ON A.PELAMAR_ID = PPEN.PELAMAR_ID
				,KONFIGURASI K
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringPelamar($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.PELAMAR_ID, A.NO_REGISTER, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.NO_KTP,
              	  A.EMAIL1, A.EMAIL2, A.NO_HP, A.STATUS_PELAMAR, D.NAMA POSISI,
            	  B.INFO_STATUS_PELAMAR, A.TANGGAL_LAMAR,
				  CASE WHEN B.INFO_STATUS_PELAMAR = '1' THEN 'Baru belum validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '2' THEN 'Sudah validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '3' THEN 'Sedang melengkapi data'
				  WHEN B.INFO_STATUS_PELAMAR = '4' THEN 'Biodata lengkap'
				  WHEN B.INFO_STATUS_PELAMAR = '5' THEN 'Selesai melamar'
				  END STATUS_PELAMAR_INFO,
				  CASE WHEN C.IS_STATUS_LOLOS = '1' THEN 'Lolos' ELSE 'Tidak Lolos' END IS_STATUS_LOLOS_NAMA,
				  D.KODE || '-' || GENERATEZERO(CAST(A.PELAMAR_KE AS TEXT),2) KODE_REGISTRASI
				  , REPLACE(REPLACE(REPLACE(REPLACE(CAST(age(TO_DATE('2018-06-01', 'YYYY-MM-DD'), A.TANGGAL_LAHIR) as text), 'years', 'Tahun'), 'mons', 'Bulan'), 'days', 'Hari'), 'mon', 'Bulan') UMUR
				  , REPLACE(REPLACE(REPLACE(REPLACE(CAST(age(K.TANGGAL_PENUTUPAN, A.TANGGAL_LAHIR) as text), 'years', 'Tahun'), 'mons', 'Bulan'), 'days', 'Hari'), 'mon', 'Bulan') UMUR1
				  , PENDIDIKAN_NAMA
				  , PPJR.DATA_TAHUN TAHUN_PENGALAMAN_RELEVAN, PPJR.DATA_BULAN BULAN_PENGALAMAN_RELEVAN
				  , COALESCE(PPJR.DATA_BULAN % 12,0) BULAN_SISA_PENGALAMAN_RELEVAN
				  , PPJ.DATA_TAHUN TAHUN_PENGALAMAN, PPJ.DATA_BULAN BULAN_PENGALAMAN
				  , COALESCE(PPJ.DATA_BULAN % 12,0) BULAN_SISA_PENGALAMAN
				  , CASE WHEN A.PANGKAT_ID = 41 THEN 'IV/a' WHEN A.PANGKAT_ID = 42 THEN 'IV/b' WHEN A.PANGKAT_ID = 43 THEN 'IV/c' WHEN A.PANGKAT_ID = 44 THEN 'IV/d' WHEN A.PANGKAT_ID = 45 THEN 'IV/e' END PANGKAT_NAMA
				  , A.STATUS_NON_PNS, AMBIL_PELAMAR_JABATAN_RELEVAN_TERAKHIR(A.PELAMAR_ID) JABATAN_TERAKHIR
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				INNER JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				LEFT JOIN PELAMAR_PENGALAMAN_JABATAN PPJ ON PPJ.PELAMAR_ID = A.PELAMAR_ID
				LEFT JOIN PELAMAR_PENGALAMAN_JABATAN_RELEVAN PPJR ON PPJR.PELAMAR_ID = A.PELAMAR_ID
				LEFT JOIN
				(
					SELECT A.PELAMAR_ID, A.PENDIDIKAN_ID
					, CASE WHEN A.PENDIDIKAN_ID = 7 THEN 'D4' WHEN A.PENDIDIKAN_ID = 8 THEN 'S1' WHEN A.PENDIDIKAN_ID = 9 THEN 'S2' WHEN A.PENDIDIKAN_ID = 10 THEN 'S3' END PENDIDIKAN_NAMA1
					, B.PENDIDIKAN PENDIDIKAN_NAMA
					FROM PELAMAR_PENDIDIKAN A
					INNER JOIN PENDIDIKAN B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
				) PPEN ON A.PELAMAR_ID = PPEN.PELAMAR_ID
				,KONFIGURASI K
				WHERE 1=1 "; 
				
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringPelamarInfo($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.PELAMAR_ID, A.NO_REGISTER, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.NO_KTP,
              	  A.EMAIL1, A.EMAIL2, A.NO_HP, A.STATUS_PELAMAR, D.NAMA POSISI,
            	  B.INFO_STATUS_PELAMAR, A.TANGGAL_LAMAR,
				  CASE WHEN B.INFO_STATUS_PELAMAR = '1' THEN 'Baru belum validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '2' THEN 'Sudah validasi'
				  WHEN B.INFO_STATUS_PELAMAR = '3' THEN 'Sedang melengkapi data'
				  WHEN B.INFO_STATUS_PELAMAR = '4' THEN 'Biodata lengkap'
				  WHEN B.INFO_STATUS_PELAMAR = '5' THEN 'Selesai melamar'
				  END STATUS_PELAMAR_INFO,
				  CASE WHEN C.IS_STATUS_LOLOS = '1' THEN 'Lolos' ELSE 'Tidak Lolos' END IS_STATUS_LOLOS_NAMA,
				  D.KODE || '-' || GENERATEZERO(CAST(A.PELAMAR_KE AS TEXT),2) KODE_REGISTRASI
				  , A.PELAMAR_INFORMASI
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				INNER JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				WHERE 1=1 "; 
				
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT COUNT(1) AS JUMLAH
				FROM PELAMAR A
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahPropinsiMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT COUNT(1) AS JUMLAH, A.PROPINSI
				FROM PROPINSI A
				INNER JOIN PELAMAR B ON B.PROPINSI_ID = A.PROPINSI_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." GROUP BY A.PROPINSI ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahPropinsiPeriodikMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT COUNT(1) AS JUMLAH, A.PROPINSI
				FROM PROPINSI A
				INNER JOIN PELAMAR B ON B.PROPINSI_ID = A.PROPINSI_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." GROUP BY A.PROPINSI ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahPosisiMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT COUNT(1) AS JUMLAH, A.NAMA
				FROM FORMASI_DETIL A
				INNER JOIN PELAMAR B ON B.FORMASI_ID = A.FORMASI_DETIL_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." GROUP BY A.NAMA ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJumlahJenisKelaminMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT COUNT(JENIS_KELAMIN) AS JUMLAH, 
				CASE WHEN A.JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN A.JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE 'Belum di isi' END NAMA
				FROM PELAMAR A
				WHERE 1=1 AND A.JENIS_KELAMIN IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." GROUP BY A.JENIS_KELAMIN ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsTahapanCutOffData($cutOff, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="")
	{
		$str = "
				SELECT A.PELAMAR_ID, A.NAMA, JENIS_KELAMIN, TEMPAT_LAHIR,
					   TANGGAL_LAHIR, STATUS_KAWIN, ALAMAT, A.EMAIL1,
					   NPWP, NO_KTP, 
					   CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET,
					   A.KOTA_ID
				FROM PELAMAR A
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				WHERE 1 = 1 
			"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement.$sOrder;
		
		//" ORDER BY A.NAMA ASC"
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsNoMember($statement="")
	{
		$str = "
		SELECT COALESCE(SUBSTRING(CAST(MAX(CAST(NO_REGISTER AS INTEGER)) + 1 AS TEXT) FROM 6), '00001') ROWCOUNT
		FROM public.PELAMAR
		WHERE 1=1 ".$statement; 
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PELAMAR WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsJumlahJenisKelaminMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
		SELECT COUNT(JENIS_KELAMIN) AS JUMLAH, 
		CASE WHEN A.JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN A.JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE 'Belum di isi' END NAMA
		FROM PELAMAR A
		WHERE 1=1
		AND A.JENIS_KELAMIN IS NOT NULL
		".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str.= " GROUP BY A.JENIS_KELAMIN) A";
		
		$this->query = $str; 
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsJumlahPosisiMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
		SELECT COUNT(1) AS JUMLAH, A.NAMA
		FROM FORMASI_DETIL A
		INNER JOIN PELAMAR B ON B.FORMASI_ID = A.FORMASI_DETIL_ID
		WHERE 1=1
		".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str.= " GROUP BY A.NAMA) A";
		
		$this->query = $str; 
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsJumlahPropinsiMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
		SELECT COUNT(1) AS JUMLAH, A.PROPINSI
		FROM PROPINSI A
		INNER JOIN PELAMAR B ON B.PROPINSI_ID = A.PROPINSI_ID
		WHERE 1=1
		".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str.= " GROUP BY A.PROPINSI) A";
		
		$this->query = $str; 
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsMonitoringKonfirmasi($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				INNER JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				,KONFIGURASI K
				WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsMonitoringPelamar($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				INNER JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM PELAMAR A 
				LEFT JOIN PELAMAR_STATUS_INFO B ON A.PELAMAR_ID = B.PELAMAR_ID
				LEFT JOIN FORMASI_DETIL D ON A.FORMASI_ID = D.FORMASI_DETIL_ID
				WHERE 1=1 ".$statement; 
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
		
	function getCountByParamsTahapanCutOffData($paramsArray=array(), $statement="")
	{
		$str = "SELECT 
				COUNT(1) ROWCOUNT
                FROM PELAMAR A
				INNER JOIN PELAMAR_HASIL C ON A.PELAMAR_ID = C.PELAMAR_ID
				WHERE 1 = 1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>
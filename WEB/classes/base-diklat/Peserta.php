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

  class Peserta extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Peserta()
	{
      $this->Entity(); 
    }
     function insertsaudara()
	{
		$this->setField("SAUDARA_ID", $this->getNextId("SAUDARA_ID","simpeg.saudara")); 

		$str = "
		INSERT INTO simpeg.saudara
		(
			SAUDARA_ID, PEGAWAI_ID, NAMA, JENIS_KELAMIN, TEMPAT,TGL_LAHIR,PENDIDIKAN,PEKERJAAN,STATUS
		) 
		VALUES(
			".$this->getField("SAUDARA_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("NAMA")."',
			'".$this->getField("JENIS_KELAMIN")."',
			'".$this->getField("TEMPAT")."',
			".$this->getField("TGL_LAHIR").",
			'".$this->getField("PENDIDIKAN")."',
			'".$this->getField("PEKERJAAN")."',
			'".$this->getField("STATUS")."'
		)"; 

		$this->query = $str;
				// echo $str;exit();

		$this->id = $this->getField("SAUDARA_ID");
		return $this->execQuery($str);
    }

    function insertRegistrasi()
	{
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","simpeg.PEGAWAI"));
		$str= "
		INSERT INTO simpeg.PEGAWAI
		(
			PEGAWAI_ID, NAMA, NIP_BARU, EMAIL, STATUS_JENIS
		)
		VALUES
		(
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("NAMA")."',	
			'".$this->getField("NIP")."',
			'".$this->getField("EMAIL")."',
			".$this->getField("STATUS_JENIS")."
		)"; 
		$this->id= $this->getField("PEGAWAI_ID");
		$this->query= $str;
		// echo $str->query; exit();
		return $this->execQuery($str);
    }


    function insertRegistrasiLuar()
	{
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","simpeg.PEGAWAI"));
		$str= "
		INSERT INTO simpeg.PEGAWAI
		(
			PEGAWAI_ID, NAMA, NIK, NIP_BARU, EMAIL, STATUS_JENIS, LAST_ESELON_ID
		)
		VALUES
		(
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("NAMA")."',	
			'".$this->getField("NIP")."',
			'".$this->getField("NIP")."',
			'".$this->getField("EMAIL")."',
			".$this->getField("STATUS_JENIS").", 99
		)"; 
		$this->id= $this->getField("PEGAWAI_ID");
		$this->query= $str;
		// echo $str->query; exit();
		return $this->execQuery($str);
    }

    function insertRegistrasiLain()
	{
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","simpeg.PEGAWAI"));
		$str= "
		INSERT INTO simpeg.PEGAWAI
		(
			PEGAWAI_ID, NAMA, NIK, EMAIL, STATUS_JENIS
		)
		VALUES
		(
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("NAMA")."',	
			'".$this->getField("NIP")."',
			'".$this->getField("EMAIL")."',
			".$this->getField("STATUS_JENIS")."
		)"; 
		$this->id= $this->getField("PEGAWAI_ID");
		$this->query= $str;
		// echo $str->query; exit();
		return $this->execQuery($str);
    }
    function insertDataPekerjaan()
	{
		$this->setField("DATA_PEKERJAAN_ID", $this->getNextId("DATA_PEKERJAAN_ID","simpeg.DATA_PEKERJAAN"));
		$str= "
		INSERT INTO simpeg.DATA_PEKERJAAN
		(
			DATA_PEKERJAAN_ID, PEGAWAI_ID, GAMBARAN, URAIKAN, TANGGUNG_JAWAB
		)
		VALUES
		(
			".$this->getField("DATA_PEKERJAAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("GAMBARAN")."',	
			'".$this->getField("URAIKAN")."',
			'".$this->getField("TANGGUNG_JAWAB")."'
		)"; 
		$this->id= $this->getField("DATA_PEKERJAAN_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
     function updateDataPekerjaan()
	{
		$str= "
		UPDATE simpeg.DATA_PEKERJAAN
		SET 
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			GAMBARAN= '".$this->getField("GAMBARAN")."',
			URAIKAN= '".$this->getField("URAIKAN")."',
			TANGGUNG_JAWAB= '".$this->getField("TANGGUNG_JAWAB")."'
		WHERE DATA_PEKERJAAN_ID= ".$this->getField("DATA_PEKERJAAN_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
     function insertKondisiKerja()
	{
		$this->setField("KONDISI_KERJA_ID", $this->getNextId("KONDISI_KERJA_ID","simpeg.KONDISI_KERJA"));
		$str= "
		INSERT INTO simpeg.KONDISI_KERJA
		(
			KONDISI_KERJA_ID, PEGAWAI_ID, BAIK_ID,CUKUP_ID,PERLU_ID, KONDISI, ASPEK
		)
		VALUES
		(
			".$this->getField("KONDISI_KERJA_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("BAIK_ID").",
			".$this->getField("CUKUP_ID").",
			".$this->getField("PERLU_ID").",
			'".$this->getField("KONDISI")."',	
			'".$this->getField("ASPEK")."'
		)"; 
		$this->id= $this->getField("KONDISI_KERJA_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
     function updateKondisiKerja()
	{
		$str= "
		UPDATE simpeg.KONDISI_KERJA
		SET 
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			BAIK_ID= ".$this->getField("BAIK_ID").",
			CUKUP_ID= ".$this->getField("CUKUP_ID").",
			PERLU_ID= ".$this->getField("PERLU_ID").",
			KONDISI= '".$this->getField("KONDISI")."',
			ASPEK= '".$this->getField("ASPEK")."'
		WHERE KONDISI_KERJA_ID= ".$this->getField("KONDISI_KERJA_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function insertMinat()
	{
		$this->setField("MINAT_HARAPAN_ID", $this->getNextId("MINAT_HARAPAN_ID","simpeg.MINAT_HARAPAN"));
		$str= "
		INSERT INTO simpeg.MINAT_HARAPAN
		(
			MINAT_HARAPAN_ID, PEGAWAI_ID, SUKAI, TIDAK_SUKAI
		)
		VALUES
		(
			".$this->getField("MINAT_HARAPAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("SUKAI")."',	
			'".$this->getField("TIDAK_SUKAI")."'
		)"; 
		$this->id= $this->getField("MINAT_HARAPAN_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
     function updateMinat()
	{
		$str= "
		UPDATE simpeg.MINAT_HARAPAN
		SET 
			MINAT_HARAPAN_ID= ".$this->getField("MINAT_HARAPAN_ID").",
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			SUKAI= '".$this->getField("SUKAI")."',
			TIDAK_SUKAI= '".$this->getField("TIDAK_SUKAI")."'
		WHERE MINAT_HARAPAN_ID= ".$this->getField("MINAT_HARAPAN_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

     function insertKekuatan()
	{
		$this->setField("KEKUATAN_KELEMAHAN_ID", $this->getNextId("KEKUATAN_KELEMAHAN_ID","simpeg.KEKUATAN_KELEMAHAN"));
		$str= "
		INSERT INTO simpeg.KEKUATAN_KELEMAHAN
		(
			KEKUATAN_KELEMAHAN_ID, PEGAWAI_ID, KEKUATAN,KELEMAHAN
		)
		VALUES
		(
			".$this->getField("KEKUATAN_KELEMAHAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("KEKUATAN")."',	
			'".$this->getField("KELEMAHAN")."'
		)"; 
		$this->id= $this->getField("KEKUATAN_KELEMAHAN_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
     function updateKekuatan()
	{
		$str= "
		UPDATE simpeg.KEKUATAN_KELEMAHAN
		SET 
			KEKUATAN_KELEMAHAN_ID= ".$this->getField("KEKUATAN_KELEMAHAN_ID").",
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			KEKUATAN= '".$this->getField("KEKUATAN")."',
			KELEMAHAN= '".$this->getField("KELEMAHAN")."'
		WHERE KEKUATAN_KELEMAHAN_ID= ".$this->getField("KEKUATAN_KELEMAHAN_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function updatePassword()
	{
		$str= "
		UPDATE cat.USER_APP
		SET 
			USER_PASS= '".$this->getField("PASSWORD_LOGIN")."'
		WHERE PEGAWAI_ID= ".$this->getField("PESERTA_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str= "
		UPDATE simpeg.PEGAWAI
		SET 
			NIK= '".$this->getField("NIK")."',
			NAMA= '".$this->getField("NAMA")."',
			JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
			TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
			TGL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
			AGAMA= '".$this->getField("AGAMA")."',
			EMAIL= '".$this->getField("EMAIL")."',
			HP= '".$this->getField("HP")."'
		WHERE PEGAWAI_ID= ".$this->getField("PESERTA_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function updateDataPribadi()
	{
		$str= "
		UPDATE simpeg.PEGAWAI
		SET 
			NIK= '".$this->getField("NIK")."',
			NAMA= '".$this->getField("NAMA")."',
			JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
			TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
			TGL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
			AGAMA= '".$this->getField("AGAMA")."',
			TEMPAT_KERJA= '".$this->getField("TEMPAT_KERJA")."',
			ALAMAT_TEMPAT_KERJA= '".$this->getField("ALAMAT_TEMPAT_KERJA")."',
			EMAIL= '".$this->getField("EMAIL")."',
			HP= '".$this->getField("HP")."',
			STATUS_KAWIN= '".$this->getField("STATUS_KAWIN")."',
			ALAMAT= '".$this->getField("ALAMAT")."',
			LAST_PANGKAT_ID= ".$this->getField("LAST_PANGKAT_ID").",
			LAST_ATASAN_LANGSUNG_NAMA= '".$this->getField("LAST_ATASAN_LANGSUNG_NAMA")."',
			LAST_ATASAN_LANGSUNG_JABATAN= '".$this->getField("LAST_ATASAN_LANGSUNG_JABATAN")."'

		WHERE PEGAWAI_ID= ".$this->getField("PESERTA_ID")."
		"; 
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PESERTA_ID", $this->getNextId("PESERTA_ID","PESERTA")); 
		$str= "
			INSERT INTO PESERTA
			(PESERTA_ID, UNIT_KERJA_NAMA, UNIT_KERJA_KOTA, KTP, NAMA, NIP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, AGAMA, GOL_RUANG, JABATAN, ALAMAT_RUMAH, ALAMAT_RUMAH_KAB_KOTA, ALAMAT_RUMAH_TELP,
			 ALAMAT_RUMAH_FAX, ALAMAT_KANTOR, ALAMAT_KANTOR_TELP, ALAMAT_KANTOR_FAX, NPWP, PENDIDIKAN_TERAKHIR, PELATIHAN, EMAIL, KONTAK_DARURAT_NAMA, KONTAK_DARURAT_HP, PASSWORD_LOGIN, PASSWORD_LOGIN_DEKRIP, GELAR, TMT_GOLONGAN, TMT_CPNS, TMT_PNS, TMT_JABATAN, TMT_MUTASI)
		  	VALUES(
				  ".$this->getField("PESERTA_ID").",
				  '".$this->getField("UNIT_KERJA_NAMA")."',
				  '".$this->getField("UNIT_KERJA_KOTA")."',
				  '".$this->getField("KTP")."',
				  '".$this->getField("NAMA")."',	
				  '".$this->getField("NIP")."',
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR").",
				  '".$this->getField("AGAMA")."',
				  '".$this->getField("GOL_RUANG")."',
				  '".$this->getField("JABATAN")."',	
				  '".$this->getField("ALAMAT_RUMAH")."',
				  '".$this->getField("ALAMAT_RUMAH_KAB_KOTA")."',
				  '".$this->getField("ALAMAT_RUMAH_TELP")."',
				  '".$this->getField("ALAMAT_RUMAH_FAX")."',
				  '".$this->getField("ALAMAT_KANTOR")."',
				  '".$this->getField("ALAMAT_KANTOR_TELP")."',
				  '".$this->getField("ALAMAT_KANTOR_FAX")."',
				  '".$this->getField("NPWP")."',
				  '".$this->getField("PENDIDIKAN_TERAKHIR")."',
				  '".$this->getField("PELATIHAN")."',
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("KONTAK_DARURAT_NAMA")."',
				  '".$this->getField("KONTAK_DARURAT_HP")."',
				  '".$this->getField("GELAR")."',
				  ".$this->getField("TMT_GOLONGAN").",
				  ".$this->getField("TMT_CPNS").",
				  ".$this->getField("TMT_PNS").",
				  ".$this->getField("TMT_JABATAN").",
				  ".$this->getField("TMT_MUTASI").",
				  '21232f297a57a5a743894a0e4a801fc3',
				  'admin'
				)"; 
		$this->id= $this->getField("PESERTA_ID");
		$this->query= $str;
		// echo $str->query; exit();
		return $this->execQuery($str);
    }
	
	function insertAdmin()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PESERTA_ID", $this->getNextId("PESERTA_ID","PESERTA")); 
		$str= "
			INSERT INTO PESERTA
			(PESERTA_ID, UNIT_KERJA_NAMA, UNIT_KERJA_KOTA, KTP, NAMA, NIP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, AGAMA, GOL_RUANG, JABATAN, ALAMAT_RUMAH, ALAMAT_RUMAH_KAB_KOTA, KOTA, ALAMAT_RUMAH_TELP,
			 ALAMAT_RUMAH_FAX, ALAMAT_KANTOR, ALAMAT_KANTOR_TELP, ALAMAT_KANTOR_FAX, NPWP, PENDIDIKAN_TERAKHIR, PELATIHAN, EMAIL, KONTAK_DARURAT_NAMA, KONTAK_DARURAT_HP, PASSWORD_LOGIN, PASSWORD_LOGIN_DEKRIP, GELAR, TMT_GOLONGAN, TMT_CPNS, TMT_PNS, TMT_JABATAN, TMT_MUTASI
			 	, M_ESELON_ID
			 ) 
		  	VALUES(
				  ".$this->getField("PESERTA_ID").",
				  '".$this->getField("UNIT_KERJA_NAMA")."',
				  '".$this->getField("UNIT_KERJA_KOTA")."',
				  '".$this->getField("KTP")."',
				  '".$this->getField("NAMA")."',	
				  '".$this->getField("NIP")."',
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR").",
				  '".$this->getField("AGAMA")."',
				  '".$this->getField("GOL_RUANG")."',
				  '".$this->getField("JABATAN")."',	
				  '".$this->getField("ALAMAT_RUMAH")."',
				  '".$this->getField("ALAMAT_RUMAH_KAB_KOTA")."',
				  '".$this->getField("KOTA")."',
				  '".$this->getField("ALAMAT_RUMAH_TELP")."',
				  '".$this->getField("ALAMAT_RUMAH_FAX")."',
				  '".$this->getField("ALAMAT_KANTOR")."',
				  '".$this->getField("ALAMAT_KANTOR_TELP")."',
				  '".$this->getField("ALAMAT_KANTOR_FAX")."',
				  '".$this->getField("NPWP")."',
				  '".$this->getField("PENDIDIKAN_TERAKHIR")."',
				  '".$this->getField("PELATIHAN")."',
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("KONTAK_DARURAT_NAMA")."',
				  '".$this->getField("KONTAK_DARURAT_HP")."',
				  '21232f297a57a5a743894a0e4a801fc3',
				  'admin',
				  '".$this->getField("GELAR")."',
				  ".$this->getField("TMT_GOLONGAN").",
				  ".$this->getField("TMT_CPNS").",
				  ".$this->getField("TMT_PNS").",
				  ".$this->getField("TMT_JABATAN").",
				  ".$this->getField("TMT_MUTASI").",
				  ".$this->getField("M_ESELON_ID")."
				)"; 
		$this->id= $this->getField("PESERTA_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function updateAdmin()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str= "
		UPDATE PESERTA
		SET 
			UNIT_KERJA_NAMA= '".$this->getField("UNIT_KERJA_NAMA")."',
			UNIT_KERJA_KOTA= '".$this->getField("UNIT_KERJA_KOTA")."',
			KTP= '".$this->getField("KTP")."',
			NAMA= '".$this->getField("NAMA")."',
			NIP= '".$this->getField("NIP")."',
			JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
			TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
			TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
			AGAMA= '".$this->getField("AGAMA")."',
			GOL_RUANG= '".$this->getField("GOL_RUANG")."',
			JABATAN= '".$this->getField("JABATAN")."',
			ALAMAT_RUMAH= '".$this->getField("ALAMAT_RUMAH")."',
			ALAMAT_RUMAH_KAB_KOTA= '".$this->getField("ALAMAT_RUMAH_KAB_KOTA")."',
			KOTA= '".$this->getField("KOTA")."',
			ALAMAT_RUMAH_TELP= '".$this->getField("ALAMAT_RUMAH_TELP")."',
			ALAMAT_RUMAH_FAX= '".$this->getField("ALAMAT_RUMAH_FAX")."',
			ALAMAT_KANTOR= '".$this->getField("ALAMAT_KANTOR")."',
			ALAMAT_KANTOR_TELP= '".$this->getField("ALAMAT_KANTOR_TELP")."',
			ALAMAT_KANTOR_FAX= '".$this->getField("ALAMAT_KANTOR_FAX")."',
			NPWP= '".$this->getField("NPWP")."',
			PENDIDIKAN_TERAKHIR= '".$this->getField("PENDIDIKAN_TERAKHIR")."',
			PELATIHAN= '".$this->getField("PELATIHAN")."',
			EMAIL= '".$this->getField("EMAIL")."',
			KONTAK_DARURAT_NAMA= '".$this->getField("KONTAK_DARURAT_NAMA")."',
			KONTAK_DARURAT_HP= '".$this->getField("KONTAK_DARURAT_HP")."',
			GELAR= '".$this->getField("GELAR")."',
			TMT_GOLONGAN= ".$this->getField("TMT_GOLONGAN").",
			TMT_CPNS= ".$this->getField("TMT_CPNS").",
			TMT_PNS= ".$this->getField("TMT_PNS").",
			TMT_JABATAN= ".$this->getField("TMT_JABATAN").",
			TMT_MUTASI= ".$this->getField("TMT_MUTASI").",
			M_ESELON_ID= ".$this->getField("M_ESELON_ID")."
		WHERE PESERTA_ID= ".$this->getField("PESERTA_ID")."
		"; 
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function insertImport()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PESERTA_ID", $this->getNextId("PESERTA_ID","PESERTA")); 
		$str= "
		INSERT INTO PESERTA
		(
			PESERTA_ID, UNIT_KERJA_NAMA, NAMA, NIP, TANGGAL_LAHIR, GOL_RUANG, JABATAN, PENDIDIKAN_TERAKHIR
			, EMAIL, M_ESELON_ID
		) 
		VALUES(
			".$this->getField("PESERTA_ID").",
			'".$this->getField("UNIT_KERJA_NAMA")."',
			'".$this->getField("NAMA")."',	
			'".$this->getField("NIP")."',
			".$this->getField("TANGGAL_LAHIR").",
			'".$this->getField("GOL_RUANG")."',
			'".$this->getField("JABATAN")."',	
			'".$this->getField("PENDIDIKAN_TERAKHIR")."',
			'".$this->getField("EMAIL")."',
			".$this->getField("M_ESELON_ID")."
		)"; 
		$this->id= $this->getField("PESERTA_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }

    function insertpendidikanriwayat()
	{
		$this->setField("RIWAYAT_PENDIDIKAN_ID", $this->getNextId("RIWAYAT_PENDIDIKAN_ID","simpeg.riwayat_pendidikan")); 

		$str = "
		INSERT INTO simpeg.riwayat_pendidikan
		(
			RIWAYAT_PENDIDIKAN_ID, PEGAWAI_ID, PENDIDIKAN_ID, NAMA_SEKOLAH, JURUSAN, TEMPAT, TAHUN_AWAL, TAHUN_AKHIR,KETERANGAN
		) 
		VALUES(
			".$this->getField("RIWAYAT_PENDIDIKAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("PENDIDIKAN_ID").",
			'".$this->getField("NAMA_SEKOLAH")."',
			'".$this->getField("JURUSAN")."',
			'".$this->getField("TEMPAT")."',
			".$this->getField("TAHUN_AWAL").",
			".$this->getField("TAHUN_AKHIR").",
			'".$this->getField("KETERANGAN")."'
		)"; 

		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("RIWAYAT_PENDIDIKAN_ID");
		return $this->execQuery($str);
    }
    function insertpendidikanriwayatnonformal()
	{
		$this->setField("RIWAYAT_PENDIDIKAN_ID", $this->getNextId("RIWAYAT_PENDIDIKAN_ID","simpeg.riwayat_pendidikan")); 

		$str = "
		INSERT INTO simpeg.riwayat_pendidikan
		(
			RIWAYAT_PENDIDIKAN_ID, PEGAWAI_ID, JENIS_PELATIHAN, TEMPAT, TAHUN, KETERANGAN
		) 
		VALUES(
			".$this->getField("RIWAYAT_PENDIDIKAN_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("JENIS_PELATIHAN")."',
			'".$this->getField("TEMPAT")."',
			'".$this->getField("TAHUN")."',
			'".$this->getField("KETERANGAN")."'
		)"; 

		$this->query = $str;
				// echo $str; exit;

		$this->id = $this->getField("RIWAYAT_PENDIDIKAN_ID");
		return $this->execQuery($str);
    }
    function insertjabatanriwayat()
	{
		$this->setField("RIWAYAT_JABATAN_ID", $this->getNextId("RIWAYAT_JABATAN_ID","simpeg.riwayat_jabatan")); 

		$str = "
		INSERT INTO simpeg.riwayat_jabatan
		(
			RIWAYAT_JABATAN_ID, JABATAN, PEGAWAI_ID, UNIT_KERJA, TAHUN_AWAL, TAHUN_AKHIR
		) 
		VALUES(
			".$this->getField("RIWAYAT_JABATAN_ID").",
			'".$this->getField("JABATAN")."',
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("UNIT_KERJA")."',
			".$this->getField("TAHUN_AWAL").",
			".$this->getField("TAHUN_AKHIR")."
		)"; 

		$this->query = $str;
		$this->id = $this->getField("RIWAYAT_JABATAN_ID");
		return $this->execQuery($str);
    }
     function insertjabatanriwayatinfo()
	{
		$this->setField("RIWAYAT_JABATAN_INFO_ID", $this->getNextId("RIWAYAT_JABATAN_INFO_ID","simpeg.riwayat_jabatan_info")); 

		$str = "
		INSERT INTO simpeg.riwayat_jabatan_info
		(
			RIWAYAT_JABATAN_INFO_ID, PEGAWAI_ID, STATUS, KETERANGAN
		) 
		VALUES(
			".$this->getField("RIWAYAT_JABATAN_INFO_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("STATUS")."',
			'".$this->getField("KETERANGAN")."'
		)"; 

		$this->query = $str;

		$this->id = $this->getField("RIWAYAT_JABATAN_INFO_ID");
		return $this->execQuery($str);
    }
    function updatejabatanriwayatinfo()
	{
		$str = "
		UPDATE simpeg.riwayat_jabatan_info
		SET
			KETERANGAN= '".$this->getField("KETERANGAN")."'
		WHERE RIWAYAT_JABATAN_INFO_ID = ".$this->getField("RIWAYAT_JABATAN_INFO_ID")."
		";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function deletejabatanriwayatinfo()
	{
        $str = "
        DELETE FROM simpeg.riwayat_jabatan_info
        WHERE 
        RIWAYAT_JABATAN_INFO_ID = '".$this->getField("RIWAYAT_JABATAN_INFO_ID")."'
		"; 
		$this->query = $str;
        return $this->execQuery($str);
    }

    function updateImport()
	{
		$str= "
		UPDATE PESERTA
		SET 
			UNIT_KERJA_NAMA= '".$this->getField("UNIT_KERJA_NAMA")."',
			NAMA= '".$this->getField("NAMA")."',
			NIP= '".$this->getField("NIP")."',
			TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
			TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
			GOL_RUANG= '".$this->getField("GOL_RUANG")."',
			JABATAN= '".$this->getField("JABATAN")."',
			PENDIDIKAN_TERAKHIR= '".$this->getField("PENDIDIKAN_TERAKHIR")."',
			EMAIL= '".$this->getField("EMAIL")."',
			M_ESELON_ID= ".$this->getField("M_ESELON_ID")."
		WHERE PESERTA_ID= ".$this->getField("PESERTA_ID")."
		"; 
		$this->query= $str;
		return $this->execQuery($str);
    }

	function updateDiklatPesertaKtp()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str= "
	    UPDATE DIKLAT_PESERTA
	    SET 
		   KTP= '".$this->getField("KTP")."'
	    WHERE PESERTA_ID= ".$this->getField("PESERTA_ID")."
	    ";
		$this->query= $str;
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
	
	function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PESERTA A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PESERTA_ID = ".$this->getField("PESERTA_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str= "DELETE FROM PESERTA
                WHERE 
                  PESERTA_ID= ".$this->getField("PESERTA_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }
     function deletesaudara()
	{
        $str = "
        DELETE FROM simpeg.saudara
        WHERE 
        PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
		"; 
		$this->query = $str;
		// echo $str; exit;
        return $this->execQuery($str);
    }
     function deletejabatanriwayat()
	{
        $str = "
        DELETE FROM simpeg.riwayat_jabatan
        WHERE 
        PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
		"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
     function deletependidikanriwayatNew()
	{
        $str = "
        DELETE FROM simpeg.riwayat_pendidikan
        WHERE 
        PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
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
     function selectByParamsPendidikanRiwayat($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $statement='',  $sOrder=" ORDER BY A.PENDIDIKAN_ID ASC, B.PEGAWAI_ID")
	{
		$str = "
		SELECT
		A.PENDIDIKAN_ID, A.NAMA
		, B.RIWAYAT_PENDIDIKAN_ID, B.NAMA_SEKOLAH, B.TAHUN
		, B.JURUSAN, B.PEGAWAI_ID, B.TEMPAT, B.TAHUN_AWAL, B.TAHUN_AKHIR
		FROM simpeg.pendidikan A
		LEFT JOIN (SELECT * FROM simpeg.riwayat_pendidikan WHERE PEGAWAI_ID = ".$pegawaiid." ) B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
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
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str= "SELECT 
					   ".$sField."
				FROM PESERTA A
				WHERE 1=1"; 
		
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query= $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    
	
	function selectByParamsWord($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
				SELECT DP.DIKLAT_PESERTA_ID, D.NAMA_DIKLAT,
				A.PESERTA_ID, DP.UNIT_KERJA_NAMA, DP.UNIT_KERJA_KOTA, DP.NAMA, A.KTP,
				DP.NIP, DP.JENIS_KELAMIN, CASE DP.JENIS_KELAMIN WHEN 'L' THEN 'LAKI-LAKI' WHEN 'P' THEN 'PEREMPUAN' END JENIS_KELAMIN_NAMA,
				DP.TEMPAT_LAHIR, DP.TANGGAL_LAHIR, DP.AGAMA, DP.GOL_RUANG, DP.JABATAN,
				DP.ALAMAT_RUMAH, DP.ALAMAT_RUMAH_KAB_KOTA, DP.ALAMAT_RUMAH_TELP, DP.ALAMAT_RUMAH_FAX, DP.EMAIL,
				DP.ALAMAT_KANTOR, DP.ALAMAT_KANTOR_TELP, DP.ALAMAT_KANTOR_FAX,
				DP.NPWP, DP.PENDIDIKAN_TERAKHIR, DP.PELATIHAN, DP.KONTAK_DARURAT_NAMA, DP.KONTAK_DARURAT_HP, A.FOTO_LINK, A.PASSWORD_LOGIN_DEKRIP, A.GELAR, A.TMT_GOLONGAN, A.TMT_CPNS, A.TMT_PNS, A.TMT_JABATAN, A.TMT_MUTASI
				FROM DIKLAT_PESERTA DP
				LEFT JOIN PESERTA A ON A.PESERTA_ID=DP.PESERTA_ID
				LEFT JOIN DIKLAT D ON D.DIKLAT_ID=DP.DIKLAT_ID
				WHERE 1=1
			"; 
		
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query= $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str= "    SELECT PESERTA_ID,
						   UNIT_KERJA_NAMA,
						   NAMA,
						   NIP,
						   JENIS_KELAMIN,
						   TEMPAT_LAHIR,
						   TANGGAL_LAHIR,
						   AGAMA,
						   GOL_RUANG,
						   JABATAN,
						   ALAMAT_RUMAH,
						   ALAMAT_RUMAH_TELP,
						   ALAMAT_RUMAH_FAX,
						   ALAMAT_KANTOR,
						   ALAMAT_KANTOR_TELP,
						   ALAMAT_KANTOR_FAX,
						   NPWP,
						   PENDIDIKAN_TERAKHIR,
						   PELATIHAN, 
						   GELAR,
						   TMT_GOLONGAN, 
						   TMT_CPNS, 
						   TMT_PNS, 
						   TMT_JABATAN, 
						   TMT_MUTASI
					  FROM PESERTA						   
				      WHERE  1=1
				"; 
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY PESERTA_ID DESC";
		$this->query= $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
     function selectByParamsPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY  A.PANGKAT_ID")
	{
		$str = "
		SELECT 
		A.*
		FROM simpeg.pangkat A
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

    function selectByParamsSaudara($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.SAUDARA_ID ASC")
	{
		$str = "
		SELECT 
		A.*
		FROM simpeg.saudara A
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
    function selectByParamsPendidikanRiwayatFormal($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.RIWAYAT_PENDIDIKAN_ID")
	{
		$str = "
		SELECT 
		A.RIWAYAT_PENDIDIKAN_ID, A.PEGAWAI_ID
		, A.PENDIDIKAN_ID, A.NAMA_SEKOLAH, A.JURUSAN, A.TEMPAT, A.TAHUN_AWAL, A.TAHUN_AKHIR,A.KETERANGAN
		FROM simpeg.riwayat_pendidikan A 
		WHERE 1=1 AND A.PENDIDIKAN_ID IS NOT NULL
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
     function selectByParamsPendidikanRiwayatNonFormal($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.RIWAYAT_PENDIDIKAN_ID")
	{
		$str = "
		SELECT 
		A.RIWAYAT_PENDIDIKAN_ID, A.PEGAWAI_ID
		, A.JENIS_PELATIHAN, A.TEMPAT, A.TAHUN, A.KETERANGAN
		FROM simpeg.riwayat_pendidikan A
		WHERE 1=1 AND A.PENDIDIKAN_ID IS NULL
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
    function selectByParamsJabatanRiwayat($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.RIWAYAT_JABATAN_ID")
	{
		$str = "
		SELECT 
		A.RIWAYAT_JABATAN_ID, A.JABATAN, A.PEGAWAI_ID, A.UNIT_KERJA, A.TAHUN_AWAL, A.TAHUN_AKHIR
		FROM simpeg.riwayat_jabatan A
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT 
			A.PEGAWAI_ID PESERTA_ID, NULL UNIT_KERJA_NAMA, NULL UNIT_KERJA_KOTA, NAMA, NIK KTP, NIP_BARU NIP, JENIS_KELAMIN
			, CASE JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA,
			TEMPAT_LAHIR, TGL_LAHIR TANGGAL_LAHIR, AGAMA, NULL GOL_RUANG, LAST_JABATAN JABATAN,
			NULL ALAMAT_RUMAH, NULL ALAMAT_RUMAH_KAB_KOTA, NULL KOTA, A.HP ALAMAT_RUMAH_TELP, NULL ALAMAT_RUMAH_FAX, A.EMAIL,
			NULL ALAMAT_KANTOR, NULL ALAMAT_KANTOR_TELP, NULL ALAMAT_KANTOR_FAX,
			NULL NPWP, NULL PENDIDIKAN_TERAKHIR, NULL PELATIHAN, NULL KONTAK_DARURAT_NAMA, NULL KONTAK_DARURAT_HP, NULL FOTO_LINK
			, NULL PASSWORD_LOGIN_DEKRIP, NULL GELAR, NULL TMT_GOLONGAN, TMT_CPNS, TMT_PNS, NULL TMT_JABATAN, NULL TMT_MUTASI
			, NULL M_ESELON_ID, NULL M_ESELON_NAMA
			, NULL UNIT_KERJA_ESELON, NULL STATUS_SATUAN_KERJA
			, A.STATUS_JENIS, A.LAST_ESELON_ID
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

     function selectByParamsJabatanRiwayatInfo($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.RIWAYAT_JABATAN_INFO_ID")
	{
		$str = "
		SELECT 
		A.RIWAYAT_JABATAN_INFO_ID, A.PEGAWAI_ID, A.STATUS, A.KETERANGAN
		FROM simpeg.riwayat_jabatan_info A
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
     function selectByParamsDataPekerjaan($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.DATA_PEKERJAAN_ID")
	{
		$str = "
		SELECT 
		A.DATA_PEKERJAAN_ID, A.PEGAWAI_ID, A.GAMBARAN, A.TANGGUNG_JAWAB,A.URAIKAN
		FROM simpeg.DATA_PEKERJAAN A
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

    function selectByParamsKondisiKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.KONDISI_KERJA_ID")
	{
		$str = "
		SELECT 
		A.*
		FROM simpeg.KONDISI_KERJA A
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
     function selectByParamsMinat($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.MINAT_HARAPAN_ID")
	{
		$str = "
		SELECT 
		A.*
		FROM simpeg.MINAT_HARAPAN A
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
     function selectByParamsKekuatan($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.KEKUATAN_KELEMAHAN_ID")
	{
		$str = "
		SELECT 
		A.*
		FROM simpeg.KEKUATAN_KELEMAHAN A
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

    function selectByParamsCekDrh($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder="")
	{
		$str = "
			SELECT 
				COALESCE( NULLIF(B.ROWATASAN,NULL), 0) ROWATASAN
				, COALESCE( NULLIF(C.ROWSAUDARA,NULL), 0) ROWSAUDARA
				, COALESCE( NULLIF(D.ROWRIPEND,NULL),0) ROWRIPEND
				, COALESCE( NULLIF(E.ROWRIPENDNON,NULL), 0) ROWRIPENDNON
				, COALESCE( NULLIF(F.ROWRIJAB,NULL), 0) ROWRIJAB
				, COALESCE( NULLIF(G.ROWBIDANGPEK,NULL), 0) ROWBIDANGPEK 
				, COALESCE( NULLIF(H.ROWDATAPEK,NULL), 0) ROWDATAPEK
				, COALESCE( NULLIF(I.ROWKONKERJA,NULL), 0) ROWKONKERJA
				, COALESCE( NULLIF(J.ROWMINHARAP,NULL), 0) ROWMINHARAP
				, COALESCE( NULLIF(K.ROWKEKKEL,NULL), 0) ROWKEKKEL
			FROM simpeg.PEGAWAI A
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWATASAN,PEGAWAI_ID
				FROM simpeg.PEGAWAI A
				WHERE 1=1
				AND LENGTH(A.LAST_ATASAN_LANGSUNG_NAMA) > 0 AND LENGTH(A.LAST_ATASAN_LANGSUNG_JABATAN) > 0 
				GROUP BY PEGAWAI_ID
			) B ON B.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWSAUDARA,PEGAWAI_ID 
				FROM simpeg.saudara A
				WHERE 1=1
				GROUP BY PEGAWAI_ID
			) C ON C.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWRIPEND,PEGAWAI_ID 
				FROM simpeg.riwayat_pendidikan A 
				WHERE 1=1 AND A.PENDIDIKAN_ID IS NOT NULL 
				GROUP BY PEGAWAI_ID
			) D ON D.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT COUNT(1) AS ROWRIPENDNON ,PEGAWAI_ID 
				FROM simpeg.riwayat_pendidikan A
				WHERE 1=1 AND A.PENDIDIKAN_ID IS NULL  
				GROUP BY PEGAWAI_ID
			) E ON E.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWRIJAB,PEGAWAI_ID  
				FROM simpeg.riwayat_jabatan A
				WHERE 1=1 
				GROUP BY PEGAWAI_ID
			) F ON F.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWBIDANGPEK,PEGAWAI_ID 
				FROM simpeg.riwayat_jabatan_info A
				WHERE 1=1
				GROUP BY PEGAWAI_ID
			) G ON G.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWDATAPEK,PEGAWAI_ID 
				FROM simpeg.DATA_PEKERJAAN A
				WHERE 1=1
				AND LENGTH(A.GAMBARAN) > 0 AND LENGTH(A.TANGGUNG_JAWAB) > 0 AND LENGTH(A.URAIKAN) > 0
				GROUP BY PEGAWAI_ID
			) H ON H.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWKONKERJA,PEGAWAI_ID 
				FROM simpeg.KONDISI_KERJA A
				WHERE 1=1 AND A.BAIK_ID IS NOT NULL OR A.CUKUP_ID IS NOT NULL OR A.PERLU_ID IS NOT NULL AND LENGTH(A.KONDISI) > 0 AND LENGTH(A.ASPEK) > 0
				GROUP BY A.PEGAWAI_ID 
			) I ON I.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWMINHARAP,PEGAWAI_ID 
				FROM simpeg.MINAT_HARAPAN A
				WHERE 1=1 AND LENGTH(A.SUKAI) > 0 AND LENGTH(A.TIDAK_SUKAI) > 0
				GROUP BY A.PEGAWAI_ID 
			) J ON J.PEGAWAI_ID =A.PEGAWAI_ID
			LEFT JOIN
			(		
				SELECT COUNT(1) AS ROWKEKKEL,PEGAWAI_ID
				FROM simpeg.KEKUATAN_KELEMAHAN A
				WHERE 1=1 AND LENGTH(A.KEKUATAN) > 0 AND LENGTH(A.KELEMAHAN) > 0
				GROUP BY A.PEGAWAI_ID 
			) K ON K.PEGAWAI_ID =A.PEGAWAI_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str= "SELECT COUNT(PESERTA_ID) AS ROWCOUNT FROM PESERTA WHERE 1= 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array())
	{
		$str= "SELECT COUNT(PESERTA_ID) AS ROWCOUNT FROM PESERTA WHERE 1= 1 "; 
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
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

  class DiklatPeserta extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DiklatPeserta()
	{
      $this->Entity(); 
    }

    function insertDaftar()
	{
		$str = "
		INSERT INTO jadwal_awal_tes_pegawai (
		JADWAL_AWAL_TES_ID, PEGAWAI_ID, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE) 
		VALUES (
			(SELECT JADWAL_AWAL_TES_ID FROM jadwal_awal_tes_simulasi WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$this->getField("JADWAL_AWAL_TES_ID")."),
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("STATUS").",
			'".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE")."
		)";
		$this->query= $str;
		// echo $str;exit();
		$this->execQuery($str);

		$str1= "
		INSERT INTO jadwal_awal_tes_simulasi_pegawai(
            JADWAL_AWAL_TES_SIMULASI_ID, JADWAL_AWAL_TES_ID, PEGAWAI_ID, LAST_CREATE_DATE)
        VALUES(
            ".$this->getField("JADWAL_AWAL_TES_ID").",
            (SELECT JADWAL_AWAL_TES_ID FROM jadwal_awal_tes_simulasi WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$this->getField("JADWAL_AWAL_TES_ID")."),
            ".$this->getField("PEGAWAI_ID").",
            NOW()
        )";
		$this->query= $str1;
		// echo $str1;exit();
		return $this->execQuery($str1);
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DIKLAT_PESERTA_ID", $this->getNextId("DIKLAT_PESERTA_ID","DIKLAT_PESERTA")); 
		$str = "
				INSERT INTO  DIKLAT_PESERTA
				(DIKLAT_PESERTA_ID, DIKLAT_ID, PESERTA_ID,
				UNIT_KERJA_NAMA, UNIT_KERJA_KOTA, KTP, NAMA, NIP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, AGAMA, GOL_RUANG, JABATAN, ALAMAT_RUMAH, ALAMAT_RUMAH_KAB_KOTA, ALAMAT_RUMAH_TELP,
			 	ALAMAT_RUMAH_FAX, ALAMAT_KANTOR, ALAMAT_KANTOR_TELP, ALAMAT_KANTOR_FAX, NPWP, PENDIDIKAN_TERAKHIR, PELATIHAN, EMAIL, KONTAK_DARURAT_NAMA, KONTAK_DARURAT_HP) 
			 	VALUES(
				  ".$this->getField("DIKLAT_PESERTA_ID").",
				  ".$this->getField("DIKLAT_ID").",
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
				  '".$this->getField("KONTAK_DARURAT_HP")."'
				)"; 
		$this->id = $this->getField("DIKLAT_PESERTA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

	function insertAdmin()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DIKLAT_PESERTA_ID", $this->getNextId("DIKLAT_PESERTA_ID","DIKLAT_PESERTA")); 
		$str = "
				INSERT INTO  DIKLAT_PESERTA
				(DIKLAT_PESERTA_ID, DIKLAT_ID, PESERTA_ID, UNIT_KERJA_NAMA, UNIT_KERJA_KOTA, KTP, NAMA, NIP, JENIS_KELAMIN, TEMPAT_LAHIR, TANGGAL_LAHIR, AGAMA, GOL_RUANG, JABATAN, ALAMAT_RUMAH, ALAMAT_RUMAH_KAB_KOTA, KOTA, ALAMAT_RUMAH_TELP,
			 	ALAMAT_RUMAH_FAX, ALAMAT_KANTOR, ALAMAT_KANTOR_TELP, ALAMAT_KANTOR_FAX, NPWP, PENDIDIKAN_TERAKHIR, PELATIHAN, EMAIL, KONTAK_DARURAT_NAMA, KONTAK_DARURAT_HP, STATUS, M_ESELON_ID) 
			 	VALUES(
				  ".$this->getField("DIKLAT_PESERTA_ID").",
				  ".$this->getField("DIKLAT_ID").",
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
				  '".$this->getField("KONTAK_DARURAT_HP")."'
				  , ".$this->getField("STATUS")."
				  , ".$this->getField("M_ESELON_ID")."
				)"; 
		$this->id = $this->getField("DIKLAT_PESERTA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DIKLAT_PESERTA SET
					DIKLAT_ID = ".$this->getField("DIKLAT_ID").",
					PESERTA_ID = ".$this->getField("PESERTA_ID").",
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
					KONTAK_DARURAT_HP= '".$this->getField("KONTAK_DARURAT_HP")."'
				 WHERE DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateAdmin()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE DIKLAT_PESERTA SET
			DIKLAT_ID = ".$this->getField("DIKLAT_ID").",
			PESERTA_ID = ".$this->getField("PESERTA_ID").",
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
			STATUS= ".$this->getField("STATUS").",
			M_ESELON_ID= ".$this->getField("M_ESELON_ID")."
		WHERE DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function insertImport()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DIKLAT_PESERTA_ID", $this->getNextId("DIKLAT_PESERTA_ID","DIKLAT_PESERTA")); 
		$str= "
		INSERT INTO DIKLAT_PESERTA
		(
			DIKLAT_PESERTA_ID, DIKLAT_ID, PESERTA_ID, UNIT_KERJA_NAMA, NAMA, NIP
			, TANGGAL_LAHIR, GOL_RUANG, JABATAN, PENDIDIKAN_TERAKHIR
			, EMAIL, M_ESELON_ID
		) 
		VALUES(
			".$this->getField("DIKLAT_PESERTA_ID").",
			".$this->getField("DIKLAT_ID").",
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

    function updateImport()
	{
		$str= "
		UPDATE DIKLAT_PESERTA
		SET 
			UNIT_KERJA_NAMA= '".$this->getField("UNIT_KERJA_NAMA")."',
			NAMA= '".$this->getField("NAMA")."',
			NIP= '".$this->getField("NIP")."',
			TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
			GOL_RUANG= '".$this->getField("GOL_RUANG")."',
			JABATAN= '".$this->getField("JABATAN")."',
			PENDIDIKAN_TERAKHIR= '".$this->getField("PENDIDIKAN_TERAKHIR")."',
			EMAIL= '".$this->getField("EMAIL")."',
			M_ESELON_ID= ".$this->getField("M_ESELON_ID")."
		WHERE DIKLAT_PESERTA_ID= ".$this->getField("DIKLAT_PESERTA_ID")."
		"; 
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function updateTandaTerimaSertifikat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE DIKLAT_PESERTA SET
					TANDA_TERIMA_SERTIFIKAT= ".$this->getField("TANDA_TERIMA_SERTIFIKAT")."
				 WHERE DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateKirim()
	{
		$str = "
		UPDATE DIKLAT_PESERTA SET
		JUMLAH_KIRIM= ( SELECT COALESCE(JUMLAH_KIRIM,0) FROM DIKLAT_PESERTA WHERE DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID")." ) + 1
		WHERE DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID")."
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
	
	function updateByField()
	{
		$str = "UPDATE DIKLAT_PESERTA A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM DIKLAT_PESERTA
                WHERE 
                  DIKLAT_PESERTA_ID = ".$this->getField("DIKLAT_PESERTA_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete_new()
	{
        $str = "DELETE FROM DIKLAT_PESERTA
                WHERE 
                  DIKLAT_PESERTA_ID= ".$this->getField("DIKLAT_PESERTA_ID")."
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
	function selectByParamsCheckPeserta($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "	
				SELECT 
					A.DIKLAT_PESERTA_ID, A.PESERTA_ID, A.DIKLAT_ID, A.STATUS
				FROM DIKLAT_PESERTA A
				INNER JOIN PESERTA B ON A.PESERTA_ID = B.PESERTA_ID
				WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "	
		SELECT A.PEGAWAI_ID, A1.NAMA PEGAWAI_NAMA, A1.NIP_BARU PEGAWAI_NIP, A1.NIK
		, B1.KODE PEGAWAI_GOL, C1.NAMA PEGAWAI_ESELON, A1.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL, D1.SATKER_ID SATKER_TES_ID, COALESCE(JD.JUMLAH_DATA,0) JUMLAH_DATA
		FROM jadwal_awal_tes_simulasi_pegawai A
		INNER JOIN simpeg.pegawai A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
		LEFT JOIN simpeg.pangkat B1 ON A1.LAST_PANGKAT_ID = B1.PANGKAT_ID
		LEFT JOIN simpeg.eselon C1 ON A1.LAST_ESELON_ID = C1.ESELON_ID
		LEFT JOIN simpeg.satker D1 ON A1.SATKER_ID = D1.SATKER_ID
		LEFT JOIN
		(
			SELECT JADWAL_TES_ID ROW_CHECK_ID, A.PEGAWAI_ID ROW_CHECK_PEGAWAI_ID, COUNT(1) JUMLAH_DATA
			FROM cat.ujian_pegawai_daftar A
			GROUP BY JADWAL_TES_ID, A.PEGAWAI_ID
		) JD ON JADWAL_AWAL_TES_SIMULASI_ID = ROW_CHECK_ID AND A.PEGAWAI_ID = ROW_CHECK_PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPesertaDiklat($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "	
				SELECT 
					A.DIKLAT_PESERTA_ID, A.PESERTA_ID, C.NAMA_DIKLAT, C.KOTA, TO_CHAR(C.TANGGAL_AWAL, 'YYYY') TAHUN_DIKLAT, A.DIKLAT_ID
				FROM DIKLAT_PESERTA A
				INNER JOIN PESERTA B ON A.PESERTA_ID = B.PESERTA_ID
				INNER JOIN DIKLAT C ON A.DIKLAT_ID = C.DIKLAT_ID
				WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsTandaTerimaSertifikat($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY D.NAMA")
	{
		$str = "	
				SELECT 
					A.DIKLAT_ID, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, 
					B.DIKLAT_PESERTA_ID, D.NAMA NAMA_PESERTA
					, D.NIP, D.UNIT_KERJA_NAMA UNIT_KERJA, B.TANDA_TERIMA_SERTIFIKAT
				FROM DIKLAT A
				INNER JOIN DIKLAT_PESERTA B ON A.DIKLAT_ID = B.DIKLAT_ID
				INNER JOIN PESERTA D ON D.PESERTA_ID = B.PESERTA_ID
				WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsHistoriPesertaDiklat($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY C.NAMA_DIKLAT, C.TANGGAL_AWAL DESC")
	{
		$str = "	
				SELECT 
					C.NAMA_DIKLAT, C.NAMA_DIKLAT GROUP_INFO,
					C.TANGGAL_AWAL, C.TANGGAL_AKHIR,
					A.DIKLAT_PESERTA_ID, A.PESERTA_ID, A.UNIT_KERJA_NAMA, A.UNIT_KERJA_KOTA, A.NAMA, A.KTP,
					A.NIP, A.JENIS_KELAMIN, CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA,
					A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.AGAMA, A.GOL_RUANG, A.JABATAN,
					A.ALAMAT_RUMAH, A.ALAMAT_RUMAH_KAB_KOTA, A.ALAMAT_RUMAH_TELP, A.ALAMAT_RUMAH_FAX, A.EMAIL,
					A.ALAMAT_KANTOR, A.ALAMAT_KANTOR_TELP, A.ALAMAT_KANTOR_FAX,
					A.NPWP, A.PENDIDIKAN_TERAKHIR,
					A.PELATIHAN, A.DIKLAT_ID, A.STATUS,
					CASE A.STATUS WHEN 1 THEN 'Proses' WHEN 2 THEN 'Tolak' ELSE '-' END STATUS_NAMA
					, A.KONTAK_DARURAT_NAMA, A.KONTAK_DARURAT_HP, B.FOTO_LINK
				FROM DIKLAT_PESERTA A
				INNER JOIN PESERTA B ON A.PESERTA_ID = B.PESERTA_ID
				INNER JOIN DIKLAT C ON A.DIKLAT_ID = C.DIKLAT_ID
				WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsHistoriPesertaDiklatKabupaten($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY CASE WHEN B.UNIT_KERJA_KOTA IS NULL OR B.UNIT_KERJA_KOTA = '' THEN 'Belum Ditentukan' ELSE B.UNIT_KERJA_KOTA END, A.NAMA_DIKLAT, D.NAMA")
	{
		$str = "	
				SELECT 
					CASE WHEN B.UNIT_KERJA_KOTA IS NULL OR B.UNIT_KERJA_KOTA = '' THEN 'Belum Ditentukan' ELSE B.UNIT_KERJA_KOTA END GROUP_INFO
					, D.NAMA NAMA_PESERTA, A.NAMA_DIKLAT, A.TAHUN, D.UNIT_KERJA_NAMA LEMBAGA
					, (COALESCE(C.PRE_TES,0) * 0.35) +  (COALESCE(C.KETRAMPILAN,0) * 0.35) + (COALESCE(C.AKTIVITAS,0) * 0.3) NILAI_AKHIR
					, CASE 
					WHEN COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) >= 80
					THEN 'SANGAT BAIK' 
					WHEN COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) >= 70 AND COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) < 80
					THEN 'BAIK' 
					WHEN COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) >= 60 AND COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) < 70
					THEN 'CUKUP BAIK' 
					WHEN COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) >= 50 AND COALESCE((COALESCE(PRE_TES,0) * 0.35) +  (COALESCE(KETRAMPILAN,0) * 0.35) + (COALESCE(AKTIVITAS,0) * 0.3),0) < 60
					THEN 'KURANG BAIK' 
					ELSE 'SANGAT KURANG BAIK' END
					KESIMPULAN_NILAI
					, CASE COALESCE(B.TANDA_TERIMA_SERTIFIKAT,0) WHEN 1 THEN 'Sudah' ELSE 'Belum' END TANDA_TERIMA_SERTIFIKAT_INFO
				FROM DIKLAT A
				INNER JOIN DIKLAT_PESERTA B ON A.DIKLAT_ID = B.DIKLAT_ID
				LEFT JOIN DIKLAT_PESERTA_NILAI_DIKLAT C ON B.DIKLAT_PESERTA_ID = C.DIKLAT_PESERTA_ID
				INNER JOIN PESERTA D ON D.PESERTA_ID = B.PESERTA_ID
				WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsPesertaId($statement="")
	{
		$str = "SELECT COALESCE(MAX(PESERTA_ID),0) + 1 ROWCOUNT FROM PESERTA";
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 1;
    }

    function getCountByParamsHistoriPesertaDiklatKabupaten($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM DIKLAT A
		INNER JOIN DIKLAT_PESERTA B ON A.DIKLAT_ID = B.DIKLAT_ID
		LEFT JOIN DIKLAT_PESERTA_NILAI_DIKLAT C ON B.DIKLAT_PESERTA_ID = C.DIKLAT_PESERTA_ID
		INNER JOIN PESERTA D ON D.PESERTA_ID = B.PESERTA_ID
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsHistoriPesertaDiklat($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM DIKLAT_PESERTA A
		INNER JOIN PESERTA B ON A.PESERTA_ID = B.PESERTA_ID
		INNER JOIN DIKLAT C ON A.DIKLAT_ID = C.DIKLAT_ID
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str; 
		// echo $str;exit();
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsPesertaDiklat($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM jadwal_awal_tes_simulasi_pegawai A
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM DIKLAT_PESERTA A
		LEFT JOIN PESERTA B ON A.PESERTA_ID = B.PESERTA_ID
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>
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
  * Entity-base class untuk mengimplementasikan tabel perencanaan_detil.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PerencanaanDetil extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function PerencanaanDetil()
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
		$this->setField("PERENCANAAN_DETIL_ID", $this->getNextId("PERENCANAAN_DETIL_ID","perencanaan_detil"));
	
		$str = "
				INSERT INTO perencanaan_detil (
				   PERENCANAAN_DETIL_ID, PEGAWAI_ID, TIPE_RENCANA, PERENCANAAN_ID, JABATAN_ID_REN, USIA_REN, USIA_CAPAI, 
				   SATKER_ID_REN, PANGKAT_ID_REN, TAHUN
				   ) 
 			  	VALUES (
				  ".$this->getField("PERENCANAAN_DETIL_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TIPE_RENCANA").",
				  ".$this->getField("PERENCANAAN_ID").",
				  ".$this->getField("JABATAN_ID_REN").",
				  ".$this->getField("USIA_REN").",
				  ".$this->getField("USIA_CAPAI").",
				  '".$this->getField("SATKER_ID_REN")."',
				  ".$this->getField("PANGKAT_ID_REN").",
				  '".$this->getField("TAHUN")."'
				)"; 
		$this->id = $this->getField("PERENCANAAN_DETIL_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE perencanaan_detil
				SET    
					  TIPE_RENCANA= ".$this->getField("TIPE_RENCANA").",
					  JABATAN_ID_REN= '".$this->getField("JABATAN_ID_REN")."',
					  USIA_REN= ".$this->getField("USIA_REN").",
					  USIA_CAPAI= ".$this->getField("USIA_CAPAI").",
					  SATKER_ID_REN= '".$this->getField("SATKER_ID_REN")."',
					  PANGKAT_ID_REN= ".$this->getField("PANGKAT_ID_REN").",
					  TAHUN= '".$this->getField("TAHUN")."'
				WHERE  PERENCANAAN_DETIL_ID = ".$this->getField("PERENCANAAN_DETIL_ID")."

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	
	function insertBak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERENCANAAN_DETIL_ID", $this->getNextId("PERENCANAAN_DETIL_ID","perencanaan_detil"));
	
		$str = "
				INSERT INTO perencanaan_detil (
				   PERENCANAAN_DETIL_ID, PEGAWAI_ID, TIPE_RENCANA, PERENCANAAN_ID, JABATAN_ID_REN, JABATAN_ID_CAPAI, USIA_REN, USIA_CAPAI, 
				   SATKER_ID_REN, SATKER_ID_CAPAI, TMT_JABATAN_REN,
				   TMT_JABATAN_CAPAI, PANGKAT_ID_REN, PANGKAT_ID_CAPAI, PENDIDIKAN_REN, PENDIDIKAN_CAPAI, KINERJA_SKP_REN, KINERJA_SKP_CAPAI,
				   KINERJA_PK_REN, KINERJA_PK_CAPAI, DIKLAT_STRUK_REN, DIKLAT_STRUK_CAPAI, DIKLAT_TEKNIS_REN, DIKLAT_TEKNIS_CAPAI, TAHUN
				   ) 
 			  	VALUES (
				  ".$this->getField("PERENCANAAN_DETIL_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TIPE_RENCANA").",
				  ".$this->getField("PERENCANAAN_ID").",
				  '".$this->getField("JABATAN_ID_REN")."',
				  '".$this->getField("JABATAN_ID_CAPAI")."',
				  ".$this->getField("USIA_REN").",
				  ".$this->getField("USIA_CAPAI").",
				  '".$this->getField("SATKER_ID_REN")."',
				  '".$this->getField("SATKER_ID_CAPAI")."',
				  ".$this->getField("TMT_JABATAN_REN").",
				  ".$this->getField("TMT_JABATAN_CAPAI").",
				  ".$this->getField("PANGKAT_ID_REN").",
				  ".$this->getField("PANGKAT_ID_CAPAI").",
				  '".$this->getField("PENDIDIKAN_REN")."',
				  '".$this->getField("PENDIDIKAN_CAPAI")."',
				  ".$this->getField("KINERJA_SKP_REN").",
				  ".$this->getField("KINERJA_SKP_CAPAI").",
				  ".$this->getField("KINERJA_PK_REN").",
				  ".$this->getField("KINERJA_PK_CAPAI").",
				  '".$this->getField("DIKLAT_STRUK_REN")."',
				  '".$this->getField("DIKLAT_STRUK_CAPAI")."',
				  '".$this->getField("DIKLAT_TEKNIS_REN")."',
				  '".$this->getField("DIKLAT_TEKNIS_CAPAI")."',
				  '".$this->getField("TAHUN")."'
				)"; 
		$this->id = $this->getField("PERENCANAAN_DETIL_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateBak()
	{
		$str = "
				UPDATE perencanaan_detil
				SET    
					  TIPE_RENCANA= ".$this->getField("TIPE_RENCANA").",
					  JABATAN_ID_REN= '".$this->getField("JABATAN_ID_REN")."',
					  JABATAN_ID_CAPAI= '".$this->getField("JABATAN_ID_CAPAI")."',
					  USIA_REN= ".$this->getField("USIA_REN").",
					  USIA_CAPAI= ".$this->getField("USIA_CAPAI").",
					  SATKER_ID_REN= '".$this->getField("SATKER_ID_REN")."',
					  SATKER_ID_CAPAI= '".$this->getField("SATKER_ID_CAPAI")."',
					  TMT_JABATAN_REN= ".$this->getField("TMT_JABATAN_REN").",
					  TMT_JABATAN_CAPAI= ".$this->getField("TMT_JABATAN_CAPAI").",
					  PANGKAT_ID_REN= ".$this->getField("PANGKAT_ID_REN").",
					  PANGKAT_ID_CAPAI= ".$this->getField("PANGKAT_ID_CAPAI").",
					  PENDIDIKAN_REN= '".$this->getField("PENDIDIKAN_REN")."',
					  PENDIDIKAN_CAPAI= '".$this->getField("PENDIDIKAN_CAPAI")."',
					  KINERJA_SKP_REN= ".$this->getField("KINERJA_SKP_REN").",
					  KINERJA_SKP_CAPAI= ".$this->getField("KINERJA_SKP_CAPAI").",
					  KINERJA_PK_REN= ".$this->getField("KINERJA_PK_REN").",
					  KINERJA_PK_CAPAI= ".$this->getField("KINERJA_PK_CAPAI").",
					  DIKLAT_STRUK_REN= '".$this->getField("DIKLAT_STRUK_REN")."',
					  DIKLAT_STRUK_CAPAI= '".$this->getField("DIKLAT_STRUK_CAPAI")."',
					  DIKLAT_TEKNIS_REN= '".$this->getField("DIKLAT_TEKNIS_REN")."',
					  DIKLAT_TEKNIS_CAPAI= '".$this->getField("DIKLAT_TEKNIS_CAPAI")."',
					  TAHUN= '".$this->getField("TAHUN")."'
				WHERE  PERENCANAAN_DETIL_ID = ".$this->getField("PERENCANAAN_DETIL_ID")."

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM perencanaan_detil
                WHERE 
                  PERENCANAAN_DETIL_ID = ".$this->getField("PERENCANAAN_DETIL_ID").""; 
				  
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
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY PERENCANAAN_DETIL_ID DESC")
	{
		$str = "
				SELECT 
					PERENCANAAN_DETIL_ID, TIPE_RENCANA, 
					AMBIL_TIPE_RENCANA(TIPE_RENCANA) TIPE_RENCANA_DETIL, 
					A.PEGAWAI_ID, A.PANGKAT_ID_REN,
					A.SATKER_ID_REN,
					A.PENDIDIKAN_REN, JABATAN_ID_REN, USIA_REN, 
					A.TAHUN,
					CONCAT(B.KODE_GOL, ' (', B.NAMA_GOL, ')') PANGKAT_REN, 
					F.NAMA_UNKER SATKER_REN, 
					AMBIL_UMUR(G.TGL_LAHIR) UMUR
					, F.NAMA_JABATAN JABATAN
				FROM perencanaan_detil A					
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.PANGKAT_ID_REN = B.KODE_GOL			
				LEFT JOIN ".$this->db.".rb_ref_unker F ON F.KODE_UNKER = A.SATKER_ID_REN
				LEFT JOIN ".$this->db.".rb_data_pegawai G ON G.IDPEG = A.PEGAWAI_ID					
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsNew($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY PERENCANAAN_DETIL_ID DESC")
	{
		$str = "
				SELECT 
					PERENCANAAN_DETIL_ID, TIPE_RENCANA, AMBIL_TIPE_RENCANA(TIPE_RENCANA) TIPE_RENCANA_DETIL, A.PEGAWAI_ID,
					A.PANGKAT_ID_REN, CONCAT(B.KODE, ' (', B.NAMA, ')') PANGKAT_REN, F.namunit SATKER_REN, A.SATKER_ID_REN,
					A.PENDIDIKAN_REN, D.tktpend PENDIDIKAN,
					JABATAN_ID_REN, C.namjab JABATAN, USIA_REN, A.TAHUN, AMBIL_UMUR(G.TGLLAHIR) UMUR,
					JABATAN_STRUK_ID_PU, JABATAN_FUNGS_ID_PU, 
					C.DIKLAT_TEKNIS_ID, H.NAMA DIKLAT_TEKNIS, C.DIKLAT_STRUKTURAL_ID, E.diklat DIKLAT_STRUKTURAL, C.SKP, C.PK,
					C.KOMPETENSI_DASAR, C.KOMPETENSI_BIDANG, C.KOMPETENSI_TEKNIK, C.KESEHATAN
					FROM perencanaan_detil A
					LEFT JOIN pangkat B ON A.PANGKAT_ID_REN = B.pangkat_id
					LEFT JOIN
					(
						SELECT 
						TIPE_JABATAN, TIPE, kdjab, DIKLAT_TEKNIS_ID, DIKLAT_STRUKTURAL_ID, PENDIDIKAN_ID_PU, kdjab_old, namjab, JABATAN_STRUK_ID_PU, JABATAN_FUNGS_ID_PU, JABATAN_ID,
						SKP, PK, KOMPETENSI_DASAR, KOMPETENSI_BIDANG, KOMPETENSI_TEKNIK, KESEHATAN
						FROM
						(
							SELECT 1 TIPE_JABATAN, 'JABATAN STRUKTURAL' TIPE, kdjab, DIKLAT_TEKNIS_ID, DIKLAT_STRUKTURAL_ID, PENDIDIKAN_ID_PU, kdjab_old, namjab, B.JABATAN_STRUK_ID_PU, '' JABATAN_FUNGS_ID_PU, B.JABATAN_ID,
							SKP, PK, KOMPETENSI_DASAR, KOMPETENSI_BIDANG, KOMPETENSI_TEKNIK, KESEHATAN
							FROM pola_karir_pu.r_jabatan A
							LEFT JOIN jabatan B ON A.kdjab = B.JABATAN_STRUK_ID_PU
							WHERE 1=1
							UNION ALL
							SELECT 2 TIPE_JABATAN, 'JABATAN FUNGSIONAL' TIPE, kdjab, DIKLAT_TEKNIS_ID, DIKLAT_STRUKTURAL_ID, PENDIDIKAN_ID_PU, kdjab_old, namjab, '' JABATAN_STRUK_ID_PU, B.JABATAN_FUNGS_ID_PU, B.JABATAN_ID,
							SKP, PK, KOMPETENSI_DASAR, KOMPETENSI_BIDANG, KOMPETENSI_TEKNIK, KESEHATAN
							FROM pola_karir_pu.r_jabatan_fungsional A
							LEFT JOIN jabatan B ON A.kdjab = B.JABATAN_FUNGS_ID_PU
							WHERE 1=1
						) A
						WHERE 1=1
					) C ON C.JABATAN_ID = A.JABATAN_ID_REN
					LEFT JOIN pola_karir_pu.r_tingkat_pendidikan D ON C.PENDIDIKAN_ID_PU = D.kdtktpend
					LEFT JOIN pola_karir_pu.r_diklatpim E ON E.kddiklat = C.DIKLAT_STRUKTURAL_ID
					LEFT JOIN pola_karir_pu.t_unit_kerja F ON F.kdunit = A.satker_id_ren
					LEFT JOIN pola_karir_pu.t_tmp_pegawai_big G ON G.nipbaru = A.PEGAWAI_ID
					LEFT JOIN diklat_teknis H ON C.DIKLAT_TEKNIS_ID = H.DIKLAT_TEKNIS_ID
					WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsBak5($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY PERENCANAAN_DETIL_ID DESC")
	{
		$str = "
				SELECT 
				PERENCANAAN_DETIL_ID, TIPE_RENCANA, pola_karir.AMBIL_TIPE_RENCANA(TIPE_RENCANA) TIPE_RENCANA_DETIL, A.PEGAWAI_ID,
				A.PANGKAT_ID_REN, CONCAT(B.KODE, ' (', B.NAMA, ')') PANGKAT_REN, F.namunit SATKER_REN, A.SATKER_ID_REN, A.PENDIDIKAN_REN,
				JABATAN_ID_REN, C.namjab JABATAN, USIA_REN, A.TAHUN, AMBIL_UMUR(G.TGLLAHIR) UMUR
				FROM pola_karir.perencanaan_detil A
				LEFT JOIN pola_karir.pangkat B ON A.PANGKAT_ID_REN = B.pangkat_id
				LEFT JOIN pola_karir_pu.r_jabatan C ON C.kdjab = A.JABATAN_ID_REN
				LEFT JOIN pola_karir.pendidikan D ON D.PENDIDIKAN_ID = A.PENDIDIKAN_REN 
				LEFT JOIN pola_karir_pu.t_unit_kerja F ON F.kdunit = A.satker_id_ren
				LEFT JOIN pola_karir_pu.t_tmp_pegawai_big G ON G.nipbaru = A.PEGAWAI_ID
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsBak($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY PERENCANAAN_DETIL_ID DESC")
	{
		$str = "
				SELECT 
				AMBIL_SATKER_NAMA(E.SATKER_ID) UNIT_KERJA,
				PERENCANAAN_DETIL_ID, A.TAHUN, A.PEGAWAI_ID, TIPE_RENCANA, AMBIL_TIPE_RENCANA(TIPE_RENCANA) TIPE_RENCANA_DETIL, 
				PERENCANAAN_ID, JABATAN_ID_REN, C.JABATAN_ID, C.JABATAN, JABATAN_ID_CAPAI, USIA_REN, USIA_CAPAI, 
				SATKER_ID_REN, AMBIL_SATKER_NAMA(SATKER_ID_REN) SATKER_REN, SATKER_ID_CAPAI, TMT_JABATAN_REN, TMT_JABATAN_CAPAI, 
				A.PANGKAT_ID_REN, CONCAT(B.NAMA, ' (', B.KODE, ')') PANGKAT_REN, 
				PANGKAT_ID_CAPAI, PENDIDIKAN_REN, D.NAMA PENDIDIKAN,
				PENDIDIKAN_CAPAI, KINERJA_SKP_REN, KINERJA_SKP_CAPAI, KINERJA_PK_REN, KINERJA_PK_CAPAI, DIKLAT_STRUK_REN, DIKLAT_STRUK_CAPAI, 
				DIKLAT_TEKNIS_REN, DIKLAT_TEKNIS_CAPAI, C.FORMAT, C.UKURAN, C.FOTO_BLOB
				FROM perencanaan_detil A
				LEFT JOIN pangkat B ON A.pangkat_id_ren = B.PANGKAT_ID
				LEFT JOIN jabatan C ON C.JABATAN_ID = A.JABATAN_ID_REN
				LEFT JOIN pendidikan D ON D.PENDIDIKAN_ID = A.PENDIDIKAN_REN
				LEFT JOIN pegawai E ON A.PEGAWAI_ID = E.PEGAWAI_ID
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
    
	function selectByParamsNetworkBak4($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT JABATAN, TAHUN, USIA, TMT
				FROM
				(
				SELECT A.uraian JABATAN, TO_CHAR((MAX(A.tglmulai)), 'YYYY') TAHUN, CONCAT(TO_CHAR((MAX(A.tglmulai)), 'YYYY') - TO_CHAR(B.tgllahir, 'YYYY'), ' Tahun') USIA, MAX(A.tglmulai) TMT
				FROM pola_karir_pu.t_riwayat_jabatan A
				INNER JOIN pola_karir_pu.t_tmp_pegawai_big B ON A.nipbaru = B.nipbaru
				WHERE A.nipbaru = '".$pegawaiId."'
				GROUP BY A.nipbaru
				ORDER BY A.nipbaru asc
				) A
				UNION ALL
				SELECT 
				B.namjab JABATAN, A.TAHUN, CONCAT(USIA_REN, ' Tahun') USIA, '' TMT
				FROM perencanaan_detil A
				LEFT JOIN pola_karir_pu.r_jabatan B ON B.kdjab = A.JABATAN_ID_REN
				WHERE 1=1 AND A.PEGAWAI_ID = '".$pegawaiId."'
				ORDER BY USIA ASC
				"; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsNetworkBak3($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT *
				FROM
				(
				SELECT
				'Analis Kepegawaian' JABATAN, '2012' TAHUN, '25 Tahun' USIA, '02 September 2014' TMT, AMBIL_SATKER_NAMA(A.SATKER_ID) UNIT_KERJA
				FROM PEGAWAI A
				WHERE 1 = 1 AND A.PEGAWAI_ID = ".$pegawaiId."
				UNION ALL
				SELECT 
				C.JABATAN, A.TAHUN, CONCAT(USIA_REN, ' Tahun') USIA, '' TMT, E.NAMA UNIT_KERJA
				FROM perencanaan_detil A
				LEFT JOIN pangkat B ON A.pangkat_id_ren = B.PANGKAT_ID
				LEFT JOIN jabatan C ON C.JABATAN_ID = A.JABATAN_ID_REN
				LEFT JOIN pendidikan D ON D.PENDIDIKAN_ID = A.PENDIDIKAN_REN
				LEFT JOIN satker E ON A.SATKER_ID_REN = E.SATKER_ID
				WHERE 1=1 AND A.PEGAWAI_ID = ".$pegawaiId."
				) A WHERE 1=1 ".$statement."
				ORDER BY USIA ASC
				"; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsNetwork($pegawaiId=0, $statement="", $sOrder= "")
	{
		$str = "
				SELECT 
				data_rencana, JABATAN, TAHUN, USIA, TMT, RUMPUN_JABATAN_ID, NAMA_UNKER UNIT_KERJA
				FROM
				(
					SELECT 1 data_rencana, A.LAST_JABATAN JABATAN, '' TAHUN, '' USIA, 
						'' TMT, '' RUMPUN_JABATAN_ID, B.NAMA NAMA_UNKER
					FROM ".$this->db.".pegawai A
					INNER JOIN ".$this->db.".satker B ON A.SATKER_ID = B.SATKER_ID
					INNER JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
					WHERE 1=1 AND A.PEGAWAI_ID = ".$pegawaiId."
					UNION ALL
					SELECT 
						2 data_rencana, '' JABATAN, A.TAHUN, 
						CONCAT(USIA_REN, ' Tahun') USIA, '' TMT, '' RUMPUN_JABATAN_ID, B.NAMA NAMA_UNKER
					FROM perencanaan_detil A
					INNER JOIN ".$this->db.".satker B ON A.SATKER_ID_REN = B.SATKER_ID
					WHERE 1=1 AND A.PEGAWAI_ID = ".$pegawaiId."
				) A WHERE 1=1
				".$statement." ".$sOrder; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsNetworkBak5($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT 
				data_rencana, JABATAN, TAHUN, USIA, TMT, RUMPUN_JABATAN_ID
				FROM
				(
				SELECT 
					1 data_rencana, A.uraian JABATAN, TO_CHAR(A.tglmulai, 'YYYY') TAHUN, 
					CONCAT(TO_CHAR(A.tglmulai, 'YYYY') - TO_CHAR(B.tgllahir, 'YYYY'), ' Tahun') USIA, A.tglmulai TMT, '' RUMPUN_JABATAN_ID
				FROM pola_karir_pu.t_riwayat_jabatan A
				INNER JOIN pola_karir_pu.t_tmp_pegawai_big B ON A.nipbaru = B.nipbaru
				WHERE A.nipbaru = '".$pegawaiId."'
				ORDER BY A.nipbaru asc
				) A
				UNION ALL
				SELECT 
					2 data_rencana, B.namjab JABATAN, A.TAHUN, 
					CONCAT(USIA_REN, ' Tahun') USIA, '' TMT, RUMPUN_JABATAN_ID
				FROM perencanaan_detil A
				LEFT JOIN
				(
					SELECT 
					TIPE_JABATAN, TIPE, kdjab, kdjab_old, namjab, JABATAN_STRUK_ID_PU, JABATAN_FUNGS_ID_PU, JABATAN_ID
					FROM
					(
						SELECT 1 TIPE_JABATAN, 'JABATAN STRUKTURAL' TIPE, kdjab, kdjab_old, namjab, B.JABATAN_STRUK_ID_PU, '' JABATAN_FUNGS_ID_PU, B.JABATAN_ID
						FROM pola_karir_pu.r_jabatan A
						LEFT JOIN jabatan B ON A.kdjab = B.JABATAN_STRUK_ID_PU
						WHERE 1=1
						UNION ALL
						SELECT 2 TIPE_JABATAN, 'JABATAN FUNGSIONAL' TIPE, kdjab, kdjab_old, namjab, '' JABATAN_STRUK_ID_PU, B.JABATAN_FUNGS_ID_PU, B.JABATAN_ID
						FROM pola_karir_pu.r_jabatan_fungsional A
						LEFT JOIN jabatan B ON A.kdjab = B.JABATAN_FUNGS_ID_PU
						WHERE 1=1
					) A
				) B ON B.JABATAN_ID = A.JABATAN_ID_REN
				LEFT JOIN rumpun_jabatan_detil C ON B.JABATAN_ID = C.JABATAN_ID
				WHERE 1=1 AND A.PEGAWAI_ID = '".$pegawaiId."'
				ORDER BY USIA ASC
				"; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsNetworkBak2($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT JABATAN, TAHUN, USIA, TMT
				FROM
				(
				SELECT A.uraian JABATAN, TO_CHAR((MAX(A.tglmulai)), 'YYYY') TAHUN, CONCAT(TO_CHAR((MAX(A.tglmulai)), 'YYYY') - TO_CHAR(B.tgllahir, 'YYYY'), ' Tahun') USIA, MAX(A.tglmulai) TMT
				FROM pola_karir_pu.t_riwayat_jabatan A
				INNER JOIN pola_karir_pu.t_tmp_pegawai_big B ON A.nipbaru = B.nipbaru
				WHERE A.nipbaru = '".$pegawaiId."'
				GROUP BY A.nipbaru
				ORDER BY A.nipbaru asc
				) A
				UNION ALL
				SELECT 
				B.namjab JABATAN, A.TAHUN, CONCAT(USIA_REN, ' Tahun') USIA, '' TMT
				FROM perencanaan_detil A
				LEFT JOIN pola_karir_pu.r_jabatan B ON B.kdjab = A.JABATAN_ID_REN
				WHERE 1=1 AND A.PEGAWAI_ID = '".$pegawaiId."'
				ORDER BY USIA ASC
				"; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsNetworkBak($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT *
				FROM
				(
				SELECT
				'Analis Kepegawaian' JABATAN, '2012' TAHUN, '25 Tahun' USIA, '02 September 2014' TMT, AMBIL_SATKER_NAMA(A.SATKER_ID) UNIT_KERJA
				FROM PEGAWAI A
				WHERE 1 = 1 AND A.PEGAWAI_ID = ".$pegawaiId."
				UNION ALL
				SELECT 
				C.JABATAN, A.TAHUN, CONCAT(USIA_REN, ' Tahun') USIA, '' TMT, E.NAMA UNIT_KERJA
				FROM perencanaan_detil A
				LEFT JOIN pangkat B ON A.pangkat_id_ren = B.PANGKAT_ID
				LEFT JOIN jabatan C ON C.JABATAN_ID = A.JABATAN_ID_REN
				LEFT JOIN pendidikan D ON D.PENDIDIKAN_ID = A.PENDIDIKAN_REN
				LEFT JOIN satker E ON A.SATKER_ID_REN = E.SATKER_ID
				WHERE 1=1 AND A.PEGAWAI_ID = ".$pegawaiId."
				) A WHERE 1=1 ".$statement."
				ORDER BY USIA ASC
				"; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT 
				PERENCANAAN_DETIL_ID, PEGAWAI_ID, TIPE_RENCANA, PERENCANAAN_ID, JABATAN_ID_REN, JABATAN_ID_CAPAI, USIA_REN, USIA_CAPAI, 
				SATKER_ID_REN, SATKER_ID_CAPAI, TMT_JABATAN_REN, TMT_JABATAN_CAPAI, PANGKAT_ID_REN, PANGKAT_ID_CAPAI, PENDIDIKAN_REN, 
				PENDIDIKAN_CAPAI, KINERJA_SKP_REN, KINERJA_SKP_CAPAI, KINERJA_PK_REN, KINERJA_PK_CAPAI, DIKLAT_STRUK_REN, DIKLAT_STRUK_CAPAI, 
				DIKLAT_TEKNIS_REN, DIKLAT_TEKNIS_CAPAI 
				FROM perencanaan_detil
				WHERE 1=1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PERENCANAAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	
	function getCountByParamsNetwork($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT 
				COUNT(1) AS ROWCOUNT
				FROM
				(
								SELECT 
								data_rencana, JABATAN, TAHUN, USIA, TMT, RUMPUN_JABATAN_ID
								FROM
								(
									SELECT 1 data_rencana, A.NAMA_JAB JABATAN, TO_CHAR(A.TGL_MULAI, 'YYYY') TAHUN,
										CONCAT(TO_CHAR(A.TGL_MULAI, 'YYYY') - TO_CHAR(B.TGL_LAHIR, 'YYYY'), ' Tahun') USIA, 
										A.TANGGAL_SK TMT, '' RUMPUN_JABATAN_ID
									FROM ".$this->db.".datajenjabatan A
									INNER JOIN ".$this->db.".rb_data_pegawai B ON (A.NIP = B.NIP_BARU OR A.NIP = B.NIP_LAMA)
									WHERE 1=1 AND A.NIP = '".$pegawaiId."'
									UNION ALL
									SELECT 1 data_rencana, B.NAMA_FUNG JABATAN, TO_CHAR(A.TANGGAL_SK, 'YYYY') TAHUN,
										CONCAT(TO_CHAR(A.TMT_JF, 'YYYY') - TO_CHAR(C.TGL_LAHIR, 'YYYY'), ' Tahun') USIA, 
										A.TANGGAL_SK TMT, '' RUMPUN_JABATAN_ID
									FROM ".$this->db.".datajenjabfung A
									INNER JOIN ".$this->db.".rb_ref_fung B ON A.KODE_FUNG = B.KODE_FUNG
									INNER JOIN ".$this->db.".rb_data_pegawai C ON (A.NIP = C.NIP_BARU OR A.NIP = C.NIP_LAMA)
									WHERE 1=1 AND A.NIP = '".$pegawaiId."'
								) A
				) A 
				WHERE 1=1
				".$statement; 
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
			
    }
				
	function getCountByParamsNetworkBak5($pegawaiId=0, $statement="")
	{
		$str = "
				SELECT 
				COUNT(1) AS ROWCOUNT
				FROM
				(
								SELECT data_rencana, JABATAN, TAHUN, USIA, TMT, RUMPUN_JABATAN_ID
								FROM
								(
								SELECT 
									1 data_rencana, A.uraian JABATAN, TO_CHAR(A.tglmulai, 'YYYY') TAHUN, 
									CONCAT(TO_CHAR(A.tglmulai, 'YYYY') - TO_CHAR(B.tgllahir, 'YYYY'), ' Tahun') USIA, A.tglmulai TMT, '' RUMPUN_JABATAN_ID
								FROM pola_karir_pu.t_riwayat_jabatan A
								INNER JOIN pola_karir_pu.t_tmp_pegawai_big B ON A.nipbaru = B.nipbaru
								WHERE A.nipbaru = '199107252011012001'
								ORDER BY A.nipbaru asc
								) A
								UNION ALL
								SELECT 
									2 data_rencana, B.namjab JABATAN, A.TAHUN, 
									CONCAT(USIA_REN, ' Tahun') USIA, '' TMT, RUMPUN_JABATAN_ID
								FROM perencanaan_detil A
								LEFT JOIN
								(
									SELECT 
									TIPE_JABATAN, TIPE, kdjab, kdjab_old, namjab, JABATAN_STRUK_ID_PU, JABATAN_FUNGS_ID_PU, JABATAN_ID
									FROM
									(
										SELECT 1 TIPE_JABATAN, 'JABATAN STRUKTURAL' TIPE, kdjab, kdjab_old, namjab, B.JABATAN_STRUK_ID_PU, '' JABATAN_FUNGS_ID_PU, B.JABATAN_ID
										FROM pola_karir_pu.r_jabatan A
										LEFT JOIN jabatan B ON A.kdjab = B.JABATAN_STRUK_ID_PU
										WHERE 1=1
										UNION ALL
										SELECT 2 TIPE_JABATAN, 'JABATAN FUNGSIONAL' TIPE, kdjab, kdjab_old, namjab, '' JABATAN_STRUK_ID_PU, B.JABATAN_FUNGS_ID_PU, B.JABATAN_ID
										FROM pola_karir_pu.r_jabatan_fungsional A
										LEFT JOIN jabatan B ON A.kdjab = B.JABATAN_FUNGS_ID_PU
										WHERE 1=1
									) A
								) B ON B.JABATAN_ID = A.JABATAN_ID_REN
								LEFT JOIN rumpun_jabatan_detil C ON B.JABATAN_ID = C.JABATAN_ID
								WHERE 1=1 AND A.PEGAWAI_ID = '199107252011012001'
								ORDER BY USIA ASC
				) A 
				WHERE 1=1
				".$statement; 
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
			
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT 
				COUNT(1) AS ROWCOUNT
				FROM perencanaan_detil
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERENCANAAN_DETIL_ID) AS ROWCOUNT FROM perencanaan_detil
		        WHERE PERENCANAAN_DETIL_ID IS NOT NULL ".$statement; 
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
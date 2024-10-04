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
  //include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/Entity.php");

  class Lowongan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Lowongan()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT 
		A.JADWAL_TES_ID DIKLAT_ID, A.ACARA NAMA_DIKLAT, A.TANGGAL_TES TANGGAL_AWAL, A.TANGGAL_TES TANGGAL_AKHIR
		, A.TEMPAT, A.ALAMAT, A.KETERANGAN
		FROM jadwal_tes A
		left join jadwal_awal_tes_simulasi_pegawai b on a.jadwal_tes_id = b.jadwal_awal_tes_simulasi_id
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM jadwal_tes A
		left join jadwal_awal_tes_simulasi_pegawai b on a.jadwal_tes_id = b.jadwal_awal_tes_simulasi_id
		WHERE 1 = 1 ".$statement; 

		// $str = "SELECT COUNT(1) AS ROWCOUNT FROM pds_rekrutmen.LOWONGAN A WHERE 1=1 ".$statement; 
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

    function selectByParamsBatas($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_AWAL_TES_SIMULASI_ID ASC")
	{
		$str = "
		SELECT
			JADWAL_AWAL_TES_SIMULASI_ID, JADWAL_AWAL_TES_ID, TANGGAL_TES
			, ACARA, TEMPAT, ALAMAT, KETERANGAN, COALESCE(BATAS_PEGAWAI,0) BATAS_PEGAWAI
		FROM jadwal_awal_tes_simulasi A
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsFasilitator($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.DIKLAT_FASILITATOR_ID, A.DIKLAT_ID, A.UNIT_KERJA_NAMA, A.NAMA, A.NIP, A.JENIS_KELAMIN
			, CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA
			, A.MATERI
		FROM DIKLAT_FASILITATOR A
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
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOWONGAN_ID", $this->getAdminNextId("LOWONGAN_ID","pds_rekrutmen.LOWONGAN")); 

		$str = "
				INSERT INTO pds_rekrutmen.LOWONGAN(
						LOWONGAN_ID, KODE, NOMOR_GENERATE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, JABATAN_ID, 
						JUMLAH, PENEMPATAN, PERSYARATAN, LAST_CREATE_USER, 
						LAST_CREATE_DATE, DOKUMEN, DOKUMEN_WAJIB, MANUAL, IS_FISIK, IS_KESEMAPTAAN, KETERANGAN, CABANG_P3_ID, STATUS_PEGAWAI_PROYEK)
				VALUES ('".$this->getField("LOWONGAN_ID")."', '".$this->getField("KODE")."', '".$this->getField("NOMOR_GENERATE")."', ".$this->getField("TANGGAL").", ".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("JABATAN_ID")."',
						'".$this->getField("JUMLAH")."', '".$this->getField("PENEMPATAN")."', '".$this->getField("PERSYARATAN")."', '".$this->getField("LAST_CREATE_USER")."',
						".$this->getField("LAST_CREATE_DATE").", '".$this->getField("DOKUMEN")."', '".$this->getField("DOKUMEN_WAJIB")."', '".$this->getField("MANUAL")."', '".$this->getField("IS_FISIK")."', '".$this->getField("IS_KESEMAPTAAN")."', '".$this->getField("KETERANGAN")."', ".$this->getField("CABANG_P3_ID").", ".$this->getField("STATUS_PEGAWAI_PROYEK").")
				"; 
		$this->query = $str;
		$this->id = $this->getField("LOWONGAN_ID");
		return $this->execQuery($str);
    }

	function insertUndangan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOWONGAN_ID", $this->getNextId("LOWONGAN_ID","pds_rekrutmen.LOWONGAN")); 

		$str = "
				INSERT INTO pds_rekrutmen.LOWONGAN(
						LOWONGAN_ID, KODE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, JABATAN_ID, 
						JUMLAH, PENEMPATAN, PERSYARATAN, LAST_CREATE_USER, 
						LAST_CREATE_DATE, DOKUMEN, DOKUMEN_WAJIB, STATUS, UNDANGAN, KETERANGAN, CABANG_P3_ID, STATUS_PEGAWAI_PROYEK)
				VALUES ('".$this->getField("LOWONGAN_ID")."', '".$this->getField("KODE")."', ".$this->getField("TANGGAL").", ".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("JABATAN_ID")."',
						'".$this->getField("JUMLAH")."', '".$this->getField("PENEMPATAN")."', '".$this->getField("PERSYARATAN")."', '".$this->getField("LAST_CREATE_USER")."',
						".$this->getField("LAST_CREATE_DATE").", '".$this->getField("DOKUMEN")."', '".$this->getField("DOKUMEN_WAJIB")."', 0, 'Y', '".$this->getField("KETERANGAN")."', ".$this->getField("CABANG_P3_ID").", ".$this->getField("STATUS_PEGAWAI_PROYEK").")
				"; 
		$this->query = $str;
		$this->id = $this->getField("LOWONGAN_ID");
		echo $str;
		return $this->execQuery($str);
    }
		
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE pds_rekrutmen.LOWONGAN SET 
					JABATAN_ID 			= '".$this->getField("JABATAN_ID")."',
					TANGGAL 			= ".$this->getField("TANGGAL").",
					TANGGAL_AWAL 		= ".$this->getField("TANGGAL_AWAL").",
					TANGGAL_AKHIR 		= ".$this->getField("TANGGAL_AKHIR").", 
					JUMLAH 				= '".$this->getField("JUMLAH")."', 
					PENEMPATAN 			= '".$this->getField("PENEMPATAN")."', 
					PERSYARATAN 		= '".$this->getField("PERSYARATAN")."', 
					DOKUMEN 			= '".$this->getField("DOKUMEN")."', 
					DOKUMEN_WAJIB 		= '".$this->getField("DOKUMEN_WAJIB")."', 
					LAST_UPDATE_USER 	= '".$this->getField("LAST_UPDATE_USER")."', 
					LAST_UPDATE_DATE 	= ".$this->getField("LAST_UPDATE_DATE").",
					MANUAL 				= '".$this->getField("MANUAL")."',
					IS_FISIK			= '".$this->getField("IS_FISIK")."', 
					IS_KESEMAPTAAN		= '".$this->getField("IS_KESEMAPTAAN")."',
					KETERANGAN			= '".$this->getField("KETERANGAN")."',
					CABANG_P3_ID		= '".$this->getField("CABANG_P3_ID")."',
					STATUS_PEGAWAI_PROYEK= ".$this->getField("STATUS_PEGAWAI_PROYEK")."
			 	WHERE LOWONGAN_ID		= '".$this->getField("LOWONGAN_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }

	function callProsesCopyLowongan()
	{
		$str = "
				SELECT pds_rekrutmen.PROSES_COPY_LOWONGAN(".$this->getField("LOWONGAN_ID").")
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function update_file()
	{
		$str = "UPDATE pds_rekrutmen.LOWONGAN SET
				  LINK_FILE = '".$this->getField("LINK_FILE")."'
				WHERE LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updatePerpanjangan()
	{
		$str = "UPDATE pds_rekrutmen.LOWONGAN 
				SET
				  TANGGAL_AKHIR = ".$this->getField("TANGGAL_AKHIR")."
				WHERE LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'
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
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.LOWONGAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }
		
	function delete()
	{
        $str = "
				DELETE FROM pds_rekrutmen.LOWONGAN
                WHERE 
                  LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'
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
    
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
			SELECT 
				A.LOWONGAN_ID, A.KODE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, A.JABATAN_ID, 
				JUMLAH, LINK_FILE, PENEMPATAN, PERSYARATAN, DOKUMEN_WAJIB, A.LAST_CREATE_USER, 
				A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, B.NAMA JABATAN, A.DOKUMEN, IS_FISIK, IS_KESEMAPTAAN,
				A.PUBLISH, CASE WHEN A.STATUS = 1 THEN 'Ya' ELSE 'Tidak' END STATUS_KETERANGAN, MANUAL, A.KETERANGAN,
				A.STATUS_UNDANGAN,  CASE WHEN A.STATUS_UNDANGAN = 1 THEN 'Ya' ELSE 'Tidak' END STATUS_UNDANGAN_KETERANGAN, A.CABANG_P3_ID, STATUS_SELESAI,
				CASE WHEN A.STATUS_SELESAI = 1 THEN 'Ya' ELSE 'Tidak' END STATUS_SELESAI_KETERANGAN
				, A.STATUS_PEGAWAI_PROYEK, COALESCE(PLS.STATUS_LOWONGAN_SHORTLIST,0) STATUS_LOWONGAN_SHORTLIST
			FROM pds_rekrutmen.LOWONGAN A
			LEFT JOIN pds_rekrutmen.JABATAN B ON B.JABATAN_ID = A.JABATAN_ID
			LEFT JOIN
			(
				SELECT LOWONGAN_ID, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS_LOWONGAN_SHORTLIST
				FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST
				GROUP BY LOWONGAN_ID
			) PLS ON A.LOWONGAN_ID = PLS.LOWONGAN_ID
			WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsUndangan($pelamarId, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.LOWONGAN_ID, A.KODE, A.TANGGAL, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.JABATAN_ID, 
					   JUMLAH, LINK_FILE, PENEMPATAN, PERSYARATAN, DOKUMEN_WAJIB, A.LAST_CREATE_USER, 
					   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, B.NAMA JABATAN, A.DOKUMEN,
					   A.PUBLISH, CASE WHEN A.STATUS = 1 THEN 'Ya' ELSE 'Tidak' END STATUS_KETERANGAN, MANUAL, A.KETERANGAN,
					   A.STATUS_UNDANGAN,  CASE WHEN A.STATUS_UNDANGAN = 1 THEN 'Ya' ELSE 'Tidak' END STATUS_UNDANGAN_KETERANGAN,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE A.LOWONGAN_ID = X.LOWONGAN_ID) JUMLAH_UNDANGAN,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE A.LOWONGAN_ID = X.LOWONGAN_ID AND X.TANGGAL_KIRIM IS NOT NULL) JUMLAH_PELAMAR,
					   C.PELAMAR_ID, A.CABANG_P3_ID
				  FROM pds_rekrutmen.LOWONGAN A
				  LEFT JOIN pds_rekrutmen.JABATAN B ON B.JABATAN_ID = A.JABATAN_ID			
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN C ON A.LOWONGAN_ID = C.LOWONGAN_ID AND C.PELAMAR_ID = ".$pelamarId."		  
				 WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsPelamar($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT LOWONGAN_ID, A.KODE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, A.JABATAN_ID, 
					   JUMLAH, LINK_FILE, PENEMPATAN, PERSYARATAN, DOKUMEN_WAJIB, A.LAST_CREATE_USER, 
					   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, B.NAMA JABATAN, A.DOKUMEN,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN X WHERE X.LOWONGAN_ID = A.LOWONGAN_ID AND TANGGAL_KIRIM IS NOT NULL) JUMLAH_PELAMAR,
					   (SELECT COUNT(1) FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.LOWONGAN_ID = A.LOWONGAN_ID) JUMLAH_SHORTLIST,
					   A.CABANG_P3_ID
				  FROM pds_rekrutmen.LOWONGAN A
				  LEFT JOIN pds_rekrutmen.JABATAN B ON B.JABATAN_ID = A.JABATAN_ID
				 WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
		
	function selectByParamsInformasi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT LOWONGAN_ID, A.KODE, TANGGAL, TANGGAL_AWAL, TANGGAL_AKHIR, A.JABATAN_ID, 
					   JUMLAH, LINK_FILE, REPLACE(PENEMPATAN, '($$)', ',') PENEMPATAN, PERSYARATAN, A.LAST_CREATE_USER, 
					   A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, B.NAMA JABATAN, A.DOKUMEN
				  FROM pds_rekrutmen.LOWONGAN A
				  LEFT JOIN pds_rekrutmen.JABATAN B ON B.JABATAN_ID = A.JABATAN_ID
				 WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
	function selectByParamsComboPersyaratan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM pds_rekrutmen.PELAMAR_PERSYARATAN A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsComboPenempatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM pds_rekrutmen.PELAMAR_PENEMPATAN A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsComboDokumen($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM pds_rekrutmen.PELAMAR_DOKUMEN A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	
	function getCountByParamsGenerateNomor($tahun='', $statement="")
	{
		$str = "
		SELECT GENERATEZERO(CAST((COALESCE(MAX(CAST(NOMOR_GENERATE AS INTEGER)), 0) + 1) AS TEXT), 4) AS ROWCOUNT 
		FROM pds_rekrutmen.LOWONGAN A WHERE 1=1 AND TO_CHAR(TANGGAL, 'YYYY') = '".$tahun."' ".$statement;
		$this->select($str);
		$this->query = $str;
		//echo $str;exit;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>
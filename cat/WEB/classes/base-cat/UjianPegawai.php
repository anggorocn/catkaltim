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

  class UjianPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianPegawai()
	{
      $this->Entity(); 
    }
	
	function insert($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat_pegawai.ujian_pegawai_".$jadwaltesid)); 

		$str = "
		INSERT INTO cat_pegawai.ujian_pegawai_".$jadwaltesid." (
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, 
			FORMULA_ESELON_ID, TIPE_UJIAN_ID, 
			UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT,
			BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID,
			LAST_CREATE_DATE, LAST_CREATE_USER
		) 
		VALUES (
			(SELECT UJIAN_PEGAWAI_DAFTAR_ID FROM cat.ujian_pegawai_daftar WHERE UJIAN_ID = ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			(SELECT TIPE_UJIAN_ID FROM cat.bank_soal WHERE BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."),
			".$this->getField("UJIAN_PEGAWAI_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_BANK_SOAL_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("TANGGAL").",
			".$this->getField("URUT").",
			".$this->getField("BANK_SOAL_ID").",
			".$this->getField("BANK_SOAL_PILIHAN_ID").",
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("LAST_CREATE_DATE").",
			'".$this->getField("LAST_CREATE_USER")."'
		)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		return $this->execQuery($str);
    }

    function insertWpt($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat_pegawai.ujian_pegawai_".$jadwaltesid)); 

		$str = "
		INSERT INTO cat_pegawai.ujian_pegawai_".$jadwaltesid." (
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, 
			FORMULA_ESELON_ID, TIPE_UJIAN_ID, 
			UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT,
			BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID,
			LAST_CREATE_DATE, LAST_CREATE_USER
		) 
		VALUES (
			(SELECT UJIAN_PEGAWAI_DAFTAR_ID FROM cat.ujian_pegawai_daftar WHERE UJIAN_ID = ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			(SELECT TIPE_UJIAN_ID FROM cat.wpt_soal WHERE WPT_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."),
			".$this->getField("UJIAN_PEGAWAI_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_BANK_SOAL_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("TANGGAL").",
			".$this->getField("URUT").",
			".$this->getField("BANK_SOAL_ID").",
			".$this->getField("BANK_SOAL_PILIHAN_ID").",
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("LAST_CREATE_DATE").",
			'".$this->getField("LAST_CREATE_USER")."'
		)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		return $this->execQuery($str);
    }

    function updateNoUrut($jadwaltesid)
	{
		$str = "
		UPDATE cat_pegawai.ujian_pegawai_".$jadwaltesid." 
		SET
			LAST_CREATE_DATE= ".$this->getField("LAST_CREATE_DATE").",
			LAST_CREATE_USER= '".$this->getField("LAST_CREATE_USER")."'
		WHERE BANK_SOAL_PILIHAN_ID= ".$this->getField("BANK_SOAL_PILIHAN_ID")."
		and PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
		AND BANK_SOAL_ID= ".$this->getField("BANK_SOAL_ID")."
		"; 
		$this->query = $str;
		// echo $str; exit;
		return $this->execQuery($str);
    }

    function update($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat_pegawai.ujian_pegawai_".$jadwaltesid." SET
					  UJIAN_ID= ".$this->getField("UJIAN_ID").",
					  UJIAN_BANK_SOAL_ID= ".$this->getField("UJIAN_BANK_SOAL_ID").",
					  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					  TANGGAL= ".$this->getField("TANGGAL").",
					  URUT= ".$this->getField("URUT").",
					  BANK_SOAL_ID= ".$this->getField("BANK_SOAL_ID").",
					  BANK_SOAL_PILIHAN_ID= ".$this->getField("BANK_SOAL_PILIHAN_ID").",
					  LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_PEGAWAI_ID= ".$this->getField("UJIAN_PEGAWAI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function deleteId($jadwaltesid)
	{
        $str = "DELETE FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
                WHERE 
                  UJIAN_PEGAWAI_ID = '".$this->getField("UJIAN_PEGAWAI_ID")."'"; 
				  
		$this->query = $str;
		//echo $str;exit;
        return $this->execQuery($str);
    }

    function insertKeterangan($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid)); 

		$str = "
		INSERT INTO cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." (
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, TIPE_UJIAN_ID, 
			UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT,
			BANK_SOAL_ID, KETERANGAN, UJIAN_TAHAP_ID,
			LAST_CREATE_DATE, LAST_CREATE_USER
		) 
		VALUES (
			(SELECT UJIAN_PEGAWAI_DAFTAR_ID FROM cat.ujian_pegawai_daftar WHERE UJIAN_ID = ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			(SELECT TIPE_UJIAN_ID FROM cat.bank_soal WHERE BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."),
			".$this->getField("UJIAN_PEGAWAI_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_BANK_SOAL_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("TANGGAL").",
			".$this->getField("URUT").",
			".$this->getField("BANK_SOAL_ID").",
			'".$this->getField("KETERANGAN")."',
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("LAST_CREATE_DATE").",
			'".$this->getField("LAST_CREATE_USER")."'
		)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function insertWptKeterangan($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid)); 

		$str = "
		INSERT INTO cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." (
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, TIPE_UJIAN_ID, 
			UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT,
			BANK_SOAL_ID, KETERANGAN, UJIAN_TAHAP_ID,
			LAST_CREATE_DATE, LAST_CREATE_USER
		) 
		VALUES (
			(SELECT UJIAN_PEGAWAI_DAFTAR_ID FROM cat.ujian_pegawai_daftar WHERE UJIAN_ID = ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			(SELECT TIPE_UJIAN_ID FROM cat.wpt_soal WHERE WPT_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."),
			".$this->getField("UJIAN_PEGAWAI_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_BANK_SOAL_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("TANGGAL").",
			".$this->getField("URUT").",
			".$this->getField("BANK_SOAL_ID").",
			'".$this->getField("KETERANGAN")."',
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("LAST_CREATE_DATE").",
			'".$this->getField("LAST_CREATE_USER")."'
		)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function insertKertihKeterangan($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid)); 

		$str = "
		INSERT INTO cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." (
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, TIPE_UJIAN_ID, 
			UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT,
			BANK_SOAL_ID, KETERANGAN, UJIAN_TAHAP_ID,
			LAST_CREATE_DATE, LAST_CREATE_USER
		) 
		VALUES (
			(SELECT UJIAN_PEGAWAI_DAFTAR_ID FROM cat.ujian_pegawai_daftar WHERE UJIAN_ID = ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			(SELECT TIPE_UJIAN_ID FROM cat.kertih_soal WHERE KERTIH_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."),
			".$this->getField("UJIAN_PEGAWAI_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_BANK_SOAL_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("TANGGAL").",
			".$this->getField("URUT").",
			".$this->getField("BANK_SOAL_ID").",
			'".$this->getField("KETERANGAN")."',
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("LAST_CREATE_DATE").",
			'".$this->getField("LAST_CREATE_USER")."'
		)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function updateKeterangan($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." SET
					  KETERANGAN= '".$this->getField("KETERANGAN")."',
					  LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_PEGAWAI_ID= ".$this->getField("UJIAN_PEGAWAI_ID")."
				"; 
				$this->query = $str;
				// echo $str;exit();
		return $this->execQuery($str);
    }

    function selectByParamsKeterangan($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, 
					   URUT, LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER, 
					   BANK_SOAL_ID, KETERANGAN
				  FROM cat_pegawai.ujian_pegawai_keterangan_".$jadwaltesid." A
				  WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, 
					   URUT, LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER, 
					   BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID
				  FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
				  WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalFinishRevisi($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement="", $order="")
	{
		$str = "
		SELECT
			A.URUT NOMOR, A.UJIAN_ID, A.BANK_SOAL_ID, A.UJIAN_TAHAP_ID, CASE WHEN COALESCE(SUM(CASE WHEN BANK_SOAL_PILIHAN_ID IS NULL THEN 0 ELSE 1 END),0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."
		GROUP BY A.URUT, A.UJIAN_ID, A.BANK_SOAL_ID, A.UJIAN_TAHAP_ID
		".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsCheck($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.JADWAL_TES_ID, A.FORMULA_ASSESMENT_ID, A.FORMULA_ESELON_ID
			, A.UJIAN_PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_BANK_SOAL_ID, A.BANK_SOAL_ID
			, A.UJIAN_TAHAP_ID, A.TIPE_UJIAN_ID, A.PEGAWAI_ID, A.BANK_SOAL_PILIHAN_ID
			, A.TANGGAL, A.URUT
		FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getNoUrut($jadwaltesid, $statement= "")
	{
		$str = "SELECT MAX(URUT) AS ROWCOUNT FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." WHERE 1=1 ".$statement;
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function insertLatihan($jadwaltesid)
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid)); 

		$str = "
		INSERT INTO cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." (
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, TIPE_UJIAN_ID, 
			UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT,
			BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID,
			LAST_CREATE_DATE, LAST_CREATE_USER
		) 
		VALUES (
			(SELECT UJIAN_PEGAWAI_DAFTAR_ID FROM cat.ujian_pegawai_daftar WHERE UJIAN_ID = ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."),
			".$this->getField("JADWAL_TES_ID").",
			".$this->getField("FORMULA_ASSESMENT_ID").",
			".$this->getField("FORMULA_ESELON_ID").",
			(SELECT TIPE_UJIAN_ID FROM cat.bank_soal WHERE BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."),
			".$this->getField("UJIAN_PEGAWAI_ID").",
			".$this->getField("UJIAN_ID").",
			".$this->getField("UJIAN_BANK_SOAL_ID").",
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("TANGGAL").",
			".$this->getField("URUT").",
			".$this->getField("BANK_SOAL_ID").",
			".$this->getField("BANK_SOAL_PILIHAN_ID").",
			".$this->getField("UJIAN_TAHAP_ID").",
			".$this->getField("LAST_CREATE_DATE").",
			'".$this->getField("LAST_CREATE_USER")."'
		)"; 
				
		$this->query = $str;
		// echo $str;exit;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		return $this->execQuery($str);
    }

    function updateLatihan($jadwaltesid)
	{
		$str = "
		UPDATE cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." SET
		UJIAN_ID= ".$this->getField("UJIAN_ID").",
		UJIAN_BANK_SOAL_ID= ".$this->getField("UJIAN_BANK_SOAL_ID").",
		PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
		TANGGAL= ".$this->getField("TANGGAL").",
		URUT= ".$this->getField("URUT").",
		BANK_SOAL_ID= ".$this->getField("BANK_SOAL_ID").",
		BANK_SOAL_PILIHAN_ID= ".$this->getField("BANK_SOAL_PILIHAN_ID").",
		LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
		LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."'
		WHERE UJIAN_PEGAWAI_ID= ".$this->getField("UJIAN_PEGAWAI_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function deleteIdLatihan($jadwaltesid)
	{
        $str = "DELETE FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid."
                WHERE 
                  UJIAN_PEGAWAI_ID = '".$this->getField("UJIAN_PEGAWAI_ID")."'"; 
				  
		$this->query = $str;
		//echo $str;exit;
        return $this->execQuery($str);
    }

    function selectByParamsLatihan($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, 
					   URUT, LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER, 
					   BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID
				  FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." A
				  WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalFinishRevisiLatihan($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid, $statement="", $order="")
	{
		$str = "
		SELECT
			A.URUT NOMOR, A.UJIAN_ID, A.BANK_SOAL_ID, A.UJIAN_TAHAP_ID, CASE WHEN COALESCE(SUM(CASE WHEN BANK_SOAL_PILIHAN_ID IS NULL THEN 0 ELSE 1 END),0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."
		GROUP BY A.URUT, A.UJIAN_ID, A.BANK_SOAL_ID, A.UJIAN_TAHAP_ID
		".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsCheckLatihan($paramsArray=array(), $limit=-1, $from=-1, $jadwaltesid, $statement='', $sorder="")
	{
		$str = "
		SELECT
			A.UJIAN_PEGAWAI_DAFTAR_ID, A.JADWAL_TES_ID, A.FORMULA_ASSESMENT_ID, A.FORMULA_ESELON_ID
			, A.UJIAN_PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_BANK_SOAL_ID, A.BANK_SOAL_ID
			, A.UJIAN_TAHAP_ID, A.TIPE_UJIAN_ID, A.PEGAWAI_ID, A.BANK_SOAL_PILIHAN_ID
			, A.TANGGAL, A.URUT
		FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function getNoUrutLatihan($jadwaltesid, $statement= "")
	{
		$str = "SELECT MAX(URUT) AS ROWCOUNT FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." WHERE 1=1 ".$statement;
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsWptPilihan($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT A.*
		FROM cat.wpt_pilihan A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
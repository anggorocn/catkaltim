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

  class UjianPegawaiDaftar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianPegawaiDaftar()
	{
      $this->Entity(); 
    }
	
	function updateStatusLog()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_PEGAWAI_DAFTAR
				SET
					".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE UJIAN_ID= ".$this->getField("UJIAN_ID")." AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, JADWAL_TES_ID, FORMULA_ASSESMENT_ID, 
			FORMULA_ESELON_ID, UJIAN_ID, PEGAWAI_ID, STATUS_UJIAN, STATUS_TAHAP, 
			STATUS_LOGIN, STATUS_SETUJU, STATUS_SELESAI, TANGGAL, LAST_CREATE_USER, 
			LAST_CREATE_DATE, LAST_UPDATE_USER, LAST_UPDATE_DATE
		FROM cat.ujian_pegawai_daftar A
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

    function selectByParamsSoalPapi($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, '' TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.soal_papi C ON B.BANK_SOAL_ID = C.SOAL_PAPI_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID
		WHERE 1=1
		"; 

		/*$str = "SELECT
					A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN
					, '' KATEGORI, C.PERTANYAAN
					, UP.BANK_SOAL_PILIHAN_ID, A.PEGAWAI_ID, UP.UJIAN_PEGAWAI_ID
					, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, UP.UJIAN_PEGAWAI_ID
					, '' TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
					, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
					, B.UJIAN_TAHAP_ID, URUT
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian_bank_soal B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.soal_papi C ON B.BANK_SOAL_ID = C.SOAL_PAPI_ID
				INNER JOIN cat.ujian UJ ON UJ.UJIAN_ID = A.UJIAN_ID
				LEFT JOIN cat.ujian_pegawai UP ON UP.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UP.UJIAN_ID AND UP.UJIAN_BANK_SOAL_ID = B.UJIAN_BANK_SOAL_ID
				LEFT JOIN
				(
					SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
					FROM cat.ujian_pegawai
					WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
					GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
				) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.UJIAN_BANK_SOAL_ID
				WHERE 1=1
			"; */
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawabanSoalPapi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.PAPI_PILIHAN_ID BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL, 
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.soal_papi C ON B.BANK_SOAL_ID = C.SOAL_PAPI_ID
		INNER JOIN cat.papi_pilihan D ON B.BANK_SOAL_ID = D.SOAL_PAPI_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalRevisi($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, C.KEMAMPUAN, C.KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, C.TIPE_SOAL, C.PATH_GAMBAR, C.PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, TIPE_SOAL, 
			REPLACE(C.PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR1
			, C.PATH_GAMBAR
			, D.PATH_GAMBAR PATH_SOAL
			, C.PATH_SOAL PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN cat.bank_soal_pilihan D ON B.BANK_SOAL_ID = D.BANK_SOAL_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalRevisiLatihan($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, C.KEMAMPUAN, C.KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, C.TIPE_SOAL, C.PATH_GAMBAR, C.PATH_SOAL
			, B.UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN cat.ujian_bank_soal_latihan B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID AND A.UJIAN_ID = B.UJIAN_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.UJIAN_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
			FROM cat_pegawai.ujian_pegawai_latihan_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.UJIAN_BANK_SOAL_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalLatihan($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, TIPE_SOAL, 
			REPLACE(C.PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR1
			, C.PATH_GAMBAR
			, D.PATH_GAMBAR PATH_SOAL
			, C.PATH_SOAL PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN cat.ujian_bank_soal_latihan B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID AND A.UJIAN_ID = B.UJIAN_ID
		INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
		INNER JOIN cat.bank_soal_pilihan D ON B.BANK_SOAL_ID = D.BANK_SOAL_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM UJIAN_PEGAWAI_DAFTAR WHERE 1=1 "; 
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

    function selectByParamsSoalEpps($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, '' TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.soal_epps C ON B.BANK_SOAL_ID = C.SOAL_EPPS_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalEpps($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.EPPS_PILIHAN_ID BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL, 
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.soal_epps C ON B.BANK_SOAL_ID = C.SOAL_EPPS_ID
		INNER JOIN cat.epps_pilihan D ON B.BANK_SOAL_ID = D.SOAL_EPPS_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalMbti($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, 1 TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.mbti_soal C ON B.BANK_SOAL_ID = C.MBTI_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalMbti($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.MBTI_PILIHAN_ID BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, 1 TIPE_SOAL, 
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.mbti_soal C ON B.BANK_SOAL_ID = C.MBTI_SOAL_ID
		INNER JOIN cat.mbti_pilihan D ON B.BANK_SOAL_ID = D.MBTI_SOAL_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalDisk($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, C.TIPE_UJIAN_ID TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.disk_soal C ON B.BANK_SOAL_ID = C.DISK_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalDisk($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.DISK_PILIHAN_ID BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL,
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1

		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.disk_soal C ON B.BANK_SOAL_ID = C.DISK_SOAL_ID
		INNER JOIN cat.disk_pilihan D ON B.BANK_SOAL_ID = D.DISK_SOAL_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalBigFive($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, C.TIPE_UJIAN_ID TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.big_five_soal C ON B.BANK_SOAL_ID = C.BIG_FIVE_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalBigFive($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.BIG_FIVE_PILIHAN_ID BANK_SOAL_PILIHAN_ID
			, CASE D.JAWABAN WHEN '1' THEN 'Sangat Tidak Sesuai' WHEN '2' THEN 'Tidak Sesuai' WHEN '3' THEN 'Ragu-ragu' WHEN '4' THEN 'Sesuai' WHEN '5' THEN 'Sangat Sesuai' ELSE '' END JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL,
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.big_five_soal C ON B.BANK_SOAL_ID = C.BIG_FIVE_SOAL_ID
		INNER JOIN cat.big_five_pilihan D ON B.BANK_SOAL_ID = D.BIG_FIVE_SOAL_ID
		WHERE 1=1
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

    function selectByParamsSoalWpt($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, C.TIPE_UJIAN_ID TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
			, C.JENIS
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.wpt_soal C ON B.BANK_SOAL_ID = C.WPT_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalWpt($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.WPT_PILIHAN_ID BANK_SOAL_PILIHAN_ID
			, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL,
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.wpt_soal C ON B.BANK_SOAL_ID = C.WPT_SOAL_ID
		INNER JOIN cat.wpt_pilihan D ON B.BANK_SOAL_ID = D.WPT_SOAL_ID
		WHERE 1=1
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

    function selectByParamsSoalKertih($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, C.TIPE_UJIAN_ID TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
			, C.JENIS
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.kertih_soal C ON B.BANK_SOAL_ID = C.KERTIH_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalKertih($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' BANK_SOAL_PILIHAN_ID
			, '' JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL,
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.kertih_soal C ON B.BANK_SOAL_ID = C.KERTIH_SOAL_ID
		WHERE 1=1
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

    function selectByParamsSoalHolland($paramsArray=array(),$limit=-1,$from=-1, $jadwaltesid="", $statement="", $statementujian="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, '' KEMAMPUAN, '' KATEGORI, C.PERTANYAAN
			, A.PEGAWAI_ID, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID
			, '' TIPE_SOAL, '' PATH_GAMBAR, '' PATH_SOAL
			, B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID
			, URUT, UP.BANK_SOAL_PILIHAN_ID, UP.UJIAN_PEGAWAI_ID
			, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.holand_soal C ON B.BANK_SOAL_ID = C.HOLAND_SOAL_ID
		LEFT JOIN
		(
			SELECT
			UJIAN_ID, UJIAN_BANK_SOAL_ID, UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_PEGAWAI_ID, URUT, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid." A
			WHERE 1=1 ".$statementujian."
		) UP ON B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID = UP.UJIAN_BANK_SOAL_ID AND UP.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		LEFT JOIN
		(
			SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
			, TIPE_UJIAN_ID
			FROM cat_pegawai.ujian_pegawai_".$jadwaltesid."
			WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
			GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, TIPE_UJIAN_ID
		) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID AND UPX.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
		WHERE 1=1
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

    function selectByParamsJawabanSoalHolland($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, B.FORMULA_ASSESMENT_UJIAN_TAHAP_BANK_SOAL_ID UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.HOLAND_PILIHAN_ID BANK_SOAL_PILIHAN_ID, D.JAWABAN
			, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL, 
			'' PATH_GAMBAR1
			, '' PATH_GAMBAR
			, '' PATH_SOAL
			, '' PATH_SOAL1
		FROM cat.ujian_pegawai_daftar A
		INNER JOIN formula_assesment_ujian_tahap_bank_soal B ON A.FORMULA_ASSESMENT_ID = B.FORMULA_ASSESMENT_ID
		INNER JOIN cat.holand_soal C ON B.BANK_SOAL_ID = C.HOLAND_SOAL_ID
		INNER JOIN cat.holand_pilihan D ON B.BANK_SOAL_ID = D.HOLAND_SOAL_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

  } 
?>
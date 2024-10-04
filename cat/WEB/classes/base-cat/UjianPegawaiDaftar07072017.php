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
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_DAFTAR_ID", $this->getNextId("UJIAN_PEGAWAI_DAFTAR_ID","cat.UJIAN_PEGAWAI_DAFTAR")); 

		$str = "INSERT INTO cat.UJIAN_PEGAWAI_DAFTAR (
				   UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_ID, PEGAWAI_ID, STATUS_LOGIN,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("STATUS_LOGIN").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_DAFTAR_ID");
		return $this->execQuery($str);
    }
	
	function insertDetil($statement="")
	{
		$str = "
		INSERT INTO cat.UJIAN_PEGAWAI_DAFTAR (
		UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_ID, PEGAWAI_ID, STATUS_LOGIN,  
		LAST_CREATE_USER, LAST_CREATE_DATE)
		SELECT 
		(SELECT COALESCE(MAX(UJIAN_PEGAWAI_DAFTAR_ID),0) + A.NOMOR_URUT FROM cat.UJIAN_PEGAWAI_DAFTAR) UJIAN_PEGAWAI_DAFTAR_ID
		, ".$this->getField("UJIAN_ID")." UJIAN_ID
		, A.PEGAWAI_ID, 0, '".$this->getField("LAST_CREATE_USER")."' LAST_CREATE_USER, ".$this->getField("LAST_CREATE_DATE")." LAST_CREATE_DATE
		FROM
		(
		SELECT 
		row_number() over() NOMOR_URUT, A.PELAMAR_ID PEGAWAI_ID
		FROM pds_rekrutmen.PELAMAR A
		INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN PL ON A.PELAMAR_ID = PL.PELAMAR_ID AND PL.TANGGAL_KIRIM IS NOT NULL
		INNER JOIN pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN PLT ON A.PELAMAR_ID = PLT.PELAMAR_ID AND PLT.LOWONGAN_ID = PL.LOWONGAN_ID
		WHERE 1=1 ".$statement."
		ORDER BY A.PELAMAR_ID
		) A "; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertUser($statement="")
	{
		$str = "
		INSERT INTO cat.USER_LOGIN (
		USER_LOGIN_ID, USER_GROUP_ID, NAMA, JABATAN, USER_LOGIN, USER_PASS, PEGAWAI_ID
		, LAST_CREATE_USER, LAST_CREATE_DATE)
		SELECT
		(SELECT COALESCE(MAX(USER_LOGIN_ID),0) + A.NOMOR_URUT FROM cat.USER_LOGIN) USER_LOGIN_ID, 2 USER_GROUP_ID, A.NAMA
		, A.JABATAN_NAMA JABATAN, A.EMAIL USER_LOGIN, MD5(A.NIP_BARU) USER_PASS, A.PEGAWAI_ID
		, '".$this->getField("LAST_CREATE_USER")."' LAST_CREATE_USER, ".$this->getField("LAST_CREATE_DATE")." LAST_CREATE_DATE
		FROM
		(
		SELECT row_number() over() NOMOR_URUT, A.PEGAWAI_ID, B.EMAIL, B.NAMA, KTP_NO NIP_BARU, '' JABATAN_NAMA
		FROM cat.UJIAN_PEGAWAI_DAFTAR A
		INNER JOIN pds_rekrutmen.PELAMAR B ON A.PEGAWAI_ID=B.PELAMAR_ID
		WHERE 1=1 AND A.PEGAWAI_ID NOT IN(SELECT PEGAWAI_ID FROM cat.USER_LOGIN WHERE PEGAWAI_ID IS NOT NULL)
		".$statement."
		ORDER BY A.PEGAWAI_ID
		) A "; 
		//echo $str;exit;
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_PEGAWAI_DAFTAR SET
					UJIAN_ID					= ".$this->getField("UJIAN_ID").",
				  	PEGAWAI_ID					= ".$this->getField("PEGAWAI_ID").",
				  	STATUS_LOGIN					= ".$this->getField("STATUS_LOGIN").",
				  	LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  	LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_PEGAWAI_DAFTAR_ID 	= ".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID")."
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
	
	function deleteDetil($statement="")
	{
        $str = "DELETE FROM cat.UJIAN_PEGAWAI_DAFTAR
                WHERE ".$statement; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.UJIAN_PEGAWAI_DAFTAR
                WHERE 
                  UJIAN_PEGAWAI_DAFTAR_ID = ".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_PEGAWAI_DAFTAR_ID, UJIAN_ID, PEGAWAI_ID, STATUS_SETUJU, STATUS_LOGIN, STATUS_SETUJU, TANGGAL, STATUS_UJIAN, STATUS_SELESAI
				  FROM cat.UJIAN_PEGAWAI_DAFTAR A
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
    
	function selectByParamsUjian($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
					A.UJIAN_PEGAWAI_DAFTAR_ID, A.UJIAN_ID, A.PEGAWAI_ID
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian UJ ON UJ.UJIAN_ID = A.UJIAN_ID
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order="")
	{
		$str = "SELECT UJIAN_PEGAWAI_DAFTAR_ID, KTP_NO NIP_BARU, B.NAMA NAMA_PEGAWAI, A.UJIAN_ID, A.PEGAWAI_ID
				  , '' PANGKAT_KODE, '' NAMA_PANGKAT, '' NAMA_JABATAN, B.JENIS_KELAMIN
				  , CASE WHEN JENIS_KELAMIN = 'L' THEN 'Laki-laki' WHEN JENIS_KELAMIN = 'P' THEN 'Perempuan' ELSE '' END JENIS_KELAMIN_KET
				  , TEMPAT_LAHIR, TANGGAL_LAHIR, EMAIL, AG.NAMA AGAMA
				  FROM cat.UJIAN_PEGAWAI_DAFTAR A
				  LEFT JOIN pds_rekrutmen.PELAMAR B ON A.PEGAWAI_ID=B.PELAMAR_ID
				  LEFT JOIN pds_rekrutmen.AGAMA AG ON AG.AGAMA_ID = B.AGAMA_ID
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsMonitoringHasil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "SELECT UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PELAMAR_ID PEGAWAI_ID
				, A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU
				, CASE WHEN STATUS_LOGIN = 0 THEN 'Belum Login' WHEN STATUS_LOGIN = 1 THEN 'Sudah Login' END STATUS_LOGIN_INFO
				, CASE WHEN STATUS_SETUJU = 1 THEN 'Setuju' ELSE 'Belum' END STATUS_SETUJU_INFO
				, STATUS_LOGIN, STATUS_SETUJU, STATUS_UJIAN, STATUS_SELESAI, NILAI_HASIL, NILAI_LULUS
				, CASE WHEN NILAI_HASIL = 0 THEN 'Belum Test' WHEN NILAI_HASIL >= NILAI_LULUS THEN 'Lulus' ELSE 'Tidak Lulus' END KESIMPULAN
				, B.TANGGAL
				, CASE STATUS_LOGIN WHEN 1 THEN 'V' ELSE '-' END STATUS_LOGIN_INFO
				, CASE STATUS_SETUJU WHEN 1 THEN 'V' ELSE '-' END STATUS_SETUJU_INFO
				, CASE STATUS_UJIAN WHEN 1 THEN 'V' ELSE '-' END STATUS_UJIAN_INFO
				, CASE STATUS_SELESAI WHEN 1 THEN 'V' ELSE '-' END STATUS_SELESAI_INFO
				FROM cat.UJIAN_PEGAWAI_DAFTAR B
				LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
				LEFT JOIN cat.UJIAN_PEGAWAI_HASIL C ON A.PELAMAR_ID = C.PEGAWAI_ID AND B.UJIAN_ID = C.UJIAN_ID
				LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
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
    
	function selectByParamsMonitoringCfitHasil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PELAMAR_ID PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU
			, CAST(COALESCE(HSL.JUMLAH_BENAR,0) AS NUMERIC) JUMLAH_BENAR, HSL.UJIAN_TAHAP_ID
			, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) NILAI_HASIL
			, CASE
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 130 THEN 'Sangat Superior'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 130 THEN 'Superior'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 120 THEN 'Diatas Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 110 THEN 'Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 90 THEN 'Dibawah Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 80 THEN 'Borderline'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) <= 69 THEN 'Intellectual Deficient'
			END KESIMPULAN
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.ID, ROUND(CAST(COALESCE(A.JUMLAH_BENAR,0) AS NUMERIC) / CAST(COALESCE(B.JUMLAH_BENAR,0) AS NUMERIC) * 100) JUMLAH_BENAR
			, C.UJIAN_TAHAP_ID
			FROM
			(
				SELECT A.PEGAWAI_ID, A.UJIAN_ID, SUBSTR(TU.ID,1,2) ID, COUNT(A.PEGAWAI_ID) AS JUMLAH_BENAR
				FROM cat.UJIAN_PEGAWAI A
				INNER JOIN cat.BANK_SOAL B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID
				INNER JOIN cat.TIPE_UJIAN TU ON TU.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				INNER JOIN 
				(
					SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN
					FROM cat.BANK_SOAL_PILIHAN
					WHERE GRADE_PROSENTASE > 0
				) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, SUBSTR(TU.ID,1,2)
			) A
			INNER JOIN
			(
				SELECT A.UJIAN_ID, SUBSTR(TU.ID,1,2) ID, COUNT(A.PEGAWAI_ID) AS JUMLAH_BENAR
				FROM cat.UJIAN_PEGAWAI A
				INNER JOIN cat.BANK_SOAL B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID
				INNER JOIN cat.TIPE_UJIAN TU ON TU.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				INNER JOIN 
				(
					SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN
					FROM cat.BANK_SOAL_PILIHAN
					WHERE GRADE_PROSENTASE > 0
				) C ON A.BANK_SOAL_ID = C.BANK_SOAL_ID
				GROUP BY A.UJIAN_ID, SUBSTR(TU.ID,1,2)
			) B ON B.ID = A.ID AND A.UJIAN_ID = B.UJIAN_ID
			INNER JOIN
			(
				SELECT A.UJIAN_TAHAP_ID, B.ID, A.UJIAN_ID
				FROM cat.UJIAN_TAHAP A
				INNER JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				WHERE 1=1
				AND PARENT_ID = '0'
			 ) C ON A.UJIAN_ID = B.UJIAN_ID AND A.ID = C.ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringHasilCetak($limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT *
		FROM
		(
			SELECT 
				A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU, NRP,
				NILAI_HASIL, 
				CASE
				WHEN CAST(NILAI_HASIL AS NUMERIC) >= 130 THEN 'Sangat Superior'
				WHEN CAST(NILAI_HASIL AS NUMERIC) >= 120 AND CAST(NILAI_HASIL AS NUMERIC) < 130 THEN 'Superior'
				WHEN CAST(NILAI_HASIL AS NUMERIC) >= 110 AND CAST(NILAI_HASIL AS NUMERIC) < 120 THEN 'Diatas Rata - Rata'
				WHEN CAST(NILAI_HASIL AS NUMERIC) >= 90 AND CAST(NILAI_HASIL AS NUMERIC) < 110 THEN 'Rata - Rata'
				WHEN CAST(NILAI_HASIL AS NUMERIC) >= 80 AND CAST(NILAI_HASIL AS NUMERIC) < 90 THEN 'Dibawah Rata - Rata'
				WHEN CAST(NILAI_HASIL AS NUMERIC) >= 70 AND CAST(NILAI_HASIL AS NUMERIC) < 80 THEN 'Borderline'
				WHEN CAST(NILAI_HASIL AS NUMERIC) <= 69 THEN 'Intellectual Deficient'
				END KESIMPULAN, TIPE, D.TIPE_UJIAN_ID
			FROM cat.UJIAN_PEGAWAI_HASIL_CFIT B
			LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
			LEFT JOIN cat.UJIAN_TAHAP C ON B.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
			LEFT JOIN cat.TIPE_UJIAN D ON C.TIPE_UJIAN_ID = D.TIPE_UJIAN_ID 
			WHERE D.TIPE_UJIAN_ID IN (1,2) AND NOT D.TIPE_UJIAN_ID = 7 AND PARENT_ID = '0' ".$statement."
				UNION ALL
			SELECT A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU, NRP,
				CAST(NILAI_HASIL AS TEXT) NILAI_HASIL, '' KESIMPULAN, TIPE, D.TIPE_UJIAN_ID
			FROM cat.UJIAN_PEGAWAI_HASIL_TIPE B
			LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
			LEFT JOIN cat.UJIAN_TAHAP C ON B.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
			LEFT JOIN cat.TIPE_UJIAN D ON C.TIPE_UJIAN_ID = D.TIPE_UJIAN_ID 
			WHERE D.TIPE_UJIAN_ID NOT IN (1,2,7) AND PARENT_ID = '0' ".$statement."
		) A
		ORDER BY NIP_BARU, TIPE_UJIAN_ID
		";
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringCfitHasilBak1($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PELAMAR_ID PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU
			, round(COALESCE(HSL.JUMLAH_BENAR,0),2) JUMLAH_BENAR, HSL.UJIAN_TAHAP_ID
			, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) NILAI_HASIL
			, CASE
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 130 THEN 'Sangat Superior'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 130 THEN 'Superior'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 120 THEN 'Diatas Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 110 THEN 'Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 90 THEN 'Dibawah Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 80 THEN 'Borderline'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) <= 69 THEN 'Intellectual Deficient'
			END KESIMPULAN
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.UJIAN_ID, B.UJIAN_TAHAP_ID, SUM(A.JUMLAH_BENAR) / COUNT(A.PEGAWAI_ID) JUMLAH_BENAR
			FROM
			(
				SELECT A_1.PEGAWAI_ID, A_1.UJIAN_ID, A_1.UJIAN_TAHAP_ID, COUNT(A_1.PEGAWAI_ID) AS JUMLAH_BENAR
				FROM cat.UJIAN_PEGAWAI A_1
				INNER JOIN 
				( 
					SELECT BANK_SOAL_PILIHAN.BANK_SOAL_ID, BANK_SOAL_PILIHAN.BANK_SOAL_PILIHAN_ID
					FROM cat.BANK_SOAL_PILIHAN
					WHERE BANK_SOAL_PILIHAN.GRADE_PROSENTASE > 0
				) B_1 ON A_1.BANK_SOAL_ID = B_1.BANK_SOAL_ID AND A_1.BANK_SOAL_PILIHAN_ID = B_1.BANK_SOAL_PILIHAN_ID
				GROUP BY A_1.PEGAWAI_ID, A_1.UJIAN_ID, A_1.UJIAN_TAHAP_ID
			) A
			INNER JOIN
			(
				SELECT COALESCE(C.UJIAN_TAHAP_ROW_ID, A.UJIAN_TAHAP_ID) UJIAN_TAHAP_ROW_ID, A.UJIAN_TAHAP_ID, A.UJIAN_ID
				FROM cat.UJIAN_TAHAP A
				INNER JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				LEFT JOIN
				(
					SELECT
					A.PARENT_ID ID_ROW, B.UJIAN_TAHAP_ID UJIAN_TAHAP_ROW_ID
					FROM cat.TIPE_UJIAN A
					INNER JOIN cat.UJIAN_TAHAP B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
					WHERE 1=1 AND PARENT_ID != '0'
				) C ON B.ID = C.ID_ROW
				WHERE 1=1 AND PARENT_ID = '0'
			) B ON B.UJIAN_TAHAP_ROW_ID = A.UJIAN_TAHAP_ID AND A.UJIAN_ID = B.UJIAN_ID
			WHERE 1=1
			GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, B.UJIAN_TAHAP_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringCfitHasilBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PELAMAR_ID PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU
			, COALESCE(HSL.JUMLAH_BENAR,0) JUMLAH_BENAR, HSL.UJIAN_TAHAP_ID
			, cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) NILAI_HASIL
			, CASE
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 130 THEN 'Sangat Superior'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 120 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 130 THEN 'Superior'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 110 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 120 THEN 'Diatas Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 90 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 110 THEN 'Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 80 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 90 THEN 'Dibawah Rata - Rata'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) >= 70 AND CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) < 80 THEN 'Borderline'
			WHEN CAST(cat.AMBIL_IQ_NILAI(COALESCE(HSL.JUMLAH_BENAR,0)) AS NUMERIC) <= 69 THEN 'Intellectual Deficient'
			END KESIMPULAN
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT A_1.PEGAWAI_ID, A_1.UJIAN_ID, A_1.UJIAN_TAHAP_ID, COUNT(A_1.PEGAWAI_ID) AS JUMLAH_BENAR
			FROM cat.UJIAN_PEGAWAI A_1
			INNER JOIN 
			( 
				SELECT BANK_SOAL_PILIHAN.BANK_SOAL_ID, BANK_SOAL_PILIHAN.BANK_SOAL_PILIHAN_ID
				FROM cat.BANK_SOAL_PILIHAN
				WHERE BANK_SOAL_PILIHAN.GRADE_PROSENTASE > 0
			) B_1 ON A_1.BANK_SOAL_ID = B_1.BANK_SOAL_ID AND A_1.BANK_SOAL_PILIHAN_ID = B_1.BANK_SOAL_PILIHAN_ID
			GROUP BY A_1.PEGAWAI_ID, A_1.UJIAN_ID, A_1.UJIAN_TAHAP_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringPapiHasil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT
			UJIAN_PEGAWAI_DAFTAR_ID, B.UJIAN_ID, A.PELAMAR_ID PEGAWAI_ID
			, A.NAMA NAMA_PEGAWAI, A.KTP_NO NIP_BARU
			, COALESCE(HSL.NILAI_W,0) NILAI_W
			, CASE 
			WHEN COALESCE(HSL.NILAI_W,0) < 4 THEN 'Hanya butuh gambaran ttg kerangka tugas scr garis besar, berpatokan pd tujuan, dpt bekerja dlm suasana yg kurang berstruktur, berinsiatif, mandiri. Tdk patuh, cenderung mengabaikan/tdk paham pentingnya peraturan/prosedur, suka membuat peraturan sendiri yg bisa bertentangan dg yg telah ada.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 4 AND COALESCE(HSL.NILAI_W,0) < 6 THEN 'Perlu pengarahan awal dan tolok ukur keberhasilan.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 6 AND COALESCE(HSL.NILAI_W,0) < 8 THEN 'Membutuhkan uraian rinci mengenai tugas, dan batasan tanggung  jawab serta wewenang.'
			WHEN COALESCE(HSL.NILAI_W,0) >= 8 AND COALESCE(HSL.NILAI_W,0) < 10 THEN 'Patuh pada kebijaksanaan, peraturan dan struktur organisasi. Ingin segala sesuatunya diuraikan secara rinci, kurang memiliki inisiatif, tdk fleksibel, terlalu tergantung pada organisasi, berharap disuapi.'
			END INFO_W
			, COALESCE(HSL.NILAI_F,0) NILAI_F
			, CASE 
			WHEN COALESCE(HSL.NILAI_F,0) < 4 THEN 'Otonom, dapat bekerja sendiri tanpa campur tangan orang lain, motivasi timbul krn pekerjaan itu sendiri - bukan krn pujian dr otoritas. Mempertanyakan otoritas, cenderung tidak puas thdp atasan, loya- litas lebih didasari kepentingan pribadi.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 4 AND COALESCE(HSL.NILAI_F,0) < 7 THEN 'Loyal pada Perusahaan.'
			WHEN COALESCE(HSL.NILAI_F,0) = 7 THEN 'Loyal pada pribadi atasan.'
			WHEN COALESCE(HSL.NILAI_F,0) >= 8 AND COALESCE(HSL.NILAI_F,0) < 10 THEN 'Loyal, berusaha dekat dg pribadi atasan, ingin menyenangkan atasan, sadar akan harapan atasan akan dirinya.  Terlalu memper- hatikan cara menyenangkan atasan, tidak berani berpendirian lain, tidak mandiri.'
			END INFO_F
			, COALESCE(HSL.NILAI_K,0) NILAI_K
			, CASE 
			WHEN COALESCE(HSL.NILAI_K,0) < 2 THEN 'Sabar, tidak menyukai konflik. Mengelak atau menghindar dari konflik, pasif, menekan atau menyembunyikan perasaan sesungguhnya,  menghindari konfrontasi, lari dari konflik, tidak mau mengakui adanya konflik.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 2 AND COALESCE(HSL.NILAI_K,0) < 4 THEN 'Lebih suka menghindari konflik, akan mencari rasionalisasi untuk  dapat menerima situasi dan melihat permasalahan dari sudut pandang orang lain.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 4 AND COALESCE(HSL.NILAI_K,0) < 6 THEN 'Tidak mencari atau menghindari konflik, mau mendengarkan pandangan orang lain tetapi dapat menjadi keras kepala saat mempertahankan pandangannya.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 6 AND COALESCE(HSL.NILAI_K,0) < 8 THEN 'Akan menghadapi konflik, mengungkapkan serta memaksakan pandangan dengan cara positif.'
			WHEN COALESCE(HSL.NILAI_K,0) >= 8 AND COALESCE(HSL.NILAI_K,0) < 10 THEN 'Terbuka, jujur, terus terang, asertif, agresif, reaktif, mudah tersinggung, mudah meledak, curiga, berprasangka, suka berkelahi atau berkonfrontasi, berpikir negatif.'
			END INFO_K
			, COALESCE(HSL.NILAI_Z,0) NILAI_Z
			, CASE 
			WHEN COALESCE(HSL.NILAI_Z,0) < 2 THEN 'Mudah beradaptasi dg pekerjaan rutin tanpa merasa bosan, tidak membutuhkan variasi, menyukai lingkungan stabil dan tidak berubah. Konservatif, menolak perubahan, sulit menerima hal-hal baru, tidak dapat beradaptasi dengan situasi yg  berbeda-beda.'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 2 AND COALESCE(HSL.NILAI_Z,0) < 4 THEN 'Enggan berubah, tidak siap untuk beradaptasi, hanya mau menerima perubahan jika alasannya jelas dan meyakinkan.'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 4 AND COALESCE(HSL.NILAI_Z,0) < 6 THEN 'Mudah beradaptasi, cukup menyukai perubahan.'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 6 AND COALESCE(HSL.NILAI_Z,0) < 8 THEN 'Antusias terhadap perubahan dan akan mencari hal-hal baru, tetapi masih selektif ( menilai kemanfaatannya ).'
			WHEN COALESCE(HSL.NILAI_Z,0) >= 8 AND COALESCE(HSL.NILAI_Z,0) < 10 THEN 'Sangat menyukai perubahan, gagasan baru/variasi, aktif mencari perubahan, antusias dg hal-hal baru, fleksibel dlm berpikir, mudah beradaptasi pd situasi yg berbeda-beda. Gelisah, frustasi, mudah bosan, sangat membutuhkan variasi, tidak menyukai tugas/situasi yg rutin-monoton.'
			END INFO_Z
			, COALESCE(HSL.NILAI_O,0) NILAI_O
			, CASE 
			WHEN COALESCE(HSL.NILAI_O,0) < 3 THEN 'Menjaga jarak, lebih memperhatikan hal - hal kedinasan, tdk mudah dipengaruhi oleh individu tertentu, objektif & analitis. Tampil dingin, tdk acuh, tdk ramah, suka berahasia, mungkin tdk sadar akan pe- rasaan org lain, & mungkin sulit menyesuaikan diri.'
			WHEN COALESCE(HSL.NILAI_O,0) >= 3 AND COALESCE(HSL.NILAI_O,0) < 6 THEN 'Tidak mencari atau menghindari hubungan antar pribadi di  lingkungan kerja, masih mampu menjaga jarak.'
			WHEN COALESCE(HSL.NILAI_O,0) >= 6 AND COALESCE(HSL.NILAI_O,0) < 10 THEN 'Peka akan kebutuhan org lain, sangat memikirkan hal - hal yg dibutuhkan org lain, suka menjalin hub persahabatan yg hangat & tulus. Sangat pe- rasa, mudah tersinggung, cenderung subjektif, dpt terlibat terlalu dlm/intim dg individu tertentu dlm pekerjaan, sangat tergantung pd individu tertentu.'
			END INFO_O
			, COALESCE(HSL.NILAI_B,0) NILAI_B
			, CASE 
			WHEN COALESCE(HSL.NILAI_B,0) < 3 THEN 'Mandiri ( dari segi emosi ) , tdk mudah dipengaruhi oleh tekanan kelompok. Penyendiri, kurang peka akan sikap & kebutuhan kelom- pok, mungkin sulit menyesuaikan diri.'
			WHEN COALESCE(HSL.NILAI_B,0) >= 3 AND COALESCE(HSL.NILAI_B,0) < 6 THEN 'Selektif dlm bergabung dg kelompok, hanya mau berhubungan dg kelompok di lingkungan kerja apabila bernilai & sesuai minat, tdk terlalu mudah dipengaruhi.'
			WHEN COALESCE(HSL.NILAI_B,0) >= 6 AND COALESCE(HSL.NILAI_B,0) < 10 THEN 'Suka bergabung dlm kelompok, sadar akan sikap & kebutuhan ke- lompok, suka bekerja sama, ingin menjadi bagian dari kelompok, ingin disukai & diakui oleh lingkungan; sangat tergantung pd kelom- pok, lebih memperhatikan kebutuhan kelompok daripada pekerjaan.'
			END INFO_B
			, COALESCE(HSL.NILAI_X,0) NILAI_X
			, CASE 
			WHEN COALESCE(HSL.NILAI_X,0) < 2 THEN 'Sederhana, rendah hati, tulus, tidak sombong dan tidak suka menam- pilkan diri. Terlalu sederhana, cenderung merendahkan kapasitas diri, tidak percaya diri, cenderung menarik diri dan pemalu.'
			WHEN COALESCE(HSL.NILAI_X,0) >= 2 AND COALESCE(HSL.NILAI_X,0) < 4 THEN 'Sederhana, cenderung diam, cenderung pemalu, tidak suka menon- jolkan diri.'
			WHEN COALESCE(HSL.NILAI_X,0) >= 4 AND COALESCE(HSL.NILAI_X,0) < 6 THEN 'Mengharapkan pengakuan lingkungan dan tidak mau diabaikan tetapi tidak mencari-cari perhatian.'
			WHEN COALESCE(HSL.NILAI_X,0) >= 6 AND COALESCE(HSL.NILAI_X,0) < 10 THEN 'Bangga akan diri dan gayanya sendiri, senang menjadi pusat perha- tian, mengharapkan penghargaan dari lingkungan. Mencari-cari perhatian dan suka menyombongkan diri.'
			END INFO_X
			, COALESCE(HSL.NILAI_P,0) NILAI_P
			, CASE 
			WHEN COALESCE(HSL.NILAI_P,0) < 2 THEN 'Permisif, akan memberikan kesempatan pada orang lain untuk memimpin. Tidak mau mengontrol orang lain dan tidak mau mempertanggung jawabkan hasil kerja bawahannya.'
			WHEN COALESCE(HSL.NILAI_P,0) >= 2 AND COALESCE(HSL.NILAI_P,0) < 4 THEN 'Enggan mengontrol org lain & tidak mau mempertanggung jawabkan hasil kerja bawahannya, lebih memberi kebebasan kpd bawahan utk memilih cara sendiri dlm penyelesaian tugas dan meminta bawahan  utk mempertanggungjawabkan hasilnya masing-masing.'
			WHEN COALESCE(HSL.NILAI_P,0) = 4 THEN 'Cenderung enggan melakukan fungsi pengarahan, pengendalian dan pengawasan, kurang aktif memanfaatkan kapasitas bawahan secara optimal, cenderung bekerja sendiri dalam mencapai tujuan kelompok.'
			WHEN COALESCE(HSL.NILAI_P,0) = 5 THEN 'Bertanggung jawab, akan melakukan fungsi pengarahan, pengendalian dan pengawasan, tapi tidak mendominasi.'
			WHEN COALESCE(HSL.NILAI_P,0) > 5 AND COALESCE(HSL.NILAI_P,0) < 8 THEN 'Dominan dan bertanggung jawab, akan melakukan fungsi pengarahan, pengendalian dan pengawasan.'
			WHEN COALESCE(HSL.NILAI_P,0) >= 8 AND COALESCE(HSL.NILAI_P,0) < 10 THEN 'Sangat dominan, sangat mempengaruhi & mengawasi org lain, bertanggung jawab atas tindakan & hasil kerja bawahan. Posesif, tdk ingin berada di  bawah pimpinan org lain, cemas bila tdk berada di posisi pemimpin,  mungkin sulit utk bekerja sama dgn rekan yg sejajar kedudukannya.'
			END INFO_P
			, COALESCE(HSL.NILAI_A,0) NILAI_A
			, CASE 
			WHEN COALESCE(HSL.NILAI_A,0) < 5 THEN 'Tidak kompetitif, mapan, puas. Tidak terdorong untuk menghasilkan prestasi, tdk berusaha utk mencapai sukses, membutuhkan dorongan dari luar diri, tidak berinisiatif, tidak memanfaatkan kemampuan diri secara optimal, ragu akan tujuan diri, misalnya sbg akibat promosi / perubahan struktur jabatan.'
			WHEN COALESCE(HSL.NILAI_A,0) >= 5 AND COALESCE(HSL.NILAI_A,0) < 8 THEN 'Tahu akan tujuan yang ingin dicapainya dan dapat merumuskannya, realistis akan kemampuan diri, dan berusaha untuk mencapai target.'
			WHEN COALESCE(HSL.NILAI_A,0) >= 8 AND COALESCE(HSL.NILAI_A,0) < 10 THEN 'Sangat berambisi utk berprestasi dan menjadi yg terbaik, menyukai tantangan, cenderung mengejar kesempurnaan, menetapkan target yg tinggi, self-starter merumuskan kerja dg baik. Tdk realistis akan kemampuannya, sulit dipuaskan, mudah kecewa, harapan yg tinggi mungkin mengganggu org lain.'
			END INFO_A
			, COALESCE(HSL.NILAI_N,0) NILAI_N
			, CASE 
			WHEN COALESCE(HSL.NILAI_N,0) < 3 THEN 'Tidak terlalu merasa perlu untuk menuntaskan sendiri tugas-tugasnya, senang	menangani beberapa pekerjaan sekaligus, mudah mendelegasikan tugas.	Komitmen rendah, cenderung meninggalkan tugas sebelum tuntas, konsentrasi mudah buyar, mungkin suka berpindah pekerjaan.'
			WHEN COALESCE(HSL.NILAI_N,0) >= 3 AND COALESCE(HSL.NILAI_N,0) < 6 THEN 'Cukup memiliki komitmen untuk menuntaskan tugas, akan tetapi jika memungkinkan akan mendelegasikan sebagian dari pekerjaannya kepada orang lain.'
			WHEN COALESCE(HSL.NILAI_N,0) >= 6 AND COALESCE(HSL.NILAI_N,0) < 8 THEN 'Komitmen tinggi, lebih suka menangani pekerjaan satu demi satu, akan tetapi masih dapat mengubah prioritas jika terpaksa.'
			WHEN COALESCE(HSL.NILAI_N,0) >= 8 AND COALESCE(HSL.NILAI_N,0) < 10 THEN 'Memiliki komitmen yg sangat tinggi thd tugas, sangat ingin menyelesaikan tugas, tekun dan tuntas dlm menangani pekerjaan satu demi satu hingga tuntas. Perhatian terpaku pada satu tugas, sulit utk menangani beberapa	pekerjaan sekaligus, sulit di interupsi, tidak melihat masalah sampingan.'
			END INFO_N
			, COALESCE(HSL.NILAI_G,0) NILAI_G
			, CASE 
			WHEN COALESCE(HSL.NILAI_G,0) < 3 THEN 'Santai, kerja adalah sesuatu yang menyenangkan-bukan beban yg membutuhkan usaha besar. Mungkin termotivasi utk mencari cara atau sistem yg dpt mempermudah dirinya dlm menyelesaikan pekerjaan, akan berusaha menghindari kerja keras, sehingga dapat memberi kesan malas.'
			WHEN COALESCE(HSL.NILAI_G,0) >= 3 AND COALESCE(HSL.NILAI_G,0) < 5 THEN 'Bekerja keras sesuai tuntutan, menyalurkan usahanya untuk hal-hal yang bermanfaat / menguntungkan.'
			WHEN COALESCE(HSL.NILAI_G,0) >= 5 AND COALESCE(HSL.NILAI_G,0) < 8 THEN 'Bekerja keras, tetapi jelas tujuan yg ingin dicapainya.'
			WHEN COALESCE(HSL.NILAI_G,0) >= 8 AND COALESCE(HSL.NILAI_G,0) < 10 THEN 'Ingin tampil sbg pekerja keras, sangat suka bila orang lain memandangnya sbg pekerja keras. Cenderung menciptakan pekerjaan	yang tidak perlu agar terlihat tetap sibuk, kadang kala tanpa tujuan yang jelas.'
			END INFO_G
			, COALESCE(HSL.NILAI_L,0) NILAI_L
			, CASE 
			WHEN COALESCE(HSL.NILAI_L,0) < 2 THEN 'Puas dengan peran sebagai bawahan, memberikan kesempatan  pada orang lain untuk memimpin, tidak dominan. Tidak percaya diri; sama sekali tidak berminat untuk berperan sebagai pemimpin; ber- sikap pasif dalam kelompok.'
			WHEN COALESCE(HSL.NILAI_L,0) >= 2 AND COALESCE(HSL.NILAI_L,0) < 4 THEN 'Tidak percaya diri dan tidak ingin memimpin atau mengawasi orang lain.'
			WHEN COALESCE(HSL.NILAI_L,0) = 4 THEN 'Kurang percaya diri dan kurang berminat utk menjadi pemimpin'
			WHEN COALESCE(HSL.NILAI_L,0) = 5 THEN 'Cukup percaya diri, tidak secara aktif mencari posisi kepemimpinan akan tetapi juga tidak akan menghindarinya.'
			WHEN COALESCE(HSL.NILAI_L,0) > 5 AND COALESCE(HSL.NILAI_L,0) < 8 THEN 'Percaya diri dan ingin berperan sebagai pemimpin.'
			WHEN COALESCE(HSL.NILAI_L,0) >= 8 AND COALESCE(HSL.NILAI_L,0) < 10 THEN 'Sangat percaya diri utk berperan sbg atasan & sangat mengharapkan posisi tersebut. Lebih mementingkan citra & status kepemimpinannya dari pada efektifitas kelompok, mungkin akan tampil angkuh atau terlalu percaya diri.'
			END INFO_L
			, COALESCE(HSL.NILAI_I,0) NILAI_I
			, CASE 
			WHEN COALESCE(HSL.NILAI_I,0) < 2 THEN 'Sangat berhati - hati, memikirkan langkah- langkahnya secara ber- sungguh - sungguh. Lamban dlm mengambil keputusan, terlalu lama merenung, cenderung menghindar mengambil keputusan.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 2 AND COALESCE(HSL.NILAI_I,0) < 4 THEN 'Enggan mengambil keputusan.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 4 AND COALESCE(HSL.NILAI_I,0) < 6 THEN 'Berhati - hati dlm pengambilan keputusan.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 6 AND COALESCE(HSL.NILAI_I,0) < 8 THEN 'Cukup percaya diri dlm pengambilan keputusan, mau mengambil resiko, dpt memutuskan dgn cepat, mengikuti alur logika.'
			WHEN COALESCE(HSL.NILAI_I,0) >= 8 AND COALESCE(HSL.NILAI_I,0) < 10 THEN 'Sangat yakin dl mengambil keputusan, cepat tanggap thd situasi, berani mengambil resiko, mau memanfaatkan kesempatan. Impulsif, dpt mem- buat keputusan yg tdk praktis, cenderung lebih mementingkan kecepatan daripada akurasi, tdk sabar, cenderung meloncat pd keputusan.'
			END INFO_I
			, COALESCE(HSL.NILAI_T,0) NILAI_T
			, CASE 
			WHEN COALESCE(HSL.NILAI_T,0) < 4 THEN 'Santai. Kurang peduli akan waktu, kurang memiliki rasa urgensi,membuang-buang waktu, bukan pekerja yang tepat waktu.'
			WHEN COALESCE(HSL.NILAI_T,0) >= 4 AND COALESCE(HSL.NILAI_T,0) < 7 THEN 'Cukup aktif dalam segi mental, dapat menyesuaikan tempo kerjanya dengan tuntutan pekerjaan / lingkungan.'
			WHEN COALESCE(HSL.NILAI_T,0) >= 7 AND COALESCE(HSL.NILAI_T,0) < 10 THEN 'Cekatan, selalu siaga, bekerja cepat, ingin segera menyelesaikantugas.  Negatifnya : Tegang, cemas, impulsif, mungkin ceroboh,banyak gerakan yang tidak perlu.'
			END INFO_T
			, COALESCE(HSL.NILAI_V,0) NILAI_V
			, CASE 
			WHEN COALESCE(HSL.NILAI_V,0) < 3 THEN 'Cocok untuk pekerjaan  di belakang meja. Cenderung lamban,tidak tanggap, mudah lelah, daya tahan lemah.'
			WHEN COALESCE(HSL.NILAI_V,0) >= 3 AND COALESCE(HSL.NILAI_V,0) < 7 THEN 'Dapat bekerja di belakang meja dan senang jika sesekali harusterjun ke lapangan atau melaksanakan tugas-tugas yang bersifat mobile.'
			WHEN COALESCE(HSL.NILAI_V,0) >= 7 AND COALESCE(HSL.NILAI_V,0) < 10 THEN 'Menyukai aktifitas fisik ( a.l. : olah raga), enerjik, memiliki staminauntuk menangani tugas-tugas berat, tidak mudah lelah. Tidak betahduduk lama, kurang dapat konsentrasi di belakang meja.'
			END INFO_V
			, COALESCE(HSL.NILAI_S,0) NILAI_S
			, CASE 
			WHEN COALESCE(HSL.NILAI_S,0) < 3 THEN 'Dpt. bekerja sendiri, tdk membutuhkan kehadiran org lain. Menarik diri, kaku dlm bergaul, canggung dlm situasi sosial, lebih memperha- tikan hal - hal lain daripada manusia.'
			WHEN COALESCE(HSL.NILAI_S,0) >= 3 AND COALESCE(HSL.NILAI_S,0) < 5 THEN 'Kurang percaya diri & kurang aktif dlm menjalin hubungan sosial.'
			WHEN COALESCE(HSL.NILAI_S,0) >= 5 AND COALESCE(HSL.NILAI_S,0) < 10 THEN 'Percaya diri & sangat senang bergaul, menyukai interaksi sosial, bisa men- ciptakan suasana yg menyenangkan, mempunyai inisiatif & mampu men- jalin hubungan & komunikasi, memperhatikan org lain. Mungkin membuang- buang waktu utk aktifitas sosial, kurang peduli akan penyelesaian tugas.'
			END INFO_S
			, COALESCE(HSL.NILAI_R,0) NILAI_R
			, CASE 
			WHEN COALESCE(HSL.NILAI_R,0) < 4 THEN 'Tipe pelaksana, praktis - pragmatis, mengandalkan pengalaman masa lalu dan intuisi. Bekerja tanpa perencanaan, mengandalkanperasaan.'
			WHEN COALESCE(HSL.NILAI_R,0) >= 4 AND COALESCE(HSL.NILAI_R,0) < 6 THEN 'Pertimbangan mencakup aspek teoritis ( konsep atau pemikiran baru ) dan aspek praktis ( pengalaman ) secara berimbang.'
			WHEN COALESCE(HSL.NILAI_R,0) >= 6 AND COALESCE(HSL.NILAI_R,0) < 8 THEN 'Suka memikirkan suatu problem secara mendalam, merujuk pada teori dan konsep.'
			WHEN COALESCE(HSL.NILAI_R,0) >= 8 AND COALESCE(HSL.NILAI_R,0) < 10 THEN 'Tipe pemikir, sangat berminat pada gagasan, konsep, teori,menca-ri alternatif baru, menyukai perencanaan. Mungkin sulit dimengerti oleh orang lain, terlalu teoritis dan tidak praktis, mengawang-awangdan berbelit-belit.'
			END INFO_R
			, COALESCE(HSL.NILAI_D,0) NILAI_D
			, CASE 
			WHEN COALESCE(HSL.NILAI_D,0) < 2 THEN 'Melihat pekerjaan scr makro, membedakan hal penting dari yg kurang penting,	mendelegasikan detil pd org lain, generalis. Menghindari detail, konsekuensinya mungkin bertindak tanpa data yg cukup/akurat, bertindak ceroboh pd hal yg butuh kecermatan. Dpt mengabaikan proses yg vital dlm evaluasi data.'
			WHEN COALESCE(HSL.NILAI_D,0) >= 2 AND COALESCE(HSL.NILAI_D,0) < 4 THEN 'Cukup peduli akan akurasi dan kelengkapan data.'
			WHEN COALESCE(HSL.NILAI_D,0) >= 4 AND COALESCE(HSL.NILAI_D,0) < 7 THEN 'Tertarik untuk menangani sendiri detail.'
			WHEN COALESCE(HSL.NILAI_D,0) >= 7 AND COALESCE(HSL.NILAI_D,0) < 10 THEN 'Sangat menyukai detail, sangat peduli akan akurasi dan kelengkapan data. Cenderung terlalu terlibat dengan detail sehingga melupakan tujuan utama.'
			END INFO_D
			, COALESCE(HSL.NILAI_C,0) NILAI_C
			, CASE 
			WHEN COALESCE(HSL.NILAI_C,0) < 3 THEN 'Lebih mementingkan fleksibilitas daripada struktur, pendekatan kerja lebih ditentukan oleh situasi daripada oleh perencanaan sebelumnya, mudah beradaptasi. Tidak mempedulikan keteraturan	atau kerapihan, ceroboh.'
			WHEN COALESCE(HSL.NILAI_C,0) >= 3 AND COALESCE(HSL.NILAI_C,0) < 5 THEN 'Fleksibel tapi masih cukup memperhatikan keteraturan atau sistematika kerja.'
			WHEN COALESCE(HSL.NILAI_C,0) >= 5 AND COALESCE(HSL.NILAI_C,0) < 7 THEN 'Memperhatikan keteraturan dan sistematika kerja, tapi cukup fleksibel.'
			WHEN COALESCE(HSL.NILAI_C,0) >= 7 AND COALESCE(HSL.NILAI_C,0) < 10 THEN 'Sistematis, bermetoda, berstruktur, rapi dan teratur, dapat menata tugas dengan baik. Cenderung kaku, tidak fleksibel.'
			END INFO_C
			, COALESCE(HSL.NILAI_E,0) NILAI_E
			, CASE 
			WHEN COALESCE(HSL.NILAI_E,0) < 2 THEN 'Sangat terbuka, terus terang, mudah terbaca (dari air muka, tindakan, perkataan, sikap). Tidak dapat mengendalikan emosi, cepat  bereaksi, kurang mengindahkan/tidak mempunyai nilai yg meng- haruskannya menahan emosi.'
			WHEN COALESCE(HSL.NILAI_E,0) >= 2 AND COALESCE(HSL.NILAI_E,0) < 4 THEN 'Terbuka, mudah mengungkap pendapat atau perasaannya menge- nai suatu hal kepada org lain.'
			WHEN COALESCE(HSL.NILAI_E,0) >= 4 AND COALESCE(HSL.NILAI_E,0) < 7 THEN 'Mampu mengungkap atau menyimpan perasaan, dapat mengen- dalikan emosi.'
			WHEN COALESCE(HSL.NILAI_E,0) >= 7 AND COALESCE(HSL.NILAI_E,0) < 10 THEN 'Mampu menyimpan pendapat atau perasaannya, tenang, dapat  mengendalikan emosi, menjaga jarak. Tampil pasif dan tidak acuh, mungkin sulit mengungkapkan emosi/perasaan/pandangan.'
			END INFO_E
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT AA.PEGAWAI_ID, AA.UJIAN_ID, AA.UJIAN_TAHAP_ID
			, COALESCE(W.NILAI,0) NILAI_W, COALESCE(F.NILAI,0) NILAI_F, COALESCE(K.NILAI,0) NILAI_K, COALESCE(Z.NILAI,0) NILAI_Z, COALESCE(O.NILAI,0) NILAI_O, COALESCE(B.NILAI,0) NILAI_B, COALESCE(X.NILAI,0) NILAI_X, COALESCE(P.NILAI,0) NILAI_P, COALESCE(A.NILAI,0) NILAI_A, COALESCE(N.NILAI,0) NILAI_N
			, COALESCE(G.NILAI,0) NILAI_G, COALESCE(L.NILAI,0) NILAI_L, COALESCE(I.NILAI,0) NILAI_I, COALESCE(T.NILAI,0) NILAI_T, COALESCE(V.NILAI,0) NILAI_V, COALESCE(S.NILAI,0) NILAI_S, COALESCE(R.NILAI,0) NILAI_R, COALESCE(D.NILAI,0) NILAI_D, COALESCE(C.NILAI,0) NILAI_C, COALESCE(E.NILAI,0) NILAI_E
			FROM
			(
				SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				FROM cat.UJIAN_PEGAWAI A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) AA
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(GRADE_A),0) NILAI
				FROM cat.UJIAN_PEGAWAI A
				INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
				WHERE B.SOAL_PAPI_ID IN (90, 80, 70, 60, 50, 40, 30, 20, 10)
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) W ON AA.PEGAWAI_ID = W.PEGAWAI_ID AND AA.UJIAN_ID = W.UJIAN_ID AND AA.UJIAN_TAHAP_ID = W.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (79, 69, 59, 49, 39, 29, 19, 9)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (20)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) F ON AA.PEGAWAI_ID = F.PEGAWAI_ID AND AA.UJIAN_ID = F.UJIAN_ID AND AA.UJIAN_TAHAP_ID = F.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (68, 58, 48, 38, 28, 18, 8)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (19, 30)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) K ON AA.PEGAWAI_ID = K.PEGAWAI_ID AND AA.UJIAN_ID = K.UJIAN_ID AND AA.UJIAN_TAHAP_ID = K.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (57, 47, 37, 27, 17, 7)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (8, 19, 30)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) Z ON AA.PEGAWAI_ID = Z.PEGAWAI_ID AND AA.UJIAN_ID = Z.UJIAN_ID AND AA.UJIAN_TAHAP_ID = Z.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (46, 36, 26, 16, 6)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (7, 18, 29, 40)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) O ON AA.PEGAWAI_ID = O.PEGAWAI_ID AND AA.UJIAN_ID = O.UJIAN_ID AND AA.UJIAN_TAHAP_ID = O.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (35, 25, 15, 5)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (6, 17, 28, 39, 50)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) B ON AA.PEGAWAI_ID = B.PEGAWAI_ID AND AA.UJIAN_ID = B.UJIAN_ID AND AA.UJIAN_TAHAP_ID = B.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (24, 14, 4)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (5, 16, 27, 38, 49, 60)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) X ON AA.PEGAWAI_ID = X.PEGAWAI_ID AND AA.UJIAN_ID = X.UJIAN_ID AND AA.UJIAN_TAHAP_ID = X.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (13, 3)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (4, 15, 26, 37, 48, 59, 70)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) P ON AA.PEGAWAI_ID = P.PEGAWAI_ID AND AA.UJIAN_ID = P.UJIAN_ID AND AA.UJIAN_TAHAP_ID = P.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (2)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (3, 14, 25, 36, 47, 58, 69, 80)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) A ON AA.PEGAWAI_ID = A.PEGAWAI_ID AND AA.UJIAN_ID = A.UJIAN_ID AND AA.UJIAN_TAHAP_ID = A.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (2, 13, 24, 35, 46, 57, 68, 79, 90)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) N ON AA.PEGAWAI_ID = N.PEGAWAI_ID AND AA.UJIAN_ID = N.UJIAN_ID AND AA.UJIAN_TAHAP_ID = N.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (81, 71, 61, 51, 41, 31, 21, 11, 1)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) G ON AA.PEGAWAI_ID = G.PEGAWAI_ID AND AA.UJIAN_ID = G.UJIAN_ID AND AA.UJIAN_TAHAP_ID = G.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (82, 72, 62, 52, 42, 32, 22, 12)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (81)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) L ON AA.PEGAWAI_ID = L.PEGAWAI_ID AND AA.UJIAN_ID = L.UJIAN_ID AND AA.UJIAN_TAHAP_ID = L.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (83, 73, 63, 53, 43, 33, 23)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (71, 82)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) I ON AA.PEGAWAI_ID = I.PEGAWAI_ID AND AA.UJIAN_ID = I.UJIAN_ID AND AA.UJIAN_TAHAP_ID = I.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (84, 74, 64, 54, 44, 34)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (61, 72, 83)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) T ON AA.PEGAWAI_ID = T.PEGAWAI_ID AND AA.UJIAN_ID = T.UJIAN_ID AND AA.UJIAN_TAHAP_ID = T.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (85, 75, 65, 55, 45)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (51, 62, 73, 84)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) V ON AA.PEGAWAI_ID = V.PEGAWAI_ID AND AA.UJIAN_ID = V.UJIAN_ID AND AA.UJIAN_TAHAP_ID = V.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (86, 76, 66, 56)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (41, 52, 63, 74, 85)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) S ON AA.PEGAWAI_ID = S.PEGAWAI_ID AND AA.UJIAN_ID = S.UJIAN_ID AND AA.UJIAN_TAHAP_ID = S.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (87, 77, 67)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (31, 42, 53, 64, 75, 86)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) R ON AA.PEGAWAI_ID = R.PEGAWAI_ID AND AA.UJIAN_ID = R.UJIAN_ID AND AA.UJIAN_TAHAP_ID = R.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (88, 78)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (21, 32, 43, 54, 65, 76, 87)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) D ON AA.PEGAWAI_ID = D.PEGAWAI_ID AND AA.UJIAN_ID = D.UJIAN_ID AND AA.UJIAN_TAHAP_ID = D.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_A),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (89)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					UNION ALL
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (11, 22, 33, 44, 55, 56, 66, 77, 88)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) C ON AA.PEGAWAI_ID = C.PEGAWAI_ID AND AA.UJIAN_ID = C.UJIAN_ID AND AA.UJIAN_TAHAP_ID = C.UJIAN_TAHAP_ID
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				, COALESCE(SUM(NILAI),0) NILAI
				FROM
				(
					SELECT
					A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
					, COALESCE(SUM(GRADE_B),0) NILAI
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.PAPI_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.PAPI_PILIHAN_ID
					WHERE B.SOAL_PAPI_ID IN (1, 12, 23, 34, 45, 56, 67, 78, 89)
					GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				) A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) E ON AA.PEGAWAI_ID = E.PEGAWAI_ID AND AA.UJIAN_ID = E.UJIAN_ID AND AA.UJIAN_TAHAP_ID = E.UJIAN_TAHAP_ID
			WHERE 1=1
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJawabanSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
					A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, D.BANK_SOAL_PILIHAN_ID, D.JAWABAN
					, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, TIPE_SOAL, 
					REPLACE(C.PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR1
					, C.PATH_GAMBAR
					, D.PATH_GAMBAR PATH_SOAL
					, C.PATH_SOAL PATH_SOAL1
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian_bank_soal B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
				INNER JOIN cat.bank_soal_pilihan D ON B.BANK_SOAL_ID = D.BANK_SOAL_ID
				INNER JOIN cat.ujian UJ ON UJ.UJIAN_ID = A.UJIAN_ID
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
	
	function selectByParamsJawabanSoalPapi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
					A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, PAPI_PILIHAN_ID BANK_SOAL_PILIHAN_ID, D.JAWABAN
					, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, C.TIPE_UJIAN_ID TIPE_SOAL, 
					'' PATH_GAMBAR1
					, '' PATH_GAMBAR
					, '' PATH_SOAL
					, '' PATH_SOAL1
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian_bank_soal B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.soal_papi C ON B.BANK_SOAL_ID = C.SOAL_PAPI_ID
				INNER JOIN cat.papi_pilihan D ON B.BANK_SOAL_ID = D.SOAL_PAPI_ID
				INNER JOIN cat.ujian UJ ON UJ.UJIAN_ID = A.UJIAN_ID
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
	
	function selectByParamsSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
					A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, C.KEMAMPUAN, C.KATEGORI, C.PERTANYAAN
					, UP.BANK_SOAL_PILIHAN_ID, A.PEGAWAI_ID, UP.UJIAN_PEGAWAI_ID
					, A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, UP.UJIAN_PEGAWAI_ID
					, C.TIPE_SOAL, C.PATH_GAMBAR, C.PATH_SOAL
					, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
					, B.UJIAN_TAHAP_ID, URUT
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian_bank_soal B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
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
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsSoalPapi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
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
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsUjianPegawaiHasil($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT 
		A.PEGAWAI_ID, A.UJIAN_ID, A.JUMLAH_BENAR
		, COALESCE(A.JUMLAH_SOAL,0) - COALESCE(A.JUMLAH_BENAR,0) JUMLAH_SALAH, A.JUMLAH_SOAL, A.NILAI_HASIL 
		FROM cat.UJIAN_PEGAWAI_HASIL A
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
	
	function selectByParamsSoalTahap($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
					A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, C.KEMAMPUAN, C.KATEGORI, C.PERTANYAAN, 
					UP.BANK_SOAL_PILIHAN_ID, A.PEGAWAI_ID, UP.UJIAN_PEGAWAI_ID, 
					A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, UP.UJIAN_PEGAWAI_ID, TIPE_SOAL, 
					REPLACE(C.PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR1
					, C.PATH_GAMBAR
					, C.PATH_SOAL, B.UJIAN_TAHAP_ID
					, CASE WHEN COALESCE(UPX.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian_bank_soal B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
				INNER JOIN cat.ujian UJ ON UJ.UJIAN_ID = A.UJIAN_ID
				INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
				LEFT JOIN cat.ujian_pegawai UP ON UP.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UP.UJIAN_ID AND UP.UJIAN_BANK_SOAL_ID = B.UJIAN_BANK_SOAL_ID
				LEFT JOIN
				(
					SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
					FROM cat.ujian_pegawai
					WHERE BANK_SOAL_PILIHAN_ID IS NOT NULL
					GROUP BY PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID
				) UPX ON UPX.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UPX.UJIAN_ID AND UPX.UJIAN_BANK_SOAL_ID = B.UJIAN_BANK_SOAL_ID
				WHERE 1=1
			"; //REPLACE(PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR, PATH_SOAL, TIPE
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsSoalTahapPapi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "SELECT
					A.UJIAN_ID, B.UJIAN_BANK_SOAL_ID, B.BANK_SOAL_ID, C.KEMAMPUAN, C.KATEGORI, C.PERTANYAAN, 
					UP.BANK_SOAL_PILIHAN_ID, A.PEGAWAI_ID, UP.UJIAN_PEGAWAI_ID, 
					A.STATUS_SETUJU, A.UJIAN_PEGAWAI_DAFTAR_ID, UP.UJIAN_PEGAWAI_ID, TIPE_SOAL, 
					REPLACE(C.PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR1
					, C.PATH_GAMBAR
					, C.PATH_SOAL, B.UJIAN_TAHAP_ID
				FROM cat.ujian_pegawai_daftar A
				INNER JOIN cat.ujian_bank_soal B ON A.UJIAN_ID = B.UJIAN_ID
				INNER JOIN cat.bank_soal C ON B.BANK_SOAL_ID = C.BANK_SOAL_ID
				INNER JOIN cat.ujian UJ ON UJ.UJIAN_ID = A.UJIAN_ID
				INNER JOIN cat.tipe_ujian TU ON TU.TIPE_UJIAN_ID = C.TIPE_UJIAN_ID
				LEFT JOIN cat.ujian_pegawai UP ON UP.PEGAWAI_ID = A.PEGAWAI_ID AND A.UJIAN_ID = UP.UJIAN_ID AND UP.UJIAN_BANK_SOAL_ID = B.UJIAN_BANK_SOAL_ID
				WHERE 1=1
			"; //REPLACE(PATH_GAMBAR, '../', '../../angkasapura-admin/') PATH_GAMBAR, PATH_SOAL, TIPE
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT UJIAN_PEGAWAI_DAFTAR_ID, C.NIP_BARU, C.NAMA NAMA_PEGAWAI, A.UJIAN_ID, A.PEGAWAI_ID
				  FROM cat.UJIAN_PEGAWAI_DAFTAR A
				  LEFT JOIN cat.UJIAN B ON A.UJIAN_ID=B.UJIAN_ID
				  LEFT JOIN cat.PEGAWAI C ON A.PEGAWAI_ID=C.PEGAWAI_ID
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM cat.UJIAN_PEGAWAI_DAFTAR A
		LEFT JOIN pds_rekrutmen.PELAMAR B ON A.PEGAWAI_ID=B.PELAMAR_ID
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
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_PEGAWAI_DAFTAR_ID) AS ROWCOUNT FROM UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_PEGAWAI_DAFTAR_ID IS NOT NULL "; 
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
	
	function getCountByParamsMonitoringPapiHasil($paramsArray=array(),$statement="")
	{
		$str = "
		SELECT COUNT(UJIAN_PEGAWAI_DAFTAR_ID) AS ROWCOUNT 
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT AA.PEGAWAI_ID, AA.UJIAN_ID, AA.UJIAN_TAHAP_ID
			FROM
			(
				SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
				FROM cat.UJIAN_PEGAWAI A
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_TAHAP_ID
			) AA
			WHERE 1=1
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
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
	
	  function getCountByParamsMonitoringCfitHasil($paramsArray=array(),$statement="")
	{
		$str = "
		SELECT COUNT(UJIAN_PEGAWAI_DAFTAR_ID) AS ROWCOUNT 
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.UJIAN_ID, A.ID, ROUND(CAST(COALESCE(A.JUMLAH_BENAR,0) AS NUMERIC) / CAST(COALESCE(B.JUMLAH_BENAR,0) AS NUMERIC) * 100) JUMLAH_BENAR
			, C.UJIAN_TAHAP_ID
			FROM
			(
				SELECT A.PEGAWAI_ID, A.UJIAN_ID, SUBSTR(TU.ID,1,2) ID, COUNT(A.PEGAWAI_ID) AS JUMLAH_BENAR
				FROM cat.UJIAN_PEGAWAI A
				INNER JOIN cat.BANK_SOAL B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID
				INNER JOIN cat.TIPE_UJIAN TU ON TU.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				INNER JOIN 
				(
					SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN
					FROM cat.BANK_SOAL_PILIHAN
					WHERE GRADE_PROSENTASE > 0
				) C ON A.BANK_SOAL_PILIHAN_ID = C.BANK_SOAL_PILIHAN_ID
				GROUP BY A.PEGAWAI_ID, A.UJIAN_ID, SUBSTR(TU.ID,1,2)
			) A
			INNER JOIN
			(
				SELECT A.UJIAN_ID, SUBSTR(TU.ID,1,2) ID, COUNT(A.PEGAWAI_ID) AS JUMLAH_BENAR
				FROM cat.UJIAN_PEGAWAI A
				INNER JOIN cat.BANK_SOAL B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID
				INNER JOIN cat.TIPE_UJIAN TU ON TU.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				INNER JOIN 
				(
					SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN
					FROM cat.BANK_SOAL_PILIHAN
					WHERE GRADE_PROSENTASE > 0
				) C ON A.BANK_SOAL_ID = C.BANK_SOAL_ID
				GROUP BY A.UJIAN_ID, SUBSTR(TU.ID,1,2)
			) B ON B.ID = A.ID AND A.UJIAN_ID = B.UJIAN_ID
			INNER JOIN
			(
				SELECT A.UJIAN_TAHAP_ID, B.ID, A.UJIAN_ID
				FROM cat.UJIAN_TAHAP A
				INNER JOIN cat.TIPE_UJIAN B ON A.TIPE_UJIAN_ID = B.TIPE_UJIAN_ID
				WHERE 1=1
				AND PARENT_ID = '0'
			 ) C ON A.UJIAN_ID = B.UJIAN_ID AND A.ID = C.ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
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
	
    function getCountByParamsMonitoringCfitHasilBak1($paramsArray=array(),$statement="")
	{
		$str = "
		SELECT COUNT(UJIAN_PEGAWAI_DAFTAR_ID) AS ROWCOUNT 
		FROM cat.UJIAN_PEGAWAI_DAFTAR B
		LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
		LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
		LEFT JOIN
		(
			SELECT A_1.PEGAWAI_ID, A_1.UJIAN_ID, A_1.UJIAN_TAHAP_ID, COUNT(A_1.PEGAWAI_ID) AS JUMLAH_BENAR
			FROM cat.UJIAN_PEGAWAI A_1
			INNER JOIN 
			( 
				SELECT BANK_SOAL_PILIHAN.BANK_SOAL_ID, BANK_SOAL_PILIHAN.BANK_SOAL_PILIHAN_ID
				FROM cat.BANK_SOAL_PILIHAN
				WHERE BANK_SOAL_PILIHAN.GRADE_PROSENTASE > 0
			) B_1 ON A_1.BANK_SOAL_ID = B_1.BANK_SOAL_ID AND A_1.BANK_SOAL_PILIHAN_ID = B_1.BANK_SOAL_PILIHAN_ID
			GROUP BY A_1.PEGAWAI_ID, A_1.UJIAN_ID, A_1.UJIAN_TAHAP_ID
		) HSL ON HSL.PEGAWAI_ID = B.PEGAWAI_ID AND HSL.UJIAN_ID = B.UJIAN_ID
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
	
	function getCountByParamsMonitoringHasil($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(UJIAN_PEGAWAI_DAFTAR_ID) AS ROWCOUNT 
				FROM cat.UJIAN_PEGAWAI_DAFTAR B
				LEFT JOIN pds_rekrutmen.PELAMAR A ON B.PEGAWAI_ID=A.PELAMAR_ID
				LEFT JOIN cat.UJIAN_PEGAWAI_HASIL C ON A.PELAMAR_ID = C.PEGAWAI_ID AND B.UJIAN_ID = C.UJIAN_ID
				LEFT JOIN cat.UJIAN D ON B.UJIAN_ID = D.UJIAN_ID
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_PEGAWAI_DAFTAR_ID) AS ROWCOUNT FROM UJIAN_PEGAWAI_DAFTAR WHERE UJIAN_PEGAWAI_DAFTAR_ID IS NOT NULL "; 
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
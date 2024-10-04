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

  class KebutuhanAsesor extends Entity{ 

	var $query;
    function KebutuhanAsesor()
	{
      $this->Entity(); 
    }
	
	function selectByParamsAsesorPenggalianAcara($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sOrder="ORDER BY C.KODE")
	{
		$str = "
		SELECT
		A.JADWAL_ASESOR_ID, A.ASESOR_ID, B.PENGGALIAN_ID
		, CASE B.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE C.NAMA END PENGGALIAN_NAMA
		, CASE B.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE C.KODE END PENGGALIAN_KODE
		FROM jadwal_asesor A
		INNER JOIN jadwal_acara B ON A.JADWAL_ACARA_ID = B.JADWAL_ACARA_ID
		LEFT JOIN penggalian C ON C.PENGGALIAN_ID = B.PENGGALIAN_ID
		WHERE 1=1
		";
		// AND A.JADWAL_TES_ID = 1
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsObservasi($paramsArray=array(), $limit=-1, $from=-1, $statement='', $sOrder="ORDER BY A.PEGAWAI_ID, C1.PENGGALIAN_ID, D.FORM_ATRIBUT_ID, G.INDIKATOR_ID")
	{
		$str = "
		SELECT 
			B.JADWAL_TES_ID, A.JADWAL_ASESOR_ID, A.PEGAWAI_ID, C1.PENGGALIAN_ID, D.FORM_ATRIBUT_ID, G.INDIKATOR_ID
			, CASE C1.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE C2.NAMA END PENGGALIAN_NAMA
			, CASE C1.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE C2.KODE END PENGGALIAN_KODE
			, FE.NAMA_FORMULA, P.NAMA PEGAWAI_NAMA, P.NIP_BARU PEGAWAI_NIP, P.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
			, TO_CHAR(C.TANGGAL_TES, 'YYYY-MM-DD') JT_TANGGAL_TES
			, C1.PUKUL1, C1.PUKUL2, B1.NAMA ASESOR_NAMA
			, F.ATRIBUT_ID, F.NAMA ATRIBUT_NAMA, G.NAMA_INDIKATOR, G1.JUMLAH_LEVEL
		FROM jadwal_pegawai A
		INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
		INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
		INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
		INNER JOIN jadwal_acara C1 ON C1.JADWAL_TES_ID = C.JADWAL_TES_ID AND B.JADWAL_ACARA_ID = C1.JADWAL_ACARA_ID
		INNER JOIN penggalian C2 ON C1.PENGGALIAN_ID = C2.PENGGALIAN_ID
		INNER JOIN
		(
		SELECT B.*, A.FORMULA NAMA_FORMULA
		FROM formula_assesment A
		INNER JOIN (SELECT FORMULA_ESELON_ID, FORMULA_ID FROM formula_eselon) B ON A.FORMULA_ID = B.FORMULA_ID
		) FE ON C.FORMULA_ESELON_ID = FE.FORMULA_ESELON_ID
		INNER JOIN simpeg.pegawai P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
		INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
		INNER JOIN indikator_penilaian G ON G.LEVEL_ID = D.LEVEL_ID AND F.ATRIBUT_ID = G.INDIKATOR_ATRIBUT_ID AND F.PERMEN_ID = G.INDIKATOR_PERMEN_ID
		INNER JOIN
		(
			SELECT A.JADWAL_PEGAWAI_ID, G.LEVEL_ID, COUNT(1) JUMLAH_LEVEL
			FROM jadwal_pegawai A
			INNER JOIN jadwal_asesor B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
			INNER JOIN asesor B1 ON B.ASESOR_ID = B1.ASESOR_ID
			INNER JOIN jadwal_tes C ON B.JADWAL_TES_ID = C.JADWAL_TES_ID
			INNER JOIN formula_atribut D ON C.FORMULA_ESELON_ID = D.FORMULA_ESELON_ID
			INNER JOIN atribut F ON D.FORM_ATRIBUT_ID = F.ATRIBUT_ID AND D.FORM_PERMEN_ID = F.PERMEN_ID
			INNER JOIN indikator_penilaian G ON G.LEVEL_ID = D.LEVEL_ID AND F.ATRIBUT_ID = G.INDIKATOR_ATRIBUT_ID AND F.PERMEN_ID = G.INDIKATOR_PERMEN_ID
			WHERE 1=1
			GROUP BY A.JADWAL_PEGAWAI_ID, G.LEVEL_ID
		) G1 ON G1.LEVEL_ID = G.LEVEL_ID AND A.JADWAL_PEGAWAI_ID = G1.JADWAL_PEGAWAI_ID
		WHERE 1=1
		AND C1.PENGGALIAN_ID > 0
		AND F.ASPEK_ID = 2
		AND EXISTS (SELECT 1 FROM atribut_penggalian X WHERE D.FORMULA_ATRIBUT_ID = X.FORMULA_ATRIBUT_ID AND C1.PENGGALIAN_ID = X.PENGGALIAN_ID)
		";
		// AND C.JADWAL_TES_ID = 1
		// AND A.PEGAWAI_ID = 4218
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM kelompok WHERE 1=1 ".$statement;
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
  } 
?>
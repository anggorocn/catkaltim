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

  class JadwalTes extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalTes()
	{
      $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 

	function selectByParamsFormulaEselon($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_TES_ID ASC")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, A.KETERANGAN, A.STATUS_PENILAIAN
		, A.FORMULA_ESELON_ID, D.FORMULA || ' untuk (' || COALESCE((C.NOTE || ' ' || C.NAMA), C.NAMA) || ')' NAMA_FORMULA_ESELON
		, COALESCE(JUMLAH_ASESOR,0) JUMLAH_ASESOR, COALESCE(JUMLAH_PEGAWAI,0) JUMLAH_PEGAWAI
		, A.JUMLAH_RUANGAN, A.STATUS_VALID, A.TTD_ASESOR, A.TTD_PIMPINAN, A.NIP_ASESOR, A.NIP_PIMPINAN
		, A.TTD_TANGGAL,A.LINK_SOAL
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN eselon C ON C.ESELON_ID = B.ESELON_ID
		INNER JOIN formula_assesment D ON D.FORMULA_ID = B.FORMULA_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, COUNT(A.ASESOR_ID) JUMLAH_ASESOR
			FROM
			(
			SELECT A.JADWAL_TES_ID, A.ASESOR_ID
			FROM jadwal_tes_simulasi_asesor A
			GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
			) A
			GROUP BY A.JADWAL_TES_ID
		) JML_ASESOR ON JML_ASESOR.JADWAL_TES_ID = A.JADWAL_TES_ID
		LEFT JOIN
		(
		SELECT A.JADWAL_TES_ID, COUNT(A.PEGAWAI_ID) JUMLAH_PEGAWAI
		FROM jadwal_tes_simulasi_pegawai A
		GROUP BY A.JADWAL_TES_ID
		) JML_PEGAWAI ON JML_PEGAWAI.JADWAL_TES_ID = A.JADWAL_TES_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	
   

  } 
?>
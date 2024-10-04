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

  class PenilaianLhkpn extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PenilaianLhkpn()
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
		$this->setField("PENILAIAN_LHKPN_ID", $this->getNextId("PENILAIAN_LHKPN_ID","penilaian_lhkpn"));
		
		$str = "INSERT INTO penilaian_lhkpn (
				   PENILAIAN_LHKPN_ID, TIPE, TANGGAL_LAPOR, KETERANGAN, NILAI, PEGAWAI_ID) 
				VALUES (
				  ".$this->getField("PENILAIAN_LHKPN_ID").",
				  ".$this->getField("TIPE").",
				  ".$this->getField("TANGGAL_LAPOR").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("NILAI").",
				  ".$this->getField("PEGAWAI_ID")."
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("PENILAIAN_LHKPN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian_lhkpn
				SET
				   		PENILAIAN_LHKPN_ID = ".$this->getField("PENILAIAN_LHKPN_ID").",
				  		TIPE =  ".$this->getField("TIPE").",
				 	    TANGGAL_LAPOR = ".$this->getField("TANGGAL_LAPOR").",
				        KETERANGAN = '".$this->getField("KETERANGAN")."',
				        NILAI = ".$this->getField("NILAI").",
				        PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				 WHERE PENILAIAN_LHKPN_ID= '".$this->getField("PENILAIAN_LHKPN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE penilaian_lhkpn
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  PENILAIAN_LHKPN_ID = '".$this->getField("PENILAIAN_LHKPN_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
		$str1= "DELETE FROM penilaian_lhkpn_detil
                WHERE 
                  PENILAIAN_LHKPN_ID = '".$this->getField("PENILAIAN_LHKPN_ID")."'"; 
		$this->query = $str1;
        $this->execQuery($str1);
				  
        $str = "DELETE FROM penilaian_lhkpn
                WHERE 
                  PENILAIAN_LHKPN_ID = '".$this->getField("PENILAIAN_LHKPN_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function selectByParamsTahunPenilaianLhkpn($statement="")
	{
		$str = "
		SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY') AS TAHUN
		FROM penilaian_lhkpn A
		WHERE 1=1 ";
		
		$str .= $statement." GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY') ORDER BY TO_CHAR(A.TANGGAL_TES, 'YYYY') DESC";
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1);
    }
	
	function selectByParamsPersonalJkmIkk($statement="")
	{
		$str = "
		SELECT
			COALESCE(X.NILAI_IKK,0) NILAI_IKK, COALESCE(ROUND(100 * X.NILAI_IKK / COUNT(1),2),0) NILAI_IKK_PERSEN
			, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
			, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) NILAI_JPM_PERSEN
			, D.PEGAWAI_ID, AA.NAMA, AA.NAMA_JAB_STRUKTURAL
		FROM  atribut B 
		INNER JOIN penilaian_lhkpn_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
		INNER JOIN penilaian_lhkpn D ON C.PENILAIAN_LHKPN_ID = D.PENILAIAN_LHKPN_ID
		INNER JOIN ".$this->db.".rb_ref_unker A ON D.SATKER_TES_ID = A.KODE_UNKER
		INNER JOIN ".$this->db.".rb_data_pegawai AA ON D.SATKER_TES_ID = AA.KODE_UNKER AND AA.IDPEG = D.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT
				SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
				, D.PENILAIAN_LHKPN_ID, B.ASPEK_ID, A.KODE_UNKER
			FROM  atribut B , penilaian_lhkpn_detil C , penilaian_lhkpn D, ".$this->db.".rb_ref_unker A  
			WHERE 1=1				
			AND B.ATRIBUT_ID = C.ATRIBUT_ID 
			AND C.PENILAIAN_LHKPN_ID = D.PENILAIAN_LHKPN_ID
			AND D.SATKER_TES_ID = A.KODE_UNKER
			AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
		    AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
			GROUP BY D.PENILAIAN_LHKPN_ID, B.ASPEK_ID, A.KODE_UNKER
		) X ON X.PENILAIAN_LHKPN_ID = D.PENILAIAN_LHKPN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
		WHERE 1=1
  		";
		
		$str .= $statement." 
		GROUP BY D.PEGAWAI_ID, AA.NAMA, AA.NAMA_JAB_STRUKTURAL
		ORDER BY ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) DESC
		";
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY PENILAIAN_LHKPN_ID ASC")
	{
		$str = "SELECT PENILAIAN_LHKPN_ID, TIPE, CASE 
				WHEN TIPE = '1' THEN 'LHKPN'
				WHEN TIPE = '2' THEN 'LHKSN'
				END NAMA_TIPE ,TANGGAL_LAPOR, KETERANGAN, NILAI, PEGAWAI_ID 
				FROM penilaian_lhkpn
				WHERE 1=1
				"; 
		//INNER JOIN ".$this->db.".JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_LHKPN_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_LHKPN_ID, A.PEGAWAI_ID, A.TANGGAL_TES, A.JABATAN_TES_ID, A.JABATAN_TES_ID JABATAN_TES,
				A.SATKER_TES_ID, B.NAMA_UNKER SATKER_TES, A.ASPEK_ID, CASE WHEN A.ASPEK_ID = '1' THEN 'Aspek Psikologi' WHEN A.ASPEK_ID = '2' THEN 'Aspek Kompetensi' ELSE '' END ASPEK_NAMA
				FROM penilaian_lhkpn A
				INNER JOIN ".$this->db.".rb_ref_unker B ON A.SATKER_TES_ID = B.KODE_UNKER
				WHERE 1=1
				"; 
		//INNER JOIN ".$this->db.".JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM penilaian_lhkpn A
		WHERE 1=1 "; 
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

  } 
?>
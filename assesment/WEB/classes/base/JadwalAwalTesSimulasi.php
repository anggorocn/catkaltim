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

  class JadwalAwalTesSimulasi extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalAwalTesSimulasi()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity();  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_AWAL_TES_ID", $this->getNextId("JADWAL_AWAL_TES_ID","jadwal_awal_tes_simulasi")); 

		$str = "INSERT INTO jadwal_awal_tes_simulasi (
				   JADWAL_AWAL_TES_ID, FORMULA_ESELON_ID, TANGGAL_TES, TANGGAL_TES_AKHIR, ACARA, TEMPAT, ALAMAT, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_AWAL_TES_ID").",
				  ".$this->getField("FORMULA_ESELON_ID").",
				  ".$this->getField("TANGGAL_TES").",
				  ".$this->getField("TANGGAL_TES_AKHIR").",
				  '".$this->getField("ACARA")."',
				  '".$this->getField("TEMPAT")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_AWAL_TES_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE jadwal_awal_tes_simulasi SET
				  BATAS_PEGAWAI= ".$this->getField("BATAS_PEGAWAI").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$this->getField("JADWAL_AWAL_TES_SIMULASI_ID")."
				"; 
				$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_awal_tes_simulasi
                WHERE 
                  JADWAL_AWAL_TES_ID = ".$this->getField("JADWAL_AWAL_TES_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function deletedetil()
	{
        $str = "DELETE FROM jadwal_awal_tes_simulasi
                WHERE 
                  JADWAL_AWAL_TES_SIMULASI_ID = ".$this->getField("JADWAL_AWAL_TES_SIMULASI_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_AWAL_TES_SIMULASI_ID ASC")
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

    function selectByParamsMenu($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_AWAL_TES_SIMULASI_ID ASC")
	{
		$str = "
		SELECT
			JADWAL_AWAL_TES_SIMULASI_ID, JADWAL_AWAL_TES_ID, TANGGAL_TES
			, ACARA, TEMPAT, ALAMAT, KETERANGAN, COALESCE(BATAS_PEGAWAI,0) BATAS_PEGAWAI
			, COALESCE(JUMLAH_DATA,0) JUMLAH_DATA
		FROM jadwal_awal_tes_simulasi A
		LEFT JOIN
		(
			SELECT JADWAL_AWAL_TES_ID ROW_CHECK_ID, COUNT(1) JUMLAH_DATA
			FROM jadwal_awal_tes_simulasi_pegawai
			GROUP BY JADWAL_AWAL_TES_ID
		) XX ON JADWAL_AWAL_TES_ID = ROW_CHECK_ID 
		WHERE 1=1 ";
		
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

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_awal_tes_simulasi A WHERE 1=1 ".$statement;
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
    function updateNoUrut()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE jadwal_awal_tes_simulasi_pegawai
				SET    
					   No_urut = ".$this->getField("NO_URUT")."
				WHERE JADWAL_AWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
				AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
				$this->query = $str;
				// echo $str; exit;
		return $this->execQuery($str);
    }
  } 
?>
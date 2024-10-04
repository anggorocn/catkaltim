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

  class UjianTahapStatusUjian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianTahapStatusUjian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_TAHAP_STATUS_UJIAN_ID", $this->getNextId("UJIAN_TAHAP_STATUS_UJIAN_ID","cat.ujian_tahap_status_ujian")); 
		$str = "INSERT INTO cat.ujian_tahap_status_ujian (
				   UJIAN_TAHAP_STATUS_UJIAN_ID, UJIAN_PEGAWAI_DAFTAR_ID,
				   JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID,
				   UJIAN_TAHAP_ID, TIPE_UJIAN_ID,
				   PEGAWAI_ID, STATUS,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_TAHAP_STATUS_UJIAN_ID").",
				  ".$this->getField("UJIAN_PEGAWAI_DAFTAR_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("FORMULA_ASSESMENT_ID").",
				  ".$this->getField("FORMULA_ESELON_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("UJIAN_TAHAP_ID").",
				  ".$this->getField("TIPE_UJIAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("STATUS").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function resetwaktu()
	{
		$str1= "
		DELETE FROM cat.ujian_tahap_pegawai
		WHERE  JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
		AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		AND TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID")."
		";
		$this->query = $str1;
        $this->execQuery($str1);

		$str = "
		DELETE FROM cat.ujian_tahap_status_ujian
		WHERE  JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
		AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		AND TIPE_UJIAN_ID = ".$this->getField("TIPE_UJIAN_ID")."
		";
		// echo $str;exit();
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT UJIAN_TAHAP_STATUS_UJIAN_ID, UJIAN_ID, UJIAN_TAHAP_ID, PEGAWAI_ID, STATUS
		FROM cat.ujian_tahap_status_ujian 
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY UJIAN_ID ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

	function getPegawaiUjianTahapSelesai($statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM cat.ujian_tahap_status_ujian A
		WHERE 1=1 ".$statement;
		$this->select($str); 
		$this->query = $str;		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>
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

  class JadwalAcara extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JadwalAcara()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_ACARA_ID", $this->getNextId("JADWAL_ACARA_ID","jadwal_acara")); 

		$str = "INSERT INTO jadwal_acara (
				   JADWAL_ACARA_ID, JADWAL_TES_ID, PENGGALIAN_ID, PUKUL1, PUKUL2, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("PENGGALIAN_ID").",
				  '".$this->getField("PUKUL1")."',
				  '".$this->getField("PUKUL2")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_ACARA_ID");
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
		$str = "UPDATE jadwal_acara SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  PENGGALIAN_ID= ".$this->getField("PENGGALIAN_ID").",
				  PUKUL1= '".$this->getField("PUKUL1")."',
				  PUKUL2= '".$this->getField("PUKUL2")."',
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_ACARA_ID = ".$this->getField("JADWAL_ACARA_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_acara
                WHERE 
                  JADWAL_ACARA_ID = ".$this->getField("JADWAL_ACARA_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_ACARA_ID ASC")
	{
		$str = "SELECT JADWAL_ACARA_ID, JADWAL_TES_ID, PENGGALIAN_ID, PUKUL1, PUKUL2, KETERANGAN
				FROM jadwal_acara WHERE JADWAL_ACARA_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PUKUL1, C.NAMA, A.KETERANGAN ASC")
	{
		$str = "
		SELECT 
		A.JADWAL_ACARA_ID, A.JADWAL_TES_ID
		, A.PUKUL1, A.PUKUL2, A.KETERANGAN AS KETERANGAN_ACARA, B.KETERANGAN AS KETERANGAN_TES
		, JT.PENGGALIAN_KODE_ID, A.PENGGALIAN_ID
		, COALESCE(C.NAMA, 'Potensi') PENGGALIAN_NAMA, COALESCE(C.NAMA, 'Potensi') PENGGALIAN_NAMAbak
		, CASE WHEN jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') = '' THEN 'Belum di tentukan' ELSE jadwal_kelompok_ruangan_info(A.JADWAL_ACARA_ID, '<br/>') END JADWAL_KELOMPOK_RUANG_DATA
		FROM jadwal_acara A
		INNER JOIN jadwal_tes B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		LEFT JOIN penggalian C ON C.PENGGALIAN_ID = A.PENGGALIAN_ID
		LEFT JOIN
		(
			SELECT
				DISTINCT A.JADWAL_TES_ID JT_ID, A.PENGGALIAN_ID PENGGALIAN_KODE_ID
			FROM jadwal_acara A INNER JOIN penggalian C ON C.PENGGALIAN_ID = A.PENGGALIAN_ID
			WHERE 1=1
			AND UPPER(C.KODE) = 'CBI'
		) JT ON A.JADWAL_TES_ID = JT.JT_ID
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
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jadwal_acara WHERE 1=1 ".$statement;
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT
				FROM jadwal_acara A
				INNER JOIN jadwal_tes B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				LEFT JOIN penggalian C ON C.PENGGALIAN_ID = A.PENGGALIAN_ID
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
	
  } 
?>
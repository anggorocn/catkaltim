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

  class RiwayatSkp extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RiwayatSkp()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("RIWAYAT_SKP_ID", $this->getNextId("RIWAYAT_SKP_ID","simpeg.riwayat_skp")); 

		$str = "
		INSERT INTO simpeg.riwayat_skp (
		RIWAYAT_SKP_ID, NILAI_SKP, SKP_TAHUN,PEGAWAI_ID)
		VALUES (
			".$this->getField("RIWAYAT_SKP_ID").",
			".$this->getField("NILAI_SKP").",
			'".$this->getField("SKP_TAHUN")."',
			'".$this->getField("PEGAWAI_ID")."'
		)"; 
				
		$this->query = $str;
		$this->pegawai_id = $this->getField("RIWAYAT_SKP_ID");
		 // echo $str;exit;
		
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE simpeg.riwayat_skp
		SET    
		NILAI_SKP= ".$this->getField("NILAI_SKP").",
		SKP_TAHUN= '".$this->getField("SKP_TAHUN")."'		  
		WHERE RIWAYAT_SKP_ID= '".$this->getField("RIWAYAT_SKP_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM simpeg.riwayat_skp
        WHERE 
        RIWAYAT_SKP_ID = '".$this->getField("RIWAYAT_SKP_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","KECAMATAN_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
    {
    	$str = "
    	SELECT 
    	A.RIWAYAT_SKP_ID,A.NILAI_SKP,A.SKP_TAHUN,A.PEGAWAI_ID, B.NAMA 
    	FROM simpeg.riwayat_skp A
    	LEFT JOIN  simpeg.pegawai B ON B.PEGAWAI_ID = A.PEGAWAI_ID
    	WHERE 1=1 

    	"; 

    	while(list($key,$val) = each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}


    	$str .= $statement." ".$orderby;
    	$this->query = $str;
		//echo $str;

    	return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) ROWCOUNT
		FROM simpeg.riwayat_skp A 
		LEFT JOIN  simpeg.pegawai B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1 = 1
		".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  }
?>
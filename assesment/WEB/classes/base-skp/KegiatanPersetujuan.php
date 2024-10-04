<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KEGIATAN_PERSETUJUAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class KegiatanPersetujuan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KegiatanPersetujuan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KEGIATAN_PERSETUJUAN_ID", $this->getNextId("KEGIATAN_PERSETUJUAN_ID","KEGIATAN_PERSETUJUAN")); 		

		$str = "
				INSERT INTO KEGIATAN_PERSETUJUAN (
				    KEGIATAN_PERSETUJUAN_ID, PEGAWAI_ID_PERSETUJUAN, PEGAWAI_ID, TAHUN, BULAN, 
   					TANGGAL, ALASAN, STATUS)  
 			  	VALUES (
				  ".$this->getField("KEGIATAN_PERSETUJUAN_ID").",
				  ".$this->getField("PEGAWAI_ID_PERSETUJUAN").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("BULAN").",
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("ALASAN")."',
				  '".$this->getField("STATUS")."'
				)"; 
		$this->query = $str;
		
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE KEGIATAN_PERSETUJUAN
				SET    
						PEGAWAI_ID_PERSETUJUAN	= ".$this->getField("PEGAWAI_ID_PERSETUJUAN").",
				  		PEGAWAI_ID				= ".$this->getField("PEGAWAI_ID").",
				  		TAHUN					= ".$this->getField("TAHUN").",
				  		TANGGAL					= ".$this->getField("TANGGAL").",
				  		ALASAN					= '".$this->getField("ALASAN")."',
				  		STATUS					= '".$this->getField("STATUS")."'					   
				WHERE  KEGIATAN_PERSETUJUAN_ID  = '".$this->getField("KEGIATAN_PERSETUJUAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KEGIATAN_PERSETUJUAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE KEGIATAN_PERSETUJUAN_ID = ".$this->getField("KEGIATAN_PERSETUJUAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

    function updateByPegawaiBulanTahun()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KEGIATAN_PERSETUJUAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND
				  BULAN = ".$this->getField("BULAN")." AND
				  TAHUN = ".$this->getField("TAHUN")." ";  
				$this->query = $str;
	
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str = "DELETE FROM KEGIATAN_PERSETUJUAN
                WHERE 
                  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND
				  BULAN = ".$this->getField("BULAN")." AND
				  TAHUN = ".$this->getField("TAHUN")." "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.KEGIATAN_PERSETUJUAN_ID DESC ")
	{
		$str = "
					SELECT 
						   KEGIATAN_PERSETUJUAN_ID, PEGAWAI_ID_PERSETUJUAN, PEGAWAI_ID, TAHUN, 
   							TANGGAL, ALASAN, STATUS
					FROM KEGIATAN_PERSETUJUAN A WHERE KEGIATAN_PERSETUJUAN_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT
						   KEGIATAN_PERSETUJUAN_ID, PEGAWAI_ID_PERSETUJUAN, PEGAWAI_ID, 
   							TANGGAL, ALASAN, STATUS
					FROM KEGIATAN_PERSETUJUAN A WHERE KEGIATAN_PERSETUJUAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(KEGIATAN_PERSETUJUAN_ID) AS ROWCOUNT FROM KEGIATAN_PERSETUJUAN A
		        WHERE KEGIATAN_PERSETUJUAN_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(KEGIATAN_PERSETUJUAN_ID) AS ROWCOUNT FROM KEGIATAN_PERSETUJUAN A
		        WHERE KEGIATAN_PERSETUJUAN_ID IS NOT NULL ".$statement; 
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
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
  * Entity-base class untuk mengimplementasikan tabel PERILAKU_KERJA.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PerilakuKerja extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PerilakuKerja()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERILAKU_KERJA_ID", $this->getNextId("PERILAKU_KERJA_ID","PERILAKU_KERJA")); 		

		$str = "
				INSERT INTO PERILAKU_KERJA (
				    PERILAKU_KERJA_ID, TAHUN, PERTANYAAN_ID, 
				   JAWABAN_ID, PEGAWAI_ID_DINILAI, PEGAWAI_ID_PENILAI, RANGE_1)  
 			  	VALUES (
				  ".$this->getField("PERILAKU_KERJA_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("PERTANYAAN_ID").",
				  ".$this->getField("JAWABAN_ID").",
				  ".$this->getField("PEGAWAI_ID_DINILAI").",
				  ".$this->getField("PEGAWAI_ID_PENILAI").",
				  ".$this->getField("RANGE_1")."
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function update()
	{
		$str = "
				UPDATE PERILAKU_KERJA
				SET    
						TAHUN			= ".$this->getField("TAHUN").",
				 		PERTANYAAN_ID	= ".$this->getField("PERTANYAAN_ID").",
				  		JAWABAN_ID		= ".$this->getField("JAWABAN_ID").",
				  		PEGAWAI_ID_DINILAI	= ".$this->getField("PEGAWAI_ID_DINILAI").",
				  		PEGAWAI_ID_PENILAI	= ".$this->getField("PEGAWAI_ID_PENILAI").",
				  		RANGE_1				= ".$this->getField("RANGE_1")."				   
				WHERE  PERILAKU_KERJA_ID  = '".$this->getField("PERILAKU_KERJA_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }


    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE PERILAKU_KERJA A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PERILAKU_KERJA_ID = ".$this->getField("PERILAKU_KERJA_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM PERILAKU_KERJA
                WHERE 
                  PERILAKU_KERJA_ID = ".$this->getField("PERILAKU_KERJA_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.PERILAKU_KERJA_ID DESC ")
	{
		$str = "
					SELECT 
						   PERILAKU_KERJA_ID, TAHUN, PERTANYAAN_ID, 
						   JAWABAN_ID, PEGAWAI_ID_DINILAI, PEGAWAI_ID_PENILAI, 
						   RANGE_1
					FROM PERILAKU_KERJA A WHERE PERILAKU_KERJA_ID IS NOT NULL
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
						   PERILAKU_KERJA_ID, TAHUN, PERTANYAAN_ID, 
						   JAWABAN_ID, PEGAWAI_ID_DINILAI, PEGAWAI_ID_PENILAI, 
						   RANGE_1
					FROM PERILAKU_KERJA A WHERE PERILAKU_KERJA_ID IS NOT NULL
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
		$str = "SELECT COUNT(PERILAKU_KERJA_ID) AS ROWCOUNT FROM PERILAKU_KERJA A
		        WHERE PERILAKU_KERJA_ID IS NOT NULL ".$statement; 
		
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
		$str = "SELECT COUNT(PERILAKU_KERJA_ID) AS ROWCOUNT FROM PERILAKU_KERJA A
		        WHERE PERILAKU_KERJA_ID IS NOT NULL ".$statement; 
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
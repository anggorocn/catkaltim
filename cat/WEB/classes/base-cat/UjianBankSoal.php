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

  class UjianBankSoal extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianBankSoal()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_BANK_SOAL_ID", $this->getNextId("UJIAN_BANK_SOAL_ID","cat.UJIAN_BANK_SOAL")); 

		$str = "INSERT INTO cat.UJIAN_BANK_SOAL (
				   UJIAN_BANK_SOAL_ID, UJIAN_ID, BANK_SOAL_ID, GRADE, UJIAN_TAHAP_ID, 
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_BANK_SOAL_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("BANK_SOAL_ID").",
				  ".$this->getField("GRADE").",
				  ".$this->getField("UJIAN_TAHAP_ID").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_BANK_SOAL_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_BANK_SOAL SET
				  UJIAN_ID					= ".$this->getField("UJIAN_ID").",
				  BANK_SOAL_ID				= ".$this->getField("BANK_SOAL_ID").",
				  GRADE						= ".$this->getField("GRADE").",
				  UJIAN_TAHAP_ID			= ".$this->getField("UJIAN_TAHAP_ID").",
				  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
				  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_BANK_SOAL_ID 	= ".$this->getField("UJIAN_BANK_SOAL_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.UJIAN_BANK_SOAL
                WHERE 
                  UJIAN_BANK_SOAL_ID = ".$this->getField("UJIAN_BANK_SOAL_ID").""; 
				  
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
		$str = " SELECT UJIAN_BANK_SOAL_ID, UJIAN_ID, BANK_SOAL_ID, GRADE, UJIAN_TAHAP_ID
				  FROM cat.UJIAN_BANK_SOAL
				  WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsMonitoringPapi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_BANK_SOAL_ID, UJIAN_ID, A.BANK_SOAL_ID, PERTANYAAN, GRADE, UJIAN_TAHAP_ID, cat.format_text(PERTANYAAN) PERTANYAAN_FORMAT
				  FROM cat.UJIAN_BANK_SOAL A
				  LEFT JOIN cat.SOAL_PAPI B ON A.BANK_SOAL_ID = B.SOAL_PAPI_ID
				  WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_BANK_SOAL_ID, UJIAN_ID, A.BANK_SOAL_ID, PERTANYAAN, GRADE, UJIAN_TAHAP_ID, cat.format_text(PERTANYAAN) PERTANYAAN_FORMAT
				  FROM cat.UJIAN_BANK_SOAL A
				  LEFT JOIN cat.BANK_SOAL B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID
				  WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsHasil($paramsArray=array(),$limit=-1,$from=-1, $pegawaiId='', $statement='', $sorder="")
	{
		$str = " SELECT UJIAN_BANK_SOAL_ID, A.UJIAN_ID, A.BANK_SOAL_ID, PERTANYAAN, GRADE, UJIAN_TAHAP_ID, cat.format_text(PERTANYAAN) PERTANYAAN_FORMAT, 
C.BANK_SOAL_PILIHAN_ID, D.BANK_SOAL_PILIHAN_ID PEGAWAI_BANK_SOAL_PILIHAN_ID, 
C.JAWABAN KUNCI_JAWABAN, D.JAWABAN PEGAWAI_JAWABAN, CASE WHEN C.BANK_SOAL_PILIHAN_ID = D.BANK_SOAL_PILIHAN_ID THEN 'Benar' ELSE 'Salah' END STATUS_JAWABAN
				  FROM cat.UJIAN_BANK_SOAL A
				  LEFT JOIN cat.BANK_SOAL B ON A.BANK_SOAL_ID = B.BANK_SOAL_ID
				  LEFT JOIN (SELECT BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, JAWABAN
					   FROM cat.BANK_SOAL_PILIHAN
					  WHERE GRADE_PROSENTASE > 0) C ON A.BANK_SOAL_ID = C.BANK_SOAL_ID
				  LEFT JOIN (SELECT UJIAN_ID, PEGAWAI_ID, A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, JAWABAN
					FROM cat.UJIAN_PEGAWAI A
					INNER JOIN cat.BANK_SOAL_PILIHAN B ON A.BANK_SOAL_PILIHAN_ID = B.BANK_SOAL_PILIHAN_ID) D ON A.BANK_SOAL_ID = D.BANK_SOAL_ID AND A.UJIAN_ID = D.UJIAN_ID AND CAST(D.PEGAWAI_ID AS TEXT) = '".$pegawaiId."'
				  WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = " SELECT UJIAN_BANK_SOAL_ID, UJIAN_ID, BANK_SOAL_ID, GRADE, UJIAN_TAHAP_ID
				  FROM UJIAN_BANK_SOAL
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
	function getCountByParamsSimulasiBankSoal()
	{
		$str = "SELECT cat.SIMULASIUJIANBANKSOAL(".$this->getField("UJIAN_ID").", '".$this->getField("LAST_CREATE_USER")."') AS ROWCOUNT ";
		$this->query = $str;
		//echo $str;exit;
		$this->select($str);
		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_BANK_SOAL_ID) AS ROWCOUNT FROM cat.UJIAN_BANK_SOAL WHERE UJIAN_BANK_SOAL_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(UJIAN_BANK_SOAL_ID) AS ROWCOUNT FROM UJIAN_BANK_SOAL WHERE UJIAN_BANK_SOAL_ID IS NOT NULL "; 
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
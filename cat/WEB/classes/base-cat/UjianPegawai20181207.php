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

  class UjianPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianPegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("UJIAN_PEGAWAI_ID", $this->getNextId("UJIAN_PEGAWAI_ID","cat.UJIAN_PEGAWAI")); 

		$str = "INSERT INTO cat.UJIAN_PEGAWAI (
				   UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, URUT, BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID, UJIAN_TAHAP_ID,
				   LAST_CREATE_DATE, LAST_CREATE_USER) 
				VALUES (
				  ".$this->getField("UJIAN_PEGAWAI_ID").",
				  ".$this->getField("UJIAN_ID").",
				  ".$this->getField("UJIAN_BANK_SOAL_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TANGGAL").",
				  ".$this->getField("URUT").",
				  ".$this->getField("BANK_SOAL_ID").",
				  ".$this->getField("BANK_SOAL_PILIHAN_ID").",
				  ".$this->getField("UJIAN_TAHAP_ID").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_USER")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("UJIAN_PEGAWAI_ID");
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_PEGAWAI SET
					  UJIAN_ID= ".$this->getField("UJIAN_ID").",
					  UJIAN_BANK_SOAL_ID= ".$this->getField("UJIAN_BANK_SOAL_ID").",
					  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					  TANGGAL= ".$this->getField("TANGGAL").",
					  URUT= ".$this->getField("URUT").",
					  BANK_SOAL_ID= ".$this->getField("BANK_SOAL_ID").",
					  BANK_SOAL_PILIHAN_ID= ".$this->getField("BANK_SOAL_PILIHAN_ID").",
					  LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."'
				WHERE UJIAN_PEGAWAI_ID 	= ".$this->getField("UJIAN_PEGAWAI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateUrutSoal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE cat.UJIAN_PEGAWAI
				   SET 
					   URUT= ".$this->getField("URUT")."
				WHERE BANK_SOAL_ID = ".$this->getField("BANK_SOAL_ID")."
				AND UJIAN_ID = ".$this->getField("UJIAN_ID")."
				AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function resetUjian()
	{
        $str = "UPDATE cat.UJIAN_PEGAWAI 
				SET BANK_SOAL_PILIHAN_ID = NULL 
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		$this->execQuery($str);
		
		$str1 = "DELETE FROM cat.UJIAN_TAHAP_PEGAWAI
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		$this->execQuery($str1);
				  
		$str2 = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		$this->execQuery($str2);
		
		$str3 = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		
		$this->query = $str3;
        return $this->execQuery($str3);
    }
	
	function resetUjianBak()
	{
        $str = "DELETE FROM cat.UJIAN_PEGAWAI
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		$this->execQuery($str);
		
		$str1 = "DELETE FROM cat.UJIAN_TAHAP_PEGAWAI
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		$this->execQuery($str1);
				  
		$str2 = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		$this->execQuery($str2);
		
		$str3 = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID").""; 
		
		$this->query = $str3;
        return $this->execQuery($str3);
    }
	
	function resetUjianTahap()
	{
        $str = "UPDATE cat.UJIAN_PEGAWAI SET BANK_SOAL_PILIHAN_ID = NULL 
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID").""; 
		$this->execQuery($str);
		
		$str1 = "DELETE FROM cat.UJIAN_TAHAP_PEGAWAI
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID").""; 
		$this->execQuery($str1);
				  
		$str2 = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID").""; 
		$this->execQuery($str2);
		
		$str3 = "DELETE FROM cat.UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK
                WHERE PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").") AND UJIAN_ID = ".$this->getField("UJIAN_ID")." AND UJIAN_TAHAP_ID = ".$this->getField("UJIAN_TAHAP_ID").""; 
		
		$this->query = $str3;
        return $this->execQuery($str3);
    }
	
	function delete()
	{
        $str = "DELETE FROM cat.UJIAN_PEGAWAI
                WHERE 
                  UJIAN_PEGAWAI_ID = ".$this->getField("UJIAN_PEGAWAI_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function deleteId()
	{
        $str = "DELETE FROM cat.UJIAN_PEGAWAI
                WHERE 
                  UJIAN_PEGAWAI_ID = '".$this->getField("UJIAN_PEGAWAI_ID")."'"; 
				  
		$this->query = $str;
		//echo $str;exit;
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
		$str = " SELECT UJIAN_PEGAWAI_ID, UJIAN_ID, UJIAN_BANK_SOAL_ID, PEGAWAI_ID, TANGGAL, 
					   URUT, LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER, 
					   BANK_SOAL_ID, BANK_SOAL_PILIHAN_ID
				  FROM cat.UJIAN_PEGAWAI
				  WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsCheck($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = "
		SELECT
			A.UJIAN_PEGAWAI_ID, A.UJIAN_ID, A.UJIAN_BANK_SOAL_ID, A.PEGAWAI_ID, A.TANGGAL, 
			A.URUT, A.LAST_CREATE_DATE, A.LAST_CREATE_USER, A.LAST_UPDATE_DATE, A.LAST_UPDATE_USER, 
			A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID, A.UJIAN_TAHAP_ID, B.TIPE_UJIAN_ID
		FROM cat.ujian_pegawai A
		INNER JOIN cat.ujian_tahap B ON A.UJIAN_TAHAP_ID = B.UJIAN_TAHAP_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsSoalFinishRevisi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT
			A.UJIAN_ID, A.BANK_SOAL_ID, A.UJIAN_TAHAP_ID, CASE WHEN COALESCE(SUM(CASE WHEN BANK_SOAL_PILIHAN_ID IS NULL THEN 0 ELSE 1 END),0) > 0 THEN 1 ELSE 0 END JUMLAH_DATA
		FROM cat.UJIAN_PEGAWAI A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."
		GROUP BY A.UJIAN_ID, A.BANK_SOAL_ID, A.UJIAN_TAHAP_ID
		".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT UJIAN_PEGAWAI_ID, D.NIP_BARU, D.NAMA NAMA_PEGAWAI, A.UJIAN_ID, A.UJIAN_BANK_SOAL_ID, A.PEGAWAI_ID, TANGGAL, 
					   URUT, A.LAST_CREATE_DATE, A.LAST_CREATE_USER, A.LAST_UPDATE_DATE, A.LAST_UPDATE_USER, 
					   A.BANK_SOAL_ID, A.BANK_SOAL_PILIHAN_ID
			   FROM cat.UJIAN_PEGAWAI A
			   LEFT JOIN cat.UJIAN B ON A.UJIAN_ID=B.UJIAN_ID
			   LEFT JOIN cat.UJIAN_BANK_SOAL C ON A.UJIAN_BANK_SOAL_ID=C.UJIAN_BANK_SOAL_ID
			   LEFT JOIN cat.PEGAWAI D ON A.PEGAWAI_ID=D.PEGAWAI_ID
			   LEFT JOIN cat.BANK_SOAL E ON A.BANK_SOAL_ID=E.BANK_SOAL_ID
			   LEFT JOIN cat.BANK_SOAL_PILIHAN F ON A.BANK_SOAL_PILIHAN_ID=F.BANK_SOAL_PILIHAN_ID
			   WHERE 1=1
				  "; 
		
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
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_PEGAWAI_ID) AS ROWCOUNT FROM cat.UJIAN_PEGAWAI WHERE UJIAN_PEGAWAI_ID IS NOT NULL "; 
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

    function getNoUrut($statement= "")
	{
		$str = "SELECT MAX(URUT) AS ROWCOUNT FROM cat.UJIAN_PEGAWAI WHERE 1=1 ".$statement;
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_PEGAWAI_ID) AS ROWCOUNT FROM UJIAN_PEGAWAI WHERE UJIAN_PEGAWAI_ID IS NOT NULL "; 
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
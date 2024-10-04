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

  class BeasiswaSertifikat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function BeasiswaSertifikat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BEASISWA_SERTIFIKAT_ID", $this->getNextId("BEASISWA_SERTIFIKAT_ID","BEASISWA_SERTIFIKAT")); 

		$str = "INSERT INTO BEASISWA_SERTIFIKAT (
				   BEASISWA_SERTIFIKAT_ID, BEASISWA_ID, JENIS_SERTIFIKAT, 
				   LEMBAGA, SKOR, TIPE_SERTIFIKAT, TANGGAL) 
				VALUES (
				  ".$this->getField("BEASISWA_SERTIFIKAT_ID").",
				  '".$this->getField("BEASISWA_ID")."',
				  '".$this->getField("JENIS_SERTIFIKAT")."',
				  '".$this->getField("LEMBAGA")."',
				  '".$this->getField("SKOR")."',
				  '".$this->getField("TIPE_SERTIFIKAT")."',
				  ".$this->getField("TANGGAL")."
				)"; 
				  //'".$this->getField("AKREDITASI")."'
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE BEASISWA_SERTIFIKAT
				SET    
					   BEASISWA_ID= '".$this->getField("BEASISWA_ID")."',
					   JENIS_SERTIFIKAT= '".$this->getField("JENIS_SERTIFIKAT")."',
					   LEMBAGA= '".$this->getField("LEMBAGA")."',
					   SKOR= '".$this->getField("SKOR")."',
					   TIPE_SERTIFIKAT= '".$this->getField("TIPE_SERTIFIKAT")."',
					   TANGGAL= ".$this->getField("TANGGAL")."
				WHERE  BEASISWA_SERTIFIKAT_ID= '".$this->getField("BEASISWA_SERTIFIKAT_ID")."'
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM BEASISWA_SERTIFIKAT
                WHERE 
                  BEASISWA_SERTIFIKAT_ID = '".$this->getField("BEASISWA_SERTIFIKAT_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","SKOR"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM BEASISWA_SERTIFIKAT A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT 
		   BEASISWA_SERTIFIKAT_ID, BEASISWA_ID, JENIS_SERTIFIKAT, 
		   CASE WHEN JENIS_SERTIFIKAT = '1' THEN 'TOEFL' WHEN JENIS_SERTIFIKAT = '2' THEN 'IELTS' ELSE '' END JENIS_SERTIFIKAT_NAMA,
		   LEMBAGA, SKOR, 
		   CASE 
		   WHEN TIPE_SERTIFIKAT = '1' THEN 'Paper Based Test TOEFL'
		   WHEN TIPE_SERTIFIKAT = '2' THEN 'Computer Based Test TOEFL' 
		   WHEN TIPE_SERTIFIKAT = '3' THEN 'Internet Based Test TOEFL' 
		   WHEN TIPE_SERTIFIKAT = '5' THEN 'No original English used' 
		   WHEN TIPE_SERTIFIKAT = '6' THEN 'Non user' 
		   WHEN TIPE_SERTIFIKAT = '7' THEN 'Intermittent user' 
		   WHEN TIPE_SERTIFIKAT = '8' THEN 'Extremely limited user' 
		   WHEN TIPE_SERTIFIKAT = '9' THEN 'Limited user' 
		   WHEN TIPE_SERTIFIKAT = '10' THEN 'Modest user' 
		   WHEN TIPE_SERTIFIKAT = '11' THEN 'Competen user' 
		   WHEN TIPE_SERTIFIKAT = '12' THEN 'Good user' 
		   WHEN TIPE_SERTIFIKAT = '13' THEN 'Very good user' 
		   WHEN TIPE_SERTIFIKAT = '14' THEN 'Expert user' 
		   ELSE '' 
		   END TIPE_SERTIFIKAT_NAMA,
		   TIPE_SERTIFIKAT, TANGGAL
		FROM BEASISWA_SERTIFIKAT A WHERE  1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY LEMBAGA ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT BEASISWA_SERTIFIKAT_ID, BEASISWA_ID, JENIS_SERTIFIKAT, 
				   SKOR, TIPE_SERTIFIKAT, JURUSAN_ASAL, 
				   LEMBAGA
				FROM BEASISWA_SERTIFIKAT WHERE BEASISWA_SERTIFIKAT_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY SKOR ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","SKOR"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(BEASISWA_SERTIFIKAT_ID) AS ROWCOUNT FROM BEASISWA_SERTIFIKAT A WHERE 1=1 ".$statement; 
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
		$str = "SELECT COUNT(BEASISWA_SERTIFIKAT_ID) AS ROWCOUNT FROM BEASISWA_SERTIFIKAT WHERE BEASISWA_SERTIFIKAT_ID IS NOT NULL "; 
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
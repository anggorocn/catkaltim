<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Dokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Dokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DNID", $this->getNextId("DNID","dokumen")); 

		$str = "INSERT INTO dokumen(DNID, nama, link_file, tanggal, status_aktif, keterangan, status_dokumen) 
				VALUES(
				  ".$this->getField("DNID").",
				  '".$this->getField("nama")."',
				  '".$this->getField("link_file")."',
				  '".$this->getField("tanggal")."',
				  '".$this->getField("status_aktif")."',
				  '".$this->getField("keterangan")."',
				  ".$this->getField("status_dokumen")."
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE dokumen SET
				  nama = '".$this->getField("nama")."',
				  link_file = '".$this->getField("link_file")."',				  
				  status_aktif = '".$this->getField("status_aktif")."',
				  keterangan = '".$this->getField("keterangan")."'
				WHERE DNID = '".$this->getField("DNID")."'
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM dokumen
                WHERE 
                  DNID = '".$this->getField("DNID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT DNID, nama, link_file, status_aktif, tanggal, keterangan, status_dokumen
				FROM dokumen WHERE DNID IS NOT NULL".$varStatement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY DNID DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsRand($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT DNID, nama, link_file, status_aktif, tanggal, keterangan, status_dokumen
				FROM dokumen WHERE DNID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY RAND()";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT DNID, nama, link_file, status_aktif, tanggal, keterangan, status_dokumen
				FROM dokumen WHERE DNID IS NOT NULL".$varStatement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY DNID DESC";
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(DNID) AS ROWCOUNT FROM dokumen WHERE DNID IS NOT NULL ".$varStatement; 
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

    function getCountByParamsLike($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(DNID) AS ROWCOUNT FROM dokumen WHERE DNID IS NOT NULL ".$varStatement; 
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
	
	function updateStatusAktif()
	{
		$str = "UPDATE dokumen SET
				  status_aktif = '".$this->getField("status_aktif")."'
				WHERE DNID = '".$this->getField("DNID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function dokumenUtama()
	{
		$str = "UPDATE dokumen SET
				  dokumen_utama = '".$this->getField("dokumen_utama")."'
				WHERE DNID = '".$this->getField("DNID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function setDefaultDokumen()
	{
		$strSet = "UPDATE dokumen SET
				  dokumen_utama = '0'
				"; 
		$this->query = $strSet;
		return $this->execQuery($strSet);
	}
  } 
?>
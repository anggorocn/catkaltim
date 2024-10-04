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

  class Prestasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Prestasi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PRID", $this->getNextId("PRID","prestasi")); 

		$str = "INSERT INTO prestasi(PRID, 
									 tanggal, 
									 judul, 
									 sub_judul, 
									 status_aktif, 
									 keterangan) 
				VALUES(
				  ".$this->getField("PRID").",
				  '".$this->getField("tanggal")."',
				  '".$this->getField("judul")."',
				  '".$this->getField("sub_judul")."',
				  '".$this->getField("status_aktif")."',
				  '".$this->getField("keterangan")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE prestasi SET
				  judul = '".$this->getField("judul")."',
				  sub_judul = '".$this->getField("sub_judul")."',
				  status_aktif = '".$this->getField("status_aktif")."',
				  tanggal = '".$this->getField("tanggal")."',
				  keterangan = '".$this->getField("keterangan")."'
				WHERE PRID = '".$this->getField("PRID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM prestasi
                WHERE 
                  PRID = '".$this->getField("PRID")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT PRID, 
						 tanggal, 
						 judul, 
						 sub_judul, 
						 status_aktif, 
						 keterangan
				FROM prestasi WHERE PRID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY tanggal DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT PRID, 
						 tanggal, 
						 judul, 
						 sub_judul, 
						 status_aktif, 
						 keterangan
				FROM prestasi WHERE PRID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY tanggal DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(PRID) AS ROWCOUNT FROM prestasi WHERE PRID IS NOT NULL "; 
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
		$str = "SELECT COUNT(PRID) AS ROWCOUNT FROM prestasi WHERE PRID IS NOT NULL "; 
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
		$str = "UPDATE prestasi SET
				  status_aktif = '".$this->getField("status_aktif")."'
				WHERE PRID = '".$this->getField("PRID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
  } 
?>
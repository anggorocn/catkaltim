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

  class Contact extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Contact()
	{
      $this->Entity(); 
    }
			
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CTID", $this->getNextId("CTID","contact")); 

		$str = "INSERT INTO contact(CTID, nama, email, perusahaan, subyek, pesan, ipaddress, tanggal) 
				VALUES(
				  ".$this->getField("CTID").",
				  '".$this->getField("nama")."',
				  '".$this->getField("email")."',
				  '".$this->getField("perusahaan")."',
				  '".$this->getField("subyek")."',				  				  
				  '".$this->getField("pesan")."',
				  '".$this->getField("ipaddress")."',				  
				  '".$this->getField("tanggal")."'				  				  
				)"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE web_link SET
				  nama = '".$this->getField("nama")."',
				  link = '".$this->getField("link")."',
				  keterangan = '".$this->getField("keterangan")."'
				WHERE WLID = '".$this->getField("WLID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM contact
                WHERE 
                  CTID = '".$this->getField("CTID")."'"; 
				  
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
		$str = "SELECT CTID, nama, email, perusahaan, subyek, pesan, ipaddress, tanggal
				FROM contact WHERE CTID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY CTID ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT CTID, nama, email, perusahaan, subyek, pesan, ipaddress, tanggal
				FROM contact WHERE CTID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY CTID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(CTID) AS ROWCOUNT FROM contact WHERE CTID IS NOT NULL "; 
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
		$str = "SELECT COUNT(CTID) AS ROWCOUNT FROM contact WHERE CTID IS NOT NULL "; 
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
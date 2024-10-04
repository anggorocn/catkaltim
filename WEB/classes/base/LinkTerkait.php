<? 

  include_once("../WEB/classes/db/Entity.php");

  class LinkTerkait extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LinkTerkait()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("link_terkait_id", $this->getNextId("link_terkait_id","link_terkait")); 
		$this->tanggal = date("Y-m-d H:i:s");

		$str = "INSERT INTO link_terkait(link_terkait_id, nama, keterangan, link) 
				VALUES(
				  ".$this->getField("link_terkait_id").",
				  '".$this->getField("nama")."',
				  '".$this->getField("keterangan")."',
				  '".$this->getField("link")."'
				)"; 
		//echo $str;		
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE link_terkait SET				  
				  keterangan = '".$this->getField("keterangan")."',
				  nama = '".$this->getField("nama")."',
				  link = '".$this->getField("link")."'
				WHERE link_terkait_id = '".$this->getField("link_terkait_id")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);//nama = '".$this->getField("nama")."',
    }
		
	function delete()
	{
        $str = "DELETE FROM link_terkait
                WHERE 
                  link_terkait_id = '".$this->getField("link_terkait_id")."'"; 
				  
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
		$str = "SELECT link_terkait_id, nama, keterangan, link
				FROM link_terkait WHERE link_terkait_id IS NOT NULL ".$varStatement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY nama ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT link_terkait_id, nama, keterangan, link
				FROM link_terkait WHERE link_terkait_id IS NOT NULL ".$varStatement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY nama ASC";
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
		$str = "SELECT COUNT(link_terkait_id) AS ROWCOUNT FROM link_terkait WHERE link_terkait_id IS NOT NULL ".$varStatement; 
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

    function getCountByParamsLike($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(link_terkait_id) AS ROWCOUNT FROM link_terkait WHERE link_terkait_id IS NOT NULL ".$varStatement; 
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
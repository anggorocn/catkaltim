<? 

  include_once("../WEB/classes/db/Entity.php");

  class Grafik extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Grafik()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("grafik_data_id", $this->getNextId("grafik_data_id","grafik_data")); 
		$this->tanggal = date("Y-m-d H:i:s");

		$str = "INSERT INTO grafik_data(grafik_data_id, grafik_id, nama, jumlah) 
				VALUES(
				  ".$this->getField("grafik_data_id").",
				  '".$this->getField("grafik_id")."',
				  '".$this->getField("nama")."',
				  '".$this->getField("jumlah")."'
				)"; 
		//echo $str;		
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE grafik_data SET
				  nama = '".$this->getField("nama")."',
				  jumlah = '".$this->getField("jumlah")."'
				WHERE grafik_data_id = '".$this->getField("grafik_data_id")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);//nama = '".$this->getField("nama")."',
    }
		
	function delete()
	{
        $str = "DELETE FROM grafik_data
                WHERE 
                  grafik_data_id = '".$this->getField("grafik_data_id")."'"; 
				  
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
		$str = "SELECT grafik_data_id, grafik_id, nama, jumlah
				FROM grafik_data WHERE grafik_data_id IS NOT NULL ".$varStatement; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY grafik_data_id ASC";
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT grafik_data_id, grafik_id, nama, jumlah
				FROM grafik_data WHERE grafik_data_id IS NOT NULL ".$varStatement; 
		
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
		$str = "SELECT COUNT(grafik_data_id) AS ROWCOUNT FROM grafik_data WHERE grafik_data_id IS NOT NULL ".$varStatement; 
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
		$str = "SELECT COUNT(grafik_data_id) AS ROWCOUNT FROM grafik_data WHERE grafik_data_id IS NOT NULL ".$varStatement; 
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

    function getSumJumlah($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT SUM(jumlah) AS ROWCOUNT FROM grafik_data WHERE grafik_data_id IS NOT NULL ".$varStatement; 
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
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

  class Propinsi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Propinsi()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT PROPINSI_ID, PROPINSI_PARENT_ID, NAMA 
		FROM propinsi WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PROPINSI_ID", $this->getNextId("PROPINSI_ID","propinsi")); 
		$str = "
				INSERT INTO propinsi
				(PROPINSI_ID, PROPINSI_PARENT_ID, NAMA) 
				VALUES(
				  ".$this->getField("PROPINSI_ID").",
				  '".$this->getField("PROPINSI_PARENT_ID")."',
				  '".$this->getField("NAMA")."'
				)"; 
		$this->id = $this->getField("PROPINSI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE propinsi SET
				  PROPINSI_PARENT_ID = '".$this->getField("PROPINSI_PARENT_ID")."',
				  NAMA = '".$this->getField("NAMA")."'
				WHERE PROPINSI_ID = '".$this->getField("PROPINSI_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM propinsi
                WHERE 
                  PROPINSI_ID = '".$this->getField("PROPINSI_ID")."'"; 
				  
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
    function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT PROPINSI_ID, PROPINSI_PARENT_ID, NAMA 
				FROM propinsi WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$str .= $statement." ORDER BY PROPINSI_ID DESC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PROPINSI_ID) AS ROWCOUNT FROM propinsi WHERE 1 = 1 ".$statement; 
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
		$str = "SELECT COUNT(PROPINSI_ID) AS ROWCOUNT FROM propinsi WHERE 1 = 1 "; 
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
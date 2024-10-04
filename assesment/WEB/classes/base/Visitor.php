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

  class Visitor extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Visitor()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("AANWIJZING_ID", $this->getNextId("AANWIJZING_ID","AANWIJZING")); 

		$str = "
				INSERT INTO VISITOR (
				   IP, TANGGAL, HITS, 
   					STATUS) 
				VALUES (
				  '".$this->getField("IP")."', 
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("HITS")."', 
				  '".$this->getField("STATUS")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function getOnline($time='', $ip='')
	{
		$str = " SELECT 1 TOTAL FROM VISITOR WHERE IP = '" . $ip . "' AND TO_DATE(TANGGAL, 'yyyy/mm/dd hh:mi:ss') = '" . $time . "' "; 
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }
	
    function hitsToday($time='')
	{
		$str = " SELECT SUM(HITS) TOTAL FROM VISITOR
				 WHERE TO_DATE(TANGGAL, 'yyyy/mm/dd hh:mi:ss') = '" . $time . "' GROUP BY TANGGAL "; 
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }
	
	function totalHits()
	{
		$str = " SELECT SUM(HITS) as TOTAL FROM VISITOR "; 
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }

	function countOnline($diff='')
	{
		$str = " SELECT COUNT(*) TOTAL FROM VISITOR WHERE STATUS > " . $diff . " "; 
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }

    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(AANWIJZING_ID) AS ROWCOUNT FROM AANWIJZING WHERE AANWIJZING_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(AANWIJZING_ID) AS ROWCOUNT FROM AANWIJZING WHERE AANWIJZING_ID IS NOT NULL "; 
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
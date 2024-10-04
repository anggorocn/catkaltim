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
				INSERT INTO pds_rekrutmen.VISITOR (
				   IP, TANGGAL, HITS, 
   					STATUS) 
				VALUES (
				  '".$this->getField("IP")."', 
				  CURRENT_DATE, 
				  '".$this->getField("HITS")."', 
				  '".$this->getField("STATUS")."'
				)"; 
				//echo $str;
		$this->query = $str;
		return $this->execQuery($str);
    }

    function getOnline($time='', $ip='')
	{
		$str = " SELECT 1 TOTAL FROM pds_rekrutmen.VISITOR WHERE IP = '" . $ip . "' AND TO_CHAR(TANGGAL, 'DD-MM-YYYY') = TO_CHAR(CURRENT_DATE, 'DD-MM-YYYY') "; 
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }
	
    function hitsToday()
	{
		$str = " SELECT SUM(HITS::integer) TOTAL FROM pds_rekrutmen.VISITOR
				 WHERE TO_CHAR(TANGGAL, 'DD-MM-YYYY') = TO_CHAR(CURRENT_DATE, 'DD-MM-YYYY')  GROUP BY TO_CHAR(TANGGAL, 'DD-MM-YYYY') "; 
		
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }
	
	function totalHits()
	{
		$str = " SELECT SUM(HITS::integer) as TOTAL FROM pds_rekrutmen.VISITOR "; 
		
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }

	function countOnline($diff='')
	{
		$str = " SELECT COUNT(*) TOTAL FROM pds_rekrutmen.VISITOR WHERE STATUS > " . $diff . " "; 
		
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOTAL"); 
		else 
			return 0; 
    }
  } 
?>
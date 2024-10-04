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

  class Polling extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Polling()
	{
      $this->Entity(); 
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE polling SET
				  vote = '".$this->getField("vote")."' + 1
				WHERE PID = '".$this->getField("PID")."'
				"; 
				$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectPolling($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT PID, vote
				FROM polling WHERE PID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY PID DESC";
		
		return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountPolling($paramsArray=array())
	{
		$str = "SELECT COUNT(PID) AS ROWCOUNT FROM polling WHERE PID IS NOT NULL "; 
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
	
	function selectPollingVote($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT PID, optionPID, ipAddress, pollingPID, UPID
				FROM polling_vote WHERE PID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY PID DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
  } 
?>
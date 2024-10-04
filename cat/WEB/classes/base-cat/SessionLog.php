<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel USER_GROUP.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class SessionLog extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SessionLog()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO cat.SESSION_LOG (
		   NID, APLIKASI_ID_LOG, IP_ADDRESS, LOGIN_DATE)
		VALUES ( ".$this->getField("NID").", '".$this->getField("APLIKASI_ID_LOG")."', '".$this->getField("IP_ADDRESS")."', ".$this->getField("LOGIN_DATE").")
		";
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
		$str = "DELETE FROM cat.SESSION_LOG WHERE NID = '".$this->getField("NID")."'"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteKondisi($statement="")
	{
		$str = "DELETE FROM cat.SESSION_LOG WHERE NID = '".$this->getField("NID")."'".$statement;
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function getSelect($statement="")
	{
		$str = "SELECT NID, IP_ADDRESS FROM cat.SESSION_LOG A WHERE 1=1  ".$statement; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
    function getToken($paramsArray=array(), $statement="")
	{
		$str = "SELECT TOKEN AS TOKEN FROM cat.SESSION_LOG A WHERE 1=1  ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("TOKEN"); 
		else 
			return ""; 
    }
	
  } 
?>
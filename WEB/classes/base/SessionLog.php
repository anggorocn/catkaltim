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
	
	function insertBak()
	{
		$str = "
		INSERT INTO SESSION_LOG (
		   NID, APLIKASI_ID_LOG, IP_ADDRESS, LOGIN_DATE)
		VALUES ( ".$this->getField("NID").", '".$this->getField("APLIKASI_ID_LOG")."', '".( preg_match( "/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] )."', ".$this->getField("LOGIN_DATE").")
		";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insert()
	{
		$str = "
		INSERT INTO SESSION_LOG (
		   NID, APLIKASI_ID_LOG, IP_ADDRESS, LOGIN_DATE)
		VALUES ( ".$this->getField("NID").", '".$this->getField("APLIKASI_ID_LOG")."', '".$this->getField("IP_ADDRESS")."', ".$this->getField("LOGIN_DATE").")
		";
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	function delete()
	{
		$str = "DELETE FROM SESSION_LOG WHERE NID = '".$this->getField("NID")."'"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function deleteFilter($statement="")
	{
		$str = "DELETE FROM SESSION_LOG WHERE 1=1 ".$statement; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteKondisi($statement="")
	{
		$str = "DELETE FROM SESSION_LOG WHERE NID = '".$this->getField("NID")."'".$statement;
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function getSelect($statement="")
	{
		$str = "SELECT NID, IP_ADDRESS FROM SESSION_LOG A WHERE 1=1  ".$statement; 
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
    function getToken($paramsArray=array(), $statement="")
	{
		$str = "SELECT TOKEN AS TOKEN FROM SESSION_LOG A WHERE 1=1  ".$statement; 
		
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.NID, A.APLIKASI_ID_LOG, A.IP_ADDRESS, A.LOGIN_DATE, B.USER_LOGIN, B.NAMA
		FROM session_log A
		LEFT JOIN pds_rekrutmen.user_login B ON B.USER_LOGIN_ID = A.NID
		LEFT JOIN pds_rekrutmen.pelamar C ON B.PELAMAR_ID = C.PELAMAR_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM session_log A
		LEFT JOIN pds_rekrutmen.user_login B ON B.USER_LOGIN_ID = A.NID
		LEFT JOIN pds_rekrutmen.pelamar C ON B.PELAMAR_ID = C.PELAMAR_ID
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>
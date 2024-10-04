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
  //include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/Entity.php");

  class UserLoginBase extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UserLoginBase()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","USER_LOGIN")); 

		$str = "
				INSERT INTO USER_LOGIN (
				   USER_LOGIN_ID, USER_GROUP_ID, 
				   NAMA, JABATAN, EMAIL, 
				   TELEPON, USER_LOGIN, USER_PASS, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE, KECAMATAN_ID, DESA_ID) 
  			 	VALUES (
				  ".$this->getField("USER_LOGIN_ID").",
				  '".$this->getField("USER_GROUP_ID")."', 	
    			  '".$this->getField("NAMA")."',
      			  '".$this->getField("JABATAN")."',
  				  '".$this->getField("EMAIL")."',
				  '".$this->getField("TELEPON")."',	
				  '".$this->getField("USER_LOGIN")."',
				  '".md5($this->getField("USER_PASS"))."',
  				  '".$this->getField("STATUS")."',
				  '".$this->getField("LAST_CREATE_USER")."',
 				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("KECAMATAN_ID").",
				  '".$this->getField("DESA_ID")."')"; 	
		$this->query = $str;
		
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE USER_LOGIN
				SET     
					   KECAMATAN_ID    = ".$this->getField("KECAMATAN_ID").",	
					   USER_GROUP_ID    = ".$this->getField("USER_GROUP_ID").",					   
					   NAMA       = '".$this->getField("NAMA")."',
					   JABATAN    = '".$this->getField("JABATAN")."',
					   EMAIL     = '".$this->getField("EMAIL")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					   TELEPON    = '".$this->getField("TELEPON")."'				   
				WHERE  USER_LOGIN_ID   = ".$this->getField("USER_LOGIN_ID")."
 				"; 

				$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatusAktif()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE USER_LOGIN
				SET     
					   STATUS       = '".$this->getField("STATUS")."'			   
				WHERE  PEGAWAI_ID   = '".$this->getField("PEGAWAI_ID")."'
 				"; 

				$this->query = $str;
		return $this->execQuery($str);
    }
		
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE USER_LOGIN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID")."
				"; 
				$this->query = $str;
	//echo $str;
		return $this->execQuery($str);
    }	

    function updateByFieldTanpaPetik()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE USER_LOGIN A SET
				  ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID")."
				"; 
				$this->query = $str;
	//echo $str;
		return $this->execQuery($str);
    }	
		
    function resetPassword()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE USER_LOGIN A SET
				  	USER_PASS = '".md5($this->getField("USER_PASS"))."'
				WHERE USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID")."
				"; 
				$this->query = $str;
	//echo $str;
		return $this->execQuery($str);
    }			
	
	function delete()
	{
        $str = "DELETE FROM USER_LOGIN
                WHERE 
                  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TANGGAL"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "SELECT USER_LOGIN_ID, USER_GROUP_ID, 
				   A.NAMA, JABATAN, EMAIL, 
				   TELEPON, USER_LOGIN, USER_PASS, 'Aktif' STATUS, LAST_CREATE_USER, LAST_CREATE_DATE, A.KECAMATAN_ID, DESA_ID, '' KECAMATAN,
				   'Administrator' USER_GROUP
				FROM USER_LOGIN A 
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ".$order;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT USER_LOGIN_ID, KECAMATAN_ID, USER_GROUP_ID, 
				   NAMA, JABATAN, EMAIL, 
				   TELEPON, USER_LOGIN, USER_PASS, 
				   USER_IS_LOGIN, USER_LAST_LOGIN, STATUS
 
				FROM USER_LOGIN WHERE USER_LOGIN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA	 ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TANGGAL"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(),$stat="")
	{
		$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM USER_LOGIN A WHERE 1 = 1 ".$stat; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM USER_LOGIN WHERE USER_LOGIN_ID IS NOT NULL "; 
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
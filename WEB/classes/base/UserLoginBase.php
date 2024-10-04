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
		$this->setField("USER_LOGIN_ID", $this->getAdminNextId("USER_LOGIN_ID","USER_LOGIN")); 

		$str = "
				INSERT INTO USER_LOGIN (
				   USER_LOGIN_ID, DEPARTEMEN_ID, USER_GROUP_ID, 
				   NAMA, JABATAN, EMAIL, WILAYAH_ID, IS_WILAYAH,
				   TELEPON, USER_LOGIN, USER_PASS, STATUS, LAST_CREATE_USER, LAST_CREATE_DATE, PEGAWAI_ID, CABANG_P3_ID) 
  			 	VALUES (
				  ".$this->getField("USER_LOGIN_ID").",
  				  ".$this->getField("DEPARTEMEN_ID").",
				  '".$this->getField("USER_GROUP_ID")."', 	
    			  '".$this->getField("NAMA")."',
      			  '".$this->getField("JABATAN")."',
  				  '".$this->getField("EMAIL")."',
  				  ".$this->getField("WILAYAH_ID").",
  				  '".$this->getField("IS_WILAYAH")."',
				  '".$this->getField("TELEPON")."',	
				  '".$this->getField("USER_LOGIN")."',
				  '".md5($this->getField("USER_PASS"))."',
  				  '".$this->getField("STATUS")."',
				  '".$this->getField("LAST_CREATE_USER")."',
 				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("CABANG_P3_ID").")"; 	
		$this->query = $str;
		
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE USER_LOGIN
				SET     
					   DEPARTEMEN_ID    = ".$this->getField("DEPARTEMEN_ID").",	
					   USER_GROUP_ID    = ".$this->getField("USER_GROUP_ID").",					   
					   NAMA       = '".$this->getField("NAMA")."',
					   JABATAN    = '".$this->getField("JABATAN")."',
					   EMAIL     = '".$this->getField("EMAIL")."',
  				  	   WILAYAH_ID		= ".$this->getField("WILAYAH_ID").",
  				  	   IS_WILAYAH		= '".$this->getField("IS_WILAYAH")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					   TELEPON    = '".$this->getField("TELEPON")."',				   
					   PEGAWAI_ID    = ".$this->getField("PEGAWAI_ID").",				   
					   CABANG_P3_ID    = ".$this->getField("CABANG_P3_ID")."					   
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
				  ".$this->getField("USER_PASS")." = '".$this->getField("USER_PASS")."'
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
		$str = "
		SELECT
			USER_LOGIN_ID, A.DEPARTEMEN_ID, A.USER_GROUP_ID, 
			'' DEPARTEMEN, C.NAMA USER_GROUP,
			A.NAMA, A.JABATAN, A.EMAIL, A.WILAYAH_ID, IS_WILAYAH,
			A.TELEPON, USER_LOGIN, USER_PASS, CASE WHEN A.STATUS = 1 THEN 'Aktif' ELSE 'Non-Aktif' END STATUS, 
			A.PEGAWAI_ID, '' STATUS_PEGAWAI, A.CABANG_P3_ID, COALESCE(G.NAMA, 'Semua Cabang') CABANG_P3_NAMA
		FROM USER_LOGIN A
		LEFT JOIN USER_GROUP C ON A.USER_GROUP_ID = C.USER_GROUP_ID
		LEFT JOIN pds_rekrutmen.CABANG_P3 G ON G.CABANG_P3_ID = A.CABANG_P3_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT USER_LOGIN_ID, DEPARTEMEN_ID, USER_GROUP_ID, 
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
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM USER_LOGIN A
				LEFT JOIN USER_GROUP C ON A.USER_GROUP_ID = C.USER_GROUP_ID
				LEFT JOIN pds_rekrutmen.CABANG_P3 G ON G.CABANG_P3_ID = A.CABANG_P3_ID
				WHERE 1=1 ".$stat;
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
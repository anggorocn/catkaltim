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
  * Entity-base class untuk mengimplementasikan tabel persyaratan_belajar.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PersyaratanBelajar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PersyaratanBelajar()
	{
	  $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERSYARATAN_ID", $this->getNextId("PERSYARATAN_ID","persyaratan_belajar")); 		

		$str = "
				INSERT INTO persyaratan_belajar (PERSYARATAN_ID, SYARAT, STATUS_TB, STATUS_IB)
 			  	VALUES (
				  ".$this->getField("PERSYARATAN_ID").",
				  ".$this->getField("SYARAT").",
  				  '".$this->getField("STATUS_TB")."',
   				  '".$this->getField("STATUS_IB")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("PERSYARATAN_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE persyaratan_belajar
				SET 
				  ".$this->getField("SYARAT").",
  				  '".$this->getField("STATUS_TB")."',
   				  '".$this->getField("STATUS_IB")."'
				WHERE  PERSYARATAN_ID  = '".$this->getField("PERSYARATAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updatePerpanjangan()
	{
		$str = "
				UPDATE persyaratan_belajar
				SET
					STATUS_BELAJAR= '2',
					TMT_SELESAI= ".$this->getField("TMT_SELESAI").",
					LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
					LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
					LAST_UPDATE_SATKER= '".$this->getField("LAST_UPDATE_SATKER")."'
				WHERE  PERSYARATAN_ID  = '".$this->getField("PERSYARATAN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE persyaratan_belajar A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PERSYARATAN_ID = ".$this->getField("PERSYARATAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM persyaratan_belajar
                WHERE 
                  PERSYARATAN_ID = ".$this->getField("PERSYARATAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteAll()
	{
		$str1 = "DELETE FROM persyaratan_belajar_lapor
                WHERE 
                  PERSYARATAN_ID = ".$this->getField("PERSYARATAN_ID").""; 
				  
		$this->query = $str1;
        $this->execQuery($str1);
		
        $str = "DELETE FROM persyaratan_belajar
                WHERE 
                  PERSYARATAN_ID = ".$this->getField("PERSYARATAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParamsIjinBelajar($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.PERSYARATAN_ID, SYARAT, STATUS_TB, STATUS_IB 
					FROM persyaratan_belajar A
				WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
  /*	function selectByParamsTugasBelajar($paramsArray=array(),$limit=-1,$from=-1, $reqId="")
	{
		$str = "SELECT A.PERSYARATAN_ID, SYARAT, STATUS_TB, STATUS_IB 
				FROM persyaratan_belajar A
				LEFT JOIN proses_syarat_belajar ON A.PERSYARATAN_ID = A.PERSYARATAN_ID AND TUGAS_BELAJAR_ID = ".$reqId."
				WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	*/
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT PERSYARATAN_ID, SYARAT, STATUS_TB, STATUS_IB 
						FROM persyaratan_belajar A WHERE PERSYARATAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NO_SK ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERSYARATAN_ID) AS ROWCOUNT FROM persyaratan_belajar A
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERSYARATAN_ID) AS ROWCOUNT FROM persyaratan_belajar A
		        WHERE PERSYARATAN_ID IS NOT NULL ".$statement; 
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
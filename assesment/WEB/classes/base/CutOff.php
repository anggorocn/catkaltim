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
  
  class CutOff extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function CutOff()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CUT_OFF_ID", $this->getNextId("CUT_OFF_ID","CUT_OFF")); 

		$str = "INSERT INTO CUT_OFF (
				   CUT_OFF_ID, TANGGAL_INTEGRASI, TANGGAL_AWAL_TPP, TANGGAL_AKHIR_TPP, LAST_CREATE_USER, LAST_CREATE_DATE
				) 
				VALUES (
				  ".$this->getField("CUT_OFF_ID").",
				  '".$this->getField("TANGGAL_INTEGRASI")."',
				  '".$this->getField("TANGGAL_AWAL_TPP")."',
				  '".$this->getField("TANGGAL_AKHIR_TPP")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		
		$this->id= $this->getField("CUT_OFF_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE CUT_OFF SET
					STATUS= ".$this->getField("STATUS").",
					LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
				    LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE CUT_OFF_ID = '".$this->getField("CUT_OFF_ID")."'"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE CUT_OFF 
				SET
				  TANGGAL_INTEGRASI	= '".$this->getField("TANGGAL_INTEGRASI")."',
				  TANGGAL_AWAL_TPP	= '".$this->getField("TANGGAL_AWAL_TPP")."',
				  TANGGAL_AKHIR_TPP	= '".$this->getField("TANGGAL_AKHIR_TPP")."',
				  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
				  LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE CUT_OFF_ID 		= '".$this->getField("CUT_OFF_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM CUT_OFF
                WHERE CUT_OFF_ID = '".$this->getField("CUT_OFF_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TANGGAL_MASUK"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY CUT_OFF_ID ASC")
	{
		$str = "SELECT CUT_OFF_ID, TANGGAL_INTEGRASI, TANGGAL_AWAL_TPP, TANGGAL_AKHIR_TPP
				FROM CUT_OFF A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsInfo($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY CUT_OFF_ID ASC")
	{
		$str = "
		SELECT 
		TANGGAL_AWAL_TPP, TANGGAL_AKHIR_TPP,
		CASE 
			WHEN CAST(TO_CHAR(CURRENT_DATE, 'DD') AS NUMERIC) >= 1 AND CAST(TO_CHAR(CURRENT_DATE, 'DD') AS NUMERIC) <= CAST(TANGGAL_AWAL_TPP AS NUMERIC) 
		THEN 
			TO_DATE(CONCAT( TANGGAL_AWAL_TPP, '-' , TO_CHAR(DATE_TRUNC('MONTH', CURRENT_DATE) + INTERVAL '1 MONTH - 1 DAY', 'MM-YYYY')), 'DD-MM-YYYY') - 1
		ELSE
			TO_DATE(CONCAT( TANGGAL_AWAL_TPP, '-' ,TO_CHAR(CURRENT_DATE + INTERVAL '1 MONTH', 'MM-YYYY')), 'DD-MM-YYYY') - 1
		END BATAS_PERIODEBAK
		,
		CASE 
			WHEN CAST(TO_CHAR(CURRENT_DATE, 'DD') AS NUMERIC) >= 1 AND CAST(TO_CHAR(CURRENT_DATE, 'DD') AS NUMERIC) <= CAST(TANGGAL_AWAL_TPP AS NUMERIC) 
		THEN 
			TO_DATE(CONCAT( TANGGAL_AWAL_TPP, '-' , TO_CHAR(DATE_TRUNC('MONTH', CURRENT_DATE) - INTERVAL '1 MONTH - 1 DAY', 'MM-YYYY')), 'DD-MM-YYYY')
		ELSE
			TO_DATE(CONCAT( TANGGAL_AWAL_TPP, '-' ,TO_CHAR(CURRENT_DATE , 'MM-YYYY')), 'DD-MM-YYYY')
		END BATAS_PERIODE
		,
		CASE 
			WHEN CAST(TO_CHAR(CURRENT_DATE, 'DD') AS NUMERIC) >= 1 AND CAST(TO_CHAR(CURRENT_DATE, 'DD') AS NUMERIC) <= CAST(TANGGAL_AWAL_TPP AS NUMERIC) 
		THEN 
			TO_DATE(CONCAT( TANGGAL_AWAL_TPP, '-' , TO_CHAR(DATE_TRUNC('MONTH', CURRENT_DATE) + INTERVAL '1 MONTH - 1 DAY', 'MM-YYYY')), 'DD-MM-YYYY') - CURRENT_DATE
		ELSE
			TO_DATE(CONCAT( TANGGAL_AWAL_TPP, '-' ,TO_CHAR(CURRENT_DATE + INTERVAL '1 MONTH', 'MM-YYYY')), 'DD-MM-YYYY') - CURRENT_DATE
		END BATAS_HARI
		FROM CUT_OFF
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY CUT_OFF_ID ASC")
	{
		$str = "SELECT A.CUT_OFF_ID, A.TANGGAL_INTEGRASI, A.TANGGAL_AWAL_TPP, A.TANGGAL_AKHIR_TPP
				, 
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.CUT_OFF_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"../WEB/images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.CUT_OFF_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"../WEB/images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM CUT_OFF A
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TANGGAL_MASUK"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM CUT_OFF A WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str.=$statement;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("rowcount"); 
		else 
			return 0; 
    }
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM CUT_OFF A 
				WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str.=$statement;
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("rowcount"); 
		else 
			return 0; 
    }

  } 
?>
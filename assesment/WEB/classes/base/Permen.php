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

  class Permen extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Permen()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity();  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERMEN_ID", $this->getNextId("PERMEN_ID","permen"));
		
		$str = "
				INSERT INTO PERMEN(
			            PERMEN_ID, NAMA, KETERANGAN, STATUS)
			    VALUES ('".$this->getField("PERMEN_ID")."', '".$this->getField("NAMA")."', '".$this->getField("KETERANGAN")."', ".$this->getField("STATUS").")

			    "; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("PERMEN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE permen
				SET
				   NAMA= '".$this->getField("NAMA")."',
				   KETERANGAN= '".$this->getField("KETERANGAN")."',
				   STATUS= ".$this->getField("STATUS")."
				WHERE PERMEN_ID= ".$this->getField("PERMEN_ID")."
				"; 
				// echo $str;
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE PERMEN
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE  PERMEN_ID = ".$this->getField("PERMEN_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateNonaktif()
	{
		$str = "
				UPDATE PERMEN
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE  PERMEN_ID != ".$this->getField("PERMEN_ID")."
			 "; 
		$this->query = $str;
		// echo $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM PERMEN
                WHERE 
                  PERMEN_ID = '".$this->getField("PERMEN_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PERMEN_ID ASC")
	{
		$str = "
				SELECT PERMEN_ID, NAMA, KETERANGAN, STATUS,
						CASE WHEN STATUS = '1' THEN 'Aktif' ELSE ' Non Aktif' END STATUS_DESC
				  FROM PERMEN A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

	/** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM PERMEN A
		WHERE 1=1 "; 
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
	
  } 
?>
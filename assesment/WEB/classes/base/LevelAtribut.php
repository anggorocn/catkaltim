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

  class LevelAtribut extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function LevelAtribut()
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
		$this->setField("LEVEL_ID", $this->getNextId("LEVEL_ID","level_atribut"));
		
		$str = "INSERT INTO level_atribut (
				   LEVEL_ID, ATRIBUT_ID, LEVEL, KETERANGAN, PERMEN_ID)
				VALUES (
				  ".$this->getField("LEVEL_ID").",
				  '".$this->getField("ATRIBUT_ID")."',
				  ".$this->getField("LEVEL").",
				  '".$this->getField("KETERANGAN")."',
				  (SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
				)"; 
		//echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("LEVEL_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE level_atribut
				SET
				   LEVEL= ".$this->getField("LEVEL").",
				   KETERANGAN= '".$this->getField("KETERANGAN")."'
				WHERE LEVEL_ID= '".$this->getField("LEVEL_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE level_atribut
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  LEVEL_ID = '".$this->getField("LEVEL_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM level_atribut
                WHERE 
                  LEVEL_ID = '".$this->getField("LEVEL_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM level_atribut A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.LEVEL_ID ASC")
	{
		$str = "
				SELECT A.LEVEL_ID, A.ATRIBUT_ID, A.LEVEL
				, A.KETERANGAN
				FROM level_atribut A
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
	
	function selectByParamsLevelAtribut($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.LEVEL_ID ASC")
	{
		$str = "
				SELECT A.LEVEL_ID, A.ATRIBUT_ID, A.LEVEL
				, A.KETERANGAN, B.NAMA ATRIBUT_NAMA
				FROM level_atribut A
				INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID
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
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM level_atribut A
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
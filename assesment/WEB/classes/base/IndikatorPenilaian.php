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

  class IndikatorPenilaian extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function IndikatorPenilaian()
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
		$this->setField("INDIKATOR_ID", $this->getNextId("INDIKATOR_ID","indikator_penilaian"));
		
		$str = "INSERT INTO indikator_penilaian (
				   INDIKATOR_ID, LEVEL_ID, NAMA_INDIKATOR, KETERANGAN)
				VALUES (
				  ".$this->getField("INDIKATOR_ID").",
				  ".$this->getField("LEVEL_ID").",
				  '".$this->getField("NAMA_INDIKATOR")."',
				  '".$this->getField("KETERANGAN")."'
				)"; 
		//echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("INDIKATOR_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE indikator_penilaian
				SET
				   LEVEL_ID= ".$this->getField("LEVEL_ID").",
				   NAMA_INDIKATOR= '".$this->getField("NAMA_INDIKATOR")."',
				   KETERANGAN= '".$this->getField("KETERANGAN")."'
				WHERE INDIKATOR_ID= ".$this->getField("INDIKATOR_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE indikator_penilaian
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  INDIKATOR_ID = '".$this->getField("INDIKATOR_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM indikator_penilaian
                WHERE 
                  INDIKATOR_ID = ".$this->getField("INDIKATOR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM indikator_penilaian A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.INDIKATOR_ID ASC")
	{
		$str = "
				SELECT A.INDIKATOR_ID, A.LEVEL_ID, A.NAMA_INDIKATOR
				, A.KETERANGAN
				FROM indikator_penilaian A
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
		FROM indikator_penilaian A
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
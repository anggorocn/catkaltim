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

  class PegawaiHcdpDetil extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function PegawaiHcdpDetil()
	{
	  $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("PEGAWAI_HCDP_DETIL_ID", $this->getNextId("PEGAWAI_HCDP_DETIL_ID","pegawai_hcdp_detil"));
		
		$str = "
		INSERT INTO pegawai_hcdp_detil 
		(
			PEGAWAI_HCDP_DETIL_ID, PEGAWAI_HCDP_ID, PEGAWAI_ID, ATRIBUT_ID
			, PELATIHAN_HCDP_ID, NAMA_PELATIHAN, PELATIHAN_ID, PELATIHAN_NAMA
			, PERMEN_ID
		)
		VALUES 
		(
			".$this->getField("PEGAWAI_HCDP_DETIL_ID")."
			, ".$this->getField("PEGAWAI_HCDP_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("ATRIBUT_ID")."'
			, '".$this->getField("PELATIHAN_HCDP_ID")."'
			, '".$this->getField("NAMA_PELATIHAN")."'
			, '".$this->getField("PELATIHAN_ID")."'
			, '".$this->getField("PELATIHAN_NAMA")."'
			, (SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
		)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("PEGAWAI_HCDP_DETIL_ID");
		return $this->execQuery($str);
    }
	
    function delete()
	{
        $str = "
        DELETE FROM pegawai_hcdp_detil
        WHERE PEGAWAI_HCDP_ID = ".$this->getField("PEGAWAI_HCDP_ID"); 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.*
		FROM pegawai_hcdp_detil A
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
	
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pegawai_hcdp_detil A
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
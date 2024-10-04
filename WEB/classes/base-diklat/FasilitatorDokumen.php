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

  class FasilitatorDokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FasilitatorDokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("FASILITATOR_DOKUMEN_ID", $this->getNextId("FASILITATOR_DOKUMEN_ID","FASILITATOR_DOKUMEN")); 
		$str = "
		INSERT INTO FASILITATOR_DOKUMEN
		(
			FASILITATOR_DOKUMEN_ID, FASILITATOR_ID, NAMA, STATUS
			, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES (
		".$this->getField("FASILITATOR_DOKUMEN_ID").",
		".$this->getField("FASILITATOR_ID").",
		'".$this->getField("NAMA")."',
		'1',
		'".$this->getField("LAST_CREATE_USER")."',
		".$this->getField("LAST_CREATE_DATE")."
		)
		";

		$this->id = $this->getField("FASILITATOR_DOKUMEN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE FASILITATOR_DOKUMEN
		SET
		NAMA= '".$this->getField("NAMA")."',
		LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
		LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		WHERE FASILITATOR_DOKUMEN_ID = ".$this->getField("FASILITATOR_DOKUMEN_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM FASILITATOR_DOKUMEN
        WHERE 
        FASILITATOR_DOKUMEN_ID = '".$this->getField("FASILITATOR_DOKUMEN_ID")."'
        ";	  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT A.FASILITATOR_DOKUMEN_ID, A.FASILITATOR_ID, A.NAMA, A.STATUS
		, CASE A.STATUS WHEN '1' THEN 'Belum Di validasi' WHEN '2' THEN 'Data Valid' ELSE 'Data di tolak' END STATUS_NAMA
		FROM FASILITATOR_DOKUMEN A
		WHERE 1 = 1
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM FASILITATOR_DOKUMEN A
		WHERE 1 = 1 ".$statement; 
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
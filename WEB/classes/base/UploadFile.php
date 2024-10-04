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

  class UploadFile extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UploadFile()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		// $this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","simpeg.UPLOAD_FILE")); 
		$str = "
		INSERT INTO simpeg.UPLOAD_FILE
		(
			PEGAWAI_ID, NAMA, LINK_FILE1, LINK_FILE2
			, LINK_FILE3,LINK_FOTO
		)
		VALUES (
		".$this->getField("PEGAWAI_ID").",
		'".$this->getField("NAMA")."',
		'".$this->getField("LINK_FILE1")."',
		'".$this->getField("LINK_FILE2")."',
		'".$this->getField("LINK_FILE3")."',
		'".$this->getField("LINK_FOTO")."'

		)
		";

		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE simpeg.UPLOAD_FILE
		SET
		NAMA= '".$this->getField("NAMA")."',
		LINK_FILE1= '".$this->getField("LINK_FILE1")."',
		LINK_FILE2= '".$this->getField("LINK_FILE2")."',
		LINK_FILE3= '".$this->getField("LINK_FILE3")."',
		LINK_FOTO= '".$this->getField("LINK_FOTO")."'

		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 
		// echo $str;exit();
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM SIMPEG.UPLOAD_FILE
        WHERE 
        PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
        ";	  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT PEGAWAI_ID, NAMA, LINK_FILE1,LINK_FILE2,LINK_FILE3,LINK_FOTO
    	FROM simpeg.UPLOAD_FILE A
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

	function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM simpeg.UPLOAD_FILE A
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
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

  class UploadFileUjian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UploadFileUjian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("UPLOAD_FILE_UJIAN_ID", $this->getNextId("UPLOAD_FILE_UJIAN_ID","cat.UPLOAD_FILE_UJIAN")); 
		$str = "
		INSERT INTO cat.UPLOAD_FILE_UJIAN
		(
			UPLOAD_FILE_UJIAN_ID,PEGAWAI_ID,JADWAL_TES_ID, UJIAN_ID, NAMA, LINK_FILE
		)
		VALUES (
		".$this->getField("UPLOAD_FILE_UJIAN_ID").",
		".$this->getField("PEGAWAI_ID").",
		".$this->getField("JADWAL_TES_ID").",
		".$this->getField("UJIAN_ID").",
		'".$this->getField("NAMA")."',
		'".$this->getField("LINK_FILE")."'
		)
		";

		$this->id = $this->getField("UPLOAD_FILE_UJIAN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE cat.UPLOAD_FILE_UJIAN
		SET
		PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
		JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
		UJIAN_ID= ".$this->getField("UJIAN_ID").",
		NAMA= '".$this->getField("NAMA")."',
		LINK_FILE= '".$this->getField("LINK_FILE")."'

		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		AND JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
		AND UJIAN_ID = ".$this->getField("UJIAN_ID")."
		"; 
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
    	SELECT UPLOAD_FILE_UJIAN_ID,JADWAL_TES_ID,UJIAN_ID,PEGAWAI_ID, NAMA, LINK_FILE
    	FROM cat.UPLOAD_FILE_UJIAN A
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
		FROM cat.UPLOAD_FILE_UJIAN A
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
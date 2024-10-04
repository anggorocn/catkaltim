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

  class PesertaPerubahanNip extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PesertaPerubahanNip()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("PESERTA_PERUBAHAN_NIP_ID", $this->getNextId("PESERTA_PERUBAHAN_NIP_ID","PESERTA_PERUBAHAN_NIP")); 
		$str = "
		INSERT INTO PESERTA_PERUBAHAN_NIP
		(
			PESERTA_PERUBAHAN_NIP_ID, PESERTA_ID, NIP, STATUS
			, LAST_CREATE_USER, LAST_CREATE_DATE
		)
		VALUES (
		".$this->getField("PESERTA_PERUBAHAN_NIP_ID").",
		".$this->getField("PESERTA_ID").",
		'".$this->getField("NIP")."',
		'1',
		'".$this->getField("LAST_CREATE_USER")."',
		".$this->getField("LAST_CREATE_DATE")."
		)
		";

		$this->id = $this->getField("PESERTA_PERUBAHAN_NIP_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE PESERTA_PERUBAHAN_NIP
		SET
		NIP= '".$this->getField("NIP")."',
		LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
		LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."
		WHERE PESERTA_PERUBAHAN_NIP_ID = ".$this->getField("PESERTA_PERUBAHAN_NIP_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM PESERTA_PERUBAHAN_NIP
        WHERE 
        PESERTA_PERUBAHAN_NIP_ID = '".$this->getField("PESERTA_PERUBAHAN_NIP_ID")."'
        ";	  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT A.PESERTA_PERUBAHAN_NIP_ID, A.PESERTA_ID, A.NIP, A.STATUS
		, CASE A.STATUS WHEN '1' THEN 'Belum Di validasi' WHEN '2' THEN 'Data Valid' ELSE 'Data di tolak' END STATUS_NAMA
		FROM PESERTA_PERUBAHAN_NIP A
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
		FROM PESERTA_PERUBAHAN_NIP A
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
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

  class Ujian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Ujian()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sorder="")
	{
		$str = " 
		SELECT
			UJIAN_ID, TGL_MULAI, TGL_SELESAI, 
			CASE WHEN  STATUS='0' THEN 'Belum Selesai' 
			WHEN STATUS='1' THEN 'Sudah Selesai'
			END STATUS_UJIAN, STATUS, NILAI_LULUS, BATAS_WAKTU_MENIT, 
			LAST_CREATE_DATE, LAST_CREATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_USER
		FROM cat.UJIAN A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sorder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(UJIAN_ID) AS ROWCOUNT FROM cat.UJIAN WHERE UJIAN_ID IS NOT NULL "; 
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
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

  class TipePelatihan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TipePelatihan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TIPE_PELATIHAN_ID", $this->getNextId("TIPE_PELATIHAN_ID","tipe_pelatihan")); 
		$str = "INSERT INTO tipe_pelatihan (
				   TIPE_PELATIHAN_ID, KODE_PELATIHAN,NAMA_TIPE_PELATIHAN) 
				VALUES (
				  ".$this->getField("TIPE_PELATIHAN_ID").",
				  '".$this->getField("KODE_PELATIHAN")."',
				  '".$this->getField("NAMA_TIPE_PELATIHAN")."' 
				)"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE tipe_pelatihan SET
				  KODE_PELATIHAN= '".$this->getField("KODE_PELATIHAN")."',
				  NAMA_TIPE_PELATIHAN= '".$this->getField("NAMA_TIPE_PELATIHAN")."'
				WHERE TIPE_PELATIHAN_ID 	= ".$this->getField("TIPE_PELATIHAN_ID")."
				"; 
				$this->query = $str;
				//echo $str;

		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM tipe_pelatihan
                WHERE 
                  TIPE_PELATIHAN_ID = ".$this->getField("TIPE_PELATIHAN_ID").""; 
				  
		$this->query = $str;

        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TIPE"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT TIPE_PELATIHAN_ID, KODE_PELATIHAN, NAMA_TIPE_PELATIHAN
				FROM tipe_pelatihan WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY TIPE_PELATIHAN_ID ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
  
	
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM tipe_pelatihan WHERE 1=1 "; 
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
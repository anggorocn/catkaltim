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

  class KategoriPelatihan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KategoriPelatihan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KATEGORI_PELATIHAN_ID", $this->getNextId("KATEGORI_PELATIHAN_ID","kategori_pelatihan")); 
		$str = "INSERT INTO kategori_pelatihan (
				   KATEGORI_PELATIHAN_ID,KODE_KATEGORI_PELATIHAN,NAMA_KATEGORI_PELATIHAN,TIPE_PELATIHAN_ID) 
				VALUES (
				  ".$this->getField("KATEGORI_PELATIHAN_ID").",
				  '".$this->getField("KODE_KATEGORI_PELATIHAN")."',
				  '".$this->getField("NAMA_KATEGORI_PELATIHAN")."',
				  ".$this->getField("TIPE_PELATIHAN_ID")."
				)"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE kategori_pelatihan SET
				  KODE_KATEGORI_PELATIHAN= '".$this->getField("KODE_KATEGORI_PELATIHAN")."',
				  NAMA_KATEGORI_PELATIHAN= '".$this->getField("NAMA_KATEGORI_PELATIHAN")."',
				  TIPE_PELATIHAN_ID= ".$this->getField("TIPE_PELATIHAN_ID")."
				WHERE KATEGORI_PELATIHAN_ID 	= ".$this->getField("KATEGORI_PELATIHAN_ID")."
				"; 
				$this->query = $str;
				//echo $str;

		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM kategori_pelatihan
                WHERE 
                  KATEGORI_PELATIHAN_ID = ".$this->getField("KATEGORI_PELATIHAN_ID").""; 
				  
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
		$str = "SELECT A.KATEGORI_PELATIHAN_ID,A.KODE_KATEGORI_PELATIHAN,A.NAMA_KATEGORI_PELATIHAN,B.TIPE_PELATIHAN_ID,B.NAMA_TIPE_PELATIHAN,B.KODE_PELATIHAN
				FROM kategori_pelatihan A
				LEFT JOIN TIPE_PELATIHAN B ON A.TIPE_PELATIHAN_ID = B.TIPE_PELATIHAN_ID 
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY KATEGORI_PELATIHAN_ID ASC";
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
  
	
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM kategori_pelatihan WHERE 1=1 "; 
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
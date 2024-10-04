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

  class BukuTamu extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function BukuTamu()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BTID", $this->getNextId("BTID","buku_tamu")); 

		$str = "INSERT INTO buku_tamu(BTID, 
									 UID, 
									 tanggal, 
									 isi, 
									 respon) 
				VALUES(
				  ".$this->getField("BTID").",
				  '".$this->getField("UID")."',
				  '".$this->getField("tanggal")."',
				  '".$this->getField("isi")."',
				  '".$this->getField("respon")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE buku_tamu SET
				  isi = '".$this->getField("isi")."',
				  respon = '".$this->getField("respon")."'
				WHERE BTID = '".$this->getField("BTID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM buku_tamu
                WHERE 
                  BTID = '".$this->getField("BTID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT bt.BTID AS bt_BTID, 
						 bt.UID AS bt_UID, 
						 bt.tanggal AS bt_tanggal, 
						 bt.isi AS bt_isi, 
						 bt.respon AS bt_respon,
						 u.NAMA AS u_NAMA
				FROM buku_tamu bt, users u 
				WHERE BTID IS NOT NULL 
					AND u.UID = bt.UID "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY bt.tanggal DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT bt.BTID AS bt_BTID, 
						 bt.UID AS bt_UID, 
						 bt.tanggal AS bt_tanggal, 
						 bt.isi AS bt_isi, 
						 bt.respon AS bt_respon,
						 u.NAMA AS u_NAMA
				FROM buku_tamu bt, users u 
				WHERE BTID IS NOT NULL 
					AND u.UID = bt.UID "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY bt.tanggal DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(BTID) AS ROWCOUNT FROM buku_tamu WHERE BTID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(BTID) AS ROWCOUNT FROM buku_tamu WHERE BTID IS NOT NULL "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function updateStatusAktif()
	{
		$str = "UPDATE buku_tamu SET
				  status_aktif = '".$this->getField("status_aktif")."'
				WHERE BTID = '".$this->getField("BTID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
  } 
?>
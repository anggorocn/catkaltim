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

  class Galeri extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Galeri()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("GLID", $this->getNextId("GLID","galeri")); 

		$str = "INSERT INTO galeri(GLID, GKID, nama, keterangan, tanggal, link_file) 
				VALUES(
				  ".$this->getField("GLID").",
				  '".$this->getField("GKID")."',
				  '".$this->getField("nama")."',
				  '".$this->getField("keterangan")."',
				  '".$this->getField("tanggal")."',
				  '".$this->getField("link_file")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE galeri SET
				  GKID = '".$this->getField("GKID")."',
				  nama = '".$this->getField("nama")."',
				  keterangan = '".$this->getField("keterangan")."',
				  link_file = '".$this->getField("link_file")."'
				WHERE GLID = '".$this->getField("GLID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM galeri
                WHERE 
                  GLID = '".$this->getField("GLID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","GKID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT gl.GLID AS gl_GLID, 
					gl.GKID AS gl_GKID, 
					gl.nama AS gl_nama, 
					gl.keterangan AS gl_keterangan,
					gl.tanggal AS gl_tanggal,
					gl.link_file AS gl_link_file,
					gk.nama AS gk_nama
				FROM galeri gl, galeri_kategori gk
				WHERE gl.GLID IS NOT NULL 
					AND gk.GKID = gl.GKID "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $varStatement." ORDER BY gl.GLID DESC";
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1,$varStatement="")
	{
		$str = "SELECT gl.GLID AS gl_GLID, 
					gl.GKID AS gl_GKID, 
					gl.nama AS gl_nama, 
					gl.keterangan AS gl_keterangan,
					gl.tanggal AS gl_tanggal,
					gl.link_file AS gl_link_file,
					gk.nama AS gk_nama
				FROM galeri gl, galeri_kategori gk
				WHERE gl.GLID IS NOT NULL 
					AND gk.GKID = gl.GKID "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $varStatement." ORDER BY gl.GLID DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","GKID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(gl.GLID) AS ROWCOUNT FROM galeri gl, galeri_kategori gk WHERE gl.GLID IS NOT NULL AND gk.GKID = gl.GKID ".$varStatement; 
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

    function getCountByParamsLike($paramsArray=array(),$varStatement="")
	{
		$str = "SELECT COUNT(gl.GLID) AS ROWCOUNT FROM galeri gl, galeri_kategori gk WHERE gl.GLID IS NOT NULL AND gk.GKID = gl.GKID ".$varStatement; 
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
	
  } 
?>
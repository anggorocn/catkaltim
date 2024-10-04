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

  class Kegiatan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Kegiatan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KGID", $this->getNextId("KGID","kegiatan")); 
		$this->tanggal = date("Y-m-d H:i:s");

		$str = "INSERT INTO kegiatan(KGID, UID, tanggal, nama, keterangan, link_file)
				VALUES(
				  ".$this->getField("KGID").",
				  '".$this->getField("UID")."',
				  '".$this->getField("tanggal")."',
				  '".$this->getField("nama")."',
				  '".$this->getField("keterangan")."',
				  '".$this->getField("link_file")."'				  
				)"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE kegiatan SET
				  tanggal = '".$this->getField("tanggal")."',
				  nama = '".$this->getField("nama")."',
				  keterangan = '".$this->getField("keterangan")."',				  
				  link_file = '".$this->getField("link_file")."'
				WHERE KGID = '".$this->getField("KGID")."'
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM kegiatan
                WHERE 
                  KGID = '".$this->getField("KGID")."'"; 
				  
		$this->query = $str;
//echo $str;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement = "")
	{
		$str = "
		SELECT
				i.KGID,
				i.UID,
				i.tanggal,
				i.nama as i_nama,
				i.keterangan,
				i.link_file,
				u.UID,
				u.nama as u_nama,
				u.level
		FROM kegiatan i, users u
		WHERE KGID IS NOT NULL AND u.UID = i.UID"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY KGID ASC ";
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

 
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
		SELECT
				i.KGID,
				i.UID,
				i.tanggal,
				i.nama as i_nama,
				i.keterangan,
				i.link_file,
				u.UID,
				u.nama as u_nama,
				u.level
		FROM kegiatan i, users u
		WHERE KGID IS NOT NULL AND u.UID = i.UID"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY KGID ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(i.KGID) AS ROWCOUNT FROM kegiatan i, users u WHERE i.KGID IS NOT NULL AND u.UID = i.UID"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(i.KGID) AS ROWCOUNT FROM kegiatan i, users u WHERE i.KGID IS NOT NULL AND u.UID = i.UID"; 
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
	

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BERDASARKAN TAHUN KEGIATAN ///////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

		function selectByParamsTahun($paramsArray=array(),$limit=-1,$from=-1, $statement = "", $reqTahun="")
		{
				$str = "
				SELECT
						i.KGID,
						i.UID,
						i.tanggal,
						i.nama as i_nama,
						i.keterangan,
						i.link_file,
						u.UID,
						u.nama as u_nama,
						u.level
				FROM kegiatan i, users u
				WHERE KGID IS NOT NULL AND u.UID = i.UID AND year(tanggal)= '".$reqTahun."' "; 
				
				while(list($key,$val) = each($paramsArray))
				{
					$str .= " AND $key = $val ";
				}
				
				$this->query = $str;
				$str .= " ORDER BY KGID ASC ";
				//echo $str;
						
				return $this->selectLimit($str,$limit,$from); 
		}
		
			
		function selectByParamsLikeTahun($paramsArray=array(),$limit=-1,$from=-1, $reqTahun="")
		{
				$str = "
				SELECT
						i.KGID,
						i.UID,
						i.tanggal,
						i.nama as i_nama,
						i.keterangan,
						i.link_file,
						u.UID,
						u.nama as u_nama,
						u.level
				FROM kegiatan i, users u
				WHERE KGID IS NOT NULL AND u.UID = i.UID AND year(tanggal)= '".$reqTahun."' "; 
				
				while(list($key,$val) = each($paramsArray))
				{
					$str .= " AND $key LIKE '%$val%' ";
				}
				
				$this->query = $str;
				$str .= " ORDER BY KGID ASC";
				//echo $str;
						
				return $this->selectLimit($str,$limit,$from); 
		}	
						/** 
						* Hitung jumlah record berdasarkan parameter (array). 
						* @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
						* @return long Jumlah record yang sesuai kriteria 
						**/ 
		function getCountByParamsTahun($paramsArray=array())
		{
				$str = "SELECT COUNT(i.KGID) AS ROWCOUNT FROM kegiatan i, users u WHERE i.KGID IS NOT NULL AND u.UID = i.UID  "; 
				while(list($key,$val)=each($paramsArray))
				{
					$str .= " AND $key = '$val' ";
				}
				
				$this->select($str); 
				//echo $str;
				if($this->firstRow()) 
					return $this->getField("ROWCOUNT"); 
				else 
					return 0; 
		}
		
		function getCountByParamsLikeTahun($paramsArray=array())
		{
				$str = "SELECT COUNT(i.KGID) AS ROWCOUNT FROM kegiatan i, users u WHERE i.KGID IS NOT NULL AND u.UID = i.UID"; 
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
		
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	// by ABM //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		

    function selectDistinctBulan($paramsArray=array(),$limit=-1,$from=-1, $statement = "")
	{
		$str = "
		SELECT DISTINCT
				month(tanggal) AS m_tanggal, 
				year(tanggal) AS y_tanggal
		FROM kegiatan
		WHERE KGID IS NOT NULL "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY tanggal DESC ";
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	function getCountPerBulan($paramsArray=array())
	{
			$str = "SELECT COUNT(KGID) AS ROWCOUNT FROM kegiatan 
					WHERE KGID IS NOT NULL "; 
			while(list($key,$val)=each($paramsArray))
			{
				$str .= " AND $key = '$val' ";
			}
			
			$this->select($str); 
			//echo $str;
			if($this->firstRow()) 
				return $this->getField("ROWCOUNT"); 
			else 
				return 0; 
	}
		
	
} 

?>
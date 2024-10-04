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

  class Informasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Informasi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("informasi_id", $this->getAdminNextId("informasi_id","pds_rekrutmen.informasi")); 
		$this->tanggal = date("Y-m-d H:i:s");

		$str = "INSERT INTO pds_rekrutmen.informasi(informasi_id, user_login_id, nama, tanggal, keterangan, status_halaman_depan, status_aktif, status_informasi)
				VALUES(
				  ".$this->getField("informasi_id").",
				  '".$this->getField("user_login_id")."',
				  '".$this->getField("nama")."',
				  ".$this->getField("tanggal").",
				  '".$this->getField("keterangan")."',
				  '".$this->getField("status_halaman_depan")."',
				  '".$this->getField("status_aktif")."',
				  '".$this->getField("status_informasi")."'				  
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.informasi SET
				  nama = '".$this->getField("nama")."',
				  keterangan = '".$this->getField("keterangan")."',
				  status_halaman_depan = '".$this->getField("status_halaman_depan")."',
				  status_aktif = '".$this->getField("status_aktif")."',
				  tanggal = ".$this->getField("tanggal")."
				WHERE informasi_id = '".$this->getField("informasi_id")."'
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }

    function updateStatusAktifOnly()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.informasi SET
				status_aktif = '".$this->getField("status_aktif")."'				  
				WHERE informasi_id = '".$this->getField("informasi_id")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function update_file()
	{
		$str = "UPDATE pds_rekrutmen.informasi SET
				  LINK_FILE = '".$this->getField("LINK_FILE")."'
				WHERE informasi_id = '".$this->getField("informasi_id")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.informasi
                WHERE 
                  informasi_id = '".$this->getField("informasi_id")."'"; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement = "", $order="")
	{
		$str = "SELECT I.INFORMASI_ID, I.USER_LOGIN_ID, I.NAMA AS I_NAMA, I.TANGGAL,
					   I.KETERANGAN AS I_KETERANGAN, I.STATUS_HALAMAN_DEPAN, I.STATUS_AKTIF,
					   I.STATUS_INFORMASI, I.LINK_FILE,
					   CASE WHEN I.STATUS_AKTIF = 1 THEN 'Aktif' ELSE 'Non-aktif' END AKTIF
				  FROM pds_rekrutmen.INFORMASI I
				 WHERE INFORMASI_ID IS NOT NULL
				 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from,$order); 
    }

	function selectAktifByParams($paramsArray=array(),$limit=-1,$from=-1,$statusInformasi="0",$orderByRand=false)
	{
		$str = "SELECT i.informasi_id, 
					i.user_login_id, 
					i.nama as i_nama, 
					i.tanggal, 
					i.keterangan, 
					i.status_halaman_depan, 
					i.status_aktif, 
					i.status_informasi, 
					i.link_file
				FROM pds_rekrutmen.informasi i
				WHERE informasi_id IS NOT NULL 
					AND i.status_aktif = '1' ";
		
		if($statusInformasi !== "-1")
			$str .= " AND i.status_informasi = '$statusInformasi' "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = $val ";
		}
		
		if($orderByRand == true)
			$str .= " ORDER BY RAND() ";
		else
			$str .= " ORDER BY i.status_halaman_depan DESC, informasi_id DESC";
		
		$this->query = $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "SELECT i.informasi_id, i.user_login_id, i.nama as i_nama, i.tanggal, i.keterangan, i.status_halaman_depan, i.status_aktif, i.status_informasi, i.link_file,
				FROM pds_rekrutmen.informasi i WHERE informasi_id IS NOT NULL "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY informasi_id DESC";
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(i.informasi_id) AS ROWCOUNT FROM pds_rekrutmen.informasi i WHERE i.informasi_id IS NOT NULL"; 
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
		$str = "SELECT COUNT(i.informasi_id) AS ROWCOUNT FROM pds_rekrutmen.informasi i WHERE i.informasi_id IS NOT NULL"; 
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
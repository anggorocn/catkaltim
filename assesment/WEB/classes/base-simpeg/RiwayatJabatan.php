<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel ESELON.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class RiwayatJabatan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RiwayatJabatan()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("RIWAYAT_JABATAN_ID", $this->getNextId("RIWAYAT_JABATAN_ID","".$this->db.".riwayat_jabatan")); 		

		$str = "
				INSERT INTO ".$this->db.".riwayat_jabatan(
					   RIWAYAT_JABATAN_ID, ESELON_ID, JABATAN
  					   , TMT_JABATAN, MASA_JAB_TAHUN, MASA_JAB_BULAN, PEGAWAI_ID) 
 			  	VALUES (
				  ".$this->getField("RIWAYAT_JABATAN_ID").",
				  ".$this->getField("ESELON_ID").",
				  '".$this->getField("JABATAN")."',
  				  ".$this->getField("TMT_JABATAN").",
  				  ".$this->getField("MASA_JAB_TAHUN").",
  				  ".$this->getField("MASA_JAB_BULAN").",
  				  ".$this->getField("PEGAWAI_ID")."
				)"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE ".$this->db.".riwayat_jabatan
				SET    
					  ESELON_ID			= ".$this->getField("ESELON_ID").",
				  	  JABATAN			= '".$this->getField("JABATAN")."',
  				  	  TMT_JABATAN		= ".$this->getField("TMT_JABATAN").",
  				  	  MASA_JAB_TAHUN	= ".$this->getField("MASA_JAB_TAHUN").",
  				  	  MASA_JAB_BULAN	= ".$this->getField("MASA_JAB_BULAN").",
  				  	  PEGAWAI_ID		= ".$this->getField("PEGAWAI_ID")."				   
				WHERE RIWAYAT_JABATAN_ID  = ".$this->getField("RIWAYAT_JABATAN_ID")."

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM ".$this->db.".riwayat_jabatan
                WHERE 
                  RIWAYAT_JABATAN_ID = ".$this->getField("RIWAYAT_JABATAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.RIWAYAT_JABATAN_ID ASC ")
	{
		$str = "
					SELECT 
						  RIWAYAT_JABATAN_ID, ESELON_ID, JABATAN
  						  , TMT_JABATAN, MASA_JAB_TAHUN, MASA_JAB_BULAN, PEGAWAI_ID
					FROM ".$this->db.".riwayat_jabatan A 
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
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT 
						  RIWAYAT_JABATAN_ID, ESELON_ID, JABATAN
  						  , TMT_JABATAN, MASA_JAB_TAHUN, MASA_JAB_BULAN, PEGAWAI_ID
					FROM ".$this->db.".riwayat_jabatan A 
					WHERE RIWAYAT_JABATAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM ".$this->db.".riwayat_jabatan A
		        WHERE 1=1 ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM ".$this->db.".riwayat_jabatan A
		        WHERE 1=1 ".$statement; 
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
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

  class Hukuman extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Hukuman()
	{
      $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("HUKUMAN_ID", $this->getNextId("HUKUMAN_ID","hukuman")); 

		$str = "INSERT INTO hukuman (
				   HUKUMAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, 
				   JENIS_HUKUMAN_ID, NO_SK, TANGGAL_SK, PEJABAT_PENETAP,
				   TMT_SK, KETERANGAN, BERLAKU, TINGKAT_HUKUMAN_ID, PERATURAN_ID,
				   TANGGAL_MULAI, TANGGAL_AKHIR, LAST_CREATE_USER, LAST_CREATE_DATE,
				   LAST_CREATE_SATKER
				   ) 
				VALUES (
				  '".$this->getField("HUKUMAN_ID")."',
				  '".$this->getField("PEGAWAI_ID")."',
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("JENIS_HUKUMAN_ID")."',
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("TMT_SK").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("BERLAKU").",
				  '".$this->getField("TINGKAT_HUKUMAN_ID")."',
				  ".$this->getField("PERATURAN_ID").",
				  ".$this->getField("TANGGAL_MULAI").",
				  ".$this->getField("TANGGAL_AKHIR").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_SATKER")."'
				)"; 
		
		$this->id= $this->getField("HUKUMAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE hukuman
				SET    
					   PEGAWAI_ID      		 = '".$this->getField("PEGAWAI_ID")."',
					   PEJABAT_PENETAP		 = '".$this->getField("PEJABAT_PENETAP")."',
					   PEJABAT_PENETAP_ID    = ".$this->getField("PEJABAT_PENETAP_ID").",
					   JENIS_HUKUMAN_ID      = ".$this->getField("JENIS_HUKUMAN_ID").",
					   TINGKAT_HUKUMAN_ID    = ".$this->getField("TINGKAT_HUKUMAN_ID").",
					   PERATURAN_ID          = ".$this->getField("PERATURAN_ID").",
					   NO_SK     			 = '".$this->getField("NO_SK")."',
					   TANGGAL_SK    		 = ".$this->getField("TANGGAL_SK").",
					   TMT_SK   			 = ".$this->getField("TMT_SK").",
					   KETERANGAN 			 = '".$this->getField("KETERANGAN")."',
					   BERLAKU 				 = ".$this->getField("BERLAKU").",
					   TANGGAL_MULAI		 = ".$this->getField("TANGGAL_MULAI").",
				  	   TANGGAL_AKHIR  		 = ".$this->getField("TANGGAL_AKHIR").",
					   LAST_UPDATE_USER		 = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE		 = ".$this->getField("LAST_UPDATE_DATE").",
					   LAST_UPDATE_SATKER	 = '".$this->getField("LAST_UPDATE_SATKER")."'
				WHERE  HUKUMAN_ID			 = '".$this->getField("HUKUMAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function update_format()
	{
		$str = "
				UPDATE hukuman
				SET
					   UKURAN= ".$this->getField("UKURAN").",
					   FORMAT= '".$this->getField("FORMAT")."'
				WHERE  HUKUMAN_ID = ".$this->getField("HUKUMAN_ID")." AND PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function selectByParamsBlob($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT encode(FOTO_BLOB, 'base64') FOTO_BLOB, FORMAT
				FROM hukuman WHERE HUKUMAN_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement."";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function delete()
	{
        $str = "DELETE FROM hukuman
                WHERE 
                  HUKUMAN_ID = ".$this->getField("HUKUMAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JENIS_HUKUMAN_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT 
				   CASE
					WHEN CURRENT_DATE <= A.TANGGAL_AKHIR AND CURRENT_DATE >= A.TANGGAL_MULAI
					THEN 'YA'
					ELSE 'TIDAK'
				   END STATUS_BERLAKU,
				   HUKUMAN_ID, PEGAWAI_ID,
				   JENIS_HUKUMAN_ID, NO_SK, TANGGAL_SK, TINGKAT_HUKUMAN_ID, 
				   TMT_SK, KETERANGAN, BERLAKU,
				   (CASE BERLAKU WHEN 1 THEN 'YA' WHEN 0 THEN 'TIDAK' END) LAKU,
                   (SELECT X.TINGKAT_HUKUMAN_ID FROM tingkat_hukuman X, jenis_hukuman Y WHERE X.TINGKAT_HUKUMAN_ID = Y.TINGKAT_HUKUMAN_ID
                    AND Y.JENIS_HUKUMAN_ID = A.JENIS_HUKUMAN_ID) TINGKAT_HUKUMAN_ID,
                   (SELECT X.NAMA FROM tingkat_hukuman X, jenis_hukuman Y WHERE X.TINGKAT_HUKUMAN_ID = Y.TINGKAT_HUKUMAN_ID 
                    AND Y.JENIS_HUKUMAN_ID = A.JENIS_HUKUMAN_ID ) NMTINGKATHUKUMAN,
                   (SELECT Y.NAMA FROM jenis_hukuman Y WHERE Y.JENIS_HUKUMAN_ID = A.JENIS_HUKUMAN_ID ) NMJENISHUKUMAN,
				   A.TANGGAL_MULAI, A.TANGGAL_AKHIR, B.NAMA NAMA_PEGAWAI
				FROM hukuman A
				LEFT JOIN ".$this->db.".rb_data_pegawai B ON B.IDPEG = A.PEGAWAI_ID
				WHERE 1 = 1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY JENIS_HUKUMAN_ID ASC";
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT HUKUMAN_ID, PEGAWAI_ID, (CASE PEJABAT_PENETAP_ID WHEN NULL THEN (SELECT PEJABAT_PENETAP_ID FROM PEJABAT_PENETAP X WHERE X.JABATAN = PEJABAT_PENETAP) ELSE a.PEJABAT_PENETAP_ID END) PEJABAT_PENETAP_ID, 
				   JENIS_HUKUMAN_ID, NO_SK, TANGGAL_SK, 
				   TMT_SK, KETERANGAN
				FROM hukuman a WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY JENIS_HUKUMAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JENIS_HUKUMAN_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(HUKUMAN_ID) AS ROWCOUNT FROM hukuman WHERE HUKUMAN_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(HUKUMAN_ID) AS ROWCOUNT FROM hukuman WHERE HUKUMAN_ID IS NOT NULL "; 
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
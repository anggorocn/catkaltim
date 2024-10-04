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

  class JabatanSyarat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JabatanSyarat()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_SYARAT_ID", $this->getNextId("JABATAN_SYARAT_ID","jabatan_syarat"));
		
		$str = "INSERT INTO jabatan_syarat (
				   JABATAN_SYARAT_ID, JABATAN_ID, KODE_ESELON, 
				   PANGKAT_MINIMAL, PANGKAT_MAKSIMAL, 
				   SYARAT) 
				VALUES (
				  '".$this->getField("JABATAN_SYARAT_ID")."',
				  '".$this->getField("JABATAN_ID")."',
				  '".$this->getField("KODE_ESELON")."',
				  '".$this->getField("PANGKAT_MINIMAL")."',
				  '".$this->getField("PANGKAT_MAKSIMAL")."',
				  '".$this->getField("SYARAT")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("JABATAN_SYARAT_ID");
		return $this->execQuery($str);
    }
	
	function insertSyarat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_SYARAT_ID", $this->getNextId("JABATAN_SYARAT_ID","jabatan_syarat"));
		
		$str = "INSERT INTO jabatan_syarat (
				   JABATAN_SYARAT_ID, JABATAN_ID, SYARAT) 
				VALUES (
				  '".$this->getField("JABATAN_SYARAT_ID")."',
				  '".$this->getField("JABATAN_ID")."',
				  '".$this->getField("SYARAT")."'
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("JABATAN_SYARAT_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE jabatan_syarat
				SET
				   SYARAT= '".$this->getField("SYARAT")."'
				WHERE JABATAN_ID= '".$this->getField("JABATAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updatePath()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE jabatan_syarat
				SET
				   PATH  = '".$this->getField("PATH")."'
				WHERE JABATAN_ID= '".$this->getField("JABATAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE jabatan_syarat
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  JABATAN_SYARAT_ID = '".$this->getField("JABATAN_SYARAT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jabatan_syarat
                WHERE 
                  JABATAN_SYARAT_ID = '".$this->getField("JABATAN_SYARAT_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParamsBlob($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT FOTO_BLOB, FORMAT
				FROM jabatan_syarat WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement."";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT A.JABATAN_SYARAT_ID,
				 A.JABATAN_ID, A.SYARAT, A.PATH,
				 B.PANGKAT_MINIMAL, C.KODE, B.DIKLAT_TEKNIS_ID, D.NAMA DIKLAT_TEKNIS,
				 B.DIKLAT_STRUKTURAL_ID, E.diklat DIKLAT_STRUKTURAL, B.SKP, B.PK,
				 B.PENDIDIKAN_ID_PU, F.tktpend PENDIDIKAN_PU,
				 B.KOMPETENSI_DASAR, B.KOMPETENSI_BIDANG, B.KOMPETENSI_TEKNIK, B.KESEHATAN
				FROM jabatan_syarat A
				INNER JOIN jabatan B ON A.JABATAN_ID = B.JABATAN_ID
				LEFT JOIN pangkat C ON B.PANGKAT_MINIMAL = C.PANGKAT_ID
				LEFT JOIN diklat_teknis D ON D.DIKLAT_TEKNIS_ID = B.DIKLAT_TEKNIS_ID
				LEFT JOIN pola_karir_pu.r_diklatpim E ON E.kddiklat = B.DIKLAT_STRUKTURAL_ID
				LEFT JOIN pola_karir_pu.r_tingkat_pendidikan F ON B.PENDIDIKAN_ID_PU = F.kdtktpend
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY JABATAN_SYARAT_ID ASC";
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT JABATAN_SYARAT_ID, PANGKAT_MAKSIMAL, 
                   JABATAN_ID, KODE_ESELON, PANGKAT_MINIMAL, SYARAT, PATH
                FROM jabatan_syarat A                
                WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY SYARAT ASC";
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(JABATAN_SYARAT_ID) AS ROWCOUNT FROM jabatan_syarat WHERE JABATAN_SYARAT_ID IS NOT NULL "; 
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
		$str = "SELECT COUNT(JABATAN_SYARAT_ID) AS ROWCOUNT FROM jabatan_syarat WHERE JABATAN_SYARAT_ID IS NOT NULL "; 
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
	
	function getMaxIdTree($jabatan_syarat_id)
	{
		$str = "SELECT JABATAN_GENERATE('".$jabatan_syarat_id."') ROWCOUNT FROM DUAL"; 

		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>
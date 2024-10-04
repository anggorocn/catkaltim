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

  class JabatanEselonAtribut extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JabatanEselonAtribut()
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
		$this->setField("JABATAN_ESELON_ATRIBUT_ID", $this->getNextId("JABATAN_ESELON_ATRIBUT_ID",$this->db.".jabatan_eselon_atribut")); 

		$str = "INSERT INTO ".$this->db.".jabatan_eselon_atribut (
				   JABATAN_ESELON_ATRIBUT_ID, TAHUN, ESELON_ID, 
				   SATUAN_KERJA_ID, ATRIBUT_ID, ATRIBUT_PARENT_ID, ASPEK_ID)
				VALUES (
				  ".$this->getField("JABATAN_ESELON_ATRIBUT_ID").",
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("ESELON_ID")."',
				  '".$this->getField("SATUAN_KERJA_ID")."',
				  '".$this->getField("ATRIBUT_ID")."',
				  '".$this->getField("ATRIBUT_PARENT_ID")."',
				  '".$this->getField("ASPEK_ID")."'
				)"; 
				  //'".$this->getField("PATH")."'
		$this->query = $str;
		$this->id = $this->getField("JABATAN_ESELON_ATRIBUT_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE jabatan_eselon_atribut
				SET    
					   TAHUN= '".$this->getField("TAHUN")."',
					   ESELON_ID= '".$this->getField("ESELON_ID")."',
					   SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
					   ATRIBUT_ID= ".$this->getField("ATRIBUT_ID").",
					   ATRIBUT_PARENT_ID= ".$this->getField("ATRIBUT_PARENT_ID")."
				WHERE  JABATAN_ESELON_ATRIBUT_ID= '".$this->getField("JABATAN_ESELON_ATRIBUT_ID")."'
				"; //PATH= '".$this->getField("PATH")."'
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }
	
	function updateFormat()
	{
		$str = "
				UPDATE jabatan_eselon_atribut
				SET
					   UKURAN= ".$this->getField("UKURAN").",
					   FORMAT= '".$this->getField("FORMAT")."'
				WHERE  JABATAN_ESELON_ATRIBUT_ID = '".$this->getField("JABATAN_ESELON_ATRIBUT_ID")."' AND TAHUN = '".$this->getField("TAHUN")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM ".$this->db.".jabatan_eselon_atribut
                WHERE 
                  ESELON_ID= '".$this->getField("ESELON_ID")."' AND
				  SATUAN_KERJA_ID= '".$this->getField("SATUAN_KERJA_ID")."' AND
				  TAHUN= '".$this->getField("TAHUN")."' AND
				  ATRIBUT_ID LIKE '".$this->getField("ATRIBUT_ID")."%'
				"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","ATRIBUT_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
		SELECT 
		   JABATAN_ESELON_ATRIBUT_ID, TAHUN, ESELON_ID, SATUAN_KERJA_ID, ATRIBUT_ID, ATRIBUT_PARENT_ID, ASPEK_ID
		FROM ".$this->db.".jabatan_eselon_atribut A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJabatanUnitKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY S.KODE_UNKER, A.POSITION ASC")
	{
		$str = "
		SELECT 
		S.KODE_UNKER JABATAN_ID, A.POSITION JABATAN_NAMA
		FROM ".$this->db.".user A 
		LEFT JOIN
		(
			SELECT
			A.ID, A.KODE_UNKER
			FROM
			(
				SELECT 
				A.ID,
				".$this->db.".GetAncestry
				(
					(
						CASE A.SUBBAG_ID WHEN '0' THEN 
						(
							CASE A.SUBDIT_ID WHEN '0' THEN 
							(
								CASE A.DITJEN_ID WHEN '0' THEN 
								(
									CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END
								)
								ELSE A.DITJEN_ID 
								END
							)
							ELSE A.SUBDIT_ID 
							END
						)
						ELSE A.SUBBAG_ID 
						END
					) 
				) KODE_UNKER
				FROM ".$this->db.".user A
				WHERE 1=1
			) A
			WHERE 1=1
		) S ON S.ID = A.ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","ATRIBUT_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM jabatan_eselon_atribut WHERE JABATAN_ESELON_ATRIBUT_ID IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>
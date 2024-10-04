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

  class Penggalian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Penggalian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENGGALIAN_ID", $this->getNextId("PENGGALIAN_ID","penggalian")); 

		$str = "INSERT INTO penggalian (
				   PENGGALIAN_ID, TAHUN, KODE, NAMA, KETERANGAN, STATUS_GROUP, STATUS_CBI, STATUS_CAT, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("PENGGALIAN_ID").",
				  ".$this->getField("TAHUN").",
				  '".$this->getField("KODE")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("STATUS_GROUP").",
				  ".$this->getField("STATUS_CBI").",
				  ".$this->getField("STATUS_CAT").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("PENGGALIAN_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }
	
	function updateDinamis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   ".$this->getField("FIELD")." = ".$this->getField("FIELD_VALUE")."
				WHERE  ".$this->getField("FIELD_ID")." = ".$this->getField("FIELD_ID_VALUE")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE penggalian SET
				  TAHUN= ".$this->getField("TAHUN").",
				  KODE= '".$this->getField("KODE")."',
				  NAMA= '".$this->getField("NAMA")."',
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
				  STATUS_GROUP= ".$this->getField("STATUS_GROUP").",
				  STATUS_CBI= ".$this->getField("STATUS_CBI").",
				  STATUS_CAT= ".$this->getField("STATUS_CAT").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE PENGGALIAN_ID = ".$this->getField("PENGGALIAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM penggalian
                WHERE 
                  PENGGALIAN_ID = ".$this->getField("PENGGALIAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParamsTahun($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder=" ORDER BY TAHUN DESC")
	{
		$str = "SELECT TAHUN
				FROM penggalian A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP  BY TAHUN ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY PENGGALIAN_ID ASC")
	{
		$str = "SELECT PENGGALIAN_ID, TAHUN, KODE, NAMA, KETERANGAN, STATUS_GROUP, STATUS_CBI, STATUS_CAT,
		(CASE STATUS_GROUP WHEN '1' THEN 'Ya' ELSE 'Tidak' END) STATUS_GROUP_NAMA,
		(CASE STATUS_CBI WHEN '1' THEN 'Ya' ELSE 'Tidak' END) STATUS_CBI_NAMA,
		(CASE STATUS_CAT WHEN '1' THEN 'Ya' ELSE 'Tidak' END) STATUS_CAT_NAMA
		FROM penggalian A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsJadwalTes($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqId="", $sOrder="ORDER BY PENGGALIAN_ID ASC")
	{
		$str = "
		SELECT A.PENGGALIAN_ID, A.TAHUN, A.KODE, A.NAMA, A.KETERANGAN, A.STATUS_GROUP
		FROM penggalian A 
		INNER JOIN
		(
			SELECT CAST(TO_CHAR(A.TANGGAL_TES, 'YYYY') AS NUMERIC) TAHUN
			FROM jadwal_tes A
			WHERE A.JADWAL_TES_ID = ".$reqId."
			GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY')
		) B ON A.TAHUN = B.TAHUN
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

    function selectByParamsJadwalTesNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $reqId="", $sOrder="ORDER BY PENGGALIAN_ID ASC")
	{
		$str = "
		SELECT A.PENGGALIAN_ID, A.TAHUN, A.KODE, A.NAMA, A.KETERANGAN, A.STATUS_GROUP 
		FROM penggalian A 
		where penggalian_id = 0
		union all
		SELECT A.PENGGALIAN_ID, A.TAHUN, A.KODE, A.NAMA, A.KETERANGAN, A.STATUS_GROUP 
		FROM penggalian A 
		INNER JOIN ( 
		SELECT DISTINCT d.penggalian_id
		FROM jadwal_tes A 
		inner join formula_eselon B on a.formula_eselon_id = b.formula_eselon_id
		inner join formula_atribut C on b.formula_eselon_id = c.formula_eselon_id
		left join atribut_penggalian D on c.formula_atribut_id = d.formula_atribut_id
		WHERE A.JADWAL_TES_ID = ".$reqId.") B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		where a.penggalian_id <> 0
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM penggalian WHERE 1=1 ".$statement;
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
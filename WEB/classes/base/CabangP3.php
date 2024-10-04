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
  * Entity-base class untuk mengimplementasikan tabel CABANG_P3.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class CabangP3 extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function CabangP3()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CABANG_P3_ID", $this->getAdminNextId("CABANG_P3_ID","pds_rekrutmen.CABANG_P3"));
		//'".$this->getField("FOTO")."',  FOTO,
		$str = "
				INSERT INTO pds_rekrutmen.CABANG_P3 (
				   CABANG_P3_ID, NAMA, KELAS_PELABUHAN, KODE_CABANG, KETERANGAN, TELEPON, ALAMAT, WILAYAH_ID, LAST_CREATE_USER, LAST_CREATE_DATE
				   ) 
 			  	VALUES (
				  ".$this->getField("CABANG_P3_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KELAS_PELABUHAN")."',
				  '".$this->getField("KODE_CABANG")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("TELEPON")."',
				  '".$this->getField("ALAMAT")."',
				  ".$this->getField("WILAYAH_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id = $this->getField("CABANG_P3_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.CABANG_P3
				SET    
					   NAMA= '".$this->getField("NAMA")."',
				  	   WILAYAH_ID		= ".$this->getField("WILAYAH_ID").",
					   KELAS_PELABUHAN	= '".$this->getField("KELAS_PELABUHAN")."',
					   KODE_CABANG		= '".$this->getField("KODE_CABANG")."',
					   KETERANGAN		= '".$this->getField("KETERANGAN")."',
				  	   TELEPON			= '".$this->getField("TELEPON")."',
				  	   ALAMAT			= '".$this->getField("ALAMAT")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  CABANG_P3_ID     = '".$this->getField("CABANG_P3_ID")."'
			 "; //FOTO= '".$this->getField("FOTO")."',
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.CABANG_P3
                WHERE 
                  CABANG_P3_ID = ".$this->getField("CABANG_P3_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY NAMA ASC")
	{
		$str = "
				SELECT 
				CABANG_P3_ID, NAMA, KELAS_PELABUHAN, KETERANGAN, KODE_CABANG, ALAMAT, TELEPON, WILAYAH_ID
				FROM pds_rekrutmen.CABANG_P3 A
				WHERE 1 = 1 
				"; 
		//, FOTO
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $sOrder="ORDER BY NAMA DESC")
	{
		$str = "
				SELECT 
					A.CABANG_P3_ID, KELAS_PELABUHAN, A.WILAYAH_ID, ALAMAT, TELEPON,
					CASE WHEN KELAS_PELABUHAN = '1' THEN 'I' WHEN KELAS_PELABUHAN = '2' THEN 'II' WHEN KELAS_PELABUHAN = '3' THEN 'III' ELSE '' END KELAS_PELABUHAN_NAMA,
					A.NAMA, A.KETERANGAN, KODE_CABANG, B.NAMA WILAYAH_NAMA
				FROM pds_rekrutmen.CABANG_P3 A
				LEFT JOIN pds_rekrutmen.WILAYAH B ON A.WILAYAH_ID = B.WILAYAH_ID
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
	
	function selectByParamsJumlah($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.NAMA", $groupBy="GROUP BY A.CABANG_P3_ID")
	{
		$str = "SELECT A.CABANG_P3_ID, A.NAMA, COUNT(1) AS TOTAL FROM pds_rekrutmen.CABANG_P3 A
		INNER JOIN pds_rekrutmen.LOWONGAN B ON B.CABANG_P3_ID=A.CABANG_P3_ID AND B.STATUS = 1 AND B.STATUS_SELESAI = 0
		WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$groupBy." ".$order;	
		$this->query = $str;	
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT CABANG_P3_ID, NAMA, KELAS_PELABUHAN, KETERANGAN
				FROM pds_rekrutmen.CABANG_P3
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
				FROM pds_rekrutmen.CABANG_P3 A
				LEFT JOIN pds_rekrutmen.WILAYAH B ON A.WILAYAH_ID = B.WILAYAH_ID
				WHERE 1=1 
				".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(CABANG_P3_ID) AS ROWCOUNT FROM pds_rekrutmen.CABANG_P3
		        WHERE CABANG_P3_ID IS NOT NULL ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(CABANG_P3_ID) AS ROWCOUNT FROM pds_rekrutmen.CABANG_P3
		        WHERE CABANG_P3_ID IS NOT NULL ".$statement; 
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
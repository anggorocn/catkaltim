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
  * Entity-base class untuk mengimplementasikan tabel TUGAS_BELAJAR_LAPOR.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class TugasBelajarLapor extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TugasBelajarLapor()
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
		$this->setField("TUGAS_BELAJAR_LAPOR_ID", $this->getNextId("TUGAS_BELAJAR_LAPOR_ID","tugas_belajar_lapor")); 		

		$str = "
				INSERT INTO tugas_belajar_lapor (
				    TUGAS_BELAJAR_LAPOR_ID, TUGAS_BELAJAR_ID, KETERANGAN, STATUS_BELAJAR, SEMESTER, TANGGAL, LAST_CREATE_USER, 
					LAST_CREATE_DATE, LAST_CREATE_SATKER, TIPE_TUGAS)
 			  	VALUES (
				  '".$this->getField("TUGAS_BELAJAR_LAPOR_ID")."',
				  '".$this->getField("TUGAS_BELAJAR_ID")."',
  				  '".$this->getField("KETERANGAN")."',
   				  '".$this->getField("STATUS_BELAJAR")."',
   				  '".$this->getField("SEMESTER")."',
				  ".$this->getField("TANGGAL").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_SATKER")."',
				  ".$this->getField("TIPE_TUGAS")."
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE tugas_belajar_lapor
				SET    
					TUGAS_BELAJAR_ID= ".$this->getField("TUGAS_BELAJAR_ID").",
					KETERANGAN= '".$this->getField("KETERANGAN")."',
					STATUS_BELAJAR= '".$this->getField("STATUS_BELAJAR")."',
					SEMESTER= '".$this->getField("SEMESTER")."',
					TANGGAL= ".$this->getField("TANGGAL").",
					LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
					LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
					LAST_UPDATE_SATKER= '".$this->getField("LAST_UPDATE_SATKER")."',
					TIPE_TUGAS=	".$this->getField("TIPE_TUGAS")."
				WHERE  TUGAS_BELAJAR_LAPOR_ID  = '".$this->getField("TUGAS_BELAJAR_LAPOR_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE tugas_belajar_lapor A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE TUGAS_BELAJAR_LAPOR_ID = ".$this->getField("TUGAS_BELAJAR_LAPOR_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM tugas_belajar_lapor
                WHERE 
                  TUGAS_BELAJAR_LAPOR_ID = ".$this->getField("TUGAS_BELAJAR_LAPOR_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.TUGAS_BELAJAR_LAPOR_ID ASC ")
	{
		$str = "
				SELECT TUGAS_BELAJAR_LAPOR_ID, A.TUGAS_BELAJAR_ID, KETERANGAN, A.STATUS_BELAJAR, SEMESTER, TANGGAL, 
				A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, A.LAST_CREATE_SATKER, A.LAST_UPDATE_SATKER,
				CASE 
				   	WHEN A.STATUS_BELAJAR = '1' THEN 'Tugas Belajar'
					WHEN A.STATUS_BELAJAR = '2' THEN 'Perpanjangan Tugas Belajar'
					WHEN A.STATUS_BELAJAR = '3' THEN 'Pengaktifan Tugas Belajar'
				   END KETERANGAN_STATUS_BELAJAR
				FROM tugas_belajar_lapor A
				LEFT JOIN tugas_belajar B ON B.TUGAS_BELAJAR_ID = A.TUGAS_BELAJAR_ID
				WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT
						   TUGAS_BELAJAR_LAPOR_ID, TUGAS_BELAJAR_ID, KETERANGAN, STATUS_BELAJAR
					FROM tugas_belajar_lapor A WHERE TUGAS_BELAJAR_LAPOR_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.TUGAS_BELAJAR_LAPOR_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TUGAS_BELAJAR_LAPOR_ID) AS ROWCOUNT FROM tugas_belajar_lapor A
		        WHERE 1=1 ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TUGAS_BELAJAR_LAPOR_ID) AS ROWCOUNT FROM tugas_belajar_lapor A
		        WHERE TUGAS_BELAJAR_LAPOR_ID IS NOT NULL ".$statement; 
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
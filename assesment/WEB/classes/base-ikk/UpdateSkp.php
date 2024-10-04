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

  class UpdateSkp extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UpdateSkp()
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
		$this->setField("riwayat_skp_id", $this->getNextId("riwayat_skp_id","simpeg.riwayat_skp")); 
		$str = "INSERT INTO simpeg.riwayat_skp (
				   riwayat_skp_id, skp_TAHUN, nilai_skp, pegawai_id
				)
				VALUES (
				  ".$this->getField("riwayat_skp_id").",
				  '".$this->getField("skp_TAHUN")."',
				  '".$this->getField("nilai_skp")."',
				  ".$this->getField("pegawai_id")."
				)"; 
				
		$this->query = $str;
		// echo $str; exit;
		$this->id = $this->getField("TAHUN");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE simpeg.riwayat_skp
				SET
				  	nilai_skp= '".$this->getField("nilai_skp")."',
				  	skp_TAHUN= '".$this->getField("skp_TAHUN")."',
				  	pegawai_id= '".$this->getField("pegawai_id")."'
				 WHERE riwayat_skp_id= '".$this->getField("riwayat_skp_id")."'
				"; 
				$this->query = $str;
		// echo $str; exit;
		return $this->execQuery($str);
    } 
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY pegawai_id ASC")
	{
		$str = "
		SELECT
			*
		FROM simpeg.pegawai
		WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsCekData($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY pegawai_id ASC")
	{
		$str = "
		SELECT
			*
		FROM simpeg.riwayat_skp
		WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;		exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM toleransi_talent_pool A
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

  } 
?>
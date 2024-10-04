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

  class ToleransiTalentPool extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function ToleransiTalentPool()
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
		$str = "INSERT INTO toleransi_talent_pool (
				   TAHUN, SKP_X0, GM_Y0, SKP_X1, GM_Y1, SKP_X2, GM_Y2,
				   SKP_Y0, GM_X0, SKP_Y1, GM_X1, SKP_Y2, GM_X2
				)
				VALUES (
				  ".$this->getField("TAHUN").",
				  ".$this->getField("SKP_X0").",
				  ".$this->getField("GM_Y0").",
				  ".$this->getField("SKP_X1").",
				  ".$this->getField("GM_Y1").",
				  ".$this->getField("SKP_X2").",
				  ".$this->getField("GM_Y2").",
				  0, 0, 0, 0, 0, 0
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("TAHUN");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE toleransi_talent_pool
				SET
					SKP_X0= ".$this->getField("SKP_X0").",
				  	GM_Y0= ".$this->getField("GM_Y0").",
					SKP_X1= ".$this->getField("SKP_X1").",
				  	GM_Y1= ".$this->getField("GM_Y1").",
					SKP_X2= ".$this->getField("SKP_X2").",
				  	GM_Y2= ".$this->getField("GM_Y2")."
				 WHERE TAHUN= '".$this->getField("TAHUN")."'
				"; 
				$this->query = $str;
				//echo $str;exit;
		return $this->execQuery($str);
    } 
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY TAHUN ASC")
	{
		$str = "
		SELECT
			TAHUN, TOLERANSI_Y, TOLERANSI_X
			, COALESCE(SKP_X0,0) SKP_X0, COALESCE(SKP_Y0,0) SKP_Y0, COALESCE(GM_X0,0) GM_X0, COALESCE(GM_Y0,0) GM_Y0
			, COALESCE(SKP_X1,0) SKP_X1, COALESCE(SKP_Y1,0) SKP_Y1, COALESCE(GM_X1,0) GM_X1, COALESCE(GM_Y1,0) GM_Y1
			, COALESCE(SKP_X2,0) SKP_X2, COALESCE(SKP_Y2,0) SKP_Y2, COALESCE(GM_X2,0) GM_X2, COALESCE(GM_Y2,0) GM_Y2
		FROM toleransi_talent_pool A
		WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
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
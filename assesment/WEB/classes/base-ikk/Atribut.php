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

  class Atribut extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Atribut()
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
		$this->setField("ATRIBUT_ID", $this->getNextId("ATRIBUT_ID","".$this->db.".atribut"));
		
		$str = "INSERT INTO ".$this->db.".atribut (
				   ATRIBUT_ID, ATRIBUT_ID_PARENT, ASPEK_ID, TAHUN, NAMA, BOBOT, NILAI_STANDAR, NILAI_ES2, NILAI_ES3, NILAI_ES4)
				VALUES (
				  (SELECT ".$this->db.".ATRIBUT_GENERATE('".$this->getField("ATRIBUT_ID_PARENT")."', '".$this->getField("TAHUN")."')),
				  '".$this->getField("ATRIBUT_ID_PARENT")."',
				  ".$this->getField("ASPEK_ID").",
				  ".$this->getField("TAHUN").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("BOBOT").",
				  ".$this->getField("NILAI_STANDAR").",
				  ".$this->getField("NILAI_ES2").",
				  ".$this->getField("NILAI_ES3").",
				  ".$this->getField("NILAI_ES4")."
				)"; 
		//echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("ATRIBUT_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE ".$this->db.".atribut
				SET
				   ASPEK_ID= ".$this->getField("ASPEK_ID").",
				   TAHUN= ".$this->getField("TAHUN").",
				   NAMA= ".$this->getField("NAMA").",
				   BOBOT= ".$this->getField("BOBOT").",
				   NILAI_STANDAR= ".$this->getField("NILAI_STANDAR").",
				   NILAI_ES2= ".$this->getField("NILAI_ES2").",
				   NILAI_ES3= ".$this->getField("NILAI_ES3").",
				   NILAI_ES4= ".$this->getField("NILAI_ES4")."
				WHERE ATRIBUT_ID= ".$this->getField("ATRIBUT_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE ATRIBUT
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  ATRIBUT_ID =".$this->getField("ATRIBUT_ID")."
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM ATRIBUT
                WHERE 
                  ATRIBUT_ID = ".$this->getField("ATRIBUT_ID").""; 
				  
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
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID
				, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Komptensi' END ASPEK_NAMA
				, A.TAHUN, A.NAMA, A.KETERANGAN, A.BOBOT, A.NILAI_STANDAR, A.NILAI_ES2, A.NILAI_ES3, A.NILAI_ES4
				FROM ".$this->db.".atribut A
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
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM ATRIBUT A
		WHERE 1=1 "; 
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

  } 
?>
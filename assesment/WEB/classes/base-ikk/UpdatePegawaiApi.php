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

  class UpdatePegawaiApi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UpdatePegawaiApi()
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
		$this->setField("pegawai_id", $this->getNextId("pegawai_id","simpeg.pegawai")); 
		$str = "INSERT INTO simpeg.pegawai (
			pegawai_id,
			nip_baru,
			nama,
			tempat_lahir,
			tgl_lahir,
			SATKER_ID,
			jenis_kelamin,
			alamat,
			hp,
			email,
			last_jabatan,
			last_eselon_id,
			status_pensiun
				)
				VALUES (
				  ".$this->getField("pegawai_id").",
				  '".$this->getField("nip_baru")."',
				  '".$this->getField("nama")."',
				  '".$this->getField("tempat_lahir")."',
				  '".$this->getField("tgl_lahir")."',
				  '".$this->getField("SATKER_ID")."',
				  '".$this->getField("jenis_kelamin")."',
				  '".$this->getField("alamat")."',
				  '".$this->getField("hp")."',
				  '".$this->getField("email")."',
				  '".$this->getField("last_jabatan")."',
				  '".$this->getField("last_eselon_id")."',
				  status_pensiun= null
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
				UPDATE simpeg.pegawai
				SET
				  	nip_baru= '".$this->getField("nip_baru")."',
				  	nama= '".$this->getField("nama")."',
				  	tempat_lahir= '".$this->getField("tempat_lahir")."',
				  	tgl_lahir= '".$this->getField("tgl_lahir")."',
				  	SATKER_ID= '".$this->getField("SATKER_ID")."',
				  	jenis_kelamin= '".$this->getField("jenis_kelamin")."',
				  	alamat= '".$this->getField("alamat")."',
				  	hp= '".$this->getField("hp")."',
				  	email= '".$this->getField("email")."',
				  	last_jabatan= '".$this->getField("last_jabatan")."',
				  	last_eselon_id= '".$this->getField("last_eselon_id")."',
				  	status_pensiun= null
				 WHERE pegawai_id= '".$this->getField("pegawai_id")."'
				"; 
				$this->query = $str;
		// echo $str; exit;
		return $this->execQuery($str);
    } 

    function updatepensiun()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE simpeg.pegawai
				SET
				  	status_pensiun=1
				 WHERE 1=1
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

    function selectByParamsCekData($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY pegawai_id ASC")
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
		// echo $str;		exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
  } 
?>
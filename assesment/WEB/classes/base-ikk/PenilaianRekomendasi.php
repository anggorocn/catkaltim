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

  class PenilaianRekomendasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PenilaianRekomendasi()
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
		$this->setField("PENILAIAN_REKOMENDASI_ID", $this->getNextId("PENILAIAN_REKOMENDASI_ID","penilaian_rekomendasi"));
		
		$str = "INSERT INTO penilaian_rekomendasi (
				   PENILAIAN_REKOMENDASI_ID, JADWAL_TES_ID, PEGAWAI_ID, TIPE, KETERANGAN,NO_URUT) 
				VALUES (
				  ".$this->getField("PENILAIAN_REKOMENDASI_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("TIPE")."',
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("NO_URUT")."
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("PENILAIAN_REKOMENDASI_ID");
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian_rekomendasi
				SET
				   KETERANGAN= '".$this->getField("KETERANGAN")."'
				WHERE JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID")."
				AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 

    function deletemode()
	{
        $str = "
        DELETE FROM penilaian_rekomendasi
        WHERE 
        PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
        AND JADWAL_TES_ID = ".$this->getField("JADWAL_TES_ID")."
        AND TIPE = '".$this->getField("TIPE")."'
        "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_REKOMENDASI_ID ASC")
	{
		$str = "
		SELECT A.PENILAIAN_REKOMENDASI_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.KETERANGAN,A.TIPE,A.NO_URUT
		FROM penilaian_rekomendasi A
		WHERE 1=1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM penilaian_rekomendasi A
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
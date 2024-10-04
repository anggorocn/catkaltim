<? 
/* *******************************************************************************************************
MODUL NAME 			: PERPUSTAKAAN
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

  class PenilaianKompetensi extends Entity{ 
	var $query;
	var $db;

	/**
	* Class constructor.
	**/
	function PenilaianKompetensi()
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
		$this->setField("penilaian_kompetensi_penilaian_ID", $this->getNextId("penilaian_kompetensi_penilaian_ID","penilaian_kompetensi_penilaian"));

		$str= "
		INSERT INTO penilaian_kompetensi_penilaian
		(
			penilaian_kompetensi_penilaian_ID, penilaian, penilaian_kompetensi_dasar_id, keterangan, pegawai_id, jadwal_tes_id
		) 
		VALUES 
		( 
			".$this->getField("penilaian_kompetensi_penilaian_ID").",
			'".$this->getField("penilaian")."',
			'".$this->getField("penilaian_kompetensi_dasar_id")."',
			'".$this->getField("keterangan")."',
			".$this->getField("pegawai_id").",
			".$this->getField("jadwal_tes_id")."
		)
		"; 
		$this->id= $this->getField("penilaian_kompetensi_penilaian_ID");
		$this->query= $str;
		// echo $str; exit;
		return $this->execQuery($str);
    }

     function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian_kompetensi_penilaian
				SET
				   penilaian= '".$this->getField("penilaian")."',
				   keterangan= '".$this->getField("keterangan")."'
				WHERE penilaian_kompetensi_penilaian_id= '".$this->getField("penilaian_kompetensi_penilaian_id")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 

    function delete()
	{
        $str= "
        DELETE FROM PERMOHONAN_FILE
        WHERE 
        PERMOHONAN_FILE_ID= ".$this->getField("PERMOHONAN_FILE_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    
    function selectByParamsDasar($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		a.*
		FROM penilaian_kompetensi_penilaian a
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDasarDataPsikologi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		select a.* from penilaian_detil a
	left join penilaian b on a.penilaian_id=b.penilaian_id
	WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
} 
?>
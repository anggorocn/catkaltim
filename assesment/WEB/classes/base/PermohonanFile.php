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

  class PermohonanFile extends Entity{ 
	var $query;
	var $db;

	/**
	* Class constructor.
	**/
	function PermohonanFile()
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
		$this->setField("PERMOHONAN_FILE_ID", $this->getNextId("PERMOHONAN_FILE_ID","PERMOHONAN_FILE"));

		$str= "
		INSERT INTO PERMOHONAN_FILE
		(
			PERMOHONAN_FILE_ID, PEGAWAI_ID, PERMOHONAN_TABLE_NAMA, PERMOHONAN_TABLE_ID, 
			NAMA, TIPE, LINK_FILE, KETERANGAN, LAST_USER, LAST_DATE, 
			USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_DATE, USER_LOGIN_CREATE_ID
		) 
		VALUES 
		( 
			".$this->getField("PERMOHONAN_FILE_ID").",
			'".$this->getField("PEGAWAI_ID")."',
			'".$this->getField("PERMOHONAN_TABLE_NAMA")."',
			".$this->getField("PERMOHONAN_TABLE_ID").",
			'".$this->getField("NAMA")."',
			'".$this->getField("TIPE")."',
			'".$this->getField("LINK_FILE")."',
			'".$this->getField("KETERANGAN")."',
			'".$this->getField("LAST_USER")."',
			NOW(),
			".$this->getField("USER_LOGIN_ID").",
			".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			NOW(),
			".$this->getField("USER_LOGIN_CREATE_ID")."
		)
		"; 
		$this->id= $this->getField("PERMOHONAN_FILE_ID");
		$this->query= $str;
		// echo $str;exit();
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PERMOHONAN_FILE_ID DESC")
	{
		$str = "
		SELECT
			PERMOHONAN_FILE_ID, PEGAWAI_ID, PERMOHONAN_TABLE_NAMA, PERMOHONAN_TABLE_ID, 
			NAMA, TIPE, LINK_FILE, KETERANGAN, STATUS, LAST_USER, LAST_DATE, 
			USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_DATE, USER_LOGIN_CREATE_ID
			FROM PERMOHONAN_FILE A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM PERMOHONAN_FILE A
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

    function selectByParamsCari($paramsArray=array(),$limit=-1,$from=-1, $ujianid, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA, A.EMAIL, A.LAST_JABATAN, D1.NAMA SATKER
		, info_bukti_file(JP.JADWAL_AWAL_TES_SIMULASI_ID, A.PEGAWAI_ID) BUKTI_PENDUKUNG
		FROM jadwal_awal_tes_simulasi_pegawai JP
		INNER JOIN simpeg.pegawai A ON A.PEGAWAI_ID = JP.PEGAWAI_ID
		LEFT JOIN simpeg.satker D1 ON A.SATKER_ID = D1.SATKER_ID
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

    function getCountByParamsCari($paramsArray=array(), $ujianid, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM jadwal_awal_tes_simulasi_pegawai JP
		INNER JOIN simpeg.pegawai A ON A.PEGAWAI_ID = JP.PEGAWAI_ID
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
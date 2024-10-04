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
  * Entity-base class untuk mengimplementasikan tabel PEGAWAI.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Pegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pegawai()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity(); 
    }
	
	
   function update()
	{
		$str = "
		UPDATE simpeg.pegawai SET
			NAMA= '".$this->getField("NAMA")."',
			EMAIL= '".$this->getField("EMAIL")."',
			TGL_LAHIR= ".$this->getField("TGL_LAHIR").",
			JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
			PENDIDIKAN= '".$this->getField("PENDIDIKAN")."'
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		";
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT A.PEGAWAI_ID PELAMAR_ID, A.NIP_BARU, md5(CAST(A.PEGAWAI_ID as TEXT)) PELAMAR_ID_ENKRIP, NULL NO_REGISTER, NAMA, TEMPAT_LAHIR, ALAMAT, NULL KOTA,
		JENIS_KELAMIN, CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA,TGL_LAHIR, A.PENDIDIKAN, A.EMAIL
		FROM simpeg.pegawai A
		WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
   
  } 
?>
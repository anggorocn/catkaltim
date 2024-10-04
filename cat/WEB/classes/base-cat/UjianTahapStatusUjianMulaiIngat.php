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

  class UjianTahapStatusUjianMulaiIngat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UjianTahapStatusUjianMulaiIngat()
	{
      $this->Entity(); 
    }

    function getCountByParams($paramsArray=array(),$statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM cat.UJIAN_TAHAP_STATUS_UJIAN_MULAI_INGAT A WHERE 1=1 ".$statement;
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function insertQueryModif($statement)
	{
	   $str = "
	   INSERT INTO cat.ujian_tahap_status_ujian_mulai_ingat(
	   UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK_ID, UJIAN_PEGAWAI_DAFTAR_ID,
	   JADWAL_TES_ID, FORMULA_ASSESMENT_ID, FORMULA_ESELON_ID, UJIAN_ID,
	   UJIAN_TAHAP_ID, TIPE_UJIAN_ID, PEGAWAI_ID, STATUS,
	   LAST_CREATE_DATE, LAST_CREATE_USER)
	   SELECT 1 + COALESCE((SELECT COALESCE(MAX(UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK_ID),0) from cat.ujian_tahap_status_ujian_mulai_ingat),0) UJIAN_TAHAP_STATUS_UJIAN_PETUNJUK_ID,
	   A.UJIAN_PEGAWAI_DAFTAR_ID, A.JADWAL_TES_ID, A.FORMULA_ASSESMENT_ID, A.FORMULA_ESELON_ID, A.UJIAN_ID,
	   B.FORMULA_ASSESMENT_UJIAN_TAHAP_ID UJIAN_TAHAP_ID, B.TIPE_UJIAN_ID, A.PEGAWAI_ID, CAST('1' AS INTEGER) STATUS, NOW(), ''
	   FROM cat.ujian_pegawai_daftar A
	   INNER JOIN formula_assesment_ujian_tahap B ON B.FORMULA_ASSESMENT_ID = A.FORMULA_ASSESMENT_ID
	   WHERE 1 = 1 ".$statement;
	   // echo $str;exit;
	   $this->query = $str;
	   return $this->execQuery($str);
    }
    
  } 
?>
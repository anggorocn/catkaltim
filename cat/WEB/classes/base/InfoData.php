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

  class InfoData extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function InfoData()
	{
      $this->Entity(); 
    }
	
	function selectByParamsLookupJadwalPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $jadwaltesid,  $sOrder="")
	{
		$str = "
		SELECT 
		A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP, A.PEGAWAI_ID ID
		, B.KODE PEGAWAI_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA PEGAWAI_ESELON
		, A.LAST_JABATAN PEGAWAI_JAB_STRUKTURAL
		, 
		CASE WHEN JA.LAST_UPDATE_DATE IS NULL THEN '' ELSE GENERATEZERO(CAST(JA.NOMOR_URUT AS TEXT), 2) END NOMOR_URUT_GENERATE
		FROM simpeg.pegawai A
		LEFT JOIN simpeg.pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN simpeg.eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
		LEFT JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
		INNER JOIN
		(
			SELECT ROW_NUMBER() OVER(ORDER BY A.LAST_UPDATE_DATE) NOMOR_URUT, A.*
			FROM jadwal_awal_tes_simulasi_pegawai A
			INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			WHERE JADWAL_AWAL_TES_SIMULASI_ID = ".$jadwaltesid."
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT A.PEGAWAI_ID PELAMAR_ID, A.NIP_BARU, md5(CAST(A.PEGAWAI_ID as TEXT)) PELAMAR_ID_ENKRIP, NULL NO_REGISTER, NAMA, TEMPAT_LAHIR, ALAMAT, NULL KOTA,
		JENIS_KELAMIN, CASE A.JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA
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
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM PELAMAR WHERE 1=1 ".$statement; 
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
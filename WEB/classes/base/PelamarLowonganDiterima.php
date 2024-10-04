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
  * Entity-base class untuk mengimplementasikan tabel JABATAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarLowonganDiterima extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowonganDiterima()
	{
      $this->Entity(); 
    }
	
	function insert()
	{

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA
							(PELAMAR_ID, LOWONGAN_ID, 
							 LAST_CREATE_USER, LAST_CREATE_DATE
							)
					 VALUES ('".$this->getField("PELAMAR_ID")."', '".$this->getField("LOWONGAN_ID")."',
					 		 '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
							)
				
				"; 
		$this->id = $this->getField("PELAMAR_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

	function updateEmail()
	{
        $str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA
				SET 
					TANGGAL_HADIR = ".$this->getField("TANGGAL_HADIR").",
					EMAIL = COALESCE(EMAIL, 0) + 1
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function updateHadir()
	{
        $str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA
				SET 
					HADIR = 1
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function updateBeritaAcara()
	{
        $str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA
				SET 
					NO_BERITA_ACARA 		= '".$this->getField("NO_BERITA_ACARA")."',
					DOKUMEN_BERITA_ACARA 	= '".$this->getField("DOKUMEN_BERITA_ACARA")."'
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
		
				
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." "; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY PELAMAR_ID ASC ")
	{
		$str = "
				SELECT PELAMAR_ID, LOWONGAN_ID, LAST_CREATE_USER, LAST_CREATE_DATE,
					   LAST_UPDATE_USER, LAST_UPDATE_DATE, NO_BERITA_ACARA, DOKUMEN_BERITA_ACARA
				  FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA A
				  WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsInformasiEmail($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY PELAMAR_ID ASC ")
	{
		$str = "
				SELECT A.PELAMAR_ID, A.LOWONGAN_ID, TANGGAL_HADIR, TO_CHAR(TANGGAL_HADIR, 'D') HARI, TO_CHAR(TANGGAL_HADIR, 'YYYY-MM-DD') TANGGAL, TO_CHAR(TANGGAL_HADIR, 'HH24:MI') WAKTU,
					   B.TANGGAL_KIRIM, initcap(C.NAMA) NAMA
				  FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA A
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID AND A.LOWONGAN_ID = B.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR C ON A.PELAMAR_ID = C.PELAMAR_ID
				  WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }		
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_LOWONGAN_DITERIMA A
		        WHERE PELAMAR_ID IS NOT NULL ".$statement; 
		
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
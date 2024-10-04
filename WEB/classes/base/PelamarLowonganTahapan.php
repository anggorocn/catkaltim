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

  class PelamarLowonganTahapan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowonganTahapan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
							(PELAMAR_ID, LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, 
							 LOLOS, LAST_CREATE_USER, LAST_CREATE_DATE
							)
					 VALUES ('".$this->getField("PELAMAR_ID")."', '".$this->getField("LOWONGAN_ID")."', '".$this->getField("LOWONGAN_TAHAPAN_ID")."',
					 		 '".$this->getField("LOLOS")."', '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
							)
				
				"; 
		$this->id = $this->getField("PELAMAR_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

	function insertAwal()
	{

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
							(PELAMAR_ID, LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, 
							 LOLOS, EMAIL, SMS, TANGGAL_HADIR, LAST_CREATE_USER, LAST_CREATE_DATE
							)
					 VALUES ('".$this->getField("PELAMAR_ID")."', '".$this->getField("LOWONGAN_ID")."', '".$this->getField("LOWONGAN_TAHAPAN_ID")."',
					 		 '".$this->getField("LOLOS")."', 
							 (SELECT EMAIL FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = '".$this->getField("PELAMAR_ID")."' AND X.LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'),
							 (SELECT SMS FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = '".$this->getField("PELAMAR_ID")."' AND X.LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'),
							 (SELECT TANGGAL_HADIR FROM pds_rekrutmen.PELAMAR_LOWONGAN_SHORTLIST X WHERE X.PELAMAR_ID = '".$this->getField("PELAMAR_ID")."' AND X.LOWONGAN_ID = '".$this->getField("LOWONGAN_ID")."'),
							 '".$this->getField("LAST_CREATE_USER")."', CURRENT_DATE
							)
				
				"; 
		$this->id = $this->getField("PELAMAR_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateNilaiWawancara()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				   SET 
					   WAWANCARA_NILAI1 = ".$this->getField("WAWANCARA_NILAI1").",
					   WAWANCARA_NILAI2 = ".$this->getField("WAWANCARA_NILAI2").",
					   WAWANCARA_NILAI3 = ".$this->getField("WAWANCARA_NILAI3").",
					   WAWANCARA_NILAI4 = ".$this->getField("WAWANCARA_NILAI4").",
					   WAWANCARA_NILAI5 = ".$this->getField("WAWANCARA_NILAI5").",
					   WAWANCARA_REKOM1 = ".$this->getField("WAWANCARA_REKOM1").",
					   WAWANCARA_REKOM2 = ".$this->getField("WAWANCARA_REKOM2").",
					   WAWANCARA_REKOM3 = ".$this->getField("WAWANCARA_REKOM3").",
					   WAWANCARA_REKOM4 = ".$this->getField("WAWANCARA_REKOM4").",
					   WAWANCARA_REKOM5 = ".$this->getField("WAWANCARA_REKOM5").",
					   WAWANCARA_RATA_NILAI = ".$this->getField("WAWANCARA_RATA_NILAI").",
					   WAWANCARA_RATA_REKOM = ".$this->getField("WAWANCARA_RATA_REKOM").",
					   LAST_UPDATE_DATE = CURRENT_DATE
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateNilaiPsikotes()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				   SET 
					   PSIKOTES_NILAI = ".$this->getField("PSIKOTES_NILAI").",
					   PSIKOTES_REKOM = ".$this->getField("PSIKOTES_REKOM").",
					   LAST_UPDATE_DATE = CURRENT_DATE
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateNilaiKesehatan()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				   SET 
					   KESEHATAN_KESIMPULAN = '".$this->getField("KESEHATAN_KESIMPULAN")."',
					   KESEHATAN_KETERANGAN = '".$this->getField("KESEHATAN_KETERANGAN")."',
					   KESEHATAN_SARAN = '".$this->getField("KESEHATAN_SARAN")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateNilai()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				   SET 
					   NILAI = '".$this->getField("NILAI")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = CURRENT_DATE
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updateLolos()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				   SET 
					   LOLOS = 1
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateTidakLolos()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				   SET 
					   LOLOS = 2
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 

		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updateEmail()
	{
        $str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
				SET 
					TANGGAL_HADIR = ".$this->getField("TANGGAL_HADIR").",
					EMAIL = COALESCE(EMAIL, 0) + 1
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 
				  
		$this->query = $str;
		//echo $str;
        return $this->execQuery($str);
    }
		
	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
                WHERE 
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function deleteData()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN
                WHERE  
                  PELAMAR_ID = ".$this->getField("PELAMAR_ID")." AND
                  LOWONGAN_ID = ".$this->getField("LOWONGAN_ID")." AND
                  LOWONGAN_TAHAPAN_ID = ".$this->getField("LOWONGAN_TAHAPAN_ID").""; 
				  
		$this->query = $str;
		//echo $str;
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
				SELECT PELAMAR_ID, LOWONGAN_ID, LOWONGAN_TAHAPAN_ID, NILAI, EMAIL, SMS, 
					   LOLOS, TANGGAL_HADIR, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_UPDATE_USER, 
					   LAST_UPDATE_DATE, WAWANCARA_NILAI1, WAWANCARA_NILAI2, WAWANCARA_NILAI3, 
					   WAWANCARA_NILAI4, WAWANCARA_NILAI5, WAWANCARA_REKOM1, WAWANCARA_REKOM2, 
					   WAWANCARA_REKOM3, WAWANCARA_REKOM4, WAWANCARA_REKOM5, WAWANCARA_RATA_NILAI, 
					   WAWANCARA_RATA_REKOM, PSIKOTES_NILAI, PSIKOTES_REKOM, KESEHATAN_KESIMPULAN, 
					   KESEHATAN_KETERANGAN, KESEHATAN_SARAN
				  FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN A
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
					   B.TANGGAL_KIRIM, initcap(C.NAMA) NAMA, D.NAMA AGENDA
				  FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN A
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN B ON A.PELAMAR_ID = B.PELAMAR_ID AND A.LOWONGAN_ID = B.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR C ON A.PELAMAR_ID = C.PELAMAR_ID
				  LEFT JOIN pds_rekrutmen.LOWONGAN_TAHAPAN D ON A.LOWONGAN_TAHAPAN_ID = D.LOWONGAN_TAHAPAN_ID
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
		$str = "SELECT COUNT(PELAMAR_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_LOWONGAN_TAHAPAN A
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
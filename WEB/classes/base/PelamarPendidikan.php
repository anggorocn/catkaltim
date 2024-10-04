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
  * Entity-base class untuk mengimplementasikan tabel PELAMAR_PENDIDIKAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarPendidikan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarPendidikan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PENDIDIKAN_ID", $this->getAdminNextId("PELAMAR_PENDIDIKAN_ID","pds_rekrutmen.PELAMAR_PENDIDIKAN"));

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PENDIDIKAN (
				   PELAMAR_PENDIDIKAN_ID, PENDIDIKAN_ID, PELAMAR_ID, NAMA, KOTA, LULUS, 
				   TANGGAL_IJASAH, NO_IJASAH, TTD_IJASAH,
				   TANGGAL_ACC, JURUSAN, UNIVERSITAS_ID, PENDIDIKAN_BIAYA_ID, LAST_CREATE_USER, LAST_CREATE_DATE,
				   JURUSAN_ID
				   ) 
 			  	VALUES (
				  ".$this->getField("PELAMAR_PENDIDIKAN_ID").",
				  '".$this->getField("PENDIDIKAN_ID")."',
				  '".$this->getField("PELAMAR_ID")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KOTA")."',
				  '".$this->getField("LULUS")."',
				  ".$this->getField("TANGGAL_IJASAH").",
				  '".$this->getField("NO_IJASAH")."',
				  '".$this->getField("TTD_IJASAH")."',
				  ".$this->getField("TANGGAL_ACC").",
				  '".$this->getField("JURUSAN")."',
				  ".$this->getField("UNIVERSITAS_ID").",
				  ".$this->getField("PENDIDIKAN_BIAYA_ID").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("JURUSAN_ID")."
				)"; 
		$this->id = $this->getField("PELAMAR_PENDIDIKAN_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_PENDIDIKAN
				SET    
					   PENDIDIKAN_ID           	= '".$this->getField("PENDIDIKAN_ID")."',
					   PELAMAR_ID      			= '".$this->getField("PELAMAR_ID")."',
					   NAMA    					= '".$this->getField("NAMA")."',
					   KOTA         			= '".$this->getField("KOTA")."',
					   LULUS					= '".$this->getField("LULUS")."',
					   TANGGAL_IJASAH			= ".$this->getField("TANGGAL_IJASAH").",
					   NO_IJASAH				= '".$this->getField("NO_IJASAH")."',
					   TTD_IJASAH				= '".$this->getField("TTD_IJASAH")."',
					   TANGGAL_ACC				= ".$this->getField("TANGGAL_ACC").",
					   JURUSAN					= '".$this->getField("JURUSAN")."',
					   UNIVERSITAS_ID			= ".$this->getField("UNIVERSITAS_ID").",
					   PENDIDIKAN_BIAYA_ID		= ".$this->getField("PENDIDIKAN_BIAYA_ID").",
					   LAST_UPDATE_USER			= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE			= ".$this->getField("LAST_UPDATE_DATE").",
					   JURUSAN_ID				= ".$this->getField("JURUSAN_ID")."
				WHERE  PELAMAR_PENDIDIKAN_ID    = '".$this->getField("PELAMAR_PENDIDIKAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_PENDIDIKAN
                WHERE 
                  PELAMAR_PENDIDIKAN_ID = ".$this->getField("PELAMAR_PENDIDIKAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function setuju()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PENDIDIKAN_ID_GEN", $this->getNextId("PELAMAR_PENDIDIKAN_ID","pds_rekrutmen.PELAMAR_PENDIDIKAN")); 		

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PENDIDIKAN (
				   PELAMAR_PENDIDIKAN_ID, PENDIDIKAN_ID, PELAMAR_ID, NAMA, KOTA, LULUS, 
				   TANGGAL_IJASAH, NO_IJASAH, TTD_IJASAH,
				   TANGGAL_ACC, JURUSAN, UNIVERSITAS_ID, PENDIDIKAN_BIAYA_ID, LAST_CREATE_USER, LAST_CREATE_DATE) 
				SELECT ".$this->getField("PELAMAR_PENDIDIKAN_ID_GEN")." PELAMAR_PENDIDIKAN_ID, PENDIDIKAN_ID, PELAMAR_ID, NAMA, KOTA, LULUS, 
				   TANGGAL_IJASAH, NO_IJASAH, TTD_IJASAH,
				   TANGGAL_ACC, JURUSAN, UNIVERSITAS_ID, PENDIDIKAN_BIAYA_ID, LAST_CREATE_USER, LAST_CREATE_DATE
				FROM pds_validasi.PELAMAR_PENDIDIKAN WHERE PELAMAR_PENDIDIKAN_ID = ".$this->getField("PELAMAR_PENDIDIKAN_ID").""; 
		$this->query = $str;
		$this->id = $this->getField("PELAMAR_PENDIDIKAN_ID");
		
		if($this->execQuery($str))
		{
			$str = "DELETE FROM pds_validasi.PELAMAR_PENDIDIKAN
					WHERE 
					  PELAMAR_PENDIDIKAN_ID = ".$this->getField("PELAMAR_PENDIDIKAN_ID").""; 		
			return $this->execQuery($str);
		}
    }
	
	function tolak()
	{
        $str = "DELETE FROM pds_validasi.PELAMAR_PENDIDIKAN
                WHERE 
                  PELAMAR_PENDIDIKAN_ID = ".$this->getField("PELAMAR_PENDIDIKAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
				SELECT 
					PELAMAR_PENDIDIKAN_ID, A.PENDIDIKAN_ID, PELAMAR_ID, A.NAMA, KOTA, LULUS,
					TANGGAL_IJASAH, NO_IJASAH, TTD_IJASAH, TANGGAL_ACC, 
					CASE
						WHEN A.JURUSAN != '' THEN A.JURUSAN
						WHEN A.JURUSAN = '' THEN E.NAMA
					END JURUSAN, A.UNIVERSITAS_ID, A.PENDIDIKAN_BIAYA_ID,
					A.JURUSAN_ID, B.NAMA PENDIDIKAN_NAMA, '' UNIVERSITAS_NAMA, '' PENDIDIKAN_BIAYA_NAMA
				FROM pds_rekrutmen.PELAMAR_PENDIDIKAN A
				LEFT JOIN pds_rekrutmen.PENDIDIKAN B ON A.PENDIDIKAN_ID=B.PENDIDIKAN_ID
				LEFT JOIN pds_rekrutmen.jurusan E ON A.JURUSAN_ID = E.JURUSAN_ID
				WHERE 1 = 1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT PELAMAR_PENDIDIKAN_ID, PENDIDIKAN_ID, PELAMAR_ID, NAMA, KOTA, LULUS,
				TANGGAL_IJASAH, NO_IJASAH, TTD_IJASAH, TANGGAL_ACC, JURUSAN, UNIVERSITAS_ID, PENDIDIKAN_BIAYA_ID
				FROM pds_rekrutmen.PELAMAR_PENDIDIKAN
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PENDIDIKAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_PENDIDIKAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PENDIDIKAN
		        WHERE PELAMAR_PENDIDIKAN_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_PENDIDIKAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PENDIDIKAN
		        WHERE PELAMAR_PENDIDIKAN_ID IS NOT NULL ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>
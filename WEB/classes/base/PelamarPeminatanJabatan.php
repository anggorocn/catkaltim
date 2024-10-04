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
  * Entity-base class untuk mengimplementasikan tabel PELAMAR_PEMINATAN_JABATAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarPeminatanJabatan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarPeminatanJabatan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_PEMINATAN_JABATAN_ID", $this->getNextId("PELAMAR_PEMINATAN_JABATAN_ID","pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN"));

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN(
						PELAMAR_PEMINATAN_JABATAN_ID, PELAMAR_ID, JABATAN_ID, URUT)
				VALUES (
				".$this->getField("PELAMAR_PEMINATAN_JABATAN_ID").",
				".$this->getField("PELAMAR_ID").", 
				".$this->getField("JABATAN_ID").", 
				".$this->getField("URUT")."
				)"; 
		$this->id=$this->getField("PELAMAR_PEMINATAN_JABATAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN
				   SET 
				   	   PELAMAR_ID= '".$this->getField("PELAMAR_ID")."', 
					   JABATAN_ID= '".$this->getField("JABATAN_ID")."'
				 WHERE PELAMAR_PEMINATAN_JABATAN_ID= '".$this->getField("PELAMAR_PEMINATAN_JABATAN_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN
                WHERE 
                  PELAMAR_ID= ".$this->getField("PELAMAR_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY URUT")
	{
		$str = "
					SELECT PELAMAR_PEMINATAN_JABATAN_ID, A.PELAMAR_ID, A.JABATAN_ID,
					C.KODE KODE_JABATAN, C.NAMA NAMA_JABATAN
  					FROM pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN A
					LEFT JOIN pds_rekrutmen.PELAMAR B ON B.PELAMAR_ID = A.PELAMAR_ID
					LEFT JOIN pds_rekrutmen.JABATAN C ON C.JABATAN_ID = A.JABATAN_ID
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
		
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	
					SELECT 
					PELAMAR_PEMINATAN_JABATAN_ID, PELAMAR_ID, SERTIFIKAT_PELAMAR_ID
					FROM pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN A WHERE PELAMAR_PEMINATAN_JABATAN_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PELAMAR_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getUrutJabatan($pelamarId, $urut)
	{
		$str = "SELECT JABATAN_ID FROM pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN A
				WHERE 1=1 AND PELAMAR_ID = ".$pelamarId." AND URUT = ".$urut." "; 
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("JABATAN_ID"); 
		else 
			return 0; 
    }	
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_PEMINATAN_JABATAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN
		        WHERE PELAMAR_PEMINATAN_JABATAN_ID IS NOT NULL				
				LEFT JOIN pds_rekrutmen.PELAMAR B ON B.PELAMAR_ID = A.PELAMAR_ID
				LEFT JOIN pds_rekrutmen.JABATAN C ON C.JABATAN_ID = A.JABATAN_ID
				 ".$statement; 
		
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
		$str = "SELECT COUNT(PELAMAR_PEMINATAN_JABATAN_ID) AS ROWCOUNT FROM pds_rekrutmen.PELAMAR_PEMINATAN_JABATAN
		        WHERE PELAMAR_PEMINATAN_JABATAN_ID IS NOT NULL ".$statement; 
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
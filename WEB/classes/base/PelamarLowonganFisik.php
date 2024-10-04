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
  * Entity-base class untuk mengimplementasikan tabel pds_rekrutmen.PELAMAR_LOWONGAN_FISIK.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarLowonganFisik extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowonganFisik()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_LOWONGAN_FISIK_ID", $this->getNextId("PELAMAR_LOWONGAN_FISIK_ID","pds_rekrutmen.PELAMAR_LOWONGAN_FISIK"));

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_FISIK (
					   PELAMAR_LOWONGAN_FISIK_ID, PELAMAR_LOWONGAN_ID, TINGGI_BADAN, BERAT_BADAN, BUTA_WARNA, KETERANGAN)
 			  	VALUES (
				  ".$this->getField("PELAMAR_LOWONGAN_FISIK_ID").",
				  '".$this->getField("PELAMAR_LOWONGAN_ID")."',
				  '".$this->getField("TINGGI_BADAN")."',
				  '".$this->getField("BERAT_BADAN")."',
				  '".$this->getField("BUTA_WARNA")."',
				  '".$this->getField("KETERANGAN")."'
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_FISIK
				SET    
					   PELAMAR_LOWONGAN_ID         	= '".$this->getField("PELAMAR_LOWONGAN_ID")."',
				  	   TINGGI_BADAN					= '".$this->getField("TINGGI_BADAN")."',
				  	   BERAT_BADAN					= '".$this->getField("BERAT_BADAN")."',
				  	   BUTA_WARNA					= '".$this->getField("BUTA_WARNA")."',
				  	   KETERANGAN					= '".$this->getField("KETERANGAN")."'
				WHERE  PELAMAR_LOWONGAN_FISIK_ID 	= '".$this->getField("PELAMAR_LOWONGAN_FISIK_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_FISIK A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PELAMAR_LOWONGAN_FISIK_ID = ".$this->getField("PELAMAR_LOWONGAN_FISIK_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_FISIK
                WHERE 
                  PELAMAR_LOWONGAN_FISIK_ID = ".$this->getField("PELAMAR_LOWONGAN_FISIK_ID").""; 
				  
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
				SELECT PELAMAR_LOWONGAN_FISIK_ID, PELAMAR_LOWONGAN_ID, TINGGI_BADAN, BERAT_BADAN, BUTA_WARNA, KETERANGAN
 	 	 			FROM pds_rekrutmen.PELAMAR_LOWONGAN_FISIK A 
		 		WHERE 1 = 1
				"; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
		
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_FISIK_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR_LOWONGAN_FISIK
		        WHERE 1=1 ".$statement; 
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
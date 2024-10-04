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
  * Entity-base class untuk mengimplementasikan tabel pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PelamarLowonganKesemaptaan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PelamarLowonganKesemaptaan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PELAMAR_LOWONGAN_KESEMAPTAAN_ID", $this->getNextId("PELAMAR_LOWONGAN_KESEMAPTAAN_ID","pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN"));

		$str = "
				INSERT INTO pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN (
					   PELAMAR_LOWONGAN_KESEMAPTAAN_ID, PELAMAR_LOWONGAN_ID, LARI, SHUTTLE_RUN, 
       					PUSH_UP, PULL_UP, SIT_UP, KETERANGAN)
 			  	VALUES (
				  ".$this->getField("PELAMAR_LOWONGAN_KESEMAPTAAN_ID").",
				  '".$this->getField("PELAMAR_LOWONGAN_ID")."',
				  '".$this->getField("LARI")."',
				  '".$this->getField("SHUTTLE_RUN")."',
				  '".$this->getField("PUSH_UP")."',
				  '".$this->getField("PULL_UP")."',
				  '".$this->getField("SIT_UP")."',
				  '".$this->getField("KETERANGAN")."'
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
				UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN
				SET    
					   PELAMAR_LOWONGAN_ID         	= '".$this->getField("PELAMAR_LOWONGAN_ID")."',
				  	   LARI							= '".$this->getField("LARI")."',
				  	   SHUTTLE_RUN					= '".$this->getField("SHUTTLE_RUN")."',
				  	   PUSH_UP						= '".$this->getField("PUSH_UP")."',
				  	   PULL_UP						= '".$this->getField("PULL_UP")."',
				  	   SIT_UP						= '".$this->getField("SIT_UP")."',
				  	   KETERANGAN					= '".$this->getField("KETERANGAN")."'
				WHERE  PELAMAR_LOWONGAN_KESEMAPTAAN_ID 	= '".$this->getField("PELAMAR_LOWONGAN_KESEMAPTAAN_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PELAMAR_LOWONGAN_KESEMAPTAAN_ID = ".$this->getField("PELAMAR_LOWONGAN_KESEMAPTAAN_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN
                WHERE 
                  PELAMAR_LOWONGAN_KESEMAPTAAN_ID = ".$this->getField("PELAMAR_LOWONGAN_KESEMAPTAAN_ID").""; 
				  
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
				SELECT PELAMAR_LOWONGAN_KESEMAPTAAN_ID, PELAMAR_LOWONGAN_ID, LARI, SHUTTLE_RUN, 
       				PUSH_UP, PULL_UP, SIT_UP, KETERANGAN
 	 	 		FROM pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN A 
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
		$str = "SELECT COUNT(PELAMAR_LOWONGAN_KESEMAPTAAN_ID) AS ROWCOUNT 
				FROM pds_rekrutmen.PELAMAR_LOWONGAN_KESEMAPTAAN
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
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
  //include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/Entity.php");

  class LowonganDokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function LowonganDokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("LOWONGAN_DOKUMEN_ID", $this->getAdminNextId("LOWONGAN_DOKUMEN_ID","pds_rekrutmen.LOWONGAN_DOKUMEN")); 

		$str = "
				INSERT INTO pds_rekrutmen.LOWONGAN_DOKUMEN(
						LOWONGAN_DOKUMEN_ID, LOWONGAN_ID, DOKUMEN_ID, NAMA, KETERANGAN, FORMAT, WAJIB)
				VALUES ('".$this->getField("LOWONGAN_DOKUMEN_ID")."', 
						'".$this->getField("LOWONGAN_ID")."', 
						".$this->getField("DOKUMEN_ID").", 
						'".$this->getField("NAMA")."', 
						'".$this->getField("KETERANGAN")."', 
						'".$this->getField("FORMAT")."', 
						'".$this->getField("WAJIB")."'
				)
				"; 
		$this->query = $str;
		$this->id = $this->getField("LOWONGAN_DOKUMEN_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE pds_rekrutmen.LOWONGAN_DOKUMEN 
				SET 
					WAJIB						= '".$this->getField("WAJIB")."',  
					DOKUMEN_ID					= ".$this->getField("DOKUMEN_ID").", 
					NAMA						= '".$this->getField("NAMA")."', 
					KETERANGAN					= '".$this->getField("KETERANGAN")."',
					FORMAT						= '".$this->getField("FORMAT")."'
			 	WHERE LOWONGAN_DOKUMEN_ID	= '".$this->getField("LOWONGAN_DOKUMEN_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE ".$this->getField("FIELD_ID")." = '".$this->getField("FIELD_VALUE_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM pds_rekrutmen.LOWONGAN_DOKUMEN
                WHERE 
                  LOWONGAN_DOKUMEN_ID = '".$this->getField("LOWONGAN_DOKUMEN_ID")."'
			"; 
			
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.LOWONGAN_DOKUMEN_ID, A.LOWONGAN_ID, A.NAMA, A.KETERANGAN, A.WAJIB, A.FORMAT, 
						CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, DOKUMEN_ID
				  FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				  LEFT JOIN pds_rekrutmen.LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
				 WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPelamarLowongan($pelamar_lowongan_id, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = " SELECT A.LOWONGAN_DOKUMEN_ID, A.LOWONGAN_ID, A.NAMA, A.KETERANGAN, COALESCE(C.LINK_FILE, E.LAMPIRAN) LINK_FILE, A.WAJIB, 
						CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, FORMAT, A.DOKUMEN_ID
				  FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				  LEFT JOIN pds_rekrutmen.LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND C.PELAMAR_LOWONGAN_ID = '".$pelamar_lowongan_id."'
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN D ON D.PELAMAR_LOWONGAN_ID = '".$pelamar_lowongan_id."'
				  LEFT JOIN pds_rekrutmen.PELAMAR_DOKUMEN E ON D.PELAMAR_ID = E.PELAMAR_ID AND A.DOKUMEN_ID = E.DOKUMEN_ID
				 WHERE 1=1 "; 
		
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
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","nama"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM pds_rekrutmen.LOWONGAN_DOKUMEN A WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsPelamarLowongan($pelamar_lowongan_id, $paramsArray=array(), $statement="")
	{
		$str = " SELECT COUNT(1) AS ROWCOUNT 
				  FROM pds_rekrutmen.LOWONGAN_DOKUMEN A
				  LEFT JOIN pds_rekrutmen.LOWONGAN B ON B.LOWONGAN_ID = A.LOWONGAN_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.LOWONGAN_DOKUMEN_ID = A.LOWONGAN_DOKUMEN_ID AND C.PELAMAR_LOWONGAN_ID = '".$pelamar_lowongan_id."'
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN D ON D.PELAMAR_LOWONGAN_ID = '".$pelamar_lowongan_id."'
				  LEFT JOIN pds_rekrutmen.PELAMAR_DOKUMEN E ON D.PELAMAR_ID = E.PELAMAR_ID AND A.DOKUMEN_ID = E.DOKUMEN_ID
				 WHERE 1=1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>
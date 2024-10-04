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

  class DiklatDokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DiklatDokumen()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DIKLAT_DOKUMEN_ID", $this->getNextId("DIKLAT_DOKUMEN_ID","diklat_dokumen")); 

		$str = "
		INSERT INTO diklat_dokumen(
		DIKLAT_DOKUMEN_ID, DIKLAT_ID, DOKUMEN_ID, NAMA, KETERANGAN, FORMAT, WAJIB)
		VALUES (
			'".$this->getField("DIKLAT_DOKUMEN_ID")."', 
			'".$this->getField("DIKLAT_ID")."', 
			".$this->getField("DOKUMEN_ID").", 
			'".$this->getField("NAMA")."', 
			'".$this->getField("KETERANGAN")."', 
			'".$this->getField("FORMAT")."', 
			'".$this->getField("WAJIB")."'
		)
		"; 
		$this->query = $str;
		$this->id = $this->getField("DIKLAT_DOKUMEN_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		$str = "
		UPDATE diklat_dokumen 
		SET 
			WAJIB						= '".$this->getField("WAJIB")."',  
			DOKUMEN_ID					= ".$this->getField("DOKUMEN_ID").", 
			NAMA						= '".$this->getField("NAMA")."', 
			KETERANGAN					= '".$this->getField("KETERANGAN")."',
			FORMAT						= '".$this->getField("FORMAT")."'
		WHERE DIKLAT_DOKUMEN_ID	= '".$this->getField("DIKLAT_DOKUMEN_ID")."'
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
				DELETE FROM diklat_dokumen
                WHERE 
                  DIKLAT_DOKUMEN_ID = '".$this->getField("DIKLAT_DOKUMEN_ID")."'
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
    function selectByParamsDiklat($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT A.NAMA_DIKLAT, A.DIKLAT_ID
		FROM diklat A
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "
		SELECT A.DIKLAT_DOKUMEN_ID, A.DIKLAT_ID, A.NAMA, A.KETERANGAN, A.WAJIB, A.FORMAT, 
		CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, DOKUMEN_ID, PENAMAAN_FILE
		FROM diklat_dokumen A
		LEFT JOIN diklat B ON B.DIKLAT_ID = A.DIKLAT_ID
		LEFT JOIN (SELECT DOKUMEN_ID ROW_ID, PENAMAAN_FILE FROM dokumen) C ON A.DOKUMEN_ID = C.ROW_ID
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

    function selectByParamsPelamarLowongan($pelamar_lowongan_id, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = " 
		SELECT A.DIKLAT_DOKUMEN_ID, A.DIKLAT_ID, A.NAMA, A.KETERANGAN, COALESCE(C.LINK_FILE, E.LAMPIRAN) LINK_FILE, A.WAJIB, 
						CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, FORMAT, A.DOKUMEN_ID
				  FROM diklat_dokumen A
				  LEFT JOIN diklat B ON B.DIKLAT_ID = A.DIKLAT_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.DIKLAT_DOKUMEN_ID = A.DIKLAT_DOKUMEN_ID AND C.PELAMAR_DIKLAT_ID = '".$pelamar_lowongan_id."'
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN D ON D.PELAMAR_DIKLAT_ID = '".$pelamar_lowongan_id."'
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM diklat_dokumen A WHERE 1=1 ".$statement; 
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
				  FROM diklat_dokumen A
				  LEFT JOIN diklat B ON B.DIKLAT_ID = A.DIKLAT_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.DIKLAT_DOKUMEN_ID = A.DIKLAT_DOKUMEN_ID AND C.PELAMAR_DIKLAT_ID = '".$pelamar_lowongan_id."'
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN D ON D.PELAMAR_DIKLAT_ID = '".$pelamar_lowongan_id."'
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
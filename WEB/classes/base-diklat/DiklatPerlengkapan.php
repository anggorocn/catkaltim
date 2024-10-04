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

  class DiklatPerlengkapan extends Entity{ 


	var $query;
    /**
    * Class constructor.
    **/
    function DiklatPerlengkapan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DIKLAT_PERLENGKAPAN_ID", $this->getNextId("DIKLAT_PERLENGKAPAN_ID","diklat_perlengkapan")); 

		$str = "
		INSERT INTO diklat_perlengkapan(
		DIKLAT_PERLENGKAPAN_ID, DIKLAT_ID, DOKUMEN_ID, JENIS_DOKUMEN, NAMA, KETERANGAN, FORMAT, WAJIB)
		VALUES (
			'".$this->getField("DIKLAT_PERLENGKAPAN_ID")."', 
			'".$this->getField("DIKLAT_ID")."', 
			".$this->getField("DOKUMEN_ID").", 
			".$this->getField("JENIS_DOKUMEN").", 
			'".$this->getField("NAMA")."', 
			'".$this->getField("KETERANGAN")."', 
			'".$this->getField("FORMAT")."', 
			'".$this->getField("WAJIB")."'
		)
		"; 
		$this->query = $str;
		$this->id = $this->getField("DIKLAT_PERLENGKAPAN_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		$str = "
		UPDATE diklat_perlengkapan 
		SET 
			WAJIB						= '".$this->getField("WAJIB")."',  
			DOKUMEN_ID					= ".$this->getField("DOKUMEN_ID").", 
			JENIS_DOKUMEN= ".$this->getField("JENIS_DOKUMEN").",
			NAMA						= '".$this->getField("NAMA")."', 
			KETERANGAN					= '".$this->getField("KETERANGAN")."',
			FORMAT						= '".$this->getField("FORMAT")."'
		WHERE DIKLAT_PERLENGKAPAN_ID	= '".$this->getField("DIKLAT_PERLENGKAPAN_ID")."'
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
				DELETE FROM diklat_perlengkapan
                WHERE 
                  DIKLAT_PERLENGKAPAN_ID = '".$this->getField("DIKLAT_PERLENGKAPAN_ID")."'
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

		$str = "
		SELECT A.DIKLAT_PERLENGKAPAN_ID, A.DIKLAT_ID, A.NAMA, A.KETERANGAN, A.WAJIB, A.FORMAT, 
		CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, DOKUMEN_ID
		, A.JENIS_DOKUMEN
		FROM diklat_perlengkapan A
		LEFT JOIN diklat B ON B.DIKLAT_ID = A.DIKLAT_ID
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
		SELECT A.DIKLAT_PERLENGKAPAN_ID, A.DIKLAT_ID, A.NAMA, A.KETERANGAN, COALESCE(C.LINK_FILE, E.LAMPIRAN) LINK_FILE, A.WAJIB, 
						CASE A.WAJIB WHEN '1' THEN 'Wajib' WHEN '0' THEN 'Tidak Wajib' END WAJIB_INFO, FORMAT, A.DOKUMEN_ID
				  FROM diklat_perlengkapan A
				  LEFT JOIN diklat B ON B.DIKLAT_ID = A.DIKLAT_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.DIKLAT_PERLENGKAPAN_ID = A.DIKLAT_PERLENGKAPAN_ID AND C.PELAMAR_DIKLAT_ID = '".$pelamar_lowongan_id."'
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM diklat_perlengkapan A WHERE 1=1 ".$statement; 
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
				  FROM diklat_perlengkapan A
				  LEFT JOIN diklat B ON B.DIKLAT_ID = A.DIKLAT_ID
				  LEFT JOIN pds_rekrutmen.PELAMAR_LOWONGAN_DOKUMEN C ON C.DIKLAT_PERLENGKAPAN_ID = A.DIKLAT_PERLENGKAPAN_ID AND C.PELAMAR_DIKLAT_ID = '".$pelamar_lowongan_id."'
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
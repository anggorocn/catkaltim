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

  class Jadwal extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Jadwal()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_ID", $this->getNextId("JADWAL_ID","JADWAL")); 

		$str = "INSERT INTO JADWAL(JADWAL_ID, NAMA, KETERANGAN)
				VALUES(
				  ".$this->getField("JADWAL_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("JADWAL_ID");
		return $this->execQuery($str);
    }
	
	function insertSahli()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_ID", $this->getNextId("JADWAL_ID","JADWAL_SAHLI")); 

		$str = "INSERT INTO JADWAL_SAHLI(JADWAL_ID, NAMA, KETERANGAN)
				VALUES(
				  ".$this->getField("JADWAL_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("JADWAL_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE JADWAL SET
				  NAMA			= '".$this->getField("NAMA")."',
				  KETERANGAN	= '".$this->getField("KETERANGAN")."'
				WHERE JADWAL_ID	= '".$this->getField("JADWAL_ID")."'
				"; 
				
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateSahli()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE JADWAL_SAHLI SET
				  NAMA			= '".$this->getField("NAMA")."',
				  KETERANGAN	= '".$this->getField("KETERANGAN")."'
				WHERE JADWAL_ID	= '".$this->getField("JADWAL_ID")."'
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
				DELETE FROM JADWAL
                WHERE 
                  JADWAL_ID = '".$this->getField("JADWAL_ID")."'
			"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteSahli()
	{
        $str = "
				DELETE FROM JADWAL_SAHLI
                WHERE 
                  JADWAL_ID = '".$this->getField("JADWAL_ID")."'
			"; 
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteAll()
	{
		$str = "
				DELETE FROM JADWAL_DETIL
                WHERE 
                  JADWAL_ID = '".$this->getField("JADWAL_ID")."'
			"; 
		$this->query = $str;
		$this->execQuery($str);
		
        $str1 = "
				DELETE FROM JADWAL
                WHERE 
                  JADWAL_ID = '".$this->getField("JADWAL_ID")."'
			"; 
		$this->query = $str1;
        return $this->execQuery($str1);
    }
	
	function deleteAllSahli()
	{
		$str = "
				DELETE FROM JADWAL_DETIL_SAHLI
                WHERE 
                  JADWAL_ID = '".$this->getField("JADWAL_ID")."'
			"; 
		$this->query = $str;
		$this->execQuery($str);
		
        $str1 = "
				DELETE FROM JADWAL_SAHLI
                WHERE 
                  JADWAL_ID = '".$this->getField("JADWAL_ID")."'
			"; 
		$this->query = $str1;
        return $this->execQuery($str1);
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
		$str = "SELECT JADWAL_ID, NAMA, KETERANGAN
				FROM JADWAL WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	 function selectByParamsSahli($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT JADWAL_ID, NAMA, KETERANGAN
				FROM JADWAL_SAHLI WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.JADWAL_ID ASC")
	{
		$str = "SELECT A.JADWAL_ID, A.NAMA, A.KETERANGAN, 
				CASE WHEN B.TANGGAL_SAMPAI is NULL THEN
				TO_CHAR(B.TANGGAL_MULAI, 'DD-MM-YYYY')
				ELSE
				TO_CHAR(B.TANGGAL_MULAI, 'DD-MM-YYYY') || ' - ' || TO_CHAR(B.TANGGAL_SAMPAI, 'DD-MM-YYYY')
				END || ' ' TANGGAL_INFO,
				A.NAMA || ': ' || B.NAMA NAMA_INFO
				FROM JADWAL_SAHLI A 
				LEFT JOIN JADWAL_DETIL_SAHLI B ON A.JADWAL_ID = B.JADWAL_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringSahli($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.JADWAL_ID ASC")
	{
		$str = "SELECT A.JADWAL_ID, A.NAMA, A.KETERANGAN, 
				CASE WHEN B.TANGGAL_SAMPAI is NULL THEN
				TO_CHAR(B.TANGGAL_MULAI, 'DD-MM-YYYY')
				ELSE
				TO_CHAR(B.TANGGAL_MULAI, 'DD-MM-YYYY') || ' - ' || TO_CHAR(B.TANGGAL_SAMPAI, 'DD-MM-YYYY')
				END || ' ' TANGGAL_INFO,
				A.NAMA || ': ' || B.NAMA NAMA_INFO
				FROM JADWAL_SAHLI A 
				LEFT JOIN JADWAL_DETIL_SAHLI B ON A.JADWAL_ID = B.JADWAL_ID
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.JADWAL_ID, A.NAMA, KETERANGAN
				FROM JADWAL A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}		
		$str .= $statement." ".$order;
		$this->query = $str;		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDataSahli($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "SELECT A.JADWAL_ID, A.NAMA, KETERANGAN
				FROM JADWAL_SAHLI A WHERE 1=1 "; 
		
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM JADWAL WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsSahli($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM JADWAL_SAHLI WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsData($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM JADWAL A WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsDataSahli($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM JADWAL_SAHLI A WHERE 1=1 ".$statement; 
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
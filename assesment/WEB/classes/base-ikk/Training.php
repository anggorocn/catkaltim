<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
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

  class Training extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Training()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TRAINING_ID", $this->getNextId("TRAINING_ID","training")); 

		$str = "INSERT INTO training (
				   TRAINING_ID, TAHUN, NAMA, 
				   JAM_ES2, JAM_ES3, JAM_ES4, JAM_JFU)
				VALUES (
				  ".$this->getField("TRAINING_ID").",
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("NAMA")."',
				  ".$this->getField("JAM_ES2").",
				  ".$this->getField("JAM_ES3").",
				  ".$this->getField("JAM_ES4").",
				  ".$this->getField("JAM_JFU")."
				)"; 
				  //'".$this->getField("PATH")."'
		$this->query = $str;
		$this->id = $this->getField("TRAINING_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE training
				SET    
					   TAHUN= '".$this->getField("TAHUN")."',
					   NAMA= '".$this->getField("NAMA")."',
					   JAM_ES2= ".$this->getField("JAM_ES2").",
					   JAM_ES3= ".$this->getField("JAM_ES3").",
					   JAM_ES4= ".$this->getField("JAM_ES4").",
					   JAM_JFU= ".$this->getField("JAM_JFU")."
				WHERE  TRAINING_ID= ".$this->getField("TRAINING_ID")."
				"; //PATH= '".$this->getField("PATH")."'
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }
	
	function updateFormat()
	{
		$str = "
				UPDATE training
				SET
					   UKURAN= ".$this->getField("UKURAN").",
					   FORMAT= '".$this->getField("FORMAT")."'
				WHERE  TRAINING_ID = ".$this->getField("TRAINING_ID")." AND TAHUN = ".$this->getField("TAHUN")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function selectByParamsBlob($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT FOTO_BLOB, FORMAT
				FROM training WHERE TRAINING_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement."";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function delete()
	{
        $str = "DELETE FROM training
                WHERE 
                  TRAINING_ID = ".$this->getField("TRAINING_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteAll()
	{
        $str1= "DELETE FROM kompetensi_training
                WHERE 
                  TRAINING_ID = ".$this->getField("TRAINING_ID").""; 
				  
		$this->query = $str1;
		$this->execQuery($str1);
		
		$str = "DELETE FROM training
                WHERE 
                  TRAINING_ID = ".$this->getField("TRAINING_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteKompetensi()
	{
        $str1= "DELETE FROM kompetensi_training
                WHERE 
                  KOMPETENSI_TRAINING_ID = ".$this->getField("KOMPETENSI_TRAINING_ID").""; 
				  
		$this->query = $str1;
		return $this->execQuery($str1);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JAM_ES3"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM training A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT 
		   TRAINING_ID, TAHUN, NAMA, JAM_ES2, JAM_ES3, JAM_ES4, JAM_JFU, PATH
		FROM training A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY JAM_ES2 ASC";
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTree($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
		SELECT ID, PARENT_ID, NAMA, ID_ROW, '' LINK_URL
		FROM
		(
			SELECT A.TAHUN ID, '0' PARENT_ID, A.TAHUN NAMA, 0 ID_ROW
			FROM training A
			GROUP BY A.TAHUN
			UNION ALL
			SELECT CONCAT(A.TAHUN,'-',CAST(A.TRAINING_ID AS CHAR)) ID, A.TAHUN PARENT_ID, A.NAMA, 1 ID_ROW
			FROM training A
			UNION ALL
			SELECT CONCAT(A.TAHUN,'-',CAST(B.TRAINING_ID AS CHAR),'-',CAST(A.KOMPETENSI_TRAINING_ID AS CHAR)) ID, CONCAT(A.TAHUN,'-',CAST(B.TRAINING_ID AS CHAR)) PARENT_ID, C.NAMA, 3 ID_ROW
			FROM kompetensi_training A
			INNER JOIN training B ON A.TRAINING_ID = B.TRAINING_ID
			INNER JOIN atribut C ON A.ATRIBUT_ID = C.ATRIBUT_ID
			WHERE 1=1
		) A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ORDER BY ID_ROW, ID DESC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.NAMA TRAINING_NAMA, CONCAT(D.ATRIBUT_PARENT_NAMA,' - ',C.NAMA) ATRIBUT_NAMA, A.TAHUN, A.TRAINING_ID
		FROM training A
		LEFT JOIN kompetensi_training B ON A.TRAINING_ID = B.TRAINING_ID
		LEFT JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID
		LEFT JOIN
		(
			SELECT ATRIBUT_ID, NAMA ATRIBUT_PARENT_NAMA
			FROM atribut
			WHERE ATRIBUT_ID_PARENT = '0'
		) D ON D.ATRIBUT_ID = SUBSTR(B.ATRIBUT_ID, 1,2)
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsKompetensi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $orderby='')
	{
		$str = "
		SELECT 
			A.NAMA TRAINING_NAMA, C.NAMA ATRIBUT_NAMA, A.TRAINING_ID, B.ATRIBUT_ID, B.KOMPETENSI_TRAINING_ID
		FROM training A
		INNER JOIN kompetensi_training B ON A.TRAINING_ID = B.TRAINING_ID
		INNER JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID
		INNER JOIN
		(
			SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
		) D ON C.ATRIBUT_ID_PARENT = D.ATRIBUT_ID
		WHERE 1=1
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT TRAINING_ID, TAHUN, NAMA, 
				   JAM_ES3, JAM_ES4, JAM_JFU, 
				   JAM_ES2
				FROM training WHERE TRAINING_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY JAM_ES3 ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JAM_ES3"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM training A
		LEFT JOIN kompetensi_training B ON A.TRAINING_ID = B.TRAINING_ID
		LEFT JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID
		LEFT JOIN
		(
			SELECT ATRIBUT_ID, NAMA ATRIBUT_PARENT_NAMA
			FROM atribut
			WHERE ATRIBUT_ID_PARENT = '0'
		) D ON D.ATRIBUT_ID = SUBSTR(B.ATRIBUT_ID, 1,2)
		WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str.= $statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(TRAINING_ID) AS ROWCOUNT FROM training WHERE TRAINING_ID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(TRAINING_ID) AS ROWCOUNT FROM training WHERE TRAINING_ID IS NOT NULL "; 
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
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

  class Formulir extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Formulir()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULIR_JAWABAN_ID", $this->getNextId("FORMULIR_JAWABAN_ID","portal.FORMULIR_JAWABAN")); 
		$str= "
			INSERT INTO portal.FORMULIR_JAWABAN
			(FORMULIR_JAWABAN_ID,PEGAWAI_ID, FORMULIR_SOAL_ID, TIPE_FORMULIR_ID, JAWABAN)
		  	VALUES(
				  ".$this->getField("FORMULIR_JAWABAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("FORMULIR_SOAL_ID").",
				  ".$this->getField("TIPE_FORMULIR_ID").",
				  '".$this->getField("JAWABAN")."'
				  
				)"; 
		$this->id= $this->getField("FORMULIR_JAWABAN_ID");
		$this->query= $str;
		// echo $str;
		return $this->execQuery($str);
    }


    function update()
	{
		$str= "
		UPDATE portal.FORMULIR_JAWABAN
		SET 
			FORMULIR_SOAL_ID= ".$this->getField("FORMULIR_SOAL_ID").",
			TIPE_FORMULIR_ID= ".$this->getField("TIPE_FORMULIR_ID").",
			JAWABAN= '".$this->getField("JAWABAN")."'
			
		WHERE PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
		"; 
		$this->query= $str;
		// echo $str;
		// return $this->execQuery($str);
    }

    function deleteInta()
	{
        $str= "DELETE FROM portal.FORMULIR_JAWABAN
                WHERE 
                  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")." 
                  AND TIPE_FORMULIR_ID= ".$this->getField("TIPE_FORMULIR_ID")."";
				  
		$this->query= $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }
	

    function selectByParamsSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT A.FORMULIR_SOAL_ID, A.TIPE_FORMULIR_ID,A.NAMA SOAL
		FROM portal.FORMULIR_SOAL A
		INNER JOIN portal.TIPE_FORMULIR B ON A.TIPE_FORMULIR_ID = B.TIPE_FORMULIR_ID
		WHERE 1=1 
		"; 
		
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query= $str;
		// echo $str;exit();

		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJawaban($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT A.FORMULIR_SOAL_ID, A.TIPE_FORMULIR_ID,C.PEGAWAI_ID, A.NAMA SOAL,C.JAWABAN
		FROM portal.FORMULIR_SOAL A
		INNER JOIN portal.TIPE_FORMULIR B ON A.TIPE_FORMULIR_ID = B.TIPE_FORMULIR_ID
		LEFT JOIN portal.FORMULIR_JAWABAN C ON A.FORMULIR_SOAL_ID = C.FORMULIR_SOAL_ID
		WHERE 1=1 
		"; 
		
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query= $str;
		// echo $str;exit();

		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoalCritical($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT FORMULIR_SOAL_CRITICAL_ID, NAMA, TOPIK, TANGGAL, BULAN, TAHUN
		FROM PORTAL.FORMULIR_SOAL_CRITICAL A
		WHERE 1=1 
		"; 
		
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query= $str;
		// echo $str;exit();

		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsCriticalSoal($paramsArray=array(), $statement="")
    {
    	$str = "SELECT COUNT(1) AS ROWCOUNT
    	FROM PORTAL.FORMULIR_SOAL_CRITICAL_TAMBAHAN A
    	LEFT JOIN PORTAL.FORMULIR_CRITICAL_JAWABAN B ON A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID = B.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID
    	WHERE JAWABAN IS NOT NULL ".$statement; 

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

    function getCountByParamsCriticalJawaban($paramsArray=array(), $statement="")
    {
    	$str = "SELECT COUNT(1) AS ROWCOUNT
		FROM PORTAL.FORMULIR_JAWABAN_CRITICAL_HEADER A
		LEFT JOIN PORTAL.FORMULIR_SOAL_CRITICAL_HEADER B ON A.FORMULIR_SOAL_CRITICAL_HEADER_ID = B.FORMULIR_SOAL_CRITICAL_HEADER_ID
		WHERE TOPIK IS NOT NULL AND TANGGAL IS NOT NULL AND BULAN IS NOT NULL AND TAHUN IS NOT NULL AND SAMPAI IS NOT NULL ".$statement; 

    	while(list($key,$val)=each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}
		// echo $str; exit;

    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return 0; 
    }

    function getCountByParamsQInta($paramsArray=array(), $statement="")
    {
    	$str = "
    	SELECT COUNT(1) AS ROWCOUNT
		FROM portal.FORMULIR_SOAL A
		INNER JOIN portal.TIPE_FORMULIR B ON A.TIPE_FORMULIR_ID = B.TIPE_FORMULIR_ID
		LEFT JOIN portal.FORMULIR_JAWABAN C ON A.FORMULIR_SOAL_ID = C.FORMULIR_SOAL_ID
		WHERE C.JAWABAN IS NOT NULL ".$statement; 

    	while(list($key,$val)=each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}
		// echo $str; exit;

    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return 0; 
    }



  
  } 
?>
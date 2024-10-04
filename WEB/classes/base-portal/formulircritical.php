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

  class FormulirCritical extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormulirCritical()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULIR_JAWABAN_CRITICAL_HEADER_ID", $this->getNextId("FORMULIR_JAWABAN_CRITICAL_HEADER_ID","portal.FORMULIR_JAWABAN_CRITICAL_HEADER")); 
		$str= "
			INSERT INTO portal.FORMULIR_JAWABAN_CRITICAL_HEADER
			(FORMULIR_JAWABAN_CRITICAL_HEADER_ID,FORMULIR_SOAL_CRITICAL_HEADER_ID, PEGAWAI_ID, TOPIK, TANGGAL,BULAN,TAHUN,SAMPAI)
		  	VALUES(
				  ".$this->getField("FORMULIR_JAWABAN_CRITICAL_HEADER_ID").",
				  ".$this->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("TOPIK")."',
				  ".$this->getField("TANGGAL").",
				  ".$this->getField("BULAN").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("SAMPAI")."
				  
				)"; 
		$this->id= $this->getField("FORMULIR_JAWABAN_CRITICAL_HEADER_ID");
		$this->query= $str;
		// echo $str;
		return $this->execQuery($str);
    }

    function insertJawaban()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMULIR_CRITICAL_JAWABAN_ID", $this->getNextId("FORMULIR_CRITICAL_JAWABAN_ID","portal.FORMULIR_CRITICAL_JAWABAN")); 
		$str= "
			INSERT INTO portal.FORMULIR_CRITICAL_JAWABAN
			(FORMULIR_CRITICAL_JAWABAN_ID,PEGAWAI_ID, FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID, JAWABAN,FORMULIR_SOAL_CRITICAL_HEADER_ID)
		  	VALUES(
				  ".$this->getField("FORMULIR_CRITICAL_JAWABAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID").",
				  '".$this->getField("JAWABAN")."',
				  ".$this->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID")."
				  
				)"; 
		$this->id= $this->getField("FORMULIR_CRITICAL_JAWABAN_ID");
		$this->query= $str;
		// echo $str;
		return $this->execQuery($str);
    }


    function update()
	{
		$str= "
		UPDATE portal.FORMULIR_SOAL_CRITICAL
		SET 
			FORMULIR_SOAL_CRITICAL_ID= ".$this->getField("FORMULIR_SOAL_CRITICAL_ID").",
			TOPIK= '".$this->getField("TOPIK")."',
			TANGGAL= ".$this->getField("TANGGAL").",
			BULAN= ".$this->getField("BULAN")."
			
		WHERE FORMULIR_SOAL_CRITICAL_ID= ".$this->getField("FORMULIR_SOAL_CRITICAL_ID")."
		"; 
		$this->query= $str;
		// echo $str;
		// return $this->execQuery($str);
    }

    function delete()
	{
        $str= "DELETE FROM portal.FORMULIR_JAWABAN_CRITICAL_HEADER
                WHERE 
                  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."" ;
				  
		$this->query= $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }


    function deleteJawaban()
	{
        $str= "DELETE FROM portal.FORMULIR_CRITICAL_JAWABAN
                WHERE 
                  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."" ;
				  
		$this->query= $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }
	


    function selectByParamsSoalCriticalHeader($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT FORMULIR_SOAL_CRITICAL_HEADER_ID, NAMA
		FROM PORTAL.FORMULIR_SOAL_CRITICAL_HEADER A
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

    function selectByParamsJawabanCriticalHeader($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT FORMULIR_JAWABAN_CRITICAL_HEADER_ID,TOPIK, TANGGAL,BULAN,TAHUN,SAMPAI,PEGAWAI_ID,B.FORMULIR_SOAL_CRITICAL_HEADER_ID
		FROM PORTAL.FORMULIR_JAWABAN_CRITICAL_HEADER A
		LEFT JOIN PORTAL.FORMULIR_SOAL_CRITICAL_HEADER B ON A.FORMULIR_SOAL_CRITICAL_HEADER_ID = B.FORMULIR_SOAL_CRITICAL_HEADER_ID
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

    function selectByParamsJawabanCritical($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID, A.NAMA,B.JAWABAN,B.PEGAWAI_ID
		FROM PORTAL.FORMULIR_SOAL_CRITICAL_TAMBAHAN A
		LEFT JOIN PORTAL.FORMULIR_CRITICAL_JAWABAN B ON A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID = B.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID
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

    function selectByParamsJawabanCriticalSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str= "
		SELECT A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID, A.NAMA 
		FROM PORTAL.FORMULIR_SOAL_CRITICAL_TAMBAHAN A 
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

    function selectByParamsJawabanCriticalNew($paramsArray=array(),$limit=-1,$from=-1,$statement="", $statement2="", $order="")
	{
		$str= "
		SELECT A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID, A.NAMA,B.JAWABAN,B.PEGAWAI_ID
		FROM PORTAL.FORMULIR_SOAL_CRITICAL_TAMBAHAN A
		LEFT JOIN PORTAL.FORMULIR_CRITICAL_JAWABAN B ON A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID = B.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID AND ".$statement2."
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



  
  } 
?>
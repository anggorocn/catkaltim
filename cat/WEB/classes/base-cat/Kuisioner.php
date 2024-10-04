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

  class Kuisioner extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Kuisioner()
	{
      $this->Entity(); 
    }

    function selectByParamsTipe($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" order by kuisioner_tipe_id asc")
	{
		$str = "
		SELECT *
		FROM cat.kuisioner_tipe A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT *
		FROM cat.kuisioner A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

       function selectByParamsJawabanMaster($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT*
		FROM cat.kuisioner_pilihan A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
   }

  function getCountByParams($paramsArray=array(),$statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM kuisioner WHERE 1=1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$order;
		
		// echo $str; exit;

		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

 function insert()
	{
	// echo "Dadad"; exit;

		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KUISIONER_ID", $this->getNextId("KUISIONER_ID","KUISIONER")); 

		$str = "INSERT INTO KUISIONER (
				   KUISIONER_ID, KUISIONER_PERTANYAAN_ID, KUISIONER_JAWABAN_ID, KUISIONER_DETIL, 
				   PEGAWAI_ID, UJIAN_ID) 
				VALUES (
				  ".$this->getField("KUISIONER_ID").",
				  ".$this->getField("KUISIONER_PERTANYAAN_ID").",
				  ".$this->getField("KUISIONER_JAWABAN_ID").",
				  '".$this->getField("KUISIONER_DETIL")."',
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("UJIAN_ID")."
				)"; 
				
		$this->id = $this->getField("KUISIONER_ID");
		$this->query = $str;
		// echo $str; exit;
		return $this->execQuery($str);
    }

     function selectByParamsJawaban($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
	{
		$str = "
		SELECT*
		FROM kuisioner A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
   }

   function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE KUISIONER SET
				  PEGAWAI_ID				= ".$this->getField("PEGAWAI_ID").",
				  KUISIONER_PERTANYAAN_ID					= '".$this->getField("KUISIONER_PERTANYAAN_ID")."',
				  KUISIONER_JAWABAN_ID			= ".$this->getField("KUISIONER_JAWABAN_ID").",
				  KUISIONER_DETIL			= '".$this->getField("KUISIONER_DETIL")."',
				  UJIAN_ID			= '".$this->getField("UJIAN_ID")."'
				WHERE KUISIONER_ID 	= ".$this->getField("KUISIONER_ID")."
				"; 
				$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

  } 

?>
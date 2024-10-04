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
  * Entity-base class untuk mengimplementasikan tabel JENIS_PENILAIAN.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class Pertanyaan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pertanyaan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PERTANYAAN_ID", $this->getNextId("PERTANYAAN_ID","pertanyaan")); 
		
		$str = "
				INSERT INTO pertanyaan (
				   PERTANYAAN_ID, KATEGORI_ID, PERTANYAAN, URUT, BOBOT) 
 			  	VALUES (
				  ".$this->getField("PERTANYAAN_ID").",
				  ".$this->getField("KATEGORI_ID").",
				  '".$this->getField("PERTANYAAN")."',
				  ".$this->getField("URUT").",
				  ".$this->getField("BOBOT")."
				)"; 
		$this->id = $this->getField("PERTANYAAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE pertanyaan
				SET    
					   KATEGORI_ID		= ".$this->getField("KATEGORI_ID").",
					   PERTANYAAN		= '".$this->getField("PERTANYAAN")."',
					   URUT			= ".$this->getField("URUT").",
					   BOBOT			= ".$this->getField("BOBOT")."
				WHERE  PERTANYAAN_ID  	= '".$this->getField("PERTANYAAN_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM pertanyaan
                WHERE 
                  PERTANYAAN_ID = ".$this->getField("PERTANYAAN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.KATEGORI_ID, A.URUT ASC ")
	{
		$str = "
				SELECT 
				PERTANYAAN_ID, A.KATEGORI_ID, PERTANYAAN, A.URUT, B.NAMA KATEGORI_NAMA, BOBOT,
                (SELECT COUNT(JAWABAN_ID) FROM jawaban X WHERE A.PERTANYAAN_ID = X.PERTANYAAN_ID) JUMLAH 
				FROM pertanyaan A
				LEFT JOIN kategori B ON B.KATEGORI_ID = A.KATEGORI_ID		
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.KATEGORI_ID, A.URUT ASC ")
	{
		$str = "
				SELECT 
				A.PERTANYAAN_ID, A.KATEGORI_ID, PERTANYAAN, A.URUT, B.NAMA KATEGORI_NAMA, BOBOT,
                (SELECT COUNT(JAWABAN_ID) FROM jawaban X WHERE A.PERTANYAAN_ID = X.PERTANYAAN_ID) JUMLAH,
                PERILAKU_KERJA_ID, C.JAWABAN_ID, C.RANGE_1
				FROM pertanyaan A
				LEFT JOIN kategori B ON B.KATEGORI_ID = A.KATEGORI_ID		
				LEFT JOIN perilaku_kerja C ON A.PERTANYAAN_ID = C.PERTANYAAN_ID AND A.TAHUN = C.TAHUN
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	    
	function selectByParamsEdit($paramsArray=array(),$limit=-1,$from=-1, $statement="", $reqPeriode="", $reqTipe="")
	{
		$str = "
				SELECT A.PERTANYAAN_ID, B.PERTANYAAN_ID PERTANYAAN_ID_PERTANYAAN, A.KATEGORI_ID, PERTANYAAN, A.URUT, C.NAMA KATEGORI_NAMA, B.PERIODE, B.TIPE
				FROM pertanyaan A 
				LEFT JOIN pertanyaan_periode B ON B.PERIODE = '".$reqPeriode."' AND B.TIPE = '".$reqTipe."' AND B.PERTANYAAN_ID = A.PERTANYAAN_ID
				LEFT JOIN kategori C ON C.KATEGORI_ID = A.KATEGORI_ID
				WHERE 1 = 1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT 
				PERTANYAAN_ID, KATEGORI_ID, PERTANYAAN, URUT, BOBOT
				FROM pertanyaan		
				WHERE 1 = 1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY PERTANYAAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERTANYAAN_ID) AS ROWCOUNT FROM pertanyaan
		        WHERE PERTANYAAN_ID IS NOT NULL ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PERTANYAAN_ID) AS ROWCOUNT FROM pertanyaan
		        WHERE PERTANYAAN_ID IS NOT NULL ".$statement; 
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
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

  class FormasiDetil extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function FormasiDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("FORMASI_DETIL_ID", $this->getNextId("FORMASI_DETIL_ID","FORMASI_DETIL")); 

		$str = "INSERT INTO FORMASI_DETIL(FORMASI_DETIL_ID, FORMASI_ID,
				  NAMA, KODE, TUGAS, PENDIDIKAN_INFO, PENGALAMAN_MINIMAL, KEAHLIAN, USIA, JUMLAH_KEBUTUHAN
				  )
				VALUES(
				  ".$this->getField("FORMASI_DETIL_ID").",
				  ".$this->getField("FORMASI_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KODE")."',
				  '".$this->getField("TUGAS")."',
				  '".$this->getField("PENDIDIKAN_INFO")."',
				  '".$this->getField("PENGALAMAN_MINIMAL")."',
				  '".$this->getField("KEAHLIAN")."',
				  '".$this->getField("USIA")."',
				  ".$this->getField("JUMLAH_KEBUTUHAN")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("FORMASI_DETIL_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE FORMASI_DETIL SET
				  NAMA= '".$this->getField("NAMA")."',
				  KODE= '".$this->getField("KODE")."',
				  TUGAS= '".$this->getField("TUGAS")."',
				  PENDIDIKAN_INFO= '".$this->getField("PENDIDIKAN_INFO")."',
				  PENGALAMAN_MINIMAL= '".$this->getField("PENGALAMAN_MINIMAL")."',
				  KEAHLIAN= '".$this->getField("KEAHLIAN")."',
				  USIA= '".$this->getField("USIA")."',
				  JUMLAH_KEBUTUHAN= ".$this->getField("JUMLAH_KEBUTUHAN")."
				WHERE FORMASI_DETIL_ID= '".$this->getField("FORMASI_DETIL_ID")."'
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
				DELETE FROM FORMASI_DETIL
                WHERE 
                  FORMASI_DETIL_ID = '".$this->getField("FORMASI_DETIL_ID")."'
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
		$str = "SELECT FORMASI_DETIL_ID, md5(CAST(FORMASI_DETIL_ID as TEXT)) FORMASI_DETIL_ID_ENKRIP, FORMASI_ID,
				  NAMA, KODE, TUGAS, PENDIDIKAN_INFO, PENGALAMAN_MINIMAL, KEAHLIAN, USIA, JUMLAH_KEBUTUHAN, TUGAS_NEW, FUNGSI
				  , CASE WHEN USIA_AKHIR IS NOT NULL THEN CAST(USIA || ' - ' || USIA_AKHIR as TEXT) || ' Tahun' ELSE CAST(USIA as TEXT) || ' Tahun' END USIA_INFO
				  , PENEMPATAN
				FROM FORMASI_DETIL WHERE 1=1 "; 
		
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
		$str = "SELECT A.FORMASI_DETIL_ID, md5(CAST(A.FORMASI_DETIL_ID as TEXT)) FORMASI_DETIL_ID_ENKRIP, A.FORMASI_ID,
				  A.NAMA, A.KODE, C.NAMA NAMA_FORMASI,
				  CASE A.KODE WHEN 'Lain-Lain' THEN 'text' ELSE 'hidden' END KODE_TYPE, A.TUGAS, A.TUGAS_NEW, A.FUNGSI,
				  A.PENDIDIKAN_INFO, A.PENGALAMAN_MINIMAL, A.KEAHLIAN, A.USIA, A.JUMLAH_KEBUTUHAN,
                  B.JUMLAH_PELAMAR, CASE WHEN A.USIA_AKHIR IS NOT NULL THEN CAST(A.USIA || ' - ' || A.USIA_AKHIR as TEXT) || ' Tahun' ELSE CAST(A.USIA as TEXT) || ' Tahun' END USIA_INFO
				  , A.PENEMPATAN
				FROM FORMASI_DETIL A 
                LEFT JOIN 
                (
                SELECT COUNT(FORMASI_ID) JUMLAH_PELAMAR, FORMASI_ID
                FROM PELAMAR
                WHERE IS_DAFTAR IS NOT NULL
                GROUP BY FORMASI_ID
                ) B ON B.FORMASI_ID = A.FORMASI_DETIL_ID
				INNER JOIN FORMASI C ON A.FORMASI_ID = C.FORMASI_ID
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM FORMASI_DETIL WHERE 1=1 ".$statement; 
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
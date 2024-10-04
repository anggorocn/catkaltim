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

  class Penilaian extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Penilaian()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENILAIAN_ID", $this->getNextId("PENILAIAN_ID","penilaian"));
		
		$str = "INSERT INTO penilaian (
				   PENILAIAN_ID, PEGAWAI_ID, TANGGAL_TES, JABATAN_TES_ID, SATKER_TES_ID, ASPEK_ID, ESELON) 
				VALUES (
				  ".$this->getField("PENILAIAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("TANGGAL_TES").",
				  '".$this->getField("JABATAN_TES_ID")."',
				  '".$this->getField("SATKER_TES_ID")."',
				  ".$this->getField("ASPEK_ID").",
				  ".$this->getField("ESELON")."
				)"; 
				
		$this->query = $str;
		$this->id = $this->getField("PENILAIAN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE penilaian
				SET
				   PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				   TANGGAL_TES= ".$this->getField("TANGGAL_TES").",
				   JABATAN_TES_ID= '".$this->getField("JABATAN_TES_ID")."',
				   SATKER_TES_ID= '".$this->getField("SATKER_TES_ID")."',
				   ASPEK_ID= ".$this->getField("ASPEK_ID")."
				WHERE PENILAIAN_ID= '".$this->getField("PENILAIAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE penilaian
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  PENILAIAN_ID = '".$this->getField("PENILAIAN_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
		$str1= "DELETE FROM penilaian_detil
                WHERE 
                  PENILAIAN_ID = '".$this->getField("PENILAIAN_ID")."'"; 
		$this->query = $str1;
        $this->execQuery($str1);
				  
        $str = "DELETE FROM penilaian
                WHERE 
                  PENILAIAN_ID = '".$this->getField("PENILAIAN_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_ID ASC")
	{
		$str = "
				SELECT A.PENILAIAN_ID, A.PEGAWAI_ID, A.TANGGAL_TES, A.JABATAN_TES_ID, A.JABATAN_TES_ID JABATAN_TES,
				A.SATKER_TES_ID, B.NAMA_UNKER SATKER_TES, A.ASPEK_ID, CASE WHEN A.ASPEK_ID = '1' THEN 'Aspek Psikologi' WHEN A.ASPEK_ID = '2' THEN 'Aspek Kompetensi' ELSE '' END ASPEK_NAMA
				FROM penilaian A
				INNER JOIN dbsimpeg.rb_ref_unker B ON A.SATKER_TES_ID = B.KODE_UNKER
				WHERE 1=1
				"; 
		//INNER JOIN dbsimpeg.JABATAN C ON A.JABATAN_TES_ID = C.JABATAN_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM penilaian A
		WHERE 1=1 "; 
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
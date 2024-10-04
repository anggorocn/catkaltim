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

  class RiwayatPendidikan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RiwayatPendidikan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("RIWAYAT_PENDIDIKAN_ID", $this->getNextId("RIWAYAT_PENDIDIKAN_ID","RIWAYAT_PENDIDIKAN")); 

		$str = "INSERT INTO RIWAYAT_PENDIDIKAN(RIWAYAT_PENDIDIKAN_ID, PELAMAR_ID, PENDIDIKAN_ID,
				  SEKOLAH_ID, JURUSAN_PENDIDIKAN, NO_IJAZAH, IPK, IPK_LUAR, TAHUN_DIK_AWAL, TAHUN_DIK_AKHIR,
				  AKREDITASI_JURUSAN, AKREDITASI_SEKOLAH, PROPINSI_ID, KABUPATEN_ID, IS_LULUSAN_LUAR_NEGERI, UNIVERSITAS, KOTA, KOTA_LUAR, NEGARA_LUAR, JURUSAN_ID)
				VALUES(
				  ".$this->getField("RIWAYAT_PENDIDIKAN_ID").",
				  ".$this->getField("PELAMAR_ID").",
				  ".$this->getField("PENDIDIKAN_ID").",
				  ".$this->getField("SEKOLAH_ID").",
				  '".$this->getField("JURUSAN_PENDIDIKAN")."',
				  '".$this->getField("NO_IJAZAH")."',
				  '".$this->getField("IPK")."',
				  '".$this->getField("IPK_LUAR")."',
				  '".$this->getField("TAHUN_DIK_AWAL")."',
				  '".$this->getField("TAHUN_DIK_AKHIR")."',
				  '".$this->getField("AKREDITASI_JURUSAN")."',
				  '".$this->getField("AKREDITASI_SEKOLAH")."',
				  ".$this->getField("PROPINSI_ID").",
				  ".$this->getField("KABUPATEN_ID").",
				  ".$this->getField("IS_LULUSAN_LUAR_NEGERI").",
				  '".$this->getField("UNIVERSITAS")."',
				  '".$this->getField("KOTA")."',
				  '".$this->getField("KOTA_LUAR")."',
				  '".$this->getField("NEGARA_LUAR")."',
				  ".$this->getField("JURUSAN_ID")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("RIWAYAT_PENDIDIKAN_ID");
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE RIWAYAT_PENDIDIKAN SET
				  PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID").",
				  SEKOLAH_ID= ".$this->getField("SEKOLAH_ID").",
				  JURUSAN_PENDIDIKAN= '".$this->getField("JURUSAN_PENDIDIKAN")."',
				  NO_IJAZAH= '".$this->getField("NO_IJAZAH")."',
				  IPK= '".$this->getField("IPK")."',
				  IPK_LUAR= '".$this->getField("IPK_LUAR")."',
				  TAHUN_DIK_AWAL= '".$this->getField("TAHUN_DIK_AWAL")."',
				  TAHUN_DIK_AKHIR= '".$this->getField("TAHUN_DIK_AKHIR")."',
				  AKREDITASI_JURUSAN= '".$this->getField("AKREDITASI_JURUSAN")."',
				  AKREDITASI_SEKOLAH= '".$this->getField("AKREDITASI_SEKOLAH")."',
				  PROPINSI_ID= ".$this->getField("PROPINSI_ID").",
				  KABUPATEN_ID= ".$this->getField("KABUPATEN_ID").",
				  IS_LULUSAN_LUAR_NEGERI= ".$this->getField("IS_LULUSAN_LUAR_NEGERI").",
				  UNIVERSITAS= '".$this->getField("UNIVERSITAS")."',
				  KOTA= '".$this->getField("KOTA")."',
				  KOTA_LUAR= '".$this->getField("KOTA_LUAR")."',
				  NEGARA_LUAR= '".$this->getField("NEGARA_LUAR")."',
				  JURUSAN_ID= ".$this->getField("JURUSAN_ID")."
				WHERE RIWAYAT_PENDIDIKAN_ID= '".$this->getField("RIWAYAT_PENDIDIKAN_ID")."'
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
				DELETE FROM RIWAYAT_PENDIDIKAN
                WHERE 
                  RIWAYAT_PENDIDIKAN_ID = '".$this->getField("RIWAYAT_PENDIDIKAN_ID")."'
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
		$str = "SELECT RIWAYAT_PENDIDIKAN_ID, md5(CAST(PELAMAR_ID as TEXT)) PELAMAR_ID_ENKRIP, PELAMAR_ID, PENDIDIKAN_ID,
				  SEKOLAH_ID, JURUSAN_PENDIDIKAN, NO_IJAZAH, IPK, IPK_LUAR, TAHUN_DIK_AWAL, TAHUN_DIK_AKHIR,
				  AKREDITASI_JURUSAN, AKREDITASI_SEKOLAH, IJAZAH_FILE, TRANSKIP_NILAI_FILE, PROPINSI_ID, KABUPATEN_ID, IS_LULUSAN_LUAR_NEGERI, UNIVERSITAS, KOTA,
				  KOTA_LUAR, NEGARA_LUAR, JURUSAN_ID,
				  FILE_1, FILE_2, FILE_3, FILE_4, FILE_5, FILE_6, FILE_7, FILE_8, FILE_9, FILE_10, FILE_11, FILE_12, FILE_13, FILE_14, FILE_15, FILE_16
				FROM RIWAYAT_PENDIDIKAN WHERE 1=1"; 
		
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
		$str = "SELECT A.RIWAYAT_PENDIDIKAN_ID, A.PELAMAR_ID, A.PENDIDIKAN_ID,
				  A.SEKOLAH_ID, A.JURUSAN_PENDIDIKAN, A.NO_IJAZAH, A.IPK, A.IPK_LUAR, A.TAHUN_DIK_AWAL, A.TAHUN_DIK_AKHIR,
				  A.AKREDITASI_JURUSAN, A.AKREDITASI_SEKOLAH, A.IJAZAH_FILE,
				  A.TRANSKIP_NILAI_FILE, B.PROPINSI_ID, B.KABUPATEN_ID, IS_LULUSAN_LUAR_NEGERI, A.UNIVERSITAS, A.KOTA, 
				  A.KOTA_LUAR, A.NEGARA_LUAR
				  , E.PENDIDIKAN PENDIDIKAN_NAMA, CASE IS_LULUSAN_LUAR_NEGERI WHEN '1' THEN 'Ya' ELSE 'Tidak' END IS_LULUSAN_LUAR_NEGERI_NAMA
				  , A.JURUSAN_ID, F.NAMA JURUSAN_NAMA
				  ,A.FILE_1, A.FILE_2, A.FILE_3, A.FILE_4, A.FILE_5, A.FILE_6, A.FILE_7, A.FILE_8, A.FILE_9, A.FILE_10, A.FILE_11, A.FILE_12, A.FILE_13, A.FILE_14, A.FILE_15, A.FILE_16
				FROM RIWAYAT_PENDIDIKAN A 
				LEFT JOIN SEKOLAH B ON A.SEKOLAH_ID = B.SEKOLAH_ID
				LEFT JOIN PROPINSI C ON C.PROPINSI_ID = B.PROPINSI_ID
				LEFT JOIN KABUPATEN D ON D.KABUPATEN_ID = B.KABUPATEN_ID
				LEFT JOIN PENDIDIKAN E ON E.PENDIDIKAN_ID = A.PENDIDIKAN_ID
				LEFT JOIN JURUSAN F ON F.JURUSAN_ID = A.JURUSAN_ID
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
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM RIWAYAT_PENDIDIKAN WHERE 1=1 ".$statement; 
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
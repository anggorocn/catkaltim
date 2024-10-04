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
  * Entity-base class untuk mengimplementasikan tabel tugas_belajar.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class TugasBelajar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TugasBelajar()
	{
	  $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TUGAS_BELAJAR_ID", $this->getNextId("TUGAS_BELAJAR_ID","tugas_belajar")); 		

		$str = "
				INSERT INTO tugas_belajar (
				    TUGAS_BELAJAR_ID, PEGAWAI_ID, NO_SK, 
   					JURUSAN, PENDIDIKAN, NAMA_SEKOLAH, SATKER_ID, SATKER_ID_ESELON, STATUS_IJIN, STATUS_BELAJAR, TMT_MULAI, TMT_SELESAI, TMT_PERPANJANGAN, TMT_AKTIF,
					PEMBIAYAAN,
					LAST_CREATE_USER, LAST_CREATE_DATE, LAST_CREATE_SATKER, TIPE_TUGAS)
 			  	VALUES (
				  ".$this->getField("TUGAS_BELAJAR_ID").",
				  ".$this->getField("PEGAWAI_ID").",
  				  '".$this->getField("NO_SK")."',
   				  '".$this->getField("JURUSAN")."',
				  '".$this->getField("PENDIDIKAN")."',
				  '".$this->getField("NAMA_SEKOLAH")."',
				  '".$this->getField("SATKER_ID")."',
				  '".$this->getField("SATKER_ID_ESELON")."',
				  '".$this->getField("STATUS_IJIN")."',
				  '".$this->getField("STATUS_BELAJAR")."',
				  ".$this->getField("TMT_MULAI").",
				  ".$this->getField("TMT_SELESAI").",
				  ".$this->getField("TMT_PERPANJANGAN").",
				  ".$this->getField("TMT_AKTIF").",
				  '".$this->getField("PEMBIAYAAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_SATKER")."',
				  ".$this->getField("TIPE_TUGAS")."
				)"; 
		$this->query = $str;
		$this->id = $this->getField("TUGAS_BELAJAR_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE tugas_belajar
				SET    
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					NO_SK= '".$this->getField("NO_SK")."',
					JURUSAN= '".$this->getField("JURUSAN")."',
					PENDIDIKAN= '".$this->getField("PENDIDIKAN")."',
					NAMA_SEKOLAH= '".$this->getField("NAMA_SEKOLAH")."',
					SATKER_ID= '".$this->getField("SATKER_ID")."',
					SATKER_ID_ESELON= '".$this->getField("SATKER_ID_ESELON")."',
					STATUS_IJIN= '".$this->getField("STATUS_IJIN")."',
					STATUS_BELAJAR= '".$this->getField("STATUS_BELAJAR")."',
					TMT_MULAI= ".$this->getField("TMT_MULAI").",
					TMT_SELESAI= ".$this->getField("TMT_SELESAI").",
					TMT_PERPANJANGAN= ".$this->getField("TMT_PERPANJANGAN").",
					TMT_AKTIF= ".$this->getField("TMT_AKTIF").",
					PEMBIAYAAN= '".$this->getField("PEMBIAYAAN")."',
					LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
					LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
					LAST_UPDATE_SATKER= '".$this->getField("LAST_UPDATE_SATKER")."',
					TIPE_TUGAS	= ".$this->getField("TIPE_TUGAS")."
				WHERE  TUGAS_BELAJAR_ID  = '".$this->getField("TUGAS_BELAJAR_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function updatePerpanjangan()
	{
		$str = "
				UPDATE tugas_belajar
				SET
					STATUS_BELAJAR= '2',
					TMT_SELESAI= ".$this->getField("TMT_SELESAI").",
					LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
					LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").",
					LAST_UPDATE_SATKER= '".$this->getField("LAST_UPDATE_SATKER")."'
				WHERE  TUGAS_BELAJAR_ID  = '".$this->getField("TUGAS_BELAJAR_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE tugas_belajar A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE TUGAS_BELAJAR_ID = ".$this->getField("TUGAS_BELAJAR_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM tugas_belajar
                WHERE 
                  TUGAS_BELAJAR_ID = ".$this->getField("TUGAS_BELAJAR_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function deleteAll()
	{
		$str1 = "DELETE FROM tugas_belajar_lapor
                WHERE 
                  TUGAS_BELAJAR_ID = ".$this->getField("TUGAS_BELAJAR_ID").""; 
				  
		$this->query = $str1;
        $this->execQuery($str1);
		
        $str = "DELETE FROM tugas_belajar
                WHERE 
                  TUGAS_BELAJAR_ID = ".$this->getField("TUGAS_BELAJAR_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.PEGAWAI_ID ASC ")
	{
		$str = "
			SELECT 
				TUGAS_BELAJAR_ID, PEGAWAI_ID, NO_SK, JURUSAN, PENDIDIKAN, NAMA_SEKOLAH, A.PEMBIAYAAN, A.TIPE_TUGAS,
				SATKER_ID, SATKER_ID_ESELON, STATUS_IJIN, STATUS_BELAJAR, TMT_MULAI, 
				TMT_SELESAI, DATE_ADD(TMT_SELESAI, INTERVAL 1 YEAR) TMT_SELESAI_PERPANJANGAN, TMT_PERPANJANGAN, TMT_AKTIF,
				B.NAMA NAMA_PEGAWAI, C.NAMA_UNKER,
				CASE 
				WHEN A.STATUS_BELAJAR = '1' THEN 'Tugas Belajar'
				WHEN A.STATUS_BELAJAR = '2' THEN 'Perpanjangan Tugas Belajar'
				WHEN A.STATUS_BELAJAR = '3' THEN 'Pengaktifan Tugas Belajar'
				END KETERANGAN_STATUS_BELAJAR,
				CASE 
				WHEN  ((TMT_SELESAI - INTERVAL 3 MONTH) > CURRENT_DATE AND CURRENT_DATE <= TMT_SELESAI) AND (A.STATUS_BELAJAR = '1' OR A.STATUS_BELAJAR = '2')  THEN 1
				WHEN  ((TMT_SELESAI - INTERVAL 3 MONTH) <= CURRENT_DATE AND CURRENT_DATE <= TMT_SELESAI) AND (A.STATUS_BELAJAR = '1' OR A.STATUS_BELAJAR = '2')  THEN 2
				WHEN  CURRENT_DATE > TMT_SELESAI  AND (A.STATUS_BELAJAR = '1' OR A.STATUS_BELAJAR = '2')  THEN 3
				WHEN A.STATUS_BELAJAR ='3' THEN 4
				END STATUS_INFO_DATA
			FROM tugas_belajar A 
			LEFT JOIN ".$this->db.".rb_data_pegawai B ON B.IDPEG = A.PEGAWAI_ID
			LEFT JOIN ".$this->db.".rb_ref_unker C ON C.KODE_UNKER = A.SATKER_ID
			WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsBak($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.PEGAWAI_ID ASC ")
	{
		$str = "
			SELECT 
				TUGAS_BELAJAR_ID, PEGAWAI_ID, NO_SK, JURUSAN, PENDIDIKAN, NAMA_SEKOLAH, A.PEMBIAYAAN, A.TIPE_TUGAS,
				SATKER_ID, SATKER_ID_ESELON, STATUS_IJIN, STATUS_BELAJAR, TMT_MULAI, 
				TMT_SELESAI, DATE_ADD(TMT_SELESAI, INTERVAL 1 YEAR) TMT_SELESAI_PERPANJANGAN, TMT_PERPANJANGAN, TMT_AKTIF,
				B.NAMA NAMA_PEGAWAI, C.NAMA_UNKER,
				CASE 
				WHEN A.STATUS_BELAJAR = '1' THEN 'Tugas Belajar'
				WHEN A.STATUS_BELAJAR = '2' THEN 'Perpanjangan Tugas Belajar'
				WHEN A.STATUS_BELAJAR = '3' THEN 'Pengaktifan Tugas Belajar'
				END KETERANGAN_STATUS_BELAJAR,
				CASE 
				WHEN (TMT_SELESAI - INTERVAL 3 MONTH) <= CURRENT_DATE AND TMT_SELESAI > CURRENT_DATE AND A.STATUS_BELAJAR = '1' THEN 1
				WHEN TMT_SELESAI <= CURRENT_DATE AND A.STATUS_BELAJAR = '1' THEN 2
				WHEN A.STATUS_BELAJAR ='3' THEN 3
				ELSE 0
				END STATUS_INFO_DATA
			FROM tugas_belajar A 
			LEFT JOIN ".$this->db.".rb_data_pegawai B ON B.IDPEG = A.PEGAWAI_ID
			LEFT JOIN ".$this->db.".rb_ref_unker C ON C.KODE_UNKER = A.SATKER_ID
			WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT
						   TUGAS_BELAJAR_ID, PEGAWAI_ID, NO_SK, JURUSAN
					FROM tugas_belajar A WHERE TUGAS_BELAJAR_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NO_SK ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TUGAS_BELAJAR_ID) AS ROWCOUNT FROM tugas_belajar A
		        WHERE 1=1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(TUGAS_BELAJAR_ID) AS ROWCOUNT FROM tugas_belajar A
		        WHERE TUGAS_BELAJAR_ID IS NOT NULL ".$statement; 
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
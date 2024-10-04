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
  * Entity-base class untuk mengimplementasikan tabel PEGAWAI_PENILAI.
  * 
  ***/
  include_once("../WEB/classes/db/Entity.php");

  class PegawaiPenilai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PegawaiPenilai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_PENILAI_ID", $this->getNextId("PEGAWAI_PENILAI_ID","pegawai_penilai")); 		

		$str = "
				INSERT INTO pegawai_penilai (
				    PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
   					PEGAWAI_ID_DINILAI, STATUS)  
 			  	VALUES (
				  ".$this->getField("PEGAWAI_PENILAI_ID").",
				  ".$this->getField("TAHUN").",
				  ".$this->getField("PEGAWAI_ID_PENILAI").",
				  ".$this->getField("PEGAWAI_ID_DINILAI").",
				  '".$this->getField("STATUS")."'
				)"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function execProsesPegawaiPenilai()
	{
		$str = "
				CALL PROSES_PEGAWAI_PENILAI('".$this->getField("SATKER_ID")."')
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
		
	function update()
	{
		$str = "
				UPDATE pegawai_penilai
				SET    						
				  		TAHUN				= ".$this->getField("TAHUN").",
				  		PEGAWAI_ID_PENILAI	= ".$this->getField("PEGAWAI_ID_PENILAI").",
				  		PEGAWAI_ID_DINILAI	= ".$this->getField("PEGAWAI_ID_DINILAI").",
				  		STATUS				= '".$this->getField("STATUS")."'				   
				WHERE  PEGAWAI_PENILAI_ID  = '".$this->getField("PEGAWAI_PENILAI_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }


    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE pegawai_penilai A SET
				  ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE PEGAWAI_PENILAI_ID = ".$this->getField("PEGAWAI_PENILAI_ID")."
				"; 
				$this->query = $str;
	
		return $this->execQuery($str);
    }	

	function delete()
	{
        $str = "DELETE FROM pegawai_penilai
                WHERE 
                  PEGAWAI_PENILAI_ID = ".$this->getField("PEGAWAI_PENILAI_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY A.PEGAWAI_PENILAI_ID DESC ")
	{
		$str = "
					SELECT 
						   PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
  						   PEGAWAI_ID_DINILAI, STATUS
					FROM pegawai_penilai A WHERE PEGAWAI_PENILAI_ID IS NOT NULL
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsMonitoringDaftar($tahun, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY B.PEGAWAI_PENILAI_ID DESC ")
	{
		/*$str = "
   				  SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
                    CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
                    C.GOL_RUANG,
					D.ESELON,
					D.JABATAN,
                    PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
  					PEGAWAI_ID_DINILAI, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 1, B.TAHUN) BL1, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 2, B.TAHUN) BL2, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 3, B.TAHUN) BL3, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 4, B.TAHUN) BL4, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 5, B.TAHUN) BL5, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 6, B.TAHUN) BL6, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 7, B.TAHUN) BL7, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 8, B.TAHUN) BL8, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 9, B.TAHUN) BL9, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 10, B.TAHUN) BL10, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 11, B.TAHUN) BL11, 
                    AMBIL_STATUS_ENTRI(A.IDPEG, 12, B.TAHUN) BL12
					FROM dbsimpeg.rb_data_pegawai A 
                    INNER JOIN PEGAWAI_PENILAI B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND TAHUN = '".$tahun."'
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K')  
				"; */
				
		$str = "
   				  SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
						    CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
  						    '' GOL_RUANG,
							'' ESELON,
					        '' JABATAN,
							PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
						    PEGAWAI_ID_DINILAI, 
						    AMBIL_STATUS_ENTRI(A.IDPEG, 1, B.TAHUN) BL1, 
						    AMBIL_STATUS_ENTRI(A.IDPEG, 2, B.TAHUN) BL2, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 3, B.TAHUN) BL3, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 4, B.TAHUN) BL4, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 5, B.TAHUN) BL5, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 6, B.TAHUN) BL6, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 7, B.TAHUN) BL7, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 8, B.TAHUN) BL8, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 9, B.TAHUN) BL9, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 10, B.TAHUN) BL10, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 11, B.TAHUN) BL11, 
							AMBIL_STATUS_ENTRI(A.IDPEG, 12, B.TAHUN) BL12
					FROM dbsimpeg.rb_data_pegawai A 
          			INNER JOIN pegawai_penilai B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND TAHUN = '".$tahun."'
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K') 
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsMonitoringPencapaian($tahun, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY B.PEGAWAI_PENILAI_ID DESC ")
	{
			/*
		$str = "
   				  SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
                    CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
                    PEGAWAI_PENILAI_ID, B.TAHUN, PEGAWAI_ID_PENILAI, 
                      PEGAWAI_ID_DINILAI, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 1, B.TAHUN) BL1, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 2, B.TAHUN) BL2, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 3, B.TAHUN) BL3, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 4, B.TAHUN) BL4, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 5, B.TAHUN) BL5, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 6, B.TAHUN) BL6, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 7, B.TAHUN) BL7, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 8, B.TAHUN) BL8, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 9, B.TAHUN) BL9, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 10, B.TAHUN) BL10, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 11, B.TAHUN) BL11, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 12, B.TAHUN) BL12,
                    MAX(CASE WHEN E.URUT = 1 THEN NILAI ELSE NULL END) PK1,
                    MAX(CASE WHEN E.URUT = 2 THEN NILAI ELSE NULL END) PK2,
                    MAX(CASE WHEN E.URUT = 3 THEN NILAI ELSE NULL END) PK3,
                    MAX(CASE WHEN E.URUT = 4 THEN NILAI ELSE NULL END) PK4,
                    MAX(CASE WHEN E.URUT = 5 THEN NILAI ELSE NULL END) PK5,
                    MAX(CASE WHEN E.URUT = 6 THEN NILAI ELSE NULL END) PK6,
                    MAX(CASE WHEN E.URUT = 7 THEN NILAI ELSE NULL END) PK7,
                    MAX(CASE WHEN E.URUT = 8 THEN NILAI ELSE NULL END) PK8
                    FROM dbsimpeg.rb_data_pegawai A 
                    INNER JOIN PEGAWAI_PENILAI B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND B.TAHUN = '".$tahun."'
                    LEFT JOIN (SELECT A.TAHUN, B.PEGAWAI_ID_DINILAI PEGAWAI_ID, A.KATEGORI_ID, C.URUT, C.NAMA KATEGORI, SUM(B.RANGE_1) / COUNT(1) NILAI FROM pertanyaan A
                                LEFT JOIN perilaku_kerja  B ON A.PERTANYAAN_ID = B.PERTANYAAN_ID AND A.TAHUN = B.TAHUN
                                LEFT JOIN kategori C ON A.KATEGORI_ID = C.KATEGORI_ID
                                GROUP BY A.TAHUN, PEGAWAI_ID_DINILAI, A.KATEGORI_ID, C.NAMA, C.URUT) E ON A.IDPEG = E.PEGAWAI_ID AND B.TAHUN = E.TAHUN
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K') 
				"; 
		*/
		
		$str = "SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
					CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
                    '' GOL_RUANG,
					'' ESELON,
					'' JABATAN,
					PEGAWAI_ID_DINILAI,
					PEGAWAI_PENILAI_ID, B.TAHUN, PEGAWAI_ID_PENILAI,
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 1, B.TAHUN) BL1, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 2, B.TAHUN) BL2, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 3, B.TAHUN) BL3, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 4, B.TAHUN) BL4, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 5, B.TAHUN) BL5, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 6, B.TAHUN) BL6, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 7, B.TAHUN) BL7, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 8, B.TAHUN) BL8, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 9, B.TAHUN) BL9, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 10, B.TAHUN) BL10, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 11, B.TAHUN) BL11, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 12, B.TAHUN) BL12,
                    MAX(CASE WHEN E.URUT = 1 THEN NILAI ELSE NULL END) PK1,
                    MAX(CASE WHEN E.URUT = 2 THEN NILAI ELSE NULL END) PK2,
                    MAX(CASE WHEN E.URUT = 3 THEN NILAI ELSE NULL END) PK3,
                    MAX(CASE WHEN E.URUT = 4 THEN NILAI ELSE NULL END) PK4,
                    MAX(CASE WHEN E.URUT = 5 THEN NILAI ELSE NULL END) PK5,
                    MAX(CASE WHEN E.URUT = 6 THEN NILAI ELSE NULL END) PK6,
                    MAX(CASE WHEN E.URUT = 7 THEN NILAI ELSE NULL END) PK7,
                    MAX(CASE WHEN E.URUT = 8 THEN NILAI ELSE NULL END) PK8
                    FROM dbsimpeg.rb_data_pegawai A 
                    INNER JOIN pegawai_penilai B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND B.TAHUN = '".$tahun."'
                    LEFT JOIN (
						SELECT A.TAHUN, B.PEGAWAI_ID_DINILAI PEGAWAI_ID, A.KATEGORI_ID, C.URUT, C.NAMA KATEGORI, SUM(B.RANGE_1) / COUNT(1) NILAI FROM pertanyaan A
						LEFT JOIN perilaku_kerja  B ON A.PERTANYAAN_ID = B.PERTANYAAN_ID AND A.TAHUN = B.TAHUN
						LEFT JOIN kategori C ON A.KATEGORI_ID = C.KATEGORI_ID
						GROUP BY A.TAHUN, PEGAWAI_ID_DINILAI, A.KATEGORI_ID, C.NAMA, C.URUT
					) E ON B.TAHUN = E.TAHUN
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K')
				";//A.IDPEG = E.PEGAWAI_ID AND 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		//$str .= $statement." GROUP BY A.IDPEG, NIP_LAMA, NIP_BARU, PEGAWAI_PENILAI_ID, B.TAHUN, PEGAWAI_ID_PENILAI  ".$order;
		/*$str .= $statement." GROUP BY A.IDPEG, NIP_LAMA, NIP_BARU, NAMA, C.GOL_RUANG, C.NMGOLRUANG, ESELON, JABATAN, PEGAWAI_PENILAI_ID, 
							 B.TAHUN, PEGAWAI_ID_PENILAI, PEGAWAI_ID_DINILAI, GELAR_DEPAN, GELAR_BELAKANG,
							 D.ESELON_ID , C.PANGKAT_ID ,  C.TMT_PANGKAT  ".$order;*/
		$str .= $statement." ";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	

	function selectByParamsMonitoringPencapaianCetak($tahun, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY B.PEGAWAI_PENILAI_ID DESC ")
	{
		$str = "SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
                    CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
                    '' GOL_RUANG, '' NMGOLRUANG,
                    '' ESELON,
                    '' JABATAN, '' SATKER, 
					PEGAWAI_PENILAI_ID, B.TAHUN, PEGAWAI_ID_PENILAI, 
                      PEGAWAI_ID_DINILAI, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 1, B.TAHUN) BL1, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 2, B.TAHUN) BL2, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 3, B.TAHUN) BL3, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 4, B.TAHUN) BL4, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 5, B.TAHUN) BL5, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 6, B.TAHUN) BL6, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 7, B.TAHUN) BL7, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 8, B.TAHUN) BL8, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 9, B.TAHUN) BL9, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 10, B.TAHUN) BL10, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 11, B.TAHUN) BL11, 
                    AMBIL_STATUS_PENCAPAIAN(A.IDPEG, 12, B.TAHUN) BL12,
                    MAX(CASE WHEN E.URUT = 1 THEN NILAI ELSE NULL END) PK1,
                    MAX(CASE WHEN E.URUT = 2 THEN NILAI ELSE NULL END) PK2,
                    MAX(CASE WHEN E.URUT = 3 THEN NILAI ELSE NULL END) PK3,
                    MAX(CASE WHEN E.URUT = 4 THEN NILAI ELSE NULL END) PK4,
                    MAX(CASE WHEN E.URUT = 5 THEN NILAI ELSE NULL END) PK5,
                    MAX(CASE WHEN E.URUT = 6 THEN NILAI ELSE NULL END) PK6,
                    MAX(CASE WHEN E.URUT = 7 THEN NILAI ELSE NULL END) PK7,
                    MAX(CASE WHEN E.URUT = 8 THEN NILAI ELSE NULL END) PK8
                    FROM dbsimpeg.rb_data_pegawai A 
                    INNER JOIN pegawai_penilai B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND B.TAHUN = '".$tahun."'
                    LEFT JOIN (SELECT A.TAHUN, B.PEGAWAI_ID_DINILAI PEGAWAI_ID, A.KATEGORI_ID, C.URUT, C.NAMA KATEGORI, SUM(B.RANGE_1) / COUNT(1) NILAI FROM pertanyaan A
                                LEFT JOIN perilaku_kerja  B ON A.PERTANYAAN_ID = B.PERTANYAAN_ID AND A.TAHUN = B.TAHUN
                                LEFT JOIN kategori C ON A.KATEGORI_ID = C.KATEGORI_ID
                                GROUP BY A.TAHUN, PEGAWAI_ID_DINILAI, A.KATEGORI_ID, C.NAMA, C.URUT) E ON A.IDPEG = E.PEGAWAI_ID AND B.TAHUN = E.TAHUN
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K')
				";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		/*$str .= $statement." GROUP BY A.IDPEG, NIP_LAMA, NIP_BARU, NAMA, C.GOL_RUANG, C.NMGOLRUANG, ESELON, JABATAN, PEGAWAI_PENILAI_ID, 
							 B.TAHUN, PEGAWAI_ID_PENILAI, PEGAWAI_ID_DINILAI, GELAR_DEPAN, GELAR_BELAKANG,
							 D.ESELON_ID , C.PANGKAT_ID ,  C.TMT_PANGKAT, SUBSTR(A.SATKER_ID, 0, 2)  ".$order;*/
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	
	function selectByParamsPenilaiCetakPrestasi($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "	SELECT
						   A.NIP_BARU, A.IDPEG, CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA, 
						   '' JABATAN,  '' DEPARTEMEN, '' GOL_RUANG, '' NMGOLRUANG,
						   E.PEGAWAI_ID_PENILAI
					FROM  dbsimpeg.rb_data_pegawai A 
                    LEFT JOIN pegawai_penilai E ON A.IDPEG = E.PEGAWAI_ID_DINILAI
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
		
	
	function selectByParamsMonitoringPenilaianPerilaku($tahun, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY B.PEGAWAI_PENILAI_ID DESC ")
	{
		$str = "
   				  SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
                    CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
                    C.GOL_RUANG,
					D.ESELON,
					D.JABATAN,
                    PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
  					PEGAWAI_ID_DINILAI
                    FROM dbsimpeg.rb_data_pegawai A 
                    INNER JOIN pegawai_penilai B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND TAHUN = '".$tahun."'
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K')  
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
			
    function selectByParamsMonitoring($tahun, $paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ORDER BY B.PEGAWAI_PENILAI_ID DESC ")
	{
		$str = "
   				  SELECT A.IDPEG, NIP_LAMA, NIP_BARU,
                    CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA,
                    '' GOL_RUANG,
					'' ESELON,
					'' JABATAN,
                    PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
  					PEGAWAI_ID_DINILAI
                    FROM dbsimpeg.rb_data_pegawai A 
                    INNER JOIN pegawai_penilai B ON A.IDPEG = B.PEGAWAI_ID_DINILAI AND TAHUN = '".$tahun."'
                    WHERE 1 = 1 AND (STATUS_PEG = 0 OR STATUS_PEG = 'K')  
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPenilai($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "	SELECT
						   A.NIP_BARU, A.IDPEG, A.NAMA, NAMA_JAB_STRUKTURAL JABATAN,  NAMA_UNKER DEPARTEMEN, '' GOL_RUANG, '' NMGOLRUANG
					FROM  dbsimpeg.rb_data_pegawai A 
					LEFT JOIN dbsimpeg.rb_ref_unker B ON A.KODE_UNKER = B.KODE_UNKER
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

	function selectByParamsDinilai($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "	SELECT
						   PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
   						   PEGAWAI_ID_DINILAI, STATUS, B.NIP_BARU, B.IDPEG, B.NAMA, '' JABATAN,  '' DEPARTEMEN 
					FROM pegawai_penilai A 
                    LEFT JOIN  dbsimpeg.rb_data_pegawai B ON A.PEGAWAI_ID_DINILAI = B.IDPEG
                    WHERE PEGAWAI_PENILAI_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function selectByParamsPenilaiCetak($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "	SELECT
						   PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
   						   PEGAWAI_ID_DINILAI, STATUS, B.NIP_BARU, B.IDPEG, B.NAMA, '' JABATAN,  '' DEPARTEMEN, 
						   '' GOL_RUANG, '' NMGOLRUANG, '' PANGKAT_GOL  
					FROM pegawai_penilai A 
                    LEFT JOIN  dbsimpeg.rb_data_pegawai B ON A.PEGAWAI_ID_PENILAI = B.IDPEG
                    WHERE 1=1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDinilaiCetak($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="")
	{
		$str = "	SELECT
						   PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
   						   PEGAWAI_ID_DINILAI, STATUS, B.NIP_BARU, B.IDPEG, B.NAMA, '' JABATAN,  '' DEPARTEMEN, 
						   '' GOL_RUANG, '' NMGOLRUANG, '' PANGKAT_GOL 
					FROM pegawai_penilai A 
                    LEFT JOIN  dbsimpeg.rb_data_pegawai B ON A.PEGAWAI_ID_DINILAI = B.IDPEG
					WHERE 1=1
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
  
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT
						   PEGAWAI_PENILAI_ID, TAHUN, PEGAWAI_ID_PENILAI, 
   						   PEGAWAI_ID_DINILAI, STATUS
					FROM pegawai_penilai A WHERE PEGAWAI_PENILAI_ID IS NOT NULL
			    "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY A.NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_PENILAI_ID) AS ROWCOUNT FROM pegawai_penilai A
		        WHERE PEGAWAI_PENILAI_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsMonitoring($tahun,$paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) ROWCOUNT
					FROM dbsimpeg.rb_data_pegawai A 
                    LEFT JOIN pegawai_penilai B ON A.IDPEG = B.PEGAWAI_ID_DINILAI   AND TAHUN = '".$tahun."'
                    LEFT JOIN (SELECT PEGAWAI_ID, CONCAT((CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE CONCAT(GELAR_DEPAN, '. ') END), A.NAMA, (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  CONCAT(', ', GELAR_BELAKANG) END)) NAMA FROM dbsimpeg.rb_data_pegawai A) E ON E.PEGAWAI_ID =  B.PEGAWAI_ID_PENILAI
                    WHERE 1 = 1  ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_PENILAI_ID) AS ROWCOUNT FROM pegawai_penilai A
		        WHERE PEGAWAI_PENILAI_ID IS NOT NULL ".$statement; 
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
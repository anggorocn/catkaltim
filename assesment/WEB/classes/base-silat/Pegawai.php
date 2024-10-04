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

  class Pegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pegawai()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","PEGAWAI")); 

		$str = "INSERT INTO PEGAWAI (
				   PEGAWAI_ID, PROPINSI_ID, KABUPATEN_ID, 
				   KECAMATAN_ID, KELURAHAN_ID, SATKER_ID, 
				   KEDUDUKAN_ID, JENIS_PEGAWAI_ID, BANK_ID, 
				   NIP_LAMA, NIP_BARU, NAMA, 
				   GELAR_DEPAN, GELAR_BELAKANG, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, STATUS_KAWIN, SUKU_BANGSA, GOLONGAN_DARAH,
				   EMAIL, ALAMAT, RT, RW, TELEPON, KODEPOS, STATUS_PEGAWAI, KARTU_PEGAWAI, ASKES, TASPEN,
				   NPWP, NIK, FOTO, FOTO_SETENGAH, NO_REKENING, TANGGAL_MATI, TANGGAL_PENSIUN, TANGGAL_TERUSAN, TANGGAL_UPDATE, TIPE_PEGAWAI_ID,
				   AGAMA_ID, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_CREATE_SATKER)
				VALUES (
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("PROPINSI_ID")."',
				  '".$this->getField("KABUPATEN_ID")."',
				  '".$this->getField("KECAMATAN_ID")."',
				  '".$this->getField("KELURAHAN_ID")."',
				  '".$this->getField("SATKER_ID")."',
				  '".$this->getField("KEDUDUKAN_ID")."',
				  '".$this->getField("JENIS_PEGAWAI_ID")."',
				  '".$this->getField("BANK_ID")."',
				  '".$this->getField("NIP_LAMA")."',
				  '".$this->getField("NIP_BARU")."',
				  '".$this->getField("NAMA")."',
				  '".$this->getField("GELAR_DEPAN")."',
				  '".$this->getField("GELAR_BELAKANG")."',
				  '".$this->getField("TEMPAT_LAHIR")."',
				  ".$this->getField("TANGGAL_LAHIR").",
				  '".$this->getField("JENIS_KELAMIN")."',
				  '".$this->getField("STATUS_KAWIN")."',
				  '".$this->getField("SUKU_BANGSA")."',
				  '".$this->getField("GOLONGAN_DARAH")."',
				  '".$this->getField("EMAIL")."',
				  '".$this->getField("ALAMAT")."',
				  '".$this->getField("RT")."',
				  '".$this->getField("RW")."',
				  '".$this->getField("TELEPON")."',
				  '".$this->getField("KODEPOS")."',
				  '".$this->getField("STATUS_PEGAWAI")."',
				  '".$this->getField("KARTU_PEGAWAI")."',
				  '".$this->getField("ASKES")."',
				  '".$this->getField("TASPEN")."',
				  '".$this->getField("NPWP")."',
				  '".$this->getField("NIK")."',
				  '".$this->getField("FOTO")."',
				  '".$this->getField("FOTO_SETENGAH")."',
				  '".$this->getField("NO_REKENING")."',
				  ".$this->getField("TANGGAL_MATI").",
				  ".$this->getField("TANGGAL_PENSIUN").",
				  ".$this->getField("TANGGAL_TERUSAN").",
				  '".$this->getField("TANGGAL_UPDATE")."',
				  '".$this->getField("TIPE_PEGAWAI")."',
				  '".$this->getField("AGAMA_ID")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  '".$this->getField("LAST_CREATE_SATKER")."'
				)"; 
				
		$this->query = $str;
		$this->pegawai_id = $this->getField("PEGAWAI_ID");
		
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PEGAWAI
				SET    
					   PROPINSI_ID       	= '".$this->getField("PROPINSI_ID")."',
					   KABUPATEN_ID    		= '".$this->getField("KABUPATEN_ID")."',
					   KECAMATAN_ID         = '".$this->getField("KECAMATAN_ID")."',
					   KELURAHAN_ID     	= '".$this->getField("KELURAHAN_ID")."',
					   SATKER_ID    		= '".$this->getField("SATKER_ID")."',
					   KEDUDUKAN_ID    		= '".$this->getField("KEDUDUKAN_ID")."',
					   JENIS_PEGAWAI_ID  	= '".$this->getField("JENIS_PEGAWAI_ID")."',
					   BANK_ID 				= '".$this->getField("BANK_ID")."',
					   NIP_LAMA        		= '".$this->getField("NIP_LAMA")."',
					   NIP_BARU       		= '".$this->getField("NIP_BARU")."',
					   NAMA      			= '".$this->getField("NAMA")."',
					   GELAR_DEPAN   		= '".$this->getField("GELAR_DEPAN")."',
					   GELAR_BELAKANG       = '".$this->getField("GELAR_BELAKANG")."',
					   TEMPAT_LAHIR         = '".$this->getField("TEMPAT_LAHIR")."',
					   TANGGAL_LAHIR        = ".$this->getField("TANGGAL_LAHIR").",
					   JENIS_KELAMIN        = '".$this->getField("JENIS_KELAMIN")."',
					   STATUS_KAWIN         = '".$this->getField("STATUS_KAWIN")."',
					   SUKU_BANGSA          = '".$this->getField("SUKU_BANGSA")."',
					   GOLONGAN_DARAH       = '".$this->getField("GOLONGAN_DARAH")."',
					   EMAIL             	= '".$this->getField("EMAIL")."',
					   ALAMAT             	= '".$this->getField("ALAMAT")."',
					   RT             		= '".$this->getField("RT")."',
					   RW             		= '".$this->getField("RW")."',
					   TELEPON             	= '".$this->getField("TELEPON")."',
					   KODEPOS            	= '".$this->getField("KODEPOS")."',
					   STATUS_PEGAWAI       = '".$this->getField("STATUS_PEGAWAI")."',
					   KARTU_PEGAWAI        = '".$this->getField("KARTU_PEGAWAI")."',
					   ASKES             	= '".$this->getField("ASKES")."',
					   TASPEN             	= '".$this->getField("TASPEN")."',
					   NPWP             	= '".$this->getField("NPWP")."',
					   NIK             		= '".$this->getField("NIK")."',
					   FOTO             	= '".$this->getField("FOTO")."',
					   FOTO_SETENGAH 		= '".$this->getField("FOTO_SETENGAH")."',
					   NO_REKENING          = '".$this->getField("NO_REKENING")."',					 
					   TANGGAL_PENSIUN      = ".$this->getField("TANGGAL_PENSIUN").",					  
					   TANGGAL_UPDATE       = sysdate,
					   TIPE_PEGAWAI_ID      = '".$this->getField("TIPE_PEGAWAI_ID")."',
					   AGAMA_ID      		= '".$this->getField("AGAMA_ID")."',
					  LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					  LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					  LAST_UPDATE_SATKER	= '".$this->getField("LAST_UPDATE_SATKER")."'
				WHERE  PEGAWAI_ID          	= '".$this->getField("PEGAWAI_ID")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM pegawai
                WHERE 
                  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function upload($table, $column, $blob, $id)
	{
		return $this->uploadBlob($table, $column, $blob, $id);
    }
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE PEGAWAI
				SET
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function callDUK()
	{
        $str = "
				CALL PINSERTDUK('".$this->getField("PERIODE")."', '".$this->getField("SATKERID")."', '".$this->getField("TIPEPEGAWAI")."')
		"; 
		
		/*$str = "
				CALL PINSERTDUK('".$this->getField("PERIODE")."', '".$this->getField("SATKERID")."', '".$this->getField("SATKERIDCHILD")."', '".$this->getField("TIPEPEGAWAI")."')
		";*/ 		  
		$this->query = $str;
		//echo $str;
        return $this->execQuery($str);
    }	

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","KECAMATAN_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
      SELECT  
	  CASE
			WHEN SYSDATE <= G.TANGGAL_AKHIR AND SYSDATE >= G.TANGGAL_MULAI
			THEN 1
			ELSE 0
		END STATUS_BERLAKU,
	  C.ESELON_ID, (SELECT x.TIPE_PEGAWAI_ID 
                    FROM pegawai x
                    WHERE x.PEGAWAI_ID = A.PEGAWAI_ID
                   ) TIPE_PEGAWAI, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN A.GELAR_DEPAN IS NULL THEN '' ELSE A.GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN A.GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || A.GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR, TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TANGGAL_LAHIR, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
						C.TMT_JABATAN TMT_JABATAN_FORMAT,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,
						E.SATKER_ID SATKER_ID,
                        '01-' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MM-YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A  
                     LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                     LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, NVL(ESELON_ID, 99) ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
					 LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
                     LEFT JOIN agama D ON  A.AGAMA_ID = D.AGAMA_ID
                     LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                     SATKER E              
                WHERE                     
                     A.SATKER_ID = E.SATKER_ID AND
                     A.STATUS_PEGAWAI IN (1, 2)
					 AND NOT EXISTS( SELECT X.PEGAWAI_ID FROM MUTASI_USULAN X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND X.SATKER_ID_LAMA = E.SATKER_ID AND X.STATUS IS NULL )
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsWebProfile($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
      SELECT  
	  ASKES, NPWP, TASPEN, NIK,
		KARTU_PEGAWAI,
		(SELECT x.TIPE_PEGAWAI_ID 
							FROM pegawai x
							WHERE x.PEGAWAI_ID = A.PEGAWAI_ID
						   ) TIPE_PEGAWAI,
		A.KODEPOS,
		RT, RW,
		A.EMAIL,
		GOLONGAN_DARAH,
		SUKU_BANGSA,
		DECODE(STATUS_KAWIN, 1, 'Belum Kawin', 2, 'Kawin', 3, 'Janda', 4, 'Duda') STATUS_KAWIN,
	  C.ESELON_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
      A.TIPE_PEGAWAI_ID, Y.USIA_TAHUN, NIP_BARU NIP_BARU_CARI,
                        TEMPAT_LAHIR, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        C.ESELON,
                        C.JABATAN,
                        B.TMT_PANGKAT,
                        C.TMT_JABATAN,
                        TANGGAL_LAHIR, 
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,
                        DECODE(SUBSTR(A.TIPE_PEGAWAI_ID, 1, 1),2,
                        '01-' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (60 * 12) + 1),  'MM-YYYY'),
                        '01-' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MM-YYYY')) TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS, CASE WHEN A.PEGAWAI_ID = E.PEGAWAI_ID THEN E.SATKER_ID ELSE 'A' END PEGAWAI_PEJABAT, A.SATKER_ID,
                        H.NAMA KEDUDUKAN, G.NAMA JENIS_PEGAWAI
                FROM pegawai A  
                     LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                     LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, NVL(ESELON_ID, 99) ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                     LEFT JOIN agama D ON  A.AGAMA_ID = D.AGAMA_ID
                     LEFT JOIN JENIS_PEGAWAI G ON A.JENIS_PEGAWAI_ID=G.JENIS_PEGAWAI_ID
                     LEFT JOIN KEDUDUKAN H ON A.KEDUDUKAN_ID=H.KEDUDUKAN_ID
                     LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                     SATKER E,
                     (
                     SELECT PEGAWAI_ID,
                     CASE WHEN (TO_NUMBER(TO_CHAR(SYSDATE, 'MM'))-TO_NUMBER(TO_CHAR(TANGGAL_LAHIR, 'MM')))<0 THEN 
                        TO_NUMBER(TO_CHAR(SYSDATE, 'YYYY'))-TO_NUMBER(TO_CHAR(TANGGAL_LAHIR, 'YYYY'))-1
                        ELSE TO_NUMBER(TO_CHAR(SYSDATE, 'YYYY'))-TO_NUMBER(TO_CHAR(TANGGAL_LAHIR, 'YYYY')) END USIA_TAHUN
                     FROM pegawai
                     ) Y
                WHERE                     
                     A.SATKER_ID = E.SATKER_ID
                     AND A.PEGAWAI_ID = Y.PEGAWAI_ID
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
		
		
	function selectByParamsMonitoring2($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		/*TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
		TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
		TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TANGGAL_LAHIR, */
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			     B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
			FROM dbsimpeg.rb_data_pegawai A 
			LEFT JOIN dbsimpeg.rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN dbsimpeg.rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			, dbsimpeg.rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
		
	function selectByFlexiport($paramsArray=array(),$limit=-1,$from=-1, $field='', $condition='', $order='')
	{
		$str = "
					SELECT DISTINCT ".$field." FROM 
					PEGAWAI A
					LEFT JOIN PANGKAT_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN JABATAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID 
					LEFT JOIN SATKER D ON A.SATKER_ID = D.SATKER_ID
					LEFT JOIN PENDIDIKAN_TERAKHIR E ON A.PEGAWAI_ID = E.PEGAWAI_ID
					LEFT JOIN PENGHARGAAN_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
					LEFT JOIN diklat_FUNGSIONAL_TERAKHIR G ON A.PEGAWAI_ID = G.PEGAWAI_ID
					LEFT JOIN diklat_TEKNIS_TERAKHIR H ON A.PEGAWAI_ID = H.PEGAWAI_ID
					LEFT JOIN diklat_STRUKTURAL_TERAKHIR I ON A.PEGAWAI_ID = I.PEGAWAI_ID
					LEFT JOIN HUKUMAN_TERAKHIR J ON A.PEGAWAI_ID = J.PEGAWAI_ID
					LEFT JOIN ANAK_TERAKHIR K ON A.PEGAWAI_ID = K.PEGAWAI_ID
					LEFT JOIN SUAMI_ISTRI L ON A.PEGAWAI_ID = L.PEGAWAI_ID 
					LEFT JOIN CUTI_TERAKHIR M ON A.PEGAWAI_ID = M.PEGAWAI_ID
					LEFT JOIN SK_CPNS N ON A.PEGAWAI_ID = N.PEGAWAI_ID
					LEFT JOIN SK_PNS O ON A.PEGAWAI_ID = O.PEGAWAI_ID
					LEFT JOIN PENDIDIKAN_TERAKHIR P ON A.PEGAWAI_ID = P.PEGAWAI_ID
					LEFT JOIN agama Q ON Q.AGAMA_ID = A.AGAMA_ID
					LEFT JOIN TIPE_PEGAWAI R ON R.TIPE_PEGAWAI_ID = A.TIPE_PEGAWAI_ID
					LEFT JOIN JENIS_PEGAWAI S ON S.JENIS_PEGAWAI_ID = A.JENIS_PEGAWAI_ID
					LEFT JOIN KEDUDUKAN T ON T.KEDUDUKAN_ID = A.KEDUDUKAN_ID
					WHERE 1 = 1
	 				";
		if($condition == "")
		{}
		else
			$str.= " ".$condition ;
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $order;
		
		$this->query = $str;
		
				
		return $this->selectLimit($str,$limit,$from); 
    }	

	function selectByParamsPegawaiAgama($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
SELECT  C.ESELON_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA, D.AGAMA_ID AGAMA_ID,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A    
                    LEFT JOIN  (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON  A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN  (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON  A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                    SATKER E                      
                WHERE   A.SATKER_ID = E.SATKER_ID AND 
                   A.STATUS_PEGAWAI IN (1, 2) 
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ORDER BY D.AGAMA_ID ASC, C.ESELON_ID ASC, B.PANGKAT_ID DESC,  B.TMT_PANGKAT ASC ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	

	function selectByParamsPegawaiPenghargaan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT * FROM 
                (
                SELECT G.LENCANA_ID,  G.LENCANA, A.SATKER_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A  
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON  A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                    (SELECT PEGAWAI_ID, NAMA LENCANA, A.LENCANA_ID FROM LENCANA_PEGAWAI B LEFT JOIN LENCANA A ON A.LENCANA_ID = B.LENCANA_ID) G,
                    SATKER E 
                WHERE A.SATKER_ID = E.SATKER_ID AND  A.PEGAWAI_ID = G.PEGAWAI_ID AND A.STATUS_PEGAWAI IN (1, 2)                   
                UNION ALL
                SELECT NVL(H.LENCANA_ID, 99) LENCANA_ID, G.NAMA LENCANA, A.SATKER_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON  A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                    SATKER E, 
                    PENGHARGAAN G  LEFT JOIN LENCANA H ON   G.NAMA = H.NAMA 
                WHERE 
                     A.SATKER_ID = E.SATKER_ID AND 
                     A.PEGAWAI_ID = G.PEGAWAI_ID  AND
                     A.STATUS_PEGAWAI IN (1, 2) 
                 )  WHERE 1 = 1
                 
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement."  ORDER BY LENCANA_ID ASC, LENCANA ASC ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }		
	

	function selectByParamsPegawaiPenghargaanGroup($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT LENCANA FROM (
                SELECT * FROM 
                (
                SELECT  G.LENCANA, A.SATKER_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A  
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON  A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                    (SELECT PEGAWAI_ID, NAMA LENCANA FROM LENCANA_PEGAWAI B LEFT JOIN LENCANA A ON A.LENCANA_ID = B.LENCANA_ID) G,
                    SATKER E 
                WHERE A.SATKER_ID = E.SATKER_ID AND  A.PEGAWAI_ID = G.PEGAWAI_ID AND A.STATUS_PEGAWAI IN (1, 2)                   
                UNION ALL
                SELECT  G.NAMA LENCANA, A.SATKER_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON  A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID, 
                    SATKER E, 
                    PENGHARGAAN G 
                WHERE 
                     A.SATKER_ID = E.SATKER_ID AND 
                     A.PEGAWAI_ID = G.PEGAWAI_ID  AND
                     A.STATUS_PEGAWAI IN (1, 2)  
                 )  WHERE 1 = 1 ) GROUP BY LENCANA

	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement."  ORDER BY LENCANA ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 

    }	
	
	
	function selectByParamsPegawaiMutasi($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
SELECT G.KEDUDUKAN_ID KEDUDUKAN_ID, G.NAMA KEDUDUKAN, C.ESELON_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A 
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON  A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID
                    LEFT JOIN KEDUDUKAN G ON  A.KEDUDUKAN_ID = G.KEDUDUKAN_ID,
                    SATKER E
                WHERE                    
                     A.SATKER_ID = E.SATKER_ID  
                     AND A.STATUS_PEGAWAI IN (1, 2) 
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ORDER BY A.KEDUDUKAN_ID ASC, C.ESELON_ID ASC, B.PANGKAT_ID DESC,  B.TMT_PANGKAT ASC ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawaiMutasiHistori($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT B.MUTASI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, A.PEGAWAI_ID,
                       GELAR_DEPAN ||  DECODE(GELAR_DEPAN, NULL, '', ' ') || NAMA || DECODE(GELAR_BELAKANG, NULL, '', ' ') || GELAR_BELAKANG NAMA,
                       AMBIL_SATKER(B.SATKER_ID_LAMA) NMSATKERLAMA, B.SATKER_ID_LAMA, B.TMT_TUGAS, B.SATKER_ID_LAMA MUTASI_SATKER_LAMA, B.SATKER_ID_BARU, AMBIL_SATKER(B.SATKER_ID_BARU) NMSATKERBARU,
					   B.KETERANGAN_USULAN
                FROM pegawai A
                INNER JOIN MUTASI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                WHERE
                    (A.STATUS_PEGAWAI = 1 OR A.STATUS_PEGAWAI = 2)
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
				
		$str .= $statement." ORDER BY B.TMT_TUGAS DESC ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
					
	function selectByParamsPegawaiPendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
SELECT  C.ESELON_ID, A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        F.PENDIDIKAN PENDIDIKAN, F.PENDIDIKAN_ID  PENDIDIKAN_ID,
                        LULUS
                FROM pegawai A
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN, PENDIDIKAN_ID FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                     SATKER E                    
                WHERE 
                     A.SATKER_ID = E.SATKER_ID AND                   
                    A.STATUS_PEGAWAI IN (1, 2)  
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ORDER BY F.PENDIDIKAN_ID ASC, C.ESELON_ID ASC, B.PANGKAT_ID DESC,  B.TMT_PANGKAT ASC ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
	
//	function selectByParamsMenuPerubahan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
//	{
//		$str = "SELECT  *
//				FROM PERUBAHAN_DATA
//                WHERE VALIDASI IS NULL
//	 			"; 
//		
//		while(list($key,$val) = each($paramsArray))
//		{
//			$str .= " AND $key = '$val' ";
//		}
//		
//		$this->query = $str;
//		$str .= $statement." ORDER BY PERUBAHAN_DATA_ID ASC ";
//		
//		echo $str;
//				
//		return $this->selectLimit($str,$limit,$from); 
//    }	
		
	function getCountByParamsPegawaiPendidikan($paramsArray=array(), $statement='')
	{
		$str = "
				
				SELECT  COUNT(A.PEGAWAI_ID) ROWCOUNT
				FROM pegawai A,  
					 (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
					 (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     AGAMA D,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN, PENDIDIKAN_ID FROM PENDIDIKAN_TERAKHIR X) F
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.AGAMA_ID = D.AGAMA_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
        			 A.STATUS_PEGAWAI IN (1,2)
					".$statement."
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//ECHO $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
		
	function selectByParamsPegawaiJabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
                SELECT  DECODE(SUBSTR(C.ESELON_ID, 0, 1), 1, 'Eselon I', 2, 'Eselon II', 3, 'Eselon III', 4, 'Eselon IV', 5, 'Eselon V') ESELON, C.ESELON_ID ESELON_ID,
                        A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     AGAMA D,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.AGAMA_ID = D.AGAMA_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
                     A.STATUS_PEGAWAI IN (1,2)
                    AND C.ESELON_ID IS NOT NULL
               
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ORDER BY C.ESELON_ID ASC, B.PANGKAT_ID DESC,  B.TMT_PANGKAT ASC  ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function selectByParamsPegawaiDiklatPerjenjangan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
SELECT  G.NAMA DIKLAT, G.DIKLAT_ID DIKLAT_ID, 
                        A.PEGAWAI_ID, NIP_LAMA,  AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A
                    LEFT JOIN (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                    LEFT JOIN agama D ON A.AGAMA_ID = D.AGAMA_ID
                    LEFT JOIN (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN, PENDIDIKAN_ID FROM PENDIDIKAN_TERAKHIR X) F ON A.PEGAWAI_ID = F.PEGAWAI_ID,
                     SATKER E , 
                     DIKLAT_STRUKTURAL_TERAKHIR G
                WHERE 
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = G.PEGAWAI_ID AND
                      A.STATUS_PEGAWAI IN (1, 2)
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		
		$str .= $statement." ORDER BY G.NAMA ASC, C.ESELON_ID ASC, B.PANGKAT_ID DESC,  B.TMT_PANGKAT ASC   ";
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
	function getCountByParamsPegawaiDiklatPerjenjangan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT COUNT(*) ROWCOUNT
				FROM(
                SELECT  G.NAMA DIKLAT, 
                        A.PEGAWAI_ID, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI) STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     AGAMA D,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F,
                     DIKLAT_STRUKTURAL_TERAKHIR G
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.AGAMA_ID = D.AGAMA_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
                     A.STATUS_PEGAWAI IN (1,2)
                    AND A.PEGAWAI_ID = G.PEGAWAI_ID
				)A
	 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	
	function getCountByParamsFlexiport($paramsArray=array(), $field='', $condition='', $order='')
	{
		$str = "
		SELECT COUNT(*) ROWCOUNT FROM (
					SELECT DISTINCT ".$field." FROM 
					PEGAWAI A
					LEFT JOIN PANGKAT_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN JABATAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID 
					LEFT JOIN SATKER D ON A.SATKER_ID = D.SATKER_ID
					LEFT JOIN PENDIDIKAN_TERAKHIR E ON A.PEGAWAI_ID = E.PEGAWAI_ID
					LEFT JOIN PENGHARGAAN_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
					LEFT JOIN diklat_FUNGSIONAL_TERAKHIR G ON A.PEGAWAI_ID = G.PEGAWAI_ID
					LEFT JOIN diklat_TEKNIS_TERAKHIR H ON A.PEGAWAI_ID = H.PEGAWAI_ID
					LEFT JOIN diklat_STRUKTURAL_TERAKHIR I ON A.PEGAWAI_ID = I.PEGAWAI_ID
					LEFT JOIN HUKUMAN_TERAKHIR J ON A.PEGAWAI_ID = J.PEGAWAI_ID
					LEFT JOIN ANAK_TERAKHIR K ON A.PEGAWAI_ID = K.PEGAWAI_ID
					LEFT JOIN SUAMI_ISTRI L ON A.PEGAWAI_ID = L.PEGAWAI_ID 
					LEFT JOIN CUTI_TERAKHIR M ON A.PEGAWAI_ID = M.PEGAWAI_ID
					LEFT JOIN SK_CPNS N ON A.PEGAWAI_ID = N.PEGAWAI_ID
					LEFT JOIN SK_PNS O ON A.PEGAWAI_ID = O.PEGAWAI_ID
					LEFT JOIN PENDIDIKAN_TERAKHIR P ON A.PEGAWAI_ID = P.PEGAWAI_ID
					LEFT JOIN agama Q ON Q.AGAMA_ID = A.AGAMA_ID
					LEFT JOIN TIPE_PEGAWAI R ON R.TIPE_PEGAWAI_ID = A.TIPE_PEGAWAI_ID
					LEFT JOIN JENIS_PEGAWAI S ON S.JENIS_PEGAWAI_ID = A.JENIS_PEGAWAI_ID
					LEFT JOIN KEDUDUKAN T ON T.KEDUDUKAN_ID = A.KEDUDUKAN_ID
					WHERE 1 = 1
	 				";
		if($condition == "")
		{}
		else
			$str.= " ".$condition." ) A" ;
		
		while(list($key,$val) = each($paramsArray))
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
	
	function getCountByParamsFlexiport_back_up($paramsArray=array(), $statement='')
	{
		$str = "
					SELECT COUNT(*) ROWCOUNT FROM (
					SELECT DISTINCT A.PEGAWAI_ID ROWCOUNT FROM 
					PEGAWAI A
					LEFT JOIN PANGKAT_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
					LEFT JOIN JABATAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID 
					LEFT JOIN SATKER D ON A.SATKER_ID = D.SATKER_ID
					LEFT JOIN PENDIDIKAN_TERAKHIR E ON A.PEGAWAI_ID = E.PEGAWAI_ID
					LEFT JOIN PENGHARGAAN_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
					LEFT JOIN diklat_FUNGSIONAL_TERAKHIR G ON A.PEGAWAI_ID = G.PEGAWAI_ID
					LEFT JOIN diklat_TEKNIS_TERAKHIR H ON A.PEGAWAI_ID = H.PEGAWAI_ID
					LEFT JOIN diklat_STRUKTURAL_TERAKHIR I ON A.PEGAWAI_ID = I.PEGAWAI_ID
					LEFT JOIN HUKUMAN_TERAKHIR J ON A.PEGAWAI_ID = J.PEGAWAI_ID
                    LEFT JOIN ANAK_TERAKHIR K ON A.PEGAWAI_ID = K.PEGAWAI_ID
                    LEFT JOIN SUAMI_ISTRI L ON A.PEGAWAI_ID = L.PEGAWAI_ID 
                    LEFT JOIN CUTI_TERAKHIR M ON A.PEGAWAI_ID = M.PEGAWAI_ID
                    LEFT JOIN SK_CPNS N ON A.PEGAWAI_ID = N.PEGAWAI_ID
                    LEFT JOIN SK_PNS O ON A.PEGAWAI_ID = O.PEGAWAI_ID
                    LEFT JOIN PENDIDIKAN_TERAKHIR P ON A.PEGAWAI_ID = P.PEGAWAI_ID
                    LEFT JOIN agama Q ON Q.AGAMA_ID = A.AGAMA_ID
                    LEFT JOIN TIPE_PEGAWAI R ON R.TIPE_PEGAWAI_ID = A.TIPE_PEGAWAI_ID
                    LEFT JOIN JENIS_PEGAWAI S ON S.JENIS_PEGAWAI_ID = A.JENIS_PEGAWAI_ID
                    LEFT JOIN KEDUDUKAN T ON T.KEDUDUKAN_ID = A.KEDUDUKAN_ID
                    WHERE 1 = 1
	 				";
		$str.= " ".$statement." ) A" ;
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}		
			
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT P.PEGAWAI_ID, S.NAMA NMSATKER, P.PROPINSI_ID, P.KABUPATEN_ID, 
                   CASE JENIS_KELAMIN WHEN 'L' THEN 'Laki-Laki' WHEN 'P' THEN 'Perempuan' END KELAMIN,
                   (SELECT NAMA FROM status_pegawai X WHERE X.STATUS_PEGAWAI_ID = P.STATUS_PEGAWAI) STATUS_PEG,
                   (SELECT NAMA FROM jenis_pegawai X WHERE X.JENIS_PEGAWAI_ID = P.JENIS_PEGAWAI_ID) JENIS_PEG,
                   (SELECT x.NAMA FROM agama x WHERE x.AGAMA_ID = P.AGAMA_ID) AGAMA,
				   P.AGAMA_ID,
                   (SELECT x.NAMA FROM kedudukan x WHERE x.KEDUDUKAN_ID = P.KEDUDUKAN_ID) KEDUDUKAN,
                   CASE STATUS_KAWIN WHEN 1 THEN 'Belum Kawin' WHEN 2 THEN 'Kawin' WHEN 3 THEN 'Janda' WHEN 4 THEN 'Duda' END KAWIN,
                   S.ALAMAT ALAMATSATKER, 
                   S.PROPINSI_ID PROPSATKER, S.KABUPATEN_ID KABSATKER,
                   S.KECAMATAN_ID KECSATKER, S.KELURAHAN_ID KELSATKER,
                   S.TELEPON TELEPONSATKER, S.KODEPOS KODEPOSSATKER,
                   P.KECAMATAN_ID, P.KELURAHAN_ID, P.SATKER_ID, 
                   KEDUDUKAN_ID, JENIS_PEGAWAI_ID, BANK_ID, 
                   NIP_LAMA, NIP_BARU, P.NAMA, 
                   GELAR_DEPAN, GELAR_BELAKANG, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, STATUS_KAWIN, SUKU_BANGSA, GOLONGAN_DARAH,
                   P.EMAIL, P.ALAMAT, RT, RW, P.TELEPON, P.KODEPOS, STATUS_PEGAWAI, KARTU_PEGAWAI, ASKES, TASPEN,
                   NPWP, NIK, FOTO_SETENGAH, NO_REKENING, TANGGAL_MATI, TANGGAL_PENSIUN, TANGGAL_TERUSAN, P.TANGGAL_UPDATE, TIPE_PEGAWAI_ID,
                   (SELECT KODE FROM pangkat where PANGKAT_ID = A.PANGKAT_ID) GOL_RUANG, DATE_FORMAT(TMT_PANGKAT, '%d-%m-%Y') TMT_PANGKAT, B.NAMA JABATAN, DATE_FORMAT(B.TMT_JABATAN, '%d-%m%Y') TMT_JABATAN,
                   C.NAMA PENDIDIKAN, date_format(C.TANGGAL_STTB,'%Y') TAHUN, 
				   C.JURUSAN, P.SATKER_ID_LAMA, P.FOTO_BLOB, P.FOTO_BLOB_OTHER
                FROM pegawai P 
                LEFT JOIN satker S ON S.SATKER_ID = P.SATKER_ID 
                LEFT JOIN pangkat_riwayat A ON P.PEGAWAI_ID = A.PEGAWAI_ID AND A.PANGKAT_RIWAYAT_ID = PANGKAT_RIWAYAT_ID_TERAKHIR
                LEFT JOIN jabatan_riwayat B ON P.PEGAWAI_ID = B.PEGAWAI_ID AND B.JABATAN_RIWAYAT_ID = JABATAN_RIWAYAT_ID_TERAKHIR
                LEFT JOIN pendidikan_riwayat C ON P.PEGAWAI_ID = C.PEGAWAI_ID AND C.PENDIDIKAN_RIWAYAT_ID = PENDIDIKAN_RIWAYAT_ID_TERAKHIR
                WHERE P.PEGAWAI_ID IS NOT NULL 
				 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY KEDUDUKAN_ID ASC";

		
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParamsSimple($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
					SELECT PEGAWAI_ID FROM pegawai WHERE 1 = 1
				 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		
				
		return $this->selectLimit($str,$limit,$from); 
    }	
	
    function selectByParamsFIP1($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
/*		$str = "
				SELECT 
						AMBIL_PROPINSI(B.PROPINSI_ID) PROPINSI_SATKER, AMBIL_KABUPATEN(B.KABUPATEN_ID) KABUPATEN_SATKER,
						AMBIL_KECAMATAN(B.KECAMATAN_ID) KECAMATAN_SATKER, AMBIL_KELURAHAN(B.KELURAHAN_ID) KELURAHAN_SATKER, B.ALAMAT ALAMAT_SATKER, 
						B.TELEPON TELEPON_SATKER, B.KODEPOS KODEPOS_SATKER, B.NAMA NAMA_SATKER, FOTO,
						(SELECT 
                        CASE WHEN (length(SATKER_ID) = 2 AND SATKER_ID = '02') THEN (SELECT NAMA_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '01' = X.SATKER_ID)
                               WHEN (length(SATKER_ID) = 2 AND SATKER_ID <> '02') THEN (SELECT NAMA_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '02' = X.SATKER_ID)
                               ELSE (SELECT NAMA_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE X.SATKER_ID = Y.SATKER_ID)
                        END AS NAMA_ATASAN
                        FROM JABATAN_TERAKHIR X WHERE X.SATKER_ID = B.SATKER_ID) NAMA_ATASAN,
                        (SELECT 
                        CASE WHEN (length(SATKER_ID) = 2 AND SATKER_ID = '02') THEN (SELECT NIP_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '01' = X.SATKER_ID)
                               WHEN (length(SATKER_ID) = 2 AND SATKER_ID <> '02') THEN (SELECT NIP_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '02' = X.SATKER_ID)
                               ELSE (SELECT NIP_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE X.SATKER_ID = Y.SATKER_ID)
                        END AS NIP_ATASAN
                        FROM JABATAN_TERAKHIR X WHERE X.SATKER_ID = B.SATKER_ID) NIP_ATASAN,
						AMBIL_SATKER_INDUK(A.SATKER_ID) SATKER_INDUK, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, TEMPAT_LAHIR,
						TO_CHAR(TANGGAL_LAHIR, 'DD MONTH YYYY') TANGGAL_LAHIR, DECODE(JENIS_KELAMIN, 'L', 'Laki-laki', 'P', 'Perempuan') JENIS_KELAMIN,
						(SELECT NAMA FROM agama X WHERE X.AGAMA_ID = A.AGAMA_ID) AGAMA, 
						DECODE(A.STATUS_PEGAWAI, 1, 'CPNS', 2, 'PNS', 3, 'Pensiun', 4, 'Tewas', 5, 'Wafat', 6, 'Pindah', 7, 'Parpol') STATUS_PEGAWAI,
						(SELECT NAMA FROM JENIS_PEGAWAI X WHERE X.JENIS_PEGAWAI_ID = A.JENIS_PEGAWAI_ID) JENIS_PEGAWAI, 
						(SELECT NAMA FROM kedudukan X WHERE X.KEDUDUKAN_ID = A.KEDUDUKAN_ID) KEDUDUKAN, 
						DECODE(STATUS_KAWIN, 1, 'Belum Kawin', 2, 'Kawin', 3, 'Janda', 4, 'Duda') STATUS_KAWIN, SUKU_BANGSA, 
						GOLONGAN_DARAH, A.ALAMAT, RT || '/' || RW RTRW, A.ALAMAT || ' RT.' || RT || ' ' || 'RW.' || RW ALAMATMODEL,
						AMBIL_KELURAHAN(A.KELURAHAN_ID) KELURAHAN, AMBIL_KECAMATAN(A.KECAMATAN_ID) KECAMATAN, AMBIL_KABUPATEN(A.KABUPATEN_ID) KABUPATEN,
						AMBIL_PROPINSI(A.PROPINSI_ID) PROPINSI, A.KODEPOS, KARTU_PEGAWAI, ASKES, TASPEN, '' SUAMIISTRI, NPWP, NIK,
						C.NAMA NAMA_INSTANSI, C.JABATAN JABATAN_INSTANSI, C.MASA_KERJA_TAHUN || '-' || C.MASA_KERJA_BULAN MASA_KERJA_INSTANSI, 
						TO_CHAR(D.TMT_CPNS, 'DD MONTH YYYY') TANGGAL_KERJA, D.NO_NOTA NOTA_CPNS, D.TANGGAL_NOTA TANGGAL_NOTA_CPNS, 
						(SELECT JABATAN FROM pejabat_penetap X WHERE X.PEJABAT_PENETAP_ID = D.PEJABAT_PENETAP_ID) PEJABAT_PENETAP_CPNS, NO_SK NO_SK_CPNS, 
						TO_CHAR(TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_CPNS, TO_CHAR(TMT_CPNS, 'DD MONTH YYYY') TMT_CPNS, 
						(SELECT KODE FROM PANGKAT X WHERE X.PANGKAT_ID = D.PANGKAT_ID) GOL_RUANG_CPNS, TO_CHAR(TANGGAL_TUGAS, 'DD MONTH YYYY') TANGGAL_TUGAS_CPNS, 
						NO_STTPP, TO_CHAR(TANGGAL_STTPP, 'DD MONTH YYYY') TANGGAL_STTPP_CPNS,
						(SELECT JABATAN FROM pejabat_penetap X WHERE X.PEJABAT_PENETAP_ID = E.PEJABAT_PENETAP_ID) PEJABAT_PENETAP_PNS, E.NO_SK NO_SK_PNS, 
						TO_CHAR(E.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_PNS, TO_CHAR(TMT_PNS, 'DD MONTH YYYY') TMT_PNS, 
						(SELECT KODE FROM PANGKAT X WHERE X.PANGKAT_ID = E.PANGKAT_ID) GOL_RUANG_PNS, TO_CHAR(TANGGAL_SUMPAH, 'DD MONTH YYYY') TANGGAL_SUMPAH,
						F.STLUD, F.NO_STLUD, TO_CHAR(TANGGAL_STLUD, 'DD MONTH YYYY') TANGGAL_STLUD, F.NO_NOTA, F.TANGGAL_NOTA, F.KREDIT, 
						JABATANPENETAP, F.NO_SK SK_PANGKAT, TO_CHAR(F.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_PANGKAT, TO_CHAR(F.TMT_PANGKAT, 'DD MONTH YYYY') TMT_PANGKAT,
						F.GOL_RUANG GOL_RUANG_PANGKAT,
						DECODE(JENIS_KP, 1, 'Reguler', 2, 'Pilihan', 3, 'Anumerta', 4, 'Pengabdian', 5, 'SK Lain-lain') JENIS_KP, 
						F.MASA_KERJA_TAHUN || '-' || F.MASA_KERJA_BULAN MASA_KERJA_PANGKAT, 
						F.MASA_KERJA_TAHUN, F.MASA_KERJA_BULAN,
						G.NO_SK NO_SK_KGB, TO_CHAR(G.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_KGB, TO_CHAR(G.TMT_SK, 'DD MONTH YYYY') TMT_SK_KGB, 
						G.MASA_KERJA_TAHUN || '-' || G.MASA_KERJA_BULAN GOL_RUANG_KGB,
						G.GAJI_POKOK, WILAYAH, KTUA, 
						H.PENDIDIKAN, H.JURUSAN, H.NAMA_SEKOLAH, H.TEMPAT, I.NAMA NAMA_DIK_STRUK, J.NAMA NAMA_DIK_FUNGS, K.NAMA NAMA_DIK_TEKNIS,
						(SELECT NAMA FROM PENATARAN X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND ROWNUM <= 1) PENATARAN, 
						(SELECT NAMA FROM SEMINAR X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND ROWNUM <= 1) SEMINAR,
						JABATANPENETAP PENETAP_JABATAN, L.NO_SK NO_SK_JABATAN, TO_CHAR(L.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_JABATAN, 
						L.JABATAN, L.ESELON, TO_CHAR(L.TMT_ESELON, 'DD MONTH YYYY') TMT_ESELON, NO_PELANTIKAN, TO_CHAR(TANGGAL_PELANTIKAN, 'DD MONTH YYYY') TANGGAL_PELANTIKAN
					FROM pegawai A
						 LEFT JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID
						 LEFT JOIN PENGALAMAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
						 LEFT JOIN SK_CPNS D ON A.PEGAWAI_ID = D.PEGAWAI_ID
						 LEFT JOIN SK_PNS E ON A.PEGAWAI_ID = E.PEGAWAI_ID 
						 LEFT JOIN PANGKAT_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
						 LEFT JOIN GAJI_TERAKHIR G ON A.PEGAWAI_ID = G.PEGAWAI_ID
						 LEFT JOIN PENDIDIKAN_TERAKHIR H ON A.PEGAWAI_ID = H.PEGAWAI_ID
						 LEFT JOIN diklat_STRUKTURAL_TERAKHIR I ON A.PEGAWAI_ID = I.PEGAWAI_ID
						 LEFT JOIN diklat_FUNGSIONAL_TERAKHIR J ON A.PEGAWAI_ID = J.PEGAWAI_ID
						 LEFT JOIN diklat_TEKNIS_TERAKHIR K ON A.PEGAWAI_ID = K.PEGAWAI_ID
						 LEFT JOIN JABATAN_TERAKHIR L ON A.PEGAWAI_ID = L.PEGAWAI_ID
					WHERE 1 = 1
 ";*/ //DECODE(GOLONGAN_DARAH, '1', 'A', '2', 'B', '3', 'AB', '4', 'O') 


		$str = "
				SELECT 
						AMBIL_PROPINSI(B.PROPINSI_ID) PROPINSI_SATKER, AMBIL_KABUPATEN(B.PROPINSI_ID, B.KABUPATEN_ID) KABUPATEN_SATKER,
						AMBIL_KECAMATAN(B.PROPINSI_ID, B.KABUPATEN_ID, B.KECAMATAN_ID) KECAMATAN_SATKER, AMBIL_KELURAHAN(B.PROPINSI_ID, B.KABUPATEN_ID, B.KECAMATAN_ID, B.KELURAHAN_ID) KELURAHAN_SATKER, B.ALAMAT ALAMAT_SATKER, 
						B.TELEPON TELEPON_SATKER, B.KODEPOS KODEPOS_SATKER, B.NAMA NAMA_SATKER, FOTO,
						(SELECT 
                        CASE WHEN (length(SATKER_ID) = 2 AND SATKER_ID = '02') THEN (SELECT NAMA_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '01' = X.SATKER_ID)
                               WHEN (length(SATKER_ID) = 2 AND SATKER_ID <> '02') THEN (SELECT NAMA_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '02' = X.SATKER_ID)
                               ELSE (SELECT NAMA_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE X.SATKER_ID = Y.SATKER_ID)
                        END AS NAMA_ATASAN
                        FROM JABATAN_TERAKHIR X WHERE X.SATKER_ID = B.SATKER_ID) NAMA_ATASAN,
                        (SELECT 
                        CASE WHEN (length(SATKER_ID) = 2 AND SATKER_ID = '02') THEN (SELECT NIP_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '01' = X.SATKER_ID)
                               WHEN (length(SATKER_ID) = 2 AND SATKER_ID <> '02') THEN (SELECT NIP_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE '02' = X.SATKER_ID)
                               ELSE (SELECT NIP_PEGAWAI FROM JABATAN_TERAKHIR Y WHERE X.SATKER_ID = Y.SATKER_ID)
                        END AS NIP_ATASAN
                        FROM JABATAN_TERAKHIR X WHERE X.SATKER_ID = B.SATKER_ID) NIP_ATASAN,
						AMBIL_SATKER_INDUK(A.SATKER_ID) SATKER_INDUK, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, TEMPAT_LAHIR,
						TO_CHAR(TANGGAL_LAHIR, 'DD MONTH YYYY') TANGGAL_LAHIR, DECODE(JENIS_KELAMIN, 'L', 'Laki-laki', 'P', 'Perempuan') JENIS_KELAMIN,
						(SELECT NAMA FROM agama X WHERE X.AGAMA_ID = A.AGAMA_ID) AGAMA, 
						DECODE(A.STATUS_PEGAWAI, 1, 'CPNS', 2, 'PNS', 3, 'Pensiun', 4, 'Tewas', 5, 'Wafat', 6, 'Pindah', 7, 'Parpol') STATUS_PEGAWAI,
						(SELECT NAMA FROM JENIS_PEGAWAI X WHERE X.JENIS_PEGAWAI_ID = A.JENIS_PEGAWAI_ID) JENIS_PEGAWAI, 
						(SELECT NAMA FROM kedudukan X WHERE X.KEDUDUKAN_ID = A.KEDUDUKAN_ID) KEDUDUKAN, 
						DECODE(STATUS_KAWIN, 1, 'Belum Kawin', 2, 'Kawin', 3, 'Janda', 4, 'Duda') STATUS_KAWIN, SUKU_BANGSA, 
						GOLONGAN_DARAH, A.ALAMAT, RT || '/' || RW RTRW, A.ALAMAT || ' RT.' || RT || ' ' || 'RW.' || RW ALAMATMODEL,
						AMBIL_KELURAHAN(A.PROPINSI_ID, A.KABUPATEN_ID, A.KECAMATAN_ID, A.KELURAHAN_ID) KELURAHAN, AMBIL_KECAMATAN(A.PROPINSI_ID, A.KABUPATEN_ID, A.KECAMATAN_ID) KECAMATAN, AMBIL_KABUPATEN(A.PROPINSI_ID, A.KABUPATEN_ID) KABUPATEN,
						AMBIL_PROPINSI(A.PROPINSI_ID) PROPINSI, A.KODEPOS, KARTU_PEGAWAI, ASKES, TASPEN, '' SUAMIISTRI, NPWP, NIK,
						C.NAMA NAMA_INSTANSI, C.JABATAN JABATAN_INSTANSI, C.MASA_KERJA_TAHUN || '-' || C.MASA_KERJA_BULAN MASA_KERJA_INSTANSI, 
						TO_CHAR(D.TMT_CPNS, 'DD MONTH YYYY') TANGGAL_KERJA, D.NO_NOTA NOTA_CPNS, D.TANGGAL_NOTA TANGGAL_NOTA_CPNS, 
						(SELECT JABATAN FROM pejabat_penetap X WHERE X.PEJABAT_PENETAP_ID = D.PEJABAT_PENETAP_ID OR X.JABATAN = D.PEJABAT_PENETAP) PEJABAT_PENETAP_CPNS, NO_SK NO_SK_CPNS, 
						TO_CHAR(TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_CPNS, TO_CHAR(TMT_CPNS, 'DD MONTH YYYY') TMT_CPNS, 
						(SELECT KODE FROM PANGKAT X WHERE X.PANGKAT_ID = D.PANGKAT_ID) GOL_RUANG_CPNS, TO_CHAR(TANGGAL_TUGAS, 'DD MONTH YYYY') TANGGAL_TUGAS_CPNS, 
						NO_STTPP, TO_CHAR(TANGGAL_STTPP, 'DD MONTH YYYY') TANGGAL_STTPP_CPNS,
						(SELECT JABATAN FROM pejabat_penetap X WHERE X.PEJABAT_PENETAP_ID = E.PEJABAT_PENETAP_ID OR X.JABATAN = D.PEJABAT_PENETAP) PEJABAT_PENETAP_PNS, E.NO_SK NO_SK_PNS, 
						TO_CHAR(E.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_PNS, TO_CHAR(TMT_PNS, 'DD MONTH YYYY') TMT_PNS, 
						(SELECT KODE FROM PANGKAT X WHERE X.PANGKAT_ID = E.PANGKAT_ID) GOL_RUANG_PNS, TO_CHAR(TANGGAL_SUMPAH, 'DD MONTH YYYY') TANGGAL_SUMPAH,
						F.STLUD, F.NO_STLUD, TO_CHAR(TANGGAL_STLUD, 'DD MONTH YYYY') TANGGAL_STLUD, F.NO_NOTA, F.TANGGAL_NOTA, F.KREDIT, 
						JABATANPENETAP, F.NO_SK SK_PANGKAT, TO_CHAR(F.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_PANGKAT, TO_CHAR(F.TMT_PANGKAT, 'DD MONTH YYYY') TMT_PANGKAT,
						F.GOL_RUANG GOL_RUANG_PANGKAT,
						DECODE(JENIS_KP, 1, 'Reguler', 2, 'Pilihan', 3, 'Anumerta', 4, 'Pengabdian', 5, 'SK Lain-lain') JENIS_KP, 
						F.MASA_KERJA_TAHUN || '-' || F.MASA_KERJA_BULAN MASA_KERJA_PANGKAT, 
						F.MASA_KERJA_TAHUN, F.MASA_KERJA_BULAN,
						G.NO_SK NO_SK_KGB, TO_CHAR(G.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_KGB, TO_CHAR(G.TMT_SK, 'DD MONTH YYYY') TMT_SK_KGB, 
						G.MASA_KERJA_TAHUN || '-' || G.MASA_KERJA_BULAN GOL_RUANG_KGB,
						G.GAJI_POKOK, WILAYAH, KTUA, 
						H.PENDIDIKAN, H.JURUSAN, H.NAMA_SEKOLAH, H.TEMPAT, I.NAMA NAMA_DIK_STRUK, J.NAMA NAMA_DIK_FUNGS, K.NAMA NAMA_DIK_TEKNIS,
						(SELECT NAMA FROM PENATARAN X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND ROWNUM <= 1) PENATARAN, 
						(SELECT NAMA FROM SEMINAR X WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND ROWNUM <= 1) SEMINAR,
						JABATANPENETAP PENETAP_JABATAN, L.NO_SK NO_SK_JABATAN, TO_CHAR(L.TANGGAL_SK, 'DD MONTH YYYY') TANGGAL_SK_JABATAN, 
						L.JABATAN, L.ESELON, TO_CHAR(L.TMT_ESELON, 'DD MONTH YYYY') TMT_ESELON, NO_PELANTIKAN, TO_CHAR(TANGGAL_PELANTIKAN, 'DD MONTH YYYY') TANGGAL_PELANTIKAN,
						A.FOTO_BLOB
					FROM pegawai A
						 LEFT JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID
						 LEFT JOIN PENGALAMAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
						 LEFT JOIN SK_CPNS D ON A.PEGAWAI_ID = D.PEGAWAI_ID
						 LEFT JOIN SK_PNS E ON A.PEGAWAI_ID = E.PEGAWAI_ID 
						 LEFT JOIN PANGKAT_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
						 LEFT JOIN GAJI_TERAKHIR G ON A.PEGAWAI_ID = G.PEGAWAI_ID
						 LEFT JOIN PENDIDIKAN_TERAKHIR H ON A.PEGAWAI_ID = H.PEGAWAI_ID
						 LEFT JOIN diklat_STRUKTURAL_TERAKHIR I ON A.PEGAWAI_ID = I.PEGAWAI_ID
						 LEFT JOIN diklat_FUNGSIONAL_TERAKHIR J ON A.PEGAWAI_ID = J.PEGAWAI_ID
						 LEFT JOIN diklat_TEKNIS_TERAKHIR K ON A.PEGAWAI_ID = K.PEGAWAI_ID
						 LEFT JOIN JABATAN_TERAKHIR L ON A.PEGAWAI_ID = L.PEGAWAI_ID
					WHERE 1 = 1
 				";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ";
		return $this->selectLimit($str,$limit,$from); 
    }
		
    function selectByParamsBiodataLengkap($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
					SELECT 
						A.NIP_LAMA, AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, TEMPAT_LAHIR,
						TO_CHAR(TANGGAL_LAHIR, 'DD MONTH YYYY') TANGGAL_LAHIR, FOTO, FOTO_BLOB, 
						(SELECT NAMA FROM agama X WHERE X.AGAMA_ID = A.AGAMA_ID) AGAMA, F.GOL_RUANG, F.TMT_PANGKAT, 
						PENDIDIKAN, JURUSAN, NAMA_SEKOLAH, TAHUN, JABATAN, ALAMAT
					FROM pegawai A
						 LEFT JOIN PANGKAT_TERAKHIR F ON A.PEGAWAI_ID = F.PEGAWAI_ID
						 LEFT JOIN PENDIDIKAN_TERAKHIR H ON A.PEGAWAI_ID = H.PEGAWAI_ID
						 LEFT JOIN JABATAN_TERAKHIR L ON A.PEGAWAI_ID = L.PEGAWAI_ID
					WHERE 1 = 1
				 "; 
		// 'NIP_LAMA', 'NIP_BARU', 'NAMA', 'TEMPAT_LAHIR', 'TANGGAL_LAHIR', 'AGAMA', 'GOL_RUANG', 'TMT_PANGKAT', 'PENDIDIKAN', 'JURUSAN', 'NAMA_SEKOLAH', 'TAHUN', 'JABATAN', 'ALAMAT'	
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ";
				
		return $this->selectLimit($str,$limit,$from); 
    }
			
	function selectByParamsPropinsiKelurahan($limit=-1,$from=-1, $prop_id='', $kab_id='', $kec_id='', $kel_id='')
	{
		$str = "SELECT
					AMBIL_PROPINSI(".$prop_id.") NMPROPINSI,
					AMBIL_KABUPATEN(".$prop_id.", ".$kab_id.") NMKABUPATEN,
					AMBIL_KECAMATAN(".$prop_id.", ".$kab_id.", ".$kec_id.") NMKECAMATAN,
					AMBIL_KELURAHAN(".$prop_id.", ".$kab_id.", ".$kec_id.", ".$kel_id.") NMKELURAHAN
				FROM DUAL "; 
				
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getNamaSatker($limit=-1,$from=-1, $satker_id='')
	{
		$str = "SELECT
					AMBIL_SATKER('".$satker_id."') NMSATKER
				FROM DUAL "; 
				
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	

    function selectByParamsDUK($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		
		$str = "
				SELECT  A.SATKER_ID, DUK, A.PEGAWAI_ID, NIP_LAMA, NIP_BARU NIP_BARU, A.NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN,
						STATUS_PEGAWAI, GOL_RUANG, TMT_PANGKAT,
						JABATAN, A.TMT_JABATAN, 
						ESELON, TMT_ESELON, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, DIKLAT_STRUKTURAL,
						TAHUN_DIKLAT, JUMLAH_DIKLAT_STRUKTURAL || '/' || JUMLAH_DIKLAT_NONSTRUKTURAL JUMLAH_DIKLAT, PENDIDIKAN, TAHUN_LULUS,
						NAMA_SEKOLAH,
						USIA, B.NAMA SATKER_NAMA, AGAMA
				 FROM DUK A
				 LEFT JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID
				 WHERE 1 = 1
 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
	
		$str .= $statement." ORDER BY DUK";
			$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }	

    function selectByParamsDUKDiklat($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		
		$str = "
				SELECT B.NAMA, A.TAHUN, NVL(JUMLAH_JAM, 0) JUMLAH_JAM FROM DIKLAT_STRUKTURAL A, DIKLAT B WHERE A.DIKLAT_ID = B.DIKLAT_ID AND A.PEGAWAI_ID = '".$statement."'
				UNION ALL
				SELECT A.NAMA, A.TAHUN, NVL(JUMLAH_JAM, 0) JUMLAH_JAM FROM DIKLAT_TEKNIS A WHERE A.PEGAWAI_ID = '".$statement."'
 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
	
			$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }	
		
	function setDuk($periode, $satker, $tipePegawai)
	{
		$sql = "CALL PINSERTDUK('".$periode."','".$satker."','".$tipePegawai."');";
		
		//echo $sql;
		$this->query = $sql;

		
		return $this->execQuery($sql);
		
	}

	function selectBySex($limit=-1,$from=-1, $statement='')
	{
		$str = "
				   SELECT A.SATKER_ID, A.NAMA, 
						   SUM(LELAKI) JUMLAH_LAKI, SUM(WANITA) JUMLAH_PEREMPUAN, SUM(JUMLAH) TOTAL
					FROM
					(
					SELECT A.SATKER_ID, A.NAMA, 
							CASE WHEN JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI,
							CASE WHEN JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS WANITA,
							SUM(JUMLAH) JUMLAH
					  FROM satker A
						   LEFT JOIN
						   (SELECT   SATKER_ID, JENIS_KELAMIN, COUNT (PEGAWAI_ID) JUMLAH
								FROM pegawai
							   WHERE STATUS_PEGAWAI IN (1, 2)
							GROUP BY SATKER_ID, JENIS_KELAMIN) B ON B.SATKER_ID LIKE  A.SATKER_ID || '%'
					  WHERE 1 = 1 ".$statement." GROUP BY A.SATKER_ID, A.NAMA, JENIS_KELAMIN
					) A 
				";

		$str .= " GROUP BY  A.SATKER_ID, A.NAMA ORDER BY A.SATKER_ID ";
						
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	function selectBySexGol($limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT A.UMUR, SUM(A.JUMLAHL) AS JUMLAH_LAKI, SUM(A.JUMLAHP) AS JUMLAH_PEREMPUAN, SUM(A.JUMLAHL)+SUM(A.JUMLAHP) TOTAL
				FROM 
				(
					SELECT CASE 
						WHEN S.UMUR<25 THEN '<25'
						WHEN S.UMUR>=25 AND S.UMUR<=35 THEN '25-35'
						WHEN S.UMUR>=36 AND S.UMUR<=45 THEN '36-45'
						WHEN S.UMUR>=46 AND S.UMUR<=55 THEN '46-55'  
						ELSE '>55'
					END AS UMUR, 
						SUM(S.JUMLAHL) AS JUMLAHL, 
						SUM(S.JUMLAHP) AS JUMLAHP
					FROM(
						SELECT TO_CHAR(sysdate, 'YYYY') - TO_CHAR(TANGGAL_LAHIR, 'YYYY')  AS UMUR, S.NAMA,
						(
							SELECT COUNT(*)  
							FROM SIMPEG.PEGAWAI E    
							WHERE E.JENIS_KELAMIN = 'L'
							AND SUBSTR (E.SATKER_ID,1,LENGTH('06'))= '06'
							AND E.SATKER_ID = S.SATKER_ID
						)JUMLAHL,
						(
							SELECT COUNT(*) 
							FROM SIMPEG.PEGAWAI E 
							WHERE E.JENIS_KELAMIN = 'P'
							AND SUBSTR (E.SATKER_ID,1,LENGTH('06'))= '06'
							AND E.SATKER_ID = S.SATKER_ID  
						)JUMLAHP
						FROM SIMPEG.PEGAWAI S
						WHERE LENGTH(SATKER_ID) >= LENGTH('06')			
					)S
					GROUP BY S.UMUR
				)A
				GROUP BY A.UMUR
				";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	function selectBySexUmur($limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT A.NAMA UMUR, 
					   CASE 
					   WHEN  A.NAMA = '<25' THEN SUM(UMUR25L) 
					   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535L) 
					   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645L)
					   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655L)
					   WHEN  A.NAMA = '>55' THEN SUM(UMUR56L)               
					   END LAKI,        
					   CASE 
					   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P) 
					   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P) 
					   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P)
					   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P)
					   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P)               
					   END PEREMPUAN,      
					   CASE 
					   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P) + SUM(UMUR25L) 
					   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P) + SUM(UMUR2535L) 
					   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P) + SUM(UMUR3645L) 
					   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P) + SUM(UMUR4655L) 
					   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P) + SUM(UMUR56L)              
					   END JUMLAH 
						FROM
								(
								SELECT A.NUM, A.NAMA,
									CASE WHEN JENIS_KELAMIN = 'L' AND  UMUR < 25 AND A.NAMA = '<25' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L,
									CASE WHEN JENIS_KELAMIN = 'P' AND  UMUR < 25 AND A.NAMA = '<25' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P,
									CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L,
									CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P,
									CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L,
									CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P,
									CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L,
									CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P,
									CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 AND A.NAMA = '>55' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L,
									CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 AND A.NAMA = '>55' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P
								FROM (
									  SELECT 1 NUM, '<25' NAMA FROM DUAL
									  UNION ALL
									  SELECT 2 NUM, '25-35' NAMA FROM DUAL
									  UNION ALL
									  SELECT 3 NUM, '36-45' NAMA FROM DUAL
									  UNION ALL
									  SELECT 4 NUM, '46-55' NAMA FROM DUAL                      
									  UNION ALL
									  SELECT 5 NUM, '>55' NAMA FROM DUAL                      
									  ) A LEFT JOIN (
								SELECT   JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR) UMUR, COUNT (A.PEGAWAI_ID) JUMLAH
											FROM pegawai A
										   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
										GROUP BY JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR)) B ON 1 = 1
								 GROUP BY A.NAMA, JENIS_KELAMIN, UMUR, A.NUM
								 ) A GROUP BY A.NAMA, A.NUM  ORDER BY NUM
				";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectJabatanJenisKelamin($limit=-1,$from=-1, $statement='')
	{
		$str = "			
					SELECT A.NAMA JABATAN, 
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(UMUR25L) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(UMUR2535L) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(UMUR3645L)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(UMUR4655L)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(UMUR56L)               
                       END LAKI,        
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(UMUR25P) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(UMUR2535P) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(UMUR3645P)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(UMUR4655P)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(UMUR56P)                      
                       END PEREMPUAN,      
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(UMUR25P) + SUM(UMUR25L) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(UMUR2535P) + SUM(UMUR2535L) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(UMUR3645P) + SUM(UMUR3645L) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(UMUR4655P) + SUM(UMUR4655L) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(UMUR56P) + SUM(UMUR56L)              
                       END JUMLAH 
                        FROM
                                (
                                SELECT A.NUM, A.NAMA,
                                    CASE WHEN JENIS_KELAMIN = 'L' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L,
                                    CASE WHEN JENIS_KELAMIN = 'P' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P,
                                    CASE WHEN JENIS_KELAMIN = 'L' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L,
                                    CASE WHEN JENIS_KELAMIN = 'P' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P,
                                    CASE WHEN JENIS_KELAMIN = 'L' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L,
                                    CASE WHEN JENIS_KELAMIN = 'P' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P,
                                    CASE WHEN JENIS_KELAMIN = 'L' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L,
                                    CASE WHEN JENIS_KELAMIN = 'P' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P,
                                    CASE WHEN JENIS_KELAMIN = 'L' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L,
                                    CASE WHEN JENIS_KELAMIN = 'P' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P
                                FROM (
                                      SELECT 1 NUM, 'Pejabat Struktural' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 2 NUM, 'Fungsional Umum/Staf' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 3 NUM, 'Fungsional Khusus/Pendidikan' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 4 NUM, 'Fungsional Khusus/Kesehatan' NAMA FROM DUAL                      
                                      UNION ALL
                                      SELECT 5 NUM, 'Fungsional Khusus/Lain-lain' NAMA FROM DUAL                      
                                      ) A LEFT JOIN (
                                SELECT   JENIS_KELAMIN, TIPE_PEGAWAI_ID, COUNT (A.PEGAWAI_ID) JUMLAH
                                            FROM pegawai A
                                           WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
                                        GROUP BY JENIS_KELAMIN, TIPE_PEGAWAI_ID) B ON 1 = 1
                                 GROUP BY A.NAMA, JENIS_KELAMIN, A.NUM, TIPE_PEGAWAI_ID
                                 ) A GROUP BY A.NAMA, A.NUM  ORDER BY NUM
				";
				
		$this->query = $str;			
		return $this->selectLimit($str,$limit,$from); 
	}	

	function selectJabatanGolongan($limit=-1,$from=-1, $statement='')
	{
		$str = "			
					SELECT A.NAMA JABATAN, 
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS1) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS1) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP1)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK1)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL1)               
                       END GOL1,        
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS2) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS2) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP2)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK2)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL2)               
                       END GOL2,      
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS3) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS3) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP3)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK3)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL3)               
                       END GOL3,      
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS4) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS4) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP4)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK4)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL4)               
                       END GOL4,      
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS1) + SUM(PS2) + SUM(PS3) + SUM(PS4) 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS1) + SUM(FS2) + SUM(FS3) + SUM(FS4) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP1) + SUM(FP2) + SUM(FP3) + SUM(FP4) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK1) + SUM(FK2) + SUM(FK3) + SUM(FK4) 
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL1) + SUM(FL2) + SUM(FL3) + SUM(FL4)       
                       END JUMLAH 
                        FROM
                                (
                                SELECT A.NUM, A.NAMA,
                                    CASE WHEN PANGKAT_ID LIKE '1%' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS PS1,
                                    CASE WHEN PANGKAT_ID LIKE '2%' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS PS2,
                                    CASE WHEN PANGKAT_ID LIKE '3%' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS PS3,
                                    CASE WHEN PANGKAT_ID LIKE '4%' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS PS4,
                                    CASE WHEN PANGKAT_ID LIKE '1%' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS FS1,
                                    CASE WHEN PANGKAT_ID LIKE '2%' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS FS2,
                                    CASE WHEN PANGKAT_ID LIKE '3%' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS FS3,
                                    CASE WHEN PANGKAT_ID LIKE '4%' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS FS4,
                                    CASE WHEN PANGKAT_ID LIKE '1%' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS FP1,
                                    CASE WHEN PANGKAT_ID LIKE '2%' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS FP2,
                                    CASE WHEN PANGKAT_ID LIKE '3%' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS FP3,
                                    CASE WHEN PANGKAT_ID LIKE '4%' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS FP4,
                                    CASE WHEN PANGKAT_ID LIKE '1%' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS FK1,
                                    CASE WHEN PANGKAT_ID LIKE '2%' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS FK2,
                                    CASE WHEN PANGKAT_ID LIKE '3%' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS FK3,
                                    CASE WHEN PANGKAT_ID LIKE '4%' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS FK4,
                                    CASE WHEN PANGKAT_ID LIKE '1%' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS FL1,
                                    CASE WHEN PANGKAT_ID LIKE '2%' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS FL2,
                                    CASE WHEN PANGKAT_ID LIKE '3%' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS FL3,
                                    CASE WHEN PANGKAT_ID LIKE '4%' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS FL4
                                FROM (
                                      SELECT 1 NUM, 'Pejabat Struktural' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 2 NUM, 'Fungsional Umum/Staf' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 3 NUM, 'Fungsional Khusus/Pendidikan' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 4 NUM, 'Fungsional Khusus/Kesehatan' NAMA FROM DUAL                      
                                      UNION ALL
                                      SELECT 5 NUM, 'Fungsional Khusus/Lain-lain' NAMA FROM DUAL                      
                                      ) A LEFT JOIN (
                                SELECT   TIPE_PEGAWAI_ID, PANGKAT_ID, COUNT (A.PEGAWAI_ID) JUMLAH
                                            FROM pegawai A INNER JOIN PANGKAT_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                                           WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
                                        GROUP BY TIPE_PEGAWAI_ID, PANGKAT_ID) B ON 1 = 1
                                 GROUP BY A.NAMA, A.NUM, TIPE_PEGAWAI_ID, PANGKAT_ID
                                 ) A GROUP BY A.NAMA, A.NUM  ORDER BY NUM
				";
				
		$this->query = $str;			
		return $this->selectLimit($str,$limit,$from); 
	}	


	function selectJabatanPendidikan($limit=-1,$from=-1, $statement='')
	{
		$arrPendidikan = array(
							0,
							1,
							2,
							4,
							5,
							6,
							7,
							8,
							9,
							10,
							11
							);
		
		$str = "			
					SELECT A.NAMA JABATAN, ";
		for($i=0;$i<count($arrPendidikan);$i++)
		{
		$str .= "
                       CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS".$arrPendidikan[$i].") 
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS".$arrPendidikan[$i].") 
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP".$arrPendidikan[$i].")
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK".$arrPendidikan[$i].")
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL".$arrPendidikan[$i].")               
                       END PENDIDIKAN".$arrPendidikan[$i].",
			     ";
		}
        $str .= "      CASE 
                       WHEN  A.NAMA = 'Pejabat Struktural' THEN SUM(PS0) + SUM(PS1) + SUM(PS2) + SUM(PS4) + SUM(PS5) + SUM(PS6) + SUM(PS7) + SUM(PS8) + SUM(PS9) + SUM(PS10) + SUM(PS11)
                       WHEN  A.NAMA = 'Fungsional Umum/Staf' THEN SUM(FS0) + SUM(FS1) + SUM(FS2) + SUM(FS4) + SUM(FS5) + SUM(FS6) + SUM(FS7) + SUM(FS8) + SUM(FS9) + SUM(FS10) + SUM(FS11)
                       WHEN  A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(FP0) + SUM(FP1) + SUM(FP2) + SUM(FP4) + SUM(FP5) + SUM(FP6) + SUM(FP7) + SUM(FP8) + SUM(FP9) + SUM(FP10) + SUM(FP11)
                       WHEN  A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(FK0) + SUM(FK1) + SUM(FK2) + SUM(FK4) + SUM(FK5) + SUM(FK6) + SUM(FK7) + SUM(FK8) + SUM(FK9) + SUM(FK10) + SUM(FK11)
                       WHEN  A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(FL0) + SUM(FL1) + SUM(FL2) + SUM(FL4) + SUM(FL5) + SUM(FL6) + SUM(FL7) + SUM(FL8) + SUM(FL9) + SUM(FL10) + SUM(FL11)
                       END JUMLAH 
                        FROM
                                (
                                SELECT A.NUM, A.NAMA,
					";
				for($i=0;$i<count($arrPendidikan);$i++)
				{	
        $str .= "         		    CASE WHEN PENDIDIKAN_ID = '".$arrPendidikan[$i]."' AND TIPE_PEGAWAI_ID IN (1, 11) AND A.NAMA = 'Pejabat Struktural' THEN SUM(JUMLAH) ELSE 0 END AS PS".$arrPendidikan[$i].",
                                    CASE WHEN PENDIDIKAN_ID = '".$arrPendidikan[$i]."' AND TIPE_PEGAWAI_ID = 12 AND A.NAMA = 'Fungsional Umum/Staf' THEN SUM(JUMLAH) ELSE 0 END AS FS".$arrPendidikan[$i].",
                                    CASE WHEN PENDIDIKAN_ID = '".$arrPendidikan[$i]."' AND TIPE_PEGAWAI_ID = 21 AND A.NAMA = 'Fungsional Khusus/Pendidikan' THEN SUM(JUMLAH) ELSE 0 END AS FP".$arrPendidikan[$i].",
                                    CASE WHEN PENDIDIKAN_ID = '".$arrPendidikan[$i]."' AND TIPE_PEGAWAI_ID = 22 AND A.NAMA = 'Fungsional Khusus/Kesehatan' THEN SUM(JUMLAH) ELSE 0 END AS FK".$arrPendidikan[$i].",
                                    CASE WHEN PENDIDIKAN_ID = '".$arrPendidikan[$i]."' AND TIPE_PEGAWAI_ID = 23 AND A.NAMA = 'Fungsional Khusus/Lain-lain' THEN SUM(JUMLAH) ELSE 0 END AS FL".$arrPendidikan[$i].",
			    ";
				}
		$str .= "									
									1
                                FROM (
                                      SELECT 1 NUM, 'Pejabat Struktural' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 2 NUM, 'Fungsional Umum/Staf' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 3 NUM, 'Fungsional Khusus/Pendidikan' NAMA FROM DUAL
                                      UNION ALL
                                      SELECT 4 NUM, 'Fungsional Khusus/Kesehatan' NAMA FROM DUAL                      
                                      UNION ALL
                                      SELECT 5 NUM, 'Fungsional Khusus/Lain-lain' NAMA FROM DUAL                      
                                      ) A LEFT JOIN (
                                SELECT   TIPE_PEGAWAI_ID, PENDIDIKAN_ID, COUNT (A.PEGAWAI_ID) JUMLAH
                                            FROM pegawai A INNER JOIN PENDIDIKAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                                           WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
                                        GROUP BY TIPE_PEGAWAI_ID,  PENDIDIKAN_ID) B ON 1 = 1
                                 GROUP BY A.NAMA, A.NUM, TIPE_PEGAWAI_ID,  PENDIDIKAN_ID
                                 ) A GROUP BY A.NAMA, A.NUM  ORDER BY NUM
				";
				
		$this->query = $str;			
		return $this->selectLimit($str,$limit,$from); 
	}	
			
	function selectBySatkerUmur($limit=-1,$from=-1, $statement='')
	{
		$str = "			
					SELECT A.SATKER_ID, A.NAMA, 
						   SUM(UMUR25L) UMUR25L, SUM(UMUR25P) UMUR25P,
						   SUM(UMUR2535L) UMUR2535L, SUM(UMUR2535P) UMUR2535P,
						   SUM(UMUR3645L) UMUR3645L, SUM(UMUR3645P) UMUR3645P,
						   SUM(UMUR4655L) UMUR4655L, SUM(UMUR4655P) UMUR4655P,
						   SUM(UMUR56L) UMUR56L, SUM(UMUR56P) UMUR56P,
						   SUM(JUMLAH) TOTAL
					FROM
					(
					SELECT A.SATKER_ID, A.NAMA, 
							CASE WHEN JENIS_KELAMIN = 'L' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L,
							CASE WHEN JENIS_KELAMIN = 'P' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P,
							CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L,
							CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P,
							CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L,
							CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P,
							CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L,
							CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P,
							CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L,
							CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P,
							SUM(JUMLAH) JUMLAH
					  FROM satker A
						   LEFT JOIN
						   (SELECT   SATKER_ID, JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR) UMUR, COUNT (PEGAWAI_ID) JUMLAH
								FROM pegawai
							   WHERE STATUS_PEGAWAI IN (1, 2)
							GROUP BY SATKER_ID, JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR)) B ON B.SATKER_ID LIKE  A.SATKER_ID || '%'
					  WHERE 1 = 1 ".$statement." GROUP BY A.SATKER_ID, A.NAMA, JENIS_KELAMIN, UMUR
					) A 
				";
		
		
		$str .= " GROUP BY  A.SATKER_ID, A.NAMA ORDER BY A.SATKER_ID ";
		$this->query = $str;			
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function selectBySatkerJabatan($limit=-1,$from=-1, $statement='')
	{
		$str = "			
			SELECT NAMA,
				   HITUNG_SATKER_JABATAN(SATKER_ID, 11) JUMLAH_PENJABAT, 
				   HITUNG_SATKER_JABATAN(SATKER_ID, 4) JUMLAH_STAF, 
				   HITUNG_SATKER_JABATAN(SATKER_ID, 2) JUMLAH_FK, 
				   HITUNG_SATKER_JABATAN(SATKER_ID, 3) JUMLAH_STRUKTURAL, 
				   HITUNG_SATKER_JABATAN(SATKER_ID, 5) JUMLAH_STRUKTURAL_KOSONG, 
				   HITUNG_SATKER_JABATAN(SATKER_ID, 0) TOTAL  
				   FROM satker WHERE 1 = 1
				";
		
		$this->query = $str;	
		$str .= $statement." ORDER BY SATKER_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function selectBySatkerJabatanPerSkpd($limit=-1,$from=-1, $satker_id='')
	{
		$str = "			
			SELECT NAMA, JUMLAH_STRUKTURAL, JUMLAH_STAF, TOTAL
			FROM
			(
					SELECT NAMA,	   
					HITUNG_SATKER_JABATAN(SATKER_ID, 5) JUMLAH_STRUKTURAL, 
                    HITUNG_SATKER_JABATAN(SATKER_ID, 10) JUMLAH_STAF, 
				    SUM(HITUNG_SATKER_JABATAN(SATKER_ID, 5)) TOTAL  
				    FROM satker WHERE 1 = 1
                    AND SATKER_ID = '".$satker_id."'
                    GROUP BY NAMA, SATKER_ID
			)
			UNION ALL                   
					SELECT NAMA,	   
					HITUNG_SATKER_JABATAN(SATKER_ID, 3) JUMLAH_STRUKTURAL, 
                    HITUNG_SATKER_JABATAN(SATKER_ID, 4) JUMLAH_STAF, 
				    SUM(HITUNG_SATKER_JABATAN(SATKER_ID, 3)) + SUM(HITUNG_SATKER_JABATAN(SATKER_ID, 4)) TOTAL  
				    FROM satker WHERE 1 = 1
                    AND SATKER_ID_PARENT = '".$satker_id."'
                    GROUP BY NAMA, SATKER_ID
			";
		$this->query = $str;	
		return $this->selectLimit($str,$limit,$from); 
	}
				   
	function selectByRekapJumlahPegawai($limit=-1,$from=-1, $statement='')
	{
		$arrPangkat = array(11,
							12,
							13,
							14,
							21,
							22,
							23,
							24,
							31,
							32,
							33,
							34,
							41,
							42,
							43,
							44,
							45
							);
		$arrEselon = array( 11,
							12,
							21,
							22,
							31,
							32,
							41,
							42,
							51,
							52
							);
		$arrPendidikan = array(
							0,
							1,
							2,
							4,
							5,
							6,
							7,
							8,
							9,
							10,
							11
							);
		$str = "			
 					SELECT A.SATKER_ID, A.NAMA, 
						   SUM(LELAKI) JUMLAH_LAKI, SUM(WANITA) JUMLAH_PEREMPUAN, 
                           ";
					for($i=0;$i<count($arrPangkat);$i++)
					{
		$str .= "	 SUM(PANGKAT".$arrPangkat[$i].") PANGKAT".$arrPangkat[$i].", "; 											
					}
					for($i=0;$i<count($arrEselon);$i++)
					{
		$str .= "	 SUM(ESELON".$arrEselon[$i].") ESELON".$arrEselon[$i].", "; 											
					}
					for($i=0;$i<count($arrPendidikan);$i++)
					{
		$str .= "	 SUM(PENDIDIKAN".$arrPendidikan[$i].") PENDIDIKAN".$arrPendidikan[$i].", "; 											
					}
						  
       $str .=      " 1
					FROM
					(
					SELECT A.SATKER_ID, A.NAMA, 
							CASE WHEN JENIS_KELAMIN = 'L' THEN SUM(B.JUMLAH) ELSE 0 END AS LELAKI,
							CASE WHEN JENIS_KELAMIN = 'P' THEN SUM(B.JUMLAH) ELSE 0 END AS WANITA,
					";
					
					for($i=0;$i<count($arrPangkat);$i++)
					{
		$str .= "	 		CASE WHEN B.PANGKAT_ID = '".$arrPangkat[$i]."' THEN SUM(B.JUMLAH) ELSE 0 END AS PANGKAT".$arrPangkat[$i].", "; 											
					}			
					for($i=0;$i<count($arrEselon);$i++)
					{
		$str .= "	 		CASE WHEN B.ESELON_ID = '".$arrEselon[$i]."' THEN SUM(B.JUMLAH) ELSE 0 END AS ESELON".$arrEselon[$i].", "; 											
					}			
					for($i=0;$i<count($arrPendidikan);$i++)
					{
		$str .= "	 		CASE WHEN B.PENDIDIKAN_ID = '".$arrPendidikan[$i]."' THEN SUM(B.JUMLAH) ELSE 0 END AS PENDIDIKAN".$arrPendidikan[$i].", "; 											
					}										
		$str .=	"							
                            1
                      FROM satker A
                           LEFT JOIN
                           (SELECT   A.SATKER_ID, JENIS_KELAMIN, ESELON_ID, PANGKAT_ID, PENDIDIKAN_ID, COUNT (A.PEGAWAI_ID) JUMLAH
                                FROM pegawai A 
                                INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                                INNER JOIN PANGKAT_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
                                INNER JOIN PENDIDIKAN_TERAKHIR D ON A.PEGAWAI_ID = D.PEGAWAI_ID
                               WHERE STATUS_PEGAWAI IN (1, 2)
                            GROUP BY A.SATKER_ID, ESELON_ID, JENIS_KELAMIN, PANGKAT_ID, PENDIDIKAN_ID) B ON B.SATKER_ID LIKE  A.SATKER_ID || '%' 
                      WHERE 1 = 1 ".$statement." GROUP BY A.SATKER_ID, A.NAMA, JENIS_KELAMIN, B.ESELON_ID, B.PANGKAT_ID, B.PENDIDIKAN_ID
                    ) A GROUP BY  A.SATKER_ID, A.NAMA ORDER BY A.SATKER_ID
				";
		
		$this->query = $str;	
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function selectByJabatanUmurStaf($limit=-1,$from=-1, $statement='')
	{
		$str = "			
			SELECT 'JUMLAH STAF' NAMA, 
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'A') LAKI25,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'A') PEREMPUAN25,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'B') LAKI2535,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'B') PEREMPUAN2535,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'C') LAKI3645,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'C') PEREMPUAN3645,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'D') LAKI4655,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'D') PEREMPUAN4655,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'E') LAKI56,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'E') PEREMPUAN56,
			   HITUNG_JABATAN_UMUR_STAF( '".$statement."', '', 'F') TOTAL
			FROM DUAL
				";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByJabatanGolonganStaf($limit=-1,$from=-1, $statement='')
	{
		$str = "						
				SELECT NAMA, LAKI25, PEREMPUAN25, LAKI2535, PEREMPUAN2535, LAKI3645, PEREMPUAN3645, LAKI4655, PEREMPUAN4655,
							   LAKI25+PEREMPUAN25+LAKI2535+PEREMPUAN2535+LAKI3645+PEREMPUAN3645+LAKI4655+PEREMPUAN4655 TOTAL
				FROM( 
				SELECT 'JUMLAH STAF' NAMA, 
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '1') LAKI25,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '1') PEREMPUAN25,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '2') LAKI2535,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '2') PEREMPUAN2535,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '3') LAKI3645,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '3') PEREMPUAN3645,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '4') LAKI4655,
						   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '4') PEREMPUAN4655
						FROM DUAL  ) JABATAN_GOL_STAF				";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function selectByJabatanUmurStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "			
				   SELECT 'Struktural' KELOMPOK, A.NAMA, A.NUM, 
					CASE 
					   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L1) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L1) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L1)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L1)               
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L1)
					   END UMUR1_LELAKI,        
					CASE
					   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P1) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P1) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P1)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P1)
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P1)               
					   END UMUR1_PEREMPUAN,       
					CASE 
					   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L2) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L2) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L2)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L2)               
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L2)
					   END UMUR2_LELAKI,        
					CASE
					   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P2) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P2) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P2)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P2)
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P2)               
					   END UMUR2_PEREMPUAN,
					CASE 
					   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L3) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L3) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L3)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L3)               
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L3)
					   END UMUR3_LELAKI,        
					CASE
					   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P3) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P3) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P3)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P3)
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P3)               
					   END UMUR3_PEREMPUAN,
					   CASE 
					   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L4) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L4) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L4)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L4)               
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L4)
					   END UMUR4_LELAKI,        
					CASE
					   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P4) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P4) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P4)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P4)
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P4)               
					   END UMUR4_PEREMPUAN,      
						  CASE 
					   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L5) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L5) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L5)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L5)               
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L5)
					   END UMUR5_LELAKI,        
					CASE
					   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P5) 
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P5) 
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P5)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P5)
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P5)               
					   END UMUR5_PEREMPUAN,    
					   CASE 
					   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P1) +  SUM(PANGKAT1L1) + SUM(PANGKAT1P2) +  SUM(PANGKAT1L2) + SUM(PANGKAT1P3) +  SUM(PANGKAT1L3) + SUM(PANGKAT1P4) +  SUM(PANGKAT1L4) + SUM(PANGKAT1P5) +  SUM(PANGKAT1L5)    
					   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P1) + SUM(PANGKAT2L1) + SUM(PANGKAT2P2) + SUM(PANGKAT2L2) + SUM(PANGKAT2P3) + SUM(PANGKAT2L3) + SUM(PANGKAT2P4) + SUM(PANGKAT2L4) + SUM(PANGKAT2P5) + SUM(PANGKAT2L5)    
					   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P1) + SUM(PANGKAT3L1) + SUM(PANGKAT3P2) + SUM(PANGKAT3L2) + SUM(PANGKAT3P3) + SUM(PANGKAT3L3) + SUM(PANGKAT3P4) + SUM(PANGKAT3L4) + SUM(PANGKAT3P5) + SUM(PANGKAT3L5)
					   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P1) + SUM(PANGKAT4L1) + SUM(PANGKAT4P2)  +   SUM(PANGKAT4L2) + SUM(PANGKAT4P3)  +   SUM(PANGKAT4L3) + SUM(PANGKAT4P4)  +   SUM(PANGKAT4L4) + SUM(PANGKAT4P5)  +   SUM(PANGKAT4L5)
					   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P1) + SUM(PANGKAT5L1) + SUM(PANGKAT5P2)  +   SUM(PANGKAT5L2) + SUM(PANGKAT5P3)  +   SUM(PANGKAT5L3) + SUM(PANGKAT5P4)  +   SUM(PANGKAT5L4) + SUM(PANGKAT5P5)  +   SUM(PANGKAT5L5)              
					   END TOTAL         
										FROM
								(
								SELECT A.NUM, A.NAMA,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR < 25  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L1,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P1,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR BETWEEN 25 AND 35  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L2,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P2,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L3,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR BETWEEN 36 AND 45  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P3,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR BETWEEN 46 AND 55  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L4,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P4,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR > 55  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L5,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P5,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L1,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P1,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L2,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P2,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L3,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P3,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L4,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P4,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L5,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P5,  
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L1,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P1,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L2,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P2,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L3,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P3,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L4,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P4,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L5,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P5,      
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR < 25  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L1,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR < 25 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P1, 
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR BETWEEN 25 AND 35  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L2,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR BETWEEN 25 AND 35  THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P2,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L3,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR BETWEEN 36 AND 45 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P3,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR BETWEEN 46 AND 55  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L4,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR BETWEEN 46 AND 55 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P4,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR > 55  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L5,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND UMUR > 55 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P5,     																	 
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR < 25  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L1,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR < 25 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P1,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR BETWEEN 25 AND 35  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L2,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR BETWEEN 25 AND 35 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P2,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR BETWEEN 36 AND 45  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L3,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR BETWEEN 36 AND 45 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P3,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR BETWEEN 46 AND 55  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L4,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR BETWEEN 46 AND 55 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P4,
											 CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR > 55  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L5,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND UMUR > 55 THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P5
								FROM (
									  SELECT 1 NUM, 'ESELON I' NAMA FROM DUAL 
									  UNION ALL
									  SELECT 2 NUM, 'ESELON II' NAMA FROM DUAL
									  UNION ALL
									  SELECT 3 NUM, 'ESELON III' NAMA FROM DUAL
									  UNION ALL
									  SELECT 4 NUM, 'ESELON IV' NAMA FROM DUAL 
									  UNION ALL
									  SELECT 5 NUM, 'ESELON V' NAMA FROM DUAL
									  ) A 
								LEFT JOIN (
								  SELECT   B.ESELON_ID, A.JENIS_KELAMIN, AMBIL_UMUR(A.TANGGAL_LAHIR) UMUR, COUNT (A.PEGAWAI_ID) JUMLAH
												FROM pegawai A INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
											   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
											GROUP BY B.ESELON_ID, JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR)) B ON 1=1
								 GROUP BY A.NAMA, B.ESELON_ID,  JENIS_KELAMIN, UMUR, A.NUM 
								 ) A GROUP BY  A.NAMA, A.NUM  
				UNION ALL
				 SELECT 'Staf' KELOMPOK, 'Staf' AS NAMA, 6 NUM,  
											 SUM(LELAKI1) AS UMUR1_L, SUM(PEREMPUAN1) AS UMUR1_P,
											 SUM(LELAKI2) AS UMUR2_L, SUM(PEREMPUAN2) AS UMUR2_P,
											 SUM(LELAKI3) AS UMUR3_L, SUM(PEREMPUAN3) AS UMUR3_P,
											 SUM(LELAKI4) AS UMUR4_L, SUM(PEREMPUAN4) AS UMUR4_P,
											 SUM(LELAKI5) AS UMUR5_L, SUM(PEREMPUAN5) AS UMUR5_P,
										SUM(LELAKI1)+ SUM(PEREMPUAN1)+SUM(LELAKI2)+SUM(PEREMPUAN2)+SUM(LELAKI3)+SUM(PEREMPUAN3)+SUM(LELAKI4)+SUM(PEREMPUAN4)+SUM(LELAKI5)+SUM(PEREMPUAN5) TOTAL
								FROM
								(
								SELECT 
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI1,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN1,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI2,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN2,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI3,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN3,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI4,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN4,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI5,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN5,
									SUM(JUMLAH) JUMLAH   
								FROM(
								SELECT   JENIS_KELAMIN,  AMBIL_UMUR(TANGGAL_LAHIR) UMUR, COUNT(A.PEGAWAI_ID) JUMLAH
											FROM pegawai A  
										   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement." AND TIPE_PEGAWAI_ID = '12'
										GROUP BY  JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR)) A
								 GROUP BY JENIS_KELAMIN, UMUR
								 )  A
				UNION ALL
				SELECT  'Fungsional' KELOMPOK, A.NAMA AS NAMA, 7 NUM,  
											 SUM(LELAKI1) AS UMUR1_L, SUM(PEREMPUAN1) AS UMUR1_P,
											 SUM(LELAKI2) AS UMUR2_L, SUM(PEREMPUAN2) AS UMUR2_P,
											 SUM(LELAKI3) AS UMUR3_L, SUM(PEREMPUAN3) AS UMUR3_P,
											 SUM(LELAKI4) AS UMUR4_L, SUM(PEREMPUAN4) AS UMUR4_P,
											 SUM(LELAKI5) AS UMUR5_L, SUM(PEREMPUAN5) AS UMUR5_P,
											 SUM(JUMLAH) AS TOTAL                        
								FROM
								(
								SELECT   A.JABATAN_FUNGSIONAL_ID, A.NAMA,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI1,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN1,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI2,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN2,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI3,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN3,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI4,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN4,
											  CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS LELAKI5,
											  CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN5,
											  SUM(JUMLAH) JUMLAH   
								FROM JABATAN_FUNGSIONAL A LEFT JOIN (
								SELECT   B.JABATAN_FUNGSIONAL_ID,  JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR) UMUR,  COUNT (A.PEGAWAI_ID) JUMLAH
											FROM pegawai A 
											INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
													   WHERE STATUS_PEGAWAI IN (1, 2)  ".$statement."
										GROUP BY JABATAN_FUNGSIONAL_ID,  JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR)) B ON A.JABATAN_FUNGSIONAL_ID = B.JABATAN_FUNGSIONAL_ID
										WHERE LENGTH(A.JABATAN_FUNGSIONAL_ID) = 4
								 GROUP BY A.JABATAN_FUNGSIONAL_ID,  A.NAMA, JENIS_KELAMIN, UMUR 
								 ) A GROUP BY  A.JABATAN_FUNGSIONAL_ID, A.NAMA                  
					ORDER BY NUM, NAMA
				";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	function getCountByJabatanUmurStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "	SELECT COUNT(*)+3 ROWCOUNT	
					FROM(
					SELECT 'STRUKTURAL' GROUPING_NAMA,
									   'ESELON I' NAMA, HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'L', 'A') LAKI25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'P', 'A') PEREMPUAN25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'L', 'B') LAKI2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'P', 'B') PEREMPUAN2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'L', 'C') LAKI3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'P', 'C') PEREMPUAN3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'L', 'D') LAKI4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'P', 'D') PEREMPUAN4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'L', 'E') LAKI56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', 'P', 'E') PEREMPUAN56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('1', '".$statement."', '', 'F') TOTAL
								 FROM DUAL
								UNION ALL
								SELECT 'STRUKTURAL' GROUPING_NAMA,'ESELON II' NAMA, HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'L', 'A') LAKI25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'P', 'A') PEREMPUAN25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'L', 'B') LAKI2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'P', 'B') PEREMPUAN2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'L', 'C') LAKI3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'P', 'C') PEREMPUAN3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'L', 'D') LAKI4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'P', 'D') PEREMPUAN4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'L', 'E') LAKI56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', 'P', 'E') PEREMPUAN56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('2', '".$statement."', '', 'F') TOTAL
								 FROM DUAL
								UNION ALL 
								SELECT 'STRUKTURAL' GROUPING_NAMA,'ESELON III' NAMA, HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'L', 'A') LAKI25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'P', 'A') PEREMPUAN25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'L', 'B') LAKI2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'P', 'B') PEREMPUAN2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'L', 'C') LAKI3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'P', 'C') PEREMPUAN3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'L', 'D') LAKI4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'P', 'D') PEREMPUAN4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'L', 'E') LAKI56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', 'P', 'E') PEREMPUAN56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('3', '".$statement."', '', 'F') TOTAL
								 FROM DUAL
								UNION ALL 
								SELECT 'STRUKTURAL' GROUPING_NAMA,'ESELON IV' NAMA, HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'L', 'A') LAKI25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'P', 'A') PEREMPUAN25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'L', 'B') LAKI2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'P', 'B') PEREMPUAN2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'L', 'C') LAKI3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'P', 'C') PEREMPUAN3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'L', 'D') LAKI4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'P', 'D') PEREMPUAN4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'L', 'E') LAKI56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', 'P', 'E') PEREMPUAN56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('4', '".$statement."', '', 'F') TOTAL
								 FROM DUAL
								UNION ALL 
								SELECT 'STRUKTURAL' GROUPING_NAMA,'ESELON V' NAMA, HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'L', 'A') LAKI25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'P', 'A') PEREMPUAN25,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'L', 'B') LAKI2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'P', 'B') PEREMPUAN2535,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'L', 'C') LAKI3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'P', 'C') PEREMPUAN3645,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'L', 'D') LAKI4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'P', 'D') PEREMPUAN4655,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'L', 'E') LAKI56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', 'P', 'E') PEREMPUAN56,
									   HITUNG_JABATAN_UMUR_STRUKTURAL('5', '".$statement."', '', 'F') TOTAL
								 FROM DUAL
							   UNION ALL
								SELECT 'FUNGSIONAL' GROUPING_NAMA, NAMA ,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', 'A') LAKI25,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', 'A') PEREMPUAN25,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', 'B') LAKI2535,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', 'B') PEREMPUAN2535,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', 'C') LAKI3645,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', 'C') PEREMPUAN3645,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', 'D') LAKI4655,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', 'D') PEREMPUAN4655,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', 'E') LAKI56,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', 'E') PEREMPUAN56,
									HITUNG_JABATAN_UMUR_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '', 'F') TOTAL         
								FROM JABATAN_FUNGSIONAL WHERE LENGTH(JABATAN_FUNGSIONAL_ID) = 4             
								UNION ALL
								SELECT 'STAFF' GROUPING_NAMA,'JUMLAH STAF' NAMA, 
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'A') LAKI25,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'A') PEREMPUAN25,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'B') LAKI2535,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'B') PEREMPUAN2535,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'C') LAKI3645,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'C') PEREMPUAN3645,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'D') LAKI4655,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'D') PEREMPUAN4655,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'L', 'E') LAKI56,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', 'P', 'E') PEREMPUAN56,
								   HITUNG_JABATAN_UMUR_STAF( '".$statement."', '', 'F') TOTAL
								FROM DUAL
							)A
							
				";
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	function selectDaftarAlamat($limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, JABATAN,
                C.ALAMAT ALAMAT_KANTOR,
                A.ALAMAT ALAMAT_RUMAH,
                A.TELEPON
                FROM pegawai A 
                    LEFT JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                    LEFT JOIN SATKER C ON A.SATKER_ID = C.SATKER_ID
                WHERE STATUS_PEGAWAI IN (1, 2) 
				";
		$str .= $statement." ORDER BY A.SATKER_ID ASC";
						
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectDaftarPensiun($limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT DISTINCT A.SATKER_ID, AMBIL_FORMAT_NIP_BARU (NIP_BARU) NIP_BARU,
                  NAMA,
                  JENIS_KELAMIN,
                  TEMPAT_LAHIR,
                  TANGGAL_LAHIR,
                  NMGOLRUANG PANGKAT,
                  GOL_RUANG GOL,
                  JABATAN,
                  TMT_PENSIUN
				FROM pegawai A
					 INNER JOIN PENSIUN_TERAKHIR X
						ON X.PEGAWAI_ID = A.PEGAWAI_ID
					 LEFT JOIN PANGKAT_TERAKHIR B
						ON A.PEGAWAI_ID = B.PEGAWAI_ID
					 LEFT JOIN JABATAN_TERAKHIR C
						ON A.PEGAWAI_ID = C.PEGAWAI_ID
			   WHERE 1 = 1 AND A.STATUS_PEGAWAI = 3
				";
		$str .= $statement." ORDER BY A.SATKER_ID ASC";
		$this->query = $str;	

				
		return $this->selectLimit($str,$limit,$from); 
	}

	function getCountDaftarPensiun($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(PEGAWAI_ID) ROWCOUNT     
				 FROM pegawai A INNER JOIN PENSIUN X ON  X.PEGAWAI_ID = A.PEGAWAI_ID
				";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;
	}
			
	function getCountDaftarAlamat($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(PEGAWAI_ID) ROWCOUNT     
				 FROM pegawai A WHERE STATUS_PEGAWAI IN (1, 2) 
				";
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;
	}
	
	function selectByJabatanJkStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "
			SELECT 'Struktural' KELOMPOK, A.NUM, A.NAMA, 
			   CASE 
				   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1L) 
				   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L) 
				   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L)
				   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L)               
				   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L)
				   END LELAKI,        
				   CASE
				   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P) 
				   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P) 
				   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P)
				   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P)
				   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P)               
				   END PEREMPUAN,
				   CASE 
				   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P) +  SUM(PANGKAT1L) 
				   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P) + SUM(PANGKAT2L) 
				   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P) + SUM(PANGKAT3L)
				   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P)  +   SUM(PANGKAT4L)
				   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P)  +   SUM(PANGKAT5L)             
				   END TOTAL         
									FROM
							(
							SELECT A.NUM, A.NAMA,
										CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L,
										CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P,
										CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L,
										CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P,
										CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L,
										CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P,
										CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L,
										CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P, 
										CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L,
										CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P
							FROM (
								  SELECT 1 NUM, 'ESELON I' NAMA FROM DUAL 
								  UNION ALL
								  SELECT 2 NUM, 'ESELON II' NAMA FROM DUAL
								  UNION ALL
								  SELECT 3 NUM, 'ESELON III' NAMA FROM DUAL
								  UNION ALL
								  SELECT 4 NUM, 'ESELON IV' NAMA FROM DUAL 
								  UNION ALL
								  SELECT 5 NUM, 'ESELON V' NAMA FROM DUAL
								  ) A 
							LEFT JOIN (
							SELECT   B.ESELON_ID, A.JENIS_KELAMIN,  COUNT (A.PEGAWAI_ID) JUMLAH
										FROM pegawai A INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID 
										WHERE STATUS_PEGAWAI IN (1, 2)  ".$statement."
									GROUP BY B.ESELON_ID, A.JENIS_KELAMIN ) B ON 1=1
							 GROUP BY A.NAMA, B.ESELON_ID, JENIS_KELAMIN, A.NUM 
							 ) A GROUP BY  A.NAMA, A.NUM  
			UNION ALL                  
						  SELECT 'Staf' KELOMPOK, 6 NUM ,'STAFF' AS NAMA, 
								   SUM(LELAKI) LELAKI, SUM(PEREMPUAN) PEREMPUAN,
								   SUM(JUMLAH) TOTAL
							FROM
							(
							SELECT 
								CASE WHEN JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI,
								CASE WHEN JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN,
								SUM(JUMLAH) JUMLAH   
							FROM(
							SELECT   JENIS_KELAMIN, COUNT(A.PEGAWAI_ID) JUMLAH
										FROM pegawai A  
									   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement." AND TIPE_PEGAWAI_ID = '12'
									GROUP BY  JENIS_KELAMIN) A
							 GROUP BY JENIS_KELAMIN
							 )  A
			UNION ALL
					   SELECT  'Fungsional' KELOMPOK, 7 NUM, A.NAMA AS NAMA, 
								   SUM(LELAKI) LELAKI, SUM(PEREMPUAN) PEREMPUAN,
								   SUM(JUMLAH) TOTAL
							FROM
							(
							SELECT A.JABATAN_FUNGSIONAL_ID, A.NAMA,
										  CASE WHEN JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI,
										  CASE WHEN JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN,
										  SUM(JUMLAH) JUMLAH   
							FROM JABATAN_FUNGSIONAL A LEFT JOIN (
							SELECT   B.JABATAN_FUNGSIONAL_ID, JENIS_KELAMIN,  COUNT (A.PEGAWAI_ID) JUMLAH
										FROM pegawai A INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
																   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
									GROUP BY JABATAN_FUNGSIONAL_ID, JENIS_KELAMIN) B ON A.JABATAN_FUNGSIONAL_ID = B.JABATAN_FUNGSIONAL_ID
									WHERE LENGTH(A.JABATAN_FUNGSIONAL_ID) = 4
							 GROUP BY A.JABATAN_FUNGSIONAL_ID, A.NAMA, JENIS_KELAMIN 
							 ) A GROUP BY  A.JABATAN_FUNGSIONAL_ID, A.NAMA  
					 ORDER BY NUM, NAMA 
	";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	function getCountByJabatanJkStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT COUNT(*) + 3 ROWCOUNT
				FROM(
				SELECT 'STRUKTURAL' GROUPING_NAMA, NAMA, LAKI25, PEREMPUAN25,
					   LAKI25+PEREMPUAN25 TOTAL
				FROM (
				SELECT 'ESELON I' NAMA, HITUNG_JABATAN_JK_STRUKTURAL('1', '".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_STRUKTURAL('1', '".$statement."', 'P') PEREMPUAN25
				 FROM DUAL
				UNION ALL
				SELECT 'ESELON II' NAMA, HITUNG_JABATAN_JK_STRUKTURAL('2', '".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_STRUKTURAL('2', '".$statement."', 'P') PEREMPUAN25
				 FROM DUAL
				UNION ALL 
				SELECT 'ESELON III' NAMA, HITUNG_JABATAN_JK_STRUKTURAL('3', '".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_STRUKTURAL('3', '".$statement."', 'P') PEREMPUAN25
				 FROM DUAL
				UNION ALL 
				SELECT 'ESELON IV' NAMA, HITUNG_JABATAN_JK_STRUKTURAL('4', '".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_STRUKTURAL('4', '".$statement."', 'P') PEREMPUAN25
				 FROM DUAL
				UNION ALL 
				SELECT 'ESELON V' NAMA, HITUNG_JABATAN_JK_STRUKTURAL('5', '".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_STRUKTURAL('5', '".$statement."', 'P') PEREMPUAN25
				 FROM DUAL) JABATAN_GOL_STRUKTURAL
				 UNION ALL
				SELECT 'FUNGSIONAL' GROUPING_NAMA, NAMA, LAKI25, PEREMPUAN25,
					   LAKI25+PEREMPUAN25 TOTAL
				FROM (
				SELECT NAMA, 
					   HITUNG_JABATAN_JK_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P') PEREMPUAN25
				 FROM JABATAN_FUNGSIONAL WHERE LENGTH(JABATAN_FUNGSIONAL_ID) = 4
				) JABATAN_JK_STRUKTURAL             
				UNION ALL
				SELECT 'STAFF' GROUPING_NAMA, NAMA, LAKI25, PEREMPUAN25,
					   LAKI25+PEREMPUAN25 TOTAL
				FROM (          
				SELECT 'JUMLAH STAF' NAMA, HITUNG_JABATAN_JK_STAF('".$statement."', 'L') LAKI25,
					   HITUNG_JABATAN_JK_STAF('".$statement."', 'P') PEREMPUAN25
				FROM DUAL
				) JABATAN_JK_STAF 
				)A         
	";
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	
	function selectByJabatanJkStaf($limit=-1,$from=-1, $statement='')
	{
		$str = "
			SELECT NAMA, LAKI25, PEREMPUAN25,
                   LAKI25+PEREMPUAN25 TOTAL
            FROM (          
            SELECT 'JUMLAH STAF' NAMA, HITUNG_JABATAN_JK_STAF('".$statement."', 'L') LAKI25,
                   HITUNG_JABATAN_JK_STAF('".$statement."', 'P') PEREMPUAN25
            FROM DUAL
            ) JABATAN_JK_STAF ";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function selectByJabatanJpStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "
					  SELECT 'Struktural' KELOMPOK, A.NAMA, A.NUM,
						CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P1) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P1) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P1)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P1)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P1)
						   END SD,        
						CASE
						   WHEN  A.NAMA = 'ESELON I' THEN SUM(ESELON1P2) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P2) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P2)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P2)
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P2)               
						   END SLTP,       
						CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P3) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P3) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P3)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P3)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P3)
						   END SLTA,        
						CASE
						   WHEN  A.NAMA = 'ESELON I' THEN SUM(ESELON1P4) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P4) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P4)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P4)
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P4)               
						   END DI,
						CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P5) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P5) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P5)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P5)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P5)
						   END DII,
						CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P6) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P6) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P6)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P6)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P6)
						   END DIII,                       
						CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P7) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P7) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P7)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P7)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P7)
						   END DIV,    
					   CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P8) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P8) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P8)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P8)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P8)
						   END S1,
					   CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P9) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P9) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P9)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P9)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P9)
						   END S2,
					  CASE 
						   WHEN  A.NAMA = 'ESELON I'  THEN SUM(ESELON1P10) 
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P10) 
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P10)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P10)               
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P10)
						   END S3, 
						   CASE 
						   WHEN  A.NAMA = 'ESELON I' THEN SUM(ESELON1P1) +  SUM(ESELON1P2) + SUM(ESELON1P3) +  SUM(ESELON1P4) + SUM(ESELON1P5) +  SUM(ESELON1P6) + SUM(ESELON1P7) +  SUM(ESELON1P8) + SUM(ESELON1P9) +  SUM(ESELON1P10)    
						   WHEN  A.NAMA = 'ESELON II' THEN SUM(ESELON2P1) +  SUM(ESELON2P2) + SUM(ESELON2P3) +  SUM(ESELON2P4) + SUM(ESELON2P5) +  SUM(ESELON2P6) + SUM(ESELON2P7) +  SUM(ESELON2P8) + SUM(ESELON2P9) +  SUM(ESELON2P10)    
						   WHEN  A.NAMA = 'ESELON III' THEN SUM(ESELON3P1) +  SUM(ESELON3P2) + SUM(ESELON3P3) +  SUM(ESELON3P4) + SUM(ESELON3P5) +  SUM(ESELON3P6) + SUM(ESELON3P7) +  SUM(ESELON3P8) + SUM(ESELON3P9) +  SUM(ESELON3P10)
						   WHEN  A.NAMA = 'ESELON IV' THEN SUM(ESELON4P1) +  SUM(ESELON4P2) + SUM(ESELON4P3) +  SUM(ESELON4P4) + SUM(ESELON4P5) +  SUM(ESELON4P6) + SUM(ESELON4P7) +  SUM(ESELON4P8) + SUM(ESELON4P9) +  SUM(ESELON4P10)
						   WHEN  A.NAMA = 'ESELON V' THEN SUM(ESELON5P1) +  SUM(ESELON5P2) + SUM(ESELON5P3) +  SUM(ESELON5P4) + SUM(ESELON5P5) +  SUM(ESELON5P6) + SUM(ESELON5P7) +  SUM(ESELON5P8) + SUM(ESELON5P9) +  SUM(ESELON5P10)              
						   END TOTAL         
											FROM
									(
									SELECT A.NUM, A.NAMA,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 1  THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P1,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 2  THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P2,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 4   THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P3,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 5  THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P4,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 6  THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P5,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 7   THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P6,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 8   THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P7,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 9  THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P8,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 10   THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P9,
												CASE WHEN  A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PENDIDIKAN_ID = 11  THEN SUM(JUMLAH) ELSE 0 END AS ESELON1P10,                            
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 1  THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P1,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 2  THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P2,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 4   THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P3,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 5  THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P4,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 6  THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P5,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 7   THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P6,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 8   THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P7,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 9  THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P8,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 10   THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P9,
												CASE WHEN  A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PENDIDIKAN_ID = 11  THEN SUM(JUMLAH) ELSE 0 END AS ESELON2P10,                            
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 1  THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P1,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 2  THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P2,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 4   THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P3,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 5  THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P4,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 6  THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P5,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 7   THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P6,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 8   THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P7,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 9  THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P8,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 10   THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P9,
												CASE WHEN  A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PENDIDIKAN_ID = 11  THEN SUM(JUMLAH) ELSE 0 END AS ESELON3P10,                            
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 1  THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P1,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 2  THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P2,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 4   THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P3,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 5  THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P4,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 6  THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P5,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 7   THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P6,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 8   THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P7,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 9  THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P8,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 10   THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P9,
												CASE WHEN  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PENDIDIKAN_ID = 11  THEN SUM(JUMLAH) ELSE 0 END AS ESELON4P10,                            
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 1  THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P1,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 2  THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P2,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 4   THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P3,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 5  THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P4,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 6  THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P5,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 7   THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P6,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 8   THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P7,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 9  THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P8,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 10   THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P9,
												CASE WHEN  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PENDIDIKAN_ID = 11  THEN SUM(JUMLAH) ELSE 0 END AS ESELON5P10
									FROM (
										  SELECT 1 NUM, 'ESELON I' NAMA FROM DUAL 
										  UNION ALL
										  SELECT 2 NUM, 'ESELON II' NAMA FROM DUAL
										  UNION ALL
										  SELECT 3 NUM, 'ESELON III' NAMA FROM DUAL
										  UNION ALL
										  SELECT 4 NUM, 'ESELON IV' NAMA FROM DUAL 
										  UNION ALL
										  SELECT 5 NUM, 'ESELON V' NAMA FROM DUAL
										  ) A 
									LEFT JOIN (
									  SELECT   B.ESELON_ID, C.PENDIDIKAN_ID,    COUNT (A.PEGAWAI_ID) JUMLAH
													FROM pegawai A INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
													INNER JOIN PENDIDIKAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
												   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
												GROUP BY B.ESELON_ID, C.PENDIDIKAN_ID) B ON 1=1
									 GROUP BY A.NAMA, B.ESELON_ID,  PENDIDIKAN_ID, A.NUM 
									 ) A GROUP BY  A.NAMA, A.NUM 
					UNION ALL
					SELECT 'Staf' KELOMPOK, 'Staf' AS NAMA, 6 NUM,  
											  SUM(PENDIDIKAN1) AS SD, SUM(PENDIDIKAN2) AS SLTP,
												 SUM(PENDIDIKAN3) AS SLTA, SUM(PENDIDIKAN4) AS DI,
												 SUM(PENDIDIKAN5) AS DII, SUM(PENDIDIKAN6) AS DIII,
												 SUM(PENDIDIKAN7) AS DIV, SUM(PENDIDIKAN8) AS S1,
												 SUM(PENDIDIKAN9) AS S2, SUM(PENDIDIKAN10) AS S3,
												 SUM(JUMLAH) AS TOTAL                        
									FROM
									(
									SELECT 
												 CASE WHEN B.PENDIDIKAN_ID = 1 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN1,
												  CASE WHEN B.PENDIDIKAN_ID = 2 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN2,
												  CASE WHEN B.PENDIDIKAN_ID = 4 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN3,
												  CASE WHEN B.PENDIDIKAN_ID = 5 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN4,
												  CASE WHEN B.PENDIDIKAN_ID = 6 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN5,
												  CASE WHEN B.PENDIDIKAN_ID = 7 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN6,
												  CASE WHEN B.PENDIDIKAN_ID = 8 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN7,
												  CASE WHEN B.PENDIDIKAN_ID = 9 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN8,
												  CASE WHEN B.PENDIDIKAN_ID = 10 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN9,
												  CASE WHEN B.PENDIDIKAN_ID = 11 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN10,
										SUM(JUMLAH) JUMLAH   
									FROM(
									SELECT    C.PENDIDIKAN_ID, COUNT(A.PEGAWAI_ID) JUMLAH
												FROM pegawai A  INNER JOIN PENDIDIKAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
											   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement." AND TIPE_PEGAWAI_ID = '12'
											GROUP BY  PENDIDIKAN_ID) B
									 GROUP BY  PENDIDIKAN_ID
									 )  A                 
					UNION ALL
					SELECT  'Fungsional' KELOMPOK, A.NAMA AS NAMA, 7 NUM, 
												 SUM(PENDIDIKAN1) AS SD, SUM(PENDIDIKAN2) AS SLTP,
												 SUM(PENDIDIKAN3) AS SLTA, SUM(PENDIDIKAN4) AS DI,
												 SUM(PENDIDIKAN5) AS DII, SUM(PENDIDIKAN6) AS DIII,
												 SUM(PENDIDIKAN7) AS DIV, SUM(PENDIDIKAN8) AS S1,
												 SUM(PENDIDIKAN9) AS S2, SUM(PENDIDIKAN10) AS S3,
												 SUM(JUMLAH) AS TOTAL                        
									FROM
									(
									SELECT   A.JABATAN_FUNGSIONAL_ID, A.NAMA,
												  CASE WHEN B.PENDIDIKAN_ID = 1 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN1,
												  CASE WHEN B.PENDIDIKAN_ID = 2 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN2,
												  CASE WHEN B.PENDIDIKAN_ID = 4 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN3,
												  CASE WHEN B.PENDIDIKAN_ID = 5 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN4,
												  CASE WHEN B.PENDIDIKAN_ID = 6 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN5,
												  CASE WHEN B.PENDIDIKAN_ID = 7 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN6,
												  CASE WHEN B.PENDIDIKAN_ID = 8 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN7,
												  CASE WHEN B.PENDIDIKAN_ID = 9 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN8,
												  CASE WHEN B.PENDIDIKAN_ID = 10 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN9,
												  CASE WHEN B.PENDIDIKAN_ID = 11 THEN SUM(JUMLAH) ELSE 0 END AS PENDIDIKAN10,
												  SUM(JUMLAH) JUMLAH   
									FROM JABATAN_FUNGSIONAL A LEFT JOIN (
									SELECT   B.JABATAN_FUNGSIONAL_ID,  C.PENDIDIKAN_ID,  COUNT (A.PEGAWAI_ID) JUMLAH
												FROM pegawai A 
												INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
												INNER JOIN PENDIDIKAN_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
														   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
											GROUP BY JABATAN_FUNGSIONAL_ID,  PENDIDIKAN_ID) B ON A.JABATAN_FUNGSIONAL_ID = B.JABATAN_FUNGSIONAL_ID
											WHERE LENGTH(A.JABATAN_FUNGSIONAL_ID) = 4
									 GROUP BY A.JABATAN_FUNGSIONAL_ID,  A.NAMA, PENDIDIKAN_ID
									 ) A GROUP BY  A.JABATAN_FUNGSIONAL_ID, A.NAMA 
			  ";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	function getCountJabatanJpStruktural($limit=-1,$from=-1, $statement='')
	{
			
		$str = "
			SELECT SUM(JUMLAH) +3 ROWCOUNT FROM (
			SELECT 5 JUMLAH FROM DUAL
			UNION ALL
			SELECT COUNT(*) JUMLAH FROM JABATAN_FUNGSIONAL WHERE LENGTH(JABATAN_FUNGSIONAL_ID) = 4
			UNION ALL
			SELECT 1 JUMLAH FROM DUAL) A				
			  ";
	
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	function getCountJabatanJpStruktural2($limit=-1,$from=-1, $statement='')
	{
			
		$str = "
			SELECT COUNT(NAMA) ROWCOUNT
					FROM (
						SELECT 'ESELON I' NAMA,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '1') SD,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '2') SLTP,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '4') SLTA,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '5') DI,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '6') DII,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '7') DIII,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '14') DIV,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '15') S1,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '16') S2,
							HITUNG_JABATAN_JP_STRUKTURAL('1', '".$statement."', '17') S3
						 FROM DUAL
						 UNION ALL
						 SELECT 'ESELON II' NAMA,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '1') SD,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '2') SLTP,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '4') SLTA,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '5') DI,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '6') DII,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '7') DIII,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '14') DIV,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '15') S1,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '16') S2,
							HITUNG_JABATAN_JP_STRUKTURAL('2', '".$statement."', '17') S3
						 FROM DUAL
						 UNION ALL
						 SELECT 'ESELON III' NAMA,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '1') SD,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '2') SLTP,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '4') SLTA,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '5') DI,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '6') DII,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '7') DIII,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '14') DIV,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '15') S1,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '16') S2,
							HITUNG_JABATAN_JP_STRUKTURAL('3', '".$statement."', '17') S3
						 FROM DUAL
						 UNION ALL
						 SELECT 'ESELON IV' NAMA,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '1') SD,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '2') SLTP,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '4') SLTA,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '5') DI,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '6') DII,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '7') DIII,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '14') DIV,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '15') S1,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '16') S2,
							HITUNG_JABATAN_JP_STRUKTURAL('4', '".$statement."', '17') S3
						 FROM DUAL
						 UNION ALL
						 SELECT 'ESELON V' NAMA,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '1') SD,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '2') SLTP,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '4') SLTA,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '5') DI,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '6') DII,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '7') DIII,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '14') DIV,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '15') S1,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '16') S2,
							HITUNG_JABATAN_JP_STRUKTURAL('5', '".$statement."', '17') S3
						 FROM DUAL
						 ) JABATAN_JP_STRUKTURAL
						 UNION ALL
						SELECT 'FUNGSIONAL', NAMA, SD, SLTP, SLTA, DI, DII, DIII, DIV, S1, S2, S3,
								SD + SLTP + SLTA + DI + DII + DIII + DIV + S1 + S2 + S3 TOTAL
						FROM
						(
						SELECT JABATAN_FUNGSIONAL_ID, NAMA ,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '1') SD,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '2') SLTP,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '4') SLTA,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '5') DI,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '6') DII,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '7') DIII,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '14') DIV,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '15') S1,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '16') S2,
							HITUNG_JABATAN_JP_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', '17') S3
						FROM JABATAN_FUNGSIONAL WHERE LENGTH(JABATAN_FUNGSIONAL_ID) = 4
					) JABATAN_GOLONGAN_FUNGSIONAL				
					UNION ALL    
					SELECT COUNT(NAMA) ROWCOUNT
						FROM( 
						SELECT 'JUMLAH STAF' NAMA, 
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '1') SD,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '2') SLTP,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '4') SLTA,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '5') DI,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '6') DII,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '7') DIII,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '14') DIV,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '15') S1,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '16') S2,
								   HITUNG_JABATAN_JP_STAF( '".$statement."', '17') S3
								FROM DUAL  ) JABATAN_JP_STAF   			
			  ";
	
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	
	function selectByJabatanJpStaf($limit=-1,$from=-1, $statement='')
	{
		$str = "
			SELECT NAMA, SD, SLTP, SLTA, DI, DII, DIII, DIV, S1, S2, S3,
					SD + SLTP + SLTA + DI + DII + DIII + DIV + S1 + S2 + S3 TOTAL
				FROM( 
				SELECT 'JUMLAH STAF' NAMA, 
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '1') SD,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '2') SLTP,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '4') SLTA,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '5') DI,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '6') DII,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '7') DIII,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '14') DIV,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '15') S1,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '16') S2,
						   HITUNG_JABATAN_JP_STAF( '".$statement."', '17') S3
						FROM DUAL  ) JABATAN_JP_STAF
			  ";
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function selectByJabatanGolonganStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "
			SELECT 'Struktural' KELOMPOK, A.NAMA, A.NUM,
				CASE 
				   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L1) 
				   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L1) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L1)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L1)               
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L1)
			   END GOL1_LELAKI,        
			CASE
			   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P1) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P1) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P1)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P1)
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P1)               
			   END GOL1_PEREMPUAN,       
			CASE 
			   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L2) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L2) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L2)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L2)               
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L2)
			   END GOL2_LELAKI,        
			CASE
			   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P2) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P2) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P2)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P2)
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P2)               
			   END GOL2_PEREMPUAN,
			CASE 
			   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L3) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L3) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L3)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L3)               
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L3)
			   END GOL3_LELAKI,        
			CASE
			   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P3) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P3) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P3)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P3)
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P3)               
			   END GOL3_PEREMPUAN,
			   CASE 
			   WHEN  A.NAMA = 'ESELON I'  THEN SUM(PANGKAT1L4) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2L4) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3L4)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4L4)               
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5L4)
			   END GOL4_LELAKI,        
			CASE
			   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P4) 
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P4) 
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P4)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P4)
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P4)               
			   END GOL4_PEREMPUAN,       
			   CASE 
			   WHEN  A.NAMA = 'ESELON I' THEN SUM(PANGKAT1P1) +  SUM(PANGKAT1L1) + SUM(PANGKAT1P2) +  SUM(PANGKAT1L2) + SUM(PANGKAT1P3) +  SUM(PANGKAT1L3) + SUM(PANGKAT1P4) +  SUM(PANGKAT1L4)    
			   WHEN  A.NAMA = 'ESELON II' THEN SUM(PANGKAT2P1) + SUM(PANGKAT2L1) + SUM(PANGKAT2P2) + SUM(PANGKAT2L2) + SUM(PANGKAT2P3) + SUM(PANGKAT2L3) + SUM(PANGKAT2P4) + SUM(PANGKAT2L4)    
			   WHEN  A.NAMA = 'ESELON III' THEN SUM(PANGKAT3P1) + SUM(PANGKAT3L1) + SUM(PANGKAT3P2) + SUM(PANGKAT3L2) + SUM(PANGKAT3P3) + SUM(PANGKAT3L3) + SUM(PANGKAT3P4) + SUM(PANGKAT3L4)
			   WHEN  A.NAMA = 'ESELON IV' THEN SUM(PANGKAT4P1) + SUM(PANGKAT4L1) + SUM(PANGKAT4P2)  +   SUM(PANGKAT4L2) + SUM(PANGKAT4P3)  +   SUM(PANGKAT4L3) + SUM(PANGKAT4P4)  +   SUM(PANGKAT4L4)
			   WHEN  A.NAMA = 'ESELON V' THEN SUM(PANGKAT5P1) + SUM(PANGKAT5L1) + SUM(PANGKAT5P2)  +   SUM(PANGKAT5L2) + SUM(PANGKAT5P3)  +   SUM(PANGKAT5L3) + SUM(PANGKAT5P4)  +   SUM(PANGKAT5L4)             
			   END TOTAL         
								FROM
						(
						SELECT A.NUM, A.NAMA,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '1%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L1,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P1,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '2%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L2,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P2,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '3%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L3,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P3,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '4%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L4,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON I' AND B.ESELON_ID LIKE '1%' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P4,                            
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L1,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P1,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L2,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P2,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L3,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P3,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L4,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON II' AND B.ESELON_ID LIKE '2%' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P4,                            
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L1,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P1,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L2,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P2,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L3,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P3,
									CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L4,
									CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'ESELON III' AND B.ESELON_ID LIKE '3%' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P4,                            
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '1%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L1,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '1%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P1, 
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '2%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L2,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '2%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P2,
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '3%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L3,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '3%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P3,
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '4%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L4,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON IV' AND B.ESELON_ID LIKE '4%' AND B.PANGKAT_ID LIKE '4%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P4,                            
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '1%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L1,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '1%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P1,
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '2%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L2,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '2%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P2,
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '3%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L3,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '3%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P3,
									CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '4%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT5L4,
									CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'ESELON V' AND B.ESELON_ID LIKE '5%' AND B.PANGKAT_ID LIKE '4%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT5P4
						FROM (
							  SELECT 1 NUM, 'ESELON I' NAMA FROM DUAL 
							  UNION ALL
							  SELECT 2 NUM, 'ESELON II' NAMA FROM DUAL
							  UNION ALL
							  SELECT 3 NUM, 'ESELON III' NAMA FROM DUAL
							  UNION ALL
							  SELECT 4 NUM, 'ESELON IV' NAMA FROM DUAL 
							  UNION ALL
							  SELECT 5 NUM, 'ESELON V' NAMA FROM DUAL
							  ) A 
						LEFT JOIN (
						SELECT   B.ESELON_ID, C.PANGKAT_ID, A.JENIS_KELAMIN,  COUNT (A.PEGAWAI_ID) JUMLAH
									FROM pegawai A 
									INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
									INNER JOIN PANGKAT_TERAKHIR C ON B.PEGAWAI_ID = C.PEGAWAI_ID  
									WHERE STATUS_PEGAWAI IN (1, 2)  ".$statement."
								GROUP BY B.ESELON_ID, C.PANGKAT_ID, A.JENIS_KELAMIN ) B ON 1=1
						 GROUP BY A.NAMA, B.ESELON_ID, B.PANGKAT_ID, JENIS_KELAMIN, A.NUM 
						 ) A GROUP BY  A.NAMA, A.NUM
		UNION ALL
			 SELECT 'Staf' KELOMPOK, 'Staf' AS NAMA, 6 NUM,  
									 SUM(LELAKI1) AS GOL1_L, SUM(PEREMPUAN1) AS GOL1_P,
									 SUM(LELAKI2) AS GOL2_L, SUM(PEREMPUAN2) AS GOL2_P,
									 SUM(LELAKI3) AS GOL3_L, SUM(PEREMPUAN3) AS GOL3_P,
									 SUM(LELAKI4) AS GOL4_L, SUM(PEREMPUAN4) AS GOL4_P,
								SUM(LELAKI1)+ SUM(PEREMPUAN1)+SUM(LELAKI2)+SUM(PEREMPUAN2)+SUM(LELAKI3)+SUM(PEREMPUAN3)+SUM(LELAKI4)+SUM(PEREMPUAN4) TOTAL
						FROM
						(
						SELECT 
									  CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '1%'  THEN SUM(JUMLAH) ELSE 0 END AS LELAKI1,
									  CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN1,
									  CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI2,
									  CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN2,
									  CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI3,
									  CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN3,
									  CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI4,
									  CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN4,
							SUM(JUMLAH) JUMLAH   
						FROM(
						SELECT   JENIS_KELAMIN,  C.PANGKAT_ID, COUNT(A.PEGAWAI_ID) JUMLAH
									FROM pegawai A  INNER JOIN PANGKAT_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
								   WHERE STATUS_PEGAWAI IN (1, 2)  ".$statement." AND TIPE_PEGAWAI_ID = '12'
								GROUP BY  JENIS_KELAMIN, C.PANGKAT_ID) A
						 GROUP BY JENIS_KELAMIN, PANGKAT_ID
						 )  A                 
		UNION ALL
		SELECT  'Fungsional' KELOMPOK, A.NAMA AS NAMA, 7 NUM, 
									 SUM(LELAKI1) AS GOL1_L, SUM(PEREMPUAN1) AS GOL1_P,
									 SUM(LELAKI2) AS GOL2_L, SUM(PEREMPUAN2) AS GOL2_P,
									 SUM(LELAKI3) AS GOL3_L, SUM(PEREMPUAN3) AS GOL3_P,
									 SUM(LELAKI4) AS GOL4_L, SUM(PEREMPUAN4) AS GOL4_P,
									 SUM(JUMLAH)  AS TOTAL                          
						FROM
						(
						SELECT B.PANGKAT_ID, A.JABATAN_FUNGSIONAL_ID, A.NAMA,
									  CASE WHEN JENIS_KELAMIN = 'L' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI1,
									  CASE WHEN JENIS_KELAMIN = 'P' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN1,
									  CASE WHEN JENIS_KELAMIN = 'L' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI2,
									  CASE WHEN JENIS_KELAMIN = 'P' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN2,
									  CASE WHEN JENIS_KELAMIN = 'L' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI3,
									  CASE WHEN JENIS_KELAMIN = 'P' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN3,
									  CASE WHEN JENIS_KELAMIN = 'L' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI4,
									  CASE WHEN JENIS_KELAMIN = 'P' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS PEREMPUAN4,
									  SUM(JUMLAH) JUMLAH   
						FROM JABATAN_FUNGSIONAL A LEFT JOIN (
						SELECT   B.JABATAN_FUNGSIONAL_ID, C.PANGKAT_ID, JENIS_KELAMIN,  COUNT (A.PEGAWAI_ID) JUMLAH
									FROM pegawai A 
									INNER JOIN JABATAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
									INNER JOIN PANGKAT_TERAKHIR C ON B.PEGAWAI_ID = C.PEGAWAI_ID
															   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
								GROUP BY JABATAN_FUNGSIONAL_ID, C.PANGKAT_ID,  JENIS_KELAMIN) B ON A.JABATAN_FUNGSIONAL_ID = B.JABATAN_FUNGSIONAL_ID
								WHERE LENGTH(A.JABATAN_FUNGSIONAL_ID) = 4
						 GROUP BY A.JABATAN_FUNGSIONAL_ID, B.PANGKAT_ID, A.NAMA, JENIS_KELAMIN 
						 ) A GROUP BY  A.JABATAN_FUNGSIONAL_ID,  A.PANGKAT_ID, A.NAMA 
		ORDER BY NUM, NAMA
	";
	
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}
	function getCountByJabatanGolonganStruktural($limit=-1,$from=-1, $statement='')
	{
		$str = "		
					SELECT COUNT(*)+3 	ROWCOUNT
					FROM(
					SELECT 'STRUKTURAL' GROUPING_NAMA, NAMA, LAKI25, PEREMPUAN25, LAKI2535, PEREMPUAN2535, LAKI3645, PEREMPUAN3645, LAKI4655, PEREMPUAN4655,
						   LAKI25+PEREMPUAN25+LAKI2535+PEREMPUAN2535+LAKI3645+PEREMPUAN3645+LAKI4655+PEREMPUAN4655 TOTAL
					FROM (
					SELECT 'ESELON I' NAMA, HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'L', '1') LAKI25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'P', '1') PEREMPUAN25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'L', '2') LAKI2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'P', '2') PEREMPUAN2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'L', '3') LAKI3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'P', '3') PEREMPUAN3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'L', '4') LAKI4655,
						   HITUNG_JABATAN_GOL_STRUKTURAL('1', '".$statement."', 'P', '4') PEREMPUAN4655
					 FROM DUAL
					UNION ALL
					SELECT 'ESELON II' NAMA, HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'L', '1') LAKI25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'P', '1') PEREMPUAN25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'L', '2') LAKI2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'P', '2') PEREMPUAN2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'L', '3') LAKI3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'P', '3') PEREMPUAN3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'L', '4') LAKI4655,
						   HITUNG_JABATAN_GOL_STRUKTURAL('2', '".$statement."', 'P', '4') PEREMPUAN4655
					 FROM DUAL
					UNION ALL 
					SELECT 'ESELON III' NAMA, HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'L', '1') LAKI25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'P', '1') PEREMPUAN25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'L', '2') LAKI2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'P', '2') PEREMPUAN2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'L', '3') LAKI3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'P', '3') PEREMPUAN3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'L', '4') LAKI4655,
						   HITUNG_JABATAN_GOL_STRUKTURAL('3', '".$statement."', 'P', '4') PEREMPUAN4655
					 FROM DUAL
					UNION ALL 
					SELECT 'ESELON IV' NAMA, HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'L', '1') LAKI25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'P', '1') PEREMPUAN25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'L', '2') LAKI2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'P', '2') PEREMPUAN2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'L', '3') LAKI3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'P', '3') PEREMPUAN3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'L', '4') LAKI4655,
						   HITUNG_JABATAN_GOL_STRUKTURAL('4', '".$statement."', 'P', '4') PEREMPUAN4655
					 FROM DUAL
					UNION ALL 
					SELECT 'ESELON V' NAMA, HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'L', '1') LAKI25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'P', '1') PEREMPUAN25,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'L', '2') LAKI2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'P', '2') PEREMPUAN2535,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'L', '3') LAKI3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'P', '3') PEREMPUAN3645,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'L', '4') LAKI4655,
						   HITUNG_JABATAN_GOL_STRUKTURAL('5', '".$statement."', 'P', '4') PEREMPUAN4655
					 FROM DUAL) JABATAN_GOL_STRUKTURAL
					 UNION ALL
					SELECT 'FUNGSIONAL' GROUPING_NAMA, NAMA, LAKI25, PEREMPUAN25, LAKI2535, PEREMPUAN2535, LAKI3645, PEREMPUAN3645, LAKI4655, PEREMPUAN4655,
						   LAKI25+PEREMPUAN25+LAKI2535+PEREMPUAN2535+LAKI3645+PEREMPUAN3645+LAKI4655+PEREMPUAN4655 TOTAL
					FROM
					(
					SELECT JABATAN_FUNGSIONAL_ID, NAMA ,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', '1') LAKI25,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', '1') PEREMPUAN25,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', '2') LAKI2535,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', '2') PEREMPUAN2535,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', '3') LAKI3645,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', '3') PEREMPUAN3645,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'L', '4') LAKI4655,
						HITUNG_JABATAN_GOL_FUNGSIONAL(JABATAN_FUNGSIONAL_ID, '".$statement."', 'P', '4') PEREMPUAN4655
					FROM JABATAN_FUNGSIONAL WHERE LENGTH(JABATAN_FUNGSIONAL_ID) = 4
					) JABATAN_GOLONGAN_FUNGSIONAL			
					UNION ALL
					SELECT 'STAFF' GROUPING_NAMA, NAMA, LAKI25, PEREMPUAN25, LAKI2535, PEREMPUAN2535, LAKI3645, PEREMPUAN3645, LAKI4655, PEREMPUAN4655,
									   LAKI25+PEREMPUAN25+LAKI2535+PEREMPUAN2535+LAKI3645+PEREMPUAN3645+LAKI4655+PEREMPUAN4655 TOTAL
					FROM( 
					SELECT 'JUMLAH STAF' NAMA, 
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '1') LAKI25,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '1') PEREMPUAN25,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '2') LAKI2535,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '2') PEREMPUAN2535,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '3') LAKI3645,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '3') PEREMPUAN3645,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'L', '4') LAKI4655,
								   HITUNG_JABATAN_GOL_STAF( '".$statement."', 'P', '4') PEREMPUAN4655
					FROM DUAL  ) JABATAN_GOL_STAF
					)A			                          
				";
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	
		
	function selectBySexPendidikan($limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT A.PENDIDIKAN_ID, A.NAMA, 
					   SUM(LELAKI) LAKI, SUM(WANITA) PEREMPUAN,
					   NVL(SUM(JUMLAH), 0) TOTAL
				FROM
				(
				SELECT A.PENDIDIKAN_ID, A.NAMA ,
					CASE WHEN JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI,
					CASE WHEN JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS WANITA,
					SUM(JUMLAH) JUMLAH   
				FROM PENDIDIKAN A LEFT JOIN (
				SELECT   PENDIDIKAN_ID, JENIS_KELAMIN, COUNT (A.PEGAWAI_ID) JUMLAH
							FROM pegawai A INNER JOIN PENDIDIKAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
						   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
						GROUP BY PENDIDIKAN_ID, JENIS_KELAMIN) B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
				 GROUP BY A.PENDIDIKAN_ID, A.NAMA, JENIS_KELAMIN
				 ) A GROUP BY  A.PENDIDIKAN_ID, A.NAMA ORDER BY A.PENDIDIKAN_ID
	   ";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	
	function getCountByPendidikanJenisKelamin($paramsArray=array(), $statement)
	{
		$str = "
				SELECT COUNT(NAMA) ROWCOUNT     
				 FROM (
				SELECT NAMA, 
					   HITUNG_SATKER_PENDIDIKAN(PENDIDIKAN_ID, '".$statement."', 'L') LAKI, 
					   HITUNG_SATKER_PENDIDIKAN(PENDIDIKAN_ID, '".$statement."', 'P') PEREMPUAN FROM PENDIDIKAN ) A
		 "; 
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByPendidikanUmur($paramsArray=array(), $statement)
	{
		$str = "
				SELECT COUNT(NAMA) ROWCOUNT     
				 FROM (
				SELECT NAMA, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'L', 'A') LAKI25, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'P', 'A') PEREMPUAN25, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'L', 'B') LAKI2535, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'P', 'B') PEREMPUAN2535,
                       HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'L', 'C') LAKI3645, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'P', 'C') PEREMPUAN3645, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'L', 'D') LAKI4655, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'P', 'D') PEREMPUAN4655, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'L', 'E') LAKI56, 
					   HITUNG_PENDIDIKAN_UMUR(PENDIDIKAN_ID, '".$statement."', 'P', 'E') PEREMPUAN56
				FROM PENDIDIKAN ) A
		 "; 
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
	
	function selectBySatkerGolonganCpnsPns($limit=-1,$from=-1, $statement='')
	{
		$str = "
		   SELECT A.SATKER_ID, B.NAMA, 
            SUM(PANGKAT1CPNS) GOL_1CPNS, SUM(PANGKAT2CPNS) GOL_2CPNS, 
            SUM(PANGKAT3CPNS) GOL_3CPNS, SUM(PANGKAT4CPNS) GOL_4CPNS,
            SUM(PANGKAT1PNS) GOL_1PNS, SUM(PANGKAT2PNS) GOL_2PNS, 
            SUM(PANGKAT3PNS) GOL_3PNS, SUM(PANGKAT4PNS) GOL_4PNS,
            SUM(JUMLAH) TOTAL
           FROM
           (
               SELECT A.SATKER_ID, A.SATKER_ID_PARENT,
                       CASE WHEN PANGKAT=1 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1CPNS,
                       CASE WHEN PANGKAT=2 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2CPNS,
                       CASE WHEN PANGKAT=3 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3CPNS,
                       CASE WHEN PANGKAT=4 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4CPNS,
                       CASE WHEN PANGKAT=1 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1PNS,
                       CASE WHEN PANGKAT=2 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2PNS,
                       CASE WHEN PANGKAT=3 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3PNS,
                       CASE WHEN PANGKAT=4 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4PNS,
                       SUM(JUMLAH) JUMLAH
                FROM 
                SATKER A LEFT JOIN
                (
                    SELECT SATKER_ID, PANGKAT, COUNT(A.PEGAWAI_ID) JUMLAH, STATUS_PEGAWAI
                    FROM PANGKAT_TERAKHIR A 
                    LEFT JOIN 
                    (
                        SELECT 1 PANGKAT FROM DUAL UNION ALL SELECT 2 PANGKAT FROM DUAL UNION ALL SELECT 3 PANGKAT FROM DUAL UNION ALL SELECT 4 PANGKAT FROM DUAL
                    ) ON PANGKAT_ID LIKE PANGKAT || '%', PEGAWAI X
                    WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND STATUS_PEGAWAI IN (1, 2) 
                    GROUP BY SATKER_ID, PANGKAT, STATUS_PEGAWAI
                    ORDER BY SATKER_ID
                ) B ON B.SATKER_ID LIKE A.SATKER_ID || '%'
               WHERE 1 = 1 ".$statement."
               GROUP BY A.SATKER_ID, B.PANGKAT, STATUS_PEGAWAI, SATKER_ID_PARENT
               ORDER BY A.SATKER_ID
           ) A INNER JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID ";

		$str .= " GROUP BY A.SATKER_ID, B.NAMA ORDER BY A.SATKER_ID";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	//AND TO_DATE(TMT_PANGKAT, 'DD-MM-YYYY') < TO_DATE('31122015', 'DD-MM-YYYY')
	function selectBySatkerGolonganCpnsPnsPerSkpd($limit=-1,$from=-1, $satker_id='')
	{
		$str = "
		   SELECT A.SATKER_ID, B.NAMA, 
            SUM(PANGKAT1CPNS) GOL_1CPNS, SUM(PANGKAT2CPNS) GOL_2CPNS, 
            SUM(PANGKAT3CPNS) GOL_3CPNS, SUM(PANGKAT4CPNS) GOL_4CPNS,
            SUM(PANGKAT1PNS) GOL_1PNS, SUM(PANGKAT2PNS) GOL_2PNS, 
            SUM(PANGKAT3PNS) GOL_3PNS, SUM(PANGKAT4PNS) GOL_4PNS,
            SUM(JUMLAH) TOTAL
           FROM
           (
               SELECT A.SATKER_ID, A.SATKER_ID_PARENT,
                       CASE WHEN PANGKAT=1 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1CPNS,
                       CASE WHEN PANGKAT=2 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2CPNS,
                       CASE WHEN PANGKAT=3 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3CPNS,
                       CASE WHEN PANGKAT=4 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4CPNS,
                       CASE WHEN PANGKAT=1 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1PNS,
                       CASE WHEN PANGKAT=2 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2PNS,
                       CASE WHEN PANGKAT=3 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3PNS,
                       CASE WHEN PANGKAT=4 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4PNS,
                       SUM(JUMLAH) JUMLAH
                FROM 
                SATKER A LEFT JOIN
                (
                    SELECT SATKER_ID, PANGKAT, COUNT(A.PEGAWAI_ID) JUMLAH, STATUS_PEGAWAI
                    FROM PANGKAT_TERAKHIR A 
                    LEFT JOIN 
                    (
                        SELECT 1 PANGKAT FROM DUAL UNION ALL SELECT 2 PANGKAT FROM DUAL UNION ALL SELECT 3 PANGKAT FROM DUAL UNION ALL SELECT 4 PANGKAT FROM DUAL
                    ) ON PANGKAT_ID LIKE PANGKAT || '%', PEGAWAI X
                    WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND STATUS_PEGAWAI IN (1, 2) AND SATKER_ID = '".$satker_id."' 
                    GROUP BY SATKER_ID, PANGKAT, STATUS_PEGAWAI
                    ORDER BY SATKER_ID
                ) B ON B.SATKER_ID LIKE A.SATKER_ID || '%'
               WHERE 1 = 1 AND A.SATKER_ID = '".$satker_id."'
               GROUP BY A.SATKER_ID, B.PANGKAT, STATUS_PEGAWAI, SATKER_ID_PARENT
               ORDER BY A.SATKER_ID
           ) A INNER JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID
           GROUP BY A.SATKER_ID, B.NAMA
           UNION ALL
           SELECT A.SATKER_ID, B.NAMA, 
            SUM(PANGKAT1CPNS) GOL_1CPNS, SUM(PANGKAT2CPNS) GOL_2CPNS, 
            SUM(PANGKAT3CPNS) GOL_3CPNS, SUM(PANGKAT4CPNS) GOL_4CPNS,
            SUM(PANGKAT1PNS) GOL_1PNS, SUM(PANGKAT2PNS) GOL_2PNS, 
            SUM(PANGKAT3PNS) GOL_3PNS, SUM(PANGKAT4PNS) GOL_4PNS,
            SUM(JUMLAH) TOTAL
           FROM
           (
               SELECT A.SATKER_ID, A.SATKER_ID_PARENT,
                       CASE WHEN PANGKAT=1 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1CPNS,
                       CASE WHEN PANGKAT=2 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2CPNS,
                       CASE WHEN PANGKAT=3 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3CPNS,
                       CASE WHEN PANGKAT=4 AND STATUS_PEGAWAI = '1' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4CPNS,
                       CASE WHEN PANGKAT=1 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1PNS,
                       CASE WHEN PANGKAT=2 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2PNS,
                       CASE WHEN PANGKAT=3 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3PNS,
                       CASE WHEN PANGKAT=4 AND STATUS_PEGAWAI = '2' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4PNS,
                       SUM(JUMLAH) JUMLAH
                FROM 
                SATKER A LEFT JOIN
                (
                    SELECT SATKER_ID, PANGKAT, COUNT(A.PEGAWAI_ID) JUMLAH, STATUS_PEGAWAI
                    FROM PANGKAT_TERAKHIR A 
                    LEFT JOIN 
                    (
                        SELECT 1 PANGKAT FROM DUAL UNION ALL SELECT 2 PANGKAT FROM DUAL UNION ALL SELECT 3 PANGKAT FROM DUAL UNION ALL SELECT 4 PANGKAT FROM DUAL
                    ) ON PANGKAT_ID LIKE PANGKAT || '%', PEGAWAI X
                    WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND STATUS_PEGAWAI IN (1, 2) 
                    GROUP BY SATKER_ID, PANGKAT, STATUS_PEGAWAI
                    ORDER BY SATKER_ID
                ) B ON B.SATKER_ID LIKE A.SATKER_ID || '%'
               WHERE 1 = 1 AND A.SATKER_ID_PARENT = '".$satker_id."'
               GROUP BY A.SATKER_ID, B.PANGKAT, STATUS_PEGAWAI, SATKER_ID_PARENT
               ORDER BY A.SATKER_ID
           ) A INNER JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID
           GROUP BY A.SATKER_ID, B.NAMA ";
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
		   
	function selectBySatkerGolongan($limit=-1,$from=-1, $statement='')
	{
		$str = "
					SELECT A.SATKER_ID, B.NAMA, 
						   SUM(PANGKAT1L) GOL_1L, SUM(PANGKAT1P) GOL_1P, 
						   SUM(PANGKAT2L) GOL_2L, SUM(PANGKAT2P) GOL_2P, 
						   SUM(PANGKAT3L) GOL_3L, SUM(PANGKAT3P) GOL_3P, 
						   SUM(PANGKAT4L) GOL_4L, SUM(PANGKAT4P) GOL_4P,
						   SUM(JUMLAH) TOTAL
					FROM
					(
					SELECT A.SATKER_ID,
							CASE WHEN PANGKAT=1 AND JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L,
							CASE WHEN PANGKAT=1 AND JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P,
							CASE WHEN PANGKAT=2 AND JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L,
							CASE WHEN PANGKAT=2 AND JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P,
							CASE WHEN PANGKAT=3 AND JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L,
							CASE WHEN PANGKAT=3 AND JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P,
							CASE WHEN PANGKAT=4 AND JENIS_KELAMIN = 'L' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L,
							CASE WHEN PANGKAT=4 AND JENIS_KELAMIN = 'P' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4P,
							SUM(JUMLAH) JUMLAH
					 FROM 
					SATKER A LEFT JOIN
					(
					SELECT SATKER_ID, PANGKAT, JENIS_KELAMIN, COUNT(A.PEGAWAI_ID) JUMLAH
					  FROM PANGKAT_TERAKHIR A LEFT JOIN 
					  (
						SELECT 1 PANGKAT FROM DUAL
						UNION ALL
						SELECT 2 PANGKAT FROM DUAL
						UNION ALL
						SELECT 3 PANGKAT FROM DUAL
						UNION ALL
						SELECT 4 PANGKAT FROM DUAL
					  ) ON PANGKAT_ID LIKE PANGKAT || '%',   
					  PEGAWAI X
					 WHERE X.PEGAWAI_ID = A.PEGAWAI_ID AND  
						   STATUS_PEGAWAI IN (1, 2) 
					GROUP BY SATKER_ID, PANGKAT, JENIS_KELAMIN
					) B ON B.SATKER_ID LIKE  A.SATKER_ID || '%'
					WHERE 1 = 1 ".$statement." 
					GROUP BY A.SATKER_ID, B.PANGKAT, JENIS_KELAMIN
					ORDER BY A.SATKER_ID
					) A INNER JOIN SATKER B ON A.SATKER_ID = B.SATKER_ID
				  ";

		$str .= " GROUP BY A.SATKER_ID, B.NAMA ORDER BY A.SATKER_ID ";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	function selectByGolonganJenisKelamin($limit=-1,$from=-1, $statement='')
	{
		$str = "			
				SELECT A.NAMA GOLONGAN, 
				   CASE 
					   WHEN  A.NAMA = 'I' THEN SUM(PANGKAT1L) 
					   WHEN  A.NAMA = 'II' THEN SUM(PANGKAT2L) 
					   WHEN  A.NAMA = 'III' THEN SUM(PANGKAT3L)
					   WHEN  A.NAMA = 'IV' THEN SUM(PANGKAT4L)               
					   END LAKI1,        
					   CASE 
					   WHEN  A.NAMA = 'I' THEN SUM(PANGKAT1P) 
					   WHEN  A.NAMA = 'II' THEN SUM(PANGKAT2P) 
					   WHEN  A.NAMA = 'III' THEN SUM(PANGKAT3P)
					   WHEN  A.NAMA = 'IV' THEN SUM(PANGKAT4P)               
					   END PEREMPUAN1,
					   CASE 
					   WHEN  A.NAMA = 'I' THEN SUM(PANGKAT1P) +  SUM(PANGKAT1L) 
					   WHEN  A.NAMA = 'II' THEN SUM(PANGKAT2P) + SUM(PANGKAT2L) 
					   WHEN  A.NAMA = 'III' THEN SUM(PANGKAT3P) + SUM(PANGKAT3L)
					   WHEN  A.NAMA = 'IV' THEN SUM(PANGKAT4P)  +   SUM(PANGKAT4L)             
					   END TOTAL         
										FROM
								(
								SELECT A.NUM, A.NAMA,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'I' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1L,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'I' AND B.PANGKAT_ID LIKE '1%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT1P,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'II' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2L,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'II' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT2P,
											CASE WHEN JENIS_KELAMIN = 'L' AND A.NAMA = 'III' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3L,
											CASE WHEN JENIS_KELAMIN = 'P' AND A.NAMA = 'III' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT3P,
											CASE WHEN JENIS_KELAMIN = 'L' AND  A.NAMA = 'IV' AND B.PANGKAT_ID LIKE '4%'  THEN SUM(JUMLAH) ELSE 0 END AS PANGKAT4L,
											CASE WHEN JENIS_KELAMIN = 'P' AND  A.NAMA = 'IV' AND B.PANGKAT_ID LIKE '4%' THEN  SUM(JUMLAH) ELSE 0 END AS PANGKAT4P 
								FROM (
									  SELECT 1 NUM, 'I' NAMA FROM DUAL 
									  UNION ALL
									  SELECT 2 NUM, 'II' NAMA FROM DUAL
									  UNION ALL
									  SELECT 3 NUM, 'III' NAMA FROM DUAL
									  UNION ALL
									  SELECT 4 NUM, 'IV' NAMA FROM DUAL 
									  ) A 
								LEFT JOIN (
								SELECT   B.PANGKAT_ID, A.JENIS_KELAMIN,  COUNT (A.PEGAWAI_ID) JUMLAH
											FROM pegawai A INNER JOIN PANGKAT_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID 
											WHERE STATUS_PEGAWAI IN (1, 2) ".$statement." 
										GROUP BY B.PANGKAT_ID, A.JENIS_KELAMIN ) B ON 1=1
								 GROUP BY A.NAMA, B.PANGKAT_ID, JENIS_KELAMIN, A.NUM 
								 ) A GROUP BY  A.NAMA, A.NUM  ORDER BY A.NUM
				";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	function getCountByGolonganJenisKelamin($limit=-1,$from=-1, $statement='')
	{
		$str = "	
				SELECT COUNT(*) ROWCOUNT
				FROM(
				SELECT GOLONGAN, LAKI1, PEREMPUAN1, LAKI1 + PEREMPUAN1 TOTAL FROM(
				SELECT 'I' GOLONGAN, HITUNG_GOLONGAN_JENIS_KELAMIN('1', '".$statement."', 'L') LAKI1, HITUNG_GOLONGAN_JENIS_KELAMIN('1', '".$statement."', 'P') PEREMPUAN1 FROM DUAL
				UNION ALL
				SELECT 'II' GOLONGAN, HITUNG_GOLONGAN_JENIS_KELAMIN('2', '".$statement."', 'L') LAKI1, HITUNG_GOLONGAN_JENIS_KELAMIN('2', '".$statement."', 'P') PEREMPUAN1 FROM DUAL
				UNION ALL
				SELECT 'III' GOLONGAN, HITUNG_GOLONGAN_JENIS_KELAMIN('3', '".$statement."', 'L') LAKI1, HITUNG_GOLONGAN_JENIS_KELAMIN('3', '".$statement."', 'P') PEREMPUAN1 FROM DUAL
				UNION ALL
				SELECT 'IV' GOLONGAN, HITUNG_GOLONGAN_JENIS_KELAMIN('4', '".$statement."', 'L') LAKI1, HITUNG_GOLONGAN_JENIS_KELAMIN('4', '".$statement."', 'P') PEREMPUAN1 FROM DUAL
				) GOLONGAN_JENIS_KELAMIN
				)A
				";
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
	}
	function selectByPendidikanGol($limit=-1,$from=-1, $statement='')
	{
		$str = "			
				SELECT A.PENDIDIKAN_ID, A.NAMA, 
									   SUM(LELAKI1) LAKI_GOL1, SUM(WANITA1) PEREMPUAN_GOL1,
									   SUM(LELAKI2) LAKI_GOL2, SUM(WANITA2) PEREMPUAN_GOL2,
									   SUM(LELAKI3) LAKI_GOL3, SUM(WANITA3) PEREMPUAN_GOL3,
									   SUM(LELAKI4) LAKI_GOL4, SUM(WANITA4) PEREMPUAN_GOL4,
									   NVL(SUM(JUMLAH), 0) TOTAL
								FROM
								(
								SELECT A.PENDIDIKAN_ID, A.NAMA ,
									CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI1,
									CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS WANITA1,
									CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI2,
									CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS WANITA2,
									CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI3,
									CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS WANITA3,
									CASE WHEN JENIS_KELAMIN = 'L' AND PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS LELAKI4,
									CASE WHEN JENIS_KELAMIN = 'P' AND PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS WANITA4,
									SUM(JUMLAH) JUMLAH   
								FROM PENDIDIKAN A LEFT JOIN (
								SELECT   PENDIDIKAN_ID, JENIS_KELAMIN, PANGKAT_ID, COUNT (A.PEGAWAI_ID) JUMLAH
											FROM pegawai A 
											INNER JOIN PENDIDIKAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
											INNER JOIN PANGKAT_TERAKHIR C ON A.PEGAWAI_ID = C.PEGAWAI_ID
										   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
										GROUP BY PENDIDIKAN_ID, JENIS_KELAMIN, PANGKAT_ID) B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
								 GROUP BY A.PENDIDIKAN_ID, A.NAMA, JENIS_KELAMIN, PANGKAT_ID
								 ) A GROUP BY  A.PENDIDIKAN_ID, A.NAMA ORDER BY A.PENDIDIKAN_ID
    ";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	function selectByPendidikanUmur($limit=-1,$from=-1, $statement='')
	{
		$str = "			
				SELECT A.PENDIDIKAN_ID, NAMA PENDIDIKAN, 
                      SUM(UMUR25L) UMUR25L, SUM(UMUR25P) UMUR25P,
                           SUM(UMUR2535L) UMUR2535L, SUM(UMUR2535P) UMUR2535P,
                           SUM(UMUR3645L) UMUR3645L, SUM(UMUR3645P) UMUR3645P,
                           SUM(UMUR4655L) UMUR4655L, SUM(UMUR4655P) UMUR4655P,
                           SUM(UMUR56L) UMUR56L, SUM(UMUR56P) UMUR56P,
                       NVL(SUM(JUMLAH), 0) TOTAL
                FROM
                (
                SELECT A.PENDIDIKAN_ID, A.NAMA ,
                            CASE WHEN JENIS_KELAMIN = 'L' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L,
                            CASE WHEN JENIS_KELAMIN = 'P' AND UMUR < 25 THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P,
                            CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L,
                            CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P,
                            CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L,
                            CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P,
                            CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L,
                            CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P,
                            CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L,
                            CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P,
                    SUM(JUMLAH) JUMLAH   
                FROM PENDIDIKAN A LEFT JOIN (
                SELECT   PENDIDIKAN_ID, JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR) UMUR, COUNT (A.PEGAWAI_ID) JUMLAH
                            FROM pegawai A INNER JOIN PENDIDIKAN_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
                                                       WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
                        GROUP BY PENDIDIKAN_ID, JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR)) B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
                 GROUP BY A.PENDIDIKAN_ID, A.NAMA, JENIS_KELAMIN, UMUR
                 ) A GROUP BY  A.PENDIDIKAN_ID, A.NAMA  ORDER BY A.PENDIDIKAN_ID
				 
				 ";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByJenisKelaminGolongan($limit=-1,$from=-1, $statement='')
	{
		$str = "			
					SELECT A.NAMA UMUR, 
						   CASE 
						   WHEN  A.NAMA = '<25'  THEN SUM(UMUR25L1) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535L1) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645L1)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655L1)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56L1)               
						   END LAKI1,        
						   CASE 
						   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P1) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P1) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P1)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P1)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P1)               
						   END PEREMPUAN1,      
						   CASE 
						   WHEN  A.NAMA = '<25'  THEN SUM(UMUR25L2) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535L2) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645L2)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655L2)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56L2)               
						   END LAKI2,        
						   CASE 
						   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P2) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P2) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P2)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P2)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P2)               
						   END PEREMPUAN2,      
						   CASE 
						   WHEN  A.NAMA = '<25'  THEN SUM(UMUR25L3) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535L3) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645L3)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655L3)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56L3)               
						   END LAKI3,        
						   CASE 
						   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P3) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P3) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P3)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P3)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P3)               
						   END PEREMPUAN3,      
						   CASE 
						   WHEN  A.NAMA = '<25'  THEN SUM(UMUR25L4) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535L4) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645L4)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655L4)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56L4)               
						   END LAKI4,        
						   CASE 
						   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P4) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P4) 
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P4)
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P4)
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P4)               
						   END PEREMPUAN4,      
						   CASE 
						   WHEN  A.NAMA = '<25' THEN SUM(UMUR25P1) + SUM(UMUR25L1) + SUM(UMUR25P2) + SUM(UMUR25L2) + SUM(UMUR25P3) + SUM(UMUR25L3)  + SUM(UMUR25P4) + SUM(UMUR25L4) 
						   WHEN  A.NAMA = '25-35' THEN SUM(UMUR2535P1) + SUM(UMUR2535L1) + SUM(UMUR2535P2) + SUM(UMUR2535L2) + SUM(UMUR2535P3) + SUM(UMUR2535L3)  + SUM(UMUR2535P4) + SUM(UMUR2535L4)  
						   WHEN  A.NAMA = '36-45' THEN SUM(UMUR3645P1) + SUM(UMUR3645L1) + SUM(UMUR3645P2) + SUM(UMUR3645L2) + SUM(UMUR3645P3) + SUM(UMUR3645L3) + SUM(UMUR3645P4) + SUM(UMUR3645L4) 
						   WHEN  A.NAMA = '46-55' THEN SUM(UMUR4655P1) + SUM(UMUR4655L1) + SUM(UMUR4655P2) + SUM(UMUR4655L2) + SUM(UMUR4655P3) + SUM(UMUR4655L3) + SUM(UMUR4655P4) + SUM(UMUR4655L4) 
						   WHEN  A.NAMA = '>55' THEN SUM(UMUR56P1) + SUM(UMUR56L1) + SUM(UMUR56P2) + SUM(UMUR56L2) + SUM(UMUR56P3) + SUM(UMUR56L3) + SUM(UMUR56P4) + SUM(UMUR56L4)             
						   END TOTAL 
							FROM
									(
									SELECT A.NUM, A.NAMA, 
										CASE WHEN JENIS_KELAMIN = 'L' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L1,
										CASE WHEN JENIS_KELAMIN = 'P' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P1,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L1,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P1,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L1,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P1,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L1,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P1,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L1,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '1%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P1,
										CASE WHEN JENIS_KELAMIN = 'L' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L2,
										CASE WHEN JENIS_KELAMIN = 'P' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P2,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L2,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P2,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L2,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P2,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L2,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P2,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L2,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '2%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P2,
										CASE WHEN JENIS_KELAMIN = 'L' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L3,
										CASE WHEN JENIS_KELAMIN = 'P' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P3,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L3,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P3,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L3,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P3,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L3,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P3,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L3,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '3%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P3,
										CASE WHEN JENIS_KELAMIN = 'L' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25L4,
										CASE WHEN JENIS_KELAMIN = 'P' AND  UMUR < 25 AND A.NAMA = '<25' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR25P4,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535L4,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 25 AND 35 AND A.NAMA = '25-35' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR2535P4,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645L4,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 36 AND 45 AND A.NAMA = '36-45' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR3645P4,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655L4,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR BETWEEN 46 AND 55 AND A.NAMA = '46-55' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR4655P4,
										CASE WHEN JENIS_KELAMIN = 'L' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56L4,
										CASE WHEN JENIS_KELAMIN = 'P' AND UMUR > 55 AND A.NAMA = '>55' AND B.PANGKAT_ID LIKE '4%' THEN SUM(JUMLAH) ELSE 0 END AS UMUR56P4
									FROM (
										  SELECT 1 NUM, '<25' NAMA FROM DUAL
										  UNION ALL
										  SELECT 2 NUM, '25-35' NAMA FROM DUAL
										  UNION ALL
										  SELECT 3 NUM, '36-45' NAMA FROM DUAL
										  UNION ALL
										  SELECT 4 NUM, '46-55' NAMA FROM DUAL                      
										  UNION ALL
										  SELECT 5 NUM, '>55' NAMA FROM DUAL                      
										  ) A LEFT JOIN (
									SELECT   JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR) UMUR, PANGKAT_ID, COUNT (A.PEGAWAI_ID) JUMLAH
												FROM pegawai A INNER JOIN PANGKAT_TERAKHIR B ON A.PEGAWAI_ID = B.PEGAWAI_ID
											   WHERE STATUS_PEGAWAI IN (1, 2) ".$statement."
											GROUP BY JENIS_KELAMIN, AMBIL_UMUR(TANGGAL_LAHIR), PANGKAT_ID) B ON 1 = 1
									 GROUP BY A.NAMA, JENIS_KELAMIN, UMUR, A.NUM, PANGKAT_ID
									 ) A GROUP BY A.NAMA, A.NUM  ORDER BY NUM
				 ";
		
		$this->query = $str;	
				
		return $this->selectLimit($str,$limit,$from); 
	}
	    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT PEGAWAI_ID, PROPINSI_ID, KABUPATEN_ID, 
				   KECAMATAN_ID, KELURAHAN_ID, SATKER_ID, 
				   KEDUDUKAN_ID, JENIS_PEGAWAI_ID, BANK_ID, 
				   NIP_LAMA, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, NAMA, 
				   GELAR_DEPAN, GELAR_BELAKANG, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, STATUS_KAWIN, SUKU_BANGSA, GOLONGAN_DARAH,
				   EMAIL, ALAMAT, RT, RW, TELEPON, KODEPOS, STATUS_PEGAWAI, KARTU_PEGAWAI, ASKES, TASPEN,
				   NPWP, NIK, FOTO, NO_REKENING, TANGGAL_MATI, TANGGAL_PENSIUN, TANGGAL_TERUSAN, TANGGAL_UPDATE, TIPE_PEGAWAI
				FROM pegawai WHERE PEGAWAI_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY KEDUDUKAN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsCetakLaporanModel1($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT 
				NIP_LAMA, GELAR_DEPAN ||  DECODE(GELAR_DEPAN, NULL, '', ' ') || A.NAMA || DECODE(GELAR_BELAKANG, NULL, '', ' ') || GELAR_BELAKANG NAMA,
				NMGOLRUANG || '(' || GOL_RUANG || ')' PANGKAT, ESELON, JABATAN, PENYELENGGARA
				FROM pegawai A, DIKLAT_STRUKTURAL_TERAKHIR B, PANGKAT_TERAKHIR C, JABATAN_TERAKHIR D 
				WHERE A.PEGAWAI_ID = B.PEGAWAI_ID AND A.PEGAWAI_ID = C.PEGAWAI_ID AND A.PEGAWAI_ID = D.PEGAWAI_ID
				AND  A.STATUS_PEGAWAI IN (1,2)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ";
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByCetakLaporanModel1($paramsArray=array(), $stat = '')
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) ROWCOUNT
				FROM pegawai A, DIKLAT_STRUKTURAL_TERAKHIR B, PANGKAT_TERAKHIR C, JABATAN_TERAKHIR D 
				WHERE A.PEGAWAI_ID = B.PEGAWAI_ID AND A.PEGAWAI_ID = C.PEGAWAI_ID AND A.PEGAWAI_ID = D.PEGAWAI_ID
				AND  A.STATUS_PEGAWAI IN (1,2)
				"; 		
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function selectByParamsCetakLaporanModel2($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT 
				A.NAMA, TANGGAL_LAHIR, NIP_LAMA, '(' || GOL_RUANG || ')' PANGKAT, TMT_PANGKAT, JABATAN, TMT_JABATAN,
				MASA_KERJA_TAHUN, MASA_KERJA_BULAN, D.NAMA DIKLAT, NAMA_SEKOLAH || ', ' ||  TANGGAL_STTB PENDIDIKAN, AMBIL_UMUR(TANGGAL_LAHIR) USIA,
				SUBSTR(GOL_RUANG, 1, INSTR(GOL_RUANG, '/') - 1) GOL, SUBSTR(GOL_RUANG, -1) RUANG, ESELON, ESELON_ID
				FROM pegawai A, JABATAN_TERAKHIR B, PANGKAT_TERAKHIR C, DIKLAT_STRUKTURAL_TERAKHIR D, PENDIDIKAN_TERAKHIR E
				WHERE A.PEGAWAI_ID = B.PEGAWAI_ID AND A.PEGAWAI_ID = C.PEGAWAI_ID AND A.PEGAWAI_ID = D.PEGAWAI_ID AND A.PEGAWAI_ID = E.PEGAWAI_ID
				AND  A.STATUS_PEGAWAI IN (1,2)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
				
		$str .= $statement." ";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }	
	
	function getCountByCetakLaporanModel2($paramsArray=array(), $stat = '')
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) ROWCOUNT
				FROM pegawai A, DIKLAT_STRUKTURAL_TERAKHIR B, PANGKAT_TERAKHIR C, JABATAN_TERAKHIR D 
				WHERE A.PEGAWAI_ID = B.PEGAWAI_ID AND A.PEGAWAI_ID = C.PEGAWAI_ID AND A.PEGAWAI_ID = D.PEGAWAI_ID
				AND  A.STATUS_PEGAWAI IN (1,2)
				"; 		
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function selectByParamsCetakLaporanModel3($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT  C.ESELON_ID, A.PEGAWAI_ID, AMBIL_FORMAT_NIP_BARU(NIP_BARU) NIP_BARU, GELAR_DEPAN ||  DECODE(GELAR_DEPAN, NULL, '', ' ') || A.NAMA || DECODE(GELAR_BELAKANG, NULL, '', ' ') || GELAR_BELAKANG NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL,
                        B.GOL_RUANG,
						TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.JABATAN,
                        A.ALAMAT,
                        E.NAMA SATKER,
                        PENDIDIKAN,
                        AMBIL_DIKLAT_ST(A.PEGAWAI_ID) DATA_DIKLAT
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
                     A.STATUS_PEGAWAI IN (1,2)
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByCetakLaporanModel3($paramsArray=array(), $stat = '')
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) ROWCOUNT
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
                     A.STATUS_PEGAWAI IN (1,2)
				"; 		
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function selectByParamsGandaNIP($limit=-1,$from=-1, $stat='', $stat1='')
	{
		$str = "
				SELECT *
				FROM(
				(SELECT 'BARU' DATA, NAMA, NIP_LAMA,  AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_BARU, COUNT(NIP_BARU) JUMLAH FROM pegawai GROUP BY NIP_BARU) A
				WHERE JUMLAH >= 2) B ON A.NIP_BARU = B.NIP_BARU WHERE 1=1 ".$stat.")
				UNION ALL
				(SELECT 'LAMA' DATA, NAMA, A.NIP_LAMA, AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_LAMA, COUNT(NIP_LAMA) JUMLAH FROM pegawai GROUP BY NIP_LAMA) A
				WHERE JUMLAH >= 2) B ON A.NIP_LAMA = B.NIP_LAMA WHERE 1=1 ".$stat.")
				) WHERE 1=1 ".$stat1." ORDER BY DATA DESC, NIP_BARU ASC, NIP_LAMA ASC
 				"; 

		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsGandaRiwayat($limit=-1,$from=-1, $stat='', $stat1='')
	{
		$str = "
				SELECT *
				FROM(
				(SELECT 'PANGKAT' GROUPING_NAMA, A.NAMA, TO_CHAR(A.PEGAWAI_ID) PEGAWAI_ID, NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT PEGAWAI_ID, COUNT(TMT_PANGKAT) JUMLAH FROM PANGKAT_RIWAYAT GROUP BY TMT_PANGKAT, PEGAWAI_ID) A
				WHERE JUMLAH >= 2) B ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1=1 ".$stat.")
				UNION ALL
				(SELECT 'JABATAN' GROUPING_NAMA, A.NAMA, TO_CHAR(A.PEGAWAI_ID) PEGAWAI_ID, NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT PEGAWAI_ID, COUNT(TMT_JABATAN) JUMLAH FROM jabatan_riwayat GROUP BY TMT_JABATAN, PEGAWAI_ID) A
				WHERE JUMLAH >= 2) B ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1=1 ".$stat.")
				UNION ALL
				(SELECT 'GAJI' GROUPING_NAMA, A.NAMA, TO_CHAR(A.PEGAWAI_ID) PEGAWAI_ID, NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT PEGAWAI_ID, COUNT(TMT_SK) JUMLAH FROM GAJI_RIWAYAT GROUP BY TMT_SK, PEGAWAI_ID) A
				WHERE JUMLAH >= 2) B ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1=1 ".$stat.")
				) WHERE 1=1 ".$stat1." ORDER BY GROUPING_NAMA DESC
 				"; 

		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsGandaNIPBARU($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT NAMA, NIP_LAMA, AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU, TANGGAL_LAHIR, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_BARU, COUNT(NIP_BARU) JUMLAH FROM pegawai GROUP BY NIP_BARU) A
				WHERE JUMLAH >= 2) B ON A.NIP_BARU = B.NIP_BARU 
				WHERE 1=1
 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.NIP_BARU";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsGandaNIPLAMA($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "
				SELECT NAMA, A.NIP_LAMA, AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU, TANGGAL_LAHIR, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_LAMA, COUNT(NIP_LAMA) JUMLAH FROM pegawai GROUP BY NIP_LAMA) A
				WHERE JUMLAH >= 2) B ON A.NIP_LAMA = B.NIP_LAMA 
				WHERE 1=1
 				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY A.NIP_LAMA";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByGandaNIP($stat = '', $stat1 = '')
	{
		$str = "SELECT COUNT(1) ROWCOUNT 
				FROM(
				(SELECT 'BARU' DATA, NAMA, NIP_LAMA, A.NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_BARU, COUNT(NIP_BARU) JUMLAH FROM pegawai GROUP BY NIP_BARU) A
				WHERE JUMLAH >= 2) B ON A.NIP_BARU = B.NIP_BARU WHERE 1=1 ".$stat.")
				UNION ALL
				(SELECT 'LAMA' DATA, NAMA, A.NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_LAMA, COUNT(NIP_LAMA) JUMLAH FROM pegawai GROUP BY NIP_LAMA) A
				WHERE JUMLAH >= 2) B ON A.NIP_LAMA = B.NIP_LAMA WHERE 1=1 ".$stat.")
				) WHERE 1=1 ".$stat1."
				"; 		
		
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByGandaRiwayat($stat = '', $stat1 = '')
	{
		$str = "SELECT COUNT(1) ROWCOUNT 
				FROM(
				(SELECT 'PANGKAT' GROUPING_NAMA, TO_CHAR(A.PEGAWAI_ID) PEGAWAI_ID, NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT PEGAWAI_ID, COUNT(TMT_PANGKAT) JUMLAH FROM PANGKAT_RIWAYAT GROUP BY TMT_PANGKAT, PEGAWAI_ID) A
				WHERE JUMLAH >= 2) B ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1=1 ".$stat.")
				UNION ALL
				(SELECT 'JABATAN' GROUPING_NAMA, TO_CHAR(A.PEGAWAI_ID) PEGAWAI_ID, NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT PEGAWAI_ID, COUNT(TMT_JABATAN) JUMLAH FROM jabatan_riwayat GROUP BY TMT_JABATAN, PEGAWAI_ID) A
				WHERE JUMLAH >= 2) B ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1=1 ".$stat.")
				UNION ALL
				(SELECT 'GAJI' GROUPING_NAMA, TO_CHAR(A.PEGAWAI_ID) PEGAWAI_ID, NIP_LAMA, NIP_BARU, TANGGAL_LAHIR, SATKER_ID, AMBIL_SATKER(SATKER_ID) SATKER FROM pegawai A INNER JOIN (SELECT * FROM (SELECT PEGAWAI_ID, COUNT(TMT_SK) JUMLAH FROM GAJI_RIWAYAT GROUP BY TMT_SK, PEGAWAI_ID) A
				WHERE JUMLAH >= 2) B ON A.PEGAWAI_ID = B.PEGAWAI_ID WHERE 1=1 ".$stat.")				
				) WHERE 1=1 ".$stat1."
				"; 		
		
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	

	function getCountByGandaNIPBARU($paramsArray=array(), $stat = '')
	{
		$str = "SELECT COUNT(PEGAWAI_ID) ROWCOUNT FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_BARU, COUNT(NIP_BARU) JUMLAH FROM pegawai GROUP BY NIP_BARU) A
				WHERE JUMLAH >= 2) B ON A.NIP_BARU = B.NIP_BARU 
				WHERE 1=1
				"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
	
	function getCountByGandaNIPLAMA($paramsArray=array(), $stat = '')
	{
		$str = "SELECT COUNT(PEGAWAI_ID) ROWCOUNT FROM pegawai A INNER JOIN (SELECT * FROM (SELECT NIP_LAMA, COUNT(NIP_LAMA) JUMLAH FROM pegawai GROUP BY NIP_LAMA) A
				WHERE JUMLAH >= 2) B ON A.NIP_LAMA = B.NIP_LAMA WHERE 1=1
				"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $stat.' ';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

	function getCountByUmur($paramsArray=array(), $stat = '')
	{			   
		$str = "
				SELECT COUNT(y.PEGAWAI_ID) USIA
				FROM (
					SELECT A.PEGAWAI_ID,A.TANGGAL_LAHIR, B.USIA    
					FROM pegawai A, (
						SELECT PEGAWAI_ID,
							CASE 
							WHEN (TO_NUMBER(TO_CHAR(SYSDATE, 'MM'))-TO_NUMBER(TO_CHAR(x.TANGGAL_LAHIR, 'MM')))<0 
							THEN 
								TO_NUMBER(TO_CHAR(SYSDATE, 'YYYY'))-TO_NUMBER(TO_CHAR(x.TANGGAL_LAHIR, 'YYYY'))-1
							ELSE TO_NUMBER(TO_CHAR(SYSDATE, 'YYYY'))-TO_NUMBER(TO_CHAR(x.TANGGAL_LAHIR, 'YYYY'))  
							END AS USIA
							FROM pegawai x
						) B
					WHERE STATUS_PEGAWAI IN (1, 2) AND A.PEGAWAI_ID = B.PEGAWAI_ID
					".$stat."
				)y";
				
		//echo $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("USIA"); 
		else 
			return 0; 
    }
	
	function getCountByJenisKelamin($paramsArray=array(), $stat = '')
	{
		$str = "SELECT  COUNT(A.JENIS_KELAMIN) JML
				FROM pegawai A
				WHERE STATUS_PEGAWAI IN (1, 2)
				"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $stat.' GROUP BY JENIS_KELAMIN';
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("JML"); 
		else 
			return 0; 
    }
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","KECAMATAN_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $state='')
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM pegawai WHERE PEGAWAI_ID IS NOT NULL ".$state; 
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
	
	function getCountByPegawaiPenghargaan($paramsArray=array(), $stat='')
	{
		$str = "SELECT COUNT(SATKER_ID) AS ROWCOUNT FROM 
				(
                SELECT  G.LENCANA, A.SATKER_ID, A.PEGAWAI_ID, NIP_LAMA, NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        DECODE(STATUS_PEGAWAI, 1, 'CPNS', 2, 'PNS', 3, 'Pensiun', 4, 'Tewas', 5, 'Wafat', 6, 'Pindah', 7, 'Parpol') STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     AGAMA D,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F,
                     (SELECT PEGAWAI_ID, NAMA LENCANA FROM LENCANA_PEGAWAI B LEFT JOIN LENCANA A ON A.LENCANA_ID = B.LENCANA_ID) G 
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.AGAMA_ID = D.AGAMA_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = G.PEGAWAI_ID AND
                     A.STATUS_PEGAWAI IN (1,2)
                UNION ALL
                SELECT  G.NAMA LENCANA, A.SATKER_ID, A.PEGAWAI_ID, NIP_LAMA, NIP_BARU, (CASE WHEN GELAR_DEPAN IS NULL THEN '' ELSE GELAR_DEPAN || '. ' END) || A.NAMA || (CASE WHEN GELAR_BELAKANG IS NULL THEN '' ELSE  ', ' || GELAR_BELAKANG END) NAMA, 
                        TEMPAT_LAHIR || ', ' || TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TTL, JENIS_KELAMIN, 
                        DECODE(STATUS_PEGAWAI, 1, 'CPNS', 2, 'PNS', 3, 'Pensiun', 4, 'Tewas', 5, 'Wafat', 6, 'Pindah', 7, 'Parpol') STATUS_PEGAWAI,
                        B.GOL_RUANG,
                        TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
                        C.ESELON,
                        C.JABATAN,
                        TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
                        D.NAMA AGAMA,
                        A.TELEPON,
                        A.ALAMAT,
                        E.NAMA SATKER,        
                        '01 ' || TO_CHAR(ADD_MONTHS(TANGGAL_LAHIR, (56 * 12) + 1),  'MON YYYY') TMT_PENSIUN,
                        PENDIDIKAN,
                        LULUS
                FROM pegawai A,  
                     (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM PANGKAT_TERAKHIR) B,
                     (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM JABATAN_TERAKHIR) C,
                     AGAMA D,
                     SATKER E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F,
                     PENGHARGAAN G 
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.AGAMA_ID = D.AGAMA_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = G.PEGAWAI_ID AND
                     A.STATUS_PEGAWAI IN (1,2)         
                 )  WHERE 1 = 1 "; 
		$str .= $stat;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsSatkerGolongan($paramsArray=array(),$statement = '')
	{	
		
		$str = "SELECT COUNT(SATKER_ID) AS ROWCOUNT FROM satker WHERE 1 = 1 "; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountBySex($paramsArray=array(), $statement)
	{
		$str = "
				SELECT COUNT(SATKER_ID) ROWCOUNT     
				 FROM satker A WHERE 1 = 1
		 ".$statement; 
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


    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT  COUNT(A.PEGAWAI_ID) ROWCOUNT
				FROM pegawai A,  
					 (SELECT TMT_PANGKAT, GOL_RUANG, PEGAWAI_ID, PANGKAT_ID FROM pangkat_terakhir) B,
					 (SELECT PEGAWAI_ID, TMT_JABATAN, ESELON, JABATAN, ESELON_ID FROM jabatan_terakhir) C,
                     agama D,
                     satker E,
                     (SELECT PEGAWAI_ID, TAHUN LULUS, PENDIDIKAN FROM PENDIDIKAN_TERAKHIR X) F
                WHERE
                     A.PEGAWAI_ID = B.PEGAWAI_ID(+) AND
                     A.PEGAWAI_ID = C.PEGAWAI_ID(+) AND
                     A.AGAMA_ID = D.AGAMA_ID(+) AND
                     A.SATKER_ID = E.SATKER_ID AND
                     A.PEGAWAI_ID = F.PEGAWAI_ID(+) AND
					 A.STATUS_PEGAWAI IN (1,2)
					 AND NOT EXISTS( SELECT X.PEGAWAI_ID FROM MUTASI_USULAN X WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND X.SATKER_ID_LAMA = E.SATKER_ID AND X.STATUS IS NULL )
					
				".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	

    function getCountByParamsMonitoring2($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM dbsimpeg.rb_data_pegawai A 
				LEFT JOIN dbsimpeg.rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN dbsimpeg.rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
				, dbsimpeg.rb_ref_unker D 
				WHERE A.KODE_UNKER = D.KODE_UNKER
				".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
					
	function getGetIdBySatker($statement="")
	{
		$str = "SELECT SIMPEG_JOMBANG.GET_CHILD(".$statement.") AS ROWCOUNT FROM DUAL";
		//echo $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }	
	
    function getCountByParamsDUK($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM DUK WHERE PEGAWAI_ID IS NOT NULL ".$statement; 
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
	
	function getNamaBySatker($paramsArray=array(), $statement="")
	{
		$str = "SELECT NAMA AS ROWCOUNT FROM satker WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ''; 
    }	

	function getCountByParamsMonitoringHistori($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM pegawai A
						INNER JOIN MUTASI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
						WHERE
							 A.STATUS_PEGAWAI IN (1,2) ".$statement; 
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
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM pegawai WHERE PEGAWAI_ID IS NOT NULL "; 
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
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

  class CetakanDataPdf extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
   function CetakanDataPdf()
	 {
	   //    $xmlfile = "../WEB/web.xml";
      // $data = simplexml_load_file($xmlfile);
      // $rconf_url_info= $data->urlConfig->main->urlbase;

      // $this->db=$rconf_url_info;
      $this->db='simpeg';
      $this->Entity(); 
    }
	

    function selectByParamsJadwalFormula($statement='', $sOrder="")
  	{
  		$str = "
  		SELECT B.FORMULA_ID
  		FROM jadwal_tes A
  		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
  		WHERE 1=1
  		"; 

  		$str .= $statement." ".$sOrder;
  		$this->query = $str;
  		// echo $str;exit;
  		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsSaudara($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.TGL_LAHIR ASC")
    {
      $str = "
      SELECT 
      A.*, b.nama nama_pendidikan
      FROM simpeg.saudara A
      left join simpeg.pendidikan b on cast(a.pendidikan as int)=b.pendidikan_id
      WHERE 1=1
      "; 
      
      while(list($key,$val) = each($paramsArray))
      {
        $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query = $str;
      // echo $str;exit();
          
      return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY  A.PANGKAT_ID")
    {
      $str = "
      SELECT 
      A.*
      FROM simpeg.pangkat A
      WHERE 1=1
      "; 
      
      while(list($key,$val) = each($paramsArray))
      {
        $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query = $str;
      // echo $str;exit();
          
      return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsDataPribadi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str= "
    	SELECT 
      A.PEGAWAI_ID PESERTA_ID, a.NAMA NAMA, a.NIK KTP, a.NIP_BARU NIP, JENIS_KELAMIN, a.jenjang_jabatan
      , CASE JENIS_KELAMIN WHEN 'L' THEN 'Laki-laki' WHEN 'P' THEN 'Perempuan' END JENIS_KELAMIN_NAMA,
      TEMPAT_LAHIR, TGL_LAHIR TANGGAL_LAHIR, AGAMA, LAST_JABATAN JABATAN,
       A.HP ALAMAT_RUMAH_TELP, A.EMAIL,b.link_foto FOTO_LINK,
       TMT_CPNS, TMT_PNS
      , A.STATUS_JENIS
      , A.STATUS_KAWIN
      , A.STATUS_PEGAWAI_ID
      , A.ALAMAT
      , A.SOSIAL_MEDIA
      , A.ALAMAT_TEMPAT_KERJA
      , A.TEMPAT_KERJA
      , A.SOSIAL_MEDIA
      , A.AUTO_ANAMNESA
      ,A.LAST_PANGKAT_ID
      ,A.LAST_ATASAN_LANGSUNG_NAMA
      ,A.LAST_ATASAN_LANGSUNG_JABATAN
      , CASE A.STATUS_KAWIN WHEN '1' THEN 'Belum Kawin' WHEN '2' THEN 'Kawin' WHEN '3' THEN 'Janda' WHEN '4' THEN 'Duda'  ELSE 'Belum ada Data' END STATUS_KAWIN_INFO
      , CASE A.STATUS_PEGAWAI_ID WHEN '1' THEN 'CPNS' WHEN '2' THEN 'PNS' END STATUS_PEGAWAI_INFO
      FROM simpeg.PEGAWAI A
      left JOIN SIMPEG.UPLOAD_FILE b ON A.PEGAWAI_ID = b.pegawai_ID
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

     function selectByParamspendidikanDataK($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
      $str= "
      SELECT 
      b.name nama_pendidikan from riwayat_pendidikan a
      left join simpeg.pendidikan b on a.pendidikan_id= b.pendidikan_id
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

    function selectByParamsPendidikanRiwayat($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $statement='',  $sOrder=" ORDER BY A.PENDIDIKAN_ID DESC, B.PEGAWAI_ID")
    {
      $str = "
      SELECT
      A.PENDIDIKAN_ID, A.NAMA
      , B.RIWAYAT_PENDIDIKAN_ID, B.NAMA_SEKOLAH, B.TAHUN
      , B.JURUSAN, B.PEGAWAI_ID, B.TEMPAT, B.TAHUN_AWAL, B.TAHUN_AKHIR
      FROM simpeg.pendidikan A
      LEFT JOIN (SELECT * FROM simpeg.riwayat_pendidikan WHERE PEGAWAI_ID = ".$pegawaiid." ) B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
      WHERE 1=1
      "; 
      
      while(list($key,$val) = each($paramsArray))
      {
        $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query = $str;
      // echo $str;exit();
          
      return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsPendidikanRiwayatFormal($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.PENDIDIKAN_ID DESC")
    {
      $str = "
      SELECT
      a.*, b.nama nama_pendidikan
      from simpeg.riwayat_pendidikan a
      left join simpeg.pendidikan b on a.pendidikan_id =b.pendidikan_id
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

    function selectByParamsPendidikanRiwayatJabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.PENDIDIKAN_ID DESC")
    {
      $str = "
      SELECT
      a.*
      from simpeg.riwayat_jabatan a
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

    function selectByParamsPendidikanUraianTugas($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.RIWAYAT_JABATAN_INFO_ID")
    {
      $str = "
      SELECT
      a.*
      from simpeg.riwayat_jabatan_info a
      WHERE 1=1
      "; 
      while(list($key,$val)= each($paramsArray))
      {
        $str .= " AND $key= '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query= $str;
    // echo $str;exit();
      return $this->selectLimit($str,$limit,$from); 
    }    

     function selectByParamsDataPekerjaan($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.DATA_PEKERJAAN_ID")
    {
      $str = "
      SELECT
      a.*
      from simpeg.data_pekerjaan a
      WHERE 1=1
      "; 
      while(list($key,$val)= each($paramsArray))
      {
        $str .= " AND $key= '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query= $str;
    // echo $str;exit();
      return $this->selectLimit($str,$limit,$from); 
    }    
    function selectByParamsKondisiKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.KONDISI_KERJA_ID")
    {
      $str = "
      SELECT
      a.*
      from simpeg.Kondisi_Kerja a
      WHERE 1=1
      "; 
      while(list($key,$val)= each($paramsArray))
      {
        $str .= " AND $key= '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query= $str;
    // echo $str;exit();
      return $this->selectLimit($str,$limit,$from); 
    }    

    function selectByParamsMinatdanHarapan($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.MINAT_HARAPAN_ID")
    {
      $str = "
      SELECT
      a.*
      from simpeg.Minat_harapan a
      WHERE 1=1
      "; 
      while(list($key,$val)= each($paramsArray))
      {
        $str .= " AND $key= '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query= $str;
    // echo $str;exit();
      return $this->selectLimit($str,$limit,$from); 
    }    

     function selectByParamsKekuatanKelemahan($paramsArray=array(),$limit=-1,$from=-1, $statement='',  $sOrder=" ORDER BY A.KEKUATAN_KELEMAHAN_ID")
    {
      $str = "
      SELECT
      a.*
      from simpeg.kekuatan_kelemahan a
      WHERE 1=1
      "; 
      while(list($key,$val)= each($paramsArray))
      {
        $str .= " AND $key= '$val' ";
      }
      
      $str .= $statement." ".$sOrder;
      $this->query= $str;
    // echo $str;exit();
      return $this->selectLimit($str,$limit,$from); 
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



  } 


?>
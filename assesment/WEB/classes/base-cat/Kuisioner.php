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

    function selectByParamsSoal($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="order by kuisioner_id asc")
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

     function selectByParamsJawaban($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="order by KUISIONER_PERTANYAAN_ID asc")
	{
		$str = "
		SELECT*
		FROM kuisioner A
		left join cat.kuisioner_pilihan b on a.kuisioner_jawaban_id=b.kuisioner_pilihan_id
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

   function selectByParamsDataPeserta($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="order by no_urut")
	{
		$str = "
		select p.pegawai_id, p.nama nama_peserta, p.nip_baru, pd.nama nama_pendidikan, p.TGL_LAHIR, b.no_urut,
		case when p.jenis_kelamin = 'L' then 'Laki-laki' 
		when p.jenis_kelamin = 'P' then 'Perempuan'
		end jenis_kelamin
		from jadwal_tes_simulasi_pegawai a
		left join simpeg.pegawai p on a.pegawai_id = p.pegawai_id
		left join simpeg.pendidikan pd on p.last_dik_jenjang = pd.pendidikan_id
		left join jadwal_awal_tes_simulasi_pegawai b on b.JADWAL_AWAL_TES_SIMULASI_ID = a.jadwal_tes_id and a.pegawai_id =b.pegawai_id
		where 1=1
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
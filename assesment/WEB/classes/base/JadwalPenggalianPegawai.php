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

  class JadwalPenggalianPegawai extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function JadwalPenggalianPegawai()
	{
      $this->db='simpeg';
	  $this->Entity();  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JADWAL_ASESOR_ID", $this->getNextId("JADWAL_ASESOR_ID","jadwal_penggalian_pegawai")); 

		$str = "INSERT INTO jadwal_penggalian_pegawai (
				   JADWAL_ASESOR_ID, JADWAL_TES_ID, JADWAL_ACARA_ID, ASESOR_ID, KELOMPOK, RUANG, JADWAL_KELOMPOK_RUANGAN_ID, KETERANGAN, BATAS_PEGAWAI, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES (
				  ".$this->getField("JADWAL_ASESOR_ID").",
				  ".$this->getField("JADWAL_TES_ID").",
				  ".$this->getField("JADWAL_ACARA_ID").",
				  ".$this->getField("ASESOR_ID").",
				  '".$this->getField("KELOMPOK")."',
				  '".$this->getField("RUANG")."',
				  ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("BATAS_PEGAWAI").",
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE")."
				)"; 
		$this->id= $this->getField("JADWAL_ASESOR_ID");
		$this->query= $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE jadwal_penggalian_pegawai SET
				  JADWAL_TES_ID= ".$this->getField("JADWAL_TES_ID").",
				  JADWAL_ACARA_ID= ".$this->getField("JADWAL_ACARA_ID").",
				  ASESOR_ID= ".$this->getField("ASESOR_ID").",
				  KELOMPOK= '".$this->getField("KELOMPOK")."',
				  RUANG= '".$this->getField("RUANG")."',
				  JADWAL_KELOMPOK_RUANGAN_ID= ".$this->getField("JADWAL_KELOMPOK_RUANGAN_ID").",
				  KETERANGAN= '".$this->getField("KETERANGAN")."',
				  BATAS_PEGAWAI= ".$this->getField("BATAS_PEGAWAI").",
   			      LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			      LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE")."	
				WHERE JADWAL_ASESOR_ID = ".$this->getField("JADWAL_ASESOR_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM jadwal_penggalian_pegawai
                WHERE 
                  JADWAL_ASESOR_ID = '".$this->getField("JADWAL_ASESOR_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectbyparams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT *
		FROM jadwal_penggalian_pegawai A WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectbyparamsNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
				SELECT JADWAL_TES_ID,PENGGALIAN_ID,PEGAWAI_ID,count(pegawai_id) total_terjawab,
		(select count(b.pegawai_id) from jadwal_pegawai_detil_atribut b where a.JADWAL_TES_ID=b.JADWAL_TES_ID
		 and a.PENGGALIAN_ID=b.PENGGALIAN_ID and a.PEGAWAI_ID=b.PEGAWAI_ID) patokan
		FROM jadwal_pegawai_detil_atribut A WHERE 1=1 AND nilai!='0.0' 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder." group by JADWAL_TES_ID,PENGGALIAN_ID,PEGAWAI_ID";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectbyparamsPsikotest($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
			SELECT jadwal_tes_id ,0 penggalian_id, a.pegawai_id,count(a.pegawai_id) total_terjawab,
			(select count(p.pegawai_id) from PENILAIAN_DETIL pd 
			LEFT JOIN PENILAIAN p ON p.PENILAIAN_ID=pd.PENILAIAN_ID
			 where b.JADWAL_TES_ID=p.JADWAL_TES_ID
					 and a.PEGAWAI_ID=pd.PEGAWAI_ID and aspek_id=2) patokan
			FROM PENILAIAN_DETIL A
			LEFT JOIN PENILAIAN B ON A.PENILAIAN_ID=B.PENILAIAN_ID 
			 WHERE 1=1 AND ASPEK_ID=2 
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder." group by JADWAL_TES_ID,PENGGALIAN_ID,a.PEGAWAI_ID";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectbyparamspenggalian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY B.KODE")
	{
		$str = "
		SELECT
			DISTINCT B.PENGGALIAN_ID, B.KODE
			, CASE B.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE B.NAMA END PENGGALIAN_NAMA
			, CASE B.PENGGALIAN_ID WHEN 0 THEN 'Psikotes' ELSE B.KODE END PENGGALIAN_KODE
		FROM jadwal_acara A
		INNER JOIN penggalian B ON A.PENGGALIAN_ID = B.PENGGALIAN_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function pjadwalpenggalianpegawai()
	{
        $str = "
		SELECT pjadwalpenggalianpegawai(".$this->getField("JADWAL_TES_ID").", ".$this->getField("PEGAWAI_ID").")
		"; 
		$this->query = $str;
		// echo $str;exit();
        return $this->execQuery($str);
    }

    function selectbyparamssimulasipegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT *
		FROM jadwal_awal_tes_simulasi_pegawai A WHERE 1=1"; 
		// jadwal_awal_tes_simulasi_id = 339
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
  } 
?>
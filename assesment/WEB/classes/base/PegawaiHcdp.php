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

  class PegawaiHcdp extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function PegawaiHcdp()
	{
	  $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("PEGAWAI_HCDP_ID", $this->getNextId("PEGAWAI_HCDP_ID","pegawai_hcdp"));
		
		$str = "
		INSERT INTO pegawai_hcdp 
		(
			PEGAWAI_HCDP_ID, PEGAWAI_ID, FORMULA_ID
			, JPM, IKK, METODE, JUMLAH_JP, TAHUN
			, KUADRAN, SARAN_PENGEMBANGAN, RINGKASAN_PROFIL_KOMPETENSI
		)
		VALUES 
		(
			".$this->getField("PEGAWAI_HCDP_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("FORMULA_ID")."
			, ".$this->getField("JPM")."
			, ".$this->getField("IKK")."
			, '".$this->getField("METODE")."'
			, 0
			, ".$this->getField("TAHUN")."
			, ".$this->getField("KUADRAN")."
			, '".$this->getField("SARAN_PENGEMBANGAN")."'
			, '".$this->getField("RINGKASAN_PROFIL_KOMPETENSI")."'
		)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("PEGAWAI_HCDP_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
		UPDATE pegawai_hcdp
		SET
		   JPM= ".$this->getField("JPM")."
		   , IKK= ".$this->getField("IKK")."
		   , METODE= '".$this->getField("METODE")."'
		   , TAHUN= ".$this->getField("TAHUN")."
		   , KUADRAN= ".$this->getField("KUADRAN")."
		   , SARAN_PENGEMBANGAN= '".$this->getField("SARAN_PENGEMBANGAN")."'
		   , RINGKASAN_PROFIL_KOMPETENSI= '".$this->getField("RINGKASAN_PROFIL_KOMPETENSI")."'
		WHERE PEGAWAI_HCDP_ID= '".$this->getField("PEGAWAI_HCDP_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updatejp()
	{
		$str = "
		UPDATE pegawai_hcdp
		SET
		   JUMLAH_JP= ".$this->getField("JUMLAH_JP")."
		WHERE PEGAWAI_HCDP_ID= '".$this->getField("PEGAWAI_HCDP_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function sethcdp()
	{
		$str = "SELECT PHCDP(".$this->getField("FORMULA_ID").", ".$this->getField("JADWAL_TES_ID").") AS ROWCOUNT ";
		// echo $str;exit;
		$this->query = $str;
		echo $str;exit;
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }

    function selectByParamsJadwalTes($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, B.FORMULA_ID
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsTahun()
	{
		$str = "
		SELECT TAHUN FROM pegawai_hcdp GROUP BY TAHUN ORDER BY TAHUN
		";

		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,-1,-1);
	}

	function selectByParamsPenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT 
		COALESCE(A.IKK,0) * 100 IKK, COALESCE(A.JPM,0) * 100 JPM
		, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
		, CASE TIPE_FORMULA WHEN '1' THEN 'Tujuan Pengisian'  WHEN '2' THEN 'Tujuan Pemetaan' ELSE '-' END METODE
		, A.SARAN_PENGEMBANGAN, A.RINGKASAN_PROFIL_KOMPETENSI
		FROM penilaian A
		INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
		INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
		WHERE 1=1 AND ASPEK_ID = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by pegawai_hcdp_id desc")
	{
		$str = "
		SELECT A.*
		, PEGAWAI_NAMA, PEGAWAI_NIP_BARU, PEGAWAI_JABATAN_NAMA, PEGAWAI_PANGKAT_KODE, PEGAWAI_PANGKAT_NAMA
		, K.KODE_KUADRAN
		FROM pegawai_hcdp A
		LEFT JOIN
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP_BARU, A.LAST_JABATAN PEGAWAI_JABATAN_NAMA
			, B.KODE PEGAWAI_PANGKAT_KODE, B.NAMA PEGAWAI_PANGKAT_NAMA
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
		) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT * FROM
			(
				SELECT * FROM P_KUADRAN_INFOJPM()
			) A
		) K ON K.ID_KUADRAN = A.KUADRAN
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by tanggal_tes desc")
	{
		$str = "
		SELECT ikk, K.KODE_KUADRAN, 
			A.PEGAWAI_ID, A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP_BARU, A.LAST_JABATAN PEGAWAI_JABATAN_NAMA
			, B.KODE PEGAWAI_PANGKAT_KODE, B.NAMA PEGAWAI_PANGKAT_NAMA, jat.jadwal_awal_tes_id JADWAL_TES_ID, fe.formula_id FORMULA_ID
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN jadwal_awal_tes_pegawai jatp ON jatp.pegawai_id = a.pegawai_id
			LEFT JOIN jadwal_awal_tes jat ON jat.jadwal_awal_tes_id = jatp.jadwal_awal_tes_id
			LEFT JOIN formula_eselon fe ON fe.formula_eselon_id = jat.formula_eselon_id
			left join pegawai_hcdp ph on ph.pegawai_id=a.pegawai_id
				LEFT JOIN
		(
			SELECT * FROM
			(
				SELECT * FROM P_KUADRAN_INFOJPM()
			) A
		) K ON K.ID_KUADRAN = ph.KUADRAN
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." group by A.PEGAWAI_ID, A.NAMA, A.NIP_BARU, A.LAST_JABATAN, B.KODE, B.NAMA,ph.ikk,k.kode_kuadran , jat.jadwal_awal_tes_id, fe.formula_id, tanggal_tes ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pegawai_hcdp A
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

    function selectByParamsRekapitulasi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT A.* 
		, PEGAWAI_NAMA, PEGAWAI_NIP_BARU
		--, CASE WHEN COALESCE(NULLIF(CUPD.JABATAN_UJIAN, ''), NULL) IS NULL THEN PEGAWAI_JABATAN_NAMA ELSE CUPD.JABATAN_UJIAN END PEGAWAI_JABATAN_NAMA_SAAT_TES
		, CASE WHEN COALESCE(NULLIF(CUPD.JABATAN_UJIAN, ''), NULL) IS NULL THEN '-' ELSE CUPD.JABATAN_UJIAN END PEGAWAI_JABATAN_NAMA_SAAT_TES
		, PEGAWAI_JABATAN_NAMA
		, PEGAWAI_PANGKAT_KODE, PEGAWAI_PANGKAT_NAMA
		, K.KODE_KUADRAN
		, x.atribut
		, y.nama_pelatihan
		, CASE WHEN A.IKK >= 50 THEN 'Tinggi'
		WHEN (A.IKK < 50 AND A.IKK >= 25) THEN 'Sedang' 
		WHEN (A.IKK < 25 AND A.IKK > 0) THEN 'Rendah'
		WHEN (A.IKK <= 0) THEN 'Tidak Ada Kesenjangan'
		END KESENJANGAN_KOMPETENSI
		, CASE 
		WHEN A.IKK >= COALESCE(TINGGI_AWAL,1000) AND A.IKK <= COALESCE(TINGGI_AKHIR,1000) THEN 'Tinggi'
		WHEN A.IKK >= COALESCE(SEDANG_AWAL,1000) AND A.IKK <= COALESCE(SEDANG_AKHIR,1000) THEN 'Sedang'
		ELSE 'Rendah' END INFO_IKK
		FROM pegawai_hcdp A
		LEFT JOIN
		(
			select a.pegawai_id, string_agg(trim(a.nama), ', ') atribut 
			from 
			(
				select a.pegawai_id,  b.nama  
				from pegawai_hcdp_detil a 
				inner join atribut b on a.atribut_id = b.atribut_id
				group by a.pegawai_id, b.nama, a.pelatihan_nama
				order by a.pegawai_id, b.nama, a.pelatihan_nama asc
			) a group by a.pegawai_id
		) X ON X.PEGAWAI_ID = A.PEGAWAI_ID 
		LEFT JOIN
		(
			select a.pegawai_id, string_agg(trim(a.nama_pelatihan), ', ') nama_pelatihan 
			from 
			(
				select a.pegawai_id,  a.nama_pelatihan 
				from pegawai_hcdp_detil a  
				group by a.pegawai_id,  a.nama_pelatihan
				order by a.pegawai_id,  a.nama_pelatihan asc
			) a group by a.pegawai_id
		) Y ON Y.PEGAWAI_ID = A.PEGAWAI_ID 
		LEFT JOIN
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP_BARU, A.LAST_JABATAN PEGAWAI_JABATAN_NAMA
			, A.SATKER_ID
			, B.KODE PEGAWAI_PANGKAT_KODE, B.NAMA PEGAWAI_PANGKAT_NAMA
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
		) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT * FROM
			(
				SELECT * FROM P_KUADRAN_INFOJPM()
			) A
		) K ON K.ID_KUADRAN = A.KUADRAN
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID PD_JADWAL_TES_ID, A.PEGAWAI_ID PD_PEGAWAI_ID, FORMULA_ID PD_FORMULA_ID, A.JABATAN_UJIAN
			FROM cat.ujian_pegawai_daftar A
			INNER JOIN
			(
				SELECT A.JADWAL_TES_ID, B.FORMULA_ID
				FROM jadwal_tes A
				INNER JOIN (SELECT FORMULA_ID, FORMULA_ESELON_ID FROM formula_eselon) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
			) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
			WHERE 1=1
		) CUPD ON A.PEGAWAI_ID = PD_PEGAWAI_ID AND A.FORMULA_ID = PD_FORMULA_ID
		LEFT JOIN (select tahun tahun_ikk, * from toleransi_ikk) TK ON TAHUN_IKK = A.TAHUN
		-- INNER JOIN
		-- (
		-- 	SELECT A.FORMULA_ID, C.TANGGAL_TES
		-- 	FROM formula_assesment A
		-- 	INNER JOIN formula_eselon B ON A.FORMULA_ID = B.FORMULA_ID
		-- 	INNER JOIN jadwal_tes C ON C.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		-- ) J ON A.FORMULA_ID = J.FORMULA_ID
		WHERE 1=1 AND A.JPM > 0
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
	
    function getCountByParamsRekapitulasi($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pegawai_hcdp A
		LEFT JOIN
		(
			select a.pegawai_id, string_agg(trim(a.nama), ', ') atribut 
			from 
			(
				select a.pegawai_id,  b.nama  
				from pegawai_hcdp_detil a 
				inner join atribut b on a.atribut_id = b.atribut_id
				group by a.pegawai_id, b.nama, a.pelatihan_nama
				order by a.pegawai_id, b.nama, a.pelatihan_nama asc
			) a group by a.pegawai_id
		) X ON X.PEGAWAI_ID = A.PEGAWAI_ID 
		LEFT JOIN
		(
			select a.pegawai_id, string_agg(trim(a.nama_pelatihan), ', ') nama_pelatihan 
			from 
			(
				select a.pegawai_id,  a.nama_pelatihan 
				from pegawai_hcdp_detil a  
				group by a.pegawai_id,  a.nama_pelatihan
				order by a.pegawai_id,  a.nama_pelatihan asc
			) a group by a.pegawai_id
		) Y ON Y.PEGAWAI_ID = A.PEGAWAI_ID 
		LEFT JOIN
		(
			SELECT
			A.PEGAWAI_ID, A.NAMA PEGAWAI_NAMA, A.NIP_BARU PEGAWAI_NIP_BARU, A.LAST_JABATAN PEGAWAI_JABATAN_NAMA
			, B.KODE PEGAWAI_PANGKAT_KODE, B.NAMA PEGAWAI_PANGKAT_NAMA
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
		) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT * FROM
			(
				SELECT * FROM P_KUADRAN_INFOJPM()
			) A
		) K ON K.ID_KUADRAN = A.KUADRAN
		LEFT JOIN (select tahun tahun_ikk, * from toleransi_ikk) TK ON TAHUN_IKK = A.TAHUN
		WHERE 1=1 AND A.JPM > 0 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function selectByParamsAtribut($paramsArray=array(),$limit=-1,$from=-1, $id, $pegawaiid, $statement='', $sOrder="ORDER BY ATR.ASPEK_ID DESC, A.ATRIBUT_ID")
	{
		$str = "
		SELECT
			A.PERMEN_ID, A.ATRIBUT_ID, ATR.NAMA ATRIBUT_NAMA
			, PELATIHAN_ID, PELATIHAN_NAMA,A.GAP,PL.JP,PL.TAHUN, jadwal_tes_id
		FROM penilaian_detil A
		left join penilaian  p on a.penilaian_id= p.penilaian_id
		INNER JOIN atribut ATR ON ATR.ATRIBUT_ID = A.ATRIBUT_ID AND ATR.PERMEN_ID = A.PERMEN_ID
		LEFT JOIN
		(
			SELECT
			PEGAWAI_HCDP_ID, PEGAWAI_ID, ATRIBUT_ID, PELATIHAN_ID, PELATIHAN_NAMA, PERMEN_ID,JP,TAHUN
			FROM pegawai_hcdp_detil
			WHERE PEGAWAI_HCDP_ID = ".$id." AND PEGAWAI_ID = ".$pegawaiid."
			GROUP BY PEGAWAI_HCDP_ID, PEGAWAI_ID, ATRIBUT_ID, PELATIHAN_ID, PELATIHAN_NAMA, PERMEN_ID,JP,TAHUN
		) PL ON PL.ATRIBUT_ID = A.ATRIBUT_ID AND PL.PERMEN_ID = A.PERMEN_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDetilFeedback($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY urut ASC")
	{
		$str = "
		SELECT a.*
		FROM feedback_pengembangan_diri A
		left join feedback f on a.feedback_id=f.feedback_id
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
	
  } 
?>
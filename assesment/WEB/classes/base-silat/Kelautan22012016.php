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

  class Kelautan extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Kelautan()
	{
      $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	
	function selectByParamsSatuanKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by A.ID_TREE asc")
	{
		$str = "
				SELECT 
					  A.ID_TREE AS ID,
						PARENT_TREE AS PARENT_ID,
						NAMA_UNKER NAMA,
						KODE_UNKER KODE,
						A.ID_TREE AS ID_TABLE,
						PARENT_TREE AS ID_TABLE_PARENT,
					  CONCAT_WS(' ',
						CASE
							WHEN COALESCE(B.JUMLAH_SUB, 0) > 0 THEN
								'<img src=\"../WEB/images/tree-satker.png\"> '
							ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
						END, NAMA_UNKER) AS NAMA_WARNA,
						ALAMAT_KANTOR, TELP, 
						ESELON,  NAMA_JABATAN
				FROM ".$this->db.".rb_ref_unker A
				LEFT JOIN 
				(SELECT COUNT(1) JUMLAH_SUB, PARENT_TREE ID_TREE FROM ".$this->db.".rb_ref_unker GROUP BY PARENT_TREE) B ON A.ID_TREE = B.ID_TREE
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
	
	function getCountByParamsSatuanKerja($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT 
					  a.id_tree AS id,
						parent_tree AS parent_id,
						nama_unker nama,
						kode_unker kode,
						a.id_tree AS id_table,
						parent_tree AS id_table_parent,
					  CONCAT_WS(' ',
						CASE
							WHEN COALESCE(b.jumlah_sub, 0) > 0 THEN
								'<img src=\"../WEB/images/tree-satker.png\"> '
							ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
						END, nama_unker) AS nama_warna,
						alamat_kantor, telp, 
						eselon,  NAMA_JABATAN
				FROM ".$this->db.".rb_ref_unker a
				LEFT JOIN 
				(select count(1) jumlah_sub, parent_tree id_tree from ".$this->db.".rb_ref_unker GROUP BY parent_tree) b on a.id_tree = b.id_tree
				WHERE 1=1
			"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$str.= ") A";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			     B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
				 , AMBIL_UMUR(A.TGL_LAHIR) UMUR
			FROM ".$this->db.".rb_data_pegawai A 
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			, ".$this->db.".rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
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
	
	function selectByParamsMonitoringEselon($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT KODE_ESELON, NAMA_ESELON 
			FROM ".$this->db.".rb_ref_eselon 
			WHERE 1=1
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
	
	function selectByParamsMonitoringPenilaianPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun='2015')
	{
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
					 B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
			FROM ".$this->db.".rb_data_pegawai A
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			INNER JOIN
			(
			SELECT PEGAWAI_ID FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) P ON A.IDPEG = P.PEGAWAI_ID
			, ".$this->db.".rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
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
	
	function selectByParamsMonitoringAnalisaPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY TO_CHAR(A.TANGGAL_TES, 'YYYY'), D.ATRIBUT_ID ASC')
	{
		$str = "
			SELECT 
			TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN,
			G.NAMA NAMA_ATRIBUT_PARENT_NAMA, D.NAMA ATRIBUT_NAMA, F.NAMA TRAINING_NAMA,
			X1.IDPEG, X1.NIP_LAMA, X1.NIP_BARU, X1.NAMA, X1.GELAR_DEPAN, X1.GELAR_BELAKANG, '' JENIS_KELAMIN, X1.TEMPAT_LAHIR, X1.TGL_LAHIR, CASE X1.STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
					 X2.NAMA_GOL, X1.TMT_GOL_AKHIR, X3.NAMA_ESELON, X1.NAMA_JAB_STRUKTURAL, X1.TELP, X4.NAMA_UNKER SATKER, X1.KODE_UNKER
			FROM penilaian A
			INNER JOIN ".$this->db.".rb_data_pegawai X1 ON A.PEGAWAI_ID = X1.IDPEG
			LEFT JOIN ".$this->db.".rb_ref_gol X2 ON X1.KODE_GOL_AKHIR = X2.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon X3 ON X1.KODE_ESELON = X3.KODE_ESELON
			INNER JOIN ".$this->db.".rb_ref_unker X4 ON X1.KODE_UNKER = X4.KODE_UNKER
			INNER JOIN penilaian_detil C ON A.PENILAIAN_ID = C.PENILAIAN_ID
			INNER JOIN atribut D ON C.ATRIBUT_ID = D.ATRIBUT_ID AND D.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY')
			INNER JOIN kompetensi_training E ON C.ATRIBUT_ID = E.ATRIBUT_ID AND E.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY')
			INNER JOIN training F ON E.TRAINING_ID = F.TRAINING_ID AND F.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY')
			INNER JOIN
			(
			SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
			) G ON D.ATRIBUT_ID_PARENT = G.ATRIBUT_ID
			WHERE 1=1
			AND C.GAP < 0 
	 		"; 
		//AND D.ATRIBUT_ID = '0101'
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringBelajarPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT CASE WHEN A.JENIS = '1' THEN 'Dalam negeri' WHEN A.JENIS = '2' THEN 'Luar negeri' ELSE '' END JENIS_NAMA,
			A.UNIVERSITAS_ASAL, 
			X1.IDPEG, X1.NIP_LAMA, X1.NIP_BARU, X1.NAMA, X1.GELAR_DEPAN, X1.GELAR_BELAKANG, '' JENIS_KELAMIN, X1.TEMPAT_LAHIR, X1.TGL_LAHIR, CASE X1.STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
					 X2.NAMA_GOL, X1.TMT_GOL_AKHIR, X3.NAMA_ESELON, X1.NAMA_JAB_STRUKTURAL, X1.TELP, X4.NAMA_UNKER SATKER, X1.KODE_UNKER
			FROM beasiswa A
			INNER JOIN ".$this->db.".rb_data_pegawai X1 ON A.PEGAWAI_ID = X1.IDPEG
			LEFT JOIN ".$this->db.".rb_ref_gol X2 ON X1.KODE_GOL_AKHIR = X2.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon X3 ON X1.KODE_ESELON = X3.KODE_ESELON
			INNER JOIN ".$this->db.".rb_ref_unker X4 ON X1.KODE_UNKER = X4.KODE_UNKER
			WHERE 1=1
	 		"; 
		//AND D.ATRIBUT_ID = '0101'
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsAnalisaDiklatKompetensiBendel($paramsArray=array(),$limit=-1,$from=-1, $statement="", $tahun='', $prosentase='0', $orderby='')
	{
		$str = "
		SELECT B.TAHUN, A.STANDAR_KOMPETENSI_ID, A.NAMA, A.DIKLAT, COUNT(D.PEGAWAI_ID) JUMLAH_PEGAWAI
		FROM standar_kompetensi A
		, standar_kompetensi_detil B 
		, penilaian_tna_detil C 
		, penilaian_tna D 
		, ".$this->db.".rb_data_pegawai E
		WHERE 1=1 AND A.STANDAR_KOMPETENSI_ID = B.STANDAR_KOMPETENSI_ID 
		";
		if($tahun == ""){}
		else
		{
		$str .= 
		"
		AND B.TAHUN = '".$tahun."'
		";
		}
		
		$str .= "
		AND B.STANDAR_KOMPETENSI_DETIL_ID = C.STANDAR_KOMPETENSI_DETIL_ID AND ((C.NILAI + (C.NILAI * ".$prosentase.")) - B.BOBOT) < 0
		AND C.PENILAIAN_TNA_ID = D.PENILAIAN_TNA_ID 
		AND D.PEGAWAI_ID = E.IDPEG
		";
		if($tahun == ""){}
		else
		{
		$str .= 
		"
		AND TO_CHAR(D.PERIODE, 'YYYY') = '".$tahun."'
		";
		}
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY B.TAHUN, A.STANDAR_KOMPETENSI_ID, A.DIKLAT ORDER BY B.TAHUN, A.STANDAR_KOMPETENSI_ID, A.NAMA ASC";
		$this->query = $str;
		//echo $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		/*TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
		TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
		TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TANGGAL_LAHIR, */
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			     B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
			FROM ".$this->db.".rb_data_pegawai A 
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			, ".$this->db.".rb_ref_unker D 
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
	
	function selectByParamsJabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY TANGGAL_SK ASC')
	{
		$str = "
				SELECT
				B.NIP, B.INFO_JABATAN, B.INFO_ESELON, B.UNIT_KERJA, B.NOMOR_SK, B.TANGGAL_SK
				FROM ".$this->db.".rb_data_pegawai A
				LEFT JOIN
				(
				SELECT NIP, NAMA_JAB INFO_JABATAN, B.NAMA_ESELON INFO_ESELON, UNIT_KERJA, NOMOR_SK, TANGGAL_SK FROM ".$this->db.".datajenjabatan A
				LEFT JOIN ".$this->db.".rb_ref_eselon B ON B.KODE_ESELON = A.ESELON
				UNION ALL
				SELECT NIP, B.NAMA_FUNG INFO_JABATAN, '' INFO_ESELON, UNIT_KERJA, NOMOR_SK, TANGGAL_SK FROM ".$this->db.".datajenjabfung A
				LEFT JOIN ".$this->db.".rb_ref_fung B ON B.KODE_FUNG = A.KODE_FUNG
				) B ON B.NIP = A.NIP_LAMA OR B.NIP = A.NIP_BARU
				WHERE 1=1
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
	
	function getCountByParamsMonitoringAnalisaPegawai($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM penilaian A
				INNER JOIN ".$this->db.".rb_data_pegawai X1 ON A.PEGAWAI_ID = X1.IDPEG
				LEFT JOIN ".$this->db.".rb_ref_gol X2 ON X1.KODE_GOL_AKHIR = X2.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon X3 ON X1.KODE_ESELON = X3.KODE_ESELON
				INNER JOIN ".$this->db.".rb_ref_unker X4 ON X1.KODE_UNKER = X4.KODE_UNKER
				INNER JOIN penilaian_detil C ON A.PENILAIAN_ID = C.PENILAIAN_ID
				INNER JOIN atribut D ON C.ATRIBUT_ID = D.ATRIBUT_ID AND D.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY')
				INNER JOIN kompetensi_training E ON C.ATRIBUT_ID = E.ATRIBUT_ID AND E.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY')
				INNER JOIN training F ON E.TRAINING_ID = F.TRAINING_ID AND F.TAHUN = TO_CHAR(A.TANGGAL_TES, 'YYYY')
				INNER JOIN
				(
				SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
				) G ON D.ATRIBUT_ID_PARENT = G.ATRIBUT_ID
				WHERE 1=1
				AND C.GAP < 0 
				".$statement; 
		while(list($key,$val)=each($paramsArray))
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
	
	function getCountByParamsMonitoringBelajarPegawai($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM beasiswa A
				INNER JOIN ".$this->db.".rb_data_pegawai X1 ON A.PEGAWAI_ID = X1.IDPEG
				LEFT JOIN ".$this->db.".rb_ref_gol X2 ON X1.KODE_GOL_AKHIR = X2.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon X3 ON X1.KODE_ESELON = X3.KODE_ESELON
				INNER JOIN ".$this->db.".rb_ref_unker X4 ON X1.KODE_UNKER = X4.KODE_UNKER
				WHERE 1=1
				".$statement; 
		while(list($key,$val)=each($paramsArray))
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
	
	function getCountByParamsMonitoringEselon($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_ref_eselon 
				WHERE 1=1
				".$statement; 
		while(list($key,$val)=each($paramsArray))
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
	
	function getCountByParamsMonitoringPenilaianPegawai($paramsArray=array(), $statement="", $reqTahun='2015')
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
				INNER JOIN
				(
				SELECT PEGAWAI_ID FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				) P ON A.IDPEG = P.PEGAWAI_ID
				, ".$this->db.".rb_ref_unker D 
				WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
				".$statement; 
		while(list($key,$val)=each($paramsArray))
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
	
	function getCountByParamsMonitoringPegawai($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A 
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
				, ".$this->db.".rb_ref_unker D 
				WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
				".$statement; 
		while(list($key,$val)=each($paramsArray))
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

	function getCountByParamsAnalisaDiklatKompetensiBendel($paramsArray=array(), $statement="", $tahun='', $prosentase='0', $orderby='')
	{
		$str = "
		SELECT COUNT(*) AS ROWCOUNT
		FROM
		(
		SELECT A.STANDAR_KOMPETENSI_ID
		FROM standar_kompetensi A , standar_kompetensi_detil B , penilaian_tna_detil C , penilaian_tna D , ".$this->db.".rb_data_pegawai E
		WHERE 1=1 AND A.STANDAR_KOMPETENSI_ID = B.STANDAR_KOMPETENSI_ID 
		";
		
		if($tahun == ""){}
		else
		{
		$str .= 
		"
		AND B.TAHUN = '".$tahun."'
		";
		}
		
		$str .= "
		AND B.STANDAR_KOMPETENSI_DETIL_ID = C.STANDAR_KOMPETENSI_DETIL_ID AND ((C.NILAI + (C.NILAI * ".$prosentase.")) - B.BOBOT) < 0
		AND C.PENILAIAN_TNA_ID = D.PENILAIAN_TNA_ID 
		AND D.PEGAWAI_ID = E.IDPEG
		";
		
		if($tahun == ""){}
		else
		{
		$str .= 
		"
		AND TO_CHAR(D.PERIODE, 'YYYY') = '".$tahun."'
		";
		}
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		
		$str .= 
		"
		GROUP BY B.TAHUN, A.STANDAR_KOMPETENSI_ID, A.NAMA
		) A
		";
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
		
    }
	
  } 
?>
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
	
	function insertKandidat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "INSERT INTO pegawai_kandidat (
				   PEGAWAI_ID_PENSIUN, PEGAWAI_ID_PENGGANTI, STATUS)
				VALUES (
				  ".$this->getField("PEGAWAI_ID_PENSIUN").",
				  ".$this->getField("PEGAWAI_ID_PENGGANTI").",
				  '0'
				)"; 
				  //'".$this->getField("PATH")."'
		$this->query = $str;
		$this->id = $this->getField("PEGAWAI_ID_PENSIUN");
		//echo $str;
		return $this->execQuery($str);
    }
	
	function deleteKandidat()
	{
        $str = "DELETE FROM pegawai_kandidat
                WHERE 
                  PEGAWAI_ID_PENSIUN= '".$this->getField("PEGAWAI_ID_PENSIUN")."' AND
				  PEGAWAI_ID_PENGGANTI= '".$this->getField("PEGAWAI_ID_PENGGANTI")."' AND
				  STATUS= 0
				"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsPegawaiKandidat($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
				SELECT 
					  A.PEGAWAI_ID_PENSIUN, A.PEGAWAI_ID_PENGGANTI, STATUS
				FROM pegawai_kandidat A
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
	
	function selectByParamsSatuanKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by A.NAMA asc")
	{
		$str = "
				SELECT A.SATKER_ID AS ID, A.SATKER_ID_PARENT AS PARENT_ID, A.NAMA
				, '' KODE, SATKER_ID AS ID_TABLE, A.SATKER_ID_PARENT AS ID_TABLE_PARENT,
				CASE
					WHEN A.SATKER_ID_PARENT = '0' THEN
						'<img src=\"../WEB/images/tree-satker.png\"> '
					ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
				END || ' ' || A.NAMA AS NAMA_WARNA,
				'' ALAMAT_KANTOR, '' TELP, '' ESELON, '' NAMA_JABATAN
				FROM ".$this->db.".satker A
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
				SELECT A.SATKER_ID AS ID, A.SATKER_ID_PARENT AS PARENT_ID, A.NAMA
				, '' KODE, SATKER_ID AS ID_TABLE, A.SATKER_ID_PARENT AS ID_TABLE_PARENT,
				CASE
					WHEN A.SATKER_ID_PARENT = '0' THEN
						'<img src=\"../WEB/images/tree-satker.png\"> '
					ELSE '<img src=\"../WEB/images/tree-subsatker.png\"> '
				END || ' ' || A.NAMA AS NAMA_WARNA,
				'' ALAMAT_KANTOR, '' TELP, '' ESELON, '' NAMA_JABATAN
				FROM ".$this->db.".satker A
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
	
	function selectByParamsAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.ATRIBUT_ID AS ID, A.ATRIBUT_ID_PARENT PARENT_ID, A.NAMA
					, A.ATRIBUT_ID ID_ROW, A.TAHUN, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0'
					THEN
					CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=',A.TAHUN,'&reqAtributParentId=',A.ATRIBUT_ID,''')\"><img src=\"../WEB/images/icn_add.png\"></a> - <a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=',A.TAHUN,'&reqAtributId=',A.ATRIBUT_ID,''')\"><img src=\"../WEB/images/icn_edit.png\"></a>')
					ELSE
					CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=',A.TAHUN,'&reqAtributId=',A.ATRIBUT_ID,''')\"><img src=\"../WEB/images/icn_edit.png\"></a>')
					END
					LINK_URL
					FROM ".$this->db.".atribut A
					WHERE 1=1
				) A
				WHERE 1=1
				"; 
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsAtributCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.ATRIBUT_ID AS ID, A.ATRIBUT_ID_PARENT PARENT_ID, A.NAMA
					, A.ATRIBUT_ID ID_ROW, A.TAHUN, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0'
					THEN
					CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=',A.TAHUN,'&reqAtributParentId=',A.ATRIBUT_ID,''')\"><img src=\"../WEB/images/icn_add.png\"></a>')
					ELSE
					CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=',A.TAHUN,'&reqAtributId=',A.ATRIBUT_ID,''')\"><img src=\"../WEB/images/icn_add.png\"></a>')
					END
					LINK_URL
					FROM ".$this->db.".atribut A
					WHERE 1=1
				) A
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
	
	function selectByParamsJabatanAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $reqTahun= "", $reqAspekId="", $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT A.ID AS ID, A.PARENT_ID AS PARENT_ID, A.NAME NAMA, CASE A.PARENT_ID WHEN 0 THEN 0 ELSE 999 END ID_ROW
					, '' LINK_URL
					FROM ".$this->db.".division A
					WHERE 1=1
					UNION ALL
					SELECT
					CONCAT(A.ID, '-', ESELON_ID, '-0') AS ID, A.ID PARENT_ID, B.SVALUE NAMA, ESELON_ID ID_ROW
					, CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''jabatan_atribut_tree_lookup.php?reqTahun=".$reqTahun."&reqAspekId=".$reqAspekId."&reqEselonId=',ESELON_ID,'&reqSatuanKerjaId=',A.ID,''')\"><img src=\"../WEB/images/icn_add.png\"></a>') LINK_URL
					FROM ".$this->db.".division A,
					(
						SELECT A.ID, A.SKEY, A.SVALUE, REPLACE(A.SVALUE, ' ','') ESELON_INFO, 
						CASE REPLACE(A.SVALUE, ' ','')
						WHEN 'I.A' THEN '11' WHEN 'I.B' THEN '12' WHEN 'II.A' THEN '21' WHEN 'II.B' THEN '22'
						WHEN 'III.A' THEN '31' WHEN 'III.B' THEN '32' WHEN 'IV.A' THEN '41' WHEN 'IV.B' THEN '42' ELSE '99' END ESELON_ID
						from ".$this->db.".sysparam A
						WHERE A.SGROUP = 'esselon'
					) B
					WHERE 1=1
					UNION ALL
					SELECT
					CONCAT(A.SATUAN_KERJA_ID, '-', A.ESELON_ID, '-', A.ATRIBUT_ID) AS ID, CONCAT(A.SATUAN_KERJA_ID, '-', A.ESELON_ID, '-', A.ATRIBUT_PARENT_ID) PARENT_ID,
					CASE A.ATRIBUT_PARENT_ID WHEN '0'
					THEN CONCAT(B.NAMA, ' (', B.BOBOT, ')' )
					ELSE
					CONCAT(B.NAMA, ' (', B.NILAI_STANDAR, ')' )
					END NAMA
					, A.ATRIBUT_ID ID_ROW
					, CONCAT('<a onClick=\"pilihatribut(''',A.ESELON_ID,''', ''',A.SATUAN_KERJA_ID,''', ''',A.TAHUN,''', ''',A.ATRIBUT_ID,''')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icn_delete.png\" width=\"15px\" heigth=\"15px\"></a>')  LINK_URL
					FROM ".$this->db.".jabatan_eselon_atribut A
					INNER JOIN ".$this->db.".atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
					WHERE 1=1 AND A.ASPEK_ID = ".$reqAspekId."
				) A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJabatanAtributLookup($paramsArray=array(),$limit=-1,$from=-1, $reqEselonId, $reqSatuanKerjaId, $statement='', $sOrder="")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.TAHUN, A.NAMA,
				CASE WHEN (SELECT X.ATRIBUT_ID FROM ".$this->db.".jabatan_eselon_atribut X WHERE X.ESELON_ID = '".$reqEselonId."' AND X.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."' AND A.ATRIBUT_ID = X.ATRIBUT_ID) = A.ATRIBUT_ID
				THEN '' 
				ELSE 
				CONCAT('<a onClick=\"pilihatribut(''',ATRIBUT_ID,''')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icon_pilih.png\" width=\"20px\" heigth=\"20px\"></a>')
				END LINK_URL,
				CASE WHEN (SELECT X.ATRIBUT_ID FROM ".$this->db.".jabatan_eselon_atribut X WHERE X.ESELON_ID = '".$reqEselonId."' AND X.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."' AND A.ATRIBUT_ID = X.ATRIBUT_ID) = A.ATRIBUT_ID
				THEN '1' ELSE '' END KONDISI_STATUS
				FROM ".$this->db.".atribut A
				WHERE 1=1
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//$str .= $statement."  AND A.TAHUN = '2016' ".$sOrder;
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPejabatSatuanKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT B.NAMA, B.KODE_ESELON, A.NAMA_UNKER
			FROM ".$this->db.".rb_ref_unker A
			INNER JOIN ".$this->db.".rb_data_pegawai B ON A.KODE_UNKER = B.KODE_UNKER AND A.ESELON = B.KODE_ESELON
			WHERE 1=1 AND B.STATUS_PEG IN ('0')
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
	
	function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA, A.NIK
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, case A.status_pegawai_id when 1 then 'CPNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
 			, B.KODE NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR
 			, A.SATKER_ID, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN
			, SUBSTR(CAST(A.LAST_ESELON_ID AS CHAR),1,1) ESELON_PARENT
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			WHERE 1=1
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringLhkpnPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			     B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
				 , CASE WHEN COALESCE(K.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END STATUS_KANDIDAT, AMBIL_UMUR(A.TGL_LAHIR) UMUR
				 , CASE WHEN COALESCE(X.MASA_LAPOR,0) < 2 THEN 1 ELSE 2 END KONDISI_LAPOR
			FROM ".$this->db.".rb_data_pegawai A 
			LEFT JOIN (SELECT pegawai_id_pensiun, COUNT(1) JUMLAH_DATA FROM pegawai_kandidat WHERE 1=1 GROUP BY pegawai_id_pensiun) K ON A.IDPEG = K.pegawai_id_pensiun
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			LEFT JOIN
			(
				SELECT PEGAWAI_ID, MAX(TANGGAL_LAPOR) TANGGAL_LAPOR,
				CASE WHEN MAX(TANGGAL_LAPOR) + INTERVAL 2 YEAR < NOW() THEN 1 ELSE 2 END MASA_LAPOR
				FROM penilaian_lhkpn 
				GROUP BY PEGAWAI_ID, NOW()
			) X ON A.IDPEG = X.PEGAWAI_ID
			, ".$this->db.".rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringSkpPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $tahun="", $orderby='')
	{
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			     B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
				 , CASE WHEN COALESCE(K.JUMLAH_DATA,0) > 0 THEN 1 ELSE 0 END STATUS_KANDIDAT, AMBIL_UMUR(A.TGL_LAHIR) UMUR
				 , CASE WHEN COALESCE(X.JUMLAH_DATA,0) = 0 THEN 1 ELSE 2 END KONDISI_LAPOR
			FROM ".$this->db.".rb_data_pegawai A 
			LEFT JOIN (SELECT pegawai_id_pensiun, COUNT(1) JUMLAH_DATA FROM pegawai_kandidat WHERE 1=1 GROUP BY pegawai_id_pensiun) K ON A.IDPEG = K.pegawai_id_pensiun
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			LEFT JOIN
			(
				SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID
				FROM skp_kkp WHERE TAHUN = ".$tahun."
				GROUP BY PEGAWAI_ID
			) X ON A.IDPEG = X.PEGAWAI_ID
			, ".$this->db.".rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringKandidatPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $tahun="2015", $orderby='ORDER BY N.JPM DESC, N.IKK DESC')
	{
		$str = "
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, case A.status_pegawai_id when 1 then 'PNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
 			, B.KODE NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR
 			, A.SATKER_ID, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN
			, SUBSTR(CAST(A.LAST_ESELON_ID AS CHAR),1,1) ESELON_PARENT
			, '' JPM, '' IKK
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			WHERE 1=1
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringKandidatPegawaiBak1($paramsArray=array(),$limit=-1,$from=-1, $statement='', $tahun="2015", $orderby='ORDER BY N.JPM DESC, N.IKK DESC')
	{
		$str = "
			SELECT
			A.IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
			, N.JPM, N.IKK
			FROM ".$this->db.".rb_data_pegawai A
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON
			INNER JOIN
			(
				SELECT
					COALESCE(X.NILAI_IKK,0) NILAI_IKK, COALESCE(ROUND(100 * X.NILAI_IKK / COUNT(1),2),0) IKK
					, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
					, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) JPM
					, D.PEGAWAI_ID IDPEG
				FROM  atribut B 
				INNER JOIN penilaian_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
				INNER JOIN penilaian D ON C.PENILAIAN_ID = D.PENILAIAN_ID
				INNER JOIN ".$this->db.".rb_ref_unker A ON D.SATKER_TES_ID = A.KODE_UNKER
				INNER JOIN ".$this->db.".rb_data_pegawai AA ON D.SATKER_TES_ID = AA.KODE_UNKER AND AA.IDPEG = D.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT
						SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
						, D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
					FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A  
					WHERE 1=1				
					AND B.ATRIBUT_ID = C.ATRIBUT_ID 
					AND C.PENILAIAN_ID = D.PENILAIAN_ID
					AND D.SATKER_TES_ID = A.KODE_UNKER
					AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
				  AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
					GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
				) X ON X.PENILAIAN_ID = D.PENILAIAN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
				WHERE 1=1
				AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$tahun."'
			    GROUP BY D.PEGAWAI_ID
			) N ON A.IDPEG = N.IDPEG
			, ".$this->db.".rb_ref_unker D
			WHERE 1=1 AND A.KODE_UNKER = D.KODE_UNKER
			AND A.STATUS_PEG IN ('0') AND SUBSTR(A.KODE_UNKER, 1, 0) = '' AND A.KODE_ESELON NOT IN (99, 88)
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPegawaiCetakExcel($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		$str = "
				select a.nama_pegawai, 
					max(case when atribut_id = '0407' then coalesce(nilai,0) end) 'kecerdasan_umum', 
					max(case when atribut_id = '0501' then coalesce(nilai,0) end) 'sistematika_kerja',
					max(case when atribut_id = '0506' then coalesce(nilai,0) end) 'motivasi_kerja',
					max(case when atribut_id = '0507' then coalesce(nilai,0) end) 'komitmen_kerja',
					max(case when atribut_id = '0508' then coalesce(nilai,0) end) 'peran_kepemimpinan',
					max(case when atribut_id = '0509' then coalesce(nilai,0) end) 'kerjasama',
					max(case when atribut_id = '0802' then coalesce(nilai,0) end) 'stabilitas_emosi',
					max(case when atribut_id = '0804' then coalesce(nilai,0) end) 'fungsi_kognisi',
					max(case when atribut_id = '0805' then coalesce(nilai,0) end) 'peran_sosial',
					max(case when atribut_id = '0115' then coalesce(nilai,0) end) 'monitoring_evaluasi_kebijakan',
					max(case when atribut_id = '0202' then coalesce(nilai,0) end) 'inovasi',
					max(case when atribut_id = '0204' then coalesce(nilai,0) end) 'berpikir_konseptual2',
					max(case when atribut_id = '0205' then coalesce(nilai,0) end) 'adaptasi_terhadap_perubahan',
					max(case when atribut_id = '0206' then coalesce(nilai,0) end) 'integritas',
					max(case when atribut_id = '0208' then coalesce(nilai,0) end) 'komitmen_terhadap_organisasi',
					max(case when atribut_id = '0213' then coalesce(nilai,0) end) 'kepemimpinan',
					max(case when atribut_id = '0215' then coalesce(nilai,0) end) 'membangun_networking',
					max(case when atribut_id = '0216' then coalesce(nilai,0) end) 'negosiasi',
					max(case when atribut_id = '0221' then coalesce(nilai,0) end) 'pengambilan_keputusan',
					max(case when atribut_id = '0224' then coalesce(nilai,0) end) 'berorientasi_kualitas',
					max(case when atribut_id = '0225' then coalesce(nilai,0) end) 'manajemen_konflik'
					from(
					select p.pegawai_id, p.nama as nama_pegawai, a.tanggal_tes, c.atribut_id, c.nama atribut, b.nilai, c.aspek_id
					from db_simpeg_kemendagri.pegawai p   
					left join penilaian a on p.pegawai_id = a.pegawai_id
					left join penilaian_detil b on a.penilaian_id = b.penilaian_id 
					left join (select     a.formula_atribut_id, b.atribut_id, c.atribut_id_parent, c.nama, c.ATRIBUT_ID ATRIBUT_GROUP, c.aspek_id 
					from formula_atribut a 
					inner join level_atribut b on a.level_id = b.level_id
					inner join atribut c on b.atribut_id = c.atribut_id 
					where 1=1
					and formula_eselon_id = 4 
					order by c.aspek_id, atribut_id) c on b.atribut_id = c.atribut_id 
					where 1=1 
					order by c.aspek_id, c.atribut_id) a 
					group by a.nama_pegawai
	 	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsPegawaiCetakExcel($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT
			from(
			select a.nama_pegawai, 
					max(case when atribut_id = '0407' then coalesce(nilai,0) end) 'kecerdasan_umum', 
					max(case when atribut_id = '0501' then coalesce(nilai,0) end) 'sistematika_kerja',
					max(case when atribut_id = '0506' then coalesce(nilai,0) end) 'motivasi_kerja',
					max(case when atribut_id = '0507' then coalesce(nilai,0) end) 'komitmen_kerja',
					max(case when atribut_id = '0508' then coalesce(nilai,0) end) 'peran_kepemimpinan',
					max(case when atribut_id = '0509' then coalesce(nilai,0) end) 'kerjasama',
					max(case when atribut_id = '0802' then coalesce(nilai,0) end) 'stabilitas_emosi',
					max(case when atribut_id = '0804' then coalesce(nilai,0) end) 'fungsi_kognisi',
					max(case when atribut_id = '0805' then coalesce(nilai,0) end) 'peran_sosial',
					max(case when atribut_id = '0115' then coalesce(nilai,0) end) 'monitoring_evaluasi_kebijakan',
					max(case when atribut_id = '0202' then coalesce(nilai,0) end) 'inovasi',
					max(case when atribut_id = '0204' then coalesce(nilai,0) end) 'berpikir_konseptual2',
					max(case when atribut_id = '0205' then coalesce(nilai,0) end) 'adaptasi_terhadap_perubahan',
					max(case when atribut_id = '0206' then coalesce(nilai,0) end) 'integritas',
					max(case when atribut_id = '0208' then coalesce(nilai,0) end) 'komitmen_terhadap_organisasi',
					max(case when atribut_id = '0213' then coalesce(nilai,0) end) 'kepemimpinan',
					max(case when atribut_id = '0215' then coalesce(nilai,0) end) 'membangun_networking',
					max(case when atribut_id = '0216' then coalesce(nilai,0) end) 'negosiasi',
					max(case when atribut_id = '0221' then coalesce(nilai,0) end) 'pengambilan_keputusan',
					max(case when atribut_id = '0224' then coalesce(nilai,0) end) 'berorientasi_kualitas',
					max(case when atribut_id = '0225' then coalesce(nilai,0) end) 'manajemen_konflik'
					from(
					select p.pegawai_id, p.nama as nama_pegawai, a.tanggal_tes, c.atribut_id, c.nama atribut, b.nilai, c.aspek_id
					from db_simpeg_kemendagri.pegawai p   
					left join penilaian a on p.pegawai_id = a.pegawai_id
					left join penilaian_detil b on a.penilaian_id = b.penilaian_id 
					left join (select     a.formula_atribut_id, b.atribut_id, c.atribut_id_parent, c.nama, c.ATRIBUT_ID ATRIBUT_GROUP, c.aspek_id 
					from formula_atribut a 
					inner join level_atribut b on a.level_id = b.level_id
					inner join atribut c on b.atribut_id = c.atribut_id 
					where 1=1
					and formula_eselon_id = 4 
					order by c.aspek_id, atribut_id) c on b.atribut_id = c.atribut_id 
					where 1=1 
					order by c.aspek_id, c.atribut_id) a 
					group by a.nama_pegawai) a
			"; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function selectByParamsMonitoringKandidatPegawaiBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $tahun="2015", $orderby='ORDER BY N.JPM DESC, N.IKK DESC')
	{
		$str = "
			SELECT
			A.IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
			B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER
			, N.JPM, N.IKK
			FROM ".$this->db.".rb_data_pegawai A
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON
			INNER JOIN
			(
				SELECT IDPEG, SUM(COALESCE(JPM,0) * 100) / COUNT(1) JPM, SUM(COALESCE(IKK,0) * 100) / COUNT(1) IKK
				FROM ".$this->db.".rb_data_pegawai A
				INNER JOIN
				(
				SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				) P ON A.IDPEG = P.PEGAWAI_ID
				LEFT JOIN
				(
				SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK1, ASPEK_ID FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' AND ASPEK_ID = '1'  and SUBSTR(SATKER_TES_ID, 1, 0) = ''
				GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
				) R ON P.ASPEK_ID = R.ASPEK_ID
				LEFT JOIN
				(
				SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK2, ASPEK_ID FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' AND ASPEK_ID = '2'  and SUBSTR(SATKER_TES_ID, 1, 0) = ''
				GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
				) S ON P.ASPEK_ID = S.ASPEK_ID 
				WHERE 1=1 AND A.STATUS_PEG IN ('0')
				and SUBSTR(A.KODE_UNKER, 1, 0) = ''  
				AND A.KODE_ESELON NOT IN (99, 88)
				GROUP BY IDPEG
			) N ON A.IDPEG = N.IDPEG
			, ".$this->db.".rb_ref_unker D
			WHERE 1=1 AND A.KODE_UNKER = D.KODE_UNKER
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
	
	function selectByParamsTotalJpmIkk($paramsArray=array(),$limit=-1,$from=-1, $statement='', $tahun="2015", $orderby='ORDER BY N.JPM DESC, N.IKK DESC')
	{
		$str = "
			SELECT
			ROUND(SUM(COALESCE(N.JPM,0)) / COUNT(1),2) JPM, ROUND(SUM(COALESCE(N.IKK,0)) / COUNT(1),2) IKK
			FROM ".$this->db.".rb_data_pegawai A
			INNER JOIN
			(
				SELECT IDPEG, SUM(COALESCE(JPM,0) * 100) / COUNT(1) JPM, SUM(COALESCE(IKK,0) * 100) / COUNT(1) IKK
				FROM ".$this->db.".rb_data_pegawai A
				INNER JOIN
				(
				SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				) P ON A.IDPEG = P.PEGAWAI_ID
				WHERE 1=1 AND A.STATUS_PEG IN ('0')
				and SUBSTR(A.KODE_UNKER, 1, 0) = ''
				GROUP BY IDPEG
			) N ON A.IDPEG = N.IDPEG
			, ".$this->db.".rb_ref_unker D
			WHERE 1=1 AND A.KODE_UNKER = D.KODE_UNKER
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
	
	function selectByParamsMonitoringPilihKandidatPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $tahun="2015", $orderby='ORDER BY N.JPM DESC, N.IKK DESC')
	{
		$str = "
			SELECT
			NIP_LAMA, NIP_BARU, A.NAMA, A.JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEGAWAI_ID WHEN 2 THEN 'PNS' ELSE 'CPNS' END STATUS,
			B.kode NAMA_GOL, A.last_tmt_pangkat TMT_GOL_AKHIR, C.nama NAMA_ESELON, a.last_jabatan NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA SATKER, N.JPM, N.IKK, K.PEGAWAI_ID_PENSIUN, K.PEGAWAI_ID_PENGGANTI
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat B ON A.last_pangkat_id = B.pangkat_id
			LEFT JOIN simpeg.eselon C ON A.last_eselon_id = C.eselon_id
			INNER JOIN
			(
				SELECT pegawai_id, SUM(COALESCE(JPM,0) * 100) / COUNT(1) JPM, SUM(COALESCE(IKK,0) * 100) / COUNT(1) IKK
				FROM simpeg.pegawai A
				INNER JOIN
				(
				SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				) P ON A.pegawai_id = P.PEGAWAI_ID
				LEFT JOIN
				(
				SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK1, ASPEK_ID FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' AND ASPEK_ID = '1'  and SUBSTR(SATKER_TES_ID, 1, 0) = ''
				GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
				) R ON P.ASPEK_ID = R.ASPEK_ID
				LEFT JOIN
				(
				SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK2, ASPEK_ID FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' AND ASPEK_ID = '2'  and SUBSTR(SATKER_TES_ID, 1, 0) = ''
				GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
				) S ON P.ASPEK_ID = S.ASPEK_ID 
				WHERE 1=1 AND A.STATUS_PEG IN ('0')
				and SUBSTR(A.KODE_UNKER, 1, 0) = ''  
				AND A.KODE_ESELON NOT IN (99, 88)
				GROUP BY pegawai_id
			) N ON A.pegawai_id = N.pegawai_id
			INNER JOIN pegawai_kandidat K ON A.IDPEG = K.pegawai_id_pengganti
			INNER JOIN simpeg.satker D ON A.satker_id = D.satker_id
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
	
	function selectByParamsMonitoringEselon($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby="ORDER BY SUBSTRING(A.ESELON_ID,1,1) ASC")
	{
		$str = "
			SELECT SUBSTRING(A.ESELON_ID,1,1) KODE_ESELON, SUBSTRING_INDEX(A.NAMA,'.',1) NAMA_ESELON
			FROM ".$this->db.".eselon A
			WHERE 1=1
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY SUBSTRING(A.ESELON_ID,1,1), SUBSTRING_INDEX(A.NAMA,'.',1) ".$orderby;
		$this->query = $str;
		//echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby="ORDER BY A.PANGKAT_ID ASC")
	{
		$str = "
			SELECT B.NAMA PANGKAT_NAMA, A.TMT_PANGKAT, A.MK_TAHUN MASA_KERJA_TAHUN, A.MK_BULAN MASA_KERJA_BULAN
			FROM ".$this->db.".riwayat_pangkat A
			INNER JOIN ".$this->db.".pangkat B ON A.PANGKAT_ID = B.PANGKAT_ID
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
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, case A.status_pegawai_id when 1 then 'PNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
 			, B.KODE NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR
 			, A.SATKER_ID, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN
			, SUBSTR(CAST(A.LAST_ESELON_ID AS CHAR),1,1) ESELON_PARENT
			, P.JADWAL_TES_ID, P.FORMULA_NAMA
			, P.JT_TANGGAL_TES, P.JT_ACARA, P.JT_KETERANGAN
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			INNER JOIN
			(
				SELECT A.PEGAWAI_ID, A.JADWAL_TES_ID, B.FORMULA_ID, B.KETERANGAN FORMULA_NAMA
				, B.JT_TANGGAL_TES, B.JT_ACARA, B.JT_KETERANGAN
				FROM penilaian A
				INNER JOIN
				(
					SELECT JADWAL_TES_ID, B.FORMULA_ID, B.KETERANGAN
					, A.TANGGAL_TES JT_TANGGAL_TES, A.ACARA JT_ACARA, A.KETERANGAN JT_KETERANGAN
					FROM jadwal_tes A
					INNER JOIN
					(
						SELECT A.FORMULA_ESELON_ID, A.FORMULA_ID, B.KETERANGAN
						FROM formula_eselon A
						INNER JOIN formula_assesment B ON A.FORMULA_ID = B.FORMULA_ID
					) B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
				) B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
				WHERE 1=1
				AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
				GROUP BY A.PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY'), A.JADWAL_TES_ID, B.FORMULA_ID, B.KETERANGAN
				, B.JT_TANGGAL_TES, B.JT_ACARA, B.JT_KETERANGAN
			) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
			WHERE 1=1
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		// echo $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringRePenilaianPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun='2015')
	{
		$str = "
			SELECT 
			S.KODE_UNKER,
			A.ID IDPEG, '' NIP_LAMA, A.NIP NIP_BARU, A.NAME NAMA
			, '' GELAR_DEPAN, '' GELAR_BELAKANG, B.SVALUE JENIS_KELAMIN, '' TEMPAT_LAHIR, '' TGL_LAHIR, '' STATUS
			, C.SVALUE NAMA_GOL, '' TMT_GOL_AKHIR, D.SVALUE NAMA_ESELON, A.POSITION NAMA_JAB_STRUKTURAL, '' TELP
			, '' STATUS_KANDIDAT, '' UMUR
			, (SELECT DS.NAME FROM ".$this->db.".division DS WHERE DS.ID = SUBSTRING_INDEX(S.KODE_UNKER, '-', 1) LIMIT 1) SATKER
			, CASE REPLACE(D.SVALUE, ' ','')
			WHEN 'I.A' THEN '1' WHEN 'I.B' THEN '1' WHEN 'II.A' THEN '2' WHEN 'II.B' THEN '2'
			WHEN 'III.A' THEN '3' WHEN 'III.B' THEN '3' WHEN 'IV.A' THEN '4' WHEN 'IV.B' THEN '4' ELSE '99' END ESELON_PENILAIAN
			FROM ".$this->db.".user A
			LEFT JOIN ".$this->db.".sysparam B ON A.GENDER = B.SKEY AND B.SGROUP = 'GENDER'
			LEFT JOIN ".$this->db.".sysparam C ON A.RANK = C.SKEY AND C.SGROUP = 'RANK'
			LEFT JOIN ".$this->db.".sysparam D ON A.ESSELON = D.SKEY AND D.SGROUP = 'ESSELON'
			LEFT JOIN
			(
				SELECT
				A.ID, A.KODE_UNKER
				FROM
				(
					SELECT 
					A.ID,
					GetAncestry
					(
						(
							CASE A.SUBBAG_ID WHEN '0' THEN 
							(
								CASE A.SUBDIT_ID WHEN '0' THEN 
								(
									CASE A.DITJEN_ID WHEN '0' THEN 
									(
										CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END
									)
									ELSE A.DITJEN_ID 
									END
								)
								ELSE A.SUBDIT_ID 
								END
							)
							ELSE A.SUBBAG_ID 
							END
						) 
					) KODE_UNKER
					FROM ".$this->db.".user A
					WHERE 1=1
				) A
				WHERE 1=1
			) S ON S.ID = A.ID
			INNER JOIN
			(
				SELECT PEGAWAI_ID FROM penilaian 
				WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') + 2 = '".$reqTahun."' GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY') + 2
			) P ON A.ID = P.PEGAWAI_ID
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
	
	function selectByParamsMonitoringPenilaianCetak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $statement1='', $orderby='ORDER BY ASPEK_ID ASC, JPM DESC, IKK DESC ', $reqTahun='2015')
	{
		$str = "
			SELECT IDPEG, NIP_LAMA, NIP_BARU, A.NAMA, GELAR_DEPAN, GELAR_BELAKANG, '' JENIS_KELAMIN, TEMPAT_LAHIR, CASE WHEN TO_CHAR(A.TGL_LAHIR, 'YYYY') > 0 THEN TGL_LAHIR ELSE '' END TGL_LAHIR, CASE STATUS_PEG WHEN 0 THEN 'PNS' ELSE 'CPNS' END STATUS,
					 B.NAMA_GOL, TMT_GOL_AKHIR, C.NAMA_ESELON, NAMA_JAB_STRUKTURAL, A.TELP, D.NAMA_UNKER SATKER,  A.KODE_UNKER, JPM, IKK, P.ASPEK_ID, COALESCE(JUMLAH_ASPEK1, 0)JUMLAH_ASPEK1, COALESCE(JUMLAH_ASPEK2,0)JUMLAH_ASPEK2
			FROM ".$this->db.".rb_data_pegawai A
			LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
			LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
			INNER JOIN
			(
			SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) P ON A.IDPEG = P.PEGAWAI_ID
			LEFT JOIN
			(
			SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK1, ASPEK_ID FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID = '1' ".$statement1."
			GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
			) R ON P.ASPEK_ID = R.ASPEK_ID
			LEFT JOIN
			(
			SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK2, ASPEK_ID FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID = '2' ".$statement1."
			GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
			) S ON P.ASPEK_ID = S.ASPEK_ID, dbsimpeg.rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTalentPoolTes($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		//COALESCE(X.NILAI_IKK,0) NILAI_IKK, COALESCE(ROUND(100 * X.NILAI_IKK / COUNT(1),2),0) IKK
		//AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
		//AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
		$str = "
			SELECT A.NAMA, CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END NILAI_X, 
			CASE WHEN COALESCE(Y.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(Y.IKK,0) * 100 END NILAI_Y
			FROM ".$this->db.".rb_data_pegawai A
			LEFT JOIN
			(
				SELECT
					COALESCE(X.NILAI_IKK,0) NILAI_IKK, CASE WHEN (COALESCE(X.NILAI_IKK,0) * 100) < 0 THEN 0 WHEN (COALESCE(X.NILAI_IKK,0) * 100) > 100 THEN 100 ELSE (COALESCE(X.NILAI_IKK,0) * 100) END IKK
					, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
					, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) NILAI_JPM_PERSEN
					, D.PEGAWAI_ID
				FROM  atribut B 
				INNER JOIN penilaian_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
				INNER JOIN penilaian D ON C.PENILAIAN_ID = D.PENILAIAN_ID
				INNER JOIN ".$this->db.".rb_ref_unker A ON D.SATKER_TES_ID = A.KODE_UNKER
				INNER JOIN ".$this->db.".rb_data_pegawai AA ON D.SATKER_TES_ID = AA.KODE_UNKER AND AA.IDPEG = D.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT
						SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
						, D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
					FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A  
					WHERE 1=1				
					AND B.ATRIBUT_ID = C.ATRIBUT_ID 
					AND C.PENILAIAN_ID = D.PENILAIAN_ID
					AND D.SATKER_TES_ID = A.KODE_UNKER
					GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
				) X ON X.PENILAIAN_ID = D.PENILAIAN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
				WHERE 1=1 AND D.ASPEK_ID = 1 AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
				GROUP BY D.PEGAWAI_ID
			) X ON A.IDPEG = X.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
					COALESCE(X.NILAI_IKK,0) NILAI_IKK, CASE WHEN (COALESCE(X.NILAI_IKK,0) * 100) < 0 THEN 0 WHEN (COALESCE(X.NILAI_IKK,0) * 100) > 100 THEN 100 ELSE (COALESCE(X.NILAI_IKK,0) * 100) END IKK
					, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
					, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) NILAI_JPM_PERSEN
					, D.PEGAWAI_ID
				FROM  atribut B 
				INNER JOIN penilaian_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
				INNER JOIN penilaian D ON C.PENILAIAN_ID = D.PENILAIAN_ID
				INNER JOIN ".$this->db.".rb_ref_unker A ON D.SATKER_TES_ID = A.KODE_UNKER
				INNER JOIN ".$this->db.".rb_data_pegawai AA ON D.SATKER_TES_ID = AA.KODE_UNKER AND AA.IDPEG = D.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT
						SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
						, D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
					FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A  
					WHERE 1=1				
					AND B.ATRIBUT_ID = C.ATRIBUT_ID 
					AND C.PENILAIAN_ID = D.PENILAIAN_ID
					AND D.SATKER_TES_ID = A.KODE_UNKER
					GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
				) X ON X.PENILAIAN_ID = D.PENILAIAN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
				WHERE 1=1 AND D.ASPEK_ID = 2 AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
				GROUP BY D.PEGAWAI_ID
			) Y ON A.IDPEG = Y.PEGAWAI_ID
			, ".$this->db.".rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
			AND ( CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(Y.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(Y.IKK,0) * 100 END) > 0
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTalentPool($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		$str = "
			SELECT A.NAMA, CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END NILAI_X, 
			CASE WHEN COALESCE(Y.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(Y.IKK,0) * 100 END NILAI_Y
			FROM ".$this->db.".rb_data_pegawai A
			LEFT JOIN
			(
			SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID = 1 GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) X ON A.IDPEG = X.PEGAWAI_ID
			LEFT JOIN
			(
			SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID = 2 GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) Y ON A.IDPEG = Y.PEGAWAI_ID
			, dbsimpeg.rb_ref_unker D 
			WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
			AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(Y.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(Y.IKK,0) * 100 END) > 0
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTalentPoolPotensiKompetensi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun='2015')
	{
		$str = "
			SELECT A.NAMA, (CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END)  NILAI_X
			, (CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END)  NILAI_Y
			FROM ".$this->db.".pegawai A
			LEFT JOIN
			(
				SELECT
					D.PEGAWAI_ID, CASE WHEN COALESCE(D.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(D.JPM,0) * 100 END NILAI
				FROM penilaian D
				INNER JOIN 
				(
					SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
					FROM ".$this->db.".pegawai A
				) AA ON AA.ID = D.PEGAWAI_ID
				WHERE 1=1 AND D.ASPEK_ID = '1'
				AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT
					D.PEGAWAI_ID, CASE WHEN COALESCE(D.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(D.JPM,0) * 100 END NILAI
				FROM penilaian D
				INNER JOIN 
				(
					SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
					FROM ".$this->db.".pegawai A
				) AA ON AA.ID = D.PEGAWAI_ID
				WHERE 1=1 AND D.ASPEK_ID = '2'
				AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
			) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
			WHERE 1=1
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTalentPoolNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		$str = "
			SELECT A.NAMA, (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END)  NILAI_X, 
			E.nilai NILAI_Y
			FROM ".$this->db.".rb_data_pegawai A,
			(
			SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
			WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
			) X,
			skp_kkp E
			, ".$this->db.".rb_ref_unker D 
			WHERE  A.IDPEG = X.PEGAWAI_ID 
				  AND A.IDPEG = E.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
				  AND A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
			AND (CASE WHEN COALESCE(X.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(X.JPM,0) * 100 END + CASE WHEN COALESCE(E.nilai,0) > 100 THEN 100 ELSE COALESCE(E.nilai,0) * 100 END) > 0
	 		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPool($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Potential Leter' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Performance Leter' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							SELECT 
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.IKK,0) * 100) >= 0 AND (COALESCE(X.IKK,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.IKK,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.IKK,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (COALESCE(Y.IKK,0) * 100) >= 0 AND (COALESCE(Y.IKK,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(Y.IKK,0) * 100) > KD.KUADRAN_1 AND (COALESCE(Y.IKK,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.IKK,0) * 100) >= 0 AND (COALESCE(X.IKK,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.IKK,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.IKK,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.IKK,0) * 100 NILAI_X,
							CASE 
							WHEN (COALESCE(Y.IKK,0) * 100) >= 0 AND (COALESCE(Y.IKK,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(Y.IKK,0) * 100) > KD.KUADRAN_1 AND (COALESCE(Y.IKK,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y,
							COALESCE(Y.IKK,0) * 100 NILAI_Y, 
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".rb_data_pegawai A
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID = 1 GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
							) X ON A.IDPEG = X.PEGAWAI_ID
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID = 2 GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
							) Y ON A.IDPEG = Y.PEGAWAI_ID
							, ".$this->db.".rb_ref_unker D
							, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
							WHERE A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
							AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(Y.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(Y.IKK,0) * 100 END) > 0
							".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolMonitoring16082016($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY B.IKK DESC', $reqTahun='2015', $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM, 
			CASE WHEN COALESCE(B.JPM,0) * 100 > 100 THEN 0 ELSE COALESCE(B.JPM,0) * 100 END JPM_TOTAL,
			CASE WHEN COALESCE(B.IKK,0) * 100 > 100 THEN 0 ELSE COALESCE(B.IKK,0) * 100 END IKK_TOTAL,
			A.*
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Potential Leter' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Performance Leter' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							SELECT 
							X.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
							X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, E.NILAI,
							X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM,
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (E.nilai >= 0) AND E.nilai <= KD.KUADRAN_1 THEN '1'
								WHEN (E.nilai > KD.KUADRAN_1) AND E.nilai <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.JPM,0) * 100 NILAI_X,
							CASE 
							WHEN (COALESCE(E.nilai,0) >= 0) AND COALESCE(E.nilai,0) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(E.nilai,0) > KD.KUADRAN_1) AND COALESCE(E.nilai,0) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y,
							COALESCE(E.nilai,0) NILAI_Y, 
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".rb_data_pegawai A
							,
							(
							SELECT A.PEGAWAI_ID, COALESCE((sum(A.JPM) / 2),0) JPM, COALESCE((sum(A.IKK)/2),0) IKK
							, COALESCE(P.JPM,0) PSIKOLOGI_JPM, COALESCE(P.IKK,0) PSIKOLOGI_IKK
							, COALESCE(K.JPM,0) KOMPETEN_JPM, COALESCE(K.IKK,0) KOMPETEN_IKK
							FROM penilaian A
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) 
							) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) 
							) K ON A.PEGAWAI_ID = K.PEGAWAI_ID
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID IN (1,2)
							GROUP BY A.PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
							) X, 
						    skp_kkp E
							, ".$this->db.".rb_ref_unker D
							, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
							WHERE  A.IDPEG = X.PEGAWAI_ID 
								AND A.IDPEG = E.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
								AND A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
							AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(E.nilai,0) > 100 THEN 100 ELSE COALESCE(E.nilai,0) END) > 0
							".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolMonitoring18082016($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY B.IKK DESC', $reqTahun='2015', $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM, 
			CASE WHEN COALESCE(B.JPM,0) * 100 > 100 THEN 0 ELSE COALESCE(B.JPM,0) * 100 END JPM_TOTAL,
			CASE WHEN COALESCE(B.IKK,0) * 100 > 100 THEN 0 ELSE COALESCE(B.IKK,0) * 100 END IKK_TOTAL,
			A.*
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 11 ID_KUADRAN, 'Mis Fit' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Concern' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Solid Contributor' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Solid Contributor' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							SELECT 
							X.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
							X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, E.NILAI,
							X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM,
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (E.nilai >= 0) AND E.nilai <= KD.KUADRAN_1 THEN '1'
								WHEN (E.nilai > KD.KUADRAN_1) AND E.nilai <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.JPM,0) * 100 NILAI_X,
							CASE 
							WHEN (COALESCE(E.nilai,0) >= 0) AND COALESCE(E.nilai,0) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(E.nilai,0) > KD.KUADRAN_1) AND COALESCE(E.nilai,0) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y,
							COALESCE(E.nilai,0) NILAI_Y, 
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".rb_data_pegawai A
							,
							(
							SELECT A.PEGAWAI_ID, COALESCE((sum(A.JPM) / 2),0) JPM, COALESCE((sum(A.IKK)/2),0) IKK
							, COALESCE(P.JPM,0) PSIKOLOGI_JPM, COALESCE(P.IKK,0) PSIKOLOGI_IKK
							, COALESCE(K.JPM,0) KOMPETEN_JPM, COALESCE(K.IKK,0) KOMPETEN_IKK
							FROM penilaian A
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) 
							) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) 
							) K ON A.PEGAWAI_ID = K.PEGAWAI_ID
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID IN (1,2)
							GROUP BY A.PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
							) X, 
						    skp_kkp E
							, ".$this->db.".rb_ref_unker D
							, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
							WHERE  A.IDPEG = X.PEGAWAI_ID 
								AND A.IDPEG = E.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
								AND A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
							AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(E.nilai,0) > 100 THEN 100 ELSE COALESCE(E.nilai,0) END) > 0
							".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolPotensiKompetensiMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun='2015', $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM,
			A.*
			FROM
			(
				SELECT * FROM
				(
					SELECT * FROM P_KUADRAN_INFO()
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
				A.NILAI_Y KOMPETEN_IKK, A.NILAI_X PSIKOLOGI_IKK, '' IKK, '' NILAI,
				'' KOMPETEN_JPM, '' PSIKOLOGI_JPM, '' JPM,
				'' JPM_TOTAL, '' IKK_TOTAL,
				A.*
				FROM
				(
					SELECT A.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
					(CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) NILAI_X,
					(CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) NILAI_Y,
					CAST
							(
								CASE WHEN
								COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN 
								COALESCE(X.NILAI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X2
								THEN '2'
								ELSE '3' END
								||
								CASE 
								WHEN (COALESCE(Y.NILAI,0) >= 0) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
							AS INTEGER) KUADRAN_PEGAWAI
					FROM ".$this->db.".pegawai A
					INNER JOIN
					(
						SELECT
							D.PEGAWAI_ID, CASE WHEN COALESCE(D.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(D.JPM,0) * 100 END NILAI
						FROM penilaian D
						INNER JOIN 
						(
							SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
							FROM ".$this->db.".pegawai A
						) AA ON AA.ID = D.PEGAWAI_ID
						WHERE 1=1 AND D.ASPEK_ID = '2'
						AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
					INNER JOIN
					(
						SELECT PEGAWAI_ID, NILAI_SKP NILAI
						FROM
						(
							SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
							FROM
							(
								SELECT 
								ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
								, PEGAWAI_ID, TAHUN, NILAI_SKP
								FROM
								(
									SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.pegawai A
									UNION ALL
									SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.riwayat_skp A 
									WHERE SKP_TAHUN = '".$reqTahun."'
								) A
							) A
							WHERE NOMOR = 1
						) A
					) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
						, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_X
					WHERE 1=1
					".$statement."
				) A
			) B ON CAST(B.KUADRAN_PEGAWAI_ID AS INTEGER) = A.ID_KUADRAN 
			WHERE 1=1
			AND B.PEGAWAI_ID IS NOT NULL 
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson." ORDER BY B.PSIKOLOGI_IKK DESC, B.KOMPETEN_IKK DESC";
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsMonitoringTableTalentPoolJPMMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun='2015', $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM,
			A.*
			FROM
			(
				SELECT * FROM
				(
					SELECT * FROM P_KUADRAN_INFOJPM()
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
				A.NILAI_Y KOMPETEN_IKK, A.NILAI_X PSIKOLOGI_IKK, '' IKK, '' NILAI,
				'' KOMPETEN_JPM, '' PSIKOLOGI_JPM, '' JPM,
				'' JPM_TOTAL, '' IKK_TOTAL,
				A.*
				FROM
				(
					SELECT A.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
					(CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) NILAI_X,
					(CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) NILAI_Y,
					CAST
							(
								CASE WHEN
								COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN 
								COALESCE(X.NILAI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X2
								THEN '2'
								ELSE '3' END
								||
								CASE 
								WHEN (COALESCE(Y.NILAI,0) >= 0) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
							AS INTEGER) KUADRAN_PEGAWAI
					FROM ".$this->db.".pegawai A
					INNER JOIN
					(
						SELECT
							D.PEGAWAI_ID, ROUND((SUM(COALESCE(D.JPM,0)) * 100) /2,2) NILAI, TO_CHAR(D.TANGGAL_TES, 'YYYY') TAHUN
						FROM penilaian D
						INNER JOIN 
						(
							SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
							FROM ".$this->db.".pegawai A
						) AA ON AA.ID = D.PEGAWAI_ID
						WHERE 1=1 AND D.ASPEK_ID in ('1','2')
						AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
						GROUP BY D.PEGAWAI_ID, TO_CHAR(D.TANGGAL_TES, 'YYYY')
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
					INNER JOIN
					(
						SELECT PEGAWAI_ID, NILAI_SKP NILAI
						FROM
						(
							SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
							FROM
							(
								SELECT 
								ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
								, PEGAWAI_ID, TAHUN, NILAI_SKP
								FROM
								(
									SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.pegawai A
									UNION ALL
									SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
									FROM simpeg.riwayat_skp A 
									WHERE SKP_TAHUN = '".$reqTahun."'
								) A
							) A
							WHERE NOMOR = 1
						) A
					) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
					, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_X
					WHERE 1=1
					".$statement."
				) A
			) B ON CAST(B.KUADRAN_PEGAWAI_ID AS INTEGER) = A.ID_KUADRAN 
			WHERE 1=1
			AND B.PEGAWAI_ID IS NOT NULL 
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson." ORDER BY B.PSIKOLOGI_IKK DESC, B.KOMPETEN_IKK DESC";
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY B.IKK DESC', $reqTahun='2015', $searcJson= "")
	{
		$str = "
			SELECT
			B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
			B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
			B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM, 
			CASE WHEN COALESCE(B.JPM,0) * 100 > 100 THEN 0 ELSE COALESCE(B.JPM,0) * 100 END JPM_TOTAL,
			CASE WHEN COALESCE(B.IKK,0) * 100 > 100 THEN 0 ELSE COALESCE(B.IKK,0) * 100 END IKK_TOTAL,
			A.*
			FROM
			(
				SELECT A.* FROM
				(
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Potential Later' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Performance Later' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
				A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
				A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
				A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							SELECT 
							X.PEGAWAI_ID, A.NAME NAMA, A.POSITION NAMA_JAB_STRUKTURAL,
							X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, '1' NILAI,
							X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM,
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								, '1'
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.JPM,0) * 100 NILAI_X
							, '1' KUADRAN_Y, '1' NILAI_Y,
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".user A
							,
							(
							SELECT A.PEGAWAI_ID, COALESCE((sum(A.JPM) / 2),0) JPM, COALESCE((sum(A.IKK)/2),0) IKK
							, COALESCE(P.JPM,0) PSIKOLOGI_JPM, COALESCE(P.IKK,0) PSIKOLOGI_IKK
							, COALESCE(K.JPM,0) KOMPETEN_JPM, COALESCE(K.IKK,0) KOMPETEN_IKK
							, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
							FROM penilaian A
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) 
							) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) 
							) K ON A.PEGAWAI_ID = K.PEGAWAI_ID
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID IN (1,2)
							GROUP BY A.PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
							) X
							LEFT JOIN
						(
							SELECT
							A.ID, A.KODE_UNKER
							FROM
							(
								SELECT 
								A.ID,
								GetAncestry
								(
									(
										CASE A.SUBBAG_ID WHEN '0' THEN 
										(
											CASE A.SUBDIT_ID WHEN '0' THEN 
											(
												CASE A.DITJEN_ID WHEN '0' THEN 
												(
													CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END
												)
												ELSE A.DITJEN_ID 
												END
											)
											ELSE A.SUBDIT_ID 
											END
										)
										ELSE A.SUBBAG_ID 
										END
									) 
								) KODE_UNKER
								FROM ".$this->db.".user A
								WHERE 1=1
							) A
							WHERE 1=1
						) S ON S.ID = X.PEGAWAI_ID
						, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
						WHERE  A.ID = X.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
						AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN 0 > 100 THEN 100 ELSE 0 END) > 0
						".$statement."
				) A
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL
		";
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $searcJson;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolNew16082016($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Potential Leter' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Performance Leter' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							SELECT 
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (E.nilai >= 0) AND E.nilai <= KD.KUADRAN_1 THEN '1'
								WHEN (E.nilai > KD.KUADRAN_1) AND E.nilai <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.JPM,0) * 100 NILAI_X,
							CASE 
							WHEN (COALESCE(E.nilai,0) >= 0) AND COALESCE(E.nilai,0) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(E.nilai,0) > KD.KUADRAN_1) AND COALESCE(E.nilai,0) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y,
							COALESCE(E.nilai,0) NILAI_Y, 
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".rb_data_pegawai A
							,
							(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
							) X, 
						    skp_kkp E
							, ".$this->db.".rb_ref_unker D
							, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
							WHERE  A.IDPEG = X.PEGAWAI_ID 
								AND A.IDPEG = E.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
								AND A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
							AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(E.nilai,0) > 100 THEN 100 ELSE COALESCE(E.nilai,0) END) > 0
							".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
		
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolNew18082016($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 'Mis Fit' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Concern' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Solid Contributor' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Solid Contributor' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
							SELECT 
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								,
								CASE 
								WHEN (E.nilai >= 0) AND E.nilai <= KD.KUADRAN_1 THEN '1'
								WHEN (E.nilai > KD.KUADRAN_1) AND E.nilai <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.JPM,0) * 100 NILAI_X,
							CASE 
							WHEN (COALESCE(E.nilai,0) >= 0) AND COALESCE(E.nilai,0) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(E.nilai,0) > KD.KUADRAN_1) AND COALESCE(E.nilai,0) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_Y,
							COALESCE(E.nilai,0) NILAI_Y, 
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".rb_data_pegawai A
							,
							(
							SELECT PEGAWAI_ID, (sum(JPM) / 2) JPM, (sum(IKK)/2) IKK FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1,2) GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
							) X, 
						    skp_kkp E
							, ".$this->db.".rb_ref_unker D
							, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
							WHERE  A.IDPEG = X.PEGAWAI_ID 
								AND A.IDPEG = E.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
								AND A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0')
							AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN COALESCE(E.nilai,0) > 100 THEN 100 ELSE COALESCE(E.nilai,0) END) > 0
							".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
		
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolPotensiKompetensi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='', $reqTahun='2015')
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'I' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'II' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'III' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Potential Leter' NAMA_KUADRAN, 'IV' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Performance Leter' NAMA_KUADRAN, 'V' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Development' NAMA_KUADRAN, 'VI' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VII' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'VIII' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
					UNION ALL
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'IX' KODE_KUADRAN, 
					'Dipromosikan dan dipertahankan</br>Masuk Kelompok Rencana Suksesi Instansi/Nasional</br>Penghargaan' KETERANGAN
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
					SELECT A.NAMA,
					(CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) NILAI_X,
					(CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) NILAI_Y,
					CONCAT
					(
						CASE 
						WHEN (CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) >= 0 AND (CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) <= KD.KUADRAN_1 THEN '1'
						WHEN COALESCE(X.NILAI,0) > KD.KUADRAN_1 AND COALESCE(X.NILAI,0) <= KD.KUADRAN_2 THEN '2'
						ELSE '3' END
						,
						CASE 
						WHEN (CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) >= 0 AND (CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) <= KD.KUADRAN_1 THEN '1'
						WHEN COALESCE(Y.NILAI,0) > KD.KUADRAN_1 AND COALESCE(Y.NILAI,0) <= KD.KUADRAN_2 THEN '2'
						ELSE '3' END
					) KUADRAN_PEGAWAI
					FROM ".$this->db.".pegawai A
					LEFT JOIN
					(
						SELECT
							D.PEGAWAI_ID, CASE WHEN COALESCE(D.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(D.JPM,0) * 100 END NILAI
						FROM penilaian D
						INNER JOIN 
						(
							SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
							FROM ".$this->db.".pegawai A
						) AA ON AA.ID = D.PEGAWAI_ID
						WHERE 1=1 AND D.ASPEK_ID = '1'
						AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
					) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
					LEFT JOIN
					(
						SELECT
							D.PEGAWAI_ID, CASE WHEN COALESCE(D.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(D.JPM,0) * 100 END NILAI
						FROM penilaian D
						INNER JOIN 
						(
							SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
							FROM ".$this->db.".pegawai A
						) AA ON AA.ID = D.PEGAWAI_ID
						WHERE 1=1 AND D.ASPEK_ID = '2'
						AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
					) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
					, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 69 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
					WHERE 1=1
					".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
		
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringTableTalentPoolNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY X.IKK DESC', $reqTahun='2015')
	{
		//echo "asdasd";exit;
		$str = "
			SELECT
			A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
			FROM
			(
				SELECT * FROM
				(
					SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 22 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 31 ID_KUADRAN, 'Potential Later' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 13 ID_KUADRAN, 'Performance Later' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
					UNION ALL
					SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
				) A
			) A
			LEFT JOIN
			(
				SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
				FROM
				(
					SELECT 
							X.PEGAWAI_ID, A.NAME NAMA, A.POSITION NAMA_JAB_STRUKTURAL,
							X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, '1' NILAI,
							X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM,
							CONCAT
							(
								CASE 
								WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
								WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
								ELSE '3' END
								, '1'
							) KUADRAN_PEGAWAI,
							CASE 
							WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
							WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
							ELSE '3' END KUADRAN_X,
							COALESCE(X.JPM,0) * 100 NILAI_X
							, '1' KUADRAN_Y, '1' NILAI_Y,
							KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
							FROM ".$this->db.".user A
							,
							(
							SELECT A.PEGAWAI_ID, COALESCE((sum(A.JPM) / 2),0) JPM, COALESCE((sum(A.IKK)/2),0) IKK
							, COALESCE(P.JPM,0) PSIKOLOGI_JPM, COALESCE(P.IKK,0) PSIKOLOGI_IKK
							, COALESCE(K.JPM,0) KOMPETEN_JPM, COALESCE(K.IKK,0) KOMPETEN_IKK
							, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
							FROM penilaian A
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) 
							) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
							LEFT JOIN
							(
							SELECT PEGAWAI_ID, JPM, IKK 
							FROM penilaian 
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) 
							) K ON A.PEGAWAI_ID = K.PEGAWAI_ID
							WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID IN (1,2)
							GROUP BY A.PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
							) X
							LEFT JOIN
						(
							SELECT
							A.ID, A.KODE_UNKER
							FROM
							(
								SELECT 
								A.ID,
								GetAncestry
								(
									(
										CASE A.SUBBAG_ID WHEN '0' THEN 
										(
											CASE A.SUBDIT_ID WHEN '0' THEN 
											(
												CASE A.DITJEN_ID WHEN '0' THEN 
												(
													CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END
												)
												ELSE A.DITJEN_ID 
												END
											)
											ELSE A.SUBDIT_ID 
											END
										)
										ELSE A.SUBBAG_ID 
										END
									) 
								) KODE_UNKER
								FROM ".$this->db.".user A
								WHERE 1=1
							) A
							WHERE 1=1
						) S ON S.ID = X.PEGAWAI_ID
						, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
						WHERE  A.ID = X.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
						AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN 0 > 100 THEN 100 ELSE 0 END) > 0
						".$statement."
				) A
				GROUP BY A.KUADRAN_PEGAWAI
			) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
			WHERE 1=1 "; 
		
		//echo $str;exit;
		while(list($key,$val) = each($paramsArray))
		{
			//$str .= " AND $key = '$val' ";
		}
		
		//$str = $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoringAnalisaPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby="ORDER BY TO_CHAR(A1.TANGGAL_TES, 'YYYY'), D1.ATRIBUT_ID ASC")
	{
		$str = "
			SELECT 
			TO_CHAR(A1.TANGGAL_TES, 'YYYY') TAHUN,
			G1.NAMA NAMA_ATRIBUT_PARENT_NAMA, D1.NAMA ATRIBUT_NAMA, F1.NAMA TRAINING_NAMA,
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, case A.status_pegawai_id when 1 then 'PNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
 			, B.KODE NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.LAST_TMT_JABATAN TMT_JABATAN, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR, E.NAMA PENDIDIKAN_NAMA, A.LAST_DIK_JURUSAN
 			, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN
			FROM penilaian A1			
			INNER JOIN ".$this->db.".pegawai A ON A1.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			LEFT JOIN ".$this->db.".pendidikan E ON A.LAST_DIK_JENJANG = E.PENDIDIKAN_ID
			INNER JOIN penilaian_detil C1 ON A1.PENILAIAN_ID = C1.PENILAIAN_ID
			INNER JOIN formula_atribut C12 ON C12.FORMULA_ATRIBUT_ID = C1.FORMULA_ATRIBUT_ID
			INNER JOIN level_atribut C13 ON C12.LEVEL_ID = C13.LEVEL_ID
			INNER JOIN atribut D1 ON C13.ATRIBUT_ID = D1.ATRIBUT_ID AND C1.ATRIBUT_ID = D1.ATRIBUT_ID
			INNER JOIN kompetensi_training E1 ON C1.ATRIBUT_ID = E1.ATRIBUT_ID AND TRIM(to_char(E1.TAHUN,'9999')) = TO_CHAR(A1.TANGGAL_TES, 'YYYY')
			INNER JOIN training F1 ON E1.TRAINING_ID = F1.TRAINING_ID AND TRIM(to_char(F1.TAHUN,'9999')) = TO_CHAR(A1.TANGGAL_TES, 'YYYY')
			INNER JOIN
			(
			SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
			) G1 ON D1.ATRIBUT_ID_PARENT = G1.ATRIBUT_ID
			WHERE 1=1
			AND C1.GAP < 0
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
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, case A.status_pegawai_id when 1 then 'CPNS' when 2 then 'PNS' when 3 then 'Pensiun' else '' end STATUS
 			, B.KODE NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.LAST_TMT_JABATAN TMT_JABATAN, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR, E.NAMA PENDIDIKAN_NAMA, A.LAST_DIK_JURUSAN
 			, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN, A.ALAMAT
 			, NIK KTP
 			, AGAMA
 			, EMAIL
 			, A.SOSIAL_MEDIA
 			, A.ALAMAT_TEMPAT_KERJA
 			, A.STATUS_KAWIN
 			, A.HP
 			, A.AUTO_ANAMNESA
 			, CASE A.STATUS_KAWIN WHEN '1' THEN 'Belum Kawin' WHEN '2' THEN 'Kawin' WHEN '3' THEN 'Janda' WHEN '4' THEN 'Duda'  ELSE 'Belum ada Data' END STATUS_KAWIN_INFO
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			LEFT JOIN ".$this->db.".pendidikan E ON A.LAST_DIK_JENJANG = E.PENDIDIKAN_ID
			WHERE 1=1
			"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$orderby;
		$this->query = $str;
		//echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='ORDER BY A.TMT_JABATAN ASC')
	{
		$str = "
				SELECT A.JABATAN INFO_JABATAN, B.NAMA INFO_ESELON, A.TMT_JABATAN TANGGAL_SK
				FROM ".$this->db.".riwayat_jabatan A
				LEFT JOIN ".$this->db.".eselon B ON A.ESELON_ID = B.ESELON_ID
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
	
	function selectByParamsPendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT riwayat_pendidikan_id,
			   PEGAWAI_ID,
			   a.PENDIDIKAN_ID,
			   a.TAHUN TAHUN, 
			   a.JURUSAN JURUSAN, 
			   b.NAMA AS PENDIDIKAN
		  FROM ".$this->db.".riwayat_pendidikan a, ".$this->db.".pendidikan b
		 WHERE a.PENDIDIKAN_ID = b.PENDIDIKAN_ID"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY PENDIDIKAN_ID ASC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsMonitoringAnalisaPegawai($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM penilaian A1			
				INNER JOIN ".$this->db.".pegawai A ON A1.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
				LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
				LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
				LEFT JOIN ".$this->db.".pendidikan E ON A.LAST_DIK_JENJANG = E.PENDIDIKAN_ID
				INNER JOIN penilaian_detil C1 ON A1.PENILAIAN_ID = C1.PENILAIAN_ID
				INNER JOIN formula_atribut C12 ON C12.FORMULA_ATRIBUT_ID = C1.FORMULA_ATRIBUT_ID
				INNER JOIN level_atribut C13 ON C12.LEVEL_ID = C13.LEVEL_ID
				INNER JOIN atribut D1 ON C13.ATRIBUT_ID = D1.ATRIBUT_ID AND C1.ATRIBUT_ID = D1.ATRIBUT_ID
				INNER JOIN kompetensi_training E1 ON C1.ATRIBUT_ID = E1.ATRIBUT_ID AND TRIM(to_char(E1.TAHUN,'9999')) = TO_CHAR(A1.TANGGAL_TES, 'YYYY')
				INNER JOIN training F1 ON E1.TRAINING_ID = F1.TRAINING_ID AND TRIM(to_char(F1.TAHUN,'9999')) = TO_CHAR(A1.TANGGAL_TES, 'YYYY')
				INNER JOIN
				(
				SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
				) G1 ON D1.ATRIBUT_ID_PARENT = G1.ATRIBUT_ID
				WHERE 1=1
				AND C1.GAP < 0
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
	
	function getCountByParamsMonitoringRePenilaianPegawai($paramsArray=array(), $statement="", $reqTahun='2015')
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".user A
				LEFT JOIN ".$this->db.".sysparam B ON A.GENDER = B.SKEY AND B.SGROUP = 'GENDER'
				LEFT JOIN ".$this->db.".sysparam C ON A.RANK = C.SKEY AND C.SGROUP = 'RANK'
				LEFT JOIN ".$this->db.".sysparam D ON A.ESSELON = D.SKEY AND D.SGROUP = 'ESSELON'
				LEFT JOIN
				(
					SELECT
					A.ID, A.KODE_UNKER
					FROM
					(
						SELECT 
						A.ID,
						GetAncestry
						(
							(
								CASE A.SUBBAG_ID WHEN '0' THEN 
								(
									CASE A.SUBDIT_ID WHEN '0' THEN 
									(
										CASE A.DITJEN_ID WHEN '0' THEN 
										(
											CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END
										)
										ELSE A.DITJEN_ID 
										END
									)
									ELSE A.SUBDIT_ID 
									END
								)
								ELSE A.SUBBAG_ID 
								END
							) 
						) KODE_UNKER
						FROM ".$this->db.".user A
						WHERE 1=1
					) A
					WHERE 1=1
				) S ON S.ID = A.ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID FROM penilaian 
					WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') + 2 = '".$reqTahun."' GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY') + 2
				) P ON A.ID = P.PEGAWAI_ID
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
				FROM ".$this->db.".pegawai A
				LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
				LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
				LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
				INNER JOIN
				(
					SELECT PEGAWAI_ID FROM penilaian 
					WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' GROUP BY PEGAWAI_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
				) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
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
	
	function getCountByParamsMonitoringPenilaianCetak($paramsArray=array(), $statement="", $reqTahun='2015')
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
				INNER JOIN
				(
				SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
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
	
	function getCountByParamsMonitoringKandidatPegawai($paramsArray=array(), $statement="", $tahun="2015")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
			WHERE 1=1
				".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsMonitoringKandidatPegawaiBak1($paramsArray=array(), $statement="", $tahun="2015")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON
				INNER JOIN
				(
					SELECT
						COALESCE(X.NILAI_IKK,0) NILAI_IKK, COALESCE(ROUND(100 * X.NILAI_IKK / COUNT(1),2),0) IKK
						, SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_JPM
						, ROUND(100 * SUM(ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) / COUNT(1),2) JPM
						, D.PEGAWAI_ID IDPEG
					FROM  atribut B 
					INNER JOIN penilaian_detil C ON B.ATRIBUT_ID = C.ATRIBUT_ID
					INNER JOIN penilaian D ON C.PENILAIAN_ID = D.PENILAIAN_ID
					INNER JOIN ".$this->db.".rb_ref_unker A ON D.SATKER_TES_ID = A.KODE_UNKER
					INNER JOIN ".$this->db.".rb_data_pegawai AA ON D.SATKER_TES_ID = AA.KODE_UNKER AND AA.IDPEG = D.PEGAWAI_ID
					LEFT JOIN
					(
						SELECT
							SUM(1 - ROUND(COALESCE(CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END,0) / COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0),2)) NILAI_IKK
							, D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
						FROM  atribut B , penilaian_detil C , penilaian D, ".$this->db.".rb_ref_unker A  
						WHERE 1=1				
						AND B.ATRIBUT_ID = C.ATRIBUT_ID 
						AND C.PENILAIAN_ID = D.PENILAIAN_ID
						AND D.SATKER_TES_ID = A.KODE_UNKER
						AND CASE WHEN C.GAP IS NULL THEN 3 - COALESCE(CASE WHEN D.ESELON = 4 THEN B.NILAI_ES4 WHEN D.ESELON = 3 THEN B.NILAI_ES3 WHEN D.ESELON = 2 THEN B.NILAI_ES2 ELSE B.NILAI_STANDAR END,0) ELSE C.GAP END < 0
					  AND CASE WHEN C.NILAI IS NULL THEN 3 ELSE C.NILAI END > 0
						GROUP BY D.PENILAIAN_ID, B.ASPEK_ID, A.KODE_UNKER
					) X ON X.PENILAIAN_ID = D.PENILAIAN_ID AND X.ASPEK_ID = D.ASPEK_ID AND X.KODE_UNKER = D.SATKER_TES_ID
					WHERE 1=1
					AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$tahun."'
					GROUP BY D.PEGAWAI_ID
				) N ON A.IDPEG = N.IDPEG
				, ".$this->db.".rb_ref_unker D
				WHERE 1=1 AND A.KODE_UNKER = D.KODE_UNKER AND A.STATUS_PEG IN ('0') AND SUBSTR(A.KODE_UNKER, 1, 0) = '' AND A.KODE_ESELON NOT IN (99, 88)
				".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;exit;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsMonitoringKandidatPegawaiBak($paramsArray=array(), $statement="", $tahun="2015")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON
				INNER JOIN
				(
					SELECT IDPEG, SUM(COALESCE(JPM,0) * 100) / COUNT(1) JPM, SUM(COALESCE(IKK,0) * 100) / COUNT(1) IKK
					FROM ".$this->db.".rb_data_pegawai A
					INNER JOIN
					(
					SELECT PEGAWAI_ID, ASPEK_ID, JPM, IKK FROM penilaian 
					WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' GROUP BY PEGAWAI_ID, ASPEK_ID, TO_CHAR(TANGGAL_TES, 'YYYY')
					) P ON A.IDPEG = P.PEGAWAI_ID
					LEFT JOIN
					(
					SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK1, ASPEK_ID FROM penilaian 
					WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' AND ASPEK_ID = '1'  and SUBSTR(SATKER_TES_ID, 1, 0) = ''
					GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
					) R ON P.ASPEK_ID = R.ASPEK_ID
					LEFT JOIN
					(
					SELECT COUNT(ASPEK_ID) JUMLAH_ASPEK2, ASPEK_ID FROM penilaian 
					WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$tahun."' AND ASPEK_ID = '2'  and SUBSTR(SATKER_TES_ID, 1, 0) = ''
					GROUP BY TO_CHAR(TANGGAL_TES, 'YYYY'), ASPEK_ID
					) S ON P.ASPEK_ID = S.ASPEK_ID 
					WHERE 1=1 AND A.STATUS_PEG IN ('0')
					and SUBSTR(A.KODE_UNKER, 1, 0) = ''  
					AND A.KODE_ESELON NOT IN (99, 88)
					GROUP BY IDPEG
				) N ON A.IDPEG = N.IDPEG
				, ".$this->db.".rb_ref_unker D
				WHERE 1=1 AND A.KODE_UNKER = D.KODE_UNKER
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
	
	function getCountByParamsMonitoringTableTalentPoolPotensiKompetensiMonitoring($paramsArray=array(), $statement="", $reqTahun='2015', $searcJson= "")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM
				(
					SELECT
					B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
					B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
					B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM,
					A.*
					FROM
					(
						SELECT * FROM
						(
							SELECT * FROM P_KUADRAN_INFO()
						) A
					) A
					LEFT JOIN
					(
						SELECT
						A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
						A.NILAI_Y KOMPETEN_IKK, A.NILAI_X PSIKOLOGI_IKK, '' IKK, '' NILAI,
						'' KOMPETEN_JPM, '' PSIKOLOGI_JPM, '' JPM,
						'' JPM_TOTAL, '' IKK_TOTAL,
						A.*
						FROM
						(
							SELECT A.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
							(CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) NILAI_X,
							(CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) NILAI_Y,
							CAST
							(
								CASE WHEN
								COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN 
								COALESCE(X.NILAI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X2
								THEN '2'
								ELSE '3' END
								||
								CASE 
								WHEN (COALESCE(Y.NILAI,0) >= 0) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
							AS INTEGER) KUADRAN_PEGAWAI
							FROM ".$this->db.".pegawai A
							INNER JOIN
							(
								SELECT
									D.PEGAWAI_ID, CASE WHEN COALESCE(D.JPM,0) * 100 > 100 THEN 100 ELSE COALESCE(D.JPM,0) * 100 END NILAI
								FROM penilaian D
								INNER JOIN 
								(
									SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
									FROM ".$this->db.".pegawai A
								) AA ON AA.ID = D.PEGAWAI_ID
								WHERE 1=1 AND D.ASPEK_ID = '2'
								AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
							) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
							LEFT JOIN
							(
								SELECT PEGAWAI_ID, NILAI_SKP NILAI
								FROM
								(
									SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
									FROM
									(
										SELECT 
										ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
										, PEGAWAI_ID, TAHUN, NILAI_SKP
										FROM
										(
											SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
											FROM simpeg.pegawai A
											UNION ALL
											SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
											FROM simpeg.riwayat_skp A 
											WHERE SKP_TAHUN = '".$reqTahun."'
										) A
									) A
									WHERE NOMOR = 1
								) A
							) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
							, 
						(
							SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_Y,
						(
							SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
							FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
						) KD_X
							WHERE 1=1
							".$statement."
						) A
					) B ON CAST(B.KUADRAN_PEGAWAI_ID AS INTEGER) = A.ID_KUADRAN 
					WHERE 1=1
					AND B.PEGAWAI_ID IS NOT NULL  
					 ";
				
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= ") A ".$searcJson;
		// echo $str;exit();
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsMonitoringTableTalentPoolJPMMonitoring($paramsArray=array(), $statement="", $reqTahun='2015', $searcJson= "")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM
				(
					SELECT
					B.PEGAWAI_ID, B.NAMA, B.NAMA_JAB_STRUKTURAL,
					B.KOMPETEN_IKK, B.PSIKOLOGI_IKK, B.IKK, B.NILAI,
					B.KOMPETEN_JPM, B.PSIKOLOGI_JPM, B.JPM,
					A.*
					FROM
					(
						SELECT * FROM
						(
							SELECT * FROM P_KUADRAN_INFO()
						) A
					) A
					LEFT JOIN
					(
						SELECT
						A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID,
						A.NILAI_Y KOMPETEN_IKK, A.NILAI_X PSIKOLOGI_IKK, '' IKK, '' NILAI,
						'' KOMPETEN_JPM, '' PSIKOLOGI_JPM, '' JPM,
						'' JPM_TOTAL, '' IKK_TOTAL,
						A.*
						FROM
						(
							SELECT A.PEGAWAI_ID, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL,
							(CASE WHEN COALESCE(X.NILAI,0) > 100 THEN 100 ELSE COALESCE(X.NILAI,0) END) NILAI_X,
							(CASE WHEN COALESCE(Y.NILAI,0) > 100 THEN 100 ELSE COALESCE(Y.NILAI,0) END) NILAI_Y,
							CAST
							(
								CASE WHEN
								COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X1 
								THEN '1'
								WHEN 
								COALESCE(X.NILAI,0) > KD_X.KUADRAN_X1 AND COALESCE(X.NILAI,0) <= KD_X.KUADRAN_X2
								THEN '2'
								ELSE '3' END
								||
								CASE 
								WHEN (COALESCE(Y.NILAI,0) >= 0) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y1 THEN '1'
								WHEN (COALESCE(Y.NILAI,0) > KD_Y.KUADRAN_Y1) AND COALESCE(Y.NILAI,0) <= KD_Y.KUADRAN_Y2 THEN '2'
								ELSE '3' END
							AS INTEGER) KUADRAN_PEGAWAI
							FROM ".$this->db.".pegawai A
							INNER JOIN
							(SELECT
									D.PEGAWAI_ID, (SUM(COALESCE(D.JPM,0)) * 100) /2 NILAI, TO_CHAR(D.TANGGAL_TES, 'YYYY') TAHUN
								FROM penilaian D
								INNER JOIN 
								(
									SELECT A.PEGAWAI_ID ID, '' ESSELON, A.NAMA, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, A.SATKER_ID KODE_UNKER
									FROM ".$this->db.".pegawai A
								) AA ON AA.ID = D.PEGAWAI_ID
								WHERE 1=1 AND D.ASPEK_ID in ('1','2')
								AND TO_CHAR(D.TANGGAL_TES, 'YYYY') = '".$reqTahun."'
								GROUP BY D.PEGAWAI_ID, TO_CHAR(D.TANGGAL_TES, 'YYYY')
							) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
							INNER JOIN
							(
								SELECT PEGAWAI_ID, NILAI_SKP NILAI
								FROM
								(
									SELECT NOMOR, PEGAWAI_ID, TAHUN, NILAI_SKP
									FROM
									(
										SELECT 
										ROW_NUMBER () OVER (PARTITION BY PEGAWAI_ID ORDER BY TAHUN) NOMOR
										, PEGAWAI_ID, TAHUN, NILAI_SKP
										FROM
										(
											SELECT PEGAWAI_ID, 9999 TAHUN, CAST(LAST_SKP AS NUMERIC) NILAI_SKP
											FROM simpeg.pegawai A
											UNION ALL
											SELECT PEGAWAI_ID, CAST(SKP_TAHUN AS NUMERIC) TAHUN, CAST(NILAI_SKP AS NUMERIC) NILAI_SKP
											FROM simpeg.riwayat_skp A 
											WHERE SKP_TAHUN = '".$reqTahun."'
										) A
									) A
									WHERE NOMOR = 1
								) A
							) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
							, 
							(
								SELECT COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
								FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
							) KD_Y,
							(
								SELECT COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
								FROM toleransi_talent_pool WHERE 1=1 AND TAHUN = '".$reqTahun."'
							) KD_X
							WHERE 1=1
							".$statement."
						) A
					) B ON CAST(B.KUADRAN_PEGAWAI_ID AS INTEGER) = A.ID_KUADRAN 
					WHERE 1=1
					AND B.PEGAWAI_ID IS NOT NULL 
					 ";
				
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= ") A ".$searcJson;
		// echo $str;exit();
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsMonitoringTableTalentPoolMonitoring($paramsArray=array(), $statement="", $reqTahun='2015', $searcJson= "")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM
				(
					SELECT
					A.*
					FROM
					(
						SELECT A.* FROM
						(
							SELECT 33 ID_KUADRAN, 'Raising Star' NAMA_KUADRAN, 'I' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 32 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'II' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 23 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'III' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 22 ID_KUADRAN, 'Promotable' NAMA_KUADRAN, 'IV' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 31 ID_KUADRAN, 'Potential Later' NAMA_KUADRAN, 'V' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 13 ID_KUADRAN, 'Performance Later' NAMA_KUADRAN, 'VI' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 21 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'VII' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 12 ID_KUADRAN, 'Improve' NAMA_KUADRAN, 'VIII' KODE_KUADRAN FROM DUAL
							UNION ALL
							SELECT 11 ID_KUADRAN, 'Dead Wood' NAMA_KUADRAN, 'IX' KODE_KUADRAN FROM DUAL
						) A
					) A
					LEFT JOIN
					(
						SELECT
						A.PEGAWAI_ID, A.NAMA, A.NAMA_JAB_STRUKTURAL,
						A.KOMPETEN_IKK, A.PSIKOLOGI_IKK, A.IKK, A.NILAI,
						A.KOMPETEN_JPM, A.PSIKOLOGI_JPM, A.JPM,
						A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
						FROM
						(
									SELECT 
									X.PEGAWAI_ID, A.NAME NAMA, A.POSITION NAMA_JAB_STRUKTURAL,
									X.KOMPETEN_IKK, X.PSIKOLOGI_IKK, X.IKK, '1' NILAI,
									X.KOMPETEN_JPM, X.PSIKOLOGI_JPM, X.JPM,
									CONCAT
									(
										CASE 
										WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
										WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
										ELSE '3' END
										, '1'
									) KUADRAN_PEGAWAI,
									CASE 
									WHEN (COALESCE(X.JPM,0) * 100) >= 0 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_1 THEN '1'
									WHEN (COALESCE(X.JPM,0) * 100) > KD.KUADRAN_1 AND (COALESCE(X.JPM,0) * 100) <= KD.KUADRAN_2 THEN '2'
									ELSE '3' END KUADRAN_X,
									COALESCE(X.JPM,0) * 100 NILAI_X
									, '1' KUADRAN_Y, '1' NILAI_Y,
									KD.KUADRAN_0, KD.KUADRAN_1, KD.KUADRAN_2, KD.KUADRAN_3
									FROM ".$this->db.".user A
									,
									(
									SELECT A.PEGAWAI_ID, COALESCE((sum(A.JPM) / 2),0) JPM, COALESCE((sum(A.IKK)/2),0) IKK
									, COALESCE(P.JPM,0) PSIKOLOGI_JPM, COALESCE(P.IKK,0) PSIKOLOGI_IKK
									, COALESCE(K.JPM,0) KOMPETEN_JPM, COALESCE(K.IKK,0) KOMPETEN_IKK
									, TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN
									FROM penilaian A
									LEFT JOIN
									(
									SELECT PEGAWAI_ID, JPM, IKK 
									FROM penilaian 
									WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (1) 
									) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
									LEFT JOIN
									(
									SELECT PEGAWAI_ID, JPM, IKK 
									FROM penilaian 
									WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID in (2) 
									) K ON A.PEGAWAI_ID = K.PEGAWAI_ID
									WHERE 1=1 AND TO_CHAR(TANGGAL_TES, 'YYYY') = '".$reqTahun."' AND ASPEK_ID IN (1,2)
									GROUP BY A.PEGAWAI_ID, TO_CHAR(A.TANGGAL_TES, 'YYYY')
									) X
									LEFT JOIN
								(
									SELECT
									A.ID, A.KODE_UNKER
									FROM
									(
										SELECT 
										A.ID,
										GetAncestry
										(
											(
												CASE A.SUBBAG_ID WHEN '0' THEN 
												(
													CASE A.SUBDIT_ID WHEN '0' THEN 
													(
														CASE A.DITJEN_ID WHEN '0' THEN 
														(
															CASE A.ORG_ID WHEN '0' THEN '0' ELSE A.ORG_ID END
														)
														ELSE A.DITJEN_ID 
														END
													)
													ELSE A.SUBDIT_ID 
													END
												)
												ELSE A.SUBBAG_ID 
												END
											) 
										) KODE_UNKER
										FROM ".$this->db.".user A
										WHERE 1=1
									) A
									WHERE 1=1
								) S ON S.ID = X.PEGAWAI_ID
								, (SELECT 0 KUADRAN_0, 33 KUADRAN_1, 70 KUADRAN_2, 100 KUADRAN_3 FROM DUAL) KD
								WHERE  A.ID = X.PEGAWAI_ID AND TAHUN = '".$reqTahun."'
								AND (CASE WHEN COALESCE(X.IKK,0) * 100 > 100 THEN 100 ELSE COALESCE(X.IKK,0) * 100 END + CASE WHEN 0 > 100 THEN 100 ELSE 0 END) > 0
								".$statement."
						) A
					) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
					WHERE 1=1 AND B.PEGAWAI_ID IS NOT NULL ";
				
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= ") A ".$searcJson;
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
				FROM ".$this->db.".pegawai A
				LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
				LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
				LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
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
	
	function getCountByParamsMonitoringLhkpnPegawai($paramsArray=array(), $statement="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A 
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON  
				LEFT JOIN
				(
					SELECT pegawai_id, max(tanggal_lapor) tanggal_lapor,
					DATE_FORMAT(NOW(),'%Y') - DATE_FORMAT(tanggal_lapor,'%Y') - (IF(DATE_FORMAT(NOW(),'%m-%d') < DATE_FORMAT(tanggal_lapor,'%m-%d'), 1, 0 )) masa_lapor
					FROM penilaian_lhkpn 
					GROUP BY pegawai_id
				) x on A.IDPEG = x.pegawai_id
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
	
	function getCountByParamsMonitoringSkpPegawai($paramsArray=array(), $statement="", $tahun="")
	{
		$str = "
				SELECT COUNT(1) ROWCOUNT
				FROM ".$this->db.".rb_data_pegawai A 
				LEFT JOIN ".$this->db.".rb_ref_gol B ON A.KODE_GOL_AKHIR = B.KODE_GOL
				LEFT JOIN ".$this->db.".rb_ref_eselon C ON A.KODE_ESELON = C.KODE_ESELON
				LEFT JOIN
				(
					SELECT COUNT(1) JUMLAH_DATA, PEGAWAI_ID
					FROM skp_kkp WHERE TAHUN = ".$tahun."
					GROUP BY PEGAWAI_ID
				) X ON A.IDPEG = X.PEGAWAI_ID
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
	
	function selectByParamsMonitoringAnalisaDiklatKompetensiBendel($paramsArray=array(),$limit=-1,$from=-1, $statement="", $tahun='', $prosentase='0', $orderby='')
	{
		$str = "
		SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN, D.ATRIBUT_ID, G.ATRIBUT_ID ATRIBUT_PARENT_ID, G.NAMA NAMA_ATRIBUT_PARENT, D.NAMA NAMA_ATRIBUT, F.TRAINING_ID, F.NAMA NAMA_TRAINING
		, COUNT(A.PEGAWAI_ID) JUMLAH_PEGAWAI, B.SATKER_ID KODE_UNKER
		FROM penilaian A
		INNER JOIN ".$this->db.".pegawai B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN penilaian_detil C ON A.PENILAIAN_ID = C.PENILAIAN_ID AND B.PEGAWAI_ID = C.PEGAWAI_ID
		INNER JOIN atribut D ON C.ATRIBUT_ID = D.ATRIBUT_ID 
		INNER JOIN kompetensi_training E ON D.ATRIBUT_ID = E.ATRIBUT_ID AND TRIM(to_char(E.TAHUN,'9999')) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
		INNER JOIN training F ON E.TRAINING_ID = F.TRAINING_ID AND TRIM(to_char(F.TAHUN,'9999')) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
		INNER JOIN
		(
		SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
		) G ON D.ATRIBUT_ID_PARENT = G.ATRIBUT_ID
		WHERE 1=1
		AND C.GAP < 0
		";
		
		if($tahun == ""){}
		else
		{
		$str .= 
		"
		AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahun."'
		";
		}
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY'), G.NAMA, D.ATRIBUT_ID, D.NAMA, F.TRAINING_ID, F.NAMA, G.ATRIBUT_ID, B.SATKER_ID ORDER BY TO_CHAR(A.TANGGAL_TES, 'YYYY'), D.ATRIBUT_ID ASC";
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function getCountByParamsMonitoringAnalisaDiklatKompetensiBendel($paramsArray=array(), $statement="", $tahun='', $prosentase='0', $orderby='')
	{
		$str = "
		SELECT COUNT(*) AS ROWCOUNT
		FROM
		(
		SELECT TO_CHAR(A.TANGGAL_TES, 'YYYY') TAHUN, G.NAMA NAMA_ATRIBUT_PARENT, D.NAMA NAMA_ATRIBUT, F.NAMA NAMA_TRAINING
		, COUNT(A.PEGAWAI_ID) JUMLAH_PEGAWAI
		FROM penilaian A
		INNER JOIN ".$this->db.".pegawai B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN penilaian_detil C ON A.PENILAIAN_ID = C.PENILAIAN_ID AND B.PEGAWAI_ID = C.PEGAWAI_ID
		INNER JOIN atribut D ON C.ATRIBUT_ID = D.ATRIBUT_ID 
		INNER JOIN kompetensi_training E ON D.ATRIBUT_ID = E.ATRIBUT_ID AND TRIM(to_char(E.TAHUN,'9999')) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
		INNER JOIN training F ON E.TRAINING_ID = F.TRAINING_ID AND TRIM(to_char(F.TAHUN,'9999')) = TO_CHAR(A.TANGGAL_TES, 'YYYY')
		INNER JOIN
		(
		SELECT A.ATRIBUT_ID, A.NAMA FROM atribut A WHERE A.ATRIBUT_ID_PARENT = '0'
		) G ON D.ATRIBUT_ID_PARENT = G.ATRIBUT_ID
		WHERE 1=1
		AND C.GAP < 0
		";
		if($tahun == ""){}
		else
		{
		$str .= 
		"
		AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$tahun."'
		";
		}
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement;
		
		$str .= 
		"
		GROUP BY TO_CHAR(A.TANGGAL_TES, 'YYYY'), G.NAMA, D.ATRIBUT_ID, D.NAMA, F.NAMA
		) A
		";
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
		
    }
	
	function selectByParamsMonitoring2($paramsArray=array(),$limit=-1,$from=-1, $statement='', $orderby='')
	{
		/*TO_CHAR(B.TMT_PANGKAT, 'DD MON YYYY') TMT_PANGKAT,
		TO_CHAR(C.TMT_JABATAN, 'DD MON YYYY') TMT_JABATAN,
		TO_CHAR(TANGGAL_LAHIR, 'DD MON YYYY') TANGGAL_LAHIR, */
		$str = "
			SELECT 
			A.SATKER_ID KODE_UNKER,
 			A.PEGAWAI_ID IDPEG, A.NIP NIP_LAMA, A.NIP_BARU, A.NAMA
 			, '' GELAR_DEPAN, '' GELAR_BELAKANG, JENIS_KELAMIN, A.TEMPAT_LAHIR, A.TGL_LAHIR, '' STATUS
 			, B.NAMA NAMA_GOL, A.LAST_TMT_PANGKAT TMT_GOL_AKHIR, C.NAMA NAMA_ESELON, A.LAST_JABATAN NAMA_JAB_STRUKTURAL, '' TELP
 			, '' STATUS_KANDIDAT, '' UMUR
 			, D.NAMA SATKER, A.LAST_ESELON_ID ESELON_PENILAIAN
			FROM ".$this->db.".pegawai A
			LEFT JOIN ".$this->db.".pangkat B ON A.LAST_PANGKAT_ID = B.PANGKAT_ID
			LEFT JOIN ".$this->db.".eselon C ON A.LAST_ESELON_ID = C.ESELON_ID
			LEFT JOIN ".$this->db.".satker D ON A.SATKER_ID = D.SATKER_ID
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
	
  } 
?>
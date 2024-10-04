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

  class MasterJabatan extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function MasterJabatan()
	{
        //    $xmlfile = "../WEB/web.xml";
	  // $data = simplexml_load_file($xmlfile);
	  // $rconf_url_info= $data->urlConfig->main->urlbase;

	  // $this->db=$rconf_url_info;
	  $this->db='simpeg';
	  $this->Entity();  
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_ID", $this->getNextId("JABATAN_ID","simpeg.jabatan"));
		
		$str = "INSERT INTO simpeg.jabatan (
				   JABATAN_ID, JABATAN_ID_PARENT, NAMA_JABATAN, KODE_JABATAN,ESELON_ID,SATKER_ID,RUMPUN_ID)
				VALUES (
				  (SELECT simpeg.jabatan_generate('".$this->getField("JABATAN_ID_PARENT")."')),
				  '".$this->getField("JABATAN_ID_PARENT")."',
				  '".$this->getField("NAMA_JABATAN")."',
				  '".$this->getField("KODE_JABATAN")."',
				  ".$this->getField("ESELON_ID").",
				  '".$this->getField("SATKER_ID")."',
				  '".$this->getField("RUMPUN_ID")."'
				)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("JABATAN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE simpeg.jabatan
				SET
				   JABATAN_ID= '".$this->getField("JABATAN_ID")."',
				   NAMA_JABATAN= '".$this->getField("NAMA_JABATAN")."',
				   KODE_JABATAN= '".$this->getField("KODE_JABATAN")."',
				   ESELON_ID= ".$this->getField("ESELON_ID").",
				   SATKER_ID= '".$this->getField("SATKER_ID")."',
				   RUMPUN_ID= '".$this->getField("RUMPUN_ID")."'
				WHERE JABATAN_ID= '".$this->getField("JABATAN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE SIMPEG.JABATAN
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  JABATAN_ID = '".$this->getField("JABATAN_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SIMPEG.JABATAN
                WHERE 
                  JABATAN_ID LIKE '".$this->getField("JABATAN_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM simpeg.jabatan A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.JABATAN_ID ASC")
	{
		$str = "
				SELECT A.JABATAN_ID, A.JABATAN_ID_PARENT, A.JABATAN_ID
				, A.NAMA_JABATAN, A.KODE_JABATAN
				, A.SATKER_ID
				, A.PANGKAT_ID
				, A.RUMPUN_ID
				, A.PANGKAT_ID
				, B.NAMA NAMA_SATKER
				, C.NAMA NAMA_PANGKAT
				, D.NAMA_RUMPUN
				, E.ESELON_ID
				, E.NAMA NAMA_ESELON
				FROM simpeg.jabatan A
				LEFT JOIN simpeg.satker B ON B.satker_id = a.satker_id
				LEFT JOIN simpeg.pangkat C ON C.pangkat_id = a.pangkat_id
				LEFT JOIN simpeg.rumpun D ON D.rumpun_id = a.rumpun_id
				LEFT JOIN simpeg.eselon E ON E.eselon_id = a.eselon_id
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
	
	function selectByParamsMasterJabatanCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
		SELECT
		ID, PARENT_ID, NAMA_JABATAN, ID_ROW, LINK_URL
		, A.RUMPUN_ID, A.RUMPUN_KODE, A.RUMPUN_NAMA
		, A.SATKER_ID, A.SATKER_NAMA
		FROM
		(
			SELECT
			A.JABATAN_ID AS ID, A.JABATAN_ID_PARENT PARENT_ID, A.NAMA_JABATAN
			, A.JABATAN_ID ID_ROW, A.JABATAN_ID
			, CASE A.JABATAN_ID_PARENT
			WHEN '0'
			THEN
			'<a onClick=\"window.OpenDHTMLCheck(''master_jabatan_add.php?reqMasterJabatanParentId=' || A.JABATAN_ID || ''',''Tambah Jabatan'',1000, 500)\"><img src=\"../WEB/images/icn_add.png\"></a> - 
			 <a onClick=\"window.OpenDHTMLCheck(''master_jabatan_add.php?reqMasterJabatanId=' || A.JABATAN_ID || ''',''Edit Jabatan'',1000, 500)\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''master_jabatan.php?reqMode=delete&reqId=' || A.JABATAN_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
			ELSE
			'<a onClick=\"window.OpenDHTMLCheck(''master_jabatan_add.php?reqMasterJabatanId=' || A.JABATAN_ID || ''',''Tambah Jabatan'',1000, 500)\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''master_jabatan.php?reqMode=delete&reqId=' || A.JABATAN_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
			END
			LINK_URL
			, A.RUMPUN_ID, C.KODE_RUMPUN RUMPUN_KODE, C.NAMA_RUMPUN RUMPUN_NAMA
			, A.SATKER_ID, D.NAMA SATKER_NAMA
			FROM simpeg.jabatan A
			LEFT JOIN simpeg.rumpun C ON A.RUMPUN_ID = C.RUMPUN_ID
			LEFT JOIN simpeg.satker D ON A.SATKER_ID = D.SATKER_ID
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
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	
	
	/** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SIMPEG.JABATAN A
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
	
	function getCountByParamsMasterJabatanCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA_JABATAN, ID_ROW, LINK_URL, ASPEK_NAMA_JABATAN
				FROM
				(
					SELECT
					A.JABATAN_ID AS ID, A.JABATAN_ID_PARENT PARENT_ID, A.NAMA_JABATAN
					, A.JABATAN_ID ID_ROW, A.JABATAN_ID, CASE A.JABATAN_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA_JABATAN
					, CASE A.JABATAN_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''master_jabatan_add.php?reqTahun=' || A.TAHUN || '&reqMasterJabatanParentId=' || A.JABATAN_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''master_jabatan_add.php?reqTahun=' || A.TAHUN || '&reqMasterJabatanId=' || A.JABATAN_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					
					FROM simpeg.jabatan A
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
	
  } 
?>
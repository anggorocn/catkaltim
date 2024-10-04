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

  class RumpunJabatan extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function RumpunJabatan()
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
		$this->setField("RUMPUN_ID", $this->getNextId("RUMPUN_ID","simpeg.rumpun"));
		
		$str = "INSERT INTO simpeg.rumpun (
				   RUMPUN_ID, RUMPUN_ID_PARENT, NAMA_RUMPUN, KODE_RUMPUN)
				VALUES (
				  '".$this->getField("RUMPUN_ID")."',
				  '0',
				  '".$this->getField("NAMA_RUMPUN")."',
				  '".$this->getField("KODE_RUMPUN")."'
				)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("RUMPUN_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE simpeg.rumpun
				SET
				   RUMPUN_ID= '".$this->getField("RUMPUN_ID")."',
				   NAMA_RUMPUN= '".$this->getField("NAMA_RUMPUN")."',
				   KODE_RUMPUN= '".$this->getField("KODE_RUMPUN")."'
				WHERE RUMPUN_ID= '".$this->getField("RUMPUN_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE SIMPEG.RUMPUN
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  RUMPUN_ID = '".$this->getField("RUMPUN_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SIMPEG.RUMPUN
                WHERE 
                  RUMPUN_ID LIKE '".$this->getField("RUMPUN_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM simpeg.rumpun A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.RUMPUN_ID ASC")
	{
		$str = "
				SELECT A.RUMPUN_ID, A.RUMPUN_ID_PARENT, A.RUMPUN_ID
				, A.NAMA_RUMPUN, A.KODE_RUMPUN
				FROM simpeg.rumpun A
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
	
	function selectByParamsRumpunJabatanCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA_RUMPUN, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.RUMPUN_ID AS ID, A.RUMPUN_ID_PARENT PARENT_ID, A.NAMA_RUMPUN
					, A.RUMPUN_ID ID_ROW, A.RUMPUN_ID
					, CASE A.RUMPUN_ID_PARENT
					WHEN '0'
					THEN
					-- '<a onClick=\"window.OpenDHTMLPopUp(''rumpun_jabatan_add.php?reqRumpunJabatanParentId=' || A.RUMPUN_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - 
					' <a onClick=\"window.OpenDHTMLPopUp(''rumpun_jabatan_add.php?reqRumpunJabatanId=' || A.RUMPUN_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''rumpun_jabatan.php?reqMode=delete&reqId=' || A.RUMPUN_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_jabatan_add.php?reqRumpunJabatanId=' || A.RUMPUN_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''rumpun_jabatan.php?reqMode=delete&reqId=' || A.RUMPUN_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					END
					LINK_URL
					
					FROM simpeg.rumpun A
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
	
	
	
	/** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","JABATAN"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM SIMPEG.RUMPUN A
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
	
	function getCountByParamsRumpunJabatanCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA_RUMPUN, ID_ROW, LINK_URL, ASPEK_NAMA_RUMPUN
				FROM
				(
					SELECT
					A.RUMPUN_ID AS ID, A.RUMPUN_ID_PARENT PARENT_ID, A.NAMA_RUMPUN
					, A.RUMPUN_ID ID_ROW, A.RUMPUN_ID, CASE A.RUMPUN_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA_RUMPUN
					, CASE A.RUMPUN_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_jabatan_add.php?reqTahun=' || A.TAHUN || '&reqRumpunJabatanParentId=' || A.RUMPUN_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_jabatan_add.php?reqTahun=' || A.TAHUN || '&reqRumpunJabatanId=' || A.RUMPUN_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					
					FROM simpeg.rumpun A
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
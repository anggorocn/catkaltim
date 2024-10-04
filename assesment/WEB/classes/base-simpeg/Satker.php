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

  class Satker extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Satker()
	{
      $xmlfile = "../WEB/web.xml";
	  $data = simplexml_load_file($xmlfile);
	  $rconf_url_info= $data->urlConfig->main->urlbase;

	  $this->db=$rconf_url_info;
	  $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SATKER_ID", $this->getNextId("SATKER_ID","simpeg.satker"));
		
		$str = "INSERT INTO simpeg.satker (
				   SATKER_ID, SATKER_ID_PARENT, NAMA)
				VALUES (
				  '".$this->getField("SATKER_ID")."',
				  '0',
				  '".$this->getField("NAMA")."'
				)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("SATKER_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE simpeg.satker
				SET
				   SATKER_ID= '".$this->getField("SATKER_ID")."',
				   NAMA= '".$this->getField("NAMA")."',
				WHERE SATKER_ID= '".$this->getField("SATKER_ID")."'
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
				WHERE  SATKER_ID = '".$this->getField("SATKER_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM SIMPEG.RUMPUN
                WHERE 
                  SATKER_ID LIKE '".$this->getField("SATKER_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM simpeg.satker A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.NAMA ASC")
	{
		$str = "
				SELECT A.SATKER_ID, A.SATKER_ID_PARENT
				, A.NAMA
				FROM simpeg.satker A
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
	
	function selectByParamsSatkerCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.SATKER_ID AS ID, A.SATKER_ID_PARENT PARENT_ID, A.NAMA
					, A.SATKER_ID ID_ROW, A.SATKER_ID
					, CASE A.SATKER_ID_PARENT
					WHEN '0'
					THEN
					-- '<a onClick=\"window.OpenDHTMLPopUp(''satker_jabatan_add.php?reqSatkerParentId=' || A.SATKER_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - 
					' <a onClick=\"window.OpenDHTMLPopUp(''satker_jabatan_add.php?reqSatkerId=' || A.SATKER_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''satker_jabatan.php?reqMode=delete&reqId=' || A.SATKER_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''satker_jabatan_add.php?reqSatkerId=' || A.SATKER_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''satker_jabatan.php?reqMode=delete&reqId=' || A.SATKER_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					END
					LINK_URL
					
					FROM simpeg.satker A
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
	
	function getCountByParamsSatkerCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.SATKER_ID AS ID, A.SATKER_ID_PARENT PARENT_ID, A.NAMA
					, A.SATKER_ID ID_ROW, A.SATKER_ID, CASE A.SATKER_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.SATKER_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''satker_jabatan_add.php?reqTahun=' || A.TAHUN || '&reqSatkerParentId=' || A.SATKER_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''satker_jabatan_add.php?reqTahun=' || A.TAHUN || '&reqSatkerId=' || A.SATKER_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					
					FROM simpeg.satker A
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
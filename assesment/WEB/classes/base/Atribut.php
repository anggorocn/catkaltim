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

  class Atribut extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function Atribut()
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
		$this->setField("ATRIBUT_ID", $this->getNextId("ATRIBUT_ID","atribut"));
		
		$str = "INSERT INTO atribut (
				   ATRIBUT_ID, ATRIBUT_ID_PARENT, ASPEK_ID, NAMA, KETERANGAN, PERMEN_ID)
				VALUES (
				  (SELECT atribut_generate('".$this->getField("ATRIBUT_ID_PARENT")."')),
				  '".$this->getField("ATRIBUT_ID_PARENT")."',
				  ".$this->getField("ASPEK_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  (SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
				)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("ATRIBUT_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE atribut
				SET
				   ASPEK_ID= ".$this->getField("ASPEK_ID").",
				   NAMA= '".$this->getField("NAMA")."',
				   KETERANGAN= '".$this->getField("KETERANGAN")."'
				WHERE ATRIBUT_ID= '".$this->getField("ATRIBUT_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE ATRIBUT
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  ATRIBUT_ID = '".$this->getField("ATRIBUT_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM ATRIBUT
                WHERE 
                  ATRIBUT_ID LIKE '".$this->getField("ATRIBUT_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM atribut A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ATRIBUT_ID ASC")
	{
		$str = "
				SELECT A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.ASPEK_ID
				, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Komptensi' END ASPEK_NAMA
				, A.NAMA, A.KETERANGAN, A.PERMEN_ID
				FROM atribut A
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
	
	function selectByParamsAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.ATRIBUT_ID AS ID, A.ATRIBUT_ID_PARENT PARENT_ID, A.NAMA
					, A.ATRIBUT_ID ID_ROW, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqAtributParentId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - <a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqAtributId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''atribut.php?reqMode=delete&reqId=' || A.ATRIBUT_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqAtributId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''atribut.php?reqMode=delete&reqId=' || A.ATRIBUT_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM atribut A
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
	
	function selectByParamsIndikatorAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.ATRIBUT_ID AS ID, A.ATRIBUT_ID_PARENT PARENT_ID, A.NAMA
					, A.ATRIBUT_ID ID_ROW, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0' THEN ''
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''indikator_penilaian_add.php?reqAtributId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM atribut A
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
	
	function selectByParamsKompetensiAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.ATRIBUT_ID AS ID, A.ATRIBUT_ID_PARENT PARENT_ID, A.NAMA
					, A.ATRIBUT_ID ID_ROW, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0' THEN ''
					ELSE
						CASE COALESCE(JUMLAH_DATA,0) WHEN 1 THEN
						'<a id=\"reqInfoSimpan' || A.ATRIBUT_ID || '\" onClick=\"pilihatribut(''' || A.ATRIBUT_ID || ''', ''simpan'')\" style=\"cursor:pointer; display:none\" title=\"Pilih\"><img src=\"../WEB/images/icon_uncheck.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus' || A.ATRIBUT_ID || '\" onClick=\"pilihatribut(''' || A.ATRIBUT_ID || ''', ''hapus'')\" style=\"cursor:pointer\" title=\"hapus\"><img src=\"../WEB/images/icon_check.png\" width=\"15px\" heigth=\"15px\"></a> '
						ELSE
						'<a id=\"reqInfoSimpan' || A.ATRIBUT_ID || '\" onClick=\"pilihatribut(''' || A.ATRIBUT_ID || ''', ''simpan'')\" style=\"cursor:pointer;\" title=\"Pilih\"><img src=\"../WEB/images/icon_uncheck.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus' || A.ATRIBUT_ID || '\" onClick=\"pilihatribut(''' || A.ATRIBUT_ID || ''', ''hapus'')\" style=\"cursor:pointer; display:none\" title=\"hapus\"><img src=\"../WEB/images/icon_check.png\" width=\"15px\" heigth=\"15px\"></a> '
						END
					END
					LINK_URL
					, A.PERMEN_ID
					FROM atribut A
					LEFT JOIN
					(
						SELECT CASE WHEN COUNT(B.ATRIBUT_ID) > 0 THEN 1 ELSE 0 END JUMLAH_DATA, A.ATRIBUT_ID
						FROM kompetensi_training A
						LEFT JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID
						GROUP BY A.ATRIBUT_ID
					) B ON A.ATRIBUT_ID = B.ATRIBUT_ID
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
		FROM ATRIBUT A
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
	
	function getCountByParamsKompetensiAtributCombo($paramsArray=array(), $statement='')
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
					'<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=' || A.TAHUN || '&reqAtributParentId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=' || A.TAHUN || '&reqAtributId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM atribut A
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
	
	function getCountByParamsIndikatorAtributCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.ATRIBUT_ID AS ID, A.ATRIBUT_ID_PARENT PARENT_ID, A.NAMA
					, A.ATRIBUT_ID ID_ROW, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0' THEN ''
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''indikator_penilaian_add.php?reqTahun=' || A.TAHUN || '&reqAtributId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM atribut A
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
					, A.ATRIBUT_ID ID_ROW, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.ATRIBUT_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=' || A.TAHUN || '&reqAtributParentId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''atribut_add.php?reqTahun=' || A.TAHUN || '&reqAtributId=' || A.ATRIBUT_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM atribut A
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
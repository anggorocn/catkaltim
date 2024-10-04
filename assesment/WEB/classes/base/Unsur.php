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

  class unsur extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function unsur()
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
		$this->setField("UNSUR_ID", $this->getNextId("UNSUR_ID","unsur"));
		
		$str = "INSERT INTO unsur_penilaian (
				   UNSUR_ID, UNSUR_ID_PARENT, NAMA, KETERANGAN, PERMEN_ID)
				VALUES (
				  (SELECT unsur_generate('".$this->getField("UNSUR_ID_PARENT")."')),
				  '".$this->getField("UNSUR_ID_PARENT")."', 
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  (SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
				)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("UNSUR_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE unsur_penilaian
				SET 
				   NAMA= '".$this->getField("NAMA")."',
				   KETERANGAN= '".$this->getField("KETERANGAN")."'
				WHERE UNSUR_ID= '".$this->getField("UNSUR_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE unsur_penilaian
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  UNSUR_ID = '".$this->getField("UNSUR_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM unsur_penilaian
                WHERE 
                  UNSUR_ID LIKE '".$this->getField("UNSUR_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM unsur_penilaian A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.UNSUR_ID ASC")
	{
		$str = "
				SELECT A.UNSUR_ID, A.UNSUR_ID_PARENT 
				, A.NAMA, A.KETERANGAN, A.PERMEN_ID
				FROM unsur_penilaian A
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
	
	function selectByParamsunsurCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.UNSUR_ID AS ID, A.UNSUR_ID_PARENT PARENT_ID, A.NAMA
					, A.UNSUR_ID ID_ROW   
					, CASE A.UNSUR_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?requnsurParentId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - <a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?requnsurId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''unsur.php?reqMode=delete&reqId=' || A.UNSUR_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?requnsurId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''unsur.php?reqMode=delete&reqId=' || A.UNSUR_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM unsur_penilaian A
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
	
	function selectByParamsIndikatorunsurCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.UNSUR_ID AS ID, A.UNSUR_ID_PARENT PARENT_ID, A.NAMA
					, A.UNSUR_ID ID_ROW 
					, CASE A.UNSUR_ID_PARENT
					WHEN '0' THEN ''
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''indikator_penilaian_add.php?requnsurId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM unsur_penilaian A
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
	
	function selectByParamsKompetensiunsurCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL, ASPEK_NAMA
				FROM
				(
					SELECT
					A.UNSUR_ID AS ID, A.UNSUR_ID_PARENT PARENT_ID, A.NAMA
					, A.UNSUR_ID ID_ROW
					, CASE A.UNSUR_ID_PARENT
					WHEN '0' THEN ''
					ELSE
						CASE COALESCE(JUMLAH_DATA,0) WHEN 1 THEN
						'<a id=\"reqInfoSimpan' || A.UNSUR_ID || '\" onClick=\"pilihunsur(''' || A.UNSUR_ID || ''', ''simpan'')\" style=\"cursor:pointer; display:none\" title=\"Pilih\"><img src=\"../WEB/images/icon_uncheck.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus' || A.UNSUR_ID || '\" onClick=\"pilihunsur(''' || A.UNSUR_ID || ''', ''hapus'')\" style=\"cursor:pointer\" title=\"hapus\"><img src=\"../WEB/images/icon_check.png\" width=\"15px\" heigth=\"15px\"></a> '
						ELSE
						'<a id=\"reqInfoSimpan' || A.UNSUR_ID || '\" onClick=\"pilihunsur(''' || A.UNSUR_ID || ''', ''simpan'')\" style=\"cursor:pointer;\" title=\"Pilih\"><img src=\"../WEB/images/icon_uncheck.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus' || A.UNSUR_ID || '\" onClick=\"pilihunsur(''' || A.UNSUR_ID || ''', ''hapus'')\" style=\"cursor:pointer; display:none\" title=\"hapus\"><img src=\"../WEB/images/icon_check.png\" width=\"15px\" heigth=\"15px\"></a> '
						END
					END
					LINK_URL
					, A.PERMEN_ID
					FROM unsur_penilaian A
					LEFT JOIN
					(
						SELECT CASE WHEN COUNT(B.UNSUR_ID) > 0 THEN 1 ELSE 0 END JUMLAH_DATA, A.UNSUR_ID
						FROM kompetensi_training A
						LEFT JOIN unsur B ON A.UNSUR_ID = B.UNSUR_ID
						GROUP BY A.UNSUR_ID
					) B ON A.UNSUR_ID = B.UNSUR_ID
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
		FROM unsur_penilaian A
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
	
	function getCountByParamsKompetensiunsurCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL 
				FROM
				(
					SELECT
					A.UNSUR_ID AS ID, A.UNSUR_ID_PARENT PARENT_ID, A.NAMA
					, A.UNSUR_ID ID_ROW, A.TAHUN, A.ASPEK_ID, CASE A.ASPEK_ID WHEN '1' THEN 'Potensi' ELSE 'Kompentensi' END ASPEK_NAMA
					, CASE A.UNSUR_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?reqTahun=' || A.TAHUN || '&requnsurParentId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?reqTahun=' || A.TAHUN || '&requnsurId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM unsur A
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
	
	function getCountByParamsIndikatorunsurCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL 
				FROM
				(
					SELECT
					A.UNSUR_ID AS ID, A.UNSUR_ID_PARENT PARENT_ID, A.NAMA
					, A.UNSUR_ID ID_ROW, A.ASPEK_ID 
					, CASE A.UNSUR_ID_PARENT
					WHEN '0' THEN ''
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''indikator_penilaian_add.php?reqTahun=' || A.TAHUN || '&requnsurId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM unsur_penilaian A
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
	
	function getCountByParamsunsurCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL 
				FROM
				(
					SELECT
					A.UNSUR_ID AS ID, A.UNSUR_ID_PARENT PARENT_ID, A.NAMA
					, A.UNSUR_ID ID_ROW 
					, CASE A.UNSUR_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?reqTahun=' || A.TAHUN || '&requnsurParentId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''unsur_add.php?reqTahun=' || A.TAHUN || '&requnsurId=' || A.UNSUR_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM unsur_penilaian A
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
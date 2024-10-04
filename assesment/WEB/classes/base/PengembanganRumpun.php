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

  class PengembanganRumpun extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function PengembanganRumpun()
	{
	  $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO rumpun_pengembangan (
		rumpun_pengembangan_ID, rumpun_pengembangan_ID_PARENT, NAMA, KETERANGAN, PERMEN_ID)
		VALUES 
		(
			(SELECT rumpun_pengembangan_generate('".$this->getField("rumpun_pengembangan_PARENT")."')),
			'".$this->getField("rumpun_pengembangan_PARENT")."',
			'".$this->getField("NAMA")."',
			'".$this->getField("KETERANGAN")."',
			(SELECT PERMEN_ID FROM PERMEN WHERE STATUS = '1')
		)"; 
		// echo $str;exit;
		$this->query = $str;
		$this->id = $this->getField("rumpun_pengembangan_ID");
		return $this->execQuery($str);
    }
	
    function update()
	{
		$str = "
		UPDATE rumpun_pengembangan
		SET
		   NAMA= '".$this->getField("NAMA")."',
		   KETERANGAN= '".$this->getField("KETERANGAN")."'
		WHERE rumpun_pengembangan_ID= '".$this->getField("rumpun_pengembangan_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    } 
	
	function updateFormatDynamis()
	{
		$str = "
				UPDATE rumpun_pengembangan
				SET
					   ".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."',
					   ".$this->getField("UKURAN_TABLE")." = ".$this->getField("UKURAN_ISI").",
					   ".$this->getField("FORMAT_TABLE")."= '".$this->getField("FORMAT_ISI")."'
				WHERE  rumpun_pengembangan_ID = '".$this->getField("rumpun_pengembangan_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM rumpun_pengembangan
                WHERE 
                  rumpun_pengembangan_ID LIKE '".$this->getField("rumpun_pengembangan_ID")."%'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sField="")
	{
		$str = "SELECT 
					   ".$sField."
				FROM rumpun_pengembangan A
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.rumpun_pengembangan_ID ASC")
	{
		$str = "
				SELECT A.rumpun_pengembangan_ID, A.rumpun_pengembangan_ID_PARENT
				, A.NAMA, A.KETERANGAN, A.PERMEN_ID
				FROM rumpun_pengembangan A
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
	
	function selectByParamsPelatihanHcdpCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.rumpun_pengembangan_ID AS ID, A.rumpun_pengembangan_ID_PARENT PARENT_ID, A.NAMA
					, A.rumpun_pengembangan_ID ID_ROW
					, CASE A.rumpun_pengembangan_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqPelatihanHcdpParentId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a> - <a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqPelatihanHcdpId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''rumpun_pengembangan.php?reqMode=delete&reqId=' || A.rumpun_pengembangan_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqPelatihanHcdpId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a> - <a onClick=\"if(confirm(''Apakah anda yakin ingin menghapus data ini?'')) { window.location.href = ''rumpun_pengembangan.php?reqMode=delete&reqId=' || A.rumpun_pengembangan_ID || '''}\"><img src=\"../WEB/images/icn_delete.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM rumpun_pengembangan A
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
	
	function selectByParamsIndikatorPelatihanHcdpCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.rumpun_pengembangan_ID AS ID, A.rumpun_pengembangan_ID_PARENT PARENT_ID, A.NAMA
					, A.rumpun_pengembangan_ID ID_ROW
					, CASE A.rumpun_pengembangan_ID_PARENT
					WHEN '0' THEN ''
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''indikator_penilaian_add.php?reqPelatihanHcdpId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_edit.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM rumpun_pengembangan A
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
	
	function selectByParamsKompetensiPelatihanHcdpCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.rumpun_pengembangan_ID AS ID, A.rumpun_pengembangan_ID_PARENT PARENT_ID, A.NAMA
					, A.rumpun_pengembangan_ID ID_ROW
					, CASE A.rumpun_pengembangan_ID_PARENT
					WHEN '0' THEN ''
					ELSE
						CASE COALESCE(JUMLAH_DATA,0) WHEN 1 THEN
						'<a id=\"reqInfoSimpan' || A.rumpun_pengembangan_ID || '\" onClick=\"pilihrumpun_pengembangan(''' || A.rumpun_pengembangan_ID || ''', ''simpan'')\" style=\"cursor:pointer; display:none\" title=\"Pilih\"><img src=\"../WEB/images/icon_uncheck.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus' || A.rumpun_pengembangan_ID || '\" onClick=\"pilihrumpun_pengembangan(''' || A.rumpun_pengembangan_ID || ''', ''hapus'')\" style=\"cursor:pointer\" title=\"hapus\"><img src=\"../WEB/images/icon_check.png\" width=\"15px\" heigth=\"15px\"></a> '
						ELSE
						'<a id=\"reqInfoSimpan' || A.rumpun_pengembangan_ID || '\" onClick=\"pilihrumpun_pengembangan(''' || A.rumpun_pengembangan_ID || ''', ''simpan'')\" style=\"cursor:pointer;\" title=\"Pilih\"><img src=\"../WEB/images/icon_uncheck.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus' || A.rumpun_pengembangan_ID || '\" onClick=\"pilihrumpun_pengembangan(''' || A.rumpun_pengembangan_ID || ''', ''hapus'')\" style=\"cursor:pointer; display:none\" title=\"hapus\"><img src=\"../WEB/images/icon_check.png\" width=\"15px\" heigth=\"15px\"></a> '
						END
					END
					LINK_URL
					, A.PERMEN_ID
					FROM rumpun_pengembangan A
					LEFT JOIN
					(
						SELECT CASE WHEN COUNT(B.rumpun_pengembangan_ID) > 0 THEN 1 ELSE 0 END JUMLAH_DATA, A.rumpun_pengembangan_ID
						FROM kompetensi_training A
						LEFT JOIN rumpun_pengembangan B ON A.rumpun_pengembangan_ID = B.rumpun_pengembangan_ID
						GROUP BY A.rumpun_pengembangan_ID
					) B ON A.rumpun_pengembangan_ID = B.rumpun_pengembangan_ID
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
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM rumpun_pengembangan A
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
	function getCountByParamsKompetensiPelatihanHcdpCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.rumpun_pengembangan_ID AS ID, A.rumpun_pengembangan_ID_PARENT PARENT_ID, A.NAMA
					, A.rumpun_pengembangan_ID ID_ROW, A.TAHUN
					, CASE A.rumpun_pengembangan_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqTahun=' || A.TAHUN || '&reqPelatihanHcdpParentId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqTahun=' || A.TAHUN || '&reqPelatihanHcdpId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM rumpun_pengembangan A
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
	
	function getCountByParamsIndikatorPelatihanHcdpCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.rumpun_pengembangan_ID AS ID, A.rumpun_pengembangan_ID_PARENT PARENT_ID, A.NAMA
					, A.rumpun_pengembangan_ID ID_ROW
					, CASE A.rumpun_pengembangan_ID_PARENT
					WHEN '0' THEN ''
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''indikator_penilaian_add.php?reqTahun=' || A.TAHUN || '&reqPelatihanHcdpId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM rumpun_pengembangan A
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
	
	function getCountByParamsPelatihanHcdpCombo($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT COUNT(1) AS ROWCOUNT FROM
			(
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT
					A.rumpun_pengembangan_ID AS ID, A.rumpun_pengembangan_ID_PARENT PARENT_ID, A.NAMA
					, A.rumpun_pengembangan_ID ID_ROW
					, CASE A.rumpun_pengembangan_ID_PARENT
					WHEN '0'
					THEN
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqTahun=' || A.TAHUN || '&reqPelatihanHcdpParentId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					ELSE
					'<a onClick=\"window.OpenDHTMLPopUp(''rumpun_pengembangan_add.php?reqTahun=' || A.TAHUN || '&reqPelatihanHcdpId=' || A.rumpun_pengembangan_ID || ''')\"><img src=\"../WEB/images/icn_add.png\"></a>'
					END
					LINK_URL
					, A.PERMEN_ID
					FROM rumpun_pengembangan A
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
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

  class SatkerEselonAtribut extends Entity{ 

	var $query;
	var $db;
    /**
    * Class constructor.
    **/
    function SatkerEselonAtribut()
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
		$this->setField("SATKER_ESELON_ATRIBUT_ID", $this->getNextId("SATKER_ESELON_ATRIBUT_ID","satker_eselon_atribut")); 

		$str = "INSERT INTO satker_eselon_atribut (
				   SATKER_ESELON_ATRIBUT_ID, TAHUN, ESELON_ID, 
				   SATUAN_KERJA_ID, ATRIBUT_ID, ATRIBUT_PARENT_ID, ASPEK_ID)
				VALUES (
				  ".$this->getField("SATKER_ESELON_ATRIBUT_ID").",
				  '".$this->getField("TAHUN")."',
				  '".$this->getField("ESELON_ID")."',
				  '".$this->getField("SATUAN_KERJA_ID")."',
				  '".$this->getField("ATRIBUT_ID")."',
				  '".$this->getField("ATRIBUT_PARENT_ID")."',
				  '".$this->getField("ASPEK_ID")."'
				)"; 
				  //'".$this->getField("PATH")."'
		$this->query = $str;
		$this->id = $this->getField("SATKER_ESELON_ATRIBUT_ID");
		//echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE satker_eselon_atribut
				SET    
					   TAHUN= '".$this->getField("TAHUN")."',
					   ESELON_ID= '".$this->getField("ESELON_ID")."',
					   SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
					   ATRIBUT_ID= ".$this->getField("ATRIBUT_ID").",
					   ATRIBUT_PARENT_ID= ".$this->getField("ATRIBUT_PARENT_ID")."
				WHERE  SATKER_ESELON_ATRIBUT_ID= '".$this->getField("SATKER_ESELON_ATRIBUT_ID")."'
				"; //PATH= '".$this->getField("PATH")."'
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }
	
	function updateFormat()
	{
		$str = "
				UPDATE satker_eselon_atribut
				SET
					   UKURAN= ".$this->getField("UKURAN").",
					   FORMAT= '".$this->getField("FORMAT")."'
				WHERE  SATKER_ESELON_ATRIBUT_ID = '".$this->getField("SATKER_ESELON_ATRIBUT_ID")."' AND TAHUN = '".$this->getField("TAHUN")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM satker_eselon_atribut
                WHERE 
                  ESELON_ID= '".$this->getField("ESELON_ID")."' AND
				  SATUAN_KERJA_ID= '".$this->getField("SATUAN_KERJA_ID")."' AND
				  TAHUN= '".$this->getField("TAHUN")."' AND
				  ATRIBUT_ID LIKE '".$this->getField("ATRIBUT_ID")."%'
				"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }
	
    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","ATRIBUT_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY SATUAN_KERJA_ID ASC")
	{
		$str = "
		SELECT 
		   SATKER_ESELON_ATRIBUT_ID, TAHUN, ESELON_ID, SATUAN_KERJA_ID, ATRIBUT_ID, ATRIBUT_PARENT_ID, ASPEK_ID
		FROM satker_eselon_atribut A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsJabatanUnitKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY S.KODE_UNKER, A.POSITION ASC")
	{
		$str = "
		SELECT 
		S.KODE_UNKER JABATAN_ID, A.POSITION JABATAN_NAMA
		FROM ".$this->db.".user A 
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
	
	function selectByParamsJabatanAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $reqTahun= "", $reqAspekId="", $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT A.ID AS ID, A.PARENT_ID AS PARENT_ID, A.NAME NAMA, CASE A.PARENT_ID WHEN 0 THEN 0 ELSE 999 END ID_ROW
					, '' LINK_URL
					FROM ".$this->db.".division A
					WHERE 1=1 AND A.PARENT_ID = '0'
					UNION ALL
					SELECT
					CONCAT(A.ID, '-', ESELON_ID, '-0') AS ID, A.ID PARENT_ID, B.SVALUE NAMA, ESELON_ID ID_ROW
					, 
					";
					if($reqTahun == "")
					$str.= "'Pilih tahun dahulu'";
					else
					$str.= " CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''jabatan_atribut_tree_lookup.php?reqTahun=".$reqTahun."&reqAspekId=".$reqAspekId."&reqEselonId=',ESELON_ID,'&reqSatuanKerjaId=',A.ID,''')\"><img src=\"../WEB/images/icn_add.png\"></a>')";
					$str.= " 
					FROM ".$this->db.".division A,
					(
						SELECT A.ID, A.SKEY, A.SVALUE, REPLACE(A.SVALUE, ' ','') ESELON_INFO, 
						CASE REPLACE(A.SVALUE, ' ','')
						WHEN 'I.A' THEN '11' WHEN 'I.B' THEN '12' WHEN 'II.A' THEN '21' WHEN 'II.B' THEN '22'
						WHEN 'III.A' THEN '31' WHEN 'III.B' THEN '32' WHEN 'IV.A' THEN '41' WHEN 'IV.B' THEN '42' ELSE '99' END ESELON_ID
						from ".$this->db.".sysparam A
						WHERE A.SGROUP = 'esselon'
					) B
					WHERE 1=1 AND A.PARENT_ID = '0'
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
					FROM satker_eselon_atribut A
					INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
					WHERE 1=1 AND A.ASPEK_ID = ".$reqAspekId." AND A.TAHUN = '".$reqTahun."'
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
	
	function selectByParamsJabatanAtributComboEselonLengkap($paramsArray=array(),$limit=-1,$from=-1, $reqTahun= "", $reqAspekId="", $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$str = "
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT A.SATKER_ID AS ID, A.SATKER_ID_PARENT AS PARENT_ID, A.NAMA, CASE A.SATKER_ID_PARENT WHEN '0' THEN 0 ELSE 999 END ID_ROW
					, '' LINK_URL
					FROM ".$this->db.".satker A
					WHERE 1=1
					AND A.SATKER_ID_PARENT = '0'
					UNION ALL
					SELECT
					CONCAT(A.SATKER_ID, '-', ESELON_ID, '-0') AS ID, A.SATKER_ID PARENT_ID, B.SVALUE NAMA, ESELON_ID ID_ROW
					, 
					";
					if($reqTahun == "")
					$str.= "'Pilih tahun dahulu'";
					else
					$str.= " CONCAT('<a onClick=\"window.OpenDHTMLPopUp(''jabatan_atribut_tree_lookup.php?reqTahun=".$reqTahun."&reqAspekId=".$reqAspekId."&reqEselonId=',ESELON_ID,'&reqSatuanKerjaId=',A.SATKER_ID,''')\"><img src=\"../WEB/images/icn_add.png\"></a>')";
					$str.= " 
					LINK_URL
					FROM ".$this->db.".satker A,
					(
						SELECT ES.ESELON_ID, ES.NAMA SVALUE, REPLACE(ES.NAMA, ' ','') ESELON_INFO
						from ".$this->db.".eselon ES
						WHERE 1=1
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
					FROM satker_eselon_atribut A
					INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
					WHERE 1=1 AND A.ASPEK_ID = ".$reqAspekId." AND A.TAHUN = '".$reqTahun."'
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
	
	function selectByParamsPenggalianPenilaianJabatanAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $reqTahun= "", $reqAspekId="", $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$statement_aspek= "";
		if($reqAspekId == ""){}
		else
		$statement_aspek= " AND A.ASPEK_ID = ".$reqAspekId;
		
		if($reqTahun == ""){}
		else
		$statement_aspek= " AND A.TAHUN = '".$reqTahun."'";
		
		$str = "
				
				SELECT ID, PARENT_ID, NAMA, ID_ROW, LINK_URL
				FROM
				(
					SELECT A.ID AS ID, A.PARENT_ID AS PARENT_ID, A.NAME NAMA, CASE A.PARENT_ID WHEN 0 THEN 0 ELSE 999 END ID_ROW
					, '' LINK_URL
					FROM ".$this->db.".division A
					WHERE 1=1 AND A.PARENT_ID = '0'
					UNION ALL
					SELECT
					CONCAT(A.ID, '-', A.ESELON_ID, '-0') AS ID, A.ID PARENT_ID, A.NAMA, A.ESELON_ID ID_ROW, '' LINK_URL
					FROM
					(
						SELECT
						A.ID, B.ESELON_ID, A.ID PARENT_ID, B.SVALUE NAMA
						, '' LINK_URL
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
					) A
					INNER JOIN
					(
						SELECT
						A.SATUAN_KERJA_ID, A.ESELON_ID, A.ATRIBUT_PARENT_ID, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH
						FROM satker_eselon_atribut A
						INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
						WHERE 1=1 AND A.ATRIBUT_PARENT_ID = '0'
						GROUP BY A.SATUAN_KERJA_ID, A.ESELON_ID, A.ATRIBUT_PARENT_ID
					) B ON A.ID = B.SATUAN_KERJA_ID AND A.ESELON_ID = B.ESELON_ID
					UNION ALL
					SELECT
					CONCAT(A.SATUAN_KERJA_ID, '-', A.ESELON_ID, '-', A.ATRIBUT_ID) AS ID, CONCAT(A.SATUAN_KERJA_ID, '-', A.ESELON_ID, '-', A.ATRIBUT_PARENT_ID) PARENT_ID,
					CASE A.ATRIBUT_PARENT_ID WHEN '0'
					THEN CONCAT(B.NAMA, ' (', B.BOBOT, ')' )
					ELSE
					CONCAT(B.NAMA, ' (', B.NILAI_STANDAR, ')' )
					END NAMA
					, A.ATRIBUT_ID ID_ROW
					, CASE A.ATRIBUT_PARENT_ID WHEN '0'
					THEN ''
					ELSE
					link_penilaian_satker_jabatan(A.SATKER_ESELON_ATRIBUT_ID, A.TAHUN, A.ATRIBUT_ID, ' - ')
					END  LINK_URL
					FROM satker_eselon_atribut A
					INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
					WHERE 1=1 ".$statement_aspek."
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
	
	function selectByParamsPenggalianJabatanAtributCombo($paramsArray=array(),$limit=-1,$from=-1, $reqTahun= "", $reqAspekId="", $reqId="", $statement='', $sOrder="ORDER BY A.ID_ROW ASC")
	{
		$statement_aspek= "";
		if($reqAspekId == ""){}
		else
		$statement_aspek= " AND A.ASPEK_ID = ".$reqAspekId;
		
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
					CONCAT(A.ID, '-', A.ESELON_ID, '-0') AS ID, A.ID PARENT_ID, A.NAMA, A.ESELON_ID ID_ROW, '' LINK_URL
					FROM
					(
						SELECT
						A.ID, B.ESELON_ID, A.ID PARENT_ID, B.SVALUE NAMA
						, '' LINK_URL
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
					) A
					INNER JOIN
					(
						SELECT
						A.SATUAN_KERJA_ID, A.ESELON_ID, A.ATRIBUT_PARENT_ID, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH
						FROM satker_eselon_atribut A
						INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
						WHERE 1=1 AND A.ATRIBUT_PARENT_ID = '0'
						GROUP BY A.SATUAN_KERJA_ID, A.ESELON_ID, A.ATRIBUT_PARENT_ID
					) B ON A.ID = B.SATUAN_KERJA_ID AND A.ESELON_ID = B.ESELON_ID
					UNION ALL
					SELECT
					CONCAT(A.SATUAN_KERJA_ID, '-', A.ESELON_ID, '-', A.ATRIBUT_ID) AS ID, CONCAT(A.SATUAN_KERJA_ID, '-', A.ESELON_ID, '-', A.ATRIBUT_PARENT_ID) PARENT_ID,
					CASE A.ATRIBUT_PARENT_ID WHEN '0'
					THEN CONCAT(B.NAMA, ' (', B.BOBOT, ')' )
					ELSE
					CONCAT(B.NAMA, ' (', B.NILAI_STANDAR, ')' )
					END NAMA
					, A.ATRIBUT_ID ID_ROW
					, CASE A.ATRIBUT_PARENT_ID WHEN '0'
					THEN ''
					ELSE
					CASE WHEN C.STATUS = 1 THEN
					CONCAT('<a style=\"display:none\" id=\"reqInfoSimpan',A.SATKER_ESELON_ATRIBUT_ID,'\" onClick=\"pilihatribut(''',A.SATKER_ESELON_ATRIBUT_ID,''', ''simpan'')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icon_pilih.png\" width=\"15px\" heigth=\"15px\"></a><a id=\"reqInfoHapus',A.SATKER_ESELON_ATRIBUT_ID,'\" onClick=\"pilihatribut(''',A.SATKER_ESELON_ATRIBUT_ID,''', ''hapus'')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icn_delete.png\" width=\"15px\" heigth=\"15px\"></a>')
					ELSE
					CONCAT('<a id=\"reqInfoSimpan',A.SATKER_ESELON_ATRIBUT_ID,'\" onClick=\"pilihatribut(''',A.SATKER_ESELON_ATRIBUT_ID,''', ''simpan'')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icon_pilih.png\" width=\"15px\" heigth=\"15px\"></a><a style=\"display:none\" id=\"reqInfoHapus',A.SATKER_ESELON_ATRIBUT_ID,'\" onClick=\"pilihatribut(''',A.SATKER_ESELON_ATRIBUT_ID,''', ''hapus'')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icn_delete.png\" width=\"15px\" heigth=\"15px\"></a>')
					END
					END  LINK_URL
					FROM satker_eselon_atribut A
					INNER JOIN atribut B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.TAHUN = B.TAHUN AND A.ASPEK_ID = B.ASPEK_ID
					LEFT JOIN
					(
						SELECT PN.SATKER_ESELON_ATRIBUT_ID, CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END STATUS
						FROM penggalian_penilaian PN 
						INNER JOIN penggalian P ON PN.PENGGALIAN_ID = P.PENGGALIAN_ID
						WHERE 1=1
						AND PN.PENGGALIAN_ID = 1
						GROUP BY PN.SATKER_ESELON_ATRIBUT_ID
					) C ON A.SATKER_ESELON_ATRIBUT_ID = C.SATKER_ESELON_ATRIBUT_ID
					WHERE 1=1 ".$statement_aspek."
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
				CASE WHEN (SELECT X.ATRIBUT_ID FROM satker_eselon_atribut X WHERE X.ESELON_ID = '".$reqEselonId."' AND X.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."' AND A.ATRIBUT_ID = X.ATRIBUT_ID) = A.ATRIBUT_ID
				THEN '' 
				ELSE 
				CONCAT('<a onClick=\"pilihatribut(''',ATRIBUT_ID,''')\" style=\"cursor:pointer\" title=\"Pilih\"><img src=\"../WEB/images/icon_pilih.png\" width=\"20px\" heigth=\"20px\"></a>')
				END LINK_URL,
				CASE WHEN (SELECT X.ATRIBUT_ID FROM satker_eselon_atribut X WHERE X.ESELON_ID = '".$reqEselonId."' AND X.SATUAN_KERJA_ID = '".$reqSatuanKerjaId."' AND A.ATRIBUT_ID = X.ATRIBUT_ID) = A.ATRIBUT_ID
				THEN '1' ELSE '' END KONDISI_STATUS
				FROM atribut A
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","ATRIBUT_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT FROM satker_eselon_atribut WHERE SATKER_ESELON_ATRIBUT_ID IS NOT NULL ".$statement; 
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
  } 
?>
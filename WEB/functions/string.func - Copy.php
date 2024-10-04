<?
include_once("../WEB/classes/base/PerubahanData.php");
/* *******************************************************************************************************
MODUL NAME 			: 
FILE NAME 			: string.func.php
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle string operation
***************************************************************************************************** */



/* fungsi untuk mengatur tampilan mata uang
 * $value = string
 * $digit = pengelompokan setiap berapa digit, default : 3
 * $symbol = menampilkan simbol mata uang (Rupiah), default : false
 * $minusToBracket = beri tanda kurung pada nilai negatif, default : true
 */
function currencyToPage($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
{
	if($value < 0)
	{
		$neg = "-";
		$value = str_replace("-", "", $value);
	}
	else
		$neg = false;
		
	$cntValue = strlen($value);
	//$cntValue = strlen($value);
	
	if($cntValue <= $digit)
		$resValue =  $value;
	
	$loopValue = floor($cntValue / $digit);
	
	for($i=1; $i<=$loopValue; $i++)
	{
		$sub = 0 - $i; //ubah jadi negatif
		$tempValue = $endValue;
		$endValue = substr($value, $sub*$digit, $digit);
		$endValue = $endValue;
		
		if($i !== 1)
			$endValue .= ".";
		
		$endValue .= $tempValue;
	}
	
	$beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
	
	if($cntValue % $digit == 0)
		$resValue = $beginValue.$endValue;
	else if($cntValue > $digit)
		$resValue = $beginValue.".".$endValue;
	
	//additional
	if($symbol == true && $resValue !== "")
	{
		$resValue = "Rp ".$resValue.",-";
	}
	
	if($minusToBracket && $neg)
	{
		$resValue = "(".$resValue.")";
		$neg = "";
	}
	
	if($minusLess == true)
	{
		$neg = "";
	}
	
	$resValue = $neg.$resValue;
	
	//$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";

	return $resValue;
}

function numberToIna($value, $symbol=true, $minusToBracket=true, $minusLess=false, $digit=3)
{
	$arr_value = explode(".", $value);
	
	if(count($arr_value) > 1)
		$value = $arr_value[0];
	
	if($value < 0)
	{
		$neg = "-";
		$value = str_replace("-", "", $value);
	}
	else
		$neg = false;
		
	$cntValue = strlen($value);
	//$cntValue = strlen($value);
	
	if($cntValue <= $digit)
		$resValue =  $value;
	
	$loopValue = floor($cntValue / $digit);
	
	for($i=1; $i<=$loopValue; $i++)
	{
		$sub = 0 - $i; //ubah jadi negatif
		$tempValue = $endValue;
		$endValue = substr($value, $sub*$digit, $digit);
		$endValue = $endValue;
		
		if($i !== 1)
			$endValue .= ".";
		
		$endValue .= $tempValue;
	}
	
	$beginValue = substr($value, 0, $cntValue - ($loopValue * $digit));
	
	if($cntValue % $digit == 0)
		$resValue = $beginValue.$endValue;
	else if($cntValue > $digit)
		$resValue = $beginValue.".".$endValue;
	
	//additional
	if($symbol == true && $resValue !== "")
	{
		$resValue = $resValue;
	}
	
	if($minusToBracket && $neg)
	{
		$resValue = "(".$resValue.")";
		$neg = "";
	}
	
	if($minusLess == true)
	{
		$neg = "";
	}

	if(count($arr_value) == 1)
		$resValue = $neg.$resValue;
	else
		$resValue = $neg.$resValue.",".$arr_value[1];
	

	
	//$resValue = "<span style='white-space:nowrap'>".$resValue."</span>";

	return $resValue;
}

function dotToComma($varId)
{
	$newId = str_replace(".", ",", $varId);	
	return $newId;
}


// fungsi untuk generate nol untuk melengkapi digit

function generateZero($varId, $digitGroup, $digitCompletor = "0")
{
	$newId = "";
	
	$lengthZero = $digitGroup - strlen($varId);
	
	for($i = 0; $i < $lengthZero; $i++)
	{
		$newId .= $digitCompletor;
	}
	
	$newId = $newId.$varId;
	
	return $newId;
}

// truncate text into desired word counts.
// to support dropDirtyHtml function, include default.func.php
function truncate($text, $limit, $dropDirtyHtml=true)
{
	$tmp_truncate = array();
	$text = str_replace("&nbsp;", " ", $text);
	$tmp = explode(" ", $text);
	
	for($i = 0; $i <= $limit; $i++)		//truncate how many words?
	{
		$tmp_truncate[$i] = $tmp[$i];
	}
	
	$truncated = implode(" ", $tmp_truncate);
	
	if ($dropDirtyHtml == true and function_exists('dropAllHtml'))
		return dropAllHtml($truncated);
	else
		return $truncated;
}

function arrayMultiCount($array, $field_name, $search)
{
	$summary = 0;
	for($i = 0; $i < count($array); $i++)
	{
		if($array[$i][$field_name] == $search)
			$summary += 1;
	}
	return $summary;
}

function getValueArray($var)
{
	//$tmp = "";
	for($i=0;$i<count($var);$i++)
	{			
		if($i == 0)
			$tmp .= $var[$i];
		else
			$tmp .= "*".$var[$i];
	}
	
	return $tmp;
}

function getValueKoma($var)
{
	if($var == '')
		$tmp = '';
	else
		$tmp = ',';	
	
	return $tmp;
}

function getValueOperator($var)
{
	if($var == 0)
		$tmp = ' AND ';
	else
		$tmp = ' OR ';	
	
	return $tmp;
}

function getValueANDOperator($var)
{
	$tmp = ' AND ';
	
	return $tmp;
}

function getValueArrayCetakBr($var)
{
	//$tmp = "";
	for($i=0;$i<count($var);$i++)
	{			
		if($i == 0)
			$tmp .= $var[$i];
		else
			$tmp .= "\n".$var[$i];
	}
	
	return $tmp;
}

function getTipePegawaiStatistik($var)
{
	if($var == 'Pejabat')			$nm = 'Pejabat Struktural';
	elseif($var == 'Staf')			$nm = 'Fungsional Umum/Staf';
	elseif($var == 'Pendidikan')	$nm = 'Fungsional Khusus/Pendidikan';
	elseif($var == 'Kesehatan')		$nm = 'Fungsional Khusus/Kesehatan';
	else							$nm = 'Fungsional Khusus/Lain-lain';
	
	return $nm;
}

function getWarnaStatistik($var)
{
	if($var == 0)		{$clr = '#00FF00';}
	elseif($var == 1)	{$clr = '#00FFFF';}
	elseif($var == 2)	{$clr = '#FFF000';}
	elseif($var == 3)	{$clr = '#838587';}
	elseif($var == 4)	{$clr = '#31c58f';}
	elseif($var == 5)	{$clr = '#81c531';}
	elseif($var == 6)	{$clr = '#c0c531';}
	elseif($var == 7)	{$clr = '#c58431';}
	elseif($var == 8)	{$clr = '#c54931';}
	elseif($var == 9)	{$clr = '#e71313';}
	elseif($var == 10){$clr = '#afa1a1';}	
	
	return $clr;
}

function getValueTable($value, $dua_tabel='')
{
	if(substr($value, 0, 2) == "[]"){
		if($dua_tabel == '')
			return 'background-color:#F00';
		else
			return 'bgcolor="#FF0000"';
	}		
	else
		return "";
}
function getValueTableAdmin($value, $id)
{
	//$tes = "[]Alimin[]MacMan";
	if(is_int($id))
		$color = "FFFF99";
	else
		$color = "FF0000";
			
	if(substr($value, 0, 2) == "[]")
		return "bgcolor='#".$color."'";
	else
		return "";
}

function getValueInput($value)
{
	if(substr($value, 0, 2) == "[]")
	{
		$explode = explode('[]',$value);
		return $explode[2];
	}
	else
		return $value;
}

function getValueBalloon($value, $_date='')
{
	if(substr($value, 0, 2) == "[]")
	{
		$explode = explode('[]',$value);
			if(trim($explode[1]) == "")
				return '<a href="#" class="cute-balloon" clase="gray" tag="-belum di entri-" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
			elseif($_date == 1){
				$arrDate = explode("-", $explode[1]);
				$_date= $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
				return '<a href="#" class="cute-balloon" clase="gray" tag="'.$_date.'" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
			}
			else
				return '<a href="#" class="cute-balloon" clase="gray" tag="'.$explode[1].'" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
	}
	else
		return "";
}

/*function getValueBalloon($value)
{
	if(substr($value, 0, 2) == "[]")
	{
		$explode = explode('[]',$value);
			if(trim($explode[1]) == "")
				return "";
			else
				return '<a href="#" class="cute-balloon" clase="gray" tag="'.$explode[1].'" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
	}
	else
		return "";
}*/

function getSetValueBalloon($value)
{
	if(substr($value, 0, 2) == "[]")
	{
		$explode = explode('[]',$value);
			if(trim($explode[1]) == "")
				return "";
			else
				return $explode[1];
	}
	else
		return "";
}

function getGetValueBalloon($value)
{
	if($value == "")
		return '<a href="#" class="cute-balloon" clase="gray" tag="-belum di entri-" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
	else
		return '<a href="#" class="cute-balloon" clase="gray" tag="'.$value.'" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
}

function getGetValueBalloonImage($value)
{
	if($value == "")
		return "";
	else
		return '<a href="#" class="cute-balloon" clase="gray" tag="<img src="'.$value.' width=\"10px\" height=\"10px\">" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';
}

function generateSqlView($field, $table, $pegawai_id, $primary_name, $primary_value)
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " CHECK_PERUBAHAN_DATA(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."') ".trim($explode[$i])." ";					
		else
			$sql .= " CHECK_PERUBAHAN_DATA(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."') ".trim($explode[$i]).", ";					
	}
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$primary_value."' ";
	$sql .= " UNION ALL ";
	$sql .= " SELECT ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i])."
					 ";					
		elseif($i == 0)
			$sql .= " 	(SELECT 
				   		'[][]' || PERUBAHAN_DATA_ID
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i]).",
					 ";							
		else
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i]).",
					 ";					
	}
			
	$sql .=	"FROM DUAL) A  WHERE ".$primary_name." IS NOT NULL ";	
	return $sql;
}

function generateSqlMonitoring($field, $table, $pegawai_id, $primary_name, $primary_value)
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i])." ";					
		else
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
	}
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$primary_value."' ";
	$sql .= " UNION ALL ";
	$sql .= " SELECT ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i])."
					 ";					
		elseif($i == 0)
			$sql .= " 	(SELECT 
				   		'[][]' || PERUBAHAN_DATA_ID
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i]).",
					 ";							
		else
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i]).",
					 ";					
	}
			
	$sql .=	"FROM DUAL) A  WHERE ".$primary_name." IS NOT NULL ";	
	return $sql;
}

function generateSqlDetil($field, $table, $pegawai_id, $primary_name, $primary_value)
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i])." ";					
		else
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
	}
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$primary_value."' ";
	$sql .= " UNION ALL ";
	$sql .= " SELECT ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i])."
					 ";					
		elseif($i == 0)
			$sql .= " 	(SELECT 
				   		CASE WHEN TIPE_PERUBAHAN_DATA = 'I' THEN '[][]' || PERUBAHAN_DATA_ID ELSE '[][]' || PERUBAHAN_DATA_ID END
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i]).",
					 ";							
		else
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."') ".trim($explode[$i]).",
					 ";					
	}
			
	$sql .=	"FROM DUAL) A ";	
	return $sql;
}

function generateSqlMonitoringSpesifik($field, $table, $pegawai_id, $primary_name, $primary_value, $statement='', $foto_blob_baru='')
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1){
			/*if($explode[$i] == ' FOTO_BLOB'){
				$sql .= " CHECK_PERUBAHAN_DATA_MON_BLOB(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i]).", ";
				//echo '---'.$explode[$i].'---';
			}
			else{*/
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i])." ";
			//}
		}
		else
			if(strpos_array($explode[$i], array('TANGGAL', 'TMT')) == true)
				$sql .= " CHECK_PERUBAHAN_DATA_MON(TO_CHAR(".trim($explode[$i]).", 'DD-MM-YYYY'), '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i]).", ";
			else
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i]).", ";					
		
	}
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$primary_value."' ";
	$sql .= " UNION ALL ";
	
	$perubahan_data = new PerubahanData();
	$perubahan_data->selectByParamsUnique(array("PEGAWAI_ID" => $pegawai_id, "FORM_FIP" => $table));
	$i = 0;	$temp_perubahan_data_id='0';
	while($perubahan_data->nextRow())
	{
		if($i == 0)
		{
			$temp_perubahan_data_id=$perubahan_data->getField("PERUBAHAN_DATA_UNIQUE");
		}
		else
		$sql .= " UNION ALL ";	

		$sql .= " SELECT ";
		for($i=0;$i<count($explode);$i++)
		{
			if($i== count($explode)-1)
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND
							PERUBAHAN_DATA_UNIQUE = '".$perubahan_data->getField("PERUBAHAN_DATA_UNIQUE")."' AND VALIDASI IS NULL 
							) ".trim($explode[$i])."
						 ";					
			elseif($i == 0)
				$sql .= " 	(SELECT 
							'[][]' || '".$perubahan_data->getField("PERUBAHAN_DATA_UNIQUE")."'
							FROM DUAL) ".trim($explode[$i]).",
						 ";
			else
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND
							PERUBAHAN_DATA_UNIQUE = '".$perubahan_data->getField("PERUBAHAN_DATA_UNIQUE")."' AND VALIDASI IS NULL
							) ".trim($explode[$i]).",
						 ";					
		}
		/*if($foto_blob_baru){
			$sql .= " , (SELECT 
				'[][]' || FOTO_BLOB_BARU ISI_BARU
				FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = 'FOTO_BLOB' AND 
				FORM_FIP = '".$table."' AND
				PEGAWAI_ID = '".$pegawai_id."' AND
				PERUBAHAN_DATA_UNIQUE = '".$temp_perubahan_data_id."' AND VALIDASI IS NULL
				) FOTO_BLOB_BARU
			 ";
		}*/
		
		$sql .= " FROM DUAL ";
		$i++;
	}
	
	if($i == 0)
	{
		$sql .= " SELECT ";
		for($i=0;$i<count($explode);$i++)
		{
			if($i== count($explode)-1)
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."'  AND VALIDASI IS NULL AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i])."
						 ";					
			elseif($i == 0)
				$sql .= " 	(SELECT 
							CASE WHEN TIPE_PERUBAHAN_DATA = 'I' THEN '[][]' || PERUBAHAN_DATA_UNIQUE ELSE '[][]' || PERUBAHAN_DATA_ID END
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND VALIDASI IS NULL AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
						 ";							
			else
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND VALIDASI IS NULL AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
						 ";					
		}
		
		/*if($foto_blob_baru){
			$sql .= " , (SELECT 
				'[][]' || FOTO_BLOB_BARU ISI_BARU
				FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
				FORM_FIP = '".$table."' AND
				PEGAWAI_ID = '".$pegawai_id."' AND
				PERUBAHAN_DATA_UNIQUE = '".$perubahan_data->getField("PERUBAHAN_DATA_UNIQUE")."' AND VALIDASI IS NULL
				) ".trim($explode[$i])."
			 ";
		}*/
		$sql .= " FROM DUAL ";
	}
			
	$sql .=	") A  WHERE ".$primary_name." IS NOT NULL ".$statement." ";	
	return $sql;
}

function generateSqlDetilSpesifik($field, $table, $pegawai_id, $primary_name, $primary_value)
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i])." ";					
		else
		{			
			if(strpos_array($explode[$i], array('TANGGAL', 'TMT', 'BULAN_BAYAR')) == true)
				$sql .= " CHECK_PERUBAHAN_DATA_MON(TO_CHAR(".trim($explode[$i]).", 'DD-MM-YYYY'), '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
			else
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
		}
	}
	
	if(is_numeric($primary_value))
		$value = $primary_value;
	else 
		$value = 0;
			
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$value."' ";
	$sql .= " UNION ALL ";
	$sql .= " SELECT ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."' AND
						PERUBAHAN_DATA_UNIQUE = '".$primary_value."' AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i])."
					 ";					
		elseif($i == 0)
			$sql .= " 	(SELECT 
				   		CASE WHEN TIPE_PERUBAHAN_DATA = 'I' THEN '[][]' || PERUBAHAN_DATA_UNIQUE ELSE '[][]' || PERUBAHAN_DATA_ID END
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."' AND
						PERUBAHAN_DATA_UNIQUE = '".$primary_value."' AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
					 ";							
		else
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."' AND
						PERUBAHAN_DATA_UNIQUE = '".$primary_value."' AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
					 ";					
	}
			
	$sql .=	"FROM DUAL) A ";	
	return $sql;
}

function generateSqlMonitoringOrangTua($field, $table, $pegawai_id, $primary_name, $primary_value, $jenis_kelamin, $tipe_form, $stat='')
{
	$unique = new PerubahanData();
	$unique->selectByParams(array("FORM_FIP_FIELD" => JENIS_KELAMIN,
								  "FORM_FIP"	=> $tipe_form,
								  "ISI_BARU"	=> $jenis_kelamin,
								  "PEGAWAI_ID" 	=> $pegawai_id), -1, -1, $stat);
	$unique->firstRow();
	//echo $unique->query;
	$unique_id = $unique->getField('PERUBAHAN_DATA_UNIQUE');
	unset($unique);
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i])." ";					
		else
			if(strpos_array($explode[$i], array('TANGGAL', 'TMT')) == true)
				$sql .= " CHECK_PERUBAHAN_DATA_MON(TO_CHAR(".trim($explode[$i]).", 'DD-MM-YYYY'), '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i]).", ";					
			else
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', ".$table."_ID) ".trim($explode[$i]).", ";					
		
	}
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$primary_value."' AND JENIS_KELAMIN = '".$jenis_kelamin."'";
	$sql .= " UNION ALL ";
	
	$perubahan_data = new PerubahanData();
	$perubahan_data->selectByParamsUnique(array("PEGAWAI_ID" => $pegawai_id, "FORM_FIP" => $table));
	$i = 0;	
	while($perubahan_data->nextRow())
	{
		if($i == 0)
		{}
		else
		$sql .= " UNION ALL ";	

		$sql .= " SELECT ";
		for($i=0;$i<count($explode);$i++)
		{
			if($i== count($explode)-1)
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND
							PERUBAHAN_DATA_UNIQUE = '".$unique_id."' AND VALIDASI IS NULL 
							) ".trim($explode[$i])."
						 ";					
			elseif($i == 0)
				$sql .= " 	(SELECT 
							'[][]' || '".$unique_id."'
							FROM DUAL) ".trim($explode[$i]).",
						 ";							
			else
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND
							PERUBAHAN_DATA_UNIQUE = '".$unique_id."' AND VALIDASI IS NULL
							) ".trim($explode[$i]).",
						 ";					
		}
		$sql .= " FROM DUAL ";
		$i++;
	}
	
	if($i == 0)
	{
		$sql .= " SELECT ";
		for($i=0;$i<count($explode);$i++)
		{
			if($i== count($explode)-1)
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."'  AND VALIDASI IS NULL AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i])."
						 ";					
			elseif($i == 0)
				$sql .= " 	(SELECT 
							CASE WHEN TIPE_PERUBAHAN_DATA = 'I' THEN '[][]' || PERUBAHAN_DATA_UNIQUE ELSE '[][]' || PERUBAHAN_DATA_ID END
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND VALIDASI IS NULL AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
						 ";							
			else
				$sql .= " 	(SELECT 
							'[][]' || ISI_BARU ISI_BARU
							FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
							FORM_FIP = '".$table."' AND
							PEGAWAI_ID = '".$pegawai_id."' AND VALIDASI IS NULL AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
						 ";					
		}		
		$sql .= " FROM DUAL ";
	}
			
	$sql .=	") A  WHERE ".$primary_name." IS NOT NULL ";	
	return $sql;
}

function generateSqlDetilOrangTua($field, $table, $pegawai_id, $primary_name, $primary_value)
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i])." ";					
		else
		{			
			if(strpos_array($explode[$i], array('TANGGAL', 'TMT', 'BULAN_BAYAR')) == true)
				$sql .= " CHECK_PERUBAHAN_DATA_MON(TO_CHAR(".trim($explode[$i]).", 'DD-MM-YYYY'), '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
			else
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
		}
	}
	
	if(is_numeric($primary_value))
		$value = $primary_value;
	else 
		$value = 0;
			
	$sql .=	" FROM ".$table." WHERE ".$primary_name." = '".$value."' ";
	$sql .= " UNION ALL ";
	$sql .= " SELECT ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."' AND
						PERUBAHAN_DATA_UNIQUE = '".$primary_value."' AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i])."
					 ";					
		elseif($i == 0)
			$sql .= " 	(SELECT 
				   		CASE WHEN TIPE_PERUBAHAN_DATA = 'I' THEN '[][]' || PERUBAHAN_DATA_UNIQUE ELSE '[][]' || PERUBAHAN_DATA_ID END
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."' AND
						PERUBAHAN_DATA_UNIQUE = '".$primary_value."' AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
					 ";							
		else
			$sql .= " 	(SELECT 
				   		'[][]' || ISI_BARU ISI_BARU
					 	FROM PERUBAHAN_DATA WHERE FORM_FIP_FIELD = '".trim($explode[$i])."' AND 
						FORM_FIP = '".$table."' AND
						PEGAWAI_ID = '".$pegawai_id."' AND
						PERUBAHAN_DATA_UNIQUE = '".$primary_value."' AND TIPE_PERUBAHAN_DATA = 'I') ".trim($explode[$i]).",
					 ";					
	}
			
	$sql .=	"FROM DUAL) A ";	
	return $sql;
}

function strpos_array($haystack, $needles) {
    if ( is_array($needles) ) {
        foreach ($needles as $str) {
            if ( is_array($str) ) {
                $pos = strpos_array($haystack, $str);
            } else {
                $pos = strpos($haystack, $str);
            }
            if ($pos !== FALSE) {
                return $pos;
            }
        }
    } else {
        return strpos($haystack, $needles);
    }
}

function in_array_column($text, $column, $array)
{
    if (!empty($array) && is_array($array))
    {
        for ($i=0; $i < count($array); $i++)
        {
            if ($array[$i][$column]==$text || strcmp($array[$i][$column],$text)==0) 
				$arr[] = $i;
        }
		return $arr;
    }
    return "";
}

function getExe($tipe)
{
	switch ($tipe) {
	  case "application/pdf": $ctype="pdf"; break;
	  case "application/octet-stream": $ctype="exe"; break;
	  case "application/zip": $ctype="zip"; break;
	  case "application/msword": $ctype="doc"; break;
	  case "application/vnd.ms-excel": $ctype="xls"; break;
	  case "application/vnd.ms-powerpoint": $ctype="ppt"; break;
	  case "image/gif": $ctype="gif"; break;
	  case "image/png": $ctype="png"; break;
	  case "image/jpeg": $ctype="jpeg"; break;
	  case "image/jpg": $ctype="jpg"; break;
	  case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": $ctype="xlsx"; break;
	  case "application/vnd.openxmlformats-officedocument.wordprocessingml.document": $ctype="docx"; break;
	  default: $ctype="application/force-download";
	} 
	
	return $ctype;
}

function setNULL($var)
{	
	if($var == '')
		$tmp = 'NULL';
	else
		$tmp = $var;
	
	return $tmp;
}

function setQuote($var, $status='')
{	
	if($status == 1)
		$tmp= str_replace("\'", "''", $var);
	else
		$tmp= str_replace("'", "''", $var);
	return $tmp;
}

?>
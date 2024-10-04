<?
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

function commaToDot($varId)
{
	$newId = str_replace(",", ".", $varId);	
	return $newId;
}

function ValToNullDB($varId)
{
	if(isNumeric($varId) == '0' || $varId == '')
	{
		if($varId == '')
			return 'NULL';
		elseif($varId == 'null')
			return 'NULL';
		else
			return $varId;
	}
	else
	{
		if($varId == '')
			return 'NULL';
		elseif($varId == 'null')
			return 'NULL';
		else
			return "'".$varId."'";
	}
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
			return 'background-color:#ffafaf';
		else
			return 'bgcolor="#ffafaf"';
	}		
	else
		return "";
}

function getWarnaTable($value, $id, $rowId)
{
	if($rowId == "")
	{
		return 'bgcolor="#ffafaf"';
	}
	else
	{
		$obj = json_decode($value);
		if($obj->{strtoupper($id)}{1} == strtoupper($id))
			return 'bgcolor="#ffafaf"';
		else
			return "";
	}
}

function getWarnaHapusTable($value, $id, $rowId, $reqHapusId)
{
	if($reqHapusId == "")
	{
		return getWarnaTable($value, $id, $rowId);
	}
	else
	{
		return 'bgcolor="#990000"';
	}
}

function getWarnaInputId($value, $id, $rowId, $reqDetilId)
{
	if($reqDetilId == "" && $rowId == ""){}
	else
	{
		if($rowId == "")
		{
			return "background-color:#ffafaf";
		}
		else
		{
			$obj = json_decode($value);
			if($obj->{strtoupper($id)}{1} == strtoupper($id))
				return "background-color:#ffafaf";
			else
				return "";
		}
	}
}

function getWarnaInputIdTanggal($value, $id, $rowId, $reqDetilId)
{
	if($reqDetilId == "" && $rowId == ""){}
	else
	{
		if($rowId == "")
		{
			return " \" data-options=\"validType:'validasiTanggal'\" ";
		}
		else
		{
			$obj = json_decode($value);
			if($obj->{strtoupper($id)}{1} == strtoupper($id))
				return " \" data-options=\"validType:'validasiTanggal'\" ";
			else
				return "";
		}
	}

}

function getWarnaInputTanggal($value, $id)
{
	$obj = json_decode($value);
	if($obj->{strtoupper($id)}{1} == strtoupper($id))
		return " \" data-options=\"validType:'validasiTanggal'\" ";
	else
		return "";
/*
	$arrValue = explode(",", $value);
	for($i=0;$i<count($arrValue);$i++)
	{		
		if($arrValue[$i] == $id)
			return 'background-color:#ffafaf';
	}	
		return "";
*/		

}

 
function getWarnaInput($value, $id)
{
	$obj = json_decode($value);
	if($obj->{strtoupper($id)}{1} == strtoupper($id))
		return "background-color:#ffafaf";
	else
		return "";
/*
	$arrValue = explode(",", $value);
	for($i=0;$i<count($arrValue);$i++)
	{		
		if($arrValue[$i] == $id)
			return 'background-color:#ffafaf';
	}	
		return "";
*/		

}

function getBallonInput($value, $id)
{
	$obj = json_decode($value);
	if($obj->{strtoupper($id)}{1} == strtoupper($id))
	{
		if($obj->{$id}{0} == "" || $obj->{$id}{0} == "0" || $obj->{$id}{0} == "1111-11-11")
			return '<a href="#" class="cute-balloon" clase="gray" tag="-belum di entri-" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';		
		else
			return '<a href="#" class="cute-balloon" clase="gray" tag="'.$obj->{strtoupper($id)}{0}.'" ><img src="cute-balloon/gris/suggestion.png" width="10px" height="10px" alt=""></a>';			
	}
	else
		return "";
/*
	$arrValue = explode(",", $value);
	for($i=0;$i<count($arrValue);$i++)
	{		
		if($arrValue[$i] == $id)
			return 'background-color:#ffafaf';
	}	
		return "";
*/		

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

function generateSqlDetilSpesifik($field, $table, $pegawai_id, $primary_name, $primary_value)
{
	$explode = explode(",", $field);
	$sql = " SELECT * FROM (
			 SELECT 
		    ";
	for($i=0;$i<count($explode);$i++)
	{
		if($i== count($explode)-1)
		{
			if(strpos_array($explode[$i], array('TANGGAL', 'TMT', 'BULAN_BAYAR', 'AWAL_BAYAR', 'AKHIR_BAYAR')) == true)
				$sql .= " CHECK_PERUBAHAN_DATA_MON(TO_CHAR(".trim($explode[$i]).", 'DD-MM-YYYY'), '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i])." ";					
			else
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i])." ";
		}
		else
		{			
			if(strpos_array($explode[$i], array('TANGGAL', 'TMT', 'BULAN_BAYAR', 'AWAL_BAYAR', 'AKHIR_BAYAR')) == true)
				$sql .= " CHECK_PERUBAHAN_DATA_MON(TO_CHAR(".trim($explode[$i]).", 'DD-MM-YYYY'), '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
			else
				$sql .= " CHECK_PERUBAHAN_DATA_MON(".trim($explode[$i]).", '".trim($explode[$i])."', '".$table."', '".$pegawai_id."', '".$primary_value."') ".trim($explode[$i]).", ";					
		}
	}
	
	
	if(preg_match("/^[0-9]+$/",$primary_value))
	{
		$value = $primary_value;
	}
	else
	{
		$value = 0;
	}
	
	/*if( isNumeric($primary_value))
		$value = $primary_value;
	else 
		$value = 0;
	*/
	
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
	
	if(preg_match("/^[0-9]+$/",$primary_value))
	{
		$value = $primary_value;
	}
	else
	{
		$value = 0;
	}
	
	/*if( isNumeric($primary_value))
		$value = $primary_value;
	else 
		$value = 0;
	*/
			
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

function separator($var, $delimeter=",")
{
	if($var == "")
		return "";
	else
		return $delimeter;
}

function setUniqId()
{
	$var = uniqid()."x";
	return $var;
}

function isNumeric($var)
{
	if(preg_match("/^[0-9]+$/",$var))
	{
		$value = '0';
	}
	else
	{
		$value = 'x';
	}
	
	return $value;
}

function dotToNo($varId)
{
	$newId = str_replace(".", "", $varId);	
	return $newId;
}

function setTipePerubahan($var)
{
	if($var == "I")
		return "valsix";
	else
		return '123';
}

function getFipNama($var)
{
   if('PEGAWAI' == $var)
    return 'FIP - 01, Identitas Pegawai';
   elseif('PENGALAMAN' == $var)
    return 'FIP - 01, Pengalaman Kerja';
   elseif('SK_CPNS' == $var)
    return 'FIP - 01, SK CPNS';
   elseif('SK_PNS' == $var)
    return 'FIP - 01, SK PNS';
   elseif('PANGKAT_RIWAYAT' == $var)
    return 'FIP - 02, Riwayat Pangkat';
   elseif('JABATAN_RIWAYAT' == $var)
    return 'FIP - 02, Riwayat Jabatan';
   elseif('GAJI_RIWAYAT' == $var)
    return 'FIP - 02, Riwayat Gaji';
   elseif('PENDIDIKAN_RIWAYAT' == $var)
    return 'FIP - 02, Pendidikan Umum';
   elseif('DIKLAT_STRUKTURAL' == $var)
    return 'FIP - 02, Diklat Struktural';
   elseif('DIKLAT_FUNGSIONAL' == $var)
    return 'FIP - 02, Diklat Fungsional';
   elseif('DIKLAT_TEKNIS' == $var)
    return 'FIP - 02, Diklat Teknis';
   elseif('DIKLAT_LPJ' == $var)
    return 'FIP - 02, Diklat Lpj';
   elseif('PENATARAN_SEMINAR' == $var)
    return 'FIP - 02, Penataran';
   elseif('SEMINAR' == $var)
    return 'FIP - 02, Seminar';
   elseif('KURSUS' == $var)
    return 'FIP - 02, Kursus Umum';
   elseif('KURSUS_KHUSUS' == $var)
    return 'FIP - 02, Kursus Khusus';
   elseif('ORANG_TUA' == $var)
    return 'FIP - 02, Orang Tua';
   elseif('MERTUA' == $var)
    return 'FIP - 02, Mertua';
   elseif('SUAMI_ISTRI' == $var)
    return 'FIP - 02, Suami Istri';
   elseif('ANAK' == $var)
    return 'FIP - 02, Anak';
   elseif('SAUDARA' == $var)
    return 'FIP - 02, Saudara';
   elseif('ORGANISASI_RIWAYAT' == $var)
    return 'FIP - 02, Organisasi';
   elseif('PENGHARGAAN' == $var)
    return 'FIP - 02, Penghargaan';
   elseif('PENILAIAN' == $var)
    return 'FIP - 02, Penilaian DP-3';
   elseif('POTENSI_DIRI' == $var)
    return 'FIP - 02, Penilaian Potensi Diri';
   elseif('PRESTASI_KERJA' == $var)
    return 'FIP - 02, Catatan Prestasi';
   elseif('HUKUMAN' == $var)
    return 'FIP - 02, Hukuman';
   elseif('CUTI' == $var)
    return 'FIP - 02, Cuti';
   elseif('TUGAS' == $var)
    return 'FIP - 02, Riwayat Penugasan';
   elseif('BAHASA' == $var)
    return 'FIP - 02, Penguasaan Bahasa';
   elseif('NIKAH_RIWAYAT' == $var)
    return 'FIP - 02, Nikah';
   elseif('TAMBAHAN_MASA_KERJA' == $var)
    return 'FIP - 02, Tambahkan Masa Kerja';
}

function setLogInfo($mode, $namaUser, $namaTable)
{
	if($mode == "insert")
		return $namaUser." telah menambah ".getFipNama($namaTable).$statement." pada tanggal ".date('d-m-Y H:i:s');
	elseif($mode == "update")
		return $namaUser." telah merubah ".getFipNama($namaTable).$statement." pada tanggal ".date('d-m-Y H:i:s');
	elseif($mode == "delete")
		return $namaUser." telah menghapus ".getFipNama($namaTable).$statement." pada tanggal ".date('d-m-Y H:i:s');
}

function setLogInfoStatement($mode, $namaUser, $namaTable, $statement)
{
	if($mode == "insert")
		return $namaUser." telah menambah ".getFipNama($namaTable).$statement." pada tanggal ".date('d-m-Y H:i:s');
	elseif($mode == "update")
		return $namaUser." telah merubah ".getFipNama($namaTable).$statement." pada tanggal ".date('d-m-Y H:i:s');
	elseif($mode == "delete")
		return $namaUser." telah menghapus ".getFipNama($namaTable).$statement." pada tanggal ".date('d-m-Y H:i:s');
}

function setImageJson($val="", $lebar="15", $tinggi="15")
{
	if($val > 0)
		return "<center><img src='../WEB/images/approve.png' width='".$lebar."px' height='".$tinggi."px'></center>";
	else
		return "<center><img src='../WEB/images/not_approve.png' width='".$lebar."px' height='".$tinggi."px'></center>";
}

// function untuk membuat header file excel
function HeaderingExcel($filename) 
{
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

function getColoms($var)
{
	$tmp = "";
	if($var == 1)	$tmp = 'A';
	elseif($var == 2)	$tmp = 'B';
	elseif($var == 3)	$tmp = 'C';
	elseif($var == 4)	$tmp = 'D';
	elseif($var == 5)	$tmp = 'E';
	elseif($var == 6)	$tmp = 'F';
	elseif($var == 7)	$tmp = 'G';
	elseif($var == 8)	$tmp = 'H';
	elseif($var == 9)	$tmp = 'I';
	elseif($var == 10)	$tmp = 'J';
	elseif($var == 11)	$tmp = 'K';
	elseif($var == 12)	$tmp = 'L';
	elseif($var == 13)	$tmp = 'M';
	elseif($var == 14)	$tmp = 'N';
	elseif($var == 15)	$tmp = 'O';
	elseif($var == 16)	$tmp = 'P';
	elseif($var == 17)	$tmp = 'Q';
	elseif($var == 18)	$tmp = 'R';
	elseif($var == 19)	$tmp = 'S';
	elseif($var == 20)	$tmp = 'T';
	elseif($var == 21)	$tmp = 'U';
	elseif($var == 22)	$tmp = 'V';
	elseif($var == 23)	$tmp = 'W';
	elseif($var == 24)	$tmp = 'X';
	elseif($var == 25)	$tmp = 'Y';
	elseif($var == 26)	$tmp = 'Z';
	elseif($var == 27)	$tmp = 'AA';
	elseif($var == 28)	$tmp = 'AB';
	elseif($var == 29)	$tmp = 'AC';
	elseif($var == 30)	$tmp = 'AD';
	elseif($var == 31)	$tmp = 'AE';
	elseif($var == 32)	$tmp = 'AF';
	elseif($var == 33)	$tmp = 'AG';
	elseif($var == 34)	$tmp = 'AH';
	elseif($var == 35)	$tmp = 'AI';
	elseif($var == 36)	$tmp = 'AJ';
	elseif($var == 37)	$tmp = 'AK';
	elseif($var == 38)	$tmp = 'AL';
	elseif($var == 39)	$tmp = 'AM';
	elseif($var == 40)	$tmp = 'AN';
	elseif($var == 41)	$tmp = 'AO';
	elseif($var == 42)	$tmp = 'AP';
	elseif($var == 43)	$tmp = 'AQ';
	elseif($var == 44)	$tmp = 'AR';
	elseif($var == 45)	$tmp = 'AS';
	elseif($var == 46)	$tmp = 'AT';
	elseif($var == 47)	$tmp = 'AU';
	elseif($var == 48)	$tmp = 'AV';
	
	return $tmp;
}

function spaseKiri($value, $makp=19)
{
	$jeda="";
	$pnama=strlen($value);
	if ($pnama>$makp)
		$pnama=$makp;
	else
		$pnama=($makp-$pnama);
		
	for ($p=1;$p<=$pnama;$p++)
		$jeda=$jeda." ";
	
	return $jeda;
}

function pathLeft($string, $count)
{
	$data = $count - strlen($string);
	
	$temp = '';
	for($i=0; $i < $data;$i++){
		$temp .= '0';
	}
	
    return $temp.$string;
}

function setTelepon($arrData, $tempSetoran="/")
{
	$tempValue="";
	for($i=0; $i < count($arrData); $i++)
	{
		if($arrData[$i] == ""){}
		else
		{
			if($tempValue == "")
				$separator= "";
			else
				$separator= $tempSetoran;
			
			$tempValue.= $separator.$arrData[$i];
		}
	}
	return $tempValue;
}

function isStrContain($string, $keyword)
{
	if (empty($string) || empty($keyword)) return false;
	$keyword_first_char = $keyword[0];
	$keyword_length = strlen($keyword);
	$string_length = strlen($string);
	
	// case 1
	if ($string_length < $keyword_length) return false;
	
	// case 2
	if ($string_length == $keyword_length) {
	  if ($string == $keyword) return true;
	  else return false;
	}
	
	// case 3
	if ($keyword_length == 1) {
	  for ($i = 0; $i < $string_length; $i++) {
	
		// Check if keyword's first char == string's first char
		if ($keyword_first_char == $string[$i]) {
		  return true;
		}
	  }
	}
	
	// case 4
	if ($keyword_length > 1) {
	  for ($i = 0; $i < $string_length; $i++) {
		/*
		the remaining part of the string is equal or greater than the keyword
		*/
		if (($string_length + 1 - $i) >= $keyword_length) {
	
		  // Check if keyword's first char == string's first char
		  if ($keyword_first_char == $string[$i]) {
			$match = 1;
			for ($j = 1; $j < $keyword_length; $j++) {
			  if (($i + $j < $string_length) && $keyword[$j] == $string[$i + $j]) {
				$match++;
			  }
			  else {
				return false;
			  }
			}
	
			if ($match == $keyword_length) {
			  return true;
			}
	
			// end if first match found
		  }
	
		  // end if remaining part
		}
		else {
		  return false;
		}
	
		// end for loop
	  }
	
	  // end case4
	}
	
	return false;
}

function findWord($word, $text){

    if (strstr($word, $text)) 
	{
        return true;
    } 
    else 
    {
        return false;
    }
}

//function makedirs($dirpath, $mode=0777)
function makedirs($dirpath, $mode=0770)
{
    return is_dir($dirpath) || mkdir($dirpath, $mode, true);
}

function getExtension($varSource)
{
	$temp = explode(".", $varSource);
	return end($temp);
}

function coalesce($varSource, $varReplace)
{
	if($varSource == "")
		return $varReplace;
		
	return $varSource;
}

function setInfoChecked($val1, $val2, $val="checked")
{
	if($val1 == $val2)
		return $val;
	else
		return "";
}

function numberToRomawi($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

?>
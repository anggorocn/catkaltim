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
		$tmp = "'".$var."'";
	
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

function setToAlpha($parse, $row, $temp="0")
{
	$value= $row + $temp;
	
	return toAlpha($parse).$value;
}

function toAlpha($data){
    $alphabet =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $alpha_flip = array_flip($alphabet);
        if($data <= 25){
          return $alphabet[$data];
        }
        elseif($data > 25){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
          } 
          return $alpha;
        }

}

function getSeparator($var, $separator=",")
{
	if($var == '')
		$tmp = '';
	else
		$tmp = $separator;	
	
	return $tmp;
}

function setJenisKp($var)
{
	$temp="";
	if($var == 1)
		$temp= "Reguler";
	elseif($var == 2)
		$temp= "Pilihan";
	elseif($var == 3)
		$temp= "Anumerta";
	elseif($var == 4)
		$temp= "Pengabdian";
	elseif($var == 5)
		$temp= "SK lain-lain";
	elseif($var == 6)
		$temp= "Fungsional";
	return $temp;
}

function setValNol($var)
{	
	if($var == '')
		$tmp = 0;
	else
		$tmp = $var;
	
	return $tmp;
}

function ValToNull($varId)
{
	if($varId == '')
		return 0;
	else
		return $varId;
}

function NolToNone($varId)
{
	if($varId == '0')
		return "";
	else
		return $varId;
}

function romanicNumber($integer, $upcase = true)
{
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0)
    {
        foreach($table as $rom=>$arb)
        {
            if($integer >= $arb)
            {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }

    return $return;
}

function infoskalapenilaian()
{
	$arrField= array(
	  array("code"=>"", "nama"=>"")
	  , array("code"=>"KS", "nama"=>"Kurang Sekali")
	  , array("code"=>"K", "nama"=>"Kurang")
	  , array("code"=>"C", "nama"=>"Cukup")
	  , array("code"=>"B", "nama"=>"Baik")
	  , array("code"=>"BS", "nama"=>"Baik Sekali")
	);
	return $arrField;
}

function radioPenilaian($tempNilai, $val="checked")
{
	  if($tempNilai == 0)
	  {
		  $arrChecked[0]=$val;
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 1)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]=$val;
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 2)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]=$val;
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 3)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]=$val;
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 4)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]=$val;
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 5)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]=$val;
	  }
	  // else
	  // {
		 //  $arrChecked[0]="";
		 //  $arrChecked[1]="";
		 //  $arrChecked[2]=$val;
		 //  $arrChecked[3]="";
		 //  $arrChecked[4]="";
	  // }
	  
	  return $arrChecked;
}

function valuechecked($n, $info= "√")
{
	$return= "";
	if($n == ""){}
	else
		$return= $info;

	return $return;
}

function radioPenilaianInfo($tempNilai)
{
	   if($tempNilai == 0)
	  {
		  $arrChecked[0]=$val;
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 1)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]=$val;
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 2)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]=$val;
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 3)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]=$val;
		  $arrChecked[4]="";
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 4)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]=$val;
		  $arrChecked[5]="";
	  }
	  elseif($tempNilai == 5)
	  {
		  $arrChecked[0]="";
		  $arrChecked[1]="";
		  $arrChecked[2]="";
		  $arrChecked[3]="";
		  $arrChecked[4]="";
		  $arrChecked[5]=$val;
	  }
	  
	  return $arrChecked;
}

function radioBackgroundPenilaianInfo($tempNilai)
{
	if($tempNilai == 1)
	{
		$arrBackgroundColor[0]="ECB2B1";
		$arrBackgroundColor[1]="";
		$arrBackgroundColor[2]="898D8C";
		$arrBackgroundColor[3]="";
		$arrBackgroundColor[4]="";
	}
	elseif($tempNilai == 2)
	{
		$arrBackgroundColor[0]="";
		$arrBackgroundColor[1]="DA392C";
		$arrBackgroundColor[2]="898D8C";
		$arrBackgroundColor[3]="";
		$arrBackgroundColor[4]="";
	}
	elseif($tempNilai == 3)
	{
		$arrBackgroundColor[0]="";
		$arrBackgroundColor[1]="";
		$arrBackgroundColor[2]="FFFE0B";
		$arrBackgroundColor[3]="";
		$arrBackgroundColor[4]="";
	}
	elseif($tempNilai == 4)
	{
		$arrBackgroundColor[0]="";
		$arrBackgroundColor[1]="";
		$arrBackgroundColor[2]="898D8C";
		$arrBackgroundColor[3]="4C9D65";
		$arrBackgroundColor[4]="";
	}
	elseif($tempNilai == 5)
	{
		$arrBackgroundColor[0]="";
		$arrBackgroundColor[1]="";
		$arrBackgroundColor[2]="898D8C";
		$arrBackgroundColor[3]="";
		$arrBackgroundColor[4]="4565A7";
	}
	  
	  return $arrBackgroundColor;
}

function setInfoKemmpuanSaran($tempNilai)
{
	if($tempNilai == 1)
	{
		$tempValue= "belum mampu";
	}
	elseif($tempNilai == 2)
	{
		$tempValue= "hampir mampu";
	}
	elseif($tempNilai == 3)
	{
		$tempValue= "mampu";
	}
	elseif($tempNilai == 4)
	{
		$tempValue= "kompeten";
	}
	elseif($tempNilai == 5)
	{
		$tempValue= "sangat kompeten";
	}
	return $tempValue;
}

function radioKeteranganPenilaianInfo($tempNilai)
{
	  if($tempNilai == 1)
	  {
		  $tempValue="Tidak Kompeten<br/>Memerlukan beberapa perubahan dan pengembangan perilaku";
	  }
	  elseif($tempNilai == 2)
	  {
		  $tempValue="Hampir Kompeten<br/>Memerlukan beberapa perubahan dan pengembangan perilaku";
	  }
	  elseif($tempNilai == 3)
	  {
		  $tempValue="Cukup Kompeten<br/>Perilaku yang di tunjukkan cukup sesuai dengan tingkatan yang diperlukan, dengan tetap memerlukan pengembangan perilaku";
	  }
	  elseif($tempNilai == 4)
	  {
		  $tempValue="Kompeten<br/>Perilaku yang di tunjukkan sesuai dengan tingkat yang diperlukan";
	  }
	  elseif($tempNilai == 5)
	  {
		  $tempValue="Sangat Kompeten";
	  }
	  else
	  {
		  $tempValue="";
	  }
	  
	  return $tempValue;
}

function setFipInfo()
{
	return array("Pegawai IKK", "Assesment", "Potensi", "Kompetensi", "General IKK", "Penilaian Lhkpn / Lhksn", "Penilaian Presensi", "Penilaian SKP", 
    "Grafik Nine Box Talent", "Tabel Nine Box Talent", "Pegawai SDM", "Analisa Diklat",  "Atribut", "Training", "Pegawai Pola Karir",
    "Periode Penilaian", "Kategori", "Pertanyaan", "Pegawai Penilai", "Kinerjaku dan SKP", "Formulir SKP", "Pencapaian SKP", 
	"Validasi Formulir SKP", "Validasi Pencapaian SKP", "Penilaian SKP", "SKP dan Perilaku Kerja", "Tugas Belajar", "Hukuman", "Rencana Suksesi");
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

function setAndKondisi($tempId= "", $field= "")
{
	$tempId= str_replace("0000", "", $tempId);
	$tempId= str_replace("00", "", $tempId);
	$panjang= strlen($tempId);
	$statement= " and SUBSTR(".$field.", 1, ".$panjang.") = '".$tempId."'";
	return $statement;
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
	elseif($var == 49)	$tmp = 'AW';
	elseif($var == 50)	$tmp = 'AX';
	elseif($var == 51)	$tmp = 'AY';
	
	return $tmp;
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

function warnanilai($n)
{
	if($n > 0 && $n <= 1)
	 return "#33B9E1DB";
	elseif($n > 1 && $n <= 2)
	 return "#FFFF00";
	elseif($n > 2 && $n <= 3)
	 return "#FFA200";
	elseif($n > 3 && $n <= 4)
	 return "#00FFFF";
	elseif($n > 4 && $n <= 5)
	 return "#00FF00";
}

function makedirs($dirpath, $mode=0770)
{
    return is_dir($dirpath) || mkdir($dirpath, $mode, true);
}

function deleteNonEmptyDir($dir) 
{
 if (is_dir($dir)) 
 {
	  $objects = scandir($dir);

	  foreach ($objects as $object) 
	  {
		  if ($object != "." && $object != "..") 
		  {
			  if (filetype($dir . "/" . $object) == "dir")
			  {
				  deleteNonEmptyDir($dir . "/" . $object); 
			  }
			  else
			  {
				  unlink($dir . "/" . $object);
			  }
		  }
	  }

	  reset($objects);
	  rmdir($dir);
  }
}

function rscript($var)
{
	$arrreplace= array("<div>", "</div>", "</font>", '<font face="Roboto, HelveticaNeue, Helvetica, sans-serif">');
	$var= str_replace($arrreplace, "", $var);
	// echo $var;exit;
	return $var;
}

function bigfive()
{
	$arrField= array(
	  array("id"=>"Agreeableness", "color"=>"dd9af5")
	  , array("id"=>"Conscientiousness", "color"=>"ff8787")
	  , array("id"=>"Extraversion", "color"=>"bcf7b7")
	  , array("id"=>"Neuroticism", "color"=>"baf9ff")
	  , array("id"=>"Openness", "color"=>"faff73")
	);
	return $arrField;
}

function vsimplexml_load_file()
{
	$arrField= array(
		"url"=>"https://assessment.kaltimbkd.info/assesment/main/index.php"
		, "urlbase"=>"simpeg"
		, "urlskpbase"=>"simpeg"
		, "urltukin"=>"db_sim_tpp"
		, "urlPengaturan"=>"https://assessment.kaltimbkd.info/assesment/pengaturan/"
		, "urlLink"=>"https://assessment.kaltimbkd.info/assesment/"
	);
	// print_r($arrField);exit();
	return $arrField;
}

function deleteFileZip($arrFile)
{
	//$tempLokasi= "zis/";
	for($indexUnlink=0; $indexUnlink < count($arrFile); $indexUnlink++)
		unlink($tempLokasi.$arrFile[$indexUnlink]);
		
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

function dotToNo($varId)
{
	$newId = str_replace(".", "", $varId);	
	$newId = str_replace(",", ".", $newId);	
	return $newId;
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    // print_r($u_agent);exit();

    //First get the platform?
    if (preg_match('/android/i', $u_agent)) {
        $platform = 'android';
    }
    elseif (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

//function rendy
function getClientIpEnv() {
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
//end function rendy

function getIpAddress() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validateIp($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}


function validateIp($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false)
    {
        return false;
    }
}

?>
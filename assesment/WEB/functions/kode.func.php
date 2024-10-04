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
function setKode($value)
{
	$tempValue= "85";
	if($value == 2 || $value == 1)//SEKRETARIAT JENDERAL;SETJEN
		$tempValue= 10;
	elseif($value == 140)//INSPEKTORAT JENDERAL;ITJEN
		$tempValue= 11;
	elseif($value == 170)//DITJEN PERIKANAN TANGKAP;DJPT
		$tempValue= 2;
	elseif($value == 442)//DITJEN PERIKANAN BUDIDAYA;DJPB
		$tempValue= 3;
	elseif($value == 646)//DITJEN PENGOLAHAN DAN PEMASARAN HASIL PERIKANAN;DJP2HP
		$tempValue= 4;	
	elseif($value == 770)//DITJEN KELAUTAN, PESISIR DAN PULAU-PULAU KECIL (KP;DJKP3K
		$tempValue= 5;
	elseif($value == 904)//DITJEN PENGAWASAN SUMBERDAYA KELAUTAN DAN PERIKANA;DJPSDKP
		$tempValue= 6;
	elseif($value == 1014)//BADAN PENELITIAN DAN PENGEMBANGAN KELAUTAN DAN PER;BALITBANG KP
		$tempValue= 7;
	elseif($value == 1345)//BADAN KARANTINA IKAN, PENGENDALIAN MUTU DAN KEAMAN;BKIPM
		$tempValue= 8;
	elseif($value == 1198)//BADAN PENGEMBANGAN SDM KELAUTAN DAN PERIKANAN;BPSDM
		$tempValue= 9;

	return $tempValue;
}
?>
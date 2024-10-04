<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: date.func.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Functions to handle date operations
***************************************************************************************************** */

	function dateToPage($_date){
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function datetimeToPage($_date, $_type, $separator="-"){
		if($_date == "")
			return "";
		$arrDateTime = explode(" ", $_date);
		if($_type == "date")
		{
			$arrDate = explode("-", $arrDateTime[0]);
			$_date = $arrDate[2].$separator.$arrDate[1].$separator.$arrDate[0];
			return $_date;
		}
		else
		{
			$_date = $arrDateTime[1];
			$arrTime = explode(":", $_date);
			if($_type == "hour")
				return $arrTime[0];
			elseif($_type == "minutes")
				return $arrTime[1];						
			else
				return $_date;							
		}
	}
	
	function dateToPageCheckMysql($_date, $validate=''){
		if($_date == "")
		{
			return "";	
		}
		
		if($validate == 1){
			if(substr($_date, 0, 2) == "[]"){
				$explode = explode('[]',$_date);
				$arrDate = explode("-", $explode[2]);
				$_date= ''.$arrDate[0]."-".$arrDate[1]."-".$arrDate[2];
			}else{
				$arrDate = explode("-", $_date);
				$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
			}
		}
		else{
			$arrDate = explode("-", $_date);
			$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		}
			return $_date;
	}
	
	function dateToPageCheck($_date, $separator="-"){
		if($_date == "")
		{
			return "";	
		}
		$arrDateTime = explode(" ", $_date);
		$arrDate = explode("-", $arrDateTime[0]);
		
		if($arrDateTime[1] == "")
		{
			$_date = $arrDate[2].$separator.generateZeroDate($arrDate[1],2).$separator.generateZeroDate($arrDate[0], 2);
		}
		else
		{
			$_date = $arrDate[2].$separator.generateZeroDate($arrDate[1],2).$separator.generateZeroDate($arrDate[0], 2)." ".$arrDateTime[1];
		}
		return $_date;
	}
	
	function dateToDB($_date){
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function dateToDBCheck($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		return "TO_DATE('".$_date."', 'YYYY-MM-DD')";
	}
	
	function dateToDBCheckMsql($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return "STR_TO_DATE('".$_date."', '%Y-%m-%d')";
	}
	
	function dateMixToDB($_date){
		$arrDate = explode("/", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function getDay($_date) {
		$arrDate = explode("-", $_date);
		return $arrDate[2];
	}
	
	function getMonth($_date) {
		$arrDate = explode("-", $_date);
		return $arrDate[1];
	}
	
	function getYear($_date) {
		$arrDate = explode("-", $_date);
		return $arrDate[0];
	}
	
	function generateZeroDate($varId, $digitGroup, $digitCompletor = "0")
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
	
	function setBulanLoop($awal=1, $akhir=12)
	{
		$index_bulan=0;
		for($i=$awal; $i <= $akhir; $i++)
		{
			$arrBulan[$index_bulan]= generateZeroDate($i,2);
			$index_bulan++;
		}
		return $arrBulan;
	}
	
	function setTahunLoop($awal, $akhir)
	{
		$index_tahun=0;
		for($i=date("Y")+$awal; $i >= date("Y")-$akhir; $i--)
		{
			$arrTahun[$index_tahun]= $i;
			$index_tahun++;
		}
		return $arrTahun;
	}
	function setPeriodeLoop($awal, $akhir)
	{
		$tempDateNow=date("Y-m-d", strtotime("+".$awal." month", strtotime(date("Y-m-t"))));
		$index_month=0;
		for($i=1; $i <= $awal; $i++)
		{	
			$tempDateNow= date("Y-m-d", strtotime("-1 month", strtotime($tempDateNow)));
			$arrPeriode[$index_month] = getMonth($tempDateNow).getYear($tempDateNow);
			$index_month++;
		}
		
		for($i=1; $i < $akhir; $i++)
		{	
			if(getMonth($tempDateNow).getYear($tempDateNow) == $arrPeriode[$index_month-1]){}
			else
			{
			$arrPeriode[$index_month] = getMonth($tempDateNow).getYear($tempDateNow);
			$index_month++;
			}
			$tempDateNow= date("Y-m-d", strtotime("-1 month", strtotime($tempDateNow)));
		}
		
		return $arrPeriode;
	}
	
	function getNamePeriode($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		 
		return getNameMonth((int)$bulan)." ".$tahun;
	}

	function getDateIndo($_date) {
		$date = explode(" ",$_date);
		$date = explode("-",$date[0]);
		return $date[2].' '.getNameMonth($date[1]).' '.$date[0];
	}
	
	function getNameMonth($number) {
		$number = (int)$number;
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");
		return $arrMonth[$number];
	}

	function getRomawiMonth($number) {
		$arrMonth = array("1"=>"I", "2"=>"II", "3"=>"III", "4"=>"IV", "5"=>"V", 
						  "6"=>"VI", "7"=>"VII", "8"=>"VIII", "9"=>"IX", "10"=>"X", 
						  "11"=>"XI", "12"=>"XII");
		return $arrMonth[$number];
	}
	
	function getTimeJam($time) {
		$tempValue= "";
		$arrTime= explode(":", $time);
		
		return (int)$arrTime[0];
	}
	
	function getTimeIndo($time, $separator= " ")
	{
		$tempValue= "";
		$arrTime= explode(":", $time);
		
		if($arrTime[0] == "00" || $arrTime[0] == ""){}
		else
		$tempValue= $arrTime[0]." Jam";
		
		if($arrTime[1] == "00" || $arrTime[1] == ""){}
		else
		{
			if($tempValue == "")
			$tempValue.= $arrTime[1]." Menit";
			else
			$tempValue.= $separator.$arrTime[1]." Menit";
		}
		
		if($arrTime[2] == "00" || $arrTime[2] == ""){}
		else
		{
			if($tempValue == "")
			$tempValue.= $arrTime[2]." Detik";
			else
			$tempValue.= $separator.$arrTime[2]." Detik";
		}
		return $tempValue;
	}
	
	// date input : database
	function getFormattedDateJson($_date)
	{
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);

		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		return $date;
	}
	
	function getValueDate($_date)
	{		
		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);
		
		$jumHari = cal_days_in_month(CAL_GREGORIAN, $_month, $arrDate[0]);	
		$date = $jumHari;
		
		return $date;
	}
	
	function getFormattedDate($_date)
	{
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);

		$date = ''.$arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0].'';
		return $date;
	}
	
	// date input : database
	function getFormattedDateTime($_date, $showTime=true, $short=false)
	{
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		if($short == true)
		{
			$arrMonth= array("1"=>"Jan", "2"=>"Feb", "3"=>"Mar", "4"=>"Apr", "5"=>"Mei", "6"=>"Jun", "7"=>"Jul", "8"=>"Agst", "9"=>"Sept", "10"=>"Okt", "11"=>"Nov", "12"=>"Des");
		}
		else
		{
			$arrMonth= array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
							  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
							  "11"=>"November", "12"=>"Desember");
		}


		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		$time = $explodedTime;

		if($showTime == true)
			$datetime = $date.',&nbsp;'.$time;
		else
			$datetime = $date;

		/*if($showTime == true)
			$datetime = '<span style="white-space:nowrap">'.$date.',&nbsp;'.$time.'</span>';
		else
			$datetime = '<span style="white-space:nowrap">'.$date.'</span>';*/
		return $datetime;
	}
	
	function getNamaHari($hari, $bulan, $tahun)
	{
		//$x= mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$x= mktime(0, 0, 0, $bulan, $hari, $tahun);
		$namahari = date("l", $x);
		
		if ($namahari == "Sunday") $namahari = "Minggu";
		else if ($namahari == "Monday") $namahari = "Senin";
		else if ($namahari == "Tuesday") $namahari = "Selasa";
		else if ($namahari == "Wednesday") $namahari = "Rabu";
		else if ($namahari == "Thursday") $namahari = "Kamis";
		else if ($namahari == "Friday") $namahari = "Jumat";
		else if ($namahari == "Saturday") $namahari = "Sabtu";
		
		return $namahari;
	}
	
	function addTwoTimes($time1 = "00:00:00", $time2 = "00:00:00", $status="1")
	{
		//$time2_arr = [];
		$time1 = $time1;
		$time2_arr = explode(":", $time2);
		//Hour
		if(isset($time2_arr[0]) && $time2_arr[0] != ""){
			$time1 = $time1." +".$time2_arr[0]." hours";
			$time1 = date("H:i:s", strtotime($time1));
		}
		//Minutes
		if(isset($time2_arr[1]) && $time2_arr[1] != ""){
			$time1 = $time1." +".$time2_arr[1]." minutes";
			$time1 = date("H:i:s", strtotime($time1));
		}
		//Seconds
		if(isset($time2_arr[2]) && $time2_arr[2] != ""){
			$time1 = $time1." +".$time2_arr[2]." seconds";
			$time1 = date("H:i:s", strtotime($time1));
		}
		
		if($status == "1")
		return date("H:i", strtotime($time1));
		else
		return date("H:i:s", strtotime($time1));
	}
?>

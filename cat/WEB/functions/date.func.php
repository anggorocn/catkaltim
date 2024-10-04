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
	
	function datetimeToPage($_date, $_type){
		if($_date == "")
			return "";
		$arrDateTime = explode(" ", $_date);
		if($_type == "date")
		{
			$arrDate = explode("-", $arrDateTime[0]);
			$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
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
	
	function dateToPageCheck($_date, $validate=''){
		if($_date == "" || strlen($_date) < 10)
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
	
	function dateTimeToPageCheck($_date){
		if($_date == "")
		{
			return "";	
		}
		$arrDateTime = explode(" ", $_date);
		$arrDate = explode("-", $arrDateTime[0]);
		
		if($arrDateTime[1] == "")
		{
			$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		}
		else
		{
			$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2)." ".$arrDateTime[1];
		}
		return $_date;
	}
	
	function dateToDB($_date){
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return $_date;
	}
	
	function dateTimeToDBCheck($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDateTime = explode(" ", $_date);
		$arrDate = explode("-", $arrDateTime[0]);
		
		$_date = $arrDate[2]."-".generateZeroDate($arrDate[1],2)."-".generateZeroDate($arrDate[0], 2);
		return "TO_TIMESTAMP('".$_date." ".$arrDateTime[1]."', 'YYYY-MM-DD HH24:MI:SS')";
	}
	
	function dateToDBCheck($_date){
		if($_date == "")
		{
			return "NULL";	
		}
		$arrDate = explode("-", $_date);
		$_date = $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
		return "TO_DATE('".$_date."', 'YYYY-MM-DD')";
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
	
	function getNamePeriode($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		 
		return getNameMonth((int)$bulan)." ".$tahun;
	}
	
	function getBulanPeriode($periode) {
		$bulan = substr($periode, 0,2);
		 
		return $bulan;
	}
	
	function getTahunPeriode($periode) {
		$tahun = substr($periode, 2,4);
		 
		return $tahun;
	}
	
	function setPeriodeInParam($periode="", $periodeAkhir="")
	{
		$bulan= getBulanPeriode($periode);
		$tahun= getTahunPeriode($periode);
		
		$bulanAkhir= getBulanPeriode($periodeAkhir);
		$tahunAkhir= getTahunPeriode($periodeAkhir);
		
		$date=$tahun."-".$bulan;
		$dateAkhir=$tahunAkhir."-".$bulanAkhir;
		$tempTanggalAwal= date("Y-m-01",strtotime($date));
		$reqTanggalAkhir= date("Y-m-t",strtotime($dateAkhir));
		
		$tempReturn= "";
		while (strtotime($tempTanggalAwal) <= strtotime($reqTanggalAkhir)) 
		{
			if($tempReturn == "")
				$delimeter= "";
			else
				$delimeter= ", ";
			
			$tempReturn .= $delimeter."'".getMonth($tempTanggalAwal).getYear($tempTanggalAwal)."'";
			$tempTanggalAwal= date ("Y-m-d", strtotime("+1 month", strtotime($tempTanggalAwal)));
		}
		
		return $tempReturn;
	}
	
	function setPeriodeAdd($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		
		if($bulan == "12")
		{
			$tahun=$tahun+1;
			$tempPeriode= "01".$tahun;
		}
		else
		{
			$tempBulan= $bulan+1;
			$tempPeriode= generateZeroDate($tempBulan,2).$tahun;
		}
		
		return $tempPeriode;
	}
	
	function setPeriodeMin($periode) {
		$bulan = substr($periode, 0,2);
		$tahun = substr($periode, 2,4);
		
		if($bulan == "1")
		{
			$tempTahun= $tahun-1;
			$tempPeriode= "12".$tempTahun;
		}
		else
		{
			$tempBulan= $bulan-1;
			$tempPeriode= generateZeroDate($tempBulan,2).$tahun;
		}
		
		return $tempPeriode;
	}
	
	function getNameMonthAll($number) {
		$number = (int)$number;
		$arrMonth = array("0"=>"Semua", "1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");
		return $arrMonth[$number];
	}
	
	function getTanggal($tanggal) {
		$arrMonth= array("Januari"=>"1", "Februari"=>"3", "Maret"=>"3", "April"=>"4", "Mei"=>"5", 
						  "Juni"=>"6", "Juli"=>"7", "Agustus"=>"8", "September"=>"9", "Oktober"=>"10", 
						  "November"=>"11", "Desember"=>"12");
						  
		$temp= explode(" ",$tanggal);
		$tempData= "01-".generateZeroDate($arrMonth[$temp[0]],2)."-".$temp[1];
		return $tempData;
	}
	
	function getNameMonth($number) {
		$number = (int)$number;
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");
		return $arrMonth[$number];
	}
	
	function getNameMonthExt($number) {
		$arrMonth = array("1"=>"Jan", "2"=>"Feb", "3"=>"Mar", "4"=>"Apr", "5"=>"Mei", 
						  "6"=>"Jun", "7"=>"Jul", "8"=>"Agu", "9"=>"Sep", "10"=>"Okt", 
						  "11"=>"Nov", "12"=>"Des");
		return $arrMonth[$number];
	}

	function getRomawiMonth($number) {
		$arrMonth = array("1"=>"I", "2"=>"II", "3"=>"III", "4"=>"IV", "5"=>"V", 
						  "6"=>"VI", "7"=>"VII", "8"=>"VIII", "9"=>"IX", "10"=>"X", 
						  "11"=>"XI", "12"=>"XII");
		return $arrMonth[$number];
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
	
	function getFormattedDateCheck($_date)
	{
		if($_date == "" || strlen($_date) < 10)
		{
			return "";	
		}
		
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $_date);
		$_month = intval($arrDate[1]);

		$date = ''.$arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0].'';
		return $date;
	}
	
	// date input : database
	function getFormattedDateTime($_date, $showTime=true)
	{
		$_date = explode(" ", $_date);
		$explodedDate = $_date[0];
		$explodedTime = $_date[1];
		
		$arrMonth = array("1"=>"Januari", "2"=>"Februari", "3"=>"Maret", "4"=>"April", "5"=>"Mei", 
						  "6"=>"Juni", "7"=>"Juli", "8"=>"Agustus", "9"=>"September", "10"=>"Oktober", 
						  "11"=>"November", "12"=>"Desember");

		$arrDate = explode("-", $explodedDate);
		$_month = intval($arrDate[1]);
		
		$date = $arrDate[2].' '.$arrMonth[$_month].' '.$arrDate[0];
		$time = $explodedTime;
		$time=explode(".", $time);
		$time= $time[0];
		
		if($showTime == true)
			$datetime = '<span style="white-space:nowrap">'.$date.',&nbsp;'.$time.'</span>';
		else
			$datetime = '<span style="white-space:nowrap">'.$date.'</span>';
		return $datetime;
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
	
	function setBulanLoopAll($awal=0, $akhir=12)
	{
		$index_bulan=0;
		for($i=$awal; $i <= $akhir; $i++)
		{
			$arrBulan[$index_bulan]= generateZeroDate($i,2);
			$index_bulan++;
		}
		return $arrBulan;
	}
	
	function setTahunLoopParse($tahun)
	{
		$index_tahun=0;
		for($i=date("Y"); $i >= $tahun; $i--)
		{
			$arrTahun[$index_tahun]= $i;
			$index_tahun++;
		}
		return $arrTahun;
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
	
	function setTahunPeriodikLoop($awal, $akhir)
	{
		$index_tahun=0;
		for($i=date("Y")+$awal; $i >= date("Y")-$akhir; $i--)
		{
			$tempTahunDepan= $i+1;
			$arrTahun[$index_tahun]= $i."-".$tempTahunDepan;
			$index_tahun++;
		}
		return $arrTahun;
	}
?>

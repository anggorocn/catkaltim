<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

function getUnitOrgArray($statement)
{
	require "../json-sql/dbmanager.php";
	$sql = "
	SELECT KODE, NAMAUNIT FROM tbunitorg WHERE 1=1 ".$statement."
	";
	
	$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	
	$arrArray="";
	$index_array= 0;
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
	{
		$arrArray[$index_array]["KODE_UNIT"] = trim($row['KODE']);
		$arrArray[$index_array]["NAMA_UNIT"] = trim($row['NAMAUNIT']);
		$index_array++;
	}
	sqlsrv_free_stmt( $stmt);
	//print_r($arrArray);
	return $arrArray;
}

function getPegawaiArray($statement)
{
	require "../json-sql/dbmanager.php";
	$sql = "
	SELECT NIPBARU, NAMA, NMJABATAN, KODEUNIT, KELASJAB, NILAIJAB, PROSEN, OPD, UPT, GOL, PANGKAT, ESL, TMTJAB, URUT FROM fntbSrcTamsil('".$statement."')
	";
	
	$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	
	$arrArray="";
	$index_array= 0;
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
	{
		$arrArray[$index_array]["NIPBARU"] = trim($row['NIPBARU']);
		$arrArray[$index_array]["NAMA"] = trim($row['NAMA']);
		$arrArray[$index_array]["NMJABATAN"] = trim($row['NMJABATAN']);
		$arrArray[$index_array]["KODEUNIT"] = trim($row['KODEUNIT']);
		$arrArray[$index_array]["KELASJAB"] = trim($row['KELASJAB']);
		$arrArray[$index_array]["NILAIJAB"] = trim($row['NILAIJAB']);
		$arrArray[$index_array]["PROSEN"] = trim($row['PROSEN']);
		$arrArray[$index_array]["OPD"] = trim($row['OPD']);
		$arrArray[$index_array]["UPT"] = trim($row['UPT']);
		$arrArray[$index_array]["GOL"] = trim($row['GOL']);
		$arrArray[$index_array]["PANGKAT"] = trim($row['PANGKAT']);
		$arrArray[$index_array]["ESL"] = trim($row['ESL']);
		$arrArray[$index_array]["TMTJAB"] = date_format($row['TMTJAB'],"d-m-Y");;
		$arrArray[$index_array]["URUT"] = trim($row['URUT']);
		$index_array++;
	}
	sqlsrv_free_stmt( $stmt);
	//print_r($arrArray);
	return $arrArray;
}
?>
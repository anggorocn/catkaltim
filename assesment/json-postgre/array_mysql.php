<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

function getAbsensi($statement)
{
	require "../json-sql/dbmanagermysql.php";
	$sql = "
	Call prAbsenHarian('".$statement."');
	";
	//echo $sql;
	$result= mysql_query($sql);
	
	
	$arrArray="";
	$index_array= 0;
	while($row = mysql_fetch_assoc($result)) 
	{
		if($row['nip']=="0"){}
		else
		{
			$arrArray[$index_array]["NIPBARU"] = $row['nip'];
			$arrArray[$index_array]["DATANG"] = $row['datang'];
			$arrArray[$index_array]["PULANG"] = $row['pulang'];
			$arrArray[$index_array]["MASUK"] = $row['masuk'];
			$arrArray[$index_array]["KELUAR"] = $row['keluar'];
			$index_array++;
		}
	}
	//echo $statement."--";
	//print_r($arrArray);//exit;
	return $arrArray;
	//mysql_close($db_handle);
}
?>
<?php
//ini_set('default_socket_timeout',   1);
//ini_set('mssql.connect_timeout',    1);
//ini_set('mssql.timeout',            3);
	
//ini_set('mssql.charset', 'UTF-8');
//ini_set('mssql.charset', 'windows-1252');

$serverName = "VALSIX-PC\VALSIX"; //serverName\instanceName
// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
//$connectionInfo = array("Database"=>"gresik_2016");
$connectionInfo = array("Database"=>"simpeg", "UID"=>"sa", "PWD"=>"password123");
//$connectionInfo = array("Database"=>"v@lid49v6_2016");

//$myDB= "[simpeg]";

// Connect to MSSQL
//$conn = mssql_connect($serverName, 'sa', 'password123');

//$conn= mssql_connect('localhost', 'db_user', 'db_password');
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if($conn)
{
     //echo "Connection established.<br />";
}
else
{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>

<?php
$dbhost= "localhost";
$dbname= "adms_mjk_2016";
$dbuser= "root";
$dbpass= "root";

$db_handle= mysql_connect($dbhost, $dbuser, $dbpass);
$db_found = mysql_select_db($dbname, $db_handle);
if (!$db_handle) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';
?>

<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: config.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: this file handle to get value of configuration from web.xml 
***************************************************************************************************** */
	include_once("../WEB/setup/defines.php");
	include_once("../WEB/functions/dialogs.func.php");
	include_once("../WEB/functions/default.func.php");
	include_once("../WEB/lib/path/patConfiguration.php" );
	
	//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/setup/defines.php");
	//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/functions/dialogs.func.php");
	//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/functions/default.func.php");
	//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/lib/path/patConfiguration.php" );
	
	$conf = new patConfiguration();
  	$conf->parseConfigFile("../WEB/web.xml" );
	//$conf->parseConfigFile("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/web.xml" );
	$datasource = $conf->getConfigValue("datasource.default");
		
	/**** DATABASE CONFIGURATION *****/
	$confDbType = $conf->getConfigValue("datasource.".$datasource.".driver");
	$confDbServer = $conf->getConfigValue("datasource.".$datasource.".server");
	$confDbName = $conf->getConfigValue("datasource.".$datasource.".database");
	$confDbUserName = $conf->getConfigValue("datasource.".$datasource.".username");
	$confDbPassword = $conf->getConfigValue("datasource.".$datasource.".password");
	$confDbTrusted = $conf->getConfigValue("datasource.".$datasource.".trusted_connection");
	
	//echo $confDbPassword;
	
	include_once("../WEB/classes/db/DBManager.php");
	//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/DBManager.php");
	$dbDefault = new DBManager();
	$dbDefault->connect();
?>

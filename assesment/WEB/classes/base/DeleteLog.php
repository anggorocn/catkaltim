<?
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 * 
 ***/
include_once("../WEB/classes/db/Entity.php");

class DeleteLog extends Entity
{

	var $query;
	var $db;
	/**
	 * Class constructor.
	 **/
	function DeleteLog()
	{
		//    $xmlfile = "../WEB/web.xml";
		// $data = simplexml_load_file($xmlfile);
		// $rconf_url_info= $data->urlConfig->main->urlbase;

		// $this->db=$rconf_url_info;
		$this->db = 'simpeg';
		$this->Entity();
	}

	function insertlog()
	{
		$this->setField("TEKEN_LOG_ID", $this->getNextId("TEKEN_LOG_ID", "teken_log"));

		$str = "
		INSERT INTO teken_log
		(
			TEKEN_LOG_ID, JENIS, IP_ADDRESS, USER_AGENT, KETERANGAN, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		) 
		VALUES 
		(
			".$this->getField("TEKEN_LOG_ID")."
			, '".$this->getField("JENIS")."'
			, '".$this->getField("IP_ADDRESS")."'
			, '".$this->getField("USER_AGENT")."'
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("USER_LOGIN_ID")."
			, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
		"; 	
		$this->query = $str;
		// echo $str;;exit();
		return $this->execQuery($str);
	}
}

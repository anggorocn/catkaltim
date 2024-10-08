<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: DBManager.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Class that responsible to handle connection to database server and error handling
***************************************************************************************************** */

/**
* Database Manager
* @author Priyo Edi P. modified by M Reza Faisal
* @version 1.2.1
**/
include_once("../WEB/lib/adodb/adodb.inc.php");
  
class DBManager{	
	/** 
	* Tipe database 
	* @var string
	**/
    var $dbType="";
	
	/** 
	* Nama database server (IP atau DNS) 
	* @var string
	**/
    var $dbServer="";
	
	/** 
	* Nama user id untuk koneksi ke db server 
	* @var string
	**/
    var $dbUserName="";
	
	/** 
	* Password untuk koneksi ke db server 
	* @var string
	**/
    var $dbPassword="";
		
	/** 
	* Nama database-nya 
	* @var string
	**/
    var $dbName = "";
		
	/** 
	* Connection object 
	* @var ADOConnection
	**/
    var $conn;
		
	/** 
	* Handler untuk error. 
	* @var int
	**/
    var $error;

    /**
	* Class Constructor.
	* @author M Reza Faisal
	* @param string dbServer Nama server database (host)
	* @param string dbName Nama database
	* @param string dbUserName Nama id user untuk koneksi ke db server
	* @param string dbPassword Password untuk koneksi ke database
	* @return void
	**/
    function DBManager($dbServer="",$dbName="",$dbUserName="",$dbPassword="", $dbType=""){
		global $confDbType, $confDbServer,$confDbName,$confDbUserName,$confDbPassword;

		if($dbType=="")
			$this->dbType = $confDbType;
		else
			$this->dbType = $dbType;
			
		if($dbServer=="")
			$this->dbServer = $confDbServer;
		else
			$this->dbServer = $dbServer;
				
		if($dbName=="")
      		$this->dbName = $confDbName;
		else			
			$this->dbName = $dbName;
			
		if($dbUserName=="")
      		$this->dbUserName  = $confDbUserName;
		else
			$this->dbUserName  = $dbUserName;
			
		if($dbPassword=="")
      		$this->dbPassword = $confDbPassword;
		else
			$this->dbPassword = $dbPassword;
    }

	/***
	* Inisiasi koneksi ke database.
	* @author M Reza Faisal
	* @return boolean True jika berhasil, false jika tidak.
	**/
    function connect(){ 
		$this->conn = &ADONewConnection("postgres");
		$this->conn->debug = false;
		//$this->conn->connectSID = true;	
		//$this->conn->Connect('192.168.0.1', 'scott', 'tiger', 'SID');
		//$statusConnection = @$this->conn->Connect('192.168.1.100', 'EPROC_ME', 'eproc', 'orcl');
		$statusConnection = @$this->conn->Connect($this->dbServer, $this->dbUserName, $this->dbPassword, $this->dbName);
		//$statusConnection = @$this->conn->Connect("host=localhost port=5432 dbname=simpeg_magetan");
		
		
		//$statusConnection = @$this->conn->Connect("ORCL", "EPROC_ME", "eproc");
		//$statusConnection = @$this->conn->Connect($this->dbServer, $this->dbUserName, $this->dbPassword);
      	//if (($this->dbType=="oracle" || $this->dbType=="oci8") && $this->dbServer=="" && $this->dbName=="") {
		//	$statusConnection = @$this->conn->Connect(false,$this->dbUserName,$this->dbPassword,$oraname);
		//} else {
		//	$statusConnection = @$this->conn->Connect($this->dbServer,$this->dbUserName,$this->dbPassword,$this->dbName);
		//}
					
      	if(!$statusConnection){
			$this->showMessageError();
      	}else
			return $statusConnection;
    }
		
	/***		
	* Menutup koneksi ke database server.
	* @author M Reza Faisal
	* @return void
	*/
    function disconnect(){
    	return @$this->conn->Close();
    }
		
	/***
	* Mengambil nomor error, jika terjadi error.
	* @author M Reza Faisal
	* @return int Nomor error.
	* @deprecated Jangan langsung gunakan method ini. Gunakan yang ada di <a href="../entities/Entity.html#getErrorNo">Entity</a>
	*/
    function getErrorNo(){
    	return $this->conn->ErrorNo();
    }
	
	/***
	* Mengambil pesan error, jika terjadi error.
	* @author M Reza Faisal
	* @return int Nomor error.
	* @deprecated Jangan langsung gunakan method ini. Gunakan yang ada di <a href="../entities/Entity.html#getErrorNo">Entity</a>
	*/
    function getErrorMsg(){
    	return $this->conn->ErrorMsg();
    }
		
	/***
	* Menampilkan pesan kesalahan ke layar 
	* @author M Reza Faisal
	* @return void
	**/		
	function showMessageError(){
		$this->getMessage("PESAN ERROR : <br>No. Error : ".$this->getErrorNo()."<br>Detail Pesan : ".$this->getErrorMsg(),true);
		exit;
	}
	
	/***
	* Generate pesan kesalahan 
	* @author M Reza Faisal
	* @return void
	**/	
	function getMessage($messageStr,$continueLoad=true){
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\"> \n";
		echo "<tr> \n";
		echo "<td width=\"100%\" valign=\"bottom\" align=\"left\">";
		echo "<p><font color='#FF0000' size='1' face='Verdana, Arial, Helvetica, sans-serif'>";
		echo "<b>$messageStr</b></font></p>";
		echo "</td> \n";
		echo "</tr> \n";
		echo "<tr> \n";
		echo "<td width=\"100%\" valign=\"bottom\" align=\"left\">&nbsp;</td> \n";
		echo "</tr> \n";
		echo "<tr> \n";
		echo "<td width=\"100%\" valign=\"bottom\" align=\"left\">";
		echo "<a href=\"javascript:history.go(-1)\"><font color='#0000FF' size='1' face='Verdana, Arial, Helvetica, sans-serif'>BACK</font></a>\n";
		echo "</td> \n";
		echo "</tr> \n";
		echo "</table> \n";
		if(!$continueLoad)
			exit;
	}	
	
};
?>
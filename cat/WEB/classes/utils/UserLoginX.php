<?
/* *******************************************************************************************************
MODUL NAME 			: SIMWEB
FILE NAME 			: TamuLogin.php
AUTHOR				: MRF
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: Class that responsible to handle process authentication for users on Admin group
***************************************************************************************************** */

/***********************************************************************
class.userlogin.php	
Mengelola informasi tentang user login. Untuk menggunakan kelas ini tidak
perlu di-instansiasi dulu, sudah otomatis.
Priyo Edi Purnomo dimodifikasi M Reza Faisal
************************************************************************/

include_once("../WEB/classes/db/Entity.php");
include_once("../WEB/classes/utils/GlobalParam.php");
include_once("../WEB/classes/entities/Users.php");
include_once("../WEB/classes/entities/GroupAccess.php");

//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/db/Entity.php");
//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/utils/GlobalParam.php");
//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/entities/Users.php");
//include_once("$INDEX_ROOT/$INDEX_SUB/src/valsix/WEB/classes/entities/GroupAccess.php");

  class UserLogin{
    /* Properties */
    //-- PERSISTENT IN SESSION
	var $userPelamarId;
	var $userPelamarEnkripId;
	var $userPelamarIsloginPertama;
	var $nama;
    var $idUser;
	var $loginTime;
	var $loginTimeStr;
	var $level;
	var $idLevel;
	var $userStatus;
	
	var $pageLevel;
	var $bannedPageLevel;
	
	var $pageId;
		
    //-- NOT PERSISTENT
	var $userSatker;
	var $userJenis;
	var $userEmail;
	var $userHp;
	var $active;
	//-- BUGTRACK
	var $query;

    /******************** CONSTRUCTOR **************************************/
    function UserLogin(){
		 $this->emptyProps();
		 $this->setProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->userPelamarId = "";
		$this->userPelamarEnkripId = "";
		$this->userPelamarIsloginPertama= "";
		$this->userNoRegister = "";
		$this->idUser = "";
		$this->nama = "";		
		$this->loginTime = "";
		$this->loginTimeStr = "";
		$this->level = "";
		$this->idLevel = "";
		$this->pageLevel = false;
			
		$this->active = false;
    }

    /** empty sessions **/
    function emptyUsrSessions(){
		$_SESSION["ssUsrPelamarId"] = "";
		$_SESSION["ssUsrPelamarEnkripId"] = "";
		$_SESSION["ssUsrPelamarIsloginPertama"] = "";
		$_SESSION["ssUsrNoRegister"] = "";
		$_SESSION["ssUsr_idUserAdmin"] = "";
		$_SESSION["ssUsrNama"] = "";		
		$_SESSION["ssUsr_loginTime"] = "";
		$_SESSION["ssUsr_loginTimeStr"] = "";		
		$_SESSION["ssUsr_userSatker"] = "";
		$_SESSION["ssUsr_userJenis"] = "";	
		$_SESSION["ssUsr_userEmail"] = "";	
		$_SESSION["ssUsr_level"] = "";		
		$_SESSION["ssUsr_idLevel"] = "";	
		
    }

	/** reset user information **/
	function resetUserInfo(){
		$this->emptyUsrSessions();
		$this->emptyProps();
	}
		
    /** set properties depends on data from sessions**/
    function setProps(){
		$this->userPelamarId = $_SESSION["ssUsrPelamarId"];
		$this->userPelamarEnkripId = $_SESSION["ssUsrPelamarEnkripId"];
		$this->userPelamarIsloginPertama= $_SESSION["ssUsrPelamarIsloginPertama"];
		$this->userNoRegister = $_SESSION["ssUsrNoRegister"];
		
		$this->idUser = $_SESSION["ssUsr_idUserAdmin"];
		$this->nama = $_SESSION["ssUsrNama"];		
		$this->loginTime = $_SESSION["ssUsr_loginTime"];
		$this->loginTimeStr = $_SESSION["ssUsr_loginTimeStr"];
		$this->level = $_SESSION["ssUsr_level"];
		$this->idLevel = $_SESSION["ssUsr_idLevel"];
		
		if($this->idUser)
			//$this->active = true;
			$this->active = true; //$this->idUser;
    }
    
    /** Verify user login. True when login is valid**/
    function verifyUserLogin($usrLogin,$password){			
		$usr = new Users();
		$this->resetUserInfo();
		if(trim($usrLogin)=="" || trim($password)==""){				
			//echo 'gagal 1<br>';
			return false;        
		}	
		if(!$usr->selectByIdPassword($usrLogin,md5($password))){
			//echo 'gagal 2<br>'.$usr->query;
			//echo 'gagal 2<br>'.$usr->errorMsg;
			return false;
		}
		if($usr->firstRow()){
			/*TEMPAT_LAHIR, TANGGAL_LAHIR, ALAMAT, KOTA,
		    JENIS_KELAMIN, NO_KTP, KTP_FILE, FOTO_FILE, EMAIL1, EMAIL2, NO_HP, ,
		    TANGGAL_DAFTAR, FORMASI_ID, PASSWORD, NPWP, KTP_BERLAKU, STATUS_KAWIN, AGAMA, KODE_POS, TELP_RUMAH,
		    IS_ALAMAT_KTP, ALAMAT_DOMISILI, IS_LAMAR_KPK, ALASAN_MELAMAR1, ALASAN_MELAMAR2, ALASAN_MELAMAR3, KOMPENSASI*/
			$_SESSION["ssUsrPelamarId"] = $usr->getField("PELAMAR_ID");
			$_SESSION["ssUsrPelamarEnkripId"] = $usr->getField("PELAMAR_ID_ENKRIP");
			$_SESSION["ssUsrPelamarIsloginPertama"] = $usr->getField("IS_LOGIN_PERTAMA");
			$_SESSION["ssUsrNoRegister"] = $usr->getField("NO_REGISTER");						
			$_SESSION["ssUsr_idUserAdmin"] = $usr->getField("EMAIL1");
			$_SESSION["ssUsrNama"] = str_replace("'", "", $usr->getField("NAMA"));
			$_SESSION["ssUsr_loginTime"] = time();
			$_SESSION["ssUsr_loginTimeStr"] = date("l, j M Y, H:i",time());
			$_SESSION["ssUsr_level"] = $usr->getField("USER_GROUP");
			$_SESSION["ssUsr_idLevel"] = $usr->getField("USER_GROUP_ID");
			
			$this->setProps();
		}else{
			return false; //login/password salah								
  	  }
      unset($usr);
      return true;
    }
		    
	// added by esa unutk ubah password supaya jika pengisian password salah tidak dilakukan verify user login
	function verifyPassLama($usrLogin,$password){			
      $usr = new Users();
			
	  if(!$usr->selectByIdPassword($usrLogin,$password)){
        return false;
		exit();
      }
					
      if($usr->firstRow()){
        $this->active=true;
		return true;
	  }else {
		return false;
	  }
				
      unset($usr);
      return true;
    }

    /** Reset login information **/
    function resetLogin(){
      $this->emptyUsrSessions();
      $this->emptyProps();
    }

    /** Cek apakah user sudah login atau belum **/
    function checkUserLogin(){
		$usr = new Users();
		
		$statusLogin = false;
		if (trim($_SESSION["ssUsr_idUserAdmin"])) {
			$statusLogin = false;
		}
		$usr->selectById($_SESSION["ssUsr_idUserAdmin"]);
		if (!$usr->firstRow()) {
			$statusLogin = false;
		} else {
			$statusLogin = true;
		}
		
		if (!$statusLogin) {
			echo '<script language="javascript">';
			echo 'alert("Anda tidak punya hak mengakses halaman ini.\n Silakan login terlebih dahulu.");';
			echo 'top.location.href = "index.php";';
			echo '</script>';
			
			exit;
		}
		/*
			return true;
      if(!$this->active){
        unset($dbMgr);
        unset($this);
        showMessageDlg("Untuk mengakses halaman ini anda harus login dulu!",false,"../","parent.");
      }
	  */
	  
	  	return $statusLogin;
    }

    function checkUserLoginAwal(){
		$usr = new Users();
		
		if ($this->userPelamarId == "") {
			echo '<script language="javascript">';
			echo 'top.location.href = "login.php";';
			echo '</script>';
			
			exit;
		}
		/*
			return true;
      if(!$this->active){
        unset($dbMgr);
        unset($this);
        showMessageDlg("Untuk mengakses halaman ini anda harus login dulu!",false,"../","parent.");
      }
	  */
	  
	  	return $statusLogin;
    }
    /** Cek apakah user memiliki kode akses yang dimaksud **/
    function checkAccessCode($accessCode=""){

      $found=0;

      if($accessCode=="")
        return true;
      else{//ada kode aksesnya
        $usr = new User();
        $usr->load($this->usrID);
        $groupFac=new GrpPrivilege();
        $groupFac->findByIdGroup($usr->idGroup);
        if ($groupFac->firstRow()){
          do{
            if ($groupFac->accessCode==$accessCode)
              $found=1;
          }while($groupFac->goNext() && !$found);
        }
        unset($groupFac);
        unset($usr);
        unset($this);
        if (!$found)
          showMessageDlg("Anda tidak memiliki hak untuk mengakses fasilitas ini.",false,"../main/mainpage.php");
        else
          return true;

      }

    }

	/** Mengambil informasi user yang sedang logged in **/
	function retrieveUserInfo(){
		$this->userPelamarId = $_SESSION["ssUsrPelamarId"];
		$this->userPelamarEnkripId = $_SESSION["ssUsrPelamarEnkripId"];
		$this->userPelamarIsloginPertama= $_SESSION["ssUsrPelamarIsloginPertama"];
		$this->userNoRegister = $_SESSION["ssUsrNoRegister"];
		$this->idUser = $_SESSION["ssUsr_idUserAdmin"];
		$this->nama = $_SESSION["ssUsrNama"];
		$this->level = $_SESSION["ssUsr_level"];
		$this->userRekanan = $_SESSION["ssUsr_rekanan"];
		$this->userPKP = $_SESSION["ssUsr_userPKP"];
		$this->userNPWP = $_SESSION["ssUsr_userNPWP"];
		$this->idLevel = $_SESSION["ssUsr_idLevel"];
	}
  	
	/* Mengambil informasi tanggal login */  
	function getLoginDateStr(){
		return date("l, j M Y",$this->loginTime);		
	}
		
	/* Mengambil informasi tanggal login */  
	function getLoginTimeStr(){
		return date("H:i",$this->loginTime);		
	}
	
	/* mengeset level akses halaman 
	   isi $varLevel dengan array untuk multilevel
	   # $varBannedLevel : level yang ditolak
	*/
	function setPageLevel($varLevel, $varBannedLevel = "")
	{
		$this->pageLevel = $varLevel;
		$this->bannedPageLevel = $varBannedLevel;
	}
	
	/* mengeset ID halaman yang akan dibandingkan dengan tabel group_access
	   apakah halaman yg bersangkutan boleh diakses oleh level user yg sedang login
	*/
	function setPageId($varId)
	{
		$this->pageId = $varId;
	}
	
	/* Mengecek level akses halaman berdasarkan $pageLevel dan $level. 
	   Jika privilege tepat maka return true.
	   Jika $pageLevel tidak diset maka akan selalu return true 
	   # untuk admin : $this->level = 1
	   # untuk guest : $this->level = 9999
	   # halaman yang boleh diakses user asal sudah login, maka : set $this->pageLevel = 9999 
	 */
	function checkPagePrivileges($autoExit = true)
	{
		$ret = false;
		
		if($this->pageLevel == false)
			$ret = true;
		
		// if admin, bypass checking
		// check whether $pageLevel is array or not
		// jika pageLevel = 9999 (public) then bypass checking
		if(is_array($this->pageLevel))
		{
			foreach($this->pageLevel as $key => $value)
			{
				if($value == $this->level || $this->level == '1'|| $this->level == '2' || $this->pageLevel == '9999')
				{
					$ret = true;
					break;
				}
				else
					$ret = false;
			}
		}
		else
		{
			if($this->pageLevel == $this->level || $this->level == '1'|| $this->level == '2' || $this->pageLevel == '9999')
				$ret = true;
			else
				$ret = false;
		}
		
		// check for any banned level
		if(is_array($this->bannedPageLevel))
		{
			foreach($this->bannedPageLevel as $key => $value)
			{
				if($value == $this->level)
				{
					$ret = false;
					break;
				}
			}
		}
		else
		{
			if($this->bannedPageLevel == $this->level)
			{
				$ret = false;
				//break;
			}
		}
		
		// cek page access
		if($this->checkPageAccess() == false)
			$ret = false;
		
		
		if($autoExit == true)
		{
			if($ret == false) exit;
		}
		else
			return $ret;
	}
	
	/* helper method untuk mengecek apakah halaman yang bersangkutan 
	   boleh dibuka oleh usergroup yg sedang login 
	*/
	function checkPageAccess()
	{
		$gp = new GlobalParam();
		
		$group_access = new GroupAccess();
		
		$group_access->selectByParams(array('UGID' => $this->level, 'id_menu' => $this->pageId));
		
		// bypass if admin
		if($this->level == $gp->usergroupAdmin)
			return true;
		
		if($group_access->firstRow())
			return true;
		else
			return false;
	}
	
	function checkAuthPemeliharaan($nrp="")
	{
		$usr = new Users();
		return $usr->getURLAplikasiPemeliharaan($nrp);
    }
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $userLogin = new UserLogin();

?>
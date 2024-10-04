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

include_once("../WEB/classes/utils/GlobalParam.php");
include_once("../WEB/classes/entities/Users.php");
include_once("../WEB/classes/entities/GroupAccess.php");

  class UserLogin{
    /* Properties */
    //-- PERSISTENT IN SESSION
	var $UID;
	var $nama;
    var $idUser;
	var $loginTime;
	var $loginTimeStr;
	var $userLevel;
	var $userStatus;
	
	var $pageLevel;
	var $bannedPageLevel;
	
	var $pageId;
		
    //-- NOT PERSISTENT
	var $userGroupId;	
	var $userPegawaiId;
	var $userSatkerId;
	var $userSatkerIdTambahan;
	var $userSatkerIdMix;
	
	var $userSatkerIkk;
	var $userSatkerPengembanganSdm;
	var $userSatkerPolaKarir;
	var $userSatkerEvaluasiKinerja;
	var $userSatkerTugasBelajar;
	var $userSatkerRencanaSuksesi;
	var $userPengaturanIKK;
	
	var $userJenis;
	var $userEmail;
	var $userHp;
	var $userPegawaiProses;
	var $userDUKProses;
	var $userKGBProses;
	var $userGroupNama;
	var $userPensiunProses;
	var $userAnjabProses;
	var $userMutasiProses;
	var $userHukumanProses;
	var $userMasterProses;
	var $userLihatProses;
	var $userTempList;
	
	var $userBidangPembinaan;
	var $userBidangDokumentasi;
	var $userBidangPendidikan;
	var $userBidangMutasi;
	
	var $userAsesorId;
	
	//-- BUGTRACK
	var $query;

    /******************** CONSTRUCTOR **************************************/
    function UserLogin(){
		/*session_register("ssUsr_UID");
		session_register("ssUsr_idUser");
		session_register("ssUsr_nama");	
		session_register("ssUsr_loginTime");
		session_register("ssUsr_loginTimeStr");							
		session_register("ssUsr_userGroupId");
		session_register("ssUsr_userPegawaiId");
		session_register("ssUsr_userSatkerId");
		session_register("ssUsr_userSatkerIdTambahan");
		session_register("ssUsr_userSatkerIdMix");
		session_register("ssUsr_userJenis");
		session_register("ssUsr_userEmail");
		session_register("ssUsr_userHp");	
		session_register("ssUsr_userTempList");	

		session_register("ssUsr_userPegawaiProses");		
		session_register("ssUsr_userDUKProses");		
		session_register("ssUsr_userKGBProses");		
		session_register("ssUsr_userGroupNama");		
		session_register("ssUsr_userPensiunProses");		
		session_register("ssUsr_userAnjabProses");		
		session_register("ssUsr_userMutasiProses");		
		session_register("ssUsr_userHukumanProses");		
		session_register("ssUsr_userMasterProses");
		session_register("ssUsr_userLihatProses");
		session_register("ssUsr_userPengaturanIKK");
		session_register("ssUsr_reqFieldName");
		session_register("ssUsr_reqKondisiField");
		session_register("ssUsr_reqKondisiOperasi");
		session_register("ssUsr_reqKondisiValue");						
		session_register("ssUsr_userBidangPembinaan");
		session_register("ssUsr_userBidangDokumentasi");
		session_register("ssUsr_userBidangPendidikan");
		session_register("ssUsr_userBidangMutasi");*/

		 $this->emptyProps();
		 $this->setProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->UID = "";
		$this->idUser = "";
		$this->nama = "";		
		$this->loginTime = "";
		$this->loginTimeStr = "";
		$this->userLevel = "";
		$this->userStatus = "";
		$this->kelas = "";
		$this->pageLevel = false;
			
		$this->active = false;
    }

    /** empty sessions **/
    function emptyUsrSessions(){
		$_SESSION["ssUsr_UID"] = "";
		$_SESSION["ssUsr_idUser"] = "";
		$_SESSION["ssUsr_nama"] = "";		
		$_SESSION["ssUsr_loginTime"] = "";
		$_SESSION["ssUsr_loginTimeStr"] = "";		
		$_SESSION["ssUsr_userGroupId"] = "";
		$_SESSION["ssUsr_userPegawaiId"] = "";
		$_SESSION["ssUsr_userSatkerId"] = "";
		$_SESSION["ssUsr_userSatkerIdTambahan"] = "";
		$_SESSION["ssUsr_userSatkerIdMix"] = "";
		
		$_SESSION["ssUsr_userSatkerIkk"]= "";
		$_SESSION["ssUsr_userSatkerPengembanganSdm"]= "";
		$_SESSION["ssUsr_userSatkerPolaKarir"]= "";
		$_SESSION["ssUsr_userSatkerEvaluasiKinerja"]= "";
		$_SESSION["ssUsr_userSatkerTugasBelajar"]= "";
		$_SESSION["ssUsr_userSatkerRencanaSuksesi"]= "";
		$_SESSION["ssUsr_userPengaturanIKK"]= "";
		
		$_SESSION["ssUsr_userJenis"] = "";	
		$_SESSION["ssUsr_userEmail"] = "";	
		$_SESSION["ssUsr_userHp"] = "";			

		$_SESSION["ssUsr_userTempList"] = "";
		$_SESSION["ssUsr_userPegawaiProses"] = "";			
		$_SESSION["ssUsr_userDUKProses"] = "";			
		$_SESSION["ssUsr_userKGBProses"] = "";			
		$_SESSION["ssUsr_userGroupNama"] = "";			
		$_SESSION["ssUsr_userPensiunProses"] = "";			
		$_SESSION["ssUsr_userAnjabProses"] = "";			
		$_SESSION["ssUsr_userMutasiProses"] = "";			
		$_SESSION["ssUsr_userHukumanProses"] = "";			
		$_SESSION["ssUsr_userMasterProses"] = "";
		$_SESSION["ssUsr_userLihatProses"] = "";
		
		$_SESSION["ssUsr_userBidangPembinaan"] = "";
		$_SESSION["ssUsr_userBidangDokumentasi"] = "";
		$_SESSION["ssUsr_userBidangPendidikan"] = "";
		$_SESSION["ssUsr_userBidangMutasi"] = "";
		
		$_SESSION["ssUsr_userAsesorId"] = "";
    }

	/** reset user information **/
	function resetUserInfo(){
		$this->emptyUsrSessions();
		$this->emptyProps();
	}
		
    /** set properties depends on data from sessions**/
    function setProps(){
		$this->UID = $_SESSION["ssUsr_UID"];
		$this->idUser = $_SESSION["ssUsr_idUser"];
		$this->nama = $_SESSION["ssUsr_nama"];		
		$this->loginTime = $_SESSION["ssUsr_loginTime"];
		$this->loginTimeStr = $_SESSION["ssUsr_loginTimeStr"];
		$this->userLevel = $_SESSION["ssUsr_level"];
		$this->userStatus = $_SESSION["ssUsr_status"];		
		$this->userGroupId = $_SESSION["ssUsr_userGroupId"];	
		$this->userPegawaiId = $_SESSION["ssUsr_userPegawaiId"];
		$this->userSatkerId = $_SESSION["ssUsr_userSatkerId"];
		$this->userSatkerIdTambahan = $_SESSION["ssUsr_userSatkerIdTambahan"];
		$this->userSatkerIdMix = $_SESSION["ssUsr_userSatkerIdMix"];
		
		$this->userSatkerIkk= $_SESSION["ssUsr_userSatkerIkk"];
		$this->userSatkerPengembanganSdm= $_SESSION["ssUsr_userSatkerPengembanganSdm"];
		$this->userSatkerPolaKarir= $_SESSION["ssUsr_userSatkerPolaKarir"];
		$this->userSatkerEvaluasiKinerja= $_SESSION["ssUsr_userSatkerEvaluasiKinerja"];
		$this->userSatkerTugasBelajar= $_SESSION["ssUsr_userSatkerTugasBelajar"];
		$this->userSatkerRencanaSuksesi= $_SESSION["ssUsr_userSatkerRencanaSuksesi"];
		$this->ssUsr_userPengaturanIKK= $_SESSION["ssUsr_userPengaturanIKK"];

		$this->userJenis = $_SESSION["ssUsr_userJenis"];
		$this->userEmail = $_SESSION["ssUsr_userEmail"];
		$this->userHp = $_SESSION["ssUsr_userHp"];		
		
		$this->userTempList= $_SESSION["ssUsr_userTempList"];
		$this->userPegawaiProses = $_SESSION["ssUsr_userPegawaiProses"];			
		$this->userDUKProses = $_SESSION["ssUsr_userDUKProses"];			
		$this->userKGBProses = $_SESSION["ssUsr_userKGBProses"];			
		$this->userGroupNama = $_SESSION["ssUsr_userGroupNama"];			
		$this->userPensiunProses = $_SESSION["ssUsr_userPensiunProses"];			
		$this->userAnjabProses = $_SESSION["ssUsr_userAnjabProses"];			
		$this->userMutasiProses = $_SESSION["ssUsr_userMutasiProses"];			
		$this->userHukumanProses = $_SESSION["ssUsr_userHukumanProses"];			
		$this->userMasterProses = $_SESSION["ssUsr_userMasterProses"];
		$this->userLihatProses = $_SESSION["ssUsr_userLihatProses"];
		
		$this->userBidangPembinaan= $_SESSION["ssUsr_userBidangPembinaan"];
		$this->userBidangDokumentasi= $_SESSION["ssUsr_userBidangDokumentasi"];
		$this->userBidangPendidikan= $_SESSION["ssUsr_userBidangPendidikan"];
		$this->userBidangMutasi= $_SESSION["ssUsr_userBidangMutasi"];
		
		$this->userAsesorId= $_SESSION["ssUsr_userAsesorId"];
		
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
			//echo 'gagal 2<br>';
			return false;
		}
		// echo $usr->errorMsg."--";exit;
		//echo "-------";exit;
		// echo $usr->query;exit();
		if($usr->firstRow()){
			// echo "asdsad";exit;
			// echo $usr->getField("USER_APP_ID")."--".$usr->getField("USER_LOGIN").'<br>';exit;
			$_SESSION["ssUsr_UID"] = $usr->getField("USER_APP_ID");
			$_SESSION["ssUsr_idUser"] = $usr->getField("USER_LOGIN");
			$_SESSION["ssUsr_nama"] = $usr->getField("NAMA");
			$_SESSION["ssUsr_loginTime"] = time();
			$_SESSION["ssUsr_loginTimeStr"] = date("l, j M Y, H:i",time());
			$_SESSION["ssUsr_level"] = $usr->getField("USER_GROUP_ID");
			$_SESSION["ssUsr_userPegawaiId"] = $usr->getField("PEGAWAI_ID");
			$_SESSION["ssUsr_userGroupId"] = 1;	//mode bukan satker validator		
			$_SESSION["ssUsr_userSatkerId"] = $usr->getField("SATKER_ID");
			$_SESSION["ssUsr_userSatkerIdTambahan"] = $usr->getField("SATKER_ID_TAMBAHAN");	
			$_SESSION["ssUsr_userSatkerIdMix"] = $usr->getField("SATKER_ID_MIX");
			
			$_SESSION["ssUsr_userSatkerIkk"]= $usr->getField("SATKER_IKK");
			$_SESSION["ssUsr_userSatkerPengembanganSdm"]= $usr->getField("SATKER_PENGEMBANGAN_SDM");
			$_SESSION["ssUsr_userSatkerPolaKarir"]= $usr->getField("SATKER_POLA_KARIR");
			$_SESSION["ssUsr_userSatkerEvaluasiKinerja"]= $usr->getField("SATKER_EVALUASI_KINERJA");
			$_SESSION["ssUsr_userSatkerTugasBelajar"]= $usr->getField("SATKER_TUGAS_BELAJAR");
			$_SESSION["ssUsr_userSatkerRencanaSuksesi"]= $usr->getField("SATKER_RENCANA_SUKSESI");
			$_SESSION["ssUsr_userPengaturanIKK"]= $usr->getField("PENGATURAN_IKK");
			
			$_SESSION["ssUsr_userEmail"] = $usr->getField("EMAIL");
			$_SESSION["ssUsr_userHp"] = $usr->getField("TELEPON");
			
			$_SESSION["ssUsr_userTempList"] = $usr->getField("TEMP_LIST");
			$_SESSION["ssUsr_userPegawaiProses"] = $usr->getField("PEGAWAI_PROSES");			
			$_SESSION["ssUsr_userDUKProses"] = $usr->getField("DUK_PROSES");			
			$_SESSION["ssUsr_userKGBProses"] = $usr->getField("KGB_PROSES");			
			$_SESSION["ssUsr_userGroupNama"] = $usr->getField("GROUP_NAMA");			
			$_SESSION["ssUsr_userPensiunProses"] = $usr->getField("PENSIUN_PROSES");			
			$_SESSION["ssUsr_userAnjabProses"] = $usr->getField("ANJAB_PROSES");			
			$_SESSION["ssUsr_userMutasiProses"] = $usr->getField("MUTASI_PROSES");
			$_SESSION["ssUsr_userHukumanProses"] = $usr->getField("HUKUMAN_PROSES");			
			$_SESSION["ssUsr_userMasterProses"] = $usr->getField("MASTER_PROSES");
			$_SESSION["ssUsr_userLihatProses"] = $usr->getField("LIHAT_PROSES");
			
			$_SESSION["ssUsr_userBidangPembinaan"] = $usr->getField("BIDANG_PEMBINAAN");
			$_SESSION["ssUsr_userBidangDokumentasi"] = $usr->getField("BIDANG_DOKUMENTASI");
			$_SESSION["ssUsr_userBidangPendidikan"] = $usr->getField("BIDANG_PENDIDIKAN");
			$_SESSION["ssUsr_userBidangMutasi"] = $usr->getField("BIDANG_MUTASI");
			
			$_SESSION["ssUsr_userAsesorId"] = $usr->getField("PEGAWAI_ID");
			
			$this->setProps();
		}else{
			//echo 'gagal 3<br>';
			//echo "tidak ada user";
			return false; //login/password salah								
  	  }
      unset($usr);
      return true;
    }
		
	//login user without password
	function verifyUserLoginNoPassword($usrLogin){			
      	$usr = new Users();
			
		$this->resetUserInfo();
      	if(trim($usrLogin)==""){				
			return false;        
		}
				
      	if(!$usr->findById($usrLogin)){
        	return false; //login/password salah
      }
			
			//echo $success.NL;		
      if($usr->firstRow()){
				if($usr->field("aktif")){
					$_SESSION["ssUsr_UID"] = $usr->getField("UID");
					$_SESSION["ssUsr_idUser"] = $usr->getField("USER_NAME");
					$_SESSION["ssUsr_nama"] = $usr->getField("NAMA");
					$_SESSION["ssUsr_siswaId"] = $usr->getField("siswa_id");
					$_SESSION["ssUsr_siswaPenilaianId"] = $usr->getField("siswa_penilaian_id"); 					
					$_SESSION["ssUsr_loginTime"] = time();
					$_SESSION["ssUsr_loginTimeStr"] = date("l, j M Y, H:i",time());
					$_SESSION["ssUsr_kelas"] = $usr->getField("kelas"); 										
					
					$_SESSION["ssUsr_userPegawaiId"] = $usr->getField("satuan_kerja_id");
					$_SESSION["ssUsr_userJenis"] = $usr->getField("jenis");
					$_SESSION["ssUsr_userEmail"] = $usr->getField("email");
					$_SESSION["ssUsr_userHp"] = $usr->getField("hp");
					
					//$this->setProps();
				} else
					return false;//user tidak aktif
      }else
				return false; //login/password salah
				
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
		if (trim($_SESSION["ssUsr_idUser"])) {
			$statusLogin = false;
		}
		$usr->selectById($_SESSION["ssUsr_idUser"]);
		// echo $usr->query;exit();
		if (!$usr->firstRow()) {
			$statusLogin = false;
		} else {
			$statusLogin = true;
		}
		
		if (!$statusLogin) {
			echo '<script language="javascript">';
			//echo 'alert("Anda tidak punya hak mengakses halaman ini.\n Silakan login terlebih dahulu.");';
			echo 'top.location.href = "../main/login.php";';
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
		$usr = new Users();
		$usr->selectById($_SESSION["ssUsr_idUser"]);
		if ($usr->firstRow()) {
			$this->UID = $_SESSION["ssUsr_UID"];
			$this->idUser = $_SESSION["ssUsr_idUser"];
			$this->nama = $usr->getField("NAMA");
			
			$this->userPengaturanIKK= $_SESSION["ssUsr_userPengaturanIKK"];
			$this->userSatkerIkk= $_SESSION["ssUsr_userSatkerIkk"];
			$this->userSatkerPengembanganSdm= $_SESSION["ssUsr_userSatkerPengembanganSdm"];
			$this->userSatkerPolaKarir= $_SESSION["ssUsr_userSatkerPolaKarir"];
			$this->userSatkerEvaluasiKinerja= $_SESSION["ssUsr_userSatkerEvaluasiKinerja"];
			$this->userSatkerTugasBelajar= $_SESSION["ssUsr_userSatkerTugasBelajar"];
			$this->userSatkerRencanaSuksesi= $_SESSION["ssUsr_userSatkerRencanaSuksesi"];

			$this->userGroupId = $usr->getField("USER_GROUP_ID");//1; mode bukan satker validator
			$this->userPegawaiId = $usr->getField("PEGAWAI_ID");
			$this->userSatkerId = $usr->getField("SATKER_ID");
			$this->userSatkerIdTambahan = $usr->getField("SATKER_ID_TAMBAHAN");
			$this->userSatkerIdMix = $usr->getField("SATKER_ID_MIX");
			$this->userJenis = $usr->getField("USER_GROUP_ID");
			$this->userEmail = $usr->getField("EMAIL");
			$this->userHp = $usr->getField("TELEPON");
		}
		
		$this->query = $usr->query;
		
		unset($usr);
	}

	function retrieveUserInfoKhusus($reqId){
		
		if(substr($reqId, 0, 2) == $this->userSatkerId || $this->userGroupId == 1)
		{}
		else
		{
			echo '<script language="javascript">';
			//echo 'alert("Anda tidak punya hak mengakses halaman ini.\n IP Address anda telah kami catat sebagai user yang mencoba mengakses Satker lain.");';
			echo 'top.location.href = "../main/login.php";';
			echo '</script>';
			
			exit;			
		}
		
		$usr = new Users();
		$usr->selectById($_SESSION["ssUsr_idUser"]);
		if ($usr->firstRow()) {
			$this->UID = $_SESSION["ssUsr_UID"];
			$this->idUser = $_SESSION["ssUsr_idUser"];
			$this->nama = $usr->getField("NAMA");
			
			$this->userPengaturanIKK= $_SESSION["ssUsr_userPengaturanIKK"];
			$this->userSatkerIkk= $_SESSION["ssUsr_userSatkerIkk"];
			$this->userSatkerPengembanganSdm= $_SESSION["ssUsr_userSatkerPengembanganSdm"];
			$this->userSatkerPolaKarir= $_SESSION["ssUsr_userSatkerPolaKarir"];
			$this->userSatkerEvaluasiKinerja= $_SESSION["ssUsr_userSatkerEvaluasiKinerja"];
			$this->userSatkerTugasBelajar= $_SESSION["ssUsr_userSatkerTugasBelajar"];
			$this->userSatkerRencanaSuksesi= $_SESSION["ssUsr_userSatkerRencanaSuksesi"];
			
			$this->userGroupId = $usr->getField("USER_GROUP_ID");//1; //mode bukan satker validator 			
			$this->userPegawaiId = $usr->getField("PEGAWAI_ID");
			$this->userSatkerId = $usr->getField("SATKER_ID");
			$this->userSatkerIdTambahan = $usr->getField("SATKER_ID_TAMBAHAN");
			$this->userSatkerIdMix = $usr->getField("SATKER_ID_MIX");
			$this->userJenis = $usr->getField("USER_GROUP_ID");
			$this->userEmail = $usr->getField("EMAIL");
			$this->userHp = $usr->getField("TELEPON");
		}
		
		$this->query = $usr->query;
		
		unset($usr);
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
	
	/* Mengecek level akses halaman berdasarkan $pageLevel dan $userLevel. 
	   Jika privilege tepat maka return true.
	   Jika $pageLevel tidak diset maka akan selalu return true 
	   # untuk admin : $this->userLevel = 1
	   # untuk guest : $this->userLevel = 9999
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
				if($value == $this->userLevel || $this->userLevel == '1'|| $this->userLevel == '2' || $this->pageLevel == '9999')
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
			if($this->pageLevel == $this->userLevel || $this->userLevel == '1'|| $this->userLevel == '2' || $this->pageLevel == '9999')
				$ret = true;
			else
				$ret = false;
		}
		
		// check for any banned level
		if(is_array($this->bannedPageLevel))
		{
			foreach($this->bannedPageLevel as $key => $value)
			{
				if($value == $this->userLevel)
				{
					$ret = false;
					break;
				}
			}
		}
		else
		{
			if($this->bannedPageLevel == $this->userLevel)
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
		
		$group_access->selectByParams(array('UGID' => $this->userLevel, 'id_menu' => $this->pageId));
		
		// bypass if admin
		if($this->userLevel == $gp->usergroupAdmin)
			return true;
		
		if($group_access->firstRow())
			return true;
		else
			return false;
	}
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $userLogin = new UserLogin();

?>
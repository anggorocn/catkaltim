<?
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/utils/UserLogin.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Aplikasi Pelaporan Hasil Assessment</title>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="shortcut icon" type="image/x-icon" href="../WEB/images/favicon.ico">

<script type="text/javascript" src="droptiles/js/Combined.js?v=14"></script>
  
<!-- DROPTILES -->
<!--<link rel="stylesheet" type="text/css" href="droptiles/css/bootstrap.min.css">-->
<link rel="stylesheet" type="text/css" href="droptiles/css/droptiles.css?v=14">
<style>
.app-nama{ color:#77726f; font-size:16px; margin-bottom:10px;}
.app-last-login-icon{ float:left; margin-right:10px; padding-top:7px;}
.app-keterangan{ float:right; color:#aba7a5; }
</style>

<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">

<!-- LIVE DATE -->
<script>

/*
Live Date Script- 
© Dynamic Drive (www.dynamicdrive.com)
For full source code, installation instructions, 100's more DHTML scripts, and Terms Of Use,
visit http://www.dynamicdrive.com
*/

var dayarray = new Array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu")
var montharray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember")

function getthedate() {
    var mydate = new Date()
    var year = mydate.getYear()
    if (year < 1000)
        year += 1900
    var day = mydate.getDay()
    var month = mydate.getMonth()
    var daym = mydate.getDate()
    if (daym < 10)
        daym = "0" + daym
    var hours = mydate.getHours()
    var minutes = mydate.getMinutes()
    var seconds = mydate.getSeconds()
    var dn = "AM"
    if (hours >= 12)
        dn = "PM"
    if (hours > 12) {
        hours = hours - 12
    }
    if (hours == 0)
        hours = 12
    if (minutes <= 9)
        minutes = "0" + minutes
    if (seconds <= 9)
        seconds = "0" + seconds
    
	    //change font size here
    var cdate = "<small><font color='000000' face='Arial'><b>" + dayarray[day] + ", " + montharray[month] + " " + daym + ", " + year + " " + hours + ":" + minutes + ":" + seconds + " " + dn + "</b></font></small>"
	var cjam = hours + ":" + minutes
	var chari = dayarray[day] + ", " + daym + " " + montharray[month] + " " + year
	
    if (document.all)
        //document.all.clock.innerHTML = cdate,
		document.all.jam.innerHTML = cjam,
		document.all.hari.innerHTML = chari
    else if (document.getElementById)
        //document.getElementById("clock").innerHTML = cdate,
		document.getElementById("jam").innerHTML = cjam,
		document.getElementById("hari").innerHTML = chari
    else
        //document.write(cdate),
		document.write(cjam),
		document.write(chari)
}
if (!document.all && !document.getElementById)
    getthedate()

function goforit() {
    if (document.all || document.getElementById)
        setInterval("getthedate()", 1000)
}

</script>

<link href="../WEB/css/hiddenMenu.css" rel="stylesheet" type="text/css" />

<script type='text/javascript'>
	$(window).load(function(){
		$('button#atasbawah').click(function () {
			//$("a").removeClass("menuAktifDynamis");
			$('#kontainer-atas').toggleClass('hidden');
			$('#trdetil').toggleClass('hidden');
		});
	});
	
	function setShowHideMenu(kondisi)
	{
		if(kondisi == 1)//bagi dua
		{
			$("#kontainer-atas").removeClass("kontainer-atasFull");
			$("#kontainer-atas").addClass("kontainer-atasBagi");
		}
		else
		{
			$("#kontainer-atas").removeClass("kontainer-atasBagi");
			$("#kontainer-atas").addClass("kontainer-atasFull");
		}
	}
	
	function setLoad(url, tipe)
	{
		if(tipe == 1)
		{
			document.getElementById('trdetil').style.display = 'none';
			setShowHideMenu(2);
		}
		else
		{
			document.getElementById('trdetil').style.display = '';
			setShowHideMenu(1);
		}
		
		mainFrame.location.href=url;
	}
</script>

</head>

<body style="overflow:hidden;" onLoad="goforit()" >

<?
include_once "../global_page/header.php";
?>
<div id="main-kontainer">
	<?
	//$reqId=8760;
	//$reqId=680;
	$reqId = $userLogin->userPegawaiId;
	?>
	<div id="login-area" style="height:92%;">
    	<div id="judul-main" style="margin-top:-15px;"><span> Informasi</span> Pegawai&nbsp;</div>
    	<iframe src="../silat/pegawai_menu_pegawai_edit.php?reqPegawaiId=<?=$reqId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>" name="menuFrame" style="margin-left:-15px; margin-top:5px" width="110%" height="95%" scrolling="auto" frameborder="0"></iframe>
	</div>
    
    <div id="logo-login-area" style="height:91%;">
    	<div style="height:108%; margin-top:-45px;">
            <div id="kontainer-atas" class="kontainer-atasFull">
                <iframe class="mainframe" id="idMainFrame" name="mainFrame" src="../silat/identitas_edit.php?reqPegawaiId=<?=$reqId?>&reqIdOrganisasi=<?=$reqIdOrganisasi?>&reqConfirm=<?=$reqConfirm?>" style="width:100%; height:calc(100% - 5px); border:none;"></iframe>
            </div>
            
            <div id="trdetil" style="overflow:hidden; display:none">
                <button id="atasbawah">Show/Hide</button>
                <iframe class="mainframe" id="idMainFrame" name="mainFrameDetil" src="" style="width:100%; height: 100%;  border:none;"></iframe>
            </div>
        </div>
    </div>
    
</div>
<div id="main-footer">
	&copy; 2016 Kementerian Dalam Negeri. All Rights Reserved.
</div>
</body>


<script type="text/javascript">
    // Bootstrap initialization
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>

<script type="text/javascript">
    window.currentUser = new User({
        firstName: "None",
        lastName: "Anonymous",
        photo: "img/User No-Frame.png",
        isAnonymous: true
    });
</script>

<script type="text/javascript" src="droptiles/js/CombinedDashboard.js?v=14"></script>
</html>

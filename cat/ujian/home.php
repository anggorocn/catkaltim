<?
include_once("../WEB/classes/utils/crfs_protect.php");

$csrf = new crfs_protect('_crfs_login');
?>
<script type="text/javascript">
    function UploadPopUp()
    {
        parent.OpenDHTML2('video_tutorial.php')
        // console.log ("asasasasa");
    }
    function nextModul()
    {
        document.location.href= "?pg=dashboard&mode=simulasi";
    }   
</script>

<!-- <script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script> -->
<link href="../WEB/lib/fontawesome/css/all.min.css" rel="stylesheet"><!--load all styles -->


<div class="logo"><img src="../WEB/images-ujian/logo-nobg.png"></div>
<!-- <div class="judul-1">TVRI</div> -->

<div class="area-login">
    <div class="judul-1">TES BERBASIS ONLINE</div>
    <div class="judul-2">CAT PENILAIAN KOMPETENSI</div>

    
    <form class="form-horizontal" id="signupForm" role="form" method="post">
        
        <div class="form-group">
            <input type="text" class="form-control" name="reqUser" id="reqUser" placeholder="NIP" required="true" />
        </div>
        
        <div class="form-group">
            <input type="password" class="form-control" name="reqPasswd" id="reqPasswd" placeholder="Password" required="true" />
        </div>
        
        <div class="form-group">
            <input type="submit" value="LOGIN" style="color: black;" />
            <input type="hidden" name="reqIp" value="<?=$tempIpUser?>">
            <input type="hidden" name="reqMode" value="submitLogin">
            <?=$csrf->echoInputField();?>
        </div>        
    </form>
</div>


<div class="area-prev-next">
            <!-- <div class="prev"  style="width: 10%;"><a href="?pg=tes" style="width: auto;">Intruksi</a></div> -->
           <button style="background-color: #216aac; align-items: right">

        <a onclick="nextModul()" style="color: white;">
        Instruksi
        </a>
        </button>
    </div>  



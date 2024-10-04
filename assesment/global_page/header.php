<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib/Font-Awesome-4.5.0/css/font-awesome.css">
<link rel="stylesheet" href="../WEB/lib/font-awesome-4.7.0/css/font-awesome.css">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<?
$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
$rconf_url= $data->urlConfig->main->url;
?>
<div id="main" class="container-fluid clear-top" style="height:15%;">
<div class="row">
    <div class="col-md-12 area-header">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2">
                    <img src="../WEB/images/logo-judul.png"> 
                </div>
                <div class="col-md-5" style="margin-left: -40px;line-height: 20px;">
                    <span><b>Sistem Informasi 
                    <br>Manajemen Assessment Center</b></span>
                    <hr style="margin:0px">
                    <span style="font-size: 12px;color: #009f3b ;">Provinsi Kalimantan Timur</span>
                </div>
            </div>
        </div> 
        <div class="col-md-6">
            <div class="area-akun" style="margin-top: 0px;">
                <span class="logo-kkp"></span>
                <span class="vr-header"></span>
                <a href="<?=$rconf_url?>"> 
                    <i class="fa fa-home" aria-hidden="true" style="color: green; font-size: 75px;"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
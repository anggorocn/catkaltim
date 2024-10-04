<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/UserGroupsBase.php");
include_once("../WEB/classes/base/Dashboard.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
    $userLogin->retrieveUserInfo();  
}


$set= new Dashboard();
$set->selectByParamsSudahUjian(array(), -1, -1);
$set->firstRow();
$tempSudahUjian = $set->getField("ROWCOUNT");

$set->selectByParamsTotalPegawai(array(), -1, -1);
$set->firstRow();
$tempTotalPeserta = $set->getField("ROWCOUNT");

$tempBelumUjian=$tempTotalPeserta-$tempSudahUjian;

$set->selectByParamsJekelPegawai(array(), -1, -1);
// echo $set->query; exit;
while($set->NextRow()){
    $tempTotalJekel[$set->getField("jenis_kelamin")]['total'] = $set->getField("ROWCOUNT");
    $tempTotalJekel[$set->getField("jenis_kelamin")]['total_asesment'] = $set->getField("ROWCOUNTAsesment");
    $tempTotalJekel[$set->getField("jenis_kelamin")]['total_asesment_belum'] = $set->getField("ROWCOUNT")-$set->getField("ROWCOUNTAsesment");
};

$set->selectByParamEseloPegawai(array(), -1, -1);
// echo $set->query; exit;
$i=0;
while($set->NextRow()){
    $tempTotalEselon[$i]['nama'] = $set->getField("nama");
    $tempTotalEselon[$i]['sudah'] = $set->getField("total_pegawai_ujian");
    $tempTotalEselon[$i]['belum'] = $set->getField("total_pegawai")-$set->getField("total_pegawai_ujian");
    $i++;
};
$totalEselon=$i;

// print_r($tempTotalEselon);exit;

$set= new UserGroupsBase();
$set->selectByParamsMonitoring(array('A.NAMA'=>$userLogin->userGroupNama), -1, -1);
$set->firstRow();


$tempPolaKarir = $set->getField("POLA_KARIR_PROSES");
$tempRencanaSuksesi = $set->getField("RENCANA_SUKSESI_PROSES");
$tempIKK = $set->getField("IKK_PROSES");
$tempPengembangan = $set->getField("PENGEMBANGAN_SDM_PROSES");

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$tempListInfo= $userLogin->userTempList;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Monitoring PENILAIAN KOMPETENSI</title>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib/Font-Awesome-4.5.0/css/font-awesome.css">
    
<!--<script type='text/javascript' src="../WEB/lib/bootstrap/jquery.js"></script> -->

    <style>
    .col-md-12{
        *padding-left:0px;
        *padding-right:0px;
    }
    </style>
    
    <!-- <script src="../WEB/lib/emodal/eModal.js"></script> -->
    <script>
    function openPopup() {
        //document.getElementById("demo").innerHTML = "Hello World";
        //alert('hhh');
        // Display a ajax modal, with a title
        eModal.ajax('konten.html', 'Judul Popup')
        //  .then(ajaxOnLoadCallback);
    }

    

    </script>
    
    <!-- FLUSH FOOTER -->
    <style>
    html, body {
        height: 100%;
    }
    
    #wrap-utama {
        min-height: 100%;
        *min-height: calc(100% - 10px);
    }
    
    #main {
        overflow:auto;
        padding-bottom:50px; /* this needs to be bigger than footer height*/
        
    }
    
    .footer {
        position: relative;
        margin-top: -50px; /* negative value of footer height */
        height: 50px;
        clear:both;
        padding-top:20px;
        *background:cyan;
        
        text-align:center;
        color:#FFF;
    }
    @media screen and (max-width:767px) {
        .footer {
            font-size:12px;
        }
    }

    </style>
    
</head>

<body>

<div id="wrap-utama" style="height:100%; ">
    <div id="main" class="container-fluid clear-top" style="height:100%;">
        
        <div class="row">
            <div class="col-md-12 area-header">
                <span class="judul-app"><img src="../WEB/images/logo-judul.png"> Sistem Manajemen Assessment Center</span>
                <div class="area-akun">
                    Selamat datang, <strong><?=$userLogin->nama?></strong> (<?=$userLogin->userGroupNama?>) - 
                    <a href="login.php?reqMode=submitLogout"><i class="fa fa-sign-out"></i> Logout</a>
                </div>
            </div>
        </div>
        
        <div class="row" style="height:calc(100% - 20px);">
            <div class="col-md-12" style="height:100%;">
                
                <div class="area-menu-app-wrapper">
                    <div class="judul-halaman" style="color: black; background-color: white; width: 150px; border-top-left-radius: 10px;border-top-right-radius: 10px;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;padding-left: 10px;">Main Menu</div>
                    <div class="area-menu-app-inner">
                        <div class="container area-menu-app">
                           <div class="row">
                                <?
                                if($userLogin->userPegawaiId==""){
                                ?>
                                <table>
                                    <tr>
                                        <?
                                        if($userLogin->userPegawaiId=="")
                                        {
                                        if($tempIKK==1)
                                        {
                                        ?>
                                        <td>
                                            <div class="area-main-menu" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="item">
                                                    <a href="../ikk/index.php">
                                                    <div class="icon"><i class="fa fa-pencil-square-o fa-3x"></i></div>
                                                    <div class="teks">Penilaian Kompetensi</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <?
                                        }
                                        if($tempPengembangan==1)
                                        {
                                        ?>
                                        <td>
                                            <div class="area-main-menu" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="item">
                                                    <a href="../silat/index.php">
                                                    <div class="icon"><i class="fa fa-user fa-3x"></i></div>
                                                    <div class="teks">HCDP</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <?
                                        }
                                        // if(findWord($tempListInfo, "Pegawai Pola Karir") == 1 || $tempListInfo == "")
                                        // {
                                        ?>
                                        <!-- <div class="col-md-5ths col-xs-6">
                                            <div class="area-main-menu">
                                                <div class="item">
                                                    <a href="../polakarir/index.php">
                                                    <div class="icon"><i class="fa fa-clipboard fa-3x"></i></div>
                                                    <div class="teks">Pola Karir</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div> -->
                                        <?
                                        // }
                                        if($tempRencanaSuksesi == 1)
                                        {
                                        ?>
                                        <td>
                                            <div class="area-main-menu" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="item">
                                                    <a href="../suksesi/index.php">
                                                    <div class="icon"><i class="fa fa-users fa-3x"></i></div>
                                                    <div class="teks">Rencana Suksesi</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <?
                                        }
                                        if($tempPolaKarir==1)
                                        {
                                        ?>
                                        <td>
                                            <div class="area-main-menu" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="item">
                                                    <a href="../pengaturan/index.php">
                                                    <div class="icon"><i class="fa fa-cog fa-3x"></i></div>
                                                    <div class="teks">Pengaturan</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- <div class="col-md-5ths col-xs-6">
                                            <div class="area-main-menu">
                                                <div class="item">
                                                    <a href="../pengaturan/index_absen.php">
                                                    <div class="icon"><i class="fa fa-qrcode fa-3x"></i></div>
                                                    <div class="teks">Absen</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="col-md-5ths col-xs-6">
                                            <div class="area-main-menu">
                                                <div class="item">
                                                    <a href="../pengaturan/index_hasil_cat.php">
                                                    <div class="icon"><i class="fa fa-qrcode fa-3x"></i></div>
                                                    <div class="teks">Hasil</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div> -->
                
                                        <?
                                        }
                                        }
                                        else
                                        {
                                        ?>
                                        <td>
                                            <div class="col-md-5ths col-xs-6" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="area-main-menu">
                                                    <div class="item">
                                                        <a href="../asesor/index.php">
                                                        <div class="icon"><i class="fa fa-cog fa-3x"></i></div>
                                                        <div class="teks">Asesor</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>   
                                        <?
                                        }
                                        ?>
                                        <!--<div class="col-md-5ths col-xs-6">
                                            <div class="area-main-menu">
                                                <div class="item">
                                                    <a href="#">
                                                    <div class="icon"><i class="fa fa-plus fa-3x"></i></div>
                                                    <div class="teks">tambah menu<br>...</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                    </tr>
                                </table>
                                <?}
                                else{?>
                                <table>
                                    <tr>
                                        <td>
                                            <div class="area-main-menu" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="item">
                                                    <a href="../asesor/index.php?reqMode=administrator">
                                                    <div class="icon"><i class="fa fa-users fa-3x"></i></div>
                                                    <div class="teks">Administrator Kegiatan</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="area-main-menu" style="padding-left: 10px;padding-right: 10px;">
                                                <div class="item">
                                                    <a href="../asesor/index.php">
                                                    <div class="icon"><i class="fa fa-bookmark-o fa-3x"></i></div>
                                                    <div class="teks">Penilaian Asesor</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <?}?>
                                
                            </div>
                        </div>
                    </div>
                    
                
                </div>
                
            </div>
        </div>
        
        <div style="color:white; background-color:black">
jumlah sudah ujian <?=$tempSudahUjian?><br>
jumlah belum ujian <?=$tempBelumUjian?><br>
jumlah sudah ujian laki laki <?=$tempTotalJekel['L']['total_asesment']?><br>
jumlah belum ujian laki laki <?=$tempTotalJekel['L']['total_asesment_belum']?><br>
jumlah sudah ujian perempuan <?=$tempTotalJekel['P']['total_asesment']?><br>
jumlah belum ujian perempuan <?=$tempTotalJekel['P']['total_asesment_belum']?><br>
<?for($i=0;$i<$totalEselon; $i++){?>
    jumlah sudah ujian eselon <?=$tempTotalEselon[$i]['nama']?> <?=$tempTotalEselon[$i]['sudah']?><br>
    jumlah belum ujian eselon <?=$tempTotalEselon[$i]['nama']?> <?=$tempTotalEselon[$i]['belum']?><br>
<?}?>
        </div>
    </div>
</div>

<footer class="footer" style="color: black; background-color: white;">
     © <? echo date('Y'); ?> Pemerintah Provinsi Kalimantan Timur. All Rights Reserved.
</footer>



    
<?php /*?>    <div class="container-fluid">
    
    
    <div class="row">
        <div class="col-md-12">
            <div class="area-footer">
            © 2016 Kementerian Dalam Negeri. All Rights Reserved. 
            </div>
        </div>
    </div>
    
</div>
<!-- /.container --> <?php */?>

<?php /*?><script type='text/javascript' src="../WEB/lib/bootstrap/bootstrap.js"></script> <?php */?>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 
    
</body>
</html>

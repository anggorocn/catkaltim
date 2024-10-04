
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

// print_r($userLogin); exit;

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

$tempTotalEselon['1']['belum']=0;
$tempTotalEselon['2']['belum']=0;
$tempTotalEselon['3']['belum']=0;
$tempTotalEselon['4']['belum']=0;
$tempTotalEselon['9']['belum']=0;
$tempTotalEselon['0']['belum']=0;
$tempTotalEselon['']['belum']=0;
$tempTotalEselon['1']['sudah']=0;
$tempTotalEselon['2']['sudah']=0;
$tempTotalEselon['3']['sudah']=0;
$tempTotalEselon['4']['sudah']=0;
$tempTotalEselon['9']['sudah']=0;
$tempTotalEselon['0']['sudah']=0;
$tempTotalEselon['']['sudah']=0;

while($set->NextRow()){
    $tempTotalEselon[$set->getField("NAMA")]['sudah'] = $set->getField("total_pegawai_ujian");
    $tempTotalEselon[$set->getField("NAMA")]['belum'] = $set->getField("total_pegawai")-$set->getField("total_pegawai_ujian");
};

// $tempTotalEselonBelum="[".$tempTotalEselon['1']['belum'].",".$tempTotalEselon['2']['belum'].",".$tempTotalEselon['3']['belum'].",".$tempTotalEselon['4']['belum'].",".$tempTotalEselon['9']['belum'].",".($tempTotalEselon['0']['belum']+$tempTotalEselon[$set->getField('')]['belum'])."]";
// $tempTotalEselonSudah="[".$tempTotalEselon['1']['sudah'].",".$tempTotalEselon['2']['sudah'].",".$tempTotalEselon['3']['sudah'].",".$tempTotalEselon['4']['belum'].",".$tempTotalEselon['9']['sudah'].",".($tempTotalEselon['0']['sudah']+$tempTotalEselon[$set->getField('')]['sudah'])."]";
$tempTotalEselonSudah="[922,158,212,258,53]";
$tempTotalEselonBelum="[0,0,0,0,0]";

// print_r($tempTotalEselonSudah); exit;

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
<link rel="stylesheet" href="../WEB/lib/font-awesome-4.7.0/css/font-awesome.css">
<link rel="icon" type="image/ico" href="../WEB/../WEB/images/favicon.ico" />

    
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
    .row{
        margin-right: 0px;
        margin-left: 0px;
    }
    @media screen and (max-width:767px) {
        .footer {
            font-size:12px;
        }
        .judul{
            text-align: center;
        }
        .area-akun{
            float: none;
            text-align: center;
        }
        .area-header{
            height: 100%;
        }
        .area-dashboard{
            overflow: hide !important;
            height: auto !important;
            padding: 0px !important;
        }
        .area-grafik{
            padding: 0px !important;
            background: white;
        }
        .judul-halaman{
            margin-left: 0px !important;
        }
        .border-grafik{
            border: 1px solid black;
            border-radius: 20px;
        }
    }

    </style>
    
</head>

<body>

<div id="wrap-utama" style="height:100%;">
    <div id="main" class="container-fluid clear-top" style="height:100%;">
        
        <div class="row">
            <div class="col-md-12 area-header">
                <div class="col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-lg-2 col-lg-12" style="text-align: center">
                            <img src="../WEB/images/logo-judul.png"> 
                        </div>
                        <div class="col-lg-5 col-lg-12 judul" style="">
                            <span><b>Sistem Informasi 
                            <br>Manajemen Assessment Center</b></span>
                            <hr style="margin:0px">
                            <span style="font-size: 12px;color: #009f3b ;">Provinsi Kalimantan Timur</span>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-6 col-md-12">
                    <div class="area-akun">
                        Selamat datang, <strong><?=$userLogin->nama?></strong> , 
                        <a href="login.php?reqMode=submitLogout"> Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4" style="background-color: rgba(0, 159, 59, 0.1);border-radius: 0px 0px 50px 0px">
                <div class="row" style="margin:10px 10px; padding: 10px;">
                    <div class="judul-halaman" style="color: black">Main Menu</div>
                    <?
                    if($userLogin->userPegawaiId==""){
                        if($userLogin->userPegawaiId=="")
                        {
                            if($tempIKK==1)
                            {
                            ?>
                                <div class="col-md-6" style="padding: 20px 5px;">
                                    <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                                        <a href="../ikk/index.php" style="text-align: center;">
                                            <div class="icon" style="color: #f8f4c2;"><i class="fa fa-pencil-square-o fa-3x"></i></div>
                                            <div class="teks" style="color: #f8f4c2; font-size: 12px;">Penilaian Kompetensi</div>
                                        </a>
                                    </div>
                                </div>
                            <?
                            }
                            if($tempPengembangan==1)
                            {
                            ?>
                                <div class="col-md-6" style="padding: 20px 5px;">
                                    <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                                        <a href="../silat/index.php" style="text-align: center;">
                                            <div class="icon" style="color: #f8f4c2;"><i class="fa fa-user fa-3x"></i></div>
                                            <div class="teks" style="color: #f8f4c2; font-size: 12px;">HCDP</div>
                                        </a>
                                    </div>
                                </div>
                            <?
                            }
                            if($tempRencanaSuksesi == 1)
                            {
                            ?>
                                <div class="col-md-6" style="padding: 20px 5px;">
                                    <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                                        <a href="../suksesi/index.php" style="text-align: center;">
                                            <div class="icon" style="color: #f8f4c2;"><i class="fa fa fa-users fa-3x"></i></div>
                                            <div class="teks" style="color: #f8f4c2; font-size: 12px;">Rencana Suksesi</div>
                                        </a>
                                    </div>
                                </div>
                            <?
                            }
                            if($tempPolaKarir==1)
                            {
                            ?>
                                <div class="col-md-6" style="padding: 20px 5px;">
                                    <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                                        <a href="../pengaturan/index.php" style="text-align: center;">
                                            <div class="icon" style="color: #f8f4c2;"><i class="fa fa-cog fa-3x"></i></div>
                                            <div class="teks" style="color: #f8f4c2; font-size: 12px;">Pengaturan</div>
                                        </a>
                                    </div>
                                </div>
                            <?
                            }
                        }
                        else
                        {
                        ?>
                        <div class="col-md-6" style="padding: 20px 5px;">
                            <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                                <a href="../asesor/index.php" style="text-align: center;">
                                    <div class="icon" style="color: #f8f4c2;"><i class="fa fa-cog fa-3x"></i></div>
                                    <div class="teks" style="color: #f8f4c2; font-size: 12px;">Asesor</div>
                                </a>
                            </div>
                        </div>
                        <?
                        }
                    }
                    else{?>
                    <div class="col-md-6" style="padding: 20px 5px;">
                        <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                            <a href="../asesor/index.php?reqMode=administrator" style="text-align: center;"> 
                                <div class="icon" style="color: #f8f4c2;"><i class="fa fa-users fa-3x"></i></div>
                                <div class="teks" style="color: #f8f4c2; font-size: 12px;">Administrator Kegiatan</div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding: 20px 5px;">
                        <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                            <a href="../asesor/index.php" style="text-align: center;">
                                <div class="icon" style="color: #f8f4c2;"><i class="fa fa-bookmark-o fa-3x"></i></div>
                                <div class="teks" style="color: #f8f4c2; font-size: 12px;">Penilaian Asesor</div>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6" style="padding: 20px 5px;">
                        <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                            <form action="../../../feedback_kaltim?reqAdminId=<?=$userLogin->UID?>" method="post" target="_blank">
                                <button style="text-align: center;width: 100%;  background-color: transparent;  border: none;">
                                    <div class="icon" style="color: #f8f4c2;"><i class="fa fa-bar-chart fa-3x"></i></div>
                                    <div class="teks" style="color: #f8f4c2; font-size: 12px;">Feedback</div>
                                    <input type="hidden" name="loginadminid" value="<?=$userLogin->UID?>">
                                </button>
                            </form>
                        </div>
                    </div>
                    <?}?>
                    <div class="col-md-6" style="padding: 20px 5px;">
                        <div class="item" style="background-color:#0e7476; padding: 20px; border-radius: 20px;">
                            <form action="../../../manajemen_talenta_kaltim/admin" method="post" target="_blank">
                                <button style="text-align: center;width: 100%;  background-color: transparent;  border: none;">
                                    <div class="icon" style="color: #f8f4c2;"><i class="fa fa-bar-chart fa-3x"></i></div>
                                    <div class="teks" style="color: #f8f4c2; font-size: 12px;">Manajemen Talenta</div>
                                    <input type="hidden" name="loginadminid" value="<?=$userLogin->UID?>">
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 area-grafik">
                <div class="row area-dashboard" style="overflow: scroll;height: 75vh;">
                    <div class="col-md-12 col-lg-4">
                        <div class="item sudah">
                            <a href="#" style="background-color:#35d8ac; color: white;">
                                <div class="row">
                                    <div class="col-md-6" style="overflow: hidden; padding: 0 0; margin: -15px -10px;">
                                        <img src="../WEB/images/pegawai_sudah.png" style="width: 140px;"> 
                                    </div>
                                    <div class="col-md-6" style="padding-right: 0;">
                                        <div style="font-family: 'Open Sans Bold'; font-size: 20px;text-align: right;">1603</div>
                                        <br>
                                        <div style="font-size: 12px;text-align: right;">
                                            Pegawai yang sudah mengikuti penkom
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="item belum">
                            <a href="#" style="background-color:#ff6038; color: white;">
                                <div class="row">
                                    <div class="col-md-6" style="overflow: hidden; padding: 0 0; margin: -15px -10px;">
                                        <img src="../WEB/images/pegawai_belum.png" style="width: 140px;"> 
                                    </div>
                                    <div class="col-md-6" style="padding-right: 0;">
                                        <div style="font-family: 'Open Sans Bold'; font-size: 20px;text-align: right;"><?=$tempBelumUjian?></div>
                                        <br>
                                        <div style="font-size: 12px;text-align: right;">
                                             Pegawai yang belum mengikuti penkom
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row border-grafik">
                            <div class="judul-halaman" style="background-color: #daf2f2; color: black;border-radius: 10px 10px 0px 0px">Peserta penkom per jenjang jabatan</div>
                            <div class="col-md-12" style="background-color: white;">
                                <div id="container" class="grafik" style="height:37vh;border-radius: 0px 0px 10px 10px "></div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12">
                        <div class="row border-grafik">
                            <div class="judul-halaman" style="background-color: #daf2f2; color: black;border-radius: 10px 10px 0px 0px">Peserta penkom berdasarkan jenis kelamin</div>
                            <div class="col-md-12" style="background-color: white;;border-radius: 0px 0px 10px 10px">
                                <div class="col-md-6">
                                    <div id="container-laki-laki" class="grafik" style="height:40vh"></div>
                                </div>
                                <div class="col-md-6">
                                    <div id="container-perempuan" class="grafik" style="height:40vh"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>        
    </div>
</div>

<footer class="footer" style="color: black;  background-color: white;">
     © <? echo date('Y'); ?> Pemerintah Provinsi Kalimantan Timur. All Rights Reserved.
</footer>

<?for($i=0;$i<$totalEselon; $i++){?>
    jumlah sudah ujian eselon <?=$tempTotalEselon[$i]['nama']?> <?=$tempTotalEselon[$i]['sudah']?><br>
    jumlah belum ujian eselon <?=$tempTotalEselon[$i]['nama']?> <?=$tempTotalEselon[$i]['belum']?><br>
<?}?>

    
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
    
    <!-- HIGHCHART -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            exporting: {
                enabled: false
            },
            title: {
                text: null,
                align: 'left'
            },
            subtitle: {
                text: null,
                align: 'left'
            },
            xAxis: {
                // categories: ['Eselon 1', 'Eselon 2', 'Eselon 3', 'Eselon 4', 'Fungsional', 'Eksternal'],
                categories: ['Pelakasana', 'Fungsional', 'Pengawas', 'Administrator', 'JPTP'],
                crosshair: true,
                accessibility: {
                    description: 'Countries'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: null
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Sudah mengikuti',
                    data: <?=$tempTotalEselonSudah?>,
                    color: '#169e40'
                },
                {
                    name: 'Belum mengikuti',
                    data: <?=$tempTotalEselonBelum?>,
                    color: '#9e1616'
                }
            ]
        });

    </script>

    <script type="text/javascript">
        // Data retrieved from https://netmarketshare.com
        Highcharts.chart('container-laki-laki', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Laki-laki',
                align: 'left'
            },
            tooltip: {
                // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                pointFormat: '{point.percentage:.1f} %<br>{point.y} Orang'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [{
                    name: 'Sudah',
                    y: <?=$tempTotalJekel['L']['total_asesment']?>,
                    sliced: true,
                    selected: true,
                    color: '#169e40'
                }, {
                    name: 'Belum',
                    y: <?=$tempTotalJekel['L']['total_asesment_belum']?>,
                    color: '#9e1616'
                }]
            }]
        });

    </script>

    <script type="text/javascript">
        // Data retrieved from https://netmarketshare.com
        Highcharts.chart('container-perempuan', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Perempuan',
                align: 'left'
            },
            tooltip: {
                // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                pointFormat: '{point.percentage:.1f} %<br>{point.y} Orang'

            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [{
                    name: 'Sudah',
                    y: <?=$tempTotalJekel['P']['total_asesment']?>,
                    sliced: true,
                    selected: true
                }, {
                    name: 'Belum',
                    y: <?=$tempTotalJekel['P']['total_asesment_belum']?>,
                }]
            }]
        });

    </script>

</body>
</html>

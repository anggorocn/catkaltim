
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

<div id="wrap-utama" style="height:100%;">
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
        
        <!-- <div class="row" style="height:calc(100% - 20px);"> -->
        <div class="row"  style="height: calc(50vh - 135px); position: relative;">
            <div class="col-md-12" style="height:100%;">
                <!-- <div class="area-menu-app-wrapper"> -->
                <div>
                    <div class="judul-halaman" style="color: black; background-color: white; width: 150px; border-top-left-radius: 10px;border-top-right-radius: 10px;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;padding-left: 10px; position: absolute; margin-top: 15px;">Main Menu</div>
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
        <div class="row area-dashboard" style="height: calc(50vh + 15px);">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="item sudah">
                            <a href="#">
                                <div class="ikon"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
                                <!-- <div class="nilai"><?=$tempSudahUjian?></div> -->
                                <div class="nilai">1603</div>
                                <div class="clearfix"></div>
                                <div class="keterangan">
                                    Pegawai yang sudah mengikuti penkom
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="item belum">
                            <a href="#">
                                <div class="ikon"><i class="fa fa-user-circle-o" aria-hidden="true"></i></div>
                                <div class="nilai"><?=$tempBelumUjian?></div>
                                <div class="clearfix"></div>
                                <div class="keterangan">
                                    Pegawai yang belum mengikuti penkom
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row area-grafik kolom">
                            <div class="judul-halaman">Peserta penkom per jenjang jabatan</div>
                            <div class="col-md-12">
                                <div id="container" class="grafik"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row area-grafik">
                            <div class="judul-halaman">Peserta penkom berdasarkan jenis kelamin</div>
                            <div class="col-md-6">
                                <div id="container-laki-laki" class="grafik"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="container-perempuan" class="grafik"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4">
                
            </div> -->
        </div>
        
        
        <div style="color:white; background-color:black; display: none;">
<!-- jumlah sudah ujian <?=$tempSudahUjian?><br>
jumlah belum ujian <?=$tempBelumUjian?><br>
jumlah sudah ujian laki laki <?=$tempTotalJekel['L']['total_asesment']?><br>
jumlah belum ujian laki laki <?=$tempTotalJekel['L']['total_asesment_belum']?><br>
jumlah sudah ujian perempuan <?=$tempTotalJekel['P']['total_asesment']?><br>
jumlah belum ujian perempuan <?=$tempTotalJekel['P']['total_asesment_belum']?><br>
<?for($i=0;$i<$totalEselon; $i++){?>
    jumlah sudah ujian eselon <?=$tempTotalEselon[$i]['nama']?> <?=$tempTotalEselon[$i]['sudah']?><br>
    jumlah belum ujian eselon <?=$tempTotalEselon[$i]['nama']?> <?=$tempTotalEselon[$i]['belum']?><br>
<?}?> -->
        </div>
    </div>
</div>

<footer class="footer" style="color: black; background-color: white;">
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

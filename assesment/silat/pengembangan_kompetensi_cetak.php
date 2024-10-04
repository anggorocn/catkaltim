<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PegawaiHcdp.php");
include_once("../WEB/classes/base/PelatihanHcdp.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqFormulaId= httpFilterGet("reqFormulaId");

$set= new PegawaiHcdp();
$set->selectByParams(array('A.PEGAWAI_ID'=>$reqId, 'A.FORMULA_ID'=>$reqFormulaId), -1, -1);
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField("PEGAWAI_HCDP_ID");
$reqJumlahJp= $set->getField("JUMLAH_JP");
unset($set);

$set= new PegawaiHcdp();
$set->selectByParamsPenilaian(array('A.PEGAWAI_ID'=>$reqId, 'D.FORMULA_ID'=>$reqFormulaId), -1, -1);
$set->firstRow();

// echo $set->query;exit;
$infoikk= $set->getField("IKK");
$infojpm= $set->getField("JPM");
$infotahun= $set->getField("TAHUN");
$infometode= $set->getField("METODE");

unset($set);

$set= new PegawaiHcdp();
$set->selectByParamsRekapitulasi(array('A.PEGAWAI_ID'=>$reqId, 'A.FORMULA_ID'=>$reqFormulaId));
$set->firstRow();
$infosaran= $set->getField("ATRIBUT");
$inforingkasan= $set->getField("NAMA_PELATIHAN");
//  echo $set->query;exit;
// echo $infosaran;
// echo $inforingkasan;
// exit;

$set= new PegawaiHcdp();
$set->selectByParamsRekapitulasi(array('A.PEGAWAI_HCDP_ID'=>$reqRowId));
// echo $set->query;exit;
$set->firstRow();
$infopegawainama= $set->getField("PEGAWAI_NAMA");
$infopegawainip= $set->getField("PEGAWAI_NIP_BARU");
$infopegawaipangkat= $set->getField("PEGAWAI_PANGKAT_KODE")." / ".$set->getField("PEGAWAI_PANGKAT_NAMA");
$infopegawaijabatan= $set->getField("PEGAWAI_JABATAN_NAMA");
$infokodekuadran= $set->getField("KODE_KUADRAN");
$infokesenjangan= $set->getField("KESENJANGAN_KOMPETENSI");

unset($set);


// print_r($arrAtribut);exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- <title>Untitled Document</title>
 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
</head>

<body>
<div id="page_effect">
<div id="bg"></div>
<div id="content" style="height:auto; width:100%;">
    <div id="header-tna-detil">Pengelolaan Pengembangan <span>Kompetensi</span></div>
    <br>
    <form id="ff" method="post" novalidate>
    <table class="table_list" cellspacing="1" width="95%" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 25%">Nama</td>
            <td style="width: 20px">:</td>
            <td><?=$infopegawainama?></td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td><?=$infopegawainip?></td>
        </tr>
        <tr>
            <td>Pangkat / Gol</td>
            <td>:</td>
            <td><?=$infopegawaipangkat?></td>
        </tr>
        <tr> 
            <td>Jabatan</td>
            <td>:</td>
            <td><?=$infopegawaijabatan?></td>
        </tr>
        <tr>
            <td>IKK</td>
            <td>:</td>
            <td><?=$infoikk?></td>
        </tr>
        <tr>
            <td>Kesenjangan</td>
            <td>:</td>
            <td><?=$infokesenjangan?></td>
        </tr>
        <tr>
            <td>Kuadran</td>
            <td>:</td>
            <td><?=$infokodekuadran?></td>
        </tr>
       <!--  <tr>
            <td>Kategori</td>
            <td>:</td>
            <td><?=$infometode?></td>
        </tr> -->
        <tr>
            <td>Jenis Kompetensi</td>
            <td>:</td>
            <td style="text-align: justify; "><?=$infosaran?></td>
        </tr>
        <tr >
            <td >Rencana Pengembangan</td>
            <td>:</td>
            <td style="text-align: justify; "><?=$inforingkasan?></td>
        </tr>
    </table>
    </form>
    </div>

</div>
</body>
</html>
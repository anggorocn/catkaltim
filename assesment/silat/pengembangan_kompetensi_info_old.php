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
include_once("../WEB/classes/base-ikk/PenilaianRekomendasi.php");
include_once("../WEB/classes/base/CetakanPdf.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

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
$infosaran= $set->getField("SARAN_PENGEMBANGAN");
$inforingkasan= $set->getField("RINGKASAN_PROFIL_KOMPETENSI");
unset($set);

$set= new Kelautan();
$set->selectByParamsMonitoringTableTalentPoolJPMMonitoring(array(), -1, -1, "AND X.FORMULA_ID = ".$reqFormulaId." AND A.PEGAWAI_ID = ".$reqId, "", $infotahun);
// echo $set->query;exit;
$set->firstRow();
$infokuadran= $set->getField("ID_KUADRAN");
unset($set);
// echo $infokuadran;exit;

$set= new PegawaiHcdp();
$set->setField("PEGAWAI_HCDP_ID", $reqRowId);
$set->setField("FORMULA_ID", $reqFormulaId);
$set->setField("PEGAWAI_ID", $reqId);
$set->setField("JPM", ValToNullDB($infojpm));
$set->setField("IKK", ValToNullDB($infoikk));
$set->setField("METODE", $infometode);
$set->setField("TAHUN", $infotahun);
$set->setField("KUADRAN", $infokuadran);
$set->setField("SARAN_PENGEMBANGAN", $infosaran);
$set->setField("RINGKASAN_PROFIL_KOMPETENSI", $inforingkasan);
if(empty($reqRowId))
{
	$set->insert();
	$reqRowId= $set->id;
}
else
$set->update();
unset($set);

$set= new PegawaiHcdp();
$set->selectByParams(array('A.PEGAWAI_HCDP_ID'=>$reqRowId));
// echo $set->query;exit;
$set->firstRow();
$infopegawainama= $set->getField("PEGAWAI_NAMA");
$infopegawainip= $set->getField("PEGAWAI_NIP_BARU");
$infopegawaipangkat= $set->getField("PEGAWAI_PANGKAT_KODE")." / ".$set->getField("PEGAWAI_PANGKAT_NAMA");
$infopegawaijabatan= $set->getField("PEGAWAI_JABATAN_NAMA");
$infokodekuadran= $set->getField("KODE_KUADRAN");
unset($set);

$index_loop= 0;
$arrAtribut=[];
$statement= "
AND EXISTS
(
	SELECT 1
	FROM
	(
		SELECT A.PENILAIAN_ID
		FROM penilaian A
		INNER JOIN JADWAL_TES B ON A.JADWAL_TES_ID = B.JADWAL_TES_ID
		INNER JOIN FORMULA_ESELON C ON B.FORMULA_ESELON_ID = C.FORMULA_ESELON_ID
		INNER JOIN FORMULA_ASSESMENT D ON C.FORMULA_ID = D.FORMULA_ID 
		WHERE 1=1 AND ASPEK_ID IN (1,2)
		AND D.FORMULA_ID = ".$reqFormulaId." AND PEGAWAI_ID = ".$reqId."
	) XXX WHERE A.PENILAIAN_ID = XXX.PENILAIAN_ID
)
";
$set= new PegawaiHcdp();
$set->selectByParamsAtribut(array(), -1,-1, $reqRowId, $reqId, $statement);
// echo $set->query;exit();
while($set->nextRow())
{
	$arrAtribut[$index_loop]["PERMEN_ID"]= $set->getField("PERMEN_ID");
	$arrAtribut[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
	$arrAtribut[$index_loop]["ATRIBUT_NAMA"]= $set->getField("ATRIBUT_NAMA");
	$arrAtribut[$index_loop]["PELATIHAN_ID"]= $set->getField("PELATIHAN_ID");
	$arrAtribut[$index_loop]["PELATIHAN_NAMA"]= $set->getField("PELATIHAN_NAMA");
    $reqJadwalTesId= $set->getField("JADWAL_TES_ID");
	$index_loop++;
}
$jumlahatribut= $index_loop;
// print_r($arrAtribut);exit;

if($reqJadwalTesId==''){
    $reqJadwalTesId=0;
}
$index_catatan= 0;
$arrNilaiAkhirSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'area_pengembangan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrNilaiAkhirSaranPengembangan[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahNilaiAkhirSaranPengembangan= $index_catatan;

$index_catatan= 0;
$arrPotensiStrength=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kekuatan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPotensiStrength[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPotensiStrength= $index_catatan;

$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new Penilaian();
$set->selectByParamsTahunPenilaian($statement);
$set->firstRow();
$reqTahun= $set->getField("TAHUN");

$set= new CetakanPdf();
$statement1= " AND A.PEGAWAI_ID= ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$statement2= " AND B.PEGAWAI_ID= ".$reqId;
$set->selectByParamsMonitoringTableTalentPoolMonitoring(array(), -1, -1, $statement1, $statement2, "", $reqTahun, "");
// echo $set->query;exit;
$set->firstRow();
$namaKuadran= $set->getField("NAMA_KUADRAN");
$kodeKuadran= $set->getField("KODE_KUADRAN");
$rekomKuadran= $set->getField("NAMA_KUADRAN");

$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId; 
$statementgroup= "";
$index_loop= 0; 
$jpm=0;
$arrPenilaianAtributJPM="";
$set= new CetakanPdf();
$set->selectByParamsPenilaianJpmAkhir(array(), -1,-1, $statement, $statementgroup);
$set->firstRow();
// echo $set->query;exit;
while($set->nextRow())
{
    $arrPenilaianAtributJPM[$index_loop]["JPM"]= $set->getField("JPM");
    $arrPenilaianAtributJPM[$index_loop]["IKK"]= $set->getField("IKK"); 
    $index_loop++;
}
$jumlah_penilaian_atribut= $index_loop;

$jpm = $set->getField("KOMPETEN_JPM");

$set= new CetakanPdf();
$statement= " AND A.PEGAWAI_ID= ".$reqId." AND TO_CHAR(A.TANGGAL_TES, 'YYYY') = '".$reqTahun."'";
$set->selectByParamsPenilaian(array(), -1, -1, $statement);
$set->firstRow();
$tempTipeTes= $set->getField("TIPE_FORMULA");

if($tempTipeTes == '1')
{
    if ($jpm >= 80)
        $HasilKonversi = 'MS = Memenuhi Syarat.';
    elseif ($jpm >= 68 && $jpm < 80)
        $HasilKonversi = 'MMS = Masih Memenuhi Syarat.';
    elseif ($jpm < 68)
        $HasilKonversi = 'KMS = Kurang Memenuhi Syarat.';
    else
        $HasilKonversi = '-'; 
}
elseif($tempTipeTes == '2')
{
    if ($jpm >= 90)
        $HasilKonversi = 'O = Optimal.';
    elseif ($jpm >= 78 && $jpm < 90)
        $HasilKonversi = 'CO = Cukup Optimal.';
    elseif ($jpm < 78)
        $HasilKonversi = 'KO = Kurang Optimal.';
    else
        $HasilKonversi = '-'; 
}
else
    $HasilKonversi = $jpm; 

$statement= "  AND A.JADWAL_TES_ID = '".$reqJadwalTesId."' AND A.PEGAWAI_ID = ".$reqId." and a.aspek_id=2";  
$statementgroup= "";
$set= new CetakanPdf();
$set->selectByParamsSumPenilaian(array(), -1,-1, $statement, $statementgroup);
$set->firstRow();
//echo $set->query;exit; 
$nilaiIndividu =  $set->getField("INDIVIDU_RATING");
$nilaiStandar =  $set->getField("STANDAR_RATING");

$arrPenilaianPotensiProfilKompetensi=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_kompetensi' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiProfilKompetensi[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiProfilKompetensi= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPengembangan=array();
$set_catatan= new PenilaianRekomendasi();
$statement_catatan= " AND A.TIPE = 'profil_saran_pengembangan' AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParams(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $arrPenilaianPotensiSaranPengembangan[$index_catatan]["NO_URUT"]= $set_catatan->getField("NO_URUT");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPengembangan= $index_catatan;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
</head>

<body>
<div id="page_effect">
<div id="bg"></div>
<div id="content" style="height:auto; width:100%;">
	<div id="header-tna-detil">Pengelolaan Pengembangan <span>Kompetensi</span></div>
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
            <td>Kuadran</td>
            <td>:</td>
            <td><?=$kodeKuadran?></td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td>:</td>
            <td><?=$infometode?></td>
        </tr>
        <tr>
            <td>Rekomendasi</td>
            <td>:</td>
            <td style="text-align: justify; ">Berdasarkan profil dan uraian di atas, maka Saudara <?=$infopegawainama?>  berada pada kategori : <?=$HasilKonversi?> </td>
        </tr>
        <tr >
            <td >Ringkasan</td>
            <td>:</td>
            <td style="text-align: justify; "><p style="font-size: 10pt;text-align: justify;">Berdasarkan  hasil    penilaian kompetensi, menunjukan bahwa nilai total kompetensi Saudara <?=$infopegawainama?> adalah <?=$nilaiIndividu?> dari total <?=$nilaiStandar?> atau setara dengan <?=$jpm?>% Job Person Match (JPM).</p>  
        <? 
        for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKompetensi; $index_catatan++)
        {
            $reqinfocatatan= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"];
            $reqinfourut= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["NO_URUT"];

            if($jumlahPenilaianPotensiProfilKompetensi == 1)
            {
                $reqinfourut= "";
            }
            else
            {
                $reqinfourut= $reqinfourut.".&nbsp; ";
            }

        ?>
         <p  style="font-size: 10pt;text-align: justify;"><?=$reqinfocatatan?></p>
        <?
        }
        ?></td>
        </tr>

         <tr >
            <td >Kekuatan</td>
            <td>:</td>
            <td style="text-align: justify; ">
                <? 
                for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
                {
                    $reqinfocatatan= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
                    $reqinfourut= $arrPotensiStrength[$index_catatan]["NO_URUT"];

                    if($jumlahPotensiStrength == 1)
                    {
                        $reqinfourut= "";
                    }
                    else
                    {
                        $reqinfourut= $reqinfourut.".&nbsp; ";
                    }
                ?>
                <p  style="font-size: 10pt;text-align: justify;"><?=str_replace("text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;","display:none",$reqinfocatatan);?></p>
                <?
                }
                ?>
            </td>
        </tr>

        <tr >
            <td >Area Pengembangan</td>
            <td>:</td>
            <td style="text-align: justify; ">
                <? 
                for($index_catatan=0; $index_catatan<$jumlahNilaiAkhirSaranPengembangan; $index_catatan++)
                {
                    $reqinfocatatan= $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"];
                    $reqinfourut= $arrNilaiAkhirSaranPengembangan[$index_catatan]["NO_URUT"];

                    if($jumlahNilaiAkhirSaranPengembangan == 1)
                    {
                        $reqinfourut= "";
                    }
                    else
                    {
                        $reqinfourut= $reqinfourut.".&nbsp; ";
                    }
                ?>
                <p  style="font-size: 10pt;text-align: justify;"><?=str_replace("text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;","display:none",$reqinfocatatan);?></p>
                <?
                }
                ?>
            </td>
        </tr>

        <tr >
            <td >Saran Pengembangan</td>
            <td>:</td>
            <td style="text-align: justify; ">
               <? 
        for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPengembangan; $index_catatan++)
        {
            $reqinfocatatan= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"];
            $reqinfourut= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["NO_URUT"];

            if($jumlahPenilaianPotensiSaranPengembangan == 1)
            {
                $reqinfourut= "";
            }
            else
            {
                $reqinfourut= $reqinfourut.".&nbsp; ";
            }
            if($reqinfocatatan!=''){
                ?>
                <p  style="font-size: 10pt;text-align: justify;"><?=$reqinfocatatan?></p>
                <?
            }
        }
        ?>
            </td>
        </tr>
    </table>
	</form>
    </div>
</div>
</body>
</html>
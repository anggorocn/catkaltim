<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/satuankerjainternal.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");
include_once("../WEB/classes/base-portal/formulir.php");


$userLogin->checkLoginPelamar();
$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;

$reqPegawaiId=$tempUserPelamarId;

$reqTipeFormulir= 2;


$peserta= new Peserta();
$peserta->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
$peserta->firstRow();
// echo $peserta->query;exit;
$reqStatusJenis= $peserta->getField('STATUS_JENIS');
$tempStatusPegawai= $peserta->getField('STATUS_PEGAWAI_ID');
$tempEmail= $peserta->getField('EMAIL');
$tempKtp= $peserta->getField('KTP');
$tempUnitKerja= $peserta->getField('UNIT_KERJA_NAMA');
$tempUnitKerjaKota= $peserta->getField('UNIT_KERJA_KOTA');
$tempNama= $peserta->getField('NAMA');
$tempNIP= $peserta->getField('NIP');
$tempGolonganRuang= $peserta->getField('GOL_RUANG');
$tempJabatan= $peserta->getField('JABATAN');
$reqMEselonId= $peserta->getField("M_ESELON_ID");
$reqStatusSatuanKerja= $peserta->getField("STATUS_SATUAN_KERJA");
$reqUnitKerjaEselon= $peserta->getField("UNIT_KERJA_ESELON");
$infoeselonid= $peserta->getField("LAST_ESELON_ID");

// echo $infoeselonid; exit;
$sOrder= "ORDER BY A.FORMULIR_SOAL_ID";
$index_loop=0;
$arrJumlahSoalPegawai=array();
// $statement= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2 ";
$statement= " AND A.TIPE_FORMULIR_ID = ".$reqTipeFormulir;

$set= new Formulir();
$set->selectByParamsSoal(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoalPegawai[$index_loop]["FORMULIR_SOAL_ID"]= $set->getField("FORMULIR_SOAL_ID");
    $arrJumlahSoalPegawai[$index_loop]["TIPE_FORMULIR_ID"]= $set->getField("TIPE_FORMULIR_ID");
    $arrJumlahSoalPegawai[$index_loop]["SOAL"]= $set->getField("SOAL");

    $statementDetil= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = ".$reqTipeFormulir." and a.FORMULIR_SOAL_ID=".$set->getField("FORMULIR_SOAL_ID");

    $setDetil= new Formulir();
    $setDetil->selectByParamsJawaban(array(), -1,-1, $statementDetil, $sOrder);
         // echo $setDetil->query;exit;
    while($setDetil->nextRow())
    {
        $arrJumlahSoalPegawai[$index_loop]["JAWABAN"]= $setDetil->getField("JAWABAN");
    }

    $index_loop++;
}
$tempJumlahSoalPegawai= $index_loop;
// print_r($arrJumlahSoalPegawai);exit;
unset($set);

// $sOrder= "ORDER BY A.FORMULIR_SOAL_ID";
// $index_loop=0;
// $arrJumlahJawabanPegawai=array();

$tempJumlahJawabanPegawai= $index_loop;
$reqMode ="";
if($tempJumlahJawabanPegawai == "")
{
    $reqMode = "insert";
}
else
{
    $reqMode = "update";
}
// print_r($tempJumlahSoalPegawai);exit;
unset($set);

?>
<link rel="stylesheet" type="text/css" href="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.css">

<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>

<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/daterangepicker.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>


<script type="text/javascript">
$(function(){
    $('#ff').form({
        url:'../json/formulir_q_inta.php',
        onSubmit:function(){
            return $(this).form('validate');
        },
        success:function(data){
            // console.log(data);return false;
            data = data.split("-");
            rowid= data[0];
            infodata= data[1];

            if(rowid != "autologin"){
                $.messager.alert('Info', infodata, 'info');
            }

            if(rowid == "xxx"){}
            else if(rowid == "autologin"){
                autologin(); return false ;
            }
            else
            {
                 document.location.reload();
            }
        }
    });
});
</script>

<div class="col-lg-8">
    <div id="judul-halaman"><?=$arrayJudul["index"]["formulir_q_inta"]?></div>
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  Pertanyaan</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data"> 
        <table style="width: 100%">
            <?
            $no = 1;
            // for($index_detil=0; $index_detil < $tempJumlahSoalPegawai; $index_detil++)
            for($index_detil=0; $index_detil < 4; $index_detil++)
            {
                $index_row= $arrJumlahSoalPegawai[$index_detil]["FORMULIR_SOAL_ID"];
                $reqSoalId= $arrJumlahSoalPegawai[$index_detil]["FORMULIR_SOAL_ID"];
                $reqSoal= $arrJumlahSoalPegawai[$index_detil]["SOAL"];
                $reqTipe= $arrJumlahSoalPegawai[$index_detil]["TIPE_FORMULIR_ID"];
                $reqJawaban= $arrJumlahSoalPegawai[$index_detil]["JAWABAN"];
            ?>
            <tr>
                <td style="vertical-align: middle !important;"><?=$no?>. <?=$reqSoal?>
                </td>
            </tr>
            <tr>
                 <td>
                      <input type="hidden" name="reqSoalId[]" id="reqSoalId<?=$reqSoalId?>" value="<?=$reqSoalId?>" />
                      <input type="hidden" name="reqTipe[]" id="reqTipe<?=$reqTipe?>" value="<?=$reqTipe?>" />
                    <textarea style="width:100%" name="reqJawaban[]" id="reqJawaban<?=$index_row?>"><?=$reqJawaban?></textarea>
                </td>
            </tr>
            <?
            $no++;
            }
            ?>
        </table>
        <div>
            <input type="hidden" name="reqSubmit" value="update">
            <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
            <input type="hidden" name="reqTipeFormulir" value="<?=$reqTipeFormulir?>">
            <input type="hidden" name="reqMode" value="<?=$reqMode?>">
            <input type="submit" value="Simpan">
        </div>

        </form>
    </div>
    
</div>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style

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

$reqTipeFormulir= 3;


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

    $index_loop++;
}
$tempJumlahSoalPegawai= $index_loop;
// print_r($tempJumlahSoalPegawai);exit;
unset($set);

$sOrder= "ORDER BY A.FORMULIR_SOAL_ID";
$index_loop=0;
$arrJumlahJawabanPegawai=array();
$statement= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = ".$reqTipeFormulir;

$set= new Formulir();
$set->selectByParamsJawaban(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawabanPegawai[$index_loop]["FORMULIR_SOAL_ID"]= $set->getField("FORMULIR_SOAL_ID");
    $arrJumlahJawabanPegawai[$index_loop]["TIPE_FORMULIR_ID"]= $set->getField("TIPE_FORMULIR_ID");
    $arrJumlahJawabanPegawai[$index_loop]["SOAL"]= $set->getField("SOAL");
    $arrJumlahJawabanPegawai[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrJumlahJawabanPegawai[$index_loop]["JAWABAN"]= $set->getField("JAWABAN");
    $index_loop++;
}
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
                // $.messager.alert('Info', infodata, 'info');
            }

            if(rowid == "xxx"){}
            else if(rowid == "autologin"){
                autologin(); return false ;
            }
            else
            {
                   document.getElementById("isiAlert").innerHTML =infodata;
                 // document.location.reload();
                modal.style.display = "block";
            }
        }
    });
});
</script>

<div class="col-lg-12">
    <div id="judul-halaman"><?=$arrayJudul["index"]["formulir_q_kompetensi_eselon"]?></div>
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  Pertanyaan</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data"> 
        <table style="width: 100%">
            <?
            $no = 1;
            for($index_detil=0; $index_detil < $tempJumlahSoalPegawai; $index_detil++)
            {
                $index_row= $arrJumlahSoalPegawai[$index_detil]["FORMULIR_SOAL_ID"];
                $reqSoalId= $arrJumlahSoalPegawai[$index_detil]["FORMULIR_SOAL_ID"];
                $reqSoal= $arrJumlahSoalPegawai[$index_detil]["SOAL"];
                $reqTipe= $arrJumlahSoalPegawai[$index_detil]["TIPE_FORMULIR_ID"];
                $reqJawaban= $arrJumlahJawabanPegawai[$index_detil]["JAWABAN"];
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


<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id='isiAlert'>Some text in the Modal..</p>
  </div>

</div>


<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
// var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
// btn.onclick = function() {
//   modal.style.display = "block";
// }

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  // modal.style.display = "none";
document.location.reload();

}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    // modal.style.display = "none";
    document.location.reload();
  }
}
</script>
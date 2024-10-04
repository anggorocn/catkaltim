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
</style>
<?
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/satuankerjainternal.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");
include_once("../WEB/classes/base-portal/formulircritical.php");



$userLogin->checkLoginPelamar();
$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;
$reqPegawaiId=$tempUserPelamarId;


$peserta= new Peserta();
$peserta->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
     // echo $peserta->query;exit;


$peserta->firstRow();
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
 //print_r($reqStatusSatuanKerja);exit();
$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_HEADER_ID";
$index_loop=0;
$arrJumlahSoalHeaderPegawai=array();
// $statement= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2 ";
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID < 3";

$set= new FormulirCritical();
$set->selectByParamsSoalCriticalHeader(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoalHeaderPegawai[$index_loop]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahSoalHeaderPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");
    $arrJumlahSoalHeaderPegawai[$index_loop]["TOPIK"]= $set->getField("TOPIK");
    $arrJumlahSoalHeaderPegawai[$index_loop]["TANGGAL"]= $set->getField("TANGGAL");
    // $arrJumlahSoalPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");

    $index_loop++;
}
$tempJumlahSoalHeaderPegawai= $index_loop;
unset($set);


$sOrder= "ORDER BY A.FORMULIR_JAWABAN_CRITICAL_HEADER_ID";
$index_loop=0;
$arrJumlahJawabanHeaderPegawai=array();
// $statement= " AND C.PEGAWAI_ID = ".$tempUserPelamarId." AND C.TIPE_FORMULIR_ID = 2 ";
$statement= "";

$set= new FormulirCritical();
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID < 3 AND A.PEGAWAI_ID = ".$tempUserPelamarId." ";
// $set->selectByParamsJawabanCriticalHeader(array(), -1,-1, $statement, $sOrder);
$set->selectByParamsJawabanCriticalHeader(array("PEGAWAI_ID"=>$tempUserPelamarId), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawabanHeaderPegawai[$index_loop]["FORMULIR_JAWABAN_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_JAWABAN_CRITICAL_HEADER_ID");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["TOPIK"]= $set->getField("TOPIK");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["TANGGAL"]= $set->getField("TANGGAL");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["BULAN"]= $set->getField("BULAN");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["TAHUN"]= $set->getField("TAHUN");
    $arrJumlahJawabanHeaderPegawai[$index_loop]["SAMPAI"]= $set->getField("SAMPAI");


    // $arrJumlahSoalPegawai[$index_loop]["NAMA"]= $set->getField("NAMA");

    $index_loop++;
}
$tempJumlahJawabanHeaderPegawai= $index_loop;
unset($set);


$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahSoal1Pegawai=array();
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID = 1 ";
// $statement2= " B.PEGAWAI_ID = ".$tempUserPelamarId." ";
$set= new FormulirCritical();
$set->selectByParamsJawabanCriticalSoal(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoal1Pegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahSoal1Pegawai[$index_check]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID");
    $arrJumlahSoal1Pegawai[$index_check]["NAMA"]= $set->getField("NAMA");

    $index_check++;
}
$tempJumlahSoal1Pegawai= $index_check;
// print_r($arrJumlahSoal1Pegawai);exit;
unset($set);

$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahJawaban1Pegawai=array();
$statement= " AND B.PEGAWAI_ID = ".$reqPegawaiId." AND B.FORMULIR_SOAL_CRITICAL_HEADER_ID = 1 ";
$set= new FormulirCritical();
$set->selectByParamsJawabanCritical(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawaban1Pegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahJawaban1Pegawai[$index_check]["JAWABAN"]= $set->getField("JAWABAN");

    $index_check++;
}
$tempJumlahJawaban1Pegawai= $index_check;
// print_r($tempJumlahJawabanPegawai);exit;
unset($set);


$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahSoalNewPegawai=array();
$statement= " AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID = 2 ";
$set= new FormulirCritical();
$set->selectByParamsJawabanCriticalSoal(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahSoalNewPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
    $arrJumlahSoalNewPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID");
    $arrJumlahSoalNewPegawai[$index_check]["NAMA"]= $set->getField("NAMA");

    $index_check++;
}
$tempJumlahSoalNewPegawai= $index_check;





$sOrder= "ORDER BY A.FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID";
$index_check=0;
$arrJumlahJawabanPegawai=array();
$statement= " AND B.PEGAWAI_ID = ".$reqPegawaiId." AND A.FORMULIR_SOAL_CRITICAL_HEADER_ID = 2 ";
$set= new FormulirCritical();
$set->selectByParamsJawabanCritical(array(), -1,-1, $statement, $sOrder);
     // echo $set->query;exit;
while($set->nextRow())
{
    $arrJumlahJawabanPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_HEADER_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_HEADER_ID");
     $arrJumlahJawabanPegawai[$index_check]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"]= $set->getField("FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID");
    $arrJumlahJawabanPegawai[$index_check]["JAWABAN"]= $set->getField("JAWABAN");

    $index_check++;
}
$tempJumlahJawabanPegawai= $index_check;
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
		url:'../json/formulir_critical_incident.php',
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
    <div id="judul-halaman"><?=$arrayJudul["index"]["formulir_critical_incident"]?></div>

    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data"> 

        <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  Pertanyaan</div>
         <table style="width: 100%">
             <?
            $no = 1;
            for($index_detil=0; $index_detil < $tempJumlahSoalHeaderPegawai; $index_detil++)
            {
              $reqSoal= $arrJumlahSoalHeaderPegawai[$index_detil]["NAMA"];
              $reqSoalId= $arrJumlahSoalHeaderPegawai[$index_detil]["FORMULIR_SOAL_CRITICAL_HEADER_ID"];
              $reqTopik= $arrJumlahJawabanHeaderPegawai[$index_detil]["TOPIK"];
              $reqTanggal= $arrJumlahJawabanHeaderPegawai[$index_detil]["TANGGAL"];
              $reqBulan= $arrJumlahJawabanHeaderPegawai[$index_detil]["BULAN"];
              $reqTahun= $arrJumlahJawabanHeaderPegawai[$index_detil]["TAHUN"];
              $reqSampai= $arrJumlahJawabanHeaderPegawai[$index_detil]["SAMPAI"];

            ?>
             <tr>
                <td style="vertical-align: middle !important;"><?=$reqSoal?>
                </td>
            </tr>

            <tr>
                <td style="vertical-align: middle !important;"><label>Topik kejadian</label> : <input type="text" style="width:80%" name="reqTopik[]" id="reqTopik"value="<?=$reqTopik?>"   />
                    <input type="hidden" name="reqSoalId[]" id="reqSoalId<?=$reqSoalId?>" value="<?=$reqSoalId?>" />
                </td>
            </tr>

            <tr>
                <td style="vertical-align: middle !important;"><label>Waktu kejadian</label> (seingatnya) :
                  Tgl <input type="text" style="width:8%" name="reqTanggal[]" id="reqTanggal" value="<?=$reqTanggal?>"   />
                  <input type="hidden" name="reqSoalId[]" id="reqSoalId<?=$reqSoalId?>" value="<?=$reqSoalId?>" />

                  bln <input type="text" style="width:8%" name="reqBulan[]" id="reqBulan" value="<?=$reqBulan?>"   />
                  <input type="hidden" name="reqSoalId[]" id="reqSoalId<?=$reqSoalId?>" value="<?=$reqSoalId?>" />

                  th <input type="text" style="width:8%" name="reqTahun[]" id="reqTahun"  value="<?=$reqTahun?>"   />
                  <input type="hidden" name="reqSoalId[]" id="reqSoalId<?=$reqSoalId?>" value="<?=$reqSoalId?>" />
                  sampai <input type="text" style="width:8%" name="reqSampai[]" id="reqSampai"  value="<?=$reqSampai?>"   />
                  <input type="hidden" name="reqSoalId[]" id="reqSoalId<?=$reqSoalId?>" value="<?=$reqSoalId?>" />
                  <span style="color: red;"> (isi dengan angka saja)</span>
                </td>
            </tr>
           

                <?
                if ( $reqSoalId == 1)
                {
                ?>

                        <? 
                       for($index_tes=0; $index_tes < $tempJumlahSoal1Pegawai; $index_tes++)
                        {
                             $reqJawabanTambahan= $arrJumlahJawaban1Pegawai[$index_tes]["JAWABAN"];
                             $reqSoalNew= $arrJumlahSoal1Pegawai[$index_tes]["NAMA"];
                             $reqSoalJawabanId= $arrJumlahSoal1Pegawai[$index_tes]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"];
                             $reqSoalHeaderId= $reqSoalId;
                            // var_dump($reqSoalJawabanId);
                            ?>
                        <tr>
                            <td style="vertical-align: middle !important;"><?=$reqSoalNew?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="hidden" name="reqSoalJawabanId[]" id="reqSoalJawabanId<?=$reqSoalJawabanId?>" value="<?=$reqSoalJawabanId?>" />
                                <input type="hidden" name="reqSoalHeaderId[]" id="reqSoalHeaderId<?=$reqSoalHeaderId?>" value="<?=$reqSoalHeaderId?>" />
                                <textarea style="width:100%" name="reqJawabanTambahan[]" id="reqJawabanTambahan<?=$index_row?>"><?=$reqJawabanTambahan?></textarea>
                            </td>
                        </tr>
                        <?
                        }
                       
                        ?>

                 <?
                 }
                 elseif($reqSoalId == 2)
                 {
                ?>
                        <? 
                       for($index_tes=0; $index_tes < $tempJumlahSoalNewPegawai; $index_tes++)
                        {
                            $reqJawabanTambahan= $arrJumlahJawabanPegawai[$index_tes]["JAWABAN"];
                             $reqSoalNew= $arrJumlahSoalNewPegawai[$index_tes]["NAMA"];
                             $reqSoalJawabanId= $arrJumlahSoalNewPegawai[$index_tes]["FORMULIR_SOAL_CRITICAL_TAMBAHAN_ID"];
                              $reqSoalHeaderId= $reqSoalId;
                            ?>
                        <tr>
                            <td style="vertical-align: middle !important;"><?=$reqSoalNew?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="hidden" name="reqSoalHeaderId[]" id="reqSoalHeaderId<?=$reqSoalHeaderId?>" value="<?=$reqSoalHeaderId?>" />
                                <input type="hidden" name="reqSoalJawabanId[]" id="reqSoalJawabanId<?=$reqSoalJawabanId?>" value="<?=$reqSoalJawabanId?>" />
                                <textarea style="width:100%" name="reqJawabanTambahan[]" id="reqJawabanTambahan<?=$index_row?>"><?=$reqJawabanTambahan?></textarea>
                            </td>
                        </tr>
                        <?
                        }
                       
                        ?>
                <?
                }
                ?>
            <?
            $no++;
            }
            ?>
            <br>
           
           
            
         
           
        </table>    
   
        <div>
            <input type="hidden" name="reqSubmit" value="update">
            <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
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
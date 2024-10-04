<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/satuankerjainternal.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");
include_once("../WEB/classes/base-diklat/MPangkat.php");
include_once("../WEB/classes/base-diklat/MPendidikan.php");
include_once("../WEB/classes/base-diklat/MEselon.php");
include_once("../WEB/classes/base-diklat/Propinsi.php");

$userLogin->checkLoginPelamar();
$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;

$reqId = $tempUserPelamarId;

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

$tempJenisKelamin= $peserta->getField('JENIS_KELAMIN');
$tempStatusKawin= $peserta->getField('STATUS_KAWIN');
// echo $tempStatusKawin;exit;
$tempStatusKawinInfo= $peserta->getField('STATUS_KAWIN_INFO');


$tempTempatLahir= $peserta->getField('TEMPAT_LAHIR');
// echo $tempTempatLahir;exit();
$tempTanggalLahir= datetimeToPage($peserta->getField('TANGGAL_LAHIR'), "date");
$tempAgama= $peserta->getField('AGAMA');
$tempGolonganRuang= $peserta->getField('GOL_RUANG');
$tempJabatan= $peserta->getField('JABATAN');
$tempAlamatRumah= $peserta->getField('ALAMAT_RUMAH');
$tempAlamat= $peserta->getField('ALAMAT');
$tempSosmed= $peserta->getField('SOSIAL_MEDIA');
$tempAuto= $peserta->getField('AUTO_ANAMNESA');
$tempTempatKerja= $peserta->getField('TEMPAT_KERJA');
$tempAlamatTempatKerja= $peserta->getField('ALAMAT_TEMPAT_KERJA');

$reqAlamatRumahKabKota= $peserta->getField('ALAMAT_RUMAH_KAB_KOTA');
$reqKota= $peserta->getField('KOTA');
 //echo $tempAgama;exit();

$tempHp= $peserta->getField('ALAMAT_RUMAH_TELP');
$tempAlamatRumahFax= $peserta->getField('ALAMAT_RUMAH_FAX');
$tempAlamatKantor= $peserta->getField('ALAMAT_KANTOR');
$tempAlamatKantorTelp= $peserta->getField('ALAMAT_KANTOR_TELP');
$tempAlamatKantorFax= $peserta->getField('ALAMAT_KANTOR_FAX');
$tempNPWP= $peserta->getField('NPWP');
$tempPendidikanTerakhir= $peserta->getField('PENDIDIKAN_TERAKHIR');
$tempPelatihan= $peserta->getField('PELATIHAN');
$tempKontakDaruratNama= $peserta->getField('KONTAK_DARURAT_NAMA');
$tempKontakDaruratHp= $peserta->getField('KONTAK_DARURAT_HP');
$tempFotoLink= $peserta->getField("FOTO_LINK");

$reqMEselonId= $peserta->getField("M_ESELON_ID");

$reqStatusSatuanKerja= $peserta->getField("STATUS_SATUAN_KERJA");
$reqUnitKerjaEselon= $peserta->getField("UNIT_KERJA_ESELON");
$reqPegawaiPangkatId= $peserta->getField("LAST_PANGKAT_ID");

$reqPegawaiAtasanLangsungNama= $peserta->getField("LAST_ATASAN_LANGSUNG_NAMA");
$reqPegawaiAtasanLangsungJabatan= $peserta->getField("LAST_ATASAN_LANGSUNG_JABATAN");


$pangkat= new MPangkat();
$pangkat->selectByParams(array(),-1,-1);

$pendidikan= new MPendidikan();
$pendidikan->selectByParams(array(),-1,-1);

$eselon= new MEselon();
$eselon->selectByParams(array(),-1,-1);

$statement= " AND PROPINSI_PARENT_ID = '0'";
$propinsi= new Propinsi();
$propinsi->selectByParams(array(),-1,-1, $statement);

$arrSatuanKerjaInternal= selectsatuankerjainternal();

$pangkat_new= new Peserta();
$pangkat_new->selectByParamsPangkat();

$arrpendidikanriwayat=array();
$index_data= 0;
$statement= " AND A.PENDIDIKAN_ID IN (1,2,3,6,7,8,9,11)";
$set= new Peserta();
$set->selectByParamsPendidikanRiwayat(array(), -1,-1, $tempUserPelamarId, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
 $arrpendidikanriwayat[$index_data]["PENDIDIKAN_ID"] = $set->getField("PENDIDIKAN_ID");
 $arrpendidikanriwayat[$index_data]["NAMA"] = $set->getField("NAMA");
 $arrpendidikanriwayat[$index_data]["RIWAYAT_PENDIDIKAN_ID"] = $set->getField("RIWAYAT_PENDIDIKAN_ID");
 $arrpendidikanriwayat[$index_data]["NAMA_SEKOLAH"] = $set->getField("NAMA_SEKOLAH");
 $arrpendidikanriwayat[$index_data]["JURUSAN"] = $set->getField("JURUSAN");
 $arrpendidikanriwayat[$index_data]["TEMPAT"] = $set->getField("TEMPAT");
 $arrpendidikanriwayat[$index_data]["TAHUN_AWAL"] = $set->getField("TAHUN_AWAL");
 $arrpendidikanriwayat[$index_data]["TAHUN_AKHIR"] = $set->getField("TAHUN_AKHIR");
 $index_data++;
}
$jumlahpendidikanriwayat= $index_data;

// print_r($arrSatuanKerjaInternal);exit();
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
$.extend($.fn.validatebox.defaults.rules, {
    Number: {  
        validator: function (value) {  
            //var reg =/^[0-9]*$/;
            //var reg =/^(\+91\d{8,18}|0\d{9,18})((,\+91\d{8,18})|(,0\d{9,18}))*$/;
            //var reg =/^(\+91\d{8,18}|0\d{0,19})(,\+91\d{8,18}|,0\d{0,19})*$/;
            
            var reg =/[0](\d{9})|([0](\d{2})( |-|)((\d{3}))( |-|)(\d{4}))|[0](\d{2})( |-|)(\d{7})|(\+|00|09)(\d{2}|\d{3})( |-|)(\d{2})( |-|)((\d{3}))( |-|)(\d{4})/;
            return reg.test(value);  
        },  
        message: 'Please input number.'  
    },
    sameComboAutoLoder: {
        validator: function(value, param){  
            var indexId= param[0];
            var value= $("#"+indexId).combobox('getValue');
            if(value == "" || typeof value == "undefined")
            {
                return false;
            }
            else
                return true;
        },
        message: 'Data tidak ditemukan'
    },
    validKTP:{
        validator: function(value, param){
            var reqNoKtp= "";
            reqNoKtp= $("#reqKtp").val();
            
             if(reqNoKtp.length == 16)
                return true;
             else
                return false;
        },
        message: 'Masukkan 16 digit nomor KTP.'
    }
});

$(function(){
	$('#ff').form({
		url:'../json/data_pribadi.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			console.log(data);return false;
            data = data.split("-");
            rowid= data[0];
            infodata= data[1];

            $.messager.alert('Info', infodata, 'info');

            if(rowid == "xxx"){}
            else
            {
			     document.location.reload();
            }
		}
	});

    var dates = $( "#reqTanggalLahir" ).datepicker({
        defaultDate: "+0w",
        dateFormat: 'dd-mm-yy',
        numberOfMonths: 1,
        changeMonth: true,
        changeYear: true,
        beforeShow: function (input, inst) {
            var rect = input.getBoundingClientRect();
            setTimeout(function () {
                // inst.dpDiv.css({ top: rect.top + 105, left: rect.left + 0 });
                // inst.dpDiv.css({ top: rect.top + 35, left: rect.left + 0 });
                // inst.dpDiv.css({ top: rect.top + 25, left: rect.left + 0 });
            }, 0);
        }
    });
	
});
</script>

<!-- UPLOAD CORE -->
<script src="../WEB/lib/multifile-master/jquery.MultiFile.js"></script>
<script>
	// wait for document to load
	$(function(){
		
		// invoke plugin
		$('#reqLampiran').MultiFile({
		onFileChange: function(){
			console.log(this, arguments);
		}
		});
	
	});
</script>	


<div class="col-lg-8">
    <div id="judul-halaman"><?=$arrayJudul["index"]["data_pribadi"]?></div>
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  IDENTITAS DIRI</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table style="width: 100%">
            <tr>
                <td>Nama Lengkap</td>
                <td>
                    <input type="text" name="reqNama" id="reqNama" <?=$read?> value="<?=$tempNama?>" class="form-control easyui-validatebox" required placeholder="Ketik Nama Lengkap Anda..." />
                </td>
            </tr>
             <tr>
                <td>Tempat / Tanggal Lahir</td>
                <td>
                    <table style="width: 100%; border: medium none ! important; margin: 0px ! important;">
                    <tr>
                        <td style="width: 40%; padding: 0px ! important;">
                            <input type="text" name="reqTempatLahir" id="reqTempatLahir" <?=$read?> value="<?=$tempTempatLahir?>" class="form-control easyui-validatebox" required placeholder="Ketik Tempat Lahir Anda..." />
                        </td>
                        <td style="width: 20%; vertical-align: middle !important;">
                            <input type="text" name="reqTanggalLahir" id="reqTanggalLahir" <?=$read?> value="<?=$tempTanggalLahir?>" class="form-control easyui-validatebox" required data-options="validType:['dateValidPicker']" placeholder="Ketik Tgl Lahir Anda..." />
                        </td>
                    </table>
                </td>
            </tr>
            <tr>        
                <td>Jenis Kelamin</td>
                <td>
                    <select name="reqJenisKelamin" id="reqJenisKelamin" class="form-control" <?=$disabled?> style="width:25%">
                        <option value="" <? if($tempJenisKelamin == "") echo "selected"?>></option>
                        <option value="L" <? if($tempJenisKelamin == "L") echo "selected"?>>Laki-laki</option>
                        <option value="P" <? if($tempJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>
                   <!--  <input type="text" name="reqAlamat" id="reqAlamat" <?=$read?> value="<?=$tempAlamat?>" class="form-control easyui-validatebox" /> -->
                    <textarea style="width:100%" name="reqAlamat" id="reqAlamat"><?=$tempAlamat?></textarea>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Telepon / HP</td>
                <td>
                    <input type="text" style="width:40%" name="reqHp" id="reqHp" <?=$read?> value="<?=$tempHp?>" class="form-control easyui-validatebox"  />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Alamat Email</td>
                <td>
                    <input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$tempEmail?>" style="width:50%" class="form-control easyui-validatebox" data-options="validType:['email']" placeholder="Ketik Email Anda..." />
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>
                    <select name="reqAgama" id="reqAgama" class="form-control" <?=$disabled?> style="width:45%">
                        <option value="" <? if($tempAgama == "") echo "selected"?>></option>
                        <option value="ISLAM" <? if($tempAgama == "ISLAM") echo "selected"?>>ISLAM</option>
                        <option value="KRISTEN PROTESTAN" <? if($tempAgama == "KRISTEN PROTESTAN") echo "selected"?>>KRISTEN PROTESTAN</option>
                        <option value="KRISTEN KATHOLIK" <? if($tempAgama == "KRISTEN KATHOLIK") echo "selected"?>>KRISTEN KATHOLIK</option>
                        <option value="HINDU" <? if($tempAgama == "HINDU") echo "selected"?>>HINDU</option>
                        <option value="BUDHA" <? if($tempAgama == "BUDHA") echo "selected"?>>BUDHA</option>
                        <option value="KONGHUCHU" <? if($tempAgama == "KONGHUCHU") echo "selected"?>>KONGHUCHU</option>
                    </select>
                </td>
            </tr>  
            <tr>        
                <td>Status Pernikahan</td>
                <td>
                    <select name="reqStatusKawin" id="reqStatusKawin" class="form-control" <?=$disabled?> style="width:25%">
                        <option value="" <? if($tempStatusKawin == "") echo "selected"?>></option>
                        <option value="1" <? if($tempStatusKawin == "1") echo "selected"?>>Belum Kawin</option>
                        <option value="2" <? if($tempStatusKawin == "2") echo "selected"?>>Kawin</option>
                        <option value="3" <? if($tempStatusKawin == "3") echo "selected"?>>Janda</option>
                        <option value="4" <? if($tempStatusKawin == "4") echo "selected"?>>Duda</option>
                    </select>
                </td>
            </tr>


            <?
            if($tempUserStatusJenis == "1")
            {
            ?>
            <tr>
                <td style="width: 25%">NIP</td>
                <td>
                    <input type="hidden" name="reqNIP" id="reqNIP" value="<?=$tempNIP?>" />
                    <input name="reqNIP" id="reqNIP" class="form-control easyui-validatebox"  maxlength="30" type="text" value="<?=$tempNIP?>" readonly style="background-color:#EBEBEB; width: 40%" />
                </td>
            </tr>
            <tr>
                <td>No KTP</td>
                <td>
                    <input name="reqKtp" id="reqKtp" class="easyui-validatebox" data-options="validType:['validKTP[\'\']']" type="text" maxlength="16" <?=$read?> value="<?=$tempKtp?>" placeholder="Ketik KTP Anda..." style="width: 50%" />
                </td>
            </tr> 
            <?
            }
            else
            {
            ?>
            <tr>
                <td style="width: 25%">No KTP</td>
                <td>
                    <input name="reqKtp" id="reqKtp" class="easyui-validatebox" data-options="validType:['validKTP[\'\']']" type="text" maxlength="16" <?=$read?> value="<?=$tempKtp?>" placeholder="Ketik KTP Anda..." readonly style="background-color:#EBEBEB; width: 50%" />
                </td>
            </tr>
            <?
            }
            ?>

            <tr>
                <td style="vertical-align: middle !important;"> Jabatan saat ini</td>
                <td>
                    <input type="text" name="reqJabatan" id="reqJabatan" <?=$read?> value="<?=$tempJabatan?>" style="width:50%" class="form-control easyui-validatebox" />
                </td>
            </tr>
            <tr>
                <td>Eselon & Gol. Ruang</td>
                <td> <select name="reqPegawaiPangkatId" id="reqPegawaiPangkatId">
                    <option value="" <? if($reqPegawaiPangkatId == "") echo 'selected'?>></option>
                    <?
                    while($pangkat_new->nextRow())
                    {
                     $infoid= $pangkat_new->getField("PANGKAT_ID");
                     $infonama= $pangkat_new->getField("NAMA")." (".$pangkat_new->getField("KODE").")";
                     ?>
                     <option value="<?=$infoid?>" <? if($reqPegawaiPangkatId == $infoid) echo 'selected'?>><?=$infonama?></option>
                     <?
                 }
                 ?>
             </select></td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Nama Atasan Langsung</td>
                <td>
                    <input type="text" name="reqPegawaiAtasanLangsungNama" id="reqPegawaiAtasanLangsungNama" <?=$read?> value="<?=$reqPegawaiAtasanLangsungNama?>" style="width:50%" class="form-control easyui-validatebox"   />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Jabatan Atasan Langsung</td>
                <td>
                    <input type="text" name="reqPegawaiAtasanLangsungJabatan" id="reqPegawaiAtasanLangsungJabatan" <?=$read?> value="<?=$reqPegawaiAtasanLangsungJabatan?>" style="width:50%" class="form-control easyui-validatebox" />
                </td>
            </tr>
           
     
        </table>    
        <br>
        <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  II. LINGKUNGAN KELUARGA</div>
        <table class="formstyle">
            <tr>
                <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_saudara()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
            </tr>
        </table>
        <br>
        <table id="tableinfo">
            <thead>
                <tr>
                    <th style="width: 30%">Nama</th>
                    <th style="width: 7%">L / P</th>
                    <th style="width: 24%">Tempat/Tgl Lahir</th>
                    <th style="width: 20%">Pendidikan</th>
                    <th style="width: 20%">Pekerjaan</th>
                    <th style="width: 5%">Action</th>
                </tr>
            </thead>
            <tbody id="tbodyObyek">
                <?php

                $reqViewSaudara = new Peserta();
                $reqViewSaudara->selectByParamsSaudara(array("PEGAWAI_ID" => (int)$reqId));

                $x=0;
                $reqNameSpareparttanda= array();
                while ($reqViewSaudara->nextRow()) {
                    $reqKeluargaSaudara = $reqViewSaudara->getField("NAMA");
                    $reqKeluargaTempat = $reqViewSaudara->getField("TEMPAT");
                    $reqKeluargaTgllahir = $reqViewSaudara->getField("TGL_LAHIR");
                    $reqKeluargaPendidikan = $reqViewSaudara->getField("PENDIDIKAN");
                    $reqKeluargaPekerjaan = $reqViewSaudara->getField("PEKERJAAN");

                    ?>
                    <tr>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqKeluargaSaudara[]" id="reqKeluargaSaudara" value="<?= $reqKeluargaSaudara ?>" style="width:100%;">
                        </td>
                         <td>
                            <select name="reqKeluargaSaudaraJenisKelamin[]" id="reqKeluargaSaudaraJenisKelamin" style="width: 100%">
                                <option value="L" <? if($reqKeluargaSaudaraJenisKelamin == "L") echo "selected"?>>L</option>
                                <option value="P" <? if($reqKeluargaSaudaraJenisKelamin == "P") echo "selected"?>>P</option>
                            </select>
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type='text' name='reqKeluargaTempat[]' id='reqKeluargaTempat' value='<?=$reqKeluargaTempat?>'  style='width:90%' >
                            <input class="easyui-validatebox textbox form-control" type='text' name='reqKeluargaTgllahir[]' id='reqKeluargaTgllahir' value='<?=$reqKeluargaTgllahir?>'  style='width:90%' >
                        </td>
                       
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqKeluargaPendidikan[]" id="reqKeluargaPendidikan" value="<?= $reqKeluargaPendidikan ?>" style="width:100%;">
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqKeluargaPekerjaan[]" id="reqKeluargaPekerjaan" value="<?= $reqKeluargaPekerjaan ?>" style="width:100%;">
                        </td>
                       
                      
                        <td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button></td>
                        <?$x++;?>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  III. RIWAYAT PENDIDIKAN  </div>
        <label>1.   Pendidikan Formal </label>
        <table class="formstyle">
            <tr>
                <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_pendidikan_formal()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
            </tr>
        </table>
        <br>
        <table id="tableinfo">
            <thead>
                <tr>
                    <th style="width: 10%">Jenjang</th>
                    <th style="width: 10%">Nama Sekolah</th>
                    <th style="width: 10%">Jurusan</th>
                    <th style="width: 15%">Tempat</th>
                    <th style="width: 15%">Thn s/d Thn</th>
                    <th style="width: 15%">Keterangan</th>
                    <th style="width: 1%">Action</th>
                </tr>
            </thead>
            <tbody id="tbodyObyekPendidikanFormula">
                <?php

                $reqViewPendidikanFormal = new Peserta();
                $reqViewPendidikanFormal->selectByParamsPendidikanRiwayatFormal(array("PEGAWAI_ID" => (int)$reqId));

                $x=0;
                $reqNameSpareparttanda= array();
                while ($reqViewPendidikanFormal->nextRow()) {
                    $reqPendidikanRiwayatPendidikanId = $reqViewPendidikanFormal->getField("PENDIDIKAN_ID");
                    $reqPendidikanRiwayatNamaSekolah = $reqViewPendidikanFormal->getField("NAMA_SEKOLAH");
                    $reqPendidikanRiwayatJurusan = $reqViewPendidikanFormal->getField("JURUSAN");
                    $reqPendidikanRiwayatTempat = $reqViewPendidikanFormal->getField("TEMPAT");
                    $reqPendidikanRiwayatTahunAwal = $reqViewPendidikanFormal->getField("TAHUN_AWAL");
                    $reqPendidikanRiwayatTahunAkhir = $reqViewPendidikanFormal->getField("TAHUN_AKHIR");
                    $reqPendidikanRiwayatKeterangan = $reqViewPendidikanFormal->getField("KETERANGAN");
                                                // echo $reqPendidikanRiwayatJurusan; exit;
                    ?>
                    <tr>
                        <td>
                            <select style='width:100%' name='reqPendidikanRiwayatPendidikanId[]' id='reqPendidikanRiwayatPendidikanId'>
                                <?
                                for($index_data=0; $index_data < $jumlahpendidikanriwayat; $index_data++){
                                    $reqPendidikanRiwayatPendidikanIdview= $arrpendidikanriwayat[$index_data]['PENDIDIKAN_ID'];
                                    $reqPendidikanRiwayatPendidikanNamaview= $arrpendidikanriwayat[$index_data]['NAMA'];
                                    ?>
                                    <option value='<?=$reqPendidikanRiwayatPendidikanIdview?>'
                                        <?if ($reqPendidikanRiwayatPendidikanId==$reqPendidikanRiwayatPendidikanIdview){?>
                                            selected
                                            <?}?>><?=$reqPendidikanRiwayatPendidikanNamaview?></option>
                                            <?}?>
                                        </select>
                                    </td>
                                    <td>
                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatNamaSekolah[]' id='reqPendidikanRiwayatNamaSekolah' value='<?=$reqPendidikanRiwayatNamaSekolah?>' data-options='' style='width:100%' >
                                    </td>
                                    <td>
                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatJurusan[]' id='reqPendidikanRiwayatJurusan' value='<?=$reqPendidikanRiwayatJurusan?>' data-options='' style='width:100%' >
                                    </td>
                                    <td>
                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTempat[]' id='reqPendidikanRiwayatTempat' value='<?=$reqPendidikanRiwayatTempat?>' data-options='' style='width:100%' >
                                    </td>
                                    <td>
                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTahunAwal[]' id='reqPendidikanRiwayatTahunAwal' value='<?=$reqPendidikanRiwayatTahunAwal?>' data-options='' style='width:43%' >
                                        s/d
                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTahunAkhir[]' id='reqPendidikanRiwayatTahunAkhir' value='<?=$reqPendidikanRiwayatTahunAkhir?>' data-options='' style='width:43%' >
                                    </td>
                                    <td>
                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatKeterangan[]' id='reqPendidikanRiwayatKeterangan' value='<?=$reqPendidikanRiwayatKeterangan?>' data-options='' style='width:100%' >
                                    </td>
                                    <td>
                                        <button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button>
                                    </td>
                                </tr>
                            <? } ?>
                        </tbody>
                    </table>
                    <br>

        <label>2.  Pendidikan Informal ( Kursus atau Training ) </label>
        <table class="formstyle">
            <tr>
                <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_pendidikan()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
            </tr>
        </table>
        <br>
        <table id="tableinfo">
            <thead>
                <tr>
                    <th style="width: 30%">Jenis Kursus / Training</th>
                    <th style="width: 30%">Tempat</th>
                    <th style="width: 10%">Tahun</th>
                    <th style="width: 15%">Keterangan</th>
                    <th style="width: 5%">Action</th>
                </tr>
            </thead>
            <tbody id="tbodyObyekPendidikanNonFormula">
                <?php

                $reqViewPendidikanNonFormal = new Peserta();
                $reqViewPendidikanNonFormal->selectByParamsPendidikanRiwayatNonFormal(array("PEGAWAI_ID" => (int)$reqId));

                $x=0;
                $reqNameSpareparttanda= array();
                while ($reqViewPendidikanNonFormal->nextRow()) {
                    $reqNonPendidikanRiwayatJurusan = $reqViewPendidikanNonFormal->getField("JENIS_PELATIHAN");
                    $reqNonPendidikanRiwayatTempat = $reqViewPendidikanNonFormal->getField("TEMPAT");
                    $reqNonPendidikanRiwayatTahun = $reqViewPendidikanNonFormal->getField("TAHUN");
                    $reqNonPendidikanRiwayatKeterangan = $reqViewPendidikanNonFormal->getField("KETERANGAN");
                    ?>
                    <tr>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatJurusan[]" id="reqNonPendidikanRiwayatJurusan" value="<?= $reqNonPendidikanRiwayatJurusan ?>" style="width:100%;">
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatTempat[]" id="reqNonPendidikanRiwayatTempat" value="<?= $reqNonPendidikanRiwayatTempat ?>" style="width:100%;">
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatTahun[]" id="reqNonPendidikanRiwayatTahun" value="<?= $reqNonPendidikanRiwayatTahun ?>" style="width:100%;">
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatKeterangan[]" id="reqNonPendidikanRiwayatKeterangan" value="<?= $reqNonPendidikanRiwayatKeterangan ?>" style="width:100%;">
                        </td>
                        <td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button></td>
                        <?$x++;?>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <br>

         <div class="judul-halaman2"><img src="../WEB/images/icon-input.png" width="28px">  IV. RIWAYAT PEKERJAAN   </div>
        <label>1.   Uraikan dengan singkat pekerjaan Anda selama ini (dimulai dari posisi terakhir):  </label>
        <table class="formstyle">
            <tr>
                <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_jabatan()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
            </tr>
        </table>
        <br>
        <table id="tableinfo">
            <thead>
                <tr>
                    <th style="width: 35%">Jabatan</th>
                    <th style="width: 30%">Tahun</th>
                    <th style="width: 30%">Instansi</th>
                    <th style="width: 5%">Action</th>
                </tr>
            </thead>
            <tbody id="tbodyObyekJabatan">
                <?php

                $reqViewJabatan = new Peserta();
                $reqViewJabatan->selectByParamsJabatanRiwayat(array("PEGAWAI_ID" => (int)$reqId));

                $x=0;
                while ($reqViewJabatan->nextRow()) {
                    $reqJabatanRiwayatNama = $reqViewJabatan->getField("JABATAN");
                    $reqJabatanRiwayatTahunAwal = $reqViewJabatan->getField("TAHUN_AWAL");
                    $reqJabatanRiwayatTahunAkhir = $reqViewJabatan->getField("TAHUN_AKHIR");
                    $reqJabatanRiwayatInstansi = $reqViewJabatan->getField("UNIT_KERJA");
                    ?>
                    <tr>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqJabatanRiwayatNama[]" id="reqJabatanRiwayatNama" value="<?= $reqJabatanRiwayatNama ?>" style="width:100%;">
                        </td>
                        <td>
                            <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAwal[]' id='reqJabatanRiwayatTahunAwal' value='<?=$reqJabatanRiwayatTahunAwal?>' data-options='' style='width:45%' >
                            s/d
                            <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAkhir[]' id='reqJabatanRiwayatTahunAkhir' value='<?=$reqJabatanRiwayatTahunAkhir?>' data-options='' style='width:45%' >
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" name="reqJabatanRiwayatInstansi[]" id="reqJabatanRiwayatInstansi" value="<?= $reqJabatanRiwayatInstansi ?>" style="width:100%;">
                        </td>
                        <td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button></td>
                        <?$x++;?>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <br>
        <table id="tableinfo">
            <label for="reqName" style="width:100%; ">Uraikan tugas untuk dua posisi terakhir bidang pekerjaan di atas. </label>
            <?
            for($index_data=0; $index_data < 5; $index_data++)
            {
                $reqJabatanRiwayatInfoTugasId= $arrjabatanriwayatinfotugas[$index_data]["RIWAYAT_JABATAN_INFO_ID"];
                $reqJabatanRiwayatInfoTugasStatus= $arrjabatanriwayatinfotugas[$index_data]["STATUS"];
                $reqJabatanRiwayatInfoTugasKeterangan= $arrjabatanriwayatinfotugas[$index_data]["KETERANGAN"];
                ?>
                
                <input type="hidden" name="reqJabatanRiwayatInfoTugasId[]" id="reqJabatanRiwayatInfoTugasId<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTugasId?>" />
                <input type="hidden" name="reqJabatanRiwayatInfoTugasStatus[]" id="reqJabatanRiwayatInfoTugasStatus<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTugasStatus?>" />
                <textarea name="reqJabatanRiwayatInfoTugasKeterangan[]" id="reqJabatanRiwayatInfoTugasKeterangan<?=$index_data?>" style="width: 100%; height: 50px"><?=$reqJabatanRiwayatInfoTugasKeterangan?></textarea>
                
                <?
            }
            ?>
        </table>


        <div>
            <input type="hidden" name="reqSubmit" value="update">
            <input type="submit" value="Simpan">
        </div>
        </form>
    </div>
    
</div>
<script type="text/javascript">
$('#reqKtp, #reqHp').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>

<script type="text/javascript">
    function add_pendidikan(array) {
            // console.log("masuk");
            test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatJurusan[]' id='reqNonPendidikanRiwayatJurusan' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatTempat[]' id='reqNonPendidikanRiwayatTempat' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatTahun[]' id='reqNonPendidikanRiwayatTahun' value='' data-options='' style='width:100%' ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatKeterangan[]' id='reqNonPendidikanRiwayatKeterangan' value='' data-options='' style='width:100%'  ></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyekPendidikanNonFormula").append(test);
        }
         function add_jabatan(array) {
            // console.log("masuk");
            test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatNama[]' id='reqJabatanRiwayatNama' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAwal[]' id='reqJabatanRiwayatTahunAwal' value='' data-options='' style='width:46%' >s/d<input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAkhir[]' id='reqJabatanRiwayatTahunAkhir' value='' data-options='' style='width:46%' ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatInstansi[]' id='reqJabatanRiwayatInstansi' value='' data-options='' style='width:100%'  ></td><td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyekJabatan").append(test);
        }
        function add_saudara(array) {
            // console.log("masuk");
            test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaSaudara[]' id='reqKeluargaSaudara' value='' data-options='' style='width:100%' ></td><td><select style='width:100%' name='reqKeluargaSaudaraJenisKelamin[]' id='reqKeluargaSaudaraJenisKelamin'><option value='L'>L</option><option value='P'>P</option></select></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaTempat[]' id='reqKeluargaTempat' value='' data-options='' style='width:46%' ><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaTgllahir[]' id='reqKeluargaTgllahir' value='' data-options='' style='width:46%' ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaPendidikan[]' id='reqKeluargaPendidikan' value='' data-options='' style='width:100%'  ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaPekerjaan[]' id='reqKeluargaPekerjaan' value='' data-options='' style='width:100%'  ></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyek").append(test);
        }
         function Hapussparepart(ctl) {
            $(ctl).parents("tr").remove();
        }
           function add_pendidikan_formal(array) {
            // console.log("masuk");
            $.get("pendidikan_formal_table?reqId=<?=$reqId?>", function (data) {
                $("#dialytable").empty();
                $("#tbodyObyekPendidikanFormula").append(data);
            });
        }  
</script>
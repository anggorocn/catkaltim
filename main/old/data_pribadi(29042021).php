<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/satuankerjainternal.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
/*include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base-simpeg/Agama.php");
include_once("../WEB/classes/base-simpeg/Bank.php");
include_once("../WEB/classes/base-simpeg/StatusPegawai.php");
include_once("../WEB/classes/base-simpeg/StatusKeluarga.php");*/

include_once("../WEB/classes/base-diklat/Peserta.php");
include_once("../WEB/classes/base-diklat/MPangkat.php");
include_once("../WEB/classes/base-diklat/MPendidikan.php");
include_once("../WEB/classes/base-diklat/MEselon.php");
include_once("../WEB/classes/base-diklat/Propinsi.php");

$userLogin->checkLoginPelamar();
$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;

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
			// console.log(data);return false;
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
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table style="width: 100%">
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
                <td>Nama</td>
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
                <td>Status Perkawinan</td>
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

            <tr>
                <td>Alamat</td>
                <td>
                   <!--  <input type="text" name="reqAlamat" id="reqAlamat" <?=$read?> value="<?=$tempAlamat?>" class="form-control easyui-validatebox" /> -->
                    <textarea style="width:100%" name="reqAlamat" id="reqAlamat"><?=$tempAlamat?></textarea>
                </td>
            </tr>

            <tr>        
                <td>Status Pegawai</td>
                <td>
                    <select name="reqStatusPegawai" id="reqStatusPegawai" class="form-control" <?=$disabled?> style="width:25%">
                        <option value="" <? if($tempStatusPegawai == "") echo "selected"?>></option>
                        <option value="1" <? if($tempStatusPegawai == "1") echo "selected"?>>CPNS</option>
                        <option value="2" <? if($tempStatusPegawai == "2") echo "selected"?>>PNS</option>
                      
                    </select>
                </td>
            </tr>

            <tr>
                <td style="vertical-align: middle !important;">Tempat Kerja</td>
                <td>
                    <input type="text" name="reqTempatKerja" id="reqTempatKerja" <?=$read?> style="width:50%" value="<?=$tempTempatKerja?>" class="form-control easyui-validatebox" />
                </td>
            </tr>

            <tr>
                <td style="vertical-align: middle !important;">Alamat Tempat Kerja</td>
                <td>
                    <textarea style="width:100%" name="reqAlamatTempatKerja" id="reqAlamatTempatKerja"><?=$tempAlamatTempatKerja?></textarea>
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
                <td style="vertical-align: middle !important;"> Alamat Email</td>
                <td>
                    <input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$tempEmail?>" style="width:50%" class="form-control easyui-validatebox" data-options="validType:['email']" placeholder="Ketik Email Anda..." />
                </td>
            </tr>
            <tr>
                <td>Akun Sosial Media</td>
                <td>
                    <!-- <input type="text" name="reqSosmed" id="reqSosmed" <?=$read?> style="width:50%" value="<?=$tempSosmed?>" class="form-control easyui-validatebox" /> -->
                    <textarea style="width:100%" name="reqSosmed" id="reqSosmed"><?=$tempSosmed?></textarea>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Kontak Pribadi (HP)</td>
                <td>
                    <input type="text" style="width:40%" name="reqHp" id="reqHp" <?=$read?> value="<?=$tempHp?>" class="form-control easyui-validatebox" required data-options="validType:'Number'" placeholder="Kontak Pribadi (HP)..." />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Auto Anamnesa (Ceritakan tentang diri anda sendiri)</td>
                <td>
                   <!--  <input type="text" style="width:40%" name="reqAuto" id="reqAuto" <?=$read?> value="<?=$tempAuto?>" class="form-control easyui-validatebox" /> -->
                    <textarea style="width:100%" name="reqAuto" id="reqAuto"><?=$tempAuto?></textarea>
                </td>
            </tr>
           <!--  <tr>
                <td>Upload foto</td>
                <td>
                    <input type="file" style="font-size:10px" name="reqFotoFile" id="reqFotoFile" class="easyui-validatebox" accept="image/*" />
                </td>        
            </tr> -->
        </table>    
        <br>   
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
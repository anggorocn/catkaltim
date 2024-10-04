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
// phpinfo();
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

flush();
ob_flush();

ini_set('max_input_vars', 0);

$urlLink= $data->urlConfig->main->urlLink;
$userLogin->checkLoginPelamar();
$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;

$reqId = $tempUserPelamarId;
$reqPegawaiId = $tempUserPelamarId;

$peserta= new Peserta();
$peserta->selectByParamsDataPribadi(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
$peserta->firstRow();
$reqStatusJenis= $peserta->getField('STATUS_JENIS');
$tempStatusPegawai= $peserta->getField('STATUS_PEGAWAI_ID');

$tempEmail= $peserta->getField('EMAIL');
$tempKtp= $peserta->getField('KTP');
$tempUnitKerja= $peserta->getField('UNIT_KERJA_NAMA');
$tempUnitKerjaKota= $peserta->getField('UNIT_KERJA_KOTA');
$tempNama= $peserta->getField('NAMA');
$tempNIP= $peserta->getField('NIP');

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);

$urlPasangan = 'https://api-simpeg.kaltimbkd.info//pns/data-suami-istri/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiPasangan = json_decode(file_get_contents($urlPasangan), true);

$urlAnak = 'https://api-simpeg.kaltimbkd.info//pns/data-anak/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiAnak = json_decode(file_get_contents($urlAnak), true);

$urlPendidikan = 'https://api-simpeg.kaltimbkd.info//pns/riwayat-pendidikan/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiPendidikan = json_decode(file_get_contents($urlPendidikan), true);

$url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-jabatan/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApiJabatan = json_decode(file_get_contents($url), true);

$tempJenisKelamin= $peserta->getField('JENIS_KELAMIN');
$tempStatusKawin= $peserta->getField('STATUS_KAWIN');
$tempStatusKawinInfo= $peserta->getField('STATUS_KAWIN_INFO');

$tempTempatLahir= $peserta->getField('TEMPAT_LAHIR');
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
$statement= " AND A.PENDIDIKAN_ID not IN (0)";
$set= new Peserta();
$set->selectByParamsPendidikanRiwayat(array(), -1,-1, $tempUserPelamarId,$statement);
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

$arrjabatanriwayatinfotugas=array();
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new Peserta();
$set->selectByParamsJabatanRiwayatInfo(array(), -1,-1, $statement);
while($set->nextRow())
{
 $arrjabatanriwayatinfotugas[$index_data]["RIWAYAT_JABATAN_INFO_ID"] = $set->getField("RIWAYAT_JABATAN_INFO_ID");
 $arrjabatanriwayatinfotugas[$index_data]["STATUS"] = "1";
 $arrjabatanriwayatinfotugas[$index_data]["KETERANGAN"] = $set->getField("KETERANGAN");
 $index_data++;
}
// print_r($arrjabatanriwayatinfotugas);exit;

$pekerjaan= new Peserta();
$pekerjaan->selectByParamsDataPekerjaan(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
$pekerjaan->firstRow();
$reqDataId=$pekerjaan->getField('DATA_PEKERJAAN_ID');
$reqGambaran= $pekerjaan->getField('GAMBARAN');
$reqUraikan= $pekerjaan->getField('URAIKAN');
$reqTanggungJawabPekerjaan= $pekerjaan->getField('TANGGUNG_JAWAB');

$kondisi= new Peserta();
$kondisi->selectByParamsKondisiKerja(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
$kondisi->firstRow();
$reqKondisiKerjaId=$kondisi->getField('KONDISI_KERJA_ID');
$reqBaikId= $kondisi->getField('BAIK_ID');
$reqCukupBaikId= $kondisi->getField('CUKUP_ID');
$reqPerluId= $kondisi->getField('PERLU_ID');
$reqKondisi= $kondisi->getField('KONDISI');
$reqAspek= $kondisi->getField('ASPEK');

$minat= new Peserta();
$minat->selectByParamsMinat(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
$minat->firstRow();
$reqMinatId=$minat->getField('MINAT_HARAPAN_ID');
$reqSukai= $minat->getField('SUKAI');
$reqTidakSukai= $minat->getField('TIDAK_SUKAI');

$kelemahan= new Peserta();
$kelemahan->selectByParamsKekuatan(array(), -1,-1, " AND A.PEGAWAI_ID = ".$tempUserPelamarId);
$kelemahan->firstRow();
$reqKekuatanId=$kelemahan->getField('KEKUATAN_KELEMAHAN_ID');
$reqKekuatan= $kelemahan->getField('KEKUATAN');
$reqKelemahan= $kelemahan->getField('KELEMAHAN');

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
    $.extend($.fn.validatebox.defaults.rules, {
        Number: {  
            validator: function (value) {              
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
                // console.log(data); return false;
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

        var dates = $( "#reqTanggalLahir" ).datepicker({
            defaultDate: "+0w",
            dateFormat: 'dd-mm-yy',
            numberOfMonths: 1,
            changeMonth: true,
            changeYear: true,
            beforeShow: function (input, inst) {
                var rect = input.getBoundingClientRect();
                setTimeout(function () {
                }, 0);
            }
        });
        
    });

</script>

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

<div class="col-lg-6">
    <div id="judul-halaman"><?=$arrayJudul["index"]["data_pribadi"]?></div>
</div>
<div class="col-lg-6"> 
      <div id="judul-halaman" align="right">
        <a href="index.php"><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" style="background-color: blue; color: white" ><i class="fa fa-arrow-left"> Back</i></button></a>
    </div>
</div>   
<div class="col-lg-12">    
    <div class="judul-halaman2"><i class="fa fa-pencil" aria-hidden="true"></i> IDENTITAS DIRI</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table style="width: 100%">
            <tr>
                <td>Nama Lengkap</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                    ?>
                        <input type="text" name="reqNama" id="reqNama" <?=$read?> value="<? if($dataApi['glr_depan']=='-'){ } else{ echo $dataApi['glr_depan']; }?> <?=$dataApi['nama']?> <? if($dataApi['glr_belakang']=='-'){ } else{ echo $dataApi['glr_belakang']; }?>" class="form-control easyui-validatebox"   readonly />
                    <?
                    }
                    else
                    {
                    ?>
                        <input type="text" name="reqNama" id="reqNama" <?=$read?> value="<?=$tempNama?>" class="form-control easyui-validatebox" required placeholder="Ketik Nama Lengkap Anda..." />
                    <?
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Tempat / Tanggal Lahir</td>
                <td>
                    <table style="width: 100%; border: medium none ! important; margin: 0px ! important;">
                    <tr>
                        <?
                        if($reqStatusJenis==1)
                        {
                        ?>
                            <td style="width: 40%; padding: 0px ! important;">
                                <input type="text" name="reqTempatLahir" id="reqTempatLahir" <?=$read?> value="<?=$dataApi['tempat_lahir']?>" class="form-control easyui-validatebox"  readonly />
                            </td>
                            <td style="width: 20%; vertical-align: middle !important;">
                                <input type="text" name="reqTanggalLahir" <?=$read?> value="<?=dateToDB($dataApi['tgl_lahir'])?>" class="form-control easyui-validatebox"  data-options="validType:['dateValidPicker']" readonly/>
                            </td>
                            <?
                        }
                        else
                        {
                            ?>
                            <td style="width: 40%; padding: 0px ! important;">
                                <input type="text" name="reqTempatLahir" id="reqTempatLahir" <?=$read?> value="<?=$tempTempatLahir?>" class="form-control easyui-validatebox" required placeholder="Ketik Tempat Lahir Anda..." />
                            </td>
                            <td style="width: 20%; vertical-align: middle !important;">
                                <input type="text" name="reqTanggalLahir" id="reqTanggalLahir" <?=$read?> value="<?=$tempTanggalLahir?>" class="form-control easyui-validatebox" required data-options="validType:['dateValidPicker']" placeholder="Ketik Tgl Lahir Anda..." />
                            </td>
                            
                            <?
                        }
                        ?>
                    </table>
                </td>
            </tr>
            <tr>        
                <td>Jenis Kelamin</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                        ?>
                        <input type="text" name="reqJenisKelaminNama" id="reqJenisKelaminNama" <?=$read?> value="<?=$dataApi['jenis_kelamin']?>" class="form-control easyui-validatebox"  data-options="validType:['dateValidPicker']" placeholder="Ketik Tgl Lahir Anda..." readonly/>
                        <input type="hidden" name="reqJenisKelamin" id="reqJenisKelamin" <?=$read?> value="<?=$dataApi['id_jenis_kelamin']?>" class="form-control easyui-validatebox"  data-options="validType:['dateValidPicker']"  readonly/>
                        <?
                    }
                    else
                    {
                        ?>
                        <select name="reqJenisKelamin" id="reqJenisKelamin" class="form-control" <?=$disabled?> style="width:25%">
                            <option value="" <? if($tempJenisKelamin == "") echo "selected"?>></option>
                            <option value="L" <? if($tempJenisKelamin == "L") echo "selected"?>>Laki-laki</option>
                            <option value="P" <? if($tempJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                        </select>

                        <?
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                    ?>
                        <input type="text" name="reqAlamat" id="reqAlamat" <?=$read?> value="<?=$dataApi['alamat']?>" class="form-control easyui-validatebox"  readonly/>
                    <?
                    }
                    else
                    {
                        ?>
                        <textarea style="width:100%" name="reqAlamat" id="reqAlamat"><?=$tempAlamat?></textarea>
                        <?
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Telepon / HP</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                        ?>
                        <input type="text" name="reqHp" id="reqHp" value="<?=$dataApi['no_hape']?>" style="width:50%" class="form-control easyui-validatebox"  placeholder="" readonly/>
                        <?
                    }
                    else
                    {
                        ?>
                         <input type="text" name="reqHp" id="reqHp" value="<?=$tempHp?>" style="width:50%" class="form-control easyui-validatebox"  placeholder="" />
                        <?
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;"> Alamat Email</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                        ?>
                        <input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$dataApi['email']?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                    <?
                    }
                    else
                    {
                    ?>
                        <input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$tempEmail?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                    <?
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                    ?>
                        <input type="text" name="reqAgama" id="reqAgama" <?=$read?> value="<?=$dataApi['agama']?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                    <?
                    }
                    else
                    {
                    ?>
                        <input type="text" name="reqAgama" id="reqAgama" <?=$read?> value="<?=$tempAgama?>" style="width:50%" class="form-control easyui-validatebox"  />
                    <?
                    }
                    ?>
                </td>
            </tr>  
            <tr>        
                <td>Status Pernikahan</td>
                <td>
                    <?
                    if($reqStatusJenis==1)
                    {
                    ?>
                        <?if($dataApi['id_status_nikah']==1){?>
                         <input type="hidden" name="reqStatusKawin" id="reqStatusKawin"value="<?=$dataApi['id_status_nikah']?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <input type="text" name="reqStatusKawinNama" id="reqStatusKawinNama" value="Menikah" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <?} else if($dataApi['id_status_nikah']==2){?>
                         <input type="hidden" name="reqStatusKawin" id="reqStatusKawin"value="<?=$dataApi['id_status_nikah']?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <input type="text" name="reqStatusKawinNama" id="reqStatusKawinNama" value="Belum Menikah" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <?} else if($dataApi['id_status_nikah']==3){?>
                         <input type="hidden" name="reqStatusKawin" id="reqStatusKawin"value="<?=$dataApi['id_status_nikah']?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <input type="text" name="reqStatusKawinNama" id="reqStatusKawinNama" value="Janda/Duda" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <?} else if($dataApi['id_status_nikah']==5){?>
                         <input type="hidden" name="reqStatusKawin" id="reqStatusKawin"value="<?=$dataApi['id_status_nikah']?>" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <input type="text" name="reqStatusKawinNama" id="reqStatusKawinNama" value="Cerai" style="width:50%" class="form-control easyui-validatebox"  readonly/>
                         <?}?>
                    <?
                    }
                    else
                    {
                    ?>
                    <select name="reqStatusKawin" id="reqStatusKawin" class="form-control" <?=$disabled?> style="width:25%">
                        <option value="" <? if($tempStatusKawin == "") echo "selected"?>></option>
                        <option value="1" <? if($tempStatusKawin == "1") echo "selected"?>>Belum Kawin</option>
                        <option value="2" <? if($tempStatusKawin == "2") echo "selected"?>>Kawin</option>
                        <option value="3" <? if($tempStatusKawin == "3") echo "selected"?>>Janda</option>
                        <option value="4" <? if($tempStatusKawin == "4") echo "selected"?>>Duda</option>
                    </select>
                    <?
                    }
                    ?>
                </td>
            </tr>
            <?
            if($tempUserStatusJenis == "1" || $tempUserStatusJenis == "2")
            {
            ?>
            <tr>
                <td style="width: 25%">NIP</td>
                <td>
                    <input type="hidden" name="reqNIP" id="reqNIP" value="<?=$tempNIP?>" />
                    <input name="reqNIP" id="reqNIP" class="form-control easyui-validatebox"  maxlength="30" type="text" value="<?=$tempNIP?>" readonly style="background-color:#EBEBEB; width: 40%" />
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
                    <?
                    if($dataApiJabatan[0]['jabatan']!='')
                    {
                    ?>
                    <input type="text" name="reqJabatan" id="reqJabatan" <?=$read?> value="<?=$dataApiJabatan[0]['jabatan']?>" style="width:50%" class="form-control easyui-validatebox" readonly />
                    <?
                    }
                    else
                    {
                    ?>
                    <input type="text" name="reqJabatan" id="reqJabatan" <?=$read?> value="<?=$tempJabatan?>" style="width:50%" class="form-control easyui-validatebox" readonly />
                    <?
                    }
                    ?>
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
        <br>  
        <div class="judul-halaman2"><i class="fa fa-pencil" aria-hidden="true"></i> II. LINGKUNGAN KELUARGA</div>
        <button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_saudara()" style="background-color: blue; color: white;margin-left: 10px;"><i class="fa fa-plus orange"> Add</i></button>
        <div class="col-md-12" style="overflow: scroll; width: 100%">
            <table id="tableinfo" style="width:150%" >
                <thead style="color: white;background: blue;">
                    <tr>
                        <th style="width: 1%;text-align: center">Status</th>
                        <th style="width: 3%;text-align: center">Nama</th>                            
                        <th style="width: 1%;text-align: center">L / P</th>
                        <th style="width: 2%;text-align: center">Tempat/Tgl Lahir</th>
                        <th style="width: 2%;text-align: center">Pendidikan</th>
                        <th style="width: 2%;text-align: center">Pekerjaan</th>
                        <th style="width: 1%;">Action</th>
                    </tr>
                </thead>
                <tbody id="tbodyObyek">
                    <tr>
                        <td>
                            <select disabled style="width: 100%">
                                <option selected>Pasangan</option>
                            </select>
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" disabled value="<?= $dataApiPasangan['0']['nama_pasutri'] ?>" style="width:100%;">
                        </td>
                        <td>
                            <select style="width: 100%" disabled>
                                <? if($dataApiPasangan['0']['jenis_kelamin'] == "Laki-laki"){?>
                                    <option >L</option>
                                <?}
                                else if($dataApiPasangan['0']['jenis_kelamin'] == "Perempuan"){?>
                                    <option>P</option>
                                <?}?>
                            </select>
                        </td>
                    </tr>

                    <?
                    for($i=0;$i<count($dataApiAnak);$i++){?>
                    <tr>
                        <td>
                            <select disabled style="width: 100%">
                                <option selected>Anak</option>
                            </select>
                        </td>
                        <td>
                            <input class="easyui-validatebox textbox form-control" type="text" disabled value="<?= $dataApiAnak[$i]['nama'] ?>" style="width:100%;">
                        </td>
                        <td>
                            <select style="width: 100%" disabled>
                                <? if($dataApiAnak[$i]['jenis_kelamin'] == "Laki-laki"){?>
                                    <option >L</option>
                                <?}
                                else if($dataApiAnak[$i]['jenis_kelamin'] == "Perempuan"){?>
                                    <option>P</option>
                                <?}?>
                            </select>
                        </td>
                        <td>
                            <div style="display: grid; grid-template-columns: auto auto">
                                <input class="easyui-validatebox textbox form-control" disabled type='text' style='width:95%;  padding: 10px;' value='<?=$dataApiAnak[$i]['tempat_lahir_anak']?>'   >
                                <input class="easyui-validatebox textbox form-control" disabled type='date' style='width:100%' value='<?=$dataApiAnak[$i]['tgl_lahir_anak']?>'   >
                            </div>
                        </td>
                    </tr>
                    <?}?>
                    <?php

                    $reqViewSaudara = new Peserta();
                    $reqViewSaudara->selectByParamsSaudara(array("PEGAWAI_ID" => (int)$reqId));

                    $x=0;
                    while ($reqViewSaudara->nextRow()) {
                        $reqKeluargaSaudara = $reqViewSaudara->getField("NAMA");
                        $reqKeluargaTempat = $reqViewSaudara->getField("TEMPAT");
                        $reqKeluargaTgllahir = $reqViewSaudara->getField("TGL_LAHIR");
                        $reqKeluargaPendidikan = $reqViewSaudara->getField("PENDIDIKAN");
                        $reqKeluargaPekerjaan = $reqViewSaudara->getField("PEKERJAAN");
                        $reqKeluargaSaudaraJenisKelamin = $reqViewSaudara->getField("JENIS_KELAMIN");
                        $reqKeluargaStatus = $reqViewSaudara->getField("STATUS");
                        ?>
                        <tr>
                            <td>
                                <select name="reqKeluargaSaudaraStatus[]" id="reqKeluargaSaudaraStatus" style="width: 100%">
                                    <option value="anak" <? if($reqKeluargaStatus == "anak") echo "selected"?>>Anak</option>
                                    <option value="pasangan" <? if($reqKeluargaStatus == "pasangan") echo "selected"?>>Pasangan</option>
                                </select>
                            </td>
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
                                <input type='text' name='reqKeluargaTempat[]' id='reqKeluargaTempat' style='width:45%' value='<?=$reqKeluargaTempat?>'   >
                                 <input  type='date' name='reqKeluargaTgllahir[]' id='reqKeluargaTgllahir' style='width:45%' value='<?=$reqKeluargaTgllahir?>'   >
                            </td>
                            <td>
                                <select style='width:100%' name='reqKeluargaPendidikan[]' id='reqKeluargaPendidikan'>
                                <?
                                for($index_data=0; $index_data < $jumlahpendidikanriwayat; $index_data++){
                                    $reqPendidikanRiwayatPendidikanIdview= $arrpendidikanriwayat[$index_data]['PENDIDIKAN_ID'];
                                    $reqPendidikanRiwayatPendidikanNamaview= $arrpendidikanriwayat[$index_data]['NAMA'];
                                    ?>
                                    <option value='<?=$reqPendidikanRiwayatPendidikanIdview?>'
                                    <?if ($reqKeluargaPendidikan==$reqPendidikanRiwayatPendidikanIdview){?>
                                        selected
                                        <?}?>><?=$reqPendidikanRiwayatPendidikanNamaview?></option>
                                        <?}?>
                                </select>
                            </td>
                            
                            <td>
                                <input class="easyui-validatebox textbox form-control" type="text" name="reqKeluargaPekerjaan[]" id="reqKeluargaPekerjaan" value="<?= $reqKeluargaPekerjaan ?>" style="width:100%;">
                            </td>
                            <td>
                                <button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button>
                            </td>
                        </tr>
                        <?$x++;?>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <br>
        <br>
        <div class="judul-halaman2" style="margin-top: 30px">      
        <br>
        <br>      
            <i class="fa fa-pencil" aria-hidden="true"></i> III. RIWAYAT PENDIDIKAN  
        </div>
        <label style="margin-left: 10px;">1.   Pendidikan Formal </label> (dari jenjang sekolah dasar hingga pendidikan terakhir)<br>
        <button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_pendidikan_formal()" style="background-color: blue; color: white;margin-left: 10px;"><i class="fa fa-plus orange"> Add</i></button>
        <br>
        <div class="col-md-12" style="overflow: scroll; width: 100%;margin-bottom: 30px;">
            <table id="tableinfo"  style="width:150%">
                <thead style="background-color: blue; color: white;">
                    <tr>
                        <th style="width: 10%;text-align: center">Jenjang</th>
                        <th style="width: 20%;text-align: center">Nama Sekolah</th>
                        <th style="width: 15%;text-align: center">Jurusan</th>
                        <th style="width: 15%;text-align: center">Tempat</th>
                        <th style="width: 30%;text-align: center">Thn s/d Thn</th>
                        <th style="width: 15%;text-align: center">Keterangan</th>
                        <th style="width: 1%;text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody id="tbodyObyekPendidikanFormula">
                    <?for($i=0; $i<count($dataApiPendidikan); $i++){?>
                        <tr>
                            <td>
                                <select disabled style="width:100%">
                                    <option> <?=$dataApiPendidikan[$i]['singkat']?></option>
                                </select>
                            </td>
                            <td>
                                <input disabled class='easyui-validatebox textbox form-control' type='text' value='<?=$dataApiPendidikan[$i]['nama_sekolah']?>' data-options='' style='width:100%' >
                            </td>
                            <td>
                                <input disabled class='easyui-validatebox textbox form-control' type='text' value='<?=$dataApiPendidikan[$i]['jurusan']?>' data-options='' style='width:100%' >
                            </td>
                            <td>
                                <input disabled class='easyui-validatebox textbox form-control' type='text' value='<?=$dataApiPendidikan[$i]['alamat_pendidikan']?>' data-options='' style='width:100%' >
                            </td>
                            <td>
                                <div style="display: grid; grid-template-columns: auto auto auto">
                                    <input disabled class='easyui-validatebox textbox form-control' type='text' value='<?=$reqPendidikanRiwayatTahunAwal?>' style='width:95%;  padding: 10px;'  >
                                    <div style='width:95%;  padding: 10px;' >s/d</div>
                                    <input disabled class='easyui-validatebox textbox form-control' type='date' value='<?=$dataApiPendidikan[$i]['tgl_lulus']?>'style='width:100%;'  >
                                </div>
                            </td>
                        </tr>
                    <?}?>
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
                                <input $disabled  type='text' name='reqPendidikanRiwayatTahunAwal[]' id='reqPendidikanRiwayatTahunAwal' value='<?=$reqPendidikanRiwayatTahunAwal?>' data-options='' style='width:45%' >
                                s/d
                                <input $disabled type='text' name='reqPendidikanRiwayatTahunAkhir[]' id='reqPendidikanRiwayatTahunAkhir' value='<?=$reqPendidikanRiwayatTahunAkhir?>' data-options='' style='width:45%' >
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
        </div>
        <br>
        <br>
        <div style="margin-top: 30px">
            <label style="margin-left: 10px;">2.  Pendidikan Informal ( Kursus atau Training ) </label>
            <br>
            <table id="tableinfo">
                <thead style="background-color: blue; color: white;">
                    <tr>
                        <th style="width: 20%;text-align: center">Jenis</th>
                        <th style="width: 20%;text-align: center">Nama</th>
                        <th style="width: 20%;text-align: center">Penyelenggara</th>
                        <th style="width: 20%;text-align: center">Tempat</th>
                        <th style="width: 10%;text-align: center">Tanggal</th>
                        <th style="width: 10%;text-align: center">Total Jam</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-diklat-struktural/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
                    $dataDiklatStruktural = json_decode(file_get_contents($url), true);
                    for($i=0; $i<count($dataDiklatStruktural['data']);$i++){?>
                        <tr>
                            <td> <input type="text" value="Diklat Struktural" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['jenis_diklat_struktural']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['penyelenggara']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['tempat_diklat']?></textarea></td>
                            <td><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_mulai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""> <center>s/d</center><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_selesai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['jumlah_jam']?></textarea></td>
                        </tr>
                    <?}
                    $startnomer=$startnomer+count($dataDiklatStruktural['data']);
                    $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-diklat-fungsional/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
                    $dataDiklatStruktural = json_decode(file_get_contents($url), true);
                    for($i=0; $i<count($dataDiklatStruktural['data']);$i++){?>
                        <tr>
                            <td><input type="text" value="Diklat fungsional" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><?=$dataDiklatStruktural['data'][$i]['jenis_diklat_fungsional']?></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['penyelenggara']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['tempat_diklat']?></textarea></td>
                           <td><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_mulai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""> <center>s/d</center><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_selesai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['jumlah_jam']?></textarea></td>
                        </tr>
                    <?}
                    ?>
                    <?
                    $startnomer=$startnomer+count($dataDiklatStruktural['data']);
                    $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-diklat-teknis/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
                    $dataDiklatStruktural = json_decode(file_get_contents($url), true);
                    for($i=0; $i<count($dataDiklatStruktural['data']);$i++){?>
                        <tr>
                            <td><input type="text" value="Diklat Teknis" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['nama_diklat']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['penyelenggara']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['tempat_diklat']?></textarea></td>
                           <td><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_mulai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""> <center>s/d</center><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_selesai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['jumlah_jam']?></textarea></td>
                        </tr>
                    <?}
                    ?>
                    <?
                    $startnomer=$startnomer+count($dataDiklatStruktural['data']);
                    $url = 'https://api-simpeg.kaltimbkd.info/pns/riwayat-seminar/'.$tempNIP.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
                    $dataDiklatStruktural = json_decode(file_get_contents($url), true);
                    for($i=0; $i<count($dataDiklatStruktural['data']);$i++){?>
                        <tr>
                            <td><input type="text" value="Seminar" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['nama_seminar']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['penyelenggara']?></textarea></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['tempat_seminar']?></textarea></td>
                            <td><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_mulai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""> <center>s/d</center><input type="text" value="<?=$dataDiklatStruktural['data'][$i]['tgl_selesai']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                            <td><textarea class="form-control easyui-validatebox validatebox-text" readonly><?=$dataDiklatStruktural['data'][$i]['jumlah_jam']?></textarea></td>
                        </tr>
                    <?}
                    ?>
                </tbody>
            </table>
        </div>

        <div class="judul-halaman2" style="margin-top: 30px"><i class="fa fa-pencil" aria-hidden="true"></i> IV. RIWAYAT JABATAN   </div>
        <label style="margin-left: 10px;">1.   Uraikan dengan singkat Jabatan Anda selama ini:  </label>
        <br>
        <div class="col-md-12" style="width: 100%">
        <table id="tableinfo"  style="width:100%">
            <thead style="background-color: blue; color: white;">
                <tr>
                    <th style="width: 30%;text-align: center">Jabatan</th>
                    <th style="width: 10%;text-align: center">Tanggal Pelantikan</th>
                    <th style="width: 30%;text-align: center">Penandatangan</th>
                    <th style="width: 18%;text-align: center">Instansi</th>
                </tr>
            </thead>
            <tbody id="tbodyObyekJabatan">
                <?php
                for($i=0; $i<count($dataApiJabatan);$i++){?>
                    <tr>
                        <td><textarea class="form-control easyui-validatebox validatebox-text" readonly=""><?=$dataApiJabatan[$i]['jabatan']?> </textarea></td>
                        <td><input type="text" value="<?=$dataApiJabatan[$i]['tgl_pelantikan']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                        <td><input type="text" value="<?=$dataApiJabatan[$i]['penandatangan']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                        <td><input type="text" value="<?=$dataApiJabatan[$i]['lokasi']?>" class="form-control easyui-validatebox validatebox-text" readonly=""></td>
                    </tr>
                <?}?>
            </tbody>
        </table>
        </div>

        <table id="tableinfo">
            <label for="reqName" style="width:100%; ">
             <br>
            <br>Uraikan tugas untuk dua posisi terakhir bidang pekerjaan di atas. </label>
            <?
            for($index_data=0; $index_data < 5; $index_data++)
            {
                $reqJabatanRiwayatInfoTugasId= $reqJabatanRiwayatInfoTugasStatus= $reqJabatanRiwayatInfoTugasKeterangan= "";
                if(!empty($arrjabatanriwayatinfotugas))
                {
                    $reqJabatanRiwayatInfoTugasId= $arrjabatanriwayatinfotugas[$index_data]["RIWAYAT_JABATAN_INFO_ID"];
                    $reqJabatanRiwayatInfoTugasStatus= $arrjabatanriwayatinfotugas[$index_data]["STATUS"];
                    $reqJabatanRiwayatInfoTugasKeterangan= $arrjabatanriwayatinfotugas[$index_data]["KETERANGAN"];
                }
                ?>
                <input type="hidden" name="reqJabatanRiwayatInfoTugasId[]" id="reqJabatanRiwayatInfoTugasId<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTugasId?>" />
                <input type="hidden" name="reqJabatanRiwayatInfoTugasStatus[]" id="reqJabatanRiwayatInfoTugasStatus<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTugasStatus?>" />
                <textarea name="reqJabatanRiwayatInfoTugasKeterangan[]" id="reqJabatanRiwayatInfoTugasKeterangan<?=$index_data?>"style="width: 100%; height: 50px;margin-bottom: 20px;"><?=$reqJabatanRiwayatInfoTugasKeterangan?></textarea>
                
                <?
            }
            ?>
        </table>
        <div class="judul-halaman2" style="margin-top: 30px"><i class="fa fa-pencil" aria-hidden="true"></i> V. DATA PEKERJAAN  </div>
        <table id="tableinfo">
            <label for="reqName" style="width:100%; margin-left: 10px;">1.    Untuk memperoleh gambaran lebih jelas mengenai posisi jabatan Anda di dalam struktur organisasi, tolong jelaskan posisi jabatan anda sesuai struktur organisasi di tempat Anda bekerja.</label>
            <input type="hidden" name="reqDataId" id="reqDataId" value="<?=$reqDataId?>" />
            <textarea name="reqGambaran" id="reqGambaran" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqGambaran?></textarea>
            <br>
            <br>
            <label for="reqName" style="width:100%; margin-left: 10px;">2.  Apa saja tanggungjawab Anda pada pekerjaan/jabatan Anda sekarang?</label>
            <textarea name="reqTanggungJawabPekerjaan" id="reqTanggungJawabPekerjaan" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqTanggungJawabPekerjaan?></textarea>
            <br>
            <br>
            <label for="reqName" style="width:100%; margin-left: 10px;">3.  Uraikan secara terperinci apa saja yang Anda lakukan selama ini dalam rangka menunaikan tiap-tiap tanggungjawab di atas</label>
            <textarea name="reqUraikan" id="reqUraikan" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqUraikan?></textarea>
        </table>


        <div class="judul-halaman2" style="margin-top: 30px"><i class="fa fa-pencil" aria-hidden="true"></i> VI. KONDISI Pekerjaan  </div>
        <table id="tableinfo">
            <label for="reqName" style="width:100%; margin-left: 10px;">Bagaimanakah kondisi kerja Anda (tempat, suasana, tugas) saat ini ?</label>
            <input type="hidden" name="reqKondisiKerjaId" id="reqKondisiKerjaId" value="<?=$reqKondisiKerjaId?>" />
            <input type="checkbox" name="reqBaikId" id="reqBaikId" value="1" style="margin-left: 10px;" <?if($reqBaikId==1){?>checked<?}?> /> <label> &nbsp;Baik &nbsp; </label>
            <input type="checkbox" name="reqCukupBaikId" id="reqCukupBaikId" value="1" <?if($reqCukupBaikId==1){?>checked<?}?>  /> <label> &nbsp;Cukup Baik &nbsp;  </label>
            <input type="checkbox" name="reqPerluId" id="reqPerluId" value="1" <?if($reqPerluId==1){?>checked<?}?>  /> <label> &nbsp;Perlu Perbaikan </label>
            <br>
            <br>

            <label for="reqName" style="width:100%; margin-left: 10px;">1. Ceritakan kondisi yang Anda maksud, dan usulan perbaikan yang perlu dilakukan : </label>
            <textarea name="reqKondisi" id="reqKondisi" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqKondisi?></textarea>
            <br>
            <br>
            <label for="reqName" style="width:100%; margin-left: 10px;">2.    Ada beberapa aspek / situasi / kondisi yang membuat Anda dapat optimal dalam bekerja. Jelaskan aspek apa saja yang dapat mendukung optimalisasi Anda dalam bekerja ?</label>
            <textarea name="reqAspek" id="reqAspek" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqAspek?></textarea>
        </table>

        <div class="judul-halaman2" style="margin-top: 30px"><i class="fa fa-pencil" aria-hidden="true"></i> VII. MINAT DAN HARAPAN    </div>
        <table id="tableinfo">
            <label for="reqName" style="width:100%; margin-left: 10px;">1.    Apakah yang Anda sukai dari pekerjaan / jabatan Anda sekarang ? ( kondisi, tugas-tugas, dsb ) Mengapa ?
            </label>
            <input type="hidden" name="reqMinatId" id="reqMinatId" value="<?=$reqMinatId?>" />
            <textarea name="reqSukai" id="reqSukai" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqSukai?></textarea>
            <br>
            <br>
            <label for="reqName" style="width:100%; margin-left: 10px;">2.    Apa yang paling Anda tidak sukai dari pekerjaan/jabatan Anda sekarang ? (kondisi, tugas-tugas, dsb ) Mengapa ? </label>
            <textarea name="reqTidakSukai" id="reqTidakSukai" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqTidakSukai?></textarea>
        </table>

        <div class="judul-halaman2"  style="margin-top: 30px"><i class="fa fa-pencil" aria-hidden="true"></i> VIII. Kekuatan dan Kelemahan  </div>
        <table id="tableinfo">
            <input type="hidden" name="reqKekuatanId" id="reqKekuatanId" value="<?=$reqKekuatanId?>" />
            <label for="reqName" style="width:100%; margin-left: 10px;">1.    Apakah yang menjadi kekuatan ( Strong Point ) Anda ? </label>
            <textarea name="reqKekuatan" id="reqKekuatan" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqKekuatan?></textarea>
            <br>
            <br>
            <label for="reqName" style="width:100%; margin-left: 10px;">2.    Apakah yang menjadi kelemahan ( Weak Point ) Anda ? </label>
            <textarea name="reqKelemahan" id="reqKelemahan" style="width: 100%; height: 50px;margin-left: 10px;"><?=$reqKelemahan?></textarea>
        </table>

        <div style="margin-top: 30px">
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

    function add_pendidikan(array) {
        // console.log("masuk");
        test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatJurusan[]' id='reqNonPendidikanRiwayatJurusan' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatTempat[]' id='reqNonPendidikanRiwayatTempat' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatTahun[]' id='reqNonPendidikanRiwayatTahun' value='' data-options='' style='width:100%' ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatKeterangan[]' id='reqNonPendidikanRiwayatKeterangan' value='' data-options='' style='width:100%'  ></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
        $("#tbodyObyekPendidikanNonFormula").append(test);
    }

    function Hapussparepart(ctl) {
        $(ctl).parents("tr").remove();
    }

    function add_pendidikan_formal(array) {
        $.get("pendidikan_formal_table.php?reqId=<?=$reqId?>", function (data) {
            $("#dialytable").empty();
            $("#tbodyObyekPendidikanFormula").append(data);
        });
    }

    function add_saudara(array) {
        $.get("pendidikan_keluarga.php?reqId=<?=$reqId?>", function (data) {
            $("#dialytable").empty();
            $("#tbodyObyek").append(data);
        });
    }  

    function autologin(){
        $('#infoujian2').firstVisitPopup({
            cookieName : 'homepage',
            showAgainSelector: '#show-message'
        });
    }

    $(function(){
        $('#ffLogin1').form({
            url:'../json/relog_json.php',
            onSubmit:function(){
                return $(this).form('validate');
                // console.log('masuk');
                // return false ;
            },
            success:function(data){
                if(data == "success"){
                    $.messager.alert('Info', 'Session Telah Diperbarui, Tekan Tombol X Dibagian Kanan Atas dan Klik Simpan Untuk Melanjutkan Menyimpan Data', 'info');
                    return false;
                }
                else
                {
                    $.messager.alert('Info', 'Username / Password Salah. Silahkan Login Kembali', 'info');
                    return false;
                }
            }
        }); 
    });
</script>

<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/css-ujian/popup.css">


<div class="my-welcome-message" id="infoujian2"  style="height:70%; margin-top:5%">
    <div class="konten-welcome">
    <div class="row" style="height:100%;">
         <div class="login-area">
            <div class="foto"><i class="fa fa-user fa-4x"></i></div>
            <form id="ffLogin1" method="post" novalidate enctype="multipart/form-data">
                <center><br><b>SESSION HABIS</b><br>
                Silahkan Login Kembali<center>
            <div class="form">
                <input type="text" name="reqUser" id="reqUser" class="easyui-validatebox" required placeholder="Nip / NIK Anda"/>
                <input type="password" name="reqPasswd" id="reqPasswd" class="easyui-validatebox" required placeholder="Password" />
                <input type="hidden" name="reqMode" value="submitLogin">
                <input type="submit" value="LOGIN">
            </div>
            </form>
        </div>
    </div>
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
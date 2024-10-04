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

$peserta= new Peserta();
$peserta->selectByParams(array(), -1,-1, " AND A.PESERTA_ID = ".$tempUserPelamarId);
$peserta->firstRow();
// echo $peserta->query;exit;

$tempEmail= $peserta->getField('EMAIL');
$tempKtp= $peserta->getField('KTP');
$tempUnitKerja= $peserta->getField('UNIT_KERJA_NAMA');
$tempUnitKerjaKota= $peserta->getField('UNIT_KERJA_KOTA');
$tempNama= $peserta->getField('NAMA');
$tempNIP= $peserta->getField('NIP');
$tempJenisKelamin= $peserta->getField('JENIS_KELAMIN');
$tempTempatLahir= $peserta->getField('TEMPAT_LAHIR');
// echo $tempTempatLahir;exit();
$tempTanggalLahir= dateToPageCheck($peserta->getField('TANGGAL_LAHIR'));
$tempAgama= $peserta->getField('AGAMA');
$tempGolonganRuang= $peserta->getField('GOL_RUANG');
$tempJabatan= $peserta->getField('JABATAN');
$tempAlamatRumah= $peserta->getField('ALAMAT_RUMAH');

$reqAlamatRumahKabKota= $peserta->getField('ALAMAT_RUMAH_KAB_KOTA');
$reqKota= $peserta->getField('KOTA');
// echo $reqKota;exit();

$tempAlamatRumahTelp= $peserta->getField('ALAMAT_RUMAH_TELP');
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

    setkota();
    $("#reqAlamatRumahKabKota").change(function() {
        setkota();
    });

    $("#reqKota").change(function() {
        setinfotempatlahir();
    });

    setunitkerjaeselon(1);
    $("#reqStatusSatuanKerja").change(function() {
        setunitkerjaeselon("");
    });
    // reqAlamatRumahKabKota;reqKota
    
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

function setunitkerjaeselon(reloaddata)
{
    var reqStatusSatuanKerja= "";
    reqStatusSatuanKerja= $("#reqStatusSatuanKerja").val();

    $("#statusunikerjaeselon").show();
    if(reqStatusSatuanKerja == "1"){}
    else
    {
        $("#statusunikerjaeselon").hide();
        if(reloaddata == "1"){}
        else
        {
            $("#reqUnitKerjaEselon").val("");
        }

    }

}

function setkota()
{
    var reqAlamatRumahKabKota= tempProgramId= "";
    reqAlamatRumahKabKota= $("#reqAlamatRumahKabKota").val();
    
    var id= "reqKota";
    $("#"+id+" :selected").remove(); 
    $("#"+id+" option").remove();
    var reqKota= "<?=$reqKota?>";
    
    var s_url= "../json/propinsi_combo_json.php?reqParentId="+reqAlamatRumahKabKota;
    $.ajax({'url': s_url,'success': function(data){
        var data= JSON.parse(data);
        
        if(typeof data.arrID[1] == "undefined"){}
        else
        {
            for(i=0;i<data.arrID.length; i++)
            {
                valId= data.arrID[i]; valNama= data.arrNama[i];
                //alert(valStatusRutin);
                // console.log(valId+" == "+reqKota);
                if(valId == reqKota)
                {
                    $("<option value='" + valId + "' selected='selected'>" + valNama + "</option>").appendTo("#"+id+"");
                    setinfotempatlahir();
                }
                else
                {
                    $("<option value='" + valId + "'>" + valNama + "</option>").appendTo("#"+id+"");
                }
            }
        }

    }});

    setinfotempatlahir();
}

function setinfotempatlahir()
{
    var reqAlamatRumahKabKota= reqKota= reqTempatLahir= "";
    reqAlamatRumahKabKota= $("#reqAlamatRumahKabKota option:selected").text();
    reqKota= $("#reqKota option:selected").text();
    // console.log(reqAlamatRumahKabKota);
    // console.log(reqKota);

    reqTempatLahir= reqAlamatRumahKabKota;
    if(reqKota == ""){}
    else
    reqTempatLahir= reqTempatLahir +' - '+ reqKota;

    // console.log(reqTempatLahir);
    $("#reqTempatLahir").val(reqTempatLahir);
}
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
                    <input type="text" name="reqKtp" id="reqKtp" <?=$read?> value="<?=$tempKtp?>" class="form-control easyui-validatebox" placeholder="Ketik KTP Anda..." style="width: 50%" />
                </td>
            </tr> 
            <tr>
                <td>Nama</td>
                <td>
                    <input type="text" name="reqNama" id="reqNama" <?=$read?> value="<?=$tempNama?>" class="form-control easyui-validatebox" required placeholder="Ketik Nama Lengkap Anda..." />
                </td>
            </tr>
            <tr>        
                <td>Jenis Kelamin</td>
                <td>
                    <select name="reqJenisKelamin" id="reqJenisKelamin" class="form-control" <?=$disabled?> style="width:25%">
                    <option value="L" <? if($tempJenisKelamin == "L") echo "selected"?>>Laki-laki</option>
                    <option value="P" <? if($tempJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                </select>
                </td>
            </tr>
            <tr>
                <td>Tempat / Tanggal Lahir</td>
                <td>
                    <table style="width: 100%; border: medium none ! important; margin: 0px ! important;">
                    <tr>
                        <td style="width: 30%; padding: 0px ! important;">
                            <input type="hidden" name="reqTempatLahir" id="reqTempatLahir" value="<?=$tempTempatLahir?>" />
                            <select name="reqAlamatRumahKabKota" id="reqAlamatRumahKabKota" class="form-control" <?=$disabled?>>
                                <option value=""></option>
                                <?
                                while($propinsi->nextRow())
                                {
                                    $id= $propinsi->getField("PROPINSI_ID");
                                    $namaid= $propinsi->getField("NAMA");
                                ?>
                                <option value="<?=$id?>" <? if($reqAlamatRumahKabKota == $id) echo "selected"?>><?=$namaid?></option>
                                <?
                                }
                                ?>
                            </select>
                        </td>
                        <td style="width: 30%">
                            <select name="reqKota" id="reqKota" class="form-control" <?=$disabled?>></select>
                        </td>
                        <td style="width: 18%; vertical-align: middle !important;">
                            <input type="text" name="reqTanggalLahir" id="reqTanggalLahir" <?=$read?> value="<?=$tempTanggalLahir?>" class="form-control easyui-validatebox" required data-options="validType:['dateValidPicker']" placeholder="Ketik Tgl Lahir Anda..." />
                        </td>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>
                    <select name="reqAgama" id="reqAgama" class="form-control" <?=$disabled?> style="width:25%">
                        <option value="Islam" <? if($tempAgama == "Islam") echo "selected"?>>Islam</option>
                        <option value="Protestan" <? if($tempAgama == "Protestan") echo "selected"?>>Protestan</option>
                        <option value="Katholik" <? if($tempAgama == "Katholik") echo "selected"?>>Katholik</option>
                        <option value="Hindu" <? if($tempAgama == "Hindu") echo "selected"?>>Hindu</option>
                        <option value="Budha" <? if($tempAgama == "Budha") echo "selected"?>>Budha</option>
                        <option value="Konghuchu" <? if($tempAgama == "Konghuchu") echo "selected"?>>Konghuchu</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Jabatan dan Eselon</td>
                <td>
                    <table style="width: 100%; border: medium none ! important; margin: 0px ! important;">
                    <tr>
                        <td style="width:40%; padding: 0px ! important; vertical-align: middle !important;">
                            <input type="text" name="reqJabatan" id="reqJabatan" <?=$read?> value="<?=$tempJabatan?>" class="form-control easyui-validatebox" placeholder="Ketik Jabatan Anda..." />
                        </td>
                        <td style="width:10%; vertical-align: middle !important;">
                            <select name="reqMEselonId" id="reqMEselonId" class="form-control" <?=$disabled?>>
                                <?
                                while ($eselon->nextRow()) 
                                {
                                    $id= $eselon->getField("M_ESELON_ID");
                                    $namaid= $eselon->getField("NAMA");
                                    ?>
                                    <option value="<?=$id?>" <? if($reqMEselonId == $id) echo "selected"?>><?=$namaid?></option>
                                    <?
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td>
                    <select name="reqGolonganRuang" id="reqGolonganRuang" class="form-control" <?=$disabled?> style="width:50%">
                        <?
                        while ($pangkat->nextRow()) 
                        {
                                    // $id= $pangkat->getField("M_PANGKAT_ID");
                            $id= $pangkat->getField("NAMA");
                            $namaid= $pangkat->getField("INFO_DATA");
                            ?>
                            <option value="<?=$id?>" <? if($tempGolonganRuang == $id) echo "selected"?>><?=$namaid?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipe Satuan Kerja</td>
                <td>
                    <select name="reqStatusSatuanKerja" id="reqStatusSatuanKerja" class="form-control" <?=$disabled?> style="width:50%">
                        <option value="1" <? if($reqStatusSatuanKerja == "1") echo "selected"?>>Kementerian</option>
                        <option value="" <? if($reqStatusSatuanKerja == "") echo "selected"?>>Lain-lain</option>
                    </select>
                </td>
            </tr>
            <tr id="statusunikerjaeselon">
                <td>Unit Kerja Eselon I</td>
                <td>
                    <select name="reqUnitKerjaEselon" id="reqUnitKerjaEselon" class="form-control" <?=$disabled?> style="width:50%">
                        <option value=""></option>
                        <?
                        for($x=0; $x < count($arrSatuanKerjaInternal); $x++)
                        {
                            $id= $arrSatuanKerjaInternal[$x];
                            $namaid= $arrSatuanKerjaInternal[$x];
                            ?>
                            <option value="<?=$id?>" <? if($reqUnitKerjaEselon == $id) echo "selected"?>><?=$namaid?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <!-- $arrSatuanKerjaInternal -->
            <tr>
                <td style="vertical-align: middle !important;">Unit Kerja</td>
                <td>
                    <input type="text" name="reqUnitKerja" id="reqUnitKerja" <?=$read?> value="<?=$tempUnitKerja?>" style="width:100%" class="form-control easyui-validatebox" required placeholder="Ketik Unit Kerja Anda..." />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Email</td>
                <td>
                    <input type="text" name="reqEmail" id="reqEmail" <?=$read?> value="<?=$tempEmail?>" style="width:50%" class="form-control easyui-validatebox" data-options="validType:['email']" placeholder="Ketik Email Anda..." />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: middle !important;">Kontak Pribadi (HP)</td>
                <td>
                    <input type="text" style="width:40%" name="reqAlamatRumahTelp" id="reqAlamatRumahTelp" <?=$read?> value="<?=$tempAlamatRumahTelp?>" class="form-control easyui-validatebox" required data-options="validType:'Number'" placeholder="Kontak Pribadi (HP)..." />
                </td>
            </tr>
            <tr>
                <td>Pendidikan Terakhir</td>
                <td>
                    <select name="reqPendidikanTerakhir" id="reqPendidikanTerakhir" class="form-control" <?=$disabled?> style="width:15%">
                        <?
                        while ($pendidikan->nextRow()) 
                        {
                                        // $id= $pendidikan->getField("M_PENDIDIKAN_ID");
                            $id= $namaid= $pendidikan->getField("NAMA");
                            ?>
                            <option value="<?=$id?>" <? if($tempPendidikanTerakhir == $id) echo "selected"?>><?=$namaid?></option>
                            <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Upload foto</td>
                <td>
                    <input type="file" style="font-size:10px" name="reqFotoFile" id="reqFotoFile" class="easyui-validatebox" accept="image/*" />
                </td>        
            </tr>
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
$('#reqAlamatRumahTelp').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
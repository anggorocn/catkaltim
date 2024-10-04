<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarKeluarga.php");
include_once("../WEB/classes/base-simpeg/HubunganKeluarga.php");
include_once("../WEB/classes/base-simpeg/Pendidikan.php");

$userLogin->checkLoginPelamar();

$pelamar = new Pelamar();
$pelamar_keluarga = new PelamarKeluarga();
$hubungan_keluarga = new HubunganKeluarga();
$pendidikan = new Pendidikan();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$pelamar_keluarga->selectByParams(array('PELAMAR_KELUARGA_ID'=>$reqRowId, "PELAMAR_ID" => $userLogin->userPelamarId));
$pelamar_keluarga->firstRow();

$tempHubunganKeluargaId = $pelamar_keluarga->getField('HUBUNGAN_KELUARGA_ID');
$tempStatusKawin = $pelamar_keluarga->getField('STATUS_KAWIN');
$tempJenisKelamin = $pelamar_keluarga->getField('JENIS_KELAMIN');
$tempStatusTunjangan = $pelamar_keluarga->getField('STATUS_TUNJANGAN');
$tempNama = $pelamar_keluarga->getField('NAMA');
$tempTanggalWafat = dateToPageCheck($pelamar_keluarga->getField('TANGGAL_WAFAT'));
$tempTanggalLahir = dateToPageCheck($pelamar_keluarga->getField('TANGGAL_LAHIR'));
$tempStatusTanggung = $pelamar_keluarga->getField('STATUS_TANGGUNG');
$tempTempatLahir = $pelamar_keluarga->getField('TEMPAT_LAHIR');
$tempPendidikanId = $pelamar_keluarga->getField('PENDIDIKAN_ID');
$tempPekerjaan = $pelamar_keluarga->getField('PEKERJAAN');
$tempRowId = $pelamar_keluarga->getField('PELAMAR_KELUARGA_ID');
$tempKesehatan = $pelamar_keluarga->getField('KESEHATAN_NO');
$tempKesehatanTanggal = dateToPageCheck($pelamar_keluarga->getField('KESEHATAN_TANGGAL'));
$tempKesehatanFaskes =  $pelamar_keluarga->getField('KESEHATAN_FASKES');
$tempKtpNo =  $pelamar_keluarga->getField('KTP_NO');

$tempAlamatDomisili= $pelamar_keluarga->getField("ALAMAT_DOMISILI");
$tempNoTelepon= $pelamar_keluarga->getField("NO_TELEPON");

$pendidikan->selectByParams();
$hubungan_keluarga->selectByParams();

$tempRowId = $reqRowId;

$pelamar_keluarga->selectByParams(array("PELAMAR_ID" => $userLogin->userPelamarId));
//echo $pelamar_keluarga->query;exit;
if($reqMode == "delete")
{
	$set= new PelamarKeluarga();
	$set->setField('PELAMAR_KELUARGA_ID', $reqId);
	if($set->delete())
	{
		echo "<script>document.location.href='index.php?pg=data_keluarga';</script>";
	}
	else
	{
		echo "<script>document.location.href='index.php?pg=data_keluarga';</script>";
	}
}

?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			url:'../json/data_keluarga_add.php',
			onSubmit:function(){
				if($(this).form('validate') == false && $("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_lampiran';
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				if($("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_lampiran';
				else
				{
					$("input, textarea").val(null);
					document.location.reload();
				}
			}
		});
		
	});
	
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
		minLength: {  
			//alert('asdsad');
			validator: function(value, param){  
				return value.length >= param[0];  
			},
			message: 'Total Kata Minimal {0} huruf.'
		},
		sameLength: {  
			//alert('asdsad');
			validator: function(value, param){  
				return value.length == param[0];  
			},
			message: 'Total Kata Sama dengan {0} huruf.'
		}
	});
		
</script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_keluarga"]?></div>
    <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Hubungan Keluarga</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jenis Kelamin</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            
            <?
            while($pelamar_keluarga->nextRow())
            {
            ?>
                <tr>
                    <td><?=$pelamar_keluarga->getField("HUBUNGAN_KELUARGA_NAMA")?></td>
                    <td><?=$pelamar_keluarga->getField("NAMA")?></td>
                    <td><?=$pelamar_keluarga->getField("JENIS_KELAMIN")?></td>
                    <td>
                    	<a href="?pg=data_keluarga&reqRowId=<?=$pelamar_keluarga->getField("PELAMAR_KELUARGA_ID")?>"><img src="../WEB/images/icon-edit.png"></a>  
                    	<a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=data_keluarga&reqMode=delete&reqId=<?=$pelamar_keluarga->getField("PELAMAR_KELUARGA_ID")?>' }"><img src="../WEB/images/icon-hapus.png"></a>
                    </td>
                </tr>    
            <?
            }
            ?>    
            </tbody>
        </table>
    
    </div>
    
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png"> Form Entri</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>
                        <input name="reqNama" id="reqNama" class="easyui-validatebox" size="45" required type="text" value="<?=$tempNama?>" />
                    </td>
                </tr>
                <tr>
                    <td>Tempat / Tanggal Lahir</td>
                    <td>
                        <input name="reqTempatLahir" id="reqTempatLahir" class="easyui-validatebox" type="text" value="<?=$tempTempatLahir?>" />
                         / 
                        <input id="reqTanggalLahir" name="reqTanggalLahir" data-options="validType:'date'" class="easyui-datebox" value="<?=$tempTanggalLahir?>" />
                    </td>
                </tr>
                <tr>
                    <td>Alamat Domisili</td>
                    <td>
                        <input name="reqAlamatDomisili" id="reqAlamatDomisili" class="easyui-validatebox" required type="text" style="width:100%" value="<?=$tempAlamatDomisili?>" />
                    </td>
                </tr>
                <tr>
                    <td>Hubungan Keluarga</td>
                    <td>
                        <select id="reqHubunganKeluargaId" name="reqHubunganKeluargaId" required>
                        <? 
                        while($hubungan_keluarga->nextRow())
                        {
                        ?>
                            <option value="<?=$hubungan_keluarga->getField('HUBUNGAN_KELUARGA_ID')?>" <? if($tempHubunganKeluargaId == $hubungan_keluarga->getField('HUBUNGAN_KELUARGA_ID')) echo 'selected';?>><?=$hubungan_keluarga->getField('NAMA')?></option>
                        <? 
                        }
                        ?>
                        </select>
                        &nbsp;No Tlp&nbsp;:&nbsp;
                        <input name="reqNoTelepon" id="reqNoTelepon" class="easyui-validatebox" data-options="validType:'Number'" style="width:22%" type="text" value="<?=$tempNoTelepon?>" />
                    </td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>
                        <select id="reqJenisKelamin" name="reqJenisKelamin">
                            <option value="L" <? if($tempJenisKelamin == 'L') echo 'selected';?>>L</option>
                            <option value="P" <? if($tempJenisKelamin == 'P') echo 'selected';?>>P</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Pendidikan</td>
                    <td>
                        <select id="reqPendidikanId" name="reqPendidikanId" required>
                        <? 
                        while($pendidikan->nextRow())
                        {
                        ?>
                            <option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($tempPendidikanId == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                        <? 
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>
                        <input name="reqPekerjaan" id="reqPekerjaan" class="easyui-validatebox" type="text" style="width:80%" value="<?=$tempPekerjaan?>" />
                    </td>
                </tr>
            </table>      
            <br>
            <div>
                <? if($tempRowId == ''){ $reqMode='insert'; }else{ $reqMode='update'; }?>
                <input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                <input id="reqSubmit" type="submit" value="Submit">
            </div>
        </form>
            <input type="submit" name="reqSelanjutnya" onClick="$('#reqFlagSelanjutnya').val('1'); $('#reqSubmit').click();" value="Selanjutnya">
            <input type="hidden" id="reqFlagSelanjutnya" value="">
    </div>
    
</div>
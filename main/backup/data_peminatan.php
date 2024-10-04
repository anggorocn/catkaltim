<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PelamarPeminatanJabatan.php");
include_once("../WEB/classes/base/PelamarPeminatanLokasi.php");

$userLogin->checkLoginPelamar();

$pelamar_peminatan_jabatan = new PelamarPeminatanJabatan();
$pelamar_peminatan_lokasi = new PelamarPeminatanLokasi();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$tempJabatan1 = $pelamar_peminatan_jabatan->getUrutJabatan($userLogin->userPelamarId, 1);
$tempJabatan2 = $pelamar_peminatan_jabatan->getUrutJabatan($userLogin->userPelamarId, 2);
$tempJabatan3 = $pelamar_peminatan_jabatan->getUrutJabatan($userLogin->userPelamarId, 3);

$tempLokasi1 = $pelamar_peminatan_lokasi->getUrutLokasi($userLogin->userPelamarId, 1);
$tempLokasi2 = $pelamar_peminatan_lokasi->getUrutLokasi($userLogin->userPelamarId, 2);
//echo $pelamar_pelatihan->query;

?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			url:'../json/data_pelamar_peminatan_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);
				//$.messager.alert('Info', data, 'info');
				document.location.reload();
			}
		});
		
	});
	
</script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_peminatan"]?></div>
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png"> Form Entri</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table>
            	<tr>
                    <td>Jabatan 1</td>
                    <td>:</td>
                    <td>
                        <input id="reqJabatan" class="easyui-combotree" name="reqJabatan[1]" data-options="panelHeight:'200',url:'../json/jabatan_combo_json.php?reqCabang=1&reqKelompok=O'" style="width:400px;" value="<?=$tempJabatan1?>" />
                    </td>
                </tr>
                <tr>    
                    <td>Jabatan 2</td>
                    <td>:</td>
                    <td>
                        <input id="reqJabatan" class="easyui-combotree" name="reqJabatan[2]" data-options="panelHeight:'200',url:'../json/jabatan_combo_json.php?reqCabang=1&reqKelompok=O'" style="width:400px;" value="<?=$tempJabatan2?>" />
                    </td>
                </tr>
                <tr>
                    <td>Jabatan 3</td>
                    <td>:</td>
                    <td>
                        <input id="reqJabatan" class="easyui-combotree" name="reqJabatan[3]" data-options="panelHeight:'200',url:'../json/jabatan_combo_json.php?reqCabang=1&reqKelompok=O'" style="width:400px;" value="<?=$tempJabatan3?>" />
                    </td>
                </tr>
                <tr>
                    <td>Lokasi 1</td>
                    <td>:</td>
                    <td>
                        <input id="reqCabangP3Id" class="easyui-combotree" name="reqCabangP3Id[1]" data-options="panelHeight:'200',url:'../json/cabang_p3_combo_json.php', method: 'get', valueField:'id',  textField:'text'" style="width:300px;" value="<?=$tempLokasi1?>" />
                    </td>
                </tr>
                <tr>
                    <td>Lokasi 2</td>
                    <td>:</td>
                    <td>
                        <input id="reqCabangP3Id" class="easyui-combotree" name="reqCabangP3Id[2]" data-options="panelHeight:'200',url:'../json/cabang_p3_combo_json.php', method: 'get', valueField:'id',  textField:'text'" style="width:300px;" value="<?=$tempLokasi2?>" />
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
    </div>
    
</div>
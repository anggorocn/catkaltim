<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPengalaman.php");

$userLogin->checkLoginPelamar();

$pelamar = new Pelamar();
$pelamar_pengalaman = new PelamarPengalaman();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$pelamar_pengalaman->selectByParams(array('PELAMAR_PENGALAMAN_ID'=>$reqId, "PELAMAR_ID" => $userLogin->userPelamarId));
$pelamar_pengalaman->firstRow();
//echo $pelamar_pengalaman->query;

$tempPerusahaan = $pelamar_pengalaman->getField('PERUSAHAAN');
$tempDurasi = $pelamar_pengalaman->getField('DURASI');
$tempTahun = $pelamar_pengalaman->getField('TAHUN');
$tempJabatan = $pelamar_pengalaman->getField('JABATAN');
$tempTanggalMasuk = dateToPageCheck($pelamar_pengalaman->getField('TANGGAL_MASUK'));
$tempRowId = $pelamar_pengalaman->getField('PELAMAR_PENGALAMAN_ID');

$pelamar_pengalaman->selectByParams(array("PELAMAR_ID" => $userLogin->userPelamarId));

if($reqMode == "delete")
{
	$set= new PelamarPengalaman();
	$set->setField('PELAMAR_PENGALAMAN_ID', $reqId);
	if($set->delete())
	{
		echo "<script>document.location.href='?pg=data_pengalaman';</script>";
	}
	else
	{
		echo "<script>document.location.href='?pg=data_pengalaman';</script>";
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
			url:'../json/data_pengalaman_add.php',
			onSubmit:function(){
				if($(this).form('validate') == false && $("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_sertifikat';
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				if($("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_sertifikat';
				else
				{
					$("input, textarea").val(null);
					document.location.reload();
				}
			}
		});
		
	});
	
</script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_pengalaman"]?></div>
    <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Perusahaan</th>
                    <th scope="col">Durasi (tahun)</th>
                    <th scope="col">Durasi (bulan)</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
				<?
                while($pelamar_pengalaman->nextRow())
                {
                ?>
                    <tr>
                        <td><?=$pelamar_pengalaman->getField("JABATAN")?></td>
                        <td><?=$pelamar_pengalaman->getField("PERUSAHAAN")?></td>
                        <td><?=$pelamar_pengalaman->getField("TAHUN")?></td>
                        <td><?=$pelamar_pengalaman->getField("DURASI")?></td>
                        <!--<td><?=dateToPageCheck($pelamar_pengalaman->getField("TANGGAL_MASUK"))?></td>-->
                        <td>
                			<a href="?pg=data_pengalaman&reqId=<?=$pelamar_pengalaman->getField("PELAMAR_PENGALAMAN_ID")?>"><img src="../WEB/images/icon-edit.png"></a>               
                        	<a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=data_pengalaman&reqMode=delete&reqId=<?=$pelamar_pengalaman->getField("PELAMAR_PENGALAMAN_ID")?>' }"><img src="../WEB/images/icon-hapus.png"></a>
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
                    <td>Jabatan</td>
                    <td>
                        <input id="reqJabatan" name="reqJabatan" type="text" class="easyui-validatebox" style="width:80%" value="<?=$tempJabatan?>" required />
                    </td>
                </tr>
                <tr>
                    <td>Perusahaan</td>
                    <td>
                        <input id="reqPerusahaan" name="reqPerusahaan" class="easyui-validatebox" style="width:100%"   value="<?=$tempPerusahaan?>" required></input>
                    </td>
                </tr>
                <tr>
                    <td>Durasi</td>
                    <td>
                        <input id="reqTahun" name="reqTahun" class="easyui-validatebox" style="width:10%" value="<?=$tempTahun?>" OnFocus="FormatAngka('reqTahun')" OnKeyUp="CekNumber('reqTahun')" maxlength="2" ></input> tahun
                        <input id="reqDurasi" name="reqDurasi" class="easyui-validatebox" style="width:10%" value="<?=$tempDurasi?>" OnFocus="FormatAngka('reqDurasi')" OnKeyUp="CekNumber('reqDurasi')" maxlength="2"></input> bulan
                    </td>
                </tr>
                <!--<tr>
                    <td>Tanggal Masuk</td>
                    <td>
                        <input id="reqTanggalMasuk" name="reqTanggalMasuk" class="easyui-datebox" data-options="validType:'date'"  value="<?=$tempTanggalMasuk?>"></input>
                    </td>
                </tr>-->
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
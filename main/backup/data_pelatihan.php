<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPelatihan.php");

$userLogin->checkLoginPelamar();

$pelamar = new Pelamar();
$pelamar_pelatihan = new PelamarPelatihan();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$pelamar_pelatihan->selectByParams(array('PELAMAR_PELATIHAN_ID'=>$reqId, "PELAMAR_ID" => $userLogin->userPelamarId));
$pelamar_pelatihan->firstRow();
//echo $pelamar_pelatihan->query;

$tempJenis = $pelamar_pelatihan->getField('JENIS');
$tempJumlah = $pelamar_pelatihan->getField('JUMLAH');
$tempWaktu = $pelamar_pelatihan->getField('WAKTU');
$tempPelatih = $pelamar_pelatihan->getField('PELATIH');
$tempRowId = $pelamar_pelatihan->getField('PELAMAR_PELATIHAN_ID');
$tempTahun = $pelamar_pelatihan->getField('TAHUN');
$tempNomorLisensi = $pelamar_pelatihan->getField('NOMOR_LISENSI');
$tempJenisLisensi = $pelamar_pelatihan->getField('JENIS_LISENSI');
$tempTanggalMulai = dateToPageCheck($pelamar_pelatihan->getField('TANGGAL_MULAI'));
$tempTanggalSelesai = dateToPageCheck($pelamar_pelatihan->getField('TANGGAL_SELESAI'));

$pelamar_pelatihan->selectByParams(array("PELAMAR_ID" => $userLogin->userPelamarId));

if($reqMode == "delete")
{
	$set= new PelamarPelatihan();
	$set->setField('PELAMAR_PELATIHAN_ID', $reqId);
	if($set->delete())
	{
		echo "<script>document.location.href='?pg=data_pelatihan';</script>";
	}
	else
	{
		echo "<script>document.location.href='?pg=data_pelatihan';</script>";
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
			url:'../json/data_pelatihan_add.php',
			onSubmit:function(){
				if($(this).form('validate') == false && $("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_pengalaman';
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				if($("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_pengalaman';
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

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_pelatihan"]?></div>
    <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">Nama Pelatihan</th>
                <th scope="col">Nomor Lisensi</th>
                <th scope="col">Jenis Lisensi</th>
                <th scope="col">Tanggal Mulai</th>
                <th scope="col">Tanggal Selesai</th>
                <th scope="col">Lama (hari)</th>
                <th scope="col">Tahun</th>
                <?php /*?><th scope="col">Instruktur</th><?php */?>
                <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?
            while($pelamar_pelatihan->nextRow())
            {
            ?>
                <tr>
                    <td><?=$pelamar_pelatihan->getField("JENIS")?></td>
                    <td><?=$pelamar_pelatihan->getField("NOMOR_LISENSI")?></td>
                    <td><?=$pelamar_pelatihan->getField("JENIS_LISENSI")?></td>
                    <td><?=dateToPageCheck($pelamar_pelatihan->getField("TANGGAL_MULAI"))?></td>
                    <td><?=dateToPageCheck($pelamar_pelatihan->getField("TANGGAL_SELESAI"))?></td>
                    <td><?=$pelamar_pelatihan->getField("WAKTU")?></td>
                    <td><?=$pelamar_pelatihan->getField("TAHUN")?></td>
                    <?php /*?><td><?=$pelamar_pelatihan->getField("PELATIH")?></td><?php */?>
                    <td>
                	<a href="?pg=data_pelatihan&reqId=<?=$pelamar_pelatihan->getField("PELAMAR_PELATIHAN_ID")?>"><img src="../WEB/images/icon-edit.png"></a>                    
                    <a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=data_pelatihan&reqMode=delete&reqId=<?=$pelamar_pelatihan->getField("PELAMAR_PELATIHAN_ID")?>' }"><img src="../WEB/images/icon-hapus.png"></a></td>
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
                    <td>Nama Pelatihan</td>
                    <td>
                        <input id="reqJenis" name="reqJenis" class="easyui-validatebox" style="width:80%"   value="<?=$tempJenis?>" required></input>
                    </td>
                </tr>
                <tr>
                	<td>Nomor Lisensi</td>
                	<td>
                        <input id="reqNomorLisensi" name="reqNomorLisensi" class="easyui-validatebox" style="width:80%"   value="<?=$tempNomorLisensi?>" ></input>
                    </td>
                </tr>
                <tr>
                	<td>Jenis Lisensi</td>
                	<td>
                        <select name="reqJenisLisensi">
                        	<option value="BASIC" <? if($tempJenisLisensi=="BASIC") echo 'selected'?>>Basic</option>
                        	<option value="JUNIOR" <? if($tempJenisLisensi=="JUNIOR") echo 'selected'?>>Junior</option>
                        	<option value="SENIOR" <? if($tempJenisLisensi=="SENIOR") echo 'selected'?>>Senior</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td>Masa Berlaku</td>
                    <td>
                    	<input type="text" class="easyui-datebox" name="reqTanggalMulai" id="reqTanggalMulai" style="width:100px;" value="<?=$tempTanggalMulai?>" />
                        &nbsp;s/d&nbsp;
                        <input type="text" class="easyui-datebox" name="reqTanggalSelesai" id="reqTanggalSelesai" style="width:100px;" value="<?=$tempTanggalSelesai?>" />
                    </td>
                </tr>
                <tr>
                    <td>Lama</td>
                    <td>
                        <input type="text" name="reqWaktu" class="easyui-validatebox" required style="width:10%" value="<?=$tempWaktu?>" /> hari
                    </td>
                </tr>
                <tr>
                    <td>Tahun</td>
                    <td>
                        <select name="reqTahun" id="reqTahun">
							<? 
                            for($i=date("Y")-25; $i < date("Y")+1; $i++)
                            {
                            ?>
                            <option value="<?=$i?>" <? if($i == $tempTahun) echo 'selected'?>><?=$i?></option>
                            <?
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <?php /*?><tr>
                    <td>Instruktur</td>
                    <td>
                        <input id="reqPelatih" name="reqPelatih" class="easyui-validatebox" style="width:50%" value="<?=$tempPelatih?>"></input>
                    </td>
                </tr><?php */?>
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
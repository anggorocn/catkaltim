<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/base/LowonganDokumen.php");
include_once("../WEB/classes/base/Pelamar.php");

$reqId = httpFilterGet("reqId");

$lowongan = new Lowongan();
$lowongan->selectByParamsInformasi(array("LOWONGAN_ID" => $reqId));
$lowongan->firstRow();

$lowongan_dokumen = new LowonganDokumen();
$ada = $lowongan_dokumen->getCountByParams(array("A.LOWONGAN_ID" => $reqId));

$daftar_entrian = new Pelamar();
$kurang_entri = $daftar_entrian->getCountByParamsDaftarEntrian(array("PELAMAR_ID" => $userLogin->userPelamarId), " AND WAJIB_ISI = '1' AND ADA = 0 ");

$arrData= array("Saya telah membaca seluruh persyaratan dan ketentuan lowongan yang telah saya pilih.",
"Saya memiliki kinerja sesuai dengan ketentuan yang telah dipersyaratkan.",
"Saya tidak pernah terlibat masalah narkoba, pidana dan keuangan.",
"Saya Tidak bertindik dan tidak bertato.",
"Saya akan menyertakan bukti yang diperlukan dari seluruh pernyataan di atas sebagaimana yang dipersyaratkan oleh panitia seleksi.");

?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>

<script type="text/javascript">
	$(function(){
		setSimpan();
		$('#ff').form({
			url:'../json/lamaran.php',
			onSubmit:function(){
				reqJumlahInfo= $("#reqJumlahInfo").val();
				var jumlahData= "<?=count($arrData)?>"
				
				if(reqJumlahInfo == jumlahData)
				{
					return $(this).form('validate');
				}
				else
				{
					$.messager.alert('Info', "Pastikan semua pernyataan sudah anda setujui terlebih dahulu", 'info');
					return false;
				}
			},
			success:function(data){
				if(data == '')
				{
					<?
					if($ada > 0)
					{
					?>
						document.location.href = '?pg=lamaran_dokumen&reqId=<?=$reqId?>';
					<?
					}
					else
					{
					?>
						document.location.href = '?pg=daftar_lamaran&reqKonfirmasi=<?=md5($reqId)?>';
					<?
					}
					?>
				}
				else
					$.messager.alert('Perhatian', data, 'warning');
			}
		});
		
		$('input[id^="reqInfo"]').click(function() {
			setSimpan();
		});
	
	});
	
	function setSimpan()
	{
		var reqJumlahInfo= "";
		reqJumlahInfo= 0;
		$('input[id^="reqInfo"]').each(function(){
			var id= $(this).attr('id');
			id= id.replace("reqInfo", "")
			
			if($(this).prop('checked'))
			{
				reqJumlahInfo= parseInt(reqJumlahInfo) + 1;
			}
	   });
		
		$("#reqJumlahInfo").val(reqJumlahInfo);
		var jumlahData= "<?=count($arrData)?>"
		
		if(reqJumlahInfo == jumlahData)
		{
			$("#reqSimpanInfo").show();
			$("#reqSimpan").show();
		}
		else
		{
			$("#reqSimpanInfo").hide();
			$("#reqSimpan").hide();
		}
		
		$("#reqSimpanInfo").show();
		$("#reqSimpan").show();
	}
</script>
<div class="col-lg-8">
	
    <div id="judul-halaman">Kirim Lamaran > Halaman Pernyataan</div>

    <div class="data-monitoring">
        <table class="table table-hover">
            <tbody>
            <tr>
            	<td style="width:20%">Kode</td>
            	<td style="width:2%">:</td>
                <td><?=$lowongan->getField("KODE")?></td>
            </tr>
            <tr>
            	<td>Nama Jabatan</td>
            	<td>:</td>
                <td><?=$lowongan->getField("JABATAN")?></td>
            </tr>
            <tr>
            	<td>Penempatan</td>
            	<td>:</td>
                <td><?=$lowongan->getField("PENEMPATAN")?></td>
            </tr>
            </tbody>
        </table>
    
    </div>
        
    <div id="pendaftaran">
    <?
    if($kurang_entri > 0)
	{
		$daftar_entrian->selectByParamsDaftarEntrian(array("PELAMAR_ID" => $userLogin->userPelamarId, "WAJIB_ISI" => "1"), -1, -1);
	?>
    	Untuk dapat melanjutkan proses lamaran ini anda harus melengkapi data sebagai berikut.

        <div class="data-monitoring">
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col" style="text-align:center">Nama</th>
                    <th scope="col" style="text-align:center">Status Entri</th>
                    <th scope="col" style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?
                while($daftar_entrian->nextRow())
                {
                ?>
                    <tr>
                        <td><?=$daftar_entrian->getField("NAMA")?></td>
                        <td align="center">
							<?
                            if($daftar_entrian->getField("ADA") > 0)
							{
							?>
                            	<img src="../WEB/images/icon-sudah.png">
                            <?
							}
							else
							{
							?>
                            	<img src="../WEB/images/icon-belum.png">
                            <?
							}
							?>
                        </td>
                        <td align="center">
							<?
                            if($daftar_entrian->getField("ADA") > 0)
							{
							?>

                            <?
							}
							else
							{
							?>	
			                    <a href="?pg=<?=$daftar_entrian->getField("LINK_FILE")?>"><img src="../WEB/images/icon-lengkapi.png"> Lengkapi Berkas</a>                            
							<?
							}
							?>
                        </td>
                    </tr>    
                <?
                }
                ?>
                </tbody>
            </table>
        </div>        
            Silahkan lengkapi berkas terlebih dahulu.
    <?
	}
	else
	{
	?>
    	Untuk mendaftar dalam proses rekrutmen dan seleksi, maka saya menyatakan bahwa:
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table>
        	<input type="hidden" name="reqJumlahInfo" id="reqJumlahInfo" value="0" />
        	<?
			for($i_data=0; $i_data < count($arrData); $i_data++)
			{
            ?>
        	<tr>
            	<td><input type="checkbox" name="reqInfo<?=$i_data?>" value="1" id="reqInfo<?=$i_data?>" /></td>
                <td><?=$arrData[$i_data]?></td>
            </tr>
            <?
			}
            ?>
            <tr>
            	<td colspan="2" style="text-align:justify">
                Apabila di kemudian dari ternyata diketahui bahwa data dan informasi yang saya berikan pada proses rekrutmen dan seleksi ini tidak benar dan/atau tidak dapat dibuktikan, maka demi tanggung jawab moral sebagai calon pegawai/pegawai, saya bersedia mengundurkan diri dari seluruh proses rekrutmen dan seleksi maupun mengundurkan diri dari perusahaan saya bekerja.
                </td>
            </tr>
            <tr id="reqSimpanInfo" style="display:none">
            	<td colspan="2"><p class="keterangan">Dengan klik tombol SETUJU, Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Rekrutmen dan Seleksi untuk menggunakan data administrasi dalam proses rekrutmen dan seleksi pegawai PT. Angkasa Pura Suport (APS)</p></td>
            </tr>
            <tr id="reqSimpan" style="display:none">
            	<td colspan="2">
                	<input name="reqSubmit" type="hidden" value="update" />
                	<input name="reqId" type="hidden" value="<?=$reqId?>" />
                    
                	<input type="submit" value="Setuju" />
                </td>
            </tr>          
        </table>
        </form>
    <?
	}
	?>
    </div>
    
    
</div>
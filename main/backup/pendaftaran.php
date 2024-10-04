<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Lowongan.php");

$set= new Pelamar();

$arrData= array("Saya telah membaca seluruh informasi dan ketentuan yang terdapat pada situs web ini.",
"Saya tidak pernah terlibat masalah narkoba, pidana dan keuangan.",
"Saya akan menyertakan bukti yang diperlukan dari seluruh pernyataan di atas sebagaimana yang dipersyaratkan oleh panitia seleksi<br/><br/>Apabila di kemudian dari ternyata diketahui bahwa data dan informasi yang saya berikan pada proses rekrutmen dan seleksi ini tidak benar dan/atau tidak dapat dibuktikan, maka demi tanggung jawab moral sebagai calon pegawai/pegawai, saya bersedia mengundurkan diri dari seluruh proses rekrutmen dan seleksi maupun mengundurkan diri dari Perusahaan saya bekerja.");

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
			url:'../json/pendaftaran.php',
			onSubmit:function(){
				reqJumlahInfo= $("#reqJumlahInfo").val();
				var jumlahData= "<?=count($arrData)?>"
				
				if(reqJumlahInfo == jumlahData)
				{
					return $(this).form('validate');
				}
				else
				{
					$.messager.alert('Info', "Pastikan semua pernyataan sudah tercentang, terlebih dahulu", 'info');
					return false;
				}
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				document.location.href = '?pg=data_pribadi';
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
	
    <div id="judul-halaman">Pendaftaran > Halaman Pernyataan</div>
    
    <div id="pendaftaran">
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
            <tr id="reqSimpanInfo" style="display:none">
            	<td colspan="2"><p class="keterangan">Dengan klik tombol SETUJU, Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Rekrutmen dan Seleksi untuk menggunakan data administrasi dalam proses rekrutmen dan seleksi pegawai PT. Angkasa Pura Suport (APS)</p></td>
            </tr>
            <tr id="reqSimpan" style="display:none">
            	<td colspan="2">
                	<input name="reqSubmit" type="hidden" value="update" />
                	<input type="submit" value="Setuju" />
                </td>
            </tr>          
        </table>
        </form>
    </div>
    
    
</div>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pegawai.php");

if($userLogin->ujianUid == "")
{
	if($pg == "" || $pg == "home"){}
	else
	{
		echo '<script language="javascript">';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}
}

$tempPegawaiId= $userLogin->pegawaiId;

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
$set= new Pegawai();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
$set->firstRow();
$tempNipBaru= $set->getField("NIP_BARU");
$tempNama= $set->getField("NAMA");
$tempJenisKelamin= $set->getField("JENIS_KELAMIN");
$tempJenisKelaminNama= $set->getField("JENIS_KELAMIN_NAMA");
$tempTempatLahir= $set->getField("TEMPAT_LAHIR");
$tempTglLahir= dateToPageCheck($set->getField("TGL_LAHIR"));
$tempJabatanId= $set->getField("JABATAN_ID");
$tempJabatan= $set->getField("JABATAN");
$tempPangkatId= $set->getField("PANGKAT_ID");
$tempPangkat= $set->getField("PANGKAT");
$tempTmtPangkat= dateToPageCheck($set->getField("TMT_PANGKAT"));
$tempPendidikan= $set->getField("PENDIDIKAN");
$tempLokasiKerja= $set->getField("LOKASI_KERJA");
$tempEmail= $set->getField("EMAIL");
//PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, , , ,, JENIS_KELAMIN, , , TMT_PANGKAT, , , PANGKAT_ID, JABATAN_ID, TELP, 
unset($set);
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<link rel="stylesheet" href="../WEB/lib-ujian/autokomplit/jquery-ui.css">
<script src="../WEB/lib-ujian/autokomplit/jquery-ui.min.js"></script>  
<style>
	.ui-autocomplete {
		max-height: 200px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		font-size:11px;
		overflow-x: hidden;
	}
	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 200px;
	}
</style>

<!-- AUTO KOMPLIT -->
<script type="text/javascript" src="../WEB/lib-ujian/easyui/easyloader.js"></script>   
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.form.js"></script>  
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.linkbutton.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.draggable.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.resizable.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.panel.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.window.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.progressbar.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.messager.js"></script>      
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.tooltip.js"></script>  
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.validatebox.js"></script>  
<script type="text/javascript" src="../WEB/lib-ujian/easyui/plugins/jquery.combo.js"></script>

<script type="text/javascript" src="../WEB/lib-ujian/easyui/kalender-easyui.js"></script>

<script language="javascript">
	$(function() {
		$('#ff').form({
			url:'../json-main/lengkapi_data.php',
			onSubmit:function(){
				//alert($(this).form('validate'));
				var f = this;
				var opts = $.data(this, 'form').options;
				if($(this).form('validate') == false){
					return false;
				}
				//var reqDiklatId= $("#reqDiklatId option:selected").text();
				$.messager.confirm('Confirm','Apakah Anda yakin ubah data ?',function(r){
					if (r){
						var onSubmit = opts.onSubmit;
						opts.onSubmit = function(){};
						$(f).form('submit');
						opts.onSubmit = onSubmit;
					}
				})
				return false;
				//return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				data= data.split("-");
				kondisiInfo= data[0];
				info= data[1];
				$.messager.alert('Info', info, 'info');
				
				if(kondisiInfo == 1)
				{
					document.location.href = 'index.php?pg=lengkapi_data'
				}
			}
		});
		
		$("#reqJabatan, #reqPangkat").autocomplete({ 
				source:function(request, response){
					var id= this.element.attr('id');
					var replaceAnakId= replaceAnak= urlAjax= "";
					
					if (id.indexOf('reqJabatan') !== -1)
					{
						var element= id.split('reqJabatan');
						var indexId= "reqJabatanId"+element[1];
						urlAjax= "../json-main/jabatan_auto_combo_json.php";
						replaceAnakId= "reqKecamatanKirimId";
						replaceAnak= "reqKecamatanKirim";
					}
					else if (id.indexOf('reqPangkat') !== -1)
					{
						var element= id.split('reqPangkat');
						var indexId= "reqPangkatId"+element[1];
						urlAjax= "../json-main/pangkat_auto_combo_json.php";
						replaceAnakId= "reqKecamatanKirimId";
						replaceAnak= "reqKecamatanKirim";
					}
					var field= "";
					
					field= "NAMA_ORDER";
					
					$.ajax({
						url: urlAjax,
						type: "GET",
						dataType: "json",
						data: { term: request.term },
						success: function(responseData){
							$("#"+indexId).val("").trigger('change');
							if(replaceAnakId == ""){}
							else
							{
							$("#"+replaceAnakId).val("").trigger('change');
							$("#"+replaceAnak).val("").trigger('change');
							}
							
							if(responseData == null)
							{
								response(null);
							}
							else
							{
								var array = responseData.map(function(element) {
									return {desc: element['desc'], id: element['id'], label: element['label']};
								});
								response(array);
							}
						}
					})
				},
				select: function (event, ui) 
				{ 
					var id= $(this).attr('id');
					var replaceAnakId= replaceAnak= "";
					
					if (id.indexOf('reqJabatan') !== -1)
					{
						var element= id.split('reqJabatan');
						var indexId= "reqJabatanId"+element[1];
					}
					else if (id.indexOf('reqPangkat') !== -1)
					{
						var element= id.split('reqPangkat');
						var indexId= "reqPangkatId"+element[1];
					}
					$("#"+indexId).val(ui.item.id).trigger('change');
				}, 
				//minLength:3,
				autoFocus: true
			}).autocomplete( "instance" )._renderItem = function( ul, item ) {
				return $( "<li>" )
			  .append( "<a>" + item.desc + "</a>" )
			  .appendTo( ul );
		};
		
	});

</script>

<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Edit / Lengkapi Data Pribadi</div>
        </div>
    </div>
    
	<div class="row">
    	<div class="col-md-4">
        	<div class="area-foto-user"><img src="../WEB/images/foto-user.png"></div>
        </div>
        <div class="col-md-8">
        	<div class="area-data-profil">
            	<form id="ff" method="post" enctype="multipart/form-data">
                <input type="hidden" name="reqPegawaiId" value="<?=$tempPegawaiId?>" />
            	<table>
                	<tr>
                    	<td>NIP</td>
                        <td>:</td>
                        <td>
                        	<input style="width:40%" type="text" class="easyui-validatebox" required placeholder="Ketik Nip Anda..." name="reqNipBaru" id="reqNipBaru" value="<?=$tempNipBaru?>" />
                        </td>
                    </tr>
                	<tr>
                    	<td>Nama</td>
                        <td>:</td>
                        <td>
                        	<input style="width:100%" type="text" class="easyui-validatebox" required placeholder="Ketik Nama Anda..." name="reqNama" id="reqNama" value="<?=$tempNama?>" />
                        </td>
                    </tr>
                	<tr>
                    	<td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>
                        <select name="reqJenisKelamin" id="reqJenisKelamin">
                        	<option value="P" <? if($tempJenisKelamin == "P") echo "selected"?>>Pria</option>
                            <option value="W" <? if($tempJenisKelamin == "W") echo "selected"?>>Wanita</option>
                        </select>
                        </td>
                    </tr>
                	<tr>
                    	<td>Tempat Lahir</td>
                        <td>:</td>
                        <td>
                        	<input style="width:50%" type="text" class="easyui-validatebox" required placeholder="Ketik Tempat Lahir Anda..." name="reqTempatLahir" id="reqTempatLahir" value="<?=$tempTempatLahir?>" />
                            &nbsp;&nbsp;Tanggal Lahir&nbsp;&nbsp;
                            <input id="reqTglLahir" name="reqTglLahir" class="easyui-datebox" data-options="validType:'date'" style="width:100px" value="<?=$tempTglLahir?>" />
                        </td>
                    </tr>
                	<?php /*?><tr>
                    	<td>Tanggal Lahir</td>
                        <td>:</td>
                        <td>
                        	<input id="reqTglLahir" name="reqTglLahir" class="easyui-datebox" data-options="validType:'date'" style="width:100px" value="<?=$tempTglLahir?>" />
                        </td>
                    </tr><?php */?>
                	<tr>
                    	<td>Jabatan</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" name="reqJabatanId" id="reqJabatanId" value="<?=$tempJabatanId?>" /> 
                            <input style="width:60%" type="text" class="easyui-validatebox" name="reqJabatan" id="reqJabatan" 
                            data-options="validType:['sameAutoLoder[\'reqJabatan\', \'\']']"
                            value="<?=$tempJabatan?>" required />
                        </td>
                    </tr>
                	<tr>
                    	<td>Pangkat</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" name="reqPangkatId" id="reqPangkatId" value="<?=$tempPangkatId?>" /> 
                            <input style="width:10%" type="text" class="easyui-validatebox" name="reqPangkat" id="reqPangkat" 
                            data-options="validType:['sameAutoLoder[\'reqPangkat\', \'\']']"
                            value="<?=$tempPangkat?>" required />
                            Tmt Pangkat
                            <input id="reqTmtPangkat" name="reqTmtPangkat" class="easyui-datebox" data-options="validType:'date'" style="width:100px" value="<?=$tempTmtPangkat?>" />
                        </td>
                    </tr>
                    <?php /*?>
                    <tr>
                    	<td>Alamat</td>
                        <td>:</td>
                        <td><textarea></textarea></td>
                    </tr><?php */?>
                </table>
                </form>
                
                <div class="lengkapi-data"><a href="#" onclick="$('#ff').submit();">Simpan</a></div>
            </div>
        </div>
        
        <div class="area-prev-next">
            <div class="prev"><a href="?pg=tata_cara"><i class="fa fa-chevron-left"></i></a></div>
            <div class="next"><a href="?pg=form_persetujuan"><i class="fa fa-chevron-right"></i></a></div>
        </div>
    
    </div>
</div>

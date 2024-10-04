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

$tempPropinsiId= $set->getField("PROPINSI_ID");
$tempPropinsi= $set->getField("PROPINSI_NAMA");
$tempKabupatenId= $set->getField("KABUPATEN_ID");
$tempKabupaten= $set->getField("KABUPATEN_NAMA");
$tempKecamatanId= $set->getField("KECAMATAN_ID");
$tempKecamatan= $set->getField("KECAMATAN_NAMA");
$tempDesaInfoId= $set->getField("DESA_INFO_ID");
//PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, , , ,, JENIS_KELAMIN, , , TMT_PANGKAT, , , PANGKAT_ID, JABATAN_ID, TELP, 
unset($set);
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<?php /*?><script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery-1.6.1.min.js"></script><?php */?>
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery-1.8.0.min.js"></script>
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

<link href="../WEB/lib-ujian/multipleselect/multiple-select.css" rel="stylesheet"/>
<script src="../WEB/lib-ujian/multipleselect/jquery.multiple.select.js"></script>
    
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
		
		$("#reqPropinsi, #reqKabupaten, #reqKecamatan, #reqJabatan, #reqPangkat").autocomplete({ 
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
					else if (id.indexOf('reqPropinsi') !== -1)
					{
						var element= id.split('reqPropinsi');
						var indexId= "reqPropinsiId"+element[1];
						urlAjax= "../json-main/propinsi_auto_combo_json.php";
						replaceAnakId= "reqKabupatenId";
						replaceAnak= "reqKabupaten";
						$("#reqKabupatenId, #reqKecamatanId, #reqDesaId").val("");
						$("#reqKabupaten, #reqKecamatan, #reqDesa").val("");
					}
					else if (id.indexOf('reqKabupaten') !== -1)
					{
						var element= id.split('reqKabupaten');
						var indexId= "reqKabupatenId"+element[1];
						var idPropVal= $("#reqPropinsiId").val();
						urlAjax= "../json-main/kabupaten_auto_combo_json.php?reqPropinsiId="+idPropVal;
						replaceAnakId= "reqKecamatanId";
						replaceAnak= "reqKecamatan";
						$("#reqKecamatanId, #reqDesaId").val("");
						$("#reqKecamatan, #reqDesa").val("");
					}
					else if (id.indexOf('reqKecamatan') !== -1)
					{
						var element= id.split('reqKecamatan');
						var indexId= "reqKecamatanId"+element[1];
						var idPropVal= $("#reqPropinsiId").val();
						var idKabVal= $("#reqKabupatenId").val();
						urlAjax= "../json-main/kecamatan_auto_combo_json.php?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal;
						replaceAnakId= "reqDesaId";
						replaceAnak= "reqDesa";
						$("#reqDesaId").val("");
						$("#reqDesa").val("");
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
					else if (id.indexOf('reqPropinsi') !== -1)
					{
						var element= id.split('reqPropinsi');
						var indexId= "reqPropinsiId"+element[1];
						replaceAnakId= "reqKabupatenId";
						replaceAnak= "reqKabupaten";
						$("#reqKabupatenId, #reqKecamatanId").val("");
						$("#reqKabupaten, #reqKecamatan").val("");
					}
					else if (id.indexOf('reqKabupaten') !== -1)
					{
						var element= id.split('reqKabupaten');
						var indexId= "reqKabupatenId"+element[1];
						replaceAnakId= "reqKecamatanId";
						replaceAnak= "reqKecamatan";
						$("#reqKecamatanId").val("");
						$("#reqKecamatan").val("");
					}
					else if (id.indexOf('reqKecamatan') !== -1)
					{
						var element= id.split('reqKecamatan');
						var indexId= "reqKecamatanId"+element[1];
						replaceAnakId= "reqDesaId";
						replaceAnak= "reqDesa";
						//$("#reqDesaId").val("");
						//$("#reqDesa").val("");
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
                <input type="hidden" name="reqNipBaru" value="<?=$tempNipBaru?>" />
                <input type="hidden" name="reqJenisKelamin" value="<?=$tempJenisKelamin?>" />
                <input type="hidden" name="reqTempatLahir" value="<?=$tempTempatLahir?>" />
                <input type="hidden" name="reqTglLahir" value="<?=$tempTglLahir?>" />
            	<table>
                	<tr>
                    	<td>NIP</td>
                        <td>:</td>
                        <td>
                        	<?=$tempNipBaru?>
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
                    	<td>Jabatan</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" name="reqJabatanId" id="reqJabatanId" value="<?=$tempJabatanId?>" /> 
                            <input style="width:35%" type="text" class="easyui-validatebox" name="reqJabatan" id="reqJabatan" 
                            data-options="validType:['sameAutoLoder[\'reqJabatan\', \'\']']"
                            value="<?=$tempJabatan?>" required />
                            &nbsp;Pangkat&nbsp;:&nbsp;
                            <input type="hidden" name="reqPangkatId" id="reqPangkatId" value="<?=$tempPangkatId?>" /> 
                            <input style="width:10%" type="text" class="easyui-validatebox" name="reqPangkat" id="reqPangkat" 
                            data-options="validType:['sameAutoLoder[\'reqPangkat\', \'\']']"
                            value="<?=$tempPangkat?>" required />
                            &nbsp;Tmt Pangkat&nbsp;:&nbsp;
                            <input id="reqTmtPangkat" name="reqTmtPangkat" class="easyui-datebox" data-options="validType:'date'" style="width:100px" value="<?=$tempTmtPangkat?>" />
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Propinsi</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" name="reqPropinsiId" id="reqPropinsiId" value="<?=$tempPropinsiId?>" /> 
                            <input type="text" <?=$disabled?> class="easyui-validatebox" style="width:80%" id="reqPropinsi" 
                            data-options="validType:['sameAutoLoder[\'reqPropinsi\', \'\']']"
                            value="<?=$tempPropinsi?>" <?=$readonly?> required />
                        </td>
                    </tr>
                    <tr>
                    	<td>Kabupaten</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" name="reqKabupatenId" id="reqKabupatenId" value="<?=$tempKabupatenId?>" /> 
                            <input type="text" <?=$disabled?> class="easyui-validatebox" style="width:70%" id="reqKabupaten" 
                            data-options="validType:['sameAutoLoder[\'reqKabupaten\', \'\']']"
                            value="<?=$tempKabupaten?>" <?=$readonly?> required />
                        </td>
                    </tr>
                    <tr>
                    	<td>Kecamatan</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" name="reqKecamatanId" id="reqKecamatanId" value="<?=$tempKecamatanId?>" /> 
                            <input type="text" <?=$disabled?> class="easyui-validatebox" style="width:60%" id="reqKecamatan" 
                            data-options="validType:['sameAutoLoder[\'reqKecamatan\', \'\']']"
                            value="<?=$tempKecamatan?>" <?=$readonly?> required />
                        </td>
                    </tr>
                    <tr>
                    	<td>Desa</td>
                        <td>:</td>
                        <td>
                        	<input type="hidden" id="reqDesa0Id" name="reqDesaId" value="<?=$tempDesaInfoId?>" />
            				<select id="reqDesa0" name="reqDesa" multiple="multiple" <?=$disabled?>></select>
                        </td>
                    </tr>
                    
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

<script>

function GetTagFillCombo(valTempId) {
	 var reqPropinsiId= reqKabupatenId= reqKecamatanId= "";
	 reqPropinsiId= $("#reqPropinsiId").val();
	 reqKabupatenId= $("#reqKabupatenId").val();
	 reqKecamatanId= $("#reqKecamatanId").val();
	 
	 jQuery.ajax({
		 type: "GET",
		 url: '../json-main/desa_multi_select.php?reqPropinsiId='+reqPropinsiId+'&reqKabupatenId='+reqKabupatenId+'&reqKecamatanId='+reqKecamatanId,
		 data: '',
		 contentType: "application/json; charset=utf-8",
		 dataType: "json",
		 success: function(data){
			FillComboOnSuccess(data, valTempId)
		 },
		 failure: function (response1) {
		 alert(response.d);
		 jQuery("#imgSearchLoading").hide();
	 }
	 });
}

function FillComboOnSuccess(data, idTemp)
{
 var h1 = "";
 var $select = $("#"+idTemp);
 var valSelectedId= $("#"+idTemp+"Id").val();
 //alert(valSelectedId+'--'+idTemp);
 valSelectedId= String(valSelectedId);
 valSelectedId= valSelectedId.split(',');
 //alert(valSelectedId[0]+'--');
 
 for (j = 0; j < data.arrID.length; j++) 
 {
	 var valId= data.arrID[j];
	 var valNama= data.arrNama[j];
	 var indexValue= valSelectedId.indexOf(valId); 
	 var selectedValue="";
	 
	 if(indexValue >= 0)
	 	selectedValue= true;
	 else
	 	selectedValue= false;
		
	 $opt = $("<option />", {
		 value: valId,
		 text: valNama,
		 selected: selectedValue
	 });
	 
	 $select.append($opt).multipleSelect("refresh");
 }
 
}

$('select[id^="reqDesa"]').multipleSelect({
	width: 315,
	multiple: true,
	multipleWidth: 345
});

GetTagFillCombo('reqDesa0');
$('select[id^="reqDesa"]').change(function() {
	var tempId= $(this).attr('id');
	var tempValueId= $('#'+tempId).multipleSelect("getSelects")
	$('#'+tempId+"Id").val(tempValueId);
});

</script>
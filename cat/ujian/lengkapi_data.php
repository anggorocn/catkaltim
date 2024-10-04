<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Pegawai.php");

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

// $statement= " AND A.PELAMAR_ID = ".$tempPegawaiId;
$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;

$set= new Pegawai();
$set->selectByParams(array(), -1,-1, $statement);
// echo $set->query;exit;
$set->firstRow();
$tempKtpNo= $set->getField("KTP_NO");
$tempNipBaru= $set->getField("NIP_BARU");
$tempNama= $set->getField("NAMA");
$tempJenisKelamin= $set->getField("JENIS_KELAMIN");
$tempJenisKelaminNama= $set->getField("JENIS_KELAMIN_NAMA");
$tempTempatLahir= $set->getField("TEMPAT_LAHIR");
$tempTglLahir= dateTimeToPageCheck($set->getField("TGL_LAHIR"));
// echo $tempTglLahir;exit;
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



$tempTelp= $set->getField("TELP");
// $tempEmail= $set->getField("EMAIL");
//PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, , , ,, JENIS_KELAMIN, , , TMT_PANGKAT, , , PANGKAT_ID, JABATAN_ID, , 
unset($set);

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
$statement.= " AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN UJ.TGL_MULAI AND UJ.TGL_SELESAI";
$set= new UjianPegawaiDaftar();
$set->selectByParamsJawabanSoal(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempUjianPegawaiDaftarId= $set->getField("UJIAN_PEGAWAI_DAFTAR_ID");
$tempStatusSetuju= $set->getField("STATUS_SETUJU");

?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<style>
input[type=checkbox]{
	width:auto !important;
}

#fvpp-blackout {
	display: none;
	z-index: 499;
	position: fixed;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background: #000;
	opacity: 0.5;
}

#my-welcome-message {
	display: none;
	z-index: 500;
	position: fixed;
	width: 600px;
	left: calc(50% - 300px);
	top: 16%;
	*bottom: 2%;
	bottom: 50%;
	padding: 18px 20px;
	*font-family: Calibri, Arial, sans-serif;
	background: #FFF;
}

#fvpp-close {
	position: absolute;
	top: 18px;
	right: 20px;
	cursor: pointer;
	background:rgba(0,0,0,0.5);
	*background:#1b4a73;
	color:#FFF;
	width:30px;
	height:30px;
	line-height:30px;
	text-align:center;
}
#fvpp-close:hover{
	*background:#9a0e01;
	background:rgba(0,0,0,0.8);
}

#fvpp-dialog h2 {
	font-size: 2em;
	margin: 0;
}

#fvpp-dialog p {
	margin: 0;
}
</style>
 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script> 
<script type="text/javascript" src="../WEB/lib-ujian/easyui/kalender-easyui.js"></script>
<link href="../WEB/lib-ujian/multipleselect/multiple-select.css" rel="stylesheet"/>
<script src="../WEB/lib-ujian/multipleselect/jquery.multiple.select.js"></script>
    
<script language="javascript">
	$(function() {
		$('#ff').form({
			url:'../json-ujian/lengkapi_data_diri.php',
			onSubmit:function(){
				//alert($(this).form('validate')); return false;
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
				// console.log(data);return false;
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
		
		$("#reqKecamatanId").change(function() { 
				$("#reqDesaId").val("");
				$("#reqDesa").val("");
		  });
	 
		
	});
	
	function nextModulLink()
	{
		if("<?=$tempStatusSetuju?>" == "")
		{
			var s_url= "../json-ujian/form_persetujuan.php?reqUjianPegawaiDaftarId=<?=$tempUjianPegawaiDaftarId?>&reqPegawaiId=<?=$tempPegawaiId?>";
			$.ajax({'url': s_url,'success': function(msg) {
				if(msg == ''){}
				else
				{
					document.location.href= "?pg=form_persetujuan";
				}
			}});
		}
		else
		{
			document.location.href= "?pg=form_persetujuan";
		}
	}
	
	function nextModul()
	{
		if("<?=$tempStatusSetuju?>" == "1")
		{
			document.location.href= "?pg=form_persetujuan";
		}
		else
		{
			$('#my-welcome-message').firstVisitPopup({
				//cookieName : 'homepage',
				showAgainSelector: '#show-message'
			});
		}
	}
	
</script>

<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Edit / Lengkapi Data Pribadi (<?=$tempNama?> - <?=$tempNipBaru?>) </div>
        </div>
    </div>
    
	<div class="row">
    	<div class="col-md-4">
        	<div class="area-foto-user"><img src="../WEB/images-ujian/foto-user.png"></div>
        </div>
        <div class="col-md-8">
        	<div class="area-data-profil">
            	<form id="ff" method="post" enctype="multipart/form-data">
                <input type="hidden" name="reqPegawaiId" value="<?=$tempPegawaiId?>" />
                <input type="hidden" name="reqNipBaru" value="<?=$tempNipBaru?>" />
                <input type="hidden" name="reqJenisKelamin" value="<?=$tempJenisKelamin?>" />
                <input type="hidden" name="reqTempatLahir" value="<?=$tempTempatLahir?>" />
                <input type="hidden" name="reqTglLahir" value="<?=$tempTglLahir?>" />
                <input type="hidden" name="reqNama" value="<?=$tempNama?>" />
                
                <input type="hidden" name="reqPropinsiId" id="reqPropinsiId" value="<?=$tempPropinsiId?>" />
                <input type="hidden" name="reqKabupatenId" id="reqKabupatenId" value="<?=$tempKabupatenId?>" />
                
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
                        <!-- <td>
                        	<input style="width:100%" type="text" class="easyui-validatebox" required placeholder="Ketik Nama Anda..." name="reqNama" id="reqNama" value="<?=$tempNama?>" />
                        </td> -->
                        <td>
                        	<?=$tempNama?>
                        </td>
                    </tr>
                    <tr>
                    	<td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>
                        	<select name="reqJenisKelamin">
                            	<option value="P" <? if($tempJenisKelamin=="P") echo 'selected'?>>Pria</option>
                            	<option value="W" <? if($tempJenisKelamin=="W") echo 'selected'?>>Wanita</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>
                            <input type="text" class="easyui-validatebox" data-options="validType:'email'" name="reqEmail" id="reqEmail" style="width:60%" value="<?=$tempEmail?>" <?=$readonly?> />
                        </td>
            		</tr>
            		<tr>
                    	<td>Tanggal Lahir</td>
                        <td>:</td>
                        <td>
                            <input id="reqTglLahir" name="reqTglLahir" class="easyui-datebox" data-options="validType:'date'" style="width:100px" value="<?=$tempTglLahir?>" />
                        </td>
                    </tr>
                    <tr>
                    	<td>Pendidikan</td>
                        <td>:</td>
                        <td>
                        <select name="reqPendidikan" id="reqPendidikan">
                        	<option value="" <? if($tempPendidikan == "") echo "selected"?>></option>
                            <option value="3" <? if($tempPendidikan == "3") echo "selected"?>>SMP/SMA</option>
                            <option value="6" <? if($tempPendidikan == "6") echo "selected"?>>D1/D2/D3</option>
                            <option value="7" <? if($tempPendidikan == "7") echo "selected"?>>D4</option>
                            <option value="8" <? if($tempPendidikan == "8") echo "selected"?>>S1</option>
                            <option value="9" <? if($tempPendidikan == "9") echo "selected"?>>S2</option>
                        </select>
                        </td>
                    </tr>
                </table>
                </form>
                
                <div class="lengkapi-data"><a href="#" onclick="$('#ff').submit();">Simpan</a></div>
            </div>
        </div>
        
        <div class="area-prev-next">
<!--             <div class="prev"><a href="?pg=tata_cara"><i class="fa fa-chevron-left"></i></a></div>
 -->            <div class="prev"><a href="?pg=dashboard"><i class="fa fa-chevron-left"></i></a></div>
            <!-- <div class="next"><a href="#" onclick="nextModul()"><i class="fa fa-chevron-right"></i></a></div> -->
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

<div id="my-welcome-message">
    <div class="konten-welcome">
    <div class="row">
    	<div class="col-md-12">
        	<div class="area-judul-halaman">Informasi</div>
        	<div class="area-tatacara"> 
            <ul style="list-style:none !important; list-style-type:none !important;">
                <li>Saya menyatakan telah membaca dan memahami seluruh petunjuk serta mengijinkan Panitia Rekruitmen untuk menggunakan data administrasi dalam proses Sertifikasi Rekruitmen Online</li>
            </ul>
            <div class="setuju"><a href="#" onclick="nextModulLink()">Setuju</a></div>
            </div>
        </div>
    </div>
    </div>
</div>
    
<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>
<script>
/*$(function () {
    $('#my-welcome-message').firstVisitPopup({
        //cookieName : 'homepage',
        showAgainSelector: '#show-message'
    });
});*/
</script>
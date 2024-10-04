<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Pelaporan Hasil Assesment</title>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
<link rel="stylesheet" href="../WEB/css/gaya-assesor.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib/Font-Awesome-4.5.0/css/font-awesome.css">
    
<!--<script type='text/javascript' src="../WEB/lib/bootstrap/jquery.js"></script> -->

<!-- FLUSH FOOTER -->
<style>
.rbtn ul{
	list-style-type:none;
}
.rbtn ul li{
	cursor:pointer; 
	*display:inline-block; 
	display:inherit;
	width:100px; 
	border:1px solid #06345f; 
	padding:5px;
	margin:-5px;
	*margin-right:5px; 
	
	-moz-border-radius: 3px; 
	-webkit-border-radius: 3px; 
	-khtml-border-radius: 3px; 
	border-radius: 3px; 
	
	text-align:center;
	
}
.over{
	background: #063a69;
}

.sebelumselected{
	background: #063a69; 
	color:#fff;
	*margin:2px;
}

.sebelumselected:before{
	font-family:"FontAwesome";
	content:"\f096";
	*margin-right:10px;
	color:#f8a406;
	font-size:18px;
	*vertical-align:middle;
}

.selected{
	background: #063a69; 
	color:#fff;
}
.selected:before{
	font-family:"FontAwesome";
	content:"\f046";
	*margin-right:10px;
	color:#f8a406;
	font-size:18px;
	*vertical-align:middle;
}
</style>
</head>

<body>

                	<div class="row">
                        <div class="col-md-12">
                        	
                        	<div class="area-table-assesor">
                                <br>
                              <div class="judul-halaman">Penilaian dan Catatan :</div>
                              	<form id="ff" method="post" novalidate>
                            	<table style="margin-bottom:60px;" class="profil">
                                <thead>
                                    <tr>
                                        <th width="100%" colspan="2">Hasil Individu</th>
                                    </tr>
                                </thead>
                                <tbody>
								                                    
                                    <tr>
                                        <td style="vertical-align:top; width:51%">
                                            <div style="margin-bottom: 10px;">Integritas</div>
                                            
                                            <div class="rbtn">
                                                <ul>
                                                   <li style="width:100%; text-align:left" id="rbtn-0--" class=" sebelumselected">
                                                   Menciptakan situasi kerja yang mendorong seluruh pemangku kepentingan mematuhi nilai, norma, dan etika organisasi dalam segala situasi dan Kondisi                                                </li>



                                                   <br/><li style="width:100%; text-align:left" id="rbtn-1--" class=" sebelumselected">
                                                   Mendukung dan menerapkan prinsip moral dan standar etika yang tinggi, serta berani menanggung konsekuensinya                                                </li>


                                                   <br/><li style="width:100%; text-align:left" id="rbtn-2--" class=" sebelumselected">
                                                   Berani melakukan koreksi atau mengambil tindakan atas penyimpangan kode etik/nilai-nilai yang dilakukan oleh orang lain, pada tataran lingkup kerja setingkat instansi meskipun ada resiko                                                </li>
                                               </ul>
                                           </div>
                                       </td>
                                       <td style="vertical-align:top; background-color:transparent; color:#000 !important">
                                           <table style="width:100%; border:none">
                                            <tr>
                                                <td style="text-align:center">1</td>
                                                <td style="text-align:center">2</td>
                                                <td style="text-align:center">3</td>
                                                <td style="text-align:center">4</td>
                                                <td style="text-align:center">5</td>
                                            </tr>
                                            <tr>
                                            	<input type="hidden" name="reqAsesorPenilaianDetilId[]" id="reqAsesorPenilaianDetilId2" value="15695" />
                                                <input type="hidden" name="reqJadwalTesId[]" id="reqJadwalTesId2" value="24" />
                                                <input type="hidden" name="reqTanggalTes[]" id="reqTanggalTes2" value="17-11-2018" />
                                                <input type="hidden" name="reqJabatanTesId[]" id="reqJabatanTesId2" value="Kepala Bidang Humas pada Pusat Penerangan Sekretariat Jenderal" />
                                                <input type="hidden" name="reqSatkerTesId[]" id="reqSatkerTesId2" value="01" />
                                                <input type="hidden" name="reqJadwalAsesorId[]" id="reqJadwalAsesorId2" value="351" />
                                                <input type="hidden" name="reqAspekId[]" id="reqAspekId2" value="2" />
                                                <input type="hidden" name="reqAsesorAtributId[]" id="reqAsesorAtributId2" value="0901" />
                                                <input type="hidden" name="reqNilaiStandar[]" id="reqNilaiStandar2" value="3" />
                                                <input type="hidden" name="reqNilai[]" id="reqNilai2" value="3" />
                                                <input type="hidden" name="reqGap[]" id="reqGap2" value="0" />
                                                <input type="hidden" name="reqAsesorFormulaEselonId[]" id="reqAsesorFormulaEselonId2" value="8" />
                                                <input type="hidden" name="reqAsesorFormulaAtributId[]" id="reqAsesorFormulaAtributId2" value="142" />
                                                <input type="hidden" name="reqAsesorPenggalianId[]" id="reqAsesorPenggalianId2" value="11" />
                                                <input type="hidden" name="reqAsesorPegawaiId[]" id="reqAsesorPegawaiId2" value="850" />
                                                
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="1" data-options="validType:'requireRadio[\'#ff input[name=reqRadio2]\', \'Pilih nilai\']'"/></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="2" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox" checked name="reqRadio2" id="reqRadio2" value="3" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="4" /></td>
                                                <td align="center"><input type="radio" class="easyui-validatebox"  name="reqRadio2" id="reqRadio2" value="5" /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                	<textarea name="reqCatatan[]" style="color:#000 !important"><br></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="2" align="center">
                                       <input type="hidden" name="reqMode" value="insert">
                                       <input name="submit1" type="submit" value="Simpan" />
                                   </td>
                               </tr>
                                                                </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyuiasesor.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script>
$(document).ready(function() {
	$(function(){

        $('.rbtn ul li').click(function(){
        // get the value from the id of the clicked li and attach it to the window object to be able to use it later.
            var choice= this.id;
            var text= $(this).text();
            var element= choice.split('-');
            
            if($('li[id^="'+choice+'"]').hasClass("selected") == true)
            {
                $('li[id^="'+choice+'"]').removeClass('selected');
                $('li[id^="'+choice+'"]').addClass('sebelumselected');
                $("#reqIndikatorId"+element[2]+", #reqLevelId"+element[2]).val("");
            }
            else
            {
                $('li[id^="'+choice+'"]').removeClass('sebelumselected');
                $('li[id^="'+choice+'"]').addClass('selected');
                $("#reqIndikatorId"+element[2]).val(element[2]);
                $("#reqLevelId"+element[2]).val(element[3]);
            }
            
        }); 
        
        $('.rbtn ul li').mouseover(function(){
            $(this).addClass('over');
        });
        
        $('.rbtn ul li').mouseout(function(){
            $(this).removeClass('over');
        });

    });
});

$(function(){
	$('#ff').form({
		url:'../json-asesor/penilaian_pegawai.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			$('#rst_form').click();
			//parent.setShowHideMenu(3);
			document.location.href = 'penilaian_pegawai.php?reqAspekId=2&reqJadwalAsesorId=351&reqJadwalPegawaiId=2610';
		}
	});
	
	$('input[id^="reqRadio"]').change(function(e) {
		var tempId= $(this).attr('id');
		var tempValId= $(this).val();
		tempId= tempId.split('reqRadio');
		tempId= tempId[1];
		
		$("#reqNilai"+tempId).val(tempValId);
		var gap= parseInt(tempValId) - parseInt($("#reqNilaiStandar"+tempId).val());
		$("#reqGap"+tempId).val(gap);
		$("#reqGapInfo"+tempId).text(gap);
	});
});
</script>

<script type="text/javascript" src="../niceEdit/nicedit.js"></script>
<script type="text/javascript">
	//new nicEditor({fullPanel : true}).panelInstance('reqIsi');
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>

</body>
</html>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarSertifikat.php");

$userLogin->checkLoginPelamar();

$pelamar = new Pelamar();
$pelamar_sertifikat = new PelamarSertifikat();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$pelamar_sertifikat->selectByParams(array('PELAMAR_SERTIFIKAT_ID'=>$reqId, "PELAMAR_ID" => $userLogin->userPelamarId));
$pelamar_sertifikat->firstRow();
//echo $pelamar_sertifikat->query;

$tempPegawaiSertifikatId = $pelamar_sertifikat->getField("PELAMAR_SERTIFIKAT_ID");
$tempNama = $pelamar_sertifikat->getField("NAMA");
$tempTanggalTerbit = dateToPageCheck($pelamar_sertifikat->getField("TANGGAL_TERBIT"));
$tempTanggalKadaluarsa = dateToPageCheck($pelamar_sertifikat->getField("TANGGAL_KADALUARSA"));
$tempGroupSertifikat = $pelamar_sertifikat->getField("GROUP_SERTIFIKAT");
$tempKeterangan = $pelamar_sertifikat->getField("KETERANGAN");
$tempSertifikatId = $pelamar_sertifikat->getField("SERTIFIKAT_ID");

if($tempNama == "")
	$tempNama = $tempSertifikatId;

$tempRowId = $tempPegawaiSertifikatId;

$pelamar_sertifikat->selectByParams(array("PELAMAR_ID" => $userLogin->userPelamarId));

if($reqMode == "delete")
{
	$set= new PelamarSertifikat();
	$set->setField('PELAMAR_SERTIFIKAT_ID', $reqId);
	if($set->delete())
	{
		echo "<script>document.location.href='?pg=data_sertifikat';</script>";
	}
	else
	{
		echo "<script>document.location.href='?pg=data_sertifikat';</script>";
	}
}

?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="../WEB/lib/autokomplit/jquery-ui.css">
<script src="../WEB/lib/autokomplit/jquery-ui.js"></script>  
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
<script type="text/javascript" src="../WEB/lib/easyui/easyloader.js"></script>   
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.form.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.linkbutton.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.draggable.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.resizable.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.panel.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.window.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.progressbar.js"></script> 
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.messager.js"></script>      
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.tooltip.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.validatebox.js"></script>  
<script type="text/javascript" src="../WEB/lib/easyui/pluginsbaru/jquery.combo.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			url:'../json/data_sertifikat_add.php',
			onSubmit:function(){
				if($(this).form('validate') == false && $("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_keluarga';
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				if($("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_keluarga';
				else
				{
					$("input, textarea").val(null);
					document.location.reload();
				}
			}
		});
		
		<?php /*?>$('#reqNama').autocomplete({ 
			source:function(request, response){
				var id= this.element.attr('id');
				var field= "";
				
				
				$.ajax({
					url: "../json/sertifikat_auto_combo_json.php",
					type: "GET",
					dataType: "json",
					data: { term: request.term },
					success: function(responseData){
						if(responseData == null)
						{
							response(null);
						}
						else
						{
							var array = responseData.map(function(element) {
								return {id: element['id'], label: element['label']};
							});
							response(array);
						}
					}
				})
			},
			select: function (event, ui) 
			{ 
				$("#reqSertifikatId").val(ui.item.id);
			}, 
			autoFocus: true
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
		  .append( "<a>" + item.label + "</a>" )
		  .appendTo( ul );
		};<?php */?>
		
	});
	
</script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_sertifikat"]?></div>
    <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Tanggal Terbit</th>
                    <th scope="col">Tanggal Kadaluarsa</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
				<?
                while($pelamar_sertifikat->nextRow())
                {
                ?>
                    <tr>
                        <td><?=$pelamar_sertifikat->getField("NAMA")?></td>
                        <td><?=dateToPageCheck($pelamar_sertifikat->getField("TANGGAL_TERBIT"))?></td>
                        <td><?=dateToPageCheck($pelamar_sertifikat->getField("TANGGAL_KADALUARSA"))?></td>
                        <td><?=$pelamar_sertifikat->getField("KETERANGAN")?></td>
                        <td>
                			<a href="?pg=data_sertifikat&reqId=<?=$pelamar_sertifikat->getField("PELAMAR_SERTIFIKAT_ID")?>"><img src="../WEB/images/icon-edit.png"></a>                                       	
	                        <a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=data_sertifikat&reqMode=delete&reqId=<?=$pelamar_sertifikat->getField("PELAMAR_SERTIFIKAT_ID")?>' }"><img src="../WEB/images/icon-hapus.png"></a>
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
                    <td>Nama Sertifikat</td>
                    <td>
                		<input type="hidden" id="reqSertifikatId" name="reqSertifikatId" value="<?=$tempSertifikatId?>" />
                        <input id="reqNama" name="reqNama" type="text" class="easyui-validatebox" style="width:80%" value="<?=$tempNama?>" required />
                    </td>
                </tr>
                <!--<tr>
                    <td>Nama Sertifikat</td>
                    <td colspan="3">
                        <input id="reqSertifikatId" name="reqSertifikatId" required style="width:250px" class="easyui-combobox" data-options="
                        filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
                        url: '../json/sertifikat_combo_json.php',
                        method: 'get',
                        valueField:'id', 
                        textField:'text'
                        " value="<?=$tempNama?>"/>
                    </td>
                </tr>-->	
                <tr>
                    <td>Tanggal Terbit</td>
                    <td>
                        <input id="reqTanggalTerbit" name="reqTanggalTerbit" class="easyui-datebox" data-options="validType:'date'"  value="<?=$tempTanggalTerbit?>" required></input>
                    </td>
                </tr>
                <tr>
                    <td>Berlaku s.d</td>
                    <td>
                        <input id="reqTanggalKadaluarsa" name="reqTanggalKadaluarsa" class="easyui-datebox" data-options="validType:'date'"  value="<?=$tempTanggalKadaluarsa?>"></input> (*jika ada)
                    </td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>
                        <input id="reqKeterangan" name="reqKeterangan" type="text" class="easyui-validatebox" style="width:100%" value="<?=$tempKeterangan?>" />
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
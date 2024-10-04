<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Pelamar.php");
include_once("../WEB/classes/base/PelamarPendidikan.php");
include_once("../WEB/classes/base-simpeg/Universitas.php");
include_once("../WEB/classes/base-simpeg/Pendidikan.php");
include_once("../WEB/classes/base-simpeg/PendidikanBiaya.php");

$userLogin->checkLoginPelamar();

$pelamar = new Pelamar();
$pelamar_pendidikan = new PelamarPendidikan();
$universitas = new Universitas();
$pendidikan = new Pendidikan();
$pendidikan_biaya = new PendidikanBiaya();


$reqId = httpFilterGet("reqId");

$pelamar_pendidikan->selectByParams(array('PELAMAR_PENDIDIKAN_ID' => $reqId, "PELAMAR_ID" => $userLogin->userPelamarId));
if($pelamar_pendidikan->firstRow())
{
	$tempPendidikanId = $pelamar_pendidikan->getField('PENDIDIKAN_ID');
	$tempPendidikanBiayaId = $pelamar_pendidikan->getField('PENDIDIKAN_BIAYA_ID');
	$tempNama = $pelamar_pendidikan->getField('NAMA');
	$tempKota = $pelamar_pendidikan->getField('KOTA');
	$tempUniversitasId = $pelamar_pendidikan->getField('UNIVERSITAS_ID');
	$tempTanggalIjasah = dateToPageCheck($pelamar_pendidikan->getField('TANGGAL_IJASAH'));
	$tempLulus = $pelamar_pendidikan->getField('LULUS');
	$tempNo = $pelamar_pendidikan->getField('NO_IJASAH');
	$tempTtdIjazah = $pelamar_pendidikan->getField('TTD_IJASAH');
	$tempJurusan = $pelamar_pendidikan->getField('JURUSAN');
	$tempTanggalAcc = dateToPageCheck($pelamar_pendidikan->getField('TANGGAL_ACC'));
	$tempRowId = $pelamar_pendidikan->getField('PELAMAR_PENDIDIKAN_ID');
	$tempJurusanId = $pelamar_pendidikan->getField('JURUSAN_ID');
	
	if($tempJurusan == "")
	$tempJurusan = $tempJurusanId;
}

$pendidikan_biaya->selectByParams();
$universitas->selectByParams();
$pendidikan->selectByParams(array(),-1,-1,"", "ORDER BY NAMA");

$pelamar_pendidikan->selectByParams(array("PELAMAR_ID" => $userLogin->userPelamarId));
//echo $pelamar_pendidikan->query;exit;
if($reqMode == "delete")
{
	$set= new PelamarPendidikan();
	$set->setField('PELAMAR_PENDIDIKAN_ID', $reqId);
	if($set->delete())
	{
		$alertMsg .= "Data berhasil dihapus";
		echo "<script>document.location.href='?pg=data_pendidikan_formal';</script>";
	}
	else
	{
		$alertMsg .= "Error ".$set->getErrorMsg();
		echo "<script>document.location.href='?pg=data_pendidikan_formal';</script>";
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
	function setValue()
	{
		$('#reqJurusan').combobox('setValue', '<?=$tempJurusan?>');
	}
	$(function(){
		$('#ff').form({
			url:'../json/data_pendidikan_formal_add.php',
			onSubmit:function(){
				if($(this).form('validate') == false && $("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_pelatihan';
				
				//alert($(this).form('validate'));
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				$.messager.alert('Info', data, 'info');
				if($("#reqFlagSelanjutnya").val() == "1")
					document.location.href = '?pg=data_pelatihan';
				else
				{
					$("input, textarea").val(null);
					document.location.reload();
				}
			}
		});
		
		<?php /*?>$('#reqJurusan').autocomplete({ 
			source:function(request, response){
				var id= this.element.attr('id');
				var field= "";
				
				
				$.ajax({
					url: "../json/jurusan_auto_combo_json.php",
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
				$("#reqJurusanId").val(ui.item.id);
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
    <div id="judul-halaman"><?=$arrayJudul["index"]["data_pendidikan_formal"]?></div>
    <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Pendidikan</th>
                    <th scope="col">Nama Sekolah</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Lulus</th>
                    <th scope="col">No Ijasah</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
        <?
        while($pelamar_pendidikan->nextRow())
        {
        ?>
            <tr onClick="parent.frames['mainFrameDetilPop'].location.href = 'PELAMAR_add_pendidikan.php?reqId=<?=$reqId?>&reqRowId=<?=$pelamar_pendidikan->getField("PELAMAR_PENDIDIKAN_ID")?>'">
                <td><?=$pelamar_pendidikan->getField("PENDIDIKAN_NAMA")?></td>
                <td><?=$pelamar_pendidikan->getField("NAMA")?></td>
                <td><?=$pelamar_pendidikan->getField("JURUSAN")?></td>
                <td><?=$pelamar_pendidikan->getField("LULUS")?></td>
                <td><?=$pelamar_pendidikan->getField("NO_IJASAH")?></td>
                <td>
                	<a href="?pg=data_pendidikan_formal&reqId=<?=$pelamar_pendidikan->getField("PELAMAR_PENDIDIKAN_ID")?>"><img src="../WEB/images/icon-edit.png"></a>
                	<a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=data_pendidikan_formal&reqMode=delete&reqId=<?=$pelamar_pendidikan->getField("PELAMAR_PENDIDIKAN_ID")?>' }"><img src="../WEB/images/icon-hapus.png"></a>
                </td>
            </tr>    
        <?
        }
        ?>    
        </table>
    
    </div>
    
    
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png"> Form Entri</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table>
            <tr>
                <td>Pendidikan</td>
                <td>
                    <select id="reqPendidikanId" name="reqPendidikanId" required>
                    <? 
                    while($pendidikan->nextRow())
                    {
                    ?>
                        <option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($tempPendidikanId == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                    <? 
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nama Instansi</td>
                <td colspan="3">
                    <input name="reqNama" id="reqNama" class="easyui-validatebox" size="80" type="text" value="<?=$tempNama?>" required />
                </td>
            </tr>
            <tr>
                <td>Kota Instansi</td>
                <td>
                    <input name="reqKota" id="reqKota" class="easyui-validatebox" size="30" type="text" value="<?=$tempKota?>" required />
                </td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td colspan="3">
                	<input type="hidden" id="reqJurusanId" name="reqJurusanId" value="<?=$tempJurusanId?>" />
                    <input name="reqJurusan" id="reqJurusan" class="easyui-validatebox" size="45" type="text" value="<?=$tempJurusan?>" />
                </td>
            </tr>
            <!--<tr>
                <td>Jurusan</td>
                <td colspan="3">
                	<input id="reqJurusanId" name="reqJurusanId" required style="width:250px" class="easyui-combobox" data-options="
                    filter: function(q, row) { return row['text'].toLowerCase().indexOf(q.toLowerCase()) != -1; },
                    url: '../json/jurusan_combo_json.php',
                    method: 'get',
                    valueField:'id', 
                    textField:'text'
                    " value="<?=$tempJurusan?>"/>
                </td>
            </tr>-->
            <tr>
                <td>Lulus Tahun</td>
                <td colspan="3">
                    <input name="reqLulus" id="reqLulus" class="easyui-validatebox" size="4" maxlength="4" type="text" value="<?=$tempLulus?>" />
                </td>
            </tr>
            <tr>
                <td>Tanggal Ijazah</td>
                <td>
                    <input id="reqTanggalIjasah" name="reqTanggalIjasah" class="easyui-datebox" data-options="validType:'date'" style="width:100px" required value="<?=$tempTanggalIjasah?>"></input>
                </td>
            </tr>
            <tr>
                <td>No Ijazah</td>
                <td colspan="3">
                    <input name="reqNoIjasah" id="reqNoIjasah" class="easyui-validatebox" style="20%" type="text" value="<?=$tempNo?>" />
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
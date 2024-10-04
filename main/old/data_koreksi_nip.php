<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/PesertaPerubahanNip.php");

$userLogin->checkLoginPelamar();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$set_data = new PesertaPerubahanNip();
if($reqRowId == ""){}
else
{
    $statement= " AND A.PESERTA_PERUBAHAN_NIP_ID = ".$reqRowId." AND A.PESERTA_ID = ".$userLogin->userPelamarId;
    $set_data->selectByParams(array(), -1, -1, $statement);
    $set_data->firstRow();

    $reqNip = $set_data->getField('NIP');
    $tempRowId = $set_data->getField('PESERTA_PERUBAHAN_NIP_ID');
    $tempRowId = $reqRowId;
}

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlkarpeg= $data->urlConfig->main->urlupload;
$FILE_DIR_KARPEG= $urlkarpeg."/scankarpeg/".$userLogin->userPelamarId."/";
// echo $FILE_DIR_KARPEG;exit();

$statement= " AND A.PESERTA_ID = ".$userLogin->userPelamarId;
// echo $statement;exit();
$set_data->selectByParams(array(),-1,-1, $statement);
// echo $set_data->errorMsg;exit;
// echo $set_data->query;exit;

if($reqMode == "delete")
{
	$set= new PesertaPerubahanNip();
	$set->setField('PESERTA_PERUBAHAN_NIP_ID', $reqId);
	if($set->delete())
	{

        $tempPegawaiKarpeg= $FILE_DIR_KARPEG.$reqId.".pdf";
        if(file_exists("$tempPegawaiKarpeg"))
        {
            unlink($tempPegawaiKarpeg);
        }

		echo "<script>document.location.href='index.php?pg=data_koreksi_nip';</script>";
	}
	else
	{
		echo "<script>document.location.href='index.php?pg=data_koreksi_nip';</script>";
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
			url:'../json/data_koreksi_nip.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
                // console.log(data);return false;

                data = data.split("-");
                rowid= data[0];
                infodata= data[1];

                $.messager.alert('Info', infodata, 'info');

                if(rowid == "xxx"){}
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

    <div id="judul-halaman"><?=$arrayJudul["koreksi_nip"]["judul"]?></div>
    <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">NIP Koreksi</th>
                    <th scope="col">Scan Karpeg</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
            
            <?
            while($set_data->nextRow())
            {
                $tempPegawaiKarpeg= $FILE_DIR_KARPEG.$set_data->getField("PESERTA_PERUBAHAN_NIP_ID").".pdf";
            ?>
            <tr>
                <td><?=$set_data->getField("NIP")?></td>
                <td>
                    <?
                    if(file_exists("$tempPegawaiKarpeg"))
                    {
                    ?>
                    <a href="<?=$tempPegawaiKarpeg?>" target="_blank">File</a>
                    <?
                    }
                    else
                    {
                    ?>
                    File tidak ada
                    <?
                    }
                    ?>
                </td>
                <td>
                    <a href="?pg=data_koreksi_nip&reqRowId=<?=$set_data->getField("PESERTA_PERUBAHAN_NIP_ID")?>"><img src="../WEB/images/icon-edit.png"></a>  
                    <a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=data_koreksi_nip&reqMode=delete&reqId=<?=$set_data->getField("PESERTA_PERUBAHAN_NIP_ID")?>' }"><img src="../WEB/images/icon-hapus.png"></a>
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
                    <td>NIP Koreksi</td>
                    <td>
                        <input name="reqNip" id="reqNip" class="easyui-validatebox" size="30" required type="text" value="<?=$reqNip?>" />
                    </td>
                </tr>
                <tr>
                    <td>Upload Scan Karpeg (format Pdf)</td>
                    <td>
                        <input type="file" style="font-size:10px" name="reqFotoFile" id="reqFotoFile" class="easyui-validatebox" accept="application/pdf" />
                    </td>        
                </tr>
            </table>      
            <br>
            <div>
                <? if($tempRowId == ''){ $reqMode='insert'; }else{ $reqMode='update'; }?>
                <input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                <input id="reqSubmit" type="submit" value="Simpan">
            </div>
        </form>
            <!-- <input type="submit" name="reqSelanjutnya" onClick="$('#reqFlagSelanjutnya').val('1'); $('#reqSubmit').click();" value="Selanjutnya">
            <input type="hidden" id="reqFlagSelanjutnya" value=""> -->
    </div>
    
</div>

<script type="text/javascript">
$('#reqNip').bind('keyup paste', function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
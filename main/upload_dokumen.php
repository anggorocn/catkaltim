<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/UploadFile.php");

$userLogin->checkLoginPelamar();

$reqId = httpFilterRequest("reqId");
$reqMode = httpFilterRequest("reqMode");
$reqSubmit = httpFilterPost("reqSubmit");
$reqRowId= httpFilterRequest("reqRowId");

$set_data = new UploadFile();
$set_upload = new UploadFile();

// if($reqRowId == ""){}
// else
// {
$statement= " AND A.PEGAWAI_ID = ".$userLogin->userPelamarId;
$set_upload->selectByParams(array(), -1, -1, $statement);
     // echo $set_upload->query;exit;
$set_upload->firstRow();

$reqNama = $set_upload->getField('NAMA');
$TempLinkFile1 = $set_upload->getField('LINK_FILE1');
// var_dump($TempLinkFile1) ;exit;
$TempLinkFile2 = $set_upload->getField('LINK_FILE2');
// $TempLinkFile3 = $set_upload->getField('LINK_FILE3');


$tempRowId = $set_upload->getField('PESERTA_PERUBAHAN_NIP_ID');
$tempRowId = $reqRowId;
// }

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlkarpeg= $data->urlConfig->main->urlupload;
// echo $urlkarpeg;exit();

$FILE_DIR_KARPEG= $urlkarpeg."dokumen/".$userLogin->userPelamarId."/";

$FILE_DIR_FOTO= $urlkarpeg."foto/".$userLogin->userPelamarId."/";

$checkfile1 = $set_upload->getField("LINK_FILE1");
$checkfile2 = $set_upload->getField("LINK_FILE2");
// $checkfile3 = $set_upload->getField("LINK_FILE3");
$checkfilefoto = $set_upload->getField("LINK_FOTO");
// echo


$tempPegawaiFile1=  $set_upload->getField("LINK_FILE1");
$tempPegawaiFile2=  $set_upload->getField("LINK_FILE2");
// $tempPegawaiFile3= $urlkarpeg.$set_upload->getField("LINK_FILE3");
$tempPegawaiFoto=  $set_upload->getField("LINK_FOTO");

// echo $tempPegawaiFile1;exit;


// echo $tempPegawaiFile;exit;


// echo $FILE_DIR_FOTO;exit();

$statement= " AND A.PEGAWAI_ID = ".$userLogin->userPelamarId;
// echo $statement;exit();
$set_data->selectByParams(array(),-1,-1, $statement);

// $tempPegawaiKarpeg= $FILE_DIR_KARPEG.$set_data->getField("NAMA");
// echo $tempPegawaiKarpeg;exit();
        // echo $tempPegawaiKarpeg;exit;



// echo $set_data->errorMsg;exit;
// echo $set_data->query;exit;



if($reqMode == "delete")
{
	$set= new UploadFile();
	$set->setField('PEGAWAI_ID', $userLogin->userPelamarId);
	if($set->delete())
	{

        $tempPegawaiKarpeg= $FILE_DIR_KARPEG.$reqNamaFile;
        // echo  $tempPegawaiKarpeg;exit;
        if(file_exists("$tempPegawaiKarpeg"))
        {
            unlink($tempPegawaiKarpeg);
        }

        echo "<script>document.location.href='index.php?pg=upload_dokumen';</script>";
    }
    else
    {
      echo "<script>document.location.href='index.php?pg=upload_dokumen';</script>";
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
			url:'../json/upload_file.php',
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

    <div id="judul-halaman"><?=$arrayJudul["upload_dokumen"]["judul"]?></div>
<!--     <div class="judul-halaman"><img src="../WEB/images/icon-menu.png"> Monitoring</div>
    
    <div class="data-monitoring">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Link Foto</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>

                <?/*
                while($set_data->nextRow())
                {
                    $tempPegawaiFoto= $urlkarpeg.$set_data->getField("LINK_FOTO");
                    ?>
                    <tr>
                        <td><?=$set_data->getField("NAMA")?></td>
                        <td>                    <a href="<?=$tempPegawaiFoto?>" target="_blank">File</a>
                        </td>
                        <td>
                            <a href="#" onClick="if(confirm('Apakah anda yakin ingin menghapus data ini?')) { window.location.href = '?pg=upload_dokumen&reqMode=delete&reqId=<?=$set_data->getField("PEGAWAI_ID")?>&reqNamaFile=<?=$set_data->getField("NAMA")?>' }"><img src="../WEB/images/icon-hapus.png"></a>
                        </td>
                    </tr>
                    <?
                }
               */ ?>    
            </tbody>
        </table>

    </div>  -->
    
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png"> Form Upload Dokumen</div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table>
                <!-- <tr>
                    <td>Upload Portfolio</td>
                    <td>
                        <input type="file" style="font-size:10px" name="reqLinkFile1" id="reqLinkFile1" class="easyui-validatebox" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword, application/pdf" />
                        <? if ($checkfile1)
                        {
                        ?>
                        <a href="<?=$tempPegawaiFile1?>" target="_blank"><img src="../WEB/images/down_icon.png" title="download" /></a>
                        <?
                        }
                        ?> 
                    </td>        
                </tr> -->
                <tr>
                    <td>Upload Dokumen Pendukung </td>
                    <td>
                        <input type="file" style="font-size:10px" name="reqLinkFile2" id="reqLinkFile2" class="easyui-validatebox" accept="application/pdf" />
                        <? if ($checkfile2)
                        {
                        ?>
                        <a href="<?=$tempPegawaiFile2?>" target="_blank"><img src="../WEB/images/down_icon.png" title="download" /></a>
                        <?
                        }
                        ?>

                    </td>        
                </tr>
                 <tr>
                    <td>Upload foto (*max ukuran file 2mb)</td>
                    <td>
                        <input type="file" style="font-size:10px" placeholder="*max ukuran file 2mb" name="reqFotoFile" id="reqFotoFile" class="easyui-validatebox" accept="image/*" />
                        <? if ($checkfilefoto)
                        {
                        ?>
                        <a href="<?=$tempPegawaiFoto?>" target="_blank"><img src="../WEB/images/down_icon.png" title="download" /></a>
                        <?
                        }
                        ?>
                    </td>        
                </tr>
               <!--  <tr>
                    <td>Upload Form Data Critical Incident</td>
                    <td>
                        <input type="file" style="font-size:10px" name="reqLinkFile3" id="reqLinkFile3" class="easyui-validatebox" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" />
                        <? if ($checkfile3)
                        {
                        ?>
                        <a href="<?=$tempPegawaiFile3?>" target="_blank"><img src="../WEB/images/down_icon.png" title="download" /></a>
                        <?
                        }
                        ?>
                    </td>        
                </tr> -->
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
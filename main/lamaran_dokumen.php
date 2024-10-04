<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Diklat.php");
include_once("../WEB/classes/base-diklat/DiklatDokumen.php");

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;

$reqId = httpFilterGet("reqId");

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlupload= $data->urlConfig->main->urlupload;
$urlupload.="diklatlampiran/".$reqId."/".$tempUserPelamarId."/";
$FILE_DIR = $urlupload;
makedirs($FILE_DIR);

$urlfoto= $data->urlConfig->main->urlfoto;
$urlfoto.=$tempUserPelamarId."/";
$FILE_DIR_PRIBADI = $urlfoto;
// echo $FILE_DIR;exit();

$urluploadcontoh= $data->urlConfig->main->urlupload;
$urluploadcontoh.="diklatdokumencontoh/".$reqId."/";

$set= new DiklatDokumen();
$set->selectByParamsDiklat(array("A.DIKLAT_ID" => $reqId));
$set->firstRow();
$reqNamaDiklat= $set->getField("NAMA_DIKLAT");

$lowongan_dokumen = new DiklatDokumen();
$lowongan_dokumen->selectByParams(array("A.DIKLAT_ID" => $reqId), -1, -1, " ORDER BY A.DOKUMEN_ID ASC");
//echo $lowongan_dokumen->query;exit;
?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			url:'../json/lamaran_dokumen_add.php',
			onSubmit:function(){
				var win = $.messager.progress({
									title:'Upload Data',
									msg:'Mengupload data...'
								});							
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);return false;
				$.messager.progress('close');	
				if(data == '')
				{
					document.location.reload(true);
				}
				else
				{
					$.messager.alert("Perhatian", data, 'warning');
				}
				$('input:file').MultiFile('reset');
			}
		});

		
	});
	
</script>

<!-- UPLOAD CORE -->
<script src="../WEB/lib/multifile-master/jquery.MultiFile.js"></script>
<script>
	// wait for document to load
	$(function(){
		
		// invoke plugin
		$('#reqLampiran').MultiFile({
		onFileChange: function(){
			console.log(this, arguments);
		}
		});
	
	});
</script>	


<div class="col-lg-8 sisi-kiri">

    <div id="judul-halaman" style="font-size: 15px !important">Dokumen yang di butuhkan pada diklat > <?=$reqNamaDiklat?></div>
    
    <div class="data-lampiran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
        <table>
        <?
        $index_data=0;
        while($lowongan_dokumen->nextRow())
		{
            $reqRowId= $lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID");
            $setFormat= $lowongan_dokumen->getField("FORMAT");
            if($setFormat == "jpg,jpeg,png")
                $setFormat= "png";

            $tempFileDiklat= $urluploadcontoh.$reqRowId.".".$setFormat;
            // echo $tempFileDiklat;

            $tempStatusFileDiklat= "";
            if(file_exists("$tempFileDiklat"))
                $tempStatusFileDiklat= "1";
		?>
            <tr>
            <td>
                <?=$lowongan_dokumen->getField("NAMA")?>
                <?
                if($lowongan_dokumen->getField("WAJIB") == "1")
				{
				?>
                <font color="#FF0000">*</font>
                <?
				}
				?>
                <ol style="list-style:square">
                    <li>Ukuran maksimum 300KB</li>
                    <li>file harus (<?=$lowongan_dokumen->getField("FORMAT")?>)</li>
                    <?
                    if($tempStatusFileDiklat == "1")
                    {
                    ?>
                    <li>
                        contoh lampiran 
                        <?
                        echo " <img onclick=\"getdiklatdokumen('".$setFormat."', '".$reqRowId."')\" style=\"cursor:pointer\" src=\"../WEB/images/icon-download.png\" width=\"15\" height=\"15\">";
                        ?>
                    </li>
                    <?
                    }
                    ?>
                </ol>
            </td>
            <td>
            	<div id="reqDivButton<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>"><input class="btn btn-info btn-sm" id="reqButtonLampiranCV" type="button" value="Ubah" onClick='$("#reqDivButton<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>").hide(); $("#reqDivLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>").show();' /></div>

            	<?
            	if($lowongan_dokumen->getField("DOKUMEN_ID") == "")
            	{
                    $reqFormat= $lowongan_dokumen->getField("FORMAT");
                    $acceptfile= "image/*";
                    if($reqFormat == "jpg,jpeg,png"){}
                    elseif($reqFormat == "pdf")
                    {
                        $acceptfile= "application/pdf";
                    }
            	?>
                <div id="reqDivLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>">
                	<span class="btn btn-default btn-file btn-sm">
                        Browse File <input name="reqLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>[]" type="file" multiple class="maxsize-20240" id="reqLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>" value="" accept="<?=$acceptfile?>" /></span>
                </div>
                <?
            	}
            	else
            	{
            		$reqFormat= $lowongan_dokumen->getField("FORMAT");
            		if($reqFormat == "jpg,jpeg,png")
            		{
            			$reqFormat= "png";
            		}

            		$oldfile= $FILE_DIR_PRIBADI.$lowongan_dokumen->getField("PENAMAAN_FILE").".".$reqFormat;
            		$newfile= $FILE_DIR.$lowongan_dokumen->getField("PENAMAAN_FILE").".".$reqFormat;
            		if(file_exists("$newfile")){}
            		else
					{
            			// copy data dari pribadi
						if(!copy($oldfile,$newfile))
						{
            				// echo $oldfile;
						}
            		}

            		// 
            		// 
            	}
                ?>
                
                <script>
                // wait for document to load
                $(function(){
					<? 
					// if($lowongan_dokumen->getField("LINK_FILE") == "") 
					// {
					?>
					$("#reqDivButton<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>").hide();
					$("#reqDivLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>").show();
					<?
					// }
					// else
					// {
					?>
					// $("#reqDivButton<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>").show();
					// $("#reqDivLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>").hide();
					<?
					// }
					?>
					
                    // invoke plugin
                    $('#reqLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>').MultiFile({
                    onFileChange: function(){
    
						$("#reqNamaDokumen").val("<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>");
						$("#reqFormat").val("<?=$lowongan_dokumen->getField("FORMAT")?>");
						$("#reqNamaLampiran").val("reqLampiran<?=$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID")?>");
						$("#btnSimpan").click();
                            
                    }
                    });
                
                });
                
                </script>                
            </td>    
            <td>
            <? 
            $reqFormat= $lowongan_dokumen->getField("FORMAT");
            if($reqFormat == "jpg,jpeg,png")
            {
            	$reqFormat= "png";
            }
            if($lowongan_dokumen->getField("PENAMAAN_FILE") == "")
            $tempFIleCheck= $FILE_DIR.$lowongan_dokumen->getField("DIKLAT_DOKUMEN_ID").".".$reqFormat;
        	else
            $tempFIleCheck= $FILE_DIR.$lowongan_dokumen->getField("PENAMAAN_FILE").".".$reqFormat;

            // echo $tempFIleCheck;
            // $tempFIleCheck= $oldfile;
            // echo $tempFIleCheck;

            if(file_exists("$tempFIleCheck"))
			{
			?>
            <a href="<?=$tempFIleCheck?>" target="_blank"><i class="fa fa-download"></i> download</a>
            <?
			}
			?>
            </td>
            </tr>
        <?
        $index_data++;
        }
		?>
        <?
        if($index_data == 0)
        {
        ?>
        <tr><td>Tidak Ada Dokumen untuk keperluan diklat</td></tr>
        <?
        }
        ?>
        </table>    
        <br>   
        <div>
        
            <input name="reqId" id="reqId" type="hidden" value="<?=$reqId?>" />
            <input name="reqPelamarId" id="reqPelamarId" type="hidden" value="<?=$tempUserPelamarId?>" />
            <input name="reqNamaDokumen" id="reqNamaDokumen" type="hidden" value="" />
            <input name="reqFormat" id="reqFormat" type="hidden" value="" />
            <input name="reqNamaLampiran" id="reqNamaLampiran" type="hidden" value="" />      
            <input id="btnSimpan" type="submit" value="Submit" style="display:none">
        </div>
        </form>

    </div>
    
</div>

<script type="text/javascript">
    function getdiklatdokumen(setFormat, reqTempPesertaId)
    {
        varurl= "<?=$urluploadcontoh?>"+reqTempPesertaId+"."+setFormat;
        newWindow = window.open(varurl, 'Cetak');
        newWindow.focus();
    }
</script>
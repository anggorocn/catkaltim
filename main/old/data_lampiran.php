<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Dokumen.php");

$userLogin->checkLoginPelamar();

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);
$urlfoto= $data->urlConfig->main->urlfoto;
$urlfoto.="/".$tempUserPelamarId."/";
$FILE_DIR = $urlfoto;
makedirs($FILE_DIR);

$reqId = httpFilterGet("reqId");
$reqKelompokPegawai = httpFilterGet("reqKelompokPegawai");

$tempPelamarId = $tempUserPelamarId;

$reqMode = "update";

$pelamar_dokumen = new Dokumen();
$statement = " AND A.STATUS_AKTIF = '1'";
$pelamar_dokumen->selectByParams(array(), -1, -1, $statement, " ORDER BY A.DOKUMEN_ID ASC");
// echo $pelamar_dokumen->query;exit;

$arrData = "";
$index_data = 0;
while($pelamar_dokumen->nextRow())
{
	$arrData[$index_data]["PELAMAR_ID"] = $tempPelamarId;
	$arrData[$index_data]["DOKUMEN_ID"] = $pelamar_dokumen->getField("DOKUMEN_ID");
	$arrData[$index_data]["NAMA"] = $pelamar_dokumen->getField("NAMA");
	$arrData[$index_data]["FORMAT"] = $pelamar_dokumen->getField("FORMAT");
	$arrData[$index_data]["WAJIB"] = $pelamar_dokumen->getField("WAJIB");
	$arrData[$index_data]["LAMPIRAN"] = $pelamar_dokumen->getField("LAMPIRAN");
	$arrData[$index_data]["PENAMAAN_FILE"] = $pelamar_dokumen->getField("PENAMAAN_FILE");
	$index_data++;
}
$tempJumlahData = $index_data;
//echo $pelamar_dokumen->query;exit;
?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		setButtonUbah();		
		$('input[id^="reqButtonLampiran"]').click(function() {
			var id= $(this).attr('id');
			id= id.replace("reqButtonLampiran", "");
			
			$("#reqDivButton"+id).hide();
			$("#reqDivLampiran"+id).show();
		});
	});
	
	function setButtonUbah()
	{
		//alert('a');
		<?
		for($checkbox=0; $checkbox<$tempJumlahData; $checkbox++)
		{
			if($arrData[$checkbox]["LAMPIRAN"]=="")
			{
		?>
			$("#reqDivButton<?=$arrData[$checkbox]["DOKUMEN_ID"]?>").hide();
			$("#reqDivLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>").show();
		<?
			}
			else
			{
		?>
			$("#reqDivButton<?=$arrData[$checkbox]["DOKUMEN_ID"]?>").show();
			$("#reqDivLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>").hide();
		<?
			}
		}
		?>
	}
</script>

<!-- UPLOAD CORE -->
<script src="../WEB/lib/multifile-master/jquery.MultiFile.js"></script>
<script>
	// wait for document to load
	$(function(){
		$('#ff').form({
			url:'../json/data_lampiran_add.php',
			onSubmit:function(){
				var win = $.messager.progress({
									title:'Upload Data',
									msg:'Mengupload data...'
								});							
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
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
		
		// invoke plugin
		$('#reqLampiran').MultiFile({
		onFileChange: function(){
			console.log(this, arguments);
		}
		});
	
	});
</script>	


<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_lampiran"]?></div>
    <div class="data-lampiran">
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
        	<?
			for($checkbox=0; $checkbox<$tempJumlahData; $checkbox++)
			{
            ?>
            <table>
                <tr>
                    <td>
                        <?=$arrData[$checkbox]["NAMA"]?> &nbsp;
                        <?
                        if($arrData[$checkbox]["WAJIB"] == 1)
                        {
                        ?>
                        <img src="../WEB/images/star.png" width="10" height="10">
                        <?
                        }
                        ?>
                        <ol style="list-style:square">
                            <li>Ukuran maksimum 300KB</li>
                            <li>file harus (<?=$arrData[$checkbox]["FORMAT"]?>)</li>
                        </ol>
                    </td>
                    <td align="center">
                        <div id="reqDivButton<?=$arrData[$checkbox]["DOKUMEN_ID"]?>"><input class="btn btn-info btn-sm" id="reqButtonLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>" type="button" value="Ubah" /></div>
                        <div id="reqDivLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>">
                            <span class="btn btn-default btn-file btn-sm">
                                <?
                                $reqFormat= $arrData[$checkbox]["FORMAT"];
                                $acceptfile= "image/*";
                                if($reqFormat == "jpg,jpeg,png"){}
                                elseif($reqFormat == "pdf")
                                {
                                    $acceptfile= "application/pdf";
                                }

                                ?>
                                Browse File <input name="reqLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>[]" type="file" multiple class="maxsize-20240" id="reqLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>" value="" accept="<?=$acceptfile?>" />
                                <input name="reqRowId" id="reqRowId<?=$arrData[$checkbox]["DOKUMEN_ID"]?>" type="hidden" value="<?=$arrData[$checkbox]["PELAMAR_DOKUMEN_ID"]?>" />
                            </span>
                        </div>
                        
                        <script>
                        // wait for document to load
                        $(function(){
                           
                            // invoke plugin
                            $('#reqLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>').MultiFile({
                            onFileChange: function(){
                                $("#reqDokumenId").val("<?=$arrData[$checkbox]["DOKUMEN_ID"]?>");
                                $("#reqRowId").val("<?=$arrData[$checkbox]["PELAMAR_DOKUMEN_ID"]?>");
                                $("#reqFormat").val("<?=$arrData[$checkbox]["FORMAT"]?>");
                                $("#reqPenamaanFile").val("<?=$arrData[$checkbox]["PENAMAAN_FILE"]?>");
                                $("#reqPelamarId").val("<?=$tempPelamarId?>");
                                $("#reqNamaLampiran").val("reqLampiran<?=$arrData[$checkbox]["DOKUMEN_ID"]?>");
                                $("#btnSimpan").click();
                            }
                            });
                        });
                        </script>
                    </td>
                    </td>    
                    <td>
                    <?
                    $reqFormat= $arrData[$checkbox]["FORMAT"];
                    if($reqFormat == "jpg,jpeg,png")
                    {
                    	$reqFormat= "png";
                    }

                    $tempFIleCheck= $FILE_DIR.$arrData[$checkbox]["PENAMAAN_FILE"].".".$reqFormat;
                    if(file_exists("$tempFIleCheck"))
                    {
                    ?>
                    <a href="<?=$tempFIleCheck?>" target="_blank"><i class="fa fa-download"></i> download</a>
                    <?
                    }
                    ?>
                    </td>
                </tr>
            </table>    
            <?
			}
            ?>
            <div>
            	<input name="reqRowId"  id="reqRowId" type="hidden" value="" />
            	<input name="reqPelamarId"  id="reqPelamarId" type="hidden" value="" />
            	<input name="reqFormat"  id="reqFormat" type="hidden" value="" />
            	<input name="reqDokumenId"  id="reqDokumenId" type="hidden" value="" />
            	<input name="reqPenamaanFile"  id="reqPenamaanFile" type="hidden" value="" />
                <input name="reqNamaDokumen" id="reqNamaDokumen" type="hidden" value="" />     
                <input name="reqNamaLampiran" id="reqNamaLampiran" type="hidden" value="" />      
                <input id="btnSimpan" type="submit" value="Submit" style="display:none">
            </div>            
            </form>

        <table>
            <tr>
              <td>
              	<span class="spanItalicKpk">Keterangan: </span>
              	<br/><img src="../WEB/images/star.png" width="10" height="10"><i>Harus diisi</i> 
              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>  
            <!-- <tr>
              <td colspan="3">
              	<span class="spanItalicKpk">Dokumen Peserta diklat, yang sering di perlukan.</span>
              </td>
            </tr>   -->
        </table>   
        
        <br>   
        <form id="ss" method="post" novalidate enctype="multipart/form-data">
            <input name="reqNamaDokumen" id="reqNamaDokumen" type="hidden" value="" />     
            <input name="reqNamaLampiran" id="reqNamaLampiran" type="hidden" value="" />      
            <input id="btnSimpan" type="submit" value="Submit" style="display:none">
        </form>
        
    </div>
    
</div>
<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/FileHandler.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalAsesorPotensiPegawai.php");
include_once("../WEB/classes/base/PermohonanFile.php");
include_once("../WEB/classes/base/Penggalian.php");
/* LOGIN CHECK */

if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

/* VARIABLE */
$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

$set= new JadwalTes();
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;
$tempTanggalTes= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempTanggalTesAkhir= getFormattedDateTime($set->getField('TANGGAL_TES_AKHIR'), false);
// $tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), 'date');
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
$reqStatusValid= $set->getField('STATUS_VALID');
$reqFormulaAssesmentId= $set->getField('FORMULA_ASSESMENT_ID');
$reqInfoStatusNama= $set->getField('INFO_STATUS_NAMA');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$infotahun= getDay(datetimeToPage($set->getField('TANGGAL_TES'), 'date'));

$tempStatusValidasi= $reqStatusValid;

$reqTipe= $set->getField("TIPE");

$statement= " AND A.JADWAL_TES_ID = ".$reqId;
$set_detil= new JadwalAsesorPotensiPegawai();
$index_loop= $set_detil->getCountByParams(array(), $statement);
// echo $set_detil->query;exit();

// $arrfilejenis= jenisfiletest();
$idata=0;
$arrfilejenis= [];
$setdetil= new Penggalian();
$setdetil->selectByParams(array(), -1, -1, " AND A.TAHUN = '".$infotahun."' AND A.KODE != 'PT' ");
// echo $setdetil->query;exit;
while($setdetil->nextRow())
{
    $arrfilejenis[$idata]["id"]= $setdetil->getField("PENGGALIAN_ID");
    $arrfilejenis[$idata]["nama"]= $setdetil->getField("NAMA");
    $arrfilejenis[$idata]["kode"]= $setdetil->getField("KODE");
    $idata++;
}
$jumlahpenggalian= $idata;
// print_r($arrfilejenis);exit;

$reqkuncijenis= $reqId;
$reqfolderjenis= "jadwaltes".$reqkuncijenis;
$reqJenis= $reqfolderjenis."-soal";

$tinggi = 285;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	$(function(){
		$('#ff').form({
			url:'../json-pengaturan/master_jadwal_add_file.php',
			onSubmit:function(){
				if($(this).form('validate'))
				{
					$.messager.progress({title:'Proses data.',msg:'Proses data...'});
					var bar = $.messager.progress('bar');
					bar.progressbar({text: ''});
				}
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.progress('close');
				// console.log(data);return false;
				data = data.split("-");
				reqId= data[0];

				$.messager.alert('Info', data[1], 'info');
				if(reqId == "xxx")
				{
					return false;
				}
				$('#rst_form').click();
				// document.location.href = 'master_jadwal_add_file.php?reqId=<?=$reqId?>';
			}
		});
		
	});

    function hapusfile(id)
    {
        infourl= '../json-pengaturan/master_jadwal_upload_hapus.php?reqId='+id;
        konfirmasi= "Hapus file ?";
        $.messager.confirm('Konfirmasi',konfirmasi,function(r){
            if (r){
                $.ajax({
                    url: infourl,
                    method: 'GET',
                    success: function (response) {
                        // console.log(response);return false;
                        document.location.href = 'master_jadwal_add_file.php?reqId=<?=$reqId?>';
                    },
                    error: function (response) {
                    },
                    complete: function () {
                    }
                });
            }
        });
    }
	
</script>
<style type="text/css" media="screen">
  label {
	clear: both;
  }
</style>
<style type="text/css">
	/* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
	html, body {height:100%; margin:0; padding:0;}
	/* Set the position and dimensions of the background image. */
	#page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index value than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
	#content {position:relative; z-index:1;}
	/* prepares the background image to full capacity of the viewing area */
	#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* places the content ontop of the background image */
	#content {position:relative; z-index:1;}
</style>
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; width:100%">
	<div id="header-tna-detil">Proses <span>Formula</span></div>
	<form id="ff" method="post" novalidate enctype="multipart/form-data">
    <table class="gradient-style" cellspacing="1" style="width: 100%; margin-left: 0px">
        <tr>
            <th width="200px">Formula</th>
            <td width="2px">:</td>
            <td><label id="reqFormulaEselon"><?=$tempFormulaEselon?></label></td>
        </tr>
        <tr>
            <th>Tanggal Tes</th>
            <td>:</td>
            <td><?=$tempTanggalTes?></td>
        </tr>
        <tr>
            <th>Acara</th>
            <td>:</td>
            <td><?=$tempAcara?></td>
        </tr>
        <tr>
            <th>Tempat</th>
            <td>:</td>
            <td><?=$tempTempat?></td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>:</td>
            <td><?=$tempAlamat?></td>
        </tr>
        <tr>
            <th>Total Peserta</th>
            <td>:</td>
            <td>
            	<label id="reqInfoTotalPeserta"><?=$index_loop?></label>
    			<input type="hidden" id="reqPegawaiId" name="reqPegawaiId" />
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">File</td>
            <td style="vertical-align: top;">:</td>
            <td>
                <table class="gradient-style" cellspacing="1" style="width: 100%; margin-left: 0px">
                    <tr>
                        <th>Jenis</th>
                        <th style="width: 20%">Upload</th>
                        <th style="width: 10%">Download</th>
                    </tr>
                    <?
                    for($x=0; $x < count($arrfilejenis); $x++)
                    {
                        $infoid= $arrfilejenis[$x]["id"];
                        $infolabel= $arrfilejenis[$x]["nama"];
                        $infojenislabel= $arrfilejenis[$x]["kode"];

                        $setdetil= new PermohonanFile();
                        $setdetil->selectByParams(array("A.PERMOHONAN_TABLE_ID"=>$reqkuncijenis, "A.PERMOHONAN_TABLE_NAMA"=>$reqJenis, "A.PEGAWAI_ID"=>$infojenislabel));
                        $setdetil->firstRow();
                        // echo $setdetil->query;exit;
                        $inforowid= $setdetil->getField("PERMOHONAN_FILE_ID");
                        $infolinkfile= $setdetil->getField("LINK_FILE");
                        $infoketerangan= $setdetil->getField("KETERANGAN");
                    ?>
                    <tr>
                        <td><?=$infolabel?></td>
                        <td>
                            <input id="reqFile" accept="application/pdf" name="reqLinkFile[]" type="file" maxlength="6" class="multi maxsize-10240" value="" />
                            <input type="hidden" name="reqFileJenisId[]" value="<?=$infoid?>" />
                            <input type="hidden" name="reqFileJenisKode[]" value="<?=$infojenislabel?>" />
                        </td>
                        <td style="text-align: center;">
                            <?
                            if(file_exists($infolinkfile))
                            {
                            ?>
                                <a href="<?=$infolinkfile?>" target="_blank">
                                    <img target="" style="cursor: pointer;" height="20" width="20" src="../WEB/images/icon_pilih.png" title="<?=$infoketerangan?>" />
                                </a>
                                <a href="javascript:void(0)" onclick="hapusfile('<?=$inforowid?>')">
                                    <img target="" style="cursor: pointer;" height="20" width="20" src="../WEB/images/delete-icon.png" title="<?=$infoketerangan?>" />
                                </a>
                            <?
                            }
                            else
                            {
                            ?>
                                <img style="cursor: pointer;" height="20" width="20" src="../WEB/images/icon_uncheck.png" title="file belum ada" />
                            <?
                            }
                            ?>
                        </td>
                    </tr>
                    <?
                    }
                    ?>
                <!--  -->
                </table>
            </td>
        </tr>
        <?
		if($reqId == ""){}
		else
		{
        ?>
        <tr>
        	<td colspan="3">
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqMode" value="insert" />
                <input type="submit" name="" value="Simpan" />
            </td>
        </tr>
        <?
		}
        ?>
    </table>
	</form>
    </div>

</div>
<script>
//$('input[id^="reqAnakNama"]').keyup(function(e) {
$('#reqBatasPegawai').bind('keyup paste', function(){
	this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>
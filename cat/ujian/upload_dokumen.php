<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/JadwalTes.php");

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$filesoal= new JadwalTes();
$filesoal->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $ujianPegawaiJadwalTesId),-1,-1,'');
$filesoal->firstRow();
$tempLinkSoal= $filesoal->getField('LINK_SOAL');
//echo $filesoal->query;exit; 
$linkfile= str_replace("../upload", "../../assesment/upload", $tempLinkSoal);


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ASSESSMENT CENTER</title>

<link rel="shortcut icon" href="../WEB/images/favicon.ico" type="image/x-icon">
<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib-ujian/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css-ujian/gaya.css" type="text/css">

<link rel="stylesheet" href="../WEB/lib-ujian/font-awesome/4.5.0/css/font-awesome.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
    $(function(){
        $('#ff').form({
            url:'../json-ujian/upload_file_ujian.php',
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
<div id="my-welcome-message">
    <div class="konten-welcome">
    <div class="row">
        <div class="col-md-12">
            <div class="area-judul-halaman" style="text-align: center"> IN TRAY </div>
             
            <div class="area-tatacara" style="text-align: left"> 
                <span>Download File Test IN TRAY : <a href="<?=$linkfile?>" target="_blank">Download</a></span>
            </div>
            <br>
            <div class="area-tatacara" style="text-align: left"> 
                <span>Upload File Test IN TRAY yang sudah Anda Kerjakan</span>
            </div>
            
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <div style="  margin-left: auto;  margin-right: auto;"> <input style="align-items: center" type="file" style="font-size:15px" name="reqLinkFile" id="reqLinkFile" class="easyui-validatebox" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword"/> </div>
                <div>
                <input type="hidden" name="reqRowId" value="<?=$tempRowId?>">
                <input type="hidden" name="reqId" value="<?=$reqId?>">
                <input type="hidden" name="reqMode" value="<?=$reqMode?>">  
                <div style="text-align: center">
                    <!-- <input id="reqSubmit" type="submit" value="Simpan"> -->
                    <button class="ke-home"  id="reqSubmit" type="submit" style="color: white; height: 30px;border-radius: 5px;background-color: #216aac; align-items: right">Simpan </button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
</div>
<script>
</script>
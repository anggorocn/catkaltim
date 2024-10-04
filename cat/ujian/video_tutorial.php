<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");



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
            <div class="area-tatacara" style="text-align: center"> 
            </div>
            <form id="ff" method="post" novalidate enctype="multipart/form-data">
                <center>
                    <video width="720" height="480" controls>
                        <source src="../WEB/video/tutorial.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video> 
                </center>
            </form>
        </div>
    </div>
    </div>
</div>
<script>
</script>
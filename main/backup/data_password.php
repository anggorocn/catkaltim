<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

$lang = $_SESSION['lang'];

$arrayJudul= "";
$arrayJudul= setJudul($lang);

$tempUserLogin= $userLogin->idUser;
?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		$('#reqIdLihatPassword').click(function () {
			if($(this).prop('checked')) {
			   // do what you need here
			   $('#reqPassword').prop('type','text');
			   //alert("Checked");
			}
			else {
			   // do what you need here
			   $('#reqPassword').prop('type','password');
			   //alert("Unchecked");
			}
		});
	
		$('#ff').form({
			url:'../json/reset_password_json.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				$.messager.alert('Info', data, 'info');
				document.location.href = 'index.php';
			}
		});
		
	});
	
</script>

<div class="col-lg-8">

    <div id="judul-halaman"><?=$arrayJudul["index"]["data_password"]?></div>
    <span class="judul-section">
    Password Anda telah di reset oleh Panitia Seleksi Angkasa Pura Support, Silahkan ubah password Anda.
    </span>
    <div class="judul-halaman2"><img src="../WEB/images/icon-input.png"> Form <?=$arrayJudul["index"]["data_password"]?></div>
    <div id="pendaftaran">
        <form id="ff" method="post" novalidate enctype="multipart/form-data">
            <table>
                <tr>
                    <td>User Login</td>
                    <td>:</td>
                    <td>
                        <input name="reqUserLogin" id="reqUserLogin" size="60" type="hidden" readonly value="<?=$tempUserLogin?>" />
                        <?=$tempUserLogin?>
                    </td>
                </tr>
                <tr>
                    <td>User Password <input type="checkbox" title="Lihat Password" id="reqIdLihatPassword" /></td>
                    <td>:</td>
                    <td>
                        <input name="reqPassword" id="reqPassword" class="easyui-validatebox" required style="width:100%;" type="password"  />
                    </td>
                </tr>
            </table>
            <br>
            <div>
                <input type="hidden" name="reqId" id="reqId" value="<?=md5($userLogin->UID)?>" />
                <input id="reqSubmit" type="submit" value="Submit">
            </div>
        </form>
    </div>
    
</div>
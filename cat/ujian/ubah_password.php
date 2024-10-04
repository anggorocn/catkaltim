<?
include_once("../WEB/classes/utils/UserLogin.php");

$tempUserLogin= $userLogin->idUser;
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
$(function(){
	$('#reqIdLihatPassword').click(function () {
		if($(this).prop('checked')) {
		   // do what you need here
		   $('#reqUserPassword').prop('type','text');
		   //alert("Checked");
		}
		else {
		   // do what you need here
		   $('#reqUserPassword').prop('type','password');
		   //alert("Unchecked");
		}
	});
	
	$('#ff').form({
		url:'../json-main/ubah_password.php',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data){
			//alert(data);return false;
			$.messager.alert('Info', data, 'info');
			<?php /*?>$('#rst_form').click();
			top.frames['mainFrame'].location.reload();
			<? if($reqMode=="update"){?>$('.closeImg', top.document).click();<? }?><?php */?>
		}
	});
	
});
</script>

<div class="container-fluid">
	<!--<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Ubah Password</div>
        </div>
    </div>
    -->
	<div class="row">
    	<div class="col-md-12">
        	<div class="area-ubah-password">
            	<form id="ff" method="post" novalidate>
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
                            <input name="reqUserPassword" id="reqUserPassword" class="easyui-validatebox" required size="60" type="password"  />
                        </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>
                      <input type="hidden" name="reqId" id="reqId" value="<?=$userLogin->UID?>" />
                      <input type="submit" value="Simpan" class="btn btn-info"> <?php /*?><input type="reset" value="Reset" class="btn btn-warning"><?php */?></td>
                    </tr>
                </table>
                </form>
            </div>
        </div>
        
    </div>
</div>

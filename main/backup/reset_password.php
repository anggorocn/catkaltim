<?

include_once("../WEB/classes/base/UsersBase.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");

$reqId = httpFilterGet("reqId");
$reqValidasi = httpFilterGet("reqValidasi");

$user = new UsersBase();

/*if($reqValidasi == md5(date("dmY")))
{}
else
{
	echo '<script language="javascript">';
	echo 'alert("Sesi ubah password anda telah habis, silahkan request kembali, Terima Kasih.");';
	echo 'top.location.href = "index.php";';
	echo '</script>';
	exit;		
}*/

$user->selectByParamsSimple(array("MD5(USER_LOGIN_ID::VARCHAR)" => $reqId));
$user->firstRow();
?>

<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			url:'../json/reset_password_json.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);
				document.location.href = "index.php";
			}
		});
		
	});
	
</script>

<div class="col-lg-8">
	<div id="judul-halaman">Reset Password</div>
    <div id="data-form">
		

    	<form id="ff" method="post" novalidate enctype="multipart/form-data">
        	<div class="password-area">
                <div class="body">
                	<div class="alert alert-warning" role="alert"><?=$user->getField("NAMA")?>, silahkan masukkan password baru anda.</div>
                    
                    <div class="row">
                    	<div class="col-md-6 col-md-offset-2">
                            <div class="form-group ">
                                <label for="email">Password Baru:</label>
                                <input type="password" class="form-control" id="reqPassword" name="reqPassword">
                                <input type="hidden" name="reqId" value="<?=$reqId?>">
                            </div>
                            <button type="submit" class="btn btn-info">Submit</button>
                    	</div>
                    </div>
                    
                
                </div>
                
            </div>
        </form>
        
    
    </div>
    

</div>


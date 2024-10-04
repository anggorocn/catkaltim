<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Pelamar.php");
//$tempLinkCssSecurity= $INDEX_SUB."/assets/valsix/WEB/";
//$tempLinkSecurity= $INDEX_SUB."/";

$tempLinkCssSecurity= $INDEX_SUB."../WEB/";
$tempLinkSecurity= $INDEX_SUB."../";
?>

<?
if($userLogin->userPelamarId == "")
{
?>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/global-tab-easyui.js"></script>

<script type="text/javascript">
	var tempCaptchaLogin= "";
    $.extend($.fn.validatebox.defaults.rules, {
        validCaptcha:{
            validator: function(value, param){
                var reqSecurity= panjang= "";
                reqSecurity= $("#reqSecurity").val();
                panjang= reqSecurity.length;
				
				if(panjang == 5)
				{
					//$.getJSON("<?=$tempLinkSecurity?>json/modul_captcha_login?characters=5&reqVal="+reqSecurity,
					$.getJSON("../json/login_captcha_validation_json.php?characters=5&reqVal="+reqSecurity,
					function(data){
						tempCaptchaLogin= data.VALUE_VALIDASI;
					});
                 }
				else
					tempCaptchaLogin= "";
                 
				 if(tempCaptchaLogin == '1')
				 {
                    return true;
					$('#ffLogin1').submit();
				 }
                 else
                    return false;
            },
            message: 'Security code yang anda masukkan salah.'
        },
		validLupaCaptcha:{
            validator: function(value, param){
                var reqSecurity= "";
                reqSecurity= $("#reqSecurityLupa").val();
                
				//$.getJSON("<?=$tempLinkSecurity?>json/modul_captcha_lupa?characters=5&reqVal="+reqSecurity,
				$.getJSON("../json/lupa_captcha_validation_json.php?characters=5&reqVal="+reqSecurity,
                function(data){
                    tempCaptcha= data.VALUE_VALIDASI;
                });
                 
                 if(tempCaptcha == '1')
                    return true;
                 else
                    return false;
            },
            message: 'Security code yang anda masukkan salah.'
        }
    });
	
    $(function(){
        $('#ffLogin1').form({
            url:'../json/login.php',
            onSubmit:function(){
                return $(this).form('validate');
            },
            success:function(data){
                //alert(data);
				if(data == "")
				document.location.href = 'index.php';
				else
                $.messager.alert('Info', data, 'info');
            }
        });
		
		$("#reqUser,#reqPasswd").keyup(function(e) {
			var reqUser= reqPasswd= reqTahun= "";
			reqUser= $("#reqUser").val();
			reqPasswd= $("#reqPasswd").val();
			
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			if(key == 13 || key == 9) 
			{
				if(reqUser !== "" && reqPasswd !== "")
				{
					$('#ffLogin1').submit();
				}
			}
		});
    });
</script>
<?
}
?>


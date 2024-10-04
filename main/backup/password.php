<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>

<script type="text/javascript">
	$(function(){
		$('#ff').form({
			url:'../json/password_json.php',
			onSubmit:function(){
				if(check_email($("#reqEmail").val()))
					return $(this).form('validate');
				else
				{
					$.messager.alert('Peringatan', "Format email salah.", 'warning');
					return false;					
				}
			},
			success:function(data){
				//alert(data);return false;
				$.messager.alert('Info', data, 'info');
			}
		});
		
	});
	
	function check_email(val){
		if(!val.match(/\S+@\S+\.\S+/)){ // Jaymon's / Squirtle's solution
			// Do something
			return false;
		}
		if( val.indexOf(' ')!=-1 || val.indexOf('..')!=-1){
			// Do something
			return false;
		}
		return true;
	}	
	
</script>

<div class="col-lg-8">
	<div id="judul-halaman"><?=$arrayJudul["password"]["judul"]?></div>
    <div id="data-form">
		

    	<form id="ff" method="post" novalidate enctype="multipart/form-data">
        	<div class="password-area">
            	<div class="header"><?=$arrayJudul["password"]["info"]?></div>
                <div class="body">
                	<div class="alert alert-warning" role="alert"><?=$arrayJudul["password"]["alert"]?></div>
                    
                    <div class="row">
                    	<div class="col-md-6 col-md-offset-2">
                            <div class="form-group ">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" id="reqEmail" name="reqEmail" data-options="validType:['email','validMail[\'\']']">
                            </div>
                            <button type="submit" class="btn btn-info">Submit</button>
                    	</div>
                    </div>
                    
                
                </div>
                
            </div>
        </form>
        
    
    </div>

</div>
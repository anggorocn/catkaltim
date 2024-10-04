<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

$tempListInfo= $userLogin->userTempList;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aplikasi Pelaporan Hasil Assesment</title>

<!-- BOOTSTRAP -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="../WEB/lib/bootstrap/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="../WEB/css/gaya-main.css" type="text/css">
<link rel="stylesheet" href="../WEB/css/gaya-assesor.css" type="text/css">
<link rel="stylesheet" href="../WEB/lib/Font-Awesome-4.5.0/css/font-awesome.css">
    
<!--<script type='text/javascript' src="../WEB/lib/bootstrap/jquery.js"></script> -->

    <style>
	.col-md-12{
		*padding-left:0px;
		*padding-right:0px;
	}
	</style>
    
    <script src="../WEB/lib/emodal/eModal.js"></script>
    <script>
	function openPopup() {
		//document.getElementById("demo").innerHTML = "Hello World";
		//alert('hhh');
		// Display a ajax modal, with a title
		eModal.ajax('konten.html', 'Judul Popup')
		//  .then(ajaxOnLoadCallback);
	}

	

	</script>
    
    <!-- FLUSH FOOTER -->
    <style>
	html, body {
		height: 100%;
	}
	
	#wrap-utama {
		min-height: 100%;
		*min-height: calc(100% - 10px);
	}
	
	#main {
		overflow:auto;
		padding-bottom:50px; /* this needs to be bigger than footer height*/
	}
	
	.footer {
		position: relative;
		margin-top: -50px; /* negative value of footer height */
		height: 50px;
		clear:both;
		padding-top:20px;
		*background:cyan;
		
		text-align:center;
		color:#FFF;
	}
	@media screen and (max-width:767px) {
		.footer {
			font-size:12px;
		}
	}

	</style>
    
    <style>
	#rbtn ul{
		list-style-type:none;
	}
	#rbtn ul li{
		cursor:pointer; 
		display:inline-block; 
		width:100px; 
		border:1px solid #06345f; 
		padding:5px; 
		margin-right:5px; 
		
		-moz-border-radius: 3px; 
		-webkit-border-radius: 3px; 
		-khtml-border-radius: 3px; 
		border-radius: 3px; 
		
		text-align:center;
		
	}
	.over{
		background: #063a69;
	}
	.selected{
		background: #063a69; 
		color:#fff;
	}
	.selected:before{
		font-family:"FontAwesome";
		content:"\f046";
		margin-right:10px;
		color:#f8a406;
		font-size:18px;
		vertical-align:middle;
	}
	</style>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
    <script>
	$(document).ready(function() {
  $(function(){
    $('#rbtn ul li').click(function(){
      // get the value from the id of the clicked li and attach it to the window object to be able to use it later.
      window.choice = this.id;
      $('#rbtn ul li').removeClass('selected');
      $(this).addClass('selected');
      }); 
 
      $('#rbtn ul li').mouseover(function(){
      $(this).addClass('over');
      });
 
      $('#rbtn ul li').mouseout(function(){
      $(this).removeClass('over');
    });
 
  }); //end function
}); //document ready
 
  function setChoice(){
    if(!$('#rbtn ul li.selected').click()){
      $('#mychoice').val(1);
      }else{
        // set the value of the hidden input field
        $('#mychoice').val(window.choice);
      }
  }
	</script>
</head>

<body>

<div id="wrap-utama" style="height:100%; ">
    <div id="main" class="container-fluid clear-top" style="height:100%;">
		
        <div class="row">
        	<div class="col-md-12">
            	<div class="area-header">
                	<span class="judul-app"><a href="index.php"><img src="../WEB/images/logo-kemendagri.png"> Aplikasi Pelaporan Hasil Assessment</a></span>
                    
                    <div class="area-akun">
                    	Selamat datang, <strong>Magdalena Doris</strong> (Tim Penilai Jabatan Fungsional Assessor) - 
                    	<a href="../main/login.php?reqMode=submitLogout">Logout</a>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="row" style="height:calc(100% - 20px);">
        	<div class="col-md-12" style="height:100%;">
            	
                
                <div class="container area-menu-app">
                	<div class="row">
                        <div class="col-md-12">
                        	<div class="breadcrumb"><a href="index.php"><i class="fa fa-home"></i> Home</a> &raquo; <a href="index2.php">Data Kegiatan</a> &raquo; Data Peserta</div>
                        	
                        	<div class="judul-halaman">Data Peserta Asesor :</div>
                        	<div class="area-table-assesor">
                            	<table>
                                <tbody>
                                	<tr>
                                    	<td>Nama</td>
                                        <td>:</td>
                                        <td><strong> Drs. DODI RIYADMADJI, MM </strong></td>
                                        <td rowspan="8" align="center">
                                        	<img src="../WEB/images/foto2.png" width="180">
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td>Jabatan Saat ini</td>
                                        <td>:</td>
                                        <td> KEPALA PUSAT DATA DAN SISTEM INFORMASI PADA SEKRETARIAT JENDERAL </td>
                                    </tr>
                                    <tr>
                                    	<td>Unit Kerja Saat ini</td>
                                        <td>:</td>
                                        <td> SISTEM INFORMASI SEKRETARIAT JENDERAL </td>
                                    </tr>
                                    <tr>
                                    	<td>Jabatan Saat Tes</td>
                                        <td>:</td>
                                        <td> KEPALA PUSAT DATA DAN SISTEM INFORMASI PADA SEKRETARIAT JENDERAL </td>
                                    </tr>
                                    <tr>
                                    	<td>Unit Kerja Saat Tes</td>
                                        <td>:</td>
                                        <td> SISTEM INFORMASI SEKRETARIAT JENDERAL </td>
                                    </tr>
                                    <tr>
                                    	<td>Nama Asesi</td>
                                        <td>:</td>
                                        <td> ASDASDAS </td>
                                    </tr>
                                    <tr>
                                    	<td>Metode</td>
                                        <td>:</td>
                                        <td> ASDAS </td>
                                    </tr>
                                    <tr>
                                    	<td>Tanggal Tes</td>
                                        <td>:</td>
                                        <td> 14 September 2016 </td>
                                    </tr>
                                    
                                </tbody>
                                </table>
                                
                                <br>
                              <div class="judul-halaman">Penilaian dan Catatan :</div>
                            	<table style="margin-bottom:60px;">
                                <thead>
                                	<tr>
                                	  <th width="51%">Hasil Individu</th>
                                	  <th width="49%">Catatan</th>
                              	  </tr>
                               	  </thead>
                                <tbody>
                                	<form action="custom-radio.php" name="form1" id="form1" method="post" onSubmit="setChoice();">
                                	<tr>
                                    	<td align="center">
                                        	<div id="rbtn">
                                              <ul>
                                                <li id="1" class="selected">1</li>
                                                <li id="2">2</li>
                                                <li id="3">3</li>
                                                    <li id="4">4</li>
                                                <li id="5">5</li>
                                              </ul>
                                            </div>
                                              
                                                
                                              
                                        </td>
                                        <td><textarea></textarea></td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2" align="center">
                                            <input name="mychoice" id="mychoice" type="hidden" value="" />
                                            <input name="submit1" type="submit" value="Submit" />
                                            <input type="reset" value="Reset">
                                        </td>
                                      </tr>
                                    </form>
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
		</div>
        
        
        
    </div>
</div>
<footer class="footer">
	 © 2016 Kementerian Dalam Negeri. All Rights Reserved. 
</footer>



    
<?php /*?>    <div class="container-fluid">
	
	
	<div class="row">
		<div class="col-md-12">
			<div class="area-footer">
			© 2016 Kementerian Dalam Negeri. All Rights Reserved. 
			</div>
		</div>
	</div>
	
</div>
<!-- /.container --> <?php */?>

<?php /*?><script type='text/javascript' src="../WEB/lib/bootstrap/bootstrap.js"></script> <?php */?>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 
    
</body>
</html>

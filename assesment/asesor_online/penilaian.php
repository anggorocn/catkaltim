<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/classes/base/Asesor.php");
include_once("../WEB/classes/base/JadwalAsesor.php");

// LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();  
}

$tempAsesorId= $userLogin->userAsesorId;

if($tempAsesorId == "")
{
	echo '<script language="javascript">';
	echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
	echo 'top.location.href = "../main/login.php";';
	echo '</script>';		
	exit;
}

$tempBulanSekarang= date("m");
$tempTahunSekarang= date("Y");

$tempBulanSekarang= date("m");
$tempSystemTanggalNow= date("d-m-Y");

// $tempBulanSekarang= '02';
// $tempTahunSekarang= "2018";
// $tempSystemTanggalNow= "01-02-2018";

$set= new Asesor();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$tempAsesorId);
$set->firstRow();
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorNama= $set->getField("NAMA");
$tempAsesorAlamat= $set->getField("ALAMAT");
$tempAsesorEmail= $set->getField("EMAIL");
$tempAsesorTelepon= $set->getField("TELEPON");
unset($set);

$pg = httpFilterRequest("pg");
$menu = httpFilterRequest("menu");

//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
//$statement= " AND (A.STATUS_PENILAIAN = '' OR A.STATUS_PENILAIAN IS NULL) AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
$statement= "";
$set= new JadwalAsesor();
$set->selectByParamsJumlahAsesorPegawai($statement, $tempAsesorId);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck(datetimeToPage($set->getField("TANGGAL_TES"), "date"));
	$arrAsesor[$index_loop]["JUMLAH"]= $set->getField("JUMLAH");
	$index_loop++;
}
$jumlah_asesor= $index_loop;
// print_r($arrAsesor);exit();
//$jumlah_asesor= 0;
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

<!-- Smart Wizard -->
<!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
<!-- Include SmartWizard CSS -->
	<link href="../WEB/lib/Flexible-Bootstrap-Plugin-To-Create-Wizard-Style-Interface-Smart-Wizard/dist/css/smart_wizard.css" rel="stylesheet" type="text/css" />

<!-- Optional SmartWizard theme -->
    <link href="../WEB/lib/Flexible-Bootstrap-Plugin-To-Create-Wizard-Style-Interface-Smart-Wizard/dist/css/smart_wizard_theme_circles.css" rel="stylesheet" type="text/css" />
    <link href="../WEB/lib/Flexible-Bootstrap-Plugin-To-Create-Wizard-Style-Interface-Smart-Wizard/dist/css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css" />
    <link href="../WEB/lib/Flexible-Bootstrap-Plugin-To-Create-Wizard-Style-Interface-Smart-Wizard/dist/css/smart_wizard_theme_dots.css" rel="stylesheet" type="text/css" />
    <style>
	.btn-toolbar{
		*display: none;
	}
	</style>

</head>

<body>

    <div id="wrap-utama" style="height:100%; ">
        <div id="main" class="container-fluid clear-top" style="height:100%;">

            <div class="row">
                <div class="col-md-12">
                    <div class="area-header">
                        <span class="judul-app"><a href="index.php"><img src="../WEB/images/logo-kemendagri.png"> Aplikasi Pelaporan Hasil Assessment</a></span>

                        <div class="area-akun">
                            Selamat datang, <strong><?=$tempAsesorNama?></strong> - 
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
                                <div class="breadcrumb"><i class="fa fa-home"></i> Home</div>
                                <div class="row profil-area" style="min-height:205px">
                                    <div class="col-md-2">
                                        <div class="profil-foto">
                                            <img id="reqImagePeserta" />
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                       <div class="judul-halaman">Profil</div>
                                       <table class="profil">
                                        <tr>
                                            <th style="width:165px">Tipe</th>
                                            <th style="width:5px">:</th>
                                            <td><?=$tempAsesorTipeNama?></td>
                                        </tr>
                                        <tr>
                                            <th>No SK</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorNoSk?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorNama?></td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorAlamat?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorEmail?></td>
                                        </tr>
                                        <tr>
                                            <th>Telepon</th>
                                            <th>:</th>
                                            <td><?=$tempAsesorTelepon?></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>

                    <?php /*?><!-- TAB -->
                    <div id="example-basic">
                        <h3>Keyboard</h3>
                        <section>
                            <p>Try the keyboard navigation by clicking arrow left or right!</p>
                        </section>
                        <h3>Effects</h3>
                        <section>
                            <p>Wonderful transition effects.</p>
                        </section>
                        <h3>Pager</h3>
                        <section>
                            <p>The next and previous buttons help you to navigate through your content.</p>
                        </section>
                    </div><?php */?>
                    
                    <!------>
                    <!-- SmartWizard html -->
                    <div id="smartwizard">
                        <ul>
                            <li><a href="#step-1">Step 1<br /><small>This is step description</small></a></li>
                            <li><a href="#step-2">Step 2<br /><small>This is step description</small></a></li>
                            <li><a href="#step-3">Step 3<br /><small>This is step description</small></a></li>
                            <li><a href="#step-4">Step 4<br /><small>This is step description</small></a></li>
                            <li><a href="#step-5">Step 5<br /><small>This is step description</small></a></li>
                            <li><a href="#step-6">Step 6<br /><small>This is step description</small></a></li>
                            <li><a href="#step-7">Step 7<br /><small>This is step description</small></a></li>
                            <li><a href="#step-8">Step 8<br /><small>This is step description</small></a></li>
                        </ul>
            
                        <div>
                            <div id="step-1" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 1 Content</h3>
                            </div>
                            <div id="step-2" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 2 Content</h3>
                            </div>
                            <div id="step-3" class="">
                            	<h3 class="border-bottom border-gray pb-2">Step 3 Content</h3>
                            </div>
                            <div id="step-4" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 4 Content</h3>
                            </div>
                            <div id="step-5" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 4 Content</h3>
                            </div>
                            <div id="step-6" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 4 Content</h3>
                            </div>
                            <div id="step-7" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 4 Content</h3>
                            </div>
                            <div id="step-8" class="">
                                <h3 class="border-bottom border-gray pb-2">Step 4 Content</h3>
                            </div>
                        </div>
                    </div>
                    <!------>
                


           </div>
           
           

       </div>
   </div>


   <div style="margin:40px">&nbsp;</div>
   
</div>
</div>
<footer class="footer">
  © 2020 Pemprov Bali. All Rights Reserved. 
</footer>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<script>
    $(function() {
       <?
       if($tempFotoLink == "")
       {
        ?>
        $("#reqImagePeserta").attr("src", "../WEB/images/no-picture.jpg");
        <?
    }
    else
    {
        ?>
        $("#reqImagePeserta").attr("src", "../uploads/<?=$tempPesertaKtp?>/foto/<?=$tempFotoLink?>");
        <?
    }
    ?>
});
</script>

<link href="../WEB/lib/responsive-calendar-0.9/css/responsive-calendar.css" rel="stylesheet">
<script src="../WEB/lib/responsive-calendar-0.9/js/responsive-calendar.js"></script>
<script type="text/javascript">
    function addLeadingZero(num) {
       if (num < 10) {
           return "0" + num;
       } else {
           return "" + num;
       }
   }

   function setModal(target, link_url)
   {
       var s_url= link_url;
       var request = $.get(s_url);

       request.done(function(msg)
       {
          if(msg == ''){}
              else
              {
                 $('#'+target).html(msg);
             }
         });
	//alert(target+'--'+link_url);
}

$(document).ready(function () {
	$(".responsive-calendar").responsiveCalendar({
     translateMonths: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
     time: '<?=$tempTahunSekarang?>-<?=$tempBulanSekarang?>',
     events: {
      "":{}
      <?
      for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
      {

          ?>
          ,"<?=$arrAsesor[$checkbox_index]["TANGGAL_TES"]?>":{"number": <?=$arrAsesor[$checkbox_index]["JUMLAH"]?>, "cssModif": "f8a406"}
          <?
      }
      ?>
  },
  onActiveDayClick: function(events) {
    var thisDayEvent, key;

		  //key = $(this).data('year')+'-'+addLeadingZero( $(this).data('month') )+'-'+addLeadingZero( $(this).data('day') );
		  key= addLeadingZero( $(this).data('day') )+'-'+addLeadingZero( $(this).data('month') )+'-'+$(this).data('year');
		  thisDayEvent = events[key];
		  
		  var link_url= 'index_detil.php?reqTanggalTes='+key;
		  setModal("reqTableKegiatan", link_url);
		  //alert(thisDayEvent.number);
		}
	});
});
</script>

<!-- SMART WIZARD -->
<!-- Include jQuery -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Include SmartWizard JavaScript source -->
    <script type="text/javascript" src="../WEB/lib/Flexible-Bootstrap-Plugin-To-Create-Wizard-Style-Interface-Smart-Wizard/dist/js/jquery.smartWizard.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            // Step show event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
               //alert("You are on step "+stepNumber+" now");
               if(stepPosition === 'first'){
                   $("#prev-btn").addClass('disabled');
               }else if(stepPosition === 'final'){
                   $("#next-btn").addClass('disabled');
               }else{
                   $("#prev-btn").removeClass('disabled');
                   $("#next-btn").removeClass('disabled');
               }
            });

            // Toolbar extra buttons
            var btnFinish = $('<button></button>').text('Finish')
                                             .addClass('btn btn-info')
                                             .on('click', function(){ alert('Finish Clicked'); });
            var btnCancel = $('<button></button>').text('Cancel')
                                             .addClass('btn btn-danger')
                                             .on('click', function(){ $('#smartwizard').smartWizard("reset"); });


            // Smart Wizard
            $('#smartwizard').smartWizard({
                    selected: 0,
                    theme: 'arrows',
                    transitionEffect:'fade',
                    showStepURLhash: true,
					
					// step bar options

					  toolbarSettings: {
						toolbarPosition: 'bottom', // none, top, bottom, both
						toolbarButtonPosition: 'right', // left, right
						showNextButton: false, // show/hide a Next button
						showPreviousButton: false, // show/hide a Previous button
						toolbarExtraButtons: []
					  },
					  
					  // anchor options
					  anchorSettings: {
						anchorClickable: true, // Enable/Disable anchor navigation
						enableAllAnchors: true, // Activates all anchors clickable all times
						markDoneStep: true, // add done css
						markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
						removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
						enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
					  }, 
					  
                    //toolbarSettings: {toolbarPosition: 'both',
//                                      toolbarButtonPosition: 'end',
//                                      toolbarExtraButtons: [btnFinish, btnCancel]
//                                    }
            });


            // External Button Events
            $("#reset-btn").on("click", function() {
                // Reset wizard
                $('#smartwizard').smartWizard("reset");
                return true;
            });

            $("#prev-btn").on("click", function() {
                // Navigate previous
                $('#smartwizard').smartWizard("prev");
                return true;
            });

            $("#next-btn").on("click", function() {
                // Navigate next
                $('#smartwizard').smartWizard("next");
                return true;
            });

            $("#theme_selector").on("change", function() {
                // Change theme
                $('#smartwizard').smartWizard("theme", $(this).val());
                return true;
            });

            // Set selected theme on page refresh
            $("#theme_selector").change();
        });
    </script>

</body>
</html>

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

$reqMode=httpFilterGet("reqMode");


$tempAsesorId= $userLogin->userAsesorId;
// echo $tempAsesorId; exit;
// if($tempAsesorId == "")
// {
//  echo '<script language="javascript">';
//  echo 'alert("anda tidak memeliki account pada aplikasi, hubungi administrator untuk lebih lanjut.");';
//  echo 'top.location.href = "../main/login.php";';
//  echo '</script>';       
//  exit;
// }

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
$reqTanggalTes= httpFilterGet("reqTanggalTes");
if($reqTanggalTes==''){
    $reqTanggalTes=date("d-m-Y");
}

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempAsesorNoSk.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
//$dateNow= date("d-m-Y");

$index_loop= 0;
$arrAsesor="";
//$statement= " AND (A.STATUS_PENILAIAN = '' OR A.STATUS_PENILAIAN IS NULL) AND COALESCE(B.JUMLAH_PESERTA,0) > 0 AND A.JADWAL_TES_ID IN (SELECT X.JADWAL_TES_ID FROM jadwal_asesor X WHERE X.ASESOR_ID = ".$tempAsesorId." GROUP BY X.JADWAL_TES_ID) ";
$statement= "";
$set= new JadwalAsesor();
if($reqMode!='administrator'){
    $set->selectByParamsJumlahAsesorPegawai($statement, $tempAsesorId);
}
else{
    $set->selectByParamsJumlahAsesorPegawaiSuper($statement, $tempAsesorId);
}
//echo $set->query;exit;
while($set->nextRow())
{
    // $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["TANGGAL_TES"]= dateToPageCheck(datetimeToPage($set->getField("TANGGAL_TES"), "date"));
    $arrAsesor[$index_loop]["JUMLAH"]= $set->getField("JUMLAH");
    $tanggalexplode=explode('-',dateToPageCheck(datetimeToPage($set->getField("TANGGAL_TES"), "date")));
    $arrAsesor[$index_loop]["d"]= ltrim($tanggalexplode[2],'0');
    $arrAsesor[$index_loop]["m"]= ltrim($tanggalexplode[1],'0');
    $arrAsesor[$index_loop]["y"]= ltrim($tanggalexplode[0],'0');
    $index_loop++;
}
$jumlah_asesor= $index_loop;
// print_r($arrAsesor);exit();
//$jumlah_asesor= 0;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Aplikasi Assesment Center</title>

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

.calendar-container {
  height: auto;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.4);
  padding: 20px 20px;
}


.calendar-week {
  display: flex;
  list-style: none;
  align-items: center;
  padding-inline-start: 0px;
}

.calendar-week-day {
  max-width: 57.1px;
  width: 100%;
  text-align: center;
  color: #525659;
}

.calendar-days {
  margin-top: 30px;
  list-style: none;
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  gap: 5px;
  padding-inline-start: 0px;
}

.calendar-day {
  text-align: center;
  color: #525659;
  padding: 10px;
}

.calendar-month-arrow-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.calendar-month-year-container {
  padding: 10px 10px 20px 10px;
  color: #525659;
  cursor: pointer;
}

.calendar-arrow-container {
  margin-top: -5px;
}

.calendar-left-arrow,
.calendar-right-arrow {
  height: 30px;
  width: 30px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  color: #525659;
}

.calendar-today-button {
  margin-top: -10px;
  border-radius: 10px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  color: #525659;
  padding: 5px 10px;
}

.calendar-today-button {
  height: 27px;
  margin-right: 10px;
  background-color: #ec7625;
  color: white;
}

.calendar-months,
.calendar-years {
  flex: 1;
  border-radius: 10px;
  height: 30px;
  border: none;
  cursor: pointer;
  outline: none;
  color: #525659;
  font-size: 15px;
}

.calendar-day-active {
  background-color: #ec7625;
  color: white;
  border-radius: 50%;
}

.calendar-alert {
  background-color: red;
  color: white;
  border-radius: 10%;
  cursor: pointer;
}
.calendar-notif{
    font-size: 12px;
  margin-top: -16px;
  color: white;
  border-radius: 30px;
  background-color: green;
  padding: 5px;
  position: absolute;
}
</style>

<script>
    function openPopup() {
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
  text-align:center;
  color:#FFF;
}
@media screen and (max-width:767px) {
    .footer {
        font-size:12px;
    }
}

</style>

</head>

<body>
    <div id="wrap-utama" style="height:100%;">
        <div id="main" class="container-fluid clear-top" style="height:100%;">            
            <div class="row">
                <div class="col-md-12 area-header">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="../WEB/images/logo-judul.png"> 
                            </div>
                            <div class="col-md-5" style="margin-left: -40px;">
                                <span><b>Sistem Informasi 
                                <br>Manajemen Assessment Center</b></span>
                                <hr style="margin:0px">
                                <span style="font-size: 12px;color: #009f3b ;">Provinsi Kalimantan Timur</span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="area-akun">
                            Selamat datang, <strong><?=$userLogin->nama?></strong> , 
                            <a href="login.php?reqMode=submitLogout"> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin: 0px 20px;">
                <div class="col-md-1" style="margin-top: 15px;">
                    <img id="reqImagePeserta" style="width: 75px;border-radius:200px ;" />
                </div>
                <div class="col-md-11">
                    <hr style="margin-top: 0px; margin-bottom: 15px;border-top: 1px solid black;">
                    <div style="margin:10px 0px"><b>
                        <? if($data['nama']==''){?>
                            <?=$tempAsesorNama?>
                        <? } 
                        else{ ?>
                        <? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?>
                        <?}?>
                        </b>
                        <br>
                        <p style="font-size: 12px;"><?=$tempAsesorNoSk?></p>
                    </div>
                    <hr style="margin-top: 0px; margin-bottom: 10px;border-top: 1px solid black;">
                    <div class="row">
                        <div class="col-md-4" style="margin: 3px 0px;">
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                            Email : <? if($data['email']==''){?>
                                            <?=$tempAsesorEmail?>
                                            <? } 
                                            else{ ?>
                                            <?=$data['email']?>
                                            <?}?>
                                                
                        </div>
                        <div class="col-md-8" style="margin: 3px 0px;">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            Telepon :<? if($data['no_hape']==''){?>
                                                <?=$tempAsesorTelepon?>
                                            <? } 
                                            else{ ?>
                                            <?=$data['no_hape']?>
                                            <?}?>
                        </div>
                        <div class="col-md-4" style="margin: 3px 0px;"> <i class="fa fa-list-alt" aria-hidden="true"></i> Tipe : <?=$tempAsesorTipeNama?></div>
                        <div class="col-md-8" style="margin: 3px 0px;">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            ALamat : <? if($data['alamat']==''){?>
                                                <?=$tempAsesorAlamat?>
                                            <? } 
                                            else{ ?>
                                            <?=$data['alamat']?>
                                            <?}?>
                        </div>
                    </div>
                    <hr style="margin-bottom: 0px; margin-top: 10px;border-top: 1px solid black;">
                </div>
            </div>
            <div class="row" style="margin: 10px 20px;">
                <div class="col-md-4">
                    <div class="calendar-container">
                      <div class="calendar-month-arrow-container">
                        <div class="calendar-month-year-container">
                          <select class="calendar-years"></select>
                          <select class="calendar-months">
                          </select>
                        </div>
                        <div class="calendar-month-year">
                        </div>
                        <div class="calendar-arrow-container">
                          <button class="calendar-today-button"></button>
                          <button class="calendar-left-arrow">
                            ← </button>
                          <button class="calendar-right-arrow"> →</button>
                        </div>
                      </div>
                      <ul class="calendar-week">
                      </ul>
                      <ul class="calendar-days">
                      </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="judul-halaman" style="background-color: #daf2f2; color: black;border-radius: 10px 10px 0px 0px">Nama Peserta</div>
                   <div class="area-table-assesor">
                       <div id="reqTableKegiatan">
                           <table>
                               <tr>
                                   <td>Tidak Ada</td>
                               </tr>
                           </table>
                       </div>
                   </div>
               </div>

           </div>
        </div>
    </div>

    <footer class="footer" style="color: black;background-color: rgba(255, 255, 255);">
         © <? echo date('Y'); ?> Pemerintah Provinsi Kalimantan Timur. All Rights Reserved.
    </footer>
<script type='text/javascript' src="../WEB/lib/bootstrap/angular.js"></script> 
<script type='text/javascript' src="../WEB/lib/js/jquery.min.js"></script> 

<script>
      $(function() {
       <?
       if($data['foto_original'] == "")
       {
        ?>
        $("#reqImagePeserta").attr("src", "../WEB/images/no-picture.jpg");
        <?
    }
    else
    {
        ?>
        $("#reqImagePeserta").attr("src", "<?=$data['foto_original']?>");
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

</script>

<script type="text/javascript">
    
    const weekArray = ["Sen", "Sel", "Rbu", "Kms", "Jmt", "Sbt", "Mng"];
const monthArray = [
  "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember"
];
const current = new Date();
// const current =  '<?=$reqTanggalTes?>';
// currentSplit =  current.split('-');
// console.log(currentSplit);

// const todaysDate = currentSplit[0];
// const currentYear = currentSplit[2];
// const currentMonth = currentSplit[1];
// console.log(currentYear);

const todaysDate = current.getDate();
const currentYear = current.getFullYear();
const currentMonth = current.getMonth();

window.onload = function () {
  const currentDate = new Date();
  generateCalendarDays(currentDate,'start');

  let calendarWeek = document.getElementsByClassName("calendar-week")[0];
  let calendarTodayButton = document.getElementsByClassName(
    "calendar-today-button"
  )[0];
  calendarTodayButton.textContent = `Today ${todaysDate}`;

  calendarTodayButton.addEventListener("click", () => {
    generateCalendarDays(currentDate);
  });

  weekArray.forEach((week) => {
    let li = document.createElement("li");
    li.textContent = week;
    li.classList.add("calendar-week-day");
    calendarWeek.appendChild(li);
  });

  const calendarMonths = document.getElementsByClassName("calendar-months")[0];
  const calendarYears = document.getElementsByClassName("calendar-years")[0];
  const monthYear = document.getElementsByClassName("calendar-month-year")[0];

  // const selectedMonth = parseInt(monthYear.getAttribute("data-month") || 0);
  // const selectedYear = parseInt(monthYear.getAttribute("data-year") || 0);
  const selectedMonth = parseInt(currentMonth)-1;
  const selectedYear = currentYear;
  monthArray.forEach((month, index) => {
    let option = document.createElement("option");
    // console.log(option);
    option.textContent = month;
    option.value = index;
    option.selected = index === selectedMonth;
    calendarMonths.appendChild(option);
  });

  // const currentYearnew = new Date().getFullYear();
  // console.log(currentYearnew);
  const startYear = 2019;
  const endYear = parseInt(selectedYear) + 3;
  let newYear = startYear;
  while (newYear <= endYear) {
    let option = document.createElement("option");
    option.textContent = newYear;
    option.value = newYear;
    if(newYear== selectedYear)
    {
    option.selected = 'selected';
    }
    else{
    option.selected = '';

    }
    calendarYears.appendChild(option);
    newYear++;
  }

  const leftArrow = document.getElementsByClassName("calendar-left-arrow")[0];

  leftArrow.addEventListener("click", () => {
    const monthYear = document.getElementsByClassName("calendar-month-year")[0];
    const month = parseInt(monthYear.getAttribute("data-month") || 0);
    const year = parseInt(monthYear.getAttribute("data-year") || 0);

    let newMonth = month === 0 ? 11 : month - 1;
    let newYear = month === 0 ? year - 1 : year;
    let newDate = new Date(newYear, newMonth, 1);
    generateCalendarDays(newDate);
  });

  const rightArrow = document.getElementsByClassName("calendar-right-arrow")[0];

  rightArrow.addEventListener("click", () => {
    const monthYear = document.getElementsByClassName("calendar-month-year")[0];
    const month = parseInt(monthYear.getAttribute("data-month") || 0);
    const year = parseInt(monthYear.getAttribute("data-year") || 0);
    let newMonth = month + 1;
    newMonth = newMonth === 12 ? 0 : newMonth;
    let newYear = newMonth === 0 ? year + 1 : year;
    let newDate = new Date(newYear, newMonth, 1);
    generateCalendarDays(newDate);
  });

  calendarMonths.addEventListener("change", function () {
    let newDate = new Date(calendarYears.value, calendarMonths.value, 1);
    generateCalendarDays(newDate);
  });

  calendarYears.addEventListener("change", function () {
    let newDate = new Date(calendarYears.value, calendarMonths.value, 1);
    generateCalendarDays(newDate);
  });
};

function generateCalendarDays(currentDate,mode='x') {
     if(mode=='x'){
        console.log('xx');
        const newDate = new Date(currentDate);
        const year = newDate.getFullYear();
        const month = newDate.getMonth();
    }
    else{
        var current =  '<?=$reqTanggalTes?>';
        currentSplit =  current.split('-');
        year = currentSplit[2];
        month = parseInt(currentSplit[1])-1;
    }
  const totalDaysInMonth = getTotalDaysInAMonth(year, month);
  const firstDayOfWeek = getFirstDayOfWeek(year, month);
  let calendarDays = document.getElementsByClassName("calendar-days")[0];
  
  removeAllChildren(calendarDays);

  let firstDay = 1;
  while (firstDay <= firstDayOfWeek) {
    let li = document.createElement("li");
    li.classList.add("calendar-day");
    calendarDays.appendChild(li);
    firstDay++;
  }

  let day = 1;
  while (day <= totalDaysInMonth) {
    let li = document.createElement("li");
    li.setAttribute("id", day+'-'+month+'-'+year);
    li.textContent = day;
    li.classList.add("calendar-day");
    // if (todaysDate === day && currentMonth === month && currentYear === year) {
    //   li.classList.add("calendar-day-active");
    // }
    <?for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++){?>
        if ( day === parseInt(<?=$arrAsesor[$checkbox_index]['d']?>) && month === parseInt(<?=$arrAsesor[$checkbox_index]['m']?>)-1 && year === parseInt(<?=$arrAsesor[$checkbox_index]['y']?>)) {
            li.classList.add("calendar-alert");
        }
        calendarDays.appendChild(li);
        if ( day === parseInt(<?=$arrAsesor[$checkbox_index]['d']?>) && month === parseInt(<?=$arrAsesor[$checkbox_index]['m']?>)-1 && year === parseInt(<?=$arrAsesor[$checkbox_index]['y']?>)) {
            $( "#<?=$arrAsesor[$checkbox_index]['d']?>-<?=$arrAsesor[$checkbox_index]['m']-1?>-<?=$arrAsesor[$checkbox_index]['y']?>" ).html(day+`<span class="calendar-notif">20</span>`);
            $( "#<?=$arrAsesor[$checkbox_index]['d']?>-<?=$arrAsesor[$checkbox_index]['m']-1?>-<?=$arrAsesor[$checkbox_index]['y']?>").attr('onClick', "showdetil('<?=$arrAsesor[$checkbox_index]['d']?>-<?=$arrAsesor[$checkbox_index]['m']?>-<?=$arrAsesor[$checkbox_index]['y']?>')");
        }
    <?}?>
    day++;
  }

  const monthYear = document.getElementsByClassName("calendar-month-year")[0];
  monthYear.setAttribute("data-month", month);
  monthYear.setAttribute("data-year", year);
  const calendarMonths = document.getElementsByClassName("calendar-months")[0];
  const calendarYears = document.getElementsByClassName("calendar-years")[0];
  calendarMonths.value = month;
  calendarYears.value = year;
}

function getTotalDaysInAMonth(year, month) {
  return new Date(year, month + 1, 0).getDate();
}

function getFirstDayOfWeek(year, month) {
  return new Date(year, month, 1).getDay();
}

function removeAllChildren(parent) {
  while (parent.firstChild) {
    parent.removeChild(parent.firstChild);
  }
}

function showdetil(argument) {
    var link_url= 'index_detil.php?reqTanggalTes='+argument+'&reqMode=<?=$reqMode?>';
    setModal("reqTableKegiatan", link_url);
}
</script>



</body>
</html>

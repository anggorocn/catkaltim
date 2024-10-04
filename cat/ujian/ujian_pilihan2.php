<?
include_once("../WEB/functions/infotipeujian.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");

$tempPegawaiId= $userLogin->pegawaiId;
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set= new Ujian();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
$tempUjianId= $set->getField("UJIAN_ID");
$final_time_saving= $set->getField("BATAS_WAKTU_MENIT");
//$final_time_saving= 1;
$hours= floor($final_time_saving / 60);
$minutes= $final_time_saving % 60;
unset($set);

$statement= " AND COALESCE(C.MENIT_SOAL,0) > 0 AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set= new UjianTahapStatusUjian();
$jumlah_selesai_tahap_batas= $set->getPegawaiUjianTahapSelesai($statement) + 1;
unset($set);
//echo $jumlah_selesai_tahap_batas;exit;

$arrUjian="";
$index_data= 0;
$statement= " AND COALESCE(C.MENIT_SOAL,0) > 0 AND B.PEGAWAI_ID = ".$tempPegawaiId." AND TO_DATE('".dateToPageCheck($tempSystemTanggalNow)."','YYYY/MM/DD') BETWEEN A.TGL_MULAI AND A.TGL_SELESAI";
$set= new UjianTahap();
$set->selectByParamsPegawaiTahapLog(array(), -1,-1, $statement, "ORDER BY ID");
//echo $set->errorMsg;exit;
//echo $set->query;exit;
while($set->nextRow())
{
	//TIPE_INFO;TIPE_STATUS;TIPE;UJIAN_TAHAP_ID;MENIT_SOAL;TIPE_UJIAN_ID
	$arrUjian[$index_data]["TIPE_UJIAN_ID"] = $set->getField("TIPE_UJIAN_ID");
	$arrUjian[$index_data]["TIPE_INFO"] = $set->getField("TIPE_INFO");
	$arrUjian[$index_data]["TIPE_STATUS"] = $set->getField("TIPE_STATUS");
	$arrUjian[$index_data]["TIPE"] = $set->getField("TIPE");
	$arrUjian[$index_data]["UJIAN_TAHAP_ID"] = $set->getField("UJIAN_TAHAP_ID");
	$arrUjian[$index_data]["MENIT_SOAL"] = $set->getField("MENIT_SOAL");
	$index_data++;
}
unset($set);
$jumlah_ujian= $index_data;

if($jumlah_ujian > $jumlah_selesai_tahap_batas)
$jumlah_ujian= $jumlah_selesai_tahap_batas;

//echo $jumlah_ujian."-".$jumlah_selesai_tahap_batas;exit;
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<script language="javascript">
//localStorage.clear();
function setujian(reqUjianTahapId)
{
	var s_url= "../json-ujian/ujian_tahap_status_ujian_petunjuk_cek.php?reqUjianId=<?=$tempUjianId?>&reqUjianTahapId="+reqUjianTahapId;
	$.ajax({'url': s_url,'success': function(msg) {
		if(msg == '0')
		{
			$.messager.alert('Info', "Harap membaca instruksi terlebih dahulu, sebelum ujian(klik OK)", 'info');
		}
		else
		{
			document.location.href= "?pg=ujian_online&reqId="+reqUjianTahapId;
		}
	}});
}

function setBacaTipeUjian(tipeujianid)
{
	var win = $.messager.progress({title:'Proses Data',msg:'Proses data...'});
	var s_url= "../json-ujian/ujian_tahap_status_ujian_petunjuk.php?reqUjianId=<?=$tempUjianId?>&reqTipeUjianId="+tipeujianid;
	$.ajax({'url': s_url,'success': function(msg) {
		if(msg == '0')
		{
			$.messager.alert('Error', "Data gagal disimpan.", 'error');	
			$.messager.progress('close'); 
		}
		else
		{
			$.messager.progress('close'); 
			$.messager.alert('Informasi', "Silahkan melakukan Ujian.", 'info');	
		}
	}});
}
</script>
<?php /*?><script language="javascript">
	// untuk clear time
	localStorage.clear();
	
	var hoursleft = <?=$hours?>;
	var minutesleft = <?=$minutes?>;
	var secondsleft = 0; 
	var finishedtext = "Countdown finished!";
	var end;
	if(localStorage.getItem("end")) {
		end = new Date(localStorage.getItem("end"));
	} else {
	   end = new Date();
	   end.setHours(end.getHours()+hoursleft);
	   end.setMinutes(end.getMinutes()+minutesleft);
	   end.setSeconds(end.getSeconds()+secondsleft);
	}
		
	var counter = function () {
		var now = new Date();
	
		var sec_now = now.getSeconds();
		var min_now = now.getMinutes(); 
		var hour_now = now.getHours(); 
		
		var sec_end = end.getSeconds();
		var min_end = end.getMinutes(); 
		var hour_end = end.getHours(); 
		
		var date1 = new Date(2000, 0, 1, hour_now,  min_now, sec_now); // 9:00 AM
		var date2 = new Date(2000, 0, 1, hour_end, min_end, sec_end); // 5:00 PM
		if (date2 < date1) {
			date2.setDate(date2.getDate() + 1);
		}
		var diff = date2 - date1;	
		
		var msec = diff;
		var hh = Math.floor(msec / 1000 / 60 / 60);
		msec -= hh * 1000 * 60 * 60;
		var mm = Math.floor(msec / 1000 / 60);
		msec -= mm * 1000 * 60;
		var ss = Math.floor(msec / 1000);
		msec -= ss * 1000;
							
		var sec = ss;
		var min = mm; 
		var hour = hh; 
		
		if (min < 10) {
			min = "0" + min;
		}
		if (sec < 10) { 
			sec = "0" + sec;
		}     
		if(now >= end) {     
			//alert('a');return false;
			clearTimeout(interval);
			localStorage.setItem("end", null);
			document.location.href = '?pg=finish';
			//document.location.href = '?pg=ujian_online';
			//document.getElementById('divCounter').innerHTML = finishedtext;
		} else {
			var value = hour + ":" + min + ":" + sec;
			localStorage.setItem("end", end);
			document.getElementById('divCounter').innerHTML = value;
			if(min.toString() == 'NaN')
			{
				//alert('b');return false;
				localStorage.setItem("waktuberakhir", "00:00");
				clearTimeout(interval);	
				localStorage.setItem("end", null);
				document.location.href = '?pg=finish';
				//document.location.href = '?pg=ujian_online';
			}
		}
	}
	var interval = setInterval(counter, 1);
</script><?php */?>

<div class="container utama">
	<div class="row">
    	<?php /*?><div class="col-md-12">
            <div class="area-sisa-waktu">
                <div class="judul"><i class="fa fa-clock-o"></i> Sisa Waktu :</div>
                <div class="waktu">
                    <div id="divCounter"></div>
                </div>
            </div>
        </div><?php */?>
        
    	<div class="col-md-12">
			<div class="area-judul-halaman">Ujian Tahap</div>
        </div>
    </div>
    
	<div class="row">
        <div class="col-md-12">
            <div class="area-soal">
                <div class="area-sudah finish">
                	<?
					for($index_loop=0; $index_loop < $jumlah_ujian; $index_loop++)
					{
						$tempTipeUjianId= $arrUjian[$index_loop]["TIPE_UJIAN_ID"];
						$tempTipeInfo= $arrUjian[$index_loop]["TIPE_INFO"];
						$tempTipeStatus= $arrUjian[$index_loop]["TIPE_STATUS"];
						$tempTipe= $arrUjian[$index_loop]["TIPE"];
						$tempUjianTahapId= $arrUjian[$index_loop]["UJIAN_TAHAP_ID"];
						$tempMenitSoal= $arrUjian[$index_loop]["MENIT_SOAL"];
						
						if($tempTipeUjianId==5){continue;}
					?>
                    <div class="row">
                        <?php /*?><div class="col-md-3">
                        	<span class="pilih-data" style="float:left; margin-left:10px;"><a href="?pg=ujian_online&reqId=<?=$set->getField("UJIAN_TAHAP_ID")?>" style="background:#7F0011; height:40px;"><?=$set->getField("TIPE")?> &raquo;</a></span>
                        </div>
                        <div class="col-md-9">
                        <span class="pilih-data" style="float:left; margin-left:10px;">
                        	<a href="?pg=ujian_online&reqId=<?=$set->getField("UJIAN_TAHAP_ID")?>" style="background:#7F0011; height:40px;">Waktu Soal &raquo; <?=$set->getField("MENIT_SOAL")?> menit</a>
                        </span>
                        </div><?php */?>
                        <div class="col-md-12">
                        	<span class="pilih-data" style="float:left; margin-left:10px;">
                            <?
							$tempTipeValInfo= "";
							if($tempTipeInfo == ""){}
							else
							$tempTipeValInfo= $tempTipeInfo." ";
							
							if($tempTipeStatus == "1")
							{
                            ?>
                            <span class="tahap-selesai">
                            <?=$tempTipeValInfo.$tempTipe?>
                            , selesai dikerjakan <i class="fa fa-check-square" aria-hidden="true"></i>
                            </span>
                            <?
							}
							else
							{
                            ?>
                            	<a href="#" onclick="setujian(<?=$tempUjianTahapId?>)" class="tahap-belum" >
								<?=$tempTipeValInfo.$tempTipe?> &raquo;
                                Waktu Soal : <?=$tempMenitSoal?> menit
                                </a>
                                <img src="../WEB/images/icn-faq2.png" title="Info <?=$tempTipeValInfo.$tempTipe?>" style="cursor:pointer" onclick="infoujian('<?=$tempTipeUjianId?>')" />
                            <?
							}
                            ?>
                            </span>
                        </div>
                        
                    </div>
                    <?php /*?><br /><?php */?>
                    
                    <?
					}
                    ?>
                </div>
            </div>
        </div>
        
        <div class="area-prev-next">
        	<div class="kembali-home">
        	<span class="ke-home"><a href="?pg=dashboard"><i class="fa fa-home"></i> Kembali ke halaman utama <!--&raquo;--></a></span>
            <?php /*?><div class="prev"><a href="?pg=ujian_mukadimah"><i class="fa fa-chevron-left"></i></a></div>
            <div class="next"><a href="#"><i class="fa fa-chevron-right"></i></a></div><?php */?>
            </div>
        </div>
    
    </div>
</div>

<style>
#fvpp-blackout {
	display: none;
	z-index: 499;
	position: fixed;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	background: #000;
	opacity: 0.5;
}

.my-welcome-message {
	display: none;
	z-index: 500;
	position: fixed;
	*width: 600px;
	*left: calc(50% - 300px);
	
	width:1000px;
	height:calc(100vh - 100px);
	left: calc(50% - 500px);
	*top: 16%;
	*bottom: 2%;
	
	top:50px;
	bottom: 50%;
	padding: 18px 20px;
	*font-family: Calibri, Arial, sans-serif;
	background: #FFF;
}

#fvpp-close {
	position: absolute;
	top: 18px;
	right: 20px;
	cursor: pointer;
	background:rgba(0,0,0,0.5);
	*background:#1b4a73;
	color:#FFF;
	width:30px;
	height:30px;
	line-height:30px;
	text-align:center;
}
#fvpp-close:hover{
	*background:#9a0e01;
	background:rgba(0,0,0,0.8);
}

#fvpp-dialog h2 {
	font-size: 2em;
	margin: 0;
}

#fvpp-dialog p {
	margin: 0;
}

/****/
.area-tatacara ul li {
    border-bottom: none;
}
.area-instruksi{
	*overflow:auto;
	*height:calc(100% - 10px);
	
	*float:left;
	*clear:both;
}
.area-instruksi .nama{
	color:#cc001a;
}
.area-instruksi .judul{
	font-weight:bold;
	margin-top:10px;
	margin-bottom:10px;
	border-top:1px solid #333;
	border-bottom:1px solid #333;
	font-size:16px;
	line-height:30px;
}
.area-instruksi .keterangan img{ 
	max-width:100%;
}
.area-instruksi #fvpp-close{
	display: block;
	float: right;
	width: auto;
	padding: 0 20px;
	height: 40px;
	line-height: 40px;
	text-transform: uppercase;
	background: #006acc;
	color: #FFF;
	text-align: center;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	border:none;
	*margin-right:10px;
	*bottom: 100px;
	*right: 200px;
	
	position:relative;
	
	/*position: absolute;
	bottom: 18px impo;
	right: 20px;
	cursor: pointer;*/
}

</style>

<script language="javascript">
function infoujian(id)
{
	$('#infoujian'+id).firstVisitPopup({
		//cookieName : 'homepage',
		showAgainSelector: '#show-message'
	});
}
</script>

<?
$set= new TipeUjian();
$set->selectByParams();
while($set->nextRow())
{
?>
<div class="my-welcome-message" id="infoujian<?=$set->getField("TIPE_UJIAN_ID")?>">
    <div class="konten-welcome" style="height:100%;">
    <div class="row" style="height:100%;">
    	<div class="col-md-12" style="height:100%;">
        	<div class="area-judul-halaman">Instruksi Pengerjaan Soal Ujian</div>
        	<div class="area-tatacara" style="height:calc(100% - 60px); overflow:auto; padding:0 0;"> 
                <ul style="list-style:none !important; list-style-type:none !important;">
                    <li>
                    	<?=setinfo($set->getField("TIPE_UJIAN_ID"))?>
						<?php /*?><?=$set->getField("KETERANGAN")?><?php */?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </div>
</div>
<?
}
?>
<script src="../WEB/lib/first-visit-popup-master/jquery.firstVisitPopup.js"></script>
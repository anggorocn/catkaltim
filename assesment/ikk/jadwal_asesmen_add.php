<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/recordcoloring.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo(); 
}
/* VARIABLE */
$reqRowId = httpFilterRequest("reqRowId");
$reqMode = httpFilterRequest("reqMode");
$reqId = httpFilterRequest("reqId");  

/* DATA VIEW */
$index_loop=0;
$arrJadwalTes= "";
$set = new JadwalTes();
$set->selectByParamsJadwalAsesmenInfo(array(), -1, -1, " AND A.JADWAL_TES_ID = ".$reqId);
//echo $set->query;exit;
while($set->nextRow())
{
	$arrJadwalTes[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
	$arrJadwalTes[$index_loop]["TANGGAL_TES"]= $set->getField("TANGGAL_TES");
	$arrJadwalTes[$index_loop]["ACARA"]= $set->getField("ACARA");
	$arrJadwalTes[$index_loop]["TEMPAT"]= $set->getField("TEMPAT");
	$arrJadwalTes[$index_loop]["ALAMAT"]= $set->getField("ALAMAT");
	
	$arrJadwalTes[$index_loop]["PROSEN_POTENSI"]= $set->getField("PROSEN_POTENSI");
	$arrJadwalTes[$index_loop]["PROSEN_KOMPETENSI"]= $set->getField("PROSEN_KOMPETENSI");
	
	$arrJadwalTes[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
	$arrJadwalTes[$index_loop]["PEGAWAI_NIP"]= $set->getField("PEGAWAI_NIP");
	$arrJadwalTes[$index_loop]["PEGAWAI_NAMA"]= $set->getField("PEGAWAI_NAMA");
	$arrJadwalTes[$index_loop]["PEGAWAI_GOL"]= $set->getField("PEGAWAI_GOL");
	$arrJadwalTes[$index_loop]["PEGAWAI_ESELON"]= $set->getField("PEGAWAI_ESELON");
	$arrJadwalTes[$index_loop]["PEGAWAI_JAB_STRUKTURAL"]= $set->getField("PEGAWAI_JAB_STRUKTURAL");
	$arrJadwalTes[$index_loop]["HASIL_POTENSI"]= $set->getField("HASIL_POTENSI");
	$arrJadwalTes[$index_loop]["HASIL_KOMPETENSI"]= $set->getField("HASIL_KOMPETENSI");
	
	$arrJadwalTes[$index_loop]["POTENSI_JPM"]= $set->getField("POTENSI_JPM");
	$arrJadwalTes[$index_loop]["KOMPETENSI_JPM"]= $set->getField("KOMPETENSI_JPM");
	$arrJadwalTes[$index_loop]["GENERAL_JPM"]= $set->getField("GENERAL_JPM");
	$arrJadwalTes[$index_loop]["GENERAL_IKK"]= $set->getField("GENERAL_IKK");
	$index_loop++;
}
$jumlah_jadwal_tes= $index_loop;

if($index_loop > 0)
{
	$tempJadwalTanggalTes= $arrJadwalTes[0]["TANGGAL_TES"];
	$tempJadwalAcara= $arrJadwalTes[0]["ACARA"];
	$tempJadwalTempat= $arrJadwalTes[0]["TEMPAT"];
	$tempJadwalAlamat= $arrJadwalTes[0]["ALAMAT"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript" src="../WEB/lib/alert/jquery.jgrowl.js"></script>
<link rel="stylesheet" href="../WEB/lib/alert/jquery.jgrowl.css" type="text/css"/>

<style type="text/css">
	/* Remove margins from the 'html' and 'body' tags, and ensure the page takes up full screen height */
	html, body {height:100%; margin:0; padding:0;}
	/* Set the position and dimensions of the background image. */
	#page-background {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* Specify the position and layering for the content that needs to appear in front of the background image. Must have a higher z-index value than the background image. Also add some padding to compensate for removing the margin from the 'html' and 'body' tags. */
	#content {position:relative; z-index:1;}
	/* prepares the background image to full capacity of the viewing area */
	#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
	/* places the content ontop of the background image */
	#content {position:relative; z-index:1;}
</style>
<link href="../WEB/css/tabs.css" rel="stylesheet" type="text/css" />

<script>
$(function(){
	$('input[id^="reqToleransi"]').keyup(function() {
		hitungJpmIkk();
	});
	
	$('#link-table tr').dblclick(function (){
		var id= $(this).attr('id');

		if(typeof id == "undefined" || id == ""){}
		else
		{
		id= id.replace("trkandidat", "");
		var reqPegawaiId= "";
		reqPegawaiId= $("#reqPegawaiId"+id).val();
		
		opUrl= 'jadwal_asesmen_add_lookup.php?reqId=<?=$reqId?>&reqPegawaiId='+reqPegawaiId;
	    window.OpenDHTMLCenter(opUrl, 'Info pegawai', '900', '400');
		
		}
	});
});

function pencarian(term, _id, cellNr){
	var suche = term.value.toLowerCase();
	var table = document.getElementById(_id);
	var ele0;	var ele1;
	for (var r = 1; r < table.rows.length; r++){
		//ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^>]+>/g,"");
		ele0 = table.rows[r].cells[0].innerHTML.replace(/<[^>]+>/g,"");
		ele1 = table.rows[r].cells[1].innerHTML.replace(/<[^>]+>/g,"");
		if (ele0.toLowerCase().indexOf(suche)>=0 || ele1.toLowerCase().indexOf(suche)>=0 )
		//if (ele0.toLowerCase().indexOf(suche)>=0)
			table.rows[r].style.display = '';
		else table.rows[r].style.display = 'none';
	}
}
		
function round(value, ndec){
    var n = 10;
    for(var i = 1; i < ndec; i++){
        n *=10;
    }

    if(!ndec || ndec <= 0)
        return Math.round(value);
    else
        return Math.round(value * n) / n;
}

function checkNan(value)
{
	if(typeof value == "undefined" || isNaN(value))
	return 0;
	else
	return value;
}

function hitungJpmIkk()
{
	tabBody=document.getElementsByTagName("TBODY").item(2);
	var rowCount= tabBody.rows.length;
	//alert(rowCount);return false;
	
	var reqToleransi= "";
	reqToleransi= $("#reqToleransi").val();
	
	if(parseFloat(checkNan(reqToleransi)) > 0)
	$("#reqToleransiInfo").text(reqToleransi);
	else
	$("#reqToleransiInfo").text("0");
	
	//reqGeneralJpm;reqGeneralIkk
	for(var i=0; i<=rowCount; i++) {
		var reqGeneralJpm= reqTempGeneralJpm= reqGeneralIkk= reqTempGeneralIkk= "";
		var reqPegawaiHasilPotensiJpm= reqTempPegawaiHasilPotensiJpm= reqPegawaiHasilKompetensiJpm= reqTempPegawaiHasilKompetensiJpm= "";
		
		
		reqGeneralJpm= $("#reqGeneralJpm"+i).text();
		reqTempGeneralJpm= $("#reqTempGeneralJpm"+i).val();
		reqGeneralIkk= $("#reqGeneralIkk"+i).text();
		reqTempGeneralIkk= $("#reqTempGeneralIkk"+i).val();
		
		reqPegawaiHasilPotensiJpm= $("#reqPegawaiHasilPotensiJpm"+i).text();
		reqTempPegawaiHasilPotensiJpm= $("#reqTempPegawaiHasilPotensiJpm"+i).val();
		reqPegawaiHasilKompetensiJpm= $("#reqPegawaiHasilKompetensiJpm"+i).text();
		reqTempPegawaiHasilKompetensiJpm= $("#reqTempPegawaiHasilKompetensiJpm"+i).val();
		
		if(parseFloat(checkNan(reqToleransi)) > 0)
		{
			var reqPersenToleransi= reqNilaiJpmToleransi= reqNilaiIkkToleransi= reqNilaiHasilJpmToleransi= reqNilaiHasilIkkToleransi= "";
			reqPersenToleransi= parseFloat(checkNan(reqToleransi)) / 100;
			
			if(typeof reqPegawaiHasilPotensiJpm == "undefined" || reqPegawaiHasilPotensiJpm == "")
			{}
			else
			{
				reqNilaiHasilJpmToleransi= round(parseFloat(checkNan(reqPegawaiHasilPotensiJpm)) + (parseFloat(checkNan(reqPegawaiHasilPotensiJpm)) * parseFloat(checkNan(reqPersenToleransi))),2);
				$("#reqPegawaiHasilPotensiJpm"+i).text(reqNilaiHasilJpmToleransi);
			}
			
			if(typeof reqPegawaiHasilKompetensiJpm == "undefined" || reqPegawaiHasilKompetensiJpm == "")
			{}
			else
			{
				reqNilaiHasilJpmToleransi= round(parseFloat(checkNan(reqPegawaiHasilKompetensiJpm)) + (parseFloat(checkNan(reqPegawaiHasilKompetensiJpm)) * parseFloat(checkNan(reqPersenToleransi))),2);
				$("#reqPegawaiHasilKompetensiJpm"+i).text(reqNilaiHasilJpmToleransi);
			}
			
			//alert(reqGeneralJpm);return false;
			if(typeof reqGeneralJpm == "undefined" || reqGeneralJpm == "")
			{}
			else
			{
				reqNilaiJpmToleransi= round(parseFloat(checkNan(reqGeneralJpm)) + (parseFloat(checkNan(reqGeneralJpm)) * parseFloat(checkNan(reqPersenToleransi))),2);
				
				if(parseFloat(reqNilaiJpmToleransi) > 100)
				reqNilaiIkkToleransi= 0;
				else
				reqNilaiIkkToleransi= round(100 - reqNilaiJpmToleransi,2);
				$("#reqGeneralJpm"+i).text(reqNilaiJpmToleransi);
				$("#reqGeneralIkk"+i).text(reqNilaiIkkToleransi);
			}
		}
		else
		{
			$("#reqPegawaiHasilPotensiJpm"+i).text(reqTempPegawaiHasilPotensiJpm);
			$("#reqPegawaiHasilKompetensiJpm"+i).text(reqTempPegawaiHasilKompetensiJpm);
			
			$("#reqGeneralJpm"+i).text(reqTempGeneralJpm);
			$("#reqGeneralIkk"+i).text(reqTempGeneralIkk);
		}
		
	}
}
</script>

<!-- POPUP WINDOW -->
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script>

<script type="text/javascript">
function iecompattest(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function OpenDHTMLCenter(opAddress, opCaption, opWidth, opHeight)
{
	 var width  = opWidth;
	 var height = opHeight;
	 var left   = "5px";
	 var top    = "10px";//(screen.height - height)/2;
	 width= iecompattest().clientWidth - 50;
	 height= iecompattest().clientHeight - 10;
	 var params = 'width='+width+', height='+height;
	 params += ', top='+top+', left='+left;
	 params += ', directories=no';
	 params += ', location=no';
	 params += ', menubar=no';
	 params += ', resizable=no';
	 params += ', scrollbars=no';
	 params += ', status=no';
	 params += ', toolbar=no';
	divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, params); return false;
}
</script>

</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
	<table class="table_list" cellspacing="1" width="100%" border="0">
        <tr>
            <td colspan="3">
            <div id="header-tna-detil">Jadwal <span> Assesment</span></div>	                    
            </td>			
        </tr>
        <?
		if($tempJadwalTanggalTes == "")
		{
        ?>
        <tr class="terang">
            <td colspan="3" style="text-align:center">
            Belum ada penilaian dari asesor
            </td>			
        </tr>
        <?
		}
		else
		{
        ?>
        <tr class="terang">
            <td width="20%">Tanggal</td>
            <td width="2%">:</td>
            <td>
                <?=dateToPageCheck($tempJadwalTanggalTes)?>
            </td>
        </tr>
        <tr class="gelap">
            <td>Acara</td>
            <td>:</td>
            <td>
                <?=$tempJadwalAcara?>
            </td>
        </tr>
        <tr class="terang">
            <td>Tempat</td>
            <td>:</td>
            <td>
                <?=$tempJadwalTempat?>
            </td>
        </tr>
        <tr class="gelap">
            <td>Alamat</td>
            <td>:</td>
            <td>
                <?=$tempJadwalAlamat?>
            </td>
        </tr>
        <tr class="terang">
            <td>Jumlah Peserta</td>
            <td>:</td>
            <td><?=$jumlah_jadwal_tes?></td>
        </tr>
        <tr class="gelap">
            <td>Toleransi</td>
            <td>:</td>
            <td>
                <input type="text" id="reqToleransi" style="width:50px" value="0" />%
            </td>
        </tr>
        <tr class="terang">
        	<td>Pencarian</td>
            <td>:</td>
            <td>
                <input name="filter" onKeyUp="pencarian(this, 'link-table', 0)" type="text" style="width:300px">
            </td>
        </tr>
        <tr>
        	<td colspan="3" width="100%">
            	<table class="gradient-style" cellspacing="1" style="width:100%; margin-left:-1px" id="link-table">
                    <tr class="terang">
                      <th rowspan="2">NIP Baru</th>
                      <th rowspan="2">Nama</th>
                      <th rowspan="2">Gol.Ruang</th>
                      <th rowspan="2">Eselon</th>
                      <th rowspan="2">Jabatan</th>
                      <th colspan="2" style="text-align:center">JPM</th>
                      <th colspan="2" style="text-align:center">JPM Toleransi <label id="reqToleransiInfo">0</label>%</th>
                      <th colspan="2" style="text-align:center">General Ikk</th>
                    </tr>
                    <tr>
                      <th>Potensi</th>
                      <th>Kompetensi</th>
                      <?
					  if($jumlah_jadwal_tes > 0)
					  {
                      ?>
                      <th style="text-align:center">Potensi<br/>(<?=$arrJadwalTes[0]["PROSEN_POTENSI"]?> %)</th>
                      <th style="text-align:center">Kompetensi<br/>(<?=$arrJadwalTes[0]["PROSEN_KOMPETENSI"]?> %)</th>
                      <?
					  }
					  else
					  {
                      ?>
                      <th>Potensi</th>
                      <th>Kompetensi</th>
                      <?
					  }
                      ?>
                      <th>JPM</th>
                      <th>IKK</th>
                    </tr>
                    <tbody>
                    <? 
					for($checkbox_index_detil=0;$checkbox_index_detil < $jumlah_jadwal_tes;$checkbox_index_detil++)
					{
						$index_loop= $checkbox_index_detil;
						$arrJadwalTes[$index_loop]["JADWAL_TES_ID"];
						$tempPegawaiId= $arrJadwalTes[$index_loop]["PEGAWAI_ID"];
						$tempPegawaiNip= $arrJadwalTes[$index_loop]["PEGAWAI_NIP"];
						$tempPegawaiNama= $arrJadwalTes[$index_loop]["PEGAWAI_NAMA"];
						$tempPegawaiGol= $arrJadwalTes[$index_loop]["PEGAWAI_GOL"];
						$tempPegawaiEselon= $arrJadwalTes[$index_loop]["PEGAWAI_ESELON"];
						$tempPegawaiJabatan= $arrJadwalTes[$index_loop]["PEGAWAI_JAB_STRUKTURAL"];
						
						$tempPegawaiPotensiJpm= $arrJadwalTes[$index_loop]["POTENSI_JPM"];
						$tempPegawaiKompetensiJpm= $arrJadwalTes[$index_loop]["KOMPETENSI_JPM"];
						
						$tempPegawaiHasilPotensiJpm= $arrJadwalTes[$index_loop]["HASIL_POTENSI"];
						$tempPegawaiHasilKompetensiJpm= $arrJadwalTes[$index_loop]["HASIL_KOMPETENSI"];
						
						$tempPegawaiGeneralJpm= $arrJadwalTes[$index_loop]["GENERAL_JPM"];
						$tempPegawaiGeneralIkk= $arrJadwalTes[$index_loop]["GENERAL_IKK"];
					?>
                    <tr id="trkandidat<?=$checkbox_index_detil?>" style="cursor:pointer">
                        <?php /*?><td><?=$tempPegawaiId."-".$tempPegawaiNip?></td><?php */?>
                        <td><?=$tempPegawaiNip?></td>
                        <td><?=$tempPegawaiNama?></td>
                        <td><?=$tempPegawaiGol?></td>
                        <td><?=$tempPegawaiEselon?></td>
                        <td><?=$tempPegawaiJabatan?></td>
                        <td>
                        	<input type="hidden" id="reqPegawaiId<?=$checkbox_index_detil?>" value="<?=$tempPegawaiId?>" />
                            <label id="reqPegawaiPotensiJpm<?=$checkbox_index_detil?>"><?=$tempPegawaiPotensiJpm?></label>
                            <input type="hidden" id="reqTempPegawaiPotensiJpm<?=$checkbox_index_detil?>" value="<?=$tempPegawaiPotensiJpm?>" />
                        </td>
                        <td>
                            <label id="reqPegawaiKompetensiJpm<?=$checkbox_index_detil?>"><?=$tempPegawaiKompetensiJpm?></label>
                            <input type="hidden" id="reqTempPegawaiKompetensiJpm<?=$checkbox_index_detil?>" value="<?=$tempPegawaiKompetensiJpm?>" />
                        </td>
                        <td>
                            <label id="reqPegawaiHasilPotensiJpm<?=$checkbox_index_detil?>"><?=$tempPegawaiHasilPotensiJpm?></label>
                            <input type="hidden" id="reqTempPegawaiHasilPotensiJpm<?=$checkbox_index_detil?>" value="<?=$tempPegawaiHasilPotensiJpm?>" />
                        </td>
                        <td>
                            <label id="reqPegawaiHasilKompetensiJpm<?=$checkbox_index_detil?>"><?=$tempPegawaiHasilKompetensiJpm?></label>
                            <input type="hidden" id="reqTempPegawaiHasilKompetensiJpm<?=$checkbox_index_detil?>" value="<?=$tempPegawaiHasilKompetensiJpm?>" />
                        </td>
                        <td>
                            <label id="reqGeneralJpm<?=$checkbox_index_detil?>"><?=$tempPegawaiGeneralJpm?></label>
                            <input type="hidden" id="reqTempGeneralJpm<?=$checkbox_index_detil?>" value="<?=$tempPegawaiGeneralJpm?>" />
                        </td>
                        <td>
                        	<label id="reqGeneralIkk<?=$checkbox_index_detil?>"><?=$tempPegawaiGeneralIkk?></label>
                            <input type="hidden" id="reqTempGeneralIkk<?=$checkbox_index_detil?>" value="<?=$tempPegawaiGeneralIkk?>" />
                        </td>
                    </tr>
                    <? 
					}
					?>
                    </tbody>
                </table>
            </td>
        </tr>
        <?
		}
        ?>
    </table>
</div>
</div>
<script>
$('input[id^="reqToleransi"]').keypress(function(e) {
	//alert(e.which);
	if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
	{
	return false;
	}
});
</script>
</body>
</html>
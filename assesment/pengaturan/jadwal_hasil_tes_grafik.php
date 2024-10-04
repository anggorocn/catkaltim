<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base/Rekap.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$statement= " AND B.UJIAN_PEGAWAI_DAFTAR_ID = ".$reqPegawaiId;
$set = new Rekap();
$set->selectByParamsMonitoringKraepelin(array(), -1, -1, $reqId, $statement);
$set->firstRow();
// echo $set->query;exit();
$tempNipPegawai= $set->getField("NIP_BARU");
$tempNamaPegawai= $set->getField("NAMA_PEGAWAI");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<!-- CSS for Drop Down Tabs Menu #2 -->
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<script type="text/javascript" src="css/dropdowntabs.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<style type="text/css" media="screen">
  label {
	/*font-size: 10px;
	font-weight: bold;
	text-transform: uppercase;
	margin-bottom: 3px;*/
	clear: both;
  }
</style>
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

<script src="../WEB/lib/mhighcharts/jquery.js"></script>
<script src="../WEB/lib/mhighcharts/highchartsmodul.js"></script>
<script src="../WEB/lib/mhighcharts/drilldown.js"></script>
<script src="../WEB/lib/mhighcharts/exporting.js"></script>

<script type="text/javascript">
	var urlAjax= "../json-pengaturan/jadwal_hasil_tes_grafik_json.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqPegawaiId=<?=$reqPegawaiId?>";
	// var request = $.get(s_url);
	
	judul= "Grafik Data";
	subjudul= "an. <?=$tempNamaPegawai?>";
	target= "reqTargetData";

	$.ajax({'url': urlAjax,'success': function(json)
	// request.done(function(json)
	{
		var json= JSON.parse(json);
		var chart = new Highcharts.Chart({
			chart: {
				renderTo: target,
				defaultSeriesType:'line'
			},
			title: {
				text: judul,
					x: -20 //center
			},
			subtitle: {
				text: subjudul,
				x: -20
			},
			xAxis: {
				// type: 'category',
				labels: {
					rotation: -45,
					step: 1
				},
				min: 0,
				categories: [
				<?
				for($i=1; $i <= 50; $i++)
				{
					if($i == 1){}
					else
						echo ",";
				?>
				"Y<?=$i?>"
				<?
				}
				?>
				]
			},
			yAxis: {
				title: {
					text: 'Jumlah'
				},
				labels: {
					formatter: function() {
						var ret, numericSymbols = ['', 'Jt', 'M', 'T'], i = 6;
						var valueInfo= this.value;

						var sign= true;
						if(parseFloat(valueInfo) < 0)
						{
							sign= false;
						}
						
						valueInfo= parseFloat(valueInfo);
						valueInfo= valueInfo.toString();
						valueInfo= valueInfo.replace("-", "");
						valueInfo= parseFloat(valueInfo);
						
						if(valueInfo >=1000) {
							while (i-- && ret === undefined) {
								multi = Math.pow(1000, i + 1);
								if (valueInfo >= multi && numericSymbols[i] !== null) {
									ret = (valueInfo / multi) + numericSymbols[i];
								}
							}
							if(sign == true){}
								else
									ret= "-"+ret;
							}
							return (ret ? ret : this.value);
						}
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
					//return '<b>N </b>'+': '+ this.y +'<br/>';
					//return '<b>Prosentase : </b>'+ Highcharts.numberFormat(this.y, 2) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ toRp(this.y) +'<br/>';
					return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ this.y +'<br/>';
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: json
		});
	}});
</script>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<table class="table_list" cellspacing="1" width="100%">
			<tr>
				<td>
					<div id="header-tna-detil">Data Grafik <span><?=$tempNamaPegawai?></span></div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100% !important;" id="reqTargetData"></div>
				</td>
		</table>
	</div>
</div>
</body>
</html>
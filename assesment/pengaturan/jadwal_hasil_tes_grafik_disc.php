<?php
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");
include_once("../WEB/classes/base-simpeg/Pegawai.php");


/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqId= httpFilterGet("reqId");
$reqTipeUjianId= httpFilterGet("reqTipeUjianId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set = new Pegawai();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
// echo $set->query;exit();
$tempNamaPegawai= $set->getField("NAMA");
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

<script src="../WEB/lib/hchartgrafikdownload/jquery-3.1.1.min.js"></script>
<script src="../WEB/lib/hchartgrafikdownload/highcharts.js"></script>
<script src="../WEB/lib/hchartgrafikdownload/exporting.js"></script>
<script src="../WEB/lib/hchartgrafikdownload/offline-exporting.js"></script>

<script type="text/javascript">
	<?
	// for($x=1; $x <= 3; $x++)
	// {
	?>

	var chart1= chart2= chart3= "";
	var urlAjax= "../json-pengaturan/jadwal_hasil_tes_grafik_disc_json.php?reqId=<?=$reqId?>&reqTipeUjianId=<?=$reqTipeUjianId?>&reqPegawaiId=<?=$reqPegawaiId?>";
	// var request = $.get(s_url);
	
	$.ajax({'url': urlAjax,'success': function(json)
	// request.done(function(json)
	{	
		var jsonawal= JSON.parse(json);
		hasil= [];
        hasil.push(jsonawal[0]);

		judul= "GRAPH 1 MOST";
		subjudul= "Mask Public Self";
		target= "reqTargetData1";
		chart1 = new Highcharts.chart({
			chart: {
				renderTo: target,
				defaultSeriesType:'line'
			},
			title: {
				text: judul,
					x: -20 //center
			},
			credits: {
				enabled: false
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
				categories: ["D", "I", "S", "C"]
			},
			yAxis: {
				title: {
					text: ''
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
					return '<b></b>'+''+ this.y +'<br/>';
					//return '<b>Prosentase : </b>'+ Highcharts.numberFormat(this.y, 2) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ toRp(this.y) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ this.y +'<br/>';
				}
			},
		    exporting: {
		        enabled: false // hide button
		    },
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: hasil
		});

		hasil= [];
        hasil.push(jsonawal[1]);
		judul= "GRAPH 2 LEAST";
		subjudul= "Core Private Self";
		target= "reqTargetData2";
		chart2 = new Highcharts.chart({
			chart: {
				renderTo: target,
				defaultSeriesType:'line'
			},
			title: {
				text: judul,
					x: -20 //center
			},
			credits: {
				enabled: false
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
				categories: ["D", "I", "S", "C"]
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
					return '<b></b>'+''+ this.y +'<br/>';
					//return '<b>Prosentase : </b>'+ Highcharts.numberFormat(this.y, 2) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ toRp(this.y) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ this.y +'<br/>';
				}
			},
		    exporting: {
		        enabled: false // hide button
		    },
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: hasil
		});

		hasil= [];
        hasil.push(jsonawal[2]);
		judul= "GRAPH 3 CHANGE";
		subjudul= "Mirror Perceived Self";
		target= "reqTargetData3";
		chart3 = new Highcharts.chart({
			chart: {
				renderTo: target,
				defaultSeriesType:'line'
			},
			title: {
				text: judul,
					x: -20 //center
			},
			credits: {
				enabled: false
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
				categories: ["D", "I", "S", "C"]
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
					return '<b></b>'+''+ this.y +'<br/>';
					//return '<b>Prosentase : </b>'+ Highcharts.numberFormat(this.y, 2) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ toRp(this.y) +'<br/>';
					// return '<b>Nominal '+ this.x +'('+this.series.name+') : </b>'+ this.y +'<br/>';
				}
			},
		    exporting: {
		        enabled: true // hide button
		    },
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			},
			series: hasil
		});

	}});
	<?
	// }
	?>

	$(function(){

		Highcharts.getSVG = function (charts) {
		    var svgArr = [],
		        top = 0,
		        width = 0;

		    Highcharts.each(charts, function (chart) {
		        var svg = chart.getSVG(),
		            // Get width/height of SVG for export
		            svgWidth = +svg.match(
		                /^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/
		            )[1],
		            svgHeight = +svg.match(
		                /^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/
		            )[1];

		        svg = svg.replace(
		            '<svg',
		            '<g transform="translate(0,' + top + ')" '
		        );
		        svg = svg.replace('</svg>', '</g>');

		        top += svgHeight;
		        width = Math.max(width, svgWidth);

		        svgArr.push(svg);
		    });

		    return '<svg height="' + top + '" width="' + width +
		        '" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
		        svgArr.join('') + '</svg>';
		};

		/**
		 * Create a global exportCharts method that takes an array of charts as an
		 * argument, and exporting options as the second argument
		 */
		Highcharts.exportCharts = function (charts, options) {

		    // Merge the options
		    options = Highcharts.merge(Highcharts.getOptions().exporting, options);

		    // Post to export server
		    Highcharts.post(options.url, {
		        filename: options.filename || 'chart',
		        type: options.type,
		        width: options.width,
		        svg: Highcharts.getSVG(charts)
		    });
		};

		$('#export-png').click(function () {
		    Highcharts.exportCharts([chart1, chart2, chart3]);
		});

		$('#export-pdf').click(function () {
		    Highcharts.exportCharts([chart1, chart2, chart3], {
		        type: 'application/pdf'
		    });
		});
	});
</script>
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
	<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<table class="table_list" cellspacing="1" width="100%">
			<tr>
				<td colspan="3">
					<div id="header-tna-detil">Data Grafik <span><?=$tempNamaPegawai?></span></div>
				</td>
			</tr>
			<tr>
				<td style="width: 33%;">
					<div style="width:100% !important;" id="reqTargetData1"></div>
				</td>
				<td style="width: 33%;">
					<div style="width:100% !important;" id="reqTargetData2"></div>
				</td>
				<td style="width: 33%;">
					<div style="width:100% !important;" id="reqTargetData3"></div>
				</td>
		</table>

		<div id="buttonrow">
			<button id="export-png">Export to PNG</button>
			<button id="export-pdf">Export to PDF</button>
		</div>
	</div>
</div>
</body>
</html>
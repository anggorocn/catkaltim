<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/ToleransiTalentPool.php");
include_once("../WEB/classes/base/FormulaAssesment.php");

/* variable */
$reqId= httpFilterGet("reqId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

$set= new Kelautan();
$statement= " AND A.SATKER_ID = '".$reqId."'";
$set->selectByParamsSatuanKerja(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$tempNamaSatuanKerja= $set->getField("NAMA");
unset($set);

if($tempNamaSatuanKerja == "")
$tempNamaSatuanKerja= "Pemerintah Provinsi Bali";

$set_eselon= new Kelautan();
$set_eselon->selectByParamsMonitoringEselon();

//$arrTahun= setTahunLoop(4,1);
$reqTahun= 2020;

$statement= " AND A.TAHUN = ".$reqTahun;
$set= new ToleransiTalentPool();
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
$tempToleransiY= $set->getField("TOLERANSI_Y");
$tempToleransiX= $set->getField("TOLERANSI_X");

$tempSkpX0= $set->getField("SKP_X0");
$tempSkpY0= $set->getField("SKP_Y0");
$tempGmX0= $set->getField("GM_X0");
$tempGmY0= $set->getField("GM_Y0");
$tempSkpX1= $set->getField("SKP_X1");
// perubahan awal
// $tempSkpY1= $set->getField("SKP_Y1");
$tempSkpY1= $tempSkpInfoY1= $tempSkpX0+1;
// perubahan tutup
$tempGmX1= $set->getField("GM_X1");
$tempGmInfoX1= $tempGmY0+1;
$tempGmY1= $set->getField("GM_Y1");
$tempSkpX2= $set->getField("SKP_X2");
// perubahan awal
// $tempSkpY2= $set->getField("SKP_Y2");
// $tempSkpInfoY2= $tempGmY1+1;
$tempSkpY2= $tempSkpInfoY2= $tempSkpX1+1;
// perubahan tutup
$tempGmX2= $set->getField("GM_X2");
$tempGmInfoX2= $tempGmY1+1;
$tempGmY2= $set->getField("GM_Y2");

if($tempSkpY0 == "") $tempSkpY0= 0;
if($tempGmX0 == "") $tempGmX0= 0;
if($tempSkpY1 == "") $tempSkpY1= 0;
if($tempGmX1 == "") $tempGmX1= 0;
if($tempSkpY2 == "") $tempSkpY2= 0;
if($tempGmX2 == "") $tempGmX2= 0;

$tempOptionValueY=$tempToleransiY;
if($tempToleransiY < 0)
{
	$tempOptionY= "-";
	$tempOptionValueY= $tempToleransiY * -1;
}
else
{
	$tempOptionY= "+";
}

$tempOptionValueX=$tempToleransiX;
if($tempToleransiX < 0)
{
	$tempOptionX= "-";
	$tempOptionValueX= $tempToleransiX * -1;
}
else
{
	$tempOptionX= "+";
}

$set_formula= new FormulaAssesment();
$set_formula->selectByParams();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

<style>
.vertical-text {
	transform: rotate(90deg);
	transform-origin: left top 0;
	float: left;
}
</style>

<!-- <script type="text/javascript" src="../WEB/js/jquery-1.6.1.js"></script>
<script script type="text/javascript" src="../WEB/js/highcharts.js"></script> -->
<script src="../WEB/lib/highcharts/jquery-3.1.1.min.js"></script>
<script src="../WEB/lib/highcharts/highcharts-spider.js"></script>
<script src="../WEB/lib/highcharts/highcharts-more.js"></script>
<script src="../WEB/lib/highcharts/exporting-spider.js"></script>
<script src="../WEB/lib/highcharts/export-data.js"></script>
<script src="../WEB/lib/highcharts/accessibility.js"></script>

<script>

var data= chartkuadran= "";

function setGrafik(link_url)
{
	var s_url= link_url;

	//alert(s_url);return false;
	var request = $.get(s_url);
	request.done(function(dataJson)
	{
		if(dataJson == ''){}
		else
		{
			dataValue= dataJson;

			var reqSkpY0= reqSkpX0= reqGmY0= reqGmX0=
			reqSkpY1= reqSkpX1= reqGmY1= reqGmX1=
			reqSkpY2= reqSkpX2= reqGmY2= reqGmX2= 0;

			// reqSkpY0= parseFloat($("#reqSkpY0").val());
			reqSkpY0= parseFloat("<?=$tempSkpY0?>");
			// reqSkpX0= parseFloat($("#reqSkpX0").val());
			reqSkpX0= parseFloat("<?=$tempSkpX0?>");
			// reqGmY0= parseFloat($("#reqGmY0").val());
			reqGmY0= parseFloat("<?=$tempGmY0?>");
			// reqGmX0= parseFloat($("#reqGmX0").val());
			reqGmX0= parseFloat("<?=$tempGmX0?>");

			// reqSkpY1= parseFloat($("#reqSkpY1").val());
			reqSkpY1= parseFloat("<?=$tempSkpY1?>");
			// reqSkpX1= parseFloat($("#reqSkpX1").val());
			reqSkpX1= parseFloat("<?=$tempSkpX1?>");
			// reqGmY1= parseFloat($("#reqGmY1").val());
			reqGmY1= parseFloat("<?=$tempGmY1?>");
			// reqGmX1= parseFloat($("#reqGmX1").val());
			reqGmX1= parseFloat("<?=$tempGmX1?>");

			// reqSkpY2= parseFloat($("#reqSkpY2").val());
			reqSkpY2= parseFloat("<?=$tempSkpY2?>");
			// reqSkpX2= parseFloat($("#reqSkpX2").val());
			reqSkpX2= parseFloat("<?=$tempSkpX2?>");
			// reqGmY2= parseFloat($("#reqGmY2").val());
			reqGmY2= parseFloat("<?=$tempGmY2?>");
			// reqGmX2= parseFloat($("#reqGmX2").val());
			reqGmX2= parseFloat("<?=$tempGmX2?>");

			chartkuadran = new Highcharts.Chart({
			chart: {
					renderTo: 'container',
				},
				credits: {
					enabled: false
				},
				legend:{
					enabled:false
				},
				xAxis: {
					title:{
						 text:'JPM'
					},
					min: 0,
					max: reqSkpX2,
					tickLength:0,
					minorTickLength:0,
					gridLineWidth:0,
					showLastLabel:true,
					showFirstLabel:false,
					lineColor:'#ccc',
					lineWidth:1,
					bgColor: "#ff0"
				},
				yAxis: {
					title:{
						text:'Kinerja ( SKP )',
						rotation:270
					},
					min: 0,
					max: reqGmY2,
					tickLength:3,
					minorTickLength:0,
					gridLineWidth:0,
					lineColor:'#ccc',
					lineWidth:1
				},
				tooltip: {
					formatter: function() {
						var s = this.point.myData;
						return s;
					}
				},
				title: {
					text:''
				},
				series: [
				{
					type: 'line',
					name: 'SKP Kurang',
					data: [[reqSkpX0, reqSkpY0], [reqSkpX0, reqSkpX2]],
					marker: {
						enabled: false
					},
					states: {
						hover: {
							lineWidth: 0
						}
					},
					enableMouseTracking: false
				},
				{
					type: 'line',
					name: 'GM Kurang',
					data: [[reqGmX0, reqGmY0], [reqGmY2, reqGmY0]],
					marker: {
						enabled: false
					},
					states: {
						hover: {
							lineWidth: 0
						}
					},
					enableMouseTracking: false
				},
				{
					type: 'line',
					name: 'SKP Sedang',
					data: [[reqSkpX1, reqSkpY1], [reqSkpX1, reqSkpX2]],
					marker: {
						enabled: false
					},
					states: {
						hover: {
							lineWidth: 0
						}
					},
					enableMouseTracking: false
				},
				{
					type: 'line',
					name: 'GM Sedang',
					data: [[reqGmX1, reqGmY1], [reqGmY2, reqGmY1]],
					marker: {
						enabled: false
					},
					states: {
						hover: {
							lineWidth: 0
						}
					},
					enableMouseTracking: false
				},
				{
					type: 'line',
					name: 'SKP Baik',
					data: [[reqSkpX2, reqSkpY2], [reqSkpX2, reqSkpX2]],
					marker: {
						enabled: false
					},
					states: {
						hover: {
							lineWidth: 0
						}
					},
					enableMouseTracking: false
				},
				{
					type: 'line',
					name: 'GM Baik',
					data: [[reqGmX2, reqGmY2], [reqGmY2, reqGmY2]],
					marker: {
						enabled: false
					},
					states: {
						hover: {
							lineWidth: 0
						}
					},
					enableMouseTracking: false
				},
				{
					type: 'scatter',
					name: 'Observations',
					color: 'blue',
					//data: [[80,80], [40.5,40.5], [60.8,60.8], [53.5,53.5], [63.9,63.9], [90.2,90.2], [95,95]],
					data: dataValue,
					marker: {
						radius: 4
					}
				}
				]

				}
				,
				function(chart) { // on complete
					//alert(chart.plotBox.width);
					//var width = chart.plotBox.width / 3.0;
					//var height = chart.plotBox.height / 3.0 + 0;
					var width= chart.plotBox.width;
					var height= chart.plotBox.height;
					var tempplotbox= tempplotboy= tempwidth= tempxwidth= tempheight= 0;
					//var box = textContainer.getBBox();

					//alert(chart.series[1].data[1].x + "--"+ chart.series[1].data[1].y);
					//alert(chart.xAxis[0].toPixels(0) + "--" + chart.yAxis[0].toPixels(0));

					//alert(chart.plotBox.x+";"+chart.plotBox.y+";"+width+";"+height);
					// PENAMBAHAN TEXT
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({

					//garis I
					//=====================================================================================
					//alert(reqGmY1);
					tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
					//tempxwidth= parseFloat(tempwidth / 2);
					tempheight1= tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3);
					tempplotbox= chart.plotBox.x;
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y;
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#00b050',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("IV", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({
					tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
					//tempxwidth= parseFloat(parseFloat(tempwidth) / 2) + parseFloat(tempwidth1);
					tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3);
					tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y;
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#92d050',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("VII", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({
					tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
					tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3);
					tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y;
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#006600',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("IX", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================

					//garis II
					//=====================================================================================
					//alert(reqGmY1);
					tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
					tempheight2= tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
					//tempxwidth= parseFloat(tempwidth / 2);
					//tempheight= parseFloat(height) * ((100 - parseFloat(reqGmY1)) / 100);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1);;
					tempplotbox= chart.plotBox.x;
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#ffff00',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("II", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({
					tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
					//tempxwidth= parseFloat(parseFloat(tempwidth) / 2) + parseFloat(tempwidth1);
					tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1);;
					tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#c4d79b',
						zIndex: 0
					}).add();

					// var text = chart.renderer.text("VI", tempwidth, tempheight).add();
					var text = chart.renderer.text("V", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({
					tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
					tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1);;
					tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#92d050',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("VIII", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================

					//garis III
					//=====================================================================================
					//alert(reqGmY1);
					tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
					tempheight3= tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
					//tempxwidth= parseFloat(tempwidth / 2);
					//tempheight= parseFloat(height) * ((100 - parseFloat(reqGmY1)) / 100);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2);
					tempplotbox= chart.plotBox.x;
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#ff0000',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("I", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({
					tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
					//tempxwidth= parseFloat(parseFloat(tempwidth) / 2) + parseFloat(tempwidth1);
					tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2);
					tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#ffff00',
						zIndex: 0
					}).add();

					var text = chart.renderer.text("III", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================
					//chart.renderer.rect(chart.plotBox.x,chart.plotBox.y, width, height, 1).attr({
					//chart.renderer.rect(76,65, 514, 306, 1).attr({
					tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
					tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
					tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2);
					tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
					tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
					tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
					//alert(tempwidth);
					chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
						fill: '#00b050',
						zIndex: 0
					}).add();

					// var text = chart.renderer.text("V", tempwidth, tempheight).add();
					var text = chart.renderer.text("VI", tempwidth, tempheight).add();
					text.attr({
						x: tempxwidth,
						y: tempyheight,
						zIndex:99
					});
					//=====================================================================================

				}

			);

		}
	});
}

setGrafik("../json-ikk/infografik_json.php?reqId=<?=$reqId?>&reqJadwalTesId=<?=$reqJadwalTesId?>");

function setInfoToleransi()
{
	var reqTahun= $("#reqTahun").val();
	var s_url= "../json-ikk/toleransi_talent_pool_data.php?reqTahun="+reqTahun;
	// var s_url= "../json-ikk/toleransi_talent_potensi_kompetensi_pool_data.php?reqTahun="+reqTahun;

	//alert(s_url);return false;
	$.ajax({'url': s_url,'success': function(dataJson) {
		var data= JSON.parse(dataJson);
		//alert(data.tempToleransiY);
		tempToleransiY= data.tempToleransiY;
		tempToleransiX= data.tempToleransiX;

		reqOptionY=0;
		if(parseFloat(tempToleransiY) < 0)
		{
			$("#reqOptionY").val("-");
			reqOptionY= parseFloat(tempToleransiY) * -1;
		}
		else
		{
			$("#reqOptionY").val("+");
		}
		$("#reqOptionY").val(reqOptionY);
		$("#reqToleransiY").val(tempToleransiY);

		reqOptionX=0;
		if(parseFloat(tempToleransiX) < 0)
		{
			$("#reqOptionX").val("-");
			reqOptionX= parseFloat(tempToleransiX) * -1;
		}
		else
		{
			$("#reqOptionX").val("+");
		}
		$("#reqToleransiX").val(tempToleransiX);

	}});
}

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
});

function setProsesToleransi()
{
	dataexportkuadran= Highcharts.getSVG([chartkuadran]);
	// console.log(dataexportpotensi);return false;
	var data = {
		options: dataexportkuadran,
		filename: 'kuadran_<?=$reqId?>_<?=$reqJadwalTesId?>',
		async: true
	};

	$.ajax({
    	type: 'POST',
    	data: data,
    	url: '../pengaturan/convertsvg.php',
    	async: false,
    	success: function(data){
    	}
    });
}
</script>

<style>
.konten-table{
	float:right; width:50%; clear:both; top:0; right:0; position:absolute; padding:8px 20px 18px; box-sizing:border-box;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: 14px;
}
.konten-table table{
	border-collapse:collapse;
	width:100%;

}
.konten-table table th{
	border:1px solid #ccc;
	padding:4px 7px;
	background:yellow;
}
.konten-table table td{
	border:1px solid #ccc;
	padding:4px 7px;
	text-align:center;
}
</style>
</head>

<body style="background-color: white !important; border: 0px solid red;">
	<div style="width:100%; border:1px solin cyan; float:left;">
        <div id="container" style="width:100%; height:94vh"> </div>
    </div>

    <!-- <div style="float: right; width: 45%; height: calc(100vh - 10px) border: 2px solid green; position: relative;">
		<div style="border: 0px solid red; margin-top: 0px;">

	        <div class="bar-status" style="width:100% !important; border:1px; padding-left: 20px">
		        <table border="0" style="width:100%">
		        	<tr>
		        		<td style="width: 25%">Tahun Assessment</td>
		        		<td style="width: 10px">:</td>
		        		<td colspan="4">
		        			<select name="reqTahun" id="reqTahun">
					        <?
					        for($i=0;$i<count($arrTahun);$i++)
					        {
					        ?>
					        <option value="<?=$arrTahun[$i]["TAHUN"]?>" <? if($reqTahun == $arrTahun[$i]["TAHUN"]) { ?> selected <? } ?>><?=$arrTahun[$i]["TAHUN"]?></option>
					        <?
					        }
					        ?>
					        </select>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td style="width: 25%">Formula</td>
		        		<td style="width: 10px">:</td>
		        		<td colspan="4">
		        			<select id="reqFormulaId" name="reqFormulaId">
		        				<option selected="selected" value="">Semua</option>
		        				<?
		        				while($set_formula->nextRow())
		        				{
		        					?>
		        					<option value="<?=$set_formula->getField("FORMULA_ID")?>"><?=$set_formula->getField("FORMULA")?></option>
		        					<?
		        				}
		        				?>
		        			</select>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td>Nama</td>
		        		<td>:</td>
		        		<td colspan="4">
		        			<input type="text" id="reqPencarian" style="width: 80%" value="" />
		        		</td>
		        	</tr>
		        </table>
		        <button onclick="setProsesToleransi()">Proses Data</button>

		        <br/>

		        <table border="0" style="width:100%">
		        	<tr>
		            	<th colspan="5">JPM</th>
		                <th colspan="5">Kinerja</th>
		            </tr>
		            <tr>
		            	<td style="width:50px">Kurang</td>
		                <td style="width:5px">:</td>
		                <td style="width:50px"><input class="easyui-numberspinner" id="reqSkpY0" value="<?=$tempSkpY0?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		                <td style="width:25px; text-align:center">s/d</td>
		                <td><input class="easyui-numberspinner" id="reqSkpX0" value="<?=$tempSkpX0?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		                <td style="width:50px">Kurang</td>
		                <td style="width:5px">:</td>
		                <td style="width:50px"><input class="easyui-numberspinner" id="reqGmX0" value="<?=$tempGmX0?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		                <td style="width:25px; text-align:center">s/d</td>
		                <td><input class="easyui-numberspinner" id="reqGmY0" value="<?=$tempGmY0?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		            </tr>
		            <tr>
		            	<td>Sedang</td>
		                <td>:</td>
		                <td><input type="hidden" id="reqSkpY1" value="<?=$tempSkpY1?>" /><label id="reqInfoSkpY1"><?=$tempSkpInfoY1?></label></td>
		                <td style="text-align:center">s/d</td>
		                <td><input class="easyui-numberspinner" id="reqSkpX1" value="<?=$tempSkpX1?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		                <td>Sedang</td>
		                <td>:</td>
		                <td><input type="hidden" id="reqGmX1" value="<?=$tempGmX1?>" /><label id="reqInfoGmX1"><?=$tempGmInfoX1?></label></td>
		                <td style="text-align:center">s/d</td>
		                <td><input class="easyui-numberspinner" id="reqGmY1" value="<?=$tempGmY1?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		            </tr>

		            <tr>
		            	<td>Baik</td>
		                <td>:</td>
		                <td><input type="hidden" id="reqSkpY2" value="<?=$tempSkpY2?>" /><label id="reqInfoSkpY2"><?=$tempSkpInfoY2?></label></td>
		                <td style="text-align:center">s/d</td>
		                <td><input class="easyui-numberspinner" id="reqSkpX2" value="<?=$tempSkpX2?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		                <td>Baik</td>
		                <td>:</td>
		                <td><input type="hidden" id="reqGmX2" value="<?=$tempGmX2?>" /><label id="reqInfoGmX2"><?=$tempGmInfoX2?></label></td>
		                <td style="text-align:center">s/d</td>
		                <td><input class="easyui-numberspinner" id="reqGmY2" value="<?=$tempGmY2?>" style="width:44px;" data-options=" min: 0, max: 200" /></td>
		            </tr>

		        </table>
	        </div>
	    </div>

    	<div style="clear: both;"></div>
	    <div style="width:100%; height: calc(60vh - 50px) !important; overflow: auto; position: relative; margin-top: 20px;" class="konten-table" id="tableGrafik"></div>

    </div> -->
<div style="clear:both"></div>

</body>
</html>

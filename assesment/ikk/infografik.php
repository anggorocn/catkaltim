<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");
include_once("../WEB/classes/base-ikk/ToleransiTalentPool.php");

$reqInfoLink= httpFilterGet("reqInfoLink");
$reqLink= httpFilterGet("reqLink");
$reqId= httpFilterGet("reqId");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");

$statement= " AND A.PEGAWAI_ID = ".$reqId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set= new Penilaian();
$set->selectByParamsTahunPenilaian($statement);
$set->firstRow();
$reqTahun= $set->getField("TAHUN");

$statement="
AND EXISTS
(
	SELECT 1 
	FROM penilaian XXX WHERE ASPEK_ID = 1 AND PEGAWAI_ID = ".$reqId." AND JADWAL_TES_ID = ".$reqJadwalTesId."
	AND XXX.PENILAIAN_ID = B.PENILAIAN_ID
)
";
$arrDataPotensi= array();
$index= 0;
$set= new PenilaianDetil();
$set->selectByParamsSpiderPenilaian(array(), -1, -1, $statement);
while($set->nextRow())
{
  $arrDataPotensi[$index]["NAMA"]= $set->getField("NAMA");
  $arrDataPotensi[$index]["NILAI"]= $set->getField("NILAI");
  $arrDataPotensi[$index]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $index++;
}
$jumlahDataPotensi= $index;
// print_r($arrDataPotensi);exit;

$statement="
AND EXISTS
(
	SELECT 1 
	FROM penilaian XXX WHERE ASPEK_ID = 2 AND PEGAWAI_ID = ".$reqId." AND JADWAL_TES_ID = ".$reqJadwalTesId."
	AND XXX.PENILAIAN_ID = B.PENILAIAN_ID
)
";
$arrDataKompetensi= array();
$index= 0;
$set= new PenilaianDetil();
$set->selectByParamsSpiderPenilaian(array(), -1, -1, $statement);
while($set->nextRow())
{
  $arrDataKompetensi[$index]["NAMA"]= $set->getField("NAMA");
  $arrDataKompetensi[$index]["NILAI"]= $set->getField("NILAI");
  $arrDataKompetensi[$index]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $index++;
}
$jumlahDataKompetensi= $index;
// print_r($arrDataKompetensi);exit;

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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css">

	<script src="../WEB/lib/highcharts/jquery-3.1.1.min.js"></script>
    <script src="../WEB/lib/highcharts/highcharts-spider.js"></script>
    <script src="../WEB/lib/highcharts/highcharts-more.js"></script>
    <script src="../WEB/lib/highcharts/exporting-spider.js"></script>
    <script src="../WEB/lib/highcharts/export-data.js"></script>
    <script src="../WEB/lib/highcharts/accessibility.js"></script>
</head>

<body>
	<div id="buttonrow">
		<button id="export-png"><?=$reqInfoLink?></button>
	</div>

	<div class="container" style="width: 80%;">
		<table style="font-size: 10pt; width: 100%;">
			<tr>
				<td colspan="2" style="width: 70%; text-align: center;">
					POSISI QUADRAN TALENT POOL SAAT INI
					<div id="containergrafik"></div>
				</td>
			</tr>
			<tr>
				<td style="width: 50%; text-align: center;">
					GRAFIK GAMBARAN KOMPETENSI SAAT INI
					<div id="containerkompetensi"></div>
				</td>
				<td style="width: 50%; text-align: center;">
					GRAFIK GAMBARAN POTENSI SAAT INI
					<div id="containerpotensi"></div>
				</td>
			</tr>
		</table>
	</div>
</body>

<script>
	var chartkompetensi= chartpotensi= chartkuadran= "";
    $(function(){
    	setGrafik("../json-ikk/infografik_json.php?reqId=<?=$reqId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$reqTahun?>");

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

    	$('#export-png').click(function () {
			dataexportpotensi= Highcharts.getSVG([chartpotensi]);
			var data = {
				options: window.encodeURIComponent(dataexportpotensi),
				// options: dataexportpotensi,
				filename: 'potensi_<?=$reqId?>_<?=$reqJadwalTesId?>',
				async: true
			};

			$.ajax({
		    	type: 'POST',
		    	data: data,
		    	url: '../pengaturan/convertsvg.php',
		    	async: false,
		    	success: function(data){
		    		dataexportkompetensi= Highcharts.getSVG([chartkompetensi]);
					var data = {
						// options: dataexportkompetensi,
						options: window.encodeURIComponent(dataexportkompetensi),
						filename: 'kompetensi_<?=$reqId?>_<?=$reqJadwalTesId?>',
						async: true
					};

					$.ajax({
				    	type: 'POST',
				    	data: data,
				    	url: '../pengaturan/convertsvg.php',
				    	async: false,
				    	success: function(data){
				    		dataexportkuadran= Highcharts.getSVG([chartkuadran]);
							var data = {
								// options: dataexportkuadran,
								options: window.encodeURIComponent(dataexportkuadran),
								filename: 'kuadran_<?=$reqId?>_<?=$reqJadwalTesId?>',
								async: true
							};

							$.ajax({
						    	type: 'POST',
						    	data: data,
						    	url: '../pengaturan/convertsvg.php',
						    	async: false,
						    	success: function(data){
						    		newWindow = window.open('<?=$reqLink?>.php?reqId=<?=$reqId?>&reqJadwalTesId=<?=$reqJadwalTesId?>&reqTahun=<?=$reqTahun?>');
						    		newWindow.focus();
						    	}
						    });

				    	}
				    });
		    	}
		    });


		    


		});
	});

	chartkompetensi = new Highcharts.chart({
		chart: {
			renderTo: 'containerkompetensi',
			polar: true,
			type: 'line'
		},

		title: {
			text: '',
			x: -80
		},

		pane: {
			size: '80%'
		},

		xAxis: {
			labels: {
				rotation: 1,
				step: 1
			},
			categories: [
			<?
			for($index_data=0; $index_data < $jumlahDataKompetensi; $index_data++)
			{
				if($index_data > 0)
					echo ",";
			?>
				'<?=$arrDataKompetensi[$index_data]["NAMA"]?>'
			<?
			}
			?>
			],
			tickmarkPlacement: 'on',
			lineWidth: 0
		},

		credits: {
	      enabled: false
	    },

		yAxis: {
			gridLineInterpolation: 'polygon',
			lineWidth: 0,
			min: 0,
			labels: {
	            enabled: false
	        }
		},

		tooltip: {
			shared: true,
			pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
		},

		legend: {
			align: 'right',
			verticalAlign: 'middle',
			layout: 'vertical'
		},

		series: [
		{
			name: 'Capaian',
			data: [
			<?
			for($index_data=0; $index_data < $jumlahDataKompetensi; $index_data++)
			{
				if($index_data > 0)
					echo ",";
			?>
				<?=$arrDataKompetensi[$index_data]["NILAI"]?>
			<?
			}
			?>
			],
			pointPlacement: 'on',
			color: "#0F0",
			dataLabels: {
				// inside: true,
				enabled: true
				// , style: {
				// 	color: 'white'
				// }
			}
		}
		, 
		{
			name: 'Nilai Standar',
			data: [
			<?
			for($index_data=0; $index_data < $jumlahDataKompetensi; $index_data++)
			{
				if($index_data > 0)
					echo ",";
			?>
				<?=$arrDataKompetensi[$index_data]["NILAI_STANDAR"]?>
			<?
			}
			?>
			],
			pointPlacement: 'on',
			color: "#FF0000",
			dataLabels: {
				// inside: true,
				enabled: true
				// , style: {
				// 	color: 'white'
				// }
			}
		}
		],

		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						align: 'center',
						verticalAlign: 'bottom',
						layout: 'horizontal'
					},
					pane: {
						size: '70%'
					}
				}
			}]
		}

	});

	chartpotensi = new Highcharts.chart({
		chart: {
			renderTo: 'containerpotensi',
			polar: true,
			type: 'line'
		},

		title: {
			text: '',
			x: -80
		},

		pane: {
			size: '80%'
		},

		xAxis: {
			labels: {
				rotation: 1,
				step: 1
			},
			categories: [
			<?
			for($index_data=0; $index_data < $jumlahDataPotensi; $index_data++)
			{
				if($index_data > 0)
					echo ",";
			?>
				'<?=$arrDataPotensi[$index_data]["NAMA"]?>'
			<?
			}
			?>
			],
			tickmarkPlacement: 'on',
			lineWidth: 0
		},

		credits: {
	      enabled: false
	    },

		yAxis: {
			gridLineInterpolation: 'polygon',
			lineWidth: 0,
			min: 0,
			labels: {
	            enabled: false
	        }
		},

		tooltip: {
			shared: true,
			pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
		},

		legend: {
			align: 'right',
			verticalAlign: 'middle',
			layout: 'vertical'
		},

		series: [
		{
			name: 'Capaian',
			data: [
			<?
			for($index_data=0; $index_data < $jumlahDataPotensi; $index_data++)
			{
				if($index_data > 0)
					echo ",";
			?>
				<?=$arrDataPotensi[$index_data]["NILAI"]?>
			<?
			}
			?>
			],
			pointPlacement: 'on',
			color: "#0F0",
			dataLabels: {
				// inside: true,
				enabled: true
				// , style: {
				// 	color: 'white'
				// }
			}
		}
		, 
		{
			name: 'Nilai Standar',
			data: [
			<?
			for($index_data=0; $index_data < $jumlahDataPotensi; $index_data++)
			{
				if($index_data > 0)
					echo ",";
			?>
				<?=$arrDataPotensi[$index_data]["NILAI_STANDAR"]?>
			<?
			}
			?>
			],
			pointPlacement: 'on',
			color: "#FF0000",
			dataLabels: {
				// inside: true,
				enabled: true
				// , style: {
				// 	color: 'white'
				// }
			}
		}
		],

		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						align: 'center',
						verticalAlign: 'bottom',
						layout: 'horizontal'
					},
					pane: {
						size: '70%'
					}
				}
			}]
		}

	});

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
						renderTo: 'containergrafik',
					},
					credits: {
						enabled: false
					},
					legend:{
						enabled:false
					},
					xAxis: {
						title:{
							 text:'Potensi'
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
							text:'Kompetensi',
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
						var width= chart.plotBox.width;
						var height= chart.plotBox.height;
						var tempplotbox= tempplotboy= tempwidth= tempxwidth= tempheight= 0;
						var modif= 45;

						//garis I
						//=====================================================================================
						tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
						tempheight1= tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) - modif;
						tempplotbox= chart.plotBox.x;
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y;
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
						tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
						tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) - modif;
						tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y;
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
						tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
						tempheight= parseFloat(height) * ((reqGmY2 - parseFloat(reqGmY1)) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) - modif;
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
						tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
						tempheight2= tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) - modif;
						tempplotbox= chart.plotBox.x;
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
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
						tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
						tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) - modif;
						tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
						chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
							fill: '#c4d79b',
							zIndex: 0
						}).add();

						var text = chart.renderer.text("V", tempwidth, tempheight).add();
						text.attr({
							x: tempxwidth,
							y: tempyheight,
							zIndex:99
						});
						//=====================================================================================
						tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
						tempheight= parseFloat(height) * ((parseFloat(reqGmY1) - parseFloat(reqGmY0)) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) - modif;
						tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y + parseFloat(tempheight1);
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
						tempwidth1= tempwidth= parseFloat(width) * (parseFloat(reqSkpX0) / reqSkpX2);
						tempheight3= tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2) - modif;
						tempplotbox= chart.plotBox.x;
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
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
						tempwidth2= tempwidth= parseFloat(width) * ((parseFloat(reqSkpX1) - parseFloat(reqSkpX0)) / reqSkpX2);
						tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2) - modif;
						tempplotbox= parseFloat(chart.plotBox.x) + parseFloat(tempwidth1);
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
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
						tempwidth= parseFloat(width) * ((reqSkpX2 - parseFloat(reqSkpX1)) / reqSkpX2);
						tempheight= parseFloat(height) * (parseFloat(reqGmY0) / reqGmY2);
						tempyheight= chart.plotBox.x + (parseFloat(tempheight) / 3) + parseFloat(tempheight1) + parseFloat(tempheight2) - modif;
						tempplotbox= chart.plotBox.x + parseFloat(tempwidth1) + parseFloat(tempwidth2);
						tempxwidth= tempplotbox + parseFloat(parseFloat(tempwidth) / 2);
						tempplotboy= chart.plotBox.y + parseFloat(tempheight1) + parseFloat(tempheight2);
						chart.renderer.rect(tempplotbox,tempplotboy, tempwidth, tempheight, 1).attr({
							fill: '#00b050',
							zIndex: 0
						}).add();

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
</script>
</html>
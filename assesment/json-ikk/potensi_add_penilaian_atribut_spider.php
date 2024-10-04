<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");
include_once("../WEB/classes/base-ikk/Penilaian.php");
include_once("../WEB/classes/base-ikk/PenilaianDetil.php");

$reqRowId= httpFilterGet("reqRowId");
//$reqRowId=2;

$set_grafik= new PenilaianDetil();
$index_array=0;
$set_grafik->selectByParamsSpiderPenilaian(array(), -1, -1, " AND B.PENILAIAN_ID = ".$reqRowId);
//echo $set_grafik->query;exit;

$indexData=0;
$arrData="";
while($set_grafik->nextRow())
{
	$arrData[$indexData]["NAMA"] = $set_grafik->getField("NAMA");
	$arrData[$indexData]["NILAI"] = round($set_grafik->getField("NILAI"),2);
	$arrData[$indexData]["NILAI_STANDAR"] = round(setValNol($set_grafik->getField("NILAI_STANDAR")),2);
	
	$indexData++;
}
//print_r($arrData);exit;

$arrKolom[0]["NAMA"] = "CAPAIAN";
$arrKolom[0]["NILAI"] = "NILAI";
$arrKolom[1]["NAMA"] = "NILAI STANDART";
$arrKolom[1]["NILAI"] = "NILAI_STANDAR";

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Aplikasi Dashboarding Nurul Hayat</title>

		<!-- Bootstrap -->
		<link href="../WEB/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="../WEB/lib/bootstrap/css/equal-height-columns.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css">
		
		<!-- HIGHCHART -->
		<?php /*?><script src="../WEB/js/jquery.js"></script>
		<script src="../WEB/lib/highcharts/highcharts.js"></script>
		<script src="../WEB/lib/highcharts/exporting.js"></script><?php */?>
	</head>
	<body>
		<div id="wrap">
			<div id="main" class="container-fluid clear-top">
				<div class="row">
					<div class="col-lg-12">
						
						<div class="container" style="margin-top:30px;">
							<div class="row" style="border:0px solid red;">
									
								<div class="col-md-12" style="border:0px solid cyan; padding-top:0px;">
									<div id="container-column" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
								</div>
								
							</div>
						</div>    	
					</div>
				</div>
			</div>
		</div>
		<!-- HIGHCHART -->
		<script>
			$(function () {
				$('#container-column').highcharts({
					chart: {
						type: 'column'
					},
					title: {
							text: 'Grafik',					},
					subtitle: {
						//text: 'Source: WorldClimate.com'
					},
					xAxis: {
						categories: [
							<?
							for($i=0; $i < $indexData; $i++)
							{
								$separator= "";
								if($i == 0){}
								else
									$separator= ",";
							?>
							<?=$separator."'".$arrData[$i]["NAMA"]."'"?>
							<?
							}
							?>
						],
						crosshair: true
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Rainfall (mm)'
						}
					},
					tooltip: {
						headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
						pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
						footerFormat: '</table>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						<?
						for($i=0; $i < 2; $i++)
						{
							$separator= "";
							if($i == 0){}
							else
								$separator= ",";
						?>
						<?=$separator?>
						{
							name: <?="'".$arrKolom[$i]["NAMA"]."'"?>, 
							data: 
							[
								<?
								for($x=0; $x < $indexData; $x++)
								{
									$separator= "";
									if($x == 0){}
									else
										$separator= ",";
								?>
								<?=$separator.$arrData[$x][$arrKolom[$i]["NILAI"]]?>
								<?
								}
								?>
							]
						}
						<?
						}
						?>
						//{name: 'Tokyo',data: [49.9, 71.5]}, 
						//{name: 'New York',data: [83.6, 78.8]}
					]
				});
			});
		</script>
	</body>
</html>
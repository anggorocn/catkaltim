<?
include_once("../WEB/page_config.php");
include_once("../WEB/functions/default.func.php");

$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.6.1.min.js"></script>

<style>
/*html{
	height:100%;
	min-height:100%;
}
body{
	padding:0 0;
	margin:0 0;
	height:100%;
	min-height:100%;
}

* {	
	padding: 0;
	margin: 0;
}*/

* {
	padding: 0;
	margin: 0;
}
html, body {
	height: 100%;
}

.kontainer-atasBagi{
	height: -moz-calc(100% - 200px);
    height: -webkit-calc(100% - 200px);
    height: -o-calc(100% - 200px);
    height: calc(100% - 200px);
}

.kontainer-atasFull{
	height: -moz-calc(100%);
    height: -webkit-calc(100%);
    height: -o-calc(100%);
    height: calc(100%);
}
	
#kontainer-atas{
	width:100%;
	/*height:305px;*/
	/*height: -moz-calc(100% - 200px);
    height: -webkit-calc(100% - 200px);
    height: -o-calc(100% - 200px);
    height: calc(100% - 200px);*/
	
	margin:0px;
	min-width: calc(100% - 0px);
	max-width: calc(100% - 0px);
	background: #FFF;
	float:left;
	overflow:auto;
	
	-webkit-transition: height 1s;
    -moz-transition: height 1s;
    -ms-transition: height 1s;
    -o-transition: height 1s;
    transition: height 1s;
}
#kontainer-atas.hidden{
	height:0px;
}

* html #trdetil {
	/*height: calc(100% - 305px);*/
	height:200px;
}
#trdetil {
	/*max-height: calc(100% - 100px);
	min-height: calc(100% - 100px);*/
	width:100%;
	/*background: #DDD ;*/
	
	background:#f7f7f7;
	background-image: url(../images/bg-main-top.png), url(../images/bg-home.png);
	background-position: top left, bottom left;
	background-repeat: repeat-x, no-repeat;
	
	/*background: url(images/bg-main-top.png) top left repeat-x, url(images/bg-home.png) bottom left no-repeat;*/ 
	/*border-left: 2px solid #666;
	border-right: 2px solid #666;*/
	/*height: calc(100% - 305px);*/
	height:200px;
	/*margin-left:219px;*/
	/*margin: 0 auto;*/
	float:left;
	overflow:auto;

	-webkit-transition: height 1s;
    -moz-transition: height 1s;
    -ms-transition: height 1s;
    -o-transition: height 1s;
    transition: height 1s;
	
}

#trdetil.hidden {
	height: calc(100% - 0px);
}
button#atasbawah{
	position:absolute;
	height:21px;
	background:#01509d;
	right:0;
	
	
}
button{ background:#fd8832; color:#FFF; width:100px; height:200px; border:none; /*margin:5px 0;*/}

</style>

<script type="text/javascript">
$(window).load(function(){
	$('button#atasbawah').click(function () {
		//$("a").removeClass("menuAktifDynamis");
		$('#kontainer-atas').toggleClass('hidden');
		$('#trdetil').toggleClass('hidden');
	});
});

function setShowHideMenu(kondisi)
{
	//alert(kondisi);
	if(kondisi == 1)//bagi dua
	{
		$("#kontainer-atas").removeClass("kontainer-atasFull");
		$("#kontainer-atas").addClass("kontainer-atasBagi");
	}
	else if(kondisi == 3)//detil full
	{
		$('#kontainer-atas').toggleClass('hidden');
		$('#trdetil').toggleClass('hidden');
	}
	else
	{
		$("#kontainer-atas").removeClass("kontainer-atasBagi");
		$("#kontainer-atas").addClass("kontainer-atasFull");
	}
}
</script>
</head>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
	<div id="kontainer-atas" class="kontainer-atasBagi">
        <iframe class="mainframe" id="idMainFrame" name="mainFramePop" src="assesment_meeting_add_monitoring.php?reqJadwalTesId=<?=$reqJadwalTesId?>&reqPegawaiId=<?=$reqPegawaiId?>" style="width:100%; height:calc(100% - 5px); border:none;"></iframe>
    </div>
    
    <div id="trdetil" style="display:none">
        <button id="atasbawah">Show/Hide</button>
        <iframe class="mainframe" id="idMainFrameDetil" name="mainFrameDetilPop" src="assesment_meeting_add_detil.php" style="width:100%; height: 100%;  border:none;"></iframe>
    </div>
</body>
</html>

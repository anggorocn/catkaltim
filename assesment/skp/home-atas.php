
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery treeTable Plugin Documentation</title>


<link rel="stylesheet" href="../WEB/css/gaya.css" type="text/css"> 
<script src="popup.js" type="text/javascript"></script>
 
<link href="../WEB/css/themes/main.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<script src="forms/jquery.uniform.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
  $(function(){
	$("input, textarea, select, button").uniform();
  });
</script>
<link rel="stylesheet" href="forms/css/uniform.default.css" type="text/css" media="screen">
 <style type="text/css" media="screen">
      label {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 3px;
        clear: both;
      }
    </style>
<!-- BEGIN Plugin Code -->
  <!-- END Plugin Code -->
  
  <!-- Popup -->  
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

<link href="styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.tambaharsip,.editarsip,.laporan,.donatebutton').append('<span class="hover"></span>').each(function () {
			var $span = $('> span.hover', this).css('opacity', 0);
			$(this).hover(function () {
				$span.stop().fadeTo(500, 1);
			}, function () {
		$span.stop().fadeTo(500, 0);
			});
		});
	});	
</script>

<script language="Javascript">
<? include_once "../jslib/formHandler.php"; ?>

function openPopup(opUrl,opWidth,opHeight)
{
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1, scrollbars");
	newWindow.focus();
}
</script>

<script language="JavaScript" src="../jslib/displayElement.js"></script>  

</head>
<script type="text/javascript">
 
$(document).ready(function(){
 
	$('#page_effect').fadeIn(2000);
 
});
 
</script>
<body>

<script language=JavaScript>
<!--

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="Function Disabled!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("return false")

// --> 
</script>

<div id="page_effect" style="display:none;">
<div id="bg"><img src="../WEB/images/bg-home.jpg" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto">
	<div style="float:right;"><img src="../WEB/images/logo-besar.png" /></div>
</div>
</div>
</body>
</html>
<?php
include_once("../WEB/classes/base/Content.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/classes/base/Product.php");
/* OBJECTS */
$content = new Content();
$product = new Product();

$product->selectByParams(array());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Valsix - Easing Technology</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/easySlider1.5.js"></script>
<script type="text/javascript" charset="utf-8">
// <![CDATA[
$(document).ready(function(){	
	$("#slider").easySlider({
		controlsBefore:	'<p id="controls">',
		controlsAfter:	'</p>',
		auto: true, 
		continuous: true
	});	
});
// ]]>
</script>
<style type="text/css">
#slider { margin:0; padding:0; list-style:none; }
#slider ul,
#slider li { margin:0; padding:0; list-style:none; }
/* 
    define width and height of list item (slide)
    entire slider area will adjust according to the parameters provided here
*/
#slider li { width:933px; height:252px; overflow:hidden; }
p#controls { margin:0; position:relative; }
#prevBtn,
#nextBtn { display:block; margin:0; overflow:hidden; width:30px; height:30px; position:absolute; left: -10px; top:-120px; }
#nextBtn { left:918px; }
#prevBtn a { display:block; width:30px; height:30px; background:url(images/l_arrow.gif) no-repeat 0 0; }
#nextBtn a { display:block; width:30px; height:30px; background:url(images/r_arrow.gif) no-repeat 0 0; }
</style>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="block_header">
      <div class="logo"><a href="index.php"><img src="images/logo.png" width="933" height="125" border="0" alt="logo" /></a></div>
      <div class="menu">
        <ul>
          <li><a href="index.php" class="active">Home</a></li>
          <li><a href="services.php">Product &amp;Service</a></li>
          <li><a href="portfolio.php">Portfolio</a></li>
          <li><a href="contact.php">Contact Us</a></li>
        </ul>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="slider">
    <div id="slider">
      <ul>
      	<?php
		while($product->nextRow())
		{
		?>
        <li>
          <div>
            <p class="img"><img src="images/simple_text_img_1.jpg" alt="screen 1" width="354" height="213" border="0" /></p>
            <h2><?=$product->getField('nama')?> <font style="font-size:14px">(<?=$product->getField('detil_nama')?>)</font></h2>
            <p><?=$product->getField('keterangan')?></p>
            <p><a href="#"><img src="images/read_more.gif" width="81" height="24" border="0" alt="img" /></a></p>
          </div>
        </li>
        <?
        }
		?>
      </ul>
    </div>
  </div>
  <div class="clr"></div>
  <div class="body">
    <div class="topi">
      <div class="blogi">
        <h2><?=$content->getContentTitle(1)?></h2>
        <p><?=$content->getContentText(1)?></p>
      </div>
      <div class="blogi">
        <h2>Services Overview</h2>
        <ul>
          <li>» Custom Build Desktop Application</li>
          <li>» Dynamic Website Development</li>
          <li>» e-Commerce Website Solution</li>
        </ul>
      </div>
      <div class="blogi">
        <h2><?=$content->getContentTitle(2)?></h2>
        <p><?=$content->getContentText(2)?></p>
      </div>
      <div class="clr"></div>
    </div>
    <div class="body_resize">
      <div class="body_h2_main">
        <p>Hi There...</p>
        <h2>Welcome Aboard</h2>
      </div>
      <div class="search">
        <div class="bloklive">
        <p class="img"><a href="#"><img src="images/livesupport.gif" alt="img" width="229" height="66" border="0" /></a></p>
        </div>
        <div class="clr"></div>
      </div>
      <div class="left">
        <h2><?=$content->getContentTitle(3)?></h2>
        <p><?=$content->getContentText(3)?></p>
        <p></p>
        <div class="bg"></div>
        <p>&nbsp;</p>
        <h2><?=$content->getContentTitle(4)?></h2>
        <p><?=$content->getContentText(4)?></p>
        <p class="img"><img src="images/serv_1.gif" width="100" height="100" alt="img" /></p>
        <h3>Custom Build Desktop Application</h3>
        <p>Kami memberikan layanan jasa pembuatan Aplikasi Desktop yang dapat di kustomisasi dan terintegrasi yang sesuai dengan kebutuhan proses bisnis Anda.</p>
        <div class="bg"></div>
        <p class="img"><img src="images/serv_2.gif" width="100" height="100" alt="img" /></p>
        <h3>Dynamic Website Development</h3>
        <p>Kami menyediakan layanan jasa web lengkap, diantaranya: perancangan website, animasi flash, pengembangan website,        pembuatan aplikasi berbasis web, web reporting, Solusi pegembangan website e-commerce, dan jasa hosting website.     </p>
        <div class="bg"></div>
        <p class="img"><img src="images/serv_3.gif" width="100" height="100" alt="img" /></p>
        <h3>Interactive Multimedia Technology</h3>
        <p>Kami mengembangkan dan mebuat Aplikasi Multimedia interaktif mulai dari Company Profile interaktif, Interaktif User Guide, sampai dengan Media Digital Interaktif untuk digunakan di eksebisi dan expo.</p>
        <div class="bg"></div>
        <p>&nbsp;</p>
      </div>
      <div class="right">
        <h2>Our Goals</h2>
        <p>Dalam setiap solusi layanan berbasis IT yang kami tawarkan kepada  Klien, Kami selalu mengedepankan user experienced &quot;the valuable six&quot;  dalam setiap pencapaiannya. Ke-enam &quot;the valuable six&quot; itu adalah.<a href="#"></a></p>
        <ul>
          <li><a href="#">Easy to Use</a></li>
          <li><a href="#">Good looking interface</a></li>
          <li><a href="#">Reliable Service</a></li>
          <li><a href="#">Solved what user needs</a></li>
          <li><a href="#">Flexible &amp; competitive price</a></li>
          <li><a href="#">Painless Procedure</a></li>
        </ul>
        <div class="bg"></div>
        <h2>Featured Client</h2>
        <center>
        <p class="img"><a href="#"><img src="images/client/bpstudio.gif" width="229" height="66" border="1" alt="img" align="middle" style="border:dotted #131313;" /></a></p>
         <p class="img"><a href="#"><img src="images/client/speedyclient.gif" alt="img" width="229" height="66" border="1" align="middle" style="border:dotted #131313;" /></a></p>
        <p class="img"><a href="#"><img src="images/client/mmpmalang.gif" width="229" height="66" border="1" alt="img" align="middle" style="border:dotted #131313;" /></a></p>
        <p class="img"><a href="#"><img src="images/client/sitaralogo.gif" alt="img" width="229" height="66" border="1" align="middle" style="border:dotted #131313;" /></a></p>
        <p class="img"><a href="#"><img src="images/client/pimmalang.gif" alt="img" width="229" height="66" border="1" align="middle" style="border:dotted #131313;" /></a></p>
        </center>
        <p>&nbsp;</p>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="footer">
    <div class="resize">
      <div>
        <p>Copyright ©Valsix - easing technology. <a href="http://dreamtemplate.com/">www.valsix.in</a>. All Rights Reserved<br />
          <a href="index.php">Home</a> | <a href="contact.php">Contact</a> | <a href="blog.php">RSS</a></p>
      </div>
    </div>
    <p class="clr"></p>
  </div>
</div>
</body>
</html>
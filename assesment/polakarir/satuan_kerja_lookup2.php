<?
/* *******************************************************************************************************
MODUL NAME 			: informasi Kategori
FILE NAME 			: informasi_kategori.php
AUTHOR				: Aon-Prog
VERSION				: 1.0 beta
MODIFICATION DOC	:
DESCRIPTION			: Halaman untuk menampilkan informasi kategori
******************************************************************************************************* */

include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/Satker.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");

/* create objects */
$unit_kerja = new Satker();

/* LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/


/* VARIABLES */
$reqStatus = httpFilterGet("reqStatus");
$reqMode = httpFilterRequest("reqMode");
$reqId = httpFilterRequest("reqId");
$reqJudul = httpFilterPost("reqJudul");
$reqKepada = httpFilterPost("reqKepada");
$reqJabatan = httpFilterPost("reqJabatan");
$reqSearchStatusKeyword = httpFilterPost("reqSearchStatusKeyword");
$submitCari = httpFilterPost("submitCari");
$reqSearchField = httpFilterPost("reqSearchField");
$reqSearchKeyword = httpFilterPost("reqSearchKeyword");

/* TEMPORARY VARIABLES */
$tempWPID = $reqWPID;

/* DEFAULT VALUES */
$unit_kerja->selectByParams(array('parent_tree' => 0));	
function getJabatanByParent($id_induk)
{
	$child = new Satker();
	
	$child->selectByParams(array("parent_tree"=>$id_induk));
		
	while($child->nextRow())
	{
		echo "
      <tr id='node-".$child->getField('kode_unker')."' class='child-of-node-".$child->getField('parent_tree')."'>
        <td><span class='file'>".$child->getField('nama_unker')."</span></td>
		<form name='selectform'>
			<input type='hidden' name='data1' value='".$child->getField('kode_unker')."*".$child->getField('nama_unker')."'>        
			<td><input type=button value='Pilih' onClick='sendValue(this.form.data1);'></td>                
		</form>
      </tr>
			 ";
	  getJabatanByParent($child->getField("kode_unker"));		
	}
	unset($child);
}
?>
<html>
<head>
<title>Pilih Jabatan </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="themes/main.css" rel="stylesheet" type="text/css">
<? /* ==================================== JAVASCRIPT ================================== */ ?>
<script language="Javascript">
<? include_once "../jslib/formHandler.php"; ?>

function openPopup(opUrl,opWidth,opHeight)
{
	newWindow = window.open(opUrl, "", "width = " + opWidth + "px, height = " + opHeight + "px, resizable = 1");
	newWindow.focus();
}

<!-- Begin
function sendValue (s){
var selvalue = s.value;
var explode = selvalue.split('*');
var varUnitPemilikId = explode[0];
var varUnitPemilikKode = explode[1];

window.parent.OptionSetMode(selvalue);
//setTimeout(parent.DwinClose, 1000);
window.parent.divwin.close();
}
//  End -->
</script>
<script language="JavaScript" src="../jslib/displayElement.js"></script>
<link rel="stylesheet" href="css/gaya.css" type="text/css" media="screen" />
<script src="jquery-1.2.6.min.js"type="text/javascript"></script>

<link href="../WEB/lib/treeTable/doc/stylesheets/master.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.js"></script>
<script type="text/javascript" src="../WEB/lib/treeTable/doc/javascripts/jquery.ui.js"></script>

<link href="../WEB/lib/treeTable/src/stylesheets/jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../WEB/lib/treeTable/src/javascripts/jquery.treeTable.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$(".example").treeTable({
  initialState: "collapsed"
});

// Drag & Drop Example Code
$("#dnd-example .file, #dnd-example .folder").draggable({
  helper: "clone",
  opacity: .75,
  refreshPositions: true,
  revert: "invalid",
  revertDuration: 300,
  scroll: true
});

$("#dnd-example .folder").each(function() {
  $($(this).parents("tr")[0]).droppable({
    accept: ".file, .folder",
    drop: function(e, ui) { 
      $($(ui.draggable).parents("tr")[0]).appendBranchTo(this);
      
      // Issue a POST call to send the new location (this) of the 
      // node (ui.draggable) to the server.
      $.post("move.php", {id: $(ui.draggable).parents("tr")[0].id, to: this.id});
    },
    hoverClass: "accept",
    over: function(e, ui) {
      if(this.id != $(ui.draggable.parents("tr.parent")[0]).id && !$(this).is(".expanded")) {
        $(this).expand();
      }
    }
  });
});

// Make visible that a row is clicked
$("table#dnd-example tbody tr").mousedown(function() {
  $("tr.selected").removeClass("selected"); // Deselect currently selected rows
  $(this).addClass("selected");
});

// Make sure row is selected when span is clicked
$("table#dnd-example tbody tr span").mousedown(function() {
  $($(this).parents("tr")[0]).trigger("mousedown");
});
});

</script>

<script type="text/javascript">
 
$(document).ready(function(){
 
	$('#page_effect').fadeIn(2000);
 
});
</script>

<style type="text/css">
/* pushes the page to the full capacity of the viewing area */
html {height:100%;}
body {height:100%; margin:0; padding:0;}
/* prepares the background image to full capacity of the viewing area */
#bg {position:fixed; top:0; left:0; width:100%; height:100%;}
/* places the content ontop of the background image */
#content {position:relative; z-index:1;}
</style>

</head>

<body>
<div id="bg"><img src="images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="page_effect">
    <div id="content">
        <table class="example" id="dnd-example">
          <thead>
            <tr>
                <th>Nama Jabatan</th>                
                <th width="7%">Action</th>                                        
            </tr>
          </thead>
          <tbody> 
            <?
            	while($unit_kerja->nextRow())
				{
			?> 
                <tr id="node-<?=$unit_kerja->getField('kode_unker')?>">
                    <td><?=$unit_kerja->getField('nama_unker')?></td>
                    <form name="selectform">
                    <input type="hidden" name="data1" value="<?=$unit_kerja->getField('kode_unker')?>*<?=$unit_kerja->getField('nama_unker')?>">
                    <td><input type=button value="Pilih" onClick="sendValue(this.form.data1);"></td>                
                    </form>
                    <?
                    getJabatanByParent($unit_kerja->getField('kode_unker'));
					?>
                </tr>
			<?php
	            }
            ?>  
            <tr>
                <td colspan="4" width="3%"></td>
            </tr>
          </tbody>
        </table>
         
	</div>                       
</div>
</body>
</html>
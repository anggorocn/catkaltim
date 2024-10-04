<?

include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/utils/PageNumber.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-simpeg/Satker.php");

/* create objects */
$satker = new Satker();

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
  $userLogin->retrieveUserInfo();
}

/* VARIABLES */
$reqMode = httpFilterRequest("reqMode");
$reqCaller = httpFilterRequest("reqCaller");
$reqId= httpFilterRequest("reqId");


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
//$statement= " AND LENGTH(SATKER_ID_PARENT) = 2";
$statement= " AND SATKER_ID_PARENT= '0'";
$satker->selectByParams(array(), -1, -1, $statement);

// echo $satker->query;exit;

function getSatkerByParent($id_induk, $parent)
{
  $child = new Satker();
  
  $child->selectByParams(array("SATKER_ID_PARENT"=>$id_induk));
  // echo $child->query;exit;
    
  while($child->nextRow())
  {   
    //$NAMA = $parent." | ".$child->getField('NAMA');
    $NAMA = $child->getField('NAMA');
    echo "
      <tr id='node-".$child->getField('SATKER_ID')."' class='child-of-node-".$child->getField('SATKER_ID_PARENT')."'>
        <td><span class='file'>".$NAMA."</span></td>
    <form name='selectform'>
      <input type='hidden' name='data1' value='".$child->getField('SATKER_ID')."*".$NAMA."'>        
      <td><input type=button value='Pilih' onClick='sendValue(this.form.data1);'></td>                
    </form>
      </tr>
       ";
    getSatkerByParent($child->getField("SATKER_ID"), $child->getField('NAMA'));   
  }
  unset($child);
}
?>
<html>
<head>
<title>Sistem Informasi Pegawai (SIP)</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="themes/main.css" rel="stylesheet" type="text/css">
<? /* ==================================== JAVASCRIPT ================================== */ ?>
<script language="Javascript">
<!-- Begin -->
function sendValue (s){
var selvalue = s.value;
// console.log(selvalue);
var explode = selvalue.split('*');
var varSatkerId = explode[0];
var varSatker = explode[1];
// console.log(varSatkerId);


parent.OptionSetSatker(varSatkerId, varSatker); 
window.parent.divwin.close(); 

}
//  End -->
</script>
<script language="JavaScript" src="../jslib/displayElement.js"></script>
<link rel="stylesheet" href="../WEB/css/gaya-popup.css" type="text/css" media="screen" />
<script src="../WEB/js/jquery-1.6.1.min.js"type="text/javascript"></script>

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
function pencarian(term, _id, cellNr){
  var suche = term.value.toLowerCase();
  var table = document.getElementById(_id);
  var ele0; var ele1;
  for (var r = 1; r < table.rows.length; r++){
    //ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^>]+>/g,"");
    ele0 = table.rows[r].cells[0].innerHTML.replace(/<[^>]+>/g,"");
    //ele1 = table.rows[r].cells[1].innerHTML.replace(/<[^>]+>/g,"");
    //if (ele0.toLowerCase().indexOf(suche)>=0 || ele1.toLowerCase().indexOf(suche)>=0 )
    if (ele0.toLowerCase().indexOf(suche)>=0)
      table.rows[r].style.display = '';
    else table.rows[r].style.display = 'none';
  }
}
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

<body class="bg-kanan-full">
  <div id="judul-popup" style="margin-top:-4px">Satuan Kerja</div>
  <div id="konten">
        <div style="margin-top:3px; margin-left:5px">
        <form>
            <font style="font-family:'Century Gothic', Calibri, Helvetica, Arial, sans-serif; font-size:12px">Pencarian <input name="filter" onKeyUp="pencarian(this, 'sf', 0)" type="text" style="width:300px"></font>
        </form> 
        </div>  
        <table class="example" id="sf">
          <thead>
            <tr>
                <th>Nama Satuan Kerja</th>
                <th width="7%">Action</th>                                        
            </tr>
          </thead>
          <tbody> 
            <?
            while($satker->nextRow())
            {
              ?> 
                <tr id="node-<?=$satker->getField('SATKER_ID')?>">
                  <td><?=$satker->getField('NAMA')?></td>
                  <form name="selectform">
                    <input type="hidden" name="data1" value="<?=$satker->getField('SATKER_ID')?>*<?=$satker->getField('NAMA')?>">
                    <td><input type=button value="Pilih" onClick="sendValue(this.form.data1);"></td>                
                  </form>
                  <?
                  // $satker->getField('NAMA');
                       getSatkerByParent($satker->getField('SATKER_ID'), $satker->getField('NAMA'));
                  ?>
                </tr>
              <?php
              }
            ?>
          </tbody>
        </table>
         
  </div>                       
</div>
</body>
</html>
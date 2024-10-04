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
include_once("../WEB/classes/base-skp/PeriodePenilaian.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/Validate.php");

/*if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

/* create objects */
$periode_penilaian = new PeriodePenilaian();

$reqHeight = httpFilterGet("reqHeight");
$reqMode = httpFilterGet("reqMode");
$reqId = httpFilterGet("reqId");

if($reqMode == "delete")
{
	$periode_penilaian->setField("GAJI_PERIODE_TAHUN_ID", $reqId);
	if($periode_penilaian->delete())
	{
		echo '<script language="javascript">';
		echo "alert('Data berhasil dihapus.');";
		echo '</script>';		
	}	
	else
	{
		echo '<script language="javascript">';
		echo "alert('Data tidak dapat dihapus, silahkan cek data lain yang berelasi dengan data ini.');";
		echo '</script>';				
	}
}

if($reqMode == "update")
{
	/*$gaji_kondisi->selectByParams(array("GAJI_PERIODE_TAHUN_ID" => $reqId));
	$gaji_kondisi->firstRow();
	$tempNama 		= $gaji_kondisi->getField("NAMA");*/
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Diklat</title>
<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico">
<link href="../WEB/css/gaya.css" rel="stylesheet" type="text/css">

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

<?
if($reqHeight == "")
{
?>
<script type="text/javascript">
	window.location.replace('periode_penilaian.php?reqHeight=' + screen.height);
</script>
<?
}
?>
<style type="text/css">
<!--
div.scroll {
	height: <?=$reqHeight - 255?>px;
	/*width: 100%;*/
	
	/*height: -moz-calc(100% - 400px);
    height: -webkit-calc(100% - 400px);
    height: -o-calc(100% - 400px);
    height: calc(100% - 400px);

	min-height: -moz-calc(100% - 400px);
    min-height: -webkit-calc(100% - 400px);
    min-height: -o-calc(100% - 400px);
    min-height: calc(100% - 400px);*/

	width: -moz-calc(100% - 40px);
    width: -webkit-calc(100% - 40px);
    width: -o-calc(100% - 40px);
    width: calc(100% - 40px);

	/*height:100%;*/
	overflow: auto;
	padding: 18px 20px;
}
.scroll table{
	border-collapse:collapse;
	/*border:1px solid #dddddd;*/
	width:100%;
	font-family: 'Open SansRegular';
}
.scroll table th{
	background:#38a3d5;
	border:1px solid #278bb9;
	border-width:1px 1px 0px 1px;
	color:#FFF;
	font-size:14px;
	padding:4px 8px;
}
.scroll table td{
	background:#f4f4f4;
	border:1px solid #dddddd;
	border-width:0px 1px 1px 1px;
	padding:4px 8px;
	font-size:14px;
}
-->
</style> 

<!-- CSS for Drop Down Tabs Menu #2 -->
<script type="text/javascript" src="../WEB/js/dropdowntabs.js"></script>
<script language="JavaScript" src="../jslib/displayElement.js"></script>  

    <link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
    <script type="text/javascript" src="../WEB/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#ff').form({
				url:'../json-skp/periode_penilaian.php',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					//alert(data);
					$.messager.alert('Info', data, 'info');
					$('#rst_form').click();
					top.frames['mainFrame'].location.reload();
					<?php /*?><? if($reqMode == "update") { ?> window.parent.divwin.close(); <? } ?>	<?php */?>				
				}
			});
		});
	</script>
    <link href="../WEB/css/begron.css" rel="stylesheet" type="text/css">  
    <link href="../WEB/css/bluetabs.css" rel="stylesheet" type="text/css" />
    

</head>
<body style="overflow:hidden">
<div id="begron"><img src="images/bg-kanan.jpg" width="100%" height="100%" alt="Smile"></div>
<div id="wadah">
    <div class="judul-halaman">Periode Penilaian</div>
    <div id="bluemenu" class="bluetabs" style="background:url(../WEB/css/media/bluetab.gif)">&nbsp;
        <!--<ul>
            <li>
            <a href="#" id="btnAdd" onClick="window.parent.createWindow('kategori_add.php?reqMode=insert', 'Administrasi Master Data')" title="Tambah"></a>
           </li>        
        </ul>-->
    </div>
    
    <div class="scroll">
      
      <form id="ff" method="post" novalidate>
      <table>
            <thead>
              <tr>
                  <th>Tahun</th> 
                  <th>Tanggal Awal</th> 
                  <th>Tanggal Akhir</th>                
              </tr>
            </thead>
            <tbody>                
				<?
                $periode_penilaian->selectByParams(array(), -1, -1, "", "ORDER BY TAHUN ASC");
				//echo $periode_penilaian->query;exit;
                while($periode_penilaian->nextRow())
                {
                ?>
                    <tr id="node-<?=$periode_penilaian->getField('GAJI_PERIODE_TAHUN_ID')?>">
                        <td><span class='file'><?=$periode_penilaian->getField('TAHUN')?></span></td>
                        <td><?=dateToPageCheck($periode_penilaian->getField('TANGGAL_AWAL'))?></td>
                        <td><?=dateToPageCheck($periode_penilaian->getField('TANGGAL_AKHIR'))?></td>
                    </tr>
                <?
                }
                ?>                
                <tr>
                    <td colspan="3">
						<?                        
                        $tempPeriode = $periode_penilaian->getField('TAHUN') + 1;
                        ?>
	                    <input name="reqPeriode" class="easyui-validatebox" required="true" size="10" maxlength="6" type="text" value="<?=$tempPeriode?>" />&nbsp;&nbsp;
                        <input type="hidden" name="reqId" value="<?=$reqId?>">
                        <input type="submit" value="Submit">                        
                    </td>
                </tr>
            <?php
				unset($periode_penilaian);
            ?>  
           </tbody>            
          </table>
          <br>
    </form>                   
      
    </div> 

</div>
</body>
</html>
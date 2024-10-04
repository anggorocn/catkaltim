<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Penggalian.php");

$set= new Penggalian();

$reqId = httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode= "insert";
}
else
{
	$reqMode= "update";
	$set->selectByParams(array("PENGGALIAN_ID"=> $reqId),-1,-1,'');
	$set->firstRow();
	
	$tempTahun 		= $set->getField('TAHUN');
	$tempKode		= $set->getField('KODE');
	$tempNama 		= $set->getField('NAMA');
	$tempKeterangan	= $set->getField('KETERANGAN');
	
}
//echo $user_group->query;exit;
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

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	var tempNRP='';
		
	$(function(){
			
		$('#ff').form({
			url:'../json-pengaturan/master_penggalian_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				//alert(data);
				$.messager.alert('Info', data, 'info');
				$('#rst_form').click();
				top.frames['mainFullFrame'].location.reload();
				<? if($reqMode == "update") { ?>
					window.parent.divwin.close();
				<? } ?>
			}
		});
		
	});
</script>
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
<link rel="stylesheet" href="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="../WEB/lib/DHTMLWindow/windowfiles/dhtmlwindow.js"></script> 
<script type="text/javascript">
    function OpenDHTML(opAddress, opCaption, opWidth, opHeight)
    {
        var left = 10;//(screen.width/2)-(opWidth/2);
        
        divwin=dhtmlwindow.open('divbox', 'iframe', opAddress, opCaption, 'width='+opWidth+'px,height='+opHeight+'px,left='+left+',top=20,resize=1,scrolling=1,midle=1'); return false;
    }
	
	function openPencarianPegawai()
    {
        OpenDHTML('pegawai_pencarian.php', 'Pencarian Pegawai', 1500, 600);	
    }
    
    function OptionSetPegawai(id){
        $.getJSON('../json-pengaturan/pegawai_pencarian_info.php?reqPegawaiId=' + id,
          function(data){
            reqPegawai=data.reqPegawai;
            reqPegawaiId=data.reqPegawaiId;
            if(data.reqPegawaiId == ""){}
            else
            {
				$('#reqPegawaiId').val(reqPegawaiId);
				$('#reqPegawaiNama').val(reqPegawai);
            }
            
        });
    }
        
    function openPencarianSatker()
    {
        OpenDHTML('satuan_kerja_pencarian.php', 'Pencarian Satuan Kerja', 800, 600);	
    }
    
    function OptionSetSatker(id){
        $.getJSON('../json-pengaturan/satuan_kerja_pencarian_info.php?reqSatkerId=' + id,
          function(data){
            reqSatker=data.reqSatker;
            reqSatkerId=data.reqSatkerId;
            if(data.reqSatkerId == ""){}
            else
            {
				$('#reqSatkerId').val(reqSatkerId);
				$('#reqSatkerNama').val(reqSatker);
            }
            
        });
    }
    </script> 
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Tambah <span>Penggalian</span></div>	                    
                </td>			
            </tr>
            <tr>
                <td width="200px">Tahun</td>
                <td width="10px">:</td>
                <td>        
                    <input type="text" name="reqTahun" style="width:150px" value="<?=$tempTahun?>"/>			
               </td>
            </tr>
			<tr>
                <td width="200px">Kode</td>
                <td width="10px">:</td>
                <td>        
                    <input type="text" name="reqKode" style="width:150px" value="<?=$tempKode?>"/>			
               </td>
            </tr>
			<tr>
                <td width="200px">Nama</td>
                <td width="10px">:</td>
                <td>        
                    <input type="text" name="reqNama" style="width:150px" value="<?=$tempNama?>"/>			
               </td>
            </tr>
			<tr>
                <td width="200px">Keterangan</td>
                <td width="10px">:</td>
                <td>        
                    <input type="text" name="reqKeterangan" style="width:150px" value="<?=$tempKeterangan?>"/>			
               </td>
            </tr>
        	<tr>
                <td>
                    <input type="hidden" name="reqId" value="<?=$reqId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
                    <input type="reset" name="" value="Reset" />
                </td>
            </tr> 
        </table>       
        </form>
        <script>
		$("#reqUrut").keypress(function(e) {
			//alert(e.which);
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			//if( e.which!=46 && e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
			{
			return false;
			}
		});
		</script>
    </div>
</div>
</body>
</html>
<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-cat/BankSoal.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base-cat/BankSoalPilihan.php");



$set= new BankSoal();
$tipe_ujian = new TipeUjian();
$bank_soal_pilihan = new BankSoalPilihan();



$reqId= httpFilterGet("reqId");

if($reqId == "")
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$tipe_ujian->selectByParams();

	$set->selectByParamsBankSoal(array('A.BANK_SOAL_ID'=>$reqId), -1, -1, $statement_tahun);
	$set->firstRow();
	//echo $set->query;exit;

	$reqPertanyaan= $set->getField("PERTANYAAN");
	$reqTipeUjian= $set->getField("TIPE");
	$reqTipeSoal= $set->getField("TIPE_SOAL_INFO");
	$tempTipeUjian= $set->getField("TIPE_UJIAN_ID");
	$tempTipeSoal= $set->getField("TIPE_SOAL");
	
	$sOrder= "ORDER BY B.BANK_SOAL_PILIHAN_ID";
	$index_bank_soal_pilihan= 0;
	$arrBankSoalPilihan="";
	$statement= " AND A.BANK_SOAL_ID = ".$reqId;
	$bank_soal_pilihan->selectByParams(array(), -1,-1, $statement, $sOrder);
	//echo $bank_soal_pilihan->query;exit;
	while($bank_soal_pilihan->nextRow())
	{	
		$arrBankSoalPilihan[$index_bank_soal_pilihan]["BANK_SOAL_PILIHAN_ID"] = $bank_soal_pilihan->getField("BANK_SOAL_PILIHAN_ID");
		$arrBankSoalPilihan[$index_bank_soal_pilihan]["JAWABAN"] = $bank_soal_pilihan->getField("JAWABAN");
		$arrBankSoalPilihan[$index_bank_soal_pilihan]["GRADE_PROSENTASE"] = $bank_soal_pilihan->getField("GRADE_PROSENTASE");
		$arrBankSoalPilihan[$index_bank_soal_pilihan]["PATH_GAMBAR"] = $bank_soal_pilihan->getField("PATH_GAMBAR");
		$index_bank_soal_pilihan++;
}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
<link rel="stylesheet" type="text/css" href="../WEB/css/gaya.css">
<link href="styles.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript">	
	$(function(){
		$('#ff').form({
			url:'../json-pengaturan/bank_soal_add.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// alert(data);return false;
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
</head>

<body>
<div id="page_effect">
<div id="bg"><img src="../WEB/images/wall-kanan-polos.png" width="100%" height="100%" alt=""></div>
<div id="content" style="height:auto; margin-top:-4px; width:100%">
		<form id="ff" method="post" novalidate>
    	<table class="table_list" cellspacing="1" width="100%">
            <tr>
                <td colspan="6">
                <div id="header-tna-detil">Bank <span>Soal</span></div>
                </td>			
            </tr>
            
            <tr>
            	<td style="width:150px">Tipe Soal</td>
                <td style="width:10px">:</td>
                <td>
                	<select name="reqTipeSoal" id="reqTipeSoal">
                    	<option value="1" <? if($tempTipeSoal==1) echo 'selected'?>>Soal Text</option>
                    	<option value="2" <? if($tempTipeSoal==2) echo 'selected'?>>Soal Gambar</option>
                        <option value="3" <? if($tempTipeSoal==3) echo 'selected'?>>Gambar lebih dari Satu Jawaban</option>
                        <option value="4" <? if($tempTipeSoal==4) echo 'selected'?>>Soal text jawaban gambar</option>
                        <option value="5" <? if($tempTipeSoal==5) echo 'selected'?>>Soal gambar jawaban text</option>
                    </select>
                </td>
                <td rowspan="3">
                	<?
					if($tempPathGambar=="" || $tempTipeSoal == "4"){}
					else
					{
						$tempUrlGambar= $tempPathGambar.$tempPathSoal;
						//echo $tempUrlGambar;exit;
						if(file_exists($tempUrlGambar))
						{
                    ?>
                    <img src="<?=$tempUrlGambar?>" height="80" width="90" />
                    <?
						}
					}
                    ?>
                </td>
            </tr>
            <tr>
            	<td>Tipe Ujian</td>
                <td>:</td>
                <td>
                	<select name="reqTipeUjian">
                    	<?
						while($tipe_ujian->nextRow())
						{
						?>
                    	<option value="<?=$tipe_ujian->getField("TIPE_UJIAN_ID")?>" <? if($tempTipeUjian==$tipe_ujian->getField("TIPE_UJIAN_ID")) echo 'selected';?>><?=$tipe_ujian->getField("TIPE")?></option>
                        <?
						}
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Pertanyaan</td><td>:</td>
                <td><input id="reqPertanyaan" name="reqPertanyaan" class="easyui-validatebox" required style="width:70%" type="text" value="<?=$reqPertanyaan?>" /></td>
            </tr>
            <tr id="reqLabelText2">
                <td colspan="4">
                	<table style="width:100%">
                        <tr>
                            <th style="width:80%">Jawaban</th>
                            <th>Prosentase</th>
                        </tr>
                        	<?
							for($i=0;$i<=5;$i++)
							{
								$tempRowId= $arrBankSoalPilihan[$i]["BANK_SOAL_PILIHAN_ID"];
								$tempJawaban= $arrBankSoalPilihan[$i]["JAWABAN"];
								$tempGradeProsentase= $arrBankSoalPilihan[$i]["GRADE_PROSENTASE"];
							?>
                         <tr>
                    		<td>
                            	<input type="text" name="reqJawaban[]"  id="reqJawaban<?=$i?>" class="easyui-validatebox" value="<?=$tempJawaban?>" style="width:100%"/>
                            </td>
                            <td>
                            	<input type="text" name="reqGradeProsentase[]" id="reqGradeProsentase<?=$i?>" class="easyui-validatebox" value="<?=$tempGradeProsentase?>" style="width:100%"/>
                            	<input type="hidden" name="reqRowId[]" id="reqRowId<?=$checkbox_index?>" class="easyui-validatebox" value="<?=$tempRowId?>" />
                            </td> 
                         </tr>
							<?
							}
							?>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                	<input type="hidden" name="reqTahun" value="<?=$reqTahun?>">
                    <input type="hidden" name="reqPermenParentId" value="<?=$reqPermenParentId?>">
                    <input type="hidden" name="reqPermenId" value="<?=$reqId?>">
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>">
                	<input type="submit" name="" value="Simpan" /> 
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
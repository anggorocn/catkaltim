<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/JadwalTes.php");
include_once("../WEB/classes/base/JadwalTesFormulaAssesmentUjianTahap.php");

$reqId = httpFilterGet("reqId");

// echo $reqId;exit;

$set= new JadwalTes();
$reqMode= "update";
$set->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $reqId),-1,-1,'');
$set->firstRow();
//echo $set->query;exit;

$tempTanggalTes= datetimeToPage($set->getField('TANGGAL_TES'), "date");
$tempTanggalTesInfo= getFormattedDateTime($set->getField('TANGGAL_TES'), false);
$tempBatch= $set->getField('BATCH');
$tempAcara= $set->getField('ACARA');
$tempTempat= $set->getField('TEMPAT');
$tempAlamat= $set->getField('ALAMAT');
$tempKeterangan= $set->getField('KETERANGAN');
$tempStatusPenilaian= $set->getField('STATUS_PENILAIAN');
$reqStatusValid= $set->getField('STATUS_VALID');

$tempFormulaEselonId= $set->getField('FORMULA_ESELON_ID');
$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');

$tempFormulaEselon= $set->getField('NAMA_FORMULA_ESELON');
$tempJumlahRuangan= $set->getField('JUMLAH_RUANGAN');
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
			url:'../json-pengaturan/master_jadwal_add_mulai.php',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data = data.split("-");
				$.messager.alert('Info', data[1], 'info');
				reqId= '<?=$reqId?>';
				$('#rst_form').click();
				document.location.href = 'master_jadwal_add_mulai.php?reqId='+reqId;
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
<link rel="stylesheet" type="text/css" href="../WEB/css/tablegradient.css">
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
				<div id="header-tna-detil">Mulai <span>Ujian</span></div>
				</td>			
			</tr>
            <tr>
				<td width="200px">Formula</td>
				<td width="2px">:</td>
				<td>
                    <label id="reqFormulaEselon"><?=$tempFormulaEselon?></label>
			   </td>
			</tr>
			<tr>
				<td>Tanggal Tes</td>
				<td>:</td>
				<td>
					<?=$tempTanggalTesInfo?>
			   </td>
			</tr>
            <tr>
				<td>Acara</td>
				<td>:</td>
				<td>
					<?=$tempAcara?>
			   </td>
			</tr>
			<tr>
				<td>Tempat</td>
				<td>:</td>
				<td>
					<?=$tempTempat?>
			   </td>
			</tr>
            <tr>
				<td>Alamat</td>
				<td>:</td>
				<td>
					<?=$tempAlamat?>
			   </td>
			</tr>
            <tr>
				<td>Keterangan</td>
				<td>:</td>
				<td>
					<?=$tempKeterangan?>
			   </td>
			</tr>
			<tr>
	            <td>
	                <input type="submit" name="" value="Simpan" />
	            </td>
	        </tr>
			</table>

			<table class="gradient-style" id="tableKandidat" style="width:99%; margin-left:2px">
				<thead>
					<tr>
						<th>Ujian</th>
						<th style="width:30%">Status</th>
					</tr>
				</thead>
				<tbody>
					<?
					$set= new JadwalTesFormulaAssesmentUjianTahap();
					$set->selectByParamsData(array(), -1,-1, " AND A.JADWAL_TES_ID = ".$reqId);
					// echo $set->query;exit;
					while ($set->nextRow()) 
					{
						$infonama= $set->getField("TIPE");
						$infowaktu= $set->getField("WAKTU");
						$infocreate= $set->getField("LAST_CREATE_DATE");
					?>
					<tr>
						<td><?=$infonama?></td>
						<td>
							<?
							if(is_numeric($infowaktu))
							{
								if(empty($infocreate))
								{
							?>
								<select name="reqStatus[]">
									<option value=""></option>
									<option value="1">Mulai</option>
								</select>
							<?
								}
								else
								{
							?>
								Sudah Mulai
								<input type="hidden" name="reqStatus[]" value="2" />
							<?
								}
							}
							else
							{
							?>
								<input type="hidden" name="reqStatus[]" value="" />
							<?
							}
							?>
            			<input type="hidden" name="reqDataJadwalTesId[]" value="<?=$set->getField("JADWAL_TES_ID")?>" />
            			<input type="hidden" name="reqDataFormulaUjianTahapId[]" value="<?=$set->getField("FORMULA_ASSESMENT_UJIAN_TAHAP_ID")?>" />
            			<input type="hidden" name="reqDataFormulaId[]" value="<?=$set->getField("FORMULA_ASSESMENT_ID")?>" />
            			<input type="hidden" name="reqDataTipeUjianId[]" value="<?=$set->getField("TIPE_UJIAN_ID")?>" />
						</td>
					</tr>
					<?
					}
					?>
				</tbody>
			</table>
        </form>
    </div>
</div>
</body>
</html>
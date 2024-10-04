<?
include_once("../WEB/functions/infotipeujian.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianTahap.php");
include_once("../WEB/classes/base-cat/TipeUjian.php");
include_once("../WEB/classes/base-cat/UjianTahapStatusUjian.php");

include_once("../WEB/classes/base-cat/KraepelinSoal.php");

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianId= $ujianPegawaiUjianId;
$tempSystemTanggalNow= date("d-m-Y");

$statement= " AND EXISTS
(
SELECT 1 FROM cat.KRAEPELIN_PAKAI X WHERE COALESCE(NULLIF(X.STATUS, ''), NULL) IS NULL
AND A.PAKAI_KRAEPELIN_ID = X.PAKAI_KRAEPELIN_ID
)";
$set= new KraepelinSoal();
$set->selectByXbarisYbarisParams(array(), -1,-1, $statement);
$set->firstRow();
$reqPakaiKraepelinId= $set->getField("PAKAI_KRAEPELIN_ID");
$x_batas= $set->getField("X_DATA");
$y_batas= $set->getField("Y_DATA");
// echo $x_batas."-".$y_batas;exit();

$arrSoal="";
$index_data= 0;
$statement= " AND A.PAKAI_KRAEPELIN_ID = ".$reqPakaiKraepelinId;
$set= new KraepelinSoal();
$set->selectByParams(array(), -1, -1, $statement);
//echo $set->errorMsg;exit;
//echo $set->query;exit;
while($set->nextRow())
{
	// A.PAKAI_KRAEPELIN_ID, A.X_DATA, A.Y_DATA, A.NILAI
	$arrSoal[$index_data]["KOORDINAT"]= $set->getField("X_DATA")."-".$set->getField("Y_DATA");
	$arrSoal[$index_data]["X_DATA"]= $set->getField("X_DATA");
	$arrSoal[$index_data]["Y_DATA"]= $set->getField("Y_DATA");
	$arrSoal[$index_data]["NILAI"]= $set->getField("NILAI");
	$index_data++;
}
unset($set);
$jumlah_soal= $index_data;

?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<style type="text/css">
	.jwb td{color: #00f;font-size:12px;padding:0 15px 0 0}
	.inputkrp
	{
		width: 10%
	}
</style>

<script type="text/javascript">
	$(function(){
		$('input[id^="reqDataXY"]').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		$('input[id^="reqDataXY"]').keyup( function(e) {
			var id= $(this).attr('id');
			arrTempId= String(id);
			arrTempId= arrTempId.split('-');

			y= arrTempId[arrTempId.length-1];
			x= arrTempId[arrTempId.length-2];
			y= parseInt(y) + 1;

			/*var idName = $(this).attr('name');
			var allowTab = true;
			var inputArr = {username:'', email:'', password:'', address:''}
			 // allow or disable the fields in inputArr by changing true / false
     		if(id in inputArr) allowTab = false;
			if(e.keyCode==9 && allowTab==false) e.preventDefault();*/
			// if(e.keyCode==9) e.preventDefault();

			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			// console.log(key);
			if(key == 13 || key == 9) {
				// e.preventDefault();
				$("#reqDataXY-"+x+"-"+y).focus();
				// console.log(id+"-"+x+"-"+y);
			}
		});


		// $('[id^="reqXYdata"]').prop("disabled", false);
		$('[id^="reqDataXY"]').prop("disabled", true);
		$('[id^="reqDataXY-1"]').prop("disabled", false);
		$("#reqDataXY-1-1").focus();

		$('input[id^="reqDataXY"]').keyup(function() {
			var tempId= $(this).attr('id');
			var tempValue= $(this).val();
			arrTempId= String(tempId);
			arrTempId= arrTempId.split('-');

			y= arrTempId[arrTempId.length-1];
			x= arrTempId[arrTempId.length-2];

			$("#reqXYdataNilai-"+x+"-"+y).val(tempValue);
			// console.log(y);
		});
	});
</script>

<div class="container utama">
	<div class="row">
    	<?php /*?><div class="col-md-12">
            <div class="area-sisa-waktu">
                <div class="judul"><i class="fa fa-clock-o"></i> Sisa Waktu :</div>
                <div class="waktu">
                    <div id="divCounter"></div>
                </div>
            </div>
        </div><?php */?>
        
    	<div class="col-md-12">
			<div class="area-judul-halaman">Ujian Tahap</div>
        </div>
    </div>
    
	<div class="row">
        <div class="col-md-12">
            <div class="area-soal">
                <div class="area-sudah finish">
                	<table style="width: 100%">
                		<tr>
                			<?
                			for($x=1; $x <= $x_batas; $x++)
                			{
                			?>
                			<td>
		                		<table style="width: 100%">
		                		<?
	                			for($y=$y_batas; $y >= 1; $y--)
	                			{
	                				$y_jawab= $y-1;

	                				$koordinat= $x."-".$y;
	                				$arrayKey= $reqSoalNilai= '';
  									$arrayKey= in_array_column($koordinat, "KOORDINAT", $arrSoal);
  									//print_r($arrayKey);exit;
  									if($arrayKey == ''){}
									else
									{
										$index_row= $arrayKey[0];
										$reqSoalNilai= $arrSoal[$index_row]["NILAI"];
									}
	                			?>
		                			<tr>
								        <td><?=$reqSoalNilai?></td>
								        <td> </td>
								    </tr>
							    <?
								    if($y_jawab > 0)
								    {
								    	//$x.$y_jawab
							    ?>
								    <tr class='jwb'>
								        <td> </td>
								        <td>
								        	<input type="hidden" name="reqXdata[]" value="<?=$x?>" />
								        	<input type="hidden" name="reqYdata[]" value="<?=$y_jawab?>" />
								        	<input type="hidden" id="reqXYdataNilai-<?=$x."-".$y_jawab?>" name="reqYdata[]" />
								        	<input type="text" id="reqDataXY-<?=$x."-".$y_jawab?>" maxlength="1" value="" class="inputkrp" />
								        </td>
								    </tr>
	                			<?
	                				}
	                			}
	                			?>
							    <!-- <tr>
							        <td>0</td>
							        <td> </td>
							    </tr>
							    <tr class='jwb'>
							        <td> </td>
							        <td><input type="text" name="" value="4" class="inputkrp" /></td>
							    </tr>
							    <tr>
							        <td>0</td>
							        <td> </td>
							    </tr> -->
								</table>
							</td>
							<?
							}
							?>
						</tr>
					</table>
                </div>
            </div>
        </div>
        
        <div class="area-prev-next">
        	<div class="kembali-home">
        	<span class="ke-home"><a href="?pg=dashboard"><i class="fa fa-home"></i> Kembali ke halaman utama <!--&raquo;--></a></span>
            </div>
        </div>
    
    </div>
</div>
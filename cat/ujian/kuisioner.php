<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base-cat/Ujian.php");
include_once("../WEB/classes/base-cat/UjianPegawaiDaftar.php");
include_once("../WEB/classes/base-cat/Kuisioner.php");
include_once("../WEB/functions/string.func.php");

if($userLogin->ujianUid == "")
{
	if($pg == "" || $pg == "home"){}
	else
	{
		echo '<script language="javascript">';
		echo 'top.location.href = "index.php";';
		echo '</script>';
		exit;
	}
}

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;
// echo $ujianPegawaiJadwalTesId; exit;
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib-ujian/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib-ujian/easyui/jquery.easyui.min.js"></script>

<script type="text/javascript">
	function nextModul()
	{
		document.location.href= "?pg=ujian_pilihan";
	}	

	function kuisioner()
	{
		document.location.href= "?pg=kuisioner";
	}
</script>

<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Kuisioner</div>
        </div>
        
        <div class="col-md-12">
       	  <div class="area-persetujuan">
       	  	<ul>
	       	  	<form id="ff" method="post" enctype="multipart/form-data">
	       	  		<input value='<?=$tempPegawaiId?>' name="pegawai_id" style="margin-left:40px" type="hidden" name="">
	       	  		<input value='<?=$ujianPegawaiJadwalTesId?>' name="ujian_id" style="margin-left:40px" type="hidden" name="">
	           		<?
						$setTipe= new Kuisioner();
						$setTipe->selectByParamsTipe(array(), -1,-1, $statement);
						$no=1;
						// memanggil tipesoal
						while($setTipe->nextRow())
						{
							$tempTipe= $setTipe->getField("NAMA");
							$tempTipeId= $setTipe->getField("KUISIONER_TIPE_ID");
							?>
								<b><?=numberToRomawi($no)?>. <?=$tempTipe?></b><br><br>           	  		
	           	  		<?
	           	  		$no++;
	           	  		$setSoal= new Kuisioner();
							$setSoal->selectByParamsSoal(array('KUISIONER_TIPE_ID'=>$tempTipeId));
							// memanggil soal
								while($setSoal->nextRow()){
								$tempSoal= $setSoal->getField("Pertanyaan");
								$tempSoalId= $setSoal->getField("KUISIONER_ID");
								?>
								<input value='<?=$tempSoalId?>' name="soal[]" style="margin-left:40px" type="hidden" name="">
								<li style="padding-left:20px"><?=$tempSoal?></li>
								<?
								$setJawabanMaster= new Kuisioner();
								$setJawabanMaster->selectByParamsJawabanMaster(array('KUISIONER_ID'=>$tempSoalId));
								// echo $setJawabanMaster->query; exit;
								//memanggil pilihan jawaban
									while($setJawabanMaster->nextRow()){
									$tempJawaban= $setJawabanMaster->getField("Jawaban");
									$tempJawabanId= $setJawabanMaster->getField("KUISIONER_PILIHan_ID");
									$tempDetilJawaban= $setJawabanMaster->getField("ADD_DETIL");

									$setJawaban= new Kuisioner();
									$setJawaban->selectByParamsJawaban(array('KUISIONER_Pertanyaan_ID'=>$tempSoalId,'KUISIONER_jawaban_ID'=>$tempJawabanId,'Pegawai_id'=>$tempPegawaiId,'ujian_id'=>$ujianPegawaiJadwalTesId));
									// echo $setJawaban->query; exit;
									$setJawaban->firstRow();
									$reqChecked= $setJawaban->getField("KUISIONER_jawaban_ID");
									$reqDetiljawaban= $setJawaban->getField("KUISIONER_DETIL");
									// $kuisionerjawaban= $setJawaban->getField("kuisioner_id");
									if($setJawaban->getField("KUISIONER_jawaban_ID")!=''){
										// echo "ssss";
										$reqjawabanfix= $setJawaban->getField("KUISIONER_jawaban_ID");
									}
									if($setJawaban->getField("kuisioner_id")!=''){
										// echo "ssss";
										$kuisionerjawabanfix= $setJawaban->getField("kuisioner_id");
									}

									?>
										<input id="piljaw-<?=$tempSoalId?>-<?=$tempJawabanId?>" value='<?=$tempJawabanId?>' <?if ($reqChecked!=''){?>checked<?}?> style="margin-left:40px" type="radio" name="piljaw-<?=$tempSoalId?>">
										<?=$tempJawaban?> <br>
										<input type="hidden" name="" id="detilTanda-<?=$tempJawabanId?>"  value='<?=$tempDetilJawaban?>' style="width: 80%;">
									<br>
									<?
								}?>
									<input id="jawaban-<?=$tempSoalId?>" name="jawaban[]" style="margin-left:40px" type="hidden" name="" value="<?=$reqjawabanfix?>">
									<input name="kuisionerjawaban[]" style="margin-left:40px" type="hidden" name="" value="<?=$kuisionerjawabanfix?>">
									<input type="text" name="detil[]" id="detil-<?=$tempSoalId?>" style="width: 80%; <?if($reqDetiljawaban==''){?> display: none; <?}?> margin-left:60px" value="<?=$reqDetiljawaban?>">
									<input type="hidden" name="cekdetil[]" id="cekdetil-<?=$tempSoalId?>" style="margin-left:60px" value='<? if ($reqDetiljawaban!=''){?><?=$tempDetilJawaban?><?}?>'>
								<br>
							<?}
							?>
							<br><br> <?
	         	  		}
	         	  	?>

								<!-- <b><?=numberToRomawi($no)?> .Apakah harapan Bapak/Ibu setelah mengikuti penilaian potensi dan kompetensi ini? </b><br><br>         
								<textarea style="margin-left: 20px; width:100%"></textarea>
								
								<br>
								<br> -->
             	</form>
            </ul>
            <div class="ikut"><a href="#" onclick="$('#ff').submit();">Simpan &raquo;</a></div>
            <div class="ikut"><a href="?pg=dashboard"  style="background: darkred;margin-right: 20px">Kembali &raquo;</a></div>
			<!-- <div class="ikut"><a href="index.php?reqMode=submitLogout" style="background-color: red ;color: white; margin-right: 10px">Logout</i></a></div> -->
            <!-- <div class="ikut"><a href="#" onclick="kuisioner()">Logout &raquo;</a></div> -->
        	</div>
            
        </div>    
    </div>
</div>

<script type="text/javascript">
	$('input[id^="piljaw"]').change(function(e) {
	    var tempId= $(this).attr('id');
	  	    arrId= tempId.split('-');
	  	     idnya= arrId[1];
	  	     idnya2= arrId[2];
	    cek=$("#detilTanda-"+idnya2).val();
	    xxx=$("input[id^='detil-"+idnya+"']").attr('id');
	   
    	if (cek==1){
    		$("#"+xxx).show();
    		$("#jawaban-"+idnya).val(idnya2);
    		$("#cekdetil-"+idnya).val(1);
    	}
    	else{
    		$("#"+xxx).hide();
    		$("#jawaban-"+idnya).val(idnya2);
    		$("#cekdetil-"+idnya).val(0);
    		$("#"+xxx).val('');
    	}
	});

		$(function() {
		$('#ff').form({
			url:'../json-ujian/kuisioner.php',
			onSubmit:function(){
				// alert($(this).form('validate')); return false;
				var f = this;
				var opts = $.data(this, 'form').options;
				if($(this).form('validate') == false){
					return false;
				}
				//var reqDiklatId= $("#reqDiklatId option:selected").text();
				
				$.messager.confirm('Confirm','Apakah Anda yakin ubah data ?',function(r){
					if (r){
						var onSubmit = opts.onSubmit;
						opts.onSubmit = function(){};
						$(f).form('submit');
						opts.onSubmit = onSubmit;
					}
				})
				return false;
				//return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data= data.split("-");
				kondisiInfo= data[0];
				info= data[1];		
				
				if(kondisiInfo == 1)
				{
					document.location.href = 'index.php?pg=kuisioner'
				}
				else{
					$.messager.alert('Info', info, 'info');
					return false;
				}
			}
		});
		
		$("#reqKecamatanId").change(function() { 
				$("#reqDesaId").val("");
				$("#reqDesa").val("");
		  });
	 
		
	});
</script>
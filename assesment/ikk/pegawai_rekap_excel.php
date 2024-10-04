<?php 
require '../WEB/lib/Classes/PHPExcel.php'; 
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/RekapAssesment.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=pegawai_rekap_excel.xls");

$reqTahun= httpFilterGet("reqTahun");
$reqJadwalTesId= httpFilterGet("reqJadwalTesId");
$reqKeterangan = httpFilterRequest("reqKeterangan");
$reqId = httpFilterRequest("reqId");
$reqCari = httpFilterRequest("reqCari");
$reqSearch = httpFilterGet("reqSearch");

// $arrkolomdata= array(
//     array("label"=>"No", "width"=>"220px")
//     ,array("label"=>"Nama", "width"=>"220px")
//     , array("label"=>"NIP Baru", "width"=>"120px")
//     , array("label"=>"Jabatan", "width"=>"")
//     , array("label"=>"Instansi", "width"=>"150px")
// );

$arrkolomdata1=array();
$arrkolomdata2=array();

$totalstandart= 0;
if(!empty($reqJadwalTesId))
{
	$jumlahdetil= 0;
	$setdetil= new RekapAssesment();
	$setdetil->selectByParamsFormula(array(), -1, -1, '', $reqJadwalTesId);
	// echo $setdetil->query;exit();
	$cekAtribut='';
	while ($setdetil->nextRow()) 
	{
		// array_push($arrkolomdata,     
		// 	array("label"=>$setdetil->getField("ATRIBUT_NAMA")."<br/>".$setdetil->getField("NILAI_STANDAR"), "width"=>"100px")
		// );
		if($cekAtribut!=$setdetil->getField("ATRIBUT_NAMA")){
			if($setdetil->getField("aspek_id")==1){
				array_push($arrkolomdata1,     
					array("label"=>$setdetil->getField("ATRIBUT_NAMA")."<br/>".$setdetil->getField("NILAI_STANDAR"), "width"=>"100px", "aspek"=>$setdetil->getField("aspek_id"))
				);
			}
			else{
				array_push($arrkolomdata2,     
					array("label"=>$setdetil->getField("ATRIBUT_NAMA")."<br/>".$setdetil->getField("NILAI_STANDAR"), "width"=>"100px", "aspek"=>$setdetil->getField("aspek_id"))
				);
			}
			$cekAtribut=$setdetil->getField("ATRIBUT_NAMA");
		}
		$totalstandart += $setdetil->getField("NILAI_STANDAR");
		$jumlahdetil++;
	}
}
// print_r($arrkolomdata);exit;
// echo $jumlahdetil;exit();
// array_push($arrkolomdata,
// 	array("label"=>"Jumlah Skor<br/>".$totalstandart, "width"=>"100px")
//     , array("label"=>"JPM", "width"=>"100px")
//     , array("label"=>"Kategori", "width"=>"100px")
// );

if($reqId == "")
	$statement='';
else
	$statement .= " AND P.SATKER_ID LIKE '".$reqId."%'";

if(empty($reqJadwalTesId))
{
	$statement .= " AND 1 = 2";
	$reqJadwalTesId= -1;
}

function setnumberdua($val)
{
	return number_format(round($val,2), 2, '.', '');
}

$set= new RekapAssesment();
$sOrder= " group by B.PEGAWAI_ID, B.NAMA, B.NIP_BARU, B.SATKER, B.JABATAN_NAMA, B.JPM , B.IKK , b.nama_kuadran,  c.no_urut
	 	ORDER BY COALESCE(B.JPM,0) DESC";
// $set->selectByParamsNewRekap(array(), -1, -1, $statement, $reqJadwalTesId, $sOrder);
$set->selectByParamsNewRekap(array(), -1, -1, $statement, $reqJadwalTesId);
// echo $set->query;exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
	</style>
	<table style="width:100%">
        <tr>
            <td colspan="10" style="font-size:13px ;font-weight:bold; text-align: center;">REKAPITULASI HASIL PENILAIAN</td>	
        </tr>
	</table>
	<br/>
	<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Nama</th>
				<th rowspan="2">NIP</th>
				<th rowspan="2">Jabatan</th>
				<th rowspan="2">Instansi</th>
				<th colspan="<?=count($arrkolomdata1)?>">Kompetensi</th>
				<th rowspan="2">Total</th>
				<!-- <th rowspan="2">Keterangan</th> -->
				<th colspan="<?=count($arrkolomdata1)?>">Gap Kompetensi</th>
				<th colspan="<?=count($arrkolomdata2)?>">Potensi</th>
				<th colspan="<?=count($arrkolomdata2)?>">Gap Potensi</th>
				<th rowspan="2">JPM</th>
			</tr>
			<tr>
				<?
				for ($i=0; $i<count($arrkolomdata1); $i++){?>
					<th><?=$arrkolomdata1[$i]['label']?></th>
				<?}?>
				<?
				for ($i=0; $i<count($arrkolomdata1); $i++){?>
					<th><?=$arrkolomdata1[$i]['label']?></th>
				<?}?>
				<?
				for ($i=0; $i<count($arrkolomdata2); $i++){?>
					<th><?=$arrkolomdata2[$i]['label']?></th>
				<?}?>
				<?
				for ($i=0; $i<count($arrkolomdata2); $i++){?>
					<th><?=$arrkolomdata2[$i]['label']?></th>
				<?}?>
			</tr>
		</thead>
		<!-- <thead>
	        <tr>
	        	<?
	        	$rowspan= 1;
	        	$batas= -1;
	        	if(!empty($reqJadwalTesId) && $jumlahdetil > 0)
				{
					$rowspan= 2;
	        		// $batas= count($arrkolomdata) - $jumlahdetil;
	        		$batas= $jumlahdetil + 3;
	        	}

				for($col=0; $col<count($arrkolomdata); $col++)
				{
					if($col <= 3)
					{
				?>
					<th rowspan="<?=$rowspan?>" width="<?=$arrkolomdata[$col]['width']?>"><?=$arrkolomdata[$col]['label']?></th>
				<?
					}
					elseif($col > 3 && $col <= $batas)
					// elseif($col <= $batas)
					{
						if($col == $batas)
						{
				?>
					<th style="text-align: center;" colspan="<?=$jumlahdetil?>">Standar Kompetensi</th>
				<?
						}
					}
					else
					{
				?>
					<th rowspan="<?=$rowspan?>" width="<?=$arrkolomdata[$col]['width']?>"><?=$arrkolomdata[$col]['label']?></th>
				<?
					}
				}
				?>
	        </tr>

	        <?
	        if(!empty($reqJadwalTesId) && $jumlahdetil > 0)
	        {
	        ?>
	        <tr>
	        	<?
	        	for($col=0; $col<count($arrkolomdata); $col++)
				{
					if($col > 3 && $col <= $batas)
					{
				?>
					<th width="<?=$arrkolomdata[$col]['width']?>"><?=$arrkolomdata[$col]['label']?></th>
				<?
					}
				}
				?>
	        </tr>
	        <?
	    	}
	        ?>
	    </thead> -->
        <tbody>
            <?
            $aColumns = array("no_urut", "NAMA", "NIP_BARU", "JABATAN_NAMA", "SATKER");
			if(!empty($reqJadwalTesId))
			{
				$jumlahdetil= 0;
				$setdetil= new RekapAssesment();
				$setdetil->selectByParamsFormula(array(), -1, -1, '', $reqJadwalTesId);
				while ($setdetil->nextRow()) 
				{
					$detilrowid= $setdetil->getField("FORMULA_ATRIBUT_ID");
					array_push($aColumns, "DATA-".$detilrowid);
				}
			}

			array_push($aColumns, "TOTAL_STANDART");

			if(!empty($reqJadwalTesId))
			{
				$jumlahdetil= 0;
				$setdetil= new RekapAssesment();
				$setdetil->selectByParamsFormula(array(), -1, -1, '', $reqJadwalTesId);
				while ($setdetil->nextRow()) 
				{
					$detilrowid= $setdetil->getField("FORMULA_ATRIBUT_ID");
					array_push($aColumns, "GAPDATA-".$detilrowid);
				}
			}
			// array_push($aColumns, "NAMA_KUADRAN");
			array_push($aColumns, "JPM_TOTAL");

			$totalstandar= 0;
			$searchword= "DATA-";
			$searchwordGap= "GAPDATA-";
            while($set->nextRow())
            {
			?>
            	<tr>
            		<?
            		for ( $i=0 ; $i<count($aColumns) ; $i++ )
					{
						if(preg_match("/\b$searchword\b/i", $aColumns[$i]))
						{
							$fielddata= str_replace($searchword, "", $aColumns[$i]);

							$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID")."  AND A.FORMULA_ATRIBUT_ID = ".$fielddata;
							$setdetil= new RekapAssesment();
							$setdetil->selectByParamsPenilaianDetil(array(), -1, -1, $statementdetil, $reqJadwalTesId);
							$setdetil->firstRow();
							if($setdetil->getField("NILAI")!=''){
							// echo $setdetil->query;exit();
						?>
							<td class="str"><?=$setdetil->getField("NILAI")?></td>
						<?
							}
				        	$totalstandar+= $setdetil->getField("NILAI");
				        	unset($setdetil);
				    	}
				    	else if(preg_match("/\b$searchwordGap\b/i", $aColumns[$i]))
						{
							$fielddata= str_replace($searchwordGap, "", $aColumns[$i]);

							$statementdetil= " AND A.PEGAWAI_ID = ".$set->getField("PEGAWAI_ID")."  AND A.FORMULA_ATRIBUT_ID = ".$fielddata;
							$setdetil= new RekapAssesment();
							$setdetil->selectByParamsPenilaianDetil(array(), -1, -1, $statementdetil, $reqJadwalTesId);
							$setdetil->firstRow();
							if($setdetil->getField("gap")!=''){
							// echo $setdetil->query;exit();
						?>
							<td class="str"><?=$setdetil->getField("NILAI")?></td>
						<?
							}
				        	$totalstandar+= $setdetil->getField("NILAI");
				        	unset($setdetil);
				    	}
				    	elseif(trim($aColumns[$i]) == "TOTAL_STANDART")
						{
						?>
							<td class="str"><?=$totalstandar?></td>
						<?
							$totalstandar= 0;
						}
	            		else
	                	{
	                    ?>
	                    	<td class="str"><?=$set->getField(trim($aColumns[$i]))?></td>
	                    <?
	                	}
	                    ?>
                    <?
                	}
                    ?>
                </tr>
			<?
				$no++;
            }
            ?>

        </tbody>
    </table>
</body>
</html>
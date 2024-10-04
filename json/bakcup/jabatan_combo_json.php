<?
/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/Jabatan.php");


/* create objects */

$jabatan = new Jabatan();

/* LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

$reqStatus= httpFilterGet("reqStatus");
$reqKelompok= httpFilterGet("reqKelompok");
$reqCabang= httpFilterGet("reqCabang");

if($reqStatus == 'perbantuan'){
	$kelompok= array('P');
	$kelompok_nama= array('Perbantuan');
	$max_loop= 1;
}
elseif($reqStatus == 'operasional'){
	$kelompok= array('O');
	$kelompok_nama= array('Operasional');
	$max_loop= 1;
}
else{
	if($reqKelompok == "O")
	{
		$kelompok= array('O');
		$kelompok_nama= array('Operasional');
		$max_loop= 1;		
	}
	else
	{
		$kelompok= array('D');
		$kelompok_nama= array('Internal');
		$max_loop= 1;		
	}
}

$jabatan_count = new Jabatan();
$jumlah = $jabatan_count->getCountByParams(array("CABANG_P3_ID" => $reqCabang));
if($jumlah > 0)
	$statement = " AND CABANG_P3_ID = ". $reqCabang;
else
	$statement = " AND COALESCE(CABANG_P3_ID, 0) = 0 ";

$arr_json = array();
$i = 0;
while($i < $max_loop){
	$arr_json[$i]['id'] = "JAB".$kelompok[$i];
	$arr_json[$i]['text'] = $kelompok_nama[$i];
	
	$j=0;
	$x = $y = $z = 0;
	$jabatan->selectByParams(array("KELOMPOK" => $kelompok[$i], "STATUS" => 1), -1, -1, $statement, "  ORDER BY A.NAMA ASC");
	while($jabatan->nextRow())
	{
		if($jabatan->getField("KATEGORI") == "")
		{
			$arr_parent[$j]['id'] = $jabatan->getField("JABATAN_ID");
			$arr_parent[$j]['text'] = $jabatan->getField("NAMA");
			$j++;
		}
		else
		{
			if($jabatan->getField("KATEGORI") == "OPS")
			{
				$arr_parent_ops[$x]['id'] = $jabatan->getField("JABATAN_ID");
				$arr_parent_ops[$x]['text'] = $jabatan->getField("NAMA");	
				$x++;							
			}
			if($jabatan->getField("KATEGORI") == "PS")
			{
				$arr_parent_ps[$y]['id'] = $jabatan->getField("JABATAN_ID");
				$arr_parent_ps[$y]['text'] = $jabatan->getField("NAMA");												
				$y++;							
			}
			if($jabatan->getField("KATEGORI") == "PBR")
			{
				$arr_parent_pbr[$z]['id'] = $jabatan->getField("JABATAN_ID");
				$arr_parent_pbr[$z]['text'] = $jabatan->getField("NAMA");												
				$z++;							
			}
			
		}
	}
	if(count($arr_parent) > 0)
		$arr_json[$i]['children'] = $arr_parent;
	else
	{
		if(count($arr_parent_ops) > 0)
		{
			$arr_parent[$j]['id'] = "OPS";
			$arr_parent[$j]['text'] = "OPS";
			$arr_parent[$j]['children'] = $arr_parent_ops;						
			$j++;							
		}
		
		if(count($arr_parent_ps) > 0)
		{
			$arr_parent[$j]['id'] = "PS";
			$arr_parent[$j]['text'] = "PS";
			$arr_parent[$j]['children'] = $arr_parent_ps;						
			$j++;							
		}
		
		if(count($arr_parent_pbr) > 0)
		{
			$arr_parent[$j]['id'] = "PBR";
			$arr_parent[$j]['text'] = "PBR";
			$arr_parent[$j]['children'] = $arr_parent_pbr;						
			$j++;							
		}
		
		$arr_json[$i]['children'] = $arr_parent;
	}		
		
	unset($departemen);	
	unset($arr_parent);
	$i++;
}

echo json_encode($arr_json);
?>
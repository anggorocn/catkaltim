<?
header('Content-Type: application/json');

/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base/PerusahaanVisiDetil.php");

/* create objects */

$set= new PerusahaanVisiDetil();
$set->selectByParamsMain(array("STATUS_CREATE"=>1),-1,-1);

/* LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

$reqId= httpFilterGet("reqId");

$capaian= $target= $keterangan= "";
$nomor= 1;
$index=0;
while($set->nextRow())
{
	$keterangan[$i]= $nomor.". ".str_replace(" ","<br>", "Tahun ke ".$set->getField("TAHUN"));
	$target[$i]= (float)ValToNullDB(round($set->getField("TERBOBOT"),2));
	$capaian[$i]= (float)ValToNullDB(round(setValNol($set->getField("CAPAIAN")),2));
	$nomor++;
	$index++;
}

$nama_pegawai= "Kebijakan dari tujuan ".$tempNamaParent;
$nama_pegawai=$tempNamaPegawai;

$arr_json= array("graphset" => 
	array(
		array(
			"type"=>"radar",
			"legend"=>
				array(
					"item"=>
						array("color"=>"#3d3d3d","font-size"=>9,"border-color"=>"#b9b9b9","border-width"=>1),
					"x"=>10,"y"=>510,"layout"=>"1x","width"=>275,"minimize"=>true,
					"header"=>
						array(
						"text"=>"Data Personal",
						"align"=>"center",
						"background-color"=>"#99dbd1",
						"color"=>"#000000"
						),
					"footer"=>
						array(
						"text"=>$nama_pegawai,
						"font-size"=>10,
						"align"=>"center",
						"background-color"=>"#99dbd1",
						"color"=>"#000000"
						)
				),
			"plotarea"=>array("margin"=>"10 10 10 10"),
			"scale"=>array("size-factor"=>0.65),
			"scale-k"=>
				array(
					"values"=>
						//array(
						$keterangan
						//"1","2","3","4","5"
						//)
						,
					"aspect"=>"star",
					"ref-angle"=>360,
					"guide"=>
						array(
							"line-color"=>"#607774",
							"line-width"=>1,
							"alpha"=>1
						)
				),
			"scale-v"=>
				array(
					"min-value"=>0, "offset-start"=>20, "slice"=>20, 
					"guide"=>
						array(
						"line-color"=>"#bdb12e",
						"line-width"=>1,
						"alpha"=>1
						)								
				),
			"plot"=>
				array(
				"aspect"=>"area",
				"marker"=>
					array(
					"type"=>"circle",
					"size"=>4
					),
				"hover-marker"=>
					array(
					"type"=>"diamond",
					"size"=>4
					),
				"click-marker"=>
					array(
					"type"=>"square",
					"size"=>4
					)											
			),						
			"series"=>
					array(
						array(
							"values"=>$capaian,
							"text"=>"Capaian",
							"marker"=>
								array(
								"type"=>"circle",
								"background-color"=>"#ffffff",
								"border-color"=>"#000000",
								"border-width"=>1,
								"size"=>4
								),
							"animate"=>true,
							"effect"=>1,
							"line-color"=>"#272cfb",
							"background-color"=>"#272cfb"
							),
						array(
							"values"=>$target,
							"text"=>"Target",
							"marker"=>
								array(
								"type"=>"circle",
								"background-color"=>"#ffffff",
								"border-color"=>"#000000",
								"border-width"=>1,
								"size"=>4
								),
							"animate"=>true,
							"effect"=>1,
							"line-color"=>"#ff0000",
							"background-color"=>"#ff0000"
							)
					)
			 )
	)
);
echo json_encode($arr_json);
die();

?>
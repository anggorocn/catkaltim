<?
header('Content-Type: application/json');

/* INCLUDE FILE */
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
//include_once("../WEB/classes/base/PerusahaanVisiDetil.php");

/* create objects */

/* LOGIN CHECK 
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}*/

$reqId= httpFilterGet("reqId");
$reqRowId= httpFilterGet("reqRowId");

//$set= new PerusahaanVisiDetil();

//$set->selectByParamsMain(array("B.PERUSAHAAN_VISI_DETIL_INFO_PARENT_ID"=>$reqId),-1,-1);
//echo $set->query;
$capaian= $target= $keterangan= "";
$nomor= 1;
$i=0;

$arrKeterangan= array("Personality<br>(Kepribadian)", "Human Relation<br>(Sosiabilitas)", "Willingess To Do More<br>(Potensi Kerja)"
					  , "Ability to Work<br>(Cara Kerja)", "Intelektual & Kemumpuan Umum");
$arrDataStandar= array("3", "3", "3", "3", "3");
$arrDataTarget= array("0", "0", "0", "0", "0");
$arrDataToleransi= array("0", "0", "0", "0", "0");

for($i=0; $i < count($arrKeterangan); $i++)
{
	$keterangan[$i]= $arrKeterangan[$i];
	$target[$i]= (float)ValToNull(round(setValNol($arrDataTarget[$i]),2));
	$capaian[$i]= (float)ValToNull(round(setValNol($arrDataStandar[$i]),2));
	$toleransi[$i]= (float)ValToNull(round(setValNol($arrDataToleransi[$i]),2));
}


//echo print_r($keterangan);

$nama_pegawai= "Kebijakan dari tujuan ".$tempNamaParent;
$nama_pegawai=$tempNamaPegawai;

$arr_json= array("graphset" => 
	array(
		array(
			"type"=>"radar",
			"legend"=>
				array(
					"item"=>
						array(
						"color"=>"#3d3d3d","font-size"=>9,"border-color"=>"#b9b9b9","border-width"=>1,"z-index"=>"9999"),
						"x"=>125,"y"=>810,"layout"=>"1x","width"=>155,"minimize"=>true,
						"position"=>"1% 80%",
					"header"=>
						array(
						"text"=>"",
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
			//"scale"=>array("size-factor"=>0.65),
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
					"values"=>"0:4:4",
					 "item"=>
					 array(
					 "font-color"=>"black",
					 "font-weight"=>"bold",
					 "font-size"=>"8px"
					 ),
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
							"line-color"=>"#0F0",
							"background-color"=>"#0F0"
							),
						array(
							"values"=>$target,
							"text"=>"Bad Area",
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
							),
						array(
							"values"=>$toleransi,
							"text"=>"Toleransi",
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
							"line-color"=>"#fffc00",
							"background-color"=>"#fffc00"
							)
					)
			 )
	)
);
echo json_encode($arr_json);
die();

?>
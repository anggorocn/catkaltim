<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/FormulaFaktor.php");

/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfo();
}

$reqGrafikId= httpFilterGet("reqGrafikId");

$arr_json = array();
if($reqGrafikId == "1")
{
	$arr_json= array(
        array("id"=>"11", "text"=>"I. Tingkatkan Kompetensi", "coba"=>"cobaI. Tingkatkan Kompetensi")
        , array("id"=>"12", "text"=>"II. Tingkatkan Peran Saat Ini", "coba"=>"cobaII. Tingkatkan Peran Saat Ini")
        , array("id"=>"21", "text"=>"III. Tingkatkan Peran Saat Ini", "coba"=>"cobaIII. Tingkatkan Peran Saat Ini")
        , array("id"=>"13", "text"=>"IV. Tingkatkan Peran Saat Ini", "coba"=>"cobaIV. Tingkatkan Peran Saat Ini")
        , array("id"=>"22", "text"=>"V. Siap Untuk Peran Masa Depan Dengan Pengembangan", "coba"=>"cobaV. Siap Untuk Peran Masa Depan Dengan Pengembangan")
        , array("id"=>"31", "text"=>"VI. Pertimbangkan (mutasi)", "coba"=>"cobaVI. Pertimbangkan (mutasi)")
        , array("id"=>"23", "text"=>"VII. Siap Untuk Peran Masa Depan Dengan Pengembangan", "coba"=>"cobaVII. Siap Untuk Peran Masa Depan Dengan Pengembangan")
        , array("id"=>"32", "text"=>"VIII. Siap Untuk Peran Masa Depan Dengan Pengembangan", "coba"=>"cobaVIII. Siap Untuk Peran Masa Depan Dengan Pengembangan")
        , array("id"=>"33", "text"=>"IX. Siap Untuk Peran Di Masa Depan", "coba"=>"cobaIX. Siap Untuk Peran Di Masa Depan")
    );
}
elseif($reqGrafikId == "2")
{
	$arr_json= array(
        array("id"=>"11", "text"=>"I. Kinerja dibawah ekspektasi dan potensial rendah", "coba"=>"cobaI. Kinerja dibawah ekspektasi dan potensial rendah")
        , array("id"=>"12", "text"=>"II. Kinerja sesuai ekspektasi dan potensial rendah", "coba"=>"cobaII. Kinerja sesuai ekspektasi dan potensial rendah")
        , array("id"=>"21", "text"=>"III. Kinerja dibawah ekspektasi dan potensial menengah", "coba"=>"cobaIII. Kinerja dibawah ekspektasi dan potensial menengah")
        , array("id"=>"13", "text"=>"IV. Kinerja diatas ekspektasi dan potensial rendah", "coba"=>"cobaIV. Kinerja diatas ekspektasi dan potensial rendah")
        , array("id"=>"22", "text"=>"V. Kinerja sesuai ekspektasi dan potensial menengah", "coba"=>"cobaV. Kinerja sesuai ekspektasi dan potensial menengah")
        , array("id"=>"31", "text"=>"VI. Kinerja dibawah ekspektasi dan potensial tinggi", "coba"=>"cobaVI. Kinerja dibawah ekspektasi dan potensial tinggi")
        , array("id"=>"23", "text"=>"VII. Kinerja diatas ekspektasi dan potensial menengah", "coba"=>"cobaVII. Kinerja diatas ekspektasi dan potensial menengah")
        , array("id"=>"32", "text"=>"VIII. Kinerja sesuai ekspektasi dan potensial tinggi", "coba"=>"cobaVIII. Kinerja sesuai ekspektasi dan potensial tinggi")
        , array("id"=>"33", "text"=>"IX. Kinerja diatas ekspektasi dan potensial tinggi", "coba"=>"cobaIX. Kinerja diatas ekspektasi dan potensial tinggi")
    );
}
elseif($reqGrafikId == "3")
{
	$arr_json= array(
        array("id"=>"11", "text"=>"I. Kinerja dibawah ekspektasi dan JPM rendah", "coba"=>"cobaI. Kinerja dibawah ekspektasi dan JPM rendah")
        , array("id"=>"12", "text"=>"II. Kinerja sesuai ekspektasi dan JPM rendah", "coba"=>"cobaII. Kinerja sesuai ekspektasi dan JPM rendah")
        , array("id"=>"21", "text"=>"III. Kinerja dibawah ekspektasi dan JPM menengah", "coba"=>"cobaIII. Kinerja dibawah ekspektasi dan JPM menengah")
        , array("id"=>"13", "text"=>"IV. Kinerja diatas ekspektasi dan JPM rendah", "coba"=>"cobaIV. Kinerja diatas ekspektasi dan JPM rendah")
        , array("id"=>"22", "text"=>"V. Kinerja sesuai ekspektasi dan JPM menengah", "coba"=>"cobaV. Kinerja sesuai ekspektasi dan JPM menengah")
        , array("id"=>"31", "text"=>"VI. Kinerja dibawah ekspektasi dan JPM tinggi", "coba"=>"cobaVI. Kinerja dibawah ekspektasi dan JPM tinggi")
        , array("id"=>"23", "text"=>"VII. Kinerja diatas ekspektasi dan JPM menengah", "coba"=>"cobaVII. Kinerja diatas ekspektasi dan JPM menengah")
        , array("id"=>"32", "text"=>"VIII. Kinerja sesuai ekspektasi dan JPM tinggi", "coba"=>"cobaVIII. Kinerja sesuai ekspektasi dan JPM tinggi")
        , array("id"=>"33", "text"=>"IX. Kinerja diatas ekspektasi dan JPM tinggi", "coba"=>"cobaIX. Kinerja diatas ekspektasi dan JPM tinggi")
    );
}

echo json_encode($arr_json);
?>
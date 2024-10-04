<?
/* INCLUDE FILE */
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-skp/Pertanyaan.php");
include_once("../WEB/classes/base-skp/Jawaban.php");
include_once("../WEB/classes/utils/UserLogin.php");

$pertanyaan = new Pertanyaan();

$reqId = httpFilterPost("reqId");
$reqMode = httpFilterPost("reqMode");
$reqKategori = httpFilterPost("reqKategori");
$reqPertanyaan = httpFilterPost("reqPertanyaan");
$reqUrut = httpFilterPost("reqUrut");
$reqBobot = httpFilterPost("reqBobot");

$reqArrayIndex= $_POST["reqArrayIndex"];
$reqJawaban= $_POST["reqJawaban"];
$reqKeterangan= $_POST["reqKeterangan"];
$reqRange1= $_POST["reqRange1"];
$reqRange2= $_POST["reqRange2"];
$reqRange3= $_POST["reqRange3"];
$set_loop= $reqArrayIndex;

if($reqMode == "insert")
{
	$pertanyaan->setField("KATEGORI_ID", $reqKategori);
	$pertanyaan->setField("PERTANYAAN", $reqPertanyaan);
	$pertanyaan->setField("URUT", $reqUrut);
	$pertanyaan->setField("BOBOT", $reqBobot);
	if($pertanyaan->insert())
	{
		$reqInfo='simpan';
		$reqId=$pertanyaan->id;
		echo "Data berhasil disimpan.";
	}
}
else
{
	$pertanyaan->setField("PERTANYAAN_ID", $reqId);
	$pertanyaan->setField("KATEGORI_ID", $reqKategori);
	$pertanyaan->setField("PERTANYAAN", $reqPertanyaan);
	$pertanyaan->setField("URUT", $reqUrut);
	$pertanyaan->setField("BOBOT", $reqBobot);
	
	if($pertanyaan->update())
	{
		$reqInfo='simpan';
		echo "Data berhasil disimpan.";
	}
}

if($reqInfo == "simpan")
{
	$child= new Jawaban();
	$child->setField("PERTANYAAN_ID", $reqId);
	$child->deleteParent();
	unset($child);
	
	$no_urut=0;
	for($i=0;$i<=$set_loop;$i++)
	{
		if($reqJawaban[$i] == "")
		{
		}
		else
		{
			$no_urut++;
			$index = $i;
			$jawaban= new Jawaban();
			$jawaban->setField("RANGE_1", $reqRange1[$index]);
			$jawaban->setField("RANGE_2", $reqRange2[$index]);
			$jawaban->setField("RANGE_3", $reqRange3[$index]);
			$jawaban->setField("KETERANGAN", $reqKeterangan[$index]);
			$jawaban->setField("JAWABAN", $reqJawaban[$index]);
			$jawaban->setField("PERTANYAAN_ID", $reqId);
			$jawaban->setField("URUT", $no_urut);
			$jawaban->insert();
			unset($jawaban);
		}
	}
}
?>
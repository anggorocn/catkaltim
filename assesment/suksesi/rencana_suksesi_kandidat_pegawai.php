<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/classes/base-silat/Kelautan.php");

$reqId = httpFilterGet("reqId");
$reqPegawaiId = httpFilterGet("reqPegawaiId");
$reqMode = httpFilterGet("reqMode");
$reqPensiunId= httpFilterGet("reqPensiunId");
$reqKeterangan = httpFilterGet("reqKeterangan");
$reqCari = httpFilterGet("reqCari");

//echo 'tes'.$reqCari;
/* LOGIN CHECK */
if ($userLogin->checkUserLogin()) 
{ 
	$userLogin->retrieveUserInfoKhusus($reqId);
}

	ini_set("memory_limit","500M");
	ini_set('max_execution_time', 520);	
	
/*if($reqId == "")
	$reqId= $userLogin->userSatkerId;*/

$arrPegawaiKandidat="";
$index_pegawai_kandidat= 0;
$pegawai_kandidat= new Kelautan();
$statement= " AND K.pegawai_id_pensiun = ".$reqPensiunId." AND (K.STATUS IS NULL OR K.STATUS = 0)";
$pegawai_kandidat->selectByParamsMonitoringPilihKandidatPegawai(array(), -1,-1, $statement);
//echo $pegawai_kandidat->query;exit;
while($pegawai_kandidat->nextRow())
{
	$arrPegawaiKandidat[$index_pegawai_kandidat]["PEGAWAI_ID_PENSIUN"] = $pegawai_kandidat->getField("PEGAWAI_ID_PENSIUN");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["PEGAWAI_ID_PENGGANTI"] = $pegawai_kandidat->getField("PEGAWAI_ID_PENGGANTI");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["NIP_LAMA"] = $pegawai_kandidat->getField("NIP_LAMA");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["NIP_BARU"] = $pegawai_kandidat->getField("NIP_BARU");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["NAMA"] = $pegawai_kandidat->getField("NAMA");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["NAMA_GOL"] = $pegawai_kandidat->getField("NAMA_GOL");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["TMT_GOL_AKHIR"] = $pegawai_kandidat->getField("TMT_GOL_AKHIR");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["NAMA_ESELON"] = $pegawai_kandidat->getField("NAMA_ESELON");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["NAMA_JAB_STRUKTURAL"] = $pegawai_kandidat->getField("NAMA_JAB_STRUKTURAL");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["SATKER"] = $pegawai_kandidat->getField("SATKER");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["JPM"] = $pegawai_kandidat->getField("JPM");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["IKK"] = $pegawai_kandidat->getField("IKK");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["TOTAL_HARGA"] = $pegawai_kandidat->getField("TOTAL_HARGA");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["MENU_KEPALA_ID"] = $pegawai_kandidat->getField("MENU_KEPALA_ID");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["MENU_KEPALA_NAMA"] = $pegawai_kandidat->getField("MENU_KEPALA_NAMA");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["MENU_KAKI_ID"] = $pegawai_kandidat->getField("MENU_KAKI_ID");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["MENU_KAKI_NAMA"] = $pegawai_kandidat->getField("MENU_KAKI_NAMA");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["MENU_KULIT_ID"] = $pegawai_kandidat->getField("MENU_KULIT_ID");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["MENU_KULIT_NAMA"] = $pegawai_kandidat->getField("MENU_KULIT_NAMA");
	$arrPegawaiKandidat[$index_pegawai_kandidat]["PILIHAN_MENU_INFO_ID"] = $pegawai_kandidat->getField("PILIHAN_MENU_INFO_ID");
	
	$index_pegawai_kandidat++;
}
unset($pegawai_kandidat);
//print_r($arrPegawaiKandidat);exit;
?>
<table class="gradient-style" id="tableMasakanUtama" style="overflow:auto !important; width:100%">
<thead>
<tr>
<th scope="col" style="width:100px">NIP</th>
<th scope="col" style="width:100px">NIP Baru</th>
<th scope="col" style="width:180px">Nama Pegawai</th>
<th scope="col" style="text-align:center; width:100px;">Gol Ruang</th>
<th scope="col" style="text-align:center; width:100px;">TMT Pangkat</th>
<th scope="col" style="text-align:center; width:100px;">Eselon</th>
<th scope="col" style="text-align:center; width:100px;">Jabatan</th>
<th scope="col" style="text-align:center; width:100px;">Satuan Kerja</th>
<th scope="col" style="text-align:center; width:80px;">JKM</th>
<th scope="col" style="text-align:center; width:80px">IKK</th>
<th scope="col" style="text-align:center; width:50px">Aksi</th>
</tr>
</thead>
<tbody>
<?
for($checkbox_index=0; $checkbox_index < $index_pegawai_kandidat; $checkbox_index++)
{
    $tempRowPegawaiId= $arrPegawaiKandidat[$checkbox_index]["PEGAWAI_ID_PENSIUN"];
    $tempRowPegawaiKandidatId= $arrPegawaiKandidat[$checkbox_index]["PEGAWAI_ID_PENGGANTI"];
    $tempPegawaiNip= $arrPegawaiKandidat[$checkbox_index]["NIP_LAMA"];
    $tempPegawaiNipBaru= $arrPegawaiKandidat[$checkbox_index]["NIP_BARU"];
    $tempPegawaiNama= $arrPegawaiKandidat[$checkbox_index]["NAMA"];
    $tempPegawaiGolRuang= $arrPegawaiKandidat[$checkbox_index]["NAMA_GOL"];
    $tempPegawaiTmtPangkat= $arrPegawaiKandidat[$checkbox_index]["TMT_GOL_AKHIR"];
    $tempPegawaiEselon= $arrPegawaiKandidat[$checkbox_index]["NAMA_ESELON"];
    $tempPegawaiJabatan= $arrPegawaiKandidat[$checkbox_index]["NAMA_JAB_STRUKTURAL"];
    $tempPegawaiSatuanKerja= $arrPegawaiKandidat[$checkbox_index]["SATKER"];
    $tempPegawaiJpm= $arrPegawaiKandidat[$checkbox_index]["JPM"];
    $tempPegawaiIkk= $arrPegawaiKandidat[$checkbox_index]["IKK"];
?>
<tr>
    <td><label><?=$tempPegawaiNip?></label></td>
    <td><label><?=$tempPegawaiNipBaru?></label></td>
    <td><label><?=$tempPegawaiNama?></label></td>
    <td><label><?=$tempPegawaiGolRuang?></label></td>
    <td><label><?=$tempPegawaiTmtPangkat?></label></td>
    <td><label><?=$tempPegawaiEselon?></label></td>
    <td><label><?=$tempPegawaiJabatan?></label></td>
    <td><label><?=$tempPegawaiSatuanKerja?></label></td>
    <td><label><?=$tempPegawaiJpm?></label></td>
    <td><label><?=$tempPegawaiIkk?></label></td>
    <td align="center">
        <label>
        <input type="hidden" name="reqRowPegawaiKandidatId[]" id="reqRowPegawaiKandidatId<?=$checkbox_index?>" value="<?=$tempRowPegawaiKandidatId?>" />
        <?
        //if($checkbox_index == 0){}
        //else
        //{
            // hanya group administator
            //if($tempIdLevel == 1)
            //{
        ?>
        <a style="cursor:pointer" onclick="deleteRowDrawTablePhp('tableMasakanUtama', this, '<?=$checkbox_index?>', 'reqRowPegawaiKandidatId', 'pegawai_kandidat')"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a>
        <?
            //}
        //}
        ?>
        </label>
    </td>
</tr>
<?
}
?>
</tbody>
</table>
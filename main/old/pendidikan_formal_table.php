<?
include_once("../WEB/classes/base-diklat/Peserta.php");
$arrpendidikanriwayat=array();
$index_data= 0;
$statement= " AND A.PENDIDIKAN_ID not IN (0)";
// $statement= " AND A.PENDIDIKAN_ID IN (1,2,3,6,7,8,9,11)";
$reqId= httpFilterGet("reqId");
$set= new Peserta();
$set->selectByParamsPendidikanRiwayat(array(), -1,-1, $reqId, $statement);
// echo $set->query; exit;
while($set->nextRow())
{
 $arrpendidikanriwayat[$index_data]["PENDIDIKAN_ID"] = $set->getField("PENDIDIKAN_ID");
 $arrpendidikanriwayat[$index_data]["NAMA"] = $set->getField("NAMA");
 $arrpendidikanriwayat[$index_data]["RIWAYAT_PENDIDIKAN_ID"] = $set->getField("RIWAYAT_PENDIDIKAN_ID");
 $arrpendidikanriwayat[$index_data]["NAMA_SEKOLAH"] = $set->getField("NAMA_SEKOLAH");
 $arrpendidikanriwayat[$index_data]["JURUSAN"] = $set->getField("JURUSAN");
 $arrpendidikanriwayat[$index_data]["TEMPAT"] = $set->getField("TEMPAT");
 $arrpendidikanriwayat[$index_data]["TAHUN_AWAL"] = $set->getField("TAHUN_AWAL");
 $arrpendidikanriwayat[$index_data]["TAHUN_AKHIR"] = $set->getField("TAHUN_AKHIR");
 $index_data++;
}
$jumlahpendidikanriwayat= $index_data;
?>

<tr>
   
    <td>
        <select style='width:100%' name='reqPendidikanRiwayatPendidikanId[]' id='reqPendidikanRiwayatPendidikanId'>
            <?
            for($index_data=0; $index_data < $jumlahpendidikanriwayat; $index_data++){
                $reqPendidikanRiwayatPendidikanId= $arrpendidikanriwayat[$index_data]['PENDIDIKAN_ID'];
                $reqPendidikanRiwayatPendidikanNama= $arrpendidikanriwayat[$index_data]['NAMA'];
            ?>
            <option value='<?=$reqPendidikanRiwayatPendidikanId?>'><?=$reqPendidikanRiwayatPendidikanNama?></option>
            <?}?>
        </select>
    </td>
    <td>
        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatNamaSekolah[]' id='reqPendidikanRiwayatNamaSekolah' value='' data-options='' style='width:100%' >
    </td>
     <td>
        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatJurusan[]' id='reqPendidikanRiwayatJurusan' value='' data-options='' style='width:100%' >
    </td>
     <td>
        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTempat[]' id='reqPendidikanRiwayatTempat' value='' data-options='' style='width:100%' >
    </td>
    <td>
        <input $disabled type='text' name='reqPendidikanRiwayatTahunAwal[]' id='reqPendidikanRiwayatTahunAwal' value='' data-options='' style='width:45%' >
        s/d
        <input $disabled type='text' name='reqPendidikanRiwayatTahunAkhir[]' id='reqPendidikanRiwayatTahunAkhir' value='' data-options='' style='width:45%' >
    </td>
    <td>
        <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatKeterangan[]" id="reqNonPendidikanRiwayatKeterangan" value="<?= $reqNonPendidikanRiwayatKeterangan ?>" style="width:100%;">
    </td>
    <td>
        <button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button>
    </td>
</tr>


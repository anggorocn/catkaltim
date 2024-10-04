<?
include_once("../WEB/classes/base-diklat/Peserta.php");
$arrpendidikanriwayat=array();
$index_data= 0;
// $statement= " AND A.PENDIDIKAN_ID not IN (0)";
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
        <select style='width:100%' name='reqKeluargaSaudaraStatus[]' id='reqKeluargaSaudaraStatus'>
            <option value='anak'>Anak</option>
            <option value='pasangan'>Pasangan</option>
        </select>
    </td>
    <td>
        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaSaudara[]' id='reqKeluargaSaudara' value='' data-options='' style='width:100%' >
    </td>
   
    <td>
        <select style='width:100%' name='reqKeluargaSaudaraJenisKelamin[]' id='reqKeluargaSaudaraJenisKelamin'>
            <option value='L'>L</option>
            <option value='P'>P</option>
        </select>
    </td>
    <td>
        <input $disabled  type='text' name='reqKeluargaTempat[]' id='reqKeluargaTempat' style='width:45%' value='' data-options=''>
        <input style='width:45%' type='date' name='reqKeluargaTgllahir[]' id='reqKeluargaTgllahir' value='' data-options=''> 
    </td>
    <td>
        <select style='width:100%' name='reqKeluargaPendidikan[]' id='reqKeluargaPendidikan'>
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
      <input class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaPekerjaan[]' id='reqKeluargaPekerjaan' value='' data-options='' style='width:100%'  ></td>
    </td>
    <td>
        <button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button>
    </td>
</tr>


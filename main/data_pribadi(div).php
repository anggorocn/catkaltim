<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/satuankerjainternal.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/base-diklat/Peserta.php");
include_once("../WEB/classes/base-diklat/MPangkat.php");
include_once("../WEB/classes/base-diklat/MPendidikan.php");
include_once("../WEB/classes/base-diklat/MEselon.php");
include_once("../WEB/classes/base-diklat/Propinsi.php");


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$check = new Peserta();

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;
$tempUserStatusJenis= $userLogin->userStatusJenis;

$reqId = $tempUserPelamarId;
$reqPegawaiId = $tempUserPelamarId;

$check->selectByParamsDataPribadi(array("A.PEGAWAI_ID" => $reqPegawaiId));
// echo $check->query;exit;
$check->firstRow();
$reqId  = $check->getField("PELAMAR_ID");

    $reqMode = "ubah";
    $pangkat= new Peserta();
    $pangkat->selectByParamsPangkat();

    $statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
    $set= new Peserta();
    $set->selectByParamsDataPribadi(array(), -1,-1, $statement);
    $set->firstRow();
    $reqPegawaiNama= $set->getField("NAMA");
    $reqPegawaiTempat= $set->getField("TEMPAT_LAHIR");
    $reqPegawaiTanggalLahir= $set->getField("TGL_LAHIR");
    // echo $reqPegawaiTanggalLahir;exit;
    $reqPegawaiJenisKelamin= $set->getField("JENIS_KELAMIN");
    $reqPegawaiEmail= $set->getField("EMAIL");
    $reqPegawaiStatus= $set->getField("STATUS_PEGAWAI_NAMA");
    $reqPegawaiAgama= $set->getField("AGAMA");
    $reqPegawaiAlamat= $set->getField("ALAMAT");
    $reqPegawaiHp= $set->getField("HP");
    $reqPegawaiStatusPernikahan= $set->getField("STATUS_NIKAH");
    $reqPegawaiNip= $set->getField("NIP_BARU");
    $reqPegawaiJabatanSaatIni= $set->getField("LAST_JABATAN");
    $reqPegawaiPangkatId= $set->getField("LAST_PANGKAT_ID");
    $reqPegawaiAtasanLangsungNama= $set->getField("LAST_ATASAN_LANGSUNG_NAMA");
    $reqPegawaiAtasanLangsungJabatan= $set->getField("LAST_ATASAN_LANGSUNG_JABATAN");

    $reqKeluargaAyah= $set->getField("NAMA_AYAH");
    $reqKeluargaIbu= $set->getField("NAMA_IBU");
    $reqKeluargaAnakUrutan= $set->getField("URUTAN_ANAK");
    $reqKeluargaTotalSaudara= $set->getField("TOTAL_SAUDARA");

    $reqMinatSukai= $set->getField("MINAT_SUKAI");
    $reqMinatTidakSukai= $set->getField("MINAT_TIDAK_SUKAI");
    $reqKekuatan= $set->getField("KEKUATAN");
    $reqKelemahan= $set->getField("KELEMAHAN");
    unset($set);

$arrsaudaralaki=array();
$index_data= 0;
$statement= " AND A.JENIS_KELAMIN = 'L' AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new Peserta();
$set->selectByParamsSaudara(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
 $arrsaudaralaki[$index_data]["SAUDARA_ID"] = $set->getField("SAUDARA_ID");
 $arrsaudaralaki[$index_data]["NAMA"] = $set->getField("NAMA");
 $arrsaudaralaki[$index_data]["POSISI"] = $set->getField("POSISI");
 $index_data++;
}
// echo $index_data;exit;

for($index_data= $index_data; $index_data  < 5; $index_data++)
{
 $arrsaudaralaki[$index_data]["SAUDARA_ID"] = "";
 $arrsaudaralaki[$index_data]["NAMA"] = "";
 $arrsaudaralaki[$index_data]["POSISI"] = "";
}

$arrsaudaraperempuan=array();
$index_data= 0;
$statement= " AND A.JENIS_KELAMIN = 'P' AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new Peserta();
$set->selectByParamsSaudara(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
 $arrsaudaraperempuan[$index_data]["SAUDARA_ID"] = $set->getField("SAUDARA_ID");
 $arrsaudaraperempuan[$index_data]["NAMA"] = $set->getField("NAMA");
 $arrsaudaraperempuan[$index_data]["POSISI"] = $set->getField("POSISI");
 $index_data++;
}

for($index_data= $index_data; $index_data  < 5; $index_data++)
{
 $arrsaudaraperempuan[$index_data]["SAUDARA_ID"] = "";
 $arrsaudaraperempuan[$index_data]["NAMA"] = "";
 $arrsaudaraperempuan[$index_data]["POSISI"] = "";
}
unset($set);
$arrpendidikanriwayat=array();
$index_data= 0;
$statement= " AND A.PENDIDIKAN_ID IN (1,2,3,6,7,8,9,11)";
$set= new Peserta();
$set->selectByParamsPendidikanRiwayat(array(), -1,-1, $reqPegawaiId, $statement);
// echo $set->query;exit;
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
// print_r($jumlahpendidikanriwayat);exit;

$arrnonformalpendidikanriwayat=array();
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new Peserta();
$set->selectByParamsPendidikanRiwayatNonFormal(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
 $arrnonformalpendidikanriwayat[$index_data]["RIWAYAT_PENDIDIKAN_ID"] = $set->getField("RIWAYAT_PENDIDIKAN_ID");
 $arrnonformalpendidikanriwayat[$index_data]["JENIS_PELATIHAN"] = $set->getField("JENIS_PELATIHAN");
 $arrnonformalpendidikanriwayat[$index_data]["TEMPAT"] = $set->getField("TEMPAT");
 $arrnonformalpendidikanriwayat[$index_data]["TAHUN"] = $set->getField("TAHUN");
 $arrnonformalpendidikanriwayat[$index_data]["KETERANGAN"] = $set->getField("KETERANGAN");
 $index_data++;
}

for($index_data= $index_data; $index_data  < 5; $index_data++)
{
 $arrnonformalpendidikanriwayat[$index_data]["RIWAYAT_PENDIDIKAN_ID"] = "";
 $arrnonformalpendidikanriwayat[$index_data]["JENIS_PELATIHAN"] = "";
 $arrnonformalpendidikanriwayat[$index_data]["TEMPAT"] = "";
 $arrnonformalpendidikanriwayat[$index_data]["TAHUN"] = "";
 $arrnonformalpendidikanriwayat[$index_data]["KETERANGAN"] = "";
}
unset($set);

$arrjabatanriwayat=array();
$index_data= 0;
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$set= new Peserta();
$set->selectByParamsJabatanRiwayat(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
 $arrjabatanriwayat[$index_data]["RIWAYAT_JABATAN_ID"] = $set->getField("RIWAYAT_JABATAN_ID");
 $arrjabatanriwayat[$index_data]["JABATAN"] = $set->getField("JABATAN");
 $arrjabatanriwayat[$index_data]["INSTANSI"] = $set->getField("INSTANSI");
 $arrjabatanriwayat[$index_data]["TAHUN_AWAL"] = $set->getField("TAHUN_AWAL");
 $arrjabatanriwayat[$index_data]["TAHUN_AKHIR"] = $set->getField("TAHUN_AKHIR");
 $index_data++;
}

for($index_data= $index_data; $index_data  < 5; $index_data++)
{
 $arrjabatanriwayat[$index_data]["RIWAYAT_JABATAN_ID"] = "";
 $arrjabatanriwayat[$index_data]["JABATAN"] = "";
 $arrjabatanriwayat[$index_data]["INSTANSI"] = "";
 $arrjabatanriwayat[$index_data]["TAHUN_AWAL"] = "";
 $arrjabatanriwayat[$index_data]["TAHUN_AKHIR"] = "";
}
unset($set);



?>

    
<style>
#tableinfo {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 300%;
    border: 1px solid black;
    border-radius: 8px;
}

#tableinfo td, #customers th {
    padding: 8px;
}

#tableinfo th {
    border: 1px solid black;
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
    background-color: #334e9f;
    color: white;
}
</style>
<!--// plugin-specific resources //-->
<script src='../WEB/lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='../WEB/lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='../WEB/lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="../WEB/css/gaya-multifile.css" type="text/css">

<div class="col-md-12">

    <div class="judul-halaman">  Form Daftar Riwayat Hidup
        <a class="pull-right " href="javascript:void(0)" style="color: white;font-weight: bold;" onclick="goBack()"><i class="fa fa-arrow-circle-left fa lg"> </i><span> Back</span> </a>

    </div>

    <div class="konten-area">
        <div class="konten-inner">
            <div>

                <!--<div class='panel-body'>-->
                <!--<form class='form-horizontal' role='form'>-->
                <form id="ff" class="easyui-form form-horizontal" method="post" novalidate enctype="multipart/form-data">

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i> A.  IDENTITAS DIRI
                            <!-- <button type="button" id="btn_editing" class="btn btn-default pull-right " style="margin-right: 10px" onclick="editing_form()"><i id="opens" class="fa fa-folder-o fa-lg"></i><b id="htmlopen">Open</b></button> -->

                        </h3>
                        <br>
                    </div>
                    <div class="form-group">
                        <label for="reqPegawaiNama" class="control-label col-md-2">Nama Lengkap </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPegawaiNama" id="reqPegawaiNama" value="<?= $reqPegawaiNama ?>" style=" width:160%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="reqPegawaiTempat" class="control-label col-md-2">Tempat & Tgl Lahir</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table>
                                        <tr>
                                            <td ><input type="text" class="easyui-validatebox textbox form-control" name="reqPegawaiTempat" id="reqPegawaiTempat" value="<?= $reqPegawaiTempat ?>"  style=" width:445px"  /></td>
                                            <td> <input type="text" style=" width:100%" id="reqPegawaiTanggalLahir"  class="easyui-datebox textbox form-control" name="reqPegawaiTanggalLahir" value="<?=$reqPegawaiTanggalLahir ?>" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPegawaiJenisKelamin" class="control-label col-md-2">Jenis Kelamin </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqPegawaiJenisKelamin" id="reqPegawaiJenisKelamin">
                                        <option value="L" <? if($reqPegawaiJenisKelamin == "L") echo "selected"?>>Laki-Laki</option>
                                        <option value="P" <? if($reqPegawaiJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPegawaiEmail" class="control-label col-md-2">NIP</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPegawaiNip" id="reqPegawaiNip" value="<?= $reqPegawaiNip ?>" style=" width:100%; background-color: #f0f0f0" readonly  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqPegawaiStatus" class="control-label col-md-2">Status</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPegawaiStatus" id="reqPegawaiStatus" value="PNS" style=" width:100%; background-color: #f0f0f0" readonly  />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="reqPegawaiStatus" class="control-label col-md-2">Status </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqPegawaiStatus" id="reqPegawaiStatus">
                                        <option value="" <? if($reqPegawaiStatus == "") echo "selected"?>></option>
                                        <option value="CPNS" <? if($reqPegawaiStatus == "CPNS") echo "selected"?>>CPNS</option>
                                        <option value="PNS" <? if($reqPegawaiStatus == "PNS") echo "selected"?>>PNS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="reqPegawaiAgama" class="control-label col-md-2">Agama </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                 <select name="reqPegawaiAgama" id="reqPegawaiAgama">
                                    <option value="" <? if($reqPegawaiAgama == "") echo "selected"?>></option>
                                    <option value="ISLAM" <? if($reqPegawaiAgama == "ISLAM") echo "selected"?>>ISLAM</option>
                                    <option value="KRISTEN PROTESTAN" <? if($reqPegawaiAgama == "KRISTEN PROTESTAN") echo "selected"?>>KRISTEN PROTESTAN</option>
                                    <option value="KATHOLIK" <? if($reqPegawaiAgama == "KATHOLIK") echo "selected"?>>KATHOLIK</option>
                                    <option value="HINDU" <? if($reqPegawaiAgama == "HINDU") echo "selected"?>>HINDU</option>
                                    <option value="BUDHA" <? if($reqPegawaiAgama == "BUDHA") echo "selected"?>>BUDHA</option>
                                    <option value="KEPERCAYAAN KTYME" <? if($reqPegawaiAgama == "KEPERCAYAAN KTYME") echo "selected"?>>KEPERCAYAAN KTYME</option>
                                </select>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="reqPegawaiAlamat" class="control-label col-md-2">Alamat </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea class="form-control" name="reqPegawaiAlamat" id="reqPegawaiAlamat" style="width:100%;"><?= $reqPegawaiAlamat; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <label for="reqPegawaiHp" class="control-label col-md-2">Telepon/HP</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" id="reqPegawaiHp" class="easyui-validatebox textbox form-control" name="reqPegawaiHp" onkeypress='validate(event)' value="<?= $reqPegawaiHp ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div>
                        <label for="reqPegawaiStatusPernikahan" class="control-label col-md-2">Status Pernikahan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqPegawaiStatusPernikahan" id="reqPegawaiStatusPernikahan">
                                        <option value="Belum Kawin" <? if($reqPegawaiStatusPernikahan == "Belum Kawin") echo 'selected'?>>Belum Kawin</option>
                                        <option value="Kawin" <? if($reqPegawaiStatusPernikahan == "Kawin") echo 'selected'?>>Kawin</option>
                                        <option value="Janda" <? if($reqPegawaiStatusPernikahan == "Janda") echo 'selected'?>>Janda</option>
                                        <option value="Duda" <? if($reqPegawaiStatusPernikahan == "Duda") echo 'selected'?>>Duda</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- <label for="reqPegawaiNip" class="control-label col-md-2">Nip</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" onkeypress='validate(event)' name="reqPegawaiNip" id="reqPegawaiNip" value="<?= $reqPegawaiNip ?>" style=" width:100%" />
                                </div>
                            </div>
                        </div> -->
                        <label for="reqPegawaiJabatanSaatIni" class="control-label col-md-2">Jabatan Saat Ini</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPegawaiJabatanSaatIni" id="reqPegawaiJabatanSaatIni" value="<?= $reqPegawaiJabatanSaatIni ?>" style=" width:160%" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reqNomor" class="control-label col-md-2">Golongan</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <select name="reqPegawaiPangkatId" id="reqPegawaiPangkatId">
                                        <option value="" <? if($reqPegawaiPangkatId == "") echo 'selected'?>></option>
                                        <?
                                        while($pangkat->nextRow())
                                        {
                                           $infoid= $pangkat->getField("PANGKAT_ID");
                                           $infonama= $pangkat->getField("NAMA")." (".$pangkat->getField("KODE").")";
                                           ?>
                                           <option value="<?=$infoid?>" <? if($reqPegawaiPangkatId == $infoid) echo 'selected'?>><?=$infonama?></option>
                                           <?
                                       }
                                       ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label for="reqPegawaiAtasanLangsungJabatan" class="control-label col-md-2">Jabatan Atasan Langsung</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <input type="text" class="easyui-validatebox textbox form-control" name="reqPegawaiAtasanLangsungJabatan" id="reqPegawaiAtasanLangsungJabatan" value="<?= $reqPegawaiAtasanLangsungJabatan ?>" style=" width:160%" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>B. LINGKUNGAN KELUARGA
                        </h3>
                        <br>
                    </div>
                 

                    <div class="form-group">
                        <label for="reqNomor" class="control-label col-md-2">Susunan Keluarga ( Pasangan dan Anak-anak )</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table class="formstyle">
                                        <tr>
                                            <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_saudara()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table id="tableinfo">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%">Nama</th>
                                                <th style="width: 30%">Jenis Kelamin</th>
                                                <th style="width: 20%">Anak Ke</th>
                                                <th style="width: 5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyObyek">
                                            <?php

                                            
                                            $reqViewSaudara = new Peserta();
                                            $reqViewSaudara->selectByParamsSaudara(array("PEGAWAI_ID" => (int)$reqId));
 
                                            $x=0;
                                            $reqNameSpareparttanda= array();
                                            while ($reqViewSaudara->nextRow()) {
                                                $reqKeluargaSaudara = $reqViewSaudara->getField("NAMA");
                                                $reqKeluargaSaudaraJenisKelamin = $reqViewSaudara->getField("JENIS_KELAMIN");
                                                $reqKeluargaSaudaraUrutan = $reqViewSaudara->getField("POSISI");
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqKeluargaSaudara[]" id="reqKeluargaSaudara" value="<?= $reqKeluargaSaudara ?>" style="width:100%;">
                                                    </td>
                                                    <td>
                                                        <select name="reqKeluargaSaudaraJenisKelamin[]" id="reqKeluargaSaudaraJenisKelamin" style="width: 100%">
                                                            <option value="L" <? if($reqKeluargaSaudaraJenisKelamin == "L") echo "selected"?>>Laki-Laki</option>
                                                            <option value="P" <? if($reqKeluargaSaudaraJenisKelamin == "P") echo "selected"?>>Perempuan</option>
                                                        </select>
                                                    </td>
                                                     <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqKeluargaSaudaraUrutan[]" id="reqKeluargaSaudaraUrutan" value="<?= $reqKeluargaSaudaraUrutan ?>" style="width:100%;">
                                                    </td>
                                                    <td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button></td>
                                                    <?$x++;?>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>C. RIWAYAT PENDIDIKAN  
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="reqNomor" class="control-label col-md-2">Pendidikan Formal</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table class="formstyle">
                                        <tr>
                                            <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_pendidikan_formal()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <div class="col-md-11" style="overflow: scroll; width: 300%">
                                        <table id="tableinfo" style="width:200%">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">Jenjang</th>
                                                <th style="width: 25%">Nama Istitusi</th>
                                                <th style="width: 20%">Jurusan</th>
                                                <th style="width: 15%">Kota</th>
                                                <th style="width: 10%">Tahun</th>
                                                <th style="width: 1%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyObyekPendidikanFormula">
                                            <?php

                                            
                                            $reqViewPendidikanFormal = new Peserta();
                                            $reqViewPendidikanFormal->selectByParamsPendidikanRiwayatFormal(array("PEGAWAI_ID" => (int)$reqId));
 
                                            $x=0;
                                            $reqNameSpareparttanda= array();
                                            while ($reqViewPendidikanFormal->nextRow()) {
                                                $reqPendidikanRiwayatPendidikanId = $reqViewPendidikanFormal->getField("PENDIDIKAN_ID");
                                                $reqPendidikanRiwayatNamaSekolah = $reqViewPendidikanFormal->getField("NAMA_SEKOLAH");
                                                $reqPendidikanRiwayatJurusan = $reqViewPendidikanFormal->getField("JURUSAN");
                                                $reqPendidikanRiwayatTempat = $reqViewPendidikanFormal->getField("TEMPAT");
                                                $reqPendidikanRiwayatTahunAwal = $reqViewPendidikanFormal->getField("TAHUN_AWAL");
                                                $reqPendidikanRiwayatTahunAkhir = $reqViewPendidikanFormal->getField("TAHUN_AKHIR");
                                                // echo $reqPendidikanRiwayatJurusan; exit;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <select style='width:100%' name='reqPendidikanRiwayatPendidikanId[]' id='reqPendidikanRiwayatPendidikanId'>
                                                            <?
                                                            for($index_data=0; $index_data < $jumlahpendidikanriwayat; $index_data++){
                                                                $reqPendidikanRiwayatPendidikanIdview= $arrpendidikanriwayat[$index_data]['PENDIDIKAN_ID'];
                                                                $reqPendidikanRiwayatPendidikanNamaview= $arrpendidikanriwayat[$index_data]['NAMA'];
                                                            ?>
                                                            <option value='<?=$reqPendidikanRiwayatPendidikanIdview?>'
                                                                <?if ($reqPendidikanRiwayatPendidikanId==$reqPendidikanRiwayatPendidikanIdview){?>
                                                                    selected
                                                                    <?}?>><?=$reqPendidikanRiwayatPendidikanNamaview?></option>
                                                            <?}?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatNamaSekolah[]' id='reqPendidikanRiwayatNamaSekolah' value='<?=$reqPendidikanRiwayatNamaSekolah?>' data-options='' style='width:100%' >
                                                    </td>
                                                     <td>
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatJurusan[]' id='reqPendidikanRiwayatJurusan' value='<?=$reqPendidikanRiwayatJurusan?>' data-options='' style='width:100%' >
                                                    </td>
                                                     <td>
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTempat[]' id='reqPendidikanRiwayatTempat' value='<?=$reqPendidikanRiwayatTempat?>' data-options='' style='width:100%' >
                                                    </td>
                                                    <td>
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTahunAwal[]' id='reqPendidikanRiwayatTahunAwal' value='<?=$reqPendidikanRiwayatTahunAwal?>' data-options='' style='width:43%' >
                                                        s/d
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqPendidikanRiwayatTahunAkhir[]' id='reqPendidikanRiwayatTahunAkhir' value='<?=$reqPendidikanRiwayatTahunAkhir?>' data-options='' style='width:43%' >
                                                    </td>
                                                    <td>
                                                        <button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button>
                                                    </td>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reqNomor" class="control-label col-md-2">Pendidikan Non Formal</label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table class="formstyle">
                                        <tr>
                                            <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_pendidikan()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table id="tableinfo">
                                        <thead>
                                            <tr>
                                                <th style="width: 30%">Jenis Pelatihan</th>
                                                <th style="width: 30%">Tempat</th>
                                                <th style="width: 10%">Tahun</th>
                                                <th style="width: 15%">Keterangan</th>
                                                <th style="width: 5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyObyekPendidikanNonFormula">
                                            <?php

                                            
                                            $reqViewPendidikanNonFormal = new Peserta();
                                            $reqViewPendidikanNonFormal->selectByParamsPendidikanRiwayatNonFormal(array("PEGAWAI_ID" => (int)$reqId));
 
                                            $x=0;
                                            $reqNameSpareparttanda= array();
                                            while ($reqViewPendidikanNonFormal->nextRow()) {
                                                $reqNonPendidikanRiwayatJurusan = $reqViewPendidikanNonFormal->getField("JENIS_PELATIHAN");
                                                $reqNonPendidikanRiwayatTempat = $reqViewPendidikanNonFormal->getField("TEMPAT");
                                                $reqNonPendidikanRiwayatTahun = $reqViewPendidikanNonFormal->getField("TAHUN");
                                                $reqNonPendidikanRiwayatKeterangan = $reqViewPendidikanNonFormal->getField("KETERANGAN");
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatJurusan[]" id="reqNonPendidikanRiwayatJurusan" value="<?= $reqNonPendidikanRiwayatJurusan ?>" style="width:100%;">
                                                    </td>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatTempat[]" id="reqNonPendidikanRiwayatTempat" value="<?= $reqNonPendidikanRiwayatTempat ?>" style="width:100%;">
                                                    </td>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatTahun[]" id="reqNonPendidikanRiwayatTahun" value="<?= $reqNonPendidikanRiwayatTahun ?>" style="width:100%;">
                                                    </td>
                                                     <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqNonPendidikanRiwayatKeterangan[]" id="reqNonPendidikanRiwayatKeterangan" value="<?= $reqNonPendidikanRiwayatKeterangan ?>" style="width:100%;">
                                                    </td>
                                                    <td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button></td>
                                                    <?$x++;?>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    


                   <!--  <div class="form-group">
                        <label for="reqNomor" class="control-label col-md-2">Pendidikan Non Formal </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table>  
                                        <tr>
                                            <td colspan="2">
                                                <table class="tableinfo" style="width: 1035px;">
                                                    <tr >
                                                        <th style="width: 20%;border: 2px solid black;background-color:  #269ede ;color:#ffffff;text-align: center" >Jenis Pelatihan</th>
                                                        <th style="width: 35%;border: 2px solid black;background-color:  #269ede ;color:#ffffff;text-align: center">Tempat</th>
                                                        <th style="width: 8%;border: 2px solid black;background-color:  #269ede ;color:#ffffff;text-align: center">Tahun</th>
                                                        <th style="width: 27%;border: 2px solid black;background-color:  #269ede ;color:#ffffff;text-align: center">Keterangan</th>
                                                      
                                                    </tr>
                                                    <?
                                                    for($index_data=0; $index_data < 5; $index_data++)
                                                    {
                                                        $reqNonPendidikanRiwayatId= $arrnonformalpendidikanriwayat[$index_data]["RIWAYAT_PENDIDIKAN_ID"];
                                                        $reqNonPendidikanRiwayatJurusan= $arrnonformalpendidikanriwayat[$index_data]["JENIS_PELATIHAN"];
                                                        $reqNonPendidikanRiwayatTempat= $arrnonformalpendidikanriwayat[$index_data]["TEMPAT"];
                                                        $reqNonPendidikanRiwayatTahun= $arrnonformalpendidikanriwayat[$index_data]["TAHUN"];
                                                        $reqNonPendidikanRiwayatKeterangan= $arrnonformalpendidikanriwayat[$index_data]["KETERANGAN"];
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: left;">
                                                                <input type="hidden" name="reqNonPendidikanRiwayatId[]" id="reqNonPendidikanRiwayatId<?=$index_data?>" value="<?=$reqNonPendidikanRiwayatId?>" />
                                                                <input style="width:100%" type="text" class="easyui-validatebox textbox form-control" name="reqNonPendidikanRiwayatJurusan[]" id="reqNonPendidikanRiwayatJurusan<?=$index_data?>" value="<?=$reqNonPendidikanRiwayatJurusan?>" />
                                                        
                                                            </td>
                                                            <td style="text-align: left;">
                                                                <input style="width:100%" type="text" class="easyui-validatebox textbox form-control" name="reqNonPendidikanRiwayatTempat[]" id="reqNonPendidikanRiwayatTempat<?=$index_data?>" value="<?=$reqNonPendidikanRiwayatTempat?>" />
                                                            </td>
                                                            <td style="text-align: left;">
                                                                <input style="width:100%" type="text" class="easyui-validatebox textbox form-control" onkeypress='validate(event)' name="reqNonPendidikanRiwayatTahun[]" id="reqNonPendidikanRiwayatTahun<?=$index_data?>" value="<?=$reqNonPendidikanRiwayatTahun?>" />
                                                            </td>
                                                            <td style="text-align: left;">
                                                                <input style="width:100%" type="text" class="easyui-validatebox textbox form-control" name="reqNonPendidikanRiwayatKeterangan[]" id="reqNonPendidikanRiwayatKeterangan<?=$index_data?>" value="<?=$reqNonPendidikanRiwayatKeterangan?>" />
                                                            </td>
                                                        </tr>
                                                        <?
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>                                      
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
 -->
                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>D. RIWAYAT PEKERJAAN  
                        </h3>
                        <br>
                    </div>

                   <!--  -->

                    <div class="form-group">
                        <label for="reqNomor" class="control-label col-md-2">Uraikan dengan singkat jabatan anda saat ini </label>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <table class="formstyle">
                                        <tr>
                                            <td><button class="btn btn-outline-secondary border--Green" type="button" id="license-category-search" {{ $disabled }} onclick="add_jabatan()" style="background-color: blue; color: white"><i class="fa fa-plus orange"> Add</i></button></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table id="tableinfo">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%">Jabatan</th>
                                                <th style="width: 30%">Tahun</th>
                                                <th style="width: 30%">Instansi</th>
                                                <th style="width: 5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyObyekJabatan">
                                            <?php

                                            
                                            $reqViewJabatan = new Peserta();
                                            $reqViewJabatan->selectByParamsJabatanRiwayat(array("PEGAWAI_ID" => (int)$reqId));
 
                                            $x=0;
                                            while ($reqViewJabatan->nextRow()) {
                                                $reqJabatanRiwayatNama = $reqViewJabatan->getField("JABATAN");
                                                $reqJabatanRiwayatTahunAwal = $reqViewJabatan->getField("TAHUN_AWAL");
                                                $reqJabatanRiwayatTahunAkhir = $reqViewJabatan->getField("TAHUN_AKHIR");
                                                $reqJabatanRiwayatInstansi = $reqViewJabatan->getField("INSTANSI");
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqJabatanRiwayatNama[]" id="reqJabatanRiwayatNama" value="<?= $reqJabatanRiwayatNama ?>" style="width:100%;">
                                                    </td>
                                                    <td>
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAwal[]' id='reqJabatanRiwayatTahunAwal' value='<?=$reqJabatanRiwayatTahunAwal?>' data-options='' style='width:45%' >
                                                        s/d
                                                        <input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAkhir[]' id='reqJabatanRiwayatTahunAkhir' value='<?=$reqJabatanRiwayatTahunAkhir?>' data-options='' style='width:45%' >
                                                    </td>
                                                    <td>
                                                        <input class="easyui-validatebox textbox form-control" type="text" name="reqJabatanRiwayatInstansi[]" id="reqJabatanRiwayatInstansi" value="<?= $reqJabatanRiwayatInstansi ?>" style="width:100%;">
                                                    </td>
                                                    <td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white'><i class='fa fa-trash fa-lg'></i></button></td>
                                                    <?$x++;?>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="reqName" style="width:100%; ">Jelaskan uraian tugas yang harus dilaksanakan pada jabatan terakhir anda</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    for($index_data=0; $index_data < 5; $index_data++)
                    {
                        $reqJabatanRiwayatInfoTugasId= $arrjabatanriwayatinfotugas[$index_data]["RIWAYAT_JABATAN_INFO_ID"];
                        $reqJabatanRiwayatInfoTugasStatus= $arrjabatanriwayatinfotugas[$index_data]["STATUS"];
                        $reqJabatanRiwayatInfoTugasKeterangan= $arrjabatanriwayatinfotugas[$index_data]["KETERANGAN"];
                        ?>
                        <div class="form-group">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <div class="col-md-11">
                                       <input type="hidden" name="reqJabatanRiwayatInfoTugasId[]" id="reqJabatanRiwayatInfoTugasId<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTugasId?>" />
                                       <input type="hidden" name="reqJabatanRiwayatInfoTugasStatus[]" id="reqJabatanRiwayatInfoTugasStatus<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTugasStatus?>" />
                                       <textarea name="reqJabatanRiwayatInfoTugasKeterangan[]" id="reqJabatanRiwayatInfoTugasKeterangan<?=$index_data?>" style="width: 100%; height: 50px"><?=$reqJabatanRiwayatInfoTugasKeterangan?></textarea>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <?
                   }
                   ?>


                     <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="reqName" style="width:100%; ">Jelaskan tanggungjawab yang harus dilaksanakan pada jabatan terakhir anda</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    for($index_data=0; $index_data < 5; $index_data++)
                    {
                         $reqJabatanRiwayatInfoTanggungJawabId= $arrjabatanriwayatinfotanggungjawab[$index_data]["RIWAYAT_JABATAN_INFO_ID"];
                         $reqJabatanRiwayatInfoTanggungJawabStatus= $arrjabatanriwayatinfotanggungjawab[$index_data]["STATUS"];
                         $reqJabatanRiwayatInfoTanggungJawabKeterangan= $arrjabatanriwayatinfotanggungjawab[$index_data]["KETERANGAN"];
                        ?>
                        <div class="form-group">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <div class="col-md-11">
                                        <input type="hidden" name="reqJabatanRiwayatInfoTanggungJawabId[]" id="reqJabatanRiwayatInfoTanggungJawabId<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTanggungJawabId?>" />
                                        <input type="hidden" name="reqJabatanRiwayatInfoTanggungJawabStatus[]" id="reqJabatanRiwayatInfoTanggungJawabStatus<?=$index_data?>" value="<?=$reqJabatanRiwayatInfoTanggungJawabStatus?>" />
                                        <textarea name="reqJabatanRiwayatInfoTanggungJawabKeterangan[]" id="reqJabatanRiwayatInfoTanggungJawabKeterangan<?=$index_data?>" style="width: 100%; height: 50px"><?=$reqJabatanRiwayatInfoTanggungJawabKeterangan?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                    }
                    ?>

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    </div>

                    <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>E. MINAT DAN HARAPAN   
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="reqName" style="width:100%; ">Apakah yang anda sukai dari pekerjaan anda saat ini? (kondisi, tugas-tugas, dsb), jelaskan mengapa?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqMinatSukai" style="width: 100%; height: 50px"><?=$reqMinatSukai?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="reqName" style="width:100%; ">Apakah yang paling anda tidak sukai dari pekerjaan anda saat ini? (kondisi, tugas-tugas, dsb), jelaskan mengapa?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqMinatTidakSukai" style="width: 100%; height: 50px"><?=$reqMinatTidakSukai?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align:center;padding:5px">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                    </div>

                     <div class="page-header">
                        <h3><i class="fa fa-file-text fa-lg"></i>F. KEKUATAN DAN KELEMAHAN    
                        </h3>
                        <br>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="reqName" style="width:100%; ">Apakah yang menjadi kekuatan anda?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqKekuatan" style="width: 100%; height: 50px"><?=$reqKekuatan?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <label for="reqName" style="width:100%; ">Apakah yang menjadi kelemahan anda?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="col-md-11">
                                    <textarea name="reqKelemahan" style="width: 100%; height: 50px"><?=$reqKelemahan?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    
                    

                    <input type="hidden" name="reqId" value="<?= $reqId ?>" />
                    <input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>" />
                    <input type="hidden" name="reqTipe" value="Complaint Customer" />

                    <input type="hidden" name="reqMode" value="<?= $reqMode ?>" />

                </form>
            </div>
            <div style="text-align:center;padding:5px">
                <a href="javascript:void(0)" class="btn btn-primary" onclick="submitForm()">Submit</a>
                <a href="javascript:void(0)" class="btn btn-warning" onclick="clearForm()">Clear</a>
                <a href="app" class="btn btn-success" >Back</a>
            </div>

        </div>
    </div>

    <script>
        function submitForm() {
  var win = $.messager.progress({
            title: 'Aplikasi Penilaian  | Kemenkes',
            msg: 'proses data...'
        });
            $('#ff').form('submit', {
                url: 'web/daftar_riwayat_hidup_json/add',
                onSubmit: function() {
                    return $(this).form('enableValidation').form('validate');
                },
                success: function(data) {
                    var datas = data.split('-');
                    // console.log(data);return false;
                    if(datas[0]=='xxx'){
                             show_toast('error', 'Have troube in ',  datas[1]);
                            $.messager.alert('Info', datas[1], 'info'); 
                    }else{
                    $.messager.alertLink('Info', datas[1], 'info', "app/index/daftar_riwayat_hidup?reqPegawaiId=<?=$reqPegawaiId?>");
                    }
                     $.messager.progress('close');
                }
            });
        }

        function clearForm() {
            $('#ff').form('clear');
        }

        function add_saudara(array) {
            // console.log("masuk");
            test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaSaudara[]' id='reqKeluargaSaudara' value='' data-options='' style='width:100%' ></td><td><select style='width:100%' name='reqKeluargaSaudaraJenisKelamin[]' id='reqKeluargaSaudaraJenisKelamin'><option value='L'>Laki-Laki</option><option value='P'>Perempuan</option></select></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqKeluargaSaudaraUrutan[]' id='reqKeluargaSaudaraUrutan' value='' data-options='' style='width:100%'  ></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyek").append(test);
        } 

        

        function add_pendidikan_formal(array) {
            // console.log("masuk");
            $.get("app/loadUrl/app/pendidika_formal_table?reqId=<?=$reqId?>", function (data) {
                $("#dialytable").empty();
                $("#tbodyObyekPendidikanFormula").append(data);
            });
        }

        function add_pendidikan(array) {
            // console.log("masuk");
            test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatJurusan[]' id='reqNonPendidikanRiwayatJurusan' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatTempat[]' id='reqNonPendidikanRiwayatTempat' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatTahun[]' id='reqNonPendidikanRiwayatTahun' value='' data-options='' style='width:100%' ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqNonPendidikanRiwayatKeterangan[]' id='reqNonPendidikanRiwayatKeterangan' value='' data-options='' style='width:100%'  ></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyekPendidikanNonFormula").append(test);
        }

        function add_jabatan(array) {
            // console.log("masuk");
            test = "<tr><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatNama[]' id='reqJabatanRiwayatNama' value='' data-options='' style='width:100%' ></td><td><input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAwal[]' id='reqJabatanRiwayatTahunAwal' value='' data-options='' style='width:46%' >s/d<input $disabled class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatTahunAkhir[]' id='reqJabatanRiwayatTahunAkhir' value='' data-options='' style='width:46%' ></td><td><input class='easyui-validatebox textbox form-control' type='text' name='reqJabatanRiwayatInstansi[]' id='reqJabatanRiwayatInstansi' value='' data-options='' style='width:100%'  ></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyekJabatan").append(test);
        } 

        function add_tugas_jabatan(array) {
            // console.log("masuk");
            test = "<tr><td> <textarea name='reqJabatanRiwayatInfoTugasKeterangan[]' id='reqJabatanRiwayatInfoTugasKeterangan<?=$index_data?>' style='width: 100%; height: 50px'><?=$reqJabatanRiwayatInfoTugasKeterangan?></textarea></td><td><button class='btn btn-outline-secondary border--Green' type='button' id='license-category-search' onclick='Hapussparepart(this)' style='background-color: red; color: white;'><i class='fa fa-trash fa-lg' ></i></button></td></tr>";
            $("#tbodyObyekTugasJabatan").append(test);
        }


        function Hapussparepart(ctl) {
            $(ctl).parents("tr").remove();
        }
    </script>

    <script type="text/javascript">
        function tambahPenyebab() {
            $.get("app/loadUrl/app/tempalate_row_attacment?", function(data) {
                $("#tambahAttacment").append(data);
            });
        }
    </script>

</div>

</div>

<?
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PelamarLowongan.php");
include_once("../WEB/classes/base/Lowongan.php");
include_once("../WEB/classes/base-diklat/DiklatPeserta.php");
include_once("../WEB/classes/base-diklat/DiklatDokumen.php");
include_once("../WEB/classes/base-diklat/DiklatPerlengkapan.php");

$tempUserPelamarId= $userLogin->userPelamarId;
$tempUserPelamarNip= $userLogin->userNoRegister;

$reqId= httpFilterGet("reqId");

$xmlfile = "../WEB/web.xml";
$data = simplexml_load_file($xmlfile);
// print_r($data);exit();/
$urlupload= $data->urlConfig->main->urlupload;
$urlupload.="diklatperlengkapancontoh/".$reqId."/";

$lowongan= new Lowongan();
$pelamar_lowongan = new PelamarLowongan();

$lowongan->selectByParams(array("A.JADWAL_TES_ID" => $reqId),-1,-1, "", "");
// echo $lowongan->query();exit;
$lowongan->firstRow();
$tempDetilNama= $lowongan->getField("NAMA_DIKLAT");
$tempDetilTanggal= getFormattedDateTime($lowongan->getField("TANGGAL_AWAL"), false);

$tempDetilId= $lowongan->getField("LOWONGAN_ID");
$tempDetilRowId= $lowongan->getField("LOWONGAN_ID_ENKRIP");

$tempDetilLokasi= $lowongan->getField("TEMPAT");
$tempDetilPenyelenggara= $lowongan->getField("ALAMAT");
$tempDetilKota= $lowongan->getField("KOTA");

$tempNamaFormasi= $lowongan->getField("NAMA_FORMASI");
$tempDetilKode= $lowongan->getField("KODE");
$tempTanggalAkhir= getFormattedDate($lowongan->getField("TANGGAL_AKHIR"));
$tempManual= $lowongan->getField("MANUAL");
$tempKeterangan= $lowongan->getField("KETERANGAN_DIKLAT");
if($tempKeterangan == "")
{
    $tempKeterangan= "Tidak ada keterangan untuk assesment ini.";
}

$tempDetilPersyaratan= $lowongan->getField("PERSYARATAN");
$tempDetilPersyaratanArr = explode("($$)", $tempDetilPersyaratan);
$count_arr_persyaratan = count($tempDetilPersyaratanArr);

$tempDetilDokumen= $lowongan->getField("DOKUMEN");
$tempDetilDokumenArr = explode("($$)", $tempDetilDokumen);
$count_arr_dokumen = count($tempDetilDokumenArr);

$tempDetilPenempatan= $lowongan->getField("PENEMPATAN");
$tempDetilPenempatanArr = explode("($$)", $tempDetilPenempatan);
$tempPenempatan= implode(",", $tempDetilPenempatanArr);

$set= new DiklatPeserta();
$statement= " AND A.JADWAL_AWAL_TES_SIMULASI_ID = ".$reqId;
if($tempUserPelamarId == "")
    $statement.= " AND A.PEGAWAI_ID = -1";
else
    $statement.= " AND A.PEGAWAI_ID = ".$tempUserPelamarId;
$checkmengikutidiklat= $set->getCountByParamsPesertaDiklat(array(), $statement);
// echo $set->query;exit();

$set= new Lowongan();
$statement= " AND A.JADWAL_AWAL_TES_SIMULASI_ID = ".$reqId;
$set->selectByParamsBatas(array(), -1,-1, $statement);
$set->firstRow();
$bataspeserta= $set->getField("BATAS_PEGAWAI");

if($checkmengikutidiklat > 0){}
else
    $checkmengikutidiklat= $checkmengikutidiklat - $bataspeserta;
// echo $checkmengikutidiklat;exit();

if($checkmengikutidiklat >= 0)
    $checkmengikutidiklat= 1;

$arrDiklatDokumen="";
$index_data= 0;
$statement= " AND A.DIKLAT_ID = ".$reqId;
$diklat_dokumen= new DiklatDokumen();
$diklat_dokumen->selectByParams(array(),-1,-1, $statement);
// echo $diklat_dokumen->query;exit;
while($diklat_dokumen->nextRow())
{
    $arrDiklatDokumen[$index_data]["DIKLAT_DOKUMEN_ID"] = $diklat_dokumen->getField("DIKLAT_DOKUMEN_ID");
    $arrDiklatDokumen[$index_data]["FORMAT"] = $diklat_dokumen->getField("FORMAT");
    $arrDiklatDokumen[$index_data]["NAMA"] = $diklat_dokumen->getField("NAMA");
    $index_data++;
}
$jumlah_diklat_dokumen= $index_data;

$arrDiklatPerlengkapan="";
$index_data= 0;
$statement= " AND A.DIKLAT_ID = ".$reqId;
$diklat_perlengkapan= new DiklatPerlengkapan();
$diklat_perlengkapan->selectByParams(array(),-1,-1, $statement);
// echo $diklat_perlengkapan->query;exit;

while($diklat_perlengkapan->nextRow())
{
    $arrDiklatPerlengkapan[$index_data]["DIKLAT_PERLENGKAPAN_ID"] = $diklat_perlengkapan->getField("DIKLAT_PERLENGKAPAN_ID");
    $arrDiklatPerlengkapan[$index_data]["FORMAT"] = $diklat_perlengkapan->getField("FORMAT");
    $arrDiklatPerlengkapan[$index_data]["NAMA"] = $diklat_perlengkapan->getField("NAMA");
    $index_data++;
}
$jumlah_diklat_perlengkapan= $index_data;
// echo $jumlah_diklat_perlengkapan;exit;

$statement= " AND A.DIKLAT_ID = ".$reqId;
$fasilitator= new lowongan();
$fasilitator->selectByParamsFasilitator(array(),-1,-1, $statement);

$statement= " AND A.JADWAL_AWAL_TES_SIMULASI_ID = ".$reqId;
$peserta_diklat= new DiklatPeserta();
$peserta_diklat->selectByParams(array(), -1,-1, $statement);
?>
<link rel="stylesheet" type="text/css" href="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.css">

<script type="text/javascript" src="../WEB/lib/easyui/jquery.min.js"></script>

<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script type="text/javascript" language="javascript" src="../WEB/lib/DateRangePicker/daterangepicker.js"></script>

<link rel="stylesheet" type="text/css" href="../WEB/lib/easyui/themes/default/easyui.css">
<script type="text/javascript" src="../WEB/lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="../WEB/lib/easyui/globalfunction.js"></script>
<script type="text/javascript">
    function setpilih()
    {
        $.messager.defaults.ok = 'Ya';
        $.messager.defaults.cancel = 'Tidak';

        var reqUndangId= "";
        reqUndangId= $("#reqUndangId").val();
        if(reqUndangId == "")
        {
            $.messager.alert('Info', "Pilih data terlebih dahulu", 'info');
        }
        else
        {
            $.messager.confirm('Konfirmasi', "Apakah Anda Yakin, pilih data terpilih ?",function(r){
                if (r){
                    urlAjax= "../json/home_detil.php/?reqId=<?=$reqId?>";
                    $.ajax({'url': urlAjax,'success': function(data){
                        // console.log(data);return false;
                        $.messager.alert('Info', data, 'info');
                        document.location.href= "?pg=home_detil&reqId=<?=$reqId?>";
                    }});
                }
            });
        }
    }
</script>
<style type="text/css">
    .ui-scroll-tabs-view
    {
        margin-bottom: 0px !important;
    }
</style>
<div class="col-lg-8">
	
    <div class="informasi-ticker-area">
    	<div class="title">Detil Assesment</div>
    </div>
    
    <div class="area-detil-diklat">
    	<div id="example_0">
            <ul role="tablist">
                <li role="tab"><a href="#tabs-0-1" role="presentation">Keterangan</a></li>
                <li role="tab"><a href="#tabs-0-3" role="presentation">Peserta yang sudah mendaftar</a></li>
                <li role="tab"><a href="#tabs-0-4" role="presentation">Waktu dan Tempat</a></li>
            </ul>
            <div id="tabs-0-1" role="tabpanel">
            	<div class="area-detil-diklat-inner">

                    <div class="nama"><?=$tempDetilNama?></div>
                    <div class="tgl-diklat">( <?=$tempDetilTanggal?> )</div>
                    <div class="judul">Keterangan</div>
                    <div class="keterangan merah"><?=$tempKeterangan?></div>
                
                </div>

            </div>
            <div id="tabs-0-3" role="tabpanel">
            	<table style="width: 100%" class="data-list">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <!-- <th>Unit Kerja</th>
                            <th>Download Biodata</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $idata=0;
                        $nomor= "1";
                        while($peserta_diklat->nextRow())
                        {
                        ?>
                            <tr>
                                <td><?=$nomor?></td>
                                <td><?=$peserta_diklat->getField("PEGAWAI_NAMA")?></td>
                                <!-- <td><?=$peserta_diklat->getField("UNIT_KERJA_NAMA")?></td>
                                <td style="text-align:center">
                                    <?
                                    if($tempUserPelamarId == $peserta_diklat->getField("PESERTA_ID"))
                                    {
                                    ?>
                                        <a href="after_login_word.php?reqId=<?=$peserta_diklat->getField("DIKLAT_PESERTA_ID")?>" target="_blank" title="cetak"><img src="../WEB/images/icon-download.png" width="20" height="20" /></a>
                                    <?
                                    }
                                    else
                                        echo "-";
                                    ?>
                                </td> -->
                            </tr>
                        <?
                            $idata++;
                            $nomor++;
                        }
                        ?>
                    </tbody>
                </table>
                <?
                if($idata == 0)
                {
                ?>
                <div class="keterangan merah">Tidak ada Peserta yang sudah mendaftar untuk saat ini</div>
                <?
                }
                ?>
            </div>
            <div id="tabs-0-4" role="tabpanel">
                <div class="area-detil-diklat-inner">
                	<div class="judul">Tanggal di laksanakan</div>
                    <div class="keterangan merah"><?=$tempDetilTanggal?></div>
                    <div class="judul">Tempat</div>
                    <div class="keterangan merah"><?=$tempDetilLokasi?></div>
                    <div class="judul">Alamat</div>
                    <div class="keterangan merah"><?=$tempDetilPenyelenggara?></div>
                    <!-- <div class="judul">Kota</div>
                    <div class="keterangan merah"><?=$tempDetilKota?></div> -->
                </div>
            </div>

          </div>
    </div>
	
    <!------------------------------------------------------------------------------------------------->
    
    <div class="lowongan-template-area" style="display: none;">
        <?
        if($lowongan->getField("TANGGAL_AKHIR") == "")
		{}
		else
		{
		?>
        <div class="row">
            <div class="col-lg-12 text-center">
            	<div class="ditujukan-ke">
                	<div>
                        Lamaran kami terima paling lambat<br />
                        <span class="batas-waktu"><?=strtoupper($tempTanggalAkhir)?></span><br />
                        
                        <span class="alamat-pengiriman text-center">
                        	<?
							if($userLogin->userPelamarId == "")
							{
								if($lowongan->getField("TANGGAL_AKHIR") < date('Y-m-d'))
								{ }
								else
								{
								?>
                                    <!-- <a href="?pg=register">Klik disini</a><br />
									untuk registrasi online di <strong>Aplikasi e-Recruitment</strong> -->
								<?
									}
							}
                            ?>
                        </span>
                        <?
                        if($tempManual == "1")
						{
						?>
                        <!-- <span class="alamat-pengiriman2 text-center">
                            atau<br />
                            Kirimkan lamaran Anda ke :<br />
                            <strong>
                            PT. Angkasa Pura Suport, Gedung Center for Excellence; Jl. Tabing No.16, Jakarta 10610
                            </strong>
                        </span> -->
                        <?
						}
                        ?>
                    </div>
                </div>
                
            </div>
        </div>
        <?
		}
		?>
        
    </div>
    
    <!------------------------------------------------------------------------------------------------->
    
    <div class="lowongan-area">
        <div class="list">
                
                <?
                $statement= " AND JADWAL_AWAL_TES_SIMULASI_ID = ".$reqId;
				$total_pelamar = $pelamar_lowongan->getCountByParams(array(), $statement);
                ?>
                <div id="info-pelamar">
                    <div>Batas peserta : <?=$bataspeserta?></div>
                    <div>Jumlah peserta sampai saat ini : <?=$total_pelamar?></div> 
                    <?
                    if($userLogin->userPelamarId == "")
					{
					?>
                    <div>Silahkan registrasi / login untuk daftar.</div>
                    <?
					}
					?>
                </div>
                
                <div class="area-tombol-kanan">
                    <?
                    if($userLogin->userPelamarId == "")
                    {}
                    else
                    {  
						if($lowongan->getField("TANGGAL_AKHIR") < date('Y-m-d'))
						{}
						else
						{
                            if($checkmengikutidiklat > 0)
                            {
                    ?>
                    <?
                            }
                            else
                            {
                    ?>
                            <!-- SETELAH LOGIN -->
                            <div class="kirim-lamaran"><a href="#" onclick="setpilih();" >Daftar</a></div>
                    <?
                            }
						}
                    }
                    ?>
                    <?
                    //}
                    ?>
                </div>
        </div>
    </div>
</div>
<?
unset($lowongan);
?>

<script type="text/javascript">
    function getdiklatdokumen(setFormat, reqTempPesertaId)
    {
        varurl= "<?=$urlupload?>"+reqTempPesertaId+"."+setFormat;
        newWindow = window.open(varurl, 'Cetak');
        newWindow.focus();
    }
</script>
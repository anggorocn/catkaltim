<?
include_once("../WEB/functions/date.func.php");
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/PelamarLowongan.php");
include_once("../WEB/classes/base/Lowongan.php");


$reqId= httpFilterGet("reqId");
//$reqId= 160119666;

$lowongan= new Lowongan();
$pelamar_lowongan = new PelamarLowongan();

$lowongan->selectByParams(array("A.LOWONGAN_ID" => $reqId),-1,-1, "", "");
$lowongan->firstRow();

$tempDetilId= $lowongan->getField("LOWONGAN_ID");
$tempDetilRowId= $lowongan->getField("LOWONGAN_ID_ENKRIP");
$tempNamaFormasi= $lowongan->getField("NAMA_FORMASI");
$tempDetilNama= $lowongan->getField("JABATAN");
$tempDetilKode= $lowongan->getField("KODE");
$tempTanggalAkhir= getFormattedDate($lowongan->getField("TANGGAL_AKHIR"));
$tempDetilPersyaratan= $lowongan->getField("PERSYARATAN");
$tempDetilPersyaratanArr = explode("($$)", $tempDetilPersyaratan);
$count_arr_persyaratan = count($tempDetilPersyaratanArr);

$tempDetilDokumen= $lowongan->getField("DOKUMEN");
$tempDetilDokumenArr = explode("($$)", $tempDetilDokumen);
$count_arr_dokumen = count($tempDetilDokumenArr);

$tempDetilPenempatan= $lowongan->getField("PENEMPATAN");
$tempDetilPenempatanArr = explode("($$)", $tempDetilPenempatan);
$tempPenempatan= implode(",", $tempDetilPenempatanArr);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html moznomarginboxes mozdisallowselectionprint>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>


<link href="../WEB/css/cetak-lowongan.css" rel="stylesheet">

</head>

<body>
<button class="cetak" onClick="javascript:window.print();">Cetak</button>


    <!------------------------------------------------------------------------------------------------->
    
    <div class="lowongan-template-area" style="">
        <div class="row" style="display:inline-block; width:100%; text-align:center !important;">
            <div class="col-md-5" style="display:inline-block; margin-right:20px;">
                <img src="../WEB/images/logo-template-lowongan.png" />
            </div>
            <div class="col-md-7 text-center" style="display:inline-block;">
            	<div class="title-lowongan">
                    <span>LOWONGAN PEKERJAAN</span><br />
                    <span style="text-decoration:underline">PT. APS</span><br />&nbsp;
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12 text-center">
            	<div class="mukadimah">
                	Kami Anak Perusahaan <span>PT Pelabuhan Indonesia III (Persero)</span><br />
                    Bergerak Di Bidang Jasa Tenaga Kerja Berpusat Di Surabaya<br />
                    Membutuhkan Tenaga Kerja yang akan ditempatkan di <span class="penempatan"><?=$tempPenempatan?></span>
                </div>
                <div class="sebagai"><em><strong>Sebagai :</strong></em></div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12 text-center">
            	<div class="area-posisi">
                	<span class="nama"><?=$tempDetilNama?></span><br />
	                <span class="kode">( KODE : <?=$tempDetilKode?> )</span>
                </div>            
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
            	<div class="syarat">
                	<span class="title">PERSYARATAN</span><br />
                	<ol type="disc">
						<?
                        for($i=0; $i<$count_arr_persyaratan; $i++)
                        {
                            if($i == 0 && $tempDetilPersyaratan == '')
                            { }
                            else
                            {
                        ?>
                            <li><?=$tempDetilPersyaratanArr[$i]?></li>
                        <?
                            }
                        }
                        ?>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="row" style="display:none;">
            <div class="col-lg-12">
            	<div class="dokumen">
                	<span class="title">DOKUMEN TERLAMPIR</span><br />
	                <span class="list">
                    	Daftar riwayat hidup (CV) , Fotocopy KTP , Pas Foto 4x6 (1 Lembar) , Fotokopi ijazah dan transkrip/SKHU
                    </span>
					<?
                    for($i=0; $i<$count_arr_dokumen; $i++)
                    {
                        if($i == 0 && $tempDetilDokumen == '')
                        { }
                        else
                        {
                    ?>
    	                    <? if($i+1 == $count_arr_dokumen) { ?>
            		            <span class="list-akhir">
									<?=$tempDetilDokumenArr[$i]?>
                                </span>
                            <? } else { ?>
	    	                    <span class="list">
									<?=$tempDetilDokumenArr[$i]?>
                                </span>
                        	<? } ?>
                    <?
                        }
                    }
                    ?>
                    
                </div>
                
            </div>
        </div>
        
        <div class="row" style="margin-top:18px; margin-bottom:18px;">
            <div class="col-lg-12 text-center">
            	<div class="ditujukan-ke" style="padding:18px 0;">
                	<div>
                    	PENDAFTARAN MELALUI<br />
                        <strong>http://www.ptpds.co.id/pds-rekrutmen</strong><br />
                        ATAU EMAIL BERKAS LAMARAN LENGKAP<br />
                        <strong>rekrutmen@ptpds.co.id</strong>
                    </div>
                </div>
                
            </div>
        </div>
        
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
                        <span class="batas-waktu"><?=strtoupper($tempTanggalAkhir)?></span>
                    </div>
                </div>
                
            </div>
        </div>
        <?
		}
		?>
        
        <div class="row">
            <div class="col-lg-12">
            	<div class="perhatian">
                	<strong>Perhatian :</strong>
                    <ul style="list-style-type:decimal">
                        <li>Selama proses rekrutmen &amp; seleksi, pelamar <strong>TIDAK DIPUNGUT BIAYA</strong> dalam bentuk apapun</li>
                        <li>Hanya pelamar yang memnuhi persyaratan dan <strong>TERBAIK</strong> yang akan dipanggil untuk mengikuti proses rekrutmen &amp; seleksi</li>
                        <li>Keputusan panitia rekrutmen &amp; seleksi adalah <strong>MUTLAK</strong> dan <strong>TIDAK DAPAT DIGANGGU GUGAT</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
    
    <!------------------------------------------------------------------------------------------------->
    
    <div class="lowongan-area" style="display:none;">
        <div class="list">
                
                <?
				$total_pelamar = $pelamar_lowongan->getCountByParams(array("LOWONGAN_ID" => $reqId), " AND TANGGAL_KIRIM IS NOT NULL ");
                ?>
                <div id="info-pelamar">
                    <div>Jumlah pelamar posisi ini sampai saat ini : <?=$total_pelamar?>
                    </div> 
                    <?
                    if($userLogin->userPelamarId == "")
					{
					?>
                    <div>Silahkan registrasi / login untuk mengirim lamaran.</div>                  
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
                    ?>
                        <!-- SETELAH LOGIN -->
                        <div class="kirim-lamaran"><a href="?pg=lamaran&reqId=<?=$reqId?>" >Kirim Lamaran</a></div>
                    <?
                    }
                    ?>
                    <?
                    //}
                    ?>
                </div>
        </div>
    </div>

<?
unset($lowongan);
?>
</body>
</html>
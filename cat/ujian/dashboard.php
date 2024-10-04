<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/classes/base/InfoData.php");
include_once("../WEB/classes/base-cat/JadwalTes.php");

$tempPegawaiId= $userLogin->pegawaiId;
$ujianPegawaiJadwalTesId= $userLogin->ujianPegawaiJadwalTesId;
$ujianPegawaiFormulaAssesmentId= $userLogin->ujianPegawaiFormulaAssesmentId;
$ujianPegawaiFormulaEselonId= $userLogin->ujianPegawaiFormulaEselonId;
$ujianPegawaiUjianId= $userLogin->ujianPegawaiUjianId;
$tempUjianPegawaiTanggalAwal= $userLogin->ujianPegawaiTanggalAwal;
$tempUjianPegawaiTanggalAkhir= $userLogin->ujianPegawaiTanggalAkhir;

$tempUjianId= $ujianPegawaiUjianId;

$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
$statement.= " AND EXISTS (SELECT 1 FROM jadwal_tes_simulasi_pegawai X WHERE 1=1 AND X.JADWAL_TES_ID = ".$ujianPegawaiJadwalTesId." AND X.PEGAWAI_ID = A.PEGAWAI_ID)";
$set= new InfoData();
$set->selectByParamsLookupJadwalPegawai(array(), -1,-1, $statement, $ujianPegawaiJadwalTesId);
// echo $set->query;exit;
$set->firstRow();
$tempTanggalUjian= getFormattedDateTime($tempUjianPegawaiTanggalAwal, FALSE);
$tempNomorUrut= $set->getField("NOMOR_URUT_GENERATE");
$tempNipBaru= $set->getField("PEGAWAI_NIP");
$tempNama= $set->getField("PEGAWAI_NAMA");
$tempKtpNo= $set->getField("NO_KTP");
$tempJenisKelamin= $set->getField("JENIS_KELAMIN");
$tempJenisKelaminNama= $set->getField("JENIS_KELAMIN_NAMA");
$tempTempatLahir= $set->getField("TEMPAT_LAHIR");
$tempTglLahir= getFormattedDate($set->getField("TANGGAL_LAHIR"));
$tempJabatan= $set->getField("PEGAWAI_JAB_STRUKTURAL");
$tempPangkat= $set->getField("PANGKAT");
$tempPendidikan= $set->getField("PENDIDIKAN");
$tempLokasiKerja= $set->getField("LOKASI_KERJA");
$tempEmail= $set->getField("EMAIL");
 
unset($set);

$filesoal= new JadwalTes();
$filesoal->selectByParamsFormulaEselon(array("A.JADWAL_TES_ID"=> $ujianPegawaiJadwalTesId),-1,-1,'');
$filesoal->firstRow();
$tempLinkSoal= $filesoal->getField('LINK_SOAL');

$linkfile= str_replace("../upload", "../../assesment/upload", $tempLinkSoal);

$arrLabel= array("No Urut", "Nama", "NIP", "Jabatan", "Tanggal Assesment");
$arrDataLabel= array($tempNomorUrut, $tempNama, $tempNipBaru, $tempJabatan, $tempTanggalUjian);
?>
<div class="container utama">
	<div class="row">
    	<div class="col-md-12">
			<div class="area-judul-halaman">Data Pribadi</div>
        </div>
    </div>
    
	<div class="row">
    	<div class="col-md-4">
        	<div class="area-foto-user">
            	<?
				if($reqLampiranFoto=="")
				{
                ?>
            	<img src="../WEB/images-ujian/foto-user.png">
                <?
				}
				else
				{
                ?>
            	<img src="../uploads/<?=$reqLampiranFoto?>" height="234" width="234">
                <?
				}
                ?>
            </div>
        </div>
        <div class="col-md-8">
        	<div class="area-data-profil">
            	<table>
                	<?
					for($i=0; $i < count($arrLabel); $i++)
					{
                    ?>
                	<tr>
                    	<td><?=$arrLabel[$i]?></td>
                        <td>:</td>
                        <?if ($mode=='simulasi'){?>
                            <td> Simulasi </td>
                        <?}
                        else{?>
                            <td><?=$arrDataLabel[$i]?></td>
                        <?}?>
                    </tr>
                    
                    <?
					}
                    ?>
                   <tr>
                        <td>Assesment Kompetensi</td>
                        <td>:</td>
                        <td><a href="?pg=upload_ujian">Masuk</a><br></td>
                    </tr>
                </table>
                <div align=center class="lengkapi-data"><a href="?pg=kuisioner" style="background-color: #5d99fb;border-radius: 5px;color: white;">Kuisioner Pelaksanaan Kegiatan</i></a></div>
            </div>
        </div>
	</div>
        

    
    <div class="row">

        <div class="area-prev-next">
            <div class="next"><a href="?pg=form_persetujuan&mode=<?=$mode?>"><span style="font-size: 20pt;">Selanjutnya</span> <i class="fa fa-chevron-right"></i></a></div>
        </div>
     
    
    </div>
</div>